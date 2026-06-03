<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

$pageTitle = __('runtime.title');
require COMPONENTS_PATH . '/header.php';
$ru = current_lang() === 'ru';
$breadcrumbs = [
    ['label' => __('nav.runtime'), 'href' => null],
];
?>
<div class="container">
    <?php require COMPONENTS_PATH . '/breadcrumbs.php'; ?>
    <header class="ws-page-hero">
        <p class="ws-hero-badge">PR8</p>
        <h1 class="ws-page-title"><?= h(__('runtime.title')) ?></h1>
        <p class="ws-page-lead"><?= $ru
            ? 'Практика 8 (примеры PHP) + клиентский JS: без демонстрации паролей.'
            : 'Practice 8 PHP samples + client JS — no credential leaks.' ?></p>
        <div class="ws-divider" aria-hidden="true"><span>◇</span></div>
    </header>
</div>
<div class="container runtime-demo">
    <div class="panel">
        <h2><?= $ru ? 'Клиентский скрипт' : 'Client script' ?></h2>
        <div id="con"></div>
        <script>
            let surname = "Voron";
            let name = "Alex";
            let patronymic = "Sergeevich";
            let full_name = surname + " " + name + " " + patronymic;
            let str = "<p><strong><?= $ru ? 'Результат JS' : 'JS output' ?></strong></p>";
            str += "<button type='button' class='btn btn-ghost' id='demo-alert'><?= $ru ? 'Нажми' : 'Click' ?></button>";
            document.getElementById("con").innerHTML = str;
            document.getElementById("demo-alert")?.addEventListener("click", () => {
                alert("<?= $ru ? 'Привет' : 'Hello' ?>, " + full_name);
            });
        </script>
    </div>
    <div class="panel">
        <h2><?= $ru ? 'Серверный PHP' : 'Server PHP' ?></h2>
        <?php
        echo '<p>Hello, User!</p>';
        print 'print() without parens<br>';
        print('print() with parens<br>');
        echo 'echo ', 'with ', 'multiple ', 'args<br>';
        $x = 5 + 5;
        echo '<pre>$x = ' . (string) $x . '</pre>';
        $login = 'operator';
        $role = 'support';
        echo '<p><strong>' . ($ru ? 'Сессия' : 'Session') . '</strong><br>'
            . ($ru ? 'Логин' : 'Login') . ': ' . htmlspecialchars($login, ENT_QUOTES, 'UTF-8')
            . '<br>' . ($ru ? 'Роль' : 'Role') . ': ' . htmlspecialchars($role, ENT_QUOTES, 'UTF-8')
            . '</p>';
        ?>
    </div>
</div>
<?php require COMPONENTS_PATH . '/footer.php'; ?>
