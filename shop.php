<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DreamWood | Магазин</title>
    <!-- Подключаем шрифт Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <!-- Подключаем ТВОЙ файл с премиум-иконками -->
    <link rel="stylesheet" href="https://knede4.github.io/css/all.css">
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

        /* Секции */
        .section {
            padding: 60px 0;
        }

        .section-title {
            font-size: 36px;
            font-weight: 500;
            letter-spacing: -0.02em;
            margin-bottom: 16px;
            color: #000;
        }

        .section-desc {
            font-size: 18px;
            font-weight: 300;
            color: #5A5A5A;
            max-width: 700px;
            margin-bottom: 48px;
        }

        /* Сетка товаров */
        .shop-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 40px;
        }

        /* Карточка товара */
        .product-card {
            background: #F9F9F9;
            border-radius: 24px;
            overflow: hidden;
            transition: transform 0.2s ease;
            border: 1px solid #F0F0F0;
            display: flex;
            flex-direction: column;
        }

        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px -10px rgba(0,0,0,0.1);
        }

        /* Изображение товара */
        .product-image {
            height: 160px;
            background: #E8E8E8;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 64px;
            color: #333;
            position: relative;
        }

        /* Категория товара */
        .product-category {
            position: absolute;
            top: 16px;
            right: 16px;
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(4px);
            padding: 6px 12px;
            border-radius: 40px;
            font-size: 12px;
            font-weight: 600;
            color: #000;
            border: 1px solid #DDD;
        }

        /* Информация о товаре */
        .product-info {
            padding: 24px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .product-name {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #000;
        }

        .product-description {
            font-size: 14px;
            font-weight: 300;
            color: #6A6A6A;
            margin-bottom: 16px;
            line-height: 1.4;
            flex: 1;
        }

        /* Продавец */
        .product-seller {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 16px;
            font-size: 14px;
            color: #4A4A4A;
            border-top: 1px dashed #DDD;
            padding-top: 16px;
        }

        .seller-avatar {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #D0D0D0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            color: #555;
        }

        .seller-name {
            font-weight: 500;
        }

        .seller-badge {
            background: #E0E0E0;
            border-radius: 40px;
            padding: 2px 8px;
            font-size: 10px;
            font-weight: 600;
            color: #444;
        }

        /* Цена и кнопка */
        .product-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: auto;
        }

        .product-price {
            display: flex;
            align-items: center;
            gap: 6px;
            font-weight: 600;
            font-size: 18px;
            background: #F0F0F0;
            padding: 6px 14px;
            border-radius: 40px;
        }

        .price-icon {
            font-size: 16px;
        }

        /* Разные цвета для разных валют */
        .price-diamond .price-icon { color: #2AA9C9; }  /* алмаз */
        .price-iron .price-icon { color: #A8A8A8; }    /* железо */
        .price-gold .price-icon { color: #FFB347; }    /* золото */
        .price-emerald .price-icon { color: #50C878; } /* изумруд */
        .price-netherite .price-icon { color: #4A3B3B; } /* незерит */
        .price-other .price-icon { color: #888; }

        .buy-btn {
            background: transparent;
            border: 1.5px solid #1E1E1E;
            border-radius: 40px;
            padding: 8px 20px;
            font-size: 14px;
            font-weight: 600;
            color: #1E1E1E;
            cursor: pointer;
            transition: all 0.15s ease;
            font-family: 'Inter', sans-serif;
        }

        .buy-btn:hover {
            background: #1E1E1E;
            color: #fff;
        }

        /* Категории фильтров */
        .categories {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin: 30px 0 20px;
        }

        .category-btn {
            background: transparent;
            border: 1px solid #D4D4D4;
            border-radius: 40px;
            padding: 8px 20px;
            font-size: 14px;
            font-weight: 500;
            color: #4A4A4A;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .category-btn:hover,
        .category-btn.active {
            background: #000;
            border-color: #000;
            color: #fff;
        }

        /* Футер */
        .footer {
            padding: 48px 0;
            background-color: #F9F9F9;
            border-top: 1px solid #EDEDED;
            margin-top: 60px;
        }

        .footer-text {
            color: #7D7D7D;
            font-size: 14px;
            font-weight: 300;
        }

        .footer .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
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

        /* Адаптация */
        @media (max-width: 700px) {
            .header-inner {
                flex-direction: column;
                gap: 16px;
            }
            .nav a {
                margin: 0 12px;
            }
        }

        /* Стили для валют */
        .currency-diamond { color: #2AA9C9; }
        .currency-iron { color: #A8A8A8; }
        .currency-gold { color: #FFB347; }
        .currency-emerald { color: #50C878; }
        .currency-netherite { color: #4A3B3B; }
    </style>
</head>
<body>
    <header class="header">
        <div class="container header-inner">
            <a href="index.html" class="logo">DreamWood <span>market</span></a>
            <div class="nav">
                <a href="index.html">Главная</a>
                <a href="shop.html" class="active">Магазин</a>
                <a href="#">Гайды</a>
                <a href="#">Discord</a>
            </div>
        </div>
    </header>

    <main>
        <div class="container">
            <section class="section">
                <h1 class="section-title">Рынок DreamWood</h1>
                <div class="section-desc">Торговая площадка сервера. Каждый продавец сам назначает цену — алмазы, железо, изумруды или что-то ещё.</div>

                <!-- Фильтры по категориям -->
                <div class="categories">
                    <button class="category-btn active">Все</button>
                    <button class="category-btn">Оружие</button>
                    <button class="category-btn">Броня</button>
                    <button class="category-btn">Артефакты</button>
                    <button class="category-btn">Ресурсы</button>
                    <button class="category-btn">Постройки</button>
                    <button class="category-btn">Еда</button>
                </div>

                <!-- Сетка товаров с разной валютой -->
                <div class="shop-grid">
                    <!-- Товар 1: Алмазы -->
                    <div class="product-card">
                        <div class="product-image">
                            <i class="fas fa-sword"></i>
                            <span class="product-category">Оружие</span>
                        </div>
                        <div class="product-info">
                            <h3 class="product-name">ааваыаывыа</h3>
                            <p class="product-description">вааврлавфлравлравылрвоыая.</p>
                            <div class="product-seller">
                                <div class="seller-avatar"><i class="fas fa-user"></i></div>
                                <span class="seller-name">дибил</span>
                                <span class="seller-badge">Игрок</span>
                            </div>
                            <div class="product-footer">
                                <div class="product-price price-diamond">
                                    <i class="fas fa-gem price-icon"></i> 10 алмазов
                                </div>
                                <button class="buy-btn">Написать</button>
                            </div>
                        </div>
                    </div>

                    <!-- Товар 2: Железо -->
                    <div class="product-card">
                        <div class="product-image">
                            <i class="fas fa-helmet-battle"></i>
                            <span class="product-category">Броня</span>
                        </div>
                        <div class="product-info">
                            <h3 class="product-name">авлолаофвлжлжфажлжавлжваав</h3>
                            <p class="product-description">авыдлажырфаождвраоувраывраыа.</p>
                            <div class="product-seller">
                                <div class="seller-avatar"><i class="fas fa-user"></i></div>
                                <span class="seller-name">Alex_2000</span>
                                <span class="seller-badge">Игрок бурмалдок</span>
                            </div>
                            <div class="product-footer">
                                <div class="product-price price-iron">
                                    <i class="fas fa-cube price-icon"></i> 23 железа
                                </div>
                                <button class="buy-btn">Написать</button>
                            </div>
                        </div>
                    </div>

                    <!-- Товар 3: Изумруды -->
                    <div class="product-card">
                        <div class="product-image">
                            <i class="fas fa-ring"></i>
                            <span class="product-category">Артефакт</span>
                        </div>
                        <div class="product-info">
                            <h3 class="product-name">Кольцо пилилность</h3>
                            <p class="product-description">Разрывает плнету на куски</p>
                            <div class="product-seller">
                                <div class="seller-avatar"><i class="fas fa-user"></i></div>
                                <span class="seller-name">Wizard_Mike</span>
                                <span class="seller-badge">Прикольность</span>
                            </div>
                            <div class="product-footer">
                                <div class="product-price price-emerald">
                                    <i class="fas fa-gem price-icon"></i> 5 изумрудов
                                </div>
                                <button class="buy-btn">Написать</button>
                            </div>
                        </div>
                    </div>

                    <!-- Товар 4: Золото -->
                    <div class="product-card">
                        <div class="product-image">
                            <i class="fas fa-apple-alt"></i>
                            <span class="product-category">Еда</span>
                        </div>
                        <div class="product-info">
                            <h3 class="product-name">Золотая морковь x32</h3>
                            <p class="product-description">Стак золотой моркови. Отличная еда для дальних походов.</p>
                            <div class="product-seller">
                                <div class="seller-avatar"><i class="fas fa-user"></i></div>
                                <span class="seller-name">Farmer_Joe</span>
                                <span class="seller-badge">Фермер</span>
                            </div>
                            <div class="product-footer">
                                <div class="product-price price-gold">
                                    <i class="fas fa-coins price-icon"></i> 8 золота
                                </div>
                                <button class="buy-btn">Написать</button>
                            </div>
                        </div>
                    </div>

                    <!-- Товар 5: Алмазы + железо (смешанная) -->
                    <div class="product-card">
                        <div class="product-image">
                            <i class="fas fa-treasure-chest"></i>
                            <span class="product-category">Ресурсы</span>
                        </div>
                        <div class="product-info">
                            <h3 class="product-name">Древние обломки x8</h3>
                            <p class="product-description">8 штук древних обломков. Хватит на незеритовый слиток.</p>
                            <div class="product-seller">
                                <div class="seller-avatar"><i class="fas fa-user"></i></div>
                                <span class="seller-name">Miner_Pro</span>
                                <span class="seller-badge">Шахтёр</span>
                            </div>
                            <div class="product-footer">
                                <div class="product-price price-other">
                                    <i class="fas fa-exchange-alt price-icon"></i> 5 алм + 15 жел
                                </div>
                                <button class="buy-btn">Написать</button>
                            </div>
                        </div>
                    </div>

                    <!-- Товар 6: Незерит -->
                    <div class="product-card">
                        <div class="product-image">
                            <i class="fas fa-home"></i>
                            <span class="product-category">Постройка</span>
                        </div>
                        <div class="product-info">
                            <h3 class="product-name">Готовая база в горах</h3>
                            <p class="product-description">Небольшой дом с фермой и складом. Координаты отдам после покупки.</p>
                            <div class="product-seller">
                                <div class="seller-avatar"><i class="fas fa-user"></i></div>
                                <span class="seller-name">Builder_Bob</span>
                                <span class="seller-badge">Архитектор</span>
                            </div>
                            <div class="product-footer">
                                <div class="product-price price-netherite">
                                    <i class="fas fa-crown price-icon"></i> 2 незерита
                                </div>
                                <button class="buy-btn">Написать</button>
                            </div>
                        </div>
                    </div>

                    <!-- Товар 7: Договорная -->
                    <div class="product-card">
                        <div class="product-image">
                            <i class="fas fa-scroll"></i>
                            <span class="product-category">Магия</span>
                        </div>
                        <div class="product-info">
                            <h3 class="product-name">Свиток телепортации</h3>
                            <p class="product-description">Из магического мода. Телепортирует в заданную точку один раз.</p>
                            <div class="product-seller">
                                <div class="seller-avatar"><i class="fas fa-user"></i></div>
                                <span class="seller-name">Enchanter</span>
                                <span class="seller-badge">Чародей</span>
                            </div>
                            <div class="product-footer">
                                <div class="product-price price-other">
                                    <i class="fas fa-question price-icon"></i> Договорная
                                </div>
                                <button class="buy-btn">Написать</button>
                            </div>
                        </div>
                    </div>

                    <!-- Товар 8: Просто железо -->
                    <div class="product-card">
                        <div class="product-image">
                            <i class="fas fa-flask"></i>
                            <span class="product-category">Зелья</span>
                        </div>
                        <div class="product-info">
                            <h3 class="product-name">Зелье скорости II x3</h3>
                            <p class="product-description">Три пузырька отличного зелья из Brewery. Бегай быстрее ветра.</p>
                            <div class="product-seller">
                                <div class="seller-avatar"><i class="fas fa-user"></i></div>
                                <span class="seller-name">Brewmaster</span>
                                <span class="seller-badge">Алхимик</span>
                            </div>
                            <div class="product-footer">
                                <div class="product-price price-iron">
                                    <i class="fas fa-cube price-icon"></i> 12 железа
                                </div>
                                <button class="buy-btn">Написать</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Информация о торговле -->
                <div style="margin-top: 60px; padding: 30px; background: #F5F5F5; border-radius: 24px; text-align: center;">
                    <i class="fas fa-handshake" style="font-size: 48px; color: #000; margin-bottom: 16px;"></i>
                    <h3 style="font-size: 24px; font-weight: 500; margin-bottom: 8px;">Свободный рынок</h3>
                    <p style="color: #6A6A6A; max-width: 500px; margin: 0 auto;">Каждый сам решает, что хочет получить за свой товар. Алмазы, железо, изумруды, золото, незерит или обмен — договаривайтесь лично.</p>
                </div>
            </section>
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer-text">© 2025 DreamWood. Торговая площадка для игроков.</div>
            <div class="social-links">
                <a href="#"><i class="fab fa-discord"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-youtube"></i></a>
            </div>
        </div>
    </footer>

    <script>
        // Скрипт для кнопок "Написать"
        document.querySelectorAll('.buy-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const card = this.closest('.product-card');
                const productName = card.querySelector('.product-name').textContent;
                const seller = card.querySelector('.seller-name').textContent;
                alert(`Хочешь купить "${productName}" у ${seller}? Напиши ему в Discord!`);
            });
        });

        // Скрипт для фильтров (демо)
        document.querySelectorAll('.category-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.category-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                alert('Фильтр "' + this.textContent + '" работает в полной версии. Здесь демонстрация дизайна.');
            });
        });
    </script>
</body>
</html>