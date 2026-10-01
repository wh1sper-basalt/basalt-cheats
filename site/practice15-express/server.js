'use strict';

const express = require('express');
const session = require('express-session');
const bcrypt = require('bcryptjs');
const path = require('path');
const fs = require('fs');

const app = express();
const PORT = Number(process.env.PORT || 3000);
const USERS_FILE = path.join(__dirname, 'users.json');

// =================================================================
// 1. НАСТРОЙКА ШАБЛОНИЗАТОРА И MIDDLEWARE
// =================================================================
app.set('view engine', 'ejs');
app.set('views', path.join(__dirname, 'views'));

app.use(express.urlencoded({ extended: true }));

app.use(session({
  secret: 'my_super_secret_key_123',
  resave: false,
  saveUninitialized: false,
  cookie: { maxAge: 3600000 } // 1 час
}));

// =================================================================
// 2. БАЗА ДАННЫХ В ФАЙЛЕ users.json  (Задание 3)
// =================================================================
function loadUsers() {
  try {
    if (!fs.existsSync(USERS_FILE)) return [];
    const data = fs.readFileSync(USERS_FILE, 'utf-8');
    return JSON.parse(data);
  } catch (err) {
    console.error('Ошибка чтения users.json:', err);
    return [];
  }
}

function saveUsers(users) {
  fs.writeFileSync(USERS_FILE, JSON.stringify(users, null, 2), 'utf-8');
}

// =================================================================
// 3. MIDDLEWARE ДЛЯ ПРОВЕРКИ АВТОРИЗАЦИИ
// =================================================================
function requireAuth(req, res, next) {
  if (req.session && req.session.user) {
    return next();
  }
  res.redirect('/login');
}

// =================================================================
// 4. МАРШРУТЫ
// =================================================================

// --- 4.1. Главная страница ---
app.get('/', (req, res) => {
  const currentUser = req.session.user || null;
  res.render('index', { user: currentUser });
});

// --- 4.2. Страница регистрации (форма) ---
app.get('/register', (req, res) => {
  if (req.session.user) return res.redirect('/');
  res.render('register', { error: null });
});

// --- 4.3. Обработка регистрации ---
app.post('/register', async (req, res) => {
  const { username, password } = req.body;

  // 1. Проверка заполнения полей
  if (!username || !password) {
    return res.render('register', { error: 'Заполните все поля!' });
  }

  // 2. Валидация длины пароля (Задание 1)
  if (password.length < 6) {
    return res.render('register', {
      error: 'Пароль должен содержать минимум 6 символов!'
    });
  }

  // 3. Проверка существования пользователя
  const users = loadUsers();
  const existingUser = users.find(u => u.username === username);
  if (existingUser) {
    return res.render('register', {
      error: 'Пользователь с таким именем уже существует!'
    });
  }

  // 4. Хэширование пароля
  const hashedPassword = await bcrypt.hash(password, 10);

  // 5. Создание и сохранение нового пользователя
  const newUser = {
    id: Date.now(),
    username: username,
    password: hashedPassword
  };
  users.push(newUser);
  saveUsers(users);

  // 6. Автоматический вход после регистрации
  req.session.user = { id: newUser.id, username: newUser.username };
  res.redirect('/');
});

// --- 4.4. Страница входа (форма) ---
app.get('/login', (req, res) => {
  if (req.session.user) return res.redirect('/');
  res.render('login', { error: null });
});

// --- 4.5. Обработка входа ---
app.post('/login', async (req, res) => {
  const { username, password } = req.body;

  if (!username || !password) {
    return res.render('login', { error: 'Заполните все поля!' });
  }

  const users = loadUsers();
  const user = users.find(u => u.username === username);
  if (!user) {
    return res.render('login', {
      error: 'Неверное имя пользователя или пароль!'
    });
  }

  const isPasswordValid = await bcrypt.compare(password, user.password);
  if (!isPasswordValid) {
    return res.render('login', {
      error: 'Неверное имя пользователя или пароль!'
    });
  }

  req.session.user = { id: user.id, username: user.username };
  res.redirect('/');
});

// --- 4.6. Приватная страница /dashboard  (Задание 2) ---
app.get('/dashboard', requireAuth, (req, res) => {
  res.render('dashboard', { user: req.session.user });
});

// --- 4.7. Выход из системы ---
app.post('/logout', (req, res) => {
  req.session.destroy((err) => {
    if (err) console.error('Ошибка при завершении сессии:', err);
    res.redirect('/');
  });
});

// =================================================================
// 5. ЗАПУСК СЕРВЕРА
// =================================================================
app.listen(PORT, () => {
  console.log(`PR15 Express auth running → http://localhost:${PORT}`);
});