<?php
// Include database connection
require_once 'connect_db.php';

// Check if ID parameter exists
if (!isset($_GET['id']) || empty($_GET['id'])) {
    // Redirect to members page if no ID provided
    header("Location: Members.php");
    exit;
}

$member_id = $_GET['id'];

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $gender = $_POST['gender'];
    $birthdate = $_POST['birthdate'];
    $house_no = $_POST['house_no'];
    $street = $_POST['street'];
    $barangay_or_village = $_POST['barangay_or_village'];
    $municipality = $_POST['municipality'];
    $city = $_POST['city'];
    $email = $_POST['email'];
    $contact_no = $_POST['contact_no'];
    
    // Update member record
    $sql = "UPDATE member SET 
            Firstname = ?, 
            Middlename = ?, 
            Lastname = ?, 
            Gender = ?, 
            Birthdate = ?, 
            House_no = ?, 
            Street = ?, 
            Barangay_or_Village = ?, 
            Municipality = ?, 
            City = ?, 
            Email = ?, 
            Contact_No = ? 
            WHERE MemberID = ?";
    
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ssssssssssssi", 
                    $firstname, 
                    $middlename, 
                    $lastname, 
                    $gender, 
                    $birthdate, 
                    $house_no, 
                    $street, 
                    $barangay_or_village, 
                    $municipality, 
                    $city, 
                    $email, 
                    $contact_no, 
                    $member_id);
    
    if ($stmt->execute()) {
        // Redirect to member view page after successful update
        header("Location: view_member.php?id=$member_id");
        exit;
    } else {
        $error_message = "Error updating record: " . $con->error;
    }
    
    $stmt->close();
}

// Fetch current member data
$sql = "SELECT * FROM member WHERE MemberID = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $member_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if member exists
if ($result->num_rows === 0) {
    // Member not found, redirect
    header("Location: Members.php");
    exit;
}

// Get member data
$member = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Member - <?php echo htmlspecialchars($member['Firstname'] . ' ' . $member['Lastname']); ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f8f0;
            color: #2c5530;
            line-height: 1.6;
            padding: 20px;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(44, 85, 48, 0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #2d5a31, #4a7c59);
            color: white;
            padding: 25px 30px;
            text-align: center;
        }

        .header h1 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .header p {
            opacity: 0.9;
            font-size: 14px;
        }

        .form-container {
            padding: 30px;
        }

        .error-message {
            background-color: #ffeaea;
            color: #d63384;
            padding: 12px 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #d63384;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: #2c5530;
            margin-bottom: 5px;
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e8e0;
            border-radius: 5px;
            font-size: 15px;
            background-color: white;
            color: #2c5530;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #4a7c59;
            background-color: #f9fdf9;
        }

        .form-control:required:invalid {
            border-color: #e0e8e0;
        }

        .form-control:required:valid {
            border-color: #4a7c59;
        }

        select.form-control {
            cursor: pointer;
        }

        .row {
            display: flex;
            gap: 15px;
        }

        .col {
            flex: 1;
        }

        .btn-group {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e0e8e0;
        }

        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            text-align: center;
        }

        .btn-primary {
            background-color: #4a7c59;
            color: white;
        }

        .btn-primary:hover {
            background-color: #3d6b4a;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background-color: #e0e8e0;
            color: #2c5530;
        }

        .btn-secondary:hover {
            background-color: #d0d8d0;
            transform: translateY(-1px);
        }

        .required {
            color: #d63384;
        }

        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

            .form-container {
                padding: 20px;
            }

            .row {
                flex-direction: column;
                gap: 0;
            }

            .btn-group {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Update Member</h1>
            <p>Edit member information</p>
        </div>

        <div class="form-container">
            <?php if (isset($error_message)): ?>
                <div class="error-message">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <form method="post" action="update_member.php?id=<?php echo $member_id; ?>">
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label class="form-label" for="firstname">First Name <span class="required">*</span></label>
                            <input type="text" id="firstname" name="firstname" class="form-control" 
                                   value="<?php echo htmlspecialchars($member['Firstname']); ?>" required>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label class="form-label" for="middlename">Middle Name</label>
                            <input type="text" id="middlename" name="middlename" class="form-control" 
                                   value="<?php echo htmlspecialchars($member['Middlename'] ?? ''); ?>">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="lastname">Last Name <span class="required">*</span></label>
                    <input type="text" id="lastname" name="lastname" class="form-control" 
                           value="<?php echo htmlspecialchars($member['Lastname']); ?>" required>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label class="form-label" for="gender">Gender <span class="required">*</span></label>
                            <select id="gender" name="gender" class="form-control" required>
                                <option value="">Select Gender</option>
                                <option value="Male" <?php if ($member['Gender'] == 'Male') echo 'selected'; ?>>Male</option>
                                <option value="Female" <?php if ($member['Gender'] == 'Female') echo 'selected'; ?>>Female</option>
                                <option value="Other" <?php if ($member['Gender'] == 'Other') echo 'selected'; ?>>Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label class="form-label" for="birthdate">Birthdate <span class="required">*</span></label>
                            <input type="date" id="birthdate" name="birthdate" class="form-control" 
                                   value="<?php echo htmlspecialchars($member['Birthdate']); ?>" required>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="house_no">House Number</label>
                    <input type="text" id="house_no" name="house_no" class="form-control" 
                           value="<?php echo htmlspecialchars($member['House_No'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label class="form-label" for="street">Street</label>
                    <input type="text" id="street" name="street" class="form-control" 
                           value="<?php echo htmlspecialchars($member['Street'] ?? ''); ?>">
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label class="form-label" for="barangay_or_village">Barangay/Village</label>
                            <input type="text" id="barangay_or_village" name="barangay_or_village" class="form-control" 
                                   value="<?php echo htmlspecialchars($member['Barangay_or_Village'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label class="form-label" for="municipality">Municipality</label>
                            <input type="text" id="municipality" name="municipality" class="form-control" 
                                   value="<?php echo htmlspecialchars($member['Municipality'] ?? ''); ?>">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="city">City</label>
                    <input type="text" id="city" name="city" class="form-control" 
                           value="<?php echo htmlspecialchars($member['City'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label class="form-label" for="email">Email Address <span class="required">*</span></label>
                    <input type="email" id="email" name="email" class="form-control" 
                           value="<?php echo htmlspecialchars($member['Email']); ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="contact_no">Contact Number <span class="required">*</span></label>
                    <input type="text" id="contact_no" name="contact_no" class="form-control" 
                           value="<?php echo htmlspecialchars($member['Contact_No']); ?>" required>
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">Update Member</button>
                    <a href="view_member.php?id=<?php echo $member_id; ?>" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

<?php
// Close the database connection
$stmt->close();
$con->close();
?>