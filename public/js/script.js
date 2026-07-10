const counters = document.querySelectorAll('.counter');
const backToTop = document.getElementById('backToTop');
const filterButtons = document.querySelectorAll('.filter-btn');
const projectItems = document.querySelectorAll('.project-item');
const testimonials = document.querySelectorAll('.testimonial-item');
const heroSlides = document.querySelectorAll('#heroSlider .hero-slide');
const heroPrev = document.getElementById('heroPrev');
const heroNext = document.getElementById('heroNext');
const testimonialPrev = document.getElementById('testPrev');
const testimonialNext = document.getElementById('testNext');
const typedText = document.getElementById('typedText');
const themeToggle = document.getElementById('themeToggle');
const serviceSearch = document.getElementById('serviceSearch');
const serviceCards = document.querySelectorAll('.service-card');
const contactForm = document.getElementById('contactForm');
const formMessage = document.getElementById('formMessage');
const scrollProgress = document.getElementById('scrollProgress');
const particleCanvas = document.getElementById('particleCanvas');
const galleryModal = document.getElementById('galleryModal');
const modalImg = document.querySelector('.gallery-modal .modal-img');
const modalCaption = document.querySelector('.gallery-modal .modal-caption');
const modalClose = document.querySelector('.gallery-modal .close-modal');
const projectImages = document.querySelectorAll('.project-card img');
let testimonialIndex = 0;
let testimonialSliderInterval = null;
let heroIndex = 0;
let heroSliderInterval = null;
let typedIndex = 0;
let typedCharIndex = 0;
let typedDeleting = false;
const typedPhrases = ['Bespoke Interiors', 'Luxury Construction', 'Elegant Renovations', 'Masterful Spaces'];
const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

window.addEventListener('load', () => {
  const preloader = document.getElementById('preloader');
  if (preloader) {
    setTimeout(() => {
      preloader.style.opacity = '0';
      preloader.style.visibility = 'hidden';
      preloader.style.pointerEvents = 'none';
    }, 700);
  }
  if (!prefersReducedMotion) {
    initAOS();
    initTypedAnimation();
    initParticles();
  } else if (typedText) {
    typedText.textContent = typedPhrases[0];
  }
  revealVisible();
  updateScrollProgress();
  setThemeFromStorage();
  initHeroSlider();
  initProjectGallery();
  initServiceSearch();
  handleHeaderScroll();
  initBeforeAfterSlider();
  initLightbox();
  initTestimonialSlider();
  initRippleEffect();
  initFullscreenMenu();
  initPageTransitions();
  initImageFadeIn();
  initCustomCursor();

  // Initialize AOS (Animate on Scroll)
  if (typeof AOS !== 'undefined') {
    AOS.init({
      duration: 800,
      easing: 'ease-in-out',
      once: true,
      mirror: false
    });
  }
});

let scrollPending = false;
window.addEventListener('scroll', () => {
  if (!scrollPending) {
    scrollPending = true;
    window.requestAnimationFrame(() => {
      handleHeaderScroll();
      handleCounterScroll();
      handleBackToTop();
      revealVisible();
      animateHeroParallax();
      updateScrollProgress();
      scrollPending = false;
    });
  }
});

function handleHeaderScroll() {
  const header = document.querySelector('.header-nav');
  if (header) {
    if (window.scrollY > 50) {
      header.classList.add('scrolled');
    } else {
      header.classList.remove('scrolled');
    }
  }
}


function handleCounterScroll() {
  counters.forEach(counter => {
    const parent = counter.closest('.counter-card') || counter.closest('.counter-card-simple') || counter.closest('.stat-card') || counter;
    if (parent && !parent.classList.contains('counted') && isElementInViewport(parent)) {
      animateCounter(counter);
      parent.classList.add('counted');
    }
  });
}

function animateCounter(counter) {
  const target = +counter.dataset.target;
  const suffix = counter.dataset.suffix || '';
  const duration = 1800;
  const stepTime = Math.max(Math.floor(duration / target), 10);
  let current = 0;
  const timer = setInterval(() => {
    current += Math.ceil(target / (duration / stepTime));
    counter.textContent = current + suffix;
    if (current >= target) {
      counter.textContent = target + suffix;
      clearInterval(timer);
    }
  }, stepTime);
}

