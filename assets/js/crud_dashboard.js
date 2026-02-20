// View and Edit project functions
var iframeModalEl = null;
var iframe = null;
var iframeLoading = null;
var iframeTitle = null;

// Initialize EDIT_SECTION_URL from data attribute (moved from inline script)
(function() {
  var scriptTag = document.currentScript || document.querySelector('script[data-edit-section-url]');
  if (scriptTag && scriptTag.getAttribute('data-edit-section-url')) {
    window.EDIT_SECTION_URL = scriptTag.getAttribute('data-edit-section-url');
  }
})();

function initIframeModal() {
  if (!iframeModalEl) {
    iframeModalEl = document.getElementById('iframeModal');
    iframe = document.getElementById('iframeModalFrame');
    iframeLoading = document.getElementById('iframeLoading');
    iframeTitle = document.getElementById('iframeModalTitle');
  }
}

function openIframe(title, url){
  initIframeModal();
  if (!iframeModalEl || !iframe) {
    console.error('iframe modal elements not found');
    Swal.fire({ icon: 'error', title: 'Error', text: 'Modal elements not found', confirmButtonColor: '#003d99' });
    return;
  }
  
  // Update title
  if(iframeTitle) iframeTitle.innerHTML = '<i class="fas fa-edit me-2"></i><span>' + title + '</span>';
  if(iframeLoading) iframeLoading.style.display = 'flex';
  
  // Set iframe source
  iframe.src = url;
  
  // Get or create modal instance (not a new one each time)
  var m = bootstrap.Modal.getOrCreateInstance(iframeModalEl);
  m.show();
  
  // Hide loading once iframe loads
  iframe.onload = function(){ 
    if(iframeLoading) iframeLoading.style.display = 'none'; 
  };
}

