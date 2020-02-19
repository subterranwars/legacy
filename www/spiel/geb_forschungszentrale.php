<?php
//Includes
require("../includes/login_check.php");

/*Gebäudelevel laden*/
$geb_res_level 		= $_SESSION['user']->getGebäudeLevel(24);		//Gebäude hat die ID 

/*Überprüfen ob User Gebäude überhaupt hat!*/
if ( $geb_res_level != 0 ) 
{		
	//Template laden
	$tpl_forschungszentrale = new TEMPLATE("templates/".$_SESSION['skin']."/gebäude/forschungszentrale.tpl");
	$daten_forschungszentrale = array(
		'FORSCHER' => '',
		'PUNKTE' => '',
		'PRODUKTION' => '',
		'KOSTEN' => '',
		'DAUER' => '',
		'FEHLER' => '',
		'AUSBILDUNG' => 'Zur Zeit werden keine Forscher ausgebildet');
	
	$tpl_ausbildung = new TEMPLATE("templates/".$_SESSION['skin']."/gebäude/forschungszentrale_ausbildung.tpl");
	$daten_ausbildung = array(
		'TIME' => '');
			
	//ForscherObjekt erzeugen
	$forscher = &$_SESSION['user']->getForscher();	
	
	/*überprüfen ob ForscherAusbildung abgebrochen werden soll*/
	if( isset($_GET['stop'])	)	//Forschung abbrechen
	{
		//ÜBerprüfen ob User baut
		$finish_time = $to_do->getForscherAusbildung();
		
		//Bildet User aus?
		if( $finish_time != 0 )	//ja
		{
			//Schreibe Nahrung gut
			$kosten = $forscher->getForscherKosten($_SESSION['user']->getGebäudeLevel(24));
			$_SESSION['user']->setRohstoffAnzahl($kosten,3);
			
			//Füge Bevölkerung hinzu
			$_SESSION['bevoelkerung']->setBevölkerung(1);
			
			//Lösche Ausbildung
			$db->query("DELETE FROM t_auftrag WHERE ID_User = ".$_SESSION['user']->getUserID()." AND ID_Kolonie = ".$_SESSION['kolonie']->getID()." AND Kategory = 'Forscher';");
		}
	}
	
	/*überprüfen ob Forscher ausgebildet werden soll!*/
	if( isset($_POST['send']) )
	{
		//Forscher asubilden
		$error = $to_do->startForscherAusbildung();
		
		//Sind Fehler aufgetreten?
		if( $error <= -1 )
		{
			//FEhler-Objekt erzeugenr
			$fehler = new FEHLER($db);
			
			//Fehlerdurchlaufen
			switch($error)
			{
				case -1:
					$daten_forschungszentrale['FEHLER'] = $fehler->meldung(800);
					break;
				case -2;
					$daten_forschungszentrale['FEHLER'] = $fehler->meldung(805);
					break;
				case -3;
					$daten_forschungszentrale['FEHLER'] = $fehler->meldung(801);
					break;
			}
		}
	}
	
	//Wird ausgebildet?
	$finish_time = $to_do->getForscherAusbildung();
	if( !empty($finish_time) )
	{
		//Templatedaten der Ausbildung setzen!
		$daten_ausbildung['TIME']  = "<div id=\"1\" title=\"".($finish_time-time())."\"></div>";
		$daten_ausbildung['TIME'] .= "<script language=javascript>CountDownForscher(1);</script>";
		
		//Templatedaten ersetzen
		$tpl_ausbildung->setObject("ausbildung", $daten_ausbildung);
		$daten_forschungszentrale['AUSBILDUNG'] = $tpl_ausbildung->getTemplate();
	}
	
	//Template daten setzen!
	$daten_forschungszentrale['FORSCHER'] 	= $forscher->getForscher();
	$daten_forschungszentrale['PUNKTE']		= $forscher->getForschungspunkte();
	$daten_forschungszentrale['PRODUKTION']	= $forscher->getForschungspunkteProduktion();	
	$daten_forschungszentrale['KOSTEN']		= $forscher->getForscherKosten($_SESSION['user']->getGebäudeLevel(24));
	$daten_forschungszentrale['DAUER']		= $forscher->getFormattedAusbildungsZeit($_SESSION['user']->getGebäudeLevel(24));
		
	
	//Template setzen
	$tpl_forschungszentrale->setObject("forschungszentrale", $daten_forschungszentrale);
	$daten['CONTENT'] .= $tpl_forschungszentrale->getTemplate();
}
else 
{
	//Fehlerobjekt erzeugen
	$fehler = new FEHLER($db);
	$daten['CONTENT'] = $fehler->meldung(141);
}
//footer einbinden
require_once("../includes/footer.php");