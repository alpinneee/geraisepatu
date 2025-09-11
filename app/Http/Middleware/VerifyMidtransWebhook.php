<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VerifyMidtransWebhook
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Only apply to Midtrans notification endpoint
        if ($request->is('midtrans/notification')) {
            // Verify that request comes from Midtrans
            $allowedIPs = [
                '103.10.128.0/24',
                '103.10.129.0/24', 
                '103.127.16.0/24',
                '103.127.17.0/24'
            ];
            
            $clientIP = $request->ip();
            $isAllowed = false;
            
            foreach ($allowedIPs as $allowedIP) {
                if ($this->ipInRange($clientIP, $allowedIP)) {
                    $isAllowed = true;
                    break;
                }
            }
            
            // For development, allow localhost
            if (!config('midtrans.is_production') && in_array($clientIP, ['127.0.0.1', '::1'])) {
                $isAllowed = true;
            }
            
            if (!$isAllowed) {
                Log::warning('Unauthorized Midtrans webhook attempt from IP: ' . $clientIP);
                return response()->json(['error' => 'Unauthorized'], 403);
            }
        }
        
        return $next($request);
    }
    
    /**
     * Check if IP is in range
     */
    private function ipInRange($ip, $range)
    {
        if (strpos($range, '/') === false) {
            return $ip === $range;
        }
        
        list($subnet, $bits) = explode('/', $range);
        $ip = ip2long($ip);
        $subnet = ip2long($subnet);
        $mask = -1 << (32 - $bits);
        $subnet &= $mask;
        
        return ($ip & $mask) === $subnet;
    }
}