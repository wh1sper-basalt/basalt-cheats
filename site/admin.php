<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';
require __DIR__ . '/connect.php';

$token = trim((string) ($_GET['token'] ?? ''));
if ($token !== '') {
    $hash = hash('sha256', $token);
    $stmt = $mysqli->prepare('SELECT id FROM admin_access_tokens WHERE token_hash = ? AND is_active = 1 LIMIT 1');
    $stmt->bind_param('s', $hash);
    $stmt->execute();
    $ok = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    if ($ok) {
        set_admin_access(true);
        set_flash('admin_ok', __('admin.access_granted'));
        redirect('admin.php');
    }
}

if (!has_admin_access()) {
    http_response_code(403);
    echo 'Forbidden.';
    exit;
}

$dbRes = $mysqli->query('SHOW DATABASES');
$databases = [];
if ($dbRes) {
    while ($row = $dbRes->fetch_row()) {
        $name = (string) ($row[0] ?? '');
        if ($name === '' || in_array($name, ['information_schema', 'performance_schema', 'mysql', 'sys'], true)) {
            continue;
        }
        $databases[] = $name;
    }
}
sort($databases);
$dbName = (string) ($_GET['db'] ?? '');
if ($dbName === '' || !in_array($dbName, $databases, true)) {
    $dbName = $databases[0] ?? '';
}

$tables = [];
if ($dbName !== '') {
    $safeDb = $mysqli->real_escape_string($dbName);
    $tblRes = $mysqli->query("SHOW TABLES FROM `{$safeDb}`");
    if ($tblRes) {
        while ($row = $tblRes->fetch_row()) {
            $tables[] = (string) ($row[0] ?? '');
        }
    }
}
$table = (string) ($_GET['table'] ?? '');
if ($table === '' || !in_array($table, $tables, true)) {
    $table = in_array('practice10_feedback', $tables, true) ? 'practice10_feedback' : ($tables[0] ?? '');
}

$search = trim((string) ($_GET['q'] ?? ''));
$rows = [];
$columns = [];
if ($dbName !== '' && $table !== '') {
    $colRes = $mysqli->query("SHOW COLUMNS FROM `{$dbName}`.`{$table}`");
    if ($colRes) {
        while ($c = $colRes->fetch_assoc()) {
            $columns[] = (string) ($c['Field'] ?? '');
        }
    }
}
$where = '';
if ($search !== '' && $columns) {
    $safe = $mysqli->real_escape_string('%' . $search . '%');
    $parts = [];
    foreach ($columns as $c) {
        $parts[] = "CAST(`{$c}` AS CHAR) LIKE '{$safe}'";
    }
    $where = ' WHERE ' . implode(' OR ', $parts);
}
$sql = '';
if ($dbName !== '' && $table !== '') {
    $sql = "SELECT * FROM `{$dbName}`.`{$table}`{$where} LIMIT 200";
}
$rowsRes = $sql !== '' ? $mysqli->query($sql) : false;
$rows = [];
if ($rowsRes) {
    while ($r = $rowsRes->fetch_assoc()) {
        $rows[] = $r;
    }
}
$columns = $rows ? array_keys($rows[0]) : $columns;

$practiceStats = null;
if ($dbName !== '' && in_array('practice10_feedback', $tables, true)) {
    $statRes = $mysqli->query(
        "SELECT COUNT(*) AS total_rows, COUNT(DISTINCT user_id) AS users_count, MAX(created_at) AS last_at
         FROM `{$dbName}`.`practice10_feedback`"
    );
    if ($statRes) {
        $practiceStats = $statRes->fetch_assoc() ?: null;
    }
}

