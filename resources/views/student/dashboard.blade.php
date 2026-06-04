<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quizora — Student Dashboard</title>
</head>
<body>
  <h1>Welcome, {{ auth()->user()->name }} 👋</h1>
  <p>You are logged in as <strong>Student</strong></p>

  <form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit">Logout</button>
  </form>
</body>
</html>