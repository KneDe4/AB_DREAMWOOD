// ============== ОБЩИЕ ФУНКЦИИ ==============
        
        let currentRotation = 0;
        let currentAudio = null;
        let currentPlayBtn = null;
function connectLauncher() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', '/api/launcher/hash.php', true);
    
    xhr.onload = function() {
        if (xhr.status === 200) {
            const hash = xhr.responseText;
            window.open(`http://localhost:1488/launcher/auth?key=${hash}&data=okakpokoe`, '_blank');
        } else {
            showNotification('Ошибка получения ключа', true);
        }
    };
    
    xhr.onerror = function() {
        showNotification('Ошибка соединения', true);
    };
    
    xhr.send();
}
function connectTelegram() {
     const xhr = new XMLHttpRequest();
    xhr.open('GET', '/api/launcher/hash.php', true);
    
    xhr.onload = function() {
        if (xhr.status === 200) {
            const hash = xhr.responseText;
            window.open(`tg://resolve?domain=Dreamwoodinfobot&start=${hash}`, '_blank');
        } else {
            showNotification('Ошибка получения ключа', true);
        }
    };
    
    xhr.onerror = function() {
        showNotification('Ошибка соединения', true);
    };
    
    xhr.send();
}
function disconnectTelegram() {
     const xhr = new XMLHttpRequest();
    xhr.open('GET', '/api/launcher/hash.php', true);
    
    xhr.onload = function() {
        if (xhr.status === 200) {
            const hash = xhr.responseText;
            window.open(`tg://resolve?domain=Dreamwoodinfobot&start=${hash}%$%//disconect`, '_blank');
        } else {
            showNotification('Ошибка получения ключа', true);
        }
    };
    
    xhr.onerror = function() {
        showNotification('Ошибка соединения', true);
    };
    
    xhr.send();
}

function toggleTwoFactor() {
     const xhr = new XMLHttpRequest();
    xhr.open('GET', `/api/settings.php?fdsqd=dvyz&dsjsah=${document.getElementById("twoFactorToggle").checked}`, true);
    xhr.onerror = function() {
        showNotification('Ошибка соединения', true);
    };
    xhr.send();
}
function toggleLoginNotify() {
        const xhr = new XMLHttpRequest();
    xhr.open('GET', `/api/settings.php?fdsqd=logdsnpfsdj&dsjsah=${document.getElementById("loginNotifyToggle").checked}`, true);
    xhr.onerror = function() {
        showNotification('Ошибка соединения', true);
    };
    xhr.send();
}
function toggletelegram() {
        const xhr = new XMLHttpRequest();
    xhr.open('GET', `/api/settings.php?fdsqd=loginidasdandadhdasg&dsjsah=${document.getElementById("telegramLoginToggle").checked}`, true);
    xhr.onerror = function() {
        showNotification('Ошибка соединения', true);
    };
    xhr.send();
}