$isEn = current_lang() === 'en';
$pageTitle = __('admin.title');
require COMPONENTS_PATH . '/header.php';
$okMsg = flash_get('admin_ok');
$errMsg = flash_get('admin_err');
?>
<div class="container admin-page">
    <header class="ws-page-hero">
        <p class="ws-hero-badge"><?= h(__('nav.admin')) ?></p>
        <h1 class="ws-page-title"><?= h(__('admin.panel_heading')) ?></h1>
    </header>
    <?php if ($okMsg !== null) { ?><p class="form-alert is-ok"><?= h($okMsg) ?></p><?php } ?>
    <?php if ($errMsg !== null) { ?><p class="form-alert is-error"><?= h($errMsg) ?></p><?php } ?>
    <?php if ($practiceStats !== null) { ?>
        <section class="card card-hover stack">
            <h3>Practice #10 / DB</h3>
            <p class="muted">
                <?= h($isEn ? 'Rows:' : 'Записей:') ?> <strong><?= (int) ($practiceStats['total_rows'] ?? 0) ?></strong>,
                <?= h($isEn ? 'Users:' : 'Пользователей:') ?> <strong><?= (int) ($practiceStats['users_count'] ?? 0) ?></strong>,
                <?= h($isEn ? 'Last:' : 'Последняя:') ?> <strong><?= h((string) ($practiceStats['last_at'] ?? '-')) ?></strong>
            </p>
            <p>
                <a class="btn btn-ghost" href="<?= h(asset('practice10.php')) ?>">Practice #10 page</a>
                <a class="btn btn-ghost" href="<?= h(url_to('admin.php', ['db' => $dbName, 'table' => 'practice10_feedback'])) ?>">Open table</a>
            </p>
        </section>
    <?php } ?>
    <section class="card card-hover stack">
        <form method="get" class="admin-filter">
            <label>
                <span><?= h(__('admin.database')) ?></span>
                <select name="db">
                    <?php foreach ($databases as $db) { ?>
                        <option value="<?= h($db) ?>" <?= $dbName === $db ? 'selected' : '' ?>><?= h($db) ?></option>
                    <?php } ?>
                </select>
            </label>
            <label>
                <span><?= h(__('admin.table')) ?></span>
                <select name="table">
                    <?php foreach ($tables as $t) { ?>
                        <option value="<?= h($t) ?>" <?= $table === $t ? 'selected' : '' ?>><?= h($t) ?></option>
                    <?php } ?>
                </select>
            </label>
            <label>
                <span><?= h(__('admin.search')) ?></span>
                <input type="search" name="q" value="<?= h($search) ?>">
            </label>
            <button type="submit" class="btn btn-ghost"><?= h(__('admin.apply')) ?></button>
        </form>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                <tr>
                    <?php foreach ($columns as $c) { ?><th><?= h($c) ?></th><?php } ?>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($rows as $r) { ?>
                    <tr>
                        <?php foreach ($columns as $c) { ?>
                            <td><?= h((string) ($r[$c] ?? '')) ?></td>
                        <?php } ?>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </section>
    <section class="admin-ops-grid">
        <article class="card card-hover">
            <h3><?= $isEn ? 'Full reimport' : 'Полный импорт' ?></h3>
            <p class="muted"><?= $isEn ? 'Reset all project databases via site/database/FULL.sql in phpMyAdmin or mysql CLI.' : 'Сброс всех БД проекта: site/database/FULL.sql в phpMyAdmin или через mysql CLI.' ?></p>
        </article>
        <article class="card card-hover">
            <h3>CREATE / UPDATE</h3>
            <form method="post" action="<?= h(asset('actions/admin-crud.php')) ?>" class="form-grid">
                <input type="hidden" name="mode" value="upsert">
                <input type="hidden" name="db" value="<?= h($dbName) ?>">
                <label class="full">
                    <span><?= h(__('admin.table')) ?></span>
                    <select name="table" required>
                        <?php foreach ($tables as $t) { ?>
                            <option value="<?= h($t) ?>" <?= $table === $t ? 'selected' : '' ?>><?= h($t) ?></option>
                        <?php } ?>
                    </select>
                </label>
                <label class="full">
                    <span><?= h(__('admin.row_id')) ?></span>
                    <input type="text" name="row_id">
                </label>
                <label class="full">
                    <span>data_json</span>
                    <textarea name="data_json" rows="8" placeholder='{"nickname":"new","is_active":1}' required></textarea>
                </label>
                <button class="btn btn-primary" type="submit">Save</button>
            </form>
        </article>
        <article class="card card-hover">
            <h3>DELETE</h3>
            <form method="post" action="<?= h(asset('actions/admin-crud.php')) ?>" class="form-grid">
                <input type="hidden" name="mode" value="delete">
                <input type="hidden" name="db" value="<?= h($dbName) ?>">
                <label class="full">
                    <span><?= h(__('admin.table')) ?></span>
                    <select name="table" required>
                        <?php foreach ($tables as $t) { ?>
                            <option value="<?= h($t) ?>" <?= $table === $t ? 'selected' : '' ?>><?= h($t) ?></option>
                        <?php } ?>
                    </select>
                </label>
                <label class="full">
                    <span><?= h(__('admin.row_id_short')) ?></span>
                    <input type="number" name="row_id" min="1" required>
                </label>
                <button class="btn btn-danger" type="submit">Delete row</button>
            </form>
        </article>
    </section>
</div>
<?php require COMPONENTS_PATH . '/footer.php'; ?>
