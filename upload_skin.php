<?php
// upload_skin.php - Обработчик загрузки скинов для DreamWood

// Настройки
$uploadDir = 'uploads/skins/'; // Папка для сохранения скинов
$baseUrl = 'https://dreamwood.space/'; // Базовый URL вашего сайта
$maxFileSize = 5 * 1024 * 1024; // 5 МБ
$allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];

// Создаем папку если её нет
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Функция для ответа в JSON формате
function sendResponse($success, $message, $data = []) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ]);
    exit;
}

// Проверяем метод запроса
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(false, 'Метод не поддерживается');
}

// Проверяем наличие файла
if (!isset($_FILES['skin']) || $_FILES['skin']['error'] !== UPLOAD_ERR_OK) {
    $uploadErrors = [
        UPLOAD_ERR_INI_SIZE => 'Файл превышает максимальный размер',
        UPLOAD_ERR_FORM_SIZE => 'Файл превышает максимальный размер',
        UPLOAD_ERR_PARTIAL => 'Файл был загружен только частично',
        UPLOAD_ERR_NO_FILE => 'Файл не был загружен',
        UPLOAD_ERR_NO_TMP_DIR => 'Отсутствует временная папка',
        UPLOAD_ERR_CANT_WRITE => 'Не удалось записать файл на диск',
    ];
    
    $errorCode = $_FILES['skin']['error'];
    $errorMessage = isset($uploadErrors[$errorCode]) ? $uploadErrors[$errorCode] : 'Неизвестная ошибка';
    
    sendResponse(false, 'Ошибка загрузки: ' . $errorMessage);
}

$file = $_FILES['skin'];

// Проверяем размер файла
if ($file['size'] > $maxFileSize) {
    sendResponse(false, 'Файл слишком большой. Максимальный размер: 5 МБ');
}

// Проверяем тип файла
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);

if (!in_array($mimeType, $allowedTypes)) {
    sendResponse(false, 'Разрешены только PNG и JPG файлы');
}

// Получаем никнейм из запроса
$username = isset($_POST['username']) ? preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['username']) : 'player';
$username = $username ?: 'player';

// Генерируем уникальное имя файла
$extension = $mimeType === 'image/png' ? 'png' : 'jpg';
$randomId = bin2hex(random_bytes(8));
$filename = $username . '_' . $randomId . '.' . $extension;
$filepath = $uploadDir . $filename;

// Перемещаем загруженный файл
if (move_uploaded_file($file['tmp_name'], $filepath)) {
    // Полный URL к файлу
    $fileUrl = $baseUrl . $filepath;
    
    // Генерируем команду для Minecraft
    $minecraftCommand = '/skin set ' . $fileUrl;
    
    sendResponse(true, 'Скин успешно загружен', [
        'url' => $fileUrl,
        'filename' => $filename,
        'command' => $minecraftCommand . ' slim',
        'username' => $username
    ]);
} else {
    sendResponse(false, 'Ошибка при сохранении файла');
}
?>