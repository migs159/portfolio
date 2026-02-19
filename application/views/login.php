<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,minimum-scale=1,user-scalable=no">
  <title>CRUD Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <!-- replaced iziToast with SweetAlert2 -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo htmlspecialchars(function_exists('base_url') ? base_url('assets/css/theme.css') : '/assets/css/theme.css'); ?>">
  <link rel="stylesheet" href="<?php echo htmlspecialchars(function_exists('base_url') ? base_url('assets/css/auth-custom.css') : '/assets/css/auth-custom.css'); ?>">
</head>
<body>
  <div class="login-container">
    <div class="login-card">
      <div class="login-header">
        <h1>User Login</h1>
        <p>Access your account</p>
      </div>
      <div class="login-body">
        <form method="post" action="<?php echo site_url('auth/login'); ?>">
          <div class="form-group">
            <label class="form-label"><i class="fas fa-user me-2"></i>Username</label>
            <input name="username" class="form-control" placeholder="Enter your username" required>
          </div>
          <div class="form-group">
            <label class="form-label"><i class="fas fa-key me-2"></i>Password</label>
            <input name="password" type="password" class="form-control" placeholder="Enter your password" required>
          </div>
          <button type="submit" class="btn-signin">Sign In</button>
        </form>
        <div class="login-actions">
          <div>
            <a href="<?php echo site_url('auth/register'); ?>" class="btn btn-outline-primary login-action-btn">
              <i class="fas fa-user-plus me-2"></i>Register
            </a>
            <a href="<?php echo site_url('portfolio'); ?>" class="auth-back-link login-action-btn"><i class="fas fa-arrow-left me-1"></i>Back to Portfolio</a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
  <script src="<?php echo htmlspecialchars(function_exists('base_url') ? base_url('assets/js/login.js') : '/assets/js/login.js'); ?>" data-login-error="<?php echo htmlspecialchars($error ?? '', ENT_QUOTES, 'UTF-8'); ?>" data-login-success="<?php echo htmlspecialchars($success ?? '', ENT_QUOTES, 'UTF-8'); ?>"></script>
</body>
</html>
