<?php
session_start();
header('Content-Type: application/json');
echo json_encode(['session_active' => isset($_SESSION['user_id'])]);
?>