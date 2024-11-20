<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/side_nav.css">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/font/css/all.min.css">
    <title>.</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        /* 
        body {
            display: flex;
            background-color: #f9f3f3;
        } */

        .main-content {
            flex-grow: 1;
            color: #ffffff;
            line-height: 35px;
            padding: 40px;
            background-color: #191919;
        }

        .track-form {
            max-width: 600px;
            margin: 0 auto;
            padding: 30px;
            border-radius: 10px;
            background-color: #232323;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
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
            border: 2px solid #ffcc00;
            border-radius: 8px;
            outline: none;
            margin-bottom: 20px;
            background-color: #333;
            color: #ffffff;
        }

        .track-form button {
            width: 100%;
            padding: 12px;
            background-color: #ffcc00;
            color: black;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .track-form button:hover {
            background-color: #e6b800;
        }

        .parcel-info {
            margin-top: 20px;
            padding: 20px;
            background-color: #2b2b2b;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        .parcel-info h3 {
            font-size: 20px;
            color: #ffcc00;
        }

        .parcel-info p {
            font-size: 15px;
            color: #ffffff;
        }

        .status-box {
            display: flex;
            justify-content: space-between;
            background-color: #333;
            padding: 9px;
            margin-bottom: 15px;
            border-radius: 10px;
            font-size: 16px;
            color: #ffcc00;
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
            background-color: #ffcc00;
            color: black;
            font-size: 16px;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .print-btn:hover {
            background-color: #e6b800;
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

            /* .status-box{
        display: none;
    } */
        }
    </style>
    <script>
        function showPrintButton() {
            document.querySelector('.print-btn').style.display = 'block';
        }
    </script>
</head>

<body>

    <div class="hamburger" id="hamburger">
        <i class="fas fa-bars"></i>
    </div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <h2>Phil<span>Express</span></h2>
        <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="branches.php"><i class="fas fa-code-branch"></i> Branches</a>
        <a href="staff.php"><i class="fas fa-users"></i> Staff</a>
        <div class="dropdown">
            <a href="#" class="dropdown-toggle"><i class="fas fa-box"></i> Parcels</a>
            <ul class="dropdown-menu">
                <li><a href="parcel_list.php">Parcel List</a></li>
                <li><a href="parcel_add.php">Add Parcel</a></li>
                <li><a href="accepted.php">Accepted by Courier</a></li>
                <li><a href="shipped.php">Shipped</a></li>
                <li><a href="in_transit.php">In Transit</a></li>
                <li><a href="arrived_at_destination.php">Arrived At Destination</a></li>
                <li><a href="out_for_delivery.php">Out for Delivery</a></li>
                <li><a href="ready_to_pickup.php">Ready to Pickup</a></li>
                <li><a href="delivered.php">Delivered</a></li>
                <li><a href="delivery_failed.php">Delivery Failed</a></li>
            </ul>
        </div>
        <a href="track.php"><i class="fas fa-location"></i> TrackParcel</a>
        <!-- <a href="activity_log.php"><i class="fas fa-file-alt"></i> ActivityLog</a> -->
        <div class="logout" onclick="confirmLogout()"><i class="fas fa-sign-out-alt"></i> Logout</div>
    </div>

    <!-- Navbar (For Mobile) -->
    <div class="navbar">
        <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i><br> Dashboard</a>
        <a href="branches.php"><i class="fas fa-code-branch"></i><br> Branches</a>
        <a href="staff.php"><i class="fas fa-users"></i><br> Staff</a>
        <div class="dropdown">
            <a href="#" class="dropdown-toggle"><i class="fas fa-box"></i><br> Parcels</a>
            <ul class="dropdown-menu" id="navbar_dropdown">
                <li><a href="parcel_list.php">Parcel List</a></li>
                <li><a href="parcel_add.php">Add Parcel</a></li>
                <li><a href="accepted.php">Accepted by Courier</a></li>
                <li><a href="shipped.php">Shipped</a></li>
                <li><a href="in_transit.php">In Transit</a></li>
                <li><a href="arrived_at_destination.php">Arrived At Destination</a></li>
                <li><a href="out_for_delivery.php">Out for Delivery</a></li>
                <li><a href="ready_to_pickup.php">Ready to Pickup</a></li>
                <li><a href="delivered.php">Delivered</a></li>
                <li><a href="delivery_failed.php">Delivery Failed</a></li>
            </ul>
        </div>
        <a href="track.php"><i class="fas fa-location"></i><br> TrackParcel</a>
        <!-- <a href="#"><i class="fas fa-file-alt"></i><br> ActivityLog</a> -->
        <a href="#" onclick="confirmLogout()" class="logout"><i class="fas fa-sign-out-alt"></i><br> Logout</a>
    </div>

    <!-- Content -->
    <div class="content">
        <div class="main-content">
            <div class="track-form">
                <img src="assets/img/logo.png" alt="Courier Management System" style="max-width: 50%; height: auto; display: block; margin: 0 auto;">

                <form method="GET" action="track.php" onsubmit="showPrintButton()">
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

    <script>
        const hamburger = document.getElementById("hamburger");
        const sidebar = document.getElementById("sidebar");

        hamburger.addEventListener("click", () => {
            sidebar.classList.toggle("active");
        });

        const dropdownToggles = document.querySelectorAll(".dropdown-toggle");
        dropdownToggles.forEach((toggle) => {
            toggle.nextElementSibling.style.display = "none";
            toggle.addEventListener("click", () => {
                toggle.nextElementSibling.style.display = toggle.nextElementSibling.style.display === "block" ? "none" : "block";
            });
        });

        function confirmLogout() {
            const confirmAction = confirm("Are you sure you want to logout?");
            if (confirmAction) {
                window.location.href = "logout.php";
            }
        }
    </script>
</body>

</html>