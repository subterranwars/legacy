<?php
//Includes
require("../includes/login_check.php");

/*Gebäudelevel laden*/
$geb_res_level 		= $_SESSION['user']->getGebäudeLevel(19);		//ID des Brutreaktors

/*Überprüfen ob User Gebäude überhaupt hat!*/
if ( $geb_res_level != 0 ) 
{	
	//Template laden
	$tpl_brutreaktor = new TEMPLATE("templates/".$_SESSION['skin']."/gebäude/brutreaktor.tpl");
	$daten_brutreaktor = array(
		'VERBRAUCH' => '',
		'GEWINN' => '',
		'ENERGIE' => '',
		'SELECT' => '',
		'FEHLER' => '');
	
	//brutreaktorObjekt erzeugen
	$brutreaktor = new BRUTREAKTOR($db, $_SESSION['user'], $_SESSION['kolonie']->getID(), $_SESSION['kolonie']->getEnergieniveau());

	/*Überprüfen ob Knopp gedrückt wurde!*/
	if( isset($_POST['send']) )
	{
		//Neue Auslastung setzen
		$error = $brutreaktor->setAuslastung($_POST['auslastung']);
		
		//Fehler abfangen
		switch($error)
		{			
			//Wert zu klein!
			case -1:
				//Fehlerobjekt erzeugen	
				$fehler = new FEHLER($db);
				$daten_brutreaktor['FEHLER'] = $fehler->meldung(700);
				break;
			//Wert zu groß!
			case -2:
				//Fehlerobjekt erzeugen
				$fehler = new FEHLER($db);
				$daten_brutreaktor['FEHLER'] = $fehler->meldung(701);
				break;
		}
	}
	
	//Templatedaten setzen
	$daten_brutreaktor['VERBRAUCH'] 	= $brutreaktor->getResVerbrauch() + $brutreaktor->getResVerbrauchEnergie();
	$daten_brutreaktor['GEWINN'] 		= $brutreaktor->getProduktion();
	$daten_brutreaktor['ENERGIE']		= $brutreaktor->getProduktionEnergie();
	
	//Auslastung des brutreaktores laden
	$auslastung = $brutreaktor->getAuslastung();
	
	//Selectdaten setzen
	$daten_brutreaktor['SELECT'] = "<select name=\"auslastung\">";
	
	for($i=0; $i <= 100; $i=$i+10 )
	{
		//Daten setzen
		$wert = $i/100;
		
		if( $auslastung == ($i/100) )
		{
			$daten_brutreaktor['SELECT'] .= "<option value=\"$wert\" selected>".$i."%</option>";
		}
		else
		{
			$daten_brutreaktor['SELECT'] .= "<option value=\"$wert\">".$i."%</option>";
		}
	}
	$daten_brutreaktor['SELECT'] .= "</select>";
	
	//Template setzen
	$tpl_brutreaktor->setObject("brutreaktor", $daten_brutreaktor);
	$daten['CONTENT'] .= $tpl_brutreaktor->getTemplate();
}
else 
{
	//Fehlerobjekt erzeugen
	$fehler = new FEHLER($db);
	$daten['CONTENT'] = $fehler->meldung(141);
}

//footer einbinden
require_once("../includes/footer.php");