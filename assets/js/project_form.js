// Add meta tags for flashdata so external JS can access it
if(!document.querySelector('meta[name="flash-success"]')){
  var meta = document.createElement('meta');
  meta.setAttribute('name', 'flash-success');
  meta.setAttribute('content', document.currentScript?.dataset?.flashSuccess || '');
  document.head.appendChild(meta);
}
if(!document.querySelector('meta[name="flash-error"]')){
  var meta = document.createElement('meta');
  meta.setAttribute('name', 'flash-error');
  meta.setAttribute('content', document.currentScript?.dataset?.flashError || '');
  document.head.appendChild(meta);
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function(){
  try{
    var flash_success = document.querySelector('meta[name="flash-success"]')?.getAttribute('content');
    var flash_error = document.querySelector('meta[name="flash-error"]')?.getAttribute('content');
    if (flash_success) {
      Swal.fire({ icon: 'success', title: 'Success', text: flash_success, confirmButtonColor: '#003d99', timer: 2500 });
    }
    if (flash_error) {
      Swal.fire({ icon: 'error', title: 'Error', text: flash_error, confirmButtonColor: '#003d99' });
    }
  }catch(e){}

  // Auto-preview for image input
  var imageInput = document.querySelector('.image-input');
  if (imageInput) {
    imageInput.addEventListener('change', function(e) {
      var file = this.files[0];
      if (file) {
        var reader = new FileReader();
        reader.onload = function(event) {
          var preview = imageInput.closest('.mb-3').querySelector('.image-preview');
          if (preview) {
            var img = preview.querySelector('img');
            if (img) {
              img.src = event.target.result;
              preview.style.display = 'block';
              var label = preview.querySelector('small');
              if (label) label.textContent = 'Selected image:';
            }
          }
        };
        reader.readAsDataURL(file);
      }
    });
  }
});
