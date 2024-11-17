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
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_review'])) {
    if (!isset($_SESSION['user_id'])) {
        echo "<script>alert('You must login to submit a review.');</script>";
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $star_rating = $_POST['star_rating'];
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);

    $sql = "INSERT INTO reviews (user_id, star_rating, comment) VALUES ('$user_id', '$star_rating', '$comment')";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Review submitted successfully!');</script>";
    } else {
        echo "<script>alert('Error submitting review: " . $conn->error . "');</script>";
    }
}

$reviews_per_page = 4;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $reviews_per_page;

$sql = "SELECT r.*, u.firstname, u.lastname, u.email FROM reviews r 
        JOIN users u ON r.user_id = u.id 
        ORDER BY r.created_at DESC LIMIT $offset, $reviews_per_page";
$result = $conn->query($sql);

$total_reviews_result = $conn->query("SELECT COUNT(*) AS total FROM reviews");
$total_reviews_row = $total_reviews_result->fetch_assoc();
$total_reviews = $total_reviews_row['total'];
$total_pages = ceil($total_reviews / $reviews_per_page);

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

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
        }

        .star-rating {
            display: flex;
        }

        .star-rating label {
            font-size: 24px;
            cursor: pointer;
            margin-right: 5px;
        }

        .star-rating input[type="radio"] {
            display: none;
        }

        .star-rating input[type="radio"]:checked ~ label {
            color: gold;
        }
        .review-form{
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .review-form, .reviews {
            margin-bottom: 30px;
        }

        .submit {
            width: 100%;
        }

        .reviews {
            border-top: 1px solid #ddd;
            padding-top: 20px;
            padding-left: 10px;
        }

        .review {
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #ddd;
            /* text-align: center; */
            
        }

        .review .stars {
            color: gold;
        }

        .pagination {
            text-align: center;
        }

        .pagination a {
            margin: 0 5px;
            padding: 5px 10px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 3px;
        }

        .pagination a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <a href="#" class="logo">Philippine Express</a>
        <ul>
            <li><a href="user_dashboard.php">Home</a></li>
            <li><a href="user_reviews.php">Review</a></li>
            <li><a href="#profile" onclick="openModal('editProfileModal')">Edit Profile</a></li>
            <li><a href="?logout=true">Logout</a></li>
        </ul>
    </nav>

    <div class="overlay" id="overlay" onclick="closeAllModals()"></div>

    <!-- Edit Profile -->
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
  <!-- Review -->
  <div class="review-form">
        <form method="POST">
            <div class="star-rating">
                <input type="radio" id="star5" name="star_rating" value="5"><label for="star5">&#9733;</label>
                <input type="radio" id="star4" name="star_rating" value="4"><label for="star4">&#9733;</label>
                <input type="radio" id="star3" name="star_rating" value="3"><label for="star3">&#9733;</label>
                <input type="radio" id="star2" name="star_rating" value="2"><label for="star2">&#9733;</label>
                <input type="radio" id="star1" name="star_rating" value="1"><label for="star1">&#9733;</label>
            </div>
            <textarea name="comment" placeholder="Write your review..." required></textarea><br>
            <button type="submit" name="submit_review" class="submit">Submit Review</button>
        </form>
    </div>

    <div class="reviews">
        <h3 align="center">Reviews</h3>
        <?php
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='review'>";
                echo "<strong>{$row['firstname']} {$row['lastname']}</strong> ({$row['email']})<br>";
                echo "<div class='stars'>" . str_repeat("&#9733;", $row['star_rating']) . "</div>";
                echo "<p>" . htmlspecialchars($row['comment']) . "</p>";
                echo "<small>Posted on " . $row['created_at'] . "</small>";
                echo "</div>";
            }
        } else {
            echo "<p>No reviews yet!</p>";
        }
        ?>

        <div class="pagination">
            <?php 
            for ($i = 1; $i <= $total_pages; $i++) { 
                echo "<a href='?page=$i'>$i</a>";
            } 
            ?>
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
<?php 
$conn->close(); 
?>
