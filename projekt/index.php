<?php
/*
 * Strona logowania
 */
require_once 'include/obsluga_sesji.php';
require_once 'include/settings.php';
require_once 'include/funkcje.php';
require_once 'skrypty/menu.php';
require_once 'nibble-forms/NibbleForm.class.php';
$LOKALIZACJA="Strona logowania";
$MENU=array(
    "index.php"=>"Logowanie",
);

//Deklaracja formularza logowania klasy NibbleForms
$form=NibbleForm::getInstance('','Zaloguj się','post',true,'inline');
$form->email=new Email('Podaj swój e-mail: ',true,60,'/[a-zA-Z0-9]+/');
$form->password=new Password('Podaj swoje hasło',6,false,true,255);

//Generacja treści
$TRESC="";
if (isset($_POST['submit']))//sprawdzanie czy nacisnieto klawisz Submit
    {
        if ($form->validate())
        {
            try//nawiazywanie polaczenia z baza i odczyt danych
            {
                $email=$_POST['email'];
                $pass=$_POST['password'];
                $pdo = new PDO("$DBEngine:host=$DBServer;dbname=$DBName", $DBUser, $DBPass);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	//Konfiguracja zgłaszania błędów poprzez wyjątki
                
                $stmt = $pdo->prepare('SELECT haslo, imie, typ, id_uzytkownika, id_klasy
                                       FROM uzytkownicy
                                       WHERE email=:email');
                        
                $stmt->bindValue(':email', $email, PDO::PARAM_STR);
                $stmt->execute();
					
                if ($stmt->rowCount()>1)throw new PDOException('Liczba użytkownikow o tym samym loginie większa od 1.');
                if ($stmt->rowCount()==0){//sprawdzanie czy brak uzytkownika
                    $TRESC.="Nieprawidłowy login";
                    $TRESC.=$form->render();
                }
                else {//jezeli jest uzytkownik to sprawdzanie jego hasla
                    $dane=$stmt->fetch(PDO::FETCH_ASSOC);
                    if ($dane['haslo']==$pass){
                       
                        $_SESSION['username']=$dane['imie'];
                        $_SESSION['user_id']=$dane['id_uzytkownika'];
                        $_SESSION['id_klasy']=$dane['id_klasy'];
                        $_SESSION['user_t']=$dane['typ'];
                        $_SESSION['usertype']='';
                        $_SESSION['usertype']=setTypUzytkownika($dane['typ']);
                        
                        $TRESC.=genEkranPowitalny ($_SESSION['username'],$_SESSION['usertype']);
                        $MENU=setMenu($_SESSION['usertype']);
                    }
                    else //złe hasło
                    {
                        $TRESC.="Podano błędne hasło";
                        $TRESC.=$form->render();
                    }
                }
                $stmt->closeCursor();
                
            }
            catch(PDOException $e)//brak połaczenia z baza
            {
                echo 'Połączenie nie mogło zostać utworzone: ' . $e->getMessage();
            } 
        
        }
        else
        {
            $TRESC.="Błędna nazwa użytkownika lub hasło!";
            $TRESC.=$form->render();
        }
    } 
else //nie nacisnieto klawisza Submit
    {
        $TRESC.=$form->render();
    }
    
//Wygenerowanie witryny z szablonu    
require_once 'szablony/witryna.php';
?>
