<?php
/*
 * Skrypt obsługujący wyświetlenie listy uwag ucznia
 */
require_once 'include/obsluga_sesji.php';
require_once 'include/settings.php';
require_once 'skrypty/menu.php';
require_once 'nibble-forms/NibbleForm.class.php';
require_once 'include/funkcje.php';
$LOKALIZACJA='Uwagi ucznia';
$TRESC='';

$uzytkownik='Nauczyciel';
checkSession();
checkUprawnienia($uzytkownik);//sprawdzenie czy uzytkownik ma poprawne uprawnienia

$powrot='n_uwagi.php';
$id_ucznia=$_GET['id_u'];
$id_nauczyciela=$_SESSION['user_id'];
$_SESSION['id_klasy']=$_GET['id_k'];


try {//nawiazywanie polaczenia z baza i odczyt danych
    $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName", $DBUser, $DBPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $uczen=getUzytkownikInfo($pdo, $id_ucznia);
    $TRESC.='<h2>Uwagi ucznia: '.$uczen['nazwisko'].' '.$uczen['imie'].'</h2>';
    $TRESC.=genListaUwagUczniaNauczyciel($pdo, $id_ucznia,$id_nauczyciela);//generowanie listy uwag nauczyciela do ucznia
}

catch(PDOException $e)// nie mozna polaczyc sie z BD
{
    $TRESC.= "Nie można pobrać danych z bazy";
}
$TRESC.='<a class="btn btn-info" href="'.$powrot.'">Powrót do listy uczniów</a>';
require_once 'szablony/witryna.php';
?>