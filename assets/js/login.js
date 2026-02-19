// Initialize on page load
document.addEventListener('DOMContentLoaded', function(){
  // Handle login error/success alerts from data attributes
  var scriptEl = document.querySelector('script[data-login-error], script[data-login-success]');
  if (scriptEl) {
    var errorMsg = scriptEl.getAttribute('data-login-error') || '';
    var successMsg = scriptEl.getAttribute('data-login-success') || '';
    
    if (errorMsg.trim()) {
      Swal.fire({
        icon: 'error',
        title: 'Login Failed',
        text: errorMsg,
        confirmButtonColor: '#003d99',
        confirmButtonText: 'Try Again'
      });
    }
    
    if (successMsg.trim()) {
      Swal.fire({
        icon: 'success',
        title: 'Success',
        text: successMsg,
        confirmButtonColor: '#003d99',
        timer: 2000
      });
    }
  }
});
