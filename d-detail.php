<?php
require 'db.php';
session_start();
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies
if(!isset($_SESSION['dname'])){
header('Location: d-login.php');
exit();
}
 $did=$_SESSION['did'];

 if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['saveDetails'])) {
    $name = $_POST['dname'];
    $password = $_POST['password']; // Consider hashing in production
    $dob = $_POST['dob'];
    $btype = $_POST['btype'];
    $contact = $_POST['contact'];
    $last_donation = $_POST['last_donation'];
    $image_file = '';

    // Image upload: only update if new image is uploaded
    if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] == 0) {
        $target_dir = "uploads/";
        $image_file = $target_dir . uniqid() ."_" . basename($_FILES["profileImage"]["name"]);
        move_uploaded_file($_FILES["profileImage"]["tmp_name"], $image_file);
      

       

    } else {
        // Get existing image from DB if not replaced
        $getImg = $con->prepare("SELECT image FROM donor WHERE did = ?");
        $getImg->bind_param("i", $did);
        $getImg->execute();
        $getImg->bind_result($image_file);
        $getImg->fetch();
        $getImg->close();
    }

    if (empty($image_file)) {
    echo "<script>alert('Image file is empty!');</script>";
}

    $stmt = $con->prepare("UPDATE donor SET dname=?, dpassword=?, dob=?, btype=?, contact=?, lastdonation=?, image=? WHERE did=?");
    $stmt->bind_param("sssssssi", $name, $password, $dob, $btype, $contact, $last_donation, $image_file, $did);
    if ($stmt->execute()) {
        echo "<script>alert('Details updated successfully!');
         window.location.href='d-detail.php';</script>";
        exit();
    } else {
        echo "<script>alert('Failed to update: " . addslashes($stmt->error) . "');</script>";
    }
    $stmt->close();
}

// Fetch donor current data
$stmt = $con->prepare("SELECT dname, dpassword, dob, btype, contact, lastdonation, image FROM donor WHERE did = ?");
$stmt->bind_param("i", $did);
$stmt->execute();
$stmt->bind_result($name, $password, $dob, $btype, $contact, $last_donation, $image);
$stmt->fetch();
$stmt->close();


if(isset($_POST['d-logoutbtn'])){
  unset($_SESSION['dusername']);
  unset($_SESSION['did']);
  unset($_SESSION['dname']);
header("Location: d-login.php");
exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Donor Details</title>
  
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f7f9fc;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .container {
      background: #fff;
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      width: 720px;
      text-align: center;
    }
    h2 {
      color: #333;
      margin-bottom: 20px;
    }
    .profile-pic {
      position: relative;
      display: inline-block;
      margin-bottom: 20px;
    }
    .profile-pic img {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      border: 3px solid #007bff;
      object-fit: cover;
    }
    .profile-pic input {
      display: none;
    }
    .upload-label {
      position: absolute;
      bottom: 0;
      right: 0;
      background: #007bff;
      color: #fff;
      border-radius: 50%;
      padding: 6px;
      cursor: pointer;
      font-size: 14px;
      border: 2px solid #fff;
    }
    .field {
      margin: 12px 0;
      text-align: left;
    }
    .field label {
      display: block;
      font-weight: bold;
      margin-bottom: 5px;
      color: #555;
    }
    .field input {
      width: 100%;
      padding: 8px;
      border: 1px solid #ddd;
      border-radius: 5px;
      background: #f0f0f0;
    }
    .field input:disabled {
      background: #f0f0f0;
    }
    .buttons {
      margin-top: 20px;
    }
    .buttons button {
      padding: 10px 15px;
      margin: 0 5px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-weight: bold;
    }
    .edit-btn {
      background: #007bff;
      color: #fff;
    }
    .save-btn {
      background: #28a745;
      color: #fff;
      display: none;
    }
    .cancel-btn {
      background: #dc3545;
      color: #fff;
      display: none;
    }
    .logout-btn{
      background: #dc3545;
      color: #fff;
    }
  </style>
  
</head>
 <script>
window.onload = function () {
  for(let i=0;i<100;i++){
    history.pushState(null,' ',window.location.href);
  }
     window.onpopstate = function () {
        history.pushState(null, ' ',window.location.href);
    };
};
</script>
 
<body style="background-image: url('images/bloodwall.webp');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;">

  <div class="container">
    <h2>Donor Details</h2>
<form method="post" id="editDonorForm" enctype="multipart/form-data">
    <!-- Profile Picture -->
    <div class="profile-pic">
      <img id="profileImage"   src="<?php echo htmlspecialchars($image); ?>" alt="Profile Picture">
      <input type="file" name="profileImage" id="fileInput" accept="image/">
      <label for="fileInput" class="upload-label">📷</label>
    </div>

    <div class="field">
      <label for="name">Name:</label>
      <input type="text" id="dname" name="dname" value="<?php echo htmlspecialchars($name); ?>"  required>
    </div>
    <div class="field">
      <label for="age">Password</label>
      <input type="text" id="password" name="password" value="<?php echo htmlspecialchars($password); ?>" required>
    </div>
    <div class="field">
      <label for="age">Date of Birth</label>
      <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($dob); ?>" required>
    </div>
    <div class="field">
      <label for="age">Last Donation</label>
      <input type="date" id="last_donation" name="last_donation" value="<?php echo htmlspecialchars($last_donation); ?>" required>
    </div>
    <div class="field">
      <label for="blood">Blood Group:</label>
      <input type="text" id="btype" value="<?php echo htmlspecialchars($btype); ?>" name="btype"   required>
    </div>
    <div class="field">
      <label for="contact">Contact:</label>
      <input type="text" id="contact" name="contact" value="<?php echo htmlspecialchars($contact); ?>" required>
    </div>
    
    <div class="buttons">
       <button type="button" class="logout-btn" onclick="Donate()" >Donate blood</button>
      <button type="button" class="edit-btn"  onclick="enableEdit()">Edit</button>
      <button type="submit" class="logout-btn"  name="d-logoutbtn" >logout</button>
      <button type="submit" class="save-btn" name="saveDetails"  onclick="saveDetails()" style="display:none;">Save</button>
      <button type="button" class="cancel-btn"  style="display:none;" onclick="cancelEdit()">Cancel</button>
    </div>
  </div>
</form>


  <script>

    const form = document.getElementById('editDonorForm');
const editBtn = document.querySelector('.edit-btn');
const saveBtn = document.querySelector('.save-btn');
const cancelBtn = document.querySelector('.cancel-btn');
const inputs = form.querySelectorAll('input, select');
let originalValues = {};

function enableEdit() {
  inputs.forEach(input => input.disabled = false);
  editBtn.style.display = 'none';
  saveBtn.style.display = 'inline-block';
  cancelBtn.style.display = 'inline-block';
  inputs.forEach(input => { originalValues[input.name] = input.value; });
}
function cancelEdit() {
  inputs.forEach(input => {
    input.value = originalValues[input.name] || input.value;
    input.disabled = true;
  });
  editBtn.style.display = 'inline-block';
  saveBtn.style.display = 'none';
  cancelBtn.style.display = 'none';
}
window.onload = function() {
  inputs.forEach(input => input.disabled = true);
  saveBtn.style.display = 'none';
  cancelBtn.style.display = 'none';
}

function Donate(){
 
  window.open('donation.php');
}
  </script>

</body>
</html>