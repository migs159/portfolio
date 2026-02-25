<?php
// Partial: vars.php
// Extracts small view-only variables and markup previously inline in the main view.
// Expected variables in scope: $site_title, (optional) FCPATH

$initial = isset($site_title) ? strtoupper(substr(trim($site_title), 0, 1)) : 'M';
// Prefer PNG (transparent) but check disk in case of name mismatch
$profile_rel = 'assets/img/profiles/profile.png';
$profile_file = defined('FCPATH') ? rtrim(FCPATH, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $profile_rel : NULL;
$profile_exists = $profile_file ? file_exists($profile_file) : false;
$profile_url = $profile_exists && function_exists('base_url') ? base_url($profile_rel) : (function_exists('base_url') ? base_url('assets/img/profiles/profile.png') : '/assets/img/profiles/profile.png');
?>
<div id="hero-initial" aria-hidden="true"><?php echo htmlspecialchars($initial); ?></div>
<div class="hero-inner">
    <img src="<?php echo htmlspecialchars($profile_url); ?>" alt="Profile Portrait" class="hero-profile" id="hero-profile-img">
    <?php if (!$profile_exists): ?>
        <div class="profile-debug">Debug: profile image file not found on server at <strong><?php echo htmlspecialchars($profile_file ?: 'unknown'); ?></strong></div>
    <?php endif; ?>
