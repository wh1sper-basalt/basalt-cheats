<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

$pageTitle = __('validation.title');
require COMPONENTS_PATH . '/header.php';
$ru = current_lang() === 'ru';
$breadcrumbs = [
    ['label' => __('nav.validation'), 'href' => null],
];
?>
<div class="container">
    <?php require COMPONENTS_PATH . '/breadcrumbs.php'; ?>
    <header class="ws-page-hero">
        <p class="ws-hero-badge">PR6</p>
        <h1 class="ws-page-title"><?= h(__('validation.title')) ?></h1>
        <p class="ws-page-lead"><?= $ru
            ? 'Форма из практики 6 (Валидация): HTML5 pattern/required/min/max без серверной отправки.'
            : 'Practice 6 form: HTML5 pattern/required/min/max — client-side only (no backend submit).' ?></p>
        <div class="ws-divider" aria-hidden="true"><span>◇</span></div>
    </header>
</div>
<div class="container validation-lab">
    <div class="validation-lab-inner card card-hover">
    <form action="#" method="get" onsubmit="return false;">
        <h2><?= $ru ? 'Представьтесь' : 'Introduce yourself' ?></h2>
        <label for="name"><?= $ru
            ? 'Имя (кириллица, пробел, дефис; 2–10 символов)'
            : 'Name (Cyrillic, space, hyphen; 2–10 chars)' ?></label>
        <input id="name" type="text" required pattern="[а-яА-ЯёЁ\-\s]{2,10}" placeholder="Иван">

        <label for="surname"><?= $ru
            ? 'Фамилия (кириллица; 2–15 символов)'
            : 'Surname (Cyrillic; 2–15 chars)' ?></label>
        <input id="surname" type="text" required pattern="[а-яА-ЯёЁ\-\s]{2,15}" placeholder="Иванов">

        <label for="email"><?= $ru ? 'Электронная почта' : 'Email' ?></label>
        <input type="email" required id="email" placeholder="example@mail.ru">

        <hr>
        <h2><?= $ru ? 'Расскажите о себе' : 'About you' ?></h2>
        <p>
            <?= $ru ? 'Пол:' : 'Gender:' ?>
            <label><input type="radio" name="sex" value="m"> <?= $ru ? 'мужской' : 'male' ?></label>
            <label><input type="radio" name="sex" value="f" checked> <?= $ru ? 'женский' : 'female' ?></label>
            <label><input type="radio" name="sex" value="h"> <?= $ru ? 'другой' : 'other' ?></label>
        </p>
        <p>
            <?= $ru ? 'Возраст' : 'Age band' ?>
            <select name="selection">
                <option value="option1"><?= $ru ? 'до 18' : 'under 18' ?></option>
                <option value="option2" selected>19–30</option>
                <option value="option3">31–45</option>
                <option value="option4"><?= $ru ? '46 и старше' : '46+' ?></option>
            </select>
        </p>

        <hr>
        <label for="number"><?= $ru ? 'Идентификатор (число 4–22)' : 'Identifier (number 4–22)' ?></label>
        <input id="number" type="number" min="4" max="22">

        <hr>
        <h2><?= $ru ? 'Мнение' : 'Feedback' ?></h2>
        <p><?= $ru ? 'Сообщение' : 'Message' ?></p>
        <textarea required name="message" rows="8" cols="50"></textarea>
        <p>
            <input type="reset" value="<?= $ru ? 'Очистить' : 'Reset' ?>">
            <input type="submit" value="<?= $ru ? 'Отправить' : 'Submit' ?>">
        </p>
    </form>
    </div>
</div>
<?php require COMPONENTS_PATH . '/footer.php'; ?>
