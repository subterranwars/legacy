<?PHP
//Includes
require("../includes/login_check.php");

//Template laden
$tpl_geb = new TEMPLATE("templates/".$_SESSION['skin']."/gebäude.tpl");
$daten_detail = array(
	'TOPIC'=>'',
	'GEBÄUDENAME'=>'',
	'STUFE'=>'', 
	'TEXT'=>'', 
	'ID'=>'', 
	'ZEIT'=>'', 
	'LINK'=>'',
	'ENERGIE',
	'NEXT',
	'CLASS_TITAN' => '',
	'CLASS_EISEN' => '',
	'CLASS_STEIN' => '',
	'CLASS_STAHL' => '');

/*Überprüfen ob ein Gebäude gebaut werden soll*/
if( isset($_GET['ID']) )
{
	//Lade Gebäudeobjekt für Bauzeit
	$geb = new GEBÄUDE();
	$geb->loadGebäude($_GET['ID'], $_SESSION['user']->getRasseID());
			
	//Gebäudelevel ermitteln
	$lvl = $_SESSION['user']->getGebäudeLevel($_GET['ID']);
	
	//Bauauftrag einleiten
	$error 		= $to_do->startBau($_GET['ID'], $_SESSION['kolonie']->getID());
	
	//Fehler beim Bauen?
	if( $error == -1 )
	{
		//Fehlerobjekt erzeugen
		$fehler = new FEHLER($db);
		$daten['CONTENT'] = $fehler->meldung(140);
	}
	elseif( $error == -2 )
	{
		//Fehlerobjekt erzeugen
		$fehler = new FEHLER($db);
		$daten['CONTENT'] = $fehler->meldung(142);
	}
	elseif( $error == -3 )	//Requirements werden nicht erfuellt
	{
		$fehler = new FEHLER($db);
		$daten['CONTENT'] = $fehler->meldung(900);
	}
}

/*Überprüfen ob der Bau enies gebäudes abgebrochen werden soll!*/
if( isset($_GET['stop_bau']) )
{
	//Lade Gebäudeobjekt
	$geb = new GEBÄUDE();
	$geb->loadGebäude($_GET['stop_bau'], $_SESSION['user']->getRasseID());
	
	//Lade evtl. vorhandenen Bau
	$db->query("SELECT t_auftrag.ID_Auftrag FROM t_auftrag, t_auftraggebaeude WHERE t_auftrag.ID_User = ".$_SESSION['user']->getUserID()." AND t_auftrag.ID_Auftrag = t_auftraggebaeude.ID_Auftrag AND t_auftrag.ID_Kolonie = ".$_SESSION['kolonie']->getID()." AND t_auftraggebaeude.ID_Gebäude = ".$_GET['stop_bau']."");
	
	//Hat User überhaupt gebaut?
	if( $db->affected_rows() > 0 )	//ja... schreibe ihm Rohstoffe wieder gut!
	{
		//Lösche Bau
		$id = $db->fetch_result(0);
		$db->query("DELETE FROM t_auftrag WHERE ID_Auftrag = $id");
		$db->query("DELETE FROM t_auftraggebaeude WHERE ID_Auftrag = $id");
		
		//Hole Baukosten!
		$geb_level = $_SESSION['user']->getGebäudeLevel($_GET['stop_bau']);
		$kosten = $geb->getKosten($_SESSION['user']->getGebäudeLevel($_GET['stop_bau']));
	
		//Schreibe User 75% der Rohstoffe wieder gut!
		$_SESSION['user']->setRohstoffAnzahl($kosten[0] /** 0.75*/, 1);		//Eisen gutschreiben!
		$_SESSION['user']->setRohstoffAnzahl($kosten[1] /** 0.75*/, 2);		//Stein gutschreiben!
		$_SESSION['user']->setRohstoffAnzahl($kosten[2] /** 0.75*/, 6);		//Stahl gutschreiben!
		$_SESSION['user']->setRohstoffAnzahl($kosten[3] /** 0.75*/, 8);		//Titan gutschreiben!
	}
}
	
/*Gebäudeobjekt erzeugen und alle Gebäude laden*/
$geb 		= new GEBÄUDE();
$gebäude 	= $geb->getGebäudeArray();
$geb_bau	= $to_do->getGebBau($_SESSION['kolonie']->getID());

/*überprüfen ob Gebäude gebaut werden
Wenn ein Gebäude gebaut wird, dann wird der Wert auf true gesetzt um ihm am Ende
des Scriptes abzufragen*/
if( !empty($geb_bau['ID_Gebäude']) )
{
	$es_wird_gebaut = true;
}
else
{
	$es_wird_gebaut = false;
}

