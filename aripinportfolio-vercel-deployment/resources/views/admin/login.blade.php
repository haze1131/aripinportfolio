<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
    body { background: #0f172a; color: white; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
    .card { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 20px; padding: 40px; width: 100%; max-width: 400px; }
    h2 { font-size: 28px; margin-bottom: 8px; }
    p { color: #94a3b8; margin-bottom: 30px; font-size: 14px; }
    label { display: block; font-size: 13px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; }
    input { width: 100%; padding: 14px; border: none; outline: none; border-radius: 10px; background: rgba(255,255,255,0.08); color: white; margin-bottom: 20px; }
    button { width: 100%; padding: 14px; border: none; border-radius: 10px; background: #22d3ee; color: black; font-weight: bold; cursor: pointer; font-size: 15px; }
    .error { background: rgba(239,68,68,0.15); border: 1px solid rgba(239,68,68,0.3); border-radius: 10px; padding: 12px 15px; color: #fca5a5; font-size: 14px; margin-bottom: 20px; }
  </style>
</head>
<body>
  <div class="card">
    <h2>Admin Login</h2>
    <p>Masuk untuk mengelola konten portfolio.</p>

    @if ($errors->any())
      <div class="error">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('admin.login') }}">
      @csrf
      <label>Email</label>
      <input type="email" name="email" value="{{ old('email') }}" placeholder="admin@portfolio.com" required>
      <label>Password</label>
      <input type="password" name="password" placeholder="••••••••" required>
      <button type="submit">Masuk</button>
    </form>
  </div>
</body>
</html>
