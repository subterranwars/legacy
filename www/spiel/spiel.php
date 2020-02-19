<?php
//Includes
require("../includes/login_check.php");

//Template laden
$tpl_overview 			= new TEMPLATE("templates/".$_SESSION['skin']."/overview.tpl");
$daten_detail = array(
	'KOLONIENAME' => '', 
	'KOORDINATEN' => '', 
	'OVERVIEW_DETAIL' => '<table class="overview"><tr><td>keine Ereignisse vorhanden.</td></tr></table>', 
	'MISSION_DETAIL' => '<table class="overview"><tr><td>keine Truppenbewegungen vorhanden.</td></tr></table>',
	'ANZAHL' => 0,
	'NEW_MSGS'=>'',
	'NEW_EREIGNISSE'=>'',
	'ENERGIE'=>'',
	'BILD' => '',
	'RASSE' => '',
	'SKILLPOINTS_LEFT' => '',
	'SKILLPOINTS_USED' => '',);

$tpl_overview_detail 	= new TEMPLATE("templates/".$_SESSION['skin']."/overview_detail.tpl");
$daten_overview = array(
	'WO'=>'', 
	'COUNTDOWN'=>'', 
	'WAS'=>'',);
	
$tpl_mission_detail		= new TEMPLATE("templates/".$_SESSION['skin']."/mission_detail.tpl");
$daten_mission_detail = array(
	'TRUPPE' => '',
	'QUELLE' => '',
	'ZIEL' => '',
	'TYP' => '',
	'COUNTDOWN' => '');

//Schauen ob user neue Nachrichten hat
$db->query("SELECT COUNT(*) FROM t_nachrichten WHERE Status = 'neu' AND ID_User = ".$_SESSION['user']->getUserID()." AND Deleted != 'empfaenger';");
$anz_new_msgs = $db->fetch_result(0);

//SChauen ob neue Ereignisse vorliegen
$db->query("SELECT COUNT(*) FROM t_ereignis WHERE Status = 'neu' AND ID_User = ".$_SESSION['user']->getUserID().";");
$anz_new_ereignisse = $db->fetch_result(0);

//Wenn neue NAchrichten bzw. Ereignisse vorhanden sind, dann muss dies auf der Titelseite angezeigt werden, andernfalls nicht!
if( $anz_new_msgs > 0 || $anz_new_ereignisse > 0 )
{
    //Überprüfen ob keine Ereignisse oder keine Nachrichten vorliegen
    if( $anz_new_msgs > 0 )
    {
        $daten_detail['NEW_MSGS'] = '<tr><th colspan="3"><a href="inbox.php">Sie haben neue Nachrichten</a></th></tr>';
    }
    if( $anz_new_ereignisse > 0 )
    {
        $daten_detail['NEW_EREIGNISSE'] = '<tr><th colspan="3"><a href="ereignisse.php">Sie haben neue Ereignisse</a></th></tr>';
    }
}

/*Aufträge laden*/
$auftraege = $to_do->getAuftraege();

