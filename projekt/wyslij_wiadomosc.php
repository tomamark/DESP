<?php
/*
 * Skrypt obsługujący wysyłanie pojedynczej wiadomości
 */

require_once 'include/obsluga_sesji.php';
require_once 'include/settings.php';
require_once 'skrypty/menu.php';
require_once 'nibble-forms/NibbleForm.class.php';
require_once 'include/funkcje.php';
$LOKALIZACJA='Wysyłanie wiadomości';
$TRESC='';
checkSession();
if (isset($_GET['t'])){
    $typ_uzytkownika=$_GET['t'];
}
$uzytkownik=setTypUzytkownika($typ_uzytkownika);
checkUprawnienia($uzytkownik);//sprawdzenie czy uzytkownik ma poprawne uprawnienia
$id_nadawcy=$_GET['id_n'];
checkAutor($id_nadawcy); // Sprawdzamy czy id nadawcy zgadza się z id sesji
$id_odbiorcy=$_GET['id_o'];
if (isset($_GET['p'])){
    $url=$_GET['p'];
}
$data=date('Y-m-d');



if (isset($_POST['submit']))//sprawdzamy czy nacisnietoklawisz Submit
{
    $wiadomosc=$_POST['wiadomosc'];
    if (strlen($wiadomosc)>0)//sprawdzamy czy wiadomosc ma tresc
    {
        try
        {//nawiazywanie polaczenia z baza i odczyt danych
            
            $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName", $DBUser, $DBPass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $pdo->prepare('INSERT INTO wiadomosci (id_nadawcy, id_odbiorcy, tresc, przeczytana, data)
                                          VALUES (:id_n, :id_o, :tresc, 0, :data)  
                                        ');
            $stmt->bindValue(':id_n',$id_nadawcy,PDO::PARAM_INT);
            $stmt->bindValue(':id_o',$id_odbiorcy,PDO::PARAM_INT);
            $stmt->bindValue(':tresc',$wiadomosc); 
            $stmt->bindValue(':data',$data);
            
            $stmt->execute();//wysylanie wiadomosci
            header ('Location:'.$url);
            die();
        }//try
        catch(PDOException $e)//nie mona polaczyc sie z BD
        {
            $TRESC='<p> Nie można wysłać wiadomości</p>';
            $TRESC.='<a class="btn btn-info" href="'.$url.'">Powrót</a>';
        } //catch
        
    } //if...validate
    else
    {
        $TRESC.='<p>Wiadomość nie może być pusta</p>';
        $TRESC.='<a class="btn btn-info" href="wyslij_wiadomosc.php?id_o='.$id_odbiorcy.'&amp;id_n='.
        $id_nadawcy.'&amp;t='.$typ_uzytkownika.'">Wyślij jeszcze raz</a>';
        
    }//else...validate
} //if isset
else
{//nie nacisnieto klawisza Submit 
        
    try
    {//nawiazywanie polaczenia z baza i odczyt danych
        
        $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName", $DBUser, $DBPass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $nadawca=getUzytkownikInfo($pdo, $id_nadawcy);
        $odbiorca=getUzytkownikInfo($pdo, $id_odbiorcy);
        $nadawca_form=$nadawca['imie'].' '.$nadawca['nazwisko'];
        $odbiorca_form=$odbiorca['imie'].' '.$odbiorca['nazwisko'];
        $TRESC.='<h2>Nowa Wiadomość</h2>';
        $TRESC.=setNowaWiadForm($nadawca_form, $odbiorca_form);
    }//try
    catch(PDOException $e)//niemozna polaczyc sie z BD
    {
        $TRESC='<p> Nie można pobrać danych użytkownika z bazy</p>';
        $TRESC.='<a class="btn btn-info" href="'.$url.'">Powrót</a>';
    } //catch
    
    
    
    
}//else;
require_once 'szablony/witryna.php';
?>