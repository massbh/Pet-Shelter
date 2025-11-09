<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Pet Shelter</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <style>
        .dashboard-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 2rem;
        }
        .user-info {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 8px;
            margin-bottom: 2rem;
        }
        .badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 4px;
            font-size: 0.875rem;
            font-weight: 600;
        }
        .badge-admin { background: #dc3545; color: white; }
        .badge-user { background: #28a745; color: white; }
        .badge-pending { background: #ffc107; color: #000; }
        .badge-approved { background: #28a745; color: white; }
        .badge-rejected { background: #dc3545; color: white; }
        .requests-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        .requests-table th,
        .requests-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        .requests-table th {
            background: #f8f9fa;
            font-weight: 600;
        }
        .btn {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 500;
            border: none;
            cursor: pointer;
            margin-right: 0.25rem;
        }
        .btn-primary { background: #007bff; color: white; }
        .btn-success { background: #28a745; color: white; }
        .btn-danger { background: #dc3545; color: white; }
            .btn-warning { background: #ffc107; color: #000; }
        .alert {
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 2rem;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        .close {
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            border: none;
            background: none;
            color: #aaa;
        }
        .close:hover {
            color: #000;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }
        .form-group textarea {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-family: inherit;
            font-size: 0.95rem;
            resize: vertical;
            min-height: 100px;
        }
        .modal-actions {
            display: flex;
            gap: 0.5rem;
            justify-content: flex-end;
            margin-top: 1rem;
        }
        .section {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            border: 1px solid #dee2e6;
        }
        .stats {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        .stat-box {
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 4px;
            flex: 1;
        }
    </style>
</head>
<body>
    <div id="header-container"></div>

    <div class="dashboard-container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="user-info">
            <h1>Welcome, {{ $user->name }}!</h1>
            <p>
                <strong>Email:</strong> {{ $user->email }}<br>
                <strong>Role:</strong> 
                <span class="badge badge-{{ $user->role }}">{{ ucfirst($user->role) }}</span>
            </p>
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-danger">Logout</button>
            </form>
        </div>

        {{-- ADMIN VIEW --}}
        @if($user->isAdmin())
            <div class="section">
                <h2>📋 All Adoption Requests (Admin View)</h2>
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
                                <th>Pet Name</th>
                                <th>Species/Age</th>
                                <th>Requester</th>
                                <th>Email</th>
                                <th>User Message</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
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
                                            <details>
                                                <summary style="cursor: pointer; color: #007bff;">Read message</summary>
                                                <p style="margin-top: 0.5rem; font-size: 0.9rem;">{{ $request->message }}</p>
                                            </details>
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
                                        @if($request->status === 'pending')
                                            <button type="button" class="btn btn-success" onclick="openModal({{ $request->id }}, 'approved', '{{ $request->user->name }}', '{{ $request->pet->name }}')">
                                                Approve
                                            </button>
                                            <button type="button" class="btn btn-danger" onclick="openModal({{ $request->id }}, 'rejected', '{{ $request->user->name }}', '{{ $request->pet->name }}')">
                                                Reject
                                            </button>
                                        @else
                                            <span class="badge badge-{{ $request->status }}">{{ ucfirst($request->status) }}</span>
                                            @if($request->admin_notes)
                                                <br><small style="color: #666; margin-top: 0.25rem; display: block;">{{ $request->admin_notes }}</small>
                                            @endif
                                        @endif
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

        {{-- USER VIEW --}}
        @if(!$user->isAdmin())
        <div class="section">
            <h2>🐾 My Adoption Requests</h2>
            <p style="color: #666; margin-bottom: 1rem;">Your personal adoption requests</p>
            
            @if($adoptionRequests->count() > 0)
                <div class="stats">
                    <div class="stat-box">
                        <strong>Total:</strong> {{ $adoptionRequests->count() }}
                    </div>
                    <div class="stat-box">
                        <strong>Pending:</strong> {{ $adoptionRequests->where('status', 'pending')->count() }}
                    </div>
                    <div class="stat-box">
                        <strong>Approved:</strong> {{ $adoptionRequests->where('status', 'approved')->count() }}
                    </div>
                    <div class="stat-box">
                        <strong>Rejected:</strong> {{ $adoptionRequests->where('status', 'rejected')->count() }}
                    </div>
                </div>

                <table class="requests-table">
                    <thead>
                        <tr>
                            <th>Pet Name</th>
                            <th>Species</th>
                            <th>Age</th>
                            <th>My Message</th>
                            <th>Status</th>
                            <th>Date Submitted</th>
                            <th>Admin Response</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($adoptionRequests as $request)
                            <tr>
                                <td><strong>{{ $request->pet->name }}</strong></td>
                                <td>{{ $request->pet->species }}</td>
                                <td>{{ $request->pet->age }} year(s)</td>
                                <td>
                                    @if($request->message)
                                        {{ Str::limit($request->message, 50) }}
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
                                    @if($request->admin_notes)
                                        {{ Str::limit($request->admin_notes, 50) }}
                                    @else
                                        <em style="color: #999;">Waiting for review...</em>
                                    @endif
                                </td>
                                <td>
                                    @if($request->status === 'pending')
                                        <form action="{{ route('adoption-requests.destroy', $request) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Cancel this request?')">Cancel</button>
                                        </form>
                                    @elseif($request->status === 'approved')
                                        <span style="color: #28a745; font-weight: bold;">✓ Approved!</span>
                                    @elseif($request->status === 'rejected')
                                        <span style="color: #dc3545;">✗ Rejected</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>You haven't submitted any adoption requests yet.</p>
                <a href="{{ route('pets.index') }}" class="btn btn-primary">Browse Available Pets</a>
            @endif
        </div>
        @endif
    </div>

    @if($user->isAdmin())
    <!-- Modal for Admin Notes -->
    <div id="statusModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">Update Request Status</h3>
                <button class="close" onclick="closeModal()">&times;</button>
            </div>
            <form id="statusForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" id="modalStatus">
                
                <div class="form-group">
                    <label for="admin_notes">Admin Notes <small>(Optional)</small></label>
                    <textarea 
                        name="admin_notes" 
                        id="admin_notes" 
                        placeholder="Add a note for the user (e.g., reason for rejection, next steps, etc.)"
                    ></textarea>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn btn-danger" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="btn btn-success" id="modalSubmit">Confirm</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(requestId, status, userName, petName) {
            const modal = document.getElementById('statusModal');
            const form = document.getElementById('statusForm');
            const title = document.getElementById('modalTitle');
            const statusInput = document.getElementById('modalStatus');
            const notesField = document.getElementById('admin_notes');
            const submitBtn = document.getElementById('modalSubmit');
            
            form.action = `/admin/adoption-requests/${requestId}/status`;
            
            statusInput.value = status;
            
            if (status === 'approved') {
                title.textContent = `Approve ${userName}'s request for ${petName}`;
                submitBtn.textContent = 'Approve Request';
                submitBtn.className = 'btn btn-success';
                notesField.placeholder = 'Add a congratulatory message or next steps for the adopter...';
            } else {
                title.textContent = `Reject ${userName}'s request for ${petName}`;
                submitBtn.textContent = 'Reject Request';
                submitBtn.className = 'btn btn-danger';
                notesField.placeholder = 'Explain the reason for rejection...';
            }
            
            notesField.value = '';
            
            modal.style.display = 'block';
        }
        
        function closeModal() {
            const modal = document.getElementById('statusModal');
            modal.style.display = 'none';
        }
        
        window.onclick = function(event) {
            const modal = document.getElementById('statusModal');
            if (event.target === modal) {
                closeModal();
            }
        }
    </script>
    @endif

    <div id="footer-container"></div>
    <script src="{{ asset('js/loadComponents.js') }}"></script>
</body>
</html>
