<?php
session_start();
require '../config/db_connection.php';

if (!isset($_SESSION['user']['role']) || $_SESSION['user']['role'] != 'admin') {
    $_SESSION['message'] = "Access denied: Admin privileges required!";
    $_SESSION['message_type'] = "danger";
    header('Location: ../login.php');
    exit();
}

$result = $conn->query("SELECT user_certificates.*, users.name 
                      FROM user_certificates 
                      JOIN users ON user_certificates.user_id = users.id 
                      WHERE user_certificates.status = 'pending'");
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate Approval</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: linear-gradient(rgba(6, 23, 16, 0.8), rgba(0, 0, 0, 0.7)), url(images/bgfile.jpeg);
            background-size: cover;
            min-height: 100vh;
        }
        .card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .custom-heading {
            color: #fff !important;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <?php if (isset($_SESSION['message'])) : ?>
            <div class="alert alert-<?= $_SESSION['message_type'] ?> alert-dismissible fade show">
                <?= $_SESSION['message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
        <?php endif; ?>

        <h2 class="text-center mb-4 custom-heading">Pending Certificates</h2>

        <?php if ($result->num_rows > 0) : ?>
            <?php while ($row = $result->fetch_assoc()) : ?>
                <div class="card mb-3 shadow-lg">
                    <div class="card-body">
                        <h5 class="card-title text-white"><?= htmlspecialchars($row['name']) ?></h5>
                        <a href="../<?= htmlspecialchars($row['certificate_path']) ?>" 
                           class="btn btn-outline-light btn-sm mb-2" 
                           target="_blank">
                            View Certificate
                        </a>
                        <form method="POST" action="../handelers/review_certificates.php">
                            <input type="hidden" name="certificate_id" value="<?= $row['id'] ?>">
                            <input type="hidden" name="user_id" value="<?= $row['user_id'] ?>">
                            <div class="d-grid gap-2 d-md-block">
                                <button type="submit" name="approve" class="btn btn-success me-md-2">
                                    Approve
                                </button>
                                <button type="submit" name="reject" class="btn btn-danger">
                                    Reject
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else : ?>
            <p class="text-center text-white">No pending certificates found.</p>
        <?php endif; ?>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>