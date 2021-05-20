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
$DBServer = 'localhost';
$DBUser   = 'pt17356_desp';
$DBPass   = 'desp';
$DBName   = 'pt17356_desp';

$SKEY='hokuspokus';
?>