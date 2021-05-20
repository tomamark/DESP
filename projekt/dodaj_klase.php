<?php
/*
 * Skrypt do dodawania klasy
 */
require_once 'include/obsluga_sesji.php';
require_once 'include/settings.php';
require_once 'include/funkcje.php';

checkSession();
checkUprawnienia("Administrator");//sprawdzenie czy uzytkownik ma poprawne uprawnienia
$url='a_uczniowie.php';

if (isset ($_POST['submit']))//sprawdzanie czy nacisnieto klawisz Submit
{
    $nowa_klasa=strtoupper($_POST['nowa_klasa']);
    try //Próba nawiązania połączenia z DB
    {
    
    $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName", $DBUser, $DBPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $pdo->prepare('SELECT id_klasy
                                   FROM klasy
                                   WHERE klasa= :klasa');
    $stmt->bindValue('klasa', $nowa_klasa);
    $stmt->execute();
    $wynik=$stmt->rowCount();
    if ($wynik==0)//sprawdzanie czy takiej klasy nie ma w bazie 
        {
            $stmt = $pdo->prepare('INSERT INTO klasy
                                    SET klasa= :nowa_klasa');
            $stmt->bindValue(':nowa_klasa', $nowa_klasa);//dodawanie nowej klasy do DB
            $stmt->execute();
        }
    $stmt->closeCursor();
    } //end try
    catch(PDOException $e) //Gdy nie można połączyć się z DB
    {
    echo 'Połączenie nie mogło zostać utworzone: ';
    die();
    }//end catch
}
header ('Location:'.$url);
?>