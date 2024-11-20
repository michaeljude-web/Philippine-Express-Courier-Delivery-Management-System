<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sender_name = mysqli_real_escape_string($conn, $_POST['sender_name']);
    $sender_address = mysqli_real_escape_string($conn, $_POST['sender_address']);
    $sender_contact = mysqli_real_escape_string($conn, $_POST['sender_contact']);
    $recipient_name = mysqli_real_escape_string($conn, $_POST['recipient_name']);
    $recipient_address = mysqli_real_escape_string($conn, $_POST['recipient_address']);
    $recipient_contact = mysqli_real_escape_string($conn, $_POST['recipient_contact']);
    $from_branch_id = mysqli_real_escape_string($conn, $_POST['from_branch_id']);
    $to_branch_id = mysqli_real_escape_string($conn, $_POST['to_branch_id']);
    $weight = mysqli_real_escape_string($conn, $_POST['weight']);
    $height = mysqli_real_escape_string($conn, $_POST['height']);
    $length = mysqli_real_escape_string($conn, $_POST['length']);
    $width = mysqli_real_escape_string($conn, $_POST['width']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $type = '1';
    $status = 'Pending';

    $reference_number = 'REF-' . strtoupper(uniqid());

    $sql = "INSERT INTO parcels (reference_number, sender_name, sender_address, sender_contact, 
                                  recipient_name, recipient_address, recipient_contact, type, 
                                  from_branch_id, to_branch_id, weight, height, width, length, 
                                  price, status) 
            VALUES ('$reference_number', '$sender_name', '$sender_address', '$sender_contact', 
                    '$recipient_name', '$recipient_address', '$recipient_contact', '$type', 
                    '$from_branch_id', '$to_branch_id', '$weight', '$height', 
                    '$width', '$length', '$price', '$status')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Parcel added successfully! Reference Number: $reference_number');</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
    }
}

