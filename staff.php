<?php
include("db_connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_staff'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $branch_id = $_POST['branch_id'];

    $sql = "INSERT INTO staff (firstname, lastname, email, password, branch_id) VALUES ('$firstname', '$lastname', '$email', '$password', '$branch_id')";
    $conn->query($sql);
}

if (isset($_POST['delete_staff'])) {
    $staff_id = $_POST['staff_id'];
    $sql = "DELETE FROM staff WHERE staff_id = $staff_id";
    $conn->query($sql);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_staff'])) {
    $staff_id = $_POST['staff_id'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $branch_id = $_POST['branch_id'];

    $sql = "UPDATE staff SET firstname='$firstname', lastname='$lastname', email='$email', branch_id='$branch_id' WHERE staff_id=$staff_id";
    $conn->query($sql);
}

$search = isset($_GET['search']) ? $_GET['search'] : '';
$sql = "SELECT s.*, b.branch_code, b.street, b.city, b.zip_code, b.country 
        FROM staff s 
        JOIN branches b ON s.branch_id = b.id 
        WHERE s.firstname LIKE '%$search%' OR s.lastname LIKE '%$search%' OR s.email LIKE '%$search%'";
$result = $conn->query($sql);

$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$countResult = $conn->query("SELECT COUNT(*) as total FROM staff WHERE firstname LIKE '%$search%' OR lastname LIKE '%$search%' OR email LIKE '%$search%'");
$totalCount = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalCount / $limit);

