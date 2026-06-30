@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="admin-dashboard py-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <div>
            <h1 class="h3">Admin Dashboard</h1>
            <p class="text-muted">Welcome back. Use the menu to manage your site.</p>
          </div>
          <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-secondary">Logout</button>
          </form>
        </div>

        <div class="row g-4">
          <div class="col-md-4">
            <div class="card shadow-sm p-4">
              <h2 class="h5">Quick Summary</h2>
              <p class="mb-0">Your admin account is successfully authenticated.</p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card shadow-sm p-4">
              <h2 class="h5">Site Settings</h2>
              <p class="mb-0">Content modules can be added after auth is stable.</p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card shadow-sm p-4">
              <h2 class="h5">Security</h2>
              <p class="mb-0">Remember Me and password reset are enabled.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
