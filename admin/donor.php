<?php
session_start();
require 'db.php'; // database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_id'])) {
    $edit_id = intval($_POST['id']);
    $edit_donorname = $_POST['donorname'];
    $edit_hospitalname = $_POST['hospitalname'];
    $edit_username = $_POST['username'];
    $edit_password = $_POST['password'];
    $edit_bloodtype = $_POST['bloodtype'];
    $edit_contact = $_POST['contact'];

    // You need to find the hospital id from the hospital name (assuming unique)
    $stmt = $con->prepare("SELECT hid FROM hospital WHERE hname = ?");
    $stmt->bind_param("s", $edit_hospitalname);
    $stmt->execute();
    $result_hid = $stmt->get_result();

    if ($result_hid->num_rows == 1) {
        $row_hid = $result_hid->fetch_assoc();
        $hid = $row_hid['hid'];

        // Now update donor table using prepared statement
        $update = $con->prepare("UPDATE donor SET dname=?, dusername=?, dpassword=?, btype=?, contact=? WHERE did=?");
        $update->bind_param("sssssi", $edit_donorname, $edit_username, $edit_password, $edit_bloodtype, $edit_contact, $edit_id);

        if ($update->execute()) {
            // Redirect to avoid form resubmission
            header("Location: donor.php");
            exit();
        } else {
            echo "Error updating record: " . $con->error;
        }
    } else {
        echo "Hospital not found.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete-btn'])) {
    $delete_id = intval($_POST['delete_id']); // Use intval for safety

    $stmt = $con->prepare("DELETE FROM donor WHERE did = ?");
    $stmt->bind_param("i", $delete_id);

    if ($stmt->execute()) {
        // Optional: redirect to avoid resubmission
        header("Location: donor.php");
        exit();
    } else {
        echo "Error deleting record: " . $stmt->error;
    }

    $stmt->close();
}


$sql = "SELECT donor.*,hospital.hname FROM donor inner join hospital on donor.hid=hospital.hid";
$result = $con->query($sql);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Donor Management</title>
    <link rel="stylesheet" href="style.css">
    <style>

        body {
    margin: 0;
    font-family: Arial, sans-serif;
}

.main-content {
    margin-left: 240px;
    padding: 30px;
}
.main-content h1 {
    margin-bottom: 24px;
}
table {
    border-collapse: collapse;
    width: 100%;
    background: #fff;
    box-shadow: 0 4px 30px rgba(0,0,0,0.05);
}
th, td {
    padding: 12px 16px;
    border-bottom: 1px solid #eee;
    text-align: left;
}
th {
    background: #eaeaea;
}
.action-btn {
    padding: 5px 16px;
    border: none;
    border-radius: 4px;
    color: #fff;
    margin-right: 5px;
    cursor: pointer;
    font-weight: bold;
}
.edit-btn {
    background-color: #28a745;
}
.delete-btn {
    background-color: #dc3545;
}
.action-btn:hover {
    opacity: 0.85;
}
    </style>
</head>
<body>
    <!-- Edit Donor Modal -->
<div id="editModal" style="display:none; position:fixed; top:10%; left:35%; background:#f9f9f9; padding:20px; border:1px solid #333; z-index:1000; width: 500px; height: 500px;">
  <h3>Edit Donor</h3>
  <form id="editForm" method="POST" action="donor.php">
    <input type="hidden" name="id" id="edit_id" />
    <label>Donor Name:</label><br />
    <input type="text" name="donorname" id="edit_donorname" required /><br /><br>
    <label>Hospital Name:</label><br />
    <input type="text" name="hospitalname" id="edit_hospitalname" required /><br /><br>
    <label>Username:</label><br />
    <input type="text" name="username" id="edit_username" required /><br /><br>
    <label>Password:</label><br />
    <input type="text" name="password" id="edit_password" required /><br /><br>
    <label>Blood Type:</label><br />
    <input type="text" name="bloodtype" id="edit_bloodtype" required /><br /><br>
    <label>Contact:</label><br />
    <input type="text" name="contact" id="edit_contact" required /><br /><br>
    <button type="submit" name="edit_id" style="background-color: #28a745; border-radius: none; color: white;">Save</button>
    <button type="button" onclick="closeModal()">Cancel</button>
  </form>
</div>

<div id="modalOverlay" style="display:none; position:fixed; top:0; left:0; width:100%;height:100%; background:rgba(0,0,0,0.5); z-index:900;"></div>
    <div class="sidebar">
        <h2>Admin Panel</h2>
           <a href="index.php">Dashboard</a>
     <a href="donor.php">Donors</a>
    <a href="hospital.php">Hospitals</a>
    <a href="inventory.php">Blood Bank</a>
    <a href="request.php">Requests</a>
    </div>
    <div class="main-content">
        <h1>Donor Management</h1>
        <table id="donorTable">
            <thead>
                <tr>
                    <th>Donor ID</th>
                    <th>Donor Name</th>
                    <th>Date of Birth</th>
                    <th>Hospital Name</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>Blood Type</th>
                    <th>Contact</th>
                    <th>Last Donation</th>
                    <th>image</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>{$row['did']}</td>
            <td>{$row['dname']}</td>
            <td>{$row['dob']}</td>
            <td>{$row['hname']}</td>
            <td>{$row['dusername']}</td>
            <td>{$row['dpassword']}</td>
            <td>{$row['btype']}</td>
            <td>{$row['contact']}</td>
            <td>{$row['lastdonation']}</td>";?>
           <td><img src= "../<?php echo $row['image'];?>" width="150" height="150"/></td>
            
            <td>
              <button class='action-btn edit-btn' onclick='openEditModal({
  id: "<?php echo $row["did"]; ?>",
  name: "<?php echo $row["dname"]; ?>",
  hospital: "<?php echo $row["hname"]; ?>",
  username: "<?php echo $row["dusername"]; ?>",
  password: "<?php echo $row["dpassword"]; ?>",
  bloodtype: "<?php echo $row["btype"]; ?>",
  contact: "<?php echo $row["contact"]; ?>"
})' data-id="<?php $row["did"];?>">Edit</button>

               <form method="POST" style="display:inline;">
    <input type="hidden" name="delete_id" value="<?php echo $row["did"]; ?>">
    <button type="submit" class="action-btn delete-btn" name="delete-btn" 
    onclick="return confirm('Are you sure?');">Delete</button>
</form>
            </td>
        </tr>
        <?php
    }
} else {
    echo "<tr><td colspan='6'>No donors found.</td></tr>";
}
$con->close();
 ?>
            </tbody>
        </table>
    </div>
    <script>

      function openEditModal(donor) {
  document.getElementById('edit_id').value = donor.id;
  document.getElementById('edit_donorname').value = donor.name;
  document.getElementById('edit_hospitalname').value = donor.hospital;
  document.getElementById('edit_username').value = donor.username;
  document.getElementById('edit_password').value = donor.password;
  document.getElementById('edit_bloodtype').value = donor.bloodtype;
  document.getElementById('edit_contact').value = donor.contact;

  document.getElementById('editModal').style.display = 'block';
  document.getElementById('modalOverlay').style.display = 'block';
}

function closeModal() {
  document.getElementById('editModal').style.display = 'none';
  document.getElementById('modalOverlay').style.display = 'none';
}
    </script>
</body>
</html>