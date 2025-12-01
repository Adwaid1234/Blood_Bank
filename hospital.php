<?php
session_start();
require 'db.php';
if (!isset($_SESSION['hid'])) {
    header("Location: login.php");
    exit();
}

  $hid = $_SESSION['hid'];
  $hname = $_SESSION['hname'];
 $username = $_SESSION['username'];

$res = $con->query("SELECT COUNT(*) as total FROM request where status='pending' and hid=$hid");
$r = $res->fetch_assoc();
$re = $con->query("SELECT COUNT(*) as total FROM donor where hid=$hid");
$rs = $re->fetch_assoc();
$sql=$con->query("SELECT SUM(units) AS total_units from inventory where status='enabled'");
$sq=$sql->fetch_assoc();


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>hospital</title>
    <link rel="icon" href="https://bloodbanktoday.com/UploadData/blood-bank.jpg">
    <link rel="stylesheet" href="style.css">
    <script src="https://kit.fontawesome.com/d658ed5907.js" crossorigin="anonymous"></script>

    <style>
      .cards {
  display: flex;
  gap: 28px;
}
.card {
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 2px 8px #aaa1;
  padding: 16px 28px;
  flex: 1;
  text-align: center;
}
    </style>
</head>
<body style="background-image: url('images/bloodwall.webp');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;">
  <nav>
    <ul>
        <li><a href="request.php">Request</a></li>
        <li><a href="d-login.php">Donor</a></li>
        <li><a href="inventory.php">Inventory</a></li>
        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<li><a href="logout.php">logout</a></li>
    </ul>
  </nav>
  <h1><b><?php echo $_SESSION['hname']; ?></b></h1>
  <br><br><br>



  <div class="cards">
        <div class="card"><h2><?php echo $rs['total']; ?></h2><p>Total Donors</p></div>
        <div class="card"><h2><?php echo $sq['total_units']; ?></h2><p>Blood Units</p></div>
        <div class="card"><h2><?php echo $r['total']; ?></h2><p>Pending Requests</p></div>
  </div>
    
</body>
</html>