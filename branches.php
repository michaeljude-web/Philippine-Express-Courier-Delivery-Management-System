<?php
include('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $street = $_POST['street'];
        $city = $_POST['city'];
        $zip_code = $_POST['zip_code'];
        $contact = $_POST['contact'];

        $sql = "INSERT INTO branches (street, city, zip_code, country, contact) VALUES ('$street', '$city', '$zip_code', 'Philippines', '$contact')";
        if ($conn->query($sql)) {
            echo json_encode(["success" => "Branch added successfully!"]);
        } else {
            echo json_encode(["error" => "Error adding branch."]);
        }
        exit;
    } elseif (isset($_POST['edit'])) {
        $id = $_POST['id'];
        $street = $_POST['street'];
        $city = $_POST['city'];
        $zip_code = $_POST['zip_code'];
        $contact = $_POST['contact'];

        $sql = "UPDATE branches SET street='$street', city='$city', zip_code='$zip_code', contact='$contact' WHERE id=$id";
        if (!$conn->query($sql)) {
            echo "Error: " . $conn->error;
        }
        exit;
    } elseif (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $sql = "DELETE FROM branches WHERE id=$id";
        if (!$conn->query($sql)) {
            echo "Error: " . $conn->error;
        }
        exit;
    }
}

function fetchBranches($conn, $search = '', $page = 1, $limit = 10)
{
    $offset = ($page - 1) * $limit;
    $sql = "SELECT * FROM branches WHERE street LIKE '%$search%' OR city LIKE '%$search%' LIMIT $limit OFFSET $offset";
    return $conn->query($sql);
}

function countBranches($conn, $search)
{
    $total_sql = "SELECT COUNT(*) as count FROM branches WHERE street LIKE '%$search%' OR city LIKE '%$search%'";
    $total_result = $conn->query($total_sql);
    return $total_result->fetch_assoc()['count'];
}

