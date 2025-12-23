<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Fu System - Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
  <style>
    /* Additional styles if needed */
    .form-check-label {
      color: #6C3A30;
      font-size: 12px;
    }
    .forgot-password-link {
      color: #6C3A30;
      text-decoration: none;
      display: block;
      text-align: right;
      margin-top: 5px;
      margin-bottom: 15px;
    }
    .forgot-password-link:hover {
      text-decoration: underline;
    }
    .btn-outline {
      background-color: #6C3A30;
      color: white;
      border: none;
    }
    .btn-outline:hover {
      background-color: #8a4d3f;
      color: white;
    }
  </style>
</head>

<body>
  <picture>
    <source srcset="{{ asset('assets/logowithname.svg') }}" type="image/svg+xml">
    <img src="{{ asset('assets/logowithname.svg') }}" class="logo" alt="logo" draggable="false">
  </picture>

  <!-- Top-left image -->
  <img src="{{ asset('assets/bottomleft.svg') }}" class="bottom-left" alt="bottomleft" draggable="false">

  <!-- Bottom-right image -->
  <img src="{{ asset('assets/topright.svg') }}" class="top-right" alt="topright" draggable="false">

  <!-- Form Container -->
  <div class="container col-12" style="flex-direction: row;">
    <div class="row g-0 col-12">
      <div class="left-form col-4 align-content-center">
        
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />
           
        <form method="POST" action="{{ route('login')  }}">
        
          @csrf
          
          <figure class="text-center">
            <blockquote class="blockquote">
              <h2 style="color: #6C3A30; margin-top: 40px; margin-bottom: 20px;user-select: none; -webkit-user-select: none; -moz-user-select: none; -ms-user-select: none;"
                data-i18n="welcomeback">Welcome Back</h2>
            </blockquote>
          </figure>
          
          <div class="text-overlay"></div>

          <!-- Email Address -->
          <label for="email" class="form-label"
            style="color: #B77848;user-select: none; -webkit-user-select: none; -moz-user-select: none; -ms-user-select: none;"
            data-i18n="username">Username</label>
          <input type="email" class="form-control form-control-sm" id="email" name="email" value="{{ old('email') }}" required autofocus>
          @error('email')
            <div class="text-danger mt-1">{{ $message }}</div>
          @enderror

          <!-- Password -->
          <label for="password" class="form-label mt-3"
            style="color: #B77848;user-select: none; -webkit-user-select: none; -moz-user-select: none; -ms-user-select: none;"
            data-i18n="password">Password</label>
          <input type="password" id="password" name="password" class="form-control form-control-sm" required autocomplete="current-password">
          @error('password')
            <div class="text-danger mt-1">{{ $message }}</div>
          @enderror

          <!-- @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="forgot-password-link" data-i18n="forgot">Forgot password?</a>
          @endif -->

          <!-- Remember Me -->
          <div class="form-check mt-2">
            <input class="form-check-input small" type="checkbox" id="remember_me" name="remember">
            <label class="form-check-label" for="remember_me" data-i18n="remember">
              Remember my username
            </label>
          </div>

          <button type="submit" class="btn btn-outline mt-3" data-i18n="login" style="width: 220px;margin-top: 10px;">Login</button>
        </form>
      </div>

      <div class="img-container col-8 g-0 d-none d-md-block">
        <img src="{{ asset('assets/paintinglogin2.svg') }}" alt="Welcome illustration" draggable="false">
        <h2 class="text-overlay" data-i18n="welcomecampus">Welcome to VoiceLine</h2>
      </div>
    </div>
  </div>

  <script src="{{ asset('js/localization.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
</body>

</html>