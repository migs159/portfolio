<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?php echo isset($project) ? 'Edit' : 'Add'; ?> Project</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <style>
    :root{--primary:#6366f1;--muted:#6b7280;--surface:#fff}
    body{font-family:Inter,system-ui,Arial;background:linear-gradient(135deg,#fff,#f8fafc);margin:0}
    .wrap{max-width:760px;margin:2.5rem auto;padding:1rem}
    .card{background:var(--surface);padding:1.25rem;border-radius:12px;box-shadow:0 8px 30px rgba(2,6,23,.06)}
    .form-label{font-weight:600}
  </style>

  <div class="wrap">
    <div class="card">
      <a href="<?php echo site_url('projects'); ?>" class="btn btn-link">&larr; Back</a>
      <h3><?php echo isset($project) ? 'Edit' : 'Add'; ?> Project</h3>

      <?php if (!empty($this->session->flashdata('error'))): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($this->session->flashdata('error')); ?></div>
      <?php endif; ?>

      <form method="post">
        <div class="mb-3">
          <label class="form-label">Title</label>
          <input name="title" class="form-control" value="<?php echo isset($project['title']) ? htmlspecialchars($project['title']) : ''; ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Description</label>
          <textarea name="description" class="form-control" rows="4"><?php echo isset($project['description']) ? htmlspecialchars($project['description']) : ''; ?></textarea>
        </div>
        <div class="mb-3">
          <label class="form-label">Image URL</label>
          <input name="image" class="form-control" value="<?php echo isset($project['image']) ? htmlspecialchars($project['image']) : ''; ?>">
        </div>
        <div class="mb-3">
          <label class="form-label">Link URL</label>
          <input name="url" class="form-control" value="<?php echo isset($project['url']) ? htmlspecialchars($project['url']) : ''; ?>">
        </div>
        <div class="mb-3">
          <label class="form-label">Tags (comma separated)</label>
          <input name="tags" class="form-control" value="<?php echo isset($project['tags']) && is_array($project['tags']) ? htmlspecialchars(implode(',', $project['tags'])) : (isset($project['tags']) ? htmlspecialchars($project['tags']) : ''); ?>">
        </div>
        <div class="d-flex gap-2">
          <button class="btn btn-primary"><?php echo isset($project) ? 'Save' : 'Create'; ?></button>
          <a href="<?php echo site_url('projects'); ?>" class="btn btn-outline-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
