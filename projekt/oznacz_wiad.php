<?php
/*
 * Skrypt do oznaczania odebranych wiadomości przeczytane/nieprzeczytane
 */
require_once 'include/obsluga_sesji.php';
require_once 'include/settings.php';
require_once 'include/funkcje.php';

checkSession();
$typ_uzyt=$_GET['t'];
$uzytkownik=setTypUzytkownika($typ_uzyt);
checkUprawnienia($uzytkownik);
$url='o_wiad.php';
$id_wiad=$_GET['id_w'];

try
{
    $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName", $DBUser, $DBPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //Sprawdzamy status wiadomości
    $stmt=$pdo->prepare ('SELECT przeczytana FROM wiadomosci
                        WHERE id_wiadomosci= :id_w');
    $stmt->bindValue(':id_w', $id_wiad,PDO::PARAM_INT);
    $stmt->execute();
    $dane=$stmt->fetch();
    $przeczytana=$dane[0];
    if ($przeczytana==0){
        $nowa=1;
    }
    else{
        $nowa=0;
    }
    //Zmiana statusu wiadomości w BD
    $stmt=$pdo->prepare ('UPDATE wiadomosci
                        SET przeczytana= :nowa
                        WHERE id_wiadomosci= :id_w');
    $stmt->bindValue(':id_w', $id_wiad,PDO::PARAM_INT);
    $stmt->bindValue(':nowa', $nowa,PDO::PARAM_INT);
    $stmt->execute();
    $stmt->closeCursor();
    header ('Location:'.$url);
    die();
}
catch(PDOException $e)
{
    $_SESSION['alert']="Nie można zmienić".$e;
    
}
header ('Location:'.$url);
die();
?>