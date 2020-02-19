<?php
//Includes
require("../includes/login_check.php");

//Template laden
$tpl_techtree_error = new TEMPLATE("templates/".$_SESSION['skin']."/techtrees/techtree_error.tpl");
$daten_techtree_error = array(
	'CONTENT' => '');
	

//OBjekte erzeugen
$db = new DATENBANK();			//Datenbank
$fehler = new FEHLER($db);		//Fehler

//Templatedaten setzen
$daten_techtree_error['CONTENT'] = $fehler->meldung($_GET['code']);
$tpl_techtree_error->setObject("techtree_error", $daten_techtree_error);
echo $tpl_techtree_error->getTemplate();


//Objekt serialisieren
$_SESSION['user'] 			= serialize($_SESSION['user']);
$_SESSION['kolonie'] 		= serialize($_SESSION['kolonie']);
$_SESSION['bevoelkerung']	= serialize($_SESSION['bevoelkerung']);