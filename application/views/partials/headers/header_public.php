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
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="<?php echo htmlspecialchars(function_exists('site_url') ? site_url('portfolio') : '/portfolio'); ?>">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#projects">Projects</a></li>
                <li class="nav-item"><a class="nav-link" href="#about">About Me</a></li>
                <li class="nav-item"><a class="nav-link" href="#skills">Skills</a></li>
                <?php if ($logged): ?>
                    <li class="nav-item"><a class="nav-link" href="<?php echo htmlspecialchars(function_exists('site_url') ? site_url('crud') : '/crud'); ?>">Admin</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo htmlspecialchars(function_exists('site_url') ? site_url('auth/logout') : '/auth/logout'); ?>">Logout</a></li>
                <?php endif; ?>
                <li class="nav-item ms-3 d-none d-lg-block"><a class="contact-btn" href="#contact">Get in Touch</a></li>
            </ul>
        </div>
    </div>
</nav>

