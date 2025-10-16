<?php
// delete_part.php
// Deletes a part from the database.
header('Content-Type: application/json');
require '../database/db.php';

$response = ["success" => false, "message" => ""];

// Check for part_id from POST request
if (isset($_POST['part_id'])) {
    $partId = intval($_POST['part_id']);

    // Prepare and bind statement to prevent SQL injection
    $sql = "DELETE FROM parts WHERE part_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $partId);

    if ($stmt->execute()) {
        $response["success"] = true;
        $response["message"] = "Product deleted successfully.";
    } else {
        $response["message"] = "Error deleting record: " . $conn->error;
    }

    $stmt->close();
} else {
    $response["message"] = "Invalid request. Missing part_id.";
}

$conn->close();
echo json_encode($response);
?>