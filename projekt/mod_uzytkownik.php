<?php
/*
 * Skrypt obsługujący modyfikację danych użytkownika
 */
require_once 'include/obsluga_sesji.php';
require_once 'include/settings.php';
require_once 'include/funkcje.php';
require_once 'skrypty/menu.php';
require_once 'nibble-forms/NibbleForm.class.php';
$LOKALIZACJA="Modyfikacja użytkownika";
$TRESC="";
$TRESC.='<h2> Edycja: '.$UZYTKOWNIK[$_GET['typ']].'</h2>';

checkSession();
checkUprawnienia("Administrator");//sprawdzenie czy uzytkownik ma poprawne uprawnienia

$typ=$_GET['typ'];
$url=setAdminUrl($typ);

if (isset($_GET['id'])){
    $id=$_GET['id'];
}

$form=setNowyUzytkownikForm();

if (isset($_POST['submit']))//sprawdzanie czy nacisnieto przycisk Submit
{
    if ($form->validate())
    {
        try
        {//nawiazywanie polaczenia z baza i odczyt danych
            
            $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName", $DBUser, $DBPass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $pdo->prepare('UPDATE uzytkownicy
                                       SET  imie= :imie,
                                            nazwisko= :nazwisko,
                                            telefon= :telefon,
                                            email= :email,
                                            haslo= :haslo
                                        WHERE id_uzytkownika= :id
                                        ');
            $stmt->bindValue(':nazwisko', $_POST['nazwisko'],PDO::PARAM_STR);
            $stmt->bindValue(':imie', $_POST['imie'],PDO::PARAM_STR);
            $stmt->bindValue(':email', $_POST['email'],PDO::PARAM_STR);
            $stmt->bindValue(':haslo', $_POST['haslo'],PDO::PARAM_STR);
            $stmt->bindValue(':telefon', $_POST['telefon'],PDO::PARAM_STR);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT); 
            $stmt->execute();//aktualizacja danych uzytkownika
            header ('Location:'.$url);
            die();
        }//try
        catch(PDOException $e)//nie mozna polaczyc się z BD
        {
            $TRESC="<p> Nie można zmienić danych użytkownika. Inna osoba korzysta z adresu ".$_POST['email']."</p>";
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
{//wyswietlanie danych uzytkownika
    try {//nawiazywanie polaczenia z baza i odczyt danyc
        $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName", $DBUser, $DBPass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $uzytkownik=getUzytkownikInfo($pdo, $_GET['id']);
        $form->addData(array(
            'imie'=>$uzytkownik['imie'],
            'nazwisko'=>$uzytkownik['nazwisko'],
            'email'=>$uzytkownik['email'],
            'telefon'=>$uzytkownik['telefon'],
            'haslo'=>$uzytkownik['haslo']
        ));
        $TRESC.=$form->render();
    }
    catch (PDOException $e){//nie mozna polaczyc sie z BD
        $TRESC.="Błąd ".$e;
    }
    
}//else;
require_once 'szablony/witryna.php';
?>