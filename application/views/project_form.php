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
        <!-- Tags removed per request -->
        <div class="mb-3">
          <label class="form-label">Framework/Language</label>
          <?php
            $typeOptions = [
              'php' => 'PHP',
              'javascript' => 'JS',
              'html_css' => 'HTML/CSS',
              'nodejs' => 'Node',
              'react' => 'React',
              'vue' => 'Vue',
              'angular' => 'Angular',
              'uiux' => 'UI',
              'cli' => 'CLI',
              'devops' => 'DevOps',
              'other' => 'Other'
            ];
            $selectedTypes = [];
            if (isset($project['type'])) {
              if (is_array($project['type'])) $selectedTypes = $project['type'];
              else $selectedTypes = array_map('trim', explode(',', $project['type']));
            }
          ?>
          <select name="type[]" class="form-control" multiple size="6">
            <?php foreach ($typeOptions as $val => $label): $sel = in_array($val, $selectedTypes) ? 'selected' : ''; ?>
              <option value="<?php echo htmlspecialchars($val); ?>" <?php echo $sel; ?>><?php echo htmlspecialchars($label); ?></option>
            <?php endforeach; ?>
          </select>
          <div class="form-text">Choose one or more frameworks or languages (hold Ctrl / Cmd to multi-select).</div>
        </div>
        <div class="d-flex gap-2">
          <button class="btn btn-primary"><?php echo isset($project) ? 'Save' : 'Create'; ?></button>
          <a href="<?php echo site_url('projects'); ?>" class="btn btn-outline-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast@1.4.0/dist/css/iziToast.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/izitoast@1.4.0/dist/js/iziToast.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function(){
      try{
        var flash_success = <?php echo json_encode($this->session->flashdata('success')); ?>;
        var flash_error = <?php echo json_encode($this->session->flashdata('error')); ?>;
        if (flash_success) {
          iziToast.success({ title: 'Success', message: flash_success, position: 'topRight', timeout: 3500 });
        }
        if (flash_error) {
          iziToast.error({ title: 'Error', message: flash_error, position: 'topRight', timeout: 5000 });
        }
      }catch(e){}
    });
  </script>
</body>
</html>
