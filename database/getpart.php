<?php
// get_part_details.php
// Fetches a single part's details from the database.

header('Content-Type: application/json');
require '../database/db.php'; // Reuse the existing database connection file

$response = ["success" => false, "message" => "Part not found.", "part" => null];

if (isset($_GET['id'])) {
    $partId = intval($_GET['id']);

    // Prepare a statement to get part details, including 'model' and 'year'
    $sql = "SELECT part_id, name, description, price, stock, images, brand, model, year, conditions FROM parts WHERE part_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $partId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $part = $result->fetch_assoc();
        $response["success"] = true;
        $response["message"] = "Part found.";
        $response["part"] = $part;
    }

    $stmt->close();
} else {
    $response["message"] = "Invalid request. Missing part ID.";
}

$conn->close();
echo json_encode($response);
?>
