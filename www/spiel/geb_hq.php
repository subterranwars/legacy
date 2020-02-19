<?php
//Includes
require("../includes/login_check.php");

/*Gebäudelevel laden*/
$geb_res_level 		= $_SESSION['user']->getGebäudeLevel(1);		//HQ hat die ID 1

/*Überprüfen ob User Gebäude überhaupt hat!*/
if ( $geb_res_level != 0 ) 
{	
	//Template laden
	$tpl_hq = new TEMPLATE("templates/".$_SESSION['skin']."/gebäude/hq.tpl");
	$daten_hq = array(
		'AKTUELLE_STUFE' => '',
		'NAECHSTE_STUFE' => '',
		'REQUIREMENT' => '',
		'DAUER' => '',
		'KOSTEN' => '',
		'BAU' => '',
		'FEHLER' => '');
		
	//Kolonieobjekt der aktuellen Stufe erzeugen!
	$kolo_status = $_SESSION['kolonie']->getStatus();
	//Setze aktuelle Stufe
	$daten_hq['AKTUELLE_STUFE'] = $_SESSION['kolonie']->getStatusName();
	
	/*Wurde der Kolonieausbau abgebrochen?*/
	if( isset($_GET['stop']) AND $_GET['stop'] = 'true' )	//ja
	{
		//Lade KolonieAusbau
		$kolo_daten = $to_do->getKolonieausbau($_SESSION['kolonie']->getID());
		
		//Hat der User überhaupt ein Kolonieupgrade im Bau?
		if( !empty($kolo_daten['ID_Auftrag']) )	//KolonieUpgrade vorhanden
		{			
			//Durchlaufe Kosten!
			$kosten = $_SESSION['kolonie']->getKosten();
			for($i=0; $i<count($kosten); $i++)
			{
				//Kostenarray zerlegen => id|anzahl
				$kosten_array = explode("|", $kosten[$i]);
				
				//Anzahl setzen!
				$_SESSION['user']->setRohstoffAnzahl($kosten_array[1], $kosten_array[0]);
				
				//Auftrag löschen
				$db->query("DELETE FROM t_auftrag WHERE  ID_Auftrag = ".$kolo_daten['ID_Auftrag']."");
				$db->query("DELETE FROM t_auftragkolonie WHERE ID_Auftrag = ".$kolo_daten['ID_Auftrag']."");
			}
		}
	}
		
	
	/*Wurde erweitern Button gedrückt?*/
	if( isset($_POST['send']) )
	{
		//Kolonie erweitern
		$error = $to_do->startKolonieAusbau();
		
		//Sind Fehler bei der Erweiterung aufgetreten?
		if( $error < 1 )
		{
			//Fehlerobjekt erzeugen
			$fehler = new FEHLER($db);
			
			//Welcher Fehler?
			switch($error)
			{
				//Wird bereits gebaut
				case -1:
					$daten_hq['FEHLER'] = $fehler->meldung(140);
					break;
				//Die Requirements werden nicht erfüllt!
				case -2:
					$daten_hq['FEHLER'] = $fehler->meldung(900);
					break;
				//Zu wenig Rohstoffe
				case -3:
					$daten_hq['FEHLER'] = $fehler->meldung(142);
					break;
			}
		}	
	}
	
	//Überprüfen, dass nicht höchstmöglichste Level erreicht wurde!
	if( ($_SESSION['kolonie']->getStatus() + 1) <= count($_SESSION['kolonie']->kolonie_status) )
	{
		//Setze Templatedatne
		$daten_hq['NAECHSTE_STUFE'] = $_SESSION['kolonie']->kolonie_status[$kolo_status+1];	//Nächstes Level
		$daten_hq['DAUER']			= $_SESSION['kolonie']->getFormattedBuildTime();
		
		//Lade requirements
		$req 	= $_SESSION['kolonie']->getRequirement();		
		
		//Durchlaufe requirement
		for( $i=0; $i<count($req); $i++ )
		{
			//Zerteile requirement
			$req_detail = explode("|", $req[$i]);
			
			//Überprüfe ob vorhanden
			switch($req_detail[0])
			{
				//Forschung
				case 2:
					break;
				//Gebäude
				case 3:
					//LVl ermitteln
					$lvl = $_SESSION['user']->getGebäudeLevel($req_detail[1]);
					//Name setzen!
					$geb = new GEBÄUDE();
					$geb->loadGebäude($req_detail[1], $_SESSION['user']->getRasseID());
					$req_detail[1] = $geb->getBezeichnung();
					break;
				//Bewohner
				case 4:
					//Bevölkerung laden
					$lvl = $_SESSION['bevoelkerung']->getAnzahl();
					//Name setzen
					$req_detail[1] = "Bevölkerung: ";
					break;
			}
			//Überprüfen ob User voraussetzung erfüllt
			if( $lvl >= $req_detail[2] )
			{
				$daten_hq['REQUIREMENT'] .= "<li>
												<div class=\"green\">".$req_detail[1]." (Level: ".$req_detail[2].")</div>
											</li>";
			}
			else 
			{
				$daten_hq['REQUIREMENT'] .= "<li>
												<div class=\"red\">".$req_detail[1]." (Level: ".$req_detail[2].")</div>
											</li>";	
			}
		}
		
		//Durchlaufe Kosten!
		$kosten = $_SESSION['kolonie']->getKosten();
		for($i=0; $i<count($kosten); $i++)
		{
			//Kostenarray zerlegen => id|anzahl
			$kosten_array = explode("|", $kosten[$i]);
			
			//Rohstoffobjekt erzeugen
			$rohstoff = new ROHSTOFF();
			$rohstoff->loadRohstoff($kosten_array[0]);
					
			//HAt user Anzahl?
			if( $_SESSION['user']->getRohstoffAnzahl($kosten_array[0]) >= $kosten_array[1] )
			{
				$daten_hq['KOSTEN'] .= "<div class=\"green\">".$rohstoff->getBezeichnung().": $kosten_array[1]</div>";
			}
			else 
			{
				$daten_hq['KOSTEN'] .= "<div class=\"red\">".$rohstoff->getBezeichnung().": $kosten_array[1]</div>";
			}
		}
	}
		
	/*überprüfen ob Kolonieausbau bereits läuft*/
	$kolo_daten = $to_do->getKolonieausbau($_SESSION['kolonie']->getID());
	if( !empty($kolo_daten['ID_Auftrag'])  )
	{
		//Verbleibende Zeit ermitteln!
		$zeit_verbleibend = $kolo_daten['FinishTime'] - time();
		$daten_hq['DAUER'] = "<div id=\"kolonieupgrade\" title=\"".$zeit_verbleibend."\"></div>";
		$daten_hq['BAU'] .= "<div id=\"kolonieupgrade2\"><script language=javascript>CountDownKolonie();</script></div>";
	}
	else //kein Ausbau vorhanden
	{
		$daten_hq['BAU'] = '<input type="submit" name="send" value="ausbauen">';
	}
	
	//Template setzen
	$tpl_hq->setObject("hq", $daten_hq);
	$daten['CONTENT'] .= $tpl_hq->getTemplate();
}
else 
{
	//Fehlerobjekt erzeugen
	$fehler = new FEHLER($db);
	$daten['CONTENT'] = $fehler->meldung(141);
}

//footer einbinden
require_once("../includes/footer.php");