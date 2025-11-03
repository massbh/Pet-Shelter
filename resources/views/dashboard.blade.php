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
        .badge-guest { background: #6c757d; color: white; }
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
            transition: all 0.3s;
        }
        .btn-primary {
            background: #007bff;
            color: white;
        }
        .btn-primary:hover {
            background: #0056b3;
        }
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        .btn-danger:hover {
            background: #c82333;
        }
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
        .logout-form {
            display: inline;
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
            <form action="{{ route('logout') }}" method="POST" class="logout-form">
                @csrf
                <button type="submit" class="btn btn-danger">Logout</button>
            </form>
            @if($user->isAdmin())
                <a href="{{ route('admin.adoption-requests.index') }}" class="btn btn-primary">Manage Adoption Requests</a>
                <a href="{{ route('pets.create') }}" class="btn btn-primary">Add New Pet</a>
            @endif
        </div>

        <div>
            <h2>My Adoption Requests</h2>
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
                                    <a href="{{ route('pets.show', $request->pet) }}">
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
                                    <a href="{{ route('adoption-requests.show', $request) }}" class="btn btn-primary">View</a>
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
    </div>

    <div id="footer-container"></div>
    <script src="{{ asset('js/loadComponents.js') }}"></script>
</body>
</html>
