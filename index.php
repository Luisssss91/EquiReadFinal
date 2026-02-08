<?php include 'config.php'; ?>
<?php include 'includes/header.php'; ?>

<div class="main-content">
    <div class="container">
        <!-- Hero Section -->
        <section class="hero-section">
            <div class="hero-content">
                <div class="hero-badge">
                    <i class="fas fa-star"></i> Inclusive Learning Platform
                </div>
                <h1>Welcome to <span class="highlight">EquiRead</span> Digital Library</h1>
                <p class="hero-description">A professional, inclusive space where knowledge meets accessibility. Discover educational resources designed for everyone, including persons with disabilities.</p>
                <div class="hero-buttons">
                    <a href="resources.php" class="btn btn-primary">
                        <i class="fas fa-book-open"></i> Explore Resources
                    </a>
                    <?php if(!isset($_SESSION['loggedin'])): ?>
                        <a href="register.php" class="btn btn-secondary">
                            <i class="fas fa-user-plus"></i> Join Our Community
                        </a>
                    <?php else: ?>
                        <a href="profile.php" class="btn btn-secondary">
                            <i class="fas fa-user"></i> My Profile
                        </a>
                    <?php endif; ?>
                </div>
                <div class="hero-stats">
                    <?php
                    // Get actual statistics from database
                    $resources_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM tbl_resources"))['count'];
                    $downloads_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM tbl_downloads"))['count'];
                    $users_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM tbl_users"))['count'];
                    ?>
                    <div class="stat">
                        <span class="number"><?php echo $resources_count; ?></span>
                        <span class="label">Resources</span>
                    </div>
                    <div class="stat">
                        <span class="number"><?php echo $downloads_count; ?></span>
                        <span class="label">Downloads</span>
                    </div>
                    <div class="stat">
                        <span class="number"><?php echo $users_count; ?></span>
                        <span class="label">Members</span>
                    </div>
                </div>
            </div>
            <div class="hero-visual">
                <div class="floating-books">
                    <div class="book book-1"><i class="fas fa-book"></i></div>
                    <div class="book book-2"><i class="fas fa-file-pdf"></i></div>
                    <div class="book book-3"><i class="fas fa-video"></i></div>
                    <div class="book book-4"><i class="fas fa-headphones"></i></div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="features-section">
            <div class="section-header">
                <h2>
                    <i class="fas fa-sparkles"></i> Why Choose EquiRead?
                </h2>
                <p>
                    We're committed to making education accessible and professional for everyone
                </p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-universal-access"></i>
                    </div>
                    <h3>Full Accessibility</h3>
                    <p>High contrast modes, text-to-speech, and adjustable fonts ensure everyone can learn comfortably</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3>User-Friendly Design</h3>
                    <p>Professional, intuitive interface designed with attention to detail and user experience</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-gem"></i>
                    </div>
                    <h3>Quality Resources</h3>
                    <p>Carefully curated educational materials across various subjects and formats</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Community Focused</h3>
                    <p>Join a supportive community of learners and educators</p>
                </div>
            </div>
        </section>

        <!-- Categories Section -->
        <section class="categories-section">
            <div class="section-header">
                <h2><i class="fas fa-tags"></i> Browse by Category</h2>
                <p>Discover resources organized by subject</p>
            </div>
            <div class="categories-grid">
                <?php
                $categories = mysqli_query($conn, "SELECT * FROM tbl_categories LIMIT 6");
                if($categories && mysqli_num_rows($categories) > 0):
                    while($category = mysqli_fetch_assoc($categories)):
                ?>
                <a href="resources.php?category=<?php echo urlencode($category['name']); ?>" class="category-card" style="--category-color: <?php echo $category['color']; ?>">
                    <div class="category-icon">
                        <i class="<?php echo $category['icon']; ?>"></i>
                    </div>
                    <h3><?php echo htmlspecialchars($category['name']); ?></h3>
                    <p><?php echo htmlspecialchars($category['description']); ?></p>
                    <div class="category-arrow">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </a>
                <?php 
                    endwhile;
                else:
                ?>
                <div class="no-categories">
                    <i class="fas fa-tags fa-3x"></i>
                    <h3>No Categories Available</h3>
                    <p>Categories will appear here once they are added by administrators.</p>
                </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- Recent Resources Section -->
        <section class="recent-resources">
            <div class="section-header">
                <h2><i class="fas fa-gift"></i> Recently Added Resources</h2>
                <p>Fresh content added to our library</p>
            </div>
            <?php
            $sql = "SELECT r.*, c.name as category_name, c.color as category_color 
                    FROM tbl_resources r 
                    LEFT JOIN tbl_categories c ON r.category = c.name 
                    ORDER BY r.upload_date DESC 
                    LIMIT 6";
            $result = mysqli_query($conn, $sql);
            
            if($result && mysqli_num_rows($result) > 0):
            ?>
            <div class="resource-grid">
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                <div class="resource-card">
                    <div class="resource-header">
                        <span class="resource-type" style="background: <?php echo $row['category_color'] ?? '#4a90e2'; ?>">
                            <?php echo htmlspecialchars(strtoupper($row['file_type'])); ?>
                        </span>
                        <span class="resource-category"><?php echo htmlspecialchars($row['category_name'] ?? 'General'); ?></span>
                    </div>
                    <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                    <p><?php echo htmlspecialchars(substr($row['description'], 0, 120)); ?>...</p>
                    <div class="resource-meta">
                        <div class="meta-item">
                            <i class="far fa-calendar"></i>
                            <?php echo date('M j, Y', strtotime($row['upload_date'])); ?>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-download"></i>
                            <?php echo $row['downloads_count']; ?> downloads
                        </div>
                    </div>
                    <div class="resource-actions">
                        <a href="resource_view.php?id=<?php echo $row['resource_id']; ?>" class="btn btn-small">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <?php if($row['file_path']): ?>
                            <a href="resource_view.php?id=<?php echo $row['resource_id']; ?>&download=true" class="btn btn-small btn-secondary">
                                <i class="fas fa-download"></i> Download
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
            <div class="section-footer">
                <a href="resources.php" class="btn btn-outline">
                    <i class="fas fa-book-open"></i> View All Resources
                </a>
            </div>
            <?php else: ?>
                <div class="no-resources">
                    <i class="fas fa-book-open fa-3x"></i>
                    <h3>No resources available yet</h3>
                    <p>Check back soon for new educational materials!</p>
                    <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <a href="add_resource.php" class="btn btn-primary">Add First Resource</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </section>

        <!-- Testimonials Section -->
        <section class="testimonial-section">
            <div class="section-header">
                <h2><i class="fas fa-comment-heart"></i> What Our Users Say</h2>
                <p>Join thousands of satisfied learners</p>
            </div>
            <div class="testimonials-grid">
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <i class="fas fa-quote-left"></i>
                        <p>EquiRead has transformed how I access educational materials as a visually impaired student. The accessibility features are incredible!</p>
                    </div>
                    <div class="testimonial-author">
                        <div class="author-avatar">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <div class="author-info">
                            <h4>Sarah Johnson</h4>
                            <p>Visual Arts Student</p>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <i class="fas fa-quote-left"></i>
                        <p>The professional design and easy navigation make learning enjoyable. I love how everything is so organized and accessible!</p>
                    </div>
                    <div class="testimonial-author">
                        <div class="author-avatar">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <div class="author-info">
                            <h4>Michael Chen</h4>
                            <p>Computer Science</p>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <i class="fas fa-quote-left"></i>
                        <p>As an educator, I appreciate the quality of resources and the commitment to inclusivity. This platform is a game-changer!</p>
                    </div>
                    <div class="testimonial-author">
                        <div class="author-avatar">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <div class="author-info">
                            <h4>Dr. Emily Rodriguez</h4>
                            <p>Mathematics Professor</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<style>
