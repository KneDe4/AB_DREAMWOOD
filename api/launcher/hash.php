<?php
session_start();
require '../../conf.php';
$key = 'DrEAMwOoDSItE1488228OkakpOkOe';
if (empty($_SESSION['user_id'])) {
    header('Location: /');
    exit(); 
}
$uid = $_SESSION['user_id'];
$user = $mysqli->query("SELECT * FROM users WHERE id = '$uid'")->fetch_assoc();
function encrypt_openssl($data, $key) {
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    $encrypted = openssl_encrypt($data, 'aes-256-cbc', $key, 0, $iv);
    return base64_encode($encrypted . '::' . $iv);
}

function decrypt_openssl($data, $key) {
    list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
    return openssl_decrypt($encrypted_data, 'aes-256-cbc', $key, 0, $iv);
}
$username = $user['username'];

if ($_GET['dec']) {
    echo decrypt_openssl($_GET['dec'], $_GET['key']);
} else {
    echo encrypt_openssl($username . ";@67@;" . $uid, $key);
}

