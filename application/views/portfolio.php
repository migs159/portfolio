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
    <style>
        * { scroll-behavior: smooth; }
        :root{--primary:#6366f1;--primary-dark:#4f46e5;--secondary:#ec4899;--accent:#111827;--muted:#6b7280;--light-bg:#f8fafc;--surface:#ffffff}
        
        html { scroll-behavior: smooth; }
        body {
            font-family:'Inter',system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial;
            padding-top:70px;
            background:#ffffff;
            color:#1e293b;
            position:relative;
            overflow-x:hidden;
        }
        
        /* Smooth gradient background */
        body::before {
            content:'';
            position:fixed;
            inset:0;
            background:radial-gradient(circle at 20% 50%,rgba(99,102,241,0.1),transparent 50%),
                        radial-gradient(circle at 80% 80%,rgba(236,72,153,0.08),transparent 50%);
            pointer-events:none;
            z-index:-1;
        }

        /* Modern navbar */
        .navbar {
            background:rgba(255,255,255,0.95) !important;
            backdrop-filter:blur(10px);
            border-bottom:1px solid rgba(0,0,0,0.05);
            z-index:1200;
            transition:all 0.3s ease;
        }
        
        .navbar.scrolled {
            box-shadow:0 4px 20px rgba(0,0,0,0.08);
        }
        
        .navbar-brand {
            font-weight:700;
            font-size:1.3rem;
            background:linear-gradient(135deg,var(--primary),var(--secondary));
            -webkit-background-clip:text;
            -webkit-text-fill-color:transparent;
            background-clip:text;
        }
        
        .navbar .nav-link {
            color:#64748b !important;
            font-weight:500;
            font-size:0.95rem;
            position:relative;
            transition:color 0.3s ease;
        }
        
        .navbar .nav-link::after {
            content:'';
            position:absolute;
            bottom:-4px;
            left:0;
            width:0;
            height:2px;
            background:var(--primary);
            transition:width 0.3s ease;
        }
        
        .navbar .nav-link:hover {
            color:var(--primary) !important;
        }
        
        .navbar .nav-link:hover::after {
            width:100%;
        }
        
        .contact-btn {
            border-radius:8px;
            padding:0.6rem 1.3rem;
            background:var(--primary);
            color:#fff !important;
            border:0;
            font-weight:600;
            transition:all 0.3s ease;
            box-shadow:0 4px 15px rgba(99,102,241,0.3);
        }
        
        .contact-btn:hover {
            background:var(--primary-dark);
            transform:translateY(-2px);
            box-shadow:0 8px 25px rgba(99,102,241,0.4);
        }

        /* Hero Section */
        .hero {
            position:relative;
            min-height:90vh;
            overflow:hidden;
            z-index:0;
            background:linear-gradient(135deg,#ffffff 0%,var(--light-bg) 100%);
        }
        
        .hero-inner {
            display:flex;
            min-height:90vh;
            align-items:center;
            padding:4rem 0;
        }
        
        .hero-left {
            flex:1;
            padding:0 3rem;
            z-index:2;
            animation:fadeInLeft 0.8s ease-out;
        }
        
        @keyframes fadeInLeft {
            from {
                opacity:0;
                transform:translateX(-30px);
            }
            to {
                opacity:1;
                transform:translateX(0);
            }
        }
        
        .hero-left .greeting {
            color:var(--muted);
            font-weight:600;
            font-size:1rem;
            text-transform:uppercase;
            letter-spacing:2px;
            margin-bottom:1rem;
        }
        
        .hero-left .name {
            font-family:'Poppins',sans-serif;
            font-size:3.5rem;
            line-height:1.1;
            font-weight:800;
            color:var(--accent);
            margin:0;
            letter-spacing:-2px;
            background:linear-gradient(135deg,var(--accent),var(--primary));
            -webkit-background-clip:text;
            -webkit-text-fill-color:transparent;
            background-clip:text;
            margin-bottom:0.5rem;
        }
        
        .hero-left .subtitle {
            color:var(--muted);
            font-weight:500;
            font-size:1.2rem;
            margin-bottom:2rem;
            line-height:1.6;
        }
        
        .hero-right {
            flex:1;
            position:relative;
            display:flex;
            align-items:center;
            justify-content:center;
            padding:0 3rem;
            animation:fadeInRight 0.8s ease-out;
        }
        
        @keyframes fadeInRight {
            from {
                opacity:0;
                transform:translateX(30px);
            }
            to {
                opacity:1;
                transform:translateX(0);
            }
        }
        
        .hero-profile {
            position:relative;
            z-index:2;
            max-width:380px;
            width:100%;
            height:380px;
            border-radius:20px;
            object-fit:cover;
            box-shadow:0 25px 60px rgba(99,102,241,0.25);
            border:3px solid #fff;
            background:#e2e8f0;
            animation:float 3s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform:translateY(0px); }
            50% { transform:translateY(-20px); }
        }
        
        #hero-initial {
            display:none;
            position:relative;
            z-index:2;
            max-width:380px;
            width:100%;
            height:380px;
            border-radius:20px;
            align-items:center;
            justify-content:center;
            background:linear-gradient(135deg,var(--primary),var(--secondary));
            color:#fff;
            font-size:120px;
            font-weight:700;
            box-shadow:0 25px 60px rgba(99,102,241,0.25);
            border:3px solid #fff;
        }
        
        /* Social Links */
        .socials {
            margin-top:2.5rem;
            display:flex;
            gap:1rem;
        }
        
        .socials a {
            display:inline-flex;
            align-items:center;
            justify-content:center;
            width:50px;
            height:50px;
            border-radius:12px;
            background:#f1f5f9;
            color:var(--primary);
            text-decoration:none;
            transition:all 0.3s ease;
            border:2px solid transparent;
        }
        
        .socials a:hover {
            background:var(--primary);
            color:#fff;
            transform:translateY(-5px);
            box-shadow:0 10px 30px rgba(99,102,241,0.3);
        }

        /* Sections */
        section {
            padding:80px 0;
            position:relative;
            z-index:1;
        }
        
        section:nth-child(even) {
            background:var(--light-bg);
        }
        
        .container { max-width:1200px;margin:0 auto; }
        
        .section-header {
            margin-bottom:4rem;
            text-align:center;
        }
        
        .section-header h2 {
            font-family:'Poppins',sans-serif;
            font-size:2.5rem;
            font-weight:800;
            line-height:1.2;
            color:var(--accent);
            margin-bottom:1rem;
            background:linear-gradient(135deg,var(--accent),var(--primary));
            -webkit-background-clip:text;
            -webkit-text-fill-color:transparent;
            background-clip:text;
        }
        
        .section-header p {
            color:var(--muted);
            font-size:1.1rem;
            margin:0;
        }

        /* Projects Section */
        .project-card {
            background:#fff;
            border:0;
            border-radius:15px;
            overflow:hidden;
            box-shadow:0 4px 20px rgba(0,0,0,0.08);
            transition:all 0.4s ease;
            height:100%;
            display:flex;
            flex-direction:column;
            position:relative;
        }
        
        .project-card::after {
            content:'';
            position:absolute;
            top:0;
            left:0;
            right:0;
            height:4px;
            background:linear-gradient(90deg,var(--primary),var(--secondary));
            transform:scaleX(0);
            transform-origin:left;
            transition:transform 0.4s ease;
        }
        
        .project-card:hover::after {
            transform:scaleX(1);
        }
        
        .project-card:hover {
            transform:translateY(-8px);
            box-shadow:0 20px 50px rgba(99,102,241,0.2);
        }
        
        .project-card .card-img-top {
            height:220px;
            object-fit:cover;
            transition:transform 0.4s ease;
        }
        
        .project-card:hover .card-img-top {
            transform:scale(1.05);
        }
        
        .project-card .card-body {
            padding:1.75rem;
            flex-grow:1;
            display:flex;
            flex-direction:column;
        }
        
        .project-card .card-title {
            font-family:'Poppins',sans-serif;
            font-weight:700;
            color:var(--accent);
            margin-bottom:0.75rem;
            font-size:1.25rem;
        }
        
        .project-card .card-text {
            color:var(--muted);
            font-size:0.95rem;
            line-height:1.6;
            flex-grow:1;
            margin-bottom:1rem;
        }
        
        .project-tags {
            display:flex;
            flex-wrap:wrap;
            gap:0.5rem;
            margin-top:auto;
        }
        
        .project-tags .tag {
            display:inline-block;
            padding:0.4rem 0.8rem;
            background:linear-gradient(135deg,rgba(99,102,241,0.1),rgba(236,72,153,0.1));
            color:var(--primary);
            border-radius:6px;
            font-size:0.8rem;
            font-weight:600;
            border:1px solid rgba(99,102,241,0.2);
        }

        /* Skills Section */
        .skills-grid {
            display:grid;
            grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
            gap:2rem;
            margin-top:3rem;
        }
        
        .skill-item {
            padding:2rem;
            background:#fff;
            border-radius:12px;
            text-align:center;
            box-shadow:0 4px 15px rgba(0,0,0,0.05);
            transition:all 0.3s ease;
            border:1px solid #e2e8f0;
        }
        
        .skill-item:hover {
            transform:translateY(-5px);
            box-shadow:0 12px 35px rgba(99,102,241,0.15);
            border-color:var(--primary);
        }
        
        .skill-item i {
            font-size:2.5rem;
            color:var(--primary);
            margin-bottom:1rem;
        }
        
        .skill-item h3 {
            font-weight:700;
            color:var(--accent);
            margin-bottom:0.5rem;
        }
        
        .skill-item p {
            color:var(--muted);
            font-size:0.9rem;
            margin:0;
        }

        /* About Section */
        .about-content {
            background:#fff;
            padding:3rem;
            border-radius:15px;
            box-shadow:0 4px 20px rgba(0,0,0,0.08);
            line-height:1.8;
            color:var(--muted);
            font-size:1.05rem;
        }

        /* Contact Section */
        .contact-card {
            background:#fff;
            border:0;
            border-radius:15px;
            box-shadow:0 4px 20px rgba(0,0,0,0.08);
            transition:all 0.3s ease;
            overflow:hidden;
            position:relative;
        }
        
        .contact-card::before {
            content:'';
            position:absolute;
            top:0;
            left:0;
            right:0;
            height:4px;
            background:linear-gradient(90deg,var(--primary),var(--secondary));
        }
        
        .contact-card .card-body {
            padding:2.5rem;
            position:relative;
        }
        
        .contact-item {
            margin-bottom:2rem;
        }
        
        .contact-item:last-child {
            margin-bottom:0;
        }
        
        .contact-item strong {
            color:var(--accent);
            display:block;
            margin-bottom:0.5rem;
            font-weight:700;
        }
        
        .contact-item a {
            color:var(--primary);
            text-decoration:none;
            font-weight:500;
            transition:color 0.3s ease;
        }
        
        .contact-item a:hover {
            color:var(--secondary);
        }

        /* Footer */
        footer {
            background:var(--accent);
            color:#fff;
            padding:3rem 0;
            text-align:center;
            margin-top:4rem;
        }
        
        footer p {
            margin:0;
            opacity:0.8;
        }

        /* Responsive */
        @media (max-width:768px) {
            .hero-inner {
                flex-direction:column;
                padding:2rem 0;
            }
            
            .hero-left {
                padding:0 1.5rem;
            }
            
            .hero-right {
                padding:0 1.5rem;
                margin-top:2rem;
            }
            
            .hero-left .name {
                font-size:2.2rem;
            }
            
            .hero-profile, #hero-initial {
                max-width:280px;
                width:100%;
                height:280px;
            }
            
            .section-header h2 {
                font-size:1.8rem;
            }
            
            .skills-grid {
                grid-template-columns:1fr;
            }
            
            section {
                padding:4rem 0;
            }
        }

        /* Utility animations */
        .fade-in {
            animation:fadeInUp 0.6s ease-out forwards;
        }
        
        @keyframes fadeInUp {
            from {
                opacity:0;
                transform:translateY(30px);
            }
            to {
                opacity:1;
                transform:translateY(0);
            }
        }
    </style>
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
                <li class="nav-item"><a class="nav-link" href="#projects">Projects</a></li>
                <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                <li class="nav-item"><a class="nav-link" href="#skills">Skills</a></li>
                <?php
                    $ci = &get_instance();
                    $ci->load->library('session');
                    $logged = $ci->session->userdata('logged_in');
                ?>
                <?php if ($logged): ?>
                    <li class="nav-item"><a class="nav-link" href="<?php echo htmlspecialchars(function_exists('site_url') ? site_url('projects') : '/projects'); ?>">Admin</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo htmlspecialchars(function_exists('site_url') ? site_url('auth/logout') : '/auth/logout'); ?>">Logout</a></li>
                <?php endif; ?>
                <li class="nav-item ms-3 d-none d-lg-block"><a class="contact-btn" href="#contact">Get in Touch</a></li>
            </ul>
        </div>
    </div>
