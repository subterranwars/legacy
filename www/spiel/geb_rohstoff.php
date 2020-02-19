<?php
//Includes
require("../includes/login_check.php");

/*Anzahl Laster setzen*/
$laster_per_level	= 5;
$geb_res_level 		= $_SESSION['user']->getGebäudeLevel(7);	//Rohstoffgebäude ID ist 7
$anzahl_drohnen 	= $laster_per_level * $geb_res_level;

/*Überprüfen ob User Gebäude überhaupt hat!*/
if ( $geb_res_level != 0 ) 
{	
	//Template laden
	$tpl_res = new TEMPLATE("templates/".$_SESSION['skin']."/gebäude/rohstoffgebäude.tpl");
	$daten_res = array(
		'VORKOMMEN_ANZAHL' => '',
		'AUSWAHL' => '',
		'FEHLER' => '',
		'DROHNEN_ANZAHL' => '', 
		'DROHNEN_USED' => '', 
		'DROHNEN_UNUSED' => '',
		'VORKOMMEN' => 'Keine Vorkommen vorhanden.',
		'VORKOMMEN_SUCHE' => 'Es werden zur Zeit keine Vorkommen gesucht.');
		
	$tpl_vorkommen = new TEMPLATE("templates/".$_SESSION['skin']."/gebäude/rohstoffgebäude_vorkommen.tpl");	
	$daten_vorkommen = array(
		'TOPIC' => '',
		'ROHSTOFF' => '',
		'SIZE' => '', 
		'VERBLEIBEND' => '',
		'JS_ID' => '',
		'LAST_CHANGE' => '',
		'SELECT' => '',
		'ID' => '',
		'CLASS' => '',
		'DROHNEN' => '');
		
	$tpl_vorkommen_suche = new TEMPLATE("templates/".$_SESSION['skin']."/gebäude/rohstoffgebäude_vorkommensuche.tpl");
	$daten_vorkommen_suche = array(
		'ROHSTOFF' => '',
		'DAUER' => '',
		'ENDE' => '');
				
	//Welche Rohstoffe sollen gesucht werden können?		
	$res_suche = array(1,2,4,7,9,11,13,14);
	
	/*überprüfen ob button zum Vorkommensuchen gedrückt wurde*/
	if( isset($_POST['send']) )
	{
		//Überprüfen ob Dauer nicht <= 0 ist!
		if( $_POST['dauer'] <= 0 )
		{
			//Fehler !
			$fehler = new FEHLER($db);
			$daten_res['FEHLER'] = $fehler->meldung(134);
		}
		else 
		{		
			//Vorkommensuche einleiten	
			$error = $to_do->searchVorkommen($_POST['rohstoff'], $_POST['dauer']);
			
			//Fehler überprüfung
			if( $error == -1 )
			{
				//Fehler objekt erzeugen
				$fehler = new FEHLER($db);
				$daten_res['FEHLER'] = $fehler->meldung(132);
			}
			elseif( $error == -2 )	//Requirements werden nicht erfüllt!
			{
				//Fehler objekt erzeugen
				$fehler = new FEHLER($db);
				$daten_res['FEHLER'] = $fehler->meldung(133);
			}
		}
	}
	
	/*Überprüfen ob User Vorkommen löschen möchte!*/
	if( isset($_POST['del_vorkommen']) )
	{
		//Durchlaufe alle Vorkommen, die glöescht werden sollen
		for( $i=0; $i<count($_POST['del']); $i++ )
		{
			$db->query("DELETE FROM t_vorkommen WHERE ID_Vorkommen = ".$_POST['del'][$i]." AND ID_User = ".$_SESSION['user']->getUserID()."");
		}
		
		//Lade Vorkommen neu
		$_SESSION['user']->loadVorkommen($_SESSION['kolonie']->getID());
	}
			
	/*Lade alle Vorkommen*/
	$vorkommen = &$_SESSION['user']->getVorkommen();
	
	//DRohnenanzahl dieses Vorkommens laden
	$benutzte_drohnen	= 0;
	
	//Vorkommen durchlaufen
	$last_rohstoff = "";	//Welcher Rohstoff war vom letzten Vorkommen?
	for( $i=0; $i<count($vorkommen); $i++ )
	{	
		//Überschrift setzen, aber nur, wenn ID_Rohstoff != $last_rohstoff
		if( $last_rohstoff != $vorkommen[$i]->getRohstoffID() )
		{
			$last_rohstoff = $vorkommen[$i]->getRohstoffID();
			$daten_vorkommen['TOPIC'] = $vorkommen[$i]->getRohstoffBezeichnung();
		}
		else 
		{
			$daten_vorkommen['TOPIC'] = "";
		}
		
		//VAriable auf 0 setzen!
		$daten_vorkommen['FEHLER'] = "";
				
		/*Überprüfen ob Neue Anzahl von Drohnen gesetzt werden soll?!
		Lade alle noch zur Verfügung stehenden Drohnen!*/
		if( isset($_POST['send_drohnen']) )
		{
			//Wurden änderungen vorgenommen?
			if( $vorkommen[$i]->getAnzahlLaster() != $_POST['drohnen'][$i] )	//ja
			{	
				$vorkommen_changed = "yes";
			}
			else 
			{
				$vorkommen_changed = "no";
			}
							
			//Neue Drohnenanzahl setzen
			$error = $vorkommen[$i]->setAnzahlLaster($_POST['drohnen'][$i], ( $anzahl_drohnen - $benutzte_drohnen) );
			
			//Nur wenn Änderungen vorgenommen wurden Meldung ausgeben und Timer ändern
			if( $vorkommen_changed == "yes" )
			{
				//Überprüfen ob Fehler aufgetreten sind?
				if( $error < 1 ) 
				{
					//Fehler objekt erzeugen
					$fehler = new FEHLER($db);
					
					//Fehler durchlaufen
					switch( $error )
					{
						//Änderungen nur jede h möglich
						case -1:
							$daten_vorkommen['FEHLER'] = $fehler->meldung(131);
							break;
						//zu viele Drohnen
						case -2:
							$daten_vorkommen['FEHLER'] = $fehler->meldung(130);
							break;
					}
				}
				else //Kein Fehler
				{
					//Aktualliseiere LastChange
					$vorkommen[$i]->setLastChangeDrohnen();
				}
			}
							
			//Aktuallisiere benutzte Drohnen!
			$benutzte_drohnen += $vorkommen[$i]->getAnzahlLaster();
		}
				
		//Welche Klasse hat die Spalte Verbleibend?
		if( $vorkommen[$i]->getSizeLeft() <= ($vorkommen[$i]->getSize() * 0.25) )
		{
			$daten_vorkommen['CLASS'] = "red";
		}
		elseif( $vorkommen[$i]->getSizeLeft() <= ($vorkommen[$i]->getSize() * 0.70) )
		{
			$daten_vorkommen['CLASS'] = "orange";
		}
		else 
		{
			$daten_vorkommen['CLASS'] = "green";
		}
		
		//Template-Daten holen
		$daten_vorkommen['ROHSTOFF'] 	= $vorkommen[$i]->getRohstoffBezeichnung();
		$daten_vorkommen['SIZE'] 		= number_format($vorkommen[$i]->getSize(), 0, ",",".");
		$daten_vorkommen['VERBLEIBEND'] = number_format($vorkommen[$i]->getSizeLeft(), 0, ",", ".");
		$daten_vorkommen['JS_ID']		= "vorkommen".($i+1);
		$daten_vorkommen['ID']			= $vorkommen[$i]->getID();
		$daten_vorkommen['LAST_CHANGE'] = ($vorkommen[$i]->getLastChangeDrohnen() + $vorkommen[$i]->LastChangeCount) - time();
		$daten_vorkommen['DROHNEN']		= $vorkommen[$i]->getAnzahlLaster();
		
		//SelectAnweisung setzen!
		$daten_vorkommen['SELECT'] = "<select name=\"drohnen[]\">";
		for($a=0; $a <= $vorkommen[$i]->maxLasterPerVorkommen; $a++)
		{
			if( $a == $vorkommen[$i]->getAnzahlLaster() )
			{
				$daten_vorkommen['SELECT'] 	.= "<option value=\"$a\"  selected>$a</option>";
			}
			else 
			{
				$daten_vorkommen['SELECT'] 	.= "<option value=\"$a\">$a</option>";
			}
		}
		$daten_vorkommen['SELECT'] .= "</select>";
				
		//Template ersetzen
		$tpl_vorkommen->setObject("vorkommen", $daten_vorkommen);
	}
	
	/*Erzeuge Daten für die neue vorkommenssuche*/		
	//Auswahlmenü erzeugen
	$daten_res['AUSWAHL'] = "<select name=\"rohstoff\">";
	foreach( $res_suche as $x )
	{
		//Rohstoffobjekt laden
		$rohstoff = new ROHSTOFF();
		$rohstoff->loadRohstoff($x);
		
		//Erfüllt User requirements?
		if( $rohstoff->checkRequirement($_SESSION['user'], $_SESSION['kolonie']->getID()) == 1 )
		{
			$daten_res['AUSWAHL'] .= "<option value=\"$x\">".$rohstoff->getBezeichnung()."</option>";
		}
	}
	$daten_res['AUSWAHL'] .= "</select>";
	
	//Drohnendaten setzen!
	$daten_res['DROHNEN_ANZAHL'] 	= $anzahl_drohnen;
	$daten_res['DROHNEN_USED'] 		= $_SESSION['user']->getUsedDrohnen($_SESSION['kolonie']->getID());
	$daten_res['DROHNEN_UNUSED'] 	= $anzahl_drohnen - $_SESSION['user']->getUsedDrohnen($_SESSION['kolonie']->getID());
	
	//Vorkommenanzahl setzen
	$daten_res['VORKOMMEN_ANZAHL'] = count($vorkommen);
	
	/*Durchlaufe alle Vorkommenssuchen!*/
	$db->query("SELECT ID_Rohstoff, Dauer, FinishReal FROM t_auftrag, t_auftragvorkommensuche WHERE t_auftrag.ID_Auftrag = t_auftragvorkommensuche.ID_Auftrag AND ID_User = ".$_SESSION['user']->getUserID()." AND ID_Kolonie = ".$_SESSION['kolonie']->getID()."");
	while( $row = $db->fetch_array() )
	{
		//Rohstoffobjekt erzeuge
		$rohstoff = new ROHSTOFF();
		$rohstoff->loadRohstoff($row['ID_Rohstoff']);
		
		//Templatedaten vorbereiten
		$daten_vorkommen_suche['ROHSTOFF'] 	= $rohstoff->getBezeichnung();
		$daten_vorkommen_suche['DAUER']		= $row['Dauer'];
		$daten_vorkommen_suche['ENDE']		= date("D, d.m.Y - H:i:s",$row['FinishReal']);
		
		//Templatedaten ersetzen
		$tpl_vorkommen_suche->setObject("vorkommen_suche", $daten_vorkommen_suche);
	}
	
	//Templatedaten ersetzen
	if( count($vorkommen) > 0 )
	{
		$daten_res['VORKOMMEN'] = $tpl_vorkommen->getTemplate();
	}
	if( $db->num_rows() > 0 )
	{
		$daten_res['VORKOMMEN_SUCHE'] = $tpl_vorkommen_suche->getTemplate();
	}
	$tpl_res->setObject("rohstoffgeb", $daten_res);
	$daten['CONTENT'] .= $tpl_res->getTemplate();
}
else 
{
	//Fehlerobjekt erzeugen
	$fehler = new FEHLER($db);
	$daten['CONTENT'] = $fehler->meldung(141);
}

//footer einbinden
require_once("../includes/footer.php");