<?php
session_start();
require 'db.php';

if (isset($_SESSION['username'])) {
    header('Location: hospital.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="https://bloodbanktoday.com/UploadData/blood-bank.jpg">
    
</head>
<body style="background-image: url('images/bloodwall.webp');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;">
<nav><h2><b>BLOOD BANK MANAGEMENT SYSTEM</b></h2></nav>
<div class="container" style=" width:500px;
    height:290px;">
    <h2>Login</h2>
    <?php
    if (isset($_SESSION['status']) && $_SESSION['status'] != '') {
        echo "<div class='alert alert-danger'>" . $_SESSION['status'] . "</div>";
        unset($_SESSION['status']);
    }
    ?>
    <form id="adminLoginForm" action="" method="post">
        <input type="text" placeholder="Username" name="username" required><br>
        <input type="password" placeholder="Password" name="password" required><br>
        <button type="submit" name="loginbtn">Login</button>
    </form>
    <p>New User? <a href="registration.php">Register here</a></p>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // 1️⃣ Verify connection first
        if (!$con) {
            die("Database connection failed: " . mysqli_connect_error());
        }

        // 2️⃣ Check user credentials
        $query = "SELECT * FROM hospital WHERE username='$username' AND password='$password'";
        $result = mysqli_query($con, $query);
        if (!$result) {
            die("User query failed: " . mysqli_error($con)); // <--- this will show exact SQL error
        }

        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);

            // 3️⃣ If hospital user
            if ($user['usertype'] == 0) {
                $_SESSION['username'] = $username;

                // Fetch hospital details
                $query2 = "SELECT hid, hname FROM hospital WHERE username='$username'";
                $result2 = mysqli_query($con, $query2);
                if (!$result2) {
                    die("Hospital query failed: " . mysqli_error($con)); // check this line if error occurs
                }

                if (mysqli_num_rows($result2) > 0) {
                    $ur = mysqli_fetch_assoc($result2);
                    $_SESSION['hid'] = $ur['hid'];
                    $_SESSION['hname'] = $ur['hname'];
                } else {
                    $_SESSION['status'] = "Hospital details not found for this user.";
                    header("Location: login.php");
                    exit();
                }

                header('Location: hospital.php');
                exit();
            } 
            elseif($user['usertype'] == 1){
                 header('Location: admin/index.php');
            }
            else {
                $_SESSION['status'] = "Invalid user type.";
                header("Location: login.php");
                exit();
            }
        } else {
            $_SESSION['status'] = "Email/Password incorrect";
            header("Location: login.php");
            exit();
        }
    }
    ?>
</div>
</body>
</html>
