<?php

declare(strict_types=1);

const ROOT_PATH = __DIR__;
const COMPONENTS_PATH = ROOT_PATH . '/components';
const CONFIG_PATH = ROOT_PATH . '/config';
const LANG_PATH = ROOT_PATH . '/lang';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

/**
 * @return array<string, string>
 */
function site_config(): array
{
    static $cache = null;
    if ($cache !== null) {
        return $cache;
    }
    $cache = [];
    $path = CONFIG_PATH . '/site.yaml';
    if (!is_readable($path)) {
        return $cache;
    }
    foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [] as $line) {
        $t = trim($line);
        if ($t === '' || str_starts_with($t, '#') || !str_contains($t, ':')) {
            continue;
        }
        [$k, $v] = array_map('trim', explode(':', $line, 2));
        $cache[$k] = trim($v, " \t\n\r\0\x0B'\"");
    }
    return $cache;
}

$lang = $_GET['lang'] ?? null;
if (is_string($lang) && in_array($lang, ['ru', 'en'], true)) {
    $_SESSION['lang'] = $lang;
}

$currentLang = $_SESSION['lang'] ?? site_config()['default_lang'] ?? 'ru';
if (!in_array($currentLang, ['ru', 'en'], true)) {
    $currentLang = 'ru';
}

/** @var array<string, string> $messages */
$messages = require LANG_PATH . '/' . $currentLang . '.php';

function __(string $key, ?string $fallback = null): string
{
    global $messages;
    return $messages[$key] ?? $fallback ?? $key;
}

