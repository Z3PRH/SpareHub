<?php
// update_part.php
// Updates product details in the database.
header('Content-Type: application/json');
require '../database/db.php';

$response = ["success" => false, "message" => ""];

// Check for required POST data
if (isset($_POST['part_id']) && isset($_POST['price']) && isset($_POST['stock'])) {
    $partId = intval($_POST['part_id']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);

    // Prepare and bind statement
    $sql = "UPDATE parts SET price = ?, stock = ? WHERE part_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $price, $stock, $partId);

    if ($stmt->execute()) {
        $response["success"] = true;
        $response["message"] = "Product updated successfully.";
    } else {
        $response["message"] = "Error updating record: " . $conn->error;
    }

    $stmt->close();
} else {
    $response["message"] = "Invalid request. Missing required data.";
}
$conn->close();
echo json_encode($response);
?>