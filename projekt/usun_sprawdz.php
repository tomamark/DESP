<?php
/*
 * Skrypt obsługujący usuwanie sprawdzianu
 */
require_once 'include/obsluga_sesji.php';
require_once 'include/settings.php';
require_once 'include/funkcje.php';

checkSession();
checkUprawnienia("Nauczyciel");//sprawdzenie czy uzytkownik ma poprawne uprawnienia
$url='n_sprawdz.php';
$_SESSION['id_przedmiotu']=$_GET['id_pk'];
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
    $stmt=$pdo->prepare ('DELETE FROM sprawdziany
                        WHERE id_sprawdzianu=:id_sp');
    $stmt->bindValue(':id_sp', $_GET['id_sp'],PDO::PARAM_INT);
    $stmt->execute();//usuwanie sprawdzianu
    $stmt->closeCursor();
    header ('Location:'.$url);
    die();
}
catch(PDOException $e)
{
    echo 'Nie można usunąć sprawdzianu';
    echo '<a class="btn btn-info" href="'.$url.'">Wróć</a>';
}

?>