<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    private function configureSocialite()
    {
        $socialite = Socialite::driver('google');
        
        // Configure HTTP client with SSL verification disabled for development
        $guzzleConfig = [
            'timeout' => 30,
            'connect_timeout' => 10,
        ];
        
        if (config('app.env') === 'local' || env('CURL_VERIFY_SSL', true) === false) {
            $guzzleConfig['verify'] = false;
            $guzzleConfig['curl'] = [
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
            ];
        }
        
        $socialite->setHttpClient(new \GuzzleHttp\Client($guzzleConfig));
        
        return $socialite;
    }

    public function redirect()
    {
        try {
            return $this->configureSocialite()->redirect();
        } catch (\Exception $e) {
            Log::error('Google OAuth redirect error:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return redirect()->route('login')->with('error', 'Tidak dapat menghubungkan ke Google. Silakan coba lagi.');
        }
    }

    public function callback()
    {
        try {
            Log::info('Google callback started');
            
            // Check for error parameter from Google
            if (request()->has('error')) {
                $error = request()->get('error');
                $errorDescription = request()->get('error_description', 'Unknown error');
                
                Log::warning('Google OAuth error from callback:', [
                    'error' => $error,
                    'description' => $errorDescription
                ]);
                
                return redirect()->route('login')->with('error', 'Login dengan Google dibatalkan atau gagal.');
            }
            
            // Check for authorization code
            if (!request()->has('code')) {
                Log::warning('Google callback missing authorization code');
                return redirect()->route('login')->with('error', 'Kode otorisasi tidak ditemukan.');
            }
            
            $googleUser = $this->configureSocialite()->user();
            
            Log::info('Google user data received:', [
                'id' => $googleUser->id,
                'name' => $googleUser->name,
                'email' => $googleUser->email
            ]);
            
            $user = User::where('email', $googleUser->email)->first();
            
            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar ?? null,
                    'email_verified_at' => now(),
                ]);
                Log::info('Created new user:', ['user_id' => $user->id]);
            } else {
                $user->update([
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar ?? $user->avatar,
                ]);
                Log::info('Updated existing user:', ['user_id' => $user->id]);
            }
            
            Auth::login($user, true);
            Log::info('User logged in successfully:', ['user_id' => $user->id]);
            
            return redirect('/')->with('success', 'Login berhasil dengan Google!');
            
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            Log::error('Google OAuth network error:', [
                'message' => $e->getMessage(),
                'response' => $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : 'No response',
                'request_uri' => $e->getRequest()->getUri()->__toString()
            ]);
            
            return redirect()->route('login')->with('error', 'Tidak dapat menghubungkan ke server Google. Periksa koneksi internet Anda.');
            
        } catch (\Laravel\Socialite\Two\InvalidStateException $e) {
            Log::error('Google OAuth invalid state:', ['message' => $e->getMessage()]);
            return redirect()->route('login')->with('error', 'Sesi login tidak valid. Silakan coba lagi.');
            
        } catch (\Exception $e) {
            Log::error('Google OAuth general error:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('login')->with('error', 'Terjadi kesalahan saat login dengan Google. Silakan coba lagi.');
        }
    }
}