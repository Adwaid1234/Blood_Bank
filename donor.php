<?php
// Include database connection
require 'db.php';
session_start();
if(!isset($_SESSION['hid'])){
header("Location: login.php");
}
//echo $_SESSION['hname'];
 $hid=$_SESSION['hid'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data safely
    $name = $_POST['name'];
    $uname = $_POST['username'];
    $pass = $_POST['password'];
    $dob = $_POST['dob'];
    $dobDate=new DateTime($dob);
    $now=new DateTime();
    $age=$now->diff($dobDate)->y;
     $blood_type = $_POST['bloodtype'];
    $last_donation = $_POST['last_donation'];
    $contact = $_POST['contact'];

    $target_dir = "uploads/"; // Make sure this folder exists and is writable
    $image_file = $target_dir . basename($_FILES["image"]["name"]);
     if($age<=19){
         echo "<script>alert('Age should be greater than 19!! ');</script>";
     }else{
   
   

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $image_file)) {
        // Prepare the SQL (ensure donor table has an image column, type VARCHAR)
        $stmt = $con->prepare("INSERT INTO donor (hid,dname, dusername, dpassword, dob, btype, contact, lastdonation, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssssss", $hid, $name, $uname, $pass, $dob, $blood_type, $contact, $last_donation,  $image_file);


        if ($stmt->execute()) {
              echo "<script>alert('Donor Registration succesfully!');
               window.location.href ='d-login.php'; // Redirect after success
            </script>";
            exit();
        } else {
            echo "<script>alert('Donor Registration failed: " . addslashes($stmt->error) . "');</script>";
        }
    } else {
        echo "<script>alert('Image upload failed!');</script>";
    }
    $stmt->close();
$con->close();
}


}
   

?>
<!DOCTYPE html>
<html>

<head>
    <title>Donor Registration</title>
    <link rel="stylesheet" href="style.css">
<link rel="icon" href="https://bloodbanktoday.com/UploadData/blood-bank.jpg">

</head>

<body style="background-image: url('images/bloodwall.webp');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;">

    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="hospital.php">Hospital</a></li>
            <li><a href="inventory.php">Inventory</a></li>
            <li><a href="request.php">Request</a></li>
              <li><a href="logout.php">logout</a></li>
        </ul>
    </nav>

    <div class="container">
        <h2>Donor Registration</h2>
        <form id="donorForm" action="" method="post" enctype="multipart/form-data">

            <input type="hidden" placeholder="Donor ID" >
            <b>Donor Name</b>
            <input type="text" placeholder="Name" name="name" required>
            <b>Username</b>
            <input type="text" placeholder="Name" name="username" required>
            <b>Password</b>
            <input type="password" placeholder="Password" id="password" name="password" required>
            <p id="message"></p>
<div id="password-message">
  <h4>Password must contain:</h4>
  <p class="password-message-item invalid"  style="font-size: smaller;">At least <b>one lowercase letter</b></p>
  <p class="password-message-item invalid" style="font-size: smaller;">At least <b>one uppercase letter</b></p>
  <p class="password-message-item invalid" style="font-size: smaller;">At least <b>one number</b></p>
  <p class="password-message-item invalid" style="font-size: smaller;">At least <b>one special character</b></p>
  <p class="password-message-item invalid" style="font-size: smaller;">Minimum <b>8 characters</b></p>
</div><br>
<input type="password" placeholder="re-enter password" id="confirmPassword" required><br>

            <b>Date of Birth</b>
            <input type="date" placeholder="Date of birth" name="dob" required>
           <b> Blood type</b>
            <select name="bloodtype" required>
            <option value="">Select Blood Type</option>
            <option value="A+" >A+</option>
            <option value="A-">A-</option>
            <option value="B+">B+</option>
            <option value="B-">B-</option>
            <option value="O+">O+</option>
            <option valueO->O-</option>
            <option value="AB+">AB+</option>
            <option value="AB-">AB-</option>
        </select>
        <b>Last Donation Date</b>
            <input type="date" placeholder="Last Donation Date" name="last_donation">
            <b>Contact</b>
            <input type="text" placeholder="Contact" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '');"  name="contact" required>
            <b>Profile pic</b>
            <input type="file" placeholder="Contact" name="image" required>

            <button type="submit" name="dregisterbtn">Register Donor</button>
        </form>
        Already a donor?<a href="d-login.php">login</a>
    </div>
<script src="script.js"></script>
</body>

</html>