# Frontend Customization Hotspots Reference

## 🎯 Main Customization Points by File

---

## 📄 frontend.php

### 1. School Name & Branding (Line 50)
```php
<h1 class="hero-title">Welcome to Our School</h1>  ← CHANGE THIS
<p class="hero-subtitle">Empowering Education Through Modern Technology</p>  ← CHANGE THIS
```

### 2. Hero Buttons (Line 55-58)
```php
<a href="#features" class="btn btn-primary">Explore Features</a>
<a href="admin/dashboard.php" class="btn btn-secondary">Admin Portal</a>
```

### 3. Feature Cards (Lines 80-150)
Each feature card:
```html
<div class="feature-card">
    <div class="feature-icon" style="background: linear-gradient(...);">👨‍🎓</div>  ← ICON
    <h3>Student Management</h3>  ← TITLE
    <p>Description here...</p>  ← DESCRIPTION
    <ul class="feature-list">
        <li>✓ Feature 1</li>  ← FEATURES
        <li>✓ Feature 2</li>
    </ul>
</div>
```

### 4. Statistics Numbers (Lines 180-210)
```html
<div class="stat-number">500+</div>  ← CHANGE NUMBER
<div class="stat-label">Active Students</div>  ← CHANGE LABEL
```

Current stats:
- Line 184: 500+ Active Students
- Line 195: 50+ Experienced Teachers
- Line 206: 25+ Classes
- Line 217: 98% Success Rate

### 5. About Section (Lines 240-280)
```php
<h2 class="section-title">About Our System</h2>
<p>Your description here...</p>  ← CHANGE DESCRIPTION

// About points (replace content):
<h4>Mission-Driven</h4>
<p>Your mission statement...</p>
```

### 6. Testimonials (Lines 300-370)
Each testimonial:
```html
<div class="testimonial-card">
    <div class="testimonial-stars">⭐⭐⭐⭐⭐</div>
    <p class="testimonial-text">"Testimonial text..."</p>  ← CHANGE QUOTE
    <div class="testimonial-author">
        <div class="author-avatar">AB</div>  ← INITIALS
        <div>
            <h4>Name Here</h4>  ← NAME
            <p>Title Here</p>  ← TITLE/ROLE
        </div>
    </div>
</div>
```

### 7. Contact Information (Lines 380-430)
```html
<!-- Address -->
<p>123 Education Street<br>School City, SC 12345</p>

<!-- Phone -->
<p>+1 (555) 123-4567<br>+1 (555) 123-4568</p>

<!-- Email -->
<p>info@school.com<br>admin@school.com</p>

<!-- Hours -->
<p>Mon - Fri: 8:00 AM - 4:00 PM<br>Sat: 9:00 AM - 1:00 PM</p>
```

### 8. Footer Links (Lines 450-480)
```html
<!-- About section -->
<li><a href="#about">About Us</a></li>
<li><a href="#features">Features</a></li>

<!-- Quick Links section -->
<li><a href="admin/dashboard.php">Admin Login</a></li>

<!-- Social Media section -->
<li><a href="#">Facebook</a></li>
<li><a href="#">Twitter</a></li>
<li><a href="#">Instagram</a></li>
```

### 9. Meta Tags (Line 7-9)
```html
<title><?php echo $page_title; ?></title>
<!-- Add custom meta tags for SEO -->
<meta name="description" content="Your school description">
<meta name="keywords" content="school, education, management">
```

---

## 🎨 assets/css/frontend.css

### 1. Primary Colors (Line 20-30)
```css
:root {
    --primary-color: #667eea;        ← Main color
    --secondary-color: #764ba2;      ← Secondary color
    --success-color: #27ae60;
    --danger-color: #e74c3c;
    --warning-color: #f39c12;
    --light-gray: #ecf0f1;
    --dark-gray: #34495e;
}
```

### 2. Hero Section Styling (Lines 100-150)
```css
.hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);  ← HERO COLORS
}

.hero-title {
    font-size: 64px;  ← TITLE SIZE
    font-weight: 800;
}

.hero-subtitle {
    font-size: 24px;  ← SUBTITLE SIZE
}
```

### 3. Section Spacing (Lines 300-320)
```css
.section-title {
    font-size: 48px;         ← TITLE SIZE
    margin-bottom: 15px;
}

.features {
    padding: 80px 0;  ← PADDING (change space between sections)
}

.statistics {
    padding: 80px 0;  ← PADDING
}
```

### 4. Feature Cards (Lines 380-420)
```css
.feature-card {
    padding: 40px 30px;      ← INTERNAL PADDING
    border-radius: 15px;     ← CORNER RADIUS
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);  ← SHADOW
}

.feature-card:hover {
    transform: translateY(-10px);  ← HOVER EFFECT
}
```

### 5. Button Styling (Lines 80-100)
```css
.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);  ← BUTTON COLOR
    color: white;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
}
```

