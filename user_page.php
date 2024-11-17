<?php
session_start();

include("db_connection.php");
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['signup'])) {
    $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
    $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $check_email = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($check_email);
    
    if ($result->num_rows > 0) {
        echo "<script>alert('Email already registered!');</script>";
    } else {
        $sql = "INSERT INTO users (firstname, lastname, email, password) 
                VALUES ('$firstname', '$lastname', '$email', '$password')";
        
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Sign Up successful! You can now log in.');</script>";
        } else {
            echo "<script>alert('Error: " . $conn->error . "');</script>";
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";  
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id']; 
        echo "<script>alert('Login successful!'); window.location.href = 'user_dashboard.php';</script>";  // Redirect to user_login2.php
    } else {
        echo "<script>alert('Invalid email or password!');</script>";
    }
}

if (isset($_SESSION['user_id'])) {
    header("Location: user_dashboard.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

        .custom-alert {
            display: none;
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #f5f5dc;
            border: 2px solid #333;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            font-size: 18px;
            color: #333;
            width: 300px;
            text-align: center;
            position: relative;
            z-index: 15;
        }

        .custom-alert .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 20px;
            color: #333;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
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
        <a href="#" class="logo"></a>
        <ul>
            <li><a href="#home">Home</a></li>
            <li><a href="#about" onclick="showCustomAlert()">Review</a></li>
            <li><a href="#login" onclick="openModal('loginModal')">Login</a></li>
            <li><a href="#signup" onclick="openModal('signupModal')">Sign up</a></li>
        </ul>
    </nav>

    <div class="custom-alert" id="customAlert">
        <a href="javascript:void(0)" class="close-btn" onclick="closeCustomAlert()">×</a>
        <p style="color: red;">You must login first!</p>
    </div>

    <div class="overlay" id="overlay" onclick="closeAllModals()"></div>

    <div class="modal" id="loginModal">
        <a href="javascript:void(0)" class="close-btn" onclick="closeAllModals()">×</a>
        <h2>Login</h2>
        <form action="" method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Login</button>
        </form>
    </div>

    <div class="modal" id="signupModal">
        <a href="javascript:void(0)" class="close-btn" onclick="closeAllModals()">×</a>
        <h2>Sign Up</h2>
        <form action="" method="POST">
            <input type="text" name="firstname" placeholder="First Name" required>
            <input type="text" name="lastname" placeholder="Last Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="signup">Sign Up</button>
        </form>
    </div>

    <script>
        function showCustomAlert() {
            document.getElementById('customAlert').style.display = 'block';
        }

        function closeCustomAlert() {
            document.getElementById('customAlert').style.display = 'none';
        }

        function openModal(modalId) {
            document.getElementById(modalId).style.display = 'block';
            document.getElementById('overlay').style.display = 'block';
        }

        function closeAllModals() {
            document.getElementById('loginModal').style.display = 'none';
            document.getElementById('signupModal').style.display = 'none';
            document.getElementById('overlay').style.display = 'none';
            closeCustomAlert();
        }
    </script>

</body>
</html>




