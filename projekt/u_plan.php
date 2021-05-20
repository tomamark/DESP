<?php
/*
 * Skrypt obsługujący Plan lekcji w menu Ucznia
 */
require_once 'include/obsluga_sesji.php';
require_once 'include/settings.php';
require_once 'skrypty/menu.php';
require_once 'nibble-forms/NibbleForm.class.php';
require_once 'include/funkcje.php';
$LOKALIZACJA='Oceny ucznia';
$TRESC='';

checkSession();
$uzytkownik='Uczeń';
checkUprawnienia($uzytkownik);//sprawdzenie czy uzytkownik ma poprawne uprawnienia
$klasa_id=$_SESSION['id_klasy'];

try {//nawiazywanie polaczenia z baza i odczyt danych
    $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName", $DBUser, $DBPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $TRESC.='<h2>Dni tygodnia</h2>';
    $TRESC.=genListaDniHTML($pdo);//genenrowanie listy dni tygodnia
    $TRESC.='<h2>Twój plan lekcji</h2>';
    $TRESC.=genListaPlanowWszystkie($pdo,$klasa_id);//generowanie planu lekcji
}
catch(PDOException $e)//nie mozna polaczyc sie z BD
{
    $TRESC.= "Nie można pobrać danych o planach z bazy ".$e;
}
require_once 'szablony/witryna.php';
?>