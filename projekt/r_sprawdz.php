<?php
/*
 * Skrypt obsługujący Sprawdziany w menu Rodzica
 */
require_once 'include/obsluga_sesji.php';
require_once 'include/settings.php';
require_once 'skrypty/menu.php';
require_once 'nibble-forms/NibbleForm.class.php';
require_once 'include/funkcje.php';
$LOKALIZACJA='Sprawdziany dzieci';
$TRESC='';

checkSession();
$uzytkownik='Rodzic';
checkUprawnienia($uzytkownik);//sprawdzenie czy uzytkownik ma poprawne uprawnienia

try {//nawiazywanie polaczenia z baza i odczyt danych
    $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName", $DBUser, $DBPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $TRESC.='<h2>Twoje dzieci</h2>';
    $dzieci=getDzieciRodzica($pdo, $_SESSION['user_id']);
    $TRESC.=genListaDzieci($dzieci);//generowanie listy dzieci dla danego rodzica
    foreach ($dzieci as $row){
        $TRESC.='<h3 id="u'.$row['id_uzytkownika'].'">Sprawdziany ucznia: '.$row['imie'].' '.$row['nazwisko'].'</h3>';
        $TRESC.=genListaSprawdzianyKlasy($pdo,$row['id_klasy']);//generowanie listy sprawdzianow dla dzieci
    }
}
catch(PDOException $e)//niemozna polaczyc sie z BD
{
    $TRESC.= "Nie można pobrać danych z bazy";
}
require_once 'szablony/witryna.php';
?>