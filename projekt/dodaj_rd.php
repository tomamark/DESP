<?php
/*
 * Skrypt obsługujący dodawanie ucznia do rodzica
 */
require_once 'include/obsluga_sesji.php';
require_once 'include/settings.php';
require_once 'include/funkcje.php';
require_once 'skrypty/menu.php';
require_once 'nibble-forms/NibbleForm.class.php';
$LOKALIZACJA="Dodawanie dziecka do rodzica";
$TRESC="";
$TRESC.='<h2>Przypisz uczniów do rodzica</h2>';

checkSession();
checkUprawnienia("Administrator");//sprawdzenie czy uzytkownik ma poprawne uprawnienia
$url='a_rodz_ucz.php';
$id_r=$_GET['id_r'];
$imie_r=$_GET['imie_r'];
$nazwisko_r=$_GET['nazwisko_r'];
$form=NibbleForm::getInstance('','Wybierz','post',true,'inline','table');


if (isset($_POST['submit']))//sprawdzanie czy nacisnieto przycisk Submit
{
    if ($form->validate())
    {
        try//nawiazywanie polaczenia z baza i odczyt danych
        {
            
            $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName", $DBUser, $DBPass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $pdo->prepare('INSERT INTO rodzice_uczniowie 
                                    VALUES (:id_r, :id_u)');
            foreach ($_POST['uczniowie'] as $row){
                
                $stmt->bindValue (':id_r', $id_r, PDO::PARAM_INT);
                $stmt->bindValue (':id_u', intval ($row), PDO::PARAM_INT);
                $stmt->execute();//dopisywanie powiazania ucznia z rodzicem
            }
            $stmt->closeCursor();
            header ('Location:'.$url);
            die();
          
        }//try
        catch(PDOException $e)//nie mozna nawiazac polaczenia z BD
        {
            $TRESC="<p> Nie można dodać relacji ".$e."</p>";
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
{//odczyt powiazan rodzicow i przypisanych do nich dzieci 
    try {//nawiazywanie polaczenia z baza i odczyt danych
        $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName", $DBUser, $DBPass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $pdo->prepare('SELECT id_uzytkownika, nazwisko, imie
                            FROM uzytkownicy
                            WHERE typ=2 AND id_uzytkownika NOT IN
                            (SELECT id_ucznia
                            FROM rodzice_uczniowie
                            WHERE id_rodzica= :id_rodzica)');
        $stmt->bindValue(':id_rodzica', $id_r,PDO::PARAM_INT);
        $stmt->execute();
        $lista=array();
        foreach ($stmt as $row){
            $uczen=$row['nazwisko'].' '.$row['imie'];
            $lista[$row['id_uzytkownika']] = $uczen;
        }
        $form->uczniowie=new MultipleSelect('Wybierz uczniów do dodania',$lista);
        $TRESC.=$form->render();
    }
    catch (PDOException $e){
        $TRESC.="Błąd ".$e;
    }
    
}//else;
require_once 'szablony/witryna.php';
?>