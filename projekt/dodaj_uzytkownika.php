<?php
/*
 * Skrypt obsługujący dodawanie nowego użytkownika
 */
require_once 'include/obsluga_sesji.php';
require_once 'include/settings.php';
require_once 'skrypty/menu.php';
require_once 'nibble-forms/NibbleForm.class.php';
require_once 'include/funkcje.php';
$LOKALIZACJA='Dodawanie użytkownika';
$TRESC='';
$TRESC.='<h2> Nowy '.$UZYTKOWNIK[$_GET['typ']].'</h2>';

checkSession();
checkUprawnienia('Administrator');//sprawdzenie czy uzytkownik ma poprawne uprawnienia

$form=setNowyUzytkownikForm();

$id_klasy=NULL;
$typ=$_GET['typ'];
$url=setAdminUrl($typ);
if (isset($_GET['id_klasy'])){
    $id_klasy=$_GET['id_klasy'];
}
if (isset($_POST['submit']))//sprawdzanie czy nacisnieto klawisz Submit
{
    if ($form->validate())
    {
        try//nawiazywanie polaczenia z baza i odczyt danych
        {
            
            $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName", $DBUser, $DBPass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $pdo->prepare('INSERT INTO uzytkownicy
                                       SET  imie= :imie,
                                            nazwisko= :nazwisko,
                                            telefon= :telefon,
                                            email= :email,
                                            haslo= :haslo,
                                            typ= :typ,
                                            id_klasy= :id_klasy
                                        ');
            $stmt->bindValue(':nazwisko', $_POST['nazwisko'],PDO::PARAM_STR);
            $stmt->bindValue(':imie', $_POST['imie'],PDO::PARAM_STR);
            $stmt->bindValue(':email', $_POST['email'],PDO::PARAM_STR);
            $stmt->bindValue(':haslo', $_POST['haslo'],PDO::PARAM_STR);
            $stmt->bindValue(':telefon', $_POST['telefon'],PDO::PARAM_STR);
            $stmt->bindValue(':typ', $typ, PDO::PARAM_INT);
            $stmt->bindValue(':id_klasy', $id_klasy, PDO::PARAM_INT);
            $stmt->execute();//dodawanie do BD nowego uzytkownika
            header ('Location:'.$url);
            die();
        }//try
        catch(PDOException $e)
        {
            $TRESC='<p> Nie można dodać użytkownika do bazy. Inna osoba korzysta z adresu '.$_POST['email'].'</p>';
            $TRESC.='<a class="btn btn-info" href="'.$url.'">Powrót</a>';
        } //catch
        
    } //if...validate
    else
    {
        $TRESC.="<p>Błędy w formularzu!</p>";
        $TRESC.=$form->render();
    }//else...validate
} //if isset
else
{
   
        $TRESC.=$form->render();
   
   
    
}//else;
require_once 'szablony/witryna.php';