function isElementInViewport(el) {
  const rect = el.getBoundingClientRect();
  return rect.top < window.innerHeight - 80 && rect.bottom > 0;
}

function handleBackToTop() {
  if (backToTop) {
    if (window.scrollY > 400) {
      backToTop.classList.add('show');
    } else {
      backToTop.classList.remove('show');
    }
  }
}

if (backToTop) {
  backToTop.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });
}

if (filterButtons.length) {
  filterButtons.forEach(button => {
    button.addEventListener('click', () => {
      const activeButton = document.querySelector('.filter-btn.active');
      if (activeButton) {
        activeButton.classList.remove('active');
        activeButton.setAttribute('aria-pressed', 'false');
      }
      button.classList.add('active');
      button.setAttribute('aria-pressed', 'true');
      const filter = button.dataset.filter;
      projectItems.forEach(item => {
        const match = filter === 'all' || item.classList.contains(filter);
        item.hidden = !match;
      });
    });
  });
}

function showTestimonial(index) {
  testimonials.forEach((item, idx) => {
    item.classList.toggle('active', idx === index);
  });
}

function nextTestimonial() {
  testimonialIndex = (testimonialIndex + 1) % testimonials.length;
  showTestimonial(testimonialIndex);
}

function prevTestimonial() {
  testimonialIndex = (testimonialIndex - 1 + testimonials.length) % testimonials.length;
  showTestimonial(testimonialIndex);
}

function initTestimonialSlider() {
  if (!testimonials.length) return;
  showTestimonial(testimonialIndex);
  resetTestimonialSlider();
}

function resetTestimonialSlider() {
  if (testimonialSliderInterval) {
    clearInterval(testimonialSliderInterval);
  }
  testimonialSliderInterval = setInterval(nextTestimonial, 8000);
}

testimonialNext?.addEventListener('click', () => {
  nextTestimonial();
  resetTestimonialSlider();
});
testimonialPrev?.addEventListener('click', () => {
  prevTestimonial();
  resetTestimonialSlider();
});

heroNext?.addEventListener('click', () => {
  nextHeroSlide();
  resetHeroSlider();
});
heroPrev?.addEventListener('click', () => {
  prevHeroSlide();
  resetHeroSlider();
});

setInterval(nextTestimonial, 8000);

function updateScrollProgress() {
  const scroll = document.documentElement.scrollTop || document.body.scrollTop;
  const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
  const percent = height > 0 ? (scroll / height) * 100 : 0;
  scrollProgress.style.width = `${percent}%`;
}

function initHeroSlider() {
  if (!heroSlides.length) return;
  showHeroSlide(heroIndex);
  heroSliderInterval = setInterval(nextHeroSlide, 10000);
}

function showHeroSlide(index) {
  heroSlides.forEach((slide, idx) => {
    slide.classList.toggle('active', idx === index);
  });
}

function nextHeroSlide() {
  heroIndex = (heroIndex + 1) % heroSlides.length;
  showHeroSlide(heroIndex);
}

function prevHeroSlide() {
  heroIndex = (heroIndex - 1 + heroSlides.length) % heroSlides.length;
  showHeroSlide(heroIndex);
}

function resetHeroSlider() {
  if (heroSliderInterval) {
    clearInterval(heroSliderInterval);
  }
  heroSliderInterval = setInterval(nextHeroSlide, 10000);
}

function initTypedAnimation() {
  if (!typedText) return;
  const type = () => {
    const phrase = typedPhrases[typedIndex];
    if (!typedDeleting) {
      typedText.textContent = phrase.slice(0, typedCharIndex + 1);
      typedCharIndex += 1;
      if (typedCharIndex === phrase.length) {
        typedDeleting = true;
        setTimeout(type, 1500);
        return;
      }
    } else {
      typedText.textContent = phrase.slice(0, typedCharIndex - 1);
      typedCharIndex -= 1;
      if (typedCharIndex === 0) {
        typedDeleting = false;
        typedIndex = (typedIndex + 1) % typedPhrases.length;
        setTimeout(type, 500);
        return;
      }
    }
    setTimeout(type, typedDeleting ? 80 : 110);
  };
  setTimeout(type, 800);
}

