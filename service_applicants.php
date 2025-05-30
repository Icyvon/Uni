<?php
require_once 'connect_db.php';

// Fetch summary counts
try {
    $result = $con->query("SELECT COUNT(*) FROM loan_application");
    $total_applications = $result->fetch_row()[0];

    $result = $con->query("SELECT COUNT(*) FROM loan_application WHERE status = 'Pending'");
    $pending_applications = $result->fetch_row()[0];

    $result = $con->query("SELECT COUNT(*) FROM loan_application WHERE status = 'Approved'");
    $approved_applications = $result->fetch_row()[0];

    $result = $con->query("SELECT COUNT(*) FROM loan_application WHERE status = 'Rejected'");
    $rejected_applications = $result->fetch_row()[0];

    // Fetch application data based on filter
    $filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
    $query = "SELECT Loan_AppID, MemberID, Firstname, Middlename, Lastname, Purpose, Amount, Interest, Total_Amount, Monthly_Amortization, Period, Status, Requested_At FROM loan_application";
    if ($filter !== 'all') {
        $query .= " WHERE status = ?";
    }
    $stmt = $con->prepare($query);
    if ($filter !== 'all') {
        $status = ucfirst($filter);
        $stmt->bind_param('s', $status);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $applications = $result->fetch_all(MYSQLI_ASSOC);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    $applications = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Applications</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1400px;
            margin: 0 auto;
        }
        .back-button {
            display: inline-block;
            padding: 12px 24px;
            background: linear-gradient(135deg, #28a745, #34c759);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 24px;
            font-weight: 500;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .back-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
            background: linear-gradient(135deg, #218838, #2ba847);
        }
        .summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 24px;
        }
        .summary-card {
            background: white;
            padding: 24px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            border-left: 4px solid #28a745;
        }
        .summary-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        }
        .summary-card h3 {
            margin: 0 0 10px;
            color: #1e7e34;
            font-size: 1.2rem;
        }
        .summary-card p {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 600;
            color: #333;
        }
        .tabs {
            margin-bottom: 24px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .tabs a {
            padding: 10px 20px;
            text-decoration: none;
            color: #28a745;
            background-color: white;
            border-radius: 8px;
            font-weight: 500;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: background-color 0.2s ease, color 0.2s ease, transform 0.2s ease;
        }
        .tabs a:hover {
            background: linear-gradient(135deg, #28a745, #34c759);
            color: white;
            transform: translateY(-2px);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }
        th {
            background: linear-gradient(135deg, #d4edda, #c3e6cb);
            color: #1e7e34;
            font-weight: 600;
        }
        tr:nth-child(even) {
            background-color: #f8fdf9;
        }
        tr:hover {
            background-color: #e9f7ef;
        }
        td a {
            color: #28a745;
            text-decoration: none;
            font-weight: 500;
        }
        td a:hover {
            color: #218838;
            text-decoration: underline;
        }
        h2 {
            color: #1e7e34;
            font-weight: 600;
            margin-bottom: 16px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Back Button -->
        <a href="Admin.php" class="back-button">Back to Dashboard</a>

        <!-- Summary Cards -->
        <div class="summary">
            <div class="summary-card">
                
                <p><?php echo $total_applications; ?> Applications</p>
            </div>
            <div class="summary-card">
                <p><?php echo $pending_applications; ?> Pending</p>
            </div>
            <div class="summary-card">
                <p><?php echo $approved_applications; ?> Approved</p>
            </div>
            <div class="summary-card">
                <p><?php echo $rejected_applications; ?> Rejected/Declined</p>
            </div>
        </div>

        <!-- Tabs -->
        <div class="tabs">
            <a href="?filter=all">All Applications</a>
            <a href="?filter=pending">Pending</a>
            <a href="?filter=approved">Approved</a>
            <a href="?filter=rejected">Rejected/Declined</a>
        </div>

        <!-- Applications Table -->
        <h2>Loan Applications</h2>
        <table>
            <thead>
                <tr>
                    <th>Loan App ID</th>
                    <th>Member ID</th>
                    <th>Applicant Name</th>
                    <th>Purpose</th>
                    <th>Amount</th>
                    <th>Interest</th>
                    <th>Total Amount</th>
                    <th>Monthly Amortization</th>
                    <th>Period</th>
                    <th>Status</th>
                    <th>Requested At</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($applications)): ?>
                    <tr>
                        <td colspan="12">No applications found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($applications as $app): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($app['Loan_AppID']); ?></td>
                            <td><?php echo htmlspecialchars($app['MemberID']); ?></td>
                            <td><?php echo htmlspecialchars(trim($app['Firstname'] . ' ' . ($app['Middlename'] ? $app['Middlename'] . ' ' : '') . $app['Lastname'])); ?></td>
                            <td><?php echo htmlspecialchars($app['Purpose']); ?></td>
                            <td>$<?php echo number_format($app['Amount'], 2); ?></td>
                            <td><?php echo number_format($app['Interest'], 2); ?>%</td>
                            <td>$<?php echo number_format($app['Total_Amount'], 2); ?></td>
                            <td>$<?php echo number_format($app['Monthly_Amortization'], 2); ?></td>
                            <td><?php echo htmlspecialchars($app['Period']); ?></td>
                            <td><?php echo htmlspecialchars($app['Status']); ?></td>
                            <td><?php echo htmlspecialchars($app['Requested_At']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>