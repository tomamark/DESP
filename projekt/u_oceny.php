<?php
/*
 * Skrypt obsługujący Oceny w menu Ucznia
 */
require_once 'include/obsluga_sesji.php';
require_once 'include/settings.php';
require_once 'skrypty/menu.php';
require_once 'nibble-forms/NibbleForm.class.php';
require_once 'include/funkcje.php';
$LOKALIZACJA='Twoje oceny';
$TRESC='';

checkSession();
$uzytkownik='Uczeń';
checkUprawnienia($uzytkownik);//sprawdzenie czy uzytkownik ma poprawne uprawnienia

try {//nawiazywanie polaczenia z baza i odczyt danych
    $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName", $DBUser, $DBPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $TRESC.='<h2>Twoje przedmioty</h2>';
    $TRESC.=genListaPrzedmiotowKlasyHTML($pdo,$_SESSION['id_klasy']);//generowanie listy przedmiotow
    $TRESC.='<h2>Twoje oceny</h2>';
    $TRESC.=genListaOcenWszystkie($pdo,$_SESSION['id_klasy'],$_SESSION['user_id'], $_SESSION['usertype']);//generowanie listy ocen ucznia
   
}
catch(PDOException $e)//nie mozna polaczyc sie z BD
{
    $TRESC.= "Nie można pobrać danych o klasach z bazy";
}
require_once 'szablony/witryna.php';
?>