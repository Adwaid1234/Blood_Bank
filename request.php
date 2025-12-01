<?php
require 'db.php';
session_start();
//ini_set('display_errors',1);
//ini_set('display_startup_errors',1);
//error_reporting(E_ALL);
if(!isset($_SESSION['hid'])){
header("Location: login.php");
}
 $hid=$_SESSION['hid'];
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bloodtype = $_POST['bloodtype'];
     $units = $_POST['units'];

    $stmt = $con->prepare("INSERT INTO request (hid, btype, units, status) VALUES (?, ?, ?, 'pending')");

if (!$stmt) {
    die("Prepare failed: " . $con->error);
}
$stmt->bind_param("isi", $hid, $bloodtype, $units);
if ($stmt->execute()) {
    echo "<script>alert('Blood request submitted successfully!');</script>";
} else {
    echo "<script>alert('Blood request failed: " . $stmt->error . "');</script>";
}

$stmt->close();

}
// Fetch previous requests
$query = "SELECT btype, units, date, status FROM request WHERE hid = ?";
$stmt = $con->prepare($query);
if (!$stmt) {
    die("Prepare failed: " . $con->error);
}
$stmt->bind_param("i", $hid);
if(!$stmt->execute()){
    die("execute failed:".$stmt->error);
}
 $stmt->bind_result($btype,$units,$date,$status);


?>
<!DOCTYPE html>
<html>

<head>
    <title>Blood Request</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="https://bloodbanktoday.com/UploadData/blood-bank.jpg">
    <style>
        
        table {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            color: black;
            border: 1px solid #ccc;
        }

        th,
        td {
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body style="background-image: url('images/bloodwall.webp');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;">

    <nav>
        <ul>
            <li><a href="hospital.php">Home</a></li>
            <li><a href="d-login.php">Donor</a></li>
             <li><a href="inventory.php">Inventory</a></li>
            <li><a href="request.php">Request</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
        </ul>
    </nav>
     <h1><b><?php echo $_SESSION['hname']; ?></b></h1>

    <div class="container" style=" width:500px;
    height:250px;">
        <h2>Request Blood</h2>
        <form id="requestForm" action="" method="POST" >
            <input type="hidden" name="rid" id="">
          <select name="bloodtype" required>
            <option value="" id="bloodType" >Select Blood Type</option>
            <option value="A+" >A+</option>
            <option value="A-" >A-</option>
            <option value="B+">B+</option>
            <option value="B-">B-</option>
            <option value="O+">O+</option>
            <option value="O-">O-</option>
            <option value="AB+">AB+</option>
            <option value="AB-">AB-</option>
        </select>
            <input type="number" placeholder="Units Required" name="units" required>
            <button type="submit" id="" name="requestbtn">Request Blood</button>
        </form>
    </div>
     <table id="requestsTable" style="background-color: white;">
            <thead>
                <tr>
                    <th>Blood Type</th>
                    <th>Units Requested</th>
                    <th>Date of Request</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($stmt->fetch()) {
                echo "<tr>";
                echo "<td>$btype</td>";
                echo "<td>$units</td>";
                echo "<td>$date</td>";
                if ($status === 'pending') {
        $color= 'orange';
    } elseif ($status === 'approved') {
        $color='green';
    } 
    else{
        $color='red';
    }
    echo "<td style='color:$color;'><b>$status</b></td>";

    echo "</tr>";
}

                ?>
            </tbody>
        </table>
        <script>
        const form = document.getElementById('requestForm');
        const tableBody = document.querySelector('#requestsTable tbody');

        form.addEventListener('submit', function (e) {
           // e.preventDefault();

            const bloodType = document.getElementById('bloodType').value;
            const units = document.getElementById('units').value;

            if (!hospitalId || !bloodType || !units) {
                alert("Please fill all fields.");
                return;
            }

            // Create new row and cells
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>${hospitalId}</td>
                <td>${bloodType}</td>
                <td>${units}</td>
            `;

            // Append to table
            tableBody.appendChild(newRow);

            // Reset form
            form.reset();
        });
    </script>

    <script src="script.js"></script>
</body>

</html>