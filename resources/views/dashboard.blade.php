<!DOCTYPE html>
<html lang="en">
      <script>
      window.user = @json(auth()->user() ? ['name' => auth()->user()->name] :
      null);
  </script>;
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Pet Shelter</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        .dashboard-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 2rem;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .user-info {
            background-color: #fff3e0; 
            padding: 2rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            border: 1px solid #ffd3a1; 
        }
        .user-info h1 {
            color: #FF6B00; 
        }

        .badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 4px;
            font-size: 0.875rem;
            font-weight: 600;
        }
        .badge-admin { background-color: #FF6B00; color: #fff; } 
        .badge-user { background-color: #FFA040; color: #fff; } 
        .badge-guest { background-color: #f0f0f0; color: #333; }
        .badge-pending { background-color: #fff4e6; color: #FF6B00; }
        .badge-approved { background-color: #E6FFEB; color: #28a745; }
        .badge-rejected { background-color: #FFE6E6; color: #dc3545; }

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
            .requests-table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 1rem;
                font-family: 'Inter', sans-serif; 
            }
            .requests-table th,
            .requests-table td {
                padding: 1rem;
                border-bottom: 1px solid #ffe0b2; 
            }
            .requests-table th {
                background-color: #fff3e0; 
                color: #FF6B00;
                font-weight: 600;
            }
            .requests-table tbody tr:hover {
                background-color: #fff7ed; 
            }

        .btn {
            border-radius: 10px;
            padding: 0.6rem 1.2rem;
            font-weight: 500;
        }

            .btn-primary {
            background-color: #FF6B00; 
            color: #fff;
        }
        .btn-primary:hover {
            background-color: #e65c00; 
        }
        .btn-danger {
            background-color: #FF6B00; 
            color: #fff;
        }
        .btn-danger:hover {
            background-color: #e65c00;
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
