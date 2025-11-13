<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <script src="{{ asset('js/loadComponents.js') }}"></script>
</head>
<body>
    <div id="header-container"></div>

    <div class="dashboard-container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="user-info">
            <div class="user-info-content">
                <div class="user-info-header">
                    <h1>Welcome, {{ $user->name }}!</h1>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger">Logout</button>
                    </form>
                </div>
                <p>
                    <strong>Email:</strong> {{ $user->email }}
                    <span style="margin-left: 2rem;"><strong>Role:</strong> 
                    <span class="badge badge-{{ $user->role }}">{{ ucfirst($user->role) }}</span></span>
                </p>
            </div>
        </div>

        {{-- ADMIN VIEW --}}
        @if($user->isAdmin())
            <div class="section">
                <h2>📋 Adoption Requests</h2>
                
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
                                            <button type="button" class="btn btn-success btn-block" onclick="openModal({{ $request->id }}, 'approved', '{{ $request->user->name }}', '{{ $request->pet->name }}')">
                                                Approve
                                            </button>
                                            <button type="button" class="btn btn-danger btn-block" onclick="openModal({{ $request->id }}, 'rejected', '{{ $request->user->name }}', '{{ $request->pet->name }}')">
                                                Reject
                                            </button>
                                        @else
                                            <span class="badge badge-{{ $request->status }}">{{ ucfirst($request->status) }}</span>
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
                                    @else
                                        <span style="color: #6c757d;">Closed Request</span>
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
                    <label for="admin_notes">Admin Notes</label>
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
</body>
</html>
