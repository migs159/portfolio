<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?php echo isset($project) ? 'Edit' : 'Add'; ?> Project</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo htmlspecialchars(function_exists('base_url') ? base_url('assets/css/project-form-custom.css') : '/assets/css/project-form-custom.css'); ?>">
</head>
<body>
  <div class="wrap">
    <div class="card">
      <h3><?php echo isset($project) ? 'Edit' : 'Add'; ?> Project</h3>

      <?php if (!empty($this->session->flashdata('error'))): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($this->session->flashdata('error')); ?></div>
      <?php endif; ?>

      <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
        <div class="mb-3">
          <label class="form-label">Title</label>
          <input name="title" class="form-control" value="<?php echo isset($project['title']) ? htmlspecialchars($project['title']) : ''; ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Description</label>
          <textarea name="description" class="form-control" rows="4"><?php echo isset($project['description']) ? htmlspecialchars($project['description']) : ''; ?></textarea>
        </div>
        <div class="mb-3">
          <label class="form-label">Project Image</label>
          <input type="file" name="image" class="form-control image-input" accept="image/png,image/jpeg,.png,.jpg,.jpeg">
          <div class="form-text">Upload a PNG or JPG image (max 5MB). Leave blank to keep current image.</div>
          <div class="image-preview<?php echo (!isset($project['image']) || empty($project['image'])) ? ' d-none' : ''; ?>">
            <?php if (isset($project['image']) && !empty($project['image'])): ?>
              <small class="text-muted">Current image:</small>
            <?php else: ?>
              <small class="text-muted">Preview:</small>
            <?php endif; ?>
            <img src="<?php echo (isset($project['image']) && !empty($project['image'])) ? base_url($project['image']) : ''; ?>" alt="Preview">
          </div>
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
              'sql' => 'SQL',
              'mysql' => 'MySQL',
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
        <div class="featured-checkbox-wrapper">
          <input type="hidden" name="featured" value="0">
          <input type="checkbox" id="featuredCheckbox" name="featured" value="1" class="form-check-input" <?php echo (isset($project['featured']) && intval($project['featured'])) ? 'checked' : ''; ?>>
          <label for="featuredCheckbox">Mark as featured</label>
        </div>
        <div class="d-flex gap-2">
          <button class="btn btn-primary"><?php echo isset($project) ? 'Save' : 'Create'; ?></button>
          <a href="<?php echo site_url('projects'); ?>" class="btn btn-outline-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="<?php echo htmlspecialchars(function_exists('base_url') ? base_url('assets/js/project_form.js') : '/assets/js/project_form.js'); ?>" data-flash-success="<?php echo htmlspecialchars($this->session->flashdata('success') ?? '', ENT_QUOTES, 'UTF-8'); ?>" data-flash-error="<?php echo htmlspecialchars($this->session->flashdata('error') ?? '', ENT_QUOTES, 'UTF-8'); ?>"></script>
</body>
</html>