/* Hero Section Styles */
.hero-section {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 3rem;
    align-items: center;
    margin-bottom: 4rem;
    padding: 3rem 0;
}

.hero-badge {
    display: inline-block;
    padding: 0.5rem 1.5rem;
    background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
    color: white;
    border-radius: 50px;
    margin-bottom: 1.5rem;
    font-weight: 600;
    font-size: 0.9rem;
}

.hero-content h1 {
    font-size: 3rem;
    margin-bottom: 1.5rem;
    color: var(--text-color);
    line-height: 1.2;
}

.highlight {
    background: linear-gradient(45deg, var(--primary-color), var(--accent-color));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    font-weight: 700;
}

.hero-description {
    font-size: 1.2rem;
    margin-bottom: 2rem;
    color: var(--text-color);
    opacity: 0.9;
    line-height: 1.6;
}

.hero-buttons {
    display: flex;
    gap: 1rem;
    margin-bottom: 3rem;
    flex-wrap: wrap;
}

.hero-stats {
    display: flex;
    gap: 2rem;
}

.hero-stats .stat {
    text-align: center;
}

.hero-stats .number {
    display: block;
    font-size: 2rem;
    font-weight: bold;
    color: var(--primary-color);
    line-height: 1;
}

.hero-stats .label {
    font-size: 0.9rem;
    color: var(--text-color);
    opacity: 0.8;
}

