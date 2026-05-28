<?php
session_start();
require 'conf.php';
if (empty($_SESSION['user_id'])) {
    header('Location: /');
    session_abort();
    exit(); 
}
$uid = $_SESSION['user_id'];
$user = $mysqli->query("SELECT * FROM users WHERE id = '$uid'")->fetch_assoc();
$settings = $mysqli->query("SELECT * FROM settings WHERE id = '$uid'")->fetch_assoc();

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DreamWood | Личный кабинет</title>
    <!-- Подключаем шрифт Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <!-- Подключаем ТВОЙ файл с премиум-иконками -->
    <link rel="stylesheet" href="https://knede4.github.io/css/all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {  
            background-color: #FFFFFF;
            font-family: 'Inter', sans-serif;
            color: #1E1E1E;
            line-height: 1.5;
            font-weight: 400;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
        }

        /* Шапка */
        .header {
            padding: 32px 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .header-inner {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 24px;
            font-weight: 600;
            letter-spacing: -0.02em;
            color: #000;
            text-decoration: none;
        }

        .logo span {
            font-weight: 300;
            color: #7F7F7F;
        }

        .nav a {
            color: #2E2E2E;
            text-decoration: none;
            margin-left: 32px;
            font-size: 16px;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .nav a:hover,
        .nav a.active {
            color: #000;
            font-weight: 700;
            border-bottom: 2px solid #000;
            padding-bottom: 4px;
        }

        /* Профиль */
        .profile-header {
            display: flex;
            align-items: center;
            gap: 40px;
            margin: 60px 0;
            padding: 40px;
            background: #F9F9F9;
            border-radius: 32px;
        }

        /* Скин через API */
        .skin-preview {
            width: 180px;
            height: 360px;
            background: #E0E0E0;
            border-radius: 24px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            border: 3px solid #fff;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .skin-render {
            width: 100%;
            height: 100%;
            object-fit: contain;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .skin-controls {
            position: absolute;
            bottom: 10px;
            right: 10px;
            display: flex;
            gap: 8px;
        }

        .skin-btn {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 40px;
            padding: 6px 12px;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 4px;
            transition: all 0.2s;
        }

        .skin-btn:hover {
            background: #000;
            color: #fff;
            border-color: #000;
        }

        .profile-info {
            flex: 1;
        }

        .profile-name {
            font-size: 32px;
            font-weight: 600;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .username-input {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 16px 0;
        }

        .username-field {
            padding: 10px 16px;
            border: 1px solid #DDD;
            border-radius: 40px;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            flex: 1;
            max-width: 250px;
        }

        .update-btn {
            background: #000;
            color: #fff;
            border: none;
            border-radius: 40px;
            padding: 10px 20px;
            font-weight: 500;
            cursor: pointer;
        }

        .profile-badge {
            background: #000;
            color: #fff;
            padding: 4px 12px;
            border-radius: 40px;
            font-size: 14px;
            font-weight: 500;
        }

        .profile-stats {
            display: flex;
            gap: 40px;
            margin: 20px 0;
        }

        .stat {
            display: flex;
            flex-direction: column;
        }

        .stat-value {
            font-size: 24px;
            font-weight: 600;
        }

        .stat-label {
            font-size: 14px;
            color: #7A7A7A;
        }

        /* Секция загрузки скина */
        .upload-section {
            background: #F9F9F9;
            border-radius: 24px;
            padding: 32px;
            margin: 40px 0;
        }

        .upload-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .file-input-area {
            display: flex;
            gap: 12px;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .file-label {
            background: #fff;
            border: 1px solid #DDD;
            border-radius: 40px;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
        }

        .file-label:hover {
            background: #000;
            color: #fff;
            border-color: #000;
        }

        #fileName {
            color: #7A7A7A;
            font-size: 14px;
        }

        .upload-btn {
            background: #000;
            color: #fff;
            border: none;
            border-radius: 40px;
            padding: 12px 30px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            transition: opacity 0.2s;
        }

        .upload-btn:disabled {
            opacity: 0.3;
            cursor: not-allowed;
        }

        /* Результат загрузки */
        .result-section {
            background: #F0F7FF;
            border-radius: 24px;
            padding: 24px;
            margin-top: 24px;
            display: none;
        }

        .result-section h4 {
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .link-box {
            display: flex;
            gap: 8px;
            background: #fff;
            border-radius: 40px;
            padding: 4px 4px 4px 20px;
            margin: 16px 0;
            border: 1px solid #DDD;
        }

        .link-box input {
            flex: 1;
            border: none;
            padding: 12px 0;
            font-family: 'Inter', sans-serif;
            outline: none;
            font-size: 14px;
        }

        .copy-btn {
            background: #000;
            color: #fff;
            border: none;
            border-radius: 40px;
            padding: 8px 20px;
            cursor: pointer;
            font-weight: 500;
            transition: opacity 0.2s;
        }

        .copy-btn:hover {
            opacity: 0.8;
        }

        .command-box {
            background: #1E1E1E;
            color: #fff;
            padding: 12px 20px;
            border-radius: 40px;
            font-family: monospace;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 16px 0;
        }

        .command-text {
            font-size: 14px;
            word-break: break-all;
        }

        .small-btn {
            background: transparent;
            border: 1px solid #fff;
            color: #fff;
            border-radius: 30px;
            padding: 4px 12px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .small-btn:hover {
            background: #fff;
            color: #000;
        }

        /* Табы */
        .tabs {
            display: flex;
            gap: 4px;
            margin: 40px 0 20px;
            border-bottom: 1px solid #EDEDED;
        }

        .tab {
            padding: 12px 24px;
            font-weight: 500;
            color: #7A7A7A;
            cursor: pointer;
            border-bottom: 2px solid transparent;
            transition: all 0.15s;
        }

        .tab.active {
            color: #000;
            border-bottom-color: #000;
        }

        .tab-content {
            display: none;
            padding: 40px 0;
        }

        .tab-content.active {
            display: block;
        }

        /* Сетка товаров */
        .items-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 24px;
            margin-top: 24px;
        }

        /* Карточка товара */
        .item-card {
            background: #F9F9F9;
            border-radius: 20px;
            padding: 20px;
            border: 1px solid #F0F0F0;
            transition: opacity 0.3s, transform 0.3s;
        }

        .item-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .item-name {
            font-weight: 600;
            font-size: 18px;
        }

        .item-status {
            font-size: 12px;
            padding: 4px 10px;
            border-radius: 40px;
            background: #E8E8E8;
        }

        .item-status.active {
            background: #C8F0C8;
            color: #1A5A1A;
        }

        .item-price {
            font-weight: 600;
            margin: 12px 0;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .item-actions {
            display: flex;
            gap: 8px;
            margin-top: 16px;
        }

        .item-btn {
            background: transparent;
            border: 1px solid #D4D4D4;
            border-radius: 30px;
            padding: 6px 16px;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.15s;
            font-family: 'Inter', sans-serif;
        }

        .item-btn:hover {
            background: #000;
            color: #fff;
            border-color: #000;
        }

        .item-btn.delete:hover {
            background: #dc3545;
            border-color: #dc3545;
        }

        /* Музыка */
        .music-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .music-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px 20px;
            background: #F9F9F9;
            border-radius: 60px;
            border: 1px solid #F0F0F0;
            transition: opacity 0.3s, transform 0.3s;
        }

        .music-play {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #000;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .music-play:hover {
            transform: scale(1.05);
        }

        .music-info {
            flex: 1;
        }

        .music-title {
            font-weight: 600;
            margin-bottom: 4px;
        }

        .music-artist {
            font-size: 14px;
            color: #7A7A7A;
        }

        .music-actions {
            display: flex;
            gap: 8px;
        }

        .music-btn {
            background: transparent;
            border: 1px solid #D4D4D4;
            border-radius: 30px;
            padding: 6px 16px;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.15s;
        }

        .music-btn:hover {
            background: #000;
            color: #fff;
            border-color: #000;
        }

        /* Форма добавления товара */
        .add-form {
            background: #F9F9F9;
            border-radius: 24px;
            padding: 32px;
            margin-bottom: 40px;
        }

        .form-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 24px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr auto;
            gap: 16px;
            align-items: end;
        }

        .form-field {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .form-field label {
            font-size: 13px;
            font-weight: 500;
            color: #7A7A7A;
        }

        .form-field input, 
        .form-field select {
            padding: 12px 16px;
            border: 1px solid #DDD;
            border-radius: 40px;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            background: #fff;
        }

        .form-field input:focus {
            outline: none;
            border-color: #000;
        }

        .submit-btn {
            background: #000;
            color: #fff;
            border: none;
            border-radius: 40px;
            padding: 12px 24px;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            height: 48px;
            white-space: nowrap;
            transition: opacity 0.2s;
        }

        .submit-btn:hover {
            opacity: 0.8;
        }

        .submit-btn:disabled {
            opacity: 0.3;
            cursor: not-allowed;
        }

        /* Загрузка музыки */
        .upload-area {
            border: 2px dashed #DDD;
            border-radius: 40px;
            padding: 40px;
            text-align: center;
            margin: 24px 0;
            cursor: pointer;
            transition: all 0.15s;
        }

        .upload-area:hover {
            border-color: #000;
            background: #F5F5F5;
        }

        .upload-area i {
            font-size: 40px;
            color: #AAA;
            margin-bottom: 12px;
        }

        /* Футер */
        .footer {
            padding: 48px 0;
            background-color: #F9F9F9;
            border-top: 1px solid #EDEDED;
            margin-top: 60px;
        }

        .footer .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .footer-text {
            color: #7D7D7D;
            font-size: 14px;
        }

        .social-links a {
            color: #4A4A4A;
            margin-left: 24px;
            font-size: 20px;
            transition: color 0.2s;
        }

        .social-links a:hover {
            color: #000;
        }

        /* Уведомления */
        .notification {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #000;
            color: #fff;
            padding: 16px 24px;
            border-radius: 60px;
            font-size: 14px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            display: none;
            z-index: 1000;
            animation: slideIn 0.3s;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* 3D кнопки поворота */
        .rotate-controls {
            display: flex;
            gap: 4px;
            margin-left: 8px;
        }

        /* Модальное окно */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1001;
        }

        .modal-content {
            background: #fff;
            padding: 32px;
            border-radius: 32px;
            max-width: 500px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
        }

        .modal-title {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .modal-close {
            float: right;
            font-size: 24px;
            cursor: pointer;
            opacity: 0.5;
            transition: opacity 0.2s;
        }

        .modal-close:hover {
            opacity: 1;
        }
        .tooltip {
  position: relative;
  display: inline-block;
  cursor: pointer;
}

.tooltip .tooltip-text {
  visibility: hidden;
  background-color: #333;
  color: #fff;
  text-align: center;
  padding: 5px 10px;
  border-radius: 6px;
  position: absolute;
  bottom: 125%;
  left: 50%;
  transform: translateX(-50%);
  white-space: nowrap;
  font-size: 14px;
  opacity: 0;
  transition: opacity 0.3s;
}

.tooltip:hover .tooltip-text {
  visibility: visible;
  opacity: 1;
}
    </style>
</head>
<body>
    <header class="header">
        <div class="container header-inner">
            <a href="index.html" class="logo">DreamWood <span>profile</span></a>
            <div class="nav">
                <a href="index.html">Главная</a>
                <a href="shop.html">Магазин</a>
                <a href="profile.html" class="active">Кабинет</a>
                <a href="#">Discord</a>
                <a href="/admin">Admin Panel</a>
            </div>
        </div>
    </header>

    <main class="container">
        <audio src="assets/kolokolnia.mp3" id="notif"></audio>
        <!-- Профиль -->
        <div class="profile-header">
            <div class="skin-preview">
                <iframe 
  src="s.php?skin=http://localhost:1488<?php echo $settings['skinpath'] ?>&iframe=1"
  width="200" 
  height="400" 
  frameborder="0">
</iframe>
               
            </div>
            
            <div class="profile-info">
                <div class="profile-name">
                    <?php
                        if ($user['auth_l']) {
                            echo <<<HTML
                    <div class="tooltip">
                    <i class="fa-solid fa-plug-circle-check"></i>
                    <span class="tooltip-text">Аккаунт авторизирован в лаунчере</span>
                    </div> 
HTML;
                        }

                    ?>
                    <span id="displayName"><?php echo $user['username'] ?></span>
                    <?php
                        if(!empty($user['suffix'])) {
                            echo <<<HTML

                            <span class="profile-badge">{$user['suffix']}</span>
HTML;
                        }
                    
                    ?>
                </div>
                
                <?php
                   
                
                ?>
                 <div class="username-input">
                    <input type="text" id="username" class="username-field" placeholder="Ваш парол  здесь" value="Ваш пароль">
                    <button class="update-btn" onclick="updateSkin(document.getElementById('username').value)">Сменить  пароль</button>
                </div>

                <div class="profile-stats">
                    <div class="stat">
                        <span class="stat-value" id="statProducts">загрузка...</span>
                        <span class="stat-label">наиграно</span>
                    </div>
                    <div class="stat">
                        <span class="stat-value" id="statSales">загрузка...</span>
                        <span class="stat-label">репутация</span>
                    </div>
                    <div class="stat">
                        <span class="stat-value" id="statTracks">загрузка...</span>
                        <span class="stat-label">донат-коины</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Секция загрузки скина -->
        <div class="upload-section">
            <div class="upload-title">
                <i class="fas fa-cloud-upload-alt"></i> Загрузить свой скин
            </div>

            <div class="file-input-area">
                <label for="skinFile" class="file-label">
                    <i class="fas fa-folder-open"></i> Выбрать файл
                </label>
                <input type="file" id="skinFile" accept=".png,.jpg,.jpeg" style="display: none;">
                <span id="fileName">Файл не выбран</span>
            </div>

            <div style="display: flex; gap: 10px;">
                <button class="upload-btn" id="uploadBtn" disabled onclick="uploadSkin()">
                    Загрузить скин
                </button>
                <button class="skin-btn" onclick="showSkinHistory()">
                    <i class="fas fa-history"></i> История
                </button>
            </div>

            <!-- Результат загрузки -->
            <div id="resultSection" class="result-section">
                <h4><i class="fas fa-check-circle" style="color: #00AA00;"></i> Скин загружен!</h4>
                
                <div class="link-box">
                    <input type="text" id="skinLink" readonly value="https://dreamwood.space/skins/skin.png">
                    <button class="copy-btn" onclick="copyLink()">Копировать</button>
                </div>

                <div class="command-box">
                    <span class="command-text" id="skinCommand">/skin set ссылка</span>
                    <button class="small-btn" onclick="copyCommand()">Копировать</button>
                </div>

                <p style="font-size: 13px; color: #555;">
                    <i class="fas fa-info-circle"></i> Вставь эту команду на сервере, чтобы установить скин
                </p>
            </div>
        </div>

        <!-- Табы -->
        <div class="tabs">
            <div class="tab active" onclick="switchTab('items', event)">Мои товары</div>
            <div class="tab" onclick="switchTab('security', event)">Безопасность</div>
            <div class="tab" onclick="switchTab('stats', event)">Статистика</div>
        </div>

        <!-- Вкладка: Товары -->
        <div id="tab-items" class="tab-content active">
            <!-- Форма добавления товара -->
            <div class="add-form">
                <div class="form-title">Выложить новый товар</div>
                <div class="form-grid">
                    <div class="form-field">
                        <label>Название товара</label>
                        <input type="text" id="productName" placeholder="Меч из незерита">
                    </div>
                    <div class="form-field">
                        <label>Цена</label>
                        <input type="text" id="productPrice" placeholder="10 алмазов">
                    </div>
                    <div class="form-field">
                        <label>Категория</label>
                        <select id="productCategory">
                            <option>Оружие</option>
                            <option>Броня</option>
                            <option>Артефакты</option>
                            <option>Ресурсы</option>
                            <option>Еда</option>
                            <option>Постройки</option>
                        </select>
                    </div>
                    <button class="submit-btn" onclick="addProduct()">➕ Добавить</button>
                </div>
                <div class="form-field" style="margin-top: 16px;">
                    <label>Описание</label>
                    <input type="text" id="productDesc" placeholder="Зачарованный меч с Sharpness V...">
                </div>
            </div>

            <!-- Список товаров -->
            <div class="items-grid" id="productsGrid">
                <!-- Товар 1 -->
                <div class="item-card">
                    <div class="item-header">
                        <span class="item-name">Меч из незерита</span>
                        <span class="item-status active">Активен</span>
                    </div>
                    <p style="font-size: 14px; color: #666; margin-bottom: 12px;">Зачарованный меч с Sharpness V. Почти не ломается.</p>
                    <div class="item-price">
                        <i class="fas fa-gem" style="color: #2AA9C9;"></i> 10 алмазов
                    </div>
                    <div class="item-actions">
                        <button class="item-btn">✏️ Редактировать</button>
                        <button class="item-btn delete" onclick="removeProduct(this)">🗑️ Удалить</button>
                    </div>
                </div>

                <!-- Товар 2 -->
                <div class="item-card">
                    <div class="item-header">
                        <span class="item-name">Алмазный нагрудник</span>
                        <span class="item-status active">Активен</span>
                    </div>
                    <p style="font-size: 14px; color: #666; margin-bottom: 12px;">Protection IV, Thorns III. Отличная защита.</p>
                    <div class="item-price">
                        <i class="fas fa-cube" style="color: #A8A8A8;"></i> 23 железа
                    </div>
                    <div class="item-actions">
                        <button class="item-btn">✏️ Редактировать</button>
                        <button class="item-btn delete" onclick="removeProduct(this)">🗑️ Удалить</button>
                    </div>
                </div>

                <!-- Товар 3 -->
                <div class="item-card">
                    <div class="item-header">
                        <span class="item-name">Кольцо невидимости</span>
                        <span class="item-status">Скрыт</span>
                    </div>
                    <p style="font-size: 14px; color: #666; margin-bottom: 12px;">Редкий артефакт. Невидимость на 10 сек.</p>
                    <div class="item-price">
                        <i class="fas fa-gem" style="color: #50C878;"></i> 5 изумрудов
                    </div>
                    <div class="item-actions">
                        <button class="item-btn">✏️ Редактировать</button>
                        <button class="item-btn delete" onclick="removeProduct(this)">🗑️ Удалить</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Вкладка: Музыка -->
        <!-- Вкладка: Безопасность (вместо музыки) -->
<div id="tab-security" class="tab-content">
    
    <!-- Подключение лаунчера -->
    <div class="add-form" style="margin-bottom: 30px;">
        <div class="form-title">
            <i class="fas fa-download"></i> Подключение лаунчера
        </div>
        
      
        
        <!-- Статус подключения лаунчера -->
        <div class="security-card" style="background: #F0F7FF; border-radius: 20px; padding: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
                <div>
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <i class="fas fa-plug" style="font-size: 24px; color: #00AA00;"></i>
                        <div>
                            <div style="font-weight: 600;">Статус лаунчера</div>
                            <div style="font-size: 14px; color: #666;" id="launcherStatus"><?php if($user['auth_l']) {echo "Подключен";} else {echo "Не подключен";}  ?></div>
                        </div>
                    </div>
                </div>
                <?php 
                if (!$user['auth_l']) {
                    echo <<<HTML
                    <button class="security-btn" onclick="connectLauncher()" style="background: #000; color: white; border: none; padding: 10px 20px; border-radius: 40px; cursor: pointer;">
                    <i class="fas fa-link"></i> Подключить лаунчер
                </button>
HTML;
                }
                
                ?>
            </div>
        </div>
    </div>
<audio src="/assets/error.mp3" id="error"></audio>
<audio src="/assets/net.mp3" id="net"></audio>
    <!-- Подключенные устройства -->
    <div class="security-card" style="background: #F9F9F9; border-radius: 24px; padding: 24px; margin-bottom: 30px;">
        <div class="form-title" style="margin-bottom: 20px;">
            <i class="fas fa-laptop"></i> Подключенные устройства
        </div>
        
        <div id="devicesList">
            <div class="device-item" style="display: flex; justify-content: space-between; align-items: center; padding: 16px; border-bottom: 1px solid #E0E0E0;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <i class="fas fa-desktop" style="font-size: 24px; color: #667eea;"></i>
                    <div>
                        <div style="font-weight: 600;">Windows PC - Основное устройство</div>
                        <div style="font-size: 12px; color: #666;">IP: 67.67.67.67 • Последний вход: сегодня, 15:30</div>
                    </div>
                </div>
                <div>
                    <button class="device-logout" onclick="logoutDevice()" style="background: none; border: none; color: #999; cursor: pointer; font-size: 14px;">
                        <i class="fas fa-sign-out-alt"></i> Отключить
                    </button>
                </div>
            </div>
            
            
        </div>
        
        <button onclick="logoutDevice()" style="margin-top: 20px; background: none; border: 1px solid #DDD; border-radius: 40px; padding: 8px 20px; cursor: pointer;">
            <i class="fas fa-history"></i> Показать историю входов
        </button>
    </div>

    <!-- Telegram привязка -->
    <div class="security-card" style="background: #f9f9f9; border-radius: 24px; padding: 24px; margin-bottom: 30px;">
        <div class="form-title" style="margin-bottom: 16px;">
            <i class="fab fa-telegram"></i> Telegram привязка
        </div>
        
        <div id="telegramStatus">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                <div id="telegramIcon" style="width: 50px; height: 50px; background: #cfcfcf; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i class="fab fa-telegram" style="font-size: 24px; color: #999999;"></i>
                </div>
                <div>
                    <div style="font-weight: 600;" id="telegramText"><?php if ($user['auth_tg'] == 1) {echo "Привязан";} else {echo "Не привязан";}?></div>
                    <div style="font-size: 12px; color: #666666;" id="telegramSubtext"><?php if ($user['auth_tg'] == 1) {echo "Привязанный аккаунт: @{$settings['tguser']}";} else {echo "Привяжите Telegram для входа через бота";}?></div>
                </div>
            </div>
            
            <div class="telegram-buttons" id="telegramButtons">
                <?php
                if ($user['auth_tg'] == 1) {
                     echo <<<HTML
<button class="security-btn" onclick="disconnectTelegram()" style="background: #e41e1e; color: white; border: none; padding: 10px 20px; border-radius: 40px; cursor: pointer;">
    <i class="fab fa-telegram"></i> Отвязать Telegram
</button>
HTML;
                } else {
echo <<<HTML
<button class="security-btn" onclick="connectTelegram()" style="background: #0088cc; color: white; border: none; padding: 10px 20px; border-radius: 40px; cursor: pointer;">
    <i class="fab fa-telegram"></i> Привязать Telegram
</button>
HTML;
                }
                ?>
            </div>
        </div>
    </div>

    <!-- 2FA и безопасность -->
    <div class="security-card" style="background: #F9F9F9; border-radius: 24px; padding: 24px;">
        <div class="form-title" style="margin-bottom: 20px;">
            <i class="fas fa-shield-alt"></i> Дополнительная безопасность
        </div>
        
        <div style="display: grid; gap: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
                <div>
                    <div style="font-weight: 600;">Двухфакторная аутентификация</div>
                    <div style="font-size: 14px; color: #666;">Защитите аккаунт дополнительным кодом при входе</div>
                </div>
                <label class="switch">
                    <input type="checkbox" id="twoFactorToggle" onchange="toggleTwoFactor()" <?php if($settings['dvyhetapka'] == 1) {echo "checked";} else {echo '';}?>>
                    <span class="slider round"></span>
                </label>
            </div>
            
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
                <div>
                    <div style="font-weight: 600;">Уведомления о входе в Telegram</div>
                    <div style="font-size: 14px; color: #666;">Получать уведомления при входе в аккаунт</div>
                </div>
                <label class="switch">
                    <input type="checkbox" id="loginNotifyToggle" <?php if($settings['notif'] == 1) {echo "checked";} else {echo '';}?> onchange="toggleLoginNotify()">
                    <span class="slider round"></span>
                </label>
            </div>
            
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
                <div>
                    <div style="font-weight: 600;">Вход через Telegram</div>
                    <div style="font-size: 14px; color: #666;">Вход в аккаунт без пароля через Telegram бота</div>
                </div>
                <label class="switch">
                    <input type="checkbox" id="telegramLoginToggle" onchange="toggletelegram()" <?php if($settings['telegram'] == 1) {echo "checked";} else {echo '';}?>>
                    <span class="slider round"></span>
                </label>
            </div>
        </div>
        
        <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid #E0E0E0;">
            <button class="security-btn" onclick="showLoginHistory()" style="background: none; border: 1px solid #DDD; border-radius: 40px; padding: 10px 20px; margin-right: 12px; cursor: pointer;">
                <i class="fas fa-history"></i> История входов
            </button>
            <button class="security-btn" onclick="showLoginHistory()" style="background: #dc3545; color: white; border: none; border-radius: 40px; padding: 10px 20px; cursor: pointer;">
                <i class="fas fa-sign-out-alt"></i> Выйти на всех устройствах
            </button>
        </div>
    </div>
</div>

<!-- Добавить в стили CSS переключатели (switch) -->
<style>
    /* Безопасность - карточки */
    .security-card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .security-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.05);
    }
    
    /* Переключатель (switch) */
    .switch {
        position: relative;
        display: inline-block;
        width: 52px;
        height: 28px;
    }
    
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: 0.3s;
    }
    
    .slider:before {
        position: absolute;
        content: "";
        height: 22px;
        width: 22px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: 0.3s;
    }
    
    input:checked + .slider {
        background-color: #000;
    }
    
    input:checked + .slider:before {
        transform: translateX(24px);
    }
    
    .slider.round {
        border-radius: 34px;
    }
    
    .slider.round:before {
        border-radius: 50%;
    }
    
    .device-item {
        transition: background 0.2s;
    }
    
    .device-item:hover {
        background: #f0f0f0;
    }
    
    .device-logout:hover {
        color: #dc3545 !important;
    }
    
    .security-btn {
        transition: opacity 0.2s, transform 0.1s;
    }
    
    .security-btn:active {
        transform: scale(0.97);
    }
    
    /* Анимация для уведомлений */
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
</style>



        <!-- Вкладка: Статистика -->
        <div id="tab-stats" class="tab-content">
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px;">
                <div style="background: #F9F9F9; padding: 32px; border-radius: 24px; text-align: center;">
                    <i class="fas fa-shopping-cart" style="font-size: 40px; margin-bottom: 16px;"></i>
                    <div style="font-size: 36px; font-weight: 600;">8</div>
                    <div style="color: #7A7A7A;">Продаж всего</div>
                </div>
                <div style="background: #F9F9F9; padding: 32px; border-radius: 24px; text-align: center;">
                    <i class="fas fa-gem" style="font-size: 40px; margin-bottom: 16px;"></i>
                    <div style="font-size: 36px; font-weight: 600;">127</div>
                    <div style="color: #7A7A7A;">Заработано алмазов</div>
                </div>
                <div style="background: #F9F9F9; padding: 32px; border-radius: 24px; text-align: center;">
                    <i class="fas fa-music" style="font-size: 40px; margin-bottom: 16px;"></i>
                    <div style="font-size: 36px; font-weight: 600;">23</div>
                    <div style="color: #7A7A7A;">Прослушиваний</div>
                </div>
            </div>

            <div style="margin-top: 40px; background: #F9F9F9; padding: 32px; border-radius: 24px;">
                <h3 style="margin-bottom: 20px;">Последние продажи</h3>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr style="border-bottom: 1px solid #DDD;">
                        <th style="text-align: left; padding: 12px 0;">Товар</th>
                        <th style="text-align: left;">Покупатель</th>
                        <th style="text-align: left;">Цена</th>
                        <th style="text-align: left;">Дата</th>
                    </tr>
                    <tr>
                        <td style="padding: 12px 0;">Меч из незерита</td>
                        <td>Alex_2000</td>
                        <td>10 алмазов</td>
                        <td>12.03.2026</td>
                    </tr>
                    <tr>
                        <td style="padding: 12px 0;">Золотая морковь x32</td>
                        <td>Farmer_Joe</td>
                        <td>8 золота</td>
                        <td>10.03.2026</td>
                    </tr>
                </table>
            </div>
        </div>
    </main>

    <!-- Модальное окно для истории скинов -->
    <div class="modal" id="skinHistoryModal">
        <div class="modal-content">
            <span class="modal-close" onclick="closeModal('skinHistoryModal')">&times;</span>
            <div class="modal-title">📜 История ваших скинов</div>
            <div id="skinHistoryList"></div>
        </div>
    </div>

    <!-- Уведомление -->
    <div class="notification" id="notification">Уведомление ххыххвыъзфхзвхыфвфвщыъвфвщфхвффлрапвл</div>

    <footer class="footer">
        <div class="container">
            <div class="footer-text">© 2025 DreamWood. Личный кабинет игрока.</div>
            <div class="social-links">
                <a href="#"><i class="fab fa-discord"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-youtube"></i></a>
            </div>
        </div>
    </footer>

   <script src="/assets/scripts/account.js"></script>
</body>
</html>