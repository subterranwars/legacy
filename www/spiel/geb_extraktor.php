<?php
//Includes
require("../includes/login_check.php");

/*Gebäudelevel laden*/
$geb_res_level 		= $_SESSION['user']->getGebäudeLevel(12);		//extraktor hat die ID 12

/*Überprüfen ob User Gebäude überhaupt hat!*/
if ( $geb_res_level != 0 ) 
{	
	//Template laden
	$tpl_extraktor = new TEMPLATE("templates/".$_SESSION['skin']."/gebäude/extraktor.tpl");
	$daten_extraktor = array(
		'VERBRAUCH' => '',
		'GEWINN' => '',
		'SELECT' => '',
		'FEHLER' => '');
	
	//extraktorObjekt erzeugen
	$extraktor = new WASSERSTOFF($db, $_SESSION['user'], $_SESSION['kolonie']->getID(), $_SESSION['kolonie']->getEnergieniveau());

	/*Überprüfen ob Knopp gedrückt wurde!*/
	if( isset($_POST['send']) )
	{
		//Neue Auslastung setzen
		$error = $extraktor->setAuslastung($_POST['auslastung']);
		
		//Fehler abfangen
		switch($error)
		{			
			//Wert zu klein!
			case -1:
				//Fehlerobjekt erzeugen	
				$fehler = new FEHLER($db);
				$daten_extraktor['FEHLER'] = $fehler->meldung(700);
				break;
			//Wert zu groß!
			case -2:
				//Fehlerobjekt erzeugen
				$fehler = new FEHLER($db);
				$daten_extraktor['FEHLER'] = $fehler->meldung(701);
				break;
		}
	}
	
	//Templatedaten setzen
	$daten_extraktor['VERBRAUCH'] 	= $extraktor->getResVerbrauch();
	$daten_extraktor['GEWINN'] 		= $extraktor->getProduktion();

	//Selectdaten setzen
	$daten_extraktor['SELECT'] = "<select name=\"auslastung\">";
	
	//Auslastung des extraktores laden
	$auslastung = $extraktor->getAuslastung();
	
	for($i=0; $i <= 100; $i=$i+10 )
	{
		//Daten setzen
		$wert = $i/100;
		
		if( $auslastung == ($i/100) )
		{
			$daten_extraktor['SELECT'] .= "<option value=\"$wert\" selected>".$i."%</option>";
		}
		else
		{
			$daten_extraktor['SELECT'] .= "<option value=\"$wert\">".$i."%</option>";
		}
	}
	$daten_extraktor['SELECT'] .= "</select>";
	
	//Template setzen
	$tpl_extraktor->setObject("extraktor", $daten_extraktor);
	$daten['CONTENT'] .= $tpl_extraktor->getTemplate();
}
else 
{
	//Fehlerobjekt erzeugen
	$fehler = new FEHLER($db);
	$daten['CONTENT'] = $fehler->meldung(141);
}

//footer einbinden
require_once("../includes/footer.php");