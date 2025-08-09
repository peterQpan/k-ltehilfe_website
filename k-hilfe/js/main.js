// Main JavaScript functionality for Kältehilfe website

// Global variables
let currentLanguage = 'de';

// DOM Content Loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all features
    initNavigation();
    initLanguageSwitcher();
    initGallery();
    initAnimations();
    initContactForm();
    initSmoothScrolling();
    
    // Load default language
    changeLanguage(currentLanguage);
});

// Navigation functionality
function initNavigation() {
    const navToggle = document.getElementById('navToggle');
    const navMenu = document.getElementById('navMenu');
    
    if (navToggle && navMenu) {
        navToggle.addEventListener('click', function() {
            navToggle.classList.toggle('active');
            navMenu.classList.toggle('active');
        });

        // Close mobile menu when clicking on a link
        const navLinks = document.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                navToggle.classList.remove('active');
                navMenu.classList.remove('active');
            });
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            if (!navToggle.contains(event.target) && !navMenu.contains(event.target)) {
                navToggle.classList.remove('active');
                navMenu.classList.remove('active');
            }
        });
    }
}

// Language switcher functionality
function initLanguageSwitcher() {
    // Get language from localStorage or default to German
    const savedLanguage = localStorage.getItem('kaeltehilfe-language') || 'de';
    currentLanguage = savedLanguage;
    
    // Update active language link
    updateActiveLanguageLink();
}

function changeLanguage(lang) {
    if (!translations[lang]) {
        console.error('Language not found:', lang);
        return;
    }
    
    currentLanguage = lang;
    localStorage.setItem('kaeltehilfe-language', lang);
    
    // Update HTML lang attribute
    document.documentElement.lang = lang;
    
    // Update all elements with data-key attributes
    const elements = document.querySelectorAll('[data-key]');
    elements.forEach(element => {
        const key = element.getAttribute('data-key');
        if (translations[lang][key]) {
            if (element.tagName === 'INPUT' || element.tagName === 'TEXTAREA') {
                element.placeholder = translations[lang][key];
            } else {
                element.innerHTML = translations[lang][key];
            }
        }
    });
    
    // Update placeholders for form elements
    const placeholderElements = document.querySelectorAll('[data-placeholder]');
    placeholderElements.forEach(element => {
        const key = element.getAttribute('data-placeholder');
        if (translations[lang][key]) {
            element.placeholder = translations[lang][key];
        }
    });
    
    // Update active language link
    updateActiveLanguageLink();
}

function updateActiveLanguageLink() {
    const langLinks = document.querySelectorAll('.lang-link');
    langLinks.forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('data-lang') === currentLanguage) {
            link.classList.add('active');
        }
    });
}

// Gallery functionality
function initGallery() {
    const galleryGrid = document.getElementById('galleryGrid');
    if (!galleryGrid) return;
    
    // Sample gallery items (placeholder images)
    const galleryItems = [
        {
            src: 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?w=400&h=300&fit=crop',
            alt: 'Essensausgabe'
        },
        {
            src: 'https://images.unsplash.com/photo-1469571486292-0ba58a3f068b?w=400&h=300&fit=crop',
            alt: 'Hilfe für Obdachlose'
        },
        {
            src: 'https://images.unsplash.com/photo-1582213782179-e0d53f98f2ca?w=400&h=300&fit=crop',
            alt: 'Warme Kleidung'
        },
        {
            src: 'https://images.unsplash.com/photo-1544027993-37dbfe43562a?w=400&h=300&fit=crop',
            alt: 'Freiwillige Helfer'
        },
        {
            src: 'https://images.unsplash.com/photo-1593113598332-cd288d649433?w=400&h=300&fit=crop',
            alt: 'Notunterkunft'
        },
        {
            src: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=400&h=300&fit=crop',
            alt: 'Gemeinschaftshilfe'
        }
    ];
    
    // Create gallery items
    galleryItems.forEach((item, index) => {
        const galleryItem = document.createElement('div');
        galleryItem.className = 'gallery-item';
        galleryItem.innerHTML = `
            <img src="${item.src}" alt="${item.alt}" loading="lazy">
        `;
        
        // Add click event for larger view
        galleryItem.addEventListener('click', function() {
            openImageModal(item.src, item.alt);
        });
        
        galleryGrid.appendChild(galleryItem);
    });
}

// Image modal functionality
function openImageModal(src, alt) {
    // Create modal if it doesn't exist
    let modal = document.getElementById('imageModal');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'imageModal';
        modal.className = 'image-modal';
        modal.innerHTML = `
            <div class="modal-overlay">
                <div class="modal-content">
                    <span class="modal-close">&times;</span>
                    <img class="modal-image" src="" alt="">
                </div>
            </div>
        `;
        document.body.appendChild(modal);
        
        // Add styles if not already present
        if (!document.getElementById('modalStyles')) {
            const style = document.createElement('style');
            style.id = 'modalStyles';
            style.textContent = `
                .image-modal {
                    display: none;
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    z-index: 9999;
                }
                .modal-overlay {
                    background: rgba(0,0,0,0.9);
                    width: 100%;
                    height: 100%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    padding: 20px;
                }
                .modal-content {
                    position: relative;
                    max-width: 90%;
                    max-height: 90%;
                }
                .modal-image {
                    max-width: 100%;
                    max-height: 100%;
                    object-fit: contain;
                }
                .modal-close {
                    position: absolute;
                    top: -40px;
                    right: 0;
                    color: white;
                    font-size: 30px;
                    cursor: pointer;
                    font-weight: bold;
                }
                .modal-close:hover {
                    opacity: 0.7;
                }
            `;
            document.head.appendChild(style);
        }
        
        // Add event listeners
        modal.querySelector('.modal-close').addEventListener('click', closeImageModal);
        modal.querySelector('.modal-overlay').addEventListener('click', function(e) {
            if (e.target === this) {
                closeImageModal();
            }
        });
        
        // Close with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal.style.display === 'block') {
                closeImageModal();
            }
        });
    }
    
    // Set image and show modal
    const modalImage = modal.querySelector('.modal-image');
    modalImage.src = src;
    modalImage.alt = alt;
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }
}

