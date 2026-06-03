<?php

declare(strict_types=1);

$heading = current_lang() === 'ru'
    ? 'Готов к заказу или нужна консультация?'
    : 'Ready to order or need a consult?';
$sub = current_lang() === 'ru'
    ? 'Оставьте контакты — ответим в Telegram или по почте.'
    : 'Leave contacts — we reply via Telegram or email.';
?>
<section class="section feedback-panel card card-hover" id="feedback">
    <div class="container stack">
        <h2><?= h($heading) ?></h2>
        <p class="lead"><?= h($sub) ?></p>
        <form class="form-grid" method="post" action="<?= h(asset('actions/contact.php')) ?>">
            <input type="hidden" name="lang" value="<?= h(current_lang()) ?>">
            <label>
                <span><?= h(__('feedback.name')) ?></span>
                <input name="name" type="text" required minlength="2" maxlength="120" autocomplete="name">
            </label>
            <label>
                <span><?= h(__('checkout.email')) ?></span>
                <input name="email" type="email" required autocomplete="email">
            </label>
            <label>
                <span><?= h(__('checkout.telegram')) ?></span>
                <input name="telegram" type="text" maxlength="120" placeholder="@nickname">
            </label>
            <label class="full">
                <span><?= current_lang() === 'ru' ? 'Сообщение' : 'Message' ?></span>
                <textarea name="message" required rows="5" minlength="4"></textarea>
            </label>
            <div class="full">
                <button class="btn btn-primary" type="submit"><?= h(__('feedback.send')) ?></button>
            </div>
        </form>
    </div>
</section>
