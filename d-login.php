<?php
require 'db.php';
session_start();
//echo $_SESSION['hname'];
if(!isset($_SESSION['hid'])){
    die('hospital id not set');
}

if(isset($_SESSION['dusername'])){
    $did=$_SESSION['did'];
header("Location: d-detail.php");
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>Donor Login</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="https://bloodbanktoday.com/UploadData/blood-bank.jpg">
</head>

<body style="background-image: url('images/bloodwall.webp');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;">
<nav> <H2><B>BLOOD BANK MANAGEMENT SYSTEM</B></H2>
 <ul>
            <li><a href="hospital.php">Home</a></li>
            <li><a href="d-login.php">Donor</a></li>
            <li><a href="inventory.php">Inventory</a></li>
            <li><a href="request.php">Request</a></li>
             <li><a href="logout.php">Logout</a></li>
        </ul>
</nav>
    <div class="container" style=" width:500px; height:290px;">
        <h2>Donor Login</h2>
        <?php
        $hospitalid= $_SESSION['hid'];
         if(isset($_POST['d-loginbtn'])){
    $username=$_POST['username'];
    $password=$_POST['password'];
    if(empty($username) || empty($password)){
        die("please fill both username and password");
    }
    
    $sql="SELECT * from donor where dusername='$username' and dpassword='$password' and hid=$hospitalid";
    $result=mysqli_query($con,$sql);
    if(!$result){

        die("Qurey failed".mysqli_error($con));
    }
    $user=mysqli_fetch_assoc($result);
    if($user){
     $_SESSION['dusername']=$username;
$_SESSION['did'] = $user['did'];  
$_SESSION['dname'] = $user['dname'];
 
        header('Location: d-detail.php');
        exit();
    }else{
        echo" <script> alert('invalid username or password');</script>";
    }
}
         
 ?>
        <form id="adminLoginForm" action="" method="post">
            <input type="text" placeholder="Username" name="username" required>
            <input type="password" placeholder="Password" name="password" required>
            <button type="submit" name="d-loginbtn">Login</button>
        </form>
        <p>New User? <a href="donor.php">Register here</a></p>
    </div>
</body>

</html>