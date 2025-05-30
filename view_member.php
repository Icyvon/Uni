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

// Fetch member details
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

// Function to format date
function formatDate($dateString) {
    $date = new DateTime($dateString);
    return $date->format('F j, Y');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Member - <?php echo htmlspecialchars($member['Firstname'] . ' ' . $member['Lastname']); ?></title>
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
            background-color: #f9fdf9;
            color: #2c5530;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #4a7c59;
        }

        .form-control[readonly] {
            background-color: #f5f9f5;
            cursor: default;
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
            <h1>Member Details</h1>
            <p>View member information</p>
        </div>

        <div class="form-container">
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label class="form-label">First Name</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($member['Firstname']); ?>" readonly>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label class="form-label">Middle Name</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($member['Middlename'] ?? ''); ?>" readonly>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Last Name</label>
                <input type="text" class="form-control" value="<?php echo htmlspecialchars($member['Lastname']); ?>" readonly>
            </div>

            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label class="form-label">Gender</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($member['Gender']); ?>" readonly>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label class="form-label">Birthdate</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($member['Birthdate']); ?>" readonly>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">House Number</label>
                <input type="text" class="form-control" value="<?php echo htmlspecialchars($member['House_No'] ?? ''); ?>" readonly>
            </div>

            <div class="form-group">
                <label class="form-label">Street</label>
                <input type="text" class="form-control" value="<?php echo htmlspecialchars($member['Street'] ?? ''); ?>" readonly>
            </div>

            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label class="form-label">Barangay/Village</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($member['Barangay_or_Village'] ?? ''); ?>" readonly>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label class="form-label">Municipality</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($member['Municipality'] ?? ''); ?>" readonly>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">City</label>
                <input type="text" class="form-control" value="<?php echo htmlspecialchars($member['City'] ?? ''); ?>" readonly>
            </div>

            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" class="form-control" value="<?php echo htmlspecialchars($member['Email']); ?>" readonly>
            </div>

            <div class="form-group">
                <label class="form-label">Contact Number</label>
                <input type="text" class="form-control" value="<?php echo htmlspecialchars($member['Contact_No']); ?>" readonly>
            </div>

            <div class="btn-group">
                <a href="update_member.php?id=<?php echo $member['MemberID']; ?>" class="btn btn-primary">Edit Member</a>
                <a href="Members.php" class="btn btn-secondary">Back to List</a>
            </div>
        </div>
    </div>
</body>
</html>

<?php
// Close the database connection
$stmt->close();
$con->close();
?>