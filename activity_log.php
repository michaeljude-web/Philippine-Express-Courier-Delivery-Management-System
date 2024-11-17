<?php
session_start();
include("db_connection.php");
$statusHistorySql = "SELECT p.reference_number, s.firstname, s.lastname, ph.status, ph.changed_at 
                     FROM parcel_status_history ph 
                     JOIN parcels p ON ph.parcel_id = p.id 
                     JOIN staff s ON ph.staff_id = s.staff_id 
                     WHERE p.id = ?  -- Optionally filter by specific parcel ID
                     ORDER BY ph.changed_at DESC";



$statusHistoryStmt = $conn->prepare($statusHistorySql);

if (!$statusHistoryStmt) {
    die("Error preparing statement: " . $conn->error);
}

$statusHistoryStmt->execute();
$statusHistoryResult = $statusHistoryStmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Log</title>
    <link rel="stylesheet" href="assets/css/style.css"> <!-- Optional: Add your own CSS -->
</head>
<body>
    <div class="container">
        <h1>Activity Log</h1>
        <table>
            <thead>
                <tr>
                    <th>Reference Number</th>
                    <th>Staff Name</th>
                    <th>Status</th>
                    <th>Changed At</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($statusHistoryResult->num_rows > 0) {
                    while ($historyRow = $statusHistoryResult->fetch_assoc()) {
                        echo "<tr>
                                <td>" . htmlspecialchars($historyRow['reference_number']) . "</td>
                                <td>" . htmlspecialchars($historyRow['firstname'] . " " . $historyRow['lastname']) . "</td>
                                <td>" . htmlspecialchars($historyRow['status']) . "</td>
                                <td>" . htmlspecialchars($historyRow['changed_at']) . "</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' style='text-align:center;'>No activity logs found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
$conn->close();
?>
