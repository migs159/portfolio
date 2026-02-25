<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!doctype html>
<html lang="en">
<head>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,minimum-scale=1,user-scalable=no">
  <title>Manage Projects</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo htmlspecialchars(function_exists('base_url') ? base_url('assets/css/projects-custom.css') : '/assets/css/projects-custom.css'); ?>">
  <!-- replaced iziToast with SweetAlert2 -->
</head>
<?php
if (function_exists('get_instance')) {
  $ci = &get_instance();
  $ci->load->view('partials/embedded_flag');
} else {
  if (isset($this) && method_exists($this->load, 'view')) {
    $this->load->view('partials/embedded_flag');
  }
}
// when embedded, we still show all projects so the iframe displays the full list
?>
<body>
<div class="container py-4">
  <?php if (empty($embedded)): ?>
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Projects</h3>
      <div>
        <a href="<?php echo site_url('projects/create'); ?>" class="btn btn-sm btn-primary">Add Project</a>
        <a href="<?php echo site_url('auth/logout'); ?>" class="btn btn-sm btn-outline-secondary">Logout</a>
      </div>
  </div>
  <?php endif; ?>
  <div class="wrap">
    <?php if (empty($embedded)): ?>
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3>Projects</h3>
      <div>
        <a href="<?php echo site_url('projects/create'); ?>" class="btn btn-sm btn-primary">Add Project</a>
        <a href="<?php echo site_url('crud'); ?>" class="btn btn-sm btn-outline-secondary">Dashboard</a>
      </div>
    </div>
    <?php endif; ?>

    <!-- flashdata will be shown as SweetAlert2 toasts -->

    <?php if (empty($projects)): ?>
      <div class="alert alert-info">No projects yet.</div>
    <?php else: ?>
      <div class="card-grid<?php echo $embedded ? ' embedded' : ''; ?>">
        <?php foreach ($projects as $p): ?>
          <div>
            <?php
              $ci = &get_instance();
              $ci->load->view('partials/project_card', ['project' => $p, 'featured' => false, 'mode' => $mode ?? null]);
            ?>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <?php if (empty($embedded)): ?>
      <div class="mt-4 text-center"><a href="<?php echo site_url('portfolio'); ?>" class="btn btn-link">Back to portfolio</a></div>
    <?php endif; ?>
  </div>

  <!-- Modals (moved to partials) -->
  <?php if (function_exists('get_instance')) { $ci = &get_instance(); $ci->load->view('partials/modal_view_project_public'); $ci->load->view('partials/modal_edit_project'); $ci->load->view('partials/modal_delete_project'); } else { if (isset($this) && method_exists($this->load,'view')) { $this->load->view('partials/modal_view_project_public'); $this->load->view('partials/modal_edit_project'); $this->load->view('partials/modal_delete_project'); } } ?>

  <!-- Meta tags for passing PHP data to external JavaScript -->
  <meta id="projects-data"
    data-projects="<?php echo htmlspecialchars(json_encode(array_values($projects)), ENT_QUOTES, 'UTF-8'); ?>"
    data-base-url="<?php echo htmlspecialchars(site_url(), ENT_QUOTES, 'UTF-8'); ?>"
    data-flash-success="<?php echo htmlspecialchars($this->session->flashdata('success') ?? '', ENT_QUOTES, 'UTF-8'); ?>"
    data-flash-error="<?php echo htmlspecialchars($this->session->flashdata('error') ?? '', ENT_QUOTES, 'UTF-8'); ?>"
    data-mode="<?php echo htmlspecialchars(isset($mode) ? $mode : 'read', ENT_QUOTES, 'UTF-8'); ?>">

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="<?php echo htmlspecialchars(site_url('assets/js/projects_index.js'), ENT_QUOTES, 'UTF-8'); ?>"></script>
</body>
</html>
