<?php
/*
 * Skrypt obsługujący wpisywanie nieobecności
 */
require_once 'include/obsluga_sesji.php';
require_once 'include/settings.php';
require_once 'skrypty/menu.php';
require_once 'nibble-forms/NibbleForm.class.php';
require_once 'include/funkcje.php';
$LOKALIZACJA='Nieobecności ucznia';
$TRESC='';

$uzytkownik='Nauczyciel';
$url='n_nieob.php';
$data=date('Y-m-d');

checkSession();
checkUprawnienia($uzytkownik);//sprawdzenie czy uzytkownik ma poprawne uprawnienia


$id_ucznia=$_GET['id_u'];
$_SESSION['id_klasy']=$_GET['id_k'];


try {//nawiazywanie polaczenia z baza i odczyt danych
    $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName", $DBUser, $DBPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $pdo->prepare ('SELECT id_nieobecnosci
                            FROM nieobecnosci
                            WHERE id_ucznia = :id_u AND data= :data
                            ');
    $stmt->bindValue (':id_u', $id_ucznia, PDO::PARAM_INT);
    $stmt->bindValue (':data', $data, PDO::PARAM_INT);
    $stmt->execute();
    $nieob=$stmt->fetch();
    if (!isset($nieob['id_nieobecnosci'])){//sprawdzenie czy takiej nieobecnosci nie ma w BD
        $stmt = $pdo->prepare ('INSERT INTO nieobecnosci (id_ucznia, data, usprawiedliwiona)
                            VALUES (:id_u, :data, 0)
                            ');
        $stmt->bindValue (':id_u', $id_ucznia, PDO::PARAM_INT);
        $stmt->bindValue (':data', $data, PDO::PARAM_INT);
        $stmt->execute();//dopisanie nowej nieobecnosci w BD
    }
    $stmt->closeCursor();
}

catch(PDOException $e)//brak polaczenia z BD
{
    $TRESC.= "Nie można pobrać danych z bazy";
}
header ('Location:'.$url);
die ();
?>