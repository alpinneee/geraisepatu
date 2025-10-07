@extends('layouts.admin')

@section('title', 'Security & Privacy')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Security & Privacy</h1>
            <p class="text-gray-600">Manage your account security and privacy settings</p>
        </div>
        <div class="flex gap-2">
            <button onclick="logoutAllDevices()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                </svg>
                Logout All Devices
            </button>
        </div>
    </div>

    <!-- Security Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Security Score</p>
                    <p class="text-2xl font-bold text-green-600">{{ $user->two_factor_enabled ? '95%' : '75%' }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">2FA Status</p>
                    <p class="text-2xl font-bold {{ $user->two_factor_enabled ? 'text-green-600' : 'text-red-600' }}">
                        {{ $user->two_factor_enabled ? 'Enabled' : 'Disabled' }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Active Sessions</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $activeSessions->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Recent Logins</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $recentLogins }}</p>
                    <p class="text-xs text-gray-500">Last 7 days</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Change Password -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Change Password</h3>
                <p class="text-sm text-gray-600">Update your password to keep your account secure</p>
            </div>
            <div class="p-6">
                <form id="passwordForm" method="POST" action="{{ route('admin.security.password') }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Current Password</label>
                            <input type="password" name="current_password" required
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('current_password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">New Password</label>
                            <input type="password" name="password" id="newPassword" required
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            
                            <!-- Password Strength Indicator -->
                            <div class="mt-2">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-600">Password Strength:</span>
                                    <span id="strengthText" class="font-medium">-</span>
                                </div>
                                <div class="mt-1 w-full bg-gray-200 rounded-full h-2">
                                    <div id="strengthBar" class="h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                                </div>
                                <div id="strengthFeedback" class="mt-1 text-xs text-gray-500"></div>
                            </div>
                            
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                            <input type="password" name="password_confirmation" required
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Two-Factor Authentication -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Two-Factor Authentication</h3>
                <p class="text-sm text-gray-600">Add an extra layer of security to your account</p>
            </div>
            <div class="p-6">
                @if($user->two_factor_enabled)
                    <div class="text-center">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h4 class="text-lg font-medium text-gray-900 mb-2">2FA is Enabled</h4>
                        <p class="text-sm text-gray-600 mb-4">Your account is protected with two-factor authentication</p>
                        <button onclick="disable2FA()" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition">
                            Disable 2FA
                        </button>
                    </div>
                @else
                    <div class="text-center">
                        <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <h4 class="text-lg font-medium text-gray-900 mb-2">2FA is Disabled</h4>
                        <p class="text-sm text-gray-600 mb-4">Enable two-factor authentication for better security</p>
                        <button onclick="enable2FA()" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition" id="enable2FABtn">
                            Enable 2FA
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Session Management -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Active Sessions</h3>
            <p class="text-sm text-gray-600">Manage your active sessions across different devices</p>
        </div>
        <div class="divide-y divide-gray-200">
            @forelse($activeSessions as $session)
                <div class="p-6 flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">{{ strpos($session->user_agent, 'Chrome') !== false ? 'Chrome Browser' : 'Unknown Device' }}</p>
                            <p class="text-xs text-gray-500">{{ $session->ip_address }} • Last active {{ $session->last_activity->diffForHumans() }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        @if($session->session_id === session()->getId())
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                Current Session
                            </span>
                        @else
                            <button onclick="terminateSession('{{ $session->id }}')" class="text-red-600 hover:text-red-900 text-sm">
                                Terminate
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-gray-500">
                    No active sessions found
                </div>
            @endforelse
        </div>
    </div>

    <!-- Activity Log -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Recent Activity</h3>
            <p class="text-sm text-gray-600">Monitor your account activity and security events</p>
        </div>
        <div class="divide-y divide-gray-200">
            @forelse($recentActivities as $activity)
                <div class="p-6 flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                            @if($activity->action === 'login')
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                </svg>
                            @elseif($activity->action === 'password_changed')
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            @else
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @endif
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $activity->action)) }}</p>
                            <p class="text-xs text-gray-500">{{ $activity->user->name ?? 'System' }} • {{ $activity->ip_address }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">{{ $activity->created_at->diffForHumans() }}</p>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                            Success
                        </span>
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-gray-500">
                    No recent activity found
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- 2FA Setup Modal -->
<div id="twoFactorModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden" style="z-index: 9999;">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg max-w-md w-full p-6 shadow-xl">
            <div class="text-center">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Setup Two-Factor Authentication</h3>
                <div id="qrCodeContainer" class="mb-4">
                    <!-- QR Code or manual entry will be inserted here -->
                </div>
                <p class="text-sm text-gray-600 mb-4">Enter the 6-digit code from your authenticator app</p>
                <input type="text" id="twoFactorCode" placeholder="Enter 6-digit code (demo: 123456)" 
                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mb-4" maxlength="6">
                <div class="flex gap-2">
                    <button onclick="confirm2FA()" class="flex-1 bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition">
                        Verify & Enable
                    </button>
                    <button onclick="closeTwoFactorModal()" class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Password strength checker
document.getElementById('newPassword').addEventListener('input', function() {
    const password = this.value;
    if (password.length === 0) {
        document.getElementById('strengthText').textContent = '-';
        document.getElementById('strengthBar').style.width = '0%';
        document.getElementById('strengthFeedback').textContent = '';
        return;
    }

    fetch('{{ route("admin.security.password-strength") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ password: password })
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('strengthText').textContent = data.strength;
        document.getElementById('strengthText').className = `font-medium text-${data.color}-600`;
        document.getElementById('strengthBar').style.width = `${(data.score / 5) * 100}%`;
        document.getElementById('strengthBar').className = `h-2 rounded-full transition-all duration-300 bg-${data.color}-500`;
        document.getElementById('strengthFeedback').textContent = data.feedback.length > 0 ? 'Missing: ' + data.feedback.join(', ') : 'Strong password!';
    });
});

