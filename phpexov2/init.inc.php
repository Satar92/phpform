<?php
define ('ROOT_DIR', $_SERVER['DOCUMENT_ROOT']. '/phpexov2/');
include_once ('./functions/functions.php');
include_once ('./config/DB/dbdata.inc.php');
include_once ('./functions/constants.php');
//demarrage de la sessio
session_start();

// connexion a la base de données
$pdo = getPDO($dbdata);

// mode

$env = 'dev';

// initialisation du tableau d'erreur/succes
$errors = [];
$success = [];


// debug donnée entrantes

if($env==='dev'){
    incomingData();
}