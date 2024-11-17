<?php
include 'db_connection.php';

if (isset($_GET['id'])) {
    $parcel_id = $_GET['id'];
    $sql = "
        SELECT 
            p.id, p.sender_name, p.sender_address, p.sender_contact,
            p.recipient_name, p.recipient_address, p.recipient_contact,
            p.weight, p.height, p.length, p.width, p.price, p.status,
            b1.street AS branch_processed_street, b1.city AS branch_processed_city, b1.country AS branch_processed_country,
            b2.street AS pickup_branch_street, b2.city AS pickup_branch_city, b2.country AS pickup_branch_country
        FROM parcels p
        LEFT JOIN branches b1 ON p.from_branch_id = b1.id
        LEFT JOIN branches b2 ON p.to_branch_id = b2.id
        WHERE p.id = ?
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $parcel_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $parcel = $result->fetch_assoc();
        echo json_encode($parcel);
    } else {
        echo json_encode(['error' => 'Parcel not found']);
    }
}
?>
