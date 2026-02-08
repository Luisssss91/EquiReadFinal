<?php 
$page_title = "Terms & Conditions";
include 'includes/header.php'; 
?>

<div class="main-content">
    <div class="container">
        <div class="terms-container">
            <div class="page-header">
                <h1>Terms & Conditions</h1>
                <p class="last-updated">Last updated: <?php echo date('F j, Y'); ?></p>
            </div>
            
            <div class="terms-content">
                <div class="alert alert-info">
                    <strong>Please read these terms carefully before using our service.</strong>
                </div>

                <section class="terms-section">
                    <h2>1. Acceptance of Terms</h2>
                    <p>By accessing and using this website ("Service"), you accept and agree to be bound by the terms and provision of this agreement. Your use of our Service is also subject to our Privacy Policy. Please read these Terms & Conditions carefully before using our Service.</p>
                </section>

                <section class="terms-section">
                    <h2>2. User Account</h2>
                    <p>To access certain features of the Service, you must register for an account. When you register, you agree to:</p>
                    <ul>
                        <li>Provide accurate, current, and complete information</li>
                        <li>Maintain the security of your password</li>
                        <li>Accept all risks of unauthorized access to your account</li>
                        <li>Notify us immediately if you discover or suspect any security breaches</li>
                    </ul>
                </section>

                <section class="terms-section">
                    <h2>3. User Responsibilities</h2>
                    <p>You are responsible for all activities that occur under your account. You agree not to:</p>
                    <ul>
                        <li>Use the Service for any illegal purpose or in violation of any laws</li>
                        <li>Harass, abuse, or harm another person</li>
                        <li>Use the Service to distribute spam or malicious software</li>
                        <li>Interfere with or disrupt the Service or servers</li>
                        <li>Attempt to gain unauthorized access to any portion of the Service</li>
                    </ul>
                </section>

                <section class="terms-section">
                    <h2>4. Intellectual Property</h2>
                    <p>The Service and its original content, features, and functionality are owned by us and are protected by international copyright, trademark, patent, trade secret, and other intellectual property laws.</p>
                </section>

                <section class="terms-section">
                    <h2>5. Termination</h2>
                    <p>We may terminate or suspend your account immediately, without prior notice or liability, for any reason whatsoever, including without limitation if you breach the Terms. Upon termination, your right to use the Service will immediately cease.</p>
                </section>

                <section class="terms-section">
                    <h2>6. Limitation of Liability</h2>
                    <p>In no event shall we, nor our directors, employees, partners, agents, suppliers, or affiliates, be liable for any indirect, incidental, special, consequential or punitive damages, including without limitation, loss of profits, data, use, goodwill, or other intangible losses, resulting from your access to or use of or inability to access or use the Service.</p>
                </section>

                <section class="terms-section">
                    <h2>7. Changes to Terms</h2>
                    <p>We reserve the right, at our sole discretion, to modify or replace these Terms at any time. If a revision is material, we will provide at least 30 days' notice prior to any new terms taking effect. What constitutes a material change will be determined at our sole discretion.</p>
                </section>

                <section class="terms-section">
                    <h2>8. Governing Law</h2>
                    <p>These Terms shall be governed and construed in accordance with the laws of [Your Country/State], without regard to its conflict of law provisions.</p>
                </section>

                <section class="terms-section">
                    <h2>9. Contact Information</h2>
                    <p>If you have any questions about these Terms, please contact us at:</p>
                    <p>
                        <strong>Email:</strong> support@equiread.org<br>
                        <strong>Address:</strong> Barangay Old Sagay , Sagay City.
                    </p>
                </section>

                <div class="terms-actions">
                    <a href="register.php" class="btn btn-primary">Back to Registration</a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.terms-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 30px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.page-header {
    border-bottom: 2px solid #007bff;
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

.terms-section {
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 1px solid #eee;
}

.terms-section:last-of-type {
    border-bottom: none;
}

.terms-section h2 {
    color: #007bff;
    margin-bottom: 15px;
    font-size: 1.4em;
}

.terms-section p {
    line-height: 1.6;
    color: #555;
    margin-bottom: 10px;
}

.terms-section ul {
    padding-left: 20px;
    margin: 15px 0;
}

.terms-section li {
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

.terms-actions {
    text-align: center;
    margin-top: 40px;
    padding-top: 20px;
    border-top: 1px solid #eee;
}

.btn-primary {
    background-color: #007bff;
    color: white;
    padding: 12px 30px;
    text-decoration: none;
    border-radius: 4px;
    display: inline-block;
    transition: background-color 0.3s;
}

.btn-primary:hover {
    background-color: #0056b3;
    color: white;
    text-decoration: none;
}

@media (max-width: 768px) {
    .terms-container {
        padding: 20px;
        margin: 10px;
    }
    
    .page-header h1 {
        font-size: 1.8em;
    }
    
    .terms-section h2 {
        font-size: 1.2em;
    }
}
</style>

<?php include 'includes/footer.php'; ?>