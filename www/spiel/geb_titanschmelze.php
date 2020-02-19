<?php
//Includes
require("../includes/login_check.php");

/*Gebäudelevel laden*/
$geb_res_level 		= $_SESSION['user']->getGebäudeLevel(13);		//titanschmelze hat die ID 10

/*Überprüfen ob User Gebäude überhaupt hat!*/
if ( $geb_res_level != 0 ) 
{	
	//Template laden
	$tpl_titanschmelze = new TEMPLATE("templates/".$_SESSION['skin']."/gebäude/titanschmelze.tpl");
	$daten_titanschmelze = array(
		'VERBRAUCH' => '',
		'GEWINN' => '',
		'SELECT' => '',
		'FEHLER' => '');
	
	//titanschmelzeObjekt erzeugen
	$titanschmelze = new TITANSCHMELZE($db, $_SESSION['user'], $_SESSION['kolonie']->getID(), $_SESSION['kolonie']->getEnergieniveau());

	/*Überprüfen ob Knopp gedrückt wurde!*/
	if( isset($_POST['send']) )
	{
		//Neue Auslastung setzen
		$error = $titanschmelze->setAuslastung($_POST['auslastung']);
		
		//Fehler abfangen
		switch($error)
		{			
			//Wert zu klein!
			case -1:
				//Fehlerobjekt erzeugen	
				$fehler = new FEHLER($db);
				$daten_titanschmelze['FEHLER'] = $fehler->meldung(700);
				break;
			//Wert zu groß!
			case -2:
				//Fehlerobjekt erzeugen
				$fehler = new FEHLER($db);
				$daten_titanschmelze['FEHLER'] = $fehler->meldung(701);
				break;
		}
	}
	
	//Templatedaten setzen
	$daten_titanschmelze['VERBRAUCH'] 	= $titanschmelze->getResVerbrauch();
	$daten_titanschmelze['GEWINN'] 		= $titanschmelze->getProduktion();

	//Selectdaten setzen
	$daten_titanschmelze['SELECT'] = "<select name=\"auslastung\">";
	
	//Auslastung des titanschmelzees laden
	$auslastung = $titanschmelze->getAuslastung();
	
	for($i=0; $i <= 100; $i=$i+10 )
	{
		//Daten setzen
		$wert = $i/100;
		
		if( $auslastung == ($i/100) )
		{
			$daten_titanschmelze['SELECT'] .= "<option value=\"$wert\" selected>".$i."%</option>";
		}
		else
		{
			$daten_titanschmelze['SELECT'] .= "<option value=\"$wert\">".$i."%</option>";
		}
	}
	$daten_titanschmelze['SELECT'] .= "</select>";
	
	//Template setzen
	$tpl_titanschmelze->setObject("titanschmelze", $daten_titanschmelze);
	$daten['CONTENT'] .= $tpl_titanschmelze->getTemplate();
}
else 
{
	//Fehlerobjekt erzeugen
	$fehler = new FEHLER($db);
	$daten['CONTENT'] = $fehler->meldung(141);
}

//footer einbinden
require_once("../includes/footer.php");