function initServiceSearch() {
  if (!serviceSearch) return;
  serviceSearch.addEventListener('input', () => {
    const query = serviceSearch.value.trim().toLowerCase();
    serviceCards.forEach(card => {
      const text = card.dataset.service || card.textContent;
      const match = !query || text.toLowerCase().includes(query);
      const parent = card.closest('.col-md-6, .col-xl-4, .col-lg-4, .col-lg-6');
      if (parent) {
        parent.hidden = !match;
      }
    });
  });
}

function setThemeFromStorage() {
  const storedTheme = localStorage.getItem('siteTheme');
  const theme = storedTheme || 'light';
  applyTheme(theme);
}

function applyTheme(theme) {
  if (theme === 'dark') {
    document.documentElement.classList.add('dark-theme');
    themeToggle.innerHTML = '<i class="fa-solid fa-moon" aria-hidden="true"></i>';
    themeToggle.setAttribute('aria-pressed', 'true');
  } else {
    document.documentElement.classList.remove('dark-theme');
    themeToggle.innerHTML = '<i class="fa-solid fa-sun" aria-hidden="true"></i>';
    themeToggle.setAttribute('aria-pressed', 'false');
  }
  localStorage.setItem('siteTheme', theme);
}

if (themeToggle) {
  themeToggle.setAttribute('aria-pressed', 'false');
  themeToggle.addEventListener('click', () => {
    const isDark = document.documentElement.classList.contains('dark-theme');
    applyTheme(isDark ? 'light' : 'dark');
  });
}

function initProjectGallery() {
  if (!galleryModal) return;
  projectImages.forEach(image => {
    const card = image.closest('.project-card');
    const title = card?.querySelector('.project-info h5')?.textContent || image.alt || 'Project Image';
    image.style.cursor = 'pointer';
    image.addEventListener('click', () => openGalleryModal(image.src, title));
  });

  modalClose?.addEventListener('click', closeGalleryModal);
  galleryModal.addEventListener('click', event => {
    if (event.target === galleryModal) {
      closeGalleryModal();
    }
  });
  document.addEventListener('keydown', event => {
    if (event.key === 'Escape' && galleryModal.classList.contains('open')) {
      closeGalleryModal();
    }
  });
}

function openGalleryModal(src, caption) {
  modalImg.src = src;
  modalCaption.textContent = caption;
  galleryModal.classList.add('open');
  galleryModal.setAttribute('aria-hidden', 'false');
  document.body.style.overflow = 'hidden';
  modalClose?.focus();
}

function closeGalleryModal() {
  galleryModal.classList.remove('open');
  galleryModal.setAttribute('aria-hidden', 'true');
  document.body.style.overflow = '';
}

if (contactForm) {
  contactForm.addEventListener('submit', event => {
    event.preventDefault();
    const inputs = Array.from(contactForm.querySelectorAll('input, textarea'));
    let valid = true;

    inputs.forEach(input => {
      if (!input.value.trim()) {
        input.classList.add('is-invalid');
        valid = false;
      } else {
        input.classList.remove('is-invalid');
      }
    });

    const emailInput = contactForm.querySelector('input[type="email"]');
    if (emailInput && emailInput.value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value)) {
      emailInput.classList.add('is-invalid');
      valid = false;
    }

    if (valid) {
      formMessage.textContent = "Message sent successfully. We'll contact you soon.";
      formMessage.className = 'form-feedback success';
      contactForm.reset();
    } else {
      formMessage.textContent = 'Please fill in all fields correctly.';
      formMessage.className = 'form-feedback error';
    }
  });
}

