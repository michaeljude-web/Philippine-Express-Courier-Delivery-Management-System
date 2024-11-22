<?php
include "db_connection.php";

$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

$sql = "SELECT * FROM parcels WHERE (reference_number LIKE ? OR sender_name LIKE ? OR recipient_name LIKE ?) AND status = 'In Transit'";
$stmt = $conn->prepare($sql);
$searchTermLike = "%" . $searchTerm . "%";
$stmt->bind_param("sss", $searchTermLike, $searchTermLike, $searchTermLike);
$stmt->execute();
$result = $stmt->get_result();

$branchQuery = "SELECT * FROM branches";
$branchResult = $conn->query($branchQuery);
$branches = [];
while ($branch = $branchResult->fetch_assoc()) {
    $branches[$branch['id']] = $branch;
}

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM parcels WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        echo "<script>alert('Parcel delete successfully!');</script>";
    } else {
        echo "Error deleting parcel: " . $conn->error;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    $parcel_id = $_POST['parcel_id'];
    $new_status = $_POST['status'];
    $update_sql = "UPDATE parcels SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("si", $new_status, $parcel_id);
    if ($stmt->execute()) {
        echo "<script>alert('Parcel status updated successfully!');</script>";
    } else {
        echo "Error updating parcel status: " . $conn->error;
    }
}





$parcelsPerPage = 10;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $parcelsPerPage;

$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

$sql = "SELECT * FROM parcels WHERE (reference_number LIKE ? OR sender_name LIKE ? OR recipient_name LIKE ?) AND status = 'Arrived at Destination' LIMIT ?, ?";
$stmt = $conn->prepare($sql);
$searchTermLike = "%" . $searchTerm . "%";
$stmt->bind_param("ssssi", $searchTermLike, $searchTermLike, $searchTermLike, $offset, $parcelsPerPage);
$stmt->execute();
$result = $stmt->get_result();

$countSql = "SELECT COUNT(*) FROM parcels WHERE (reference_number LIKE ? OR sender_name LIKE ? OR recipient_name LIKE ?) AND status = 'Arrived at Destination'";
$countStmt = $conn->prepare($countSql);
$countStmt->bind_param("sss", $searchTermLike, $searchTermLike, $searchTermLike);
$countStmt->execute();
$countResult = $countStmt->get_result();
$totalParcels = $countResult->fetch_row()[0];

