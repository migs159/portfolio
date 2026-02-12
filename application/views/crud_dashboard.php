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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast@1.4.0/dist/css/iziToast.min.css">
  <link rel="stylesheet" href="<?php echo htmlspecialchars(function_exists('base_url') ? base_url('assets/css/theme.css') : '/assets/css/theme.css'); ?>">
  <style>
    * { scroll-behavior: smooth; }
    
    body {
      font-family: 'Inter', system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial;
      background: #ffffff;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
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
    .navbar {
      background:rgba(255,255,255,0.95) !important;
      backdrop-filter:blur(10px);
      border-bottom:1px solid rgba(0,0,0,0.05);
      z-index:1200;
      transition:all 0.3s ease;
    }

    .navbar.scrolled {
      box-shadow:0 4px 20px rgba(0,0,0,0.08);
    }

    .navbar-brand {
      font-weight:700;
      font-size:1.3rem;
      background:linear-gradient(135deg,var(--primary),var(--secondary));
      -webkit-background-clip:text;
      -webkit-text-fill-color:transparent;
      background-clip:text;
    }

    .navbar .nav-link {
      color:#64748b !important;
      font-weight:500;
      font-size:0.95rem;
      position:relative;
      transition:color 0.3s ease;
    }

    .navbar .nav-link::after {
      content:'';
      position:absolute;
      bottom:-4px;
      left:0;
      width:0;
      height:2px;
      background:var(--primary);
      transition:width 0.3s ease;
    }

    /* Hide the decorative pseudo-element for dropdown toggles (profile) so
       no small tooltip/caret appears when the dropdown is open. */
    .navbar .nav-link.dropdown-toggle::after,
    #profileDropdown::after,
    #profileDropdown.show::after { display: none !important; }

    .navbar .nav-link:hover {
      color:var(--primary) !important;
    }

    .navbar .nav-link:hover::after {
      width:100%;
    }

    /* View Account modal tweaks: move slightly upwards and green active badge */
    #viewAccountModal .modal-dialog { transform: translateY(-8vh) !important; }
    .view-account-badge { background: linear-gradient(90deg,#22c55e,#16a34a); color:#fff; padding:.45rem .75rem; border-radius:999px; font-weight:700; }

    .btn-logout {
      border-radius:8px;
      padding:0.6rem 1.3rem;
      background:var(--primary);
      color:#fff !important;
      border:0;
      font-weight:600;
      transition:all 0.3s ease;
      box-shadow:0 4px 15px rgba(99,102,241,0.3);
    }

    .btn-logout:hover {
      background:var(--primary-dark);
      transform:translateY(-2px);
      box-shadow:0 8px 25px rgba(99,102,241,0.4);
    }

    /* Profile initial/avatar in navbar */
    .profile-initial {
      width:34px;
      height:34px;
      border-radius:50%;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      background:linear-gradient(135deg,var(--primary),var(--primary-dark));
      color:#fff;
      font-weight:700;
      font-size:0.9rem;
      line-height:1;
      vertical-align:middle;
    }
    .nav-link.dropdown-toggle { padding-right: .6rem; }
    .nav-link .profile-initial { margin-top: 0; }
    /* Align dropdown items icon + text */
    .dropdown-menu .dropdown-item { display:flex; align-items:center; gap:.6rem; }
    .dropdown-menu .dropdown-item i { width:20px; text-align:center; font-size:1.05rem; }
    .dropdown-menu { min-width:200px; }
    /* Position the dropdown relative to its nav-item and nudge it further right.
       Keep the small-screen override below. */
    .navbar-nav .nav-item.dropdown { position: relative; }
    .navbar-nav .nav-item.dropdown .dropdown-menu[aria-labelledby="profileDropdown"] {
      position: absolute !important;
      right: 0 !important;
      left: auto !important;
      transform: translateX(140px) !important; /* nudged slightly right from 135px */
      transform-origin: top right !important;
      top: calc(100% + 8px) !important;
      z-index: 1400 !important;
    }

    /* Prevent the dropdown from overflowing on small screens */
    @media (max-width: 768px) {
      .navbar-nav .nav-item.dropdown .dropdown-menu[aria-labelledby="profileDropdown"] {
        transform: none !important;
        right: auto !important;
        left: 0 !important;
      }
    }

    /* Container */
    .container-main {
      max-width: 1200px;
      margin: 0 auto;
      padding: 1.5rem 1.5rem;
    }

    /* Space between navbar and page header */
    .page-header-top {
      padding-top: 2.5rem;
      padding-bottom: 1.5rem;
    }

    /* main content area should grow so footer stays at page bottom */
    .main-content { flex: 1 1 auto; }

    /* Footer styles to keep it pinned to bottom and visually light */
    footer {
      margin-top: auto;
      padding: 1rem 0;
      color: #6b7280;
      font-size: 0.95rem;
      background: transparent;
    }

    footer .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 1.5rem;
    }

    /* Header */
    .page-header {
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:1rem;
      margin-bottom: 2.25rem;
    }

    .page-header h1 {
      font-family: 'Poppins', sans-serif;
      font-size: 2rem;
      font-weight: 800;
      margin: 0 0 0.25rem 0;
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    /* (removed container styles for CRUD header to keep it transparent) */

    

    .page-header .subtitle {
      color: var(--muted);
      font-size: 0.95rem;
      margin: 0;
    }

    .header-actions { display:flex; align-items:center; gap:0.75rem; }
    .search-input { border-radius: 999px; padding:.55rem .9rem; border:1px solid rgba(15,23,42,0.06); min-width:220px; box-shadow:none }
    .search-input:focus{ outline:none; box-shadow:0 8px 30px rgba(30,64,175,0.06); border-color:rgba(30,64,175,0.14) }

    /* Dashboard Cards */
    .dashboard-grid {
      display: grid;
      gap: 1.25rem;
      margin-bottom: 2rem;
      align-items: start;
      grid-template-columns: 1fr;
    }

    /* Make all dashboard cards equal height */
    .dashboard-grid { align-items: stretch; grid-auto-rows: 1fr; }

    @media (min-width: 600px) {
      .dashboard-grid { grid-template-columns: repeat(2, minmax(240px, 1fr)); }
    }
    @media (min-width: 992px) {
      .dashboard-grid { grid-template-columns: repeat(3, minmax(240px, 1fr)); }
    }
    @media (min-width: 1400px) {
      .dashboard-grid { grid-template-columns: repeat(4, minmax(220px, 1fr)); }
    }

    .dashboard-card {
      background: linear-gradient(135deg, var(--surface) 0%, var(--light-bg) 100%);
      border-radius: var(--radius);
      padding: 1.5rem;
      box-shadow: 0 10px 30px rgba(37,99,235,0.08);
      transition: transform 0.22s cubic-bezier(.2,.9,.3,1), box-shadow 0.22s ease, border-color 0.22s ease;
      border: 1px solid rgba(37,99,235,0.12);
      display: flex;
      flex-direction: column;
      gap: 1rem;
      min-height: 220px;
      height: 100%;
      align-self: stretch;
    }

    .dashboard-card:hover {
      transform: translateY(-8px) scale(1.01);
      box-shadow: 0 20px 50px rgba(37,99,235,0.15);
      border-color: rgba(37,99,235,0.2);
    }

    .card-icon {
      width: 54px;
      height: 54px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, rgba(37, 99, 235, 0.12), rgba(6, 182, 212, 0.08));
      border-radius: 10px;
      font-size: 1.3rem;
      color: var(--primary);
    }

    .card-title {
      font-family: 'Poppins', sans-serif;
      font-size: 1.05rem;
      font-weight: 700;
      color: var(--accent);
      margin-bottom: 0.25rem;
    }

    .card-text {
      color: var(--muted);
      font-size: 0.92rem;
      line-height: 1.5;
      margin-bottom: 1rem;
    }

    /* Button system for dashboard actions */
    .btn-pill{border-radius:999px;padding:.55rem 1rem;font-weight:700;display:inline-flex;align-items:center;gap:.6rem;transition:transform .12s ease,box-shadow .12s ease,opacity .12s ease}
    .btn-primary-custom{background:linear-gradient(135deg, var(--primary), var(--primary-dark));color:#fff;border:0;box-shadow:0 8px 25px rgba(37, 99, 235, 0.25)}
    .btn-primary-custom:hover{transform:translateY(-2px);box-shadow:0 10px 30px rgba(30,64,175,0.16)}
    .btn-ghost{background:transparent;border:1px solid rgba(30,64,175,0.12);color:var(--primary)}
    .btn-ghost:hover{background:rgba(30,64,175,0.04)}
    .btn-danger-custom{background:linear-gradient(90deg,#ef4444,#dc2626);color:#fff;border:0}
    .btn-danger-custom:hover{transform:translateY(-2px);box-shadow:0 10px 30px rgba(239,68,68,0.14)}
    .card-action{margin-top:auto;display:flex;gap:.5rem}
    .card-action .btn-pill{display:inline-flex;align-items:center;gap:.5rem}
    .card-action .fa-arrow-right{opacity:.95}
    .btn-pill:focus{outline:none;box-shadow:0 10px 30px rgba(30,64,175,0.14)}

    /* Modal visual refinements */
    .modal-content { border-radius: 14px; overflow: hidden; border: 0; }
    .modal-header {
      background: linear-gradient(90deg,var(--primary),var(--primary-dark));
      color: #fff; border-bottom: 0; padding: 1.1rem 1.25rem; display:flex;align-items:center;gap:.75rem;
    }
    .modal-header .modal-icon {
      width:44px;height:44px;border-radius:10px;display:inline-flex;align-items:center;justify-content:center;background:rgba(255,255,255,0.12);
    }
    .modal-title { font-weight: 800; font-size: 1.05rem; letter-spacing: 0.2px; margin:0; }
    .modal-sub { font-size:0.9rem; opacity:0.9; margin-left:.4rem }
    .modal-body { padding: 1.25rem; background: linear-gradient(180deg, var(--surface) 0%, var(--light-bg) 100%); }
    .modal-footer { padding: 0.9rem 1.25rem; border-top: 0; display:flex; gap:.5rem; justify-content:flex-end; background:transparent }
    .modal .form-label { font-weight:700; color:var(--accent); font-size:.85rem }
    .modal .form-control { border-radius: 10px; border:1px solid rgba(15,23,42,0.06); padding:.7rem .9rem; box-shadow:none }
    .modal .form-control:focus{box-shadow:0 8px 30px rgba(30,64,175,0.06);border-color:rgba(30,64,175,0.16);outline:none}
    .modal .form-text { font-size:0.85rem;color:var(--muted);margin-top:.35rem }
    .modal .btn-pill { box-shadow: 0 8px 28px rgba(2,6,23,0.06); padding:.6rem 1rem }
    .modal .btn-ghost { background: transparent; border: 1px solid rgba(15,23,42,0.06); color:var(--accent); }
    .modal .close { color: rgba(255,255,255,0.95); opacity: .95; }
    .modal .form-row { display:grid; grid-template-columns:1fr; gap:.8rem }
    @media(min-width:768px){ .modal .form-row.cols-2{ grid-template-columns:1fr 1fr } }

    /* iframe modal specifics */
    .iframe-wrap{width:100%;height:65vh;border-radius:10px;border:1px solid rgba(15,23,42,0.04);background:var(--surface);box-shadow:0 8px 30px rgba(15,23,42,0.06);overflow:hidden}
    .iframe-loading{display:flex;align-items:center;justify-content:center;height:65vh}

    /* Make the iframe modal wider so embedded content has room; keeps boxed layout for the project card */
    @media(min-width:1200px){
      /* Match example modal width for a comfortable 3-column layout inside */
      #iframeModal .modal-dialog { max-width: 1150px; }
    }
    .iframe-embedded-header{position:sticky;top:0;z-index:20;background:linear-gradient(180deg,#fff,#fbfbfd);padding:.6rem 1rem;border-radius:8px;margin-bottom:.75rem;box-shadow:0 6px 18px rgba(2,6,23,0.04)}
    .iframe-embedded-header div{color:inherit}

     /* Ensure modals appear above footer/header and make the backdrop transparent
       (remove the dark gray overlay behind modals while preserving backdrop clicks) */
     .modal-backdrop { z-index: 2000 !important; background-color: transparent !important; }
     .modal-backdrop.show { background-color: transparent !important; opacity: 1 !important; }
     .modal { z-index: 2001 !important; }

    /* Table Section */
    .table-section {
      background: linear-gradient(135deg, #ffffff 0%, var(--light-bg) 100%);
      border-radius: 16px;
      padding: 2.5rem;
      box-shadow: var(--card-shadow);
      border: 1px solid rgba(37,99,235,0.04);
      margin-bottom: 2.5rem;
    }

    .table-section h3 {
      font-family: 'Poppins', sans-serif;
      font-weight: 800;
      font-size: 1.3rem;
      background: linear-gradient(135deg, var(--accent), var(--primary));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      margin-bottom: 2rem;
      display: flex;
      align-items: center;
      gap: 0.6rem;
    }

    .info-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
      gap: 1.5rem;
    }

    .info-item {
      padding: 1.5rem;
      background: linear-gradient(135deg, var(--surface) 0%, rgba(37,99,235,0.02) 100%);
      border-radius: 12px;
      box-shadow: 0 8px 24px rgba(37,99,235,0.08);
      border: 1px solid rgba(37,99,235,0.12);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .info-item:hover {
      transform: translateY(-4px);
      box-shadow: 0 12px 32px rgba(37,99,235,0.12);
      border-color: rgba(37,99,235,0.18);
    }

    .info-label {
      font-size: 0.75rem;
      color: var(--muted);
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 1.2px;
      margin-bottom: 0.75rem;
    }

    .info-value {
      font-size: 1.3rem;
      font-weight: 800;
      background: linear-gradient(135deg, var(--accent), var(--primary));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .info-value .badge {
      background-clip: padding-box !important;
      -webkit-background-clip: padding-box !important;
      -webkit-text-fill-color: white !important;
      color: white !important;
      font-size: 0.85rem;
      font-weight: 700;
      padding: 0.5rem 1rem;
      border-radius: 20px;
      display: inline-block;
    }

    @media (max-width: 640px) {
      .info-grid { grid-template-columns: 1fr; }
    }

    /* Footer */
    footer {
      background: var(--footer-bg);
      color: var(--footer-text);
      padding: 3rem 0;
      text-align: center;
      margin-top: 0; /* allow flex to push footer to bottom */
    }

    footer p {
      margin: 0;
      opacity: 0.9;
      font-weight: 600;
    }

    /* Quick Actions styling */
    .quick-actions {
      display: flex;
      justify-content: center;
      gap: 1rem;
      flex-wrap: wrap;
    }

    .quick-actions .btn {
      border-radius: 8px;
      padding: 0.7rem 1.5rem;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .quick-actions .btn-primary {
      background: linear-gradient(135deg, var(--primary), var(--primary-dark));
      border: 0;
      color: #fff;
      box-shadow: 0 8px 25px rgba(37, 99, 235, 0.2);
    }

    .quick-actions .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 12px 35px rgba(37, 99, 235, 0.3);
      background: linear-gradient(135deg, var(--primary-dark), var(--primary));
      color: #fff;
    }

    .quick-actions .btn-danger {
      background: linear-gradient(135deg, #ef4444, #dc2626);
      border: 0;
      color: #fff;
      box-shadow: 0 8px 25px rgba(239, 68, 68, 0.2);
    }

    .quick-actions .btn-danger:hover {
      transform: translateY(-2px);
      box-shadow: 0 12px 35px rgba(239, 68, 68, 0.3);
      background: linear-gradient(135deg, #dc2626, #b91c1c);
      color: #fff;
    }
  </style>
</head>
<body>
  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-light sticky-top">
    <div class="container">
         <a class="navbar-brand" href="<?php echo site_url('portfolio'); ?>">
           <i class="fas fa-cube me-2"></i><span class="brand-text">CRUD </span>
         </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto align-items-center">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <span class="profile-initial me-2" aria-label="Account"><?php echo htmlspecialchars($__profile_initial); ?></span>
              <span class="d-none d-md-inline"><?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Account'; ?></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end p-3" aria-labelledby="profileDropdown" style="min-width:260px;">
              <li>
                <a href="#" class="dropdown-item d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#viewAccountModal">
                  <div class="profile-initial me-2" style="width:40px;height:40px;font-size:0.95rem"><?php echo htmlspecialchars($__profile_initial); ?></div>
                  <div>
                    <div style="font-weight:800">View Account</div>
                    <div class="text-muted" style="font-size:0.85rem">See account details</div>
                  </div>
                </a>
              </li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="<?php echo site_url('portfolio'); ?>"><i class="fas fa-home me-2"></i>View Portfolio</a></li>
              <li><a class="dropdown-item text-danger" href="<?php echo site_url('crud/logout'); ?>"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <!-- Top Header -->
  <div class="page-header-top" style="background:transparent;border-bottom:1px solid rgba(15,23,42,0.02);">
    <div class="container-main" style="padding-top:0;padding-bottom:0;">
      <div class="page-header">
        <div>
          <h1><i class="fas fa-cogs me-2"></i>CRUD Management</h1>
          <p class="subtitle">Welcome, <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'User'; ?> — manage your data</p>
        </div>
        
      </div>
    </div>
  </div>

  <!-- Main Content -->
  <div class="container-main main-content">
    <!-- Dashboard Overview -->
    <div class="dashboard-grid">
        <div class="dashboard-card">
        <div class="card-icon">
          <i class="fas fa-plus-circle"></i>
        </div>
        <div class="card-title">Create</div>
        <div class="card-text">Add new records to the system with detailed information.</div>
        <div class="card-action"><button type="button" class="btn-pill btn-primary-custom" data-bs-toggle="modal" data-bs-target="#quickCreateModal"><i class="fas fa-plus-circle"></i> Add New</button></div>
      </div>

        <div class="dashboard-card">
        <div class="card-icon">
          <i class="fas fa-list"></i>
        </div>
        <div class="card-title">Read</div>
        <div class="card-text">View all records in a clean and organized table layout.</div>
        <div class="card-text" style="font-weight:700;color:var(--primary);">Projects: <?php echo isset($projects_count) ? intval($projects_count) : 0; ?></div>
        <div class="card-action"><button type="button" id="openViewBtn" class="btn-pill btn-primary-custom"><i class="fas fa-list"></i> View Records</button></div>
      </div>

        <div class="dashboard-card">
        <div class="card-icon">
          <i class="fas fa-edit"></i>
        </div>
        <div class="card-title">Update</div>
        <div class="card-text">Modify existing records and keep your data up-to-date.</div>
        <div class="card-action"><button type="button" id="openEditBtn" class="btn-pill btn-primary-custom"><i class="fas fa-edit"></i> Edit Records</button></div>
      </div>

        <div class="dashboard-card">
        <div class="card-icon">
          <i class="fas fa-trash"></i>
        </div>
        <div class="card-title">Delete</div>
        <div class="card-text">Remove records you no longer need with confirmation.</div>
        <div class="card-action"><button type="button" id="openManageBtn" class="btn-pill btn-primary-custom"><i class="fas fa-trash"></i> Manage</button></div>
      </div>
    </div>

    <!-- User information moved into profile dropdown -->

    <!-- Quick Actions -->
    <div class="quick-actions" style="margin-top: 3rem;">
    </div>
  </div>

  <!-- Footer -->
  <footer>
    <div class="container">
      <p>&copy; <?php echo date('Y'); ?> CRUD Dashboard. All rights reserved.</p>
    </div>
  </footer>

  <!-- Quick Create Modal -->
  <div class="modal fade" id="quickCreateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Quick Create Project</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="post" action="<?php echo site_url('projects/create'); ?>">
          <input type="hidden" name="return_to" value="crud">
          <div class="modal-body">
              <div class="form-row cols-2">
                <div>
                  <label class="form-label">Title</label>
                  <input name="title" class="form-control" required placeholder="Project title">
                  <div class="form-text">Give a concise, descriptive title.</div>
                </div>
                <!-- Tags removed per request -->
                <div style="display:flex;flex-direction:column;gap:0.5rem;">
                  <label class="form-label">Framework/Language</label>
                  <select name="type[]" class="form-control" multiple size="6">
                    <option value="">--Select framework / language-- </option>
                    <option value="php">PHP</option>
                    <option value="javascript">JavaScript</option>
                    <option value="html_css">HTML/CSS</option>
                    <option value="nodejs">Node.js</option>
                    <option value="react">React</option>
                    <option value="vue">Vue.js</option>
                    <option value="angular">Angular</option>
                    <option value="uiux">UI/UX</option>
                    <option value="cli">CLI / Tools</option>
                    <option value="devops">DevOps</option>
                    <option value="other">Other</option>
                  </select>
                  <div class="form-text">Choose one or more frameworks or languages (hold Ctrl / Cmd to multi-select).</div>
                </div>

                <div style="grid-column:1/ -1;">
                  <label class="form-label">Description</label>
                  <textarea name="description" class="form-control" rows="4" placeholder="Short description"></textarea>
                </div>

                <div>
                  <label class="form-label">Image URL</label>
                  <input name="image" class="form-control" placeholder="https://...">
                </div>
                <div>
                  <label class="form-label">Link URL</label>
                  <input name="url" class="form-control" placeholder="https://...">
                </div>
              </div>
            </div>
          <div class="modal-footer">
            <button type="button" class="btn-pill btn-ghost" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn-pill btn-primary-custom">Create</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- View Account Modal -->
  <?php $user_email = isset($user['email']) ? $user['email'] : (isset($_SESSION['email']) ? $_SESSION['email'] : 'Not set'); ?>
  <div class="modal fade" id="viewAccountModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-fullscreen-md-down">
      <div class="modal-content">
        <div class="modal-body p-0">
          <div style="position:relative;">
            <div class="view-account-cover" style="height:120px;background:linear-gradient(135deg,var(--primary),var(--primary-dark));border-top-left-radius:14px;border-top-right-radius:14px;"></div>
            <div style="position:absolute;left:24px;top:64px;">
              <div class="profile-initial" style="width:96px;height:96px;border:6px solid #fff;font-size:1.6rem;box-shadow:0 8px 30px rgba(2,6,23,0.12);border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--primary-dark));display:flex;align-items:center;justify-content:center;color:#fff;">
                <?php echo htmlspecialchars($__profile_initial); ?>
              </div>
            </div>
            <!-- Edit Profile button removed per request -->
          </div>
          <div style="padding:1.25rem 1.5rem 1.5rem 1.5rem;">
            <div style="margin-left:132px;">
              <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;">
                <div>
                  <div style="font-family:'Poppins',sans-serif;font-weight:800;font-size:1.25rem;color:var(--accent);">
                    <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'User'; ?>
                  </div>
                  <div style="color:var(--muted);margin-top:4px;font-size:0.95rem">Member since: <?php echo date('Y'); ?></div>
                </div>
                <div style="text-align:right;">
                  <span class="view-account-badge">Active</span>
                </div>
              </div>
              <hr style="margin:1rem 0;border-color:rgba(15,23,42,0.06)">
              <div class="view-account-grid" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1rem;">
                <div>
                  <div style="font-size:0.82rem;color:var(--muted);font-weight:700;letter-spacing:0.8px;margin-bottom:.45rem"><i class="fas fa-envelope me-2"></i>EMAIL</div>
                  <div style="font-weight:700;color:var(--accent);">
                    <?php echo !empty($user_email) ? htmlspecialchars($user_email) : 'Not set'; ?>
                  </div>
                </div>
                <div>
                  <div style="font-size:0.82rem;color:var(--muted);font-weight:700;letter-spacing:0.8px;margin-bottom:.45rem"><i class="fas fa-id-badge me-2"></i>USERNAME</div>
                  <div style="font-weight:700;color:var(--accent);">
                    <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'User'; ?>
                  </div>
                </div>
                <div>
                  <div style="font-size:0.82rem;color:var(--muted);font-weight:700;letter-spacing:0.8px;margin-bottom:.45rem"><i class="fas fa-phone me-2"></i>PHONE</div>
                  <div style="font-weight:700;color:var(--accent);">
                    <?php echo isset($_SESSION['phone']) ? htmlspecialchars($_SESSION['phone']) : 'Not set'; ?>
                  </div>
                </div>
                <div>
                  <div style="font-size:0.82rem;color:var(--muted);font-weight:700;letter-spacing:0.8px;margin-bottom:.45rem"><i class="fas fa-clock me-2"></i>LAST LOGIN</div>
                  <div style="font-weight:700;color:var(--accent);">Just now</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Reusable iframe modal for View/Edit/Manage -->
  <div class="modal fade" id="iframeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-fullscreen-md-down">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="iframeModalTitle"><i class="fas fa-table me-2"></i><span id="iframeModalLabel">Manage</span></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" style="padding:1.5rem;">
          <div class="iframe-embedded-header" id="iframeEmbeddedHeader">
            <div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;">
              <div style="font-weight:800;font-size:1.15rem;color:var(--primary);">Projects</div>
              <div style="opacity:.85;font-size:.95rem;color:var(--muted)"></div>
            </div>
          </div>
          <div class="iframe-loading" id="iframeLoading" style="display:none"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>
          <iframe id="iframeModalFrame" src="" class="iframe-wrap"></iframe>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // AJAX submit for quick-create so we can show iziToast and close the modal without a full redirect
    (function(){
      var modal = document.getElementById('quickCreateModal');
      if(!modal) return;
      var form = modal.querySelector('form');
      if(!form) return;
      form.addEventListener('submit', function(ev){
        ev.preventDefault();
        var fd = new FormData(form);
        fetch(form.action, { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
          .then(function(res){ return res.json(); })
          .then(function(json){
            try{
              var inst = bootstrap.Modal.getOrCreateInstance(modal); inst.hide();
            }catch(e){}
            if (json && json.success) {
              iziToast.success({ title: 'Success', message: json.message || 'Created', position: 'topRight', timeout: 3500 });
              // Optionally reload iframe or parts of the page if needed
              // refresh project count if present
              var el = document.querySelector('.card-text strong');
            } else {
              iziToast.error({ title: 'Error', message: (json && json.message) || 'Create failed', position: 'topRight', timeout: 5000 });
            }
          }).catch(function(){
            try{ bootstrap.Modal.getOrCreateInstance(modal).hide(); }catch(e){}
            iziToast.error({ title: 'Error', message: 'Network error', position: 'topRight', timeout: 5000 });
          });
      });
    })();
  
  </script>
  <script>
    (function(){
      var viewUrl = '<?php echo site_url('projects'); ?>?embedded=1&mode=read';
      var editUrl = '<?php echo site_url('projects'); ?>?embedded=1&mode=update';
      var manageUrl = '<?php echo site_url('projects'); ?>?embedded=1&mode=delete';
      var iframeModalEl = document.getElementById('iframeModal');
      var iframe = document.getElementById('iframeModalFrame');
      var iframeLoading = document.getElementById('iframeLoading');
      var iframeTitle = document.getElementById('iframeModalTitle');

      function openIframe(title, url){
        iframeTitle.textContent = title;
        if(iframeLoading) iframeLoading.style.display = 'flex';
        iframe.src = url;
        var m = new bootstrap.Modal(iframeModalEl);
        m.show();
        iframe.addEventListener('load', function(){ if(iframeLoading) iframeLoading.style.display = 'none'; }, { once:true });
        iframeModalEl.addEventListener('hidden.bs.modal', function(){ iframe.src = ''; if(iframeLoading) iframeLoading.style.display = 'none'; }, { once:true });
      }

      var btnView = document.getElementById('openViewBtn');
      if(btnView) btnView.addEventListener('click', function(){ openIframe('View Projects', viewUrl); });

      var btnEdit = document.getElementById('openEditBtn');
      if(btnEdit) btnEdit.addEventListener('click', function(){ openIframe('Edit Projects', editUrl); });

      var btnManage = document.getElementById('openManageBtn');
      if(btnManage) btnManage.addEventListener('click', function(){ openIframe('Manage Projects', manageUrl); });
    })();
  
  </script>
  <script src="https://cdn.jsdelivr.net/npm/izitoast@1.4.0/dist/js/iziToast.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function(){
      <?php if (!empty($login_success)): ?>
        iziToast.success({
          title: 'Welcome',
          message: <?php echo json_encode($login_success); ?>,
          position: 'topRight',
          timeout: 4000
        });
      <?php endif; ?>
      <?php if ($this->session->flashdata('success')): ?>
        iziToast.success({
          title: 'Success',
          message: <?php echo json_encode($this->session->flashdata('success')); ?>,
          position: 'topRight',
          timeout: 3500
        });
      <?php endif; ?>
      <?php if ($this->session->flashdata('error')): ?>
        iziToast.error({
          title: 'Error',
          message: <?php echo json_encode($this->session->flashdata('error')); ?>,
          position: 'topRight',
          timeout: 5000
        });
      <?php endif; ?>
      // If a create/update/delete happened and we have a flash, ensure quickCreateModal is closed
      <?php if ($this->session->flashdata('success') || $this->session->flashdata('error')): ?>
        try { var qc = document.getElementById('quickCreateModal'); if(qc){ var _m = bootstrap.Modal.getOrCreateInstance(qc); _m.hide(); } } catch(e){}
      <?php endif; ?>
      // Initialize Bootstrap tooltips
      try {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (el) { return new bootstrap.Tooltip(el); });
      } catch (err){}
    });
  </script>
  <script>
    // Dashboard search filter for cards
    (function(){
      var input = document.getElementById('dashboardSearch');
      if(!input) return;
      var cards = Array.from(document.querySelectorAll('.dashboard-card'));
      input.addEventListener('input', function(){
        var q = (this.value || '').trim().toLowerCase();
        if(!q){ cards.forEach(function(c){ c.style.display = ''; }); return; }
        cards.forEach(function(card){
          var title = (card.querySelector('.card-title') || {textContent:''}).textContent.toLowerCase();
          var txt = (card.querySelector('.card-text') || {textContent:''}).textContent.toLowerCase();
          if(title.indexOf(q) !== -1 || txt.indexOf(q) !== -1) { card.style.display = ''; }
          else { card.style.display = 'none'; }
        });
      });
    })();
  </script>
  </body>
</html>
