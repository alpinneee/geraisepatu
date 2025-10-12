@extends('layouts.admin')

@section('title', 'Contact Messages')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Contact Messages</h3>
                    @if($unreadCount > 0)
                        <span class="badge badge-danger">{{ $unreadCount }} unread</span>
                    @endif
                </div>

                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <form method="GET" action="{{ route('admin.contacts.index') }}" class="form-inline">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="Search by name, email, or subject..." value="{{ request('search') }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="submit">Search</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-6">
                            <div class="btn-group float-right">
                                <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-primary {{ !request('status') ? 'active' : '' }}">All</a>
                                <a href="{{ route('admin.contacts.index', ['status' => 'unread']) }}" class="btn btn-outline-warning {{ request('status') === 'unread' ? 'active' : '' }}">Unread</a>
                                <a href="{{ route('admin.contacts.index', ['status' => 'read']) }}" class="btn btn-outline-success {{ request('status') === 'read' ? 'active' : '' }}">Read</a>
                            </div>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    @endif

                    <!-- Bulk Actions -->
                    <form id="bulk-form" method="POST">
                        @csrf
                        @method('DELETE')
                        
                        <div class="mb-3">
                            <button type="button" class="btn btn-sm btn-success" onclick="markAsRead()">Mark as Read</button>
                            <button type="button" class="btn btn-sm btn-danger" onclick="bulkDelete()">Delete Selected</button>
                        </div>

                        <!-- Contacts Table -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th width="30">
                                            <input type="checkbox" id="select-all">
                                        </th>
                                        <th>Status</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Subject</th>
                                        <th>Date</th>
                                        <th width="120">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($contacts as $contact)
                                        <tr class="{{ !$contact->is_read ? 'table-warning' : '' }}">
                                            <td>
                                                <input type="checkbox" name="contact_ids[]" value="{{ $contact->id }}" class="contact-checkbox">
                                            </td>
                                            <td>
                                                @if(!$contact->is_read)
                                                    <span class="badge badge-warning">Unread</span>
                                                @elseif($contact->replied_at)
                                                    <span class="badge badge-success">Replied</span>
                                                @else
                                                    <span class="badge badge-info">Read</span>
                                                @endif
                                            </td>
                                            <td>{{ $contact->name }}</td>
                                            <td>
                                                <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
                                            </td>
                                            <td>{{ Str::limit($contact->subject, 50) }}</td>
                                            <td>{{ $contact->created_at->format('d M Y, H:i') }}</td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('admin.contacts.show', $contact) }}" class="btn btn-info btn-sm">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <form method="POST" action="{{ route('admin.contacts.destroy', $contact) }}" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">No contact messages found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </form>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $contacts->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select all checkbox functionality
    document.getElementById('select-all').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.contact-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
});

function markAsRead() {
    const form = document.getElementById('bulk-form');
    const checkedBoxes = document.querySelectorAll('.contact-checkbox:checked');
    
    if (checkedBoxes.length === 0) {
        alert('Please select at least one contact.');
        return;
    }
    
    form.action = '{{ route("admin.contacts.mark-as-read") }}';
    form.method = 'POST';
    form.querySelector('input[name="_method"]').value = 'POST';
    form.submit();
}

function bulkDelete() {
    const form = document.getElementById('bulk-form');
    const checkedBoxes = document.querySelectorAll('.contact-checkbox:checked');
    
    if (checkedBoxes.length === 0) {
        alert('Please select at least one contact.');
        return;
    }
    
    if (!confirm('Are you sure you want to delete the selected contacts?')) {
        return;
    }
    
    form.action = '{{ route("admin.contacts.bulk-delete") }}';
    form.method = 'POST';
    form.querySelector('input[name="_method"]').value = 'DELETE';
    form.submit();
}
</script>
@endsection