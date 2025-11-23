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
                                    <a href="{{ route('pets.show', $request->pet) }}" style="color:#FF6B00; font-weight:600;">
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
                                <form action="{{ route('adoption-requests.destroy', $request) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"
                                        onclick="return confirm('Are you sure you want to delete this adoption request?');">
                                        <i data-lucide="trash-2"></i> Delete
                                    </button>
                                </form>
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
        
    </div>

    
    @include('components.footer')
    
    <script>lucide.createIcons();</script>

</body>
</html>
