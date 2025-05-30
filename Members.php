<?php
// Include database connection
require_once 'connect_db.php';

// Function to format date
function formatDate($dateString) {
    $date = new DateTime($dateString);
    return $date->format('F j, Y');
}

// Fetch all members from the database (from the member table)
$sql = "SELECT MemberID, firstname, middlename, lastname, gender, birthdate, house_no, street, barangay_or_village, municipality, city, email, password, contact_no FROM member ORDER BY lastname, firstname";
$result = $con->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Members</title>
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
                    <h1>PAY360 Coop Members</h1>
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
                <h2>Active Members</h2>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th class="col-name">Name</th>
                            <th class="col-gender">Gender</th>
                            <th class="col-birthdate">Birthdate</th>
                            <th class="col-address">Address</th>
                            <th class="col-email">Email</th>
                            <th class="col-contact">Contact No</th>
                            <th class="col-actions">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
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
                                echo "<td class='col-name'>" . $fullName . "</td>";
                                echo "<td class='col-gender'>" . htmlspecialchars($row["gender"]) . "</td>";
                                echo "<td class='col-birthdate'>" . htmlspecialchars($row["birthdate"]) . "</td>";
                                echo "<td class='col-address'>" . $fullAddress . "</td>";
                                echo "<td class='col-email'>" . htmlspecialchars($row["email"]) . "</td>";
                                echo "<td class='col-contact'>" . htmlspecialchars($row["contact_no"]) . "</td>";
                                echo "<td class='col-actions action-buttons'>";
                                echo "<a href='view_member.php?id=" . $row["MemberID"] . "' class='btn btn-view' title='View Member'><i class='fas fa-eye'></i> View</a>";
                                echo "<a href='update_member.php?id=" . $row["MemberID"] . "' class='btn btn-update' title='Update Member'><i class='fas fa-edit'></i> Update</a>";
                                echo "<button onclick='confirmDelete(" . $row["MemberID"] . ")' class='btn btn-delete' title='Delete Member'><i class='fas fa-trash'></i> Delete</button>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7' class='no-data'>No members found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <script>
        function confirmDelete(id) {
            if (confirm("Are you sure you want to delete this member?")) {
                window.location.href = "delete_member.php?id=" + id;
            }
        }
    </script>
</body>
</html>

<?php
// Close the database connection
$con->close();
?>