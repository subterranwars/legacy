<?php
//Includes
require("../includes/login_check.php");

//Type setzen
/*Überprüfen welcher Techtree angezeigt werden soll*/
switch( $_GET['type'] )
{
	default:
	case 1:
		$tpl_techtree = new TEMPLATE("templates/".$_SESSION['skin']."/techtrees/dorf/dorf.tpl");
		$min_id = 1;
		$max_id = 13;
		$type=1;
		break;
	case 2:
		$tpl_techtree = new TEMPLATE("templates/".$_SESSION['skin']."/techtrees/kleinstadt/kleinstadt.tpl");
		$min_id = 16;
		$max_id = 44;
		$type=2;
		break;
	case 3:
		$tpl_techtree = new TEMPLATE("templates/".$_SESSION['skin']."/techtrees/stadt/stadt.tpl");
		$min_id = 51;
		$max_id = 95;
		$type = 3;
		break;
	case 4:
		$tpl_techtree = new TEMPLATE("templates/".$_SESSION['skin']."/techtrees/grossstadt/grossstadt.tpl");
		$min_id = 96;
		$max_id = 137;
		$type = 4;
		break;
	case 5:
		$tpl_techtree = new TEMPLATE("templates/".$_SESSION['skin']."/techtrees/metropole/metropole.tpl");
		$min_id = 138;
		$max_id = 173;
		$type = 5;
		break;
}

//GebäudeIDs welche gefunden werden können
$gebäude_ids[1] = array(
	15,	//Rohstofflager
	29,	//Kaserne
	30	//VErteidigungsministerium
	);

$gebäude_ids[2] = array(
	10,	//Schmelze
	11,	//Chemiefabrik
	13,	//Titanschmelze
	31	//Waffenfabrik
	);
	
$gebäude_ids[3] = array(
	12,
	16,
	18,
	27,
	35
	);
	
$gebäude_ids[4] = array(
	32,
	19,
	25
	);
	
$gebäude_ids[5] = array(
	26
	);
	
//Teile IDs welche erforscht werden können
$teile_ids[1] = array(
	1,	//Dorfmiliz
	2,	//Musketier
	13,	//Flinte
	33,	//Lederweste
	34, //LederWeste PLUS
	53,	//Standard-Patrone
	14,	//GatlingGun
		//Kanone VM
	52,	//Einfache Patrone
	51	//Schiesspulver
	);

$teile_ids[2] = array(
	35,	//Uniform mit Helm
	3,	//Schütze
		//Tunnelsysteme
	20,	//Panzerabwehrgewehr
	41,
	4,	//Soldat
		//Ress-Techs
		//Auto-Kanone
	54,	//Munitionsketten
	126,//Mörsergranaate
	55,	//Vollmantelgeschoss
	115,//Panzergranate
	56,	//Stahlkerngeschoss
	116,//Schwere Panzergranate
	15,	//Maschinenpistole
	107,//Leichte Haubitze
		//P.A.K
	101,//20mm BK
	102,//30mm BK
	103,//90mm BK
	69,	//Kettenpanzer
	70,	//Tank
	21,	//Panzerfaust
	60,	//ungelenkte Rakete
	135,//Fahrzeug-Stahlpanzerung
	136,//Gehärte Fahrzeugpanzerung
	40,	//Schutzweste
	41,	//Schutzweste PLUS
	36,	//Kevlarweste
	42,	//Schwere Schutzweste
	83,	//Verbrennungsmotor MK2
	84,	//Verbrennungsmotor MK1
	210	//kleiner LKW
	);
	
	
$teile_ids[3] = array(
	213,
	151,
	152,
	153,
	5,
	6,
	117,
	127,
	203,
	205,
	95,
	171,
	172,
	16,
	17,
	104,
	108,
	177,
	187,
	118,
	137,
	188,
	186,
	57,
	138,
	211,
	71,
	131,
	198,
	132,
	162,
	37,
	38,
	43,
	161,
	85,
	62,
	190,
	123,
	36,
	22,
	61,
	97,
	180,
	26,
	86	
	);
	
$teile_ids[4] = array(
	7,
	154,
	155,
	58,
	87,
	182,
	174,
	164,
	192,
	119,
	59,
	189,
	88,
	139,
	204,
	178,
	109,
	140,
	96,
	23,
	18,
	39,
	44,
	45,
	206,
	163,
	105,
	120,
	212,
	72,
	98,
	27,
	173,
	191,
	128,
	28,
	181,
	63,
	124,
	199,
	133,
	200
	);
	
$teile_ids[5] = array	(
	8,
	156,
	74,
	47,
	157,
	193,
	183,
	19,
	176,
	24,
	25,
	106,
	179,
	100,
	46,
	121,
	122,
	141,
	207,
	202,
	73,
	64,
	89,
	125,
	90,
	194,
	134,
	99,
	175,
	201,
	165,
	166
	);
	

