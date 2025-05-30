<?php
// Include database connection
require_once 'connect_db.php';

// Function to format date
function formatDate($dateString) {
    $date = new DateTime($dateString);
    return $date->format('F j, Y');
}

// Process form actions if submitted (accept/decline)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action']) && isset($_POST['ApplicationID'])) {
        $action = $_POST['action'];
        $id = $_POST['ApplicationID'];
        
        if ($action == 'accept') {
            // Get all the data from the application before we delete it
            $getAppSql = "SELECT firstname, middlename, lastname, gender, birthdate, house_no, 
                          street, barangay_or_village, municipality, city, email, contact_no 
                          FROM membership_application WHERE ApplicationID = ?";
            $getAppStmt = $con->prepare($getAppSql);
            $getAppStmt->bind_param("i", $id);
            $getAppStmt->execute();
            $result = $getAppStmt->get_result();
            
            if ($row = $result->fetch_assoc()) {
                // Insert into members table
                $insertSql = "INSERT INTO member (firstname, middlename, lastname, gender, birthdate, 
                              house_no, street, barangay_or_village, municipality, city, email, contact_no) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $insertStmt = $con->prepare($insertSql);
                $insertStmt->bind_param("ssssssssssss", 
                                     $row['firstname'], 
                                     $row['middlename'], 
                                     $row['lastname'], 
                                     $row['gender'], 
                                     $row['birthdate'], 
                                     $row['house_no'], 
                                     $row['street'], 
                                     $row['barangay_or_village'], 
                                     $row['municipality'], 
                                     $row['city'], 
                                     $row['email'], 
                                     $row['contact_no']);
                
                // If successful, delete from applications table
                if ($insertStmt->execute()) {
                    $deleteSql = "DELETE FROM membership_application WHERE ApplicationID = ?";
                    $deleteStmt = $con->prepare($deleteSql);
                    $deleteStmt->bind_param("i", $id);
                    $deleteStmt->execute();
                    $deleteStmt->close();
                }
                $insertStmt->close();
            }
            $getAppStmt->close();
        } else {
            // For decline, just update the status
            $sql = "UPDATE membership_application SET status = 'Declined' WHERE ApplicationID = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
        }
    }
}

// Fetch all applications from the database
$sql = "SELECT ApplicationID, firstname, middlename, lastname, gender, birthdate, house_no, street, barangay_or_village, municipality, city, email, contact_no, status, applied_at FROM membership_application ORDER BY applied_at DESC";
$result = $con->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membership Applications - Loan Management System</title>
    <link rel="stylesheet" href="members.css">
    <!-- Google Fonts: Roboto for professional typography -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <h1>Member Applications</h1>
                </div>
                <nav>
                    <button class="btn btn-back" onclick="location.href='Admin.php'">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </button>
                </nav>
            </div>
        </div>
    </header>

    <main class="container">
        <section class="card">
            <div class="card-header">
                <h2>Applicants</h2>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Gender</th>
                            <th>Birthdate</th>
                            <th>Address</th>
                            <th>Email</th>
                            <th>Contact No</th>
                            <th>Status</th>
                            <th>Applied At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $statusClass = strtolower($row["status"]);
                                
                                // Combine name fields
                                $fullName = htmlspecialchars($row["firstname"]);
                                if (!empty($row["middlename"])) {
                                    $fullName .= " " . htmlspecialchars($row["middlename"]);
                                }
                                $fullName .= " " . htmlspecialchars($row["lastname"]);
                                
                                // Combine address fields
                                $fullAddress = "";
                                if (!empty($row["house_no"])) {
                                    $fullAddress .= htmlspecialchars($row["house_no"]);
                                }
                                if (!empty($row["street"])) {
                                    if (!empty($fullAddress)) $fullAddress .= ", ";
                                    $fullAddress .= htmlspecialchars($row["street"]);
                                }
                                if (!empty($row["barangay_or_village"])) {
                                    if (!empty($fullAddress)) $fullAddress .= ", ";
                                    $fullAddress .= htmlspecialchars($row["barangay_or_village"]);
                                }
                                if (!empty($row["municipality"])) {
                                    if (!empty($fullAddress)) $fullAddress .= ", ";
                                    $fullAddress .= htmlspecialchars($row["municipality"]);
                                }
                                if (!empty($row["city"])) {
                                    if (!empty($fullAddress)) $fullAddress .= ", ";
                                    $fullAddress .= htmlspecialchars($row["city"]);
                                }
                                
                                echo "<tr>";
                                echo "<td>" . $fullName . "</td>";
                                echo "<td>" . htmlspecialchars($row["gender"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["birthdate"]) . "</td>";
                                echo "<td>" . $fullAddress . "</td>";
                                echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["contact_no"]) . "</td>";
                                echo "<td><span class='status-badge status-" . $statusClass . "'>" . htmlspecialchars($row["status"]) . "</span></td>";
                                echo "<td>" . formatDate($row["applied_at"]) . "</td>";
                                echo "<td class='action-buttons'>";
                                if (strtolower($row["status"]) == "pending") {
                                    echo "<form method='post' style='display:inline;'>";
                                    echo "<input type='hidden' name='ApplicationID' value='" . $row["ApplicationID"] . "'>";
                                    echo "<input type='hidden' name='action' value='accept'>";
                                    echo "<button type='submit' class='btn btn-accept' title='Accept Application'><i class='fas fa-check'></i> Accept</button>";
                                    echo "</form>";
                                    echo "<form method='post' style='display:inline;'>";
                                    echo "<input type='hidden' name='ApplicationID' value='" . $row["ApplicationID"] . "'>";
                                    echo "<input type='hidden' name='action' value='decline'>";
                                    echo "<button type='submit' class='btn btn-decline' title='Decline Application'><i class='fas fa-times'></i> Decline</button>";
                                    echo "</form>";
                                } else {
                                    echo "<span class='status-text'>" . htmlspecialchars($row["status"]) . "</span>";
                                }
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='9' class='no-data'>No applications found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <!-- Toast Container for notifications -->
    <div id="toastContainer" class="toast-container"></div>

    <script>
        // Toast notification function
        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            
            const icon = document.createElement('span');
            icon.className = 'toast-icon';
            if (type === 'error') {
                icon.innerHTML = '<i class="fas fa-exclamation-circle"></i>';
            } else if (type === 'success') {
                icon.innerHTML = '<i class="fas fa-check-circle"></i>';
            } else {
                icon.innerHTML = '<i class="fas fa-info-circle"></i>';
            }
            
            const messageSpan = document.createElement('span');
            messageSpan.className = 'toast-message';
            messageSpan.textContent = message;
            
            toast.appendChild(icon);
            toast.appendChild(messageSpan);
            
            const container = document.getElementById('toastContainer');
            container.appendChild(toast);
            
            setTimeout(() => {
                toast.classList.add('toast-fadeout');
                setTimeout(() => {
                    container.removeChild(toast);
                }, 300);
            }, 3000);
        }

        // Show toast on page load if action was performed
        <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('<?php echo $action == 'accept' ? 'Membership application accepted successfully' : 'Membership application declined successfully'; ?>', 'success');
            });
        <?php endif; ?>
    </script>
</body>
</html>

<?php
// Close the database connection
$con->close();
?>