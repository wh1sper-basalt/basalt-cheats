<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

$pageTitle = __('regions.title');
require COMPONENTS_PATH . '/header.php';
$ru = current_lang() === 'ru';
$breadcrumbs = [
    ['label' => __('nav.regions'), 'href' => null],
];
?>
<div class="container">
    <?php require COMPONENTS_PATH . '/breadcrumbs.php'; ?>
    <header class="ws-page-hero">
        <p class="ws-hero-badge"><?= $ru ? 'Network' : 'Network' ?></p>
        <h1 class="ws-page-title"><?= h(__('regions.title')) ?></h1>
        <p class="ws-page-lead"><?= $ru
            ? 'Структурированный обзор сети узлов выдачи ключей: вложенные списки и акценты (как в учебном макете с многоуровневой навигацией).'
            : 'Structured view of the key-delivery node network: nested lists and emphasis (same layout pattern as the course multi-level nav exercise).' ?></p>
        <div class="ws-divider" aria-hidden="true"><span>◇</span></div>
    </header>
</div>
<div class="container card card-hover">
    <?php if ($ru) { ?>
        <h2>Сеть Basalt</h2>
        <p>Распределённый контур с административным шлюзом в точке выдачи лицензий. Узлы географически разнесены, логика — единая.</p>
        <p><b>Семь операционных единиц:</b></p>
        <ul class="list1">
            <li>3 ядровых узла:
                <ol class="list2">
                    <li>«Прайм» — контроль SLA и маршрутизация заказов;</li>
                    <li>«Буфер» — стадирование обновлений модулей;</li>
                    <li>«Магистраль» — синхронизация артефактов.</li>
                </ol>
            </li>
            <li>4 периферийных кластера, 14 песочниц:
                <ol class="list2">
                    <li>Кластер «Север»;</li>
                    <li>Кластер «Озёрный»;</li>
                    <li>Кластер «Степь»;</li>
                    <li>Кластер «Рубеж».</li>
                </ol>
            </li>
        </ul>
        <p><span class="select">Покрытие маршрутов:</span> 28,9 тыс. км логических каналов.</p>
        <p><span class="select">Активные сессии:</span> 61,1 тыс. (оценка), 47% ночь / 53% день.</p>
        <p><span class="select">Плотность тикетов:</span> 2,1 обращения на единицу канала.</p>
        <p class="fill">Пиковая нагрузка приходится на окна игровых патчей; средняя стабильность инцидентов держится в коридоре SLA.</p>
        <p>Основные процессы — выдача ключей, подписание билдов, сопровождение интеграций.</p>
        <p class="fill">Архив первых релизов оцифрован и доступен внутренним командам для регрессии.</p>
        <p>Контрольные точки: SLA-витрина, патч-лог, мост доверенной доставки, мемориал инцидентов «zero lost keys».</p>
    <?php } else { ?>
        <h2>Basalt network</h2>
        <p>Distributed perimeter with the admin gateway at the license hand-off. Nodes are geo-dispersed; policy is unified.</p>
        <p><b>Seven operational units:</b></p>
        <ul class="list1">
            <li>3 core nodes:
                <ol class="list2">
                    <li>“Prime” — SLA control and order routing;</li>
                    <li>“Buffer” — module update staging;</li>
                    <li>“Trunk” — artifact synchronization.</li>
                </ol>
            </li>
            <li>4 edge clusters, 14 sandboxes:
                <ol class="list2">
                    <li>“North” cluster;</li>
                    <li>“Lakes” cluster;</li>
                    <li>“Steppe” cluster;</li>
                    <li>“Frontier” cluster.</li>
                </ol>
            </li>
        </ul>
        <p><span class="select">Route coverage:</span> 28.9k km of logical channels.</p>
        <p><span class="select">Active sessions:</span> 61.1k (estimate), 47% night / 53% day.</p>
        <p><span class="select">Ticket density:</span> 2.1 tickets per channel unit.</p>
        <p class="fill">Peaks align with game patch windows; incident stability stays within the SLA corridor.</p>
        <p>Core flows — key issuance, build signing, integration support.</p>
        <p class="fill">First-release archive is digitized for internal regression teams.</p>
        <p>Checkpoints: SLA board, patch log, trusted delivery bridge, “zero lost keys” incident memorial.</p>
    <?php } ?>
</div>
<?php require COMPONENTS_PATH . '/footer.php'; ?>
