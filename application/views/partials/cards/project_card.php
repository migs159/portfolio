<?php
// Moved project_card into cards/
if (!defined('BASEPATH')) exit('No direct script access allowed');

$project = $project ?? ($p ?? null);
if (!$project) return;
$featured = isset($featured) && $featured ? true : false;

$title = isset($project['title']) ? $project['title'] : ($project['name'] ?? 'Untitled');
$desc  = isset($project['description']) ? $project['description'] : '';
$img   = isset($project['image']) && $project['image'] ? $project['image'] : '';
if ($img && strpos($img, 'http') !== 0 && strpos($img, '//') !== 0) {
    if (function_exists('base_url')) $img = base_url($img);
}
$url   = isset($project['url']) ? $project['url'] : '#';
$tags  = [];
if (isset($project['tags']) && is_array($project['tags'])) {
    $tags = $project['tags'];
}
$types = [];
if (isset($project['type']) && $project['type']) {
    if (is_array($project['type'])) {
        $types = $project['type'];
    } else {
        $raw = trim((string) $project['type']);
        $decoded = json_decode($raw, true);
        if (is_array($decoded)) {
            $types = $decoded;
        } elseif ($raw !== '') {
            $types = array_filter(array_map('trim', explode(',', $raw)));
        }
    }
}

$typeLabels = [
    'php' => 'PHP', 'javascript' => 'JS', 'html_css' => 'HTML/CSS',
    'nodejs' => 'Node', 'react' => 'React', 'vue' => 'Vue',
    'angular' => 'Angular', 'uiux' => 'UI', 'cli' => 'CLI',
    'devops' => 'DevOps', 'other' => 'Other'
];

foreach ($types as $t) {
    $k = trim((string)$t);
    if ($k === '') continue;
    $label = isset($typeLabels[$k]) ? $typeLabels[$k] : $k;
    if (!in_array($label, $tags, true)) $tags[] = $label;
}

if ($featured) : ?>
    <div class="card project-card featured h-100">
        <div class="badge-featured" aria-hidden="true">Featured</div>
        <a href="<?php echo htmlspecialchars($url); ?>" target="_blank" rel="noopener" class="stretched-link link-overlay">
            <img src="<?php echo htmlspecialchars($img ?: 'https://via.placeholder.com/1200x450?text=Featured'); ?>" class="card-img-top project-img featured-img" alt="<?php echo htmlspecialchars($title); ?>">
        </a>
        <div class="card-body">
            <h5 class="card-title"><?php echo htmlspecialchars($title); ?></h5>
            <p class="card-text"><?php echo htmlspecialchars($desc); ?></p>
            <div class="project-tags">
                <?php foreach ($tags as $t) { if (trim((string)$t) === '') continue; echo '<span class="tag">'.htmlspecialchars($t).'</span>'; } ?>
            </div>
        </div>
    </div>
        <?php else: ?>
            <div class="card project-card h-100">
        <?php if ($img): ?>
            <a href="<?php echo htmlspecialchars($url); ?>" target="_blank" rel="noopener" class="stretched-link link-overlay">
                <img src="<?php echo htmlspecialchars($img ?: 'https://via.placeholder.com/800x450?text=Project'); ?>" class="card-img-top project-img" alt="<?php echo htmlspecialchars($title); ?>">
            </a>
        <?php else: ?>
            <div class="card-img-top project-img placeholder-img"></div>
        <?php endif; ?>
        <div class="card-body">
            <h5 class="card-title"><?php echo htmlspecialchars($title); ?></h5>
            <p class="card-text"><?php echo htmlspecialchars($desc); ?></p>
            <div class="project-tags">
                <?php foreach ($tags as $t) { if (trim((string)$t) === '') continue; echo '<span class="tag">'.htmlspecialchars($t).'</span>'; } ?>
            </div>
        </div>
    </div>
<?php endif; ?>
