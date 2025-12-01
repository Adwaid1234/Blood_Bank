<?php
require 'db.php';
session_start();
//echo $_SESSION['hname'];
if(isset($_SESSION['hname'])){
   }
if(isset($_SESSION['dname'])){
     
}
 $hid=$_SESSION['hid'];
 $did=$_SESSION['did'];
  $dname=$_SESSION['dname'];
   

$stmt = $con->prepare("SELECT btype FROM donor WHERE did = ?");
$stmt->bind_param("i", $did);
$stmt->execute();
$stmt->bind_result($bloodtype);
$stmt->fetch();
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bloodType = $_POST['bloodType'];
    $units = $_POST['units'];
    $donationDate = $_POST['donationDate'];

    // Prepare and execute insertion
    $stmt = $con->prepare("INSERT INTO donation (did, dname, hid, btype, units, date) VALUES (?,?, ?, ?, ?, ?)");
    $stmt->bind_param("isisis", $did,$dname, $hid, $bloodType, $units, $donationDate);

    
    

    if ($stmt->execute()) {
        $sql=$con->prepare("UPDATE donor set lastdonation = ? where did = ?");
        $sql->bind_param("si",  $donationDate, $did);

        if($sql->execute()){
            $res=$con->prepare("UPDATE inventory set units = units + ? where btype = ?");
        $res->bind_param("is",  $units, $bloodType);

        if($res->execute()){
             echo "<script>alert('Donation entry added successfully!');</script>";

        header("Location: d-detail.php");
        exit();
        }
        }
    } else {
        header("Location: donation.php?success=0");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Blood Donation Entry</title>
    <link rel="stylesheet" href="style.css">
    <style>

        body {
    background-color: #f7f7f7;
    font-family: Arial, sans-serif;
}

h2 {
    color: #900;
    margin-bottom: 24px;
}
form {
    display: flex;
    flex-direction: column;
    gap: 16px;
}
input, select, button {
    padding: 10px;
    border-radius: 4px;
    border: 1px solid #bbb;
    font-size: 16px;
}
button {
    background-color: #900;
    color: white;
    border: none;
    cursor: pointer;
    font-size: 16px;
}
button:hover {
    background-color: #b00;
}
#message {
    margin-top: 20px;
    color: #009900;
    font-weight: bold;
}
    </style>
</head>
<body style="background-image: url('images/bloodwall.webp');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;">
    <?php
if (isset($_GET['success']) && $_GET['success'] == 1) {
    echo "<script>alert('Donation entry added successfully!');</script>";
}
if (isset($_GET['success']) && $_GET['success'] == 0) {
    echo "<script>alert('Error adding donation entry.');</script>";
}
?>
    <nav>
    <ul>
        <li><a href="request.php">Request</a></li>
        <li><a href="d-login.php">Donor Registration</a></li>
        <li><a href="inventory.php">Inventory</a></li>
         <li><a href="logout.php">Logout</a></li>
        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<li><a href="logout.php">logout</a></li>
    </ul>
  </nav>
    <div class="container" style=" width:500px;">
        <h2>Donation Form</h2>
        <form id="donationForm" method="POST" action="donation.php">
            <select id="bloodType" name="bloodType" required>
                <option value="A+" <?php if($bloodtype=="A+") echo "selected"; ?>>A+</option>
                <option value="A-" <?php if($bloodtype=="A-") echo "selected"; ?>>A-</option>
                <option value="B+" <?php if($bloodtype=="B+") echo "selected"; ?>>B+</option>
                <option value="B-" <?php if($bloodtype=="B-") echo "selected"; ?>>B-</option>
                <option value="O+" <?php if($bloodtype=="O+") echo "selected"; ?>>O+</option>
                <option value="O-" <?php if($bloodtype=="O-") echo "selected"; ?>>O-</option>
                <option value="AB+" <?php if($bloodtype=="AB+") echo "selected"; ?>>AB+</option>
                <option value="AB-" <?php if($bloodtype=="AB-") echo "selected"; ?>>AB-</option>
            </select>
            <input type="number" id="units" name="units" placeholder="Units" required>
            <input type="date" id="donationDate" name="donationDate" required>
            <button type="submit" name="donatebtn">Donate</button>
        </form>
        <div id="message"></div>
    </div>
    <script src="donation-script.js"></script>
</body>
</html>