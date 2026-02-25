<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
// Expects $contact array with keys: type, value
if (empty($contact) || !is_array($contact)) return;
$type = $contact['type'] ?? '';
$value = $contact['value'] ?? '';
$iconMap = [
    'Email' => 'fas fa-envelope', 'Phone' => 'fas fa-phone', 'GitHub' => 'fab fa-github',
    'LinkedIn' => 'fab fa-linkedin', 'Twitter' => 'fab fa-twitter', 'Facebook' => 'fab fa-facebook',
    'Instagram' => 'fab fa-instagram', 'YouTube' => 'fab fa-youtube', 'TikTok' => 'fab fa-tiktok',
    'Discord' => 'fab fa-discord', 'Telegram' => 'fab fa-telegram', 'WhatsApp' => 'fab fa-whatsapp',
    'Website' => 'fas fa-globe', 'Portfolio' => 'fas fa-briefcase', 'Address' => 'fas fa-map-marker-alt',
    'Skype' => 'fab fa-skype', 'Slack' => 'fab fa-slack', 'Other' => 'fas fa-link'
];
$iconClass = isset($iconMap[$type]) ? $iconMap[$type] : 'fas fa-address-card';
?>
<div class="col-md-6">
  <div class="contact-item">
    <strong><i class="<?php echo $iconClass; ?> me-2"></i><?php echo htmlspecialchars($type); ?></strong>
    <?php
    $contactType = strtolower($type);
    $isEmail = ($contactType === 'email' || strpos($value, '@') !== false);
    $isLink = (strpos($value, 'http') === 0);
    $isPhone = ($contactType === 'phone' || $contactType === 'whatsapp');
    if ($isEmail): ?>
      <a href="mailto:<?php echo htmlspecialchars($value); ?>"><?php echo htmlspecialchars($value); ?></a>
    <?php elseif ($isPhone && !$isLink): ?>
      <a href="tel:<?php echo htmlspecialchars(preg_replace('/[^0-9+]/', '', $value)); ?>"><?php echo htmlspecialchars($value); ?></a>
    <?php elseif ($isLink): ?>
      <a href="<?php echo htmlspecialchars($value); ?>" target="_blank" rel="noopener noreferrer"><?php echo htmlspecialchars($value); ?></a>
    <?php else: ?>
      <p><?php echo htmlspecialchars($value); ?></p>
    <?php endif; ?>
  </div>
</div>
