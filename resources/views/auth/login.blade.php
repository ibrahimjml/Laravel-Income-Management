<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Login-page')</title>

    <!-- AdminLTE CSS -->
    <link href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
      body {
          background-color: #f0f8ff;
          font-family: 'Poppins', sans-serif;
          display: flex;
          justify-content: center;
          align-items: center;
          height: 100vh;
          margin: 0;
      }

      .login-card {
          background-color: #ffffff;
          border: 1px solid #ddd;
          border-radius: 8px;
          box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
          padding: 2rem;
          width: 100%;
          max-width: 400px;
      }

      .login-header {
          text-align: center;
          color: #007bff;
          margin-bottom: 1rem;
      }

      .error-message {
          color: red;
          text-align: center;
      }
  </style>
</head>
<body>
  <div class="login-card">
    <h2 class="login-header">Login</h2>
    @if(session()->has('error'))
    <div class="error-message">
      {{session('error')}}
    </div>
    @endif
    <form action="{{route('admin.login')}}" method="POST">
      @csrf
      @method('POST')
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Email address</label>
            <input type="email" name="email" class="form-control" id="exampleInputEmail1" required>
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Password</label>
            <input type="password" name="password" class="form-control" id="exampleInputPassword1" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
</div>

</body>
</html>