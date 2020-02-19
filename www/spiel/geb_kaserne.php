<?php
//Includes
require("../includes/login_check.php");

/*Überprüfen ob der User die Requirements für den kleinen, mittleren, großen LKW 
sowie einen Truppentransporter erfüllt.
Ist dies der Fall muss ein Profil für diesen gespeichert werden!*/
$teile_id = array(
				210,	//kleiner LKW
				211,	//mittlerer LKW
				212,	//großer LKW
				213		//Truppentransporter
				);
//Durchlaufe alle Teile.
foreach( $teile_id as $x )
{
	//TeileObjekt erzeugen
	$teile = new TEILE();
	$teile->loadTeile($x, $_SESSION['user']->getRasseID());
	
	//erfüllt User Requirements?
	if( $teile->checkRequirement($_SESSION['user'], $_SESSION['kolonie']->getID()) == 1 )	//Wird erfüllt
	{
		//Überprüfen ob Profil schon gespeichert ist!
		$db->query(
				"SELECT
					COUNT(*)
				FROM
					t_profil
				WHERE
					ID_User = ".$_SESSION['user']->getUserID()."
				AND
					MaxZuladung = ".$teile->getZuladung()."
				AND
					ChassisTyp = ".$teile->getTyp()."");
		$anzahl = $db->fetch_result(0);
		
		//Wenn Ergebnis > 0 , dann Profil vorhanden, ansonsten Profil anlegen
		if( $anzahl <= 0 )
		{
			//Kosten laden
			$kosten = $teile->getKosten();
			
			//Profil eintragen
			$db->query(
					"INSERT INTO
						t_profil
						(Bezeichnung,
						ChassisTyp,
						MaxLeistung,
						MaxZuladung,
						Geschwindigkeit,
						Lebenspunkte, 
						Eisen, 
						Stahl, 
						Titan, 
						Kunststoff, 
						Wasserstoff, 
						Uran, 
						Plutonium, 
						Gold,
						Diamant, 
						Bevölkerung,
						Bauzeit,
						ID_User)
					VALUES
						('".$teile->getBezeichnung()."', 
						".$teile->getTyp().", 
						".$teile->getLeistung().",
						".$teile->getZuladung().",
						".$teile->getGeschwindigkeit().",
						".$teile->getLebenspunkte().",
						".$kosten[0].",
						".$kosten[1].",
						".$kosten[2].",
						".$kosten[3].",
						".$kosten[4].",
						".$kosten[5].",
						".$kosten[6].",
						".$kosten[7].",
						".$kosten[8].",
						".$kosten[9].",
						".$teile->getBuildTime().",
						".$_SESSION['user']->getUserID().")");	
			//ID_Profil laden
			$ID_Profil = $db->last_insert();
				
			//Teile für Profil speichern
			$db->query("INSERT INTO t_profilhatteile (ID_Profil, ID_Teile) VALUES ($ID_Profil, ".$teile->geTID().")");
		}
	}
}

/*Gebäudelevel laden
Wenn $_GET['Show'] = 1, dann Kaserne, ansonsten Waffenfabrik laden!*/
/*Bestimme Query*/
switch( $_GET['show'] )
{
	default:	//Infanteristen oder nicht definierte Eingabe
	case 1:
		$query_where	= "ChassisTyp = 1";
		$geb_req_level 	= $_SESSION['user']->getGebäudeLevel(29);	//KasernenID
		break;
	case 2:		//Fahrzeuge und Mechs!
		$query_where 	= "ChassisTyp > 1";
		$geb_req_level 	= $_SESSION['user']->getGebäudeLevel(31);	//WaffenFabrikID
		break;
}

/*Überprüfen ob User Gebäude überhaupt hat!*/
if ( $geb_req_level != 0 ) 
{	
	//Template laden
	$tpl_kaserne = new TEMPLATE("templates/".$_SESSION['skin']."/gebäude/kaserne.tpl");
	$daten_kaserne = array(
		'BAUPLÄNE' => '',
		'FEHLER' => '',
		'AUSBILDUNG' => '',
		'ANZAHL' => 0);
	
	/*Ist DeleteKnopf gedrückt, dann bitte PRofil löschen*/
	if( isset($_GET['ID']) )
	{
		//Überprüfen ob dieser Bauauftrag benutzt wird
		$db->query("SELECT COUNT(*) FROM t_auftrag, t_auftrageinheit WHERE t_auftrag.ID_Auftrag = t_auftrageinheit.ID_Auftrag AND ID_User =".$_SESSION['user']->getUserID()." AND ID_Bauplan = ".$_GET['ID']."");
		$anzahl_baupläne1 = $db->fetch_result(0);
		
		//Überprüfen ob Bauplan vorhanden ist in einer Einheit
		$db->query("SELECT COUNT(*) FROM t_einheit WHERE ID_Bauplan = ".$_GET['ID']."");
		$anzahl_baupläne2 = $db->fetch_result(0);
		
		//Kann Bauplan gelöscht werden?
		if( $anzahl_baupläne1 <= 0 AND $anzahl_baupläne2 <= 0 )
		{
			//SQL anweisung
			$db->query("DELETE FROM t_profil WHERE ID_User = ".$_SESSION['user']->getUserID()." AND ID_Profil = ".$_GET['ID']."");
			
			//Wenn Löschung erfolgreich, bitte teile des profiles entfernen!
			if( $db->affected_rows() > 0 )
			{
				$db->query("DELETE FROM t_profilhatteile WHERE ID_Profil = ".$_GET['ID']."");
			}
		}
		else 
		{
			//Fehlerobjekt erzeugen!
			$fehler = new FEHLER($db);
			$daten_kaserne['FEHLER'] = $fehler->meldung(927);
		}
	}
	
	/*Soll eine Ausbildung beendet werden?*/
	if( isset($_GET['del_auftrag']) )
	{		
		//Überprüfen ob user bauplan löschen darf!
		$db->query("
			SELECT
				t_auftrag.ID_Auftrag, ID_Bauplan, Dauer, Anzahl, Fertig, ID_User, FinishTime
			FROM 
				t_auftrageinheit, t_auftrag
			WHERE 
				t_auftrag.ID_Auftrag = ".$_GET['del_auftrag']." 
				AND ID_User = ".$_SESSION['user']->getUserID()."");
		if( $db->num_rows() > 0 )	//User darf Auftrag löschen!
		{
			//Daten elektieren
			$ergebnis = $db->fetch_array();		
					
			//Bauplanobjekt erzeugen
			$bauplan = new BAUPLAN($db, $_SESSION['user'], $_SESSION['kolonie']->getID());
			$bauplan->loadBauplan($ergebnis['ID_Bauplan']);
			
			//Kosten laden
			$kosten = $bauplan->getKosten();
			
			//Rohstoffe wieder hinzufügen
			$_SESSION['user']->setRohstoffAnzahl($kosten[0]*$ergebnis['Anzahl'], 1);		//Eisen
			$_SESSION['user']->setRohstoffAnzahl($kosten[1]*$ergebnis['Anzahl'], 6);		//Stahl
			$_SESSION['user']->setRohstoffAnzahl($kosten[2]*$ergebnis['Anzahl'], 8);		//Titan
			$_SESSION['user']->setRohstoffAnzahl($kosten[3]*$ergebnis['Anzahl'], 15);		//Kunststoff
			$_SESSION['user']->setRohstoffAnzahl($kosten[4]*$ergebnis['Anzahl'], 10);		//Wasserstoff
			$_SESSION['user']->setRohstoffAnzahl($kosten[5]*$ergebnis['Anzahl'], 11);		//Uran
			$_SESSION['user']->setRohstoffAnzahl($kosten[6]*$ergebnis['Anzahl'], 12);		//Plutonium
			$_SESSION['user']->setRohstoffAnzahl($kosten[7]*$ergebnis['Anzahl'], 14);		//Gold
			$_SESSION['user']->setRohstoffAnzahl($kosten[8]*$ergebnis['Anzahl'], 13);		//Diamant
			
			//Bevölkerung
			$_SESSION['bevoelkerung']->setBevölkerung($kosten[9]*$ergebnis['Anzahl']);				//Bevölkerung
			
			//Startoffset der anderen aufträge ermitteln!
			$start_offset = $ergebnis['Dauer'] * ($ergebnis['Anzahl'] + $ergebnis['Fertig']);
			
			//Auftrag löschen!
			$db->query("DELETE FROM t_auftrag WHERE ID_Auftrag =".$_GET['del_auftrag']."");
			$db->query("DELETE FROM t_auftrageinheit WHERE ID_Auftrag = ".$_GET['del_auftrag']."");
			
			//OFfset von den anderen Aufträgen abziehen!
			$db->query("
				UPDATE
					t_auftrag 
				SET 
					FinishTime = (FinishTime-$start_offset) 
				WHERE
					ID_User = ".$_SESSION['user']->getUserID()." 
					AND ID_Kolonie =".$_SESSION['kolonie']->getID()."
					AND FinishTime > ".$ergebnis['FinishTime']."
					AND Kategory = 'Einheiten'");
		}
		else 	//Auftrag gehört wem anders!"
		{
		}
	}
	
	/*Soll gebaut werden?*/
	if( isset($_POST['send']) )
	{		
		//Alle Aufträge starten :)
		for( $i=0; $i<count($_POST['ID']); $i++ )
		{
			//Wenn was eingetragen ist, dann bitte ausführen!
			if( !empty($_POST['anzahl'][$i]) )
			{
				$error = $to_do->startAusbildung($_POST['ID'][$i], $_POST['anzahl'][$i], $_SESSION['kolonie']->getID(), 1);
			}
		}
	}
				
	/*Lade Alle Baupläne*/
	$db->query("
		SELECT 
			ID_Profil 
		FROM 
			t_profil
		WHERE 
			".$query_where." 
		AND 
			ID_User =".$_SESSION['user']->getUserID()."
		ORDER BY
			Lebenspunkte ASC");
	
	//Ist min. ein Bauplan vorhanden?
	if( $db->num_rows() > 0 )
	{
		//Template laden
		$tpl_kaserne_baupläne = new TEMPLATE("templates/".$_SESSION['skin']."/gebäude/kaserne_bauplan.tpl");
		$daten_kaserne_baupläne = array(
			'ID' => '',
			'SHOW' => '',
			'NAME' => '',
			'ANGRIFF' => '',
			'VERTEIDIGUNG' =>'',
			'GESCHWINDIGKEIT' => '',
			'WENDIGKEIT' => '',
			'ZIELEN' => '');
			
		//2. Datenbank objekt erzeugen
		$db2 = new DATENBANK();
		//Bauplanobjekt erstellen
		$bauplan = new BAUPLAN($db2, $_SESSION['user'], $_SESSION['kolonie']->getID());
		
		//Daten aus Query laden
		while( $row = $db->fetch_array() )
		{
			//Bauplan laden
			$bauplan->loadBauplan($row['ID_Profil']);
			
			//Template daten sestzen
			$daten_kaserne_baupläne['SHOW']				= $_GET['show'];
			$daten_kaserne_baupläne['ID'] 				= $bauplan->getID();
			$daten_kaserne_baupläne['NAME'] 			= $bauplan->getBezeichnung();
			$daten_kaserne_baupläne['HP']				= $bauplan->getLebenspunkte();
			$daten_kaserne_baupläne['ANGRIFF'] 			= $bauplan->geTAngriff();
			$daten_kaserne_baupläne['VERTEIDIGUNG'] 	= $bauplan->getPanzerung();
			$daten_kaserne_baupläne['GESCHWINDIGKEIT'] 	= $bauplan->getGeschwindigkeit();
			$daten_kaserne_baupläne['WENDIGKEIT'] 		= $bauplan->getWendigkeit();
			$daten_kaserne_baupläne['ZIELEN'] 			= $bauplan->getZielen();
			
			//Von welchem Typ ist der Bauplan?
			if( $bauplan->getChassis() == 1 )	//Infanterist
			{
				$daten_kaserne_baupläne['BUILDTIME'] = $bauplan->getFormattedBuildTime($_SESSION['user']->getGebäudeLevel(29));
			}
			else 								//Mech, Fahrzeug etc
			{
				$daten_kaserne_baupläne['BUILDTIME'] = $bauplan->getFormattedBuildTime($_SESSION['user']->getGebäudeLevel(31));
			}
			
			//Template erweitern
			$tpl_kaserne_baupläne->setObject('bauplan', $daten_kaserne_baupläne);
		}	
		//Daten löschen 
		unset($db2);
		unset($bauplan);
		
		//Daten aus der Kaserne setzen
		$daten_kaserne['BAUPLÄNE'] = $tpl_kaserne_baupläne->getTemplate();	
	}
	else	//Kein BAuplan vorhanden! 
	{
		$daten_kaserne['BAUPLÄNE'] = "Zur Zeit keine Baupläne vorhanden!";
	}
	
	//Wird was ausgebildet?
	$einheiten = $to_do->getAusbildung($_SESSION['kolonie']->getID());
	
	if( !empty($einheiten['Bauplan'][0]) )
	{
		//Ausbildungstemplate setzen!
		$tpl_kaserne_ausbildung = new TEMPLATE("templates/".$_SESSION['skin']."/gebäude/kaserne_ausbildung.tpl");
		$daten_kaserne_einheiten = array(
			'ID' => '',
			'PROFIL' => '',
			'ANZAHL' => '',
			'FERTIG' => '',
			'VERBLEIBEND' => '');
			
		//Aufträge durchlaufen
		for( $i=0; $i<count($einheiten['ID_Auftrag']); $i++ )
		{    		
			//Templatedaten vorbereiten
			$daten_kaserne_einheiten['ID'] 			= $einheiten['ID_Auftrag'][$i];
			$daten_kaserne_einheiten['PROFIL'] 		= $einheiten['Bauplan'][$i];
			$daten_kaserne_einheiten['ANZAHL'] 		= $einheiten['Anzahl'][$i] + $einheiten['Fertig'][$i];
			$daten_kaserne_einheiten['FERTIG'] 		= $einheiten['Fertig'][$i];
			$daten_kaserne_einheiten['VERBLEIBEND'] = "<div id=\"einheit".($i+1)."\" title=\"".($einheiten['FinishTime'][$i] - time())."\"></div>";
			
			//Templadtedaten setzen!
			$tpl_kaserne_ausbildung->setObject("einheiten_bau", $daten_kaserne_einheiten);
		}
		//Templatedaten aktuallisieren!
		$daten_kaserne['ANZAHL']	 = count($einheiten['ID_Auftrag']);
		$daten_kaserne['AUSBILDUNG'] = $tpl_kaserne_ausbildung->getTemplate();
	}
	else 
	{
		$daten_kaserne['AUSBILDUNG'] = "Zur Zeit werden keine Einheiten ausgebildet!";	
	}

	//Templatedaten ersetzen
	$tpl_kaserne->setObject('kaserne', $daten_kaserne);
	$daten['CONTENT'] = $tpl_kaserne->getTemplate();
}
else 
{
	//Fehlerobjekt erzeugen
	$fehler = new FEHLER($db);
	$daten['CONTENT'] = $fehler->meldung(141);
}

//footer einbinden
require_once("../includes/footer.php");