<?php
$platform = "pc";

if ($platform === "pc") {
  $password = '';
  $port = 3306;
} else {
  $password = "root";
  $port = 8889;
}

$dbdata = [
  'driver' => 'mysql',
  'server' => 'localhost',
  'base' => 'repertoire',
  'port' => $port,
  'user' => 'root',
  'password' => $password,
  'charset' => 'utf8mb4',
  'options' => [
    //PDO :: MYSQL_ATTR_INIT_COMMANDE => 'SET NAMES utf8mb4
    //PDO :: MYSQL_ATTR_USE_BUFFERES_QUERY =>  true
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
  ]
];


?>