$sql = "SELECT s.*, b.branch_code, b.street, b.city, b.zip_code, b.country 
        FROM staff s 
        JOIN branches b ON s.branch_id = b.id 
        WHERE s.firstname LIKE '%$search%' OR s.lastname LIKE '%$search%' OR s.email LIKE '%$search%' 
        LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/side_nav.css">
    <link rel="stylesheet" href="assets/css/pagination.css">
    <style>
        .container {
            width: 80%;
            margin: auto;
        }

        .btn {
            padding: 10px 15px;
            margin: 5px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-success {
            background-color: #555;
            color: #fff;
        }

        .btn-warning {
            background-color: #777;
            color: #fff;
        }

        .btn-danger {
            background-color: #a33;
            color: #fff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #333;
        }

        th,
        td {
            text-align: center;
            padding: 10px;
            border: 1px solid #666;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
            border: 1px solid #666;
        }

        th {
            background-color: #444;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
        }

        .modal-content {
            background-color: #222;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 20%;
            border-radius: 10px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: #fff;
            text-decoration: none;
            cursor: pointer;
        }

        input {
            width: 90%;
            padding: 10px;
            margin: 5px 0;
            border: none;
            border-radius: 5px;
            background-color: #333;
            color: #fff;
        }

        select {
            width: 97%;
            padding: 10px;
            margin: 5px 0;
            border: none;
            border-radius: 5px;
            background-color: #333;
            color: #fff;
        }

        .search {
            width: 52%;
            padding: 10px;
            margin: 5px 0;
            border: none;
            border-radius: 5px;
            background-color: #333;
            color: #fff;
        }

        input:focus,
        select:focus {
            outline: none;
            border: 1px solid #28a745;
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
        <!-- <a href="activity_log.php"><i class="fas fa-file-alt"></i> ActivityLog</a> -->
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
        <div class="header">
            <h1>Staff List</h1>
        </div>
        <div class="container">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <input type="text" class="search" name="search" placeholder="Search..." value="<?php echo htmlspecialchars($search); ?>" onkeyup="searchStaff()">
                <button class="btn btn-success" onclick="openModal('addStaffModal')">Add Staff</button>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Firstname</th>
                        <th>Lastname</th>
                        <th>Email</th>
                        <th>Branch</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="staffList">
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr data-id="<?php echo $row['staff_id']; ?>" data-branch-id="<?php echo $row['branch_id']; ?>">
                            <td><?php echo htmlspecialchars($row['firstname']); ?></td>
                            <td><?php echo htmlspecialchars($row['lastname']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['branch_code']) . " (" . htmlspecialchars($row['street']) . ", " . htmlspecialchars($row['city']) . ", " . htmlspecialchars($row['zip_code']) . ", " . htmlspecialchars($row['country']) . ")"; ?></td>
                            <td>
                                <button class="btn btn-warning" onclick="openEditModal(<?php echo $row['staff_id']; ?>)">Edit</button>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="staff_id" value="<?php echo $row['staff_id']; ?>">
                                    <button type="submit" name="delete_staff" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <div class="pagination">
            <ul>
                <?php
                for ($i = 1; $i <= $totalPages; $i++) {
                    if ($i == $page) {
                        echo "<li class='active'>$i</li>";
                    } else {
                        echo "<li><a href='?page=$i&search=" . urlencode($search) . "'>$i</a></li>";
                    }
                }
                ?>
            </ul>
        </div>

        <!-- Add Staff Modal -->
        <div id="addStaffModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal('addStaffModal')">&times;</span>
                <form method="POST">
                    <h3>Add Staff</h3>
                    <input type="text" name="firstname" placeholder="Firstname" required>
                    <input type="text" name="lastname" placeholder="Lastname" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <select name="branch_id" required>
                        <option value="">Select Branch</option>
                        <?php
                        $branches = $conn->query("SELECT * FROM branches");
                        while ($branch = $branches->fetch_assoc()) {
                            echo "<option value='{$branch['id']}'>{$branch['branch_code']} ({$branch['street']}, {$branch['city']}, {$branch['zip_code']}, {$branch['country']})</option>";
                        }
                        ?>
                    </select>
                    <button type="submit" name="add_staff" class="btn btn-success">Add Staff</button>
                </form>
            </div>
        </div>

        <!-- Edit Staff Modal -->
        <div id="editStaffModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal('editStaffModal')">&times;</span>
                <form method="POST" id="editForm">
                    <h3>Edit Staff</h3>
                    <input type="hidden" name="staff_id" id="editStaffId">
                    <input type="text" name="firstname" id="editFirstname" required>
                    <input type="text" name="lastname" id="editLastname" required>
                    <input type="email" name="email" id="editEmail" required>
                    <select name="branch_id" id="editBranch" required>
                        <?php
                        $branches = $conn->query("SELECT * FROM branches");
                        while ($branch = $branches->fetch_assoc()) {
                            echo "<option value='{$branch['id']}'>{$branch['branch_code']} ({$branch['street']}, {$branch['city']}, {$branch['zip_code']}, {$branch['country']})</option>";
                        }
                        ?>
                    </select>
                    <button type="submit" name="edit_staff" class="btn btn-warning">Save Changes</button>
                </form>
            </div>
        </div>

        <script>
            function openModal(modalId) {
                document.getElementById(modalId).style.display = 'block';
            }

            function closeModal(modalId) {
                document.getElementById(modalId).style.display = 'none';
            }

            function openEditModal(staffId) {
                const row = document.querySelector(`tr[data-id='${staffId}']`);
                document.getElementById('editStaffId').value = staffId;
                document.getElementById('editFirstname').value = row.cells[0].innerText;
                document.getElementById('editLastname').value = row.cells[1].innerText;
                document.getElementById('editEmail').value = row.cells[2].innerText;
                document.getElementById('editBranch').value = row.getAttribute('data-branch-id');
                openModal('editStaffModal');
            }

            function searchStaff() {
                const input = document.querySelector('input[name="search"]');
                const filter = input.value.toLowerCase();
                const rows = document.querySelectorAll('#staffList tr');

                rows.forEach(row => {
                    const text = row.innerText.toLowerCase();
                    row.style.display = text.includes(filter) ? "" : "none";
                });
            }

            window.onclick = function(event) {
                if (event.target.classList.contains('modal')) {
                    closeModal('addStaffModal');
                    closeModal('editStaffModal');
                }
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

<?php
$conn->close();
?>