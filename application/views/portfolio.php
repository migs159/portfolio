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
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
<?php if (function_exists('get_instance')) {
    $ci = &get_instance();
    $ci->load->view('partials/header_public');
} else {
    if (isset($this) && method_exists($this->load, 'view')) {
        $this->load->view('partials/header_public');
    }

}
?>

<header class="hero">
    <div class="container">
        <?php if (function_exists('get_instance')) {
            $ci = &get_instance();
            // Load partial that defines $initial and profile variables and prints the hero-initial and image
            $ci->load->view('partials/vars');
        } else {
            // Fallback if $this is available in the view
            if (isset($this) && method_exists($this->load, 'view')) {
                $this->load->view('partials/vars');
            }
        }
        ?>
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

            <div class="projects-panel">
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
                            // Render featured project via partial
                            if (function_exists('get_instance')) {
                                $ci = &get_instance();
                                $ci->load->view('partials/project_card', ['project' => $fp, 'featured' => true]);
                            } else {
                                if (isset($this) && method_exists($this->load, 'view')) {
                                    $this->load->view('partials/project_card', ['project' => $fp, 'featured' => true]);
                                }
                            }
                        ?>
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
                        if (stripos($title, 'crud') !== false) { $crud_project = $p; continue; }
                    ?>
                    <div class="col-12 col-md-6 col-lg-4">
                        <?php
                            if (function_exists('get_instance')) {
                                $ci = &get_instance();
                                $ci->load->view('partials/project_card', ['project' => $p, 'featured' => false]);
                            } else {
                                if (isset($this) && method_exists($this->load, 'view')) {
                                    $this->load->view('partials/project_card', ['project' => $p, 'featured' => false]);
                                }
                            }
                        ?>
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
            </div><!-- /.projects-panel -->
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
                                            if (function_exists('get_instance')) {
                                                $ci = &get_instance();
                                                $ci->load->view('partials/contact_item', ['contact' => $contact]);
                                            } else {
                                                if (isset($this) && method_exists($this->load, 'view')) {
                                                    $this->load->view('partials/contact_item', ['contact' => $contact]);
                                                }
                                            }
                                        ?>
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

<?php if (function_exists('get_instance')) {
    $ci = &get_instance();
    $ci->load->view('partials/footer');
} else {
    if (isset($this) && method_exists($this->load, 'view')) {
        $this->load->view('partials/footer');
    }
    }
    ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="<?php echo htmlspecialchars(function_exists('base_url') ? base_url('assets/js/portfolio.js') : '/assets/js/portfolio.js'); ?>" data-asset-path="<?php echo htmlspecialchars(base_url('assets/img/'), ENT_QUOTES, 'UTF-8'); ?>"></script>
</body>
</html>