//Ist min 1 Auftrag vorhanden?
if( count($auftraege) > 0 )
{
	//Templatedaten löschen
	$daten_detail['OVERVIEW_DETAIL'] = '';
	
	//Aufträge durchlaufen
	for( $i=0; $i<count($auftraege); $i++ )
	{
		/*Bestimme Kolonie, wo Auftrag stattfindet*/
		$kolo = new KOLONIE($auftraege[$i]['ID_Kolonie'], $db);
		
		//Kategory bestimmen
		switch($auftraege[$i]['Kategory'])
		{
			case 'Gebäude':
				//Daten laden
				$geb_daten = $to_do->getGebBau($_SESSION['kolonie']->getID());
				$geb = new GEBÄUDE();
				$geb->loadGebäude($geb_daten['ID_Gebäude'], $_SESSION['user']->getRasseID());
				//DAten setzen
				$daten_overview['WO'] 			= $kolo->getbezeichnung();
				$daten_overview['COUNTDOWN'] 	= "<div id=\"auftrag".($i+1)."\" title=\"".($geb_daten['FinishTime']-time())."\"></div>";
				$daten_overview['WAS'] 			= "Gebäudebau: ".$geb->getBezeichnung()." (Level: ".($_SESSION['user']->getGebäudeLevel($geb_daten['ID_Gebäude'])+1).")";
				break;
			case 'Forschung':
				//Daten laden
				$forsch_daten = $to_do->getForschungsBau($_SESSION['kolonie']->getID());
				$forschung = new FORSCHUNG();
				$forschung->loadForschung($forsch_daten['ID_Forschung']);
				//Daten setzen
				$daten_overview['WO'] 			= $kolo->getbezeichnung();
				$daten_overview['COUNTDOWN'] 	= "<div id=\"auftrag".($i+1)."\" title=\"".($forsch_daten['FinishTime']-time())."\"></div>";
				$daten_overview['WAS'] 			= "Forschung: ".$forschung->getBezeichnung()." (Level: ".($_SESSION['user']->getForschungsLevel($forsch_daten['ID_Forschung'])+1).")";
				break;	
			case 'Kolonieausbau':
				//Daten setzen
				$daten_overview['WO'] 			= $kolo->getBezeichnung();
				$daten_overview['COUNTDOWN'] 	= "<div id=\"auftrag".($i+1)."\" title=\"".($auftraege[$i]['FinishTime']-time())."\"></div>";
				$daten_overview['WAS'] 			= "update <b>".$kolo->getStatusName()."</b> zur <b>".($kolo->kolonie_status[$kolo->getStatus()+1])."</b>";
				break;
			case 'Einheiten':
				//Bauplan id laden
    			$db->query("SELECT ID_Bauplan, Fertig, Anzahl FROM t_auftrageinheit WHERE ID_Auftrag = ".$auftraege[$i]['ID']."");
    			$daten_bauplan = $db->fetch_array();
    			//Bauplanobjekt erzeugen
    			$bauplan = new BAUPLAN($db, $_SESSION['user'], $_SESSION['kolonie']->getID());
    			$bauplan->loadBauplan($daten_bauplan['ID_Bauplan']);    	
    			//Typ bestimmen
    			$typ = $bauplan->getChassis();		
    			switch($typ)
    			{
    				case 1:
    					$typ = "Infanterist(en)";
    					break;
    				case 2:
    					$typ = "Panzer";
    					break;
    				case 3:
    					$typ = "Mech(s)";
    					break;
    				case 4:
    					$typ = "LKW(s)";
    					break;
    				case 5:
    					$typ = "Truppentransporter";
    					break;
    			}
    			//Daten setzen
				$daten_overview['WO'] 			= $kolo->getBezeichnung();
				$daten_overview['COUNTDOWN'] 	= "<div id=\"auftrag".($i+1)."\" title=\"".($auftraege[$i]['FinishTime']-time())."\"></div>";
				$daten_overview['WAS'] 			= "Ausbildung: <b>".$bauplan->getBezeichnung()."</b> (".$daten_bauplan['Fertig']." von ".($daten_bauplan['Anzahl'] + $daten_bauplan['Fertig']).") $typ";
				break;
			case 'Forscher':
				//Daten setzen
				$daten_overview['WO'] 			= $kolo->getBezeichnung();
				$daten_overview['COUNTDOWN'] 	= "<div id=\"auftrag".($i+1)."\" title=\"".($auftraege[$i]['FinishTime']-time())."\"></div>";
				$daten_overview['WAS'] 			= "es wird 1 Forscher ausgebildet";
				break;
			case 'Vorkommensuche':
				//Lade Vorkommensuche
				$db->query("SELECT ID_Rohstoff, Dauer, FinishReal FROM t_auftragvorkommensuche WHERE ID_Auftrag = ".$auftraege[$i]['ID']."");
				$vorkommen_daten = $db->fetch_array();
				//Lade Rohstoffdaten
				$res = new ROHSTOFF();
				$res->loadRohstoff($vorkommen_daten['ID_Rohstoff']);
				//Daten setzen
				$daten_overview['WO'] 			= $kolo->getBezeichnung();
				$daten_overview['COUNTDOWN'] 	= "<div id=\"auftrag".($i+1)."\" title=\"".($vorkommen_daten['FinishReal']-time())."\"></div>";
				$daten_overview['WAS'] 			= "Vorkommensuche: ".$res->getBezeichnung()." (Dauer: ".$vorkommen_daten['Dauer'].")";
				break;
		}
		//Templatedaten ersetzen
		$tpl_overview_detail->setObject('overview_countdown', $daten_overview);
	}
	$daten_detail['OVERVIEW_DETAIL'] = $tpl_overview_detail->getTemplate();
}

