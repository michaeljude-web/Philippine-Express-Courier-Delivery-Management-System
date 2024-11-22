<?php
session_start();

include("db_connection.php"); 

$search_query = "";
if (isset($_POST['search'])) {
    $search_query = mysqli_real_escape_string($conn, $_POST['search']);
}

$reviews_per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $reviews_per_page;

$sql = "SELECT r.*, u.firstname, u.lastname, u.email 
        FROM reviews r 
        JOIN users u ON r.user_id = u.id
        WHERE u.firstname LIKE '%$search_query%' OR u.lastname LIKE '%$search_query%'
        ORDER BY r.created_at DESC 
        LIMIT $reviews_per_page OFFSET $offset";
$result = $conn->query($sql);

$total_sql = "SELECT COUNT(*) AS total_reviews FROM reviews r
              JOIN users u ON r.user_id = u.id
              WHERE u.firstname LIKE '%$search_query%' OR u.lastname LIKE '%$search_query%'";
$total_result = $conn->query($total_sql);
$total_row = $total_result->fetch_assoc();
$total_reviews = $total_row['total_reviews'];
$total_pages = ceil($total_reviews / $reviews_per_page);

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #222;
            color: #fff;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }

        .container {
            width: 90%;
            max-width: 1000px;
            background-color: #333;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #f8b400;
            margin-bottom: 20px;
        }

        .search-bar {
            text-align: center;
            margin-bottom: 20px;
        }

        .search-bar input[type="text"] {
            padding: 10px;
            width: 250px;
            margin-right: 10px;
            border-radius: 5px;
            border: 1px solid #444;
            background-color: #444;
            color: #fff;
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
            text-align: center;
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
            color: #ff4d4d;
            font-size: 20px;
            cursor: pointer;
        }

        .delete-btn:hover {
            color: #e60000;
        }

        .pagination {
            text-align: center;
            margin-top: 20px;
        }

        .pagination a {
            margin: 0 5px;
            padding: 8px 15px;
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

    </style>
</head>
<body>

    <h2>Admin Review Dashboard</h2>

    <div class="search-bar">
        <form method="POST" action="">
            <input type="text" name="search" value="<?php echo htmlspecialchars($search_query); ?>" placeholder="Search by user name">
            <button type="submit">Search</button>
        </form>
    </div>

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
                    echo "<td><a href='#' class='delete-btn' onclick='confirmDelete({$row['id']})'><i class='fas fa-trash'></i></a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No reviews found.</td></tr>";
            }
            ?>
        </table>

        <div class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                <a href="?page=<?php echo $i; ?>" class="<?php echo ($i == $page) ? 'active' : ''; ?>">
                    <?php echo $i; ?>
                </a>
            <?php } ?>
        </div>
    </div>

    <script>
        function confirmDelete(id) {
            if (confirm("Are you sure you want to delete this review?")) {
                window.location.href = "?delete_id=" + id;
            }
        }
    </script>

</body>
</html>

<?php 
$conn->close(); 
?>
