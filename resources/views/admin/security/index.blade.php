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
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Security Score</p>
                    <p class="text-2xl font-bold text-green-600">75%</p>
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


</script>
@endsection