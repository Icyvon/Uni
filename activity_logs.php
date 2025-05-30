<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions Log</title>
    <link rel="stylesheet" href="admin.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
        }
        .log-container {
            width: 100%;
            min-height: 100vh;
            padding: 20px;
            background: #fff;
            box-sizing: border-box;
        }
        .log-container h2 {
            margin-bottom: 20px;
            text-align: center;
        }
        .filter-group {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: center;
        }
        .filter-group select {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        .table-container {
            width: 100%;
            overflow-x: auto;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        table {
            width: 100%;
            min-width: 800px;
            border-collapse: collapse;
            background: white;
        }
        th, td {
            padding: 12px 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            white-space: nowrap;
        }
        th {
            background: #f8f9fa;
            font-weight: bold;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        tr:hover {
            background: #f1f3f5;
        }
        
        /* Responsive column widths */
        th:nth-child(1), td:nth-child(1) { width: 10%; } /* Transaction ID */
        th:nth-child(2), td:nth-child(2) { width: 12%; } /* Type */
        th:nth-child(3), td:nth-child(3) { width: 18%; } /* Member */
        th:nth-child(4), td:nth-child(4) { width: 12%; } /* Amount */
        th:nth-child(5), td:nth-child(5) { width: 12%; } /* Balance After */
        th:nth-child(6), td:nth-child(6) { width: 15%; } /* Date */
        th:nth-child(7), td:nth-child(7) { width: 21%; white-space: normal; } /* Description */
        
        /* Member table columns */
        .member-table th:nth-child(1), .member-table td:nth-child(1) { width: 15%; } /* Member ID */
        .member-table th:nth-child(2), .member-table td:nth-child(2) { width: 25%; } /* Name */
        .member-table th:nth-child(3), .member-table td:nth-child(3) { width: 35%; } /* Email */
        .member-table th:nth-child(4), .member-table td:nth-child(4) { width: 25%; } /* Contact */
        .button-group {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 20px;
        }
        .btn-back {
            background: #6c757d;
            color: #fff;
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
        }
        .btn-back:hover {
            background: #5a6268;
        }
        .no-data {
            text-align: center;
            padding: 40px 20px;
            color: #6c757d;
            font-size: 16px;
        }
        
        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .log-container {
                padding: 10px;
            }
            
            th, td {
                padding: 8px 4px;
                font-size: 12px;
            }
            
            .filter-group {
                justify-content: center;
            }
            
            .filter-group select {
                padding: 6px 8px;
                font-size: 12px;
            }
            
            .btn-back {
                padding: 10px 16px;
                font-size: 12px;
            }
            
            /* Stack table content for very small screens */
            .table-container {
                font-size: 11px;
            }
        }
        
        @media (max-width: 480px) {
            .log-container {
                padding: 5px;
            }
            
            .log-container h2 {
                font-size: 18px;
                margin-bottom: 15px;
            }
            
            th, td {
                padding: 6px 2px;
                font-size: 10px;
            }
        }
        .debug-info {
            background: #e7f3ff;
            border: 1px solid #b3d9ff;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <?php
    $host = 'sql313.infinityfree.com';
    $dbname = 'if0_38993274_pay360';
    $username = 'if0_38993274';
    $password = 'ZfkENfWP5Jh'; 

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Get and sanitize filter parameter
        $filter = isset($_GET['filter']) ? trim($_GET['filter']) : 'all';
        
        // Debug: Uncomment the line below to see what filter value is being received
        // echo "<div class='debug-info'>Current filter: " . htmlspecialchars($filter) . "</div>";

        $transactions = [];
        $members = [];

        if ($filter === 'member_additions') {
            // Fetch member additions (using MemberID as a proxy for creation order)
            $member_query = "SELECT MemberID, Firstname, Lastname, Email, Contact_No FROM member ORDER BY MemberID ASC";
            $member_stmt = $pdo->prepare($member_query);
            $member_stmt->execute();
            $members = $member_stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            // Build transaction query based on filter
            $transaction_query = "
                SELECT t.transaction_id, t.transaction_type, t.amount, t.balance_after, t.transaction_date, t.description, m.Firstname, m.Lastname
                FROM transactions t
                JOIN member m ON t.MemberID = m.MemberID
            ";
            
            $params = [];
            
            if ($filter !== 'all') {
                // Add WHERE clause for specific transaction types
                $transaction_query .= " WHERE t.transaction_type = :filter";
                $params[':filter'] = $filter;
            }
            
            // Add ORDER BY to show most recent transactions first
            $transaction_query .= " ORDER BY t.transaction_date DESC";
            
            $transaction_stmt = $pdo->prepare($transaction_query);
            
            // Bind parameters if any
            foreach ($params as $key => $value) {
                $transaction_stmt->bindValue($key, $value);
            }
            
            $transaction_stmt->execute();
            $transactions = $transaction_stmt->fetchAll(PDO::FETCH_ASSOC);
        }

    } catch (PDOException $e) {
        echo "<div class='log-container'><div style='color: #721c24; background: #f8d7da; padding: 10px; border-radius: 4px;'>Database error: " . htmlspecialchars($e->getMessage()) . "</div></div>";
        exit;
    }
    ?>

    <div class="log-container">
        <h2>Transactions Log</h2>
        <div class="filter-group">
            <select onchange="window.location.href='<?php echo $_SERVER['PHP_SELF']; ?>?filter=' + this.value">
                <option value="all" <?php echo $filter === 'all' ? 'selected' : ''; ?>>All Transactions</option>
                <option value="Deposit" <?php echo $filter === 'Deposit' ? 'selected' : ''; ?>>Deposits</option>
                <option value="Withdrawal" <?php echo $filter === 'Withdrawal' ? 'selected' : ''; ?>>Withdrawals</option>
                <option value="member_additions" <?php echo $filter === 'member_additions' ? 'selected' : ''; ?>>Member Additions</option>
            </select>
        </div>

        <?php if ($filter === 'member_additions'): ?>
            <h3>New Members</h3>
            <?php if (count($members) > 0): ?>
                <div class="table-container">
                    <table class="member-table">
                        <thead>
                            <tr>
                                <th>Member ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Contact Number</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($members as $member): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($member['MemberID']); ?></td>
                                    <td><?php echo htmlspecialchars($member['Firstname'] . ' ' . $member['Lastname']); ?></td>
                                    <td><?php echo htmlspecialchars($member['Email']); ?></td>
                                    <td><?php echo htmlspecialchars($member['Contact_No']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="no-data">No members found.</div>
            <?php endif; ?>
        <?php else: ?>
            <h3>
                <?php 
                if ($filter === 'all') {
                    echo 'All Transactions';
                } else {
                    echo ucfirst($filter) . ' Transactions';
                }
                ?>
                (<?php echo count($transactions); ?> records)
            </h3>
            <?php if (count($transactions) > 0): ?>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Transaction ID</th>
                                <th>Type</th>
                                <th>Member</th>
                                <th>Amount</th>
                                <th>Balance After</th>
                                <th>Date</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($transactions as $transaction): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($transaction['transaction_id']); ?></td>
                                    <td><?php echo htmlspecialchars($transaction['transaction_type']); ?></td>
                                    <td><?php echo htmlspecialchars($transaction['Firstname'] . ' ' . $transaction['Lastname']); ?></td>
                                    <td>$<?php echo number_format($transaction['amount'], 2); ?></td>
                                    <td>$<?php echo number_format($transaction['balance_after'], 2); ?></td>
                                    <td><?php echo date('Y-m-d H:i:s', strtotime($transaction['transaction_date'])); ?></td>
                                    <td><?php echo htmlspecialchars($transaction['description'] ?: 'N/A'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="no-data">
                    <?php 
                    if ($filter === 'all') {
                        echo 'No transactions found in the database.';
                    } else {
                        echo 'No ' . strtolower($filter) . ' transactions found.';
                    }
                    ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="button-group">
            <a href="Admin.php" class="btn-back">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>