<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Manage Projects</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Projects</h3>
    <div>
      <a href="<?php echo site_url('projects/create'); ?>" class="btn btn-sm btn-primary">Add Project</a>
      <a href="<?php echo site_url('auth/logout'); ?>" class="btn btn-sm btn-outline-secondary">Logout</a>
    </div>
  </div>

  <?php if (empty($projects)): ?>
    <div class="alert alert-info">No projects yet.</div>
  <?php else: ?>
    <div class="list-group">
      <?php foreach ($projects as $p): ?>
        <div class="list-group-item">
          <div class="d-flex w-100 justify-content-between">
            <h5 class="mb-1"><?php echo htmlspecialchars($p['title'] ?? 'Untitled'); ?></h5>
            <small>
              <a href="<?php echo site_url('projects/edit/'.$p['id']); ?>" class="btn btn-sm btn-outline-primary">Edit</a>
              <a href="<?php echo site_url('projects/delete/'.$p['id']); ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete?')">Delete</a>
            </small>
          </div>
          <p class="mb-1 text-muted"><?php echo htmlspecialchars($p['description'] ?? ''); ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
  <div class="mt-4">
    <a href="<?php echo site_url('portfolio'); ?>" class="btn btn-link">Back to portfolio</a>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
