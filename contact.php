<?php include 'config.php'; ?>
<?php include 'includes/header.php'; ?>

<div class="main-content">
    <div class="container">
        <!-- Hero Section -->
        <section class="contact-hero">
            <div class="hero-content">
                <div class="hero-badge">
                    <i class="fas fa-envelope"></i> Get In Touch
                </div>
                <h1>Contact Us</h1>
                <p class="hero-description">We'd love to hear from you! Whether you have questions, feedback, or need support, our team is here to help.</p>
            </div>
            <div class="hero-visual">
                <div class="contact-visual">
                    <i class="fas fa-comments"></i>
                </div>
            </div>
        </section>

        <div class="contact-layout">
            <!-- Contact Form -->
            <div class="contact-form-section" id="contact-form">
                <div class="section-header">
                    <h2><i class="fas fa-paper-plane"></i> Send us a Message</h2>
                    <p>Fill out the form below and we'll get back to you as soon as possible.</p>
                </div>

                <?php
                // Handle form submission
                if($_SERVER["REQUEST_METHOD"] == "POST") {
                    $name = trim($_POST["name"] ?? '');
                    $email = trim($_POST["email"] ?? '');
                    $subject = trim($_POST["subject"] ?? '');
                    $message = trim($_POST["message"] ?? '');
                    
                    // Basic validation
                    $errors = [];
                    
                    if(empty($name)) {
                        $errors[] = "Please enter your name.";
                    }
                    
                    if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $errors[] = "Please enter a valid email address.";
                    }
                    
                    if(empty($subject)) {
                        $errors[] = "Please enter a subject.";
                    }
                    
                    if(empty($message)) {
                        $errors[] = "Please enter your message.";
                    }
                    
                    if(empty($errors)) {
                        // In a real application, you would send an email here
                        // For now, we'll just show a success message
                        $success_message = "Thank you for your message! We'll get back to you within 24 hours.";
                        
                        // Clear form fields
                        $name = $email = $subject = $message = "";
                    } else {
                        $error_message = implode("<br>", $errors);
                    }
                }
                ?>

                <?php if(isset($success_message)): ?>
                    <div class="alert alert-success"><?php echo $success_message; ?></div>
                <?php endif; ?>
                
                <?php if(isset($error_message)): ?>
                    <div class="alert alert-error"><?php echo $error_message; ?></div>
                <?php endif; ?>

                <form method="POST" class="contact-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Full Name *</label>
                            <input type="text" id="name" name="name" class="form-control" 
                                   value="<?php echo htmlspecialchars($name ?? ''); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" name="email" class="form-control" 
                                   value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="subject">Subject *</label>
                        <input type="text" id="subject" name="subject" class="form-control" 
                               value="<?php echo htmlspecialchars($subject ?? ''); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="message">Message *</label>
                        <textarea id="message" name="message" class="form-control" rows="6" 
                                  required placeholder="Tell us how we can help you..."><?php echo htmlspecialchars($message ?? ''); ?></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-large">
                        <i class="fas fa-paper-plane"></i> Send Message
                    </button>
                </form>
            </div>

            <!-- Team & Contact Info -->
            <div class="contact-sidebar">
                <!-- Team Section -->
                <div class="team-section">
                    <h3><i class="fas fa-users"></i> Meet Our Team</h3>
                    <p class="team-description">Dedicated professionals committed to making education accessible for everyone.</p>
                    
                    <div class="team-grid">
                        <div class="team-member">
                            <div class="member-avatar">
                                <img src="images/team/christian-caballero.jpg" alt="Christian Caballero" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="avatar-fallback" style="display: none;">
                                    <i class="fas fa-user"></i>
                                </div>
                            </div>
                            <div class="member-info">
                                <h4>Christian Caballero</h4>
                                <p>Head Librarian</p>
                            </div>
                        </div>
                        
                        <div class="team-member">
                            <div class="member-avatar">
                                <img src="images/team/aimie-canomay.jpg" alt="Aimie Canomay" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="avatar-fallback" style="display: none;">
                                    <i class="fas fa-user"></i>
                                </div>
                            </div>
                            <div class="member-info">
                                <h4>Aimie Canomay</h4>
                                <p>Librarian</p>
                            </div>
                        </div>
                        
                        <div class="team-member">
                            <div class="member-avatar">
                                <img src="images/team/daniela-esolan.jpg" alt="Daniela Esolan" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="avatar-fallback" style="display: none;">
                                    <i class="fas fa-user"></i>
                                </div>
                            </div>
                            <div class="member-info">
                                <h4>Daniela Esolan</h4>
                                <p>Librarian</p>
                            </div>
                        </div>
                        
                        <div class="team-member">
                            <div class="member-avatar">
                                <img src="images/team/diana-marie-sultan.jpg" alt="Diana Marie Sultan" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="avatar-fallback" style="display: none;">
                                    <i class="fas fa-user"></i>
                                </div>
                            </div>
                            <div class="member-info">
                                <h4>Diana Marie Sultan</h4>
                                <p>Assistant/Technicianss</p>
                            </div>
                        </div>
                        
                        <div class="team-member">
                            <div class="member-avatar">
                                <img src="images/team/rizalyn-biasca.jpg" alt="Rizalyn Biasca" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="avatar-fallback" style="display: none;">
                                    <i class="fas fa-user"></i>
                                </div>
                            </div>
                            <div class="member-info">
                                <h4>Rizalyn Biasca</h4>
                                <p>Assistant/Technician</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="contact-info">
                    <h3><i class="fas fa-info-circle"></i> Contact Information</h3>
                    <div class="info-list">
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="info-content">
                                <h4>Email</h4>
                                <p>support@equiread.org</p>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="info-content">
                                <h4>Phone</h4>
                                <p>09678652741 / 09750268174</p>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="info-content">
                                <h4>Response Time</h4>
                                <p>Within 24 hours</p>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-headset"></i>
                            </div>
                            <div class="info-content">
                                <h4>Support Hours</h4>
                                <p>Mon-Fri: 9AM-6PM</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FAQ Quick Links -->
                <div class="faq-section">
                    <h3><i class="fas fa-question-circle"></i> Quick Help</h3>
                    <div class="faq-links">
                        <div class="faq-item">
                            <div class="faq-question">
                                <i class="fas fa-book"></i> How to access resources?
                                <span class="faq-toggle"><i class="fas fa-chevron-down"></i></span>
                            </div>
                            <div class="faq-answer">
                                <p>To access resources:</p>
                                <ol>
                                    <li>Navigate to the <strong>Resources</strong> page from the main menu</li>
                                    <li>Use the search bar to find specific resources or browse by category</li>
                                    <li>Click on any resource card to view details</li>
                                    <li>Click the "Download" button to save the resource to your device</li>
                                    <li>Registered users can also favorite resources for quick access later</li>
                                </ol>
                            </div>
                        </div>
                        
                        <div class="faq-item">
                            <div class="faq-question">
                                <i class="fas fa-universal-access"></i> Accessibility features guide
                                <span class="faq-toggle"><i class="fas fa-chevron-down"></i></span>
                            </div>
                            <div class="faq-answer">
                                <p>Our platform includes several accessibility features:</p>
                                <ul>
                                    <li><strong>High Contrast Mode:</strong> Toggle this in the header for better visibility</li>
                                    <li><strong>Text Size Adjustment:</strong> Use A+/A- buttons to adjust font size</li>
                                    <li><strong>Text-to-Speech:</strong> Click the "Read" button to have content read aloud</li>
                                    <li><strong>Keyboard Navigation:</strong> Navigate using Tab key and Enter to select</li>
                                    <li><strong>Screen Reader Compatible:</strong> Full support for screen readers like JAWS and NVDA</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="faq-item">
                            <div class="faq-question">
                                <i class="fas fa-user-plus"></i> Account registration help
                                <span class="faq-toggle"><i class="fas fa-chevron-down"></i></span>
                            </div>
                            <div class="faq-answer">
                                <p>Creating an account is easy:</p>
                                <ol>
                                    <li>Click on <strong>Register</strong> in the main navigation</li>
                                    <li>Fill in your full name, email address, and password</li>
                                    <li>Confirm your password</li>
                                    <li>Click the "Register" button</li>
                                    <li>Check your email for a confirmation message (if required)</li>
                                    <li>Log in with your new credentials</li>
                                </ol>
                                <p><strong>Benefits of registering:</strong></p>
                                <ul>
                                    <li>Track your download history</li>
                                    <li>Save favorite resources</li>
                                    <li>Personalize accessibility settings</li>
                                    <li>Receive notifications about new resources</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="faq-item">
                            <div class="faq-question">
                                <i class="fas fa-download"></i> Download issues
                                <span class="faq-toggle"><i class="fas fa-chevron-down"></i></span>
                            </div>
                            <div class="faq-answer">
                                <p>If you're experiencing download issues, try these solutions:</p>
                                <ul>
                                    <li><strong>Check your internet connection</strong> - Ensure you have a stable connection</li>
                                    <li><strong>Clear browser cache</strong> - Sometimes cached data can cause issues</li>
                                    <li><strong>Try a different browser</strong> - Some browsers handle downloads differently</li>
                                    <li><strong>Check storage space</strong> - Ensure you have enough space on your device</li>
                                    <li><strong>Disable pop-up blockers</strong> - Some downloads may be blocked by pop-up blockers</li>
                                    <li><strong>Update your browser</strong> - Make sure you're using the latest version</li>
                                </ul>
                                <p>If problems persist, please <a href="#contact-form" class="text-link">contact our support team</a> with details about the issue.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const faqItems = document.querySelectorAll('.faq-item');
    
    faqItems.forEach(item => {
        const question = item.querySelector('.faq-question');
        const answer = item.querySelector('.faq-answer');
        const toggle = item.querySelector('.faq-toggle');
        
        question.addEventListener('click', function() {
            // Close all other FAQ items
            faqItems.forEach(otherItem => {
                if (otherItem !== item) {
                    otherItem.classList.remove('active');
                    otherItem.querySelector('.faq-answer').style.maxHeight = null;
                    otherItem.querySelector('.faq-toggle i').className = 'fas fa-chevron-down';
                }
            });
            
            // Toggle current item
            item.classList.toggle('active');
            
            if (item.classList.contains('active')) {
                answer.style.maxHeight = answer.scrollHeight + 'px';
                toggle.innerHTML = '<i class="fas fa-chevron-up"></i>';
            } else {
                answer.style.maxHeight = null;
                toggle.innerHTML = '<i class="fas fa-chevron-down"></i>';
            }
        });
    });
    
    // Auto-open FAQ if URL has hash
    const urlHash = window.location.hash;
    if (urlHash === '#contact-form') {
        // Find the download issues FAQ and open it
        const downloadFaq = document.querySelector('.faq-item:nth-child(4)');
        if (downloadFaq) {
            const answer = downloadFaq.querySelector('.faq-answer');
            const toggle = downloadFaq.querySelector('.faq-toggle');
            
            downloadFaq.classList.add('active');
            answer.style.maxHeight = answer.scrollHeight + 'px';
            toggle.innerHTML = '<i class="fas fa-chevron-up"></i>';
            
            // Scroll to the FAQ section
            setTimeout(() => {
                downloadFaq.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }, 300);
        }
    }
});
</script>

