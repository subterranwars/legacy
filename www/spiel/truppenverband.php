<?php
//Includes
require("../includes/login_check.php");

//Welche IDs haben Kaserne und Waffenfabrik
$kaserne_id 		= 29;
$waffenfabrik_id 	= 31;

//Wenn User kasrne oder Waffenfabrik hat weitermachen
if( $_SESSION['user']->getGebäudeLevel($kaserne_id) > 0 || $_SESSION['user']->getGebäudeLevel($waffenfabrik_id) > 0 )
{
	//Truppenverbandstemplate laden
	$tpl_truppen = new TEMPLATE("templates/".$_SESSION['skin']."/truppenverband.tpl");
	$daten_truppen = array(
		'TRUPPEN' => '',
		'EINHEITEN_FEHLER' => '',
		'EINHEITEN' => '',
		'SELECT' => '');
		
	
	/*Wurde Knopf gedrückt um Truppe aufzulösen?*/
	if( isset($_GET['del']) )
	{
		//Ist Truppe in Benutzung?
		$db->query("SELECT COUNT(*) FROM t_mission WHERE ID_Flotte = ".$_GET['del']."");
		$trup_anzahl = $db->fetch_result(0);
		
		//Wenn Anzahl <= 0 ist, dann kann truppe gelöscht werden!
		if( $trup_anzahl <= 0 )
		{
			//Truppe löschen, sofern User diese Gruppe gehört
			$db->query("DELETE FROM t_flotte WHERE ID_Flotte = ".$_GET['del']." AND ID_User = ".$_SESSION['user']->getUserID()."");
		
			//Wenn Truppe gelöscht wurde, die Einheiten wieder keiner Flotte zuweisen
			if( $db->affected_rows() > 0 )
			{
				$db->query("UPDATE t_einheit SET ID_Flotte = 0 WHERE ID_Flotte = ".$_GET['del']."");
			}
		}
	}
	
	/*Wurde Knopf gedürckt, um Einheiten zu einer Truppe zu gruppieren?*/
	if( isset($_POST['send']) )
	{
		//Ist Truppenbezeichnung gesetzt worden?
		if( !empty($_POST['truppen_bez']) )
		{
			//Sind min eine Einheit gewählt worden?
			if( count($_POST['ID']) > 0 )
			{
				//Einheiten vorhanden... Flotte anlegen
				$db->query("
					INSERT INTO t_flotte
						(Bezeichnung, ID_User, ID_Kolonie)
					VALUES 
						('".$_POST['truppen_bez']."', ".$_SESSION['user']->getUserID().", ".$_SESSION['kolonie']->getID().")");
				//ID_Flotte laden
				$ID_Flotte = $db->last_insert();
				
				//Durchlaufe alle Einheiten, um sie der Flotte zuzuordnen
				for( $i=0; $i<count($_POST['ID']); $i++ )
				{
					/*Da der Wert folgendermaßen aufgebaut ist ChassisTyp|BauplanTyp|ID_Einheit 
					muss der STring gesplittet werden*/
					//ID_Einheit aus String rausfiltern
					$split = explode("|", $_POST['ID'][$i]);
	
					//Einheit gruppieren

					$db->query("UPDATE t_einheit SET ID_Flotte = $ID_Flotte WHERE ID_Einheit = ".$split[2]." AND ID_User = ".$_SESSION['user']->getUserID()."");
				}
			}
			else 	//KEine Einheit gewählt!
			{
				//Fehlerobjekt erzeugen
				$fehler = new FEHLER($db);
				$daten_truppen['EINHEITEN_FEHLER'] = $fehler->meldung(952);
			}
		}
		else 	//Truppen-Bezeichnung vergessen
		{
			//fehler objekt erzeugen
			$fehler = new FEHLER($db);
			$daten_truppen['EINHEITEN_FEHLER'] = $fehler->meldung(951);
		}	
	}
	
	//Lade evtl. vorhandene Truppen
	$db->query("
		SELECT
			t_flotte.ID_Flotte AS ID_Flotte, Bezeichnung, 
			t_flotte.ID_User AS ID_User,
			Parameter
		FROM 
			t_flotte LEFT JOIN t_mission
			USING ( ID_Flotte ) 
		WHERE 
			t_flotte.ID_User = ".$_SESSION['user']->getUserID()."");
	if( $db->num_rows() > 0 )
	{
		//Template erstellen
		$tpl_truppen_truppen = new TEMPLATE("templates/".$_SESSION['skin']."/truppenverband_truppen.tpl");
		$daten_truppen_truppen = array(
			'ID' => '',
			'LINK' => '',
			'NAME' => '',
			'ANZAHL' =>'',
			'INF' =>'',
			'PANZER' => '',
			'MECHS' => '',
			'LKWS' => '',
			'TRUPPENTRANSPORTER' => '',
			'ERFAHRUNG' => '',
			'LEBENSPUNKTE' => '');
		
		//2. Datenbankobjekt erstellen
		$db2 = new DATENBANK();
			
		//Durchlaufe alle Truppenverbände
		while( $row = $db->fetch_array() )
		{	
			//Standarddaten setzen!
			$daten_truppen_truppen['ID'] 			= $row['ID_Flotte'];
			$daten_truppen_truppen['NAME'] 			= $row['Bezeichnung'];
			
			//Spezielledaten laden
			$db2->query("
				SELECT
					COUNT(*), AVG(Erfahrung), AVG(Lebenspunkte), AVG(LebenProzent)
				FROM
					t_einheit, t_profil
				WHERE 
					ID_Flotte = ".$row['ID_Flotte']."
					AND t_einheit.ID_Bauplan = t_profil.ID_Profil");
			$ergebnis = $db2->fetch_array();
			
			//Lade Anzahl Infanteristen, Fahrzueuge und Mechs!
			$sql_inf 		= "SELECT COUNT(*) FROM t_einheit, t_profil WHERE t_einheit.ID_Bauplan = t_profil.ID_Profil AND t_profil.ChassisTyp = 1 AND t_einheit.ID_Flotte = ".$row['ID_Flotte']."";
			$sql_panzer 	= "SELECT COUNT(*) FROM t_einheit, t_profil WHERE t_einheit.ID_Bauplan = t_profil.ID_Profil AND t_profil.ChassisTyp = 2 AND t_einheit.ID_Flotte = ".$row['ID_Flotte']."";
			$sql_mech		= "SELECT COUNT(*) FROM t_einheit, t_profil WHERE t_einheit.ID_Bauplan = t_profil.ID_Profil AND t_profil.ChassisTyp = 3 AND t_einheit.ID_Flotte = ".$row['ID_Flotte']."";
			$sql_lkw		= "SELECT COUNT(*) FROM t_einheit, t_profil WHERE t_einheit.ID_Bauplan = t_profil.ID_Profil AND t_profil.ChassisTyp = 4 AND t_einheit.ID_Flotte = ".$row['ID_Flotte']."";
			$sql_transporter= "SELECT COUNT(*) FROM t_einheit, t_profil WHERE t_einheit.ID_Bauplan = t_profil.ID_Profil AND t_profil.ChassisTyp = 5 AND t_einheit.ID_Flotte = ".$row['ID_Flotte']."";
			
			//Queries ausühren
			//infanteristen
			$db2->query($sql_inf);
			$anz_inf = $db2->fetch_result(0);
			//Fahrzeuge
			$db2->query($sql_panzer);
			$anz_fahrzeuge = $db2->fetch_result(0);
			//Mechs
			$db2->query($sql_mech);
			$anz_mech = $db2->fetch_result(0);
			//Lkws
			$db2->query($sql_lkw);
			$anz_lkw = $db2->fetch_result(0);
			//Truppentransporter
			$db2->query($sql_transporter);
			$anz_transporter = $db2->fetch_result(0);
			
			//Daten setzen!
			$daten_truppen_truppen['ANZAHL'] 				= $ergebnis[0];
			$daten_truppen_truppen['INF'] 					= $anz_inf;
			$daten_truppen_truppen['PANZER'] 				= $anz_fahrzeuge;
			$daten_truppen_truppen['MECHS'] 				= $anz_mech;
			$daten_truppen_truppen['LKWS'] 					= $anz_lkw;
			$daten_truppen_truppen['TRUPPENTRANSPORTER'] 	= $anz_transporter;
			$daten_truppen_truppen['ERFAHRUNG'] 			= $ergebnis[1];
			$daten_truppen_truppen['LEBENSPROZENT'] 		= number_format($ergebnis[3]*100, 0,",",".");
			$daten_truppen_truppen['LEBENSPUNKTE'] 			= number_format($ergebnis[2]*$ergebnis[3],0,",",".");
			
			//Wenn Truppe unterwegs ist, dann Missionstyp = unterwegs
			if( empty($row['Parameter']) )		//Flotte ist @ home
			{
				$daten_truppen_truppen['LINK'] = "<a href=\"mission.php?ID=".$row['ID_Flotte']."\">Mission starten</a>";
			}
			else								//Flotte unterwegs
			{
				$daten_truppen_truppen['LINK'] = "unterwegs";
			}
			
			//Templatedaten setzen
			$tpl_truppen_truppen->setObject("truppenverband_truppen", $daten_truppen_truppen);
		}
		//Datenbankobjekt löschen
		$db2->db_connect_close();
		unset($db2);
		
		//Templatedaten aktuallisieren
		$daten_truppen['TRUPPEN'] = $tpl_truppen_truppen->getTemplate();
	}
	else 
	{
		$daten_truppen['TRUPPEN'] = "Keine Truppenverbände vorhanden";
	}
	
	/*Template für die Einheitenauswahl setzen*/
	//Lade Anzahl Infanteristen
	$db->query("SELECT COUNT(*) FROM t_einheit WHERE ID_Flotte = 0 AND ID_User = ".$_SESSION['user']->getUserID()."");
	$anz_einheiten = $db->fetch_result(0);

	//Setze Template-Select
	$daten_truppen['SELECT'] .= "<a href=\"#\" onClick=\"delSelection($anz_einheiten)\">Auswahl aufheben</a><br>";
	$daten_truppen['SELECT'] .= "<a href=\"#\" onClick=\"selectAll($anz_einheiten)\">Alle Einheiten selektieren</a><br>";
	$daten_truppen['SELECT'] .= "<a href=\"#\" onClick=\"selectEinheit(1,0,$anz_einheiten)\">alle <b>Infanteristen</b> selektieren</a><br>";
	$daten_truppen['SELECT'] .= "<a href=\"#\" onClick=\"selectEinheit(2,0,$anz_einheiten)\">alle <b>Panzer</b> selektieren</a><br>";
	$daten_truppen['SELECT'] .= "<a href=\"#\" onClick=\"selectEinheit(3,0,$anz_einheiten)\">alle <b>Mechs</b> selektieren</a><br>";
	
	//LAde evtl. vorhandene Einheiten
	$db->query("
		SELECT 
			ID_Einheit, t_einheit.ID_Bauplan as ID_Bauplan, Erfahrung, LebenProzent, Bezeichnung, ChassisTyp,
			Angriff, Panzerung, Zielen, Geschwindigkeit, Lebenspunkte, ChassisTyp
		FROM
			t_einheit, t_profil
		WHERE
			t_einheit.ID_Bauplan = t_profil.ID_Profil 
			AND t_einheit.ID_User = ".$_SESSION['user']->getUserID()."
			AND ID_Flotte = 0
		ORDER BY
			ChassisTyp ASC, ID_Bauplan ASC, LebenProzent DESC;");
	//Sind Einheiten vorhanden?
	if( $db->num_rows() > 0 )	//ja
	{
		//Lade Template
		$tpl_truppen_einheiten = new TEMPLATE("templates/".$_SESSION['skin']."/truppenverband_einheiten.tpl");
		$daten_truppen_einheiten = array(
			'I' => '',
			'ID' => '',
			'NAME' => '',
			'KLASSE' => '',
			'HP' => '',
			'ERFAHRUNG' => '',
			'ANGRIFF' => '',
			'VERTEIDIGUNG' => '',
			'ZIELEN' => '',
			'GESCHWINDIGKEIT' => '',
			'CHASSIS' => '',
			'ID_PROFIL' => '');
			
		//Daten durchlaufe und werte setzen!
		$i = 0;
		while( $row = $db->fetch_array() )
		{
			//Templatedaten setzen!
			$daten_truppen_einheiten['I']				= $i;
			$daten_truppen_einheiten['ID'] 				= $row['ID_Einheit'];	
			$daten_truppen_einheiten['NAME'] 			= $row['Bezeichnung'];
			$daten_truppen_einheiten['HP']				= number_format($row['LebenProzent'] * $row['Lebenspunkte'],0,",",".");
			$daten_truppen_einheiten['ERFAHRUNG'] 		= $row['Erfahrung'];
			$daten_truppen_einheiten['ANGRIFF'] 		= $row['Angriff'];
			$daten_truppen_einheiten['VERTEIDIGUNG'] 	= $row['Panzerung'];
			$daten_truppen_einheiten['ZIELEN'] 			= $row['Zielen'];
			$daten_truppen_einheiten['GESCHWINDIGKEIT'] = $row['Geschwindigkeit'];
			$daten_truppen_einheiten['CHASSIS']			= $row['ChassisTyp'];
			$daten_truppen_einheiten['ID_PROFIL']		= $row['ID_Bauplan'];
			$i++;
			
			//Setze die Balken der Leben in %
			$width = 100;		//100 pixel breit
			$daten_truppen_einheiten['WIDTH'] = ($width / $row['Lebenspunkte']) * $row['LebenProzent'] * $row['Lebenspunkte'];
						
			//Klasse setzen Infanterist, Mech, Fahrzeug
			if( $row['ChassisTyp'] == 1 )	//Infanterist
			{
				$daten_truppen_einheiten['KLASSE']	= 'Infanterist';
			}
			elseif( $row['ChassisTyp'] == 2 )	//Panzer
			{
				$daten_truppen_einheiten['KLASSE']	= 'Panzer';
			}
			elseif( $row['ChassisTyp'] == 3 )	//Mech
			{
				$daten_truppen_einheiten['KLASSE']	= 'Mech';
			}
			elseif( $row['ChassisTyp'] == 4 )	//LKW
			{
				$daten_truppen_einheiten['KLASSE']	= 'LKW';
			}
			else 								//Truppentransporter
			{
				$daten_truppen_einheiten['KLASSE']	= 'Truppentransporter';
			}
						
			//Aktuallisiere Templatedaten
			$tpl_truppen_einheiten->setObject("truppenverband_einheiten", $daten_truppen_einheiten);
		}
		//Templatedaten setzen!
		$daten_truppen['EINHEITEN'] = $tpl_truppen_einheiten->getTemplate();
		
		//Lade nun alle Daten für SelectTemplate
		$db->query("SELECT ID_Bauplan, Bezeichnung FROM t_einheit, t_profil WHERE t_einheit.ID_Bauplan = t_profil.ID_Profil AND t_einheit.ID_User = ".$_SESSION['user']->getUserID()." AND t_einheit.ID_Flotte = 0 GROUP BY ID_Bauplan ORDER BY ChassisTyp ASC");
		while( $row = $db->fetch_array() )
		{
			//Setze Template-Select
			$daten_truppen['SELECT'] .= "<a onClick=\"selectEinheit(0,".$row['ID_Bauplan'].",".$anz_einheiten.")\">alle <b>".$row['Bezeichnung']."</b> selektieren</a><br>";
		}
	}
	else //Nein
	{
		$daten_truppen['EINHEITEN'] = "Keine Einheiten vorhanden.";
	}
	
	//Templatedaten aktuallisieren
	$tpl_truppen->setObject("truppenverband", $daten_truppen);
	$daten['CONTENT'] = $tpl_truppen->getTemplate();
}
else 	//User hat keine KAserne oder Waffenfabrik
{
	//Fehlerobjekt erzeugen
	$fehler = new FEHLER($db);
	$daten['CONTENT'] = $fehler->meldung(950);
}

//footer einbinden
require_once("../includes/footer.php");