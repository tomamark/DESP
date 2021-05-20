<?php
/*
 * Skrypt obsługujący dodawanie oceny
 */
require_once 'include/obsluga_sesji.php';
require_once 'include/settings.php';
require_once 'skrypty/menu.php';
require_once 'nibble-forms/NibbleForm.class.php';
require_once 'include/funkcje.php';
$LOKALIZACJA='Wpisywanie oceny';
$uzytkownik='Nauczyciel';
$TRESC='';
$TRESC.='<h2>Nowa ocena</h2>';
checkSession();
checkUprawnienia($uzytkownik);//sprawdzenie czy uzytkownik ma poprawne uprawnienia
$url='n_oceny.php';
$id_ucznia=$_GET['id_ucznia'];
$id_przedmiotuklasy=$_GET['id_pk'];
$data=date("Y-m-d");
$modyfikacja=FALSE;
if (isset($_GET['id_ou'])){
    $modyfikacja=TRUE;
    $id_ocenyucznia=$_GET['id_ou'];
    $TRESC='';
    $TRESC.='<h2>Zmień ocenę</h2>';
}
$form=NibbleForm::getInstance('','Wybierz','post',true,'inline','table');


if (isset($_POST['submit']))//sprawdzanie czy nacisnieto klawisz Submit
{
    $_SESSION['id_przedmiotu']=$id_przedmiotuklasy;
    if ($form->validate())
    {
        try//nawiazywanie polaczenia z baza i odczyt danych
        {
            
            $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName", $DBUser, $DBPass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            if ($modyfikacja==TRUE){
            $stmt = $pdo->prepare('UPDATE oceny_ucznia
                                    SET 
                                        symbol= :symbol,
                                        id_kategorii= :id_k
                                    WHERE id_oceny= :id_ou');
            $stmt->bindValue(':symbol', $_POST['symbol'],PDO::PARAM_STR);
            $stmt->bindValue(':id_k', $_POST['id_kategorii'],PDO::PARAM_INT);
            $stmt->bindValue(':id_ou', $id_ocenyucznia,PDO::PARAM_INT);
            $stmt->execute();//aktualizacja oceny ucznia
            }
            else{   
            $stmt = $pdo->prepare('INSERT INTO oceny_ucznia
                                       SET  id_ucznia= :id_u,
                                            id_przedmiotuklasy= :id_pk,
                                            symbol= :symbol,
                                            id_kategorii= :id_k,
                                            data= :data
                                            
                                        ');
            $stmt->bindValue(':id_u', $id_ucznia,PDO::PARAM_INT);
            $stmt->bindValue(':id_pk', $id_przedmiotuklasy,PDO::PARAM_INT);
            $stmt->bindValue(':symbol', $_POST['symbol'],PDO::PARAM_STR);
            $stmt->bindValue(':id_k', $_POST['id_kategorii'],PDO::PARAM_INT);
            $stmt->bindValue(':data', $data);
            $stmt->execute();//dopisywanie nowej oceny ucznia
            }
            header ('Location:'.$url);
            die();
        }//try
        catch(PDOException $e)//nie mozna polaczyc się z BD
        {
            $TRESC="<p> Nie można dodać oceny. </p>".$e;
            $TRESC.="<a class=button href=".$url.">Powrót</a>";
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
    $lista_ocen=array();
    $lista_kategorii=array();
    $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName", $DBUser, $DBPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if ($modyfikacja==TRUE){
        $stmt = $pdo->prepare('SELECT symbol
                            FROM oceny_ucznia
                            WHERE id_oceny= :id_ou');
        $stmt->bindValue(':id_ou', $id_ocenyucznia,PDO::PARAM_INT);
        $stmt->execute();
        $ocena=$stmt->fetch(PDO::FETCH_ASSOC);
        $lista_ocen[$ocena['symbol']]=$ocena['symbol'];
        $stmt = $pdo->prepare('SELECT id_kategorii, kategoria
                            FROM oceny_ucznia NATURAL JOIN kategorie_ocen
                            WHERE id_oceny= :id_ou');
        $stmt->bindValue(':id_ou', $id_ocenyucznia, PDO::PARAM_INT);
        $stmt->execute();
        $kategoria=$stmt->fetch(PDO::FETCH_ASSOC);
        $lista_kategorii[$kategoria['id_kategorii']]=$kategoria['kategoria'];
    }
    $stmt = $pdo->prepare('SELECT id_kategorii, kategoria
                                FROM kategorie_ocen');
    $stmt->execute();
    foreach ($stmt as $row){
        $lista_kategorii[$row['id_kategorii']] = $row['kategoria'];
    }
    $stmt = $pdo->prepare('SELECT symbol
                                FROM oceny');
    $stmt->execute();
    foreach ($stmt as $row){
            $lista_ocen[$row['symbol']] = $row['symbol'];
    }
    $form->symbol=new Select('Wybierz ocenę:',$lista_ocen,1,true);
    $form->id_kategorii=new Select('Wybierz kategorię:',$lista_kategorii,1,true);
    $TRESC.=$form->render();
    
    
}//else;
require_once 'szablony/witryna.php';
?>