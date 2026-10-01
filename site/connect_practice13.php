<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

/** @var array{host:string,port:int,user:string,pass:string,name:string} $cfg */
$cfg = require CONFIG_PATH . '/database_practice13.php';

$mysqli_practice13 = new mysqli($cfg['host'], $cfg['user'], $cfg['pass'], $cfg['name'], $cfg['port']);
if ($mysqli_practice13->connect_errno) {
    http_response_code(500);
    exit('Practice 13 database connection failed.');
}
$mysqli_practice13->set_charset('utf8mb4');
