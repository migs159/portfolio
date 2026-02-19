<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,minimum-scale=1,user-scalable=no">
  <title>Manage Projects</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo htmlspecialchars(function_exists('base_url') ? base_url('assets/css/projects-custom.css') : '/assets/css/projects-custom.css'); ?>">
  <!-- replaced iziToast with SweetAlert2 -->
</head>
<?php $embedded = isset($_GET['embedded']) && $_GET['embedded'] == '1'; ?>
<?php // when embedded, we still show all projects so the iframe displays the full list ?>
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
          <div class="proj-card" data-id="<?php echo htmlspecialchars($p['id']); ?>">
            <?php if (!empty($p['image'])): ?>
              <img src="<?php echo htmlspecialchars($p['image']); ?>" alt="" class="proj-thumb">
            <?php else: ?>
              <div class="proj-thumb"></div>
            <?php endif; ?>
            <div class="proj-title"><?php echo htmlspecialchars($p['title'] ?? 'Untitled'); ?></div>
            <div class="text-muted proj-description"><?php echo htmlspecialchars($p['description'] ?? ''); ?></div>
            <?php if (!empty($p['tags']) && is_array($p['tags'])): ?>
              <div class="proj-tags-wrapper">
                <?php foreach ($p['tags'] as $t): ?>
                  <span class="tag"><?php echo htmlspecialchars($t); ?></span>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
            <div class="actions d-flex justify-content-between">
              <div>
                <?php if (isset($mode) && $mode === 'update'): ?>
                  <button type="button" class="btn-pill btn-edit btn-sm" aria-label="Edit project"> <i class="fas fa-edit"></i> Edit</button>
                <?php elseif (isset($mode) && $mode === 'delete'): ?>
                  <button type="button" class="btn-pill btn-del btn-sm" aria-label="Delete project"> <i class="fas fa-trash"></i> Delete</button>
                <?php endif; ?>
              </div>
              <div>
                <?php if (!empty($p['url']) && (isset($mode) && $mode === 'read')): ?>
                  <button type="button" class="btn-pill btn-view btn-sm" aria-label="View project"> <i class="fas fa-external-link-alt"></i> View</button>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <?php if (empty($embedded)): ?>
      <div class="mt-4 text-center"><a href="<?php echo site_url('portfolio'); ?>" class="btn btn-link">Back to portfolio</a></div>
    <?php endif; ?>
  </div>

  <!-- Modals -->
  <div class="modal fade" id="viewProjectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="viewProjectTitle">Project</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <img id="viewProjectImage" src="" alt="" class="view-project-image">
          <p id="viewProjectDescription" class="text-muted"></p>
          <div id="viewProjectTags" class="view-project-tags-wrapper"></div>
        </div>
        <div class="modal-footer">
          <a id="viewProjectLink" href="#" target="_blank" class="btn-pill btn-primary-custom">Open Link</a>
          <button type="button" class="btn-pill btn-ghost" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="editProjectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Project</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="editProjectForm" method="post" action="">
          <div class="modal-body">
            <div class="row g-3">
              <div class="col-12">
                <label class="form-label">Title</label>
                <input name="title" id="edit_title" class="form-control" required>
              </div>
              <div class="col-12">
                <label class="form-label">Description</label>
                <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
              </div>
              <div class="col-md-6">
                <label class="form-label">Image URL</label>
                <input name="image" id="edit_image" class="form-control">
              </div>
              <div class="col-md-6">
                <label class="form-label">Link URL</label>
                <input name="url" id="edit_url" class="form-control">
              </div>
              <div class="col-12">
                <label class="form-label">Framework/Language</label>
                <select name="type[]" id="edit_type" class="form-control" multiple size="6">
                  <option value="" disabled>-- Select framework / language --</option>
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
                </select>
                <div class="form-text">Choose one or more frameworks or languages (hold Ctrl / Cmd to multi-select).</div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn-pill btn-ghost" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn-pill btn-primary-custom">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="deleteProjectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Delete Project</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to delete <strong id="deleteProjectName"></strong>?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn-pill btn-ghost" data-bs-dismiss="modal">Cancel</button>
          <a id="confirmDeleteBtn" href="#" class="btn-pill btn-danger-custom">Delete</a>
        </div>
      </div>
    </div>
  </div>

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
