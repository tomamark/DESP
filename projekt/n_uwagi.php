<?php
/*
 * Skrypt obsługujący Uwagi w menu Nauczyciela
 */
require_once 'include/obsluga_sesji.php';
require_once 'include/settings.php';
require_once 'skrypty/menu.php';
require_once 'nibble-forms/NibbleForm.class.php';
require_once 'include/funkcje.php';
$LOKALIZACJA='Uwagi';
$TRESC='';

checkSession();
$uzytkownik='Nauczyciel';
checkUprawnienia($uzytkownik);//sprawdzenie czy uzytkownik ma poprawne uprawnienia
$id_nauczyciela=$_SESSION['user_id'];
$uzytkownik_typ=$_SESSION['user_t'];
$powrot="n_uwagi.php";
$form=NibbleForm::getInstance('','Wybierz','post',true,'inline','table');

try {//nawiazywanie polaczenia z baza i odczyt danych
    $lista_klas=array();
    $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName", $DBUser, $DBPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $pdo->prepare('SELECT id_klasy, klasa
                                FROM przedmioty_klasy NATURAL JOIN klasy
                                WHERE id_nauczyciela= :id_n');
    $stmt->bindValue(':id_n', $id_nauczyciela,PDO::PARAM_INT);
    $stmt->execute();
    $klasa=$stmt->fetch(PDO::FETCH_ASSOC);
    $lista_klas[$klasa['id_klasy']]=$klasa['klasa'];
    foreach ($stmt as $row){
        $lista_klas[$row['id_klasy']] = $row['klasa'];
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
        $TRESC.=genUwagiKlasy($pdo, $id_klasy, $klasa['klasa'], $id_nauczyciela, $powrot);
    }
    else{
        $TRESC.=genUwagiKlasy ($pdo,$klasa['id_klasy'],$klasa['klasa'], $id_nauczyciela, $powrot);
    }
    $stmt->closeCursor();
}
catch (PDOException $e){//nie mozna polaczyc sie z BD
    $TRESC.="Błąd połączenia z bazą danych".$e;
}

require_once 'szablony/witryna.php';
?>