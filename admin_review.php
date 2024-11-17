<?php
session_start();

// Include the correct path to db_connection.php
include("db_connection.php"); 

// Handle the search query
$search_query = "";
if (isset($_POST['search'])) {
    $search_query = mysqli_real_escape_string($conn, $_POST['search']);
}

// Pagination logic
$reviews_per_page = 10; // Number of reviews per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $reviews_per_page;

// Fetch reviews with user details and pagination, applying the search filter
$sql = "SELECT r.*, u.firstname, u.lastname, u.email 
        FROM reviews r 
        JOIN users u ON r.user_id = u.id
        WHERE u.firstname LIKE '%$search_query%' OR u.lastname LIKE '%$search_query%'
        ORDER BY r.created_at DESC 
        LIMIT $reviews_per_page OFFSET $offset";
$result = $conn->query($sql);

// Fetch total number of reviews for pagination calculation
$total_sql = "SELECT COUNT(*) AS total_reviews FROM reviews r
              JOIN users u ON r.user_id = u.id
              WHERE u.firstname LIKE '%$search_query%' OR u.lastname LIKE '%$search_query%'";
$total_result = $conn->query($total_sql);
$total_row = $total_result->fetch_assoc();
$total_reviews = $total_row['total_reviews'];
$total_pages = ceil($total_reviews / $reviews_per_page);

// Handle review deletion
if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    $delete_sql = "DELETE FROM reviews WHERE id = $delete_id";
    if ($conn->query($delete_sql) === TRUE) {
        echo "<script>alert('Review deleted successfully');</script>";
    } else {
        echo "<script>alert('Error deleting review');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - View Reviews</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #212121; /* Dark background */
            color: #fff;
            padding: 20px;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: #333;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #f8b400; /* Golden yellow */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #444;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #444;
        }

        tr:nth-child(even) {
            background-color: #555;
        }

        .stars {
            color: gold;
        }

        .delete-btn {
            background-color: #ff4d4d;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
        }

        .delete-btn:hover {
            background-color: #ff0000;
        }

        .pagination {
            text-align: center;
            margin-top: 20px;
        }

        .pagination a {
            margin: 0 5px;
            padding: 5px 10px;
            background-color: #f8b400;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .pagination a:hover {
            background-color: #f39c12;
        }

        .pagination a.active {
            background-color: #f39c12;
            color: #fff;
        }

        .search-bar {
            text-align: center;
            margin-bottom: 20px;
        }

        .search-bar input[type="text"] {
            padding: 10px;
            width: 300px;
            margin-right: 10px;
            border-radius: 5px;
            border: 1px solid #444;
        }

        .search-bar button {
            padding: 10px 20px;
            background-color: #f8b400;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        .search-bar button:hover {
            background-color: #f39c12;
        }
    </style>
</head>
<body>

    <h2>Admin Review Dashboard</h2>

    <!-- Search Bar -->
    <div class="search-bar">
        <form method="POST" action="">
            <input type="text" name="search" value="<?php echo htmlspecialchars($search_query); ?>" placeholder="Search by user name">
            <button type="submit">Search</button>
        </form>
    </div>

    <!-- Admin Review Table -->
    <div class="container">
        <table>
            <tr>
                <th>User Name</th>
                <th>Email</th>
                <th>Star Rating</th>
                <th>Comment</th>
                <th>Submitted On</th>
                <th>Actions</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['firstname']} {$row['lastname']}</td>";
                    echo "<td>{$row['email']}</td>";
                    echo "<td>" . str_repeat("&#9733;", $row['star_rating']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['comment']) . "</td>";
                    echo "<td>" . $row['created_at'] . "</td>";
                    echo "<td><a href='?delete_id={$row['id']}' class='delete-btn'>Delete</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No reviews found.</td></tr>";
            }
            ?>
        </table>

        <!-- Pagination -->
        <div class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                <a href="?page=<?php echo $i; ?>" class="<?php echo ($i == $page) ? 'active' : ''; ?>">
                    <?php echo $i; ?>
                </a>
            <?php } ?>
        </div>
    </div>

</body>
</html>

<?php 
// Close the connection
$conn->close(); 
?>
