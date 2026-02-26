<?php
// Partial: header_crud.php (moved to headers/)
$ci = function_exists('get_instance') ? get_instance() : (isset($this) ? $this : null);
$__profile_initial = isset($_SESSION['username']) && $_SESSION['username'] ? strtoupper(substr(trim($_SESSION['username']),0,1)) : 'A';
?>
<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top standardized shadow-sm">
  <div class="container">
    <div class="navbar-brand d-flex align-items-center" aria-label="Portfolio home">
      <span class="brand-text">My Portfolio</span>
    </div>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-center sections-nav-tabs-navbar">
        <li class="nav-item">
          <button class="nav-link section-nav-btn active" data-section="projects">
            <i class="fas fa-folder me-2"></i>Projects
          </button>
        </li>
        <li class="nav-item">
          <button class="nav-link section-nav-btn" data-section="home">
            <i class="fas fa-home me-2"></i>Home
          </button>
        </li>
        <li class="nav-item">
          <button class="nav-link section-nav-btn" data-section="about">
            <i class="fas fa-user me-2"></i>About Me
          </button>
        </li>
        <li class="nav-item">
          <button class="nav-link section-nav-btn" data-section="skills">
            <i class="fas fa-star me-2"></i>Skills
          </button>
        </li>
        <li class="nav-item">
          <button class="nav-link section-nav-btn" data-section="contact">
            <i class="fas fa-envelope me-2"></i>Get in Touch
          </button>
        </li>
        <li class="nav-item dropdown ms-3">
          <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <span class="profile-initial me-2" aria-label="Account"><?php echo htmlspecialchars($__profile_initial); ?></span>
            <span class="d-none d-md-inline"><?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Account'; ?></span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end p-3" aria-labelledby="profileDropdown">
            <li>
              <a href="<?php echo site_url('crud/profile'); ?>" class="dropdown-item d-flex align-items-center">
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
