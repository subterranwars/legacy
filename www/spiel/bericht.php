<?php
session_start();
//Inkludes
require_once("../includes/klassen/db.php");
require_once("../includes/klassen/text.php");
require_once("../includes/klassen/fehler.php");
require_once("../includes/klassen/template.php");

//Datenbnakobjekt einbinden!
$db = new DATENBANK();

//überprüfen ob $_GET['ID'] gesetzt ist
if( isset($_GET['ID']) )	//ja
{
	//Überprüfen ob Kampfbericht vorhanden ist!
	if( file_exists("cache/berichte/".$_GET['ID'].".html") )
	{
		//Datei anzeigen!
		$fp = fopen("cache/berichte/".$_GET['ID'].".html", 'r');
		fpassthru($fp);
	}
	else 
	{
		//Neues Template erzeugen
		$tpl = new TEMPLATE("templates/stw_v02/bericht.tpl");
		$daten_bericht = array(
			'FEHLER' => '');
			
		//Fehlerobjekt erzeugen
		$fehler 					= new FEHLER($db);	
		//KAmpfbericht nicht vorhanden
		$daten_bericht['FEHLER'] 	= $fehler->meldung(1000);
		
		//Setze Templatedaten
		$tpl->setObject("bericht", $daten_bericht);
		echo $tpl->getTemplate();
	}
}?>