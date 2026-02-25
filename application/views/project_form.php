<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!doctype html>
<html lang="en">
<head>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,minimum-scale=1,user-scalable=no">
  <title><?php echo isset($project) ? 'Edit' : 'Add'; ?> Project</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo htmlspecialchars(function_exists('base_url') ? base_url('assets/css/project-form-custom.css') : '/assets/css/project-form-custom.css'); ?>">
</head>
<body>
  <div class="wrap">
    <div>

      <?php if (!empty($this->session->flashdata('error'))): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($this->session->flashdata('error')); ?></div>
      <?php endif; ?>

      <form id="project-form" method="post" enctype="multipart/form-data">
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
            if (function_exists('get_instance')) {
              $ci = &get_instance();
              $ci->load->view('partials/project_type_select', isset($project) ? ['project' => $project] : []);
            } else {
              if (isset($this) && method_exists($this->load, 'view')) {
                $this->load->view('partials/project_type_select', isset($project) ? ['project' => $project] : []);
              }
            }
          ?>
        </div>
        <div class="featured-checkbox-wrapper">
          <input type="hidden" name="featured" value="0">
          <input type="checkbox" id="featuredCheckbox" name="featured" value="1" class="form-check-input" <?php echo (isset($project['featured']) && intval($project['featured'])) ? 'checked' : ''; ?>>
          <label for="featuredCheckbox">Mark as featured</label>
        </div>
      </form>
      <div class="modal-footer">
        <?php if (empty($_GET['embedded'])): ?>
        <a href="<?php echo site_url('projects'); ?>" class="btn-pill btn-ghost">Cancel</a>
        <button type="submit" form="project-form" class="btn-pill btn-primary-custom"><?php echo isset($project) ? 'Save' : 'Create'; ?></button>
        <?php else: ?>
        <button type="button" class="btn-pill btn-ghost" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" form="project-form" class="btn-pill btn-primary-custom"><?php echo isset($project) ? 'Save' : 'Create'; ?></button>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="<?php echo htmlspecialchars(function_exists('base_url') ? base_url('assets/js/project_form.js') : '/assets/js/project_form.js'); ?>" data-flash-success="<?php echo htmlspecialchars($this->session->flashdata('success') ?? '', ENT_QUOTES, 'UTF-8'); ?>" data-flash-error="<?php echo htmlspecialchars($this->session->flashdata('error') ?? '', ENT_QUOTES, 'UTF-8'); ?>"></script>
</body>
</html>
