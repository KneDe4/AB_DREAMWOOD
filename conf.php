<?php
$host = 'pililteam.ru';
$dbname = 'dw';
$username = 'pilil';
$password = 'lilkapilka2022';

try {
    $mysqli = new mysqli($host, $username, $password, $dbname);
    
    if ($mysqli->connect_error) {
        throw new Exception("Ошибка подключения: " . $mysqli->connect_error);
    }
    
    $mysqli->set_charset("utf8mb4");
    
} catch (Exception $e) {
    // Логируем ошибку и показываем пользователю
    error_log($e->getMessage());
    die("Произошла ошибка при подключении к базе данных. Пожалуйста, попробуйте позже.");
}
