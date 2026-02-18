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
                    <option value="">--Select framework / language-- </option>
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
                    <option value="other">Other</option>
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
  <script>
    // View and Edit project functions
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

    function handleViewProject(id){
      if(!id){ Swal.fire({ icon: 'error', title: 'Error', text: 'Project ID not available', confirmButtonColor: '#003d99' }); return; }
      
      var projectsBase = '<?php echo site_url('projects'); ?>';
      var url = projectsBase + '/get/' + id;
      fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(function(res){ return res.json(); })
        .then(function(json){
          if(json && json.project){
            var p = json.project;
            document.getElementById('viewTitle').textContent = p.title || '-';
            document.getElementById('viewDescription').textContent = p.description || '-';
            document.getElementById('viewUrl').textContent = p.url || '-';
            document.getElementById('viewCreated').textContent = p.created_at ? p.created_at.split(' ')[0] : '-';
            document.getElementById('viewStatus').textContent = p.status ? 'Active' : 'Inactive';
            document.getElementById('viewFeatured').innerHTML = p.featured ? '<span class="badge bg-primary text-white badge-featured"><i class="fas fa-star me-1"></i>Featured</span>' : 'No';
            document.getElementById('viewImage').textContent = p.image || '-';
            
            var modal = new bootstrap.Modal(document.getElementById('viewProjectModal'));
            modal.show();
          } else {
            Swal.fire({ icon: 'error', title: 'Error', text: 'Unable to load project details', confirmButtonColor: '#003d99' });
          }
        }).catch(function(){
          Swal.fire({ icon: 'error', title: 'Error', text: 'Network error loading project', confirmButtonColor: '#003d99' });
        });
    }

    function handleEditProject(url, id){
      if(!url && !id){
        Swal.fire({ icon: 'error', title: 'Error', text: 'Edit URL not available', confirmButtonColor: '#003d99' });
        return;
      }
      if(!url && id){
        var projectsBase = '<?php echo site_url('projects'); ?>';
        url = projectsBase + '/edit/' + id + '?embedded=1';
      }
      openIframe('Edit Project', url);
    }

    function handleDeleteProject(url, id){
      if(!url && !id){
        Swal.fire({ icon: 'error', title: 'Error', text: 'Delete URL not available', confirmButtonColor: '#003d99' });
        return;
      }
      if(!url && id){
        var projectsBase = '<?php echo site_url('projects'); ?>';
        url = projectsBase + '/delete/' + id;
      }

      Swal.fire({
        title: 'Delete project?',
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete',
        cancelButtonText: 'Cancel'
      }).then(function(result){
        if(!result.isConfirmed) return;
        // Build FormData with CSRF token
        var fd = new FormData();
        var tokenName = '<?php echo $this->security->get_csrf_token_name(); ?>';
        var token = getCsrf();
        if(tokenName && token) fd.append(tokenName, token);
        
        fetch(url, { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
          .then(function(res){
            console.log('Delete response status:', res.status);
            return res.text().then(function(text){
              try {
                return JSON.parse(text);
              } catch(e) {
                console.error('Response not valid JSON:', text);
                throw new Error('Invalid JSON response: ' + text);
              }
            });
          })
          .then(function(json){
            console.log('Delete response:', json);
            if(json && json.success){
              var deleteBtn = document.querySelector('[data-id="'+ id +'"].btn-delete');
              if(deleteBtn){
                var row = deleteBtn.closest('tr');
                if(row) row.parentNode.removeChild(row);
              }
              Swal.fire({ icon: 'success', title: 'Success', text: json.message || 'Deleted', confirmButtonColor: '#003d99', timer: 2000 });
            } else {
              Swal.fire({ icon: 'error', title: 'Error', text: (json && json.message) || 'Delete failed', confirmButtonColor: '#003d99' });
            }
          })
          .catch(function(err){
            console.error('Delete error:', err);
            Swal.fire({ icon: 'error', title: 'Delete Error', text: 'Failed to delete: ' + (err.message || 'Unknown error'), confirmButtonColor: '#003d99' });
          });
      });
    }

    function getCsrf(){
      var m = document.querySelector('meta[name="csrf-token"]');
      return m ? m.getAttribute('content') : null;
    }
  </script>
  <script>
    // Handle featured checkbox - update hidden field when checkbox value changes
    (function(){
      var modal = document.getElementById('quickCreateModal');
      if(!modal) return;
      var featuredCheckbox = modal.querySelector('#featuredCheckbox');
      var featuredHidden = modal.querySelector('#featuredHidden');
      if(featuredCheckbox && featuredHidden){
        featuredCheckbox.addEventListener('change', function(){
          featuredHidden.value = this.checked ? '1' : '0';
          console.log('Featured checkbox changed:', this.checked, 'Hidden value:', featuredHidden.value);
        });
      }
    })();

    // AJAX submit for quick-create so we can show a Toast and close the modal without a full redirect
    (function(){
      var modal = document.getElementById('quickCreateModal');
      if(!modal) return;
      var form = modal.querySelector('form');
      if(!form) return;
      form.addEventListener('submit', function(ev){
        ev.preventDefault();
        var fd = new FormData(form);
        console.log('Form submission started', { action: form.action, formData: Array.from(fd.entries()) });
        fetch(form.action, { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
          .then(function(res){
            console.log('Fetch response received', { status: res.status, statusText: res.statusText, contentType: res.headers.get('content-type') });
            return res.text().then(function(text){
              console.log('Response body (first 500 chars):', text.substring(0, 500));
              try {
                return JSON.parse(text);
              } catch(parseErr) {
                console.error('JSON parse error:', parseErr);
                console.error('Raw response:', text);
                throw new Error('Server returned non-JSON response');
              }
            });
          })
          .then(function(json){
            console.log('Parsed JSON response:', json);
            try{ bootstrap.Modal.getOrCreateInstance(modal).hide(); }catch(e){}
            if (json && json.success) {
              Swal.fire({
                icon: 'success',
                title: 'Project Created',
                text: json.message || 'Your project has been created successfully',
                confirmButtonColor: '#003d99',
                timer: 2500,
                didClose: function(){
                  location.reload();
                }
              });

              // Prepend the newly created project into the events table.
              try{
                var tbody = document.querySelector('.table-section table tbody');
                if(tbody){
                  // If server returned HTML for the row, use it
                  if(json.project_html){
                    // Remove empty-state row if present
                    var emptyAlert = tbody.querySelector('tr td .alert');
                    if(emptyAlert) tbody.innerHTML = '';
                    tbody.insertAdjacentHTML('afterbegin', json.project_html);
                  } else {
                    // Build a fallback row from returned project data or from form values
                    var p = json.project || {};
                    var title = p.title || fd.get('title') || 'Untitled Project';
                    var description = p.description || fd.get('description') || '';
                    if(description && description.length > 60) description = description.substring(0, 60);
                    var url = p.url || fd.get('url') || '-';
                    var created_at = p.created_at || new Date().toISOString().split('T')[0];
                    var status = p.status ? 'Active' : 'Inactive';
                    var featured = p.featured ? 1 : 0;
                    var featuredBadge = featured ? '<span class="badge bg-primary text-white badge-featured"><i class="fas fa-star me-1"></i>Featured</span>' : '';

                    function escapeHtml(s){ return (''+s).replace(/[&<>"']/g, function(c){ return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":"&#39;"}[c]; }); }

                    var row =
                      '<tr>' +
                        '<td>' +
                          '<div class="project-title-cell">'+ escapeHtml(title) +'</div>' +
                          '<div class="project-desc-cell">'+ escapeHtml(description) +'</div>' +
                        '</td>' +
                        '<td class="table-cell-muted">-</td>' +
                        '<td class="table-cell-muted">'+ escapeHtml(url) +'</td>' +
                        '<td class="table-cell-muted">'+ escapeHtml(created_at) +'</td>' +
                        '<td><span class="badge bg-light text-muted badge-status">'+ escapeHtml(status) +'</span>' + featuredBadge + '</td>' +
                        '<td>' +
                          '<div class="action-buttons-flex">' +
                            '<button class="btn btn-sm btn-outline-primary btn-view" data-id="'+ escapeHtml(p.id || '') +'" title="View"><i class="fas fa-eye"></i></button>' +
                            '<button class="btn btn-sm btn-outline-secondary btn-edit" data-id="'+ escapeHtml(p.id || '') +'" title="Edit"><i class="fas fa-edit"></i></button>' +
                            '<button class="btn btn-sm btn-outline-danger btn-delete" data-id="'+ escapeHtml(p.id || '') +'" title="Delete"><i class="fas fa-trash"></i></button>' +
                          '</div>' +
                        '</td>' +
                      '</tr>';

                    // Remove empty-state row if present
                    var emptyAlert = tbody.querySelector('tr td .alert');
                    if(emptyAlert) tbody.innerHTML = '';
                    tbody.insertAdjacentHTML('afterbegin', row);
                  }

                  // Update dashboard counts (increment Total Events and Upcoming if applicable)
                  try{
                    function updateCount(cardTitle, delta){
                      var cards = Array.from(document.querySelectorAll('.dashboard-card'));
                      cards.forEach(function(c){
                        var t = (c.querySelector('.card-title') || {textContent:''}).textContent.trim();
                        if(t === cardTitle){
                          var el = c.querySelector('.card-text');
                          if(el){
                            var n = parseInt(el.textContent.replace(/[^0-9]/g,'')) || 0;
                            el.textContent = n + delta;
                          }
                        }
                      });
                    }
                    updateCount('Total Projects', 1);
                    // If the event is upcoming (simple heuristic: datetime in future), increment Upcoming
                    try{
                      var projDate = new Date((p && p.datetime) || fd.get('datetime'));
                      if(!isNaN(projDate) && projDate > new Date()) updateCount('Upcoming Projects', 1);
                    }catch(e){}
                  }catch(e){/* ignore count update errors */}
                }
              }catch(e){console.error(e);} 
            } else {
              Swal.fire({ icon: 'error', title: 'Project Creation Failed', text: (json && json.message) || 'Unable to create project. Please try again.', confirmButtonColor: '#003d99' });
            }
          }).catch(function(err){
            console.error('Create request caught error:', err.message, err);
            try{ bootstrap.Modal.getOrCreateInstance(modal).hide(); }catch(e){}
            Swal.fire({ icon: 'error', title: 'Network Error', text: 'Failed to connect: ' + (err.message || 'Unknown error'), confirmButtonColor: '#003d99' });
          });
      });
    })();
    
    // Auto-preview for image input in quick-create form
    (function(){
      var modal = document.getElementById('quickCreateModal');
      if(!modal) return;
      var imageInput = modal.querySelector('.image-input');
      if(!imageInput) return;
      imageInput.addEventListener('change', function(e){
        var file = this.files[0];
        if(file){
          var reader = new FileReader();
          reader.onload = function(event){
            var preview = imageInput.closest('div').querySelector('.image-preview');
            if(preview){
              var img = preview.querySelector('img');
              if(img){
                img.src = event.target.result;
                preview.style.display = 'block';
              }
            }
          };
          reader.readAsDataURL(file);
        }
      });
      // Clear preview when modal closes
      modal.addEventListener('hidden.bs.modal', function(){
        var preview = imageInput.closest('div').querySelector('.image-preview');
        if(preview){
          preview.style.display = 'none';
          var img = preview.querySelector('img');
          if(img) img.src = '';
        }
        imageInput.value = '';
      });
    })();
  </script>
  <script>
    (function(){
      var viewUrl = '<?php echo site_url('projects'); ?>?embedded=1&mode=read';
      var editUrl = '<?php echo site_url('projects'); ?>?embedded=1&mode=update';
      var manageUrl = '<?php echo site_url('projects'); ?>?embedded=1&mode=delete';

      var btnView = document.getElementById('openViewBtn');
      if(btnView) btnView.addEventListener('click', function(){ openIframe('View Projects', viewUrl); });

      var btnEdit = document.getElementById('openEditBtn');
      if(btnEdit) btnEdit.addEventListener('click', function(){ openIframe('Edit Projects', editUrl); });

      var btnManage = document.getElementById('openManageBtn');
      if(btnManage) btnManage.addEventListener('click', function(){ openIframe('Manage Projects', manageUrl); });
    })();
  
  </script>
  <script>
    // Delegated handlers for View, Edit and Delete buttons in the projects table
    (function(){
      var tbody = document.querySelector('.table-section table tbody');
      if(!tbody) return;

      tbody.addEventListener('click', function(ev){
        var viewBtn = ev.target.closest && ev.target.closest('.btn-view');
        if(viewBtn){
          var id = viewBtn.getAttribute('data-id');
          handleViewProject(id);
          return;
        }

        var editBtn = ev.target.closest && ev.target.closest('.btn-edit');
        if(editBtn){
          var url = editBtn.getAttribute('data-edit-url');
          var id = editBtn.getAttribute('data-id');
          handleEditProject(url, id);
          return;
        }

        var delBtn = ev.target.closest && ev.target.closest('.btn-delete');
        if(delBtn){
          var id = delBtn.getAttribute('data-id');
          var url = delBtn.getAttribute('data-delete-url');
          handleDeleteProject(url, id);
          return;
        }
      });
    })();
  </script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function(){
      <?php if (!empty($login_success)): ?>
        Swal.fire({
          icon: 'success',
          title: 'Success',
          text: <?php echo json_encode($login_success); ?>,
          confirmButtonColor: '#003d99',
          timer: 3000
        });
      <?php endif; ?>
      <?php if ($this->session->flashdata('success')): ?>
        Swal.fire({
          icon: 'success',
          title: 'Success',
          text: <?php echo json_encode($this->session->flashdata('success')); ?>,
          confirmButtonColor: '#003d99',
          timer: 3000
        });
      <?php endif; ?>
      <?php if ($this->session->flashdata('error')): ?>
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: <?php echo json_encode($this->session->flashdata('error')); ?>,
          confirmButtonColor: '#003d99'
        });
      <?php endif; ?>
      // If a create/update/delete happened and we have a flash, ensure quickCreateModal is closed
      <?php if ($this->session->flashdata('success') || $this->session->flashdata('error')): ?>
        try { var qc = document.getElementById('quickCreateModal'); if(qc){ var _m = bootstrap.Modal.getOrCreateInstance(qc); _m.hide(); } } catch(e){}
      <?php endif; ?>
      // Initialize Bootstrap tooltips
      try {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (el) { return new bootstrap.Tooltip(el); });
      } catch (err){}
    });
  </script>
  <script>
    // Search filter for projects table
    (function(){
      var input = document.getElementById('projectSearch');
      if(!input) return;
      var rows = Array.from(document.querySelectorAll('table tbody tr'));
      input.addEventListener('input', function(){
        var q = (this.value || '').trim().toLowerCase();
        if(!q){ rows.forEach(function(r){ r.style.display = ''; }); return; }
        rows.forEach(function(row){
          var text = row.textContent.toLowerCase();
          if(text.indexOf(q) !== -1) { row.style.display = ''; }
          else { row.style.display = 'none'; }
        });
      });
    })();
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
