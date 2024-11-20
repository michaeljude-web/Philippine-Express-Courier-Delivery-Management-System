<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- <link rel="stylesheet" href="assets/css/side_nav.css"> -->
    <!-- <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet"> -->
    <link rel="stylesheet" href="assets/font/css/all.min.css">
    <title>.</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        .track-form {
            max-width: 600px;
            margin: 0 auto;
            padding: 30px;
            border-radius: 10px;
            /* background-color: #232323; */
            /* box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); */
        }

        .track-form h1 {
            text-align: center;
            font-size: 28px;
            color: #ffcc00;
        }

        .track-form input[type="text"] {
            width: 100%;
            padding: 12px;
            font-size: 14px;
            border: 2px solid #555;
            border-radius: 8px;
            outline: none;
            margin-bottom: 20px;
            color: #ffffff;
        }

        .track-form button {
            width: 100%;
            padding: 12px;
            background-color: gray;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .parcel-info {
            margin-top: 20px;
            /* padding: 10px; */
            /* background-color: #2b2b2b; */
            /* border-radius: 10px; */
            /* box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); */
        }

        .parcel-info h3 {
            font-size: 20px;
            color: #000;
        }

        .parcel-info p {
            font-size: 15px;
            color: #000;
            line-height: 40px;
        }

        .status-box {
            display: flex;
            justify-content: space-between;
            padding: 9px;
            margin-bottom: 15px;
            border-radius: 10px;
            font-size: 16px;
            color: #555;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.2);
        }

        .status-box .status-text {
            display: flex;
            align-items: center;
        }

        .status-box .status-time {
            display: flex;
            align-items: center;
            font-size: 14px;
        }

        .print-btn {
            display: block;
            margin-top: 20px;
            padding: 12px;
            width: 100%;
            background-color: #555;
            color: whitesmoke;
            font-size: 16px;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        @media print {
            * {
                color: black;
            }

            body {
                font-family: Arial, sans-serif;
                background-color: white;
                line-height: 25px;
                position: relative;
                bottom: 120px;
            }

            .navbar {
                display: none;
            }

            .parcel-info h3 {
                color: #000;
            }

            .parcel-info p {
                color: #000;
            }

            .hamburger {
                display: none;
            }

            .main-content {
                padding: 0;
            }

            .track-form,
            .parcel-info,
            .status-box {
                box-shadow: none;
                padding: 0;
            }

            .print-btn {
                display: none;
            }

            .track-form input[type="text"],
            .track-form button {
                display: none;
            }

            .parcel-info,
            .status-box {
                background-color: #f7f7f7;
                color: black;
            }
        }
    </style>
    <script>
        function showPrintButton() {
            document.querySelector('.print-btn').style.display = 'block';
        }
    </script>
</head>

<body>


    <!-- Content -->
    <div class="content">
        <div class="main-content">
            <div class="track-form">
                <img src="assets/img/logo.png" alt="Courier Management System" style="max-width: 50%; height: auto; display: block; margin: 0 auto;">

                <form method="GET" action="tr.php" onsubmit="showPrintButton()">
                    <input type="text" name="reference_number" placeholder="Enter Parcel Reference Number" required>
                    <button type="submit">Track Parcel</button>
                </form>

                <?php
                if (isset($_GET['reference_number'])) {
                    $reference_number = $_GET['reference_number'];
                    include "db_connection.php";

                    $sql = "SELECT * FROM parcels WHERE reference_number = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("s", $reference_number);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        $parcel = $result->fetch_assoc();
                        echo "
                    <div class='parcel-info'>
                        <h3>Parcel Details</h3>
                        <p><strong>Reference Number:</strong> " . $parcel['reference_number'] . "</p>
                        <p><strong>Sender:</strong> " . $parcel['sender_name'] . "</p>
                        <p><strong>Recipient:</strong> " . $parcel['recipient_name'] . "</p>
                        <p><strong>Current Status:</strong> " . $parcel['status'] . "</p>
                        <h4>Status History:</h4>";

                        $history_sql = "SELECT * FROM parcel_status_history WHERE parcel_id = ? ORDER BY changed_at DESC";
                        $history_stmt = $conn->prepare($history_sql);
                        $history_stmt->bind_param("i", $parcel['id']);
                        $history_stmt->execute();
                        $history_result = $history_stmt->get_result();

                        if ($history_result->num_rows > 0) {
                            while ($history = $history_result->fetch_assoc()) {
                                $status_icon = "";
                                switch ($history['status']) {
                                    case 'Shipped':
                                        $status_icon = "<i class='fas fa-truck status-shipped'></i>";
                                        break;
                                    case 'In Transit':
                                        $status_icon = "<i class='fas fa-road status-transit'></i>";
                                        break;
                                    case 'Delivered':
                                        $status_icon = "<i class='fas fa-box-open status-delivered'></i>";
                                        break;
                                    case 'Out for Delivery':
                                        $status_icon = "<i class='fas fa-motorcycle status-out-delivery'></i>";
                                        break;
                                    default:
                                        $status_icon = "<i class='fas fa-question-circle'></i>";
                                }

                                echo "
                            <div class='status-box'>
                                <div class='status-text'>{$status_icon}&nbsp; <span>{$history['status']}</span></div>
                                <div class='status-time'><i class='fas fa-clock'></i>&nbsp; " . date("Y-m-d H:i:s", strtotime($history['changed_at'])) . "</div>
                            </div>";
                            }
                        } else {
                            echo "<p>No status history available.</p>";
                        }

                        echo "</div>";
                        echo "<a href='javascript:void(0)' class='print-btn' onclick='window.print()'>Print Parcel Details</a>";
                    } else {
                        echo "<p>No parcel found with the provided reference number.</p>";
                    }
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>