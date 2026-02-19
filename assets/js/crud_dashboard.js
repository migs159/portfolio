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
  
  var projectsBase = document.querySelector('meta[name="projects-base-url"]')?.getAttribute('content') || '/projects';
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
    var projectsBase = document.querySelector('meta[name="projects-base-url"]')?.getAttribute('content') || '/projects';
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
    var projectsBase = document.querySelector('meta[name="projects-base-url"]')?.getAttribute('content') || '/projects';
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
    var tokenName = document.querySelector('meta[name="csrf-token-name"]')?.getAttribute('content');
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

// Open project iframe buttons handlers
(function(){
  var viewUrl = document.querySelector('meta[name="view-projects-url"]')?.getAttribute('content') || '/projects?embedded=1&mode=read';
  var editUrl = document.querySelector('meta[name="edit-projects-url"]')?.getAttribute('content') || '/projects?embedded=1&mode=update';
  var manageUrl = document.querySelector('meta[name="manage-projects-url"]')?.getAttribute('content') || '/projects?embedded=1&mode=delete';

  var btnView = document.getElementById('openViewBtn');
  if(btnView) btnView.addEventListener('click', function(){ openIframe('View Projects', viewUrl); });

  var btnEdit = document.getElementById('openEditBtn');
  if(btnEdit) btnEdit.addEventListener('click', function(){ openIframe('Edit Projects', editUrl); });

  var btnManage = document.getElementById('openManageBtn');
  if(btnManage) btnManage.addEventListener('click', function(){ openIframe('Manage Projects', manageUrl); });
})();

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

// Initialize on page load
document.addEventListener('DOMContentLoaded', function(){
  // Initialize Bootstrap tooltips
  try {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (el) { return new bootstrap.Tooltip(el); });
  } catch (err){}
});

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

// Handle flashdata messages and modal initialization
document.addEventListener('DOMContentLoaded', function(){
  // Get flash messages from script data attributes
  var scriptEl = document.querySelector('script[data-flash-success], script[data-flash-error]');
  if (scriptEl) {
    var successMsg = scriptEl.getAttribute('data-flash-success') || '';
    var errorMsg = scriptEl.getAttribute('data-flash-error') || '';
    
    if (successMsg.trim()) {
      Swal.fire({
        icon: 'success',
        title: 'Success',
        text: successMsg,
        confirmButtonColor: '#003d99',
        timer: 3000
      });
      // Close quickCreateModal if open
      try {
        var qc = document.getElementById('quickCreateModal');
        if (qc) {
          var _m = bootstrap.Modal.getOrCreateInstance(qc);
          _m.hide();
        }
      } catch(e) {}
    }
    
    if (errorMsg.trim()) {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: errorMsg,
        confirmButtonColor: '#003d99'
      });
      // Close quickCreateModal if open
      try {
        var qc = document.getElementById('quickCreateModal');
        if (qc) {
          var _m = bootstrap.Modal.getOrCreateInstance(qc);
          _m.hide();
        }
      } catch(e) {}
    }
  }

  // Add meta tags for URLs
  if (!document.querySelector('meta[name="projects-base-url"]')) {
    var meta = document.createElement('meta');
    meta.setAttribute('name', 'projects-base-url');
    meta.setAttribute('content', '/projects');
    document.head.appendChild(meta);
  }
  if (!document.querySelector('meta[name="csrf-token-name"]')) {
    var meta = document.createElement('meta');
    meta.setAttribute('name', 'csrf-token-name');
    meta.setAttribute('content', 'csrf_token');
    document.head.appendChild(meta);
  }
});
