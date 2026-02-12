<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Manage Projects</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast@1.4.0/dist/css/iziToast.min.css">
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
  <style>
    :root{--primary:#4f46e5;--primary-dark:#3730a3;--accent:#0f172a;--muted:#6b7280;--light-bg:#f8fafc;--surface:#ffffff}
    body{font-family:Inter,system-ui,Arial;margin:0;background:linear-gradient(135deg,#fff,var(--light-bg))}
    .wrap{max-width:1100px;margin:2.5rem auto;padding:1rem}
    .card-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:1.25rem}
    .proj-card{background:var(--surface);border-radius:14px;padding:1.15rem;box-shadow:0 10px 30px rgba(2,6,23,.06);border:1px solid rgba(79,70,229,0.06);display:flex;flex-direction:column}
    .proj-thumb{width:100%;height:160px;background:#f3f4f6;border-radius:10px;object-fit:cover}
    
    .proj-title{font-weight:800;color:var(--accent);margin-top:.6rem;font-size:1.05rem}
    .tag{display:inline-block;background:#eef2ff;color:var(--primary);padding:.25rem .55rem;border-radius:999px;font-size:.78rem;margin-right:.35rem}
    .actions{margin-top:.85rem}

    /* Button system */
    .btn-pill{border-radius:999px;padding:.45rem .8rem;font-weight:700;display:inline-flex;align-items:center;gap:.5rem;transition:transform .12s ease,box-shadow .12s ease}
    .btn-primary-custom{background:linear-gradient(90deg,var(--primary),var(--primary-dark));color:#fff;border:0}
    .btn-primary-custom:hover{transform:translateY(-2px);box-shadow:0 8px 28px rgba(79,70,229,0.16)}
    .btn-ghost{background:transparent;border:1px solid rgba(79,70,229,0.12);color:var(--primary)}
    .btn-ghost:hover{background:rgba(79,70,229,0.04)}
    .btn-danger-custom{background:linear-gradient(90deg,#ef4444,#dc2626);color:#fff;border:0}
    .btn-danger-custom:hover{transform:translateY(-2px);box-shadow:0 8px 28px rgba(239,68,68,0.14)}
    /* Use the same primary gradient for view/edit actions to keep colors strict */
    .btn-edit{display:inline-flex;align-items:center;gap:.5rem;border:0;padding:.4rem .7rem;border-radius:8px;background:linear-gradient(135deg,var(--primary),var(--primary-dark));color:#fff}
    .btn-del{display:inline-flex;align-items:center;gap:.5rem;padding:.4rem .7rem;border-radius:8px;background:linear-gradient(90deg,#ef4444,#dc2626);color:#fff;border:0}
    .btn-view{display:inline-flex;align-items:center;gap:.4rem;padding:.38rem .65rem;border-radius:8px;background:linear-gradient(135deg,var(--primary),var(--primary-dark));color:#fff;border:0}

    /* Modal visuals (match dashboard) */
    .modal-content { border-radius: 14px; overflow: hidden; }
    .modal-header { background: linear-gradient(90deg,var(--primary),var(--primary-dark)); color: #fff; border-bottom: 0; padding: 1rem 1.25rem; }
    .modal-title { font-weight: 800; font-size: 1.05rem; letter-spacing: 0.2px; }
    .modal-body { padding: 1.25rem; background: linear-gradient(180deg, #fbfbfd 0%, #f6f7fb 100%); max-height:60vh; overflow-y:auto; }
     /* Ensure modals and backdrops sit above other page chrome (footer/header).
       Make the backdrop light/white inside the embedded iframe so it doesn't show
       as a heavy gray box when editing inside the parent modal. */
     .modal-backdrop { z-index: 2000 !important; background-color: rgba(255,255,255,0.95) !important; }
     .modal-backdrop.show { background-color: rgba(255,255,255,0.95) !important; opacity: 1 !important; }
     .modal { z-index: 2001 !important; }
    /* Tighter modal widths and constrained media to avoid overly wide content */
    #viewProjectModal .modal-dialog,
    #editProjectModal .modal-dialog { max-width:820px; }
    #deleteProjectModal .modal-dialog { max-width:520px; }
    #viewProjectModal img#viewProjectImage { max-height:320px; object-fit:cover; display:block; margin:0 auto 0.75rem; }
    .modal .proj-card { max-width:760px; margin-left:auto; margin-right:auto; }
    .modal-footer { padding: 0.85rem 1.25rem; border-top: 0; display:flex; gap:.5rem; justify-content:flex-end; }
    .modal .form-label { font-weight:600; color:var(--accent); }
    .modal .form-control { border-radius: 8px; border:1px solid rgba(15,23,42,0.06); padding:.6rem .9rem; }
    .modal .btn-pill { box-shadow: 0 6px 20px rgba(2,6,23,0.06); }
    .modal .btn-ghost { background: transparent; border: 1px solid rgba(15,23,42,0.06); color:var(--accent); }
    /* Style multi-select to remove default gray outline and match modal controls */
    .modal select.form-control { border-radius: 8px; border:1px solid rgba(15,23,42,0.06); padding:.6rem .9rem; box-shadow:none; outline:none; }
    .modal select.form-control:focus { outline:none; box-shadow:none; border-color: rgba(79,70,229,0.12); }
    /* When this page is embedded (in an iframe modal), render project list as a boxed card grid
       - uses a responsive 3-column-ish grid on wide screens matching the example
       - each card is boxed, slightly rounded, with clear spacing */
    .card-grid.embedded {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 1.5rem;
      justify-items: center;
      align-items: start;
      width: 100%;
    }
    .card-grid.embedded .proj-card {
      width: 100%;
      max-width: 360px; /* box width like example */
      border-radius: 12px; /* gentle corner to match example */
      padding: 1.25rem;
      box-shadow: 0 10px 30px rgba(2,6,23,0.04);
      border: 1px solid rgba(2,6,23,0.04);
      display: flex;
      flex-direction: column;
      gap: 0.75rem;
    }
    .card-grid.embedded .proj-thumb { height: 140px; border-radius: 10px; object-fit:cover }
    /* Embedded header - fixed inside iframe (not moving when scrolling) */
    .embedded-header.embedded{
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 1200;
      background: linear-gradient(90deg,var(--primary),var(--primary-dark));
      color: #fff;
      padding: 0.9rem 1rem;
      margin: 0;
      box-shadow: 0 6px 20px rgba(2,6,23,0.06);
      display:flex;align-items:center;justify-content:space-between
    }
    .embedded-header.embedded h3{margin:0;color:#fff}
    /* when header is fixed, add top padding to the main content so it isn't covered */
    .has-fixed-header{padding-top:72px}
  </style>
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

    <!-- flashdata will be shown as iziToast notifications -->

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
            <div class="text-muted" style="margin-top:.4rem"><?php echo htmlspecialchars($p['description'] ?? ''); ?></div>
            <?php if (!empty($p['tags']) && is_array($p['tags'])): ?>
              <div style="margin-top:.5rem">
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
                <?php else: ?>
                  <?php if (!empty($p['url'])): ?>
                    <button type="button" class="btn-pill btn-view btn-sm" aria-label="View project"> <i class="fas fa-external-link-alt"></i> View</button>
                  <?php endif; ?>
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
          <img id="viewProjectImage" src="" alt="" style="width:100%;height:auto;border-radius:8px;display:none;margin-bottom:.75rem">
          <p id="viewProjectDescription" class="text-muted"></p>
          <div id="viewProjectTags" style="margin-top:.5rem"></div>
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
                  <option value="">-- Select framework / language --</option>
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
                  <option value="other">Other</option>
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

  <script>
    var projects = <?php echo json_encode(array_values($projects)); ?>;
    var baseUrl = '<?php echo site_url(); ?>';
    var flash_success = <?php echo json_encode($this->session->flashdata('success')); ?>;
    var flash_error = <?php echo json_encode($this->session->flashdata('error')); ?>;
    var mode = '<?php echo isset($mode) ? $mode : 'read'; ?>';

    function findProject(id){
      for(var i=0;i<projects.length;i++) if(projects[i].id == id) return projects[i];
      return null;
    }

    document.addEventListener('DOMContentLoaded', function(){
      document.querySelectorAll('.proj-card').forEach(function(card){
        var id = card.getAttribute('data-id');
        // attach buttons
        var viewBtn = card.querySelector('.btn-view');
        var editBtn = card.querySelector('.btn-edit');
        var delBtn = card.querySelector('.btn-del');
        if(viewBtn){
          viewBtn.addEventListener('click', function(e){
            var p = findProject(id);
            if(!p) return;
            document.getElementById('viewProjectTitle').textContent = p.title || 'Project';
            var img = document.getElementById('viewProjectImage');
            if(p.image){ img.src = p.image; img.style.display='block'; } else { img.style.display='none'; }
            document.getElementById('viewProjectDescription').textContent = p.description || '';
            var tagsWrap = document.getElementById('viewProjectTags'); tagsWrap.innerHTML='';
            if(p.tags && Array.isArray(p.tags)) p.tags.forEach(function(t){ var s=document.createElement('span'); s.className='tag'; s.textContent=t; tagsWrap.appendChild(s); });
            var link = document.getElementById('viewProjectLink'); if(p.url){ link.href = p.url; link.style.display='inline-block'; } else { link.style.display='none'; }
            var modal = new bootstrap.Modal(document.getElementById('viewProjectModal'));
            modal.show();
          });
        }
        if(editBtn){
            editBtn.addEventListener('click', function(e){
            var p = findProject(id);
            if(!p) return;
            document.getElementById('edit_title').value = p.title || '';
            document.getElementById('edit_description').value = p.description || '';
            document.getElementById('edit_image').value = p.image || '';
            document.getElementById('edit_url').value = p.url || '';
            // Populate the Type multi-select from p.type (supports array, JSON, or CSV)
            (function(){
              var sel = document.getElementById('edit_type');
              if(!sel) return;
              // clear
              Array.from(sel.options).forEach(function(o){ o.selected = false; });
              var types = [];
              if (p.type) {
                if (Array.isArray(p.type)) types = p.type;
                else {
                  var raw = String(p.type || '').trim();
                  try {
                    var dec = JSON.parse(raw);
                    if (Array.isArray(dec)) types = dec;
                    else types = raw ? raw.split(',').map(function(s){return s.trim();}) : [];
                  } catch(e) {
                    types = raw ? raw.split(',').map(function(s){return s.trim();}) : [];
                  }
                }
              }
              types.forEach(function(t){
                for(var i=0;i<sel.options.length;i++){
                  if(sel.options[i].value === t){ sel.options[i].selected = true; break; }
                }
              });
            })();
            var form = document.getElementById('editProjectForm');
            form.action = '<?php echo site_url('projects/edit/'); ?>' + id + '?embedded=1';
            var modal = new bootstrap.Modal(document.getElementById('editProjectModal'));
            modal.show();
          });
        }
        if(delBtn){
          delBtn.addEventListener('click', function(e){
            var p = findProject(id);
            if(!p) return;
            document.getElementById('deleteProjectName').textContent = p.title || 'this project';
            // Use PHP-generated site_url to ensure correct index.php presence and keep embedded context
            document.getElementById('confirmDeleteBtn').href = '<?php echo site_url('projects/delete/'); ?>' + id + '?embedded=1';
            var modal = new bootstrap.Modal(document.getElementById('deleteProjectModal'));
            modal.show();
          });
        }
      });
  
      // Intercept confirm delete clicks and perform AJAX delete so UI updates without full reload
      document.addEventListener('click', function(e){
        var el = e.target.closest && e.target.closest('#confirmDeleteBtn');
        if(!el) return;
        e.preventDefault();
        var url = el.getAttribute('href');
        if(!url) return;
        fetch(url, { method: 'GET', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
          .then(function(res){ return res.json ? res.json() : res.text(); })
          .then(function(data){
            try{ 
              // close the modal
              var dm = document.getElementById('deleteProjectModal');
              bootstrap.Modal.getOrCreateInstance(dm).hide();
            }catch(e){}
            // If server returned JSON with success, remove the project card from DOM
            if (data && typeof data === 'object' && data.success && data.id) {
              var card = document.querySelector('.proj-card[data-id="'+data.id+'"]');
              if(card) card.remove();
              iziToast.success({ title: 'Success', message: data.message || 'Project deleted', position: 'topRight', timeout: 3500 });
            } else {
              // fallback: reload the iframe to reflect changes
              iziToast.success({ title: 'Deleted', message: 'Project removed, refreshing', position: 'topRight', timeout: 2200 });
              setTimeout(function(){ location.reload(); }, 600);
            }
          }).catch(function(){ iziToast.error({ title: 'Error', message: 'Delete failed', position: 'topRight' }); });
      });

      // Handle edit form via AJAX so the edit modal closes and the card updates without reload
      (function(){
        var editForm = document.getElementById('editProjectForm');
        if(!editForm) return;
        editForm.addEventListener('submit', function(ev){
          ev.preventDefault();
          var fd = new FormData(editForm);
          fetch(editForm.action, { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function(res){ return res.json(); })
            .then(function(json){
              try{ bootstrap.Modal.getOrCreateInstance(document.getElementById('editProjectModal')).hide(); }catch(e){}
              if(json && json.success && json.project){
                var p = json.project;
                var card = document.querySelector('.proj-card[data-id="'+p.id+'"]');
                if(card){
                  // update title
                  var t = card.querySelector('.proj-title'); if(t) t.textContent = p.title || 'Untitled';
                  // update description (first .text-muted inside card)
                  var d = card.querySelector('.text-muted'); if(d) d.textContent = p.description || '';
                  // update tags: remove existing .tag elements then insert new ones
                  var existingTags = card.querySelectorAll('.tag');
                  existingTags.forEach(function(el){ el.remove(); });
                  if(p.tags && Array.isArray(p.tags) && p.tags.length){
                    var desc = card.querySelector('.text-muted');
                    var tagsWrap = document.createElement('div'); tagsWrap.style.marginTop = '.5rem';
                    p.tags.forEach(function(tag){ var s = document.createElement('span'); s.className = 'tag'; s.textContent = tag; tagsWrap.appendChild(s); });
                    if(desc && desc.parentNode) desc.parentNode.insertBefore(tagsWrap, desc.nextSibling);
                  }
                  // update image/thumb
                  var thumb = card.querySelector('.proj-thumb');
                  if(p.image){
                    if(thumb && thumb.tagName && thumb.tagName.toLowerCase() === 'img'){
                      thumb.src = p.image;
                      thumb.style.display = 'block';
                    } else {
                      var img = document.createElement('img'); img.className = 'proj-thumb'; img.src = p.image; img.alt = '';
                      if(thumb) thumb.parentNode.replaceChild(img, thumb);
                    }
                  } else {
                    if(thumb && thumb.tagName && thumb.tagName.toLowerCase() === 'img'){
                      var div = document.createElement('div'); div.className = 'proj-thumb'; if(thumb.parentNode) thumb.parentNode.replaceChild(div, thumb);
                    }
                  }
                }
                iziToast.success({ title: 'Success', message: json.message || 'Project updated', position: 'topRight', timeout: 3500 });
              } else {
                iziToast.error({ title: 'Error', message: (json && json.message) || 'Update failed', position: 'topRight' });
              }
            }).catch(function(){ iziToast.error({ title: 'Error', message: 'Update failed', position: 'topRight' }); });
        });
      })();
    });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/izitoast@1.4.0/dist/js/iziToast.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function(){
      try {
        if (flash_success) {
          iziToast.success({ title: 'Success', message: flash_success, position: 'topRight', timeout: 3500 });
        }
        if (flash_error) {
          iziToast.error({ title: 'Error', message: flash_error, position: 'topRight', timeout: 5000 });
        }
      } catch (err) {}
    });
  </script>

  <script>
    // If an operation produced a flashmessage (success/error), ensure any open Bootstrap modal is closed.
    function _hideAllBootstrapModals(){
      try{
        document.querySelectorAll('.modal').forEach(function(m){
          try{ var inst = bootstrap.Modal.getOrCreateInstance(m); inst.hide(); }catch(e){}
        });
      }catch(e){}
    }

    document.addEventListener('DOMContentLoaded', function(){
      try {
        if (flash_success || flash_error) {
          // give iziToast a tick to start then hide modals so UI is clean
          setTimeout(function(){ _hideAllBootstrapModals(); }, 50);
        }
      } catch (err) {}
    });
  </script>
</body>
</html>
