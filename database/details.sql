$sql = "INSERT INTO orders (user_id, status, total_amount, shipping_address, tracking_number) VALUES (?, ?, ?, ?, ?)";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("isdss", $user_id, $status, $total_amount, $shipping_address, $tracking_number);
$stmt->execute();
$stmt->close();