/*Gebäude durchlaufen*/
for( $i=1; $i <= count($gebäude); $i++ )
{	
	//Überprüfen ob eintrag leer ist?
	if( !empty($gebäude[$i]) )
	{
		//Gebäudeobjekt laden
		$geb->loadGebäude($i, $_SESSION['user']->getRasseID());
		
		//GebäudeLevel holen
		$level = $_SESSION['user']->getGebäudeLevel($i);
		
		//Kann Gebäude gebaut werden
		$erfuellt = $geb->checkRequirement($_SESSION['user'], $_SESSION['kolonie']);
		
		//Kann gebaut werden?
		if( $erfuellt == 1 )
		{
			//Überprüfen ob neue Kategory angefangen hat
			if( !empty($gebäude[$i][7]) )
			{
				$daten_detail['TOPIC'] = "<tr><th colspan=\"10\">".$gebäude[$i][7]."</th></tr>";
			}
			else 
			{
				$daten_detail['TOPIC'] = "";
			}
			
			//Bauzeit in tage, stunden, minuten und sekunden ermitteln
			$bauzeit = $geb->getFormattedBuildTime($level, $_SESSION['user']->getGebäudeLevel(1));
			
			//Templatelevel-Daten setzen
			if( empty($level) )
			{
				$daten_detail['STUFE'] = "";
			}
			else
			{
				$daten_detail['STUFE'] = "(Stufe: $level)";
			}
			
			//Energieverbrauch ermitteln!
			if( $level == 0 )
			{
				$energieverbrauch_current = 0;
			}
			else 
			{
				$energieverbrauch_current		= $geb->getEnergieverbrauch(($level-1));//Energieverbrauch des aktuellen Levels
			}
			
			//GEbäudenamen ermitteln
			$energieverbrauch_next			= $geb->getEnergieverbrauch($level);	//Energieverbrauch des nächsten levels!
			$daten_detail['GEBÄUDENAME'] 	= $geb->getBezeichnung();
			$daten_detail['ID']				= $geb->getID();
			$daten_detail['BESCHREIBUNG']	= $geb->getBeschreibung();
			$daten_detail['ZEIT']			= $bauzeit;
			$daten_detail['LINK']			= $gebäude[$i][5];
			$daten_detail['NEXT']			= number_format($energieverbrauch_next, 0, ",", ".");
			$daten_detail['ENERGIE']		= number_format($energieverbrauch_current, 0, ",", ".");
					
			//Gebäudekosten setzen
			$kosten = $geb->getKosten($level);
			
			//Soll Anzeige rot oder grün sein?
			$daten_detail['CLASS_EISEN'] = "green";
			$daten_detail['CLASS_STEIN'] = "green";
			$daten_detail['CLASS_STAHL'] = "green";
			$daten_detail['CLASS_TITAN'] = "green";
	
			//Wenn zu wenig da, dann rot!
			if( $kosten[0] > $_SESSION['user']->getRohstoffAnzahl(1) )
			{
				$daten_detail['CLASS_EISEN'] = "red";
			}
			if( $kosten[1] > $_SESSION['user']->getRohstoffAnzahl(2) )
			{
				$daten_detail['CLASS_STEIN'] = "red";
			}
			if( $kosten[2] > $_SESSION['user']->getRohstoffAnzahl(6) )
			{
				$daten_detail['CLASS_STAHL'] = "red";
			}
			if( $kosten[3] > $_SESSION['user']->getRohstoffAnzahl(8) )
			{
				$daten_detail['CLASS_TITAN'] = "red";
			} 
			
			
			//Templatedaten setzen!
			$daten_detail['EISEN'] = number_format($kosten[0], "0", ",", ".");
			$daten_detail['STEIN'] = number_format($kosten[1], "0", ",", ".");
			$daten_detail['STAHL'] = number_format($kosten[2], "0", ",", ".");
			$daten_detail['TITAN'] = number_format($kosten[3], "0", ",", ".");
			
			//Überprüfen ob Gebäude gebaut wird
			if( $es_wird_gebaut == true )
			{
				//Überprüfen welches gebäude gebaut wird
				if( $geb_bau['ID_Gebäude'] == $i )
				{
					//Verbleibende Zeit ermitteln!
					$zeit_verbleibend = $geb_bau['FinishTime'] - time();
					$daten_detail['TEXT']  = "<div id=\"".$geb_bau['ID_Gebäude']."\" title=\"".$zeit_verbleibend."\"></div>";
					$daten_detail['TEXT'] .= "<script language=javascript>CountDownGeb(".$geb_bau['ID_Gebäude'].");</script>";
				}
				else
				{
					$daten_detail['TEXT'] = "-";
				}
			}
			//Überprüfen ob User Gebäude angezeigt bekommen darf
			elseif( $gebäude[$i][4] == 0 || $gebäude[$i][4] == $_SESSION['user']->getRasseID())
			{
				//Leveltemplate setzen
				if( empty($level) )
				{
					$daten_detail['TEXT'] = "<a href=\"gebaeude.php?ID=".$i."\">bauen</a>";
				}
				else 
				{
					$level_anzeige = $level+1;
					$daten_detail['TEXT'] = "<a href=\"gebaeude.php?ID=".$i."\">zur Stufe $level_anzeige<br>ausbauen</a>";
				}
			}
			
			//Gebäudetemplate ersetzen
			$tpl_geb->setObject("gebäude", $daten_detail);
		}
	}
}

//Template laden
$daten['CONTENT'] .= $tpl_geb->getTemplate();

//footer einbinden
require_once("../includes/footer.php");
?>