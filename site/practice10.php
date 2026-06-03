<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';
require __DIR__ . '/connect.php';
require __DIR__ . '/connect_practice.php';
require_login();

$isRu = current_lang() === 'ru';
$isEn = !$isRu;
$pageTitle = __('practice10.title');
require COMPONENTS_PATH . '/header.php';

$user = current_user();
$uid = (int) ($user['id'] ?? 0);
$ok = flash_get('practice10_ok');
$err = flash_get('practice10_err');

$blocks = [];
$col = $isRu ? 'content_ru' : 'content_en';
$res = $mysqli->query("SELECT id_element, alias, {$col} AS body FROM index_page ORDER BY id_element ASC LIMIT 2");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $blocks[] = $row;
    }
    $res->free();
}

$rows = [];
$stmt = $mysqli_practice->prepare(
    'SELECT id, name, phone, note, file_path, created_at
     FROM practice10_feedback
     WHERE user_id = ?
     ORDER BY id DESC
     LIMIT 8'
);
if ($stmt) {
    $stmt->bind_param('i', $uid);
    $stmt->execute();
    $r = $stmt->get_result();
    while ($row = $r->fetch_assoc()) {
        $rows[] = $row;
    }
    $stmt->close();
}
?>
<div class="container practice10-page">
    <header class="ws-page-hero">
        <p class="ws-hero-badge">PR10</p>
        <h1 class="ws-page-title"><?= h(__('practice10.heading')) ?></h1>
        <p class="ws-page-lead"><?= h(__('practice10.lead')) ?></p>
        <div class="ws-divider" aria-hidden="true"><span>◇</span></div>
    </header>

    <?php if ($ok !== null) { ?><p class="form-alert is-ok"><?= h($ok) ?></p><?php } ?>
    <?php if ($err !== null) { ?><p class="form-alert is-error"><?= h($err) ?></p><?php } ?>

    <?php if ($blocks !== []) { ?>
        <section class="practice10-blocks stack">
            <?php foreach ($blocks as $b) { ?>
                <article class="index-db-block card card-hover" data-alias="<?= h((string) ($b['alias'] ?? '')) ?>">
                    <?= fix_db_hrefs((string) ($b['body'] ?? '')) ?>
                </article>
            <?php } ?>
        </section>
    <?php } ?>

    <section class="card card-hover stack practice10-form-section">
        <h2><?= h($isRu ? 'Запись в отдельную БД' : 'Write to separate DB') ?></h2>
        <p class="muted"><?= h($isRu ? 'Данные сохраняются в basalt_practice10.' : 'Records are stored in basalt_practice10.') ?></p>
        <form class="form-grid" method="post" action="<?= h(asset('actions/practice10-save.php')) ?>" enctype="multipart/form-data">
            <label class="full">
                <span><?= h(__('feedback.name')) ?></span>
                <input type="text" name="name" maxlength="120" required>
            </label>
            <label class="full">
                <span><?= h($isRu ? 'Телефон' : 'Phone') ?></span>
                <input type="text" name="phone" maxlength="64" required>
            </label>
            <label class="full">
                <span><?= h($isRu ? 'Комментарий' : 'Comment') ?></span>
                <textarea name="note" rows="4"></textarea>
            </label>
            <label class="full">
                <span><?= h($isRu ? 'Файл (опционально)' : 'File (optional)') ?></span>
                <input type="file" name="attachment" accept=".jpg,.jpeg,.png,.webp,.gif,.pdf,.txt">
            </label>
            <div class="full">
                <button class="btn btn-primary" type="submit"><?= h(__('feedback.send')) ?></button>
            </div>
        </form>
    </section>

    <section class="card card-hover stack">
        <h2><?= h($isRu ? 'Мои последние записи' : 'My latest records') ?></h2>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th><?= h(__('feedback.name')) ?></th>
                    <th><?= h($isRu ? 'Телефон' : 'Phone') ?></th>
                    <th><?= h($isRu ? 'Комментарий' : 'Comment') ?></th>
                    <th><?= h($isRu ? 'Файл' : 'File') ?></th>
                    <th><?= h($isRu ? 'Дата' : 'Created') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($rows as $r) { ?>
                    <tr>
                        <td><?= (int) $r['id'] ?></td>
                        <td><?= h((string) $r['name']) ?></td>
                        <td><?= h((string) $r['phone']) ?></td>
                        <td><?= h((string) ($r['note'] ?? '')) ?></td>
                        <td>
                            <?php if (!empty($r['file_path'])) { ?>
                                <a href="<?= h(asset((string) $r['file_path'])) ?>" target="_blank" rel="noopener noreferrer"><?= h($isRu ? 'Открыть' : 'Open') ?></a>
                            <?php } else { ?>
                                —
                            <?php } ?>
                        </td>
                        <td><?= h((string) $r['created_at']) ?></td>
                    </tr>
                <?php } ?>
                <?php if ($rows === []) { ?>
                    <tr>
                        <td colspan="6" class="muted"><?= h($isRu ? 'Записей пока нет.' : 'No records yet.') ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </section>
</div>
<?php require COMPONENTS_PATH . '/footer.php'; ?>