$branchQuery = "SELECT id, CONCAT(street, ', ', city, ', ', country) AS address FROM branches";
$branchesResult = mysqli_query($conn, $branchQuery);
$branches = mysqli_fetch_all($branchesResult, MYSQLI_ASSOC);
?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/side_nav.css">
    <style>
        .main-content {
            flex-grow: 1;
            padding: 20px;
            color: #e0e0e0;
            overflow-y: auto;
            height: 100%;
            /* background-color: #121212;  */
        }


        input,
        select {
            width: 60%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #555;
            background-color: #333;
            color: #fff;
            font-size: 14px;
            transition: border-color 0.3s, background-color 0.3s;
        }


        button {
            background-color: #555;
            color: white;
            padding: 12px;
            width: 95%;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }



        h2 {
            font-size: 18px;
            margin-top: 20px;
            margin-bottom: 10px;
            color: #f8a200;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
        }

        label {
            font-size: 12px;
            margin-bottom: 5px;
            display: block;
            color: #e0e0e0;
        }

        .info-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .info-section {
            flex: 1;
            margin-right: 15px;
            padding: 15px;
            border-radius: 8px;
        }

        .info-section:last-child {
            margin-right: 0;
        }

        .parcel-details {
            margin-top: 20px;
        }

        .parcel-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .parcel-section {
            flex: 1;
            margin-right: 15px;
            padding: 15px;
            border-radius: 8px;
            /* background: #1a1a1a;   */
            /* box-shadow: 0 3px 8px rgba(0, 0, 0, 0.4);   */
        }

        .parcel-section:last-child {
            margin-right: 0;
        }


        button:hover,
        input:hover,
        select:hover {
            background-color: #444444;
            border-color: #f8a200;
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 15px;
            }

            input,
            select {
                width: 95%;
                font-size: 13px;
            }

            button {
                font-size: 14px;
            }

            h2 {
                font-size: 16px;
            }

            .info-container {
                flex-direction: column;
            }

            .parcel-container {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>

    <div class="hamburger" id="hamburger">
        <i class="fas fa-bars"></i>
    </div>

  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
        <h2>Phil<span>Express</span></h2>
        <a href="staff_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <div class="dropdown">
            <a href="#" class="dropdown-toggle"><i class="fas fa-box"></i> Parcels</a>
            <ul class="dropdown-menu">
                <li><a href="staff_parcel_list.php">Parcel List</a></li>
                <li><a href="staff_parcel_add.php">Add Parcel</a></li>
                <li><a href="staff_accepted.php">Accepted by Courier</a></li>
                <li><a href="staff_shipped.php">Shipped</a></li>
                <li><a href="staff_in_transit.php">In Transit</a></li>
                <li><a href="staff_arrived_at_destination.php">Arrived At Destination</a></li>
                <li><a href="staff_out_for_delivery.php">Out for Delivery</a></li>
                <li><a href="Staff_ready_to_pickup.php">Ready to Pickup</a></li>
                <li><a href="staff_delivered.php">Delivered</a></li>
                <li><a href="Staff_delivery_failed.php">Delivery Failed</a></li>
            </ul>
        </div>
        <a href="staff_track.php"><i class="fas fa-location"></i> TrackParcel</a>
        <div class="logout" onclick="confirmLogout()"><i class="fas fa-sign-out-alt"></i> Logout</div>
    </div>

    <!-- Navbar (For Mobile) -->
    <div class="navbar">
        <a href="staff_dashboard.php"><i class="fas fa-tachometer-alt"></i><br> Dashboard</a>
        <div class="dropdown">
            <a href="#" class="dropdown-toggle"><i class="fas fa-box"></i><br> Parcels</a>
            <ul class="dropdown-menu" id="navbar_dropdown">
                <li><a href="staff_parcel_list.php">Parcel List</a></li>
                <li><a href="staff_parcel_add.php">Add Parcel</a></li>
                <li><a href="staff_accepted.php">Accepted by Courier</a></li>
                <li><a href="staff_shipped.php">Shipped</a></li>
                <li><a href="staff_in_transit.php">In Transit</a></li>
                <li><a href="staff_arrived_at_destination.php">Arrived At Destination</a></li>
                <li><a href="staff_out_for_delivery.php">Out for Delivery</a></li>
                <li><a href="Staff_ready_to_pickup.php">Ready to Pickup</a></li>
                <li><a href="staff_delivered.php">Delivered</a></li>
                <li><a href="Staff_delivery_failed.php">Delivery Failed</a></li>
            </ul>
        </div>
        <a href="staff_track.php"><i class="fas fa-location"></i><br> TrackParcel</a>
        <a href="#" onclick="confirmLogout()" class="logout"><i class="fas fa-sign-out-alt"></i><br> Logout</a>
    </div>

    <!-- Content -->
    <div class="content">
        <div class="main-content">
            <!-- <h1>Add Parcel</h1> -->
            <form method="POST" action="">
                <div class="info-container">
                    <div class="info-section">
                        <h2>Sender Information</h2>
                        <label>Name:</label>
                        <input type="text" name="sender_name" required>
                        <label>Address:</label>
                        <input type="text" name="sender_address" required>
                        <label>Contact #:</label>
                        <input type="text" name="sender_contact" required>
                    </div>
                    <div class="info-section">
                        <h2>Recipient Information</h2>
                        <label>Name:</label>
                        <input type="text" name="recipient_name" required>
                        <label>Address:</label>
                        <input type="text" name="recipient_address" required>
                        <label>Contact #:</label>
                        <input type="text" name="recipient_contact" required>
                    </div>
                </div>

                <div class="parcel-container">
                    <div class="parcel-section">
                        <h2>Branch Processed</h2>
                        <select name="from_branch_id" required>
                            <option value="">Select Branch</option>
                            <?php foreach ($branches as $branch): ?>
                                <option value="<?= $branch['id'] ?>"><?= htmlspecialchars($branch['address']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="parcel-section">
                        <h2>Pickup Branch</h2>
                        <select name="to_branch_id" required>
                            <option value="">Select Branch</option>
                            <?php foreach ($branches as $branch): ?>
                                <option value="<?= $branch['id'] ?>"><?= htmlspecialchars($branch['address']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="parcel-details">
                    <h2>Parcel Details</h2>
                    <div class="parcel-container">
                        <div class="parcel-section">
                            <label>Weight:</label>
                            <input type="number" name="weight" required>
                        </div>
                        <div class="parcel-section">
                            <label>Height:</label>
                            <input type="number" name="height" required>
                        </div>
                        <div class="parcel-section">
                            <label>Length:</label>
                            <input type="number" name="length" required>
                        </div>
                        <div class="parcel-section">
                            <label>Width:</label>
                            <input type="number" name="width" required>
                        </div>
                        <div class="parcel-section">
                            <label>Price:</label>
                            <input type="number" name="price" required>
                        </div>
                    </div>
                </div>

                <button type="submit">Add Parcel</button>
            </form>
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