<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
session_start();
require '../conf.php';
$uid = $_SESSION['user_id'];
$user = $mysqli->query("SELECT * FROM users WHERE id = '$uid'")->fetch_assoc();

$method = $_SERVER['REQUEST_METHOD'];
if ($method == "PUT") {
    $data = json_decode(file_get_contents('php://input'), true);
         $stmt = $mysqli->prepare("INSERT INTO products (user, name, price, descp, status, type) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssss", $uid, $data['name'], $data['price'], $data['description'], $data['status'], $data['category']);
        if ($stmt->execute()) {
            echo json_encode(["status" => "ok"]);
        } else {
            echo json_encode(["status" => "neok"]);
        }
}
?>