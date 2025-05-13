<?php
session_start();
require '../config/db_connection.php';

// التحقق من دخول الأدمن
if (!isset($_SESSION['user']['role']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// جلب التقارير المعلقة
$stmt = $conn->prepare("
    SELECT 
        r.id AS report_id,
        r.reason,
        r.status,
        r.created_at AS report_date,
        u1.name AS reported_by,
        u2.name AS post_author,
        p.title AS post_title,
        p.content AS post_content
    FROM reports r
    JOIN posts p ON r.post_id = p.id
    JOIN users u1 ON r.user_id = u1.id
    JOIN users u2 ON p.user_id = u2.id
    WHERE r.status = 'pending'
    ORDER BY r.created_at DESC
");
$stmt->execute();
$reports = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// CSRF Token
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pending Post Reports</title>
    <style>
        body {
            background: #1e1e1e;
            color: #fff;
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        table {
            width: 100%;
            background: #2c2c2c;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            border: 1px solid #444;
            text-align: left;
        }
        th {
            background-color: #444;
        }
        tr:hover {
            background-color: #333;
        }
        .btn {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            color: #fff;
            cursor: pointer;
        }
        .approve { background-color: #2ecc71; }
        .reject { background-color: #e74c3c; }
    </style>
</head>
<body>
    <h2>Pending Post Reports</h2>

    <?php if (isset($_SESSION['message'])): ?>
        <p style="color: lightgreen"><?= $_SESSION['message'] ?></p>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <?php if (empty($reports)): ?>
        <p>No pending reports found.</p>
    <?php else: ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Post Title</th>
                <th>Post Content</th>
                <th>Reported By</th>
                <th>Post Author</th>
                <th>Reason</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reports as $report): ?>
                <tr>
                    <td><?= htmlspecialchars($report['report_id']) ?></td>
                    <td><?= htmlspecialchars($report['post_title']) ?></td>
                    <td><?= htmlspecialchars($report['post_content']) ?></td>
                    <td><?= htmlspecialchars($report['reported_by']) ?></td>
                    <td><?= htmlspecialchars($report['post_author']) ?></td>
                    <td><?= htmlspecialchars($report['reason']) ?></td>
                    <td><?= htmlspecialchars($report['report_date']) ?></td>
                    <td>
                        <form action="../handelers/review_reports.php" method="post" style="display:inline;">
                            <input type="hidden" name="report_id" value="<?= $report['report_id'] ?>">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                            <button type="submit" name="action" value="approve" class="btn approve">Approve</button>
                        </form>
                        <form action="../handlers/review_reports.php" method="post" style="display:inline;">
                            <input type="hidden" name="report_id" value="<?= $report['report_id'] ?>">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                            <button type="submit" name="action" value="reject" class="btn reject">Reject</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</body>
</html>
