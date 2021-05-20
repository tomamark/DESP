<?php
/*
 * Skrypt obsługujący zarządzanie relacjami rodzice-uczniowie
 */
require_once 'include/obsluga_sesji.php';
require_once 'include/settings.php';
require_once 'skrypty/menu.php';
require_once 'nibble-forms/NibbleForm.class.php';
require_once 'include/funkcje.php';
$LOKALIZACJA='Administracja uczniami';
$TRESC='';

checkSession();
$uzytkownik='Administrator';
checkUprawnienia($uzytkownik);//sprawdzenie czy uzytkownik ma poprawne uprawnienia

try {//nawiazywanie polaczenia z baza i odczyt danych
    $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName", $DBUser, $DBPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $TRESC.='<h2>Rodzice=>Uczniowie</h2>';
    $TRESC.=genRodzicUczenHTML($pdo);//lista powiazan rodzic uczen
   
}
catch(PDOException $e)
{
    $TRESC.= "Nie można pobrać danych z bazy";
}
require_once 'szablony/witryna.php';
?>