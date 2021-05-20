<?php
/*
 * Skrypt obsługujący wpisywanie uwagi
 */
require_once 'include/obsluga_sesji.php';
require_once 'include/settings.php';
require_once 'skrypty/menu.php';
require_once 'nibble-forms/NibbleForm.class.php';
require_once 'include/funkcje.php';
$LOKALIZACJA='Wpisywanie uwagi';
$TRESC='';
checkSession();

$uzytkownik='Nauczyciel';
checkUprawnienia($uzytkownik);//sprawdzenie czy uzytkownik ma poprawne uprawnienia
$id_nauczyciela=$_GET['id_n'];
checkAutor($id_nauczyciela); // Sprawdzamy czy id nadawcy zgadza się z id sesji
$id_ucznia=$_GET['id_u'];
$url=$_GET['p'];
$data=date('Y-m-d');
if (isset($_GET['id_k'])){
$_SESSION['id_klasy']=$_GET['id_k'];
}


if (isset($_POST['submit']))//sprawdzamy czy nacisnieto klawisz Submit
{
    $uwaga=$_POST['uwaga'];
    if (strlen($uwaga)>0)//sprawdzanie czy dlugosc uwagi>0
    {
        $typ=$_POST['typ'];
        try
        {//nawiazywanie polaczenia z baza i odczyt danych
            
            $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName", $DBUser, $DBPass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $pdo->prepare('INSERT INTO uwagi (id_ucznia, id_nauczyciela, opis, negatywna, data)
                                          VALUES (:id_u, :id_n, :tresc, :typ, :data)
                                        ');
            $stmt->bindValue(':id_u',$id_ucznia,PDO::PARAM_INT);
            $stmt->bindValue(':id_n',$id_nauczyciela,PDO::PARAM_INT);
            $stmt->bindValue(':tresc',$uwaga);
            $stmt->bindValue(':typ',$typ);
            $stmt->bindValue(':data',$data);
            
            $stmt->execute();//dodawanie uwagi do BD
            header ('Location:'.$url);
            die();
        }//try
        catch(PDOException $e)
        {
            $TRESC='<p> Nie można dodać uwagi</p>';
            $TRESC.='<a class="btn btn-info" href="'.$url.'">Powrót</a>';
        } //catch
        
    } //if...validate
    else
    {
        $TRESC.='<p>Treść uwagi nie może być pusta</p>';
        $TRESC.='<a class="btn btn-info" href="dodaj_uwage.php?id_u='.$id_ucznia.'&amp;id_n='.
            $id_nauczyciela.'&amp;p='.$url.'">Wyślij jeszcze raz</a>';
            
    }//else...validate
} //if isset
else
{
    
    try
    {
        
        $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName", $DBUser, $DBPass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $uczen=getUzytkownikInfo($pdo, $id_ucznia);
        $uczen_form=$uczen['imie'].' '.$uczen['nazwisko'];
        $TRESC.='<h2>Nowa uwaga</h2>';
        $TRESC.=setNowaUwagaForm($uczen_form);
    }//try
    catch(PDOException $e)
    {
        $TRESC='<p> Nie można pobrać danych użytkownika z bazy</p>';
        $TRESC.='<a class="btn btn-info" href=".$url.">Powrót</a>';
    } //catch
    
    
    
    
}//else;
require_once 'szablony/witryna.php';
?>