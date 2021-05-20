<?php
/*
 * Skrypt obsługujący zarządzanie rodzicami
 */
require_once 'include/obsluga_sesji.php';
require_once 'include/settings.php';
require_once 'skrypty/menu.php';
require_once 'nibble-forms/NibbleForm.class.php';
require_once 'include/funkcje.php';
$LOKALIZACJA='Administracja rodzicami';
$TRESC='';

checkSession();
checkUprawnienia("Administrator");//sprawdzenie czy uzytkownik ma poprawne uprawnienia

try {//nawiazywanie polaczenia z baza i odczyt danych
    $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName", $DBUser, $DBPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $TRESC.='<h2>Rodzice</h2>';
    $TRESC.=genTabelaUzytkownikow($pdo,3,'a_rodzice.php');//wyswietla liste rodzicow
}
catch(PDOException $e)
{
    $TRESC.= "Nie można pobrać danych o użytkownikach";
}
require_once 'szablony/witryna.php';
?>