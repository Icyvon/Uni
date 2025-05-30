<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User | Pay360 Management System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #e8f5e8 0%, #f0f9f0 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .form-container {
            max-width: 800px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(46, 125, 50, 0.1);
            overflow: hidden;
        }
        
        .form-header {
            background: linear-gradient(135deg, #4caf50 0%, #2e7d32 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .form-header h2 {
            font-size: 28px;
            font-weight: 300;
            margin: 0;
        }
        
        .form-content {
            padding: 40px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #2e7d32;
            font-size: 14px;
        }
        
        .form-group input, 
        .form-group select {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e8f5e8;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: #fafafa;
        }
        
        .form-group input:focus, 
        .form-group select:focus {
            outline: none;
            border-color: #4caf50;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1);
        }
        
        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }
        
        .message {
            padding: 16px;
            margin-bottom: 25px;
            border-radius: 8px;
            font-weight: 500;
        }
        
        .success {
            background: #e8f5e8;
            color: #2e7d32;
            border: 1px solid #4caf50;
        }
        
        .error {
            background: #ffebee;
            color: #c62828;
            border: 1px solid #ef5350;
        }
        
        .hidden {
            display: none;
        }
        
        .button-group {
            display: flex;
            gap: 15px;
            margin-top: 40px;
            justify-content: center;
        }
        
        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-submit {
            background: linear-gradient(135deg, #4caf50 0%, #2e7d32 100%);
            color: white;
        }
        
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(76, 175, 80, 0.3);
        }
        
        .btn-back {
            background: #ffffff;
            color: #4caf50;
            border: 2px solid #4caf50;
        }
        
        .btn-back:hover {
            background: #4caf50;
            color: white;
            transform: translateY(-2px);
        }
        
        .section-title {
            color: #2e7d32;
            font-size: 18px;
            font-weight: 600;
            margin: 30px 0 20px 0;
            padding-bottom: 10px;
            border-bottom: 2px solid #e8f5e8;
        }
        
        @media (max-width: 768px) {
            .form-content {
                padding: 20px;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .button-group {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <?php
    $host = 'sql313.infinityfree.com';
    $dbname = 'if0_38993274_pay360';
    $username = 'if0_38993274';
    $password = 'ZfkENfWP5Jh';
    
    $errors = [];
    $success = '';
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $user_type = $_POST['user_type'] ?? '';
            $firstname = trim($_POST['firstname'] ?? '');
            $middlename = trim($_POST['middlename'] ?? '');
            $lastname = trim($_POST['lastname'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password_input = $_POST['password'] ?? '';
            $contact_no = trim($_POST['contact_no'] ?? '');

            // Common validations
            if (empty($firstname)) $errors[] = "First name is required.";
            if (empty($lastname)) $errors[] = "Last name is required.";
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required.";
            if (empty($password_input) || strlen($password_input) < 8) $errors[] = "Password must be at least 8 characters.";
            if (empty($contact_no) || !preg_match('/^[0-9]{10,11}$/', $contact_no)) $errors[] = "Valid contact number (10-11 digits) is required.";

            // Check for unique email
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM member WHERE Email = ? UNION SELECT COUNT(*) FROM admin WHERE Email = ?");
            $stmt->execute([$email, $email]);
            $email_counts = $stmt->fetchAll(PDO::FETCH_COLUMN);
            if (array_sum($email_counts) > 0) {
                $errors[] = "Email is already in use.";
            }

            if ($user_type === 'member') {
                $gender = $_POST['gender'] ?? '';
                $birthdate = $_POST['birthdate'] ?? '';
                $house_no = trim($_POST['house_no'] ?? '');
                $street = trim($_POST['street'] ?? '');
                $barangay = trim($_POST['barangay'] ?? '');
                $municipality = trim($_POST['municipality'] ?? '');
                $city = trim($_POST['city'] ?? '');

                // Member-specific validations
                if (empty($gender)) $errors[] = "Gender is required.";
                if (empty($birthdate)) $errors[] = "Birthdate is required.";
                if (empty($house_no) || !is_numeric($house_no)) $errors[] = "Valid house number is required.";
                if (empty($street)) $errors[] = "Street is required.";
                if (empty($barangay)) $errors[] = "Barangay is required.";
                if (empty($municipality)) $errors[] = "Municipality is required.";
                if (empty($city)) $errors[] = "City is required.";

                if (empty($errors)) {
                    $hashed_password = password_hash($password_input, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("
                        INSERT INTO member (Firstname, Middlename, Lastname, Gender, Birthdate, House_No, Street, Barangay_or_Village, Municipality, City, Email, Password, Contact_No)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                    ");
                    $stmt->execute([$firstname, $middlename, $lastname, $gender, $birthdate, $house_no, $street, $barangay, $municipality, $city, $email, $hashed_password, $contact_no]);
                    $success = "Member added successfully.";
                }
            } elseif ($user_type === 'admin') {
                $role = $_POST['role'] ?? '';

                // Admin-specific validations
                if (empty($role)) $errors[] = "Role is required.";

                if (empty($errors)) {
                    $hashed_password = password_hash($password_input, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("
                        INSERT INTO admin (Firstname, Middlename, Lastname, Email, Password, Contact_No, Role)
                        VALUES (?, ?, ?, ?, ?, ?, ?)
                    ");
                    $stmt->execute([$firstname, $middlename, $lastname, $email, $hashed_password, $contact_no, $role]);
                    $success = "Admin/Staff added successfully.";
                }
            } else {
                $errors[] = "Invalid user type selected.";
            }
        } catch (PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
    ?>

    <div class="form-container">
        <div class="form-header">
            <h2>Add New User</h2>
        </div>
        
        <div class="form-content">
            <?php if ($success): ?>
                <div class="message success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            <?php if ($errors): ?>
                <div class="message error">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="user_type">User Type</label>
                    <select id="user_type" name="user_type" onchange="toggleFields()" required>
                        <option value="">Select User Type</option>
                        <option value="member" <?php echo (isset($_POST['user_type']) && $_POST['user_type'] === 'member') ? 'selected' : ''; ?>>Member</option>
                        <option value="admin" <?php echo (isset($_POST['user_type']) && $_POST['user_type'] === 'admin') ? 'selected' : ''; ?>>Admin/Staff</option>
                    </select>
                </div>
                
                <div class="section-title">Basic Information</div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="firstname">First Name</label>
                        <input type="text" id="firstname" name="firstname" value="<?php echo isset($_POST['firstname']) ? htmlspecialchars($_POST['firstname']) : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="middlename">Middle Name</label>
                        <input type="text" id="middlename" name="middlename" value="<?php echo isset($_POST['middlename']) ? htmlspecialchars($_POST['middlename']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="lastname">Last Name</label>
                        <input type="text" id="lastname" name="lastname" value="<?php echo isset($_POST['lastname']) ? htmlspecialchars($_POST['lastname']) : ''; ?>" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="contact_no">Contact Number</label>
                        <input type="text" id="contact_no" name="contact_no" value="<?php echo isset($_POST['contact_no']) ? htmlspecialchars($_POST['contact_no']) : ''; ?>" required>
                    </div>
                </div>
                
                <!-- Member-specific fields -->
                <div class="member-field hidden">
                    <div class="section-title">Personal Details</div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="gender">Gender</label>
                            <select id="gender" name="gender">
                                <option value="">Select Gender</option>
                                <option value="Male" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'Male') ? 'selected' : ''; ?>>Male</option>
                                <option value="Female" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'Female') ? 'selected' : ''; ?>>Female</option>
                                <option value="Other" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'Other') ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="birthdate">Birthdate</label>
                            <input type="date" id="birthdate" name="birthdate" value="<?php echo isset($_POST['birthdate']) ? htmlspecialchars($_POST['birthdate']) : ''; ?>">
                        </div>
                    </div>
                    
                    <div class="section-title">Address Information</div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="house_no">House Number</label>
                            <input type="number" id="house_no" name="house_no" value="<?php echo isset($_POST['house_no']) ? htmlspecialchars($_POST['house_no']) : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="street">Street</label>
                            <input type="text" id="street" name="street" value="<?php echo isset($_POST['street']) ? htmlspecialchars($_POST['street']) : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="barangay">Barangay</label>
                            <input type="text" id="barangay" name="barangay" value="<?php echo isset($_POST['barangay']) ? htmlspecialchars($_POST['barangay']) : ''; ?>">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="municipality">Municipality</label>
                            <input type="text" id="municipality" name="municipality" value="<?php echo isset($_POST['municipality']) ? htmlspecialchars($_POST['municipality']) : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="city">City</label>
                            <input type="text" id="city" name="city" value="<?php echo isset($_POST['city']) ? htmlspecialchars($_POST['city']) : ''; ?>">
                        </div>
                    </div>
                </div>
                
                <!-- Admin-specific fields -->
                <div class="admin-field hidden">
                    <div class="section-title">Role Assignment</div>
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select id="role" name="role">
                            <option value="">Select Role</option>
                            <option value="admin" <?php echo (isset($_POST['role']) && $_POST['role'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
                            <option value="staff" <?php echo (isset($_POST['role']) && $_POST['role'] === 'staff') ? 'selected' : ''; ?>>Staff</option>
                        </select>
                    </div>
                </div>
                
                <div class="button-group">
                    <button type="button" class="btn btn-back" onclick="history.back()">Back</button>
                    <button type="submit" class="btn btn-submit">Add User</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleFields() {
            const userType = document.getElementById('user_type').value;
            const memberFields = document.querySelectorAll('.member-field');
            const adminFields = document.querySelectorAll('.admin-field');

            memberFields.forEach(field => {
                field.classList.toggle('hidden', userType !== 'member');
                const inputs = field.querySelectorAll('input, select');
                inputs.forEach(input => input.required = userType === 'member');
            });

            adminFields.forEach(field => {
                field.classList.toggle('hidden', userType !== 'admin');
                const input = field.querySelector('select');
                if (input) input.required = userType === 'admin';
            });
        }

        // Initialize field visibility on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleFields();
        });
        
        document.getElementById('user_type').addEventListener('change', toggleFields);
    </script>
</body>
</html>