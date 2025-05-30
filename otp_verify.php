<?php
session_start();
require_once 'connect_db.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $otp = mysqli_real_escape_string($con, $_POST['otp']);

    // Verify OTP
    $sql = "SELECT * FROM pending_registrations WHERE email = ? AND otp = ? AND otp_expiry > NOW() AND status = 'pending'";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ss", $email, $otp);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Move data to membership_application
        $sql = "INSERT INTO membership_application 
                (Firstname, Middlename, Lastname, Gender, Birthdate, 
                 House_No, Street, Barangay_or_Village, Municipality, City, 
                 Email, Contact_No, Password, Status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $con->prepare($sql);
        $status = 'pending';
        $stmt->bind_param(
            "ssssssssssssss",
            $row['firstname'], $row['middlename'], $row['lastname'], $row['gender'], $row['birthdate'],
            $row['house_no'], $row['street'], $row['barangay_or_village'], $row['municipality'], $row['city'],
            $row['email'], $row['contact_no'], $row['password'], $status
        );

        if ($stmt->execute()) {
            // Delete from pending_registrations
            $sql = "DELETE FROM pending_registrations WHERE email = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();

            // Set success notification
            $_SESSION['notification'] = 'Registration successful! Your application is pending approval.';
            echo json_encode(['success' => true, 'message' => 'OTP verified! Registration complete.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error finalizing registration: ' . $stmt->error]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid or expired OTP.']);
    }

    $stmt->close();
    $con->close();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>OTP Verification</title>
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
    <button class="back-button" onclick="window.location.href = 'Register.php'">
      <i class="fas fa-arrow-left"></i> Back
    </button>

    <div class="auth-card">
      <div class="card-header">
        <h1>Verify OTP</h1>
        <p>Enter the OTP sent to your email</p>
      </div>

      <div class="card-content">
        <div id="alertBox" class="alert"></div>
        <form id="otpForm" class="otp-form">
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" class="form-control" placeholder="your@email.com" value="<?php echo isset($_SESSION['registration_email']) ? htmlspecialchars($_SESSION['registration_email']) : ''; ?>" required readonly>
          </div>
          <div class="form-group">
            <label for="otp">OTP Code</label>
            <input type="text" id="otp" name="otp" class="form-control" placeholder="Enter 6-digit OTP" required>
          </div>
          <button type="submit" class="submit-button blue-button">Verify OTP</button>
        </form>
      </div>
    </div>
  </div>

  <script>
    document.getElementById('otpForm').addEventListener('submit', function(e) {
      e.preventDefault();
      
      const formData = new FormData(this);
      
      fetch('otp_verify.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        const alertBox = document.getElementById('alertBox');
        alertBox.style.display = 'block';
        
        if (data.success) {
          alertBox.className = 'alert alert-success';
          alertBox.textContent = data.message;
          setTimeout(() => {
            window.location.href = 'Home.php';
          }, 2000);
        } else {
          alertBox.className = 'alert alert-danger';
          alertBox.textContent = data.message;
        }
      })
      .catch(error => {
        console.error('Error:', error);
        const alertBox = document.getElementById('alertBox');
        alertBox.style.display = 'block';
        alertBox.className = 'alert alert-danger';
        alertBox.textContent = 'An error occurred. Please try again.';
      });
    });
  </script>
</body>
</html>