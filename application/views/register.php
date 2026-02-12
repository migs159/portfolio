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
  <link rel="stylesheet" href="<?php echo htmlspecialchars(function_exists('base_url') ? base_url('assets/css/theme.css') : '/assets/css/theme.css'); ?>">
  <style>
    * { scroll-behavior: smooth; }
    html { scroll-behavior: smooth; }
    body {
      font-family: 'Inter', system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem;
      background: linear-gradient(135deg, #ffffff 0%, var(--light-bg) 100%);
      position: relative;
      overflow-x: hidden;
    }
    body::before {
      content: '';
      position: fixed;
      inset: 0;
      background: radial-gradient(circle at 20% 50%, rgba(99, 102, 241, 0.08), transparent 50%),
                  radial-gradient(circle at 80% 80%, rgba(236, 72, 153, 0.06), transparent 50%);
      pointer-events: none;
      z-index: -1;
    }
    .card{max-width:540px;width:100%;border-radius:15px;box-shadow:0 10px 40px rgba(0,0,0,0.1);overflow:hidden;background:var(--surface);border:0}
    .card-header{background:linear-gradient(135deg,var(--primary),var(--secondary));color:#fff;padding:3rem 2rem 2rem;text-align:center}
    .card-header h3{font-family:'Poppins',sans-serif;margin:0;font-weight:800}
    .card-header p{margin:0;opacity:0.9}
    .card-body{padding:2.5rem}
    .form-label{font-weight:600;color:var(--accent);margin-bottom:.75rem;font-size:.95rem}
    .form-control{border:2px solid #e2e8f0;border-radius:8px;padding:.75rem 1rem;background:var(--light-bg)}
    .form-control:focus{border-color:var(--primary);background:var(--surface);box-shadow:0 0 0 3px rgba(99,102,241,0.08);outline:none}
    .btn-register{background:linear-gradient(135deg,var(--primary),var(--primary-dark));color:#fff;border:0;border-radius:8px;padding:.75rem 1.2rem;font-weight:600;box-shadow:0 4px 15px rgba(99,102,241,0.3)}
    .btn-register:hover{transform:translateY(-2px);box-shadow:0 8px 25px rgba(99,102,241,0.4)}
    .btn-link{color:var(--primary)}
    .alert{border:0;border-radius:8px;border-left:4px solid}
    .alert-danger{border-left-color:#ef4444;background:rgba(239,68,68,0.1);color:#991b1b}
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
