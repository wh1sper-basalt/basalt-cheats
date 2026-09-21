'use strict';

const path = require('path');
const express = require('express');

const app = express();
const PORT = Number(process.env.PORT || 3000);

const myLogger = (req, res, next) => {
  console.log(`[${new Date().toISOString()}] ${req.method} ${req.originalUrl}`);
  next();
};

const requestTime = (req, res, next) => {
  req.requestTime = new Date().toLocaleString('ru-RU');
  next();
};

app.use(myLogger);
app.use(requestTime);

app.use(express.json());
app.use(express.urlencoded({ extended: true }));

app.use(express.static(path.join(__dirname, 'public')));

app.set('view engine', 'ejs');
app.set('views', path.join(__dirname, 'views'));
app.set('json spaces', 2);

app.get('/', (req, res) => {
  res.render('index', { time: req.requestTime });
});

app.get('/api/echo', (req, res) => {
  res.json({
    method: 'GET',
    requestTime: req.requestTime,
    query: req.query,
    headers: {
      'user-agent': req.get('user-agent'),
      host: req.get('host'),
    },
  });
});

app.post('/api/echo', (req, res) => {
  res.json({
    method: 'POST',
    requestTime: req.requestTime,
    body: req.body,
  });
});

app.get('/greet/:name', (req, res) => {
  res.send(`Hello, ${req.params.name}! (route param demo)`);
});

app.use((req, res) => {
  res.status(404).render('404', { url: req.originalUrl });
});

app.use((err, req, res, next) => {
  console.error(err.stack);
  if (res.headersSent) {
    return next(err);
  }
  res.status(500).render('error', { message: err.message });
});

app.listen(PORT, () => {
  console.log(`PR14 Express running → http://localhost:${PORT}`);
});