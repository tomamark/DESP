<?php
/*
 * Skrypt obsługujący usuwanie przedmiotu klasy
 */
require_once 'include/obsluga_sesji.php';
require_once 'include/settings.php';
require_once 'include/funkcje.php';

checkSession();
checkUprawnienia("Administrator");//sprawdzenie czy uzytkownik ma poprawne uprawnienia
$url='a_przklas.php';
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
    $stmt=$pdo->prepare ('DELETE FROM przedmioty_klasy
                        WHERE id_przedmiotuklasy=:id');
    $stmt->bindValue(':id', $_GET['id'],PDO::PARAM_INT);
    $stmt->execute();//usuwanie przedmiotu dla klasy
    $stmt->closeCursor();
    header ('Location:'.$url);
    die();
}
catch(PDOException $e)
{
    echo 'Nie można usunąć przedmiotu. W bazie znajdują się już dodatkowe dane (np. oceny) związane z tym przedmiotem';
    echo '<a class="btn btn-info" href='.$url.'>Wróć</a>';
}

?>