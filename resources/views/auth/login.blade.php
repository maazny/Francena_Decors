<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Login | Francena Decors</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet" />
  <link href="{{ asset('css/admin.css') }}" rel="stylesheet" />
  <style>
    html, body { height: 100%; }
    body {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, #0F172A 0%, #1E293B 60%, #1659B8 100%);
      font-family: 'Inter', sans-serif;
    }
    .login-wrapper {
      width: 100%;
      max-width: 440px;
      padding: 16px;
    }
    .login-card {
      background: #FFFFFF;
      border-radius: 18px;
      box-shadow: 0 24px 64px rgba(15,23,42,0.35);
      overflow: hidden;
    }
    .login-header {
      background: linear-gradient(135deg, #0F172A, #1659B8);
      padding: 32px 32px 24px;
      text-align: center;
    }
    .login-header img {
      height: 60px;
      width: auto;
      object-fit: contain;
      margin-bottom: 12px;
    }
    .login-header h1 {
      font-family: 'Montserrat', sans-serif;
      font-size: 1.1rem;
      font-weight: 700;
      color: rgba(255,255,255,0.7);
      margin: 0;
      letter-spacing: 0.06em;
      text-transform: uppercase;
    }
    .login-body { padding: 32px; }
    .login-body .form-label {
      font-size: 0.82rem;
      font-weight: 600;
      color: #334155;
      margin-bottom: 6px;
    }
    .login-body .form-control {
      border: 1.5px solid #E2E8F0;
      border-radius: 10px;
      padding: 10px 14px;
      font-size: 0.875rem;
      color: #0F172A;
      transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .login-body .form-control:focus {
      border-color: #1659B8;
      box-shadow: 0 0 0 3px rgba(22,89,184,0.15);
      outline: none;
    }
    .login-body .btn-login {
      background: #1659B8;
      color: #FFFFFF;
      border: none;
      border-radius: 10px;
      font-weight: 700;
      font-size: 0.95rem;
      padding: 12px;
      width: 100%;
      transition: background 0.25s ease, box-shadow 0.25s ease, transform 0.25s ease;
      box-shadow: 0 6px 20px rgba(22,89,184,0.3);
      letter-spacing: 0.02em;
    }
    .login-body .btn-login:hover {
      background: #0F4A9B;
      box-shadow: 0 8px 28px rgba(22,89,184,0.45);
      transform: translateY(-1px);
    }
    .login-footer {
      padding: 16px 32px;
      background: #F8FAFC;
      border-top: 1px solid #E2E8F0;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .login-footer a {
      font-size: 0.82rem;
      color: #1659B8;
      text-decoration: none;
      font-weight: 500;
    }
    .login-footer a:hover { color: #0F4A9B; }
    .login-footer .version {
      font-size: 0.75rem;
      color: #94A3B8;
    }
    .alert-danger-custom {
      background: #FEF2F2;
      color: #991B1B;
      border: none;
      border-left: 4px solid #EF4444;
      border-radius: 8px;
      padding: 10px 14px;
      font-size: 0.82rem;
    }
  </style>
</head>
<body>
  <div class="login-wrapper">
    <div class="login-card">
      <div class="login-header">
        @php $siteSetting = app(\App\Models\SiteSetting::class)::first(); @endphp
        @if($siteSetting?->logo)
          <img src="{{ Storage::url($siteSetting->logo) }}" alt="Francena Decors" />
        @else
          <div style="font-family:'Montserrat',sans-serif;font-size:1.8rem;font-weight:800;color:#E8551A;margin-bottom:8px;">FD</div>
        @endif
        <h1>Admin Panel</h1>
      </div>

      <div class="login-body">
        @if(session('status'))
          <div class="alert-danger-custom mb-4" style="background:#F0FDF4;color:#166534;border-left-color:#22C55E;">
            {{ session('status') }}
          </div>
        @endif

        @if($errors->any())
          <div class="alert-danger-custom mb-4">
            {{ $errors->first() }}
          </div>
        @endif

        <form method="POST" action="{{ route('admin.login.submit') }}">
          @csrf

          <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input id="email" name="email" type="email"
              class="form-control @error('email') is-invalid @enderror"
              value="{{ old('email') }}" required autofocus
              placeholder="admin@francena.com" />
            @error('email')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input id="password" name="password" type="password"
              class="form-control @error('password') is-invalid @enderror"
              required placeholder="••••••••" />
            @error('password')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-4 form-check" style="padding-left:0;display:flex;align-items:center;gap:8px;">
            <input id="remember" name="remember" type="checkbox"
              class="form-check-input" {{ old('remember') ? 'checked' : '' }}
              style="width:16px;height:16px;border-radius:4px;border:1.5px solid #CBD5E1;cursor:pointer;flex-shrink:0;" />
            <label for="remember" class="form-check-label" style="font-size:0.82rem;color:#64748B;cursor:pointer;">
              Keep me signed in
            </label>
          </div>

          <button type="submit" class="btn-login">
            <i class="fa-solid fa-right-to-bracket me-2"></i>
            Sign In to Dashboard
          </button>
        </form>
      </div>

      <div class="login-footer">
        <a href="{{ route('admin.password.request') }}">
          <i class="fa-solid fa-key me-1"></i>Forgot Password?
        </a>
        <span class="version">Francena Decors CMS</span>
      </div>
    </div>
  </div>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