$totalPages = ceil($totalParcels / $parcelsPerPage);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/side_nav.css">
    <link rel="stylesheet" href="assets/css/table.css">
    <link rel="stylesheet" href="assets/css/pagination.css">
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
        <!-- <a href="#"><i class="fas fa-file-alt"></i> ActivityLog</a> -->
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
        <a href="#" onclick="confirmLogout()"class="logout"><i class="fas fa-sign-out-alt"></i><br> Logout</a>
    </div>

    <!-- Content -->
    <div class="content">
        <div class="header">
            <h1>Arrived at Destination</h1>
        </div>
        <div class="main-content">
            <form method="GET" action="">
                <input type="text" id="searchInput" name="search" placeholder="Search " value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>" style="padding: 10px; font-size: 16px; border-radius: 8px;" oninput="searchParcels()">
            </form>


            <table>
                <thead>
                    <tr>
                        <th>Reference Number</th>
                        <th>Sender Name</th>
                        <th>Recipient Name</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                <td>" . $row["reference_number"] . "</td>
                                <td>" . $row["sender_name"] . "</td>
                                <td>" . $row["recipient_name"] . "</td>
                                <td>" . $row["status"] . "</td>
                                <td class='action-icons'>
                                    <i class='fas fa-eye' onclick='viewParcel(" . $row["id"] . ")'></i>
                                    <a href='?delete_id=" . $row["id"] . "'><i class='fas fa-trash-alt'></i></a>
                                </td>
                              </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' style='text-align:center;'>No parcels found</td></tr>";
                    }
                    ?>

                </tbody>
            </table>
            <div class="pagination">
                <ul>
                    <?php
                    for ($i = 1; $i <= $totalPages; $i++) {
                        if ($i == $page) {
                            echo "<li class='active'>$i</li>";
                        } else {
                            echo "<li><a href='?page=$i&search=" . urlencode($searchTerm) . "'>$i</a></li>";
                        }
                    }
                    ?>
                </ul>
            </div>








            <div id="parcelModal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>

                    <div class="flex-container">
                        <div class="info-section">
                            <h3>Sender Information</h3>
                            <p id="sender_name"></p>
                            <p id="sender_address"></p>
                            <p id="sender_contact"></p>
                        </div>

                        <div class="info-section">
                            <h3>Recipient Information</h3>
                            <p id="recipient_name"></p>
                            <p id="recipient_address"></p>
                            <p id="recipient_contact"></p>
                        </div>
                    </div>
                    <hr>
                    <h3>Branch Processed</h3>
                    <p id="branch_processed"></p>

                    <h3>Pickup Branch</h3>
                    <p id="pickup_branch"></p>
                    <hr>
                    <h3>Parcel Details</h3>
                    <div class="parcel-details">
                        <p id="weight"></p>
                        <p id="height"></p>
                        <p id="length"></p>
                        <p id="width"></p>
                        <p id="price"></p>
                    </div>
                    <hr>
                    <h3 align="center">Status</h3>
                    <form method="POST" action="">
                        <input type="hidden" name="parcel_id" id="modal_parcel_id">
                        <select name="status" id="modal_status">
                            <option value="Item Accepted by Courier">Item Accepted by Courier</option>
                            <option value="Shipped">Shipped</option>
                            <option value="In Transit">In Transit</option>
                            <option value="Arrived at Destination">Arrived at Destination</option>
                            <option value="Out for Delivery">Out for Delivery</option>
                            <option value="Ready to Picked Up">Ready to Picked Up</option>
                            <option value="Delivered">Delivered</option>
                            <!-- <option value="Picked Up">Picked Up</option> -->
                            <option value="Unsuccessful Delivery Attempt">Unsuccessful Delivery Attempt</option>
                        </select>&nbsp;
                        <button type="submit" name="update_status" class="btn">Update Status</button>
                    </form>
                </div>
            </div>




            <script>
                function searchParcels() {
                    const searchTerm = document.getElementById("searchInput").value;

                    fetch('dashboard.php?search=' + encodeURIComponent(searchTerm))
                        .then(response => response.text())
                        .then(data => {

                            document.getElementById("parcelList").innerHTML = data;
                        })
                        .catch(error => {
                            console.error('Error during search:', error);
                        });
                }






                function viewParcel(parcelId) {
                    fetch('getParcelDetails.php?id=' + parcelId)
                        .then(response => response.json())
                        .then(data => {
                            document.getElementById("sender_name").textContent = "Name: " + data.sender_name;
                            document.getElementById("sender_address").textContent = "Address: " + data.sender_address;
                            document.getElementById("sender_contact").textContent = "Contact #: " + data.sender_contact;

                            document.getElementById("recipient_name").textContent = "Name: " + data.recipient_name;
                            document.getElementById("recipient_address").textContent = "Address: " + data.recipient_address;
                            document.getElementById("recipient_contact").textContent = "Contact #: " + data.recipient_contact;

                            document.getElementById("branch_processed").textContent = `Processed by: ${data.branch_processed_street}, ${data.branch_processed_city}, ${data.branch_processed_country}`;
                            document.getElementById("pickup_branch").textContent = `Pickup Branch: ${data.pickup_branch_street}, ${data.pickup_branch_city}, ${data.pickup_branch_country}`;

                            document.getElementById("weight").textContent = "Weight: " + data.weight;
                            document.getElementById("height").textContent = "Height: " + data.height;
                            document.getElementById("length").textContent = "Length: " + data.length;
                            document.getElementById("width").textContent = "Width: " + data.width;
                            document.getElementById("price").textContent = "Price: " + data.price;

                            document.getElementById("modal_status").value = data.status;
                            document.getElementById("modal_parcel_id").value = data.id;

                            document.getElementById("parcelModal").style.display = "block";
                        })
                        .catch(error => {
                            console.error('Error fetching parcel data:', error);
                        });
                }

                document.querySelector(".close").onclick = function() {
                    document.getElementById("parcelModal").style.display = "none";
                }
            </script>
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
                    window.location.href = "admin_login.php";
                }
            }
        </script>
</body>

</html>