// Animation functionality
function initAnimations() {
    // Intersection Observer for fade-in animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in-up');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    // Observe elements for animation
    const animatedElements = document.querySelectorAll('.stat-item, .help-card, .gallery-item, .contact-item');
    animatedElements.forEach(el => observer.observe(el));
    
    // Counter animation for stats
    animateCounters();
}

function animateCounters() {
    const counters = document.querySelectorAll('.stat-number');
    
    counters.forEach(counter => {
        const target = parseInt(counter.textContent.replace(/\D/g, ''));
        const suffix = counter.textContent.replace(/[0-9]/g, '');
        let current = 0;
        const increment = target / 100;
        
        const updateCounter = () => {
            if (current < target) {
                current += increment;
                counter.textContent = Math.floor(current) + suffix;
                setTimeout(updateCounter, 20);
            } else {
                counter.textContent = target + suffix;
            }
        };
        
        // Start animation when element becomes visible
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    updateCounter();
                    observer.unobserve(entry.target);
                }
            });
        });
        
        observer.observe(counter);
    });
}

// Contact form functionality
function initContactForm() {
    const contactForm = document.querySelector('.contact-form form');
    if (!contactForm) return;
    
    // Add floating label functionality
    const formGroups = document.querySelectorAll('.form-group');
    formGroups.forEach(group => {
        const input = group.querySelector('input, textarea');
        const label = group.querySelector('label');
        
        if (input && label) {
            // Check if input has value on page load
            if (input.value) {
                label.classList.add('active');
            }
            
            input.addEventListener('focus', function() {
                label.classList.add('active');
            });
            
            input.addEventListener('blur', function() {
                if (!input.value) {
                    label.classList.remove('active');
                }
            });
            
            input.addEventListener('input', function() {
                if (input.value) {
                    label.classList.add('active');
                } else {
                    label.classList.remove('active');
                }
            });
        }
    });
    
    // Form validation
    contactForm.addEventListener('submit', function(e) {
        const name = contactForm.querySelector('input[name="name"]').value.trim();
        const email = contactForm.querySelector('input[name="email"]').value.trim();
        const message = contactForm.querySelector('textarea[name="message"]').value.trim();
        
        if (!name || !email || !message) {
            e.preventDefault();
            alert(getTranslation('formValidationError') || 'Please fill in all fields.');
            return;
        }
        
        if (!isValidEmail(email)) {
            e.preventDefault();
            alert(getTranslation('formEmailError') || 'Please enter a valid email address.');
            return;
        }
    });
}

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function getTranslation(key) {
    return translations[currentLanguage] && translations[currentLanguage][key];
}

// Smooth scrolling functionality
function initSmoothScrolling() {
    const links = document.querySelectorAll('a[href^="#"]');
    
    links.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);
            
            if (targetElement) {
                const offsetTop = targetElement.offsetTop - 80; // Account for fixed navbar
                
                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });
            }
        });
    });
}

// Social media integration placeholder
function loadSocialFeed() {
    // This would integrate with Facebook/Instagram APIs
    // For now, we'll use placeholder functionality
    console.log('Social media feed integration would go here');
    
    // Placeholder: Show social media links
    const socialLinks = document.querySelectorAll('.social-link');
    socialLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // Track social media clicks for analytics
            if (typeof gtag !== 'undefined') {
                gtag('event', 'social_click', {
                    'social_network': this.classList.contains('facebook') ? 'Facebook' : 'Instagram',
                    'event_category': 'Social Media'
                });
            }
        });
    });
}

// Utility functions
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Add resize event listener for responsive adjustments
window.addEventListener('resize', debounce(function() {
    // Handle any responsive adjustments here
}, 250));

// Initialize social feed when page loads
document.addEventListener('DOMContentLoaded', loadSocialFeed);

// Add GDPR cookie notice functionality (simple implementation)
function initCookieNotice() {
    if (!localStorage.getItem('kaeltehilfe-cookies-accepted')) {
        const cookieNotice = document.createElement('div');
        cookieNotice.className = 'cookie-notice';
        cookieNotice.innerHTML = `
            <div class="cookie-content">
                <p>${getTranslation('cookieNotice') || 'Diese Website verwendet Cookies für eine bessere Nutzererfahrung.'}</p>
                <button onclick="acceptCookies()" class="btn btn-primary">${getTranslation('acceptCookies') || 'Akzeptieren'}</button>
            </div>
        `;
        
        // Add styles
        const style = document.createElement('style');
        style.textContent = `
            .cookie-notice {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                background: var(--dark-gray);
                color: white;
                padding: 1rem;
                z-index: 1000;
                box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
            }
            .cookie-content {
                max-width: 1200px;
                margin: 0 auto;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 1rem;
            }
            @media (max-width: 768px) {
                .cookie-content {
                    flex-direction: column;
                    text-align: center;
                }
            }
        `;
        document.head.appendChild(style);
        
        document.body.appendChild(cookieNotice);
    }
}

function acceptCookies() {
    localStorage.setItem('kaeltehilfe-cookies-accepted', 'true');
    const cookieNotice = document.querySelector('.cookie-notice');
    if (cookieNotice) {
        cookieNotice.remove();
    }
}

// Initialize cookie notice after page load
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(initCookieNotice, 1000);
});

// Export functions for global access
window.changeLanguage = changeLanguage;
window.acceptCookies = acceptCookies;