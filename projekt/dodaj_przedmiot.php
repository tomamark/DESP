<?php
/*
 * Skrypt obsługujący dodawanie przedmiotu do klasy
 */
require_once 'include/obsluga_sesji.php';
require_once 'include/settings.php';
require_once 'include/funkcje.php';
require_once 'skrypty/menu.php';
require_once 'nibble-forms/NibbleForm.class.php';
$LOKALIZACJA="Dodawanie/zmiana przedmiotów klasy";
$TRESC="";
$TRESC.='<h2>Dodaj przedmioty do klasy</h2>';

checkSession();
checkUprawnienia("Administrator");//sprawdzenie czy uzytkownik ma poprawne uprawnienia

$url='a_przklas.php';
$id_klasy=$_GET['id_klasy'];
$modyfikacja=FALSE;
if (isset($_GET['id_przedmiotuklasy']))
{
   $modyfikacja=TRUE;
   $id_przedmiotuklasy=$_GET['id_przedmiotuklasy'];
   $id_nauczyciela=$_GET['id_nauczyciela'];
   $id_przedmiotu=$_GET['id_przedmiotu'];
   $TRESC="";
   $TRESC.='<h2>Zmień przedmiot klasy</h2>';
}
$form=NibbleForm::getInstance('','Zatwierdź','post',true,'inline','table');


if (isset($_POST['submit']))//sprawdzenie czy nacisnieto klawisz Submit
{
    if ($form->validate())
    {
        try//nawiazywanie polaczenia z baza i odczyt danych
        {
            
            $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName", $DBUser, $DBPass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            if ($modyfikacja==TRUE){
                $stmt = $pdo->prepare('UPDATE przedmioty_klasy
                                    SET id_przedmiotu= :id_p, id_nauczyciela= :id_n, id_klasy= :id_k
                                    WHERE id_przedmiotuklasy= :id_pk');
                
                
                $stmt->bindValue (':id_pk', $id_przedmiotuklasy, PDO::PARAM_INT);
                $stmt->bindValue (':id_k', $id_klasy, PDO::PARAM_INT);
                $stmt->bindValue (':id_p', $_POST['przedmioty'], PDO::PARAM_INT);
                $stmt->bindValue (':id_n', $_POST['nauczyciele'], PDO::PARAM_INT);
                $stmt->execute();//modyfikacja przedmiotu
            }// end if modyfikacja
            else {
            $stmt = $pdo->prepare('INSERT INTO przedmioty_klasy (id_klasy, id_przedmiotu, id_nauczyciela)
                                    VALUES (:id_k, :id_p, :id_n)');
            
                
                $stmt->bindValue (':id_k', $id_klasy, PDO::PARAM_INT);
                $stmt->bindValue (':id_p', $_POST['przedmioty'], PDO::PARAM_INT);
                $stmt->bindValue (':id_n', $_POST['nauczyciele'], PDO::PARAM_INT);
                $stmt->execute();//dodanie nowego przedmiotu
            } //end else modyfikacja
            $stmt->closeCursor();
            header ('Location:'.$url);
            die();
            
        }//try
        catch(PDOException $e)//brak polaczenia z BD
        {
            $TRESC="<p> Nie można dodać przedmiotu ".$e."</p>";
            $TRESC.="<a class=button href=".$url.">Powrót</a>";
        } //catch
        
    } //if...validate
    else
    {
        $TRESC.="Błędy w formularzu!";
        $TRESC.=$form->render();
    }//else...validate
} //if isset

else
{
    try {
        $lista_przedmiotow=array();
        $lista_nauczycieli=array();
        if ($modyfikacja==TRUE){
            $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName", $DBUser, $DBPass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $pdo->prepare('SELECT przedmiot
                            FROM przedmioty
                            WHERE id_przedmiotu= :id_p');
            $stmt->bindValue(':id_p', $id_przedmiotu,PDO::PARAM_INT);
            $stmt->execute();
            $przedmiot=$stmt->fetch(PDO::FETCH_ASSOC);
            $lista_przedmiotow[$id_przedmiotu]=$przedmiot['przedmiot'];
            $stmt = $pdo->prepare('SELECT nazwisko, imie
                            FROM uzytkownicy
                            WHERE id_uzytkownika= :id_n');
            $stmt->bindValue(':id_n', $id_nauczyciela, PDO::PARAM_INT);
            $stmt->execute();
            $nauczyciel=$stmt->fetch(PDO::FETCH_ASSOC);
            $lista_nauczycieli[$id_nauczyciela]=$nauczyciel['nazwisko'].' '.$nauczyciel['imie'];
        }
        $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName", $DBUser, $DBPass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $pdo->prepare('SELECT id_przedmiotu, przedmiot
                            FROM przedmioty');
        $stmt->execute();
        
        foreach ($stmt as $row){
            $lista_przedmiotow[$row['id_przedmiotu']] = $row['przedmiot'];
        }
        
        $stmt = $pdo->prepare('SELECT id_uzytkownika, nazwisko, imie
                            FROM uzytkownicy
                              WHERE typ=1');
        $stmt->execute();
        
        foreach ($stmt as $row){
            $lista_nauczycieli[$row['id_uzytkownika']] = $row['nazwisko'].' '.$row['imie'];
        }
        
        $form->przedmioty=new Select('Wybierz przedmiot do dodania',$lista_przedmiotow,1,true);
        $form->nauczyciele=new Select('Wybierz nauczyciela przedmiotu',$lista_nauczycieli,1,false);
        $TRESC.=$form->render();
    }
    catch (PDOException $e){
        $TRESC.="Błąd połączenia z bazą danych".$e;
    }
    
}//else;
require_once 'szablony/witryna.php';
?>