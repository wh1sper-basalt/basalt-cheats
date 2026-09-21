<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/inc/mobile-guard.php';

$html = __DIR__ . '/index.html';
if (!is_readable($html)) {
    http_response_code(404);
    exit('Not found');
}

header('Content-Type: text/html; charset=UTF-8');
$content = (string) file_get_contents($html);
$inject = '<script src="../assets/js/devtools-guard.js" defer></script>';
if (stripos($content, 'devtools-guard.js') === false) {
    $content = str_ireplace('</body>', $inject . '</body>', $content);
}
echo $content;