function initParticles() {
  if (!particleCanvas || prefersReducedMotion) return;
  const ctx = particleCanvas.getContext('2d');
  const ratio = window.devicePixelRatio || 1;
  let width = particleCanvas.clientWidth;
  let height = particleCanvas.clientHeight;
  particleCanvas.width = width * ratio;
  particleCanvas.height = height * ratio;
  ctx.setTransform(ratio, 0, 0, ratio, 0, 0);

  const particles = [];
  const count = window.innerWidth < 768 ? 24 : 40;

  function createParticle() {
    return {
      x: Math.random() * width,
      y: Math.random() * height,
      radius: 1.1 + Math.random() * 1.8,
      vx: -0.15 + Math.random() * 0.3,
      vy: -0.05 + Math.random() * 0.15,
      alpha: 0.15 + Math.random() * 0.25,
    };
  }

  for (let i = 0; i < count; i += 1) {
    particles.push(createParticle());
  }

  function resizeCanvas() {
    width = particleCanvas.clientWidth;
    height = particleCanvas.clientHeight;
    particleCanvas.width = width * ratio;
    particleCanvas.height = height * ratio;
    ctx.setTransform(ratio, 0, 0, ratio, 0, 0);
  }

  function draw() {
    ctx.clearRect(0, 0, width, height);
    particles.forEach(particle => {
      particle.x += particle.vx;
      particle.y += particle.vy;
      if (particle.x < -20) particle.x = width + 20;
      if (particle.x > width + 20) particle.x = -20;
      if (particle.y < -20) particle.y = height + 20;
      if (particle.y > height + 20) particle.y = -20;

      ctx.beginPath();
      ctx.arc(particle.x, particle.y, particle.radius, 0, Math.PI * 2);
      ctx.fillStyle = `rgba(255,255,255,${particle.alpha})`;
      ctx.fill();
    });
    requestAnimationFrame(draw);
  }

  window.addEventListener('resize', resizeCanvas);
  resizeCanvas();
  requestAnimationFrame(draw);
}

function revealVisible() {
  const fadeItems = document.querySelectorAll('.fade-up');
  fadeItems.forEach(item => {
    if (isElementInViewport(item)) {
      item.classList.add('visible');
    }
  });
}

const sectionsToReveal = document.querySelectorAll('section, .service-card, .feature-box, .project-card, .testimonial-item, .process-card, .accordion-item, .glass-card');
sectionsToReveal.forEach((section, index) => {
  section.classList.add('fade-up');
  section.style.transitionDelay = `${Math.min(index * 40, 260)}ms`;
});

const navLinks = document.querySelectorAll('.nav-link');
navLinks.forEach(link => {
  link.addEventListener('click', () => {
    const collapse = document.querySelector('.navbar-collapse');
    if (collapse.classList.contains('show')) {
      new bootstrap.Collapse(collapse).hide();
    }
  });
});

function initAOS() {
  if (prefersReducedMotion) return;
  const revealSelectors = [
    'main section',
    '.service-card',
    '.project-card',
    '.feature-box',
    '.process-card',
    '.counter-card',
    '.testimonial-item',
    '.glass-card',
    '.accordion-item'
  ];

  revealSelectors.forEach(selector => {
    document.querySelectorAll(selector).forEach(element => {
      if (!element.hasAttribute('data-aos')) {
        element.setAttribute('data-aos', 'fade-up');
        element.setAttribute('data-aos-duration', '900');
        element.setAttribute('data-aos-once', 'true');
        element.setAttribute('data-aos-offset', '120');
      }
    });
  });

  if (window.AOS) {
    AOS.init({ duration: 900, easing: 'ease-out-cubic', once: true, mirror: false });
  }
}

const heroSection = document.querySelector('.hero-section');
const heroShapes = document.querySelectorAll('.floating-shape');
function animateHeroParallax() {
  const offset = window.scrollY;
  if (heroSection) {
    heroSection.style.backgroundPosition = `center ${50 + offset * 0.06}%`;
  }
  heroShapes.forEach((shape, index) => {
    const amplitude = 16 + index * 2;
    shape.style.transform = `translateY(${Math.sin((offset + index * 120) / 60) * amplitude}px) translateX(${Math.cos((offset + index * 160) / 80) * amplitude / 2}px)`;
  });
}