<style>
/* Contact Page Specific Styles */
.contact-hero {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 3rem;
    align-items: center;
    margin-bottom: 4rem;
    padding: 3rem 0;
}

.contact-hero h1 {
    font-size: 3rem;
    margin-bottom: 1.5rem;
    color: var(--text-color);
    line-height: 1.2;
}

.hero-description {
    font-size: 1.2rem;
    margin-bottom: 2rem;
    color: var(--text-color);
    opacity: 0.9;
    line-height: 1.6;
}

.contact-visual {
    text-align: center;
}

.contact-visual i {
    font-size: 8rem;
    color: var(--primary-color);
    opacity: 0.8;
}

.contact-layout {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 3rem;
    margin-top: 2rem;
}

/* Contact Form */
.contact-form-section {
    background: white;
    padding: 2.5rem;
    border-radius: var(--border-radius);
    box-shadow: 0 8px 25px rgba(255, 182, 193, 0.2);
    border: 1px solid var(--medium-pink);
}

.contact-form .form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}

.contact-form .form-group {
    margin-bottom: 1.5rem;
}

.contact-form label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: var(--dark-pink);
}

.contact-form .form-control {
    width: 100%;
    padding: 0.9rem;
    border: 2px solid var(--medium-pink);
    border-radius: 12px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: var(--light-pink);
}

