<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ActivityLog;
use App\Models\UserSession;
use Symfony\Component\HttpFoundation\Response;

class LogActivity
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (auth()->check()) {
            $user = auth()->user();
            
            // Log login activity
            if ($request->routeIs('login') && $response->isSuccessful()) {
                ActivityLog::log('login', 'User logged in successfully');
                
                // Track session
                UserSession::updateOrCreate([
                    'user_id' => $user->id,
                    'session_id' => session()->getId(),
                ], [
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'last_activity' => now(),
                ]);
            }
            
            // Log admin actions
            if ($request->routeIs('admin.*') && $request->isMethod('POST')) {
                $action = $this->getActionFromRoute($request->route()->getName());
                if ($action) {
                    ActivityLog::log($action, $this->getActionDescription($action, $request));
                }
            }
            
            // Update session activity
            UserSession::where('user_id', $user->id)
                ->where('session_id', session()->getId())
                ->update(['last_activity' => now()]);
        }

        return $response;
    }

    private function getActionFromRoute($routeName)
    {
        $actions = [
            'admin.products.store' => 'product_created',
            'admin.products.update' => 'product_updated',
            'admin.products.destroy' => 'product_deleted',
            'admin.categories.store' => 'category_created',
            'admin.categories.update' => 'category_updated',
            'admin.categories.destroy' => 'category_deleted',
            'admin.orders.update' => 'order_updated',
            'admin.users.update' => 'user_updated',
            'admin.security.password' => 'password_changed',
        ];

        return $actions[$routeName] ?? null;
    }

    private function getActionDescription($action, $request)
    {
        $descriptions = [
            'product_created' => 'Created a new product',
            'product_updated' => 'Updated product information',
            'product_deleted' => 'Deleted a product',
            'category_created' => 'Created a new category',
            'category_updated' => 'Updated category information',
            'category_deleted' => 'Deleted a category',
            'order_updated' => 'Updated order status',
            'user_updated' => 'Updated user information',
            'password_changed' => 'Changed account password',
        ];

        return $descriptions[$action] ?? 'Performed admin action';
    }
}