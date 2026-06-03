<?php

declare(strict_types=1);

require dirname(__DIR__) . '/bootstrap.php';
require dirname(__DIR__) . '/connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !has_admin_access()) {
    redirect('index.php');
}

$dbName = (string) ($_POST['db'] ?? '');
$table = (string) ($_POST['table'] ?? '');
$mode = (string) ($_POST['mode'] ?? '');
$rowId = (int) ($_POST['row_id'] ?? 0);
$json = trim((string) ($_POST['data_json'] ?? ''));

if ($dbName === '' || $table === '') {
    set_flash('admin_err', __('admin.invalid_table'));
    redirect('admin.php');
}
$dbEsc = $mysqli->real_escape_string($dbName);
$tblEsc = $mysqli->real_escape_string($table);
$existsRes = $mysqli->query("SELECT 1 FROM information_schema.tables WHERE table_schema='{$dbEsc}' AND table_name='{$tblEsc}' LIMIT 1");
if (!$existsRes || !$existsRes->fetch_row()) {
    set_flash('admin_err', __('admin.invalid_table'));
    redirect('admin.php');
}

$pkRes = $mysqli->query("SHOW KEYS FROM `{$dbName}`.`{$table}` WHERE Key_name = 'PRIMARY'");
$primaryKey = 'id';
if ($pkRes && ($pkRow = $pkRes->fetch_assoc())) {
    $primaryKey = (string) ($pkRow['Column_name'] ?? 'id');
}

$logAction = function (string $action, string $targetId, string $payload) use ($mysqli, $table, $dbName): void {
    $scope = 'admin_session';
    $targetTable = $dbName . '.' . $table;
    $stmt = $mysqli->prepare('INSERT INTO admin_change_logs (admin_scope, table_name, row_id, action, payload_json) VALUES (?, ?, ?, ?, ?)');
    if ($stmt !== false) {
        $stmt->bind_param('sssss', $scope, $targetTable, $targetId, $action, $payload);
        $stmt->execute();
        $stmt->close();
    }
};

if ($mode === 'delete') {
    if ($rowId < 1) {
        set_flash('admin_err', __('admin.invalid_id'));
        redirect('admin.php', ['db' => $dbName, 'table' => $table]);
    }
    $sql = "DELETE FROM `{$dbName}`.`{$table}` WHERE `{$primaryKey}` = ? LIMIT 1";
    $stmt = $mysqli->prepare($sql);
    if ($stmt === false) {
        set_flash('admin_err', __('admin.query_failed'));
        redirect('admin.php', ['db' => $dbName, 'table' => $table]);
    }
    $stmt->bind_param('i', $rowId);
    $stmt->execute();
    $stmt->close();
    $logAction('delete', (string) $rowId, '{}');
    set_flash('admin_ok', __('admin.saved'));
    redirect('admin.php', ['db' => $dbName, 'table' => $table]);
}

$data = json_decode($json, true);
if (!is_array($data) || !$data) {
    set_flash('admin_err', __('admin.invalid_json'));
    redirect('admin.php', ['db' => $dbName, 'table' => $table]);
}
unset($data[$primaryKey]);

$columnsRes = $mysqli->query("SHOW COLUMNS FROM `{$dbName}`.`{$table}`");
$allowedColumns = [];
if ($columnsRes) {
    while ($c = $columnsRes->fetch_assoc()) {
        $name = (string) ($c['Field'] ?? '');
        if ($name !== $primaryKey && $name !== 'created_at' && $name !== 'updated_at') {
            $allowedColumns[$name] = true;
        }
    }
}

$fields = [];
$values = [];
foreach ($data as $k => $v) {
    $key = (string) $k;
    if (!isset($allowedColumns[$key])) {
        continue;
    }
    $fields[] = $key;
    $values[] = is_scalar($v) || $v === null ? $v : json_encode($v, JSON_UNESCAPED_UNICODE);
}
if (!$fields) {
    set_flash('admin_err', __('admin.no_fields'));
    redirect('admin.php', ['db' => $dbName, 'table' => $table]);
}

if ($rowId > 0) {
    $setParts = [];
    foreach ($fields as $f) {
        $setParts[] = "`{$f}` = ?";
    }
    $sql = "UPDATE `{$dbName}`.`{$table}` SET " . implode(', ', $setParts) . " WHERE `{$primaryKey}` = ? LIMIT 1";
    $stmt = $mysqli->prepare($sql);
    if ($stmt === false) {
        set_flash('admin_err', __('admin.query_failed'));
        redirect('admin.php', ['db' => $dbName, 'table' => $table]);
    }
    $types = str_repeat('s', count($values)) . 'i';
    $bindValues = $values;
    $bindValues[] = $rowId;
    $refs = [];
    $refs[] = &$types;
    foreach ($bindValues as $idx => $val) {
        $bindValues[$idx] = (string) $val;
        $refs[] = &$bindValues[$idx];
    }
    call_user_func_array([$stmt, 'bind_param'], $refs);
    $stmt->execute();
    $stmt->close();
    $logAction('update', (string) $rowId, json_encode($data, JSON_UNESCAPED_UNICODE));
} else {
    $fieldSql = '`' . implode('`,`', $fields) . '`';
    $placeholderSql = implode(',', array_fill(0, count($fields), '?'));
    $sql = "INSERT INTO `{$dbName}`.`{$table}` ({$fieldSql}) VALUES ({$placeholderSql})";
    $stmt = $mysqli->prepare($sql);
    if ($stmt === false) {
        set_flash('admin_err', __('admin.query_failed'));
        redirect('admin.php', ['db' => $dbName, 'table' => $table]);
    }
    $types = str_repeat('s', count($values));
    $bindValues = array_map(static fn($v): string => (string) $v, $values);
    $refs = [];
    $refs[] = &$types;
    foreach ($bindValues as $idx => $val) {
        $refs[] = &$bindValues[$idx];
    }
    call_user_func_array([$stmt, 'bind_param'], $refs);
    $stmt->execute();
    $newId = (int) $stmt->insert_id;
    $stmt->close();
    $logAction('create', (string) $newId, json_encode($data, JSON_UNESCAPED_UNICODE));
}

set_flash('admin_ok', __('admin.saved'));
redirect('admin.php', ['db' => $dbName, 'table' => $table]);
