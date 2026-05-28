// Переключение видимости пароля
function togglePasswordVisibility() {
    const passwordInput = document.getElementById('loginPassword');
    const toggleIcon = document.getElementById('passwordToggleIcon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.className = 'fa-regular fa-eye';
    } else {
        passwordInput.type = 'password';
        toggleIcon.className = 'fa-regular fa-eye-slash';
    }
}

// Функция открытия модального окна
function login() {
    fetch('/check_session.php', {
    method: 'GET',
    credentials: 'same-origin' 
})
.then(response => response.json())
.then(data => {
    if (data.session_active) {
        window.location.href = 'account.php';
    }
});
    document.getElementById('loginModal').style.display = 'flex';
    // Очищаем поля и ошибки при открытии
    document.getElementById('loginUsername').value = '';
    document.getElementById('loginPassword').value = '';
    document.getElementById('loginError').style.display = 'none';
    // Сбрасываем тип пароля на password
    const passwordInput = document.getElementById('loginPassword');
    const toggleIcon = document.getElementById('passwordToggleIcon');
    passwordInput.type = 'password';
    toggleIcon.className = 'fa-regular fa-eye-slash';
}

// Функция закрытия модального окна
function closeModal() {
    document.getElementById('loginModal').style.display = 'none';
}

// Закрытие по клику вне окна
window.onclick = function(event) {
    const modal = document.getElementById('loginModal');
    if (event.target === modal) {
        closeModal();
    }
}

// Функция входа (вызывает lognext)
function submitLogin() {
    const username = document.getElementById('loginUsername').value.trim();
    const password = document.getElementById('loginPassword').value;
    const errorDiv = document.getElementById('loginError');
    
    // Простая валидация
    if (!username) {
        errorDiv.textContent = 'Введите логин или email';
        errorDiv.style.display = 'block';
        return;
    }
    
    if (!password) {
        errorDiv.textContent = 'Введите пароль';
        errorDiv.style.display = 'block';
        return;
    }
    
    // Вызываем функцию lognext с переданными данными
    lognext(username, password);
}

// Основная функция авторизации
function lognext(login, password) {
    const errorDiv = document.getElementById('loginError');
    const loginBtn = document.querySelector('.login-btn');
    
    // Блокируем кнопку на время проверки
    loginBtn.disabled = true;
    loginBtn.textContent = '⏳ Вход...';
    
    // Отправка POST запроса на сервер
    fetch('api/login.php', {
        method: 'POST',
        headers: { 
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ 
            login: login, 
            password: password 
        })
    })
    .then(response => {
        // Проверяем статус ответа
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Успешный вход - перенаправляем
            window.location.href = data.redirect || 'account.php';
        } else {
            // Ошибка авторизации
            errorDiv.textContent = data.message || 'Неверный логин или пароль';
            errorDiv.style.display = 'block';
            loginBtn.disabled = false;
            loginBtn.textContent = 'Войти';
        }
    })
    .catch(error => {
        console.error('Ошибка:', error);
        errorDiv.textContent = 'Ошибка соединения с сервером. Попробуйте позже.';
        errorDiv.style.display = 'block';
        loginBtn.disabled = false;
        loginBtn.textContent = 'Войти';
    });
}

// Поддержка Enter для отправки формы
document.getElementById('loginPassword').addEventListener('keypress', function(event) {
    if (event.key === 'Enter') {
        event.preventDefault();
        submitLogin();
    }
});

document.getElementById('loginUsername').addEventListener('keypress', function(event) {
    if (event.key === 'Enter') {
        event.preventDefault();
        submitLogin();
    }
});