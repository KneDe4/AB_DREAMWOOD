<?php
class ProxyChecker {
    private $timeout = 2;
    private $test_urls = [
        "https://files.minecraftforge.net/",
    ];
    
    public function __construct($timeout = 10) {
        $this->timeout = $timeout;
    }
    
    public function checkProxy($proxy_string) {
        $start_time = microtime(true);
        
        // Парсим IP и порт
        if (strpos($proxy_string, ':') !== false) {
            list($ip, $port) = explode(':', $proxy_string);
            $port = intval($port);
        } else {
            // Если порт не указан, пробуем стандартные
            $ip = $proxy_string;
            $port = 80;
        }
        
        $proxy_ip = $ip . ':' . $port;
        
        // Проверяем прокси
        $is_working = $this->testHttpProxy($ip, $port);
        
        $ping = $is_working ? round((microtime(true) - $start_time) * 1000) : 0;
        
        return [
            'proxy' => $is_working,
            'ping' => $ping,
            'proxy_ip' => $proxy_ip
        ];
    }
    
    private function testHttpProxy($ip, $port) {
        // Пробуем разные тестовые URL
        foreach ($this->test_urls as $url) {
            $ch = curl_init();
            
            // Настройки CURL для HTTP прокси
            curl_setopt_array($ch, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => $this->timeout,
                CURLOPT_PROXY => $ip,
                CURLOPT_PROXYPORT => $port,
                CURLOPT_PROXYTYPE => CURLPROXY_HTTP,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HEADER => false,
                CURLOPT_NOBODY => false,
                CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
            ]);
            
            $response = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            
            curl_close($ch);
            
            // Если получили успешный ответ
            if ($http_code == 200 && !empty($response)) {
                return true;
            }
            
            // Если получили 403, но есть ответ - может быть рабочий
            if ($http_code == 403 && !empty($response)) {
                return true;
            }
        }
        
        return false;
    }
}

// Обработка GET запроса
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['ip'])) {
    $proxy_string = trim($_GET['ip']);
    
    // Базовая валидация
    if (empty($proxy_string)) {
        echo json_encode([
            'proxy' => false,
            'ping' => 0,
            'proxy_ip' => '',
            'error' => 'Proxy IP is empty'
        ]);
        exit;
    }
    
    // Проверяем формат IP:PORT
    if (!preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}:\d{1,5}$/', $proxy_string)) {
        echo json_encode([
            'proxy' => false,
            'ping' => 0,
            'proxy_ip' => $proxy_string,
            'error' => 'Invalid format. Use IP:PORT'
        ]);
        exit;
    }
    
    // Создаем чекер и проверяем прокси
    $checker = new ProxyChecker(10); // 10 секунд таймаут
    $result = $checker->checkProxy($proxy_string);
    
    // Возвращаем JSON
    header('Content-Type: application/json');
    echo json_encode($result);
    
} else {
    // Если не передан параметр ip
    echo json_encode([
        'proxy' => false,
        'ping' => 0,
        'proxy_ip' => '',
        'error' => 'Missing ip parameter. Use: proxy.php?ip=IP:PORT'
    ]);
}
?>