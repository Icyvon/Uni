<?php
session_start();

require_once 'connect_db.php';

// Initialize error variables
$emailError = "";
$passwordError = "";
$generalError = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Match form field names with the actual input names in the HTML form
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        if (empty($email)) {
            $emailError = 'Email is required.';
        }
        if (empty($password)) {
            $passwordError = 'Password is required.';
        }
    } else {
        // 1. Check if user is an admin
        $stmt = $con->prepare("SELECT AdminID, Email, Password FROM admin WHERE Email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $adminResult = $stmt->get_result();

        if ($adminResult->num_rows > 0) {
            $admin = $adminResult->fetch_assoc();

            // Check password (assuming plain text for now, as per current code)
            if ($password === $admin['Password']) {
                // Admin login successful
                $_SESSION['AdminID'] = $admin['AdminID'];
                $_SESSION['Email'] = $admin['Email'];
                $_SESSION['Role'] = 'admin';
                $_SESSION['loggedin'] = true;
                header('Location: Admin.php');
                exit();
            } else {
                $passwordError = 'Incorrect password.';
            }
        } else {
            // 2. Check if user is a member
            $stmt = $con->prepare("SELECT MemberID, Email, Password FROM member WHERE Email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $memberResult = $stmt->get_result();

            if ($memberResult->num_rows > 0) {
                $member = $memberResult->fetch_assoc();

                // Check password (assuming plain text for now, as per current code)
                if ($password === $member['Password']) {
                    // Member login successful
                    $_SESSION['MemberID'] = $member['MemberID'];
                    $_SESSION['Email'] = $member['Email'];
                    $_SESSION['Role'] = 'member';
                    $_SESSION['loggedin'] = true;
                    header('Location: MemberDashboard.php');
                    exit();
                } else {
                    $passwordError = 'Incorrect password.';
                }
            } else {
                $emailError = 'Email not found.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container">
        <button class="back-button" onclick="window.location.href = 'Home.php'">
            <i class="fas fa-arrow-left"></i> Back
        </button>

        <div class="auth-card">
            <div class="card-header">
                <h1>Welcome Back</h1>
                <p>Log in to your account</p>
            </div>

            <div class="card-tabs">
                <div class="tab active">Login</div>
                <div class="tab" onclick="window.location.href='Register.php'">Register</div>
            </div>

            <div class="card-content">
                <form class="login-form" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return validateForm()">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="your@email.com" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                        <div class="error-message" id="emailError"><?php echo $emailError; ?></div>
                    </div>

                    <div class="form-group">
                        <div class="password-label-row">
                            <label for="password">Password</label>
                            <a href="#" class="forgot-password">Forgot Password?</a>
                        </div>
                        <div class="password-input-container">
                            <input type="password" id="password" name="password" placeholder="••••••••" required>
                            <button type="button" class="toggle-password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="error-message" id="passwordError"><?php echo $passwordError; ?></div>
                    </div>

                    <button type="submit" class="submit-button green-button">Log In</button>

                    <div class="form-footer">
                        <span>Don't have an account?</span>
                        <a href="Register.php" class="register-link">Register as a member</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Toggle password visibility
        document.querySelector('.toggle-password').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });

        // Client-side form validation
        function validateForm() {
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const emailError = document.getElementById('emailError');
            const passwordError = document.getElementById('passwordError');
            let isValid = true;

            // Reset error messages
            emailError.textContent = '';
            passwordError.textContent = '';

            // Validate email format
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!email) {
                emailError.textContent = 'Email is required.';
                isValid = false;
            } else if (!emailRegex.test(email)) {
                emailError.textContent = 'Please enter a valid email address.';
                isValid = false;
            }

            // Validate password presence
            if (!password) {
                passwordError.textContent = 'Password is required.';
                isValid = false;
            }

            return isValid;
        }

        document.querySelectorAll('.tab').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                
                const header = document.querySelector('.card-header h1');
                const subheader = document.querySelector('.card-header p');
                
                if (this.textContent === 'Login') {
                    header.textContent = 'Welcome Back';
                    subheader.textContent = 'Log in to your account';
                } else {
                    header.textContent = 'Create Account';
                    subheader.textContent = 'Register as a new member';
                }
            });
        });
    </script>
</body>
</html>