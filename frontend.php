<?php
session_start();
include('db_config.php');
$page_title = 'Welcome to Our School - School Management System';

// Get published content
$content_list = getAllRows("SELECT * FROM content WHERE status = 'published' ORDER BY created_at DESC LIMIT 3");

// Get gallery images
$gallery_list = getAllRows("SELECT * FROM gallery WHERE status = 'active' ORDER BY display_order ASC, created_at DESC LIMIT 12");

// Get upcoming events
$events_list = getAllRows("SELECT * FROM events WHERE status IN ('upcoming', 'ongoing') ORDER BY event_date ASC LIMIT 6");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    <link rel="stylesheet" href="assets/css/frontend.css">
</head>
<body>
    <!-- ========== Navigation Bar ========== -->
    <nav class="navbar">
        <div class="navbar-container">
            <div class="navbar-brand">
                <h2>📚 School Management System</h2>
            </div>
            
            <ul class="navbar-menu">
                <li><a href="#home" class="nav-link">Home</a></li>
                <li><a href="#features" class="nav-link">Features</a></li>
                <li><a href="#gallery" class="nav-link">Gallery</a></li>
                <li><a href="#events" class="nav-link">Events</a></li>
                <li><a href="#content" class="nav-link">News</a></li>
                <li><a href="#contact" class="nav-link">Contact</a></li>
                <li><a href="admin/dashboard.php" class="nav-link btn-admin">🔐 Admin Login</a></li>
            </ul>
        </div>
    </nav>

    <!-- ========== Hero Section ========== -->
    <section class="hero" id="home">
        <div class="hero-content">
            <h1 class="hero-title">Welcome to Our School</h1>
            <p class="hero-subtitle">Empowering Education Through Modern Technology</p>
            <div class="hero-buttons">
                <a href="#features" class="btn btn-primary">Explore Features</a>
                <a href="admin/dashboard.php" class="btn btn-secondary">Admin Portal</a>
            </div>
        </div>
        <div class="hero-background">
            <div class="hero-shape shape-1"></div>
            <div class="hero-shape shape-2"></div>
            <div class="hero-shape shape-3"></div>
        </div>
    </section>

    <!-- ========== Features Section ========== -->
    <section class="features" id="features">
        <div class="container">
            <h2 class="section-title">Our Powerful Features</h2>
            <p class="section-subtitle">Comprehensive tools for managing every aspect of school administration</p>
            
            <div class="features-grid">
                <!-- Students Management -->
                <div class="feature-card">
                    <div class="feature-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        👨‍🎓
                    </div>
                    <h3>Student Management</h3>
                    <p>Complete student records, enrollment, academic performance tracking, and progress reports all in one place.</p>
                    <ul class="feature-list">
                        <li>✓ Student Registration</li>
                        <li>✓ Academic Records</li>
                        <li>✓ Progress Tracking</li>
                    </ul>
                </div>

                <!-- Teachers Management -->
                <div class="feature-card">
                    <div class="feature-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                        👨‍🏫
                    </div>
                    <h3>Teacher Management</h3>
                    <p>Manage teacher information, qualifications, schedules, and their performance metrics.</p>
                    <ul class="feature-list">
                        <li>✓ Teacher Profiles</li>
                        <li>✓ Schedule Management</li>
                        <li>✓ Performance Analytics</li>
                    </ul>
                </div>

                <!-- Classes Management -->
                <div class="feature-card">
                    <div class="feature-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                        🏫
                    </div>
                    <h3>Class Organization</h3>
                    <p>Organize classes, manage sections, assign teachers, and track class performance.</p>
                    <ul class="feature-list">
                        <li>✓ Class Creation</li>
                        <li>✓ Teacher Assignment</li>
                        <li>✓ Class Analytics</li>
                    </ul>
                </div>

                <!-- Attendance Tracking -->
                <div class="feature-card">
                    <div class="feature-icon" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                        📋
                    </div>
                    <h3>Attendance Tracking</h3>
                    <p>Digital attendance system with real-time tracking and automated reports.</p>
                    <ul class="feature-list">
                        <li>✓ Digital Attendance</li>
                        <li>✓ Automated Reports</li>
                        <li>✓ Absence Alerts</li>
                    </ul>
                </div>

                <!-- Exam Management -->
                <div class="feature-card">
                    <div class="feature-icon" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
                        📝
                    </div>
                    <h3>Exam Management</h3>
                    <p>Create, schedule, and manage exams with automatic grading and result analysis.</p>
                    <ul class="feature-list">
                        <li>✓ Exam Scheduling</li>
                        <li>✓ Automatic Grading</li>
                        <li>✓ Result Analysis</li>
                    </ul>
                </div>

                <!-- Fees Management -->
                <div class="feature-card">
                    <div class="feature-icon" style="background: linear-gradient(135deg, #ff9a56 0%, #ff6a88 100%);">
                        💰
                    </div>
                    <h3>Fee Management</h3>
                    <p>Complete fee tracking, payment management, and financial reporting system.</p>
                    <ul class="feature-list">
                        <li>✓ Fee Collection</li>
                        <li>✓ Payment Tracking</li>
                        <li>✓ Financial Reports</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== Statistics Section ========== -->
    <section class="statistics" id="statistics">
        <div class="container">
            <h2 class="section-title">School Overview</h2>
            <div class="stats-grid">
                <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="stat-content">
                        <div class="stat-number">500+</div>
                        <div class="stat-label">Active Students</div>
                    </div>
                    <div class="stat-icon">👨‍🎓</div>
                </div>
                
                <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <div class="stat-content">
                        <div class="stat-number">50+</div>
                        <div class="stat-label">Experienced Teachers</div>
                    </div>
                    <div class="stat-icon">👨‍🏫</div>
                </div>
                
                <div class="stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <div class="stat-content">
                        <div class="stat-number">25+</div>
                        <div class="stat-label">Classes</div>
                    </div>
                    <div class="stat-icon">🏫</div>
                </div>
                
                <div class="stat-card" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                    <div class="stat-content">
                        <div class="stat-number">98%</div>
                        <div class="stat-label">Success Rate</div>
                    </div>
                    <div class="stat-icon">🎯</div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== About Section ========== -->
    <section class="about" id="about">
        <div class="container">
            <div class="about-grid">
                <div class="about-content">
                    <h2 class="section-title">About Our System</h2>
                    <p>Our School Management System is designed to streamline educational administration and improve communication between teachers, students, and parents.</p>
                    
                    <div class="about-points">
                        <div class="about-point">
                            <span class="about-icon">🎯</span>
                            <div>
                                <h4>Mission-Driven</h4>
                                <p>Committed to delivering quality education through modern technology solutions.</p>
                            </div>
                        </div>
                        <div class="about-point">
                            <span class="about-icon">🔒</span>
                            <div>
                                <h4>Secure & Reliable</h4>
                                <p>Enterprise-grade security with role-based access control and data protection.</p>
                            </div>
                        </div>
                        <div class="about-point">
                            <span class="about-icon">⚡</span>
                            <div>
                                <h4>Fast & Efficient</h4>
                                <p>Streamlined workflows reduce administrative burden and free up time for education.</p>
                            </div>
                        </div>
                        <div class="about-point">
                            <span class="about-icon">📱</span>
                            <div>
                                <h4>Mobile Responsive</h4>
                                <p>Access the system anytime, anywhere from any device.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="about-illustration">
                    <div class="illustration-card">
                        <div class="illustration-icon">📚</div>
                        <div class="illustration-icon">👥</div>
                        <div class="illustration-icon">📊</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== Testimonials Section ========== -->
    <section class="testimonials">
        <div class="container">
            <h2 class="section-title">What People Say</h2>
            
            <div class="testimonials-grid">
                <div class="testimonial-card">
                    <div class="testimonial-stars">⭐⭐⭐⭐⭐</div>
                    <p class="testimonial-text">"The system has completely transformed how we manage student records. Everything is now just a few clicks away!"</p>
                    <div class="testimonial-author">
                        <div class="author-avatar">PR</div>
                        <div>
                            <h4>Principal Ramesh</h4>
                            <p>School Principal</p>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card">
                    <div class="testimonial-stars">⭐⭐⭐⭐⭐</div>
                    <p class="testimonial-text">"As a teacher, I appreciate how easy it is to track attendance and grade assignments. Saves me hours every week!"</p>
                    <div class="testimonial-author">
                        <div class="author-avatar">ST</div>
                        <div>
                            <h4>Ms. Sarah Teacher</h4>
                            <p>Mathematics Teacher</p>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card">
                    <div class="testimonial-stars">⭐⭐⭐⭐⭐</div>
                    <p class="testimonial-text">"As a parent, I love being able to check my child's progress and attendance online. Great peace of mind!"</p>
                    <div class="testimonial-author">
                        <div class="author-avatar">RP</div>
                        <div>
                            <h4>Mr. Rajesh Parent</h4>
                            <p>Parent</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== Gallery Section ========== -->
    <?php if (!empty($gallery_list)): ?>
    <section class="gallery-section" id="gallery">
        <div class="container">
            <h2 class="section-title">📸 Our Gallery</h2>
            <p class="section-subtitle">Explore memorable moments from our school</p>
            
            <div class="gallery-grid">
                <?php foreach ($gallery_list as $image): ?>
                <div class="gallery-item">
                    <img src="<?php echo $image['image_path']; ?>" alt="<?php echo htmlspecialchars($image['alt_text']); ?>">
                    <div class="gallery-overlay">
                        <div class="gallery-text">
                            <h3><?php echo htmlspecialchars($image['title']); ?></h3>
                            <?php if ($image['category']): ?>
                                <p><?php echo htmlspecialchars($image['category']); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- ========== Events Section ========== -->
    <?php if (!empty($events_list)): ?>
    <section class="events-section" id="events">
        <div class="container">
            <h2 class="section-title">📅 Upcoming Events</h2>
            <p class="section-subtitle">Join us for exciting school events and celebrations</p>
            
            <div class="events-list">
                <?php foreach ($events_list as $event): ?>
                <div class="event-card">
                    <div class="event-date">
                        <span class="date-day"><?php echo date('d', strtotime($event['event_date'])); ?></span>
                        <span class="date-month"><?php echo date('M', strtotime($event['event_date'])); ?></span>
                    </div>
                    <div class="event-content">
                        <h3><?php echo htmlspecialchars($event['title']); ?></h3>
                        <p class="event-time">
                            🕐 <?php echo $event['event_time'] ? date('h:i A', strtotime($event['event_time'])) : 'All day'; ?>
                        </p>
                        <?php if ($event['location']): ?>
                            <p class="event-location">📍 <?php echo htmlspecialchars($event['location']); ?></p>
                        <?php endif; ?>
                        <p><?php echo substr(htmlspecialchars($event['description']), 0, 100); ?>...</p>
                        <?php if ($event['category']): ?>
                            <span class="event-badge"><?php echo htmlspecialchars($event['category']); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- ========== News/Content Section ========== -->
    <?php if (!empty($content_list)): ?>
    <section class="content-section" id="content">
        <div class="container">
            <h2 class="section-title">📰 Latest News</h2>
            <p class="section-subtitle">Stay updated with the latest school news and announcements</p>
            
            <div class="content-grid">
                <?php foreach ($content_list as $post): ?>
                <div class="content-card">
                    <?php if ($post['featured_image']): ?>
                        <img src="<?php echo $post['featured_image']; ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" class="content-image">
                    <?php endif; ?>
                    <div class="content-body">
                        <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                        <p class="content-date">📅 <?php echo date('M d, Y', strtotime($post['created_at'])); ?></p>
                        <p class="content-excerpt"><?php echo substr(htmlspecialchars($post['description']), 0, 80); ?>...</p>
                        <a href="#content-<?php echo $post['id']; ?>" class="read-more">Read More →</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- ========== CTA Section ========== -->
    <section class="cta-section">
        <div class="container">
            <h2>Ready to Experience Modern School Management?</h2>
            <p>Join us and revolutionize your school's administration</p>
            <a href="admin/dashboard.php" class="btn btn-primary btn-large">Access Admin Portal</a>
        </div>
    </section>

    <!-- ========== Contact Section ========== -->
    <section class="contact" id="contact">
        <div class="container">
            <h2 class="section-title">Get in Touch</h2>
            
            <div class="contact-grid">
                <div class="contact-info">
                    <div class="contact-item">
                        <span class="contact-icon">📍</span>
                        <div>
                            <h4>Address</h4>
                            <p>123 Education Street<br>School City, SC 12345</p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <span class="contact-icon">📞</span>
                        <div>
                            <h4>Phone</h4>
                            <p>+1 (555) 123-4567<br>+1 (555) 123-4568</p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <span class="contact-icon">✉️</span>
                        <div>
                            <h4>Email</h4>
                            <p>info@school.com<br>admin@school.com</p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <span class="contact-icon">🕐</span>
                        <div>
                            <h4>Hours</h4>
                            <p>Mon - Fri: 8:00 AM - 4:00 PM<br>Sat: 9:00 AM - 1:00 PM</p>
                        </div>
                    </div>
                </div>

                <form class="contact-form">
                    <div class="form-group">
                        <input type="text" placeholder="Your Name" required>
                    </div>
                    <div class="form-group">
                        <input type="email" placeholder="Your Email" required>
                    </div>
                    <div class="form-group">
                        <input type="text" placeholder="Subject">
                    </div>
                    <div class="form-group">
                        <textarea placeholder="Message" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Send Message</button>
                </form>
            </div>
        </div>
    </section>

    <!-- ========== Footer ========== -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h4>About</h4>
                <ul>
                    <li><a href="#about">About Us</a></li>
                    <li><a href="#features">Features</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="admin/dashboard.php">Admin Login</a></li>
                    <li><a href="#statistics">Statistics</a></li>
                    <li><a href="#features">Technology</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h4>Follow Us</h4>
                <ul>
                    <li><a href="#">Facebook</a></li>
                    <li><a href="#">Twitter</a></li>
                    <li><a href="#">Instagram</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; 2024 School Management System. All rights reserved.</p>
        </div>
    </footer>

    <script src="assets/js/frontend.js"></script>
</body>
</html>
