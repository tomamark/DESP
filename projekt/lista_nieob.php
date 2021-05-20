<?php
/*
 * Skrypt obsługujący wyświetlenie listy nieobecności ucznia
 */
require_once 'include/obsluga_sesji.php';
require_once 'include/settings.php';
require_once 'skrypty/menu.php';
require_once 'nibble-forms/NibbleForm.class.php';
require_once 'include/funkcje.php';

$LOKALIZACJA='Nieobecności ucznia';
$TRESC='';

$uzytkownik='Nauczyciel';
checkSession();
checkUprawnienia($uzytkownik);//sprawdzenie czy uzytkownik ma poprawne uprawnienia
$powrot="n_nieob.php";

$id_ucznia=$_GET['id_u'];
$_SESSION['id_klasy']=$_GET['id_k'];


try {//nawiazywanie polaczenia z baza i odczyt danych
    $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName", $DBUser, $DBPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $uczen=getUzytkownikInfo($pdo, $id_ucznia);
    $TRESC.='<h2>Nieobecności ucznia: '.$uczen['nazwisko'].' '.$uczen['imie'].'</h2>';
    $TRESC.=genListaNieobUczniaNauczyciel($pdo, $id_ucznia,$powrot);//generowanie listy nieobecnosci ucznia
    }

catch(PDOException $e)//brak polaczenia z BD
{
    $TRESC.= "Nie można pobrać danych z bazy";
}
$TRESC.='<a class="btn btn-info" href="n_nieob.php">Powrót do listy</a>';
require_once 'szablony/witryna.php';
?>