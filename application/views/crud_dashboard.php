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
  <style>
    :root {
      --primary: #4f46e5;
      --primary-dark: #3730a3;
      --secondary: #ec4899;
      --accent: #0f172a;
      --muted: #6b7280;
      --light-bg: #f6f8fb;
      --surface: #ffffff;
      --glass: rgba(79,70,229,0.06);
      --card-shadow: 0 12px 40px rgba(2,6,23,0.06);
      --radius: 12px;
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
      padding: 1.5rem 1.5rem;
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
      color: var(--accent);
      margin: 0 0 0.25rem 0;
    }

    .page-header .subtitle {
      color: var(--muted);
      font-size: 0.95rem;
      margin: 0;
    }

    .header-actions { display:flex; align-items:center; gap:0.75rem; }
    .search-input { border-radius: 999px; padding:.55rem .9rem; border:1px solid rgba(15,23,42,0.06); min-width:220px; box-shadow:none }
    .search-input:focus{ outline:none; box-shadow:0 8px 30px rgba(79,70,229,0.06); border-color:rgba(79,70,229,0.14) }

    /* Dashboard Cards */
    .dashboard-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
      gap: 1.25rem;
      margin-bottom: 2rem;
      align-items: start;
    }

    .dashboard-card {
      background: linear-gradient(180deg, var(--surface) 0%, #fbfdff 100%);
      border-radius: var(--radius);
      padding: 1.25rem;
      box-shadow: var(--card-shadow);
      transition: transform 0.22s cubic-bezier(.2,.9,.3,1), box-shadow 0.22s ease;
      border: 1px solid rgba(15,23,42,0.04);
      display:flex;
      flex-direction:column;
      gap:1rem;
      min-height:220px;
    }

    .dashboard-card:hover {
      transform: translateY(-8px) scale(1.01);
      box-shadow: 0 22px 60px rgba(2,6,23,0.12);
      border-color: rgba(79,70,229,0.12);
    }

    .card-icon {
      width: 54px;
      height: 54px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, rgba(99, 102, 241, 0.08), rgba(236, 72, 153, 0.06));
      border-radius: 10px;
      font-size: 1.25rem;
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
    .btn-primary-custom{background:linear-gradient(90deg,var(--primary),var(--primary-dark));color:#fff;border:0;box-shadow:0 8px 28px rgba(79,70,229,0.12)}
    .btn-primary-custom:hover{transform:translateY(-2px);box-shadow:0 10px 30px rgba(79,70,229,0.16)}
    .btn-ghost{background:transparent;border:1px solid rgba(79,70,229,0.12);color:var(--primary)}
    .btn-ghost:hover{background:rgba(79,70,229,0.04)}
    .btn-danger-custom{background:linear-gradient(90deg,#ef4444,#dc2626);color:#fff;border:0}
    .btn-danger-custom:hover{transform:translateY(-2px);box-shadow:0 10px 30px rgba(239,68,68,0.14)}
    .card-action{margin-top:auto;display:flex;gap:.5rem}
    .card-action .btn-pill{display:inline-flex;align-items:center;gap:.5rem}
    .card-action .fa-arrow-right{opacity:.95}
    .btn-pill:focus{outline:none;box-shadow:0 10px 30px rgba(71,58,224,0.14)}

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
    .modal-body { padding: 1.25rem; background: linear-gradient(180deg, #fbfbfd 0%, #f6f7fb 100%); }
    .modal-footer { padding: 0.9rem 1.25rem; border-top: 0; display:flex; gap:.5rem; justify-content:flex-end; background:transparent }
    .modal .form-label { font-weight:700; color:var(--accent); font-size:.85rem }
    .modal .form-control { border-radius: 10px; border:1px solid rgba(15,23,42,0.06); padding:.7rem .9rem; box-shadow:none }
    .modal .form-control:focus{box-shadow:0 8px 30px rgba(79,70,229,0.06);border-color:rgba(79,70,229,0.16);outline:none}
    .modal .form-text { font-size:0.85rem;color:var(--muted);margin-top:.35rem }
    .modal .btn-pill { box-shadow: 0 8px 28px rgba(2,6,23,0.06); padding:.6rem 1rem }
    .modal .btn-ghost { background: transparent; border: 1px solid rgba(15,23,42,0.06); color:var(--accent); }
    .modal .close { color: rgba(255,255,255,0.95); opacity: .95; }
    .modal .form-row { display:grid; grid-template-columns:1fr; gap:.8rem }
    @media(min-width:768px){ .modal .form-row.cols-2{ grid-template-columns:1fr 1fr } }

    /* iframe modal specifics */
    .iframe-wrap{width:100%;height:72vh;border-radius:10px;border:1px solid rgba(15,23,42,0.04);background:#fff;box-shadow:0 8px 30px rgba(15,23,42,0.06)}
    .iframe-loading{display:flex;align-items:center;justify-content:center;height:72vh}

    /* Table Section */
    .table-section {
      background: linear-gradient(180deg, #ffffff 0%, #fbfbff 100%);
      border-radius: 16px;
      padding: 2rem;
      box-shadow: 0 10px 30px rgba(15,23,42,0.04);
      border: 1px solid rgba(79,70,229,0.04);
    }

    .table-section h3 {
      font-family: 'Poppins', sans-serif;
      font-weight: 800;
      color: var(--accent);
      margin-bottom: 1.5rem;
      display:flex;align-items:center;gap:.6rem
    }

    .info-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 1.25rem;
    }

    .info-item {
      padding: 1.25rem;
      background: linear-gradient(90deg, rgba(79,70,229,0.03), rgba(236,72,153,0.02));
      border-radius: 12px;
      box-shadow: 0 6px 18px rgba(15,23,42,0.04);
      border: 1px solid rgba(15,23,42,0.03);
    }

    .info-label {
      font-size: 0.78rem;
      color: var(--muted);
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-bottom: 0.5rem;
    }

    .info-value {
      font-size: 1.25rem;
      font-weight: 800;
      color: var(--accent);
    }

    @media (max-width: 640px) {
      .info-grid { grid-template-columns: 1fr; }
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
  <!-- Top Header -->
  <div class="page-header-top" style="background:transparent;border-bottom:1px solid rgba(15,23,42,0.02);">
    <div class="container-main" style="padding-top:0;padding-bottom:0;">
      <div class="page-header">
        <div>
          <h1><i class="fas fa-cogs me-2"></i>CRUD Management</h1>
          <p class="subtitle">Welcome, <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'User'; ?> — manage your data</p>
        </div>
        <div class="header-actions">
          <input id="dashboardSearch" class="search-input" placeholder="Search projects or cards..." aria-label="Search">
          <button type="button" class="btn-pill btn-primary-custom" data-bs-toggle="modal" data-bs-target="#quickCreateModal"><i class="fas fa-plus-circle"></i> Add New</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Main Content -->
  <div class="container-main">
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

  <!-- Quick Create Modal -->
  <div class="modal fade" id="quickCreateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Quick Create Project</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="post" action="<?php echo site_url('projects/create'); ?>">
          <div class="modal-body">
              <div class="form-row cols-2">
                <div>
                  <label class="form-label">Title</label>
                  <input name="title" class="form-control" required placeholder="Project title">
                  <div class="form-text">Give a concise, descriptive title.</div>
                </div>
                <div>
                  <label class="form-label">Tags</label>
                  <input name="tags" class="form-control" placeholder="tag1, tag2">
                  <div class="form-text">Separate tags with commas.</div>
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

  <!-- Reusable iframe modal for View/Edit/Manage -->
  <div class="modal fade" id="iframeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="iframeModalTitle"><i class="fas fa-table me-2"></i><span id="iframeModalLabel">Manage</span></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" style="padding:1rem;">
          <div class="iframe-loading" id="iframeLoading" style="display:none"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>
          <iframe id="iframeModalFrame" src="" class="iframe-wrap"></iframe>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    (function(){
      var viewUrl = '<?php echo site_url('projects'); ?>?embedded=1';
      var editUrl = '<?php echo site_url('projects'); ?>?embedded=1';
      var manageUrl = '<?php echo site_url('projects'); ?>?embedded=1';
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
