<?php
/**
 * funkcja generująca pozycje menu 
 * @param menu array <p>
 * Tablica zawierajaca nazwe strony jako klucz oraz wyswietlany tekst jako wartosć;
 * </p>
 */
function menu($menu)
{
	if (!is_array($menu)) return FALSE;
	$tresc='';
	$tresc.='<ul class="nav navbar-nav">'.PHP_EOL;
	foreach ($menu as $adres => $napis)
	{
		if (is_file($adres))
		$tresc.='<li class="active btn btn-warning"><a class="btn btn-danger" href="'.$adres.'">'.$napis.'</a></li>'.PHP_EOL;
	}
	$tresc.='</ul>'.PHP_EOL;
	return $tresc;
}
?>