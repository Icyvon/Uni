<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Pay360 Management System</title>
    <link rel="stylesheet" href="admin.css">
    <style>
        .activity-section {
            margin-top: 24px;
        }
        .activity-section .card {
            max-width: 1800px;
            margin: 0 auto;
            padding: 20px;
        }
        .filter-group {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .filter-group select {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .activity-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .activity-table th, .activity-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .activity-table th {
            background: #f8f9fa;
            font-weight: bold;
        }
        .activity-table tr:hover {
            background: #f1f3f5;
        }
        .no-data {
            text-align: center;
            padding: 20px;
            color: #6c757d;
        }
        .view-all {
            display: inline-block;
            padding: 10px 20px;
            background: #0ac20a;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
        }
        .view-all:hover {
            background: #029a02;
        }
    </style>
</head>
<body>
    <?php
    // Database connection
    $host = 'sql313.infinityfree.com';
    $dbname = 'if0_38993274_pay360';
    $username = 'if0_38993274';
    $password = 'ZfkENfWP5Jh'; // Update with your database password
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Fetch counts
        $total_members = $pdo->query("SELECT COUNT(*) FROM member")->fetchColumn();
        $total_loans = $pdo->query("SELECT SUM(Total_Amount) FROM loan")->fetchColumn();
        $total_savings = $pdo->query("SELECT SUM(Balance) FROM account WHERE Account_Type = 'Savings'")->fetchColumn();
        $total_applicants = $pdo->query("SELECT COUNT(*) FROM membership_application WHERE Status = 'pending'")->fetchColumn();

        // Format values
        $total_loans = number_format($total_loans ?: 0, 2);
        $total_savings = number_format($total_savings ?: 0, 2);

        // Get filter parameter
        $filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

        // Fetch transactions (limit to 5 for dashboard)
        $transaction_query = "
            SELECT t.transaction_id, t.transaction_type, t.amount, t.balance_after, t.transaction_date, t.description, m.Firstname, m.Lastname
            FROM transactions t
            JOIN member m ON t.MemberID = m.MemberID
        ";
        if ($filter !== 'all' && $filter !== 'member_additions') {
            $transaction_query .= " WHERE t.transaction_type = :filter";
        }
        $transaction_query .= " ORDER BY t.transaction_date DESC LIMIT 5";
        $transaction_stmt = $pdo->prepare($transaction_query);
        if ($filter !== 'all' && $filter !== 'member_additions') {
            $transaction_stmt->bindParam(':filter', $filter);
        }
        $transaction_stmt->execute();
        $transactions = $transaction_stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch member additions (limit to 5 for dashboard)
        $member_query = "
            SELECT MemberID, Firstname, Lastname, Email, Contact_No
            FROM member
            ORDER BY MemberID DESC LIMIT 5
        ";
        $member_stmt = $pdo->prepare($member_query);
        $member_stmt->execute();
        $members = $member_stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        echo "<div style='color: #721c24; background: #f8d7da; padding: 10px; border-radius: 4px; margin: 20px auto; max-width: 1000px;'>Connection failed: " . htmlspecialchars($e->getMessage()) . "</div>";
        exit;
    }
    ?>

    <!-- Sidebar Navigation -->
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <img src="logo.png" alt="Pay360 Logo" width="24" height="24" />
                <span>Pay360</span>
            </div>
            <button class="sidebar-toggle">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M15 18l-6-6 6-6"/>
                </svg>
            </button>
        </div>
        <div class="sidebar-menu">
            <a href="#" class="menu-item active">
                <i>
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7"/>
                        <rect x="14" y="3" width="7" height="7"/>
                        <rect x="14" y="14" width="7" height="7"/>
                        <rect x="3" y="14" width="7" height="7"/>
                    </svg>
                </i>
                <span>Dashboard</span>
            </a>
            
            <!-- Members & Membership Section -->
            <div class="menu-title">Member Management</div>
            <a href="Membership.php" class="menu-item">
                <i>
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 сев
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H4a4 4 0 0 0-4 4v2"/>
                        <circle cx="8" cy="7" r="4"/>
                        <line x1="18" y1="8" x2="23" y2="13"/>
                        <line x1="23" y1="8" x2="18" y2="13"/>
                    </svg>
                </i>
                <span>Membership</span>
            </a>
            <a href="Members.php" class="menu-item">
                <i>
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </i>
                <span>Active Members</span>
            </a>
            
            <!-- Financial Services Section -->
            <div class="menu-title">Financial Services</div>
            <a href="#" class="menu-item">
                <i>
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="5" width="20" height="14" rx="2"/>
                        <line x1="2" y1="10" x2="22" y2="10"/>
                    </svg>
                </i>
                <span>Loans</span>
                <span class="dropdown-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 9l6 6 6-6"/>
                    </svg>
                </span>
            </a>
            <div class="submenu">
                <a href="Service1.php" class="submenu-item">Applications</a>
                <a href="service_applicants.php" class="submenu-item">Applicants</a>
            </div>


            <a href="#" class="menu-item">
                <i>
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2v20"/>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                    </svg>
                </i>
                <span>Savings</span>
                <span class="dropdown-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 9l6 6 6-6"/>
                    </svg>
                </span>
            </a>
            <div class="submenu">
                <a href="dynamic_form.php" class="submenu-item">Deposit</a>
                <a href="dynamic_form.php" class="submenu-item">Withdraw</a>
                <a href="dynamic_form.php" class="submenu-item">Check Balance</a>
            </div>
            <a href="#" class="menu-item">
                <i>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-rolodex" viewBox="0 0 16 16">
                        <path d="M8 9.05a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
                        <path d="M1 1a1 1 0 0 0-1 1v11a1 1 0 0 0 1 1h.5a.5.5 0 0 0 .5-.5.5.5 0 0 1 1 0 .5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5.5.5 0 0 1 1 0 .5.5 0 0 0 .5.5h.5a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1H6.707L6 1.293A1 1 0 0 0 5.293 1zm0 1h4.293L6 2.707A1 1 0 0 0 6.707 3H15v10h-.085a1.5 1.5 0 0 0-2.4-.63C11.885 11.223 10.554 10 8 10c-2.555 0-3.886 1.224-4.514 2.37a1.5 1.5 0 0 0-2.4.63H1z"/>
                    </svg>
                </i>
                <span>Fixed Account</span>
                <span class="dropdown-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 9l6 6 6-6"/>
                    </svg>
                </span>
            </a>
            <div class="submenu">
                <a href="dynamic_form.php" class="submenu-item">Deposit</a>
                <a href="dynamic_form.php" class="submenu-item">Withdraw</a>
                <a href="dynamic_form.php" class="submenu-item">Check Balance</a>
            </div>
            <a href="#" class="menu-item">
                <i>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-check" viewBox="0 0 16 16">
                        <path d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0"/>
                        <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z"/>
                    </svg>
                </i>
                <span>Time Deposit</span>
                <span class="dropdown-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 9l6 6 6-6"/>
                    </svg>
                </span>
            </a>
            <div class="submenu">
                <a href="dynamic_form.php" class="submenu-item">Deposit</a>
                <a href="dynamic_form.php" class="submenu-item">Withdraw</a>
                <a href="dynamic_form.php" class="submenu-item">Check Balance</a>
            </div>
            
            <!-- Contact Us and About Us -->
            <div class="menu-title">Contact Us</div>
            <a href="#" class="menu-item">
                <i>
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                </i>
                <span>Contact Us</span>
            </a>
            <a href="#" class="menu-item">
                <i>
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                </i>
                <span>About Us</span>
            </a>
            
            <!-- Admin Settings -->
            <div class="menu-title">Settings</div>
            <a href="#" class="menu-item">
                <i>
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="3"/>
                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                    </svg>
                </i>
                <span>System Settings</span>
            </a>
            <a href="#" class="menu-item">
                <i>
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                    </svg>
                </i>
                <span>Notifications</span>
            </a>
            <a href="Home.php" class="menu-item">
                <i>
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <polyline points="16 17 21 12 16 7"/>
                        <line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                </i>
                <span>Logout</span>
            </a>
        </div>
        <div class="sidebar-footer">
            Raine Pangilinan
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="header">
            <h1 class="page-title">Dashboard</h1>
            <div class="user-profile">
                <div class="user-avatar">LP</div>
                <div class="user-info">
                    <div class="user-name">Lorraine Pangilinan</div>
                    <div class="user-role">System Administrator</div>
                </div>
            </div>
        </div>

        <div class="content">
            <!-- Statistics Cards -->
            <div class="stats">
                <div class="stat-card">
                    <div class="stat-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                    </div>
                    <div class="stat-info">
                        <div class="stat-value" id="total-members"><?php echo $total_members; ?></div>
                        <div class="stat-label">Total Members</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="5" width="20" height="14" rx="2"/>
                            <line x1="2" y1="10" x2="22" y2="10"/>
                        </svg>
                    </div>
                    <div class="stat-info">
                        <div class="stat-value" id="total-loans">₱<?php echo $total_loans; ?></div>
                        <div class="stat-label">Total Loans</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2v20"/>
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                        </svg>
                    </div>
                    <div class="stat-info">
                        <div class="stat-value" id="total-savings">₱<?php echo $total_savings; ?></div>
                        <div class="stat-label">Total Savings</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                        </svg>
                    </div>
                    <div class="stat-info">
                        <div class="stat-value" id="total-applicants"><?php echo $total_applicants; ?></div>
                        <div class="stat-label">New Applicants</div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Section -->
            <div class="dashboard-section" style="margin-top: 24px;">
                <div class="section-header">
                    <h2 class="section-title">Quick Actions</h2>
                </div>
                <div class="card">
                    <div style="display: flex; flex-wrap: wrap; gap: 16px;">
                        <a href="add_user.php" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                <circle cx="8.5" cy="7" r="4"/>
                                <line x1="20" y1="8" x2="20" y2="14"/>
                                <line x1="23" y1="11" x2="17" y2="11"/>
                            </svg>
                            Add Member
                        </a>
                        <a href="Service1.php" class="btn btn-outline">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                                <rect x="2" y="5" width="20" height="14" rx="2"/>
                                <line x1="2" y1="10" x2="22" y2="10"/>
                            </svg>
                            New Loan
                        </a>
                        <a href="dynamic_form.php" class="btn btn-outline">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                                <path d="M12 2v20"/>
                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                            </svg>
                            Record Deposit
                        </a>
                        <a href="#" class="btn btn-outline">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                <line x1="16" y1="2" x2="16" y2="6"/>
                                <line x1="8" y1="2" x2="8" y2="6"/>
 0 0 1"/>
                                <line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>
                            View Calendar
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Activity Section -->
            <div class="activity-section">
                <div class="section-header">
                    <h2 class="section-title">Recent Activity</h2>
                </div>
                <div class="card">
                    <div class="filter-group">
                        <select onchange="window.location.href='Admin.php?filter=' + this.value">
                            <option value="all" <?php echo $filter === 'all' ? 'selected' : ''; ?>>All Transactions</option>
                            <option value="Deposit" <?php echo $filter === 'Deposit' ? 'selected' : ''; ?>>Deposits</option>
                            <option value="Withdrawal" <?php echo $filter === 'Withdrawal' ? 'selected' : ''; ?>>Withdrawals</option>
                            <option value="Adjustment" <?php echo $filter === 'Adjustment' ? 'selected' : ''; ?>>Adjustments</option>
                            <option value="member_additions" <?php echo $filter === 'member_additions' ? 'selected' : ''; ?>>Member Additions</option>
                        </select>
                    </div>
                    <?php if ($filter === 'member_additions'): ?>
                        <h3>New Members</h3>
                        <?php if (count($members) > 0): ?>
                            <table class="activity-table">
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
                        <?php else: ?>
                            <div class="no-data">No members found.</div>
                        <?php endif; ?>
                    <?php else: ?>
                        <h3>Recent Transactions</h3>
                        <?php if (count($transactions) > 0): ?>
                            <table class="activity-table">
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
                                            <td>₱<?php echo number_format($transaction['amount'], 2); ?></td>
                                            <td>₱<?php echo number_format($transaction['balance_after'], 2); ?></td>
                                            <td><?php echo date('Y-m-d H:i:s', strtotime($transaction['transaction_date'])); ?></td>
                                            <td><?php echo htmlspecialchars($transaction['description'] ?: 'N/A'); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <div class="no-data">No transactions found for the selected filter.</div>
                        <?php endif; ?>
                    <?php endif; ?>
                    <a href="activity_logs.php" class="view-all">View All Activity</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle submenu visibility
        document.querySelectorAll('.menu-item').forEach(item => {
            item.addEventListener('click', function(e) {
                const nextEl = this.nextElementSibling;
                if (nextEl && nextEl.classList.contains('submenu')) {
                    e.preventDefault();
                    this.classList.toggle('expanded');
                    nextEl.classList.toggle('submenu-visible');
                }
            });
        });

        // Desktop sidebar toggle
        document.querySelector('.sidebar-toggle').addEventListener('click', function() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.style.width = sidebar.style.width === '80px' ? '260px' : '80px';
            const menuTexts = document.querySelectorAll('.menu-item span:not(.dropdown-icon)');
            menuTexts.forEach(text => {
                text.style.display = text.style.display === 'none' ? 'block' : 'none';
            });
            const logoText = document.querySelector('.sidebar-logo span');
            logoText.style.display = logoText.style.display === 'none' ? 'block' : 'none';
            const submenus = document.querySelectorAll('.submenu');
            submenus.forEach(submenu => {
                submenu.classList.remove('submenu-visible');
            });
            const dropdownIcons = document.querySelectorAll('.dropdown-icon');
            dropdownIcons.forEach(icon => {
                icon.style.display = icon.style.display === 'none' ? 'block' : 'none';
            });
            const menuTitles = document.querySelectorAll('.menu-title');
            menuTitles.forEach(title => {
                title.style.display = title.style.display === 'none' ? 'block' : 'none';
            });
            const sidebarFooter = document.querySelector('.sidebar-footer');
            sidebarFooter.style.display = sidebarFooter.style.display === 'none' ? 'block' : 'none';
        });

        // Real-time stats update
        function updateStats() {
            fetch('fetch_stats.php')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('total-members').textContent = data.total_members;
                    document.getElementById('total-loans').textContent = '₱' + parseFloat(data.total_loans).toFixed(2);
                    document.getElementById('total-savings').textContent = '₱' + parseFloat(data.total_savings).toFixed(2);
                    document.getElementById('total-applicants').textContent = data.total_applicants;
                })
                .catch(error => console.error('Error fetching stats:', error));
        }

        // Update stats every 30 seconds
        setInterval(updateStats, 30000);
        // Initial fetch
        updateStats();
    </script>
</body>
</html>