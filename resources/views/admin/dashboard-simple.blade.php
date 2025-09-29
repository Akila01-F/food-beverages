<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Test</title>
</head>
<body>
    <h1>Admin Dashboard</h1>
    <p>Welcome, {{ auth()->user()->name }}!</p>
    <p>You are logged in as an admin.</p>
    <a href="{{ route('admin.logout') }}">Logout</a>
</body>
</html>