$search = isset($_GET['search']) ? $_GET['search'] : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 6;
$branches = fetchBranches($conn, $search, $page, $limit);
$total_count = countBranches($conn, $search);
$total_pages = ceil($total_count / $limit);

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $branch_sql = "SELECT * FROM branches WHERE id = $id";
    $branch_result = $conn->query($branch_sql);
    $branch = $branch_result->fetch_assoc();
    echo json_encode($branch);
    exit;
}
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
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #333;
            /* border-radius: 8px; */
            overflow: hidden;
        }

        th,
        td {
            text-align: center;
            padding: 10px;
            border: 1px solid #666;
        }

        th {
            background-color: #444;
        }

        .btn {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 5px;
        }

        .btn-primary {
            background-color: #555;
            color: white;
        }

        .btn-warning {
            background-color: #777;
            color: white;
        }

        .btn-danger {
            background-color: #a33;
            color: white;
        }

        .btn:hover {
            opacity: 0.8;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #222;
            padding: 20px;
            border-radius: 10px;
            color: #fff;
            width: 300px;
        }

        .form-control {
            width: 93%;
            padding: 10px;
            background-color: #555;
            color: #fff;
            border: 1px solid #666;
            border-radius: 5px;
            margin-bottom: 10px;
            /* display: flex;
            justify-content: space-between: */
        }

        .form-control::placeholder {
            color: #ccc;
        }

        .d-flex {
            display: flex;
            align-items: center;
        }

        .search-add {
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .active {
            background-color: red;
        }

        .modal-content input {
            margin-bottom: 10px;
        }

        .modal-content .form-group {
            display: flex;
            justify-content: space-between;
        }

        @media (max-width: 600px) {
            .modal-content .form-group {
                flex-direction: column;
            }
        }

        .pagination a {
            padding: 10px 15px;
            font-size: 16px;
        }

        .pagination li.active {
            color: black;
            background-color: #ff6a00;
            border: 1px solid black;
            font-weight: bold;
            padding: 0;

        }

        .pagination li {
            display: inline-block;
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
        <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i><br> Dashboard</a>
        <a href="branches.php"><i class="fas fa-code-branch"></i><br> Branches</a>
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
        <a onclick="confirmLogout()"  class="logout"><i class="fas fa-sign-out-alt"></i><br> Logout</a>
    </div>

    <!-- Content -->
    <div class="content">
        <div class="header">
            <h1>Branch Management</h1>
        </div>
        <div class="container mt-4">
            <div class="d-flex search-add">
                <input type="text" id="search" class="form-control" placeholder="Search branches..." onkeypress="if(event.keyCode === 13) searchBranches()">
                <button class="btn btn-primary" onclick="openAddModal()">Add Branch</button>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Street</th>
                        <th>City</th>
                        <th>Zip Code</th>
                        <th>Contact</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="branchTableBody">
                    <?php while ($row = $branches->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= $row['street'] ?></td>
                            <td><?= $row['city'] ?></td>
                            <td><?= $row['zip_code'] ?></td>
                            <td><?= $row['contact'] ?></td>
                            <td>
                                <button class="btn btn-warning" onclick="openEditModal(<?= $row['id'] ?>)">Edit</button>
                                <button class="btn btn-danger" onclick="deleteBranch(<?= $row['id'] ?>)">Delete</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <div class="pagination">
                <ul>
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="<?= $i === $page ? 'active' : '' ?>">
                            <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </div>


            <!-- Add Branch Modal -->
            <div class="modal" id="addModal">
                <div class="modal-content">
                    <h5>Add Branch</h5>
                    <input type="text" id="addStreet" class="form-control" placeholder="Street" required>
                    <input type="text" id="addCity" class="form-control" placeholder="City" required>
                    <div class="form-group">
                        <input type="text" id="addZipCode" class="form-control" placeholder="Zip Code" required>&nbsp;
                        <input type="text" id="addContact" class="form-control" placeholder="Contact" required>
                    </div>
                    <button class="btn btn-primary" onclick="addBranch()">Add Branch</button>
                    <button class="btn btn-danger" onclick="closeModal('addModal')">Close</button>
                </div>
            </div>

            <!-- Edit Branch Modal -->
            <div class="modal" id="editModal">
                <div class="modal-content">
                    <h5>Edit Branch</h5>
                    <input type="hidden" id="editId">
                    <input type="text" id="editStreet" class="form-control" placeholder="Street" required>
                    <input type="text" id="editCity" class="form-control" placeholder="City" required>
                    <div class="form-group">
                        <input type="text" id="editZipCode" class="form-control" placeholder="Zip Code" required>&nbsp;
                        <input type="text" id="editContact" class="form-control" placeholder="Contact" required>
                    </div>
                    <button class="btn btn-primary" onclick="updateBranch()">Update Branch</button>
                    <button class="btn btn-danger" onclick="closeModal('editModal')">Close</button>
                </div>
            </div>

            <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
            <script>
                function searchBranches() {
                    let searchQuery = document.getElementById('search').value;
                    window.location.href = '?search=' + encodeURIComponent(searchQuery);
                }

                function openAddModal() {
                    document.getElementById('addModal').style.display = 'flex';
                }

                function closeModal(modalId) {
                    document.getElementById(modalId).style.display = 'none';
                }

                function addBranch() {
                    let street = document.getElementById('addStreet').value;
                    let city = document.getElementById('addCity').value;
                    let zip_code = document.getElementById('addZipCode').value;
                    let contact = document.getElementById('addContact').value;

                    $.post('', {
                        add: true,
                        street,
                        city,
                        zip_code,
                        contact
                    }, function(response) {
                        let res = JSON.parse(response);
                        if (res.success) {
                            alert(res.success);
                            location.reload();
                        } else if (res.error) {
                            alert(res.error);
                        }
                    });
                }

                function openEditModal(id) {
                    $.post('', {
                        id: id
                    }, function(data) {
                        let branch = JSON.parse(data);
                        document.getElementById('editId').value = branch.id;
                        document.getElementById('editStreet').value = branch.street;
                        document.getElementById('editCity').value = branch.city;
                        document.getElementById('editZipCode').value = branch.zip_code;
                        document.getElementById('editContact').value = branch.contact;
                        document.getElementById('editModal').style.display = 'flex';
                    });
                }

                function updateBranch() {
                    let id = document.getElementById('editId').value;
                    let street = document.getElementById('editStreet').value;
                    let city = document.getElementById('editCity').value;
                    let zip_code = document.getElementById('editZipCode').value;
                    let contact = document.getElementById('editContact').value;

                    $.post('', {
                        edit: true,
                        id,
                        street,
                        city,
                        zip_code,
                        contact
                    }, function() {
                        location.reload();
                    });
                }

                function deleteBranch(id) {
                    if (confirm("Are you sure you want to delete this branch?")) {
                        $.post('', {
                            delete: true,
                            id: id
                        }, function() {
                            location.reload();
                        });
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