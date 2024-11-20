<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: staff_login.php");
    exit();
}

$user = $_SESSION['user'];
$firstname = $user['firstname'];
$lastname = $user['lastname'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/side_nav.css">
    <style>
        .welcome {
            display: flex;
            align-items: center;
            font-size: 1.2em;
            margin: 20px;
            color: #fff;
        }

        .welcome i {
            margin-right: 10px;
            color: #28a745;
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
        <div class="header">
            <h1><i class="fas fa-user-circle"></i>
                <span>Welcome, <strong><?php echo htmlspecialchars($firstname); ?> <?php echo htmlspecialchars($lastname); ?></strong>!</span>
            </h1>
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