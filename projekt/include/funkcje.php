<?php
require_once 'nibble-forms/NibbleForm.class.php';
/*
 *   Funkcja sprawdzająca kto jest zalogowany 
 */
function checkSession(){
    if (!isset($_SESSION['username']))
    {
        header ('Location: http:index.php');
    }
}
/*
 *   Funkcja sprawdzająca rodzaj uprawnień zalogowanego użytkownika
 */
function checkUprawnienia($uzytkownik){
    if ($uzytkownik!=$_SESSION['usertype']){
        header ('Location: http:index.php');
    }
}
/*
 *   Funkcja kto jest zalogowany
 */
function checkAutor($id_nadawcy){
    if ($id_nadawcy!=$_SESSION['user_id']){
        header ('Location: http:index.php');
    }
}
/*
 *   Funkcja sprawdzająca typ użytkownika
 */
function setTypUzytkownika($typ_int){
    $typ='';
    switch ($typ_int){
        case 0: $typ="Administrator"; break;
        case 1: $typ="Nauczyciel"; break;
        case 3: $typ="Rodzic"; break;
        case 2: $typ="Uczeń"; break;
    }
    return $typ;
}
/*
 *   Funkcja przyporządkowująca menu w zależnosci od użytkownika
 */
function setMenu ($usertype){
$menu=array(
        "index.php"=>"Logowanie",
);
switch ($usertype){
    case 'Administrator': 
        $menu=array(
        "a_uczniowie.php"=>"Klasy i uczniowie",
        "a_rodzice.php"=>"Rodzice",
        "a_rodz_ucz.php"=>"Rodzice->Uczniowie",
        "a_nauczyciele.php"=>"Nauczyciele",
        "a_przklas.php"=>"Przedmioty klas",
        "a_plany.php"=>"Plany lekcji",
        "a_admin.php"=>"Administratorzy",
        "logout.php"=>"Wyloguj");
    break;
    case 'Nauczyciel':
        $menu=array(
        "n_oceny.php"=>"Oceny",
        "n_sprawdz.php"=>"Sprawdziany",
        "n_nieob.php"=>"Nieobecności",
        "n_uwagi.php"=>"Uwagi",
        "n_wiad.php"=>"Wyslij wiadomość",
        "o_wiad.php"=>"Wiadomości odebrane",
        "logout.php"=>"Wyloguj");
        break;
    case 'Uczeń':
        $menu=array(
        "u_oceny.php"=>"Moje oceny",
        "u_plan.php"=>"Plan lekcji",
        "u_sprawdz.php"=>"Sprawdziany",
        "logout.php"=>"Wyloguj");
        break;
    case 'Rodzic':
        $menu=array(
        "r_oceny.php"=>"Oceny",
        "r_uwagi.php"=>"Uwagi",
        "r_nieob.php"=>"Nieobecności",
        "r_sprawdz.php"=>"Sprawdziany",
        "o_wiad.php"=>"Wiadomości",
        "logout.php"=>"Wyloguj");
        break;
    }
return $menu;
}

function Powrot($url){
    $powrot='';
    $powrot = 'http://' . $_SERVER['HTTP_HOST'];
    $powrot .= rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $powrot .= '/'.$url; 
}

/*
 *   Funkcja generująca listę klas
 *   param: $pdo - obiekt DB
 *   wynik: $returnedHTML - linki do klas
 *   */
function genListaKlasHTML ($pdo)
{
    $returnedHTML='';
    $stmt = $pdo->prepare('SELECT id_klasy, klasa
                                       FROM klasy ORDER BY klasa'
        );
    $stmt->execute();
    foreach ($stmt as $row){
        $returnedHTML.='<a class="btn btn-info" href=#'.$row['id_klasy'].'>'.$row['klasa'].'</a>'.PHP_EOL;
    }
    $stmt->closeCursor();
    return $returnedHTML;
}
/*
 *   Funkcja generująca listę uczniów dla danej klasy $id_klasy
 */
