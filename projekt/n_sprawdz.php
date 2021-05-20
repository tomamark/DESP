<?php
/*
 * Skrypt obsługujący Sprawdziany w menu Nauczyciela
 */
require_once 'include/obsluga_sesji.php';
require_once 'include/settings.php';
require_once 'skrypty/menu.php';
require_once 'nibble-forms/NibbleForm.class.php';
require_once 'include/funkcje.php';
$LOKALIZACJA='Sprawdziany';
$TRESC='';

checkSession();
$uzytkownik='Nauczyciel';
checkUprawnienia($uzytkownik);//sprawdzenie czy uzytkownik ma poprawne uprawnienia

$id_nauczyciela=$_SESSION['user_id'];
$form=NibbleForm::getInstance('','Wybierz','post',true,'inline','table');

try {//nawiazywanie polaczenia z baza i odczyt danych
    $lista_przedmiotow=array();
    $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName", $DBUser, $DBPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $pdo->prepare('SELECT id_przedmiotuklasy, id_klasy, id_przedmiotu, klasa, przedmiot
                                FROM przedmioty_klasy NATURAL JOIN klasy NATURAL JOIN przedmioty
                                WHERE id_nauczyciela= :id_n');
    $stmt->bindValue(':id_n', $id_nauczyciela,PDO::PARAM_INT);
    $stmt->execute();
    $przedmiot=$stmt->fetch(PDO::FETCH_ASSOC);
    $lista_przedmiotow[$przedmiot['id_przedmiotuklasy']]=$przedmiot['klasa'].' - '.$przedmiot['przedmiot'];
    foreach ($stmt as $row){
        $lista_przedmiotow[$row['id_przedmiotuklasy']] = $row['klasa'].' - '.$row['przedmiot'];
    }
    $form->id_przedmiotu=new Select('Wybierz klasę i przedmiot',$lista_przedmiotow,1,true);
    $TRESC.=$form->render();
    if ((isset($_POST['id_przedmiotu'])) or(isset($_SESSION['id_przedmiotu'])) ){
        if (isset($_POST['id_przedmiotu'])){
            $id_przedmiotuklasy=$_POST['id_przedmiotu'];
            
        }
        else{
            $id_przedmiotuklasy=$_SESSION['id_przedmiotu'];
        }
        unset($_SESSION['id_przedmiotu']);
        $stmt = $pdo->prepare('SELECT id_klasy, klasa, przedmiot
                                FROM przedmioty_klasy NATURAL JOIN klasy NATURAL JOIN przedmioty
                                WHERE id_przedmiotuklasy= :id_pk');
        $stmt->bindValue(':id_pk', $id_przedmiotuklasy,PDO::PARAM_INT);
        $stmt->execute();
        $przedmiot=$stmt->fetch(PDO::FETCH_ASSOC);
        $TRESC.=genSprawdzianyPrzedmiotu ($pdo, $id_przedmiotuklasy,$przedmiot['id_klasy'],$przedmiot['klasa'],$przedmiot['przedmiot']);
    }
    else{
        $TRESC.=genSprawdzianyPrzedmiotu ($pdo, $przedmiot['id_przedmiotuklasy'],$przedmiot['id_klasy'],$przedmiot['klasa'],$przedmiot['przedmiot']);
    }
    $stmt->closeCursor();
}
catch (PDOException $e){//nie mozna polaczyc sie z BD
    $TRESC.="Błąd połączenia z bazą danych".$e;
}


require_once 'szablony/witryna.php';
?>

