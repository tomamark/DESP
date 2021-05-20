<?php
/*
 * Skrypt obsługujący usuwanie lekcji z planu
 */
require_once 'include/obsluga_sesji.php';
require_once 'include/settings.php';
require_once 'include/funkcje.php';

checkSession();
$uzytkownik='Administrator';
checkUprawnienia($uzytkownik);//sprawdzenie czy uzytkownik ma poprawne uprawnienia
$id_klasy=$_GET['id_k'];
$id_godziny=$_GET['id_g'];
$id_dnia=$_GET['id_d'];
$id_przedmiotuklasy=$_GET['id_pk'];
$_SESSION['id_klasy']=$id_klasy;
$url='a_plany.php';
try
{//nawiazywanie polaczenia z baza i odczyt danych
    $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName", $DBUser, $DBPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt=$pdo->prepare ('DELETE FROM plany_lekcji
                        WHERE id_dnia= :id_d AND id_godziny= :id_g AND id_przedmiotuklasy= :id_pk');
    
    $stmt->bindValue(':id_d', $id_dnia,PDO::PARAM_INT);
    $stmt->bindValue(':id_g', $id_godziny,PDO::PARAM_INT);
    $stmt->bindValue(':id_pk', $id_przedmiotuklasy,PDO::PARAM_INT);
    $stmt->execute();//usuwanie lekcji z planu
    $stmt->closeCursor();
    $uczen=getUzytkownikInfo($pdo, $id_ucznia);
    header ('Location:'.$url);
    die();
}
catch(PDOException $e)//nie mozna polaczyc sie z BD
{
    $_SESSION['alert']="Nie można usunąć".$e;
    
}
header ('Location:'.$url);
die();
?>