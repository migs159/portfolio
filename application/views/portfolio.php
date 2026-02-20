<?php
/**
 * Portfolio view
 * Path: /D:/xamp1/htdocs/portfolio/application/views/portfolio.php
 *
 * Expects:
 * - $site_title (string)
 * - $projects (array of arrays: ['title','description','image','tags'=>array(),'url'])
 * - $contact_success (optional)
 */
?><!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,minimum-scale=1,user-scalable=no">
    <title><?php echo isset($site_title) ? htmlspecialchars($site_title) : 'Portfolio'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo htmlspecialchars(function_exists('base_url') ? base_url('assets/css/theme.css') : '/assets/css/theme.css'); ?>">
    <link rel="stylesheet" href="<?php echo htmlspecialchars(function_exists('base_url') ? base_url('assets/css/portfolio-custom.css') : '/assets/css/portfolio-custom.css'); ?>">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light fixed-top">
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
                <?php
                    $ci = &get_instance();
                    $ci->load->library('session');
                    $logged = $ci->session->userdata('logged_in');
                ?>
                <?php if ($logged): ?>
                    <li class="nav-item"><a class="nav-link" href="<?php echo htmlspecialchars(function_exists('site_url') ? site_url('crud') : '/crud'); ?>">Admin</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo htmlspecialchars(function_exists('site_url') ? site_url('auth/logout') : '/auth/logout'); ?>">Logout</a></li>
                <?php endif; ?>
                <li class="nav-item ms-3 d-none d-lg-block"><a class="contact-btn" href="#contact">Get in Touch</a></li>
            </ul>
        </div>
    </div>
</nav>