//Erzeuge Forschungsobjekt
$forschung = new FORSCHUNG();
$daten_techtree['FEHLER'] = '';				//Fehlervariabel löschen.

/*Ist Knopf zum Forschen gedrückt worden?*/
if( isset($_GET['ID']) )
{
	//Lade Forschungsdaten
	$forschung->loadForschung($_GET['ID']);
			
	//Gebäudelevel ermitteln
	$lvl = $_SESSION['user']->getForschungsLevel($_GET['ID']);
	
	//Forschung einleiten
	$error 		= $to_do->startForschung($_GET['ID'], $_SESSION['kolonie']->getID());
	
	//Sind Fehler beim Forschen aufgetreten??
	if( $error <= -1 )	//Fehler aufgetreten
	{
		//Fehlerobjekt erzeugen
		$fehler = new FEHLER($db);
		
		//Fehlercode durchlaufen
		switch($error)
		{
			//Wird bereits geforscht
			case -1:
				$daten_techtree['FEHLER'] = "<script language=\"JavaScript\">PopUp('techtree_error.php?code=803', 200, 250);</script>";
				break;
			//nicht genügend Forschungspunkte
			case -2:
				$daten_techtree['FEHLER'] = "<script language=\"JavaScript\">PopUp('techtree_error.php?code=804', 200, 250);</script>";	
				break;
			//Requirements werden nicht erfüllt!
			case -3:
				$daten_techtree['FEHLER'] = "<script language=\"JavaScript\">PopUp('techtree_error.php?code=900', 200, 250);</script>";
				break;
		}
	}
}
	
