<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?php echo isset($page_title) ? htmlspecialchars($page_title) : 'CRUD Dashboard'; ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
    
    body {
      font-family: 'Inter', system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial;
      background: linear-gradient(135deg, #ffffff 0%, var(--light-bg) 100%);
      min-height: 100vh;
      position: relative;
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

    /* Navbar */
    .navbar {
      background: rgba(255, 255, 255, 0.95) !important;
      backdrop-filter: blur(10px);
      border-bottom: 1px solid rgba(0, 0, 0, 0.05);
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .navbar-brand {
      font-weight: 700;
      font-size: 1.3rem;
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .nav-link {
      color: #64748b !important;
      font-weight: 500;
      transition: color 0.3s ease;
    }

    .nav-link:hover {
      color: var(--primary) !important;
    }

    .btn-logout {
      background: linear-gradient(135deg, var(--primary), var(--primary-dark));
      color: #fff !important;
      border-radius: 8px;
      padding: 0.6rem 1.2rem;
      font-weight: 600;
      transition: all 0.3s ease;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
    }

    .btn-logout:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(99, 102, 241, 0.4);
    }

    /* Container */
    .container-main {
      max-width: 1200px;
      margin: 0 auto;
      padding: 3rem 1.5rem;
    }

    /* Header */
    .page-header {
      text-align: center;
      margin-bottom: 3rem;
    }

    .page-header h1 {
      font-family: 'Poppins', sans-serif;
      font-size: 2.5rem;
      font-weight: 800;
      color: var(--accent);
      margin-bottom: 0.5rem;
      background: linear-gradient(135deg, var(--accent), var(--primary));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .page-header p {
      color: var(--muted);
      font-size: 1.05rem;
      margin: 0;
    }

    /* Dashboard Cards */
    .dashboard-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 2rem;
      margin-bottom: 3rem;
    }

    .dashboard-card {
      background: var(--surface);
      border-radius: 15px;
      padding: 2rem;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
      transition: all 0.3s ease;
      border: 1px solid #e2e8f0;
    }

    .dashboard-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 35px rgba(99, 102, 241, 0.15);
      border-color: var(--primary);
    }

    .card-icon {
      width: 60px;
      height: 60px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(236, 72, 153, 0.1));
      border-radius: 12px;
      font-size: 1.5rem;
      color: var(--primary);
      margin-bottom: 1rem;
    }

    .card-title {
      font-family: 'Poppins', sans-serif;
      font-size: 1.1rem;
      font-weight: 700;
      color: var(--accent);
      margin-bottom: 0.5rem;
    }

    .card-text {
      color: var(--muted);
      font-size: 0.95rem;
      line-height: 1.6;
      margin-bottom: 1.5rem;
    }

    .card-action {
      display: inline-block;
      color: var(--primary);
      text-decoration: none;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .card-action:hover {
      color: var(--secondary);
      transform: translateX(5px);
    }

    /* Table Section */
    .table-section {
      background: var(--surface);
      border-radius: 15px;
      padding: 2rem;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .table-section h3 {
      font-family: 'Poppins', sans-serif;
      font-weight: 700;
      color: var(--accent);
      margin-bottom: 1.5rem;
    }

    .info-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1.5rem;
    }

    .info-item {
      padding: 1rem;
      background: var(--light-bg);
      border-radius: 10px;
      border-left: 4px solid var(--primary);
    }

    .info-label {
      font-size: 0.85rem;
      color: var(--muted);
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-bottom: 0.25rem;
    }

    .info-value {
      font-size: 1.1rem;
      font-weight: 600;
      color: var(--accent);
    }

    /* Footer */
    footer {
      background: var(--accent);
      color: #fff;
      padding: 2rem 0;
      text-align: center;
      margin-top: 4rem;
    }

    footer p {
      margin: 0;
      opacity: 0.8;
    }
  </style>
</head>
<body>
  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-light sticky-top">
    <div class="container">
      <a class="navbar-brand" href="<?php echo site_url('portfolio'); ?>"><i class="fas fa-cube me-2"></i>CRUD Dashboard</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link" href="<?php echo site_url('crud'); ?>">Dashboard</a>
          </li>
          <li class="nav-item">
            <a class="btn-logout ms-2" href="<?php echo site_url('crud/logout'); ?>">
              <i class="fas fa-sign-out-alt"></i>Logout
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <div class="container-main">
    <!-- Page Header -->
    <div class="page-header">
      <h1><i class="fas fa-cogs me-2"></i>CRUD Management</h1>
      <p>Welcome to your data management dashboard</p>
    </div>

    <!-- Dashboard Overview -->
    <div class="dashboard-grid">
      <div class="dashboard-card">
        <div class="card-icon">
          <i class="fas fa-plus-circle"></i>
        </div>
        <div class="card-title">Create</div>
        <div class="card-text">Add new records to the system with detailed information.</div>
        <a href="#" class="card-action">Add New <i class="fas fa-arrow-right ms-1"></i></a>
      </div>

      <div class="dashboard-card">
        <div class="card-icon">
          <i class="fas fa-list"></i>
        </div>
        <div class="card-title">Read</div>
        <div class="card-text">View all records in a clean and organized table layout.</div>
        <a href="#" class="card-action">View Records <i class="fas fa-arrow-right ms-1"></i></a>
      </div>

      <div class="dashboard-card">
        <div class="card-icon">
          <i class="fas fa-edit"></i>
        </div>
        <div class="card-title">Update</div>
        <div class="card-text">Modify existing records and keep your data up-to-date.</div>
        <a href="#" class="card-action">Edit Records <i class="fas fa-arrow-right ms-1"></i></a>
      </div>

      <div class="dashboard-card">
        <div class="card-icon">
          <i class="fas fa-trash"></i>
        </div>
        <div class="card-title">Delete</div>
        <div class="card-text">Remove records you no longer need with confirmation.</div>
        <a href="#" class="card-action">Delete <i class="fas fa-arrow-right ms-1"></i></a>
      </div>
    </div>

    <!-- User Information Section -->
    <div class="table-section">
      <h3><i class="fas fa-user-circle me-2"></i>Your Account Information</h3>
      <div class="info-grid">
        <div class="info-item">
          <div class="info-label">Username</div>
          <div class="info-value"><?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'User'; ?></div>
        </div>
        <div class="info-item">
          <div class="info-label">Status</div>
          <div class="info-value"><span class="badge bg-success">Active</span></div>
        </div>
        <div class="info-item">
          <div class="info-label">Account Type</div>
          <div class="info-value">Standard User</div>
        </div>
        <div class="info-item">
          <div class="info-label">Last Login</div>
          <div class="info-value">Just now</div>
        </div>
      </div>
    </div>

    <!-- Quick Actions -->
    <div style="margin-top: 3rem; text-align: center;">
      <a href="<?php echo site_url('portfolio'); ?>" class="btn btn-outline-primary me-2" style="border-radius: 8px;">
        <i class="fas fa-arrow-left me-1"></i>Back to Portfolio
      </a>
      <a href="<?php echo site_url('crud/logout'); ?>" class="btn btn-danger" style="border-radius: 8px;">
        <i class="fas fa-sign-out-alt me-1"></i>Logout
      </a>
    </div>
  </div>

  <!-- Footer -->
  <footer>
    <div class="container">
      <p>&copy; <?php echo date('Y'); ?> CRUD Dashboard. All rights reserved.</p>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
