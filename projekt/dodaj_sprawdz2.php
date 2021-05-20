<?php
/*
 * Skrypt obsługujący dodawanie sprawdzianu
 */
require_once 'include/obsluga_sesji.php';
require_once 'include/settings.php';
require_once 'skrypty/menu.php';
require_once 'nibble-forms/NibbleForm.class.php';
require_once 'include/funkcje.php';
$LOKALIZACJA='Sprawdzian';
$uzytkownik='Nauczyciel';
checkSession();
checkUprawnienia($uzytkownik);//sprawdzenie czy uzytkownik ma poprawne uprawnienia
$url2='n_sprawdz.php';

if (isset($_POST['submit2']))//sprawdzanie czy nacisnieto klawisz Submit
{
    $_SESSION['id_przedmiotu']=$_POST['id_pk'];
    $id_przedmiotuklasy=$_POST['id_pk'];
    $data=$_POST['data'];
        try
        {//nawiazywanie polaczenia z baza i odczyt danych
            
            $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName", $DBUser, $DBPass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stmt = $pdo->prepare('INSERT INTO sprawdziany (data, id_przedmiotuklasy)
                                       VALUES (:data, :id_pk)
                                               ');
                $stmt->bindValue(':id_pk', $id_przedmiotuklasy,PDO::PARAM_INT);
                $stmt->bindValue(':data', $data);
                $stmt->execute();//dopisanie nowego sprawdzianu dla klasy
           
        }//try
        catch(PDOException $e)//nie mozna polaczyc sie z BD
        {
            $TRESC='<p> Nie można dodać sprawdzianu. </p>'.$e;
            $TRESC.='<a class="btn btn-info" href="'.$url.'>Powrót</a>';
        } //catch
        
}
$url = 'http://' . $_SERVER['HTTP_HOST'];            
$url .= rtrim(dirname($_SERVER['PHP_SELF']), '/\\'); 
$url .= '/'.$url2; 
header ('Location:'.$url);
die ();
?>