<?php
/*
 * Skrypt obsługujący Uwagi w menu Rodzica
 */
require_once 'include/obsluga_sesji.php';
require_once 'include/settings.php';
require_once 'skrypty/menu.php';
require_once 'nibble-forms/NibbleForm.class.php';
require_once 'include/funkcje.php';
$LOKALIZACJA='Uwagi dzieci';
$TRESC='';

checkSession();
$uzytkownik='Rodzic';
checkUprawnienia($uzytkownik);//sprawdzenie czy uzytkownik ma poprawne uprawnienia

try {//nawiazywanie polaczenia z baza i odczyt danych
    $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName", $DBUser, $DBPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $TRESC.='<h2>Twoje dzieci</h2>';
    $dzieci=getDzieciRodzica($pdo, $_SESSION['user_id']);
    $TRESC.=genListaDzieci($dzieci);//generowanie list dzieci dla danego rodzica
    foreach ($dzieci as $row){
        $TRESC.='<h3 id=u'.$row['id_uzytkownika'].'>Uwagi ucznia: '.$row['imie'].' '.$row['nazwisko'].'</h3>';
        $TRESC.=genListaUwagUcznia($pdo,$row['id_uzytkownika']);//generowanie listy uwag dla dziecka
    }
}
catch(PDOException $e)//nie mozna polaczyc sie z BD
{
    $TRESC.= "Nie można pobrać danych z bazy";
}
require_once 'szablony/witryna.php';
?>