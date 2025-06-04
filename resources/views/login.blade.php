<!DOCTYPE html>
<html>

<head>
    <title>SSO Login</title>
</head>

<body>
    <h2>Login</h2>
    @if ($errors->any())
        <p style="color:red">{{ $errors->first() }}</p>
    @endif
    <form method="POST" action="{{ url('/login') }}">
        @csrf
        <label>Email:</label><br>
        <input type="email" name="email" required><br>
        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>
        <button type="submit">Login</button>
    </form>
    <br>
    <form method="POST" action="{{ url('/addname') }}">
        @csrf
        <label>Name:</label><br>
        <input type="text" name="name" ><br><br>
        <button type="submit">Add</button>
    </form>
</body>

</html>
