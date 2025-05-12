<?php
session_start();
require '../config/db_connection.php';

// تفعيل عرض الأخطاء
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

// استرجاع البيانات
try {
    $stmt = $conn->prepare("
        SELECT 
            ds.id,
            ds.name AS disease_name,
            ds.description,
            ds.symptoms,
            u.name AS suggested_by,
            ds.created_at
        FROM disease_suggestions ds
        JOIN users u ON ds.expert_id = u.id
        WHERE ds.status = 'pending'
        ORDER BY ds.created_at DESC
    ");
    $stmt->execute();
    $suggestions = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Disease Suggestions Approval</title>
    <link href="../style/dapprove.css" rel="stylesheet">
    <style>
        body {
            background-image: linear-gradient(rgba(6, 23, 16, 0.8), rgba(0, 0, 0, 0.7)), url(images/bgfile.jpeg);
            background-size: cover;
            color: #ccc;
            min-height: 100vh;
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
        }

        th, td {
            padding: 12px 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        th {
            background-color: #083813;
            color: #fff;
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
        }

        .accept-btn { background-color: #2ecc71; }
        .reject-btn { background-color: #e74c3c; }
        .action-btn:hover { opacity: 0.9; }

        .alert {
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .alert-success { background: #d4edda; }
        .alert-danger { background: #f8d7da; }

        @media (max-width: 768px) {
            table { font-size: 14px; }
            .action-btn { padding: 6px 12px; }
        }
    </style>
</head>
<body>
    <div class="parent">
        <a href="index.php" class="back-btn">← Home</a>
        
        <?php if(isset($_SESSION['message'])): ?>
            <div class="alert alert-<?= $_SESSION['message_type'] ?>">
                <?= $_SESSION['message'] ?>
            </div>
            <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
        <?php endif; ?>

        <h2>Pending Suggestions</h2>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Disease</th>
                    <th>Suggested By</th>
                    <th>Description</th>
                    <th>Symptoms</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($suggestions)): ?>
                    <tr>
                        <td colspan="7" class="text-center">No pending suggestions</td>
                    </tr>
                <?php else: ?>
                    <?php foreach($suggestions as $suggestion): ?>
                        <tr>
                            <td><?= htmlspecialchars($suggestion['id']) ?></td>
                            <td><?= htmlspecialchars($suggestion['disease_name']) ?></td>
                            <td><?= htmlspecialchars($suggestion['suggested_by']) ?></td>
                            <td><?= nl2br(htmlspecialchars($suggestion['description'])) ?></td>
                            <td><?= nl2br(htmlspecialchars($suggestion['symptoms'])) ?></td>
                            <td><?= date('M j, Y H:i', strtotime($suggestion['created_at'])) ?></td>
                            <td>
                                <form method="post" action="../handelers/review_disease.php">
                                    <input type="hidden" name="suggestion_id" value="<?= $suggestion['id'] ?>">
                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                    <button type="submit" name="action" value="accept" class="action-btn accept-btn">Accept</button>
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
                if (!confirm('Are you sure?')) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>