<?php
/*
 * Skrypt obsługujący Sprawdziany w menu Ucznia
 */
require_once 'include/obsluga_sesji.php';
require_once 'include/settings.php';
require_once 'skrypty/menu.php';
require_once 'nibble-forms/NibbleForm.class.php';
require_once 'include/funkcje.php';
$LOKALIZACJA='Twoje sprawdziany';
$TRESC='';

checkSession();
$uzytkownik='Uczeń';
checkUprawnienia($uzytkownik);//sprawdzenie czy uzytkownik ma poprawne uprawnienia

try {//nawiazywanie polaczenia z baza i odczyt danych
    $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName", $DBUser, $DBPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $TRESC.='<h2>Twoje sprawdziany</h2>';
    $TRESC.=genListaSprawdzianyKlasy($pdo,$_SESSION['id_klasy']);
    
}
catch(PDOException $e)//nie mozna polaczyc sie z BD
{
    $TRESC.= "Nie można pobrać danych o klasach z bazy";
}
require_once 'szablony/witryna.php';
?>