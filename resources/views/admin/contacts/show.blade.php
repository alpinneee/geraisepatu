@extends('layouts.admin')

@section('title', 'Contact Message Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Contact Message Details</h3>
                    <div>
                        <a href="{{ route('admin.contacts.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-8">
                            <!-- Message Content -->
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title mb-0">{{ $contact->subject }}</h4>
                                </div>
                                <div class="card-body">
                                    <div class="message-content">
                                        {!! nl2br(e($contact->message)) !!}
                                    </div>
                                </div>
                            </div>

                            <!-- Reply Section -->
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Quick Reply</h5>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted">
                                        You can reply directly by clicking the email link or using your email client.
                                    </p>
                                    <div class="btn-group">
                                        <a href="mailto:{{ $contact->email }}?subject=Re: {{ $contact->subject }}" class="btn btn-primary">
                                            <i class="fas fa-reply"></i> Reply via Email
                                        </a>
                                        @if(!$contact->replied_at)
                                            <form method="POST" action="{{ route('admin.contacts.mark-as-replied', $contact) }}" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-success">
                                                    <i class="fas fa-check"></i> Mark as Replied
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <!-- Contact Information -->
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Contact Information</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Name:</strong></td>
                                            <td>{{ $contact->name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Email:</strong></td>
                                            <td>
                                                <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Subject:</strong></td>
                                            <td>{{ $contact->subject }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Date:</strong></td>
                                            <td>{{ $contact->created_at->format('d M Y, H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Status:</strong></td>
                                            <td>
                                                @if(!$contact->is_read)
                                                    <span class="badge badge-warning">Unread</span>
                                                @elseif($contact->replied_at)
                                                    <span class="badge badge-success">Replied</span>
                                                    <br><small class="text-muted">{{ $contact->replied_at->format('d M Y, H:i') }}</small>
                                                @else
                                                    <span class="badge badge-info">Read</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Actions</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <a href="mailto:{{ $contact->email }}?subject=Re: {{ $contact->subject }}" class="btn btn-primary btn-block">
                                            <i class="fas fa-envelope"></i> Send Email
                                        </a>
                                        
                                        @if(!$contact->replied_at)
                                            <form method="POST" action="{{ route('admin.contacts.mark-as-replied', $contact) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-success btn-block">
                                                    <i class="fas fa-check"></i> Mark as Replied
                                                </button>
                                            </form>
                                        @endif

                                        <form method="POST" action="{{ route('admin.contacts.destroy', $contact) }}" onsubmit="return confirm('Are you sure you want to delete this contact?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-block">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.message-content {
    background-color: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    border-left: 4px solid #007bff;
    font-size: 14px;
    line-height: 1.6;
}
</style>
@endsection