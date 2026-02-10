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
  <div class="container py-4">
    <a href="<?php echo site_url('projects'); ?>" class="btn btn-link">Back</a>
    <h3><?php echo isset($project) ? 'Edit' : 'Add'; ?> Project</h3>
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
        <input name="tags" class="form-control" value="<?php echo isset($project['tags']) ? htmlspecialchars(implode(',', $project['tags'])) : ''; ?>">
      </div>
      <button class="btn btn-primary"><?php echo isset($project) ? 'Save' : 'Create'; ?></button>
    </form>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
