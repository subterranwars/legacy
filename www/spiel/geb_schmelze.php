<?php
//Includes
require("../includes/login_check.php");

/*Gebäudelevel laden*/
$geb_res_level 		= $_SESSION['user']->getGebäudeLevel(10);		//Schmelze hat die ID 10

/*Überprüfen ob User Gebäude überhaupt hat!*/
if ( $geb_res_level != 0 ) 
{	
	//Template laden
	$tpl_schmelze = new TEMPLATE("templates/".$_SESSION['skin']."/gebäude/schmelze.tpl");
	$daten_schmelze = array(
		'VERBRAUCH' => '',
		'GEWINN' => '',
		'SELECT' => '',
		'FEHLER' => '');
	
	//schmelzeObjekt erzeugen
	$schmelze = new SCHMELZE($db, $_SESSION['user'], $_SESSION['kolonie']->getID(), $_SESSION['kolonie']->getEnergieniveau());

	/*Überprüfen ob Knopp gedrückt wurde!*/
	if( isset($_POST['send']) )
	{
		//Neue Auslastung setzen
		$error = $schmelze->setAuslastung($_POST['auslastung']);
		
		//Fehler abfangen
		switch($error)
		{			
			//Wert zu klein!
			case -1:
				//Fehlerobjekt erzeugen	
				$fehler = new FEHLER($db);
				$daten_schmelze['FEHLER'] = $fehler->meldung(700);
				break;
			//Wert zu groß!
			case -2:
				//Fehlerobjekt erzeugen
				$fehler = new FEHLER($db);
				$daten_schmelze['FEHLER'] = $fehler->meldung(701);
				break;
		}
	}
	
	//Templatedaten setzen
	$daten_schmelze['VERBRAUCH'] 	= $schmelze->getResVerbrauch();
	$daten_schmelze['GEWINN'] 		= $schmelze->getProduktion();

	//Selectdaten setzen
	$daten_schmelze['SELECT'] = "<select name=\"auslastung\">";
	
	//Auslastung des schmelzees laden
	$auslastung = $schmelze->getAuslastung();
	
	for($i=0; $i <= 100; $i=$i+10 )
	{
		//Daten setzen
		$wert = $i/100;
		
		if( $auslastung == ($i/100) )
		{
			$daten_schmelze['SELECT'] .= "<option value=\"$wert\" selected>".$i."%</option>";
		}
		else
		{
			$daten_schmelze['SELECT'] .= "<option value=\"$wert\">".$i."%</option>";
		}
	}
	$daten_schmelze['SELECT'] .= "</select>";
	
	//Template setzen
	$tpl_schmelze->setObject("schmelze", $daten_schmelze);
	$daten['CONTENT'] .= $tpl_schmelze->getTemplate();
}
else 
{
	//Fehlerobjekt erzeugen
	$fehler = new FEHLER($db);
	$daten['CONTENT'] = $fehler->meldung(141);
}
//footer einbinden
require_once("../includes/footer.php");