<?php
//Includes
require("../includes/login_check.php");

/*Gebäudelevel laden*/
$geb_res_level 		= $_SESSION['user']->getGebäudeLevel(24);		//ID 24

/*Überprüfen ob User Gebäude überhaupt hat!*/
if ( $geb_res_level != 0 ) 
{	
	//LAde Forscher
	$forscher = &$_SESSION['user']->getForscher();
	
	//Sind mindestens ein Forscher vorhanden!
	if( $forscher->getForscher() > 0 )
	{
		//Template laden
		$tpl_forschung = new TEMPLATE("templates/".$_SESSION['skin']."/forschung.tpl");
		$daten_forschung = array(
			'FORSCHUNG' => '',
			'KOSTEN' => '',
			'DAUER' => '',
			'TEXT' =>'',
			'STUFE' => '');
		
		/*Wurde Forschung abgebrochen?*/
		if( isset($_GET['stop']) )
		{
			//Lade Forschungsobjekt um später die Baukosten wiederzubekommen!
			$forschung = new FORSCHUNG($_SESSION['user'], $_SESSION['user']->getRasseID());
			$forschung->loadForschung($_GET['stop']);
					
			//Lade evtl. vorhandene Forschung
			$db->query("SELECT t_auftrag.ID_Auftrag FROM t_auftrag, t_auftragforschung WHERE t_auftrag.ID_User = ".$_SESSION['user']->getUserID()." AND t_auftrag.ID_Auftrag = t_auftragforschung.ID_Auftrag AND t_auftrag.ID_Kolonie = ".$_SESSION['kolonie']->getID()." AND t_auftragforschung.ID_Forschung = ".$_GET['stop']."");
			
			//Hat User überhaupt geforscht?
			if( $db->affected_rows() > 0 )	//ja... schreibe ihm Punkte gut!
			{
				//Lösche Forschung
				$id = $db->fetch_result(0);
				$db->query("DELETE FROM t_auftrag WHERE ID_Auftrag = $id");
				$db->query("DELETE FROM t_auftragforschung WHERE ID_Auftrag = $id");
				
				//Hole Baukosten!
				$level = $_SESSION['user']->getForschungsLevel($_GET['stop']);
				$kosten = $forschung->getKosten($level);
				
				//Schreibe User 75% der Rohstoffe wieder gut!
				$forscher = &$_SESSION['user']->getForscher();
				$forscher->setForschungspunkte($kosten + $forscher->getForschungspunkte());
			}
		}		
	
		/*Ist Knopf zum Forschen gedrückt worden?*/
		if( isset($_GET['ID']) )
		{
			//Lade Forschungsobjekt für Bauzeit
			$forschung = new FORSCHUNG($_SESSION['user'], $_SESSION['user']->getRasseID());
			$forschung->loadForschung($_GET['ID']);
					
			//Gebäudelevel ermitteln
			$lvl = $_SESSION['user']->getForschungsLevel($_GET['ID']);
			
			//Forschung einleiten
			$error 		= $to_do->startForschung($_GET['ID'], $_SESSION['kolonie']->getID());
			
			//Fehler beim Forschen??
			if( $error == -1 )		//Wird bereits geforscht
			{
				//Fehlerobjekt erzeugen
				$fehler = new FEHLER($db);
				$daten['CONTENT'] = $fehler->meldung(803);
			}
			elseif( $error == -2 )	//nicht genügend rohstoffe
			{
				//Fehlerobjekt erzeugen
				$fehler = new FEHLER($db);
				$daten['CONTENT'] = $fehler->meldung(804);
			}
			elseif( $error == -3 )	//Requirements werden nicht erfüllt!
			{
				//Fehlerobjekt erzeugen
				$fehler = new FEHLER($db);
				$daten['CONTENT'] = $fehler->meldung(900);
			}
		}
		
		//Lade Forschungsstatus!
		$forschungs_bau	= $to_do->getForschungsBau($_SESSION['kolonie']->getID());
		
		//Lade alle Forschungen
		$forschung = new FORSCHUNG($_SESSION['user'], $_SESSION['user']->getRasseID());
		$forsch_array = $forschung->getForschungsArray();
		
		//wird bereits geforscht?
		if( !empty($forschungs_bau['ID_Forschung']) )
		{
			$es_wird_geforscht = true;
		}
		else 
		{
			$es_wird_geforscht = false;
		}
		
		//Durchlaufe forschugnsarray
		for( $i=1; $i<=count($forsch_array); $i++ )
		{
			//Überprüfen ob array nicht leer ist!
			if( !empty($forsch_array[$i][0]) )
			{
				//Forschungsdaten laden
				$forschung->loadForschung($i);
				
				//Forschungslevel laden
				$level = $_SESSION['user']->getForschungsLevel($i);
				
				//Lade Requirement
				$erfuellt = $forschung->checkRequirement($_SESSION['user'], $_SESSION['kolonie']->getID());
			
				//Kann Forschung geforscht werden?
				if( $erfuellt == 1 )
				{
					//Forscher laden!
					$forscher = &$_SESSION['user']->getForscher();
					
					//Bauzeit in tage, stunden, minuten und sekunden ermitteln
					$bauzeit = $forschung->getFormattedBuildTime($level, $forscher->getForscher());
					
					//Templatelevel-Daten setzen
					if( empty($level) )
					{
						$daten_detail['STUFE'] = "";
					}
					else
					{
						$daten_detail['STUFE'] = "(Stufe: $level)";
					}
							
					//Template-Daten setzen
					$daten_detail['FORSCHUNG'] 	= $forschung->getBezeichnung();
					$daten_detail['KOSTEN']		= $forschung->getKosten($_SESSION['user']->getForschungsLevel($i));
					$daten_detail['DAUER']		= $bauzeit;
					
					//Überprüfen ob Gebäude gebaut wird
					if( $es_wird_geforscht == true )
					{
						//Überprüfen welches gebäude gebaut wird
						if( $forschungs_bau['ID_Forschung'] == $i )
						{
							//Verbleibende Zeit ermitteln!
							$zeit_verbleibend = $forschungs_bau['FinishTime'] - time();
							$daten_detail['TEXT']  = "<div id=\"".$forschungs_bau['ID_Forschung']."\" title=\"".$zeit_verbleibend."\"></div>";
							$daten_detail['TEXT'] .= "<script language=javascript>CountDownForschung(".$forschungs_bau['ID_Forschung'].");</script>";
						}
						else
						{
							$daten_detail['TEXT'] = "-";
						}
					}
					else
					{
						//Leveltemplate setzen
						if( empty($level) )
						{
							$daten_detail['TEXT'] = "<a href=\"forschung.php?ID=".$i."\">forschen</a>";
						}
						else 
						{
							//$level_anzeige = $level+1;
							$daten_detail['TEXT'] = "<a href=\"forschung.php?ID=".$i."\">erweitern</a>";
						}
					}
					
					//Templatedaten ersetzen
					$tpl_forschung->setObject("forschung", $daten_detail);
				}
			}
		}
		//TEmplatedaten setzen
		$daten['CONTENT'] .= $tpl_forschung->getTemplate();
	}
	else //Keine Forscher!
	{
		//Fehlerobjekt erzeugen & Meldung ausgeben!
		$fehler = new FEHLER($db);
		$daten['CONTENT'] = $fehler->meldung(802);
	}
}
else 
{
	//Fehlerobjekt erzeugen
	$fehler = new FEHLER($db);
	$daten['CONTENT'] = $fehler->meldung(141);
}
//footer einbinden
require_once("../includes/footer.php");