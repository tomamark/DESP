<?php
/*
 * Skrypt obsługujący wpisywanie usprawiedliwienia
 */
require_once 'include/obsluga_sesji.php';
require_once 'include/settings.php';
require_once 'include/funkcje.php';
require_once 'skrypty/menu.php';
require_once 'nibble-forms/NibbleForm.class.php';
$LOKALIZACJA="Wypisywanie usprawiedliwienia";
$TRESC="";
$TRESC.='<h2>Usprawiediwienie</h2>';

checkSession();
checkUprawnienia("Rodzic");//sprawdzenie czy uzytkownik ma poprawne uprawnienia

$id_nieobecnosci=$_GET['id_nb'];
$id_rodzica=$_GET['id_r'];
$url='r_nieob.php';
$form=NibbleForm::getInstance('','Zatwierdź','post',true,'inline','table');
$form->usprawiedliwienie = new Text('Wpisz usprawiediwienie', true, 255, '/[a-zA-Z0-9]+/');

if (isset($_POST['submit']))
{
    if ($form->validate())
    {
        try
        {//nawiazywanie polaczenia z baza i odczyt danych
            
            $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName", $DBUser, $DBPass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $pdo->prepare('UPDATE nieobecnosci
                                       SET  usprawiedliwiona=1,
                                            id_rodzica= :id_r,
                                            usprawiedliwienie= :uspr
                                        WHERE id_nieobecnosci= :id_nb
                                        ');
            $stmt->bindValue(':uspr', $_POST['usprawiedliwienie'],PDO::PARAM_STR);
            $stmt->bindValue(':id_r', $id_rodzica,PDO::PARAM_INT);
            $stmt->bindValue(':id_nb', $id_nieobecnosci,PDO::PARAM_INT);
            $stmt->execute();//aktualizacja nieusprawiedliwionej nieobecnosci na usprawiedliwiona
            header ('Location:'.$url);
            die();
        }//try
        catch(PDOException $e)//nie mozna polaczyc sie z BD
        {
            $TRESC="<p> Nie można zmienić danych</p>".$e.$_SESSION['HTTP_REFER'].$url;
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
    
    $TRESC.=$form->render();
}//else;
require_once 'szablony/witryna.php';
?>