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
    <script>
    
    window.user = @json(auth()->user() ? ['name' => auth()->user()->name] : null);
</script>


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
