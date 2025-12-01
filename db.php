<?php
$con=mysqli_connect("localhost","root","","bloodbank");
if($con->connect_error){
    die("connection failed".$con->connect_error);
}
//echo"connection successfully";

?>