// 2FA Functions
function enable2FA() {
    console.log('Enable 2FA clicked');
    
    const button = document.getElementById('enable2FABtn');
    const originalText = button.innerHTML;
    
    // Show loading state
    button.innerHTML = 'Loading...';
    button.disabled = true;
    
    fetch('{{ route("admin.security.enable-2fa") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            // Show manual entry instead of QR code for now
            document.getElementById('qrCodeContainer').innerHTML = `
                <div class="bg-gray-100 p-4 rounded-lg mb-4">
                    <p class="text-sm font-medium text-gray-700 mb-2">Manual Entry Key:</p>
                    <code class="text-sm bg-white p-2 rounded border block">${data.secret}</code>
                    <p class="text-xs text-gray-500 mt-2">Enter this key in your authenticator app (Google Authenticator, Authy, etc.)</p>
                </div>
            `;
            document.getElementById('twoFactorModal').classList.remove('hidden');
        } else {
            alert('Error: ' + (data.message || 'Failed to enable 2FA'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error enabling 2FA: ' + error.message);
    })
    .finally(() => {
        // Restore button state
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

function confirm2FA() {
    const code = document.getElementById('twoFactorCode').value;
    
    if (!code || code.length !== 6) {
        alert('Please enter a 6-digit code');
        return;
    }
    
    fetch('{{ route("admin.security.confirm-2fa") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ code: code })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert('2FA enabled successfully! Save your recovery codes: ' + data.recovery_codes.join(', '));
            location.reload();
        } else {
            alert('Invalid code. Please try again. (For demo, use: 123456)');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error confirming 2FA: ' + error.message);
    });
}

function disable2FA() {
    const password = prompt('Enter your password to disable 2FA:');
    if (!password) return;

    fetch('{{ route("admin.security.disable-2fa") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ password: password })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('2FA disabled successfully');
            location.reload();
        } else {
            alert(data.message);
        }
    });
}

function closeTwoFactorModal() {
    document.getElementById('twoFactorModal').classList.add('hidden');
}

function logoutAllDevices() {
    const password = prompt('Enter your password to logout from all devices:');
    if (!password) return;

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("admin.security.logout-all") }}';
    
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    
    const passwordInput = document.createElement('input');
    passwordInput.type = 'hidden';
    passwordInput.name = 'password';
    passwordInput.value = password;
    
    form.appendChild(csrfToken);
    form.appendChild(passwordInput);
    document.body.appendChild(form);
    form.submit();
}

// Add event listener when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, setting up 2FA button');
    
    // Alternative event listener for 2FA button
    const enable2FABtn = document.getElementById('enable2FABtn');
    if (enable2FABtn) {
        enable2FABtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('2FA button clicked via event listener');
            enable2FA();
        });
    }
    
    // Test CSRF token
    console.log('CSRF Token:', '{{ csrf_token() }}');
});
</script>
@endsection