function handleViewProject(id){
  if(!id){ Swal.fire({ icon: 'error', title: 'Error', text: 'Project ID not available', confirmButtonColor: '#003d99' }); return; }
  
  var url = 'projects/get/' + id;
  
  if(iframeLoading) iframeLoading.style.display = 'flex';
  
  fetch(url, { 
    headers: { 'X-Requested-With': 'XMLHttpRequest' },
    credentials: 'same-origin'
  })
    .then(function(res){ 
      switch(res.status) {
        case 200: return res.json();
        case 401: throw new Error('You are not authenticated. Please log in again.');
        case 404: throw new Error('Project not found');
        case 400: throw new Error('Invalid request');
        default: throw new Error('Server error (HTTP ' + res.status + ')');
      }
    })
    .then(function(json){
      if(iframeLoading) iframeLoading.style.display = 'none';
      
      if(json && json.project){
        var p = json.project;
        document.getElementById('viewTitle').textContent = p.title || '-';
        document.getElementById('viewDescription').textContent = p.description || '-';
        document.getElementById('viewUrl').textContent = p.url || '-';
        document.getElementById('viewCreated').textContent = p.created_at ? p.created_at.split(' ')[0] : '-';
        document.getElementById('viewStatus').textContent = p.status ? 'Active' : 'Inactive';
        document.getElementById('viewImage').textContent = p.image || '-';
        
        var modal = new bootstrap.Modal(document.getElementById('viewProjectModal'));
        modal.show();
      } else {
        Swal.fire({ icon: 'error', title: 'Error', text: json?.message || 'Unable to load project details', confirmButtonColor: '#003d99' });
      }
    }).catch(function(err){
      if(iframeLoading) iframeLoading.style.display = 'none';
      var msg = err && typeof err.message === 'string' ? err.message : 'Unknown error';
      Swal.fire({ icon: 'error', title: 'Error', text: msg, confirmButtonColor: '#003d99' });
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

function getCsrfTokenName(){
  var m = document.querySelector('meta[name="csrf-token-name"]');
  return m ? m.getAttribute('content') : 'csrf_test_name';
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
                var starIcon = featured ? '<i class="fas fa-star featured-star"></i>' : '';

                function escapeHtml(s){ return (''+s).replace(/[&<>"']/g, function(c){ return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":"&#39;"}[c]; }); }

                var row =
                  '<tr>' +
                    '<td>' +
                      '<div class="project-title-cell">'+ escapeHtml(title) + starIcon +'</div>' +
                      '<div class="project-desc-cell">'+ escapeHtml(description) +'</div>' +
                    '</td>' +
                    '<td class="table-cell-muted">-</td>' +
                    '<td class="table-cell-muted">'+ escapeHtml(url) +'</td>' +
                    '<td class="table-cell-muted">'+ escapeHtml(created_at) +'</td>' +
                    '<td><span class="badge bg-light text-muted badge-status">'+ escapeHtml(status) +'</span></td>' +
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

// Section navigation handler
function setupSectionNavigation(){
  var navBtns = document.querySelectorAll('.section-nav-btn');
  if(!navBtns || navBtns.length === 0) return;
  
  navBtns.forEach(function(btn){
    btn.addEventListener('click', function(){
      var section = this.getAttribute('data-section');
      
      // Save to localStorage
      localStorage.setItem('crudActiveSection', section);
      
      // Hide all sections
      var allSections = document.querySelectorAll('.section-content');
      allSections.forEach(function(sec){ sec.style.display = 'none'; });
      
      // Remove active class from all buttons
      navBtns.forEach(function(b){ b.classList.remove('active'); });
      
      // Show selected section
      var selectedSection = document.getElementById(section + '-section');
      if(selectedSection) selectedSection.style.display = 'block';
      
      // Add active class to clicked button
      this.classList.add('active');
    });
  });
  
  // Restore saved section from localStorage
  var savedSection = localStorage.getItem('crudActiveSection');
  if(savedSection) {
    var btn = document.querySelector('.section-nav-btn[data-section="' + savedSection + '"]');
    if(btn) {
      btn.click();
    }
  }
}

function setupTableEventDelegation(){
  // Use document-level delegation for project action buttons to work across all sections
  document.addEventListener('click', function(ev){
    // Only handle clicks on project action buttons (those with data-id for projects)
    var viewBtn = ev.target.closest && ev.target.closest('.btn-view[data-id]');
    if(viewBtn && viewBtn.closest('#projects-section')){
      var id = viewBtn.getAttribute('data-id');
      handleViewProject(id);
      return;
    }

    var editBtn = ev.target.closest && ev.target.closest('.btn-edit[data-id]');
    if(editBtn && editBtn.closest('#projects-section')){
      var url = editBtn.getAttribute('data-edit-url');
      var id = editBtn.getAttribute('data-id');
      console.log('Edit button clicked with ID:', id, 'URL:', url);
      handleEditProject(url, id);
      return;
    }

    var delBtn = ev.target.closest && ev.target.closest('.btn-delete[data-id]');
    if(delBtn && delBtn.closest('#projects-section')){
      var id = delBtn.getAttribute('data-id');
      var url = delBtn.getAttribute('data-delete-url');
      handleDeleteProject(url, id);
      return;
    }
  });
  return true;
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function(){
  // Initialize iframe modal
  initIframeModal();
  
  // Setup section navigation
  setupSectionNavigation();
  
  // Setup event delegation for table buttons
  setupTableEventDelegation();
  
  // Handle iframe modal close event - automatically refresh page
  if(iframeModalEl) {
    iframeModalEl.addEventListener('hidden.bs.modal', function() { 
      if(iframe) iframe.src = ''; 
      if(iframeLoading) iframeLoading.style.display = 'none';
      // Auto-refresh page after brief delay
      setTimeout(function() {
        location.reload();
      }, 500);
    });
  }
  
  // Initialize Bootstrap tooltips
  try {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (el) { return new bootstrap.Tooltip(el); });
  } catch (err){}
  
  // Handle clear/delete field buttons for Home, About, Skills, Contact sections
  document.addEventListener('click', function(ev){
    var clearBtn = ev.target.closest && ev.target.closest('.btn-clear-field');
    if(!clearBtn) return;
    
    var field = clearBtn.getAttribute('data-field');
    var section = clearBtn.getAttribute('data-section');
    var skillName = clearBtn.getAttribute('data-skill-name');
    var skillId = clearBtn.getAttribute('data-skill-id');
    var contactId = clearBtn.getAttribute('data-contact-id');
    
    var fieldLabel = field.replace(/_/g, ' ').replace(/\b\w/g, function(c){ return c.toUpperCase(); });
    if(skillName) fieldLabel = skillName;
    
    // Different dialog styles for different sections
    var isDeleteStyle = (section === 'skills' || section === 'contact');
    var dialogTitle, dialogText, confirmText, successTitle, successText;
    
    if(isDeleteStyle) {
      dialogTitle = section === 'skills' ? 'Delete skill?' : 'Delete contact?';
      dialogText = 'This action cannot be undone.';
      confirmText = 'Yes, delete';
      successTitle = 'Deleted';
      successText = fieldLabel + ' has been deleted.';
    } else {
      dialogTitle = 'Clear ' + fieldLabel + '?';
      dialogText = 'This will set the field value to empty.';
      confirmText = 'Yes, clear it';
      successTitle = 'Cleared';
      successText = fieldLabel + ' has been cleared.';
    }
    
    Swal.fire({
      title: dialogTitle,
      text: dialogText,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: confirmText,
      cancelButtonText: 'Cancel'
    }).then(function(result){
      if(!result.isConfirmed) return;
      
      var fd = new FormData();
      fd.append('section', section);
      fd.append('field', field);
      fd.append('value', '');
      if(skillName) fd.append('skill_name', skillName);
      if(skillId) fd.append('skill_id', skillId);
      if(contactId) fd.append('contact_id', contactId);
      
      var tokenName = getCsrfTokenName();
      var token = getCsrf();
      if(token) fd.append(tokenName, token);
      
      console.log('Clear field request:', { section: section, field: field, skillName: skillName, tokenName: tokenName });
      
      fetch(window.EDIT_SECTION_URL || 'crud/edit_section', {
        method: 'POST',
        body: fd,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      })
      .then(function(res){ 
        console.log('Clear field response status:', res.status);
        if(!res.ok) {
          throw new Error('Server returned ' + res.status);
        }
        return res.json(); 
      })
      .then(function(json){
        console.log('Clear field response:', json);
        if(json && json.success){
          Swal.fire({
            icon: 'success',
            title: successTitle,
            text: successText,
            confirmButtonColor: '#003d99',
            timer: 2000
          }).then(function(){ location.reload(); });
        } else {
          Swal.fire({ icon: 'error', title: 'Error', text: json?.message || (isDeleteStyle ? 'Failed to delete' : 'Failed to clear field'), confirmButtonColor: '#003d99' });
        }
      })
      .catch(function(err){
        console.error('Clear field error:', err);
        Swal.fire({ icon: 'error', title: 'Error', text: 'Request failed: ' + (err.message || 'Unknown error'), confirmButtonColor: '#003d99' });
      });
    });
  });
  
  // Handle View Field button clicks
  document.addEventListener('click', function(ev){
    var viewBtn = ev.target.closest && ev.target.closest('.btn-view-field');
    if(!viewBtn) return;
    
    var label = viewBtn.getAttribute('data-label') || 'Field';
    var value = viewBtn.getAttribute('data-value') || '';
    var skillName = viewBtn.getAttribute('data-skill-name');
    var skillPercent = viewBtn.getAttribute('data-skill-percent');
    var type = viewBtn.getAttribute('data-type') || 'text';
    
    // Set modal content
    document.getElementById('viewFieldTitle').textContent = 'View ' + label;
    document.getElementById('viewFieldLabel').textContent = label;
    
    if(skillName && skillPercent) {
      document.getElementById('viewFieldValue').innerHTML = '<strong>' + skillName + '</strong><br>Proficiency: ' + skillPercent + '%';
    } else if(type === 'image' && value) {
      document.getElementById('viewFieldValue').innerHTML = '<img src="' + value + '?t=' + Date.now() + '" alt="' + label + '" style="max-width: 200px; max-height: 200px; border-radius: 8px;">';
    } else {
      document.getElementById('viewFieldValue').textContent = value || 'Not set';
    }
    
    // Show modal
    var modal = new bootstrap.Modal(document.getElementById('viewFieldModal'));
    modal.show();
  });
  
  // Handle Edit Field button clicks
  document.addEventListener('click', function(ev){
    var editBtn = ev.target.closest && ev.target.closest('.btn-edit-field');
    if(!editBtn) return;
    
    var field = editBtn.getAttribute('data-field') || '';
    var section = editBtn.getAttribute('data-section') || '';
    var label = editBtn.getAttribute('data-label') || 'Field';
    var value = editBtn.getAttribute('data-value') || '';
    var type = editBtn.getAttribute('data-type') || 'text';
    var skillName = editBtn.getAttribute('data-skill-name');
    var skillPercent = editBtn.getAttribute('data-skill-percent');
    var skillId = editBtn.getAttribute('data-skill-id');
    var contactId = editBtn.getAttribute('data-contact-id');
    
    // Store skill_id and contact_id as data attributes on the form for retrieval on submit
    var editForm = document.getElementById('editFieldForm');
    if(editForm) {
      editForm.setAttribute('data-skill-id', skillId || '');
      editForm.setAttribute('data-contact-id', contactId || '');
    }
    
    // Set modal title and hidden fields
    document.getElementById('editFieldTitle').textContent = (type === 'skill') ? 'Edit Skills' : 'Edit ' + label;
    document.getElementById('editFieldName').value = field;
    document.getElementById('editFieldSection').value = section;
    document.getElementById('editFieldLabel').textContent = (type === 'skill') ? 'Skills' : label;
    document.getElementById('editFieldSkillName').value = skillName || '';
    
    // Get input wrapper
    var inputWrapper = document.getElementById('editFieldInputWrapper');
    var skillPercentWrapper = document.getElementById('editSkillPercentWrapper');
    
    // Clear all previous dynamic elements (inputs, selects, labels, help text)
    var dynamicElements = inputWrapper.querySelectorAll('input, textarea, select, label, small');
    dynamicElements.forEach(function(el) {
      // Keep the default editFieldLabel, remove only dynamically added elements
      if(el.id !== 'editFieldLabel') el.remove();
    });
    
    // Handle skill type differently
    if(type === 'skill') {
      // Show default label for skills
      document.getElementById('editFieldLabel').style.display = 'block';
      
      var select = document.createElement('select');
      select.name = 'value';
      select.id = 'editFieldInput';
      select.className = 'form-select';
      
      var skillOptions = [
        'HTML5 / CSS3', 'JavaScript', 'TypeScript', 'Python', 'Java', 'C#', 'C++', 'C',
        'PHP', 'Ruby', 'Go', 'Rust', 'Swift', 'Kotlin', 'Dart', 'SQL',
        'React', 'Vue.js', 'Angular', 'Node.js', 'Express.js', 'Next.js', 'Django', 'Flask',
        'Laravel', 'Spring Boot', 'ASP.NET', 'Ruby on Rails', 'Flutter', 'React Native',
        'Bootstrap', 'Tailwind CSS', 'jQuery', 'MongoDB', 'PostgreSQL', 'MySQL', 'Redis',
        'Docker', 'Kubernetes', 'AWS', 'Azure', 'Google Cloud', 'Linux', 'GitHub',
        'Figma', 'Adobe XD', 'Photoshop', 'Illustrator'
      ];
      
      skillOptions.forEach(function(opt) {
        var option = document.createElement('option');
        option.value = opt;
        option.textContent = opt;
        if(opt === skillName) option.selected = true;
        select.appendChild(option);
      });
      
      inputWrapper.appendChild(select);
      
      // Show skill percent field
      skillPercentWrapper.style.display = 'block';
      document.getElementById('editSkillPercent').value = skillPercent || '';
    } else if(section === 'contact') {
      // Hide skill percent field
      skillPercentWrapper.style.display = 'none';
      
      // Create contact type dropdown
      var typeSelect = document.createElement('select');
      typeSelect.name = 'contact_type';
      typeSelect.id = 'editContactType';
      typeSelect.className = 'form-select mb-3';
      
      var contactTypes = [
        { value: 'Email', icon: '📧' },
        { value: 'Phone', icon: '📞' },
        { value: 'GitHub', icon: '🐙' },
        { value: 'LinkedIn', icon: '💼' },
        { value: 'Twitter', icon: '🐦' },
        { value: 'Facebook', icon: '📘' },
        { value: 'Instagram', icon: '📸' },
        { value: 'YouTube', icon: '🎬' },
        { value: 'TikTok', icon: '🎵' },
        { value: 'Discord', icon: '🎮' },
        { value: 'Telegram', icon: '✈️' },
        { value: 'WhatsApp', icon: '💬' },
        { value: 'Website', icon: '🌐' },
        { value: 'Portfolio', icon: '💼' },
        { value: 'Address', icon: '📍' },
        { value: 'Skype', icon: '💠' },
        { value: 'Slack', icon: '💬' },
        { value: 'Other', icon: '🔗' }
      ];
      
      contactTypes.forEach(function(ct) {
        var option = document.createElement('option');
        option.value = ct.value;
        option.textContent = ct.icon + ' ' + ct.value;
        if(ct.value === label) option.selected = true;
        typeSelect.appendChild(option);
      });
      
      // Create label for type dropdown
      var typeLabel = document.createElement('label');
      typeLabel.className = 'form-label fw-bold';
      typeLabel.textContent = 'Contact Type';
      typeLabel.setAttribute('for', 'editContactType');
      
      inputWrapper.appendChild(typeLabel);
      inputWrapper.appendChild(typeSelect);
      
      // Create value input
      var valueLabel = document.createElement('label');
      valueLabel.className = 'form-label fw-bold';
      valueLabel.textContent = 'Contact Value';
      valueLabel.setAttribute('for', 'editFieldInput');
      inputWrapper.appendChild(valueLabel);
      
      var input = document.createElement('input');
      input.type = 'text';
      input.name = 'value';
      input.id = 'editFieldInput';
      input.className = 'form-control';
      input.value = value;
      input.placeholder = 'Enter contact value (URL, email, phone, etc.)';
      inputWrapper.appendChild(input);
      
      // Hide the default label since we have custom labels
      document.getElementById('editFieldLabel').style.display = 'none';
    } else if(type === 'file') {
      // Hide skill percent field
      skillPercentWrapper.style.display = 'none';
      // Show default label
      document.getElementById('editFieldLabel').style.display = 'block';
      
      var fileInput = document.createElement('input');
      fileInput.type = 'file';
      fileInput.name = 'profile_image';
      fileInput.id = 'editFieldInput';
      fileInput.className = 'form-control';
      fileInput.accept = 'image/*';
      inputWrapper.appendChild(fileInput);
      
      var helpText = document.createElement('small');
      helpText.className = 'form-text text-muted';
      helpText.textContent = 'Upload a PNG or JPG image (max 5MB)';
      inputWrapper.appendChild(helpText);
    } else {
      // Hide skill percent field
      skillPercentWrapper.style.display = 'none';
      // Show default label
      document.getElementById('editFieldLabel').style.display = 'block';
      
      if(type === 'textarea') {
        var textarea = document.createElement('textarea');
        textarea.name = 'value';
        textarea.id = 'editFieldInput';
        textarea.className = 'form-control';
        textarea.rows = 6;
        textarea.value = value;
        textarea.placeholder = 'Enter ' + label.toLowerCase();
        inputWrapper.appendChild(textarea);
      } else {
        var input = document.createElement('input');
        input.type = type;
        input.name = 'value';
        input.id = 'editFieldInput';
        input.className = 'form-control';
        input.value = value;
        input.placeholder = 'Enter ' + label.toLowerCase();
        inputWrapper.appendChild(input);
      }
    }
    
    // Show modal
    var modal = new bootstrap.Modal(document.getElementById('editFieldModal'));
    modal.show();
  });
  
  // Handle Edit Field Form submission
  var editFieldForm = document.getElementById('editFieldForm');
  if(editFieldForm) {
    editFieldForm.addEventListener('submit', function(ev){
      ev.preventDefault();
      
      var fd = new FormData(editFieldForm);
      var field = fd.get('field');
      var section = fd.get('section');
      var value = fd.get('value');
      var skillName = fd.get('skill_name');
      var skillPercent = fd.get('skill_percent');
      var profileImage = fd.get('profile_image');
      var contactType = fd.get('contact_type');
      var skillId = editFieldForm.getAttribute('data-skill-id');
      var contactId = editFieldForm.getAttribute('data-contact-id');
      
      // Build request data
      var requestData = new FormData();
      requestData.append('section', section);
      requestData.append('field', field);
      
      // Handle file upload for profile_image
      if(field === 'profile_image' && profileImage && profileImage.size > 0) {
        requestData.append('profile_image', profileImage);
      } else {
        requestData.append('value', value);
      }
      
      if(skillName) requestData.append('skill_name', skillName);
      if(skillPercent) requestData.append('skill_percent', skillPercent);
      if(skillId) requestData.append('skill_id', skillId);
      if(contactId) requestData.append('contact_id', contactId);
      if(contactType) requestData.append('contact_type', contactType);
      
      var tokenName = getCsrfTokenName();
      var token = getCsrf();
      if(token) requestData.append(tokenName, token);
      
      fetch(window.EDIT_SECTION_URL || 'crud/edit_section', {
        method: 'POST',
        body: requestData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      })
      .then(function(res){ return res.json(); })
      .then(function(json){
        if(json && json.success){
          // Close modal
          var modalEl = document.getElementById('editFieldModal');
          var modal = bootstrap.Modal.getInstance(modalEl);
          if(modal) modal.hide();
          
          Swal.fire({
            icon: 'success',
            title: 'Saved',
            text: 'Field has been updated.',
            confirmButtonColor: '#003d99',
            timer: 2000
          }).then(function(){ location.reload(); });
        } else {
          Swal.fire({ icon: 'error', title: 'Error', text: json?.message || 'Failed to save field', confirmButtonColor: '#003d99' });
        }
      })
      .catch(function(err){
        Swal.fire({ icon: 'error', title: 'Error', text: 'Request failed: ' + (err.message || 'Unknown error'), confirmButtonColor: '#003d99' });
      });
    });
  }
  
  // Handle Add Skill Form submission
  var addSkillForm = document.getElementById('addSkillForm');
  if(addSkillForm) {
    addSkillForm.addEventListener('submit', function(ev){
      ev.preventDefault();
      
      var skillName = document.getElementById('addSkillName').value.trim();
      var skillPercent = document.getElementById('addSkillPercent').value;
      
      if(!skillName) {
        Swal.fire({ icon: 'warning', title: 'Required', text: 'Please enter a skill name', confirmButtonColor: '#003d99' });
        return;
      }
      if(!skillPercent || skillPercent < 0 || skillPercent > 100) {
        Swal.fire({ icon: 'warning', title: 'Required', text: 'Please enter a valid proficiency (0-100)', confirmButtonColor: '#003d99' });
        return;
      }
      
      var requestData = new FormData();
      requestData.append('section', 'skills');
      requestData.append('field', 'skill');
      requestData.append('skill_name', skillName);
      requestData.append('skill_percent', skillPercent);
      requestData.append('value', skillName); // For new skill, value is the name
      requestData.append('add_mode', '1'); // Flag to indicate adding new skill
      
      var tokenName = getCsrfTokenName();
      var token = getCsrf();
      if(token) requestData.append(tokenName, token);
      
      fetch(window.EDIT_SECTION_URL || 'crud/edit_section', {
        method: 'POST',
        body: requestData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      })
      .then(function(res){ return res.json(); })
      .then(function(json){
        if(json && json.success){
          var modalEl = document.getElementById('addSkillModal');
          var modal = bootstrap.Modal.getInstance(modalEl);
          if(modal) modal.hide();
          
          Swal.fire({
            icon: 'success',
            title: 'Added',
            text: 'Skill has been added.',
            confirmButtonColor: '#003d99',
            timer: 2000
          }).then(function(){ location.reload(); });
        } else {
          Swal.fire({ icon: 'error', title: 'Error', text: json?.message || 'Failed to add skill', confirmButtonColor: '#003d99' });
        }
      })
      .catch(function(err){
        Swal.fire({ icon: 'error', title: 'Error', text: 'Request failed: ' + (err.message || 'Unknown error'), confirmButtonColor: '#003d99' });
      });
    });
  }
  
  // Handle Add Contact Form submission
  var addContactForm = document.getElementById('addContactForm');
  if(addContactForm) {
    addContactForm.addEventListener('submit', function(ev){
      ev.preventDefault();
      
      var contactType = document.getElementById('addContactType').value;
      var contactValue = document.getElementById('addContactValue').value.trim();
      
      if(!contactType) {
        Swal.fire({ icon: 'warning', title: 'Required', text: 'Please select a contact type', confirmButtonColor: '#003d99' });
        return;
      }
      if(!contactValue) {
        Swal.fire({ icon: 'warning', title: 'Required', text: 'Please enter a value', confirmButtonColor: '#003d99' });
        return;
      }
      
      var requestData = new FormData();
      requestData.append('section', 'contact');
      requestData.append('field', contactType);
      requestData.append('value', contactValue);
      requestData.append('add_mode', '1');
      
      var tokenName = getCsrfTokenName();
      var token = getCsrf();
      if(token) requestData.append(tokenName, token);
      
      fetch(window.EDIT_SECTION_URL || 'crud/edit_section', {
        method: 'POST',
        body: requestData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      })
      .then(function(res){ return res.json(); })
      .then(function(json){
        if(json && json.success){
          var modalEl = document.getElementById('addContactModal');
          var modal = bootstrap.Modal.getInstance(modalEl);
          if(modal) modal.hide();
          
          Swal.fire({
            icon: 'success',
            title: 'Added',
            text: 'Contact has been added.',
            confirmButtonColor: '#003d99',
            timer: 2000
          }).then(function(){ location.reload(); });
        } else {
          Swal.fire({ icon: 'error', title: 'Error', text: json?.message || 'Failed to add contact', confirmButtonColor: '#003d99' });
        }
      })
      .catch(function(err){
        Swal.fire({ icon: 'error', title: 'Error', text: 'Request failed: ' + (err.message || 'Unknown error'), confirmButtonColor: '#003d99' });
      });
    });
  }
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

  // Handle Cancel buttons for all modals - dismiss and reset forms
  document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      // Find the nearest modal and close it
      var modal = this.closest('.modal');
      if (modal) {
        var bsModal = bootstrap.Modal.getInstance(modal);
        if (bsModal) {
          bsModal.hide();
        } else {
          // If no instance exists, create and close it
          new bootstrap.Modal(modal).hide();
        }
      }
    });
  });

  // Specifically handle quickCreateModal form reset on dismiss
  var quickCreateModal = document.getElementById('quickCreateModal');
  if (quickCreateModal) {
    quickCreateModal.addEventListener('hidden.bs.modal', function() {
      // Reset form when modal is dismissed
      var form = this.querySelector('form');
      if (form) {
        form.reset();
        // Clear image preview
        var preview = form.querySelector('.image-preview-img');
        if (preview) preview.src = '';
        var checkbox = form.querySelector('#featuredCheckbox');
        if (checkbox) checkbox.checked = false;
      }
    });
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



  // Escape key closes any open modal
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      // Close all open modals
      var openModals = document.querySelectorAll('.modal.show');
      openModals.forEach(function(modal) {
        var bsModal = bootstrap.Modal.getInstance(modal);
        if (bsModal) {
          bsModal.hide();
        }
      });
    }
  });

  // Handle Edit Home Form Submission
  (function(){
    var form = document.getElementById('editHomeForm');
    if(!form) return;
    
    // Handle profile image preview
    var fileInput = document.getElementById('profileImageInput');
    var preview = document.getElementById('profilePreview');
    if(fileInput) {
      fileInput.addEventListener('change', function(e) {
        if(this.files && this.files[0]) {
          var reader = new FileReader();
          reader.onload = function(event) {
            preview.src = event.target.result;
          };
          reader.readAsDataURL(this.files[0]);
        }
      });
    }
    
    form.addEventListener('submit', function(e){
      e.preventDefault();
      var inputs = form.querySelectorAll('input[type="text"]');
      var fileInput = document.getElementById('profileImageInput');
      var data = new FormData(form);
      data.append('section', 'home');
      data.append('title', inputs[0]?.value);
      data.append('subtitle', inputs[1]?.value);
      
      // Add profile image file if selected
      if(fileInput && fileInput.files && fileInput.files[0]) {
        data.append('profile_image', fileInput.files[0]);
      }
      
      fetch(window.EDIT_SECTION_URL, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: data
      })
      .then(r => r.json())
      .then(j => {
        if(j.success){
          document.getElementById('homeTitle').textContent = inputs[0]?.value;
          document.getElementById('homeSubtitle').textContent = inputs[1]?.value;
          
          // Refresh profile image with cache buster
          if(fileInput && fileInput.files && fileInput.files[0]) {
            preview.src = document.getElementById('profilePreview').src.split('?')[0] + '?t=' + new Date().getTime();
            fileInput.value = '';
          }
          
          bootstrap.Modal.getInstance(document.getElementById('editHomeModal')).hide();
          Swal.fire({ icon: 'success', title: 'Saved', text: 'Home section updated', confirmButtonColor: '#003d99', timer: 2000 });
        } else {
          Swal.fire({ icon: 'error', title: 'Error', text: j.message || 'Failed to save', confirmButtonColor: '#003d99' });
        }
      })
      .catch(err => Swal.fire({ icon: 'error', title: 'Error', text: err.message, confirmButtonColor: '#003d99' }));
    });
  })();

  // Handle Edit About Form Submission
  (function(){
    var form = document.getElementById('editAboutForm');
    if(!form) return;
    form.addEventListener('submit', function(e){
      e.preventDefault();
      var textarea = form.querySelector('textarea');
      var data = new FormData();
      data.append('section', 'about');
      data.append('content', textarea?.value);
      
      fetch(window.EDIT_SECTION_URL, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: data
      })
      .then(r => r.json())
      .then(j => {
        if(j.success){
          document.getElementById('aboutContent').textContent = textarea?.value;
          bootstrap.Modal.getInstance(document.getElementById('editAboutModal')).hide();
          Swal.fire({ icon: 'success', title: 'Saved', text: 'About section updated', confirmButtonColor: '#003d99', timer: 2000 });
        } else {
          Swal.fire({ icon: 'error', title: 'Error', text: j.message || 'Failed to save', confirmButtonColor: '#003d99' });
        }
      })
      .catch(err => Swal.fire({ icon: 'error', title: 'Error', text: err.message, confirmButtonColor: '#003d99' }));
    });
  })();

  // Handle Edit Skills Form Submission
  (function(){
    var form = document.getElementById('editSkillsForm');
    if(!form) return;
    form.addEventListener('submit', function(e){
      e.preventDefault();
      var skillRows = form.querySelectorAll('.skill-edit-row');
      var skills = [];
      skillRows.forEach(function(row){
        var name = row.querySelector('.skill-name')?.value;
        var percent = row.querySelector('.skill-percent')?.value;
        if(name) skills.push({ name: name, percent: percent });
      });
      
      var data = new FormData();
      data.append('section', 'skills');
      data.append('skills', JSON.stringify(skills));
      
      fetch(window.EDIT_SECTION_URL, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: data
      })
      .then(r => r.json())
      .then(j => {
        if(j.success){
          var skillsList = document.getElementById('skillsList');
          skillsList.innerHTML = '';
          skills.forEach(s => {
            var li = document.createElement('li');
            li.textContent = s.name + ' - ' + s.percent + '%';
            skillsList.appendChild(li);
          });
          bootstrap.Modal.getInstance(document.getElementById('editSkillsModal')).hide();
          Swal.fire({ icon: 'success', title: 'Saved', text: 'Skills updated', confirmButtonColor: '#003d99', timer: 2000 });
        } else {
          Swal.fire({ icon: 'error', title: 'Error', text: j.message || 'Failed to save', confirmButtonColor: '#003d99' });
        }
      })
      .catch(err => Swal.fire({ icon: 'error', title: 'Error', text: err.message, confirmButtonColor: '#003d99' }));
    });
  })();

  // Handle Edit Contact Form Submission
  (function(){
    var form = document.getElementById('editContactForm');
    if(!form) return;
    form.addEventListener('submit', function(e){
      e.preventDefault();
      var inputs = form.querySelectorAll('input');
      var data = new FormData();
      data.append('section', 'contact');
      data.append('email', inputs[0]?.value);
      data.append('phone', inputs[1]?.value);
      data.append('github', inputs[2]?.value);
      data.append('linkedin', inputs[3]?.value);
      
      fetch(window.EDIT_SECTION_URL, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: data
      })
      .then(r => r.json())
      .then(j => {
        if(j.success){
          document.getElementById('contactEmail').textContent = inputs[0]?.value;
          document.getElementById('contactPhone').textContent = inputs[1]?.value;
          document.getElementById('contactGithub').textContent = inputs[2]?.value;
          document.getElementById('contactLinkedin').textContent = inputs[3]?.value;
          bootstrap.Modal.getInstance(document.getElementById('editContactModal')).hide();
          Swal.fire({ icon: 'success', title: 'Saved', text: 'Contact info updated', confirmButtonColor: '#003d99', timer: 2000 });
        } else {
          Swal.fire({ icon: 'error', title: 'Error', text: j.message || 'Failed to save', confirmButtonColor: '#003d99' });
        }
      })
      .catch(err => Swal.fire({ icon: 'error', title: 'Error', text: err.message, confirmButtonColor: '#003d99' }));
    });
  })();

  // Handle Edit Education Form Submission
  (function(){
    var form = document.getElementById('editEducationForm');
    if(!form) return;
    form.addEventListener('submit', function(e){
      e.preventDefault();
      var elemSchool = form.querySelector('[name="education_elementary"]')?.value || '';
      var highSchool = form.querySelector('[name="education_high_school"]')?.value || '';
      var seniorHigh = form.querySelector('[name="education_senior_high"]')?.value || '';
      var college = form.querySelector('[name="education_college"]')?.value || '';
      var certification = form.querySelector('[name="education_certification"]')?.value || '';
      
      var data = new FormData();
      data.append('section', 'education');
      data.append('education_elementary', elemSchool);
      data.append('education_high_school', highSchool);
      data.append('education_senior_high', seniorHigh);
      data.append('education_college', college);
      data.append('education_certification', certification);
      
      fetch(window.EDIT_SECTION_URL, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: data
      })
      .then(r => r.json())
      .then(j => {
        if(j.success){
          document.getElementById('eduElementary').textContent = elemSchool || 'Not set';
          document.getElementById('eduHighSchool').textContent = highSchool || 'Not set';
          document.getElementById('eduSeniorHigh').textContent = seniorHigh || 'Not set';
          document.getElementById('eduCollege').textContent = college || 'Not set';
          document.getElementById('eduCertification').textContent = certification || 'Not set';
          bootstrap.Modal.getInstance(document.getElementById('editEducationModal')).hide();
          Swal.fire({ icon: 'success', title: 'Saved', text: 'Education updated', confirmButtonColor: '#003d99', timer: 2000 });
        } else {
          Swal.fire({ icon: 'error', title: 'Error', text: j.message || 'Failed to save', confirmButtonColor: '#003d99' });
        }
      })
      .catch(err => Swal.fire({ icon: 'error', title: 'Error', text: err.message, confirmButtonColor: '#003d99' }));
    });
  })();
});

