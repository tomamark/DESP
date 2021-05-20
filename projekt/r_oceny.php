<?php
/*
 * Skrypt obsługujący Oceny w menu Rodzica
 */
require_once 'include/obsluga_sesji.php';
require_once 'include/settings.php';
require_once 'skrypty/menu.php';
require_once 'nibble-forms/NibbleForm.class.php';
require_once 'include/funkcje.php';
$LOKALIZACJA='Oceny dzieci';
$TRESC='';

checkSession();
$uzytkownik='Rodzic';
checkUprawnienia($uzytkownik);//sprawdzenie czy uzytkownik ma poprawne uprawnienia
$id_uzytk=$_SESSION['user_id'];
$typ_uzytk=$_SESSION['usertype'];

try {//nawiazywanie polaczenia z baza i odczyt danych
    $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName", $DBUser, $DBPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $TRESC.='<h2>Twoje dzieci</h2>';
    $dzieci=getDzieciRodzica($pdo,$id_uzytk );//generowanie listy dzieci dla danego rodzica
    $TRESC.=genListaDzieci($dzieci);
    foreach ($dzieci as $row){
        $TRESC.='<h3 id=u'.$row['id_uzytkownika'].'>Przedmioty ucznia: '.$row['imie'].' '.$row['nazwisko'].'</h3>';
        $TRESC.=genListaPrzedmiotowKlasyHTML($pdo,$row['id_klasy']);
        $TRESC.='<h3>Oceny ucznia: '.$row['imie'].' '.$row['nazwisko'].'</h3>';
        $TRESC.=genListaOcenWszystkie($pdo,$row['id_klasy'],$row['id_uzytkownika'], $typ_uzytk);//generowanie listy ocen dla dziecka       
    }
}
catch(PDOException $e)//nie mozna polaczyc sie z BD
{
    $TRESC.= "Nie można pobrać danych z bazy";
}
require_once 'szablony/witryna.php';
?>