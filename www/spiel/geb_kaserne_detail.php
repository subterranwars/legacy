<?php
/*geb_kaserne_detail.php
Diese Datei zeigt den genauen Inhalt eines Profiles an.
Die Bauteile, sowie dieKosten und auch die Bauzeit

History:
			23.09.2004		Markus von Rüden		created
*/
//Includes
require("../includes/login_check.php");

//Objekte laden
$db 	= new DATENBANK();	//DB-Objekt
$fehler = new FEHLER($db);	//Fehler-Objekt

//Ist ID gesetzt?
if( isset($_GET['ID']) )
{
	//Template laden
	$tpl = new TEMPLATE("templates/".$_SESSION['skin']."/gebäude/kaserne_bauplan_detail.tpl");
	$daten = array(
		'NAME' => '',
		'STAHL_GES' => '',
		'EISEN_GES' => '',
		'TITAN_GES' => '',
		'KUNSTSTOFF_GES' => '',
		'WASSERSTOFF_GES' => '',
		'URAN_GES' => '',
		'PLUTONIUM_GES' => '',
		'GOLD_GES' => '',
		'DIAMANT_GES' => '',
		'BEVÖLKERUNG_GES' => '',
		'BAUZEIT_GES' => '');	
	
	$daten_detail = array(
		'BEZ' => '',
		'STAHL' => '',
		'TITAN' => '',
		'EISEN' => '',
		'KUNSTSTOFF' => '',
		'WASSERSTOFF' => '',
		'URAN' => '',
		'PLUTONIUM' => '',
		'GOLD' => '',
		'DIAMANT' => '',
		'BEVÖLKERUNG' => '',
		'BAUZEIT' => '',
		'TYP' => '');
		
	//Gehört Profil mir?
	$db->query("SELECT ID_User FROM t_profil WHERE ID_Profil = ".$_GET['ID']."");
	if( $db->num_rows() > 0 )	//Ja
	{
		//Bauplanobjekt erstellen
		$bauplan = new BAUPLAN($db, $_SESSION['user'], $_SESSION['kolonie']);
		$bauplan->loadBauplan($_GET['ID']);
		
		//Teile laden
		$teile_array = $bauplan->getteileArray();

		//Teile Objekt erzeugen
		$teile = new TEILE();
	
		//Teile durchlaufen
		for( $i=0; $i<count($teile_array); $i++ )
		{
			//Teildaten laden
			$teile->loadTeile($teile_array[$i], $_SESSION['user']->getRasseID());
			
			//Kosten laden
			$kosten = $teile->getKosten();	//$this->Kosten[$ID] = array('Eisen', 'Stahl', 'Titan','Kunststoff','Wasserstoff', 'Uran', 'Plutonium', 'Gold', 'Diamant', 'Bevölkerung');
			
			//Daten setzen!
			$daten_detail['BEZ'] 			= $teile->getBezeichnung();
			$daten_detail['EISEN'] 			= $kosten[0];
			$daten_detail['STAHL'] 			= $kosten[1];
			$daten_detail['TITAN'] 			= $kosten[2];
			$daten_detail['KUNSTSTOFF'] 	= $kosten[3];
			$daten_detail['WASSERSTOFF'] 	= $kosten[4];
			$daten_detail['URAN'] 			= $kosten[5];
			$daten_detail['PLUTONIUM'] 		= $kosten[6];
			$daten_detail['GOLD'] 			= $kosten[7];
			$daten_detail['DIAMANT'] 		= $kosten[8];
			$daten_detail['BEVÖLKERUNG'] 	= $kosten[9];
			$daten_detail['BAUZEIT'] 		= $teile->getFormattedBuildTime();
			
			//Typ setzen
			switch( $teile->getKategory() )
			{
				case 1:
					$daten_detail['TYP'] = "Chassis";
					break;
				case 2:
					$daten_detail['TYP'] = "Antrieb";
					break;
				case 3:
					$daten_detail['TYP'] = "Waffe";
					break;
				case 4:
					$daten_detail['TYP'] = "Munition";
					break;
				case 5:
					$daten_detail['TYP'] = "Panzerung";
					break;
			}					

			//Template setzen
			$tpl->setObject("kaserne_bauplan_detail_detail", $daten_detail);		
		}
		
		//Kosten laden
		$kosten = $bauplan->getKosten();	//$this->Kosten[$ID] = array('Eisen', 'Stahl', 'Titan','Kunststoff','Wasserstoff', 'Uran', 'Plutonium', 'Gold', 'Diamant', 'Bevölkerung');
		
		//Setze Gesamtdaten
		$daten['NAME'] 				= $bauplan->getBezeichnung();
		$daten['STAHL_GES']			= $kosten[1];
		$daten['EISEN_GES'] 		= $kosten[0];
		$daten['TITAN_GES'] 		= $kosten[2];
		$daten['KUNSTSTOFF_GES'] 	= $kosten[3];
		$daten['WASSERSTOFF_GES'] 	= $kosten[4];
		$daten['URAN_GES'] 			= $kosten[5];
		$daten['PLUTONIUM_GES'] 	= $kosten[6];
		$daten['GOLD_GES'] 			= $kosten[7];
		$daten['DIAMANT_GES'] 		= $kosten[8];
		$daten['BEVÖLKERUNG_GES'] 	= $kosten[9];
		$daten['BAUZEIT_GES']		= $bauplan->getFormattedBuildTime(1);
		
		//Setze Templatedaten
		$tpl->setObject("kaserne_bauplan_detail", $daten);
		echo $tpl->getTemplate();
	}
	else 	//Fehler Profil gehört nicht Ihnen
	{
		echo $fehler->meldung(934);
	}
	
}
else //Fehler keine ÜbergabeWerte
{
	echo $fehler->meldung(935);
}

//Objekt serialisieren
$_SESSION['user'] 			= serialize($_SESSION['user']);
$_SESSION['bevoelkerung'] 	= serialize($_SESSION['bevoelkerung']);
$_SESSION['kolonie'] 		= serialize($_SESSION['kolonie']);