<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

// Debug route to test Google OAuth configuration
Route::get('/google-debug', function () {
    try {
        $config = config('services.google');
        
        $status = [
            'Google OAuth Configuration' => [
                'Client ID' => $config['client_id'] ? 'Set (' . substr($config['client_id'], 0, 10) . '...)' : 'Not set',
                'Client Secret' => $config['client_secret'] ? 'Set (' . substr($config['client_secret'], 0, 10) . '...)' : 'Not set',
                'Redirect URI' => $config['redirect'] ?? 'Not set',
            ],
            'Environment Settings' => [
                'APP_ENV' => config('app.env'),
                'APP_URL' => config('app.url'),
                'CURL_VERIFY_SSL' => env('CURL_VERIFY_SSL', 'true'),
            ],
            'Network Test' => []
        ];
        
        // Test network connectivity to Google
        try {
            $client = new \GuzzleHttp\Client([
                'timeout' => 10,
                'verify' => false
            ]);
            
            $response = $client->get('https://accounts.google.com/.well-known/openid_configuration');
            $status['Network Test']['Google OAuth Discovery'] = 'Success (Status: ' . $response->getStatusCode() . ')';
        } catch (\Exception $e) {
            $status['Network Test']['Google OAuth Discovery'] = 'Failed: ' . $e->getMessage();
        }
        
        // Test Socialite driver initialization
        try {
            $socialite = Socialite::driver('google');
            $status['Socialite Test']['Driver Initialization'] = 'Success';
            
            // Test redirect URL generation (without actually redirecting)
            $authUrl = $socialite->redirect()->getTargetUrl();
            $status['Socialite Test']['Auth URL Generation'] = 'Success';
            $status['Socialite Test']['Auth URL'] = substr($authUrl, 0, 100) . '...';
        } catch (\Exception $e) {
            $status['Socialite Test']['Error'] = $e->getMessage();
        }
        
        return response()->json($status, 200, [], JSON_PRETTY_PRINT);
        
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ], 500, [], JSON_PRETTY_PRINT);
    }
});