/* ========================================
   School Management System - Frontend JS
   ======================================== */

// Smooth Scrolling for Navigation Links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Mobile Menu Toggle
const navbar = document.querySelector('.navbar');
const navbarMenu = document.querySelector('.navbar-menu');

// Close menu when link is clicked
document.querySelectorAll('.nav-link').forEach(link => {
    link.addEventListener('click', function() {
        if (window.innerWidth <= 768) {
            navbarMenu.style.display = 'none';
        }
    });
});

// Add scroll event listener for navbar styling
window.addEventListener('scroll', function() {
    if (window.scrollY > 50) {
        navbar.style.boxShadow = '0 5px 20px rgba(0, 0, 0, 0.2)';
    } else {
        navbar.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.1)';
    }
});

// Form Validation and Submit
const contactForm = document.querySelector('.contact-form');
if (contactForm) {
    contactForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Get form values
        const name = this.querySelector('input[type="text"]').value.trim();
        const email = this.querySelector('input[type="email"]').value.trim();
        const subject = this.querySelectorAll('input[type="text"]')[1].value.trim();
        const message = this.querySelector('textarea').value.trim();
        
        // Validation
        if (!name || !email || !message) {
            showNotification('Please fill in all required fields!', 'error');
            return;
        }
        
        if (!isValidEmail(email)) {
            showNotification('Please enter a valid email address!', 'error');
            return;
        }
        
        // Show success message
        showNotification('Message sent successfully! We will get back to you soon.', 'success');
        
        // Reset form
        this.reset();
        
        // Optional: Send to server via AJAX
        sendContactForm({
            name: name,
            email: email,
            subject: subject,
            message: message
        });
    });
}

// Email Validation Function
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Show Notification
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    
    // Add styles to body
    if (!document.querySelector('style[data-notification]')) {
        const style = document.createElement('style');
        style.setAttribute('data-notification', 'true');
        style.textContent = `
            .notification {
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 16px 24px;
                border-radius: 8px;
                color: white;
                font-weight: 600;
                z-index: 9999;
                animation: slideInRight 0.3s ease-out;
                max-width: 400px;
            }
            
            .notification-success {
                background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
                box-shadow: 0 10px 30px rgba(39, 174, 96, 0.3);
            }
            
            .notification-error {
                background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
                box-shadow: 0 10px 30px rgba(231, 76, 60, 0.3);
            }
            
            .notification-info {
                background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
                box-shadow: 0 10px 30px rgba(52, 152, 219, 0.3);
            }
            
            @keyframes slideInRight {
                from {
                    opacity: 0;
                    transform: translateX(100px);
                }
                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }
            
            @keyframes slideOutRight {
                from {
                    opacity: 1;
                    transform: translateX(0);
                }
                to {
                    opacity: 0;
                    transform: translateX(100px);
                }
            }
            
            .notification.removing {
                animation: slideOutRight 0.3s ease-out;
            }
            
            @media (max-width: 600px) {
                .notification {
                    right: 10px;
                    left: 10px;
                    max-width: none;
                }
            }
        `;
        document.head.appendChild(style);
    }
    
    document.body.appendChild(notification);
    
    // Auto remove after 4 seconds
    setTimeout(() => {
        notification.classList.add('removing');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 4000);
}

// Send Contact Form Data (Optional - implement on backend)
function sendContactForm(data) {
    // This is where you would send the form data to your backend
    // For now, we'll just log it
    console.log('Contact form data:', data);
    
    // Uncomment to send to backend:
    /*
    fetch('contact.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        console.log('Server response:', data);
    })
    .catch(error => console.error('Error:', error));
    */
}

// Intersection Observer for Fade-In Animation
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver(function(entries) {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
            observer.unobserve(entry.target);
        }
    });
}, observerOptions);

// Apply fade-in animation to cards
document.querySelectorAll('.feature-card, .stat-card, .testimonial-card, .contact-item').forEach(card => {
    card.style.opacity = '0';
    card.style.transform = 'translateY(20px)';
    card.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
    observer.observe(card);
});

// Active Navigation Link
window.addEventListener('scroll', () => {
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('.nav-link[href^="#"]');
    
    let currentSection = '';
    
    sections.forEach(section => {
        const sectionTop = section.offsetTop;
        if (scrollY >= sectionTop - 200) {
            currentSection = section.getAttribute('id');
        }
    });
    
    navLinks.forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('href') === `#${currentSection}`) {
            link.classList.add('active');
        }
    });
});

// Add active link styling
if (!document.querySelector('style[data-active-link]')) {
    const style = document.createElement('style');
    style.setAttribute('data-active-link', 'true');
    style.textContent = `
        .nav-link.active {
            background-color: rgba(255, 255, 255, 0.3);
            border-bottom: 2px solid white;
        }
    `;
    document.head.appendChild(style);
}

// Counter Animation for Statistics
function animateCounter(element, target, duration = 2000) {
    let start = 0;
    const increment = target / (duration / 16);
    
    const timer = setInterval(() => {
        start += increment;
        if (start >= target) {
            element.textContent = target;
            clearInterval(timer);
        } else {
            element.textContent = Math.floor(start);
        }
    }, 16);
}

// Trigger counter animation when stats section is visible
const statsSection = document.querySelector('.statistics');
if (statsSection) {
    let animated = false;
    
    const statsObserver = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !animated) {
                animated = true;
                document.querySelectorAll('.stat-number').forEach(element => {
                    const text = element.textContent.trim();
                    const number = parseInt(text);
                    if (!isNaN(number)) {
                        animateCounter(element, number);
                    }
                });
                statsObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });
    
    statsObserver.observe(statsSection);
}

// Responsive Navbar Menu Toggle
window.addEventListener('resize', () => {
    if (window.innerWidth > 768 && navbarMenu && navbarMenu.style.display === 'none') {
        navbarMenu.style.display = '';
    }
});

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    console.log('Frontend initialized successfully');
});
