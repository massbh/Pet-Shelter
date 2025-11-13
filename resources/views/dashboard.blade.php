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
    
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>

        body {
            font-family: 'Inter', sans-serif;
            background-color: #fff8f2;
            margin: 0;
            padding: 0;
            color: #333;
        }

        a {
            text-decoration: none;
            transition: all 0.3s ease;
        }


        .dashboard-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 2rem;
            background-color: #ffffff;
            border-radius: 16px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.08);
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }


        .user-info {
            background-color: #fff7eb;
            padding: 2rem;
            border-radius: 14px;
            margin-bottom: 2rem;
            border: 1px solid #ffd3a1;
            transition: box-shadow 0.3s ease, transform 0.3s ease;
        }

        .user-info:hover {
            box-shadow: 0 6px 16px rgba(255, 107, 0, 0.15);
            transform: translateY(-2px);
        }

        .user-info h1 {
            color: #FF6B00;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .user-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .user-detail {
            background: #fff;
            border: 1px solid #ffe0b2;
            border-radius: 10px;
            padding: 1rem 1.2rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }

        .user-detail:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 10px rgba(255, 107, 0, 0.15);
        }

        .user-detail-icon {
            color: #FF6B00;
            flex-shrink: 0;
        }

        .user-detail strong {
            display: block;
            font-size: 0.85rem;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .user-detail span {
            font-size: 0.95rem;
            color: #333;
            font-weight: 500;
        }


        .badge {
            display: inline-block;
            padding: 0.3rem 0.8rem;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 600;
            transition: transform 0.2s ease;
            position: relative;
        }

        .badge:hover {
            transform: scale(1.05);
        }

        .badge::after {
            content: attr(data-tooltip);
            position: absolute;
            bottom: 125%;
            left: 50%;
            transform: translateX(-50%);
            background-color: #333;
            color: #fff;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s ease;
        }

        .badge:hover::after {
            opacity: 1;
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
            border-radius: 10px;
            overflow: hidden;
        }

        .requests-table th,
        .requests-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #ffe0b2;
            transition: background-color 0.2s ease;
        }

        .requests-table th {
            background-color: #fff3e0;
            color: #FF6B00;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .requests-table tbody tr {
            transition: background-color 0.2s ease, transform 0.2s ease;
        }

        .requests-table tbody tr:hover {
            background-color: #fff8f2;
            transform: scale(1.01);
        }


        .btn {
            border-radius: 10px;
            padding: 0.7rem 1.4rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
        }

        .btn-primary {
            background-color: #FF6B00;
            color: #fff;
            box-shadow: 0 3px 8px rgba(255, 107, 0, 0.3);
        }

        .btn-primary:hover {
            background-color: #e65c00;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(255, 107, 0, 0.4);
        }

        .btn-danger {
            background-color: #FF6B00;
            color: #fff;
            box-shadow: 0 3px 8px rgba(255, 107, 0, 0.3);
        }

        .btn-danger:hover {
            background-color: #e65c00;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(255, 107, 0, 0.4);
        }


        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            animation: slideIn 0.4s ease-out;
        }

        @keyframes slideIn {
            from { transform: translateY(-10px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .logout-form {
            display: inline;
        }

        a.btn-primary {
            text-decoration: none;
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
                <a href="{{ route('admin.adoption-requests.index') }}" class="btn btn-primary"><i data-lucide="file-text"></i> Manage Requests</a>
                <a href="{{ route('pets.create') }}" class="btn btn-primary"><i data-lucide="plus-circle"></i> Add New Pet</a>
            @endif
        </div>

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
                                    <a href="{{ route('adoption-requests.show', $request) }}" class="btn btn-primary"><i data-lucide="eye"></i> View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>You haven't submitted any adoption requests yet.</p>
                <a href="{{ route('pets.index') }}" class="btn btn-primary"><i data-lucide="paw-print"></i> Browse Available Pets</a>
            @endif
        </div>
    </div>

    <div id="footer-container"></div>
    <script src="{{ asset('js/loadComponents.js') }}"></script>
    <script>lucide.createIcons();</script>
</body>
</html>