// Before-After Slider Drag functionality
function initBeforeAfterSlider() {
  const container = document.querySelector('.before-after-slider');
  if (!container) return;

  const beforeImgContainer = container.querySelector('.before-image-container');
  const beforeImg = beforeImgContainer.querySelector('img');
  const handle = container.querySelector('.slider-handle');
  
  function resizeBeforeImage() {
    beforeImg.style.width = `${container.offsetWidth}px`;
  }
  
  resizeBeforeImage();
  window.addEventListener('resize', resizeBeforeImage);

  let isDragging = false;

  function updateSlider(clientX) {
    const rect = container.getBoundingClientRect();
    let x = clientX - rect.left;
    
    if (x < 0) x = 0;
    if (x > rect.width) x = rect.width;
    
    const percentage = (x / rect.width) * 100;
    beforeImgContainer.style.width = `${percentage}%`;
    handle.style.left = `${percentage}%`;
  }

  container.addEventListener('mousedown', (e) => {
    isDragging = true;
    updateSlider(e.clientX);
  });

  window.addEventListener('mousemove', (e) => {
    if (!isDragging) return;
    updateSlider(e.clientX);
  });

  window.addEventListener('mouseup', () => {
    isDragging = false;
  });

  container.addEventListener('touchstart', (e) => {
    isDragging = true;
    updateSlider(e.touches[0].clientX);
  });

  window.addEventListener('touchmove', (e) => {
    if (!isDragging) return;
    updateSlider(e.touches[0].clientX);
  });

  container.addEventListener('touchend', () => {
    isDragging = false;
  });
}

// Lightbox Modal for Gallery Portfolio
function initLightbox() {
  const modal = document.getElementById('lightboxModal');
  if (!modal) return;

  const modalImg = document.getElementById('lightboxImg');
  const modalCaption = document.getElementById('lightboxCaption');
  const closeBtn = modal.querySelector('.lightbox-close');
  const prevBtn = modal.querySelector('.lightbox-prev');
  const nextBtn = modal.querySelector('.lightbox-next');
  
  const galleryImages = Array.from(document.querySelectorAll('.gallery-item img'));
  let currentIndex = 0;

  function showImage(index) {
    if (index < 0 || index >= galleryImages.length) return;
    currentIndex = index;
    const img = galleryImages[currentIndex];
    const card = img.closest('.gallery-item');
    const title = card.querySelector('.gallery-info h5')?.textContent || img.alt || 'Gallery View';
    
    modalImg.src = img.src;
    modalCaption.textContent = title;
  }

  galleryImages.forEach((img, index) => {
    img.addEventListener('click', () => {
      showImage(index);
      modal.classList.remove('d-none');
      setTimeout(() => modal.classList.add('show'), 10);
      document.body.style.overflow = 'hidden';
    });
  });

  function closeModal() {
    modal.classList.remove('show');
    setTimeout(() => modal.classList.add('d-none'), 350);
    document.body.style.overflow = '';
  }

  closeBtn?.addEventListener('click', closeModal);
  modal.addEventListener('click', (e) => {
    if (e.target === modal) closeModal();
  });

  prevBtn?.addEventListener('click', (e) => {
    e.stopPropagation();
    let prevIndex = currentIndex - 1;
    if (prevIndex < 0) prevIndex = galleryImages.length - 1;
    showImage(prevIndex);
  });

  nextBtn?.addEventListener('click', (e) => {
    e.stopPropagation();
    let nextIndex = currentIndex + 1;
    if (nextIndex >= galleryImages.length) nextIndex = 0;
    showImage(nextIndex);
  });

  document.addEventListener('keydown', (e) => {
    if (!modal.classList.contains('show')) return;
    if (e.key === 'Escape') closeModal();
    if (e.key === 'ArrowLeft') prevBtn?.click();
    if (e.key === 'ArrowRight') nextBtn?.click();
  });
}

// Click Ripple animation for buttons
function initRippleEffect() {
  const buttons = document.querySelectorAll('.btn, .btn-gold, .btn-outline-light, .action-btn');
  buttons.forEach(button => {
    button.addEventListener('click', function(e) {
      const rect = button.getBoundingClientRect();
      const x = e.clientX - rect.left;
      const y = e.clientY - rect.top;
      
      const ripple = document.createElement('span');
      ripple.classList.add('btn-ripple');
      ripple.style.left = `${x}px`;
      ripple.style.top = `${y}px`;
      
      button.appendChild(ripple);
      
      setTimeout(() => {
        ripple.remove();
      }, 600);
    });
  });
}

