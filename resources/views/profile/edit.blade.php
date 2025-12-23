@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="css/login.css">
  <style>
    body {
      background: #f8f9fa;
    }
    .profile-card {
      max-width: 600px;
      margin: 50px auto;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0px 4px 12px rgba(0,0,0,0.1);
      padding: 30px;
    }
    .profile-header {
      text-align: center;
      margin-bottom: 20px;
    }
    .profile-header i {
      font-size: 80px;
      color: #6b4226;
    }
  </style>
</head>
<body>
<picture>
    <source srcset="assets/logowithname.svg" type="image/svg+xml">
    <img src="assets/logowithname.svg" class="logo" alt="logo" draggable="false">
  </picture>

  <!-- Top-left image -->
  <img src="assets/bottomleft.svg" class="bottom-left" alt="bottomleft" draggable="false">
  <!-- Bottom-right image -->
  <img src="assets/topright.svg" class="top-right" alt="topright" draggable="false">

  <div class="profile-card">
    
    <div class="profile-info">
      <h5>User Information</h5>
      <table class="table">
        <tr>
          <th>Full Name</th>
          <td>{{ $user->name }}</td>
        </tr>
        <tr>
          <th>Email</th>
          <td>{{ $user->email }}</td>
        </tr>
        <tr>
          <th>Role</th>
          <td>{{ $user->role }}</td>
        </tr>
       
      </table>
    </div>

    <!-- Update Profile Button -->
    <div class="text-center">
        
        <div class="actions">
            <div class="action">
                <a href="#" data-bs-toggle="modal" data-bs-target="#updateModal">Update Profile</a>
            </div>
            <div class="action">
                <a href="password.html" data-bs-toggle="modal" data-bs-target="#passModal">Change Password</a>
            </div>
        </div>
      
    </div>
  </div>

  <!-- Update Modal -->
<!-- Update Modal -->
<div class="modal fade" id="updateModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Update Profile</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      
      <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        @method('PATCH')

        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" class="form-control" value="{{ $user->name }}">
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ $user->email }}">
          </div>
        </div>

        <div class="modal-footer">
            
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
        
      </form>
      
    </div>
  </div>
</div>


<!-- Password Modal -->
<div class="modal fade" id="passModal" tabindex="-1" aria-labelledby="passModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      
      <!-- Header -->
      <div class="modal-header">
        <h5 class="modal-title" id="passModalLabel">Change Password</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <!-- Form -->
      <form method="POST" action="{{ route('profile.updatePassword') }}">
        @csrf
        @method('PATCH')

        <div class="modal-body">
          <div class="mb-3">
            <label for="oldPassword" class="form-label">Current Password</label>
            <input type="password" class="form-control" id="oldPassword" name="oldPassword" required>
          </div>
          <div class="mb-3">
            <label for="newPassword" class="form-label">New Password</label>
            <input type="password" class="form-control" id="newPassword" name="newPassword" required>
          </div>
          <div class="mb-3">
            <label for="confirmPassword" class="form-label">Confirm New Password</label>
            <input type="password" class="form-control" id="confirmPassword" name="newPassword_confirmation" required>
          </div>
        </div>

        <!-- Footer -->
        <div class="modal-footer">
          <div class="actions d-flex justify-content-between w-100">
            <div class="action">
              <a href="#" data-bs-dismiss="modal">Cancel</a>
            </div>
            <div class="action">
              <button type="submit" class="btn btn-link p-0">Save Changes</button>
            </div>
          </div>
        </div>
      </form>

    </div>
  </div>
</div>





  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
@yield('content');
 