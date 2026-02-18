document.addEventListener('DOMContentLoaded', function(){
  // Get PHP-provided data from meta tags
  var projects = JSON.parse(document.getElementById('projects-data').getAttribute('data-projects') || '[]');
  var baseUrl = document.getElementById('projects-data').getAttribute('data-base-url') || '';
  var flash_success = document.getElementById('projects-data').getAttribute('data-flash-success');
  var flash_error = document.getElementById('projects-data').getAttribute('data-flash-error');
  var mode = document.getElementById('projects-data').getAttribute('data-mode') || 'read';

  function findProject(id){
    for(var i=0;i<projects.length;i++) if(projects[i].id == id) return projects[i];
    return null;
  }

  // Attach event listeners to project cards
  document.querySelectorAll('.proj-card').forEach(function(card){
    var id = card.getAttribute('data-id');
    var viewBtn = card.querySelector('.btn-view');
    var editBtn = card.querySelector('.btn-edit');
    var delBtn = card.querySelector('.btn-del');
    
    if(viewBtn){
      viewBtn.addEventListener('click', function(e){
        var p = findProject(id);
        if(!p) return;
        document.getElementById('viewProjectTitle').textContent = p.title || 'Project';
        var img = document.getElementById('viewProjectImage');
        if(p.image){ 
          img.src = p.image;
          img.classList.add('show');
        } else { 
          img.classList.remove('show');
        }
        document.getElementById('viewProjectDescription').textContent = p.description || '';
        var tagsWrap = document.getElementById('viewProjectTags'); tagsWrap.innerHTML='';
        if(p.tags && Array.isArray(p.tags)) p.tags.forEach(function(t){ var s=document.createElement('span'); s.className='tag'; s.textContent=t; tagsWrap.appendChild(s); });
        var link = document.getElementById('viewProjectLink'); 
        if(p.url){ 
          link.href = p.url;
          link.classList.add('show');
        } else { 
          link.classList.remove('show');
        }
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
        form.action = baseUrl + 'projects/edit/' + id + '?embedded=1';
        var modal = new bootstrap.Modal(document.getElementById('editProjectModal'));
        modal.show();
      });
    }
    
    if(delBtn){
      delBtn.addEventListener('click', function(e){
        var p = findProject(id);
        if(!p) return;
        document.getElementById('deleteProjectName').textContent = p.title || 'this project';
        document.getElementById('confirmDeleteBtn').href = baseUrl + 'projects/delete/' + id + '?embedded=1';
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
          var dm = document.getElementById('deleteProjectModal');
          bootstrap.Modal.getOrCreateInstance(dm).hide();
        }catch(e){}
        if (data && typeof data === 'object' && data.success && data.id) {
          var card = document.querySelector('.proj-card[data-id="'+data.id+'"]');
          if(card) card.remove();
          Swal.fire({ icon: 'success', title: 'Project Deleted', text: data.message || 'Your project has been deleted', confirmButtonColor: '#003d99', timer: 2500 });
        } else {
          Swal.fire({ icon: 'success', title: 'Project Removed', text: 'Refreshing...', confirmButtonColor: '#003d99', timer: 1500 });
          setTimeout(function(){ location.reload(); }, 600);
        }
      }).catch(function(){ Swal.fire({ icon: 'error', title: 'Delete Failed', text: 'Unable to delete project', confirmButtonColor: '#003d99' }); });
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
              var t = card.querySelector('.proj-title'); if(t) t.textContent = p.title || 'Untitled';
              var d = card.querySelector('.text-muted'); if(d) d.textContent = p.description || '';
              var existingTags = card.querySelectorAll('.tag');
              existingTags.forEach(function(el){ el.remove(); });
              if(p.tags && Array.isArray(p.tags) && p.tags.length){
                var desc = card.querySelector('.text-muted');
                var tagsWrap = document.createElement('div'); tagsWrap.className = 'proj-tags-wrapper';
                p.tags.forEach(function(tag){ var s = document.createElement('span'); s.className = 'tag'; s.textContent = tag; tagsWrap.appendChild(s); });
                if(desc && desc.parentNode) desc.parentNode.insertBefore(tagsWrap, desc.nextSibling);
              }
              var thumb = card.querySelector('.proj-thumb');
              if(p.image){
                if(thumb && thumb.tagName && thumb.tagName.toLowerCase() === 'img'){
                  thumb.src = p.image;
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
            Swal.fire({ icon: 'success', title: 'Project Updated', text: json.message || 'Your project has been updated successfully', confirmButtonColor: '#003d99', timer: 2500 });
          } else {
            Swal.fire({ icon: 'error', title: 'Update Failed', text: (json && json.message) || 'Unable to update project', confirmButtonColor: '#003d99' });
          }
        }).catch(function(){ Swal.fire({ icon: 'error', title: 'Update Error', text: 'Failed to connect', confirmButtonColor: '#003d99' }); });
    });
  })();

  // Show flashdata alerts
  try {
    if (flash_success) {
      Swal.fire({ icon: 'success', title: 'Success', text: flash_success, confirmButtonColor: '#003d99', timer: 2500 });
    }
    if (flash_error) {
      Swal.fire({ icon: 'error', title: 'Error', text: flash_error, confirmButtonColor: '#003d99' });
    }
  } catch (err) {}

  // If an operation produced a flashmessage, ensure any open Bootstrap modal is closed
  function _hideAllBootstrapModals(){
    try{
      document.querySelectorAll('.modal').forEach(function(m){
        try{ var inst = bootstrap.Modal.getOrCreateInstance(m); inst.hide(); }catch(e){}
      });
    }catch(e){}
  }

  try {
    if (flash_success || flash_error) {
      setTimeout(function(){ _hideAllBootstrapModals(); }, 50);
    }
  } catch (err) {}
});
