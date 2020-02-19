<?php
//Includes
require("../includes/login_check.php");

/*Gebäudelevel laden*/
$geb_res_level 		= $_SESSION['user']->getGebäudeLevel(17);		//Kraftwerk hat die ID 17

/*Überprüfen ob User Gebäude überhaupt hat!*/
if ( $geb_res_level != 0 ) 
{	
	//Template laden
	$tpl_kraftwerk = new TEMPLATE("templates/".$_SESSION['skin']."/gebäude/kraftwerk.tpl");
	$daten_kraftwerk = array(
		'VERBRAUCH'=>'',
		'GEWINN'=>'',
		'SELECT'=>'',
		'FEHLER' => '');
	
	//KraftwerkObjekt erzeugen
	$kraftwerk = new KRAFTWERK($db, $_SESSION['user'], $_SESSION['kolonie']->getID());

	/*Überprüfen ob Knopp gedrückt wurde!*/
	if( isset($_POST['send']) )
	{
		//Neue Auslastung setzen
		$error = $kraftwerk->setAuslastung($_POST['auslastung']);
		
		//Fehler abfangen
		switch($error)
		{			
			//Wert zu klein!
			case -1:
				//Fehlerobjekt erzeugen	
				$fehler = new FEHLER($db);
				$daten_kraftwerk['FEHLER'] = $fehler->meldung(700);
				break;
			//Wert zu groß!
			case -2:
				//Fehlerobjekt erzeugen
				$fehler = new FEHLER($db);
				$daten_kraftwerk['FEHLER'] = $fehler->meldung(701);
				break;
		}
	}
	
	//Templatedaten setzen
	$daten_kraftwerk['VERBRAUCH'] 	= $kraftwerk->getResVerbrauch();
	$daten_kraftwerk['GEWINN'] 	= $kraftwerk->getProduktion();

	//Selectdaten setzen
	$daten_kraftwerk['SELECT'] = "<select name=\"auslastung\">";
	
	//Auslastung des KRaftwerkes laden
	$auslastung = $kraftwerk->getAuslastung();
	
	for($i=0; $i <= 100; $i=$i+5 )
	{
		//Daten setzen
		$wert = $i/100;
		
		if( $auslastung == ($i/100) )
		{
			$daten_kraftwerk['SELECT'] .= "<option value=\"$wert\" selected>".$i."%</option>";
		}
		else
		{
			$daten_kraftwerk['SELECT'] .= "<option value=\"$wert\">".$i."%</option>";
		}
	}
	$daten_kraftwerk['SELECT'] .= "</select>";
	
	//Template setzen
	$tpl_kraftwerk->setObject("kraftwerk", $daten_kraftwerk);
	$daten['CONTENT'] .= $tpl_kraftwerk->getTemplate();
}
else 
{
	//Fehlerobjekt erzeugen
	$fehler = new FEHLER($db);
	$daten['CONTENT'] = $fehler->meldung(141);
}

//footer einbinden
require_once("../includes/footer.php");