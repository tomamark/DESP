<?php
/*
 * Skrypt dodający lekcję do planu lekcji
 */
require_once 'include/obsluga_sesji.php';
require_once 'include/settings.php';
require_once 'skrypty/menu.php';
require_once 'nibble-forms/NibbleForm.class.php';
require_once 'include/funkcje.php';
$LOKALIZACJA='Dodawanie lekcji do planu';
$uzytkownik='Administrator';
$TRESC='';
$TRESC.='<h2>Nowa lekcja</h2>';
checkSession();
checkUprawnienia($uzytkownik);//sprawdzenie czy uzytkownik ma poprawne uprawnienia
$url='a_plany.php';
$id_klasy=$_GET['id_k'];
$id_dnia=$_GET['id_d'];
$_SESSION['id_klasy']=$id_klasy;


$form=NibbleForm::getInstance('','Wybierz','post',true,'inline','table');


if (isset($_POST['submit']))//sprawdzanie czy nie nacisnieto klawisza Submit
{
   
   $id_godziny=$_POST['id_godziny'];
   if ($form->validate() AND (isset($_POST['id_przedmiotuklasy'])))
    {
        $id_przedmiotuklasy=$_POST['id_przedmiotuklasy'];
        try//Próba nawiązania połączenia z DB
        {
            
            $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName", $DBUser, $DBPass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $godzina_zajeta=FALSE;
            $stmt = $pdo->prepare('SELECT id_przedmiotuklasy 
                                    FROM przedmioty_klasy NATURAL JOIN plany_lekcji 
                                    WHERE id_dnia= :id_d AND id_godziny= :id_g AND id_klasy= :id_k');
            $stmt->bindValue(':id_d', $id_dnia,PDO::PARAM_INT);
            $stmt->bindValue(':id_g', $id_godziny,PDO::PARAM_INT);
            $stmt->bindValue(':id_k', $id_klasy,PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->rowCount()>0){//sprawdzanie czy dla wybranej godziny nie jest juz przypisana lekcja
                $godzina_zajeta=TRUE;
                $dane=$stmt->fetch();
                $id_obecne=$dane['id_przedmiotuklasy'];
            }
            
            if ($godzina_zajeta==TRUE){//jezeli juz byla przypisana lekcja to podmieniamy na nowa
                $stmt = $pdo->prepare('UPDATE plany_lekcji
                                    SET id_przedmiotuklasy= :id_pk
                                    WHERE id_dnia= :id_d AND id_godziny= :id_g AND id_przedmiotuklasy= :id_o');
                $stmt->bindValue(':id_d', $id_dnia,PDO::PARAM_INT);
                $stmt->bindValue(':id_g', $id_godziny,PDO::PARAM_INT);
                $stmt->bindValue(':id_pk', $id_przedmiotuklasy,PDO::PARAM_INT);
                $stmt->bindValue(':id_o', $id_obecne,PDO::PARAM_INT);
                $stmt->execute();
            }
            else{//jezeli nie bylo przypisanej lekcji to dopisujemy nowa lekcje
                $stmt = $pdo->prepare('INSERT INTO plany_lekcji (id_dnia, id_godziny, id_przedmiotuklasy)
                                           VALUES  (:id_d,
                                                   :id_g,
                                                   :id_pk)
                                        ');
                $stmt->bindValue(':id_g', $id_godziny,PDO::PARAM_INT);
                $stmt->bindValue(':id_pk', $id_przedmiotuklasy,PDO::PARAM_INT);
                $stmt->bindValue(':id_d', $id_dnia,PDO::PARAM_INT);
                $stmt->execute();
            }
            header ('Location:'.$url);
            die();
        }//try
        catch(PDOException $e)
        {
            $TRESC='<p> Nie można dodać przedmiotu. </p>'.$e;
            $TRESC.='<a class="btn btn-info" href="'.$url.'">Powrót</a>';
        } //catch
        
    } //if...validate
    else
    {
        $TRESC.='<p>Błędy w formularzu! Brak przedmiotu do dodania</p>';
        $TRESC.='<a class="btn btn-info" href="'.$url.'">Powrót</a>';
    }//else...validate
} //if isset
else
{//wyswietlanie aktualnego planu lekcji
    $lista_godzin=array();
    $lista_przedmiotowklasy=array();
    $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName", $DBUser, $DBPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $pdo->prepare('SELECT id_godziny, czas
                                FROM godziny');
    $stmt->execute();
    foreach ($stmt as $row){
        $lista_godzin[$row['id_godziny']] = $row['czas'];
    }
    $stmt = $pdo->prepare('SELECT id_przedmiotuklasy, przedmiot
                                FROM przedmioty_klasy NATURAL JOIN przedmioty
                                WHERE id_klasy= :id_k');
    $stmt->bindValue(':id_k', $id_klasy, PDO::PARAM_INT);
    $stmt->execute();
    foreach ($stmt as $row){
        $lista_przedmiotowklasy[$row['id_przedmiotuklasy']] = $row['przedmiot'];
    }
    $form->id_godziny=new Select('Wybierz godzinę:',$lista_godzin,1,true);
    $form->id_przedmiotuklasy=new Select('Wybierz przedmiot:',$lista_przedmiotowklasy,1,true);
    $TRESC.=$form->render();
    
    
}//else;
require_once 'szablony/witryna.php';
?>