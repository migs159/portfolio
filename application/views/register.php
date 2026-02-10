<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Register</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
  <style>
    body{display:flex;min-height:100vh;align-items:center;justify-content:center;padding:2rem;background:#f8fafc;font-family:Inter,system-ui,-apple-system}
    .card{max-width:540px;width:100%;border-radius:12px;box-shadow:0 10px 40px rgba(2,6,23,0.07);overflow:hidden}
    .card-header{background:linear-gradient(135deg,#6366f1,#ec4899);color:#fff;padding:2rem}
    .card-body{padding:2rem}
    .form-control{border-radius:8px}
    .btn-register{background:linear-gradient(135deg,#6366f1,#4f46e5);color:#fff;border:0;border-radius:8px;padding:.7rem 1.2rem}
  </style>
</head>
<body>
  <div class="card">
    <div class="card-header text-center">
      <h3 style="margin:0;font-family:Poppins,system-ui;font-weight:700">Create an account</h3>
      <p style="opacity:.9;margin:0">Register to access your user dashboard</p>
    </div>
    <div class="card-body">
      <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
      <?php endif; ?>
      <form method="post" action="<?php echo site_url('auth/register'); ?>">
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">First name</label>
            <input name="first_name" class="form-control" required>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Last name</label>
            <input name="last_name" class="form-control" required>
          </div>
        </div>
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input name="email" type="email" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Username</label>
          <input name="username" class="form-control" required>
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
          <button class="btn-register">Register</button>
          <a href="<?php echo site_url('auth/login'); ?>" class="btn btn-link">Back to Login</a>
        </div>
      </form>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
