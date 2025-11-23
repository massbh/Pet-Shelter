<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Pet Shelter</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="{{ asset('js/dashboard_modal.js') }}" defer></script>


</head>

<body>
    
    @include('components.header')


    <div class="dashboard-container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="user-info">
            <h1><i data-lucide="user"></i> Welcome, {{ $user->name }}!</h1>

            <div class="user-details">
                <div class="user-detail">
                    <i data-lucide="mail" class="user-detail-icon"></i>
                    <div>
                        <strong>Email</strong>
                        <span>{{ $user->email }}</span>
                    </div>
                </div>

                <div class="user-detail">
                    <i data-lucide="shield" class="user-detail-icon"></i>
                    <div>
                        <strong>Role</strong>
                        <span>
                            <span class="badge badge-{{ $user->role }}" data-tooltip="This is your account permission level.">
                                {{ ucfirst($user->role) }}
                            </span>
                        </span>
                    </div>
                </div>
            </div>

            <form action="{{ route('logout') }}" method="POST" class="logout-form">
                @csrf
                <button type="submit" class="btn btn-danger"><i data-lucide="log-out"></i> Logout</button>
            </form>
            @if($user->isAdmin())
                <a href="{{ route('pets.create') }}" class="btn btn-primary"><i data-lucide="plus-circle"></i> Add New Pet</a>
                <a href="/admin/pet-gallery" class="btn btn-primary"><i data-lucide="paw-print"></i> Manage Pets</a>
            @endif
        </div>

        @if (!$user->isAdmin())
            <div>
            <h2 style="color:#FF6B00;">My Adoption Requests</h2>
            @if($adoptionRequests->count() > 0)
                <table class="requests-table">
                    <thead>
                        <tr>
                            <th>Pet</th>
                            <th>Species</th>
                            <th>Status</th>
                            <th>Submitted</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($adoptionRequests as $request)
                            <tr>
                                <td>
                                    <a href="#" onclick="showPetDetails({{ $request->pet->id }}); return false;" style="color:#FF6B00; font-weight:600; cursor: pointer;">
                                        {{ $request->pet->name }}
                                    </a>
                                </td>
                                <td>{{ $request->pet->species }}</td>
                                <td>
                                    <span class="badge badge-{{ $request->status }}">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                </td>
                                <td>{{ $request->created_at->format('M d, Y') }}</td>
                                <td>
                                    <a href="{{ route('adoption-requests.show', $request) }}" class="btn btn-primary"><i data-lucide="eye"></i> View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>You haven't submitted any adoption requests yet.</p>
                <a href="/admin/pet-gallery" class="btn btn-primary"><i data-lucide="paw-print"></i> Browse Available Pets</a>
            @endif
        </div>
        @else
            <div class="admin-stats">
                <div class="stat-card">
                    <i data-lucide="paw-print"></i>
                    <div>
                        <h4>Total Pets</h4>
                        <p class="stat-number">{{ \App\Models\Pet::count() }}</p>
                    </div>
                </div>
                <div class="stat-card">
                    <i data-lucide="check-circle"></i>
                    <div>
                        <h4>Available</h4>
                        <p class="stat-number">{{ \App\Models\Pet::where('status', 'available')->count() }}</p>
                    </div>
                </div>
                <div class="stat-card">
                    <i data-lucide="clock"></i>
                    <div>
                        <h4>Pending Requests</h4>
                        <p class="stat-number">{{ \App\Models\AdoptionRequest::where('status', 'pending')->count() }}</p>
                    </div>
                </div>
                <div class="stat-card">
                    <i data-lucide="users"></i>
                    <div>
                        <h4>Total Users</h4>
                        <p class="stat-number">{{ \App\Models\User::count() }}</p>
                    </div>
                </div>
            </div>
            <div class="section">
                <h2>📋 All Adoption Requests</h2>
                <p style="color: #666; margin-bottom: 1rem;">Viewing requests from all users in the system</p>
                
                <div class="stats">
                    <div class="stat-box">
                        <strong>Total:</strong> {{ $allRequests->count() }}
                    </div>
                    <div class="stat-box">
                        <strong>Pending:</strong> {{ $allRequests->where('status', 'pending')->count() }}
                    </div>
                    <div class="stat-box">
                        <strong>Approved:</strong> {{ $allRequests->where('status', 'approved')->count() }}
                    </div>
                    <div class="stat-box">
                        <strong>Rejected:</strong> {{ $allRequests->where('status', 'rejected')->count() }}
                    </div>
                </div>

                @if($allRequests->count() > 0)
                    <table class="requests-table">
                        <thead>
                            <tr>
                                <th style="white-space: nowrap;">Pet Name</th>
                                <th style="white-space: nowrap;">Species/Age</th>
                                <th style="white-space: nowrap;">Requester</th>
                                <th style="white-space: nowrap;">Email</th>
                                <th style="white-space: nowrap;">User Message</th>
                                <th style="white-space: nowrap;">Status</th>
                                <th style="white-space: nowrap;">Date</th>
                                <th style="white-space: nowrap;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allRequests as $request)
                                <tr>
                                    <td><strong>{{ $request->pet->name }}</strong></td>
                                    <td>{{ $request->pet->species }}, {{ $request->pet->age }}y</td>
                                    <td>{{ $request->user->name }}</td>
                                    <td>{{ $request->user->email }}</td>
                                    <td>
                                        @if($request->message)
                                            <button type="button" class="btn btn-info btn-sm" onclick="showMessage('{{ addslashes($request->message) }}')">
                                                <i data-lucide="message-circle"></i> Read
                                            </button>
                                        @else
                                            <em style="color: #999;">No message</em>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $request->status }}">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $request->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div style="display: flex; gap: 0.5rem; align-items: center;">
                                            @if($request->status === 'pending')
                                                <button type="button" class="btn btn-success" onclick="openModal({{ $request->id }}, 'approved', '{{ $request->user->name }}', '{{ $request->pet->name }}')">
                                                    Approve
                                                </button>
                                                <button type="button" class="btn btn-danger" onclick="openModal({{ $request->id }}, 'rejected', '{{ $request->user->name }}', '{{ $request->pet->name }}')">
                                                    Reject
                                                </button>
                                            @else
                                                @if($request->admin_notes)
                                                    <button type="button" class="btn btn-info btn-sm" onclick="showAdminNotes('{{ addslashes($request->admin_notes) }}')">
                                                        <i data-lucide="file-text"></i> Read Notes
                                                    </button>
                                                @else
                                                    <em style="color: #999;">No notes</em>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p>No adoption requests in the system yet.</p>
                @endif
            </div>
        @endif
        
    </div>

    
    @include('components.footer')
    
    <!-- Action Confirmation Modal -->
    <div id="actionModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="actionModalTitle"><i data-lucide="alert-circle"></i> Confirm Action</h3>
                <span class="close" onclick="closeActionModal()">&times;</span>
            </div>
            <form id="actionForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <p id="actionConfirmText"></p>
                    <div style="margin-top: 1rem;">
                        <label for="admin_notes" style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Admin Notes (Optional)</label>
                        <textarea 
                            id="admin_notes" 
                            name="admin_notes" 
                            rows="3" 
                            placeholder="Add any notes about this decision..."
                            style="width: 100%; padding: 0.5rem; border: 1px solid #ffd3a1; border-radius: 6px; font-family: inherit;"
                        ></textarea>
                    </div>
                    <input type="hidden" name="status" id="actionStatus">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeActionModal()">Cancel</button>
                    <button type="submit" class="btn" id="actionSubmitBtn">Confirm</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Message Modal -->
    <div id="messageModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i data-lucide="message-circle"></i> User Message</h3>
                <span class="close" onclick="closeMessageModal()">&times;</span>
            </div>
            <div class="modal-body">
                <p id="messageText"></p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" onclick="closeMessageModal()">Close</button>
            </div>
        </div>
    </div>

    <!-- Admin Notes Modal -->
    <div id="adminNotesModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i data-lucide="file-text"></i> Admin Notes</h3>
                <span class="close" onclick="closeAdminNotesModal()">&times;</span>
            </div>
            <div class="modal-body">
                <p id="adminNotesText"></p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" onclick="closeAdminNotesModal()">Close</button>
            </div>
        </div>
    </div>

    <!-- Pet Details Modal -->
    <div id="petDetailsModal" class="modal">
        <div class="modal-content" style="max-width: 600px;">
            <div class="modal-header">
                <h3><i data-lucide="paw-print"></i> Pet Details</h3>
                <span class="close" onclick="closePetDetailsModal()">&times;</span>
            </div>
            <div class="modal-body" id="petDetailsContent">
                <div style="text-align: center; padding: 2rem;">
                    <p>Loading...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closePetDetailsModal()">Close</button>
            </div>
        </div>
    </div>

</body>
</html>
