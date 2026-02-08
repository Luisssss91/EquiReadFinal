<?php include 'config.php'; ?>
<?php include 'includes/header.php'; ?>

<div class="main-content">
    <div class="container">
        <!-- Hero Section -->
        <section class="about-hero">
            <div class="hero-content">
                <div class="hero-badge">
                    <i class="fas fa-heart"></i> Our Story
                </div>
                <h1>About EquiRead Digital Library</h1>
                <p class="hero-description">Empowering education through inclusive technology and accessible learning resources for everyone.</p>
            </div>
            <div class="hero-visual">
                <div class="floating-icons">
                    <div class="icon icon-1"><i class="fas fa-universal-access"></i></div>
                    <div class="icon icon-2"><i class="fas fa-book-open"></i></div>
                    <div class="icon icon-3"><i class="fas fa-users"></i></div>
                    <div class="icon icon-4"><i class="fas fa-graduation-cap"></i></div>
                </div>
            </div>
        </section>

        <!-- Mission & Vision -->
        <section class="mission-vision">
            <div class="section-header">
                <h2><i class="fas fa-bullseye"></i> Our Mission & Vision</h2>
                <p>Driving educational equality through technology</p>
            </div>
            <div class="mv-grid">
                <div class="mv-card">
                    <div class="mv-icon">
                        <i class="fas fa-bullseye"></i>
                    </div>
                    <h3>Our Mission</h3>
                    <p>To provide equal access to educational resources for all learners, regardless of their abilities or disabilities. We believe that knowledge should be accessible to everyone, and technology should bridge gaps, not create them.</p>
                </div>
                <div class="mv-card">
                    <div class="mv-icon">
                        <i class="fas fa-eye"></i>
                    </div>
                    <h3>Our Vision</h3>
                    <p>A world where every learner has the tools and resources they need to succeed. We envision a future where educational barriers are eliminated through innovative, inclusive technology solutions.</p>
                </div>
            </div>
        </section>

        <!-- Values -->
        <section class="values-section">
            <div class="section-header">
                <h2><i class="fas fa-star"></i> Our Values</h2>
                <p>The principles that guide everything we do</p>
            </div>
            <div class="values-grid">
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-universal-access"></i>
                    </div>
                    <h3>Accessibility First</h3>
                    <p>We design every feature with accessibility in mind, ensuring our platform is usable by everyone.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3>Inclusion</h3>
                    <p>We celebrate diversity and create an environment where every learner feels welcome and supported.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-gem"></i>
                    </div>
                    <h3>Quality</h3>
                    <p>We curate high-quality educational resources and maintain the highest standards in everything we do.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Community</h3>
                    <p>We believe in the power of community and collaborative learning to drive educational success.</p>
                </div>
            </div>
        </section>

        <!-- Features Overview -->
        <section class="features-overview">
            <div class="section-header">
                <h2><i class="fas fa-sparkles"></i> What Makes Us Different</h2>
                <p>Innovative features designed for inclusive learning</p>
            </div>
            <div class="features-grid">
                <div class="feature-highlight">
                    <div class="feature-number">01</div>
                    <h3>Full Accessibility Suite</h3>
                    <p>High contrast modes, text-to-speech, adjustable fonts, and keyboard navigation designed for users with various disabilities.</p>
                </div>
                <div class="feature-highlight">
                    <div class="feature-number">02</div>
                    <h3>Multi-Format Resources</h3>
                    <p>Access educational materials in various formats including PDF, audio, video, and interactive content to suit different learning styles.</p>
                </div>
                <div class="feature-highlight">
                    <div class="feature-number">03</div>
                    <h3>Personalized Learning</h3>
                    <p>Customizable accessibility profiles that remember your preferences and adapt the platform to your needs.</p>
                </div>
                <div class="feature-highlight">
                    <div class="feature-number">04</div>
                    <h3>Community Driven</h3>
                    <p>A supportive community of learners and educators working together to create better educational experiences.</p>
                </div>
            </div>
        </section>

        <!-- Statistics -->
        <section class="stats-section">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number"><?php echo mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM tbl_resources"))['count']; ?>+</div>
                    <div class="stat-label">Educational Resources</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM tbl_users"))['count']; ?>+</div>
                    <div class="stat-label">Active Learners</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM tbl_downloads"))['count']; ?>+</div>
                    <div class="stat-label">Resources Downloaded</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Accessibility Support</div>
                </div>
            </div>
        </section>

        <!-- Call to Action -->
        <section class="cta-section">
            <div class="cta-content">
                <h2>Ready to Start Your Learning Journey?</h2>
                <p>Join thousands of learners who are already benefiting from our accessible educational platform.</p>
                <div class="cta-buttons">
                    <a href="register.php" class="btn btn-primary">
                        <i class="fas fa-user-plus"></i> Join Now
                    </a>
                    <a href="resources.php" class="btn btn-outline">
                        <i class="fas fa-book-open"></i> Browse Resources
                    </a>
                </div>
            </div>
        </section>
    </div>
</div>

<style>
/* About Page Specific Styles */
.about-hero {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 3rem;
    align-items: center;
    margin-bottom: 4rem;
    padding: 3rem 0;
}

