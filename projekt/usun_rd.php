<?php
/*
 * Skrypt obsługujący usuwanie relacji rodzic-uczeń
 */
require_once 'include/obsluga_sesji.php';
require_once 'include/settings.php';
require_once 'include/funkcje.php';

checkSession();
checkUprawnienia("Administrator");//sprawdzenie czy uzytkownik ma poprawne uprawnienia
$url='a_rodz_ucz.php';

try //Próba nawiązania połączenia z DB
{
    
    $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName", $DBUser, $DBPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} //end try
catch(PDOException $e) //Gdy nie można połączyć się z DB
{
    echo 'Połączenie nie mogło zostać utworzone: ';
    die();
}//end catch
try
{
    echo $_GET['id_r'].' '.$_GET['id_d'];
    $stmt=$pdo->prepare ('DELETE FROM rodzice_uczniowie
                        WHERE id_rodzica=:id_r AND id_ucznia=:id_d');
    $stmt->bindValue(':id_r', $_GET['id_r'],PDO::PARAM_INT);
    $stmt->bindValue(':id_d', $_GET['id_d'],PDO::PARAM_INT);
    $stmt->execute();//usuwanie powiazania rodzic uczen
    $stmt->closeCursor();
    header ('Location:'.$url);
    die();
}
catch(PDOException $e)
{
    $_SESSION['alert']="Nie można usunąć użytkownika";
    
}
header ('Location:'.$url);
die();
?>