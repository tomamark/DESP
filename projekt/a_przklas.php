<?php
/*
 * Skrypt obsługujący zarządzanie przedmiotami klas
 */
require_once 'include/obsluga_sesji.php';
require_once 'include/settings.php';
require_once 'skrypty/menu.php';
require_once 'nibble-forms/NibbleForm.class.php';
require_once 'include/funkcje.php';
$LOKALIZACJA='Plany lekcji';
$TRESC='';

checkSession();
$uzytkownik='Administrator';
checkUprawnienia($uzytkownik);//sprawdzenie czy uzytkownik ma poprawne uprawnienia

try {//nawiazywanie polaczenia z baza i odczyt danych
    $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName", $DBUser, $DBPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $TRESC.='<h2>Klasy</h2>';
    $TRESC.=genListaKlasHTML($pdo);//lista klas
    $TRESC.='<h2>Przedmioty klas</h2>';
    $TRESC.=genTabelaPrzedmiotyWszystkie($pdo);//lista przedmiotow
}
catch(PDOException $e)
{
    $TRESC.= "Nie można pobrać danych o klasach z bazy".$e;
}
require_once 'szablony/witryna.php';
?>