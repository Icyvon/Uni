<?php
// Include database connection and PHPMailer
require_once 'connect_db.php';
require_once 'PHPMailer-master/src/PHPMailer.php'; // Adjust path if PHPMailer folder is elsewhere
require_once 'PHPMailer-master/src/SMTP.php';
require_once 'PHPMailer-master/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Function to generate OTP
function generateOTP($length = 6) {
    return str_pad(rand(0, pow(10, $length)-1), $length, '0', STR_PAD_LEFT);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle OTP verification
    if (isset($_POST['verify_otp'])) {
        $email = $_POST['email'];
        $entered_otp = $_POST['otp'];
        
        // Check OTP in session
        session_start();
        if (isset($_SESSION['otp']) && $_SESSION['otp'] == $entered_otp && $_SESSION['email'] == $email) {
            // OTP verified, proceed with registration
            $data = $_SESSION['form_data'];
            $password = password_hash($data['password'], PASSWORD_DEFAULT); // Hash password
            $status = 'pending';
            
            $sql = "INSERT INTO membership_application 
                   (Firstname, Middlename, Lastname, Gender, Birthdate, 
                    House_No, Street, Barangay_or_Village, Municipality, City, 
                    Email, Contact_No, Password, Status) 
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $con->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("ssssssssssssss",
                    $data['firstname'], $data['middlename'], $data['lastname'], 
                    $data['gender'], $data['birthdate'], $data['house_no'],
                    $data['street'], $data['barangay_or_village'], $data['municipality'],
                    $data['city'], $data['email'], $data['contact_no'], 
                    $password, $status);
                
                if ($stmt->execute()) {
                    // Clear session data
                    unset($_SESSION['otp']);
                    unset($_SESSION['form_data']);
                    unset($_SESSION['email']);
                    
                    echo json_encode(['success' => true, 'message' => 'Registration successful! Your application is pending approval.']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
                }
                $stmt->close();
            } else {
                echo json_encode(['success' => false, 'message' => 'Error: ' . $con->error]);
            }
            $con->close();
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid OTP']);
        }
        exit;
    }
    
    // Handle registration form submission
    $firstname = mysqli_real_escape_string($con, $_POST['firstname']);
    $middlename = mysqli_real_escape_string($con, $_POST['middlename']);
    $lastname = mysqli_real_escape_string($con, $_POST['lastname']);
    $gender = mysqli_real_escape_string($con, $_POST['gender']);
    $birthdate = mysqli_real_escape_string($con, $_POST['birthdate']);
    $house_no = mysqli_real_escape_string($con, $_POST['house_no']);
    $street = mysqli_real_escape_string($con, $_POST['street']);
    $barangay_or_village = mysqli_real_escape_string($con, $_POST['barangay_or_village']);
    $municipality = mysqli_real_escape_string($con, $_POST['municipality']);
    $city = mysqli_real_escape_string($con, $_POST['city']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $contact_no = mysqli_real_escape_string($con, $_POST['contact_no']);
    $password = $_POST['password'];
    
    // Store form data in session
    session_start();
    $_SESSION['form_data'] = [
        'firstname' => $firstname,
        'middlename' => $middlename,
        'lastname' => $lastname,
        'gender' => $gender,
        'birthdate' => $birthdate,
        'house_no' => $house_no,
        'street' => $street,
        'barangay_or_village' => $barangay_or_village,
        'municipality' => $municipality,
        'city' => $city,
        'email' => $email,
        'contact_no' => $contact_no,
        'password' => $password
    ];
    
    // Generate and store OTP
    $otp = generateOTP();
    $_SESSION['otp'] = $otp;
    $_SESSION['email'] = $email;
    
    // Send OTP via email
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Update with your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'rainepangilinan11@gmail.com'; // Update with your email
        $mail->Password = 'vhjqwhuppuegjzrk'; // Update with your app-specific password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        
        // Recipients
        $mail->setFrom('rainepangilinan11@gmail.com', 'Pay360 Cooperative');
        $mail->addAddress($email);
        
        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Pay360 OTP Verification - Welcome, ' . htmlspecialchars($firstname) . '!';
        
        // Custom HTML email body
        $mail->Body = '
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        background-color: #f4f4f4;
                        color: #333;
                        margin: 0;
                        padding: 0;
                    }
                    .container {
                        max-width: 600px;
                        margin: 20px auto;
                        background-color: #ffffff;
                        border-radius: 8px;
                        overflow: hidden;
                        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
                    }
                    .header {
                        background-color: #28a745;
                        color: white;
                        text-align: center;
                        padding: 20px;
                    }
                    .header img {
                        max-width: 150px;
                    }
                    .content {
                        padding: 20px;
                        text-align: center;
                    }
                    .otp-box {
                        background-color: #e9f7ef;
                        padding: 15px;
                        border-radius: 5px;
                        display: inline-block;
                        font-size: 24px;
                        font-weight: bold;
                        color: #155724;
                        margin: 20px 0;
                    }
                    .footer {
                        background-color: #f8f9fa;
                        text-align: center;
                        padding: 10px;
                        font-size: 12px;
                        color: #6c757d;
                    }
                    a {
                        color: #28a745;
                        text-decoration: none;
                    }
                    a:hover {
                        text-decoration: underline;
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="header">
                        <img src="logo.png" alt="Pay360 Logo">
                        <h2>Welcome to Pay360 Cooperative, ' . htmlspecialchars($firstname) . '!</h2>
                    </div>
                    <div class="content">
                        <p>Thank you for registering with Pay360 Cooperative. To complete your registration, please use the One-Time Password (OTP) below to verify your email address.</p>
                        <div class="otp-box">' . $otp . '</div>
                        <p>This OTP is valid for the next 10 minutes. If you didn’t request this, please contact our support team at <a href="mailto:support@pay360coop.com">rainepangilinan11@gmail.com</a>.</p>
                        <p>Click <a href="otp_verify.php?email=' . urlencode($email) . '">here</a> to verify your OTP online, or enter it on the verification page.</p>
                    </div>
                    <div class="footer">
                        <p>&copy; ' . date('Y') . ' Pay360 Cooperative. All rights reserved.</p>
                    </div>
                </div>
            </body>
            </html>
        ';

        $mail->send();
        echo json_encode(['success' => true, 'message' => 'OTP sent to your email']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Failed to send OTP: ' . $mail->ErrorInfo]);
    }
    
    $con->close();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registration</title>
  <link rel="stylesheet" href="login.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style> 
  .form-control {
    width: 100%;
    padding: 10px 12px;
    font-size: 1rem;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
    box-sizing: border-box;
    transition: border-color 0.3s ease;
  }
  .form-control:focus {
    border-color: #66FF66;
    outline: none;
    box-shadow: 0 0 5px rgba(102, 255, 102, 0.5);
  }
  .form-row {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
  }
  .form-row .form-group {
    flex: 1 1 0;
    min-width: 110px;
  }
  .alert {
    padding: 10px;
    margin-bottom: 15px;
    border-radius: 4px;
    display: none;
  }
  .alert-success {
    background-color: #d4edda;
    border-color: #c3e6cb;
    color: #155724;
  }
  .alert-danger {
    background-color: #f8d7da;
    border-color: #f5c6cb;
    color: #721c24;
  }
  </style>
</head>
<body>
  <div class="container">
    <button class="back-button" onclick="window.location.href = 'Home.php'">
      <i class="fas fa-arrow-left"></i> Back
    </button>

    <div class="auth-card">
      <div class="card-header">
        <h1>Create Account</h1>
        <p>Be one of Pay360 cooperative member!</p>
      </div>

      <div class="card-tabs">
        <div class="tab">Login</div>
        <div class="tab active">Register</div>
      </div>

      <div class="card-content">
          <div id="alertBox" class="alert"></div>
          
          <form id="registerForm" class="register-form">
            <div class="form-row">
              <div class="form-group">
                  <label for="firstname">Firstname</label>
                  <input type="text" id="firstname" name="firstname" class="form-control" placeholder="River" required>
              </div>

              <div class="form-group">
                  <label for="middlename">Middlename</label>
                  <input type="text" id="middlename" name="middlename" class="form-control" placeholder="Quiros" required>
              </div>

              <div class="form-group">
                  <label for="lastname">Lastname</label>
                  <input type="text" id="lastname" name="lastname" class="form-control" placeholder="Joseph" required>
              </div>
            </div>

            <div class="form-group">
            <label for="gender">Gender</label>
            <select id="gender" name="gender" class="form-control" required>
                <option value="" disabled selected>Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
            </div>

            <div class="form-group">
                <label for="birthdate">Birthdate</label>
                <input type="date" id="birthdate" name="birthdate" class="form-control" required>
            </div>

            <div class="form-row">
              <div class="form-group">
                  <label for="house_no">House No.</label>
                  <input type="number" id="house_no" name="house_no" class="form-control" placeholder="123" required>
              </div>

              <div class="form-group">
                  <label for="street">Street</label>
                  <input type="text" id="street" name="street" class="form-control" placeholder="Main St" required>
              </div>

              <div class="form-group">
                  <label for="barangay_or_village">Barangay/Village</label>
                  <input type="text" id="barangay_or_village" name="barangay_or_village" class="form-control" placeholder="Barangay 1" required>
              </div>

              <div class="form-group">
                  <label for="municipality">Municipality</label>
                  <input type="text" id="municipality" name="municipality" class="form-control" placeholder="Municipality Name" required>
              </div>

              <div class="form-group">
                  <label for="city">City</label>
                  <input type="text" id="city" name="city" class="form-control" placeholder="City Name" required>
              </div>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="your@email.com" required>
            </div>
            
            <div class="form-group">
                <label for="contact_no">Contact Number</label>
                <input type="text" id="contact_no" name="contact_no" class="form-control" placeholder="09123456789" required>
            </div>

            <div class="form-group">
              <label for="password">Password</label>
              <div class="password-input-container">
                <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
                <button type="button" class="toggle-password">
                  <i class="fas fa-eye"></i>
                </button>
              </div>
            </div>

            <button type="submit" class="submit-button blue-button">Register</button>

            <div class="form-footer">
              <span>Already have an account?</span>
              <a href="login.php" class="login-link">Log in</a>
            </div>
          </form>

          <!-- OTP Verification Form -->
          <form id="otpForm" class="otp-form" style="display: none;">
            <div class="form-group">
              <label for="otp">Enter OTP</label>
              <input type="text" id="otp" name="otp" class="form-control" placeholder="Enter 6-digit OTP" required>
              <input type="hidden" name="email" value="">
            </div>
            <button type="submit" class="submit-button blue-button">Verify OTP</button>
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

    // Tab switching
    document.querySelectorAll('.tab').forEach(tab => {
      tab.addEventListener('click', function() {
        if (this.textContent === 'Login') {
          window.location.href = 'Login.php';
        }
      });
    });

    // Form submission with AJAX
    document.getElementById('registerForm').addEventListener('submit', function(e) {
      e.preventDefault();
      
      const formData = new FormData(this);
      const alertBox = document.getElementById('alertBox');
      
      fetch('Register.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        alertBox.style.display = 'block';
        if (data.success) {
          alertBox.className = 'alert alert-success';
          alertBox.textContent = data.message;
          
          // Show OTP form
          document.getElementById('registerForm').style.display = 'none';
          document.getElementById('otpForm').style.display = 'block';
          document.querySelector('#otpForm input[name="email"]').value = document.getElementById('email').value;
        } else {
          alertBox.className = 'alert alert-danger';
          alertBox.textContent = data.message;
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alertBox.style.display = 'block';
        alertBox.className = 'alert alert-danger';
        alertBox.textContent = 'An error occurred. Please try again.';
      });
    });

    // OTP form submission
    document.getElementById('otpForm').addEventListener('submit', function(e) {
      e.preventDefault();
      
      const formData = new FormData(this);
      formData.append('verify_otp', true);
      const alertBox = document.getElementById('alertBox');
      
      fetch('Register.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        alertBox.style.display = 'block';
        if (data.success) {
          alertBox.className = 'alert alert-success';
          alertBox.textContent = data.message;
          // Redirect to home page
          localStorage.setItem('pay360_notification', 'Your membership application has been submitted and is pending approval.');
          setTimeout(() => {
            window.location.href = 'Home.php';
          }, 1000);
        } else {
          alertBox.className = 'alert alert-danger';
          alertBox.textContent = data.message;
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alertBox.style.display = 'block';
        alertBox.className = 'alert alert-danger';
        alertBox.textContent = 'An error occurred. Please try again.';
      });
    });
  </script>
</body>
</html>