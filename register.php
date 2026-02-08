<?php include 'config.php'; ?>

<?php
$full_name = $email = $password = $confirm_password = "";
$full_name_err = $email_err = $password_err = $confirm_password_err = $terms_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    // Validate full name
    if(empty(trim($_POST["full_name"]))){
        $full_name_err = "Please enter your full name.";
    } else{
        $full_name = trim($_POST["full_name"]);
    }
    
    // Validate email
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter an email.";
    } else{
        $sql = "SELECT user_id FROM tbl_users WHERE email = ?";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            $param_email = trim($_POST["email"]);
            
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $email_err = "This email is already taken.";
                } else{
                    $email = trim($_POST["email"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have at least 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    
    // Validate terms and conditions
    if(!isset($_POST["agree_terms"])){
        $terms_err = "You must agree to the Terms & Conditions to register.";
    }
    
    // Check input errors before inserting in database
    if(empty($full_name_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err) && empty($terms_err)){
        
        $sql = "INSERT INTO tbl_users (full_name, email, password, accessibility_profile, agreed_terms) VALUES (?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "sssss", $param_full_name, $param_email, $param_password, $param_accessibility, $param_agreed_terms);
            
            $param_full_name = $full_name;
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT);
            $param_accessibility = "normal";
            $param_agreed_terms = "yes"; // or you could use timestamp: date('Y-m-d H:i:s')
            
            if(mysqli_stmt_execute($stmt)){
                header("location: login.php?registration=success");
            } else{
                echo "Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    }
    
    mysqli_close($conn);
}
?>

<?php include 'includes/header.php'; ?>

<div class="main-content">
    <div class="container">
        <div class="form-container">
            <h2>Create Your Account</h2>
            <p>Please fill in this form to create an account.</p>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="full_name" class="form-control <?php echo (!empty($full_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $full_name; ?>">
                    <span class="invalid-feedback"><?php echo $full_name_err; ?></span>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                    <span class="invalid-feedback"><?php echo $email_err; ?></span>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                    <span class="invalid-feedback"><?php echo $password_err; ?></span>
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                    <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                </div>
                
                <!-- Terms and Conditions Section -->
                <div class="form-group terms-section">
                    <div class="terms-container <?php echo (!empty($terms_err)) ? 'terms-error' : ''; ?>">
                        <input type="checkbox" name="agree_terms" id="agree_terms" value="1" <?php echo (isset($_POST['agree_terms'])) ? 'checked' : ''; ?>>
                        <label for="agree_terms">
                            I agree to the <a href="terms.php" target="_blank">Terms & Conditions</a> and <a href="privacy.php" target="_blank">Privacy Policy</a>
                        </label>
                    </div>
                    <?php if(!empty($terms_err)): ?>
                        <span class="terms-error-message"><?php echo $terms_err; ?></span>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <input type="submit" class="btn" value="Register">
                </div>
                <p>Already have an account? <a href="login.php">Login here</a>.</p>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>