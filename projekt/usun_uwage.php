<?php
/*
 * Skrypt obsługujący usuwanie uwagi
 */

require_once 'include/obsluga_sesji.php';
require_once 'include/settings.php';
require_once 'include/funkcje.php';

checkSession();
$uzytkownik='Nauczyciel';
checkUprawnienia($uzytkownik);//sprawdzenie czy uzytkownik ma poprawne uprawnienia
$id_uwagi=$_GET['id_uw'];
$id_ucznia=$_GET['id_u'];
$id_nauczyciela=$_GET['id_n'];
$url='n_uwagi.php';
try
{
    $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName", $DBUser, $DBPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt=$pdo->prepare ('DELETE FROM uwagi
                        WHERE id_uwagi= :id_uw');
    
    $stmt->bindValue(':id_uw', $id_uwagi,PDO::PARAM_INT);
    $stmt->execute();//usuwanie uwagi
    $stmt->closeCursor();
    $uczen=getUzytkownikInfo($pdo, $id_ucznia);
    $url='lista_uwag.php?id_u='.$id_ucznia.'&id_n='.$id_nauczyciela.'&id_k='.$uczen['id_klasy'];
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