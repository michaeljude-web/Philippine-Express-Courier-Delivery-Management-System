<?php
session_start();

// Database connection settings
$host = 'localhost';
$username = 'root';  // Your MySQL username
$password = '';      // Your MySQL password
$dbname = 'project_im'; // Your database name

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



// Handle Parcel Tracking
$parcel_info = null;
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['reference_number'])) {
    $reference_number = $_GET['reference_number'];

    // Fetch parcel details from the database based on the reference number
    $sql = "SELECT * FROM parcels WHERE reference_number = '$reference_number'";
    $result = $conn->query($sql);
    $parcel_info = $result->fetch_assoc();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Philippine Express</title>
</head>
<body>



<!-- Main Content -->
<div class="container">
    <div class="track-form" style="max-width: 600px; margin: 0 auto; padding: 30px; text-align: center;">
        <h1>Track Your Parcel</h1>
        
        <form method="GET" style="text-align: center;">
            <input type="text" name="reference_number" placeholder="Enter Parcel Reference Number" required style="padding: 12px; width: 80%; margin-bottom: 20px;">
            <button type="submit" style="padding: 12px; width: 80%; background-color: #ffcc00; color: black; border: none; border-radius: 8px; cursor: pointer;">Track Parcel</button>
        </form>

        <?php if ($parcel_info): ?>
            <div class="parcel-info" style="margin-top: 20px; padding: 20px; text-align: left;">
                <h3>Parcel Details</h3>
                <p><strong>Reference Number:</strong> <?php echo $parcel_info['reference_number']; ?></p>
                <p><strong>Sender:</strong> <?php echo $parcel_info['sender_name']; ?></p>
                <p><strong>Recipient:</strong> <?php echo $parcel_info['recipient_name']; ?></p>
                <p><strong>Current Status:</strong> <?php echo $parcel_info['status']; ?></p>

                <h4>Status History:</h4>
                <?php
                    $history_sql = "SELECT * FROM parcel_status_history WHERE parcel_id = ? ORDER BY changed_at DESC";
                    $history_stmt = $conn->prepare($history_sql);
                    $history_stmt->bind_param("i", $parcel_info['id']);
                    $history_stmt->execute();
                    $history_result = $history_stmt->get_result();

                    if ($history_result->num_rows > 0) {
                        while ($history = $history_result->fetch_assoc()) {
                            $status_icon = "<i class='fas fa-question-circle'></i>";  // Default icon
                            switch ($history['status']) {
                                case 'Shipped':
                                    $status_icon = "<i class='fas fa-truck'></i>";
                                    break;
                                case 'In Transit':
                                    $status_icon = "<i class='fas fa-road'></i>";
                                    break;
                                case 'Delivered':
                                    $status_icon = "<i class='fas fa-box-open'></i>";
                                    break;
                                case 'Out for Delivery':
                                    $status_icon = "<i class='fas fa-motorcycle'></i>";
                                    break;
                            }
                            echo "
                            <div class='status-box' style='display: flex; justify-content: space-between; padding: 9px; margin-bottom: 15px; border-radius: 10px;'>
                                <div class='status-text'>{$status_icon} {$history['status']}</div>
                                <div class='status-time'>{$history['changed_at']}</div>
                            </div>";
                        }
                    }
                ?>
            </div>
        <?php endif; ?>
    </div>
</div>


</body>
</html>
