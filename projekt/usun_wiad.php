<?php
/*
 * Skrypt obsługujący usuwanie wiadomości
 */

require_once 'include/obsluga_sesji.php';
require_once 'include/settings.php';
require_once 'include/funkcje.php';

checkSession();
$typ_uzyt=$_GET['t'];
$uzytkownik=setTypUzytkownika($typ_uzyt);
checkUprawnienia($uzytkownik);//sprawdzenie czy uzytkownik ma poprawne uprawnienia
$url='o_wiad.php';
$id_wiad=$_GET['id_w'];

try
{//nawiazywanie polaczenia z baza i odczyt danych
    $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName", $DBUser, $DBPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt=$pdo->prepare ('DELETE FROM wiadomosci
                        WHERE id_wiadomosci= :id_w');
    
    $stmt->bindValue(':id_w', $id_wiad,PDO::PARAM_INT);
    $stmt->execute();//usuwanie widomosci
    $stmt->closeCursor();
    header ('Location: http:'.$url);
}
catch(PDOException $e)//niemozna polaczyc sie z BD
{
    $_SESSION['alert']="Nie można usunąć".$e;
    
}
header ('Location:'.$url);
die();
?>