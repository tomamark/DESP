<?php
/*
 * Skrypt obsługujący usuwanie użytkownika
 */

require_once 'include/obsluga_sesji.php';
require_once 'include/settings.php';
require_once 'include/funkcje.php';

checkSession();
checkUprawnienia("Administrator");//sprawdzenie czy uzytkownik ma poprawne uprawnienia

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
    $stmt=$pdo->prepare ('DELETE FROM uzytkownicy
                        WHERE id_uzytkownika=:id');
    $stmt->bindValue(':id', $_GET['id'],PDO::PARAM_INT);
    $stmt->execute();//usuwanie z BD uzytkownika
    $stmt->closeCursor();
}
catch(PDOException $e)
{
    $_SESSION['alert']="Nie można usunąć użytkownika";
    
}
header ('Location:'.$_GET['url']);
die();
?>