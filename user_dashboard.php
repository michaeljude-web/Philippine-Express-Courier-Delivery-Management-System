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