.contact-form .form-control:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(255, 133, 162, 0.2);
    background: white;
}

.contact-form textarea.form-control {
    resize: vertical;
    min-height: 150px;
}

/* Contact Sidebar */
.contact-sidebar {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.team-section,
.contact-info,
.faq-section {
    background: white;
    padding: 2rem;
    border-radius: var(--border-radius);
    box-shadow: 0 8px 25px rgba(255, 182, 193, 0.2);
    border: 1px solid var(--medium-pink);
}

.team-section h3,
.contact-info h3,
.faq-section h3 {
    color: var(--dark-pink);
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.team-description {
    color: var(--text-color);
    opacity: 0.8;
    margin-bottom: 1.5rem;
    line-height: 1.5;
}

.team-grid {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.team-member {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    border-radius: 8px;
    transition: background-color 0.3s ease;
}

.team-member:hover {
    background: var(--light-pink);
}

.member-avatar {
    width: 50px;
    height: 50px;
    background: var(--light-pink);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary-color);
    font-size: 1.2rem;
    flex-shrink: 0;
    overflow: hidden;
    position: relative;
}

.member-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
}

.avatar-fallback {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--light-pink);
    border-radius: 50%;
    color: var(--primary-color);
}

.member-info h4 {
    margin: 0 0 0.25rem 0;
    color: var(--text-color);
    font-size: 1rem;
}