<header class="hero">
    <div class="container">
        <?php $initial = isset($site_title) ? strtoupper(substr(trim($site_title),0,1)) : 'M'; ?>
        <div id="hero-initial" aria-hidden="true"><?php echo htmlspecialchars($initial); ?></div>
        <div class="hero-inner">
                    <?php
                        // Prefer PNG (transparent) but check disk in case of name mismatch
                        $profile_rel = 'assets/img/profiles/profile.png';
                        $profile_file = defined('FCPATH') ? rtrim(FCPATH, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.$profile_rel : NULL;
                        $profile_exists = $profile_file ? file_exists($profile_file) : false;
                        $profile_url = $profile_exists && function_exists('base_url') ? base_url($profile_rel) : (function_exists('base_url') ? base_url('assets/img/profiles/profile.png') : '/assets/img/profiles/profile.png');
                    ?>
                    <img src="<?php echo htmlspecialchars($profile_url); ?>" alt="Miguel Andrei Portrait" class="hero-profile" id="hero-profile-img">
                    <?php if (!$profile_exists): ?>
                        <div class="profile-debug">Debug: profile image file not found on server at <strong><?php echo htmlspecialchars($profile_file ?: 'unknown'); ?></strong></div>
                    <?php endif; ?>
            <div class="hero-left">
                <div class="greeting">Welcome to my portfolio</div>
                <h1 class="name" aria-label="<?php echo isset($portfolio_data['hero_title']) ? htmlspecialchars($portfolio_data['hero_title']) : ''; ?>">
                    <span class="typed" data-text="<?php echo isset($portfolio_data['hero_title']) ? htmlspecialchars($portfolio_data['hero_title']) : ''; ?>"></span>
                    <span class="typing-cursor" aria-hidden="true"></span>
                </h1>
                <p class="subtitle"><?php echo isset($portfolio_data['hero_subtitle']) ? htmlspecialchars($portfolio_data['hero_subtitle']) : ''; ?></p>
                
                <div class="socials">
                    <?php if (!empty($portfolio_data['github_url'])): ?>
                    <a href="<?php echo htmlspecialchars($portfolio_data['github_url']); ?>" target="_blank" rel="noopener noreferrer" title="GitHub">
                        <i class="fab fa-github"></i>
                    </a>
                    <?php endif; ?>
                    <?php if (!empty($portfolio_data['linkedin_url'])): ?>
                    <a href="<?php echo htmlspecialchars($portfolio_data['linkedin_url']); ?>" target="_blank" rel="noopener noreferrer" title="LinkedIn">
                        <i class="fab fa-linkedin"></i>
                    </a>
                    <?php endif; ?>
                    <?php if (!empty($portfolio_data['email'])): ?>
                    <a href="mailto:<?php echo htmlspecialchars($portfolio_data['email']); ?>" title="Email">
                        <i class="fas fa-envelope"></i>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</header>


<main class="py-1">
    <section id="projects">
        <div class="container">
            <div class="section-header">
                <h2>Projects</h2>
                <p>Explore some of my recent work and projects</p>
            </div>

            <div class="row g-4">
                <!-- Featured projects from database -->
                <div class="col-12">
                    <div class="featured-projects-scroll">
                    <?php 
                        $featured_projects = [];
                        $regular_projects = [];
                        $has_aconnect = false;
                        $has_crud = false;
                        
                        if (!empty($projects) && is_array($projects)) {
                            foreach ($projects as $p) {
                                // Check if project is featured - must be explicitly 1 or true
                                $is_featured = (isset($p['featured']) && ($p['featured'] == 1 || $p['featured'] === true )) ? true : false;
                                
                                if ($is_featured) {
                                    $featured_projects[] = $p;
                                } else {
                                    $regular_projects[] = $p;
                                }
                            }
                        }
                        
                        // Display featured projects from database
                        foreach ($featured_projects as $fp):
                            $ftitle = isset($fp['title']) ? $fp['title'] : 'Featured Project';
                            $fdesc = isset($fp['description']) ? $fp['description'] : '';
                            $fimg = isset($fp['image']) && $fp['image'] ? $fp['image'] : 'https://via.placeholder.com/1200x450?text=Featured';
                            // Apply base_url() to relative paths for uploaded images
                            if ($fimg && strpos($fimg, 'http') !== 0 && strpos($fimg, '//') !== 0) {
                                $fimg = base_url($fimg);
                            }
                            $furl = isset($fp['url']) ? $fp['url'] : '#';
                            
                            // Decode type field
                            $ftypes = [];
                            if (isset($fp['type']) && $fp['type']) {
                                if (is_array($fp['type'])) {
                                    $ftypes = $fp['type'];
                                } else {
                                    $raw = trim((string) $fp['type']);
                                    $decoded = json_decode($raw, true);
                                    if (is_array($decoded)) {
                                        $ftypes = $decoded;
                                    } elseif ($raw !== '') {
                                        $ftypes = array_filter(array_map('trim', explode(',', $raw)));
                                    }
                                }
                            }
                            
                            $ftypeLabels = [
                                'php' => 'PHP', 'javascript' => 'JS', 'html_css' => 'HTML/CSS',
                                'nodejs' => 'Node', 'react' => 'React', 'vue' => 'Vue',
                                'angular' => 'Angular', 'uiux' => 'UI', 'cli' => 'CLI',
                                'devops' => 'DevOps', 'other' => 'Other'
                            ];
                    ?>
                        <div class="card project-card featured h-100">
                            <div class="badge-featured" aria-hidden="true">Featured</div>
                            <a href="<?php echo htmlspecialchars($furl); ?>" target="_blank" rel="noopener" class="stretched-link link-overlay">
                                <img src="<?php echo htmlspecialchars($fimg); ?>" class="card-img-top project-img featured-img" alt="<?php echo htmlspecialchars($ftitle); ?>">
                            </a>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($ftitle); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($fdesc); ?></p>
                                <div class="project-tags">
                                    <?php
                                        foreach ($ftypes as $ft) {
                                            $key = trim((string) $ft);
                                            if ($key === '') continue;
                                            $label = isset($ftypeLabels[$key]) ? $ftypeLabels[$key] : $key;
                                            echo '<span class="tag">' . htmlspecialchars($label) . '</span>';
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Regular projects -->
                <?php if (!empty($regular_projects) && is_array($regular_projects)): ?>
                    <?php
                        $crud_project = null;
                    ?>
                    <?php foreach ($regular_projects as $p): 
                        $title = isset($p['title']) ? $p['title'] : 'Untitled';
                        $desc  = isset($p['description']) ? $p['description'] : '';
                        $img   = isset($p['image']) && $p['image'] ? $p['image'] : 'https://via.placeholder.com/800x450?text=Project';
                        // Apply base_url() to relative paths for uploaded images
                        if ($img && strpos($img, 'http') !== 0 && strpos($img, '//') !== 0) {
                            $img = base_url($img);
                        }
                        $url   = isset($p['url']) ? $p['url'] : '#';
                        // Compute image basename
                        $img_basename_check = '';
                        $tmp = basename(parse_url($img, PHP_URL_PATH) ?: $img);
                        if ($tmp) $img_basename_check = strtolower($tmp);
                        $tags  = isset($p['tags']) && is_array($p['tags']) ? $p['tags'] : [];

                        if (stripos($title, 'crud') !== false) { $crud_project = $p; continue; }

                    ?>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card project-card h-100">
                                <a href="<?php echo htmlspecialchars($url); ?>" target="_blank" rel="noopener" class="stretched-link link-overlay">
                                <?php $img_basename = basename(parse_url($img, PHP_URL_PATH) ?: $img); ?>
                                <img src="<?php echo htmlspecialchars($img); ?>" class="card-img-top project-img" alt="<?php echo htmlspecialchars($title); ?>" data-basename="<?php echo htmlspecialchars($img_basename); ?>">
                            </a>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($title); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($desc); ?></p>
                                <?php
                                    // Normalize types (may be array, CSV string, JSON array, or from database)
                                    $types = [];
                                    // Accept multiple possible fields that might contain type info
                                    $typeSource = null;
                                    if (isset($p['type'])) $typeSource = $p['type'];
                                    elseif (isset($p['types'])) $typeSource = $p['types'];

                                    if ($typeSource !== null) {
                                        if (is_array($typeSource)) {
                                            $types = $typeSource;
                                        } else {
                                            $raw = trim((string) $typeSource);
                                            // Try JSON decode first (handles database JSON field or form submission)
                                            $decoded = null;
                                            if ($raw !== '') {
                                                $json = json_decode($raw, true);
                                                if (json_last_error() === JSON_ERROR_NONE && is_array($json)) {
                                                    $decoded = $json;
                                                }
                                            }
                                            if (is_array($decoded)) {
                                                $types = $decoded;
                                            } elseif ($raw !== '') {
                                                // Fallback to CSV parsing
                                                $types = array_filter(array_map('trim', explode(',', $raw)));
                                            }
                                        }
                                    }

                                    $typeLabels = [
                                        'php' => 'PHP',
                                        'javascript' => 'JS',
                                        'html_css' => 'HTML/CSS',
                                        'nodejs' => 'Node',
                                        'react' => 'React',
                                        'vue' => 'Vue',
                                        'angular' => 'Angular',
                                        'uiux' => 'UI',
                                        'cli' => 'CLI',
                                        'devops' => 'DevOps',
                                        'other' => 'Other'
                                    ];
                                ?>
                                <div class="project-tags">
                                    <?php
                                        // Render types first, mapping keys to readable labels
                                        $printed = [];
                                        foreach ($types as $tt) {
                                            $key = trim((string) $tt);
                                            if ($key === '') continue;
                                            $label = isset($typeLabels[$key]) ? $typeLabels[$key] : $key;
                                            $printed[strtolower($label)] = true;
                                            echo '<span class="tag">' . htmlspecialchars($label) . '</span>';
                                        }

                                        // Render tags but avoid duplicates of types
                                        foreach ($tags as $t) {
                                            $tag = trim((string) $t);
                                            if ($tag === '') continue;
                                            if (isset($printed[strtolower($tag)])) continue;
                                            // Also skip if tag matches one of the raw type keys (case-insensitive)
                                            $match = false;
                                            foreach ($types as $tt) { if (strcasecmp(trim((string)$tt), $tag) === 0) { $match = true; break; } }
                                            if ($match) continue;
                                            echo '<span class="tag">' . htmlspecialchars($tag) . '</span>';
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php
                        // After rendering other projects, output the CRUD card if it was present
                        if ($crud_project !== null) {
                            $cp = $crud_project;
                            $ctitle = isset($cp['title']) ? $cp['title'] : 'CRUD';
                            $cdesc  = isset($cp['description']) ? $cp['description'] : '';
                            $cimg   = isset($cp['image']) && $cp['image'] ? $cp['image'] : 'https://via.placeholder.com/800x450?text=CRUD';
                            // Apply base_url() to relative paths for uploaded images
                            if ($cimg && strpos($cimg, 'http') !== 0 && strpos($cimg, '//') !== 0) {
                                $cimg = base_url($cimg);
                            }
                            $curl   = isset($cp['url']) ? $cp['url'] : '#';
                    ?>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card project-card h-100">
                            <a href="<?php echo htmlspecialchars($curl); ?>" target="_blank" rel="noopener" class="stretched-link link-overlay">
                                <img src="<?php echo htmlspecialchars($cimg); ?>" class="card-img-top project-img" alt="<?php echo htmlspecialchars($ctitle); ?>">
                            </a>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($ctitle); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($cdesc); ?></p>
                                <div class="project-tags">
                                    <?php
                                        // Decode type field for CRUD if available
                                        $ctypes = [];
                                        if (isset($cp['type']) && $cp['type']) {
                                            if (is_array($cp['type'])) {
                                                $ctypes = $cp['type'];
                                            } else {
                                                $craw = trim((string) $cp['type']);
                                                $cdecoded = json_decode($craw, true);
                                                if (is_array($cdecoded)) {
                                                    $ctypes = $cdecoded;
                                                } elseif ($craw !== '') {
                                                    $ctypes = array_filter(array_map('trim', explode(',', $craw)));
                                                }
                                            }
                                        }
                                        
                                        $ctypeLabels = [
                                            'php' => 'PHP', 'javascript' => 'JS', 'html_css' => 'HTML/CSS',
                                            'nodejs' => 'Node', 'react' => 'React', 'vue' => 'Vue',
                                            'angular' => 'Angular', 'uiux' => 'UI', 'cli' => 'CLI',
                                            'devops' => 'DevOps', 'other' => 'Other'
                                        ];
                                        
                                        if (!empty($ctypes)) {
                                            foreach ($ctypes as $ct) {
                                                $ckey = trim((string) $ct);
                                                if ($ckey === '') continue;
                                                $clabel = isset($ctypeLabels[$ckey]) ? $ctypeLabels[$ckey] : $ckey;
                                                echo '<span class="tag">' . htmlspecialchars($clabel) . '</span>';
                                            }
                                        } else {
                                            // Fallback to hardcoded if no type field
                                            echo '<span class="tag">JS</span>';
                                            echo '<span class="tag">UI</span>';
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-info mb-0">
                            <strong>No projects yet.</strong> Add projects in the admin panel to showcase your work.
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section id="about">
        <div class="container">
            <div class="section-header">
                <h2>About Me</h2>
                <p>Get to know me better and my academic journey</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-6">
                    <h3 class="column-title">About Me</h3>
                    <div class="about-content">
                        <p><?php echo isset($portfolio_data['about_content']) ? htmlspecialchars($portfolio_data['about_content']) : ''; ?></p>
                    </div>
                </div>

                <div class="col-lg-6" id="education">
                    <h3 class="column-title">Education</h3>
                    <div class="education-card">
                        <div class="education-list">
                            <?php
                            $education_items = [
                                'education_elementary' => 'Elementary School',
                                'education_high_school' => 'High School',
                                'education_senior_high' => 'Senior High School',
                                'education_college' => 'College / University',
                                'education_certification' => 'Certification'
                            ];
                            
                            foreach ($education_items as $key => $label) {
                                $value = isset($portfolio_data[$key]) && !empty($portfolio_data[$key]) ? htmlspecialchars($portfolio_data[$key]) : null;
                                if ($value) {
                                    echo '<div class="education-item">';
                                    echo '<strong>' . htmlspecialchars($label) . ':</strong> ' . $value;
                                    echo '</div>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="skills">
        <div class="container">
            <div class="section-header">
                <h2>My Skills</h2>
                <p>Technologies and tools I work with</p>
            </div>

            

            <!-- Horizontal skill rows (existing layout) -->
            <div class="skills-grid rows">
                    <?php if (isset($portfolio_data['skills']) && is_array($portfolio_data['skills'])): ?>
                        <?php foreach ($portfolio_data['skills'] as $skill): ?>
                    <div class="skill-row">
                        <div class="skill-head">
                            <div class="skill-label"><?php echo htmlspecialchars($skill['name']); ?></div>
                            <div class="skill-value"><?php echo htmlspecialchars($skill['percent']); ?>%</div>
                        </div>
                        <div class="skill-bar">
                            <div class="skill-bar-track">
                                <div class="skill-bar-fill" data-percent="<?php echo htmlspecialchars($skill['percent']); ?>"></div>
                            </div>
                        </div>
                    </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                </div>
        </div>
    </section>

    <section id="contact">
        <div class="container">
            <div class="section-header">
                <h2>Get in Touch</h2>
                <p>Let's connect and discuss your next project</p>
            </div>
            <?php if (!empty($contact_success)): ?>
                <div class="row">
                    <div class="col-lg-8 mx-auto">
                        <div class="alert alert-success alert-dismissible fade show">
                            <strong>Success!</strong> <?php echo htmlspecialchars($contact_success); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="contact-card">
                        <div class="card-body">
                            <div class="row">
                                <?php if (!empty($portfolio_data['contacts']) && is_array($portfolio_data['contacts'])): ?>
                                    <?php foreach ($portfolio_data['contacts'] as $contact): ?>
                                    <?php
                                    // Map contact types to Font Awesome icons
                                    $iconMap = [
                                        'Email' => 'fas fa-envelope',
                                        'Phone' => 'fas fa-phone',
                                        'GitHub' => 'fab fa-github',
                                        'LinkedIn' => 'fab fa-linkedin',
                                        'Twitter' => 'fab fa-twitter',
                                        'Facebook' => 'fab fa-facebook',
                                        'Instagram' => 'fab fa-instagram',
                                        'YouTube' => 'fab fa-youtube',
                                        'TikTok' => 'fab fa-tiktok',
                                        'Discord' => 'fab fa-discord',
                                        'Telegram' => 'fab fa-telegram',
                                        'WhatsApp' => 'fab fa-whatsapp',
                                        'Website' => 'fas fa-globe',
                                        'Portfolio' => 'fas fa-briefcase',
                                        'Address' => 'fas fa-map-marker-alt',
                                        'Skype' => 'fab fa-skype',
                                        'Slack' => 'fab fa-slack',
                                        'Other' => 'fas fa-link'
                                    ];
                                    $iconClass = isset($iconMap[$contact['type']]) ? $iconMap[$contact['type']] : 'fas fa-address-card';
                                    ?>
                                    <div class="col-md-6">
                                        <div class="contact-item">
                                            <strong><i class="<?php echo $iconClass; ?> me-2"></i><?php echo htmlspecialchars($contact['type']); ?></strong>
                                            <?php 
                                            $value = $contact['value'];
                                            $contactType = strtolower($contact['type']);
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
                                    <?php endforeach; ?>
                                <?php else: ?>
                                <div class="col-12">
                                    <p class="text-center text-muted">No contact information available.</p>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<footer>
    <div class="container">
        <p>&copy; <?php echo date('Y'); ?> <?php echo isset($site_title) ? htmlspecialchars($site_title) : 'Miguel Andrei del Rosario'; ?>. All rights reserved.</p>
        <div class="mt-2 footer-credit">
            <p>Crafted with <i class="fas fa-heart heart-icon"></i> for excellence in web development</p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="<?php echo htmlspecialchars(function_exists('base_url') ? base_url('assets/js/portfolio.js') : '/assets/js/portfolio.js'); ?>" data-asset-path="<?php echo htmlspecialchars(base_url('assets/img/'), ENT_QUOTES, 'UTF-8'); ?>"></script>
</body>
</html>