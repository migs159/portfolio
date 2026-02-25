<?php
// Partial: header_public.php (moved to headers/)
$ci = function_exists('get_instance') ? get_instance() : (isset($this) ? $this : null);
$logged = false;
if ($ci && isset($ci->session)) {
    $ci->load->library('session');
    $logged = $ci->session->userdata('logged_in');
}
?>
<nav class="navbar navbar-expand-lg navbar-light fixed-top standardized">
    <div class="container">
        <a class="navbar-brand" href="<?php echo htmlspecialchars(function_exists('site_url') ? site_url() : '/'); ?>">My Portfolio</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav" aria-controls="nav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
                <div class="collapse navbar-collapse" id="nav">
                        <ul class="navbar-nav ms-auto align-items-center sections-nav-tabs-navbar">
                <li class="nav-item">
                    <button class="nav-link section-nav-btn" data-section="projects">
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
                    <button class="nav-link section-nav-btn active" data-section="contact">
                        <i class="fas fa-envelope me-2"></i>Get in Touch
                    </button>
                </li>
                <li class="nav-item dropdown ms-3">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end p-3" aria-labelledby="profileDropdown">
                        <li>
                            <a href="<?php echo htmlspecialchars(function_exists('site_url') ? site_url('crud/profile') : '/crud/profile'); ?>" class="dropdown-item d-flex align-items-center">
                                <div class="profile-initial me-2">M</div>
                                <div>
                                    <div class="fw-bold">View Account</div>
                                    <div class="text-muted text-muted-small">See account details</div>
                                </div>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?php echo htmlspecialchars(function_exists('site_url') ? site_url() : '/'); ?>"><i class="fas fa-home me-2"></i>View Portfolio</a></li>
                        <li><a class="dropdown-item text-danger" href="<?php echo htmlspecialchars(function_exists('site_url') ? site_url('auth/logout') : '/auth/logout'); ?>"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                    </ul>
                </li>
            </ul>
                </div>
    </div>
</nav>

