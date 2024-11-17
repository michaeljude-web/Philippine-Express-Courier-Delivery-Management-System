<?php
session_start();
include('db_connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM admin WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $_SESSION['username'] = $username;
        header("Location:dashboard.php");
        exit(); 
    } else {
        echo "<script>
                alert('Invalid username or password try after few mounts.');
                window.location.href = 'admin_login.php';
              </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Admin Login</title>
    <link rel="stylesheet" href="(link unavailable)">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #1b1b1b;
            color: #e0e0e0;
        }
        
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #1b1b1b;
        }
        
        .login-box {
            background-color: #2a2a2a;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }
        
        .login-box h2 {
            text-align: center;
            color: #ffb100;
            margin-bottom: 20px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 10px;
            color: #fff;
        }
        
        .form-group input[type="text"],
        .form-group input[type="password"] {
            width: 91%;
            padding: 10px;
            border: none;
            border-radius: 10px;
            background-color: #444;
            color: #fff;
        }
        
        button[type="submit"] {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 10px;
            background-color: #ffb100;
            color: red;
            cursor: pointer;
        }
        
        /* button[type="submit"]:hover {
            background-color: #e74c3c;
        } */
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h2>Login</h2>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit">Login</button>
            </form>
            <!-- <p>Don't have an account? <a href="#">Register now</a></p> -->
        </div>
    </div>
</body>
</html>