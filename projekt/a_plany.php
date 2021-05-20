<?php
/*
 * Skrypt obsługujący zarządzanie planami lekcji
 */
require_once 'include/obsluga_sesji.php';
require_once 'include/settings.php';
require_once 'skrypty/menu.php';
require_once 'nibble-forms/NibbleForm.class.php';
require_once 'include/funkcje.php';
$LOKALIZACJA='Plany lekcji';
$TRESC='';

checkSession();
$uzytkownik='Administrator';
checkUprawnienia($uzytkownik);//sprawdzenie czy uzytkownik ma poprawne uprawnienia
$powrot="a_plany.php";
$form=NibbleForm::getInstance('','Wybierz','post',true,'inline','table');

try {//nawiazywanie polaczenia z baza i odczyt danych
    $lista_klas=array();
    $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName", $DBUser, $DBPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $pdo->prepare('SELECT id_klasy, klasa
                                FROM klasy');
    $stmt->execute();
    $klasa=$stmt->fetch(PDO::FETCH_ASSOC);
    $lista_klas[$klasa['id_klasy']]=$klasa['klasa'];
    foreach ($stmt as $row){
        $lista_klas[$row['id_klasy']] = $row['klasa'];//wyswietlanie listy wszystkich klas
    }
    $form->id_klasy=new Select('Wybierz klasę',$lista_klas,1,true);
    $TRESC.=$form->render();
    if ((isset($_POST['id_klasy'])) or(isset($_SESSION['id_klasy'])) ){
        if (isset($_POST['id_klasy'])){
            $id_klasy=$_POST['id_klasy'];
            
        }
        else{
            $id_klasy=$_SESSION['id_klasy'];
        }
        unset($_SESSION['id_klasy']);
        $stmt = $pdo->prepare('SELECT  klasa
                              FROM  klasy
                               WHERE id_klasy= :id_k');
        $stmt->bindValue(':id_k', $id_klasy,PDO::PARAM_INT);
        $stmt->execute();
        $klasa=$stmt->fetch(PDO::FETCH_ASSOC);
        $TRESC.=genPlanyKlasy($pdo, $id_klasy, $klasa['klasa'], $powrot);//dla wybranej klasy wyswietlanie planu
    }
    else{
        $TRESC.=genPlanyKlasy ($pdo,$klasa['id_klasy'],$klasa['klasa'], $powrot);
    }
    $stmt->closeCursor();
}
catch (PDOException $e){
    $TRESC.="Błąd połączenia z bazą danych".$e;
}

require_once 'szablony/witryna.php';
?>