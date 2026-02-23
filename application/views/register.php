<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!doctype html>
<html lang="en">
<head>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,minimum-scale=1,user-scalable=no">
  <title>Register</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo htmlspecialchars(function_exists('base_url') ? base_url('assets/css/theme.css') : '/assets/css/theme.css'); ?>">
  <link rel="stylesheet" href="<?php echo htmlspecialchars(function_exists('base_url') ? base_url('assets/css/auth-custom.css') : '/assets/css/auth-custom.css'); ?>">
</head>
<body>
  <div class="card">
    <div class="card-header text-center">
      <h3>Create an account</h3>
      <p>Register to access your user dashboard</p>
    </div>
    <div class="card-body">
      <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
      <?php endif; ?>
      <form method="post" action="<?php echo site_url('auth/register'); ?>">
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">First name</label>
            <input name="first_name" class="form-control" value="<?php echo htmlspecialchars(isset($first_name) ? $first_name : ''); ?>" required>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Last name</label>
            <input name="last_name" class="form-control" value="<?php echo htmlspecialchars(isset($last_name) ? $last_name : ''); ?>" required>
          </div>
        </div>
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input name="email" type="email" class="form-control" value="<?php echo htmlspecialchars(isset($email) ? $email : ''); ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Username</label>
          <input name="username" class="form-control" value="<?php echo htmlspecialchars(isset($username) ? $username : ''); ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input name="password" type="password" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Confirm Password</label>
          <input name="confirm" type="password" class="form-control" required>
        </div>
        <div class="d-flex justify-content-between align-items-center">
          <button type="submit" class="btn-register">Register</button>
          <a href="<?php echo site_url('auth/login'); ?>" class="auth-back-link"><i class="fas fa-arrow-left"></i> Back to Login</a>
        </div>
      </form>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
