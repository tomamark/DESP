<?php
require_once 'include/funkcje.php';
$MENU=array(
		"index.php"=>"Logowanie",
		);
if (isset ($_SESSION['username'])){
    
    $MENU=setMenu($_SESSION['usertype']);
}

$NAZWA_STRONY="Dziennik Elektroniczny dla Szkoły Podstawowej";
$COPYRIGHT="(C) 2018 K. Kołaczek, M. Kruła, T. Markuszewski";
$UZYTKOWNIK=array   ("0"=>"Administrator",
                     "1"=>"Nauczyciel",
                     "2"=>"Uczeń",
                     "3"=>"Rodzic"
);

// Konfiguracja DB:
$DBEngine = 'mysql';
$DBServer = 'localhost:3306';
$DBUser   = 'root';
$DBPass   = '';
$DBName   = 'desp_new';

$SKEY='hokuspokus';
?>