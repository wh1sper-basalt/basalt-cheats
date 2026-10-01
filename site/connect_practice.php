<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

/** @var array{host:string,port:int,user:string,pass:string,name:string} $cfg */
$cfg = require CONFIG_PATH . '/database_practice.php';

$mysqli_practice = new mysqli($cfg['host'], $cfg['user'], $cfg['pass'], $cfg['name'], $cfg['port']);
if ($mysqli_practice->connect_errno) {
    http_response_code(500);
    exit('Practice database connection failed.');
}
$mysqli_practice->set_charset('utf8mb4');
