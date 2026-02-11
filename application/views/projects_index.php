<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Manage Projects</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<?php $embedded = isset($_GET['embedded']) && $_GET['embedded'] == '1'; ?>
<body>
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Projects</h3>
    <?php if (empty($embedded)): ?>
      <div>
        <a href="<?php echo site_url('projects/create'); ?>" class="btn btn-sm btn-primary">Add Project</a>
        <a href="<?php echo site_url('auth/logout'); ?>" class="btn btn-sm btn-outline-secondary">Logout</a>
      </div>
    <?php endif; ?>
  </div>
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
    .btn-edit{display:inline-flex;align-items:center;gap:.5rem;border:0;padding:.4rem .7rem;border-radius:8px;background:linear-gradient(90deg,#06b6d4,var(--primary));color:#fff}
    .btn-del{display:inline-flex;align-items:center;gap:.5rem;padding:.4rem .7rem;border-radius:8px;background:transparent;border:1px solid rgba(239,68,68,0.15);color:#ef4444}
    .btn-view{display:inline-flex;align-items:center;gap:.4rem;padding:.38rem .65rem;border-radius:8px;background:transparent;border:1px solid rgba(79,70,229,0.12);color:var(--primary)}

    /* Modal visuals (match dashboard) */
    .modal-content { border-radius: 14px; overflow: hidden; }
    .modal-header { background: linear-gradient(90deg,var(--primary),var(--primary-dark)); color: #fff; border-bottom: 0; padding: 1rem 1.25rem; }
    .modal-title { font-weight: 800; font-size: 1.05rem; letter-spacing: 0.2px; }
    .modal-body { padding: 1.25rem; background: linear-gradient(180deg, #fbfbfd 0%, #f6f7fb 100%); }
    .modal-footer { padding: 0.85rem 1.25rem; border-top: 0; display:flex; gap:.5rem; justify-content:flex-end; }
    .modal .form-label { font-weight:600; color:var(--accent); }
    .modal .form-control { border-radius: 8px; border:1px solid rgba(15,23,42,0.06); padding:.6rem .9rem; }
    .modal .btn-pill { box-shadow: 0 6px 20px rgba(2,6,23,0.06); }
    .modal .btn-ghost { background: transparent; border: 1px solid rgba(15,23,42,0.06); color:var(--accent); }
  </style>
  <div class="wrap">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3 style="margin:0">Projects</h3>
      <?php if (empty($embedded)): ?>
      <div>
        <a href="<?php echo site_url('projects/create'); ?>" class="btn btn-sm btn-primary">Add Project</a>
        <a href="<?php echo site_url('crud'); ?>" class="btn btn-sm btn-outline-secondary">Dashboard</a>
      </div>
      <?php endif; ?>
    </div>

    <?php if (!empty(
      $this->session->flashdata('success') || $this->session->flashdata('error')
    )): ?>
      <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($this->session->flashdata('success')); ?></div>
      <?php endif; ?>
      <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($this->session->flashdata('error')); ?></div>
      <?php endif; ?>
    <?php endif; ?>

    <?php if (empty($projects)): ?>
      <div class="alert alert-info">No projects yet.</div>
    <?php else: ?>
      <div class="card-grid">
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
                <button type="button" class="btn-pill btn-edit btn-sm" aria-label="Edit project"> <i class="fas fa-edit"></i> Edit</button>
                <button type="button" class="btn-pill btn-del btn-sm" aria-label="Delete project"> <i class="fas fa-trash"></i> Delete</button>
              </div>
              <div>
                <?php if (!empty($p['url'])): ?>
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
                <label class="form-label">Tags (comma separated)</label>
                <input name="tags" id="edit_tags" class="form-control">
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
            document.getElementById('edit_tags').value = (p.tags && Array.isArray(p.tags)) ? p.tags.join(',') : (p.tags || '');
            var form = document.getElementById('editProjectForm');
            form.action = baseUrl + 'projects/edit/' + id;
            var modal = new bootstrap.Modal(document.getElementById('editProjectModal'));
            modal.show();
          });
        }
        if(delBtn){
          delBtn.addEventListener('click', function(e){
            var p = findProject(id);
            if(!p) return;
            document.getElementById('deleteProjectName').textContent = p.title || 'this project';
            document.getElementById('confirmDeleteBtn').href = baseUrl + 'projects/delete/' + id;
            var modal = new bootstrap.Modal(document.getElementById('deleteProjectModal'));
            modal.show();
          });
        }
      });
    });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
