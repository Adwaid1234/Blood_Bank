<?php
session_start();
require 'db.php';
//print_r($_POST);
if(isset($_POST['btn']))
    {
        
        $username=$_POST['username'];
        $password = $_POST['password'];
        $hname = $_POST['hname'];
        $address = $_POST['address'];
        $contact = $_POST['contact'];
      $sql = "INSERT INTO hospital VALUES (NULL,'$username', '$hname', '$address', '$contact', '$password',0)";
				$res=mysqli_query($con,$sql);
				if ($res) {
                    $_SESSION['hname']=$hname;
    echo "<script>
        alert('Registration successfully');
        window.location.href = 'hospital.php';
    </script>";
    exit();
}
                    

                        
          
        else{
          echo "not inserted".mysqli_error($con);
        }

    }
    
    ?>
    
<!DOCTYPE html>
<html>

<head>
  
    <title>Registration</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="https://bloodbanktoday.com/UploadData/blood-bank.jpg">
</head>

<body style="background-image: url('images/bloodwall.webp');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;">

   
    <nav>
        <h2><b>BLOOD BANK MANAGEMENT SYSTEM</b></h2>
    </nav>
    


    <div class="container">
        <h2>Registration</h2>


        <form id="hospitalForm"  action="" method="post">
             <input type="text" placeholder="Username" name="username" required>
            <input type="text" placeholder="Hospital Name" name="hname" required>
            <input type="text" placeholder="Address" name="address" required>
            <input type="text" placeholder="Contact Number" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '');"  name="contact" required>
             <input type="password" placeholder="Enter password" name="password" id="password" required>
              <input type="password" placeholder="re-enter password" id="confirmPassword" required>
              <p id="message"></p>
<div id="password-message">
  <h4>Password must contain:</h4>
  <p class="password-message-item invalid" style="font-size: smaller;">At least <b>one lowercase letter</b></p>
  <p class="password-message-item invalid" style="font-size: smaller;">At least <b>one uppercase letter</b></p>
  <p class="password-message-item invalid" style="font-size: smaller;">At least <b>one number</b></p>
  <p class="password-message-item invalid" style="font-size: smaller;">At least <b>one special character</b></p>
  <p class="password-message-item invalid" style="font-size: smaller;">Minimum <b>8 characters</b></p>
</div>
            <input type="hidden" name="usertype" value="0">
            <button type="submit"  id="submitbtn" name="btn" >Register Hospital</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
</div>


<script src="script.js"></script>
</body>

</html>