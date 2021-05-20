<?php
/*
 * Skrypt strony wylogowania
 */
require_once 'include/obsluga_sesji.php';
require_once 'include/settings.php';
require_once 'skrypty/menu.php';
require_once 'include/funkcje.php';

$LOKALIZACJA='Strona wylogowania';
$TRESC='';
$MENU=setMenu('');
$TRESC.=genEkranPozegnalny($_SESSION['username']);//generowanie ekranu pozegalnego uzytkownika ktory sie wylogowal
unset($_SESSION['username']);
require_once 'szablony/witryna.php';
?> 
