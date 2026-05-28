<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DreamWood | Minecraft Modded Server</title>
    <!-- Подключаем шрифт Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <!-- Font Awesome 6 Free (официальная) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/assets/styles/index.css">
  
</head>
<body>
    <header class="header">
        <div class="container header-inner">
            <a href="#" class="logo">DreamWood <span>House</span></a>
            <div class="nav">
                
                <a href="#" class="active">Главная</a>
                 <a href="shop.php">Магазин</a>
                  <a href="account.php">Кабинет</a>
                <a href="launcher">Лаунчер</a>
                
               
            </div>
        </div>
    </header>

    <main>
        <!-- Hero с правильным IP -->
        <section class="hero">
            <h1>DreamWood<br>модный приватный сервер</h1>
            <div class="subtitle">Индустрия, магия, приключения и уют — всё в одном месте. Только для своих.</div>
            <div class="ip-box">
                <span>Личный кабинет</span>
                <button class="copy-btn" onclick="login()"><i class="fa-solid fa-user"></i> Войти в аккаунт</button>
            </div>
        </section>

        <!-- Модальное окно входа -->
        <div class="modal" id="loginModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Вход в аккаунт</h2>
                    <span class="modal-close" onclick="closeModal()">&times;</span>
                </div>
                
                <div class="input-group">
                    <label>Логин </label>
                    <input type="text" id="loginUsername" placeholder="Ваш ник или email">
                </div>
                
                <div class="input-group">
                    <label>Пароль</label>
                    <div class="password-wrapper">
                        <input type="password" id="loginPassword" placeholder="••••••••">
                        <button type="button" class="toggle-password" onclick="togglePasswordVisibility()">
                            <i class="fa-regular fa-eye-slash" id="passwordToggleIcon"></i>
                        </button>
                    </div>
                </div>
                
                <div class="error-message" id="loginError"></div>
                
                <button class="login-btn" onclick="submitLogin()">Войти</button>
                
                <div class="modal-footer">
                    Нет аккаунта? <a href="#" onclick="alert('Заявка в Discord: discord.gg/dreamwood')">Подать заявку</a>
                </div>
            </div>
        </div>

        <!-- О проекте -->
        <div class="container">
            <section class="section">
                <h2 class="section-title">О проекте</h2>
                <div class="section-desc">Приватный модовый сервер с продуманным миром. 79+ модов, которые дополняют друг друга, создавая уникальный опыт.</div>
                
                <div class="stats">
                    <div class="stat-item">
                        <span class="stat-number">79+</span>
                        <span class="stat-label">модов</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">1.20.1</span>
                        <span class="stat-label">версия</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">Forge</span>
                        <span class="stat-label">API</span>
                    </div>
                </div>

                <a href="#" class="button">Подать заявку</a>
                <a href="#" class="button button-light">Discord</a>
            </section>
        </div>

        <!-- ЧТО МОЖНО ДЕЛАТЬ НА СЕРВЕРЕ -->
        <div class="container">
            <section class="section">
                <h2 class="section-title">Что можно делать на DreamWood</h2>
                <div class="section-desc">Мы собрали моды так, чтобы каждый нашёл занятие по душе. Вот лишь часть возможностей:</div>
                
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fas fa-cogs"></i></div>
                        <h3>Строй заводы</h3>
                        <p>Механизмы, шестерёнки, поезда и автоматизация. Строй настоящие фабрики с конвейерами и роботами.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fas fa-map"></i></div>
                        <h3>Исследуй новые миры</h3>
                        <p>Сотни новых биомов, деревень и загадочных мест. Каждый уголок мира хранит свои секреты.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fas fa-magic"></i></div>
                        <h3>Постигай магию</h3>
                        <p>Колдуй, призывай существ и создавай могущественные артефакты. Магия здесь — часть повседневности.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fas fa-paw"></i></div>
                        <h3>Встречай новых мобов</h3>
                        <p>Мир наполнен удивительными существами — от безобидных зверушек до опасных созданий.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fas fa-wine-bottle"></i></div>
                        <h3>Фермерь и готовь</h3>
                        <p>Выращивай культуры, вари зелья, пеки хлеб и делай своё вино. Гастрономический рай.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fas fa-thermometer-half"></i></div>
                        <h3>Борись за выживание</h3>
                        <p>Следи за температурой — мёрзни в снегах и перегревайся в пустыне. Смерть не страшна, вещи сохранятся.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fas fa-hammer"></i></div>
                        <h3>Декорируй с душой</h3>
                        <p>Мебель, украшения и мелкие детали для уютных построек. Строй не просто дома, а настоящие произведения искусства.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fas fa-crown"></i></div>
                        <h3>Сражайся с врагами</h3>
                        <p>Новые боссы, данжи и могущественные артефакты. Испытай себя в настоящих приключениях.</p>
                    </div>
                </div>
            </section>
        </div>

        <!-- Особенности приватного сервера -->
        <div class="container">
            <section class="section">
                <h2 class="section-title">Почему уютно</h2>
                <div class="server-features">
                    <div class="server-feature-item">
                        <i class="fas fa-user-lock"></i>
                        <div><strong>Приватный доступ</strong> — только по заявкам. Никаких гриферов, только адекватные люди.</div>
                    </div>
                    <div class="server-feature-item">
                        <i class="fas fa-microphone-alt"></i>
                        <div><strong>Голосовой чат</strong> — мод Simple Voice Chat для живого общения.</div>
                    </div>
                    <div class="server-feature-item">
                        <i class="fas fa-tachometer-alt"></i>
                        <div><strong>Оптимизация</strong> — Embeddium, FerriteCore, ModernFix, Canary. Всё летает даже на слабых ПК.</div>
                    </div>
                    <div class="server-feature-item">
                        <i class="fas fa-tree"></i>
                        <div><strong>Кастомный спаун</strong> — лесная точка возрождения с постройками от администрации.</div>
                    </div>
                    <div class="server-feature-item">
                        <i class="fas fa-calendar-alt"></i>
                        <div><strong>Ивенты и конкурсы</strong> — регулярные сборы, билд-битвы и лотереи.</div>
                    </div>
                    <div class="server-feature-item">
                        <i class="fas fa-shield-alt"></i>
                        <div><strong>Безопасность</strong> — есть защита построек, но без навязчивых приватов.</div>
                    </div>
                </div>
            </section>
        </div>

        <!-- Как попасть -->
        <div class="container">
            <section class="section">
                <h2 class="section-title">Как попасть на сервер</h2>
                <div style="display: flex; gap: 24px; flex-wrap: wrap; margin: 40px 0;">
                    <div style="flex: 1; min-width: 200px; background: #F7F7F7; border-radius: 24px; padding: 32px 24px; text-align: center;">
                        <i class="fab fa-discord" style="font-size: 40px; color: #000; margin-bottom: 16px;"></i>
                        <h3 style="font-weight: 600; margin-bottom: 8px;">Шаг 1</h3>
                        <p style="color: #5F5F5F;">Заходи в наш манго</p>
                    </div>
                    <div style="flex: 1; min-width: 200px; background: #F7F7F7; border-radius: 24px; padding: 32px 24px; text-align: center;">
                        <i class="fas fa-file-signature" style="font-size: 40px; color: #000; margin-bottom: 16px;"></i>
                        <h3 style="font-weight: 600; margin-bottom: 8px;">Шаг 2</h3>
                        <p style="color: #5F5F5F;">Заполняешь анкету</p>
                    </div>
                    <div style="flex: 1; min-width: 200px; background: #F7F7F7; border-radius: 24px; padding: 32px 24px; text-align: center;">
                        <i class="fas fa-download" style="font-size: 40px; color: #000; margin-bottom: 16px;"></i>
                        <h3 style="font-weight: 600; margin-bottom: 8px;">Шаг 3</h3>
                        <p style="color: #5F5F5F;">Скачиваешь модпак</p>
                    </div>
                    <div style="flex: 1; min-width: 200px; background: #F7F7F7; border-radius: 24px; padding: 32px 24px; text-align: center;">
                        <i class="fas fa-play" style="font-size: 40px; color: #000; margin-bottom: 16px;"></i>
                        <h3 style="font-weight: 600; margin-bottom: 8px;">Шаг 4</h3>
                        <p style="color: #5F5F5F;">Заходи и играй</p>
                    </div>
                </div>
                <a href="#" class="button">Войти в Discord</a>
            </section>
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer-text">© 2026 DreamWood. Неофициальный фанатский проект. Не принадлежит Mojang. <img src="mango.png" id="mangoimg" alt="Это же манго!" onclick="playmango()"></div>
            <audio src="/mangosound.mp3" id="mango"></audio>
            <div class="social-links">
                <a href="#"><i class="fab fa-discord"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-youtube"></i></a>
            </div>
        </div>
    </footer>

    <script src="/assets/scripts/index.js">
    
    </script>
    <script src="/mango/mango.js"></script>
</body>
</html>