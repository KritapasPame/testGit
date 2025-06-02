<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <h2>Welcome, {{ Auth::user()->email }}</h2>

    <h3>Active Users</h3>
    <ul>
        <li>
            {{ $user->email }} (User ID: {{ $user->id }})
        </li>
    </ul>

    <form method="POST" action="/logout">
        @csrf
        <button type="submit">Logout</button>
    </form>
    <script>
        // This script sends an AJAX request to update the last activity time every 5 minutes
        setInterval(function() {
            fetch('/update-last-activity', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({})  // Sending an empty body
            });
        }, 300000);  // 300000ms = 5 minutes

    </script>
</body>
</html>
