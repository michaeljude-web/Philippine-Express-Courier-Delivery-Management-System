<?php
session_start();
include("db_connection");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
        $user_id = $_SESSION['id']; 
        $rating = $_POST['rating'];
        $comment = $_POST['comment'];

        $sql = "INSERT INTO reviews (user_id, rating, comment) VALUES ('$user_id', '$rating', '$comment')";
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Review submitted successfully!');</script>";
        } else {
            echo "<script>alert('Error: " . $conn->error . "');</script>";
        }
    } else {
        echo "<script>alert('Please log in to submit a review.');</script>";
    }
}

$sql_reviews = "SELECT * FROM reviews ORDER BY created_at DESC";
$result_reviews = $conn->query($sql_reviews);

$sql_avg_rating = "SELECT AVG(rating) AS avg_rating FROM reviews";
$result_avg_rating = $conn->query($sql_avg_rating);
$row_avg_rating = $result_avg_rating->fetch_assoc();
$average_rating = round($row_avg_rating['avg_rating'], 2); // Rounded to 2 decimal places
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f5f5f5;
        }
        .container {
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        .reviews {
            margin-top: 30px;
        }
        .review {
            border-bottom: 1px solid #ddd;
            padding: 15px 0;
        }
        .review .rating {
            font-size: 18px;
            color: #ffcc00;
        }
        .review .comment {
            margin-top: 10px;
            font-style: italic;
        }
        .review .timestamp {
            font-size: 12px;
            color: #888;
        }
        .submit-review {
            display: flex;
            flex-direction: column;
            margin-top: 30px;
        }
        .submit-review select, .submit-review textarea {
            padding: 10px;
            margin: 10px 0;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .submit-review button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .submit-review button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Product Reviews</h1>

    <!-- Rating -->
    <div style="text-align: center; margin-bottom: 20px;">
        <strong>Average Rating: </strong>
        <span style="font-size: 24px; color: #ffcc00;"><?php echo str_repeat('★', floor($average_rating)); ?><?php echo str_repeat('☆', 5 - floor($average_rating)); ?></span>
        <span> (<?php echo $average_rating; ?> / 5)</span>
    </div>

    <!-- Review Form -->
    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
        <div class="submit-review">
            <form method="POST">
                <label for="rating">Rating (1 to 5):</label>
                <select id="rating" name="rating" required>
                    <option value="1">1 Star</option>
                    <option value="2">2 Stars</option>
                    <option value="3">3 Stars</option>
                    <option value="4">4 Stars</option>
                    <option value="5">5 Stars</option>
                </select>
                <label for="comment">Comment:</label>
                <textarea id="comment" name="comment" rows="4" required></textarea>
                <button type="submit" name="submit_review">Submit Review</button>
            </form>
        </div>
    <?php else: ?>
        <p>Please <a href="login.php">login</a> to submit a review.</p>
    <?php endif; ?>

    <!-- Reviews List -->
    <div class="reviews">
        <?php while ($row = $result_reviews->fetch_assoc()): ?>
            <div class="review">
                <div class="rating"><?php echo str_repeat('★', $row['rating']); ?><?php echo str_repeat('☆', 5 - $row['rating']); ?></div>
                <div class="comment"><?php echo nl2br($row['comment']); ?></div>
                <div class="timestamp">Reviewed on: <?php echo date('F j, Y, g:i a', strtotime($row['created_at'])); ?></div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

</body>
</html>

<?php
$conn->close();
?>
