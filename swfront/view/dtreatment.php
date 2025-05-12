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

// استرجاع بيانات العلاجات المعلقة
try {
    $stmt = $conn->prepare("
        SELECT 
            ts.id,
            ts.name AS treatment_name,
            ts.method,
            u.name AS suggested_by,
            ts.created_at
        FROM treatment_suggestions ts
        JOIN users u ON ts.expert_id = u.id
        WHERE ts.status = 'pending'
        ORDER BY ts.created_at DESC
    ");
    $stmt->execute();
    $treatments = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Treatment Suggestions Approval</title>
    <link href="../style/dapprove.css" rel="stylesheet">
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
            color: #155724;
        }
        .alert-success { background: #d4edda; }
        .alert-danger { background: #f8d7da; }

        .back-btn {
            color: #2ecc71;
            text-decoration: none;
            font-size: 16px;
            display: inline-block;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            table { font-size: 14px; }
            .action-btn { 
                padding: 6px 12px;
                font-size: 12px;
            }
            th, td {
                padding: 8px 10px;
            }
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

        <h2>Pending Treatment Suggestions</h2>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Treatment Name</th>
                    <th>Suggested By</th>
                    <th>Method</th>
                    <th>Date Suggested</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($treatments)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 30px;">
                            No pending treatment suggestions found
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach($treatments as $treatment): ?>
                        <tr>
                            <td><?= htmlspecialchars($treatment['id']) ?></td>
                            <td><?= htmlspecialchars($treatment['treatment_name']) ?></td>
                            <td><?= htmlspecialchars($treatment['suggested_by']) ?></td>
                            <td style="max-width: 400px;"><?= nl2br(htmlspecialchars($treatment['method'])) ?></td>
                            <td><?= date('M d, Y H:i', strtotime($treatment['created_at'])) ?></td>
                            <td>
                                <form method="post" action="../handelers/review_treatment.php" style="display: flex; gap: 5px;">
                                    <input type="hidden" name="treatment_id" value="<?= $treatment['id'] ?>">
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
        // تأكيد الإجراء قبل التنفيذ
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