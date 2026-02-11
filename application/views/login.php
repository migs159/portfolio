<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>CRUD Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast@1.4.0/dist/css/iziToast.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary: #6366f1;
      --primary-dark: #4f46e5;
      --secondary: #ec4899;
      --accent: #111827;
      --muted: #6b7280;
      --light-bg: #f8fafc;
      --surface: #ffffff;
    }

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
      background: radial-gradient(circle at 20% 50%, rgba(99, 102, 241, 0.1), transparent 50%),
                  radial-gradient(circle at 80% 80%, rgba(236, 72, 153, 0.08), transparent 50%);
      pointer-events: none;
      z-index: -1;
    }

    .login-container {
      width: 100%;
      max-width: 480px;
      z-index: 1;
    }

    .login-card {
      background: var(--surface);
      border: 0;
      border-radius: 15px;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
      overflow: hidden;
      animation: fadeInUp 0.6s ease-out;
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .login-header {
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      padding: 3rem 2rem 2rem;
      text-align: center;
      color: #fff;
    }

    .login-header h1 {
      font-family: 'Poppins', sans-serif;
      font-size: 1.8rem;
      font-weight: 800;
      margin: 0 0 0.5rem 0;
    }

    .login-header p {
      font-size: 0.9rem;
      opacity: 0.9;
      margin: 0;
    }

    .login-body {
      padding: 2.5rem;
    }

    .form-label {
      font-weight: 600;
      color: var(--accent);
      margin-bottom: 0.75rem;
      font-size: 0.95rem;
    }

    .form-control {
      border: 2px solid #e2e8f0;
      border-radius: 8px;
      padding: 0.75rem 1rem;
      font-size: 0.95rem;
      transition: all 0.3s ease;
      background: var(--light-bg);
    }

    .form-control:focus {
      border-color: var(--primary);
      background: var(--surface);
      box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }

    .form-group {
      margin-bottom: 1.5rem;
    }

    .btn-signin {
      background: linear-gradient(135deg, var(--primary), var(--primary-dark));
      color: #fff;
      border: 0;
      border-radius: 8px;
      padding: 0.75rem 2rem;
      font-weight: 600;
      transition: all 0.3s ease;
      width: 100%;
      box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
    }

    .btn-signin:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(99, 102, 241, 0.4);
    }

    .btn-signin:active {
      transform: translateY(0);
    }

    .btn-back {
      color: var(--primary);
      text-decoration: none;
      font-weight: 600;
      transition: all 0.3s ease;
      padding: 0.75rem 1.5rem;
    }

    .btn-back:hover {
      color: var(--primary-dark);
      transform: translateX(-5px);
    }

    .login-actions {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 2rem;
      gap: 1rem;
    }

    .alert {
      border: 0;
      border-radius: 8px;
      border-left: 4px solid;
    }

    .alert-danger {
      border-left-color: #ef4444;
      background: rgba(239, 68, 68, 0.1);
      color: #991b1b;
    }

    .alert-success {
      border-left-color: #22c55e;
      background: rgba(34, 197, 94, 0.1);
      color: #166534;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <div class="login-card">
      <div class="login-header">
        <h1>User Login</h1>
        <p>Access your account</p>
      </div>
      <div class="login-body">
        <?php if (!empty($error)): ?>
          <div class="alert alert-danger mb-3" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><?php echo htmlspecialchars($error); ?>
          </div>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
          <div class="alert alert-success mb-3" role="alert">
            <i class="fas fa-check-circle me-2"></i><?php echo htmlspecialchars($success); ?>
          </div>
        <?php endif; ?>
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
          <div style="display:flex;gap:0.5rem;width:100%;">
            <a href="<?php echo site_url('auth/register'); ?>" class="btn btn-outline-primary" style="flex:1;display:inline-flex;align-items:center;justify-content:center;border-radius:8px;padding:.6rem .9rem;">
              <i class="fas fa-user-plus me-2"></i>Register
            </a>
            <a href="<?php echo site_url('portfolio'); ?>" class="btn-back" style="flex:1;text-align:center;"> <i class="fas fa-arrow-left me-1"></i>Back to Portfolio</a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/izitoast@1.4.0/dist/js/iziToast.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function(){
      <?php if (!empty($error)): ?>
        iziToast.error({
          title: 'Error',
          message: <?php echo json_encode($error); ?>,
          position: 'topRight',
          timeout: 5000
        });
      <?php endif; ?>
      <?php if (!empty($success)): ?>
        iziToast.success({
          title: 'Success',
          message: <?php echo json_encode($success); ?>,
          position: 'topRight',
          timeout: 4000
        });
      <?php endif; ?>
      <?php if (!empty($error) || !empty($success)): ?>
        // hide inline bootstrap alerts when showing iziToast
        document.querySelectorAll('.alert').forEach(function(el){ el.style.display = 'none'; });
      <?php endif; ?>
    });
  </script>
</body>
</html>
