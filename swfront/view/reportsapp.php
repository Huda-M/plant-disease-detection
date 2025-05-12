<?php
session_start();
require '../config/db_connection.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// التحقق من صلاحيات الأدمن
if (!isset($_SESSION['user']['role']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// توليد CSRF token
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// استرجاع التقارير المعلقة مع البيانات المرتبطة
try {
    $stmt = $conn->prepare("
       SELECT 
            pr.report_id,
            pr.status,
            p.title AS post_title,
            p.content AS post_content,
            u_reporter.name AS reported_by,
            u_author.name AS post_author,
            pr.reason,
            pr.created_at AS report_date
        FROM post_reports pr
        JOIN posts p ON pr.post_id = p.id
        JOIN users u_reporter ON pr.user_id = u_reporter.id
        JOIN users u_author ON p.user_id = u_author.id
        WHERE pr.status = 'pending'
        ORDER BY pr.created_at DESC
    ");
    $stmt->execute();
    $reports = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports Management</title>
    <style>
        body {
            background-image: linear-gradient(rgba(6, 23, 16, 0.8), rgba(0, 0, 0, 0.7)), url(images/bgfile.jpeg);
            background-size: cover;
            color: #ccc;
            min-height: 100vh;
            font-family: 'Arial', sans-serif;
        }

        .parent {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
            margin-top: 20px;
        }

        th, td {
            padding: 12px 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            text-align: left;
        }

        th {
            background-color: #083813;
            color: #fff;
            font-weight: bold;
        }

        tr:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }

        .action-btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s;
            margin: 2px;
        }

        .accept-btn { 
            background-color: #2ecc71; 
            color: white;
        }

        .reject-btn { 
            background-color: #e74c3c; 
            color: white;
        }

        .action-btn:hover { 
            opacity: 0.8; 
            transform: translateY(-1px);
        }

        .alert {
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .alert-success { background: #d4edda; color: #155724; }
        .alert-danger { background: #f8d7da; color: #721c24; }

        .status {
            padding: 4px 8px;
            border-radius: 12px;
            font-weight: 500;
        }

        .status-pending { background: #fef3c7; color: #d97706; }

        @media (max-width: 768px) {
            table { font-size: 14px; }
            .action-btn { padding: 6px 12px; }
        }
    </style>
</head>
<body>
    <div class="parent">
        <a href="index.php" class="back-btn">← Back to Home</a>
        
        <?php if(isset($_SESSION['message'])): ?>
            <div class="alert alert-<?= $_SESSION['message_type'] ?>">
                <?= $_SESSION['message'] ?>
            </div>
            <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
        <?php endif; ?>

        <h2>Pending Reports</h2>
        
        <table>
            <thead>
                <tr>
                    <th>Report ID</th>
                    <th>Post Title</th>
                    <th>Post Content</th>
                    <th>Reported By</th>
                    <th>Post Author</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Report Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($reports)): ?>
                    <tr>
                        <td colspan="9" style="text-align: center; padding: 30px;">
                            No pending reports found
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach($reports as $report): ?>
                        <tr>
                            <td><?= htmlspecialchars($report['report_id']) ?></td>
                            <td><?= htmlspecialchars($report['post_title']) ?></td>
                            <td><?= htmlspecialchars($report['post_content']) ?></td>
                            <td><?= htmlspecialchars($report['reported_by']) ?></td>
                            <td><?= htmlspecialchars($report['post_author']) ?></td>
                            <td><?= htmlspecialchars($report['reason']) ?></td>
                            <td>
                                <span class="status status-<?= $report['status'] ?>">
                                    <?= ucfirst($report['status']) ?>
                                </span>
                            </td>
                            <td><?= date('M d, Y H:i', strtotime($report['report_date'])) ?></td>
                            <td>
                                <form method="post" action="../handlers/review_reports.php" style="display: flex; gap: 5px;">
                                    <input type="hidden" name="report_id" value="<?= $report['report_id'] ?>">
                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                    <button type="submit" name="action" value="approve" class="action-btn accept-btn">Approve</button>
                                    <button type="submit" name="action" value="reject" class="action-btn reject-btn">Reject</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', (e) => {
                if (!confirm('Are you sure you want to perform this action?')) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>