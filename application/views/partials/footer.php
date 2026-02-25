<?php
// Partial: footer.php
// Moved footer markup out of main view so it's reusable and not inline.
?>
<footer>
    <div class="container">
        <p>&copy; <?php echo date('Y'); ?> <?php echo isset($site_title) ? htmlspecialchars($site_title) : 'Miguel Andrei del Rosario'; ?>. All rights reserved.</p>
        <div class="mt-2 footer-credit">
            <p>Crafted with <i class="fas fa-heart heart-icon"></i> for excellence in web development</p>
        </div>
    </div>
</footer>
