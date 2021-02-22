<?php
//TODO jomel 02222021 if there is something wrong with connection please check path.ini
$path = parse_ini_file("path.ini", true);
$pathstr = $path['PATH']['path'];
$dbcredentials = parse_ini_file($pathstr, true);

$host = $dbcredentials['LOCAL_RBGM_DB']['host'];
$db   = $dbcredentials['LOCAL_RBGM_DB']['db'];
$user = $dbcredentials['LOCAL_RBGM_DB']['user'];
$pass = $dbcredentials['LOCAL_RBGM_DB']['pass'];
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false
];
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}
/**/