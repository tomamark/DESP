<?php 
echo '<p>Witaj w Dzienniku Elektronicznym. Widok:<b> '.$LOKALIZACJA.' </b>';
if (isset($_SESSION['username'])){
    echo 'Jesteś zalogowany jako <b>'.$_SESSION['username'].'</b> ('.$_SESSION['usertype'].')';
    echo '<a class="btn btn-warning" href="logout.php"> Wyloguj się</a></p>';
}
else {
    echo 'Nie jesteś zalogowany ';
   // echo '<a class="btn btn-info" href="index.php"> Zaloguj się</a></p>';
}
?>