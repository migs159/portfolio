<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,minimum-scale=1,user-scalable=no">
  <title><?php echo isset($page_title) ? htmlspecialchars($page_title) : 'Account'; ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo htmlspecialchars(function_exists('base_url') ? base_url('assets/css/theme.css') : '/assets/css/theme.css'); ?>">
  <link rel="stylesheet" href="<?php echo htmlspecialchars(function_exists('base_url') ? base_url('assets/css/crud-dashboard-custom.css') : '/assets/css/crud-dashboard-custom.css'); ?>">
</head>
<body>
<?php if (function_exists('get_instance')) { $ci = &get_instance(); $ci->load->view('partials/header_crud'); } else { if (isset($this) && method_exists($this->load,'view')) { $this->load->view('partials/header_crud'); } } ?>

<div class="container py-4">
  <div class="card mx-auto" style="max-width:720px;">
    <div class="card-body text-center">
      <div class="profile-initial-large mb-3"><?php echo htmlspecialchars(isset($__profile_initial) ? $__profile_initial : ($this->session->userdata('username') ? strtoupper(substr($this->session->userdata('username'),0,1)) : 'U')); ?></div>
      <h3><?php echo htmlspecialchars(isset($user['username']) ? $user['username'] : ($this->session->userdata('username') ?: 'User')); ?></h3>
      <p class="text-muted"><?php echo htmlspecialchars(isset($user['email']) ? $user['email'] : ($this->session->userdata('email') ?: 'Not set')); ?></p>
      <div class="mt-3">
        <a href="<?php echo site_url('crud'); ?>" class="btn btn-outline-secondary">Back to Dashboard</a>
        <a href="<?php echo site_url('crud/logout'); ?>" class="btn btn-danger ms-2">Logout</a>
      </div>
    </div>
  </div>
</div>

<?php if (function_exists('get_instance')) { $ci = &get_instance(); $ci->load->view('partials/footer_crud'); } else { if (isset($this) && method_exists($this->load,'view')) { $this->load->view('partials/footer_crud'); } } ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>