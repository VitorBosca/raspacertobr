// Modern Login Page Enhancements
document.addEventListener('DOMContentLoaded', function() {
    // Only run on login page
    if (!document.querySelector('.fi-simple-layout')) return;
    
    // Add floating particles animation
    createFloatingParticles();
    
    // Enhanced form interactions
    enhanceFormInputs();
    
    // Add keyboard shortcuts
    addKeyboardShortcuts();
    
    // Add loading states
    enhanceLoginButton();
    
    // Add smooth transitions
    addSmoothTransitions();
});

function createFloatingParticles() {
    const container = document.querySelector('.fi-simple-layout');
    if (!container) return;
    
    const particlesContainer = document.createElement('div');
    particlesContainer.className = 'floating-particles';
    particlesContainer.style.cssText = `
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 1;
        overflow: hidden;
    `;
    
    // Create floating particles
    for (let i = 0; i < 15; i++) {
        const particle = document.createElement('div');
        particle.className = 'particle';
        particle.style.cssText = `
            position: absolute;
            width: ${Math.random() * 6 + 2}px;
            height: ${Math.random() * 6 + 2}px;
            background: rgba(255, 255, 255, ${Math.random() * 0.3 + 0.1});
            border-radius: 50%;
            animation: float-particle ${Math.random() * 10 + 10}s linear infinite;
            left: ${Math.random() * 100}%;
            animation-delay: ${Math.random() * 10}s;
        `;
        particlesContainer.appendChild(particle);
    }
    
    container.appendChild(particlesContainer);
    
    // Add CSS for particle animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes float-particle {
            0% {
                transform: translateY(100vh) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100px) rotate(360deg);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);
}

function enhanceFormInputs() {
    const inputs = document.querySelectorAll('.fi-simple-layout .fi-input');
    
    inputs.forEach(input => {
        // Add subtle focus effects only
        input.addEventListener('focus', function() {
            this.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
        });
        
        input.addEventListener('blur', function() {
            // Remove any transforms on blur
            this.style.transform = 'none';
        });
        
        // Remove problematic input animations
        input.addEventListener('input', function() {
            // Simple feedback without scaling
            this.style.borderColor = '#667eea';
            setTimeout(() => {
                this.style.borderColor = '';
            }, 300);
        });
    });
}

function addKeyboardShortcuts() {
    document.addEventListener('keydown', function(e) {
        // Alt + L to focus on email/username field
        if (e.altKey && e.key === 'l') {
            e.preventDefault();
            const emailInput = document.querySelector('input[type="email"], input[name="email"], input[name="username"]');
            if (emailInput) emailInput.focus();
        }
        
        // Alt + P to focus on password field
        if (e.altKey && e.key === 'p') {
            e.preventDefault();
            const passwordInput = document.querySelector('input[type="password"]');
            if (passwordInput) passwordInput.focus();
        }
        
        // Enter key enhancement for login
        if (e.key === 'Enter') {
            const loginButton = document.querySelector('.fi-simple-layout .fi-btn-primary');
            if (loginButton && !loginButton.disabled) {
                loginButton.click();
            }
        }
    });
}

function enhanceLoginButton() {
    const loginButton = document.querySelector('.fi-simple-layout .fi-btn-primary');
    if (!loginButton) return;
    
    const originalText = loginButton.textContent;
    
    // Simplified hover effects
    loginButton.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-1px)';
    });
    
    loginButton.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0)';
    });
    
    // Simplified click handling
    loginButton.addEventListener('click', function() {
        if (this.disabled) return;
        
        // Simple loading state without complex animations
        this.disabled = true;
        this.style.opacity = '0.8';
        this.textContent = 'Entrando...';
        
        // Reset after timeout (fallback)
        setTimeout(() => {
            this.disabled = false;
            this.style.opacity = '1';
            this.textContent = originalText;
        }, 5000);
    });
}

function addSmoothTransitions() {
    // Add intersection observer for animations
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.1 });
    
    // Observe login card elements
    const elementsToAnimate = document.querySelectorAll('.fi-simple-layout .fi-simple-card > *');
    elementsToAnimate.forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
        observer.observe(el);
    });
}

// Add error handling for form validation
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.fi-simple-layout form');
    if (!form) return;
    
    form.addEventListener('submit', function(e) {
        const inputs = form.querySelectorAll('input[required]');
        let hasErrors = false;
        
        inputs.forEach(input => {
            if (!input.value.trim()) {
                hasErrors = true;
                input.style.border = '2px solid #ef4444';
                input.style.animation = 'shake 0.5s ease-in-out';
                
                // Remove error styling after animation
                setTimeout(() => {
                    input.style.border = '';
                    input.style.animation = '';
                }, 500);
            }
        });
        
        if (hasErrors) {
            e.preventDefault();
            
            // Add shake animation
            const style = document.createElement('style');
            style.textContent = `
                @keyframes shake {
                    0%, 100% { transform: translateX(0); }
                    25% { transform: translateX(-5px); }
                    75% { transform: translateX(5px); }
                }
            `;
            document.head.appendChild(style);
        }
    });
});

// Add theme detection and preference saving
function detectAndApplyTheme() {
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const savedTheme = localStorage.getItem('login-theme');
    
    if (savedTheme || prefersDark) {
        document.documentElement.setAttribute('data-theme', savedTheme || 'dark');
    }
}

// Run theme detection
detectAndApplyTheme();

// Listen for theme changes
window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', detectAndApplyTheme);
