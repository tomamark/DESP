<?php
/*
 * Skrypt obsługujący zarządzanie uczniami
 */
require_once 'include/obsluga_sesji.php';
require_once 'include/settings.php';
require_once 'skrypty/menu.php';
require_once 'nibble-forms/NibbleForm.class.php';
require_once 'include/funkcje.php';
$LOKALIZACJA='Administracja klasami i uczniami';
$TRESC='';

checkSession();
$uzytkownik='Administrator';
checkUprawnienia($uzytkownik);//sprawdzenie czy uzytkownik ma poprawne uprawnienia

try {//nawiazywanie polaczenia z baza i odczyt danych
    $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName", $DBUser, $DBPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $TRESC.='<h2>Klasy</h2>';
    $TRESC.=genListaKlasHTML($pdo);//wyswietla liste klas
    $form=NibbleForm::getInstance('dodaj_klase.php','Dodaj klasę','post',true,'inline','table');//dopisywanie nowej klasy
    $form->nowa_klasa=new Text('Nowa klasa: ',true,2,'/[a-zA-Z0-9]+/');
    $TRESC.=$form->render();
    $TRESC.='<h2>Uczniowie</h2>';
    $TRESC.=genTabelaUczniowieWszyscy($pdo);//wyswietlanie listy uczniow 
   
}
catch(PDOException $e)
{
    $TRESC.= "Nie można pobrać danych o klasach z bazy";
} 
require_once 'szablony/witryna.php';
?>