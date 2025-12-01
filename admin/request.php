<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['rid'])) {
    $id = intval($_POST['rid']);
    $action = $_POST['action'];
    $success = false;
    $flag='';
   

    if ($action === 'approve') {
        $btype = $_POST['btype'];
        $unit = intval($_POST['units']);

        // Step 1: Subtract from inventory if enough units exist
        $stmt = $con->prepare("UPDATE inventory SET units = units - ? WHERE btype = ? AND units >= ?");
        $stmt->bind_param("isi", $unit, $btype, $unit);
        $flag='true';
        $stmt->execute();



        if ($flag == 'true') {

            // Step 2: Mark request as approved
            
            $stmt2 = $con->prepare("UPDATE request SET status ='Approved' WHERE rid = ?");
            $stmt2->bind_param("i", $id);
            $stmt2->execute();
            $stmt2->close();

            echo "<script>alert('Request approved and inventory updated.');</script>";
            $success = true;
        } else {
            echo "<script>alert('Not enough units available in inventory.');</script>";
        }
        $stmt->close();

    } elseif ($action === 'reject') {
        // Reject the request
        $stmt = $con->prepare("UPDATE request SET status ='Rejected' WHERE rid = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        echo "<script>alert('Request rejected.');</script>";
        $success = true;
    }

    if ($success) {
        echo '<script>window.location.href="request.php";</script>';
        exit;
    }
}

// Fetch all requests with hospital info
$res = $con->query("
    SELECT r.rid, r.hid, h.hname, r.btype, r.units, r.date, r.status
    FROM request r
    LEFT JOIN hospital h ON r.hid = h.hid
");

$rows = [];
if ($res === false) {
    echo '<div style="color:red;">Query Error: ' . $con->error . '</div>';
} else {
    while ($row = $res->fetch_assoc()) {
        $rows[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Requests</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .status.Approved { color: green; font-weight: bold; }
        .status.Rejected { color: red; font-weight: bold; }
         .status.pending { color: orange; font-weight: bold; }
    </style>
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
    <h1>Manage Requests</h1>
    <form method="POST" action="">
        <table id="requestTable">
            <thead>

                <tr>
                    <th>ID</th>
                    <th>Hospital ID</th>
                     <th>Hospital Name</th>
                    <th>Blood Type</th>
                    <th>Units</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($rows as $req): ?>
                <tr>
                    <td><?= htmlspecialchars($req['rid']) ?></td>
                    <td><?= htmlspecialchars($req['hid']) ?></td>
                     <td><?= htmlspecialchars($req['hname']) ?></td>
                    <td><?= htmlspecialchars($req['btype']) ?></td>
                    <td><?= htmlspecialchars($req['units']) ?></td>
                    <td><?= htmlspecialchars($req['date']) ?></td>
                    <td class="status <?= htmlspecialchars($req['status']) ?>">
                        <?= ucfirst(htmlspecialchars($req['status'])) ?></td>
                    <td>
                         <?php if ($req['status'] == 'pending'): ?>
<form method="POST" action="request.php" style="display:inline;">
    <input type="hidden" name="rid" value="<?php echo htmlspecialchars($req['rid']); ?>">
    <input type="hidden" name="btype" value="<?php echo htmlspecialchars($req['btype']); ?>">
    <input type="hidden" name="units" value="<?php echo htmlspecialchars($req['units']); ?>">
    <button type="submit" name="action" value="approve" class="btn-approve">Approve</button>
    <button type="submit" name="action" value="reject" class="btn-reject">Reject</button>
    <?php echo "<!-- RID debug: " . htmlspecialchars($req['rid']) . " -->"; ?>

</form>
<?php endif; ?>
                    </td>
                    
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        
</div>
</body>
</html>