/*Wurde Forschung abgebrochen?*/
if( isset($_GET['stop']) )
{
	//Lade Forschungsobjekt um später die Baukosten wiederzubekommen!
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

//Lade Forschungen die evtl. im Bau sind!
$forsch_array 	= $to_do->getForschungsBau($_SESSION['kolonie']->getID());		

//Nun alle Forschungen durchlaufen
for( $i=$min_id; $i<=$max_id; $i++ )
{
	//Forschungsobjekt erzeugen
	$forschung->loadForschung($i);
	
	//Level laden
	$level 			= $_SESSION['user']->getForschungsLevel($i);
	$kosten 		= $forschung->getKosten($level);
	$forscher		= &$_SESSION['user']->getForscher();
	$bauzeit 		= $forschung->getFormattedBuildTime($level, $forscher->getForscher());
	$bezeichnung 	= $forschung->getBezeichnung();
	$beschreibung 	= $forschung->getBeschreibung();
		
	//Bezeichnung kürzen
	if( strlen($bezeichnung) > 20 )
	{
		$bez_titel = substr($bezeichnung, 0, 17)."...";
	}
	else 		//Kürzen nicht von nöten!
	{
		$bez_titel = $bezeichnung;
	}
	
	/*Anzeigeklasse bestimmen*/
	if( $forschung->checkRequirement($_SESSION['user'], $_SESSION['kolonie']->getID()) == 1 )
	{
		$class = 'green';		//User erfüllt Requirements
	}
	else 
	{
		$class = 'red';			//User erfüllt Requirements nicht
	}
	
	//Ausgabe setzen
	$text = "<table><tr><td colspan=2>$beschreibung</td></tr><tr><td colspan=2><hr size=1></td></tr><tr><td>Forschungspunkte:</td><td>$kosten</td></tr><tr><td>Forschungszeit:</td><td>$bauzeit</td></tr></table>";
	
	/*Überprüfen ob diese Forschung gerade geforscht wird!*/
	if( $forsch_array['ID_Forschung'] == $i )	//aktuelle Forschung wird geforscht
	{
		$daten_techtree[$i] = "<div onMouseOut=\"hideElement()\" onMouseOver=\"showElement('<b>$bezeichnung</b>', '$text', 'green')\" id=\"".$forsch_array['ID_Forschung']."\" title=\"".($forsch_array['FinishTime']-time())."\"></div>";
		$daten_techtree[$i] .= "<script language=javascript>CountDownForschung(".$forsch_array['ID_Forschung'].", $type);</script>";
	}
	else 
	{
		//Schauen ob User diese Forschung hat
		if( $_SESSION['user']->getForschungsLevel($i) > 0 )
		{
			$daten_techtree[$i] = "&nbsp;<a class=\"$class\" href=\"techtree.php?type=$type&ID=".$forschung->getID()."\" onMouseOut=\"hideElement()\" onMouseOver=\"showElement('<b>$bezeichnung ($level)</b>', '$text', 'green')\">$bez_titel ($level)</a>";
		}
		else 
		{
			$daten_techtree[$i] = "&nbsp;<a class=\"$class\" href=\"techtree.php?type=$type&ID=".$forschung->getID()."\" onMouseOut=\"hideElement()\" onMouseOver=\"showElement('<b>$bezeichnung</b>', '$text', 'green')\">$bez_titel</a>";	
		}		
	}
}

//Nun alle Gebäude überprüfen
for( $i=0; $i<count($gebäude_ids[$type]); $i++ )
{
	//Gebäudeobjekt erzeugen
	$geb = new GEBÄUDE();
	$geb->loadGebäude($gebäude_ids[$type][$i], $_SESSION['user']->getRasseID());
	
	/*Kann User Gebäude bauen?*/
	if( $geb->checkRequirement($_SESSION['user'], $_SESSION['kolonie']->getID()) == 1 )	//user erfüllt requirements
	{
		$bezeichnung = $geb->getBezeichnung();
		$beschreibung= $geb->getBeschreibung();		
	}
	else 
	{
		$bezeichnung = 'unbekanntes Gebäude';
		$beschreibung = 'Gebäude nocht nicht erforscht';
	}
	
	//Kann User dieses Gebäude bauen?
	if( $geb->checkRequirement($_SESSION['user'], $_SESSION['kolonie']->getID()) == -1 )	//nein!
	{
		$daten_techtree["G".$geb->getID()] = "<img onMouseOut=\"hideElement()\" onMouseOver=\"showElement('<b>$bezeichnung</b>', '$beschreibung', 'purple')\" src=\"templates/".$_SESSION['skin']."/techtrees/haus_rot.gif\">";
	} 
	else 
	{
		$daten_techtree["G".$geb->getID()] = "<img onMouseOut=\"hideElement()\" onMouseOver=\"showElement('<b>$bezeichnung</b>', '$beschreibung', 'purple')\" src=\"templates/".$_SESSION['skin']."/techtrees/haus_green.gif\">";
	}
}

//Nun alle Teile überprüfen
for( $i=0; $i<count($teile_ids[$type]); $i++ )
{
	//Teile objekt erzeugen
	$teil = new TEILE();
	$teil->loadTeile($teile_ids[$type][$i], $_SESSION['user']->getRasseID());
	
	
	/*TeileDaten für MouseOver setzen*/
	//Überprüfen ob User Teil bauen kann
	if( $teil->checkRequirement($_SESSION['user'], $_SESSION['kolonie']->getID()) == 1 )	//Requirements werden erfüllt
	{
		$bezeichnung 	= $teil->getBezeichnung();
		$beschreibung 	= $teil->getBeschreibung();
	}
	else 
	{
		$bezeichnung = 'unbekanntes Bauteil';
		$beschreibung = 'Dieses Bauteil ist nicht erforscht.';
	}
	
	//Kann User dieses Teil bauen?
	if( $teil->checkRequirement($_SESSION['user'], $_SESSION['kolonie']->getID()) == -1 )	//nein
	{
		$daten_techtree["T".$teil->getID()] = "<img onMouseOut=\"hideElement()\" onMouseOver=\"showElement('<b>$bezeichnung</b>', '$beschreibung', '#000066')\" src=\"templates/".$_SESSION['skin']."/techtrees/granate_rot.gif\">";
	}
	else 
	{
		$daten_techtree["T".$teil->getID()] = "<img onMouseOut=\"hideElement()\" onMouseOver=\"showElement('<b>$bezeichnung</b>', '$beschreibung', '#000066')\" src=\"templates/".$_SESSION['skin']."/techtrees/granate_gruen.gif\">";
	}
}

/*Setze Titelleiste, zum Navigieren!*/
for( $i=1; $i<=5; $i++ )
{
	//echo $_SESSION['kolonie']->kolonie_status[1];
	//echo $_SESSION['kolonie']->getID();
	//Aktuelle Kolonieausbaustufe = $type?
	if( $i == $type )
	{
		$daten_techtree['NAVIGATION'] .= "<b>".$_SESSION['kolonie']->kolonie_status[$i]."</b>&nbsp;&nbsp;";
	}
	else 
	{
		$daten_techtree['NAVIGATION'] .= "<a href=\"techtree.php?type=$i\">".$_SESSION['kolonie']->kolonie_status[$i]."</a>&nbsp; &nbsp;";
	}
}

//Templatedaten ersetzen
$tpl_techtree->setObject("techtree", $daten_techtree);
echo $tpl_techtree->getTemplate();

//Objekt serialisieren
$_SESSION['user'] 			= serialize($_SESSION['user']);
$_SESSION['kolonie'] 		= serialize($_SESSION['kolonie']);
$_SESSION['bevoelkerung']	= serialize($_SESSION['bevoelkerung']);