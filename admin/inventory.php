<?php
require 'db.php';
$result=$con->query("SELECT * from inventory");
if(isset($_POST['disablebtn'])){
  $id=$_POST['bid'];
  $con->query("UPDATE inventory set  status='disabled' where bid='$id' ");
  header("Loction: admin/inventory.php");
}

if(isset($_POST['enablebtn'])){
  $id=$_POST['bid'];
  $con->query("UPDATE inventory set  status='enabled' where bid='$id' ");
   header("Loction: admin/inventory.php");
}
?>
<!DOCTYPE html>
<html lang="en">
  <style>
    .ebtn{
      background-color: #4CAF50;
      color: white;
      border: none;
      padding: 5px 12px;
      border-radius: 4px;
      cursor: pointer;
      
    }
    .dbtn{
      background-color: orange;
      color: white;
      border: none;
      padding: 5px 12px;
      border-radius: 4px;
      cursor: pointer;
      
    }
  </style>
<head>
  <meta charset="UTF-8">
  <title>Blood Bank Inventory</title>
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
    <h1>Manage Blood Inventory</h1>
    <button class="btn-add" onclick="openPopup('blood')">Add Blood</button>
    <table id="bloodTable">
      <thead>
        <tr>
          <th>ID</th>
        <th>Blood Type</th>
        <th>Units</th>
        <th>Action</th>
        <th>Status</th>
      </thead>
      <tbody>
        <?php
 while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>".$row['bid']."</td>
                <td>".$row['btype']."</td>
                <td>".$row['units']."</td>
               
                <td>
               
                <form method='post' style='display:inline'>
                <input type='hidden' name='bid' value='{$row['bid']}'>
                <button type='submit' name='disablebtn' class='dbtn'>Disable</button>
                </form>
                <form method='post' style='display:inline'>
                <input type='hidden' name='bid' value='{$row['bid']}'>
                <button type='submit' name='enablebtn' class='ebtn'>Enable</button>
                </form>
                </td>" ?>
                <td>
                <?php
                 if ($row['status'] == 'enabled')
                {
               echo "<span style=' color:#4CAF50;'><b>Enabled</b></span>";
                }else{
                  echo "<span style='color:#f44336;'><b>Disabled</b></span>";
                }
                
                
 }
            ?>
                </td>
                <?php "</tr>"?>
      </tbody>
    </table>
  </div>
  <div class="overlay" id="popupOverlay">
    <div class="popup">
      <h3 id="popupTitle">Add Blood Unit</h3>
      <form id="form"></form>
      <button class="btn-save" onclick="saveData()">Save</button>
      <button class="btn-cancel" onclick="closePopup()">Cancel</button>
    </div>
  </div>
  <script>
    let bloodId = 1;
function openPopup(type, row=null) {
  document.getElementById('popupOverlay').classList.add('show');
  let form = document.getElementById('form');
  form.innerHTML = `<input type="text" id="bloodtype" placeholder="Blood Type" value="${row ? row.cells[1].innerText : ''}" required>
  <input type="number" id="bloodunits" placeholder="Units" value="${row ? row.cells[2].innerText : ''}" required>
  <input type="date" id="bloodexpiry" value="${row ? row.cells[3].innerText : ''}" required>`;
  window.editRow = row;
}
function closePopup() {
  document.getElementById('popupOverlay').classList.remove('show');
}
function saveData() {
  let type = document.getElementById('bloodtype').value;
  let units = document.getElementById('bloodunits').value;
  let exp = document.getElementById('bloodexpiry').value;
  let table = document.getElementById('bloodTable').getElementsByTagName('tbody')[0];
  if (window.editRow) {
    window.editRow.cells[1].innerText = type;
    window.editRow.cells[2].innerText = units;
    window.editRow.cells[3].innerText = exp;
  } else {
    let id = ++bloodId;
    let row = table.insertRow();
    row.innerHTML = `<td>${id}</td><td>${type}</td><td>${units}</td><td>${exp}</td>
    <td>
      <button onclick="openPopup('blood',this.parentNode.parentNode)">Edit</button>
      <button onclick="this.closest('tr').remove()">Delete</button>
    </td>`;
  }
  closePopup();
}
  </script>
</body>
</html>