// Full Screen Mobile Menu Overlay
function initFullscreenMenu() {
  const toggler = document.querySelector('.navbar-toggler');
  const overlay = document.getElementById('fullscreenMenu');
  const closeBtn = document.querySelector('.close-menu-btn');
  const menuLinks = document.querySelectorAll('.menu-link');

  if (!toggler || !overlay) return;

  toggler.addEventListener('click', (e) => {
    e.preventDefault();
    overlay.classList.remove('d-none');
    setTimeout(() => overlay.classList.add('show'), 10);
    document.body.style.overflow = 'hidden';
  });

  function closeMenu() {
    overlay.classList.remove('show');
    setTimeout(() => overlay.classList.add('d-none'), 400);
    document.body.style.overflow = '';
  }

  closeBtn?.addEventListener('click', closeMenu);
  
  menuLinks.forEach(link => {
    link.addEventListener('click', () => {
      closeMenu();
    });
  });
}

// Progress Bar and Smooth Page Transitions
function initPageTransitions() {
  const progressBar = document.getElementById('pageProgressBar');
  const preloader = document.getElementById('preloader');

  // Complete page load progress animation
  if (progressBar) {
    progressBar.style.width = '100%';
    setTimeout(() => {
      progressBar.style.opacity = '0';
    }, 400);
  }

  // Intercept links for smooth fade transitions
  const links = document.querySelectorAll('a');
  links.forEach(link => {
    const href = link.getAttribute('href');
    const target = link.getAttribute('target');
    
    if (href && !href.startsWith('#') && !href.startsWith('mailto:') && !href.startsWith('tel:') && (!target || target === '_self')) {
      try {
        const url = new URL(link.href, window.location.href);
        if (url.origin === window.location.origin) {
          link.addEventListener('click', (e) => {
            if (e.ctrlKey || e.metaKey) return;
            
            e.preventDefault();
            
            if (progressBar) {
              progressBar.style.opacity = '1';
              progressBar.style.width = '0';
              setTimeout(() => {
                progressBar.style.width = '70%';
              }, 10);
            }
            
            if (preloader) {
              preloader.style.visibility = 'visible';
              preloader.style.pointerEvents = 'all';
              preloader.style.opacity = '1';
            }
            
            setTimeout(() => {
              window.location.href = link.href;
            }, 400);
          });
        }
      } catch (err) {
        // Invalid URL, skip
      }
    }
  });
}

// Image graceful fade in on load
function initImageFadeIn() {
  const lazyImages = document.querySelectorAll('img[loading="lazy"]');
  lazyImages.forEach(img => {
    if (img.complete) {
      img.classList.add('fade-in-loaded');
    } else {
      img.addEventListener('load', () => {
        img.classList.add('fade-in-loaded');
      });
    }
  });
}

// Custom Cursor (Desktop ring and pointer dot)
function initCustomCursor() {
  const cursor = document.getElementById('customCursor');
  const dot = document.getElementById('customCursorDot');
  if (!cursor || !dot) return;

  if (window.matchMedia('(max-width: 991.98px)').matches) {
    cursor.style.display = 'none';
    dot.style.display = 'none';
    return;
  }

  document.addEventListener('mousemove', (e) => {
    cursor.style.left = e.clientX + 'px';
    cursor.style.top = e.clientY + 'px';
    dot.style.left = e.clientX + 'px';
    dot.style.top = e.clientY + 'px';
  });

  const hoverables = document.querySelectorAll('a, button, input, select, textarea, .btn, .btn-gold, .btn-outline-light, .navbar-toggler, [role="button"], .project-card, .gallery-item img');
  hoverables.forEach(item => {
    item.addEventListener('mouseenter', () => {
      cursor.classList.add('hovered');
      dot.classList.add('hovered');
    });
    item.addEventListener('mouseleave', () => {
      cursor.classList.remove('hovered');
      dot.classList.remove('hovered');
    });
  });
}

// Scroll progress bar calculation
function updateScrollProgress() {
  const bar = document.getElementById('scrollProgress');
  if (!bar) return;
  const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
  const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
  const scrolled = height > 0 ? (winScroll / height) * 100 : 0;
  bar.style.width = scrolled + '%';
}