function  genTabelaUczniowie ($pdo,$id_klasy){
    $returnedHTML='';
    $returnedHTML.='<a class="btn btn-info" href="dodaj_uzytkownika.php?typ=2&amp;id_klasy='.$id_klasy.'">Dodaj ucznia do klasy</a>';
    $stmt = $pdo->prepare('SELECT id_uzytkownika, nazwisko, imie
                                       FROM uzytkownicy
                                    WHERE id_klasy=:id_klasy ORDER BY nazwisko'
        );
    $stmt->bindValue(':id_klasy', $id_klasy, PDO::PARAM_STR);
    $stmt->execute();
    $returnedHTML.='<table class="responsive"><tr><th> Lp. </th><th> Nazwisko </th><th> Imię </th><th> Zmień </th><th> Usuń </th></tr>';
    $i=1;
    foreach ($stmt as $row){
        $returnedHTML.='<tr><td>'.$i.'</td><td>'.$row['nazwisko'].'</td><td>'.$row['imie'].'</td><td><a class="btn btn-info" href="mod_uzytkownik.php?id='
            .$row['id_uzytkownika'].'&amp;typ=2">Zmień</a></td><td><a class="btn btn-danger" href="usun_uzytkownik.php?id='.$row['id_uzytkownika'].'&amp;url=a_uczniowie.php" onclick="return confirm(\'Czy na pewno chcesz usunąć ?\')">Usuń</a></td></tr>';
            $i++;
    }
    $returnedHTML.='</table>';
    $returnedHTML.='<a class="btn btn-danger" href="usun_klase.php?id='.$id_klasy.'
                    &amp;url=a_uczniowie.php" onclick="return confirm(\'Usunięcie klasy spowoduje też usunięcie uczniów!!! Czy na pewno chcesz usunąć ?\')">Usuń klasę</a>';
    $returnedHTML.='<br/><br/>';
    $stmt->closeCursor();
    return $returnedHTML;
}
/*
 *   Funkcja dodająca nowego użytkownika
 *   $typ - rodzaj użytkownika
 */
function  genTabelaUzytkownikow ($pdo,$typ,$url){
    $returnedHTML='';
    $returnedHTML.='<a class="btn btn-info" href="dodaj_uzytkownika.php?typ='.$typ.'">Dodaj użytkownika</a>';
    $stmt = $pdo->prepare('SELECT id_uzytkownika, nazwisko, imie
                                       FROM uzytkownicy
                                       WHERE typ=:typ 
                                       ORDER BY nazwisko'
        );
    $stmt->bindValue(':typ', $typ, PDO::PARAM_STR);
    $stmt->execute();
    $returnedHTML.='<table class="responsive"><tr><th> Lp. </th><th> Nazwisko </th><th> Imię </th><th> Zmień </th><th> Usuń </th></tr>';
    $i=1;
    foreach ($stmt as $row){
        $returnedHTML.='<tr><td>'.$i.'</td><td>'.$row['nazwisko'].'</td><td>'.$row['imie'].'</td><td><a class="btn btn-info" href="mod_uzytkownik.php?id='
            .$row['id_uzytkownika'].'&amp;typ='.$typ.'">Zmień</a></td><td><a class="btn btn-danger" href="usun_uzytkownik.php?id='.$row['id_uzytkownika'].'&amp;url='.$url.'" onclick="return confirm(\'Czy na pewno chcesz usunąć ?\')">Usuń</a></td></tr>';
            $i++;
    }
    $returnedHTML.='</table>';
    $stmt->closeCursor();
    return $returnedHTML;
}
/*
 *   Funkcja generująca listę uczniów dla wszystkich klas
 */
function genTabelaUczniowieWszyscy ($pdo){
    $returnedHTML='';
    $stmt = $pdo->prepare('SELECT id_klasy, klasa
                                       FROM klasy'
        );
    $stmt->execute();
    
    foreach ($stmt as $row)
    {
        $returnedHTML.='<p id="'.$row['id_klasy'].'">Klasa '.$row['klasa'].'</p><br/>';
        $returnedHTML.=genTabelaUczniowie($pdo, $row['id_klasy']);
        $returnedHTML.='<br/>';
    }
    return $returnedHTML;
}
/*
 *   Funkcja generująca informację o danym użytkowniku $id_uzytkownika
 */
function getUzytkownikInfo ($pdo,$id_uzytkownika){
    $dane='';
    $stmt = $pdo->prepare('SELECT imie, nazwisko, typ, telefon,email, haslo, id_klasy
                                       FROM uzytkownicy WHERE id_uzytkownika=:id;'
        );
    $stmt->bindValue(':id',$id_uzytkownika,PDO::PARAM_INT);
    $stmt->execute();
    $dane=$stmt->fetch();
    return $dane;
}
/*
 *   Funkcja generująca info o użytkowniku $id_uzytkownika
 */
function genUzytkownikHTML($pdo, $id_uzytkownika){
    $returnedHTML='';
    $dane=getUzytkownikInfo($pdo, $id_uzytkownika);
    $typ=setTypUzytkownika($dane['typ']);
    $returnedHTML.='<table class="info">';
    $returnedHTML.='<tr><td>Funkcja</td><td>'.$typ.'</td></tr>';
    $returnedHTML.='<tr><td>Nazwisko</td><td>'.$dane['nazwisko'].'</td></tr>';
    $returnedHTML.='<tr><td>Imie</td><td>'.$dane['imie'].'</td></tr>';
    $returnedHTML.='<tr><td>Email</td><td>'.$dane['email'].'</td></tr>';
    $returnedHTML.='<tr><td>Telefon</td><td>'.$dane['telefon'].'</td></tr>';
    $returnedHTML.='</table>';
    return $returnedHTML;
}
/*
 *   Funkcja formularza generująca nowego użytkownika 
 */
function setNowyUzytkownikForm(){
    $form=NibbleForm::getInstance('','Zatwierdź','post',true,'inline','table');
    $form->nazwisko=new Text('Nazwisko: ',true,30,'/[a-zA-Z0-9]+/');
    $form->imie=new Text('Imie: ',true,30,'/[a-zA-Z0-9]+/');
    $form->email=new Email('E-mail: ',true,60,'/[a-zA-Z0-9]+/');
    $form->telefon=new Text('Telefon: ',false,12,'/[0-9]/');
    $form->haslo=new Password('Hasło: (min. 6 znaków)',6,false,true,255);
    return $form;
}
/*
 *   Funkcja formularza dla nowej wiadomosci
 */
function setNowaWiadForm($nadawca, $odbiorca){
    $form='';
    $form=<<<EOD
    <form method="post">
  <div class="form-group row">
    <label for="nadawca"  class="col-form-label">Od: </label>
   
      <input type="text" readonly class="form-control-plaintext" id="nadawca" value="$nadawca">
    
  </div>
  <div class="form-group row">
    <label for="odbiorca" class="col-form-label">Do: </label>
    
      <input type="text" readonly class="form-control-plaintext" id="odbiorca" value="$odbiorca">
    
  </div>
  <div class="form-group">
    <label for="wiadomosc">Treść wiadomości</label>
    <textarea class="form-control" draggable="false" id="wiadomosc" rows="5" name="wiadomosc"></textarea>
  </div>
  <input type="submit" class="btn btn-primary" name="submit" value="Wyślij">
</form>
EOD;
    return $form;
}
/*
 *   Funkcja formularza dla nowej uwagi dla ucznia $uczen
 */
function setNowaUwagaForm($uczen){
    $form='';
    $form=<<<EOD
   <form class="form-horizontal" method="post">
<fieldset>

<!-- Form Name -->
<legend>Nowa uwaga</legend>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="textinput">Uczeń</label>  
  <div class="col-md-6">
  <input id="textinput" readonly name="textinput" type="text" placeholder="$uczen" class="form-control input-md">
    
  </div>
</div>

<!-- Multiple Radios -->
<div class="form-group">
  <label class="col-md-4 control-label" for="radios">Typ uwagi</label>
  <div class="col-md-4">
  <div class="radio">
    <label for="radios-0">
      <input type="radio" name="typ" id="radios-0" value="0" checked="checked">
      Pozytywna
    </label>
	</div>
  <div class="radio">
    <label for="radios-1">
      <input type="radio" name="typ" id="radios-1" value="1">
      Negatywna
    </label>
	</div>
  </div>
</div>

<!-- Textarea -->
<div class="form-group">
  <label class="col-md-4 control-label" for="uwaga">Treść</label>
  <div class="col-md-4">                     
    <textarea class="form-control" id="uwaga" name="uwaga"></textarea>
  </div>
</div>

<!-- Button -->
<div class="form-group">
  <label class="col-md-4 control-label" for="submit"></label>
  <div class="col-md-4">
    <button id="submit" name="submit" class="btn btn-primary">Wpisz</button>
  </div>
</div>

</fieldset>
</form>
EOD;
    return $form;
}

function setAdminUrl ($typ){
    $url='index.php';
    switch ($typ){
        case "0": $url='a_admin.php'; break;
        case "1": $url='a_nauczyciele.php'; break;
        case "2": $url='a_uczniowie.php'; break;
        case "3": $url='a_rodzice.php'; break;
    }
    return $url;
}
/*
 *   Funkcja przypisująca dziecko do rodzica
 */
function genRodzicUczenHTML($pdo){
    $returnedHTML='';
    $stmt = $pdo->prepare('SELECT id_uzytkownika, nazwisko, imie
                                       FROM uzytkownicy
                                        WHERE typ=3
                                        ORDER BY nazwisko'
        );
    $stmt->execute();
    
    foreach ($stmt as $row)
    {
        $returnedHTML.='<div class="info">';
        $returnedHTML.='<p id="'.$row['id_uzytkownika'].'">Rodzic: '.$row['nazwisko'].' '.$row['imie'].'</p><br/>';
        $returnedHTML.=genTabelaDzieci($pdo, $row['id_uzytkownika']);
        $returnedHTML.='<a class="btn btn-info" href="dodaj_rd.php?id_r='.$row['id_uzytkownika'].
        '&amp;nazwisko_r='.$row['nazwisko'].'&amp;imie_r='.$row['imie'].'">Dodaj dziecko</a>';
        $returnedHTML.='</div><br/>';
    }
    $stmt->closeCursor();
    return $returnedHTML;
}
/*
 *   Funkcja wyswietlająca listę dzieci dla danego rodzica $id_rodzica
 */
function genTabelaDzieci ($pdo,$id_rodzica){
    $returnedHTML='';
    $stmt = $pdo->prepare('SELECT id_uzytkownika, nazwisko, imie 
                            FROM uzytkownicy 
                            WHERE id_uzytkownika IN 
                            (SELECT id_ucznia 
                            FROM rodzice_uczniowie 
                            WHERE id_rodzica= :id_rodzica)'
        );
    $stmt->bindValue(':id_rodzica',$id_rodzica,PDO::PARAM_INT);
    $stmt->execute();
    $returnedHTML.='<table class="responsive">';
    foreach ($stmt as $row)
    {
        $returnedHTML.='<tr><td>'.$row['nazwisko'].'</td><td>'.$row['imie'].
        '</td><td><a class="btn btn-danger" href="usun_rd.php?id_r='.$id_rodzica.'&amp;id_d='.$row['id_uzytkownika'].
        '" onclick="return confirm(\'Czy na pewno chcesz usunąć ?\')">Usuń połączenie</a></td></tr>';
    }
    $returnedHTML.='</table>';
    return $returnedHTML;
}
/*
 *   Funkcja dodająca nowe przedmioty dla danej klasy $id_klasy
 */
function  genTabelaPrzedmioty ($pdo,$id_klasy){
    $returnedHTML='';
    $returnedHTML.='<a class="btn btn-info" href="dodaj_przedmiot.php?id_klasy='.$id_klasy.'">Dodaj przedmiot do klasy</a>';
    $stmt = $pdo->prepare('SELECT id_przedmiotuklasy, id_przedmiotu, id_nauczyciela
                                       FROM przedmioty_klasy
                                    WHERE id_klasy=:id_klasy'
        );
    $stmt->bindValue(':id_klasy', $id_klasy, PDO::PARAM_INT);
    $stmt->execute();
    $returnedHTML.='<table class="responsive"><tr><th> Lp. </th><th> Przedmiot </th><th> Nauczyciel </th><th> Zmień </th><th> Usuń </th></tr>';
    $i=1;
    foreach ($stmt as $row){
        $stmt2 = $pdo->prepare('SELECT przedmiot
                                       FROM przedmioty
                                    WHERE id_przedmiotu=:id_p'
            );
        $stmt2->bindValue(':id_p', $row['id_przedmiotu'], PDO::PARAM_INT);
        $stmt2->execute();
        $przedmiot='';
        $dane=$stmt2->fetch(PDO::FETCH_ASSOC);
        $przedmiot=$dane['przedmiot'];
        $nazwisko='';
        $imie='';
        if ($row['id_nauczyciela']!=NULL){
            $stmt3 = $pdo->prepare('SELECT nazwisko, imie
                                       FROM uzytkownicy
                                    WHERE id_uzytkownika=:id_n'
                );
            $stmt3->bindValue(':id_n', $row['id_nauczyciela'], PDO::PARAM_INT);
            $stmt3->execute();
            $dane=$stmt3->fetch(PDO::FETCH_ASSOC);
            $nazwisko=$dane['nazwisko'];
            $imie=$dane['imie'];
        }
        $returnedHTML.='<tr><td>'.$i.'</td><td>'.$przedmiot.'</td><td>'.$nazwisko.' '.$imie.'</td><td>
            <a class="btn btn-info" href="dodaj_przedmiot.php?id_przedmiotuklasy='.$row['id_przedmiotuklasy'].
            '&amp;id_przedmiotu='.$row['id_przedmiotu'].
            '&amp;id_nauczyciela='.$row['id_nauczyciela'].
            '&amp;id_klasy='.$id_klasy.'">Zmień</a></td><td>
            <a class="btn btn-danger" href="usun_przedmiot.php?id='.$row['id_przedmiotuklasy'].
            '" onclick="return confirm(\'Czy na pewno chcesz usunąć ?\')">Usuń</a></td></tr>';
            $i++;
    }
    $returnedHTML.='</table>';
    $returnedHTML.='<br/><br/>';
    $stmt->closeCursor();
    return $returnedHTML;
}
/*
 *   Funkcja generująca wszystkie przedmioty dla poszczególnych klas
 */
function genTabelaPrzedmiotyWszystkie ($pdo){
    $returnedHTML='';
    $stmt = $pdo->prepare('SELECT id_klasy, klasa
                                       FROM klasy'
        );
    $stmt->execute();
    foreach ($stmt as $row)
    {
        $returnedHTML.='<p id="'.$row['id_klasy'].'">Klasa '.$row['klasa'].'</p><br/>';
        $returnedHTML.=genTabelaPrzedmioty($pdo, $row['id_klasy']);
        $returnedHTML.='<br/>';
    }
    return $returnedHTML;
}
/*
 *   Funkcja generująca listę przedmiotów dla danej klasy $id_klasy
 */
function genListaPrzedmiotowKlasyHTML($pdo,$id_klasy)
{
    $returnedHTML='';
    $stmt = $pdo->prepare('SELECT id_przedmiotuklasy, przedmiot
                                       FROM  przedmioty_klasy NATURAL JOIN przedmioty
                                        WHERE id_klasy= :id_k
                                        ORDER BY przedmiot'
                            );
    $stmt->bindValue (':id_k', $id_klasy, PDO::PARAM_INT);
    $stmt->execute();
    foreach ($stmt as $row){
        $returnedHTML.='<a class="btn btn-info" href=#'.$row['id_przedmiotuklasy'].'>'.$row['przedmiot'].'</a>'.PHP_EOL;
    }
    $stmt->closeCursor();
    return $returnedHTML;
}
/*
 *   Funkcja generująca wszystkich ocen dla danej klasy $id_klasy i ucznia $id_ucznia
 */
function genListaOcenWszystkie ($pdo, $id_klasy, $id_ucznia, $typ_uzytkownika){
    $returnedHTML='';
    $powrot="r_oceny.php";
    $id_nadawcy=$_SESSION['user_id'];
    $stmt = $pdo->prepare('SELECT id_przedmiotuklasy, id_nauczyciela, przedmiot
                                       FROM  przedmioty_klasy NATURAL JOIN przedmioty
                                        WHERE id_klasy= :id_k
                                        ORDER BY przedmiot'
        );
    $stmt->bindValue (':id_k', $id_klasy, PDO::PARAM_INT);
    $stmt->execute();
    
    foreach ($stmt as $row)
    {
        $returnedHTML.='<p id="'.$row['id_przedmiotuklasy'].'">'.$row['przedmiot'].'</p><br/>';
        if ($typ_uzytkownika=='Rodzic'){
            $returnedHTML.=genKontakt ($pdo, $row['id_nauczyciela'],$id_nadawcy,3,$powrot);
        }
        $returnedHTML.=genTabelaOceny($pdo, $row['id_przedmiotuklasy'], $id_ucznia);
        $returnedHTML.='<br/>';
    }
    $stmt->closeCursor();
    return $returnedHTML;
}
/*
 *   Funkcja generująca listę ocen z przedmiotu $id_przedmiotuklasy dla ucznia $id_ucznia
 */
function genTabelaOceny ($pdo, $id_przedmiotuklasy, $id_ucznia){
    $returnedHTML='';
    $srednia_klasy=0;
    $srednia_ucznia=0;
    $stmt = $pdo->prepare('SELECT wartosc
                                       FROM  oceny_ucznia NATURAL JOIN oceny
                                        WHERE id_przedmiotuklasy= :id_pk'
                                        
        );
    $stmt->bindValue (':id_pk', $id_przedmiotuklasy, PDO::PARAM_INT);
    $stmt->execute();
    $srednia_klasy=sredniaOcen ($stmt);
    $stmt = $pdo->prepare('SELECT symbol, wartosc, kategoria, data
                                       FROM  oceny_ucznia NATURAL JOIN oceny NATURAL JOIN kategorie_ocen
                                        WHERE id_przedmiotuklasy= :id_pk AND id_ucznia= :id_u 
                                        ORDER BY data DESC'
        
        );
    $stmt->bindValue (':id_pk', $id_przedmiotuklasy, PDO::PARAM_INT);
    $stmt->bindValue (':id_u', $id_ucznia, PDO::PARAM_INT);
    $stmt->execute();
    $returnedHTML.='<table class="responsive">';
    $returnedHTML.='<tr><th>Data</th><th>Ocena</th><th>Kategoria</th></tr>';
    foreach ($stmt as $row){
           
        $returnedHTML.='<tr><td>'.$row['data'].'</td><td>'.$row['symbol'].'</td><td>'.$row['kategoria'].'</td></tr>';
    }
       
    
    $returnedHTML.='</table>';
    $stmt->execute();
    $srednia_ucznia=sredniaOcen ($stmt);
    $s_srednia_klasy=number_format($srednia_klasy,2,",",".");
    $s_srednia_ucznia=number_format($srednia_ucznia,2,",",".");
    $returnedHTML.='<p>Średnia ocen: '.$s_srednia_ucznia.' Średnia ocen klasy: '.$s_srednia_klasy.'</p>';
    return $returnedHTML;
}
/*
 *   Funkcja wyliczająca srednią ocen
 */
function sredniaOcen ($stmt){
    $wynik=0;
    $suma=0;
    $i=0;
    foreach ($stmt as $row){
        $suma+=$row['wartosc'];
        $i++;
    }
    if ($i>0){
        $wynik=$suma/$i;
    }
    return $wynik;
}
function getListaDni ($pdo){
    $lista_dni=array();
    $stmt = $pdo->prepare('SELECT id_dnia, dzien
                                       FROM  dni
                                        ORDER BY id_dnia'
        );
    $stmt->execute();
    $lista_dni=$stmt->fetchAll();
    $stmt->closeCursor();
    return $lista_dni;
}
/*
 *   Funkcja generująca listę dni tygodnia
 */
function genListaDniHTML($pdo){
    $returnedHTML='';
    $lista=getListaDni($pdo);
    foreach ($lista as $row){
        $returnedHTML.='<a class="btn btn-info" href=#'.$row['id_dnia'].'>'.$row['dzien'].'</a>'.PHP_EOL;
    }
    
    return $returnedHTML;
}
/*
 *   Funkcja generująca plan lekcji dla danej klasy $id_klasy
 */
function genListaPlanowWszystkie($pdo,$id_klasy){
    $returnedHTML='';
    $lista=getListaDni($pdo);
    foreach ($lista as $row)
    {
        $returnedHTML.='<p id="'.$row['id_dnia'].'">'.$row['dzien'].'</p><br/>';
        $returnedHTML.=genTabelaDniaKlasy($pdo, $row['id_dnia'], $id_klasy);
        $returnedHTML.='<br/>';
    }
    
    return $returnedHTML;
}
/*
 *   Funkcja generująca plan lekcji dla danej klasy $id_klasy w danym dniu $id_dnia
 */
function genTabelaDniaKlasy ($pdo,$id_dnia,$id_klasy){
    $returnedHTML='';
    $stmt = $pdo->prepare ('SELECT czas,przedmiot, id_godziny
                            FROM plany_lekcji
                            NATURAL JOIN godziny 
                            NATURAL JOIN przedmioty_klasy 
                            NATURAL JOIN przedmioty 
                            WHERE id_dnia = :id_d AND id_klasy = :id_k
                            ORDER BY id_godziny');
    $stmt->bindValue (':id_d', $id_dnia, PDO::PARAM_INT);
    $stmt->bindValue (':id_k', $id_klasy, PDO::PARAM_INT);
    $stmt->execute();
    $returnedHTML.='<table class="responsive">';
    $returnedHTML.='<tr><th>Nr lekcji</th><th>Godzina</th><th>Przedmiot</th></tr>';
    foreach ($stmt as $row){
        
        $returnedHTML.='<tr><td>'.$row['id_godziny'].'</td><td>'.$row['czas'].'</td><td>'.$row['przedmiot'].'</td></tr>';
    }
    $returnedHTML.='</table>';
    return $returnedHTML;
}
/*
 *   Funkcja generująca listę dzieci
 */
function genListaDzieci($stmt){
    $returnedHTML='';
    foreach ($stmt as $row){
        $returnedHTML.='<a class="btn btn-info" href=#u'.$row['id_uzytkownika'].'>'.$row['imie'].' '.$row['nazwisko'].'</a>'.PHP_EOL;
    }
    return $returnedHTML;
}
/*
 *   Funkcja formatująca wiadomosć do nadawcy $id_nadawcy do odbiorcy $id_odbiorcy
 */
function genKontakt($pdo, $id_odbiorcy, $id_nadawcy, $typ, $powrot){
    $returnedHTML='';
    $stmt = $pdo->prepare ('SELECT imie,nazwisko
                            FROM uzytkownicy
                            WHERE id_uzytkownika = :id_u
                            ');
    $stmt->bindValue (':id_u', $id_odbiorcy, PDO::PARAM_INT);
    $stmt->execute();
    $dane=$stmt->fetch(PDO::FETCH_ASSOC);
    $returnedHTML.='<span>'.$dane['nazwisko'].' '.$dane['imie'].' ';
    $returnedHTML.='<a class="btn btn-success" href="wyslij_wiadomosc.php?id_o='.$id_odbiorcy.'&amp;id_n='.$id_nadawcy.'&amp;t='.$typ.
                    '&amp;p='.$powrot.'">Wyślij wiadomość</a>'.PHP_EOL;
    $returnedHTML.='</span>';
    return $returnedHTML;
}
/*
 *   Funkcja wyswietlająca listę uwag dla ucznia $id_ucznia
 */
function genListaUwagUcznia ($pdo, $id_ucznia){
    $returnedHTML='';
    $powrot='r_uwagi.php';
    $id_nadawcy=$_SESSION['user_id'];
    $stmt = $pdo->prepare ('SELECT id_nauczyciela, data, opis, negatywna
                            FROM uwagi
                            WHERE id_ucznia = :id_u
                            ORDER by data DESC
                            ');
    $stmt->bindValue (':id_u', $id_ucznia, PDO::PARAM_INT);
    $stmt->execute();
    foreach ($stmt as $row){
        $nauczyciel=getUzytkownikInfo($pdo, $row['id_nauczyciela']);
        if ($row['negatywna']==0){
            $typ_uwagi='Pozytywna';
            $class='success';
        }
        else {
            $typ_uwagi='Negatywna';
            $class='danger';
        }
        $returnedHTML.='<p><table class="responsive">';
        $returnedHTML.='<tr><td>Data: '.$row['data'].'</td><td class="'.$class.'">Typ: '.$typ_uwagi.'</td></tr>';
        $returnedHTML.='<tr><td>Informacja:</td><td>'.$row['opis'].'</td></tr>';
        $returnedHTML.='</table>';
        $returnedHTML.=genKontakt($pdo, $row['id_nauczyciela'], $id_nadawcy,3,$powrot);
        $returnedHTML.='</p>';
    }
    return $returnedHTML;
}
/*
 *   Funkcja wyswietlająca listę dzieci rodzica $id_rodzica
 */
function getDzieciRodzica ($pdo, $id_rodzica){
    $stmt = $pdo->prepare('SELECT id_uzytkownika, nazwisko, imie, id_klasy
                            FROM uzytkownicy
                            WHERE id_uzytkownika IN
                            (SELECT id_ucznia
                            FROM rodzice_uczniowie
                            WHERE id_rodzica= :id_rodzica)'
        );
    $stmt->bindValue(':id_rodzica',$id_rodzica,PDO::PARAM_INT);
    $stmt->execute();
    $dzieci=$stmt->fetchAll();
   return $dzieci;
}
/*
 *   Funkcja wyswietlająca listę rodziców ucznia $id_ucznia
 */
function getRodziceUcznia ($pdo, $id_ucznia){
    $stmt = $pdo->prepare('SELECT id_uzytkownika, nazwisko, imie
                            FROM uzytkownicy
                            WHERE id_uzytkownika IN
                            (SELECT id_rodzica
                            FROM rodzice_uczniowie
                            WHERE id_ucznia= :id_ucznia)'
        );
    $stmt->bindValue(':id_ucznia',$id_ucznia,PDO::PARAM_INT);
    $stmt->execute();
    $rodzice=$stmt->fetchAll();
    return $rodzice;
}
/*
 *   Funkcja wyswietlająca listę nieobecnosci ucznia $id_ucznia z możliwoscią ich usprawiedliwienia przez rodzica $id_rodzica
 */
function genListaNieobUcznia($pdo,$id_ucznia,$id_rodzica){
    $returnedHTML='';
    $stmt = $pdo->prepare ('SELECT data, usprawiedliwiona, id_nieobecnosci, usprawiedliwienie, id_rodzica
                            FROM nieobecnosci
                            WHERE id_ucznia = :id_u
                            ORDER by data DESC
                            ');
    $stmt->bindValue (':id_u', $id_ucznia, PDO::PARAM_INT);
    $stmt->execute();
    foreach ($stmt as $row){
        $returnedHTML.='<p><table class="responsive">';
        $returnedHTML.='<tr><th>Data</th><th>Usprawiedliwienie</th><th>Rodzic</th></tr>';
        $returnedHTML.='<tr><td>'.$row['data'].'</td>';
        if ($row['usprawiedliwiona']==0){
            $returnedHTML.='<td>BRAK</td><td><a class="btn btn-info" href="usp_nieob.php?id_nb='.$row['id_nieobecnosci'].
                            '&amp;id_r='.$id_rodzica.'">Napisz usprawiedliwienie</a></td></tr>';
        }
        else{
            $rodzic=getUzytkownikInfo($pdo, $row ['id_rodzica']);
            $skrot=substr($rodzic['imie'],0,1);
            $returnedHTML.='<td>'.$row['usprawiedliwienie'].'</td><td>'.$skrot.'. '.$rodzic['nazwisko'].'</td></tr>';
        }
        $returnedHTML.='</table>';
        $returnedHTML.='</p>';
    }
    return $returnedHTML;
}
/*
 *   Funkcja wyswietlająca listę przedmiotów dla danej klasy $id_klasy z możliwoscią dodania oceny dla ucznia $id_ucznia
 */
function genOcenyPrzedmiotuKlasy ($pdo, $id_przedmiotuklasy, $id_klasy, $klasa, $przedmiot){
    $returnedHTML='';
    $returnedHTML.='<h2>Klasa:'.$klasa.', Przedmiot: '.$przedmiot.'</h2>';
    $i=1;
    $uczniowie=getListaUczniowKlasy($pdo, $id_klasy);
    foreach ($uczniowie as $row){
        $returnedHTML.='<p>'.$i++.'. '.$row['nazwisko'].' '.$row['imie'].' <a class="btn btn-info" href="dodaj_ocene.php?id_ucznia='
            .$row['id_uzytkownika'].'&amp;id_pk='.$id_przedmiotuklasy.'">Dodaj ocenę</a></p>';
        $returnedHTML.=genListaOcenUcznia($pdo, $row['id_uzytkownika'],$id_przedmiotuklasy);
        $returnedHTML.='<br/>';
    }
    return $returnedHTML;
}
/*
 *   Funkcja generująca listę uczniów dla danej klasy $id_klasy
 */
function getListaUczniowKlasy ($pdo, $id_klasy){
    $ucznowie=array();
    $stmt = $pdo->prepare ('SELECT id_uzytkownika, imie, nazwisko
                            FROM uzytkownicy
                            WHERE id_klasy = :id_k
                            ORDER by nazwisko
                            ');
    $stmt->bindValue (':id_k', $id_klasy, PDO::PARAM_INT);
    $stmt->execute();
    $uczniowie=$stmt->fetchAll();
    return $uczniowie;
}
/*
 *   Funkcja generująca listę ocen dla danego ucznia $id_ucznia z danego przedmiotu $id_przedmiotuklasy
 */
function genListaOcenUcznia($pdo, $id_ucznia,$id_przedmiotuklasy){
    $returnedHTML='';
    $stmt = $pdo->prepare ('SELECT id_oceny, symbol, data, kategoria
                            FROM oceny_ucznia NATURAL JOIN kategorie_ocen
                            WHERE id_ucznia = :id_u AND id_przedmiotuklasy= :id_pk
                            ORDER by data
                            ');
    $stmt->bindValue (':id_u', $id_ucznia, PDO::PARAM_INT);
    $stmt->bindValue (':id_pk', $id_przedmiotuklasy, PDO::PARAM_INT);
    $stmt->execute();
    $returnedHTML.='<table class="responsive">';
    foreach ($stmt as $row){
        $returnedHTML.='<tr><td>'.$row['data'].'</td><td>'.$row['symbol'].'</td><td>'.$row['kategoria'].
        '</td><td><a class="btn btn-warning" href="dodaj_ocene.php?id_ucznia='
            .$id_ucznia.'&amp;id_pk='.$id_przedmiotuklasy.'&amp;id_ou='.$row['id_oceny'].'">Zmień</a></td><td><a class="btn btn-danger" href="usun_ocene.php?id_oceny='
                .$row['id_oceny'].'&amp;id_pk='.$id_przedmiotuklasy.'" onclick="return confirm(\'Czy na pewno chcesz usunąć ?\')">Usuń</a></td></tr>';
    }
    $returnedHTML.='</table>';
    return $returnedHTML;
}
/*
 *   Funkcja generująca sprawdzianów dla danej klasy $id_klasy
 */
function genListaSprawdzianyKlasy ($pdo, $id_klasy){
    $returnedHTML='';
    $stmt = $pdo->prepare('SELECT data, przedmiot 
                           FROM sprawdziany NATURAL JOIN przedmioty_klasy NATURAL JOIN przedmioty
                           WHERE id_klasy= :id_k
                           ORDER BY data DESC'
                            );
    $stmt->bindValue (':id_k', $id_klasy, PDO::PARAM_INT);
    $stmt->execute();
    $dzisiaj=date('Y-m-d');
    $returnedHTML.='<table class="responsive">';
    $returnedHTML.='<tr><th>Kiedy</th><th>Data</th><th>Przedmiot</th>';
    foreach ($stmt as $row)
    {
        $info=genIkonaKiedy($row['data'], $dzisiaj);
        $returnedHTML.='<tr><td>'.$info.'</td><td>'.$row['data'].'</td><td>'.$row['przedmiot'].'</td></tr>';
       
    }
    $returnedHTML.='</table>';
    $stmt->closeCursor();
    return $returnedHTML;
}
/*
 *   Funkcja generująca sprawdzian dla danej klasy $id_klasy z okreslonego przedmiotu $id_przedmiotuklasy
 */
function genSprawdzianyPrzedmiotu ($pdo, $id_przedmiotuklasy, $id_klasy, $klasa, $przedmiot){
    $returnedHTML='';
    $dzisiaj=date('Y-m-d');
    $returnedHTML.='<h2>Klasa:'.$klasa.', Przedmiot: '.$przedmiot.'</h2>';
    $returnedHTML.='<form id="data" method="post" action="dodaj_sprawdz2.php">';
    $returnedHTML.='Wybierz datę sprawdzianu:';
    $returnedHTML.='<input type="date" name="data" min="2017-01-01" max="2100-12-31" value="'.$dzisiaj.'">';
    $returnedHTML.='<input type="hidden" name="id_pk" value="'.$id_przedmiotuklasy.'">';
    $returnedHTML.='<input type="submit" name="submit2" value="Dodaj sprawdzian">';
    $returnedHTML.='</form>';
   // $returnedHTML.=' <a class="btn btn-info" href="dodaj_sprawdz.php?id_pk='.$id_przedmiotuklasy.'">Dodaj sprawdzian</a>';
    $stmt = $pdo->prepare('SELECT data, id_sprawdzianu
                           FROM sprawdziany
                           WHERE id_przedmiotuklasy= :id_pk
                           ORDER BY data DESC'
        );
    $stmt->bindValue (':id_pk', $id_przedmiotuklasy, PDO::PARAM_INT);
    $stmt->execute();
    $returnedHTML.='<table class="responsive">';
    foreach ($stmt as $row){
        $info=genIkonaKiedy($row['data'], $dzisiaj);
        $returnedHTML.='<tr><td>'.$info.'</td><td>'.$row['data'].'</td><td><a class="btn btn-danger" href="usun_sprawdz.php?id_sp='
                .$row['id_sprawdzianu'].'&amp;id_pk='.$id_przedmiotuklasy.'" onclick="return confirm(\'Czy na pewno chcesz usunąć ?\')">Usuń</a></td></tr>';
    }
    $returnedHTML.='</table>';
    return $returnedHTML;
}
function genIkonaKiedy ($data,$dzisiaj){
    $info='';
    $roznica=strtotime($dzisiaj)-strtotime($data);
    $znak=($roznica > 0) - ($roznica < 0);
    switch ($znak){
        case -1: $info='<a class="btn btn-warning">Wkrótce</a>'; break;
        case  0: $info='<a class="btn btn-danger">Dzisiaj</a>'; break;
        case  1: $info='<a class="btn btn-info">Już minął</a>'; break;
    }
    return $info;
}
/*
 *   Funkcja generująca nieobecnosci dla danej klasy $id_klasy wybranego ucznia
 */
function genNieobecnosciKlasy ($pdo,$id_klasy,$klasa){
    $returnedHTML='';
    $i=1;
    $returnedHTML.='<h2>Klasa: '.$klasa.'</h2>';
    $uczniowie=getListaUczniowKlasy($pdo, $id_klasy);
    $returnedHTML.='<table class="responsive">';
    foreach ($uczniowie as $row){
        $returnedHTML.='<tr><td>'.$i++.'</td><td>'.$row['nazwisko'].' '.$row['imie'].'</td><td><a class="btn btn-danger" href="dodaj_nieob.php?id_u='
            .$row['id_uzytkownika'].'&amp;id_k='.$id_klasy.'" onclick="return confirm(\'Czy na pewno chcesz wpisać nieobecność ?\')">Wpisz</a></td>
            <td><a class="btn btn-info" href="lista_nieob.php?id_u='.$row['id_uzytkownika'].'&amp;id_k='.$id_klasy.'">Lista</a></td></tr>';
    }
    $returnedHTML.='</table>';
    return $returnedHTML;
}
/*
 *   Funkcja generująca nieobecnosci ucznia $id_ucznia do usprawiedliwienia
 */
function genListaNieobUczniaNauczyciel($pdo,$id_ucznia,$powrot){
    $returnedHTML='';
    $stmt = $pdo->prepare ('SELECT data, usprawiedliwiona, id_nieobecnosci, usprawiedliwienie, id_rodzica
                            FROM nieobecnosci
                            WHERE id_ucznia = :id_u
                            ORDER by data DESC
                            ');
    $stmt->bindValue (':id_u', $id_ucznia, PDO::PARAM_INT);
    $stmt->execute();
    foreach ($stmt as $row){
        $returnedHTML.='<p><table class="responsive">';
        $returnedHTML.='<tr><th>Data</th><th>Usprawiedliwienie</th><th>Rodzic</th></tr>';
        $returnedHTML.='<tr><td>'.$row['data'].'</td>';
        if ($row['usprawiedliwiona']==0){
            $returnedHTML.='<td>BRAK</td><td><a class="btn btn-info" href="wyslij_rd.php?id_u='.$id_ucznia.
                '&amp;p='.$powrot.'">Napisz do rodziców</a></td></tr>';
        }
        else{
            $rodzic=getUzytkownikInfo($pdo, $row ['id_rodzica']);
            $skrot=substr($rodzic['imie'],0,1);
            $returnedHTML.='<td>'.$row['usprawiedliwienie'].'</td><td>'.$skrot.'. '.$rodzic['nazwisko'].'</td></tr>';
        }
        $returnedHTML.='</table>';
        $returnedHTML.='</p>';
    }
    return $returnedHTML;
}
/*
 *   Funkcja sprawdzająca status wiadomosci
 */
function genListaWiadomosci($pdo, $id_odbiorcy,$przeczytana,$odbiorca_typ,$powrot){
     $returnedHTML='';
     if ($przeczytana==1){
         $info='Nieprzeczytana';
     }
     else{
         $info='Przeczytana';
     }
     $stmt = $pdo->prepare ('SELECT data, id_wiadomosci, id_nadawcy, tresc
                            FROM wiadomosci
                            WHERE id_odbiorcy = :id_o AND przeczytana= :p
                            ORDER by data DESC
                            ');
     $stmt->bindValue (':id_o', $id_odbiorcy, PDO::PARAM_INT);
     $stmt->bindValue (':p', $przeczytana, PDO::PARAM_INT);
     $stmt->execute();
     foreach ($stmt as $row){
         $nadawca=getUzytkownikInfo($pdo, $row['id_nadawcy']);
         $returnedHTML.='<p>';
         $returnedHTML.='<span> Od:';
         $returnedHTML.=genKontakt($pdo, $row['id_nadawcy'], $id_odbiorcy, $odbiorca_typ, $powrot);
         $returnedHTML.='<a class="btn btn-warning" href="oznacz_wiad.php?id_w='.$row['id_wiadomosci'].
                            '&amp;t='.$odbiorca_typ.'">'.$info.'</a>';
         $returnedHTML.='<a class="btn btn-danger" href="usun_wiad.php?id_w='.$row['id_wiadomosci'].
                            '&amp;t='.$odbiorca_typ.'" onclick="return confirm(\'Czy na pewno chcesz usunąć wiadomość ?\')" >Usuń</a>';
         $returnedHTML.='</span>';
         $returnedHTML.='</p>';
         $returnedHTML.='<table class="responsive">';
         $returnedHTML.='<tr><th>Data</th><th>Treść</th></tr>';
         $returnedHTML.='<tr><td>'.$row['data'].'</td><td>'.$row['tresc'].'</td></tr>';
         $returnedHTML.='</table><br/>';
         
        
    }
    return $returnedHTML;
}
/*
 *   Funkcja generująca widomosci do rodziców dla całej klasy $id_klasy
 */
function genWiadomoscKlasy ($pdo,$id_klasy,$klasa,$powrot){
    $returnedHTML='';
    $i=1;
    $returnedHTML.='<h2>Klasa: '.$klasa.'</h2>';
    $returnedHTML.='<a class="btn btn-warning" href="wyslij_rd.php?id_k='.$id_klasy.'&amp;k='.$klasa.'&amp;p='.$powrot.'">Wyślij do rodziców wszystkich uczniów w klasie</a>';
    $uczniowie=getListaUczniowKlasy($pdo, $id_klasy);
    $returnedHTML.='<table class="responsive">';
    foreach ($uczniowie as $row){
        $returnedHTML.='<tr><td>'.$i++.'</td><td>'.$row['nazwisko'].' '.$row['imie'].'</td><td><a class="btn btn-info" href="wyslij_rd.php?id_u='
            .$row['id_uzytkownika'].'&amp;p='.$powrot.'">Wyślij do rodziców</a></td></tr>';
    }
    $returnedHTML.='</table>';
    return $returnedHTML;
}
/*
 *   Funkcja generująca listę uwag nauczyciela $id_nauczyciela dla danej klasy $id_klasy
 */
function genUwagiKlasy ($pdo,$id_klasy,$klasa,$id_nauczyciela, $powrot){
    $returnedHTML='';
    $i=1;
    $returnedHTML.='<h2>Klasa: '.$klasa.'</h2>';
    $uczniowie=getListaUczniowKlasy($pdo, $id_klasy);
    $returnedHTML.='<table class="responsive">';
    foreach ($uczniowie as $row){
        $returnedHTML.='<tr><td>'.$i++.'</td><td>'.$row['nazwisko'].' '.$row['imie'].'</td><td><a class="btn btn-danger" href="dodaj_uwage.php?id_u='
            .$row['id_uzytkownika'].'&amp;id_n='.$id_nauczyciela.'&amp;p='.$powrot.'&amp;id_k='.$id_klasy.'">Wpisz</a></td>
            <td><a class="btn btn-info" href="lista_uwag.php?id_u='.$row['id_uzytkownika'].'&amp;id_n='.$id_nauczyciela.
            '&amp;p='.$powrot.'&amp;id_k='.$id_klasy.'">Lista uwag</a></td></tr>';
    }
    $returnedHTML.='</table>';
    return $returnedHTML;
}
/*
 *   Funkcja generująca listę uwag nauczyciela $id_nauczyciela dla danego ucznia $id_ucznia
 */
function genListaUwagUczniaNauczyciel ($pdo, $id_ucznia,$id_nauczyciela){
    $returnedHTML='';
    $stmt = $pdo->prepare ('SELECT data, negatywna, id_uwagi, opis
                            FROM uwagi
                            WHERE id_ucznia = :id_u AND id_nauczyciela= :id_n
                            ORDER by data DESC
                            ');
    $stmt->bindValue (':id_u', $id_ucznia, PDO::PARAM_INT);
    $stmt->bindValue (':id_n', $id_nauczyciela, PDO::PARAM_INT);
    $stmt->execute();
    $returnedHTML.='<p><table class="responsive">';
    $returnedHTML.='<tr><th>Data</th><th>Treść uwagi</th><th>P/N</th><th>Usuń</th></tr>';
    foreach ($stmt as $row){
        $returnedHTML.='<tr><td>'.$row['data'].'</td><td>'.$row['opis'].'</td>';
        if ($row['negatywna']==0){
            $returnedHTML.='<td><button class="btn btn-info">Pozytywna</button></td>';
        }
        else{
            $returnedHTML.='<td><button class="btn btn-danger">Negatywna</button></td>';
        }
        $returnedHTML.='<td><a class="btn btn-danger" href="usun_uwage.php?id_uw='.$row['id_uwagi'].'&amp;id_u='.$id_ucznia
                        .'&amp;id_n='.$id_nauczyciela.'">Usuń</a></td></tr>';
    }
    $returnedHTML.='</table>';
    $returnedHTML.='</p>';
    return $returnedHTML;
}
/*
 *   Funkcja generująca plan lekcji dla danej klasy $id_klasy
 */
function genPlanyKlasy($pdo, $id_klasy, $klasa, $powrot){
    $returnedHTML='';
    $returnedHTML.='<h2>Plan klasy: '.$klasa.'</h2>';
    $returnedHTML.=genListaDniHTML($pdo);
    $dni=getListaDni($pdo);
    foreach ($dni as $row){
        $returnedHTML.='<h3 id=#'.$row['id_dnia'].'> '.$row['dzien'].'</h3>';
        $returnedHTML.=genPlanDniaKlasy($pdo, $row['id_dnia'],$id_klasy, $klasa);
    }
    return $returnedHTML;
}
/*
 *   Funkcja generująca plan lekcji dla danej klasy $id_klasy dla danego dnia $id_dnia
 */
function genPlanDniaKlasy($pdo, $id_dnia, $id_klasy, $klasa){
    $returnedHTML='';
    $stmt = $pdo->prepare ('SELECT czas, id_przedmiotuklasy, przedmiot, id_godziny
                            FROM plany_lekcji
                            NATURAL JOIN godziny
                            NATURAL JOIN przedmioty_klasy
                            NATURAL JOIN przedmioty
                            WHERE id_dnia = :id_d AND id_klasy = :id_k
                            ORDER BY id_godziny');
    $stmt->bindValue (':id_d', $id_dnia, PDO::PARAM_INT);
    $stmt->bindValue (':id_k', $id_klasy, PDO::PARAM_INT);
    $stmt->execute();
    $returnedHTML.='<a class="btn btn-info" href="dodaj_lekcje.php?id_d='.$id_dnia.'&amp;id_k='.$id_klasy.'">Dodaj lekcję</a>';
    $returnedHTML.='<table class="responsive">';
    $returnedHTML.='<tr><th>Nr lekcji</th><th>Godzina</th><th>Przedmiot</th><th>Usuń</th></tr>';
    foreach ($stmt as $row){
        
        $returnedHTML.='<tr><td>'.$row['id_godziny'].'</td><td>'.$row['czas'].'</td><td>'.$row['przedmiot'].'</td><td><a class="btn btn-danger" href="usun_lekcje.php?id_d='.$id_dnia.
        '&amp;id_g='.$row['id_godziny'].'&amp;id_pk='.$row['id_przedmiotuklasy'].'&amp;id_k='.$id_klasy.'" onclick="return confirm(\'Czy na pewno chcesz usunąć wiadomość ?\')" >Usuń</a></td></tr>';
    }
    $returnedHTML.='</table>';
    return $returnedHTML;
}
/*
 *   Funkcja generująca ekran powitalny po zalogowaniu
 */
function genEkranPowitalny($nazwa,$typ){
    $returnedHTML='';
    $returnedHTML=<<<EKRAN
    <div class="welcome">
    <h2>Witaj $nazwa</h2>
    <h3>Właśnie zalogowałeś się do Dziennika Elektronicznego jako $typ</h3>
    <h3>Skorzystaj z menu po lewej stronie,</h3>
    <h3>a w przypadku małego ekranu z przycisku u góry.</h3>
    </div>
EKRAN;
    return $returnedHTML;
}
/*
 *   Funkcja generująca ekran pozegnalny po wylogowaniu
 */
function genEkranPozegnalny($nazwa){
    $returnedHTML='';
    $returnedHTML=<<<EKRAN
    <div class="welcome">
    <h2>Do widzenia $nazwa</h2>
    <h3>Wylogowanie poprawne</h3>
    <h3>Miłego dnia</h3>
    </div>
EKRAN;
    return $returnedHTML;
}
?>
