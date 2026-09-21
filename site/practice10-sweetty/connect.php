<?php

declare(strict_types=1);

/** @var array{host:string,port:int,user:string,pass:string,name:string} $cfg */
$cfg = require dirname(__DIR__) . '/config/database_sweetty.php';

$mysqli = new mysqli($cfg['host'], $cfg['user'], $cfg['pass'], $cfg['name'], $cfg['port']);
if ($mysqli->connect_errno) {
    http_response_code(500);
    exit('Sweetty database connection failed.');
}
$mysqli->set_charset('utf8mb4');
