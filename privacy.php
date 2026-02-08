<?php 
$page_title = "Privacy Policy";
include 'includes/header.php'; 
?>

<div class="main-content">
    <div class="container">
        <div class="privacy-container">
            <div class="page-header">
                <h1>Privacy Policy</h1>
                <p class="last-updated">Last updated: <?php echo date('F j, Y'); ?></p>
            </div>
            
            <div class="privacy-content">
                <div class="alert alert-info">
                    <strong>Your privacy is important to us. This policy explains how we collect, use, and protect your personal information.</strong>
                </div>

                <section class="privacy-section">
                    <h2>1. Information We Collect</h2>
                    <h3>Personal Information</h3>
                    <p>When you register for an account, we collect:</p>
                    <ul>
                        <li>Full name</li>
                        <li>Email address</li>
                        <li>Password (encrypted)</li>
                        <li>Accessibility preferences</li>
                        <li>Account creation date</li>
                    </ul>

                    <h3>Automatically Collected Information</h3>
                    <p>We may automatically collect certain information when you use our Service:</p>
                    <ul>
                        <li>IP address and browser type</li>
                        <li>Device information</li>
                        <li>Usage data and analytics</li>
                        <li>Cookies and similar tracking technologies</li>
                    </ul>
                </section>

                <section class="privacy-section">
                    <h2>2. How We Use Your Information</h2>
                    <p>We use the collected information for various purposes:</p>
                    <ul>
                        <li>To provide and maintain our Service</li>
                        <li>To notify you about changes to our Service</li>
                        <li>To allow you to participate in interactive features</li>
                        <li>To provide customer support</li>
                        <li>To gather analysis or valuable information to improve our Service</li>
                        <li>To monitor the usage of our Service</li>
                        <li>To detect, prevent and address technical issues</li>
                    </ul>
                </section>

                <section class="privacy-section">
                    <h2>3. Data Protection</h2>
                    <p>We implement appropriate security measures to protect your personal information:</p>
                    <ul>
                        <li>Passwords are encrypted using bcrypt hashing</li>
                        <li>SSL encryption for data transmission</li>
                        <li>Regular security assessments</li>
                        <li>Limited access to personal data</li>
                    </ul>
                </section>

                <section class="privacy-section">
                    <h2>4. Data Sharing and Disclosure</h2>
                    <p>We do not sell, trade, or rent your personal identification information to others. We may share generic aggregated demographic information not linked to any personal identification information regarding visitors and users with our business partners.</p>
                    
                    <p>We may disclose your personal information in the following circumstances:</p>
                    <ul>
                        <li>To comply with a legal obligation</li>
                        <li>To protect and defend our rights or property</li>
                        <li>To prevent or investigate possible wrongdoing in connection with the Service</li>
                        <li>To protect the personal safety of users of the Service or the public</li>
                    </ul>
                </section>

                <section class="privacy-section">
                    <h2>5. Your Data Rights</h2>
                    <p>You have the right to:</p>
                    <ul>
                        <li>Access and receive a copy of your personal data</li>
                        <li>Rectify or update your personal data</li>
                        <li>Request deletion of your personal data</li>
                        <li>Object to processing of your personal data</li>
                        <li>Data portability</li>
                        <li>Withdraw consent at any time</li>
                    </ul>
                    <p>To exercise these rights, please contact us using the information below.</p>
                </section>

                <section class="privacy-section">
                    <h2>6. Cookies</h2>
                    <p>We use cookies and similar tracking technologies to track activity on our Service and hold certain information. Cookies are files with small amount of data which may include an anonymous unique identifier.</p>
                    <p>You can instruct your browser to refuse all cookies or to indicate when a cookie is being sent. However, if you do not accept cookies, you may not be able to use some portions of our Service.</p>
                </section>

                <section class="privacy-section">
                    <h2>7. Data Retention</h2>
                    <p>We will retain your personal information only for as long as is necessary for the purposes set out in this Privacy Policy. We will retain and use your information to the extent necessary to comply with our legal obligations, resolve disputes, and enforce our policies.</p>
                </section>

                <section class="privacy-section">
                    <h2>8. Children's Privacy</h2>
                    <p>Our Service does not address anyone under the age of 13. We do not knowingly collect personally identifiable information from anyone under the age of 13. If you are a parent or guardian and you are aware that your child has provided us with personal data, please contact us.</p>
                </section>

                <section class="privacy-section">
                    <h2>9. Changes to This Privacy Policy</h2>
                    <p>We may update our Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on this page and updating the "last updated" date.</p>
                    <p>You are advised to review this Privacy Policy periodically for any changes. Changes to this Privacy Policy are effective when they are posted on this page.</p>
                </section>

                <section class="privacy-section">
                    <h2>10. Contact Us</h2>
                    <p>If you have any questions about this Privacy Policy, please contact us:</p>
                    <p>
                        <strong>Email:</strong> support@equiread.org<br>
                        <strong>Address:</strong> Barangay Old Sagay , Sagay City<br>
                        <strong>Phone:</strong> 09678652741
                    </p>
                </section>

                <div class="privacy-actions">
                    <a href="register.php" class="btn btn-primary">Back to Registration</a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.privacy-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 30px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.page-header {
    border-bottom: 2px solid #28a745;
    padding-bottom: 20px;
    margin-bottom: 30px;
}

.page-header h1 {
    color: #333;
    margin-bottom: 5px;
}

.last-updated {
    color: #666;
    font-style: italic;
}

.privacy-section {
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 1px solid #eee;
}

.privacy-section:last-of-type {
    border-bottom: none;
}

.privacy-section h2 {
    color: #28a745;
    margin-bottom: 15px;
    font-size: 1.4em;
}

.privacy-section h3 {
    color: #555;
    margin: 20px 0 10px 0;
    font-size: 1.1em;
}

.privacy-section p {
    line-height: 1.6;
    color: #555;
    margin-bottom: 10px;
}

.privacy-section ul {
    padding-left: 20px;
    margin: 15px 0;
}

.privacy-section li {
    margin-bottom: 8px;
    line-height: 1.5;
}

.alert-info {
    background-color: #d1ecf1;
    border-color: #bee5eb;
    color: #0c5460;
    padding: 15px;
    border-radius: 4px;
    margin-bottom: 30px;
}

.privacy-actions {
    text-align: center;
    margin-top: 40px;
    padding-top: 20px;
    border-top: 1px solid #eee;
}

.btn-primary {
    background-color: #28a745;
    color: white;
    padding: 12px 30px;
    text-decoration: none;
    border-radius: 4px;
    display: inline-block;
    transition: background-color 0.3s;
}

.btn-primary:hover {
    background-color: #218838;
    color: white;
    text-decoration: none;
}

@media (max-width: 768px) {
    .privacy-container {
        padding: 20px;
        margin: 10px;
    }
    
    .page-header h1 {
        font-size: 1.8em;
    }
    
    .privacy-section h2 {
        font-size: 1.2em;
    }
    
    .privacy-section h3 {
        font-size: 1em;
    }
}
</style>

<?php include 'includes/footer.php'; ?>