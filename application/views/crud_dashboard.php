<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="csrf-token" content="<?php echo $this->security->get_csrf_hash(); ?>">
  <title><?php echo isset($page_title) ? htmlspecialchars($page_title) : 'CRUD Dashboard'; ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
  <!-- using SweetAlert2 for toasts instead of iziToast -->
  <link rel="stylesheet" href="<?php echo htmlspecialchars(function_exists('base_url') ? base_url('assets/css/theme.css') : '/assets/css/theme.css'); ?>">
  <link rel="stylesheet" href="<?php echo htmlspecialchars(function_exists('base_url') ? base_url('assets/css/crud-dashboard-custom.css') : '/assets/css/crud-dashboard-custom.css'); ?>">
</head>
<body>
  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-light sticky-top">
    <div class="container">
         <span class="navbar-brand">
           <i class="fas fa-cube me-2"></i><span class="brand-text">CRUD </span>
         </span>
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
            <ul class="dropdown-menu dropdown-menu-end p-3" aria-labelledby="profileDropdown">
              <li>
                <a href="#" class="dropdown-item d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#viewAccountModal">
                  <div class="profile-initial me-2"><?php echo htmlspecialchars($__profile_initial); ?></div>
                  <div>
                    <div class="fw-bold">View Account</div>
                    <div class="text-muted text-muted-small">See account details</div>
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
  <!-- Top Header (redesigned as Event Management) -->
  <div class="page-header-top">
    <div class="container-main">
      <div class="page-header">
        <div class="header-content">
          <h1><i class="fas fa-cube me-2"></i>Project Management</h1>
          <p class="header-subtitle">Organize and manage your projects efficiently.</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Main Content -->
  <div class="container-main main-content">
    <!-- Search and table section modeled after the provided template -->
    <div class="search-filter-section">
      <div class="search-wrapper">
        <input type="search" id="projectSearch" class="form-control search-input" placeholder="Search projects by name...">
      </div>
      <div class="filter-actions">
        <button class="btn-pill btn-primary-custom" data-bs-toggle="modal" data-bs-target="#quickCreateModal"><i class="fas fa-plus me-2"></i>Create Project</button>
      </div>
    </div>
    
    <div class="table-section">

      <div class="table-section-wrapper">
        <div class="table-overflow-wrapper">
          <table class="table table-borderless">
            <thead>
              <tr>
                <th>Project Titles</th>
                <th>Image</th>
                <th>URL</th>
                <th>Created</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($events) && is_array($events)): ?>
                <?php foreach ($events as $e): ?>
                  <tr>
                    <td>
                      <div class="project-title-cell">
                        <?php echo htmlspecialchars(isset($e['title']) ? $e['title'] : 'Untitled Project'); ?>
                      </div>
                      <div class="project-desc-cell">
                        <?php echo htmlspecialchars(isset($e['description']) ? mb_substr($e['description'], 0, 60) : ''); ?>
                      </div>
                    </td>
                    <td>
                      <?php 
                        $img = isset($e['image']) ? $e['image'] : '';
                        $imgName = $img ? basename($img) : '-';
                      ?>
                      <span class="image-name-text"><?php echo htmlspecialchars($imgName); ?></span>
                    </td>
                    <td class="table-cell-muted">
                      <?php echo htmlspecialchars(isset($e['url']) ? $e['url'] : '-'); ?>
                    </td>
                    <td class="table-cell-muted">
                      <?php echo htmlspecialchars(isset($e['created_at']) ? date('M d, Y', strtotime($e['created_at'])) : ''); ?>
                    </td>
                    <td>
                      <?php if (isset($e['featured']) && $e['featured']): ?>
                        <span class="badge bg-primary text-white badge-featured"><i class="fas fa-star me-1"></i>Featured</span>
                      <?php else: ?>
                        <span class="badge bg-light text-muted badge-status">Active</span>
                      <?php endif; ?>
                    </td>
                    <?php
                      $pid = null;
                      if (isset($e['id'])) $pid = $e['id'];
                      elseif (isset($e['project_id'])) $pid = $e['project_id'];
                      $editUrl = $pid ? site_url('projects/edit/'.$pid.'?embedded=1') : '#';
                      $deleteUrl = $pid ? site_url('projects/delete/'.$pid) : '#';
                    ?>
                    <td>
                      <div class="action-buttons-flex">
                        <button class="btn btn-sm btn-outline-primary btn-view" data-id="<?php echo htmlspecialchars($pid); ?>" title="View"><i class="fas fa-eye"></i></button>
                        <button class="btn btn-sm btn-outline-secondary btn-edit" data-id="<?php echo htmlspecialchars($pid); ?>" data-edit-url="<?php echo htmlspecialchars($editUrl); ?>" title="Edit"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-sm btn-outline-danger btn-delete" data-id="<?php echo htmlspecialchars($pid); ?>" data-delete-url="<?php echo htmlspecialchars($deleteUrl); ?>" title="Delete"><i class="fas fa-trash"></i></button>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="5">
                    <div class="alert alert-info mb-0">No projects found. Use the <strong>Create Project</strong> button to add one.</div>
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
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
          <h5 class="modal-title">Create Project</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="post" action="<?php echo site_url('projects/create'); ?>" enctype="multipart/form-data">
          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
          <input type="hidden" name="return_to" value="crud">
          <div class="modal-body">
              <div class="form-row cols-2">
                <div>
                  <label class="form-label">Title</label>
                  <input name="title" class="form-control" required placeholder="Project title">
                  <div class="form-text">Give a concise, descriptive title.</div>
                </div>
                <!-- Tags removed per request -->
                <div class="modal-form-column">
                  <label class="form-label">Framework/Language</label>
                  <select name="type[]" class="form-control" multiple size="6">
                    <option value="" disabled>--Select framework / language-- </option>
                    <option value="php">PHP</option>
                    <option value="javascript">JavaScript</option>
                    <option value="html_css">HTML/CSS</option>
                    <option value="nodejs">Node.js</option>
                    <option value="react">React</option>
                    <option value="vue">Vue.js</option>
                    <option value="angular">Angular</option>
                    <option value="sql">SQL</option>
                    <option value="mysql">MySQL</option>
                    <option value="uiux">UI/UX</option>
                    <option value="cli">CLI / Tools</option>
                    <option value="devops">DevOps</option>
                  </select>
                  <div class="form-text">Choose one or more frameworks or languages (hold Ctrl / Cmd to multi-select).</div>
                </div>

                <div class="form-full-width">
                  <label class="form-label">Description</label>
                  <textarea name="description" class="form-control" rows="4" placeholder="Short description"></textarea>
                </div>

                <div>
                  <label class="form-label">Project Image</label>
                  <input type="file" name="image" class="form-control image-input" accept="image/png,image/jpeg,.png,.jpg,.jpeg">
                  <div class="form-text">Upload a PNG or JPG image (max 5MB)</div>
                  <div class="image-preview-container">
                    <img src="" alt="Preview" class="image-preview-img">
                  </div>
                </div>
                <div>
                  <label class="form-label">Link URL</label>
                  <input name="url" class="form-control" placeholder="https://...">
                </div>

                <div class="featured-checkbox-wrapper-full">
                  <input type="hidden" name="featured" value="0" id="featuredHidden">
                  <input type="checkbox" id="featuredCheckbox" class="form-check-input featured-checkbox-input">
                  <label for="featuredCheckbox" class="featured-checkbox-label-text">Mark as featured</label>
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
          <div class="view-account-modal-container">
            <div class="view-account-cover-image"></div>
            <div class="view-account-avatar-wrapper">
              <div class="profile-initial-large">
                <?php echo htmlspecialchars($__profile_initial); ?>
              </div>
            </div>
            <!-- Edit Profile button removed per request -->
          </div>
          <div class="view-account-content-wrapper">
            <div class="view-account-content-inner">
              <div class="view-account-header-layout">
                <div>
                  <div class="view-account-username-text">
                    <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'User'; ?>
                  </div>
                  <div class="view-account-member-since">Member since: <?php echo date('Y'); ?></div>
                </div>
                <div class="view-account-badge-container">
                  <span class="view-account-badge">Active</span>
                </div>
              </div>
              <hr class="view-account-divider">
              <div class="view-account-grid-container">
                <div>
                  <div class="view-account-item-label"><i class="fas fa-envelope me-2"></i>EMAIL</div>
                  <div class="view-account-item-value">
                    <?php echo !empty($user_email) ? htmlspecialchars($user_email) : 'Not set'; ?>
                  </div>
                </div>
                <div>
                  <div class="view-account-item-label"><i class="fas fa-id-badge me-2"></i>USERNAME</div>
                  <div class="view-account-item-value">
                    <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'User'; ?>
                  </div>
                </div>
                <div>
                  <div class="view-account-item-label"><i class="fas fa-phone me-2"></i>PHONE</div>
                  <div class="view-account-item-value">
                    <?php echo isset($_SESSION['phone']) ? htmlspecialchars($_SESSION['phone']) : 'Not set'; ?>
                  </div>
                </div>
                <div>
                  <div class="view-account-item-label"><i class="fas fa-clock me-2"></i>LAST LOGIN</div>
                  <div class="view-account-item-value">Just now</div>
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
        <div class="modal-body modal-body-custom-padding">
          <div class="iframe-embedded-header" id="iframeEmbeddedHeader">
            <div class="view-project-modal-header">
              <div class="view-project-modal-title">Projects</div>
              <div class="view-project-modal-subtitle"></div>
            </div>
          </div>
          <div class="iframe-loading-hidden" id="iframeLoading"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>
          <iframe id="iframeModalFrame" src="" class="iframe-wrap"></iframe>
        </div>
      </div>
    </div>
  </div>

  <!-- View Project Details Modal -->
  <div class="modal fade" id="viewProjectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-fullscreen-md-down">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="fas fa-info-circle me-2"></i>Project Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="view-project-items">
            <div>
              <label class="view-project-field-label">Title</label>
              <div id="viewTitle" class="view-project-field-value">-</div>
            </div>
            <div>
              <label class="view-project-field-label">Description</label>
              <div id="viewDescription" class="view-project-description">-</div>
            </div>
            <div class="view-project-grid-2col">
              <div>
                <label class="view-project-field-label">URL</label>
                <div id="viewUrl" class="view-project-field-value-break">-</div>
              </div>
              <div>
                <label class="view-project-field-label">Created</label>
                <div id="viewCreated" class="view-project-field-value">-</div>
              </div>
            </div>
            <div class="view-project-grid-2col">
              <div>
                <label class="view-project-field-label">Status</label>
                <div id="viewStatus" class="view-project-field-value">-</div>
              </div>
              <div>
                <label class="view-project-field-label">Featured</label>
                <div id="viewFeatured" class="view-project-field-value">-</div>
              </div>
            </div>
            <div>
              <label class="view-project-field-label">Image</label>
              <div id="viewImage" class="view-project-field-value-break">-</div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn-pill btn-ghost" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="<?php echo htmlspecialchars(function_exists('base_url') ? base_url('assets/js/crud_dashboard.js') : '/assets/js/crud_dashboard.js'); ?>" data-flash-success="<?php echo htmlspecialchars($this->session->flashdata('success') ?? '', ENT_QUOTES, 'UTF-8'); ?>" data-flash-error="<?php echo htmlspecialchars($this->session->flashdata('error') ?? '', ENT_QUOTES, 'UTF-8'); ?>"></script>
  </body>
</html>