.member-info p {
    margin: 0;
    color: var(--text-color);
    opacity: 0.7;
    font-size: 0.9rem;
}

/* Contact Info */
.info-list {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.info-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
}

.info-icon {
    width: 40px;
    height: 40px;
    background: var(--light-pink);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary-color);
    flex-shrink: 0;
}

.info-content h4 {
    margin: 0 0 0.25rem 0;
    color: var(--text-color);
    font-size: 1rem;
}

.info-content p {
    margin: 0;
    color: var(--text-color);
    opacity: 0.8;
}

/* FAQ Section */
.faq-links {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.faq-item {
    border: 1px solid var(--medium-pink);
    border-radius: 8px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.faq-item:hover {
    border-color: var(--primary-color);
}

.faq-question {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem;
    background: var(--light-pink);
    cursor: pointer;
    transition: background-color 0.3s ease;
    font-weight: 600;
    color: var(--text-color);
}

.faq-question:hover {
    background: var(--medium-pink);
}

.faq-question i {
    color: var(--primary-color);
    margin-right: 0.5rem;
    width: 20px;
    text-align: center;
}

.faq-toggle {
    color: var(--primary-color);
    transition: transform 0.3s ease;
}

.faq-answer {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease;
    background: white;
}

.faq-answer-content {
    padding: 0 1rem;
}

.faq-answer p {
    margin: 1rem 0;
    color: var(--text-color);
    line-height: 1.6;
}

.faq-answer ol,
.faq-answer ul {
    margin: 1rem 0;
    padding-left: 1.5rem;
    color: var(--text-color);
}

.faq-answer li {
    margin-bottom: 0.5rem;
    line-height: 1.5;
}

.text-link {
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 600;
}

.text-link:hover {
    text-decoration: underline;
}

.faq-item.active .faq-question {
    background: var(--primary-color);
    color: white;
}

.faq-item.active .faq-question i {
    color: white;
}

.faq-item.active .faq-toggle {
    color: white;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .contact-layout {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
}

@media (max-width: 768px) {
    .contact-hero {
        grid-template-columns: 1fr;
        text-align: center;
    }
    
    .contact-hero h1 {
        font-size: 2.2rem;
    }
    
    .contact-visual i {
        font-size: 6rem;
    }
    
    .contact-form .form-row {
        grid-template-columns: 1fr;
    }
    
    .team-member {
        flex-direction: column;
        text-align: center;
        gap: 0.75rem;
    }
    
    .info-item {
        flex-direction: column;
        text-align: center;
        gap: 0.75rem;
    }
    
    .info-icon {
        align-self: center;
    }
}
</style>

<?php include 'includes/footer.php'; ?>
