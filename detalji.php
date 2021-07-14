<?php
	header('Access-Control-Allow-Origin: *');
	error_reporting(E_ALL);
	include_once("funkcije.php");
	$id=$_REQUEST["id"];
	$dom=new DOMDocument();
	$dom->load('podaci.xml');
	$xp= new DOMXPath($dom);
	$upit='/muzeji/muzej[contains(@id,"'.$id.'")]';

	$results=$xp->query($upit);
	
	foreach($results as $el){
		echo "<p class='detalji1'> DETALJI: </p><br/>";
		echo "<p class='detalji'>Posjetitelja godisnje: </p>";
		echo getEl('posjeta_god',$el) ;
		echo "<br/><p class='detalji'>Izložbenih primjeraka: </p>";
		echo getEl('predmeti',$el);
		echo "<br/>Kontakt:";
		echo "<a href=http://".getEl('kontakt',$el).">".getEl('kontakt',$el)."</a>" ;
	}


?>