<?php
/*
 * Skrypt obsługujący wysyłanie wiadomości do rodziców
 */

require_once 'include/obsluga_sesji.php';
require_once 'include/settings.php';
require_once 'skrypty/menu.php';
require_once 'nibble-forms/NibbleForm.class.php';
require_once 'include/funkcje.php';
$LOKALIZACJA='Wysyłanie wiadomości do rodziców';
$TRESC='';
checkSession();
if (isset($_GET['id_k'])){
    $cala_klasa=TRUE;
    $id_klasy=$_GET['id_k'];
    $klasa=$_GET['k'];
}
else {
    $cala_klasa=FALSE;
    $id_ucznia=$_GET['id_u'];
}
$uzytkownik='Nauczyciel';
checkUprawnienia($uzytkownik);//sprawdzenie czy uzytkownik ma poprawne uprawnienia
$id_nadawcy=$_SESSION['user_id'];
$url=$_GET['p'];
$data=date('Y-m-d');



if (isset($_POST['submit']))//sprawdzanie czy nacisnieto klawisz Submit
{
    $wiadomosc=$_POST['wiadomosc'];
    if (strlen($wiadomosc)>0)//sprawdzanie czy tresc wiadomosci nie jest pusta
    {
        
        try
        {//nawiazywanie polaczenia z baza i odczyt danych
            $lista_odbiorcow=array();
            $lista_uczniow=array();
            $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName", $DBUser, $DBPass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            if ($cala_klasa==TRUE){
                $lista_uczniow=getListaUczniowKlasy($pdo, $id_klasy);
                $i=1;
                foreach ($lista_uczniow as $row){
                    $rodzice=getRodziceUcznia($pdo, $row['id_uzytkownika']);
                    foreach ($rodzice as $row2){
                        $lista_odbiorcow[]=$row2['id_uzytkownika'];
                    }
                }
            }
                else{
                    $rodzice=getRodziceUcznia($pdo, $id_ucznia);
                    $i=1;
                    foreach ($rodzice as $row2){
                        $lista_odbiorcow[]=$row2['id_uzytkownika'];
                    }
                }
  
            $stmt = $pdo->prepare('INSERT INTO wiadomosci (id_nadawcy, id_odbiorcy, tresc, przeczytana, data)
                                          VALUES (:id_n, :id_o, :tresc, 0, :data)
                                        ');
            foreach ($lista_odbiorcow as $row){
            $stmt->bindValue(':id_n',$id_nadawcy,PDO::PARAM_INT);
            $stmt->bindValue(':id_o',$row,PDO::PARAM_INT);
            $stmt->bindValue(':tresc',$wiadomosc);
            $stmt->bindValue(':data',$data);
            $stmt->execute();//wysylanie wiadomosci do wybranych uzytkownikow
            }
            header ('Location:'.$url);
            die();
        }//try
        catch(PDOException $e)//nie mozna polaczyc sie z BD
        {
            $TRESC='<p> Nie można wysłać wiadomości</p>';
            $TRESC.='<a class="btn btn-info" href="'.$url.'">Powrót</a>';
        } //catch
        
    } //if...validate
    else
    {
        $TRESC.='<p>Wiadomość nie może być pusta</p>';
        $TRESC.='<a class="btn btn-info" href="'.$url.'">Powrót</a>';
            
    }//else...validate
} //if isset
else
{//nie nacisnieto klawisza Submit
    
    try
    {//nawiazywanie polaczenia z baza i odczyt danych
        
        $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName", $DBUser, $DBPass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $nadawca=getUzytkownikInfo($pdo, $id_nadawcy);
        $nadawca_form=$nadawca['imie'].' '.$nadawca['nazwisko'];
        $odbiorca_form='';
        if ($cala_klasa==TRUE){
            $odbiorca_form='Rodzice uczniów klasy'.$klasa;
        }
        else{
            $rodzice=getRodziceUcznia($pdo, $id_ucznia);
            foreach ($rodzice as $row){
                $odbiorca_form.=$row['imie'].' '.$row['nazwisko'].'; ';
            }
        }
        $TRESC.='<h2>Nowa Wiadomość</h2>';
        $TRESC.=setNowaWiadForm($nadawca_form, $odbiorca_form);
    }//try
    catch(PDOException $e)//niemozna polaczyc sie z BD
    {
        $TRESC='<p> Nie można pobrać danych użytkownika z bazy</p>';
        $TRESC.='<a class=button href=".$url.">Powrót</a>';
    } //catch
    
    
    
    
}//else;
require_once 'szablony/witryna.php';