### 6. Responsive Breakpoints (Lines 700+)
```css
@media (max-width: 768px) {
    .hero-title { font-size: 42px; }  ← TABLET SIZE
}

@media (max-width: 480px) {
    .hero-title { font-size: 32px; }  ← MOBILE SIZE
}
```

---

## 📜 assets/js/frontend.js

### 1. Notification Messages (Lines 50-100)
```javascript
showNotification(message, type = 'info');  // success, error, info

// Example:
showNotification('Success message here', 'success');
showNotification('Error message here', 'error');
```

### 2. Form Validation (Lines 30-45)
```javascript
// Email validation regex:
const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

// Add custom validation rules here
```

### 3. Contact Form Action (Lines 40-60)
```javascript
// Change where contact form data is sent:
// Currently: Logs to console only
// To enable backend:
// Uncomment in sendContactForm() function

fetch('contact.php', {
    method: 'POST',
    body: JSON.stringify(data)
    // Send to this endpoint
})
```

### 4. Smooth Scroll Speed (Lines 10-15)
```javascript
target.scrollIntoView({
    behavior: 'smooth',  ← Change to 'auto' for instant
    block: 'start'
});
```

### 5. Counter Animation Duration (Lines 220-230)
```javascript
animateCounter(element, target, duration = 2000);  ← DURATION (milliseconds)
```

### 6. Notification Timeout (Lines 90-100)
```javascript
setTimeout(() => {
    notification.classList.add('removing');
}, 4000);  ← DISPLAY TIME (milliseconds) - change 4000 to your value
```

---

## 📧 contact.php

### 1. Email Recipient (Line 40)
```php
$to = 'admin@school.com';  ← CHANGE TO YOUR EMAIL
```

### 2. Enable Email Sending (Line 48)
```php
// Uncomment this line to send actual emails:
// mail($to, $emailSubject, $emailBody, $headers);
```

### 3. Log File Location (Line 51)
```php
$logFile = __DIR__ . '/messages.log';  ← Messages saved here
```

### 4. Email Subject (Line 34)
```php
$emailSubject = "New Contact Form Submission: " . $subject;  ← CHANGE PREFIX
```

---

## 🎨 Color Gradient Reference

### Used Gradients:
```css
/* Feature Cards */
background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);  /* Purple */
background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);  /* Pink */
background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);  /* Blue */
background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);  /* Orange */
background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);  /* Cyan */
background: linear-gradient(135deg, #ff9a56 0%, #ff6a88 100%);  /* Red */
```

---

## 📱 Responsive Breakpoints

### Tablet (768px and below)
```css
@media (max-width: 768px) {
    .hero-title { font-size: 42px; }
    .features-grid { grid-template-columns: 1fr; }
    .about-grid { grid-template-columns: 1fr; }
}
```

### Mobile (480px and below)
```css
@media (max-width: 480px) {
    .hero-title { font-size: 32px; }
    .btn { min-width: 250px; }
}
```

---

## 🔧 Common Customizations

### Change Hero Gradient
```css
/* frontend.css, line 104 */
background: linear-gradient(135deg, NEW_COLOR_1 0%, NEW_COLOR_2 100%);
```

### Change Button Text
```php
<!-- frontend.php, line 55-58 -->
<a href="#features" class="btn btn-primary">NEW TEXT HERE</a>
```

### Change Section Padding
```css
/* frontend.css */
.features { padding: 120px 0; }  /* Increase from 80px */
.statistics { padding: 120px 0; }
```

### Add New Feature Card
```php
<!-- frontend.php, line 120+, duplicate a feature-card div -->
<div class="feature-card">
    <div class="feature-icon" style="background: ...;">🎯</div>
    <h3>New Feature</h3>
    <p>Description</p>
    <ul class="feature-list">
        <li>✓ New Item</li>
    </ul>
</div>
```

### Update Button Color
```css
/* frontend.css, line 85+ */
.btn-primary {
    background: linear-gradient(135deg, NEW_COLOR_1 0%, NEW_COLOR_2 100%);
}
```

---

## 📋 Checklist for Full Customization

- [ ] Change school name
- [ ] Update hero subtitle
- [ ] Update all statistics
- [ ] Update contact information
- [ ] Replace testimonials
- [ ] Update feature descriptions
- [ ] Change colors (if needed)
- [ ] Add school logo
- [ ] Update social media links
- [ ] Enable contact form emails
- [ ] Test on mobile
- [ ] Update meta tags for SEO

---

## 🚀 Quick Edit Commands

### Change school name everywhere:
1. Open frontend.php
2. Find: "Our School"
3. Replace with: "Your School Name"

### Change primary color:
1. Open frontend.css
2. Find: `--primary-color: #667eea;`
3. Replace with: `--primary-color: #YOUR_COLOR;`

### Change email recipient:
1. Open contact.php
2. Find: `$to = 'admin@school.com';`
3. Replace with: `$to = 'your@email.com';`

---

**Last Updated**: 2024
**Version**: 1.0