function h(?string $s): string
{
    return htmlspecialchars($s ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function remember_cookie_name(): string
{
    return 'basalt_remember';
}

function remember_cookie_value(): string
{
    $name = remember_cookie_name();
    $val = $_COOKIE[$name] ?? '';
    return is_string($val) ? trim($val) : '';
}

function set_remember_cookie(string $token, int $ttlSeconds): void
{
    $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
    setcookie(remember_cookie_name(), $token, [
        'expires' => time() + $ttlSeconds,
        'path' => '/',
        'secure' => $secure,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}

function clear_remember_cookie(): void
{
    $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
    setcookie(remember_cookie_name(), '', [
        'expires' => time() - 3600,
        'path' => '/',
        'secure' => $secure,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}

function current_lang(): string
{
    global $currentLang;
    return $currentLang;
}

function lang_switch_url(string $targetLang): string
{
    $uri = $_SERVER['REQUEST_URI'] ?? '/';
    $parts = parse_url($uri);
    $path = $parts['path'] ?? '/';
    parse_str($parts['query'] ?? '', $q);
    $q['lang'] = $targetLang;
    $qs = http_build_query($q);
    return $qs === '' ? $path : $path . '?' . $qs;
}

function base_url_prefix(): string
{
    static $p = null;
    if ($p !== null) {
        return $p;
    }
    $c = site_config();
    $raw = $c['web_base'] ?? '';
    $p = trim((string) $raw);
    if ($p !== '') {
        $p = rtrim(str_replace('\\', '/', $p), '/');
        return $p;
    }

    // Auto-detect project subfolder when app is not deployed at web root.
    $docRoot = isset($_SERVER['DOCUMENT_ROOT']) ? (string) $_SERVER['DOCUMENT_ROOT'] : '';
    $rootPath = str_replace('\\', '/', ROOT_PATH);
    $docRootNorm = rtrim(str_replace('\\', '/', $docRoot), '/');

    if ($docRootNorm !== '' && str_starts_with(strtolower($rootPath), strtolower($docRootNorm . '/'))) {
        $rel = substr($rootPath, strlen($docRootNorm));
        $rel = trim((string) $rel, '/');
        if ($rel !== '') {
            $p = '/' . $rel;
            return $p;
        }
    }

    $scriptName = isset($_SERVER['SCRIPT_NAME']) ? (string) $_SERVER['SCRIPT_NAME'] : '';
    $scriptDir = trim((string) dirname($scriptName), '/\\.');
    $p = $scriptDir === '' ? '' : '/' . str_replace('\\', '/', $scriptDir);
    return $p;
}

function asset(string $path): string
{
    $b = base_url_prefix();
    $path = ltrim($path, '/');
    if ($b === '') {
        return '/' . $path;
    }
    return '/' . ltrim($b, '/') . '/' . $path;
}

/**
 * @param array<string, scalar|null> $query
 */
function url_to(string $script, array $query = []): string
{
    $q = http_build_query($query);
    $base = asset($script);
    return $q === '' ? $base : $base . '?' . $q;
}

function redirect(string $script, array $query = []): void
{
    header('Location: ' . url_to($script, $query));
    exit;
}

/**
 * Fix relative hrefs stored in DB HTML for subfolder installs (web_base).
 */
function fix_db_hrefs(string $html): string
{
    $pages = [
        'games.php', 'faq.php', 'checkout.php', 'reviews.php', 'chronicle.php',
        'plans.php', 'cheats.php', 'index.php', 'feedback.php', 'gallery.php',
        'freebies.php',
    ];
    $targets = [
        'catalog.php' => 'games.php',
        'modules.php' => 'games.php',
        'delivery.php' => 'faq.php',
    ];
    foreach ($targets as $old => $new) {
        $html = str_replace('href="' . $old . '"', 'href="' . h(asset($new)) . '"', $html);
    }
    foreach ($pages as $p) {
        $html = str_replace('href="' . $p . '"', 'href="' . h(asset($p)) . '"', $html);
    }
    return $html;
}

function nav_active_id(): string
{
    if (isset($GLOBALS['navActive']) && is_string($GLOBALS['navActive']) && $GLOBALS['navActive'] !== '') {
        return $GLOBALS['navActive'];
    }
    $script = basename($_SERVER['SCRIPT_NAME'] ?? 'index.php');
    if (in_array($script, ['games.php', 'cheats.php', 'cheat.php', 'plans.php', 'checkout.php'], true)) {
        return 'shop';
    }
    return match ($script) {
        'index.php' => 'home',
        'reviews.php', 'article.php' => 'reviews',
        'chronicle.php' => 'blog',
        'freebies.php' => 'freebies',
        'faq.php' => 'faq',
        'feedback.php' => 'support',
        'admin.php' => 'admin',
        default => '',
    };
}

function nav_class(string $id): string
{
    return nav_active_id() === $id ? 'is-active' : '';
}

/**
 * @param array<string, mixed> $value
 */
function set_session_user(array $value): void
{
    $avatar = trim((string) ($value['avatar_path'] ?? ''));
    $_SESSION['user'] = [
        'id' => (int) ($value['id'] ?? 0),
        'email' => (string) ($value['email'] ?? ''),
        'nickname' => (string) ($value['nickname'] ?? ''),
        'avatar_path' => $avatar !== '' ? $avatar : null,
    ];
}

/**
 * @return array<string, mixed>|null
 */
function current_user(): ?array
{
    $u = $_SESSION['user'] ?? null;
    if (!is_array($u) || (int) ($u['id'] ?? 0) < 1) {
        return null;
    }
    return $u;
}

/**
 * Attempt to restore session from persistent cookie token.
 * Called from connect.php (after mysqli is ready).
 */
function maybe_restore_user_from_remember(mysqli $mysqli): void
{
    if (current_user() !== null) {
        return;
    }
    $raw = remember_cookie_value();
    if ($raw === '' || strlen($raw) < 20) {
        return;
    }

    $hash = hash('sha256', $raw);
    $stmt = $mysqli->prepare(
        'SELECT t.user_id, u.email, u.nickname, u.avatar_path
         FROM user_auth_tokens t
         INNER JOIN users u ON u.id = t.user_id
         WHERE t.token_hash = ? AND t.revoked = 0 AND t.expires_at > NOW() AND u.is_deleted = 0
         LIMIT 1'
    );
    if (!$stmt) {
        return;
    }
    $stmt->bind_param('s', $hash);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    if (!$row) {
        return;
    }

    set_session_user([
        'id' => (int) $row['user_id'],
        'email' => (string) $row['email'],
        'nickname' => (string) $row['nickname'],
        'avatar_path' => $row['avatar_path'] ?? null,
    ]);

    // Rotate token to reduce replay window.
    $newToken = bin2hex(random_bytes(32));
    $newHash = hash('sha256', $newToken);
    $upd = $mysqli->prepare('UPDATE user_auth_tokens SET token_hash = ?, last_used_at = NOW() WHERE token_hash = ? LIMIT 1');
    if ($upd) {
        $upd->bind_param('ss', $newHash, $hash);
        $upd->execute();
        $upd->close();
        set_remember_cookie($newToken, 60 * 60 * 24 * 30);
    }
}

function is_logged_in(): bool
{
    return current_user() !== null;
}

function require_login(): void
{
    if (!is_logged_in()) {
        redirect('index.php', ['err' => 'auth']);
    }
}

function logout_user(): void
{
    unset($_SESSION['user']);
}

function set_flash(string $key, string $message): void
{
    if (!isset($_SESSION['flash']) || !is_array($_SESSION['flash'])) {
        $_SESSION['flash'] = [];
    }
    $_SESSION['flash'][$key] = $message;
}

function flash_get(string $key): ?string
{
    $val = $_SESSION['flash'][$key] ?? null;
    if (is_string($val)) {
        unset($_SESSION['flash'][$key]);
        return $val;
    }
    return null;
}

function has_admin_access(): bool
{
    if (!isset($_SESSION['admin_access']) || $_SESSION['admin_access'] !== 1) {
        return false;
    }
    $exp = (int) ($_SESSION['admin_access_exp'] ?? 0);
    if ($exp > 0 && $exp < time()) {
        unset($_SESSION['admin_access'], $_SESSION['admin_access_exp']);
        return false;
    }
    return true;
}

function set_admin_access(bool $value): void
{
    if ($value) {
        $_SESSION['admin_access'] = 1;
        // Keep access short-lived so ADMIN button disappears after refresh/session drift.
        $_SESSION['admin_access_exp'] = time() + 300;
        return;
    }
    unset($_SESSION['admin_access'], $_SESSION['admin_access_exp']);
}

function consume_admin_access_once(): void
{
    if (has_admin_access()) {
        set_admin_access(false);
    }
}

function is_mobile_client(): bool
{
    if (isset($_COOKIE['basalt_desktop']) && $_COOKIE['basalt_desktop'] === '1') {
        return false;
    }

    $secMobile = $_SERVER['HTTP_SEC_CH_UA_MOBILE'] ?? '';
    if ($secMobile === '?1') {
        return true;
    }

    $ua = (string) ($_SERVER['HTTP_USER_AGENT'] ?? '');
    if ($ua === '') {
        return false;
    }

    return (bool) preg_match(
        '/Mobile|Android|iPhone|iPod|webOS|BlackBerry|IEMobile|Opera Mini|Windows Phone/i',
        $ua
    ) || (bool) preg_match('/iPad|Tablet|PlayBook|Silk/i', $ua);
}

function mobile_guard_should_run(): bool
{
    $script = basename((string) ($_SERVER['SCRIPT_NAME'] ?? ''));
    if ($script === 'mobile-blocked.php') {
        return false;
    }

    $uri = (string) ($_SERVER['REQUEST_URI'] ?? '');
    if (str_contains($uri, '/actions/')) {
        return false;
    }

    return true;
}

function mobile_guard_redirect(): void
{
    if (!mobile_guard_should_run() || !is_mobile_client()) {
        return;
    }

    if (isset($_GET['desktop']) && (string) $_GET['desktop'] === '1') {
        $secure = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
        setcookie('basalt_desktop', '1', [
            'expires' => time() + 86400,
            'path' => '/',
            'secure' => $secure,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        return;
    }

    header('Location: ' . asset('mobile-blocked.php'), true, 302);
    exit;
}

mobile_guard_redirect();
