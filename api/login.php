<?php
// api/login.php
require '../conf.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Метод не разрешен']);
    exit();
}

$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, true);

if (!$input) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Некорректные данные']);
    exit();
}

$login = trim($input['login'] ?? '');
$password = $input['password'] ?? '';

if (empty($login)) {
    echo json_encode(['success' => false, 'message' => 'Введите логин']);
    exit();
}

if (empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Введите пароль']);
    exit();
}

try {
    global $mysqli;
    
    // Ищем пользователя по username
    $stmt = $mysqli->prepare("SELECT id, username, password, donate, ban FROM users WHERE username = ?");
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    if (!$user) {
        echo json_encode(['success' => false, 'message' => 'Пользователь не найден']);
        exit();
    }
    
    // Проверяем бан
    if ($user['ban'] == 1) {
        echo json_encode(['success' => false, 'message' => 'Ваш аккаунт заблокирован']);
        exit();
    }
    
    // Прямое сравнение паролей (так как они хранятся в открытом виде)
    if ($password === $user['password']) {
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['donate'] = $user['donate'];
        
        echo json_encode([
            'success' => true,
            'message' => 'Вход выполнен успешно',
            'redirect' => 'account.php',
            'user' => [
                'id' => $user['id'],
                'username' => $user['username'],
                'donate' => $user['donate']
            ]
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Неверный пароль']);
    }
    
    $stmt->close();
    
} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Ошибка сервера: ' . $e->getMessage()]);
}