.about-hero h1 {
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

.floating-icons {
    position: relative;
    width: 100%;
    height: 300px;
}

.icon {
    position: absolute;
    width: 80px;
    height: 80px;
    background: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: var(--primary-color);
    box-shadow: 0 10px 30px rgba(255, 133, 162, 0.3);
    border: 2px solid var(--medium-pink);
    animation: float 6s ease-in-out infinite;
}

.icon-1 {
    top: 10%;
    left: 20%;
    animation-delay: 0s;
    background: linear-gradient(135deg, #ff85a2, #ffb6c1);
    color: white;
}

.icon-2 {
    top: 50%;
    left: 10%;
    animation-delay: 1.5s;
    background: linear-gradient(135deg, #d291bc, #e6b0e6);
    color: white;
}

.icon-3 {
    top: 20%;
    right: 20%;
    animation-delay: 3s;
    background: linear-gradient(135deg, #ffb6c1, #ffd1dc);
    color: var(--dark-pink);
}

.icon-4 {
    bottom: 10%;
    right: 15%;
    animation-delay: 4.5s;
    background: linear-gradient(135deg, #c8a2c8, #d8bfd8);
    color: white;
}

@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(5deg); }
}

/* Mission & Vision */
.mv-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    margin-top: 2rem;
}

.mv-card {
    background: white;
    padding: 2.5rem;
    border-radius: var(--border-radius);
    box-shadow: 0 8px 25px rgba(255, 182, 193, 0.2);
    border: 1px solid var(--medium-pink);
    text-align: center;
    position: relative;
    overflow: hidden;
}

.mv-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
}

.mv-icon {
    width: 80px;
    height: 80px;
    background: var(--light-pink);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    color: var(--primary-color);
    font-size: 2rem;
}

.mv-card h3 {
    color: var(--text-color);
    margin-bottom: 1rem;
    font-size: 1.5rem;
}

.mv-card p {
    color: var(--text-color);
    opacity: 0.8;
    line-height: 1.6;
}

/* Values */
.values-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
    margin-top: 2rem;
}

.value-card {
    background: white;
    padding: 2rem;
    border-radius: var(--border-radius);
    box-shadow: 0 8px 25px rgba(255, 182, 193, 0.2);
    border: 1px solid var(--medium-pink);
    text-align: center;
    transition: all 0.3s ease;
}

.value-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 30px rgba(255, 182, 193, 0.3);
}

.value-icon {
    width: 70px;
    height: 70px;
    background: var(--light-pink);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    color: var(--primary-color);
    font-size: 1.8rem;
}

.value-card h3 {
    color: var(--text-color);
    margin-bottom: 1rem;
    font-size: 1.3rem;
}

.value-card p {
    color: var(--text-color);
    opacity: 0.8;
    line-height: 1.6;
}

/* Features Overview */
.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2rem;
    margin-top: 2rem;
}

.feature-highlight {
    background: white;
    padding: 2rem;
    border-radius: var(--border-radius);
    box-shadow: 0 8px 25px rgba(255, 182, 193, 0.2);
    border: 1px solid var(--medium-pink);
    position: relative;
}

.feature-number {
    position: absolute;
    top: -15px;
    left: -15px;
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 1.2rem;
    box-shadow: 0 4px 15px rgba(255, 133, 162, 0.4);
}

.feature-highlight h3 {
    color: var(--text-color);
    margin-bottom: 1rem;
    font-size: 1.3rem;
    padding-left: 2rem;
}

.feature-highlight p {
    color: var(--text-color);
    opacity: 0.8;
    line-height: 1.6;
}

/* Statistics */
.stats-section {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%);
    padding: 4rem 0;
    border-radius: var(--border-radius);
    margin: 4rem 0;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 2rem;
    text-align: center;
}

.stat-item {
    color: white;
}

.stat-number {
    font-size: 3rem;
    font-weight: bold;
    margin-bottom: 0.5rem;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
}

.stat-label {
    font-size: 1.1rem;
    opacity: 0.9;
}

/* CTA Section */
.cta-section {
    background: white;
    padding: 4rem 2rem;
    border-radius: var(--border-radius);
    box-shadow: 0 8px 25px rgba(255, 182, 193, 0.2);
    border: 1px solid var(--medium-pink);
    text-align: center;
    margin-top: 4rem;
}

.cta-content h2 {
    color: var(--text-color);
    margin-bottom: 1rem;
    font-size: 2.2rem;
}

.cta-content p {
    color: var(--text-color);
    opacity: 0.8;
    margin-bottom: 2rem;
    font-size: 1.1rem;
}

.cta-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

/* Responsive Design */
@media (max-width: 768px) {
    .about-hero {
        grid-template-columns: 1fr;
        text-align: center;
    }
    
    .about-hero h1 {
        font-size: 2.2rem;
    }
    
    .mv-grid {
        grid-template-columns: 1fr;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .cta-buttons {
        flex-direction: column;
        align-items: center;
    }
    
    .floating-icons {
        height: 200px;
    }
    
    .icon {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
    }
}
</style>

<?php include 'includes/footer.php'; ?>