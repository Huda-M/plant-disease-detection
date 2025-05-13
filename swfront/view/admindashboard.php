<?php
session_start();
require '../config/db_connection.php';

// احصائيات عامة
$totalUsers = $conn->query("SELECT COUNT(*) FROM users WHERE role = 'user'")->fetch_row()[0];
$totalExperts = $conn->query("SELECT COUNT(*) FROM users WHERE role = 'expert'")->fetch_row()[0];
$totalAdmins = $conn->query("SELECT COUNT(*) FROM users WHERE role = 'admin'")->fetch_row()[0];
$totalPosts = $conn->query("SELECT COUNT(*) FROM posts")->fetch_row()[0];
$totalReports = $conn->query("SELECT COUNT(*) FROM reports")->fetch_row()[0];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="../style/admindashboard.css">
</head>
<body>
  <div class="parent">
    <div class="dashboard-container">
        <h2>Admin Dashboard</h2>
    
        <div class="stats">
  <div class="card blue">
    <p>Total Users</p>
    <h3><?= $totalUsers ?></h3>
  </div>
  <div class="card green">
    <p>Total Experts</p>
    <h3><?= $totalExperts ?></h3>
  </div>
  <div class="card yellow">
    <p>Total Posts</p>
    <h3><?= $totalPosts ?></h3>
  </div>
  <div class="card red">
    <p>Total Reports</p>
    <h3><?= $totalReports ?></h3>
  </div>
</div>
<div class="card purple">
  <p>Total Admins</p>
  <h3><?= $totalAdmins ?></h3>
</div>

    
        <h3 class="margin">Quick Links</h3>
        <ul class="quick-links">
          
          <li><a href="usermanage.php">User management </a></li>
          <li><a href="certificateapp.php">review Certificates</a></li>
          <li><a href="dapprove.php">Disease Suggestions</a></li>
          <li><a href="dtreatment.php">Treatment Suggestions</a></li>
          <li><a href="reportsapp.php">View Reports</a></li>
        </ul>
    
        <div class="footer-buttons">
          <button onclick="location.href='index.php'" class="home-btn">Back to Home</button>
          <button class="logout-btn" onclick="handleLogout()">Logout</button>
        </div>
      </div>
  </div>
  <script>
    function handleLogout() {
      alert("You have been logged out.");
      
    }
  </script>
</body>
</html>
