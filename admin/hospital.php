<?php
require 'db.php';
if (isset($_POST['edit_id'])) {
    $id = intval($_POST['edit_id']);
    $hospitalname = $con->real_escape_string($_POST['edit_hospitalname']);
    $username = $con->real_escape_string($_POST['edit_username']);
    $contact= $con->real_escape_string($_POST['edit_contact']);
     $address= $con->real_escape_string($_POST['edit_address']);
      $password= $con->real_escape_string($_POST['edit_password']);
    $con->query("UPDATE hospital SET hname='$hospitalname', username='$username', contact='$contact', address='$address', password='$password WHERE hid=$id");
    echo "<script>window.location='".$_SERVER['PHP_SELF']."';</script>";
    exit;
}

// Handle Delete
if (isset($_POST['delete_id'])) {
    $delete_id = intval($_POST['delete_id']);
    $con->query("DELETE FROM hospital WHERE hid=$delete_id");
    echo "<script>window.location='".$_SERVER['PHP_SELF']."';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Hospitals</title>
  <link rel="stylesheet" href="style.css">
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
    <h1>Manage Hospitals</h1>
    <button class="btn-add" onclick="openPopup('hospital')">Add Hospital</button>
    <table id="userTable">
      <thead>
        <tr><th>ID</th>
        <th>USERNAME</th>
        <th>HOSPITAL NAME</th>
        <th>ADDRESS</th>
        <th>CONTACT</th>
        <th>PASSWORD</th>
        <th>ACTION</th>
      </tr>
      </thead>
      <tbody>
         <?php
        $result = $con->query("SELECT * FROM hospital ");
    
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>".$row['hid']."</td>
                <td>".$row['username']."</td>
                <td>".$row['hname']."</td>
                <td>".$row['address']."</td>
                 <td>".$row['contact']."</td>
                  <td>".$row['password']."</td>
                <td>"?>
                    <button class='green-btn'
                    onclick="openEditModal(<?= $row['hid'] ?>, 
                    '<?= htmlspecialchars(addslashes($row['username'])) ?>', 
                    '<?= htmlspecialchars(addslashes($row['hname'])) ?>',
                     '<?= htmlspecialchars(addslashes($row['contact'])) ?>',
                     '<?= htmlspecialchars(addslashes($row['password'])) ?>',
                     '<?= htmlspecialchars(addslashes($row['address'])) ?>')"
                >Edit</button>
                <form method="post" style="display:inline;" onsubmit="return confirm('Delete this hospital?');">
                    <input type="hidden" name="delete_id" value="<?= $row['hid'] ?>">
                    <br><button type="submit" class="red-btn">Delete</button>
                </form>
               <?php "</td>
            </tr>";
        }
    $con->close();
        ?>
      </tbody>
    </table>
  </div>

   <div id="editHospitalModal" class="modal">
        <div class="modal-content">
            <span class="modal-close" onclick="closeEditModal()">&times;</span>
            <form method="post" action="">
                <input type="hidden" name="edit_id" id="modal_id">
                <label>Username:</label><br>
                <input type="text" name="edit_username" id="modal_username" required><br>
                  <label>Password</label><br>
                <input type="text" name="edit_password" id="modal_password" required><br>
                <label>hospital Name:</label><br>
                <input type="text" name="edit_hospitalname" id="modal_hospitalname" required><br>
                 <label>Address</label><br>
                <input type="text" name="edit_address" id="modal_address" required><br>
                <label>Contact:</label><br>
                <input type="text" name="edit_contact" id="modal_contact" required><br><br>
                <button type="submit" class="green-btn">Save Changes</button>
            </form>
        </div>
    </div>

 

  <div class="overlay" id="popupOverlay">
    <div class="popup">
      <h3 id="popupTitle">Add Hospital</h3>
      <form id="form"></form>
      <button class="btn-save" onclick="saveData()">Save</button>
      <button class="btn-cancel" onclick="closePopup()">Cancel</button>
    </div>
  </div>
  <script>
    function openEditModal(id, username, hospitalname,contact,password,address) {
       
      document.getElementById('modal_id').value = id;
    document.getElementById('modal_username').value = username;
    document.getElementById('modal_hospitalname').value = hospitalname;
    document.getElementById('modal_contact').value = contact;
    document.getElementById('modal_password').value = password;
    document.getElementById('modal_address').value = address;
    document.getElementById('editHospitalModal').style.display = 'block';
    }
    function closeEditModal() {
        document.getElementById('editHospitalModal').style.display = 'none';
    }
    window.onclick = function(event) {
        var modal = document.getElementById('editHospitalModal');
        if (event.target == modal) modal.style.display = "none";
    }


    let userId = 1;
function openPopup(type, row=null) {
  document.getElementById('popupOverlay').classList.add('show');
  let form = document.getElementById('form');
  form.innerHTML = `<input type="text" id="username" placeholder="Name" value="${row ? row.cells[1].innerText : ''}" required><br><br>
  <input type="text" id="userhosname" placeholder="hospitalname" value="${row ? row.cells[2].innerText : ''}" required><br><br>
  <input type="text" id="usercon" placeholder="contact" value="${row ? row.cells[3].innerText : ''}" required><br><br>
  <input type="text" id="userpass" placeholder="password" value="${row ? row.cells[4].innerText : ''}" required><br><br>
  <input type="text" id="useraddr" placeholder="address" value="${row ? row.cells[5].innerText : ''}" required>`;
  window.editRow = row;
}
function closePopup() {
  document.getElementById('popupOverlay').classList.remove('show');
}
function saveData() {
  let name = document.getElementById('username').value;
  let hosname = document.getElementById('userhosname').value;
  let con = document.getElementById('usercon').value;
   let pass = document.getElementById('userpass').value;
   let addr = document.getElementById('useraddr').value;
  let table = document.getElementById('userTable').getElementsByTagName('tbody')[0];
  if (window.editRow) {
    window.editRow.cells[1].innerText = name;
    window.editRow.cells[2].innerText = hosname;
    window.editRow.cells[3].innerText = con;
    window.editRow.cells[4].innerText = pass;
    window.editRow.cells[5].innerText = addr;
  
  } else {
    let id = ++userId;
    let row = table.insertRow();
    row.innerHTML = `<td>${id}</td>
    <td>${name}</td>
    <td>${hosname}</td>
    <td>${con}</td>
    <td>${pass}</td>
    <td>${addr}</td>
    <td>
      <button onclick="openPopup('hospital',this.parentNode.parentNode)">Edit</button>
      <button onclick="this.closest('tr').remove()">Delete</button>
    </td>`;
  }
  closePopup();
}
  </script>
</body>
</html>