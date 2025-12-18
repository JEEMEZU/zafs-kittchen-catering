<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "connection.php";

session_start();

$errors = [];
$token = '';
$token_valid = false;
$user_email = '';

// Check if token exists in URL
if(isset($_GET['token'])) {
    $token = trim($_GET['token']);
    
    try {
        // Verify token is valid and not expired
        $check_token = "SELECT email, name FROM usertable WHERE reset_token = :token AND reset_expiry > CURRENT_TIMESTAMP AND status = 'verified'";
        $stmt = $conn->prepare($check_token);
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        
        if($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $token_valid = true;
            $user_email = $user['email'];
        } else {
            $errors['token'] = 'Invalid or expired reset link. Please request a new one.';
        }
    } catch(PDOException $e) {
        error_log("Token verification error: " . $e->getMessage());
        $errors['token'] = 'Database error occurred.';
    }
}

// Process password reset
if(isset($_POST['reset-password'])) {
    $token = trim($_POST['token']);
    $new_password = $_POST['password'];
    $confirm_password = $_POST['cpassword'];
    
    // Validate passwords
    if(empty($new_password) || empty($confirm_password)) {
        $errors['password'] = 'Please fill in all fields.';
    } elseif($new_password !== $confirm_password) {
        $errors['password'] = 'Passwords do not match!';
    } elseif(strlen($new_password) < 8) {
        $errors['password'] = 'Password must be at least 8 characters long!';
    } else {
        try {
            // Verify token again
            $check_token = "SELECT email FROM usertable WHERE reset_token = :token AND reset_expiry > CURRENT_TIMESTAMP";
            $stmt = $conn->prepare($check_token);
            $stmt->bindParam(':token', $token);
            $stmt->execute();
            
            if($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                $email = $user['email'];
                
                // Hash new password
                $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
                
                // Update password and clear reset token
                $update_password = "UPDATE usertable SET password = :password, reset_token = NULL, reset_expiry = NULL WHERE email = :email";
                $update_stmt = $conn->prepare($update_password);
                $update_stmt->bindParam(':password', $hashed_password);
                $update_stmt->bindParam(':email', $email);
                
                if($update_stmt->execute()) {
                    error_log("✅ Password reset successful for: $email");
                    $_SESSION['reset_success'] = 'Your password has been reset successfully! You can now sign in with your new password.';
                    header('Location: auth.php');
                    exit();
                } else {
                    $errors['password'] = 'Failed to reset password. Please try again.';
                }
            } else {
                $errors['password'] = 'Invalid or expired reset link.';
            }
        } catch(PDOException $e) {
            error_log("Password reset error: " . $e->getMessage());
            $errors['password'] = 'Database error occurred.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Zaf's Kitchen</title>
    <link rel="icon" type="image/png" href="logo/logo.png">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>
<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap');

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

body {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background-image: url("authbackground/authbackground.jpg");
    background-color: black;
    background-repeat: no-repeat;
    background-position: center center;
    background-size: cover;
    padding: 20px;
    position: relative;
    z-index: 0;
}

body::before {
    content: "";
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: -1;
    pointer-events: none;
}

.reset-container {
    background: #ffffff;
    border-radius: 20px;
    box-shadow: 0 0 30px rgba(0, 0, 0, .3);
    padding: 40px;
    width: 100%;
    max-width: 450px;
    text-align: center;
}

.reset-container h1 {
    color: #DC2626;
    font-size: 32px;
    margin-bottom: 10px;
}

.reset-container h4 {
    font-size: 14px;
    color: #666;
    margin-bottom: 30px;
    line-height: 1.5;
}

.input-box {
    position: relative;
    margin: 20px 0;
}

.input-box input {
    width: 100%;
    padding: 14px 45px 14px 18px;
    background: #eee;
    border-radius: 8px;
    border: none;
    outline: none;
    font-size: 15px;
    color: #333;
    font-weight: 500;
}

.input-box input::placeholder {
    color: #888;
    font-weight: 400;
}

.input-box i {
    position: absolute;
    right: 20px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 20px;
    color: #888;
}

.btn {
    width: 100%;
    height: 45px;
    background: #DC2626;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, .1);
    border: none;
    cursor: pointer;
    font-size: 17px;
    color: #fff;
    font-weight: 600;
    margin-top: 10px;
    transition: all 0.3s ease;
}

.btn:hover {
    background: #B91C1C;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(220, 38, 38, 0.4);
}

.error-message {
    background: #f8d7da;
    color: #721c24;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    border-left: 4px solid #dc3545;
    text-align: left;
    font-size: 14px;
}

.error-message i {
    margin-right: 8px;
}

.back-link {
    margin-top: 20px;
}

.back-link a {
    color: #DC2626;
    text-decoration: none;
    font-size: 14px;
    transition: color 0.3s;
}

.back-link a:hover {
    color: #B91C1C;
    text-decoration: underline;
}

.password-requirements {
    background: #f0f9ff;
    border: 1px solid #bfdbfe;
    border-radius: 8px;
    padding: 15px;
    margin: 20px 0;
    text-align: left;
}

.password-requirements h5 {
    color: #1e40af;
    font-size: 13px;
    margin-bottom: 10px;
}

.password-requirements ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.password-requirements ul li {
    font-size: 12px;
    color: #1e40af;
    margin: 5px 0;
    padding-left: 20px;
    position: relative;
}

.password-requirements ul li::before {
    content: '✓';
    position: absolute;
    left: 0;
    color: #059669;
    font-weight: bold;
}

@media screen and (max-width: 480px) {
    .reset-container {
        padding: 30px 25px;
    }
    
    .reset-container h1 {
        font-size: 28px;
    }
    
    .reset-container h4 {
        font-size: 13px;
    }
    
    .input-box input {
        padding: 12px 40px 12px 15px;
        font-size: 14px;
    }
    
    .btn {
        height: 42px;
        font-size: 15px;
    }
}
</style>

<body>
    <div class="reset-container">
        <?php if(!empty($token) && $token_valid): ?>
            <h1>Reset Password</h1>
            <h4>Enter your new password for<br><strong><?php echo htmlspecialchars($user_email); ?></strong></h4>
            
            <?php if(count($errors) > 0): ?>
                <div class="error-message">
                    <i class='bx bx-error-circle'></i>
                    <?php foreach($errors as $error): ?>
                        <?php echo htmlspecialchars($error); ?><br>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <form action="" method="POST">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                
                <div class="input-box">
                    <input type="password" name="password" placeholder="New Password" required>
                    <i class='bx bxs-lock-alt'></i>
                </div>
                
                <div class="input-box">
                    <input type="password" name="cpassword" placeholder="Confirm New Password" required>
                    <i class='bx bxs-lock-alt'></i>
                </div>
                
                <div class="password-requirements">
                    <h5>Password Requirements:</h5>
                    <ul>
                        <li>At least 8 characters long</li>
                        <li>Both passwords must match</li>
                    </ul>
                </div>
                
                <button type="submit" name="reset-password" class="btn">Reset Password</button>
            </form>
            
        <?php else: ?>
            <h1>Invalid Link</h1>
            <h4>This password reset link is invalid or has expired.</h4>
            
            <div class="error-message">
                <i class='bx bx-error-circle'></i>
                <?php 
                if(isset($errors['token'])) {
                    echo htmlspecialchars($errors['token']);
                } else {
                    echo 'Please request a new password reset link.';
                }
                ?>
            </div>
            
            <a href="auth.php" class="btn" style="display: inline-block; text-decoration: none; line-height: 45px;">Back to Login</a>
        <?php endif; ?>
        
        <div class="back-link">
            <a href="auth.php"><i class='bx bx-arrow-back'></i> Back to Login</a>
        </div>
    </div>
</body>
</html>