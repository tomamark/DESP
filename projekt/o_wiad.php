<?php
/*
 * Skrypt obsługujący Wiadomości w menu Nauczyciela i Rodzica
 */
require_once 'include/obsluga_sesji.php';
require_once 'include/settings.php';
require_once 'skrypty/menu.php';
require_once 'nibble-forms/NibbleForm.class.php';
require_once 'include/funkcje.php';
$LOKALIZACJA='Wiadomosci';
$TRESC='';

checkSession();

$odbiorca=$_SESSION['user_id'];
$odbiorca_typ=$_SESSION['user_t'];
$powrot="o_wiad.php";

if (($odbiorca_typ==1) or ($odbiorca_typ==3))
{
    try {//nawiazywanie polaczenia z baza i odczyt danych
    $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName", $DBUser, $DBPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $TRESC.='<h2>Wiadomosci</h2>';
    $TRESC.='<a href="#wn" class="btn btn-success">Nieprzeczytane</a>';
    $TRESC.='<a href="#wp" class="btn btn-success">Przeczytane</a>';
    $TRESC.='<h3 id="wn">Nieprzeczytane</h3>';
    $TRESC.=genListaWiadomosci($pdo, $odbiorca,0,$odbiorca_typ,$powrot);//generowanie listy wiadomosci nieprzeczytanych
    $TRESC.='<h3 id="wp">Przeczytane</h3>';
    $TRESC.=genListaWiadomosci($pdo, $odbiorca,1,$odbiorca_typ,$powrot);//generowanie listy wiadomosci przeczytanych
}
catch(PDOException $e)//nie mozna polaczyc sie z BD
{
    $TRESC.= "Nie można pobrać danych z bazy".$e;
}
require_once 'szablony/witryna.php';
}
else {
    header('Location: index.php');
    die();
}
?>