</nav>

<header class="hero">
    <div class="container">
        <div class="hero-inner">
            <div class="hero-left">
                <div class="greeting">Welcome to my portfolio</div>
                <h1 class="name">Miguel Andrei del Rosario</h1>
                <p class="subtitle">Information Technology Student</p>
                
                <div class="socials">
                    <a href="https://github.com/migs159" target="_blank" rel="noopener noreferrer" title="GitHub">
                        <i class="fab fa-github"></i>
                    </a>
                    <a href="mailto:miguelandrei@sdca.edu.ph" title="Email">
                        <i class="fas fa-envelope"></i>
                    </a>
                </div>
            </div>
            <div class="hero-right">
                <?php $initial = isset($site_title) ? strtoupper(substr(trim($site_title),0,1)) : 'M'; ?>
                <img src="/assets/img/profile.jpg" alt="Miguel Andrei Portrait" class="hero-profile" onerror="this.style.display='none';document.getElementById('hero-initial').style.display='flex'">
                <div id="hero-initial" aria-hidden="true"><?php echo htmlspecialchars($initial); ?></div>
            </div>
        </div>
    </div>
</header>


<main class="py-1">
    <section id="projects">
        <div class="container">
            <div class="section-header">
                <h2>Featured Projects</h2>
                <p>Explore some of my recent work and projects</p>
            </div>

            <div class="row g-4">
                <?php if (!empty($projects) && is_array($projects)): ?>
                    <?php foreach ($projects as $p): 
                        $title = isset($p['title']) ? $p['title'] : 'Untitled';
                        $desc  = isset($p['description']) ? $p['description'] : '';
                        $img   = isset($p['image']) && $p['image'] ? $p['image'] : 'https://via.placeholder.com/800x450?text=Project';
                        $url   = isset($p['url']) ? $p['url'] : '#';
                        $tags  = isset($p['tags']) && is_array($p['tags']) ? $p['tags'] : [];
                    ?>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card project-card h-100">
                            <a href="<?php echo htmlspecialchars($url); ?>" target="_blank" rel="noopener" class="stretched-link" style="z-index:10">
                                <img src="<?php echo htmlspecialchars($img); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($title); ?>">
                            </a>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($title); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($desc); ?></p>
                                <div class="project-tags">
                                    <?php foreach ($tags as $t): ?>
                                        <span class="tag"><?php echo htmlspecialchars($t); ?></span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
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
                <div class="col-lg-8 mx-auto">
                    <div class="about-content">
                        <p>I'm a motivated Information Technology student passionate about creating innovative web solutions. Currently working on completing my On-the-Job Training (OJT) to gain hands-on experience in a real-world tech environment.</p>
                        <p>I specialize in front-end development and have a solid foundation in web technologies. I'm dedicated to continuous learning and always excited to tackle new challenges and contribute meaningfully to any organization.</p>
                        <p>When I'm not coding, you can find me exploring new technologies, contributing to open-source projects, or working on personal projects to expand my skill set.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="skills">
        <div class="container">
            <div class="section-header">
                <h2>Technical Skills</h2>
                <p>Technologies and tools I work with</p>
            </div>
            <div class="skills-grid">
                <div class="skill-item">
                    <i class="fab fa-github"></i>
                    <h3>Version Control</h3>
                    <p>Git & GitHub for collaborative development</p>
                </div>
                <div class="skill-item">
                    <i class="fab fa-html5"></i>
                    <h3>HTML & CSS</h3>
                    <p>Modern semantic markup and responsive design</p>
                </div>
                <div class="skill-item">
                    <i class="fab fa-js"></i>
                    <h3>JavaScript</h3>
                    <p>Dynamic web interactions and frontend logic</p>
                </div>
                <div class="skill-item">
                    <i class="fab fa-php"></i>
                    <h3>PHP</h3>
                    <p>Server-side development and backend logic</p>
                </div>
                <div class="skill-item">
                    <i class="fas fa-server"></i>
                    <h3>CodeIgniter</h3>
                    <p>MVC framework for robust web applications</p>
                </div>
                <div class="skill-item">
                    <i class="fas fa-tools"></i>
                    <h3>Technical Support</h3>
                    <p>Computer troubleshooting and system administration</p>
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
                                        <p>09096059630</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="contact-item">
                                        <strong><i class="fab fa-github me-2"></i>GitHub</strong>
                                        <a href="https://github.com/migs159" target="_blank" rel="noopener noreferrer">github.com/migs159</a>
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
        <div class="mt-2" style="opacity:0.6;font-size:0.9rem">
            <p>Crafted with <i class="fas fa-heart" style="color:#ec4899"></i> for excellence in web development</p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
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
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.project-card, .skill-item, .about-content').forEach(el => {
        observer.observe(el);
    });
});
</script>
</body>
</html>