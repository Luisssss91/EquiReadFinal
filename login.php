<?php include 'config.php'; ?>

<?php
$email = $password = "";
$email_err = $password_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if email is empty
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter email.";
    } else{
        $email = trim($_POST["email"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($email_err) && empty($password_err)){
        $sql = "SELECT user_id, full_name, email, password, role, accessibility_profile FROM tbl_users WHERE email = ?";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            $param_email = $email;
            
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                
                // Check if email exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    mysqli_stmt_bind_result($stmt, $id, $full_name, $email, $hashed_password, $role, $accessibility_profile);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["user_id"] = $id;
                            $_SESSION["full_name"] = $full_name;
                            $_SESSION["email"] = $email;
                            $_SESSION["role"] = $role;
                            $_SESSION["accessibility_profile"] = $accessibility_profile;
                            
                            // Redirect user to appropriate page
                            if($role == "admin"){
                                header("location: dashboard.php");
                            } else{
                                header("location: index.php");
                            }
                        } else{
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else{
                    $email_err = "No account found with that email.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
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
            <!-- Logo Section - Perfectly Centered -->
            <div style="text-align: center; margin-bottom: .5rem;">
                <img src="images/balds.png" alt="Site Logo" style="max-width: 130px; display: inline-block;">
            </div>
            
            <h2 style="text-align: center; margin-bottom: 0.5rem;">Welcome Back!</h2>
            <p style="text-align: center; color: #6c757d; margin-bottom: 1.5rem;">Please login to your account</p>
            
            <?php if(isset($_GET['registration']) && $_GET['registration'] == 'success'): ?>
                <div class="alert alert-success">Registration successful! Please login.</div>
            <?php endif; ?>
            
            <?php if(isset($_GET['logout']) && $_GET['logout'] == 'success'): ?>
                <div class="alert alert-success">You have been logged out successfully.</div>
            <?php endif; ?>
            
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label><i class="fas fa-envelope"></i> Email</label>
                    <input type="email" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>" placeholder="Enter your email">
                    <span class="invalid-feedback"><?php echo $email_err; ?></span>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-lock"></i> Password</label>
                    <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" placeholder="Enter your password">
                    <span class="invalid-feedback"><?php echo $password_err; ?></span>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block" style="width: 100%;">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </button>
                </div>
                
                <div style="text-align: center; margin-top: 1.5rem;">
                    <p>Don't have an account? <a href="register.php" style="color: #8ACDD7;">Sign up now</a></p>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>