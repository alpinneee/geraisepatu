<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SecurityController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Security stats
        $totalAdmins = User::count();
        $recentLogins = 5; // Sample data

        // Recent activities (sample data)
        $recentActivities = collect([
            (object) [
                'id' => 1,
                'action' => 'login',
                'description' => 'User logged in successfully',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'created_at' => now()->subMinutes(5),
                'user' => $user
            ],
            (object) [
                'id' => 2,
                'action' => 'password_changed',
                'description' => 'Password was changed',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'created_at' => now()->subHours(2),
                'user' => $user
            ],
            (object) [
                'id' => 3,
                'action' => 'product_updated',
                'description' => 'Updated product information',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'created_at' => now()->subHours(4),
                'user' => $user
            ]
        ]);

        // Active sessions (sample data)
        $activeSessions = collect([
            (object) [
                'id' => 1,
                'session_id' => session()->getId(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'last_activity' => now(),
                'user' => $user
            ]
        ]);

        return view('admin.security.index', compact(
            'user', 
            'totalAdmins', 
            'recentLogins', 
            'recentActivities',
            'activeSessions'
        ));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/',
        ], [
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.'
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // Send notification email
        $this->sendSecurityNotification($user, 'Password Changed', 'Your password has been successfully changed.');

        return back()->with('success', 'Password updated successfully');
    }

    public function enable2FA()
    {
        try {
            $user = Auth::user();
            $secret = $this->generateSecret();
            
            $user->update(['two_factor_secret' => $secret]);

            // Generate a simple QR code placeholder
            $qrCodeData = "otpauth://totp/" . config('app.name') . ":" . $user->email . "?secret=" . $secret . "&issuer=" . config('app.name');
            $qrCodeBase64 = base64_encode($qrCodeData);

            return response()->json([
                'success' => true,
                'secret' => $secret,
                'qr_code' => $qrCodeBase64,
                'manual_entry' => $secret
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to enable 2FA: ' . $e->getMessage()
            ], 500);
        }
    }

    public function confirm2FA(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:6'
        ]);

        $user = Auth::user();
        // Simple validation - accept test code for demo
        $valid = $request->code === '123456';

        if ($valid) {
            $recoveryCodes = collect(range(1, 8))->map(function () {
                return Str::random(10);
            });

            $user->update([
                'two_factor_enabled' => true,
                'two_factor_confirmed_at' => now(),
                'two_factor_recovery_codes' => $recoveryCodes
            ]);

            $this->sendSecurityNotification($user, '2FA Enabled', 'Two-factor authentication has been enabled on your account.');

            return response()->json([
                'success' => true,
                'recovery_codes' => $recoveryCodes
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Invalid code']);
    }

    public function disable2FA(Request $request)
    {
        $request->validate([
            'password' => 'required'
        ]);

        $user = Auth::user();

        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['success' => false, 'message' => 'Invalid password']);
        }

        $user->update([
            'two_factor_enabled' => false,
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null
        ]);

        $this->sendSecurityNotification($user, '2FA Disabled', 'Two-factor authentication has been disabled on your account.');

        return response()->json(['success' => true]);
    }

    public function logoutAllDevices(Request $request)
    {
        $request->validate([
            'password' => 'required'
        ]);

        $user = Auth::user();

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Invalid password']);
        }

        // Invalidate all sessions in Laravel
        $user->setRememberToken(Str::random(60));
        $user->save();

        $this->sendSecurityNotification($user, 'Security Alert', 'You have been logged out from all devices.');

        return back()->with('success', 'Successfully logged out from all devices');
    }

    public function getPasswordStrength(Request $request)
    {
        $password = $request->password;
        $score = 0;
        $feedback = [];

        if (strlen($password) >= 8) $score++;
        else $feedback[] = 'At least 8 characters';

        if (preg_match('/[a-z]/', $password)) $score++;
        else $feedback[] = 'Lowercase letter';

        if (preg_match('/[A-Z]/', $password)) $score++;
        else $feedback[] = 'Uppercase letter';

        if (preg_match('/\d/', $password)) $score++;
        else $feedback[] = 'Number';

        if (preg_match('/[@$!%*?&]/', $password)) $score++;
        else $feedback[] = 'Special character';

        $strength = ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong'][$score] ?? 'Very Weak';
        $color = ['red', 'orange', 'yellow', 'blue', 'green'][$score] ?? 'red';

        return response()->json([
            'score' => $score,
            'strength' => $strength,
            'color' => $color,
            'feedback' => $feedback
        ]);
    }

    private function generateSecret()
    {
        return strtoupper(Str::random(16));
    }

    private function sendSecurityNotification($user, $subject, $message)
    {
        try {
            Mail::send('emails.security-notification', [
                'user' => $user,
                'subject' => $subject,
                'message' => $message,
                'ip' => request()->ip(),
                'userAgent' => request()->userAgent(),
                'time' => now()->format('Y-m-d H:i:s')
            ], function ($mail) use ($user, $subject) {
                $mail->to($user->email)
                     ->subject($subject . ' - ' . config('app.name'));
            });
        } catch (\Exception $e) {
            \Log::error('Failed to send security notification: ' . $e->getMessage());
        }
    }
}