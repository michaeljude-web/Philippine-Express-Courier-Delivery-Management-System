<?php
session_start(); // Siguraduhing simulan ang session

// I-check kung ang user ay naka-log in
if (!isset($_SESSION['user'])) {
    // Kung hindi pa naka-log in, i-redirect sa login page
    header("Location: staff_login.php");
    exit();
}

// Kunin ang user data mula sa session
$user = $_SESSION['user']; // Halimbawa, ang session variable ay 'user'
$firstname = $user['firstname']; // Siguraduhing na-assign ang mga value
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
            color: #28a745; /* Customize icon color */
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
        <a href="activity_log.php"><i class="fas fa-file-alt"></i> ActivityLog</a>
        <div class="logout" onclick="confirmLogout()"><i class="fas fa-sign-out-alt"></i> Logout</div>
    </div>

    <!-- Navbar (For Mobile) -->
    <div class="navbar">
        <a href="#"><i class="fas fa-tachometer-alt"></i><br> Dashboard</a>
        <a href="#"><i class="fas fa-code-branch"></i><br> Branches</a>
        <a href="#"><i class="fas fa-users"></i><br> Staff</a>
        <div class="dropdown">
            <a href="#" class="dropdown-toggle"><i class="fas fa-box"></i><br> Parcels</a>
            <ul class="dropdown-menu" id="navbar_dropdown">
                <li><a href="#">Parcel List</a></li>
                <li><a href="#">Add Parcel</a></li>
                <li><a href="#">Accepted by Courier</a></li>
                <li><a href="#">Shipped</a></li>
                <li><a href="#">In Transit</a></li>
                <li><a href="#">Arrived At Destination</a></li>
                <li><a href="#">Out for Delivery</a></li>
                <li><a href="#">Ready to Pickup</a></li>
                <li><a href="#">Delivered</a></li>
                <li><a href="#">Delivery Failed</a></li>
            </ul>
        </div>
        <a href="#"><i class="fas fa-location"></i><br> TrackParcel</a>
        <a href="#"><i class="fas fa-file-alt"></i><br> ActivityLog</a>
        <a href="#" class="logout"><i class="fas fa-sign-out-alt"></i><br> Logout</a>
    </div>

    <!-- Content -->
    <div class="content">
        <div class="header">
            <h1><i class="fas fa-user-circle"></i>
            <span>Welcome, <strong><?php echo htmlspecialchars($firstname); ?> <?php echo htmlspecialchars($lastname); ?></strong>!</span>
        </h1>
        <a href="staff_accepted.php">dd</a>
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
