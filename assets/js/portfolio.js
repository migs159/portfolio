// Add meta tag for asset base URL so external JS can access it
if(!document.querySelector('meta[name="asset-base-url"]')){
  var meta = document.createElement('meta');
  meta.setAttribute('name', 'asset-base-url');
  meta.setAttribute('content', document.currentScript?.dataset?.assetPath || '/assets/img/');
  document.head.appendChild(meta);
}

document.addEventListener('DOMContentLoaded', function(){
    // Profile image error handler
    const profileImg = document.getElementById('hero-profile-img');
    if (profileImg) {
        profileImg.addEventListener('error', function() {
            console.warn('Profile image failed to load:', this.src);
            this.style.display = 'none';
            const heroInitial = document.getElementById('hero-initial');
            if (heroInitial) {
                heroInitial.style.display = 'flex';
            }
        });
    }

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
            var assetBase = document.querySelector('meta[name="asset-base-url"]')?.getAttribute('content') || '/assets/img/';
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
        document.querySelectorAll('.skill-row').forEach(row => {
            const fill = row.querySelector('.skill-bar-fill');
            const valueEl = row.querySelector('.skill-value');
            const pct = fill ? parseInt(fill.getAttribute('data-percent') || '0', 10) : 0;
            
            row._isAnimating = false;

            row.addEventListener('mouseenter', () => {
                // Clean up any existing animation span immediately
                try { if (row._animSpan) { row._animSpan.remove(); row._animSpan = null; } } catch(e){}
                
                // Prevent overlapping animations
                if (row._isAnimating) return;
                row._isAnimating = true;
                
                if (fill) {
                    // Set CSS custom property for animation
                    fill.style.setProperty('--skill-percent', pct + '%');
                    fill.classList.add('animate-fill');
                }
                
                if (valueEl) {
                    valueEl.style.opacity = '1';
                    valueEl.style.transform = 'translateY(0)';
                    // Clear any existing text content first
                    valueEl.textContent = '';
                    
                    try {
                        var anim = document.createElement('span');
                        anim.className = 'skill-value-anim';
                        anim.textContent = '';
                        row._animSpan = anim;
                        row.querySelector('.skill-head').appendChild(anim);
                        var target = parseInt(valueEl.getAttribute('data-percent')||pct,10) || 0;
                        
                        // Animate from 0 to target
                        animateCount(anim, target, 1400, 0, function(){
                            try { 
                                if (row._animSpan) { 
                                    row._animSpan.remove(); 
                                    row._animSpan = null; 
                                } 
                            } catch(e){}
                            try { 
                                valueEl.textContent = target + '%'; 
                            } catch(e){}
                            row._isAnimating = false;
                        });
                    } catch(e){
                        row._isAnimating = false;
                    }
                }
            });

            row.addEventListener('mouseleave', () => {
                if (fill) {
                    fill.classList.remove('animate-fill');
                    fill.style.width = '0%';
                }
                if (valueEl) {
                    valueEl.style.opacity = '';
                    valueEl.style.transform = '';
                    valueEl.textContent = '';
                }
                // Clean up animated span
                try { if (row._animSpan) { row._animSpan.remove(); row._animSpan = null; } } catch(e){}
                row._isAnimating = false;
            });
        });
    } catch (err) {}

    // Typing animation for hero name
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

    // Make section nav buttons functional on the portfolio page
    try {
        var sectionButtons = document.querySelectorAll('.section-nav-btn');
        if (sectionButtons && sectionButtons.length) {
            sectionButtons.forEach(function(btn){
                btn.addEventListener('click', function(e){
                    var section = btn.getAttribute('data-section');
                    if (!section) return;

                    // Toggle active state (single active at a time)
                    try {
                        sectionButtons.forEach(function(b){ b.classList.remove('active'); b.removeAttribute('aria-current'); });
                        btn.classList.add('active');
                        btn.setAttribute('aria-current', 'true');
                    } catch (errInner) {}

                    var targetSelector = section === 'home' ? 'header.hero' : ('#' + section);
                    var target = document.querySelector(targetSelector);
                    if (target) {
                        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }

                    // Keep the navbar collapse open (don't auto-close)
                });
            });
        }
    } catch (err) { console.warn('section-nav-btn handler error', err); }
});