function logoutDevice() {
    document.getElementById('error').play()
}
function showLoginHistory() {
    document.getElementById('net').play()
    document.getElementById('net').volume = 10
}




        // Переключение табов
        function switchTab(tabName, event) {
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            
            event.target.classList.add('active');
            document.getElementById(`tab-${tabName}`).classList.add('active');
            
            if (tabName === 'music') {
                setTimeout(loadUserMusic, 100);
            }
        }

        // Показать уведомление
        function showNotification(text, isError = false) {
            document.getElementById("notif").play()
            const notif = document.getElementById('notification');
            notif.style.display = 'block';
            notif.textContent = text;
            notif.style.background = isError ? '#dc3545' : '#000';
            setTimeout(() => {
                notif.style.display = 'none';
            }, 2000);
        }

        // Экранирование HTML
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Открыть модальное окно
        function openModal(id) {
            document.getElementById(id).style.display = 'flex';
        }

        // Закрыть модальное окно
        function closeModal(id) {
            document.getElementById(id).style.display = 'none';
        }

        // ============== СКИНЫ ==============

        // тутууттутуут пу пупу 
        function updateSkin(pas) {
            const username = document.getElementById('username').value.trim();
            if (!username) {
                
            showNotification(`Введите пароллл!`);

                return;
            }
            fetch(`/api/account.php?d=chand&pas=${pas}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.text(); 
            })
            .then(data => {
                console.log('Данные:', data);
                showNotification(`Пароль сменен`);
            })
            .catch(error => {
                console.error('Ошибка:', error);
                showNotification(`Ошибка запроса`);
            });
           
            
            
            
            
            
        }

        // Поворот скина
        function rotateSkin(degrees) {
            currentRotation += degrees;
            const skinImg = document.getElementById('skinRender');
            const baseUrl = skinImg.src.split('?')[0];
            skinImg.src = `${baseUrl}?size=180&default=MHF_Steve&overlay&rotate=${currentRotation}&t=${Date.now()}`;
        }

        // Случайный скин
        function randomSkin() {
            const randomNames = ['DreamWalker', 'Notch', 'Herobrine', 'Steve', 'Alex', 'Jeb_', 'Grum', 'Dinnerbone'];
            const randomName = randomNames[Math.floor(Math.random() * randomNames.length)];
            document.getElementById('username').value = randomName;
            updateSkin();
        }

        // Выбор файла скина
        document.getElementById('skinFile').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                document.getElementById('fileName').textContent = file.name;
                document.getElementById('uploadBtn').disabled = false;
            }
        });

        // Загрузка скина
        function uploadSkin() {
            const fileInput = document.getElementById('skinFile');
            const file = fileInput.files[0];
            
            if (!file) {
                showNotification('Выберите файл для загрузки', true);
                return;
            }

            if (file.size > 5 * 1024 * 1024) {
                showNotification('Файл слишком большой (максимум 5 МБ)', true);
                return;
            }

            if (!file.type.match('image/png') && !file.type.match('image/jpeg')) {
                showNotification('Разрешены только PNG и JPG файлы', true);
                return;
            }

            const uploadBtn = document.getElementById('uploadBtn');
            const originalText = uploadBtn.textContent;
            uploadBtn.textContent = 'Загрузка...';
            uploadBtn.disabled = true;

            const formData = new FormData();
            formData.append('skin', file);
            formData.append('username', document.getElementById('displayName').textContent);

            fetch('upload_skin.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('skinRender').src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                    
                    document.getElementById('skinLink').value = data.data.url;
                    document.getElementById('skinCommand').textContent = data.data.command;
                    document.getElementById('resultSection').style.display = 'block';
                    
                    showNotification('✅ Скин успешно загружен!');
                } else {
                    showNotification('❌ Ошибка: ' + data.message, true);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('❌ Ошибка соединения с сервером', true);
            })
            .finally(() => {
                uploadBtn.textContent = originalText;
                uploadBtn.disabled = false;
            });
        }

        // Копировать ссылку
        function copyLink() {
            const link = document.getElementById('skinLink');
            link.select();
            document.execCommand('copy');
            showNotification('Ссылка скопирована');
        }

        // Копировать команду
        function copyCommand() {
            const command = document.getElementById('skinCommand').textContent;
            navigator.clipboard.writeText(command);
            showNotification('Команда скопирована');
        }

        // Показать историю скинов
        function showSkinHistory() {
            const username = document.getElementById('displayName').textContent;
            
            fetch(`get_user_skins.php?username=${encodeURIComponent(username)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.skins.length > 0) {
                        let historyHtml = '';
                        
                        data.skins.forEach(skin => {
                            historyHtml += `
                                <div style="display: flex; align-items: center; gap: 15px; padding: 15px; border-bottom: 1px solid #eee;">
                                    <img src="${skin.url}" style="width: 50px; height: 100px; object-fit: contain; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 10px;">
                                    <div style="flex: 1;">
                                        <div style="font-size: 12px; color: #666;">${skin.date}</div>
                                        <button onclick="useOldSkin('${skin.url}')" style="margin-top: 8px; padding: 6px 12px; background: #000; color: #fff; border: none; border-radius: 20px; cursor: pointer;">Использовать</button>
                                    </div>
                                </div>
                            `;
                        });
                        
                        document.getElementById('skinHistoryList').innerHTML = historyHtml;
                        openModal('skinHistoryModal');
                    } else {
                        showNotification('У вас пока нет загруженных скинов');
                    }
                });
        }

        // Использовать старый скин
        function useOldSkin(url) {
            document.getElementById('skinRender').src = url;
            document.getElementById('skinLink').value = url;
            document.getElementById('skinCommand').textContent = `/skin set ${url}`;
            document.getElementById('resultSection').style.display = 'block';
            closeModal('skinHistoryModal');
            showNotification('Скин загружен из истории');
        }

        // ============== ТОВАРЫ ==============

        // Удаление товара
        function removeProduct(btn) {
            const card = btn.closest('.item-card');
            card.style.opacity = '0';
            card.style.transform = 'translateX(20px)';
            setTimeout(() => {
                card.remove();
                updateProductsCount();
            }, 300);
            showNotification('Товар удалён');
        }

        // Добавление товара
       function addProduct() {
    const name = document.getElementById('productName').value;
    const price = document.getElementById('productPrice').value;
    const desc = document.getElementById('productDesc').value;
    const category = document.getElementById('productCategory').value; // ← Добавили категорию
    const categoryText = document.getElementById('productCategory').options[document.getElementById('productCategory').selectedIndex].text;

    if (!name || !price) {
        alert('Заполни название и цену!');
        return;
    }

    const productData = {
        name: name,
        price: price,
        description: desc || 'Новый товар',
        category: category,        // ← ID категории
        status: 'active'
    };

    // PUT запрос на сервер
    fetch('/api/product.php', {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(productData)
    })
    .then(function(response) {
        if (!response.ok) {
            throw new Error('Ошибка сервера: ' + response.status);
        }
        return response.json();
    })
    .then(function(result) {
        const productId = result.id || Date.now();

        const grid = document.getElementById('productsGrid');
        const newCard = document.createElement('div');
        newCard.className = 'item-card';
        newCard.dataset.id = productId;
        newCard.dataset.category = category; // ← сохраняем категорию в data-атрибут
        newCard.innerHTML = `
            <div class="item-header">
                <span class="item-name">${escapeHtml(name)}</span>
                <span class="item-status active">Активен</span>
            </div>
            <div style="margin-bottom: 8px;">
                <span style="background: #e0e0e0; padding: 2px 8px; border-radius: 12px; font-size: 11px;">${escapeHtml(categoryText)}</span>
            </div>
            <p style="font-size: 14px; color: #666; margin-bottom: 12px;">${escapeHtml(desc) || 'Новый товар'}</p>
            <div class="item-price">
                <i class="fas fa-gem" style="color: #2AA9C9;"></i> ${escapeHtml(price)}
            </div>
            <div class="item-actions">
                <button class="item-btn" onclick="editProduct(this)">✏️ Редактировать</button>
                <button class="item-btn delete" onclick="removeProduct(this)">🗑️ Удалить</button>
            </div>
        `;

        grid.prepend(newCard);
        
        // Очищаем форму
        document.getElementById('productName').value = '';
        document.getElementById('productPrice').value = '';
        document.getElementById('productDesc').value = '';
        document.getElementById('productCategory').value = ''; // ← сбрасываем select
        
        updateProductsCount();
        showNotification('Товар добавлен!');
    })
    .catch(function(error) {
        console.error('Ошибка:', error);
        showNotification('Ошибка при добавлении товара!', 'error');
    });
}

        // Обновить счетчик товаров
        function updateProductsCount() {
            const count = document.querySelectorAll('.item-card').length;
            document.getElementById('statProducts').textContent = count;
        }

        // ============== МУЗЫКА ==============

        // Выбор файла музыки
        document.getElementById('musicUploadArea').addEventListener('click', function() {
            document.getElementById('musicFile').click();
        });

        document.getElementById('musicFile').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                if (file.size > 10 * 1024 * 1024) {
                    showNotification('❌ Файл слишком большой (максимум 10 МБ)', true);
                    this.value = '';
                    return;
                }
                
                document.getElementById('musicFileName').textContent = file.name;
                
                let size = file.size;
                if (size < 1024) {
                    document.getElementById('musicFileSize').textContent = size + ' Б';
                } else if (size < 1024 * 1024) {
                    document.getElementById('musicFileSize').textContent = (size / 1024).toFixed(1) + ' КБ';
                } else {
                    document.getElementById('musicFileSize').textContent = (size / (1024 * 1024)).toFixed(1) + ' МБ';
                }
                
                document.getElementById('musicUploadInfo').style.display = 'block';
            }
        });

        // Загрузка музыки
        function uploadMusic() {
            const fileInput = document.getElementById('musicFile');
            const file = fileInput.files[0];
            
            if (!file) {
                showNotification('Выберите файл для загрузки', true);
                return;
            }

            const title = document.getElementById('musicTitle').value || file.name.replace(/\.[^/.]+$/, "");
            const visibility = document.getElementById('musicVisibility').value;
            const username = document.getElementById('displayName').textContent;

            const uploadBtn = document.querySelector('#musicUploadInfo .submit-btn');
            const originalText = uploadBtn.textContent;
            uploadBtn.textContent = '⏳ Загрузка...';
            uploadBtn.disabled = true;

            const formData = new FormData();
            formData.append('music', file);
            formData.append('username', username);
            formData.append('title', title);
            formData.append('visibility', visibility);

            fetch('upload_music.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('✅ Трек успешно загружен!');
                    
                    document.getElementById('musicTitle').value = '';
                    document.getElementById('musicFile').value = '';
                    document.getElementById('musicUploadInfo').style.display = 'none';
                    
                    loadUserMusic();
                } else {
                    showNotification('❌ Ошибка: ' + data.message, true);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('❌ Ошибка соединения с сервером', true);
            })
            .finally(() => {
                uploadBtn.textContent = originalText;
                uploadBtn.disabled = false;
            });
        }

        // Загрузка списка музыки
        function loadUserMusic() {
            const username = document.getElementById('displayName').textContent;
            const musicList = document.getElementById('musicList');
            const musicCount = document.getElementById('musicCount');
            
            musicList.innerHTML = '<div style="text-align: center; padding: 60px; color: #7A7A7A;"><i class="fas fa-circle-notch fa-spin" style="font-size: 40px; margin-bottom: 16px;"></i><p>Загрузка музыки...</p></div>';
            
            fetch(`get_user_music.php?username=${encodeURIComponent(username)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        musicCount.textContent = data.count;
                        
                        if (data.music.length === 0) {
                            musicList.innerHTML = `
                                <div style="text-align: center; padding: 60px; background: #F9F9F9; border-radius: 60px;">
                                    <i class="fas fa-music" style="font-size: 50px; color: #CCC; margin-bottom: 16px;"></i>
                                    <h3 style="margin-bottom: 8px;">У вас пока нет музыки</h3>
                                    <p style="color: #7A7A7A;">Загрузите свой первый трек!</p>
                                </div>
                            `;
                            return;
                        }
                        
                        let html = '';
                        data.music.forEach(track => {
                            const visibilityIcon = track.visibility === 'public' ? '🔊' : '🔇';
                            const visibilityText = track.visibility === 'public' ? 'Публичная' : 'Приватная';
                            
                            html += `
                                <div class="music-item" data-id="${track.id}">
                                    <div class="music-play" onclick="playMusicTrack('${track.url}', this)">
                                        <i class="fas fa-play"></i>
                                    </div>
                                    <div class="music-info">
                                        <div class="music-title">${escapeHtml(track.title)}</div>
                                        <div class="music-artist">
                                            ${track.duration_formatted || '--:--'} • ${track.date_formatted || 'Дата неизвестна'}
                                            ${track.artist !== 'Unknown' ? ' • ' + escapeHtml(track.artist) : ''}
                                        </div>
                                    </div>
                                    <div class="music-actions">
                                        <button class="music-btn" onclick="copyMusicLink('${track.url}')">
                                            🔗 Ссылка
                                        </button>
                                        <button class="music-btn" onclick="deleteMusic('${track.id}')" style="color: #dc3545;">
                                            🗑️
                                        </button>
                                    </div>
                                </div>
                            `;
                        });
                        
                        musicList.innerHTML = html;
                        document.getElementById('statTracks').textContent = data.count;
                    } else {
                        musicList.innerHTML = '<div style="text-align: center; padding: 60px; color: #dc3545;">Ошибка загрузки</div>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    musicList.innerHTML = '<div style="text-align: center; padding: 60px; color: #dc3545;">Ошибка соединения</div>';
                });
        }

        // Воспроизведение музыки
        function playMusicTrack(url, btn) {
            if (currentAudio && currentAudio.src === url) {
                if (currentAudio.paused) {
                    currentAudio.play();
                    btn.querySelector('i').className = 'fas fa-pause';
                } else {
                    currentAudio.pause();
                    btn.querySelector('i').className = 'fas fa-play';
                }
                return;
            }
            
            if (currentAudio) {
                currentAudio.pause();
                if (currentPlayBtn) {
                    currentPlayBtn.querySelector('i').className = 'fas fa-play';
                }
            }
            
            currentAudio = new Audio(url);
            currentAudio.play();
            
            btn.querySelector('i').className = 'fas fa-pause';
            currentPlayBtn = btn;
            
            currentAudio.onended = function() {
                btn.querySelector('i').className = 'fas fa-play';
                currentPlayBtn = null;
                currentAudio = null;
            };
        }

        // Копирование ссылки на музыку
        function copyMusicLink(url) {
            navigator.clipboard.writeText(url);
            showNotification('🔗 Ссылка скопирована');
        }

        // Удаление музыки
        function deleteMusic(id) {
            if (!confirm('Удалить этот трек?')) return;
            
            const username = document.getElementById('displayName').textContent;
            
            fetch('delete_music.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    id: id,
                    username: username
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('✅ Трек удален');
                    
                    const item = document.querySelector(`.music-item[data-id="${id}"]`);
                    if (item) {
                        item.style.opacity = '0';
                        item.style.transform = 'translateX(20px)';
                        setTimeout(() => {
                            loadUserMusic();
                        }, 300);
                    }
                } else {
                    showNotification('❌ ' + data.message, true);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('❌ Ошибка при удалении', true);
            });
        }

        // ============== ИНИЦИАЛИЗАЦИЯ ==============

        // Загрузка при старте
        window.onload = function() {
           
            
            // Проверяем, активна ли вкладка музыки при загрузке
            if (document.querySelector('.tab.active').textContent.includes('Музыка')) {
                loadUserMusic();
            }
        };