/*Missionen laden*/
$mission = new MISSION($db, $_SESSION['user']);
$missionen = $mission->getMission(time());

//Sind Missionen vorhanden?
if( count($missionen['ID']) > 0 )
{
	//Offset bestimmen, der angibt, wo die Truppenbewegungen beginnen
	$offset = $i;
	
	//Alle missionen durchlaufen und Werte setzen!
	for($i=0; $i<count($missionen['ID']); $i++)
	{
		//Kolonieobjekt erzeugen
		$kolo = new KOLONIE($missionen['ID_KoloSource'][$i], $db);
		$kolo2 = new KOLONIE($missionen['ID_KoloDest'][$i], $db);
				
		//Templatedaten setzen
		$daten_mission_detail['QUELLE'] = $kolo->getKoordinaten();
		$daten_mission_detail['ZIEL'] 	= $kolo2->getKoordinaten();
		$daten_mission_detail['COUNTDOWN'] = "<div id=\"auftrag".($i+$offset+1)."\" title=\"".($missionen['Time'][$i]-time())."\"></div>";
		$daten_mission_detail['TYP'] 	= $missionen['Parameter'][$i];
		$daten_mission_detail['TRUPPE'] = $missionen['Flotte'][$i];
		$daten_mission_detail['CLASS']	= $missionen['Color'][$i];
		
		//Templatedaten setzen
		$tpl_mission_detail->setObject("mission_countdown", $daten_mission_detail);
	}
	
	//Templatedaten ersetzen!
	$daten_detail['MISSION_DETAIL'] = $tpl_mission_detail->getTemplate();
	
	//Kolonieobjekt löschen
	unset($kolo);
	unset($kolo2);
}

/*Energieniveau überprüfen*/
if( $_SESSION['kolonie']->getEnergieniveau() == 'critical' )
{
	$daten_detail['ENERGIE'] = '<tr><td colspan="3" class="error">Energieniveau kritisch!</td></tr>';
}

//Koloniedaten setzen
$rasse = new RASSE($_SESSION['user']->getRasseID());
$daten_detail['KOLONIENAME'] = $_SESSION['kolonie']->getBezeichnung();
$daten_detail['KOORDINATEN'] = $_SESSION['kolonie']->getKoordinaten();
$daten_detail['ANZAHL']		 = count($auftraege) + count($missionen['ID']);
$daten_detail['RASSE']		 = $rasse->getBezeichnung();

//Weitere wichtige Daten setzen!
$general = &$_SESSION['user']->getGeneral();
$daten_detail['SKILLPOINTS_LEFT'] = $general->getSkillPointsLeft();
$daten_detail['SKILLPOINTS_USED'] = $general->getUsedSkillPoints();

//Avatar setzen!
$avatar = $_SESSION['user']->getAvatar();
if( empty($avatar) || $avatar == "" )
{
	$daten_detail['BILD'] = '<a href="einstellungen.php">Kein Avatar gewählt</a>';
}
else 
{
	$daten_detail['BILD'] = "<img src=\"cache/avatar/".$_SESSION['user']->getAvatar()."\">";
}

//Templatedaten setzen
$tpl_overview->setObject("overview", $daten_detail);
$daten['CONTENT'] = $tpl_overview->getTemplate();

//footer einbinden
require_once("../includes/footer.php");?>