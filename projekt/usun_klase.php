<?php
/*
 * Skrypt obsługujący usuwanie klasy
 */
require_once 'include/obsluga_sesji.php';
require_once 'include/settings.php';
require_once 'include/funkcje.php';

checkSession();
checkUprawnienia("Administrator");//sprawdzenie czy uzytkownik ma poprawne uprawnienia
$url='a_uczniowie.php';
$id_klasy=$_GET['id'];

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
    $stmt=$pdo->prepare ('DELETE FROM klasy
                        WHERE id_klasy=:id_klasy');
    $stmt->bindValue(':id_klasy', $id_klasy,PDO::PARAM_INT);
    $stmt->execute();//usuwanie danej klasy z BD
    $stmt->closeCursor();
    header ('Location:'.$url);
    die();
}
catch(PDOException $e)
{
    $TRESC="<p> Nie można usunąć klasy, ponieważ zdefiniowano dla niej przedmioty</p>";
    $TRESC.="<a class=button href=".$url.">Powrót</a>";
}
header ('Location:'.$url);
die();
?>
