<?php
require 'db.php';
session_start();
$result = $con->query("SELECT COUNT(*) as total FROM hospital where usertype = 0");
$row = $result->fetch_assoc();
$res = $con->query("SELECT COUNT(*) as total FROM request where status='pending'");
$r = $res->fetch_assoc();
$re = $con->query("SELECT COUNT(*) as total FROM donor");
$rs = $re->fetch_assoc();
$sql=$con->query("SELECT SUM(units) AS total_units from inventory where status='enabled'");
$sq=$sql->fetch_assoc();

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <div class="sidebar">
    <h2>Admin Panel</h2>
    <a href="index.php">Dashboard</a>
     <a href="donor.php">Donors</a>
    <a href="hospital.php">Hospitals</a>
    <a href="inventory.php">Blood Bank</a>
    <a href="request.php">Requests</a>
  </div>
  <div class="main">
    <div class="topbar">
      <img src="images/blood.png" class="profile-pic" onclick="toggleDropdown()" />
      <div class="dropdown" id="profileDropdown">
        <a href="logout.php">Log out</a>
      </div>
    </div>
    <div id="dashboard" class="section active">
      <h1>Dashboard</h1>
      <div class="cards">
        <div class="card"><h2><?php echo $rs['total']; ?></h2><p>Total Donors</p></div>
        <div class="card"><h2><?php echo $sq['total_units']; ?></h2><p>Blood Units</p></div>
        <div class="card"><h2><?php echo $row['total']; ?></h2><p>Hospitals</p></div>
        <div class="card"><h2><?php echo $r['total']; ?></h2><p>Pending Requests</p></div>
      </div>
    </div>
  </div>
  <script src="script.js"></script>
</body>
</html>