<?php
require 'db.php';
session_start();
if(isset($_SESSION['hname'])){

}

$sql="SELECT btype,units FROM inventory where status='enabled'";
$result=$con->query($sql);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Blood Inventory</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="https://bloodbanktoday.com/UploadData/blood-bank.jpg">
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
    </nav>
     <h1><b><?php echo $_SESSION['hname'] ?? ''; ?></b></h1>

    <div class="container">
        <h2>Blood Inventory</h2>
        <table>
            <thead>
                <tr>
                    <th>Blood Type</th>
                    <th>Units Available</th>
                </tr>
            </thead>
            <tbody id="inventoryTable">
               <?php
            if ($result->num_rows > 0) {
                // Output data for each row
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                    <td>" . htmlspecialchars($row['btype']) . "</td>
                    <td>" . htmlspecialchars($row['units']) . "</td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='2'>No inventory found</td></tr>";
            }
            $con->close();
            ?>
            </tbody>
        </table>
    </div>

    <script src="script.js"></script>
</body>

</html>