<?php
session_start();
include("db_connection.php");

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$user_details = null;

if ($user_id) {
    $sql = "SELECT * FROM users WHERE id = '$user_id'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $user_details = $result->fetch_assoc();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_profile'])) {
    $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
    $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    
    $sql = "UPDATE users SET firstname='$firstname', lastname='$lastname', email='$email', password='$password' WHERE id='$user_id'";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Profile updated successfully!');</script>";
    } else {
        echo "<script>alert('Error updating profile: " . $conn->error . "');</script>";
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: user_page.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
        }

        .navbar {
            background-color: #ffffff;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #ddd;
        }

        .navbar .logo {
            color: #333;
            font-size: 24px;
            font-weight: bold;
            text-decoration: none;
        }

        .navbar ul {
            list-style: none;
            display: flex;
        }

        .navbar ul li {
            margin-left: 20px;
        }

        .navbar ul li a {
            color: #333;
            text-decoration: none;
            font-size: 18px;
            padding: 5px 10px;
            transition: background-color 0.3s, color 0.3s;
        }

        .navbar ul li a:hover {
            background-color: #eee;
            color: #333;
            border-radius: 5px;
        }

        .modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            width: 300px;
            text-align: center;
            z-index: 10;
            background-color: white;
        }

        .modal h2 {
            margin-bottom: 15px;
        }

        .modal input {
            width: 100%;
            padding: 8px;
            margin: 8px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .modal button {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: none;
            border-radius: 5px;
            background-color: #333;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .modal button:hover {
            background-color: #555;
        }

        .modal .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 20px;
            color: #333;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
        }

        .modal .close-btn:hover {
            color: #555;
        }

        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 5;
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
</head>
<body>

    <nav class="navbar">
        <a href="#" class="logo">Logo</a>
        <ul>
            <li><a href="user_dashboard.php">Home</a></li>
            <li><a href="user_reviews.php">Review</a></li>
            <li><a href="#profile" onclick="openModal('editProfileModal')">Edit Profile</a></li>
            <li><a href="?logout=true">Logout</a></li>
        </ul>
    </nav>

    <div class="overlay" id="overlay" onclick="closeAllModals()"></div>

    <!-- Edit Profile Modal -->
    <div class="modal" id="editProfileModal">
        <a href="javascript:void(0)" class="close-btn" onclick="closeAllModals()">×</a>
        <h2>Edit Profile</h2>
        <form action="" method="POST">
            <input type="text" name="firstname" placeholder="First Name" value="<?php echo $user_details['firstname']; ?>" required>
            <input type="text" name="lastname" placeholder="Last Name" value="<?php echo $user_details['lastname']; ?>" required>
            <input type="email" name="email" placeholder="Email" value="<?php echo $user_details['email']; ?>" required>
            <input type="password" name="password" placeholder="New Password">
            <button type="submit" name="edit_profile">Save Changes</button>
        </form>
    </div>

     <!-- Content -->
     <div class="content">
        <div class="main-content">
            <div class="track-form">
                <img src="assets/img/logo.png" alt="Courier Management System" style="max-width: 50%; height: auto; display: block; margin: 0 auto;">

                <form method="GET" action="user_dashboard.php" onsubmit="showPrintButton()">
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
        function openModal(modalId) {
            document.getElementById(modalId).style.display = 'block';
            document.getElementById('overlay').style.display = 'block';
        }

        function closeAllModals() {
            document.getElementById('editProfileModal').style.display = 'none';
            document.getElementById('overlay').style.display = 'none';
        }
    </script>

</body>
</html>
