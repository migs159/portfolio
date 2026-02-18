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
    <meta name="viewport" content="width=device-width,initial-scale=1">
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
                <li class="nav-item"><a class="nav-link" href="http://localhost/portfolio/index.php/portfolio">Home</a></li>
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
                        $profile_rel = 'assets/img/profile.png';
                        $profile_file = defined('FCPATH') ? rtrim(FCPATH, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.$profile_rel : NULL;
                        $profile_exists = $profile_file ? file_exists($profile_file) : false;
                        $profile_url = $profile_exists && function_exists('base_url') ? base_url($profile_rel) : (function_exists('base_url') ? base_url('assets/img/profile.png') : '/assets/img/profile.png');
                    ?>
                    <img src="<?php echo htmlspecialchars($profile_url); ?>" alt="Miguel Andrei Portrait" class="hero-profile" id="hero-profile-img" onerror="console.warn('Profile image failed to load:', this.src);this.style.display='none';document.getElementById('hero-initial').style.display='flex'">
                    <?php if (!$profile_exists): ?>
                        <div class="profile-debug">Debug: profile image file not found on server at <strong><?php echo htmlspecialchars($profile_file ?: 'unknown'); ?></strong></div>
                    <?php endif; ?>
            <div class="hero-left">
                <div class="greeting">Welcome to my portfolio</div>
                <h1 class="name" aria-label="Miguel Andrei del Rosario">
                    <span class="typed" data-text="Miguel Andrei del Rosario"></span>
                    <span class="typing-cursor" aria-hidden="true"></span>
                </h1>
                <p class="subtitle">A Web Developer Trainee</p>
                
                <div class="socials">
                    <a href="https://github.com/migs159" target="_blank" rel="noopener noreferrer" title="GitHub">
                        <i class="fab fa-github"></i>
                    </a>
                    <a href="https://www.linkedin.com/in/miguel-andrei-del-rosario-a291693b1/" target="_blank" rel="noopener noreferrer" title="LinkedIn">
                        <i class="fab fa-linkedin"></i>
                    </a>
                    <a href="mailto:miguelandrei@sdca.edu.ph" title="Email">
                        <i class="fas fa-envelope"></i>
                    </a>
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
                <div class="col-12">
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
                </div>
                <?php endforeach; ?>
                
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
                <p>Get to know me better</p>
            </div>
            <div class="row">
                <div class="col-lg-8">
                    <div class="about-content">
                        <p>I'm a motivated Information Technology student passionate about creating innovative web solutions. Currently working on completing my On-the-Job Training (OJT) to gain hands-on experience in a real-world tech environment.</p>
                        <p>I specialize in front-end development and have a solid foundation in web technologies. I'm dedicated to continuous learning and always excited to tackle new challenges and contribute meaningfully to any organization.</p>
                        <p>When I'm not coding, you can find me exploring new technologies, contributing to open-source projects, or working on personal projects to expand my skill set.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="education">
        <div class="container">
            <div class="section-header">
                <h2>Education</h2>
                <p>Academic background and qualifications</p>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="education-card">
                        <div class="edu-timeline">
                            <div class="edu-item">
                                <div class="edu-initial" aria-hidden="true">T</div>
                                <div class="edu-year">2008 &ndash; 2014</div>
                                <div class="edu-body">
                                    <div class="edu-school">Talon Elementary School</div>
                                    <div class="edu-meta">Graduated 2014</div>
                                </div>
                            </div>

                            <div class="edu-item">
                                <div class="edu-initial" aria-hidden="true">C</div>
                                <div class="edu-year">2015 &ndash; 2019</div>
                                <div class="edu-body">
                                    <div class="edu-school">City of Bacoor National High School &mdash; Springville Campus</div>
                                    <div class="edu-meta">Graduated 2019</div>
                                </div>
                            </div>

                            <div class="edu-item">
                                <div class="edu-initial" aria-hidden="true">L</div>
                                <div class="edu-year">2020 &ndash; 2021</div>
                                <div class="edu-body">
                                    <div class="edu-school">Las Piñas City National Senior High School &mdash; Doña Josefa Campus</div>
                                    <div class="edu-meta">ICT Strand  &mdash; Graduated 2021</div>
                                </div>
                            </div>

                            <div class="edu-item">
                                <div class="edu-initial" aria-hidden="true">S</div>
                                <div class="edu-year">2023 &ndash; 2026</div>
                                <div class="edu-body">
                                    <div class="edu-school">St. Dominic College of Asia</div>
                                    <div class="edu-meta">B.S. Information Technology &mdash; Expected Graduation 2026</div>
                                </div>
                            </div>
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
                    <div class="skill-row">
                        <div class="skill-head">
                                <div class="skill-label">HTML5 / CSS3</div>
                                <div class="skill-value" data-percent="90"></div>
                            </div>
                        <div class="skill-bar">
                            <div class="skill-bar-track">
                                <div class="skill-bar-fill" data-percent="90"></div>
                            </div>
                        </div>
                    </div>

                    <div class="skill-row">
                        <div class="skill-head">
                            <div class="skill-label">JavaScript</div>
                            <div class="skill-value" data-percent="80"></div>
                        </div>
                        <div class="skill-bar">
                            <div class="skill-bar-track">
                                <div class="skill-bar-fill" data-percent="80"></div>
                            </div>
                        </div>
                    </div>

                    

                    <div class="skill-row">
                        <div class="skill-head">
                            <div class="skill-label">GitHub</div>
                            <div class="skill-value" data-percent="92"></div>
                        </div>
                        <div class="skill-bar">
                            <div class="skill-bar-track">
                                <div class="skill-bar-fill" data-percent="92"></div>
                            </div>
                        </div>
                    </div>

                    <div class="skill-row">
                        <div class="skill-head">
                            <div class="skill-label">CodeIgniter</div>
                            <div class="skill-value" data-percent="70"></div>
                        </div>
                        <div class="skill-bar">
                            <div class="skill-bar-track">
                                <div class="skill-bar-fill" data-percent="70"></div>
                            </div>
                        </div>
                    </div>

                    <div class="skill-row">
                        <div class="skill-head">
                            <div class="skill-label">PHP</div>
                            <div class="skill-value" data-percent="70"></div>
                        </div>
                        <div class="skill-bar">
                            <div class="skill-bar-track">
                                <div class="skill-bar-fill" data-percent="70"></div>
                            </div>
                        </div>
                    </div>

                    <div class="skill-row">
                        <div class="skill-head">
                            <div class="skill-label">MySQL</div>
                            <div class="skill-value" data-percent="60"></div>
                        </div>
                        <div class="skill-bar">
                            <div class="skill-bar-track">
                                <div class="skill-bar-fill" data-percent="60"></div>
                            </div>
                        </div>
                    </div>


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
                                <div class="col-md-6">
                                    <div class="contact-item">
                                        <strong><i class="fas fa-envelope me-2"></i>Email</strong>
                                        <a href="mailto:miguelandrei@sdca.edu.ph">miguelandrei@sdca.edu.ph</a>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="contact-item">
                                        <strong><i class="fas fa-mobile-alt me-2"></i>Phone</strong>
                                        <p>639096059630</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="contact-item">
                                        <strong><i class="fab fa-github me-2"></i>GitHub</strong>
                                        <a href="https://github.com/migs159" target="_blank" rel="noopener noreferrer">github.com/migs159</a>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="contact-item">
                                        <strong><i class="fab fa-linkedin me-2"></i>LinkedIn</strong>
                                        <a href="https://www.linkedin.com/in/miguel-andrei-del-rosario-a291693b1/" target="_blank" rel="noopener noreferrer">linkedin.com/in/miguel-andrei-del-rosario-a291693b1</a>
                                    </div>
                                </div>
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
<script>
document.addEventListener('DOMContentLoaded', function(){
    // helper: animate numeric count from 0 to target
    // animateCount(el, target, duration, fromOverride, onComplete)
    // - el: element to update (text content will be set to '<n>%')
    // - target: final percent number
    // - duration: ms (optional)
    // - fromOverride: numeric start value (optional)
    // - onComplete: function called after finishing (optional)
    function animateCount(el, target, duration, fromOverride, onComplete){
        if (!el) return;
        target = Math.max(0, Math.min(100, parseInt(target,10)||0));
        duration = duration || 1400;
        const start = performance.now();
        const raw = (el.textContent||'').replace('%','').trim();
        let from = Number.isFinite(fromOverride) ? fromOverride : parseInt(raw, 10);
        if (!Number.isFinite(from) || isNaN(from)) from = 0;
        function easeOutCubic(t){ return 1 - Math.pow(1-t,3); }
        function frame(now){
            const t = Math.min(1, (now - start)/duration);
            const v = Math.round(from + (target - from)*easeOutCubic(t));
            el.textContent = v + '%';
            if (t < 1) requestAnimationFrame(frame);
            else if (typeof onComplete === 'function') onComplete();
        }
        requestAnimationFrame(frame);
    }
    var imgs = document.querySelectorAll('.project-img');
    imgs.forEach(function(img){
        img.addEventListener('error', function handler(){
            var basename = img.dataset.basename || '';
            var assetBase = '<?php echo base_url('assets/img/'); ?>';
            var tries = [];

            if (basename) {
                var m = basename.match(/^(.*)\.(\w+)$/);
                if (m) {
                    var name = m[1];
                    var ext = m[2];
                    tries.push(assetBase + name + '@2x.' + ext);
                    tries.push(assetBase + name + '-2x.' + ext);
                    tries.push(assetBase + name + '.' + ext);
                    tries.push(assetBase + name + '.webp');
                } else {
                    tries.push(assetBase + basename);
                }
            }
            tries.push(assetBase + 'project-fallback.png');
            tries.push('https://via.placeholder.com/1200x800?text=No+Image');

            // set srcset when possible for high-DPI displays
            try {
                if (basename) {
                    var m2 = basename.match(/^(.*)\.(\w+)$/);
                    if (m2) {
                        var n = m2[1], e = m2[2];
                        img.setAttribute('srcset', assetBase + n + '@2x.' + e + ' 2x, ' + assetBase + basename + ' 1x');
                        img.setAttribute('decoding', 'async');
                        img.setAttribute('loading', 'lazy');
                    }
                }
            } catch (err) {}

            var i = 0;
            img.removeEventListener('error', handler);
            function next(){
                if(i >= tries.length) return;
                img.onerror = null;
                img.src = tries[i++];
                img.onerror = next;
            }
            next();
        });
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function(){
    // Navbar scroll effect
    const navbar = document.querySelector('.navbar');
    window.addEventListener('scroll', function(){
        if (window.scrollY > 100){
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e){
            const href = this.getAttribute('href');
            if (href !== '#' && document.querySelector(href)){
                e.preventDefault();
                document.querySelector(href).scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
                
                // Close navbar if open on mobile
                const navbarToggle = document.querySelector('.navbar-toggler');
                if (navbarToggle.offsetParent !== null){
                    navbarToggle.click();
                }
            }
        });
    });

    // Fade in animations on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries){
        entries.forEach(entry => {
            if (entry.isIntersecting){
                entry.target.classList.add('fade-in');
                // animate any meter/bar fills inside this section
                try {
                    // old meter support
                    const oldFill = entry.target.querySelector('.skill-meter-fill');
                    if (oldFill) {
                        const pct = parseInt(oldFill.getAttribute('data-percent') || '0', 10) || 0;
                        setTimeout(() => { oldFill.style.width = pct + '%'; }, 60);
                    }

                    // new bar support: do not auto-fill bars on reveal — bars appear only on hover
                } catch (e) {}
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.project-card, .skill-row, .about-content').forEach(el => {
        observer.observe(el);
    });

    // Hover interactions: ensure new bars animate on hover and show value visually
    try {
        // Populate visible percentage numbers from data-percent so values are shown by default
        document.querySelectorAll('.skill-value').forEach(function(v){
            try {
                var dp = v.getAttribute('data-percent');
                if (dp !== null && dp !== undefined && dp !== '') v.textContent = (parseInt(dp,10)||0) + '%';
            } catch(e){}
        });
        document.querySelectorAll('.skill-row').forEach(row => {
            const fill = row.querySelector('.skill-bar-fill');
            const valueEl = row.querySelector('.skill-value');
            const pct = fill ? parseInt(fill.getAttribute('data-percent') || '0', 10) : 0;

            row.addEventListener('mouseenter', () => {
                if (fill) {
                    // Set CSS custom property for animation
                    fill.style.setProperty('--skill-percent', pct + '%');
                    fill.classList.add('animate-fill');
                }
                    if (valueEl) {
                        // ensure label stays visible (we now show numbers by default)
                        valueEl.style.opacity = '1';
                        valueEl.style.transform = 'translateY(0)';
                        // create a transient animated span so original number remains visible
                        try {
                            if (!row._animSpan) {
                                var anim = document.createElement('span');
                                anim.className = 'skill-value-anim';
                                anim.textContent = '';
                                row._animSpan = anim;
                                row.querySelector('.skill-head').appendChild(anim);
                                var target = parseInt(valueEl.getAttribute('data-percent')||pct,10) || 0;
                                // animate from 0 to target and then remove transient and ensure original shows target
                                animateCount(anim, target, 1400, 0, function(){
                                    try { if (row._animSpan) { row._animSpan.remove(); row._animSpan = null; } } catch(e){}
                                    try { valueEl.textContent = target + '%'; } catch(e){}
                                });
                            }
                        } catch(e){}
                    }
            });

                row.addEventListener('mouseleave', () => {
                    if (fill) {
                        // Remove animation class to reset
                        fill.classList.remove('animate-fill');
                    }
                    if (valueEl) {
                            valueEl.style.opacity = '';
                            valueEl.style.transform = '';
                            // cleanup transient animator if present
                            try { if (row._animSpan) { row._animSpan.remove(); row._animSpan = null; } } catch(e){}
                        }
                });

        });
    } catch (err) {}
});
</script>
<script>
// Typing animation for hero name
document.addEventListener('DOMContentLoaded', function(){
    try {
        var typedEl = document.querySelector('.name .typed');
        if (!typedEl) return;
        var text = typedEl.getAttribute('data-text') || typedEl.textContent || '';
        // ensure accessible name remains via aria-label on the h1
        typedEl.textContent = '';
        var idx = 0;
        var speed = 80; // ms per character
        function step(){
            if (idx <= text.length){
                typedEl.textContent = text.slice(0, idx);
                idx++;
                setTimeout(step, speed);
            }
        }
        // small delay so other hero animations run first
        setTimeout(step, 220);
    } catch (e) { console.warn('Typing animation error', e); }
});
</script>
</body>
</html>