/* Hero Visual with Floating Books */
.hero-visual {
    position: relative;
    height: 400px;
}

.floating-books {
    position: relative;
    width: 100%;
    height: 100%;
}

.book {
    position: absolute;
    width: 80px;
    height: 80px;
    background: white;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: var(--primary-color);
    box-shadow: 0 10px 30px rgba(74, 144, 226, 0.3);
    border: 2px solid var(--medium-color);
    animation: float 6s ease-in-out infinite;
}

.book-1 {
    top: 20%;
    left: 10%;
    animation-delay: 0s;
    background: linear-gradient(135deg, #4a90e2, #3498db);
    color: white;
}

.book-2 {
    top: 60%;
    left: 20%;
    animation-delay: 1.5s;
    background: linear-gradient(135deg, #5dbb63, #7bcfa9);
    color: white;
}

.book-3 {
    top: 30%;
    right: 20%;
    animation-delay: 3s;
    background: linear-gradient(135deg, #7b68ee, #a59cff);
    color: white;
}

.book-4 {
    bottom: 20%;
    right: 10%;
    animation-delay: 4.5s;
    background: linear-gradient(135deg, #2c3e50, #4a6572);
    color: white;
}

@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(5deg); }
}

/* Features Grid */
.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2rem;
    margin-top: 2rem;
}

.feature-card {
    background: white;
    padding: 2.5rem 2rem;
    border-radius: var(--border-radius);
    text-align: center;
    box-shadow: 0 8px 25px rgba(224, 230, 237, 0.3);
    border: 1px solid var(--medium-color);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.feature-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
}

.feature-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 35px rgba(224, 230, 237, 0.4);
}

.feature-icon {
    width: 80px;
    height: 80px;
    background: #f0f7ff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    color: var(--primary-color);
    font-size: 2rem;
}

.feature-card h3 {
    color: var(--text-color);
    margin-bottom: 1rem;
    font-size: 1.3rem;
}

.feature-card p {
    color: var(--text-color);
    opacity: 0.8;
    line-height: 1.6;
}

/* Categories No Categories State */
.no-categories {
    grid-column: 1 / -1;
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: var(--border-radius);
    border: 2px dashed var(--medium-color);
}

.no-categories i {
    color: var(--medium-color);
    margin-bottom: 1.5rem;
}

.no-categories h3 {
    color: var(--text-color);
    margin-bottom: 1rem;
}

.no-categories p {
    color: var(--text-color);
    opacity: 0.7;
    margin-bottom: 2rem;
}

/* No Resources State */
.no-resources {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: var(--border-radius);
    border: 2px dashed var(--medium-color);
    margin: 2rem 0;
}

.no-resources i {
    color: var(--medium-color);
    margin-bottom: 1.5rem;
}

.no-resources h3 {
    color: var(--text-color);
    margin-bottom: 1rem;
}

.no-resources p {
    color: var(--text-color);
    opacity: 0.7;
    margin-bottom: 2rem;
}

/* Section Footer */
.section-footer {
    text-align: center;
    margin-top: 3rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .hero-section {
        grid-template-columns: 1fr;
        text-align: center;
        gap: 2rem;
    }
    
    .hero-content h1 {
        font-size: 2.2rem;
    }
    
    .hero-buttons {
        justify-content: center;
    }
    
    .hero-stats {
        justify-content: center;
    }
    
    .hero-visual {
        height: 300px;
    }
    
    .book {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
    }
    
    .features-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?php include 'includes/footer.php'; ?>