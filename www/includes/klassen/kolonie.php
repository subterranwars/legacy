<?php
class KOLONIE
{
	var $ID;
	var $Bezeichnung;
	var $Status;
	var $ID_User;
	var $db;
	var $ID_Koordinaten;
	var $x;
	var $y;
	var $z;
	var $Hauptquartier;
	var $Energieniveau;
	var $kolonie_status;		//Koloniestaten
	var $kolonie_requirement;	//Was braucht man für die nächste Kolonie!
	var $kosten;				//Kosten, welche benötigt werden um Kolonie upzugraden
	var $GebäudePunkte;			//Gebäudepunkte
	var $MilitärPunkte;			//Militärpunkte
	
	function KOLONIE($ID, &$db)
	{
		//Koloniedaten setzen!
		$this->ID = $ID;
		$this->db = &$db;
		
		$this->db->query("SELECT * from t_kolonie, t_koordinaten WHERE t_kolonie.ID_Kolonie = ".$this->ID." AND t_kolonie.ID_Koordinaten = t_koordinaten.ID_Koordinaten");
		$result = $this->db->fetch_array();
		
		$this->Bezeichnung 	= $result['Bezeichnung'];
		$this->Status		= $result['Status'];
		$this->ID_User		= $result['ID_User'];
		$this->x			= $result['X'];
		$this->y			= $result['Y'];
		$this->z			= $result['Z'];
		$this->Energieniveau= $result['Energieniveau'];
		$this->Hauptquartier= $result['Hauptquartier'];
		$this->GebäudePunkte= $result['GebäudePunkte'];
		$this->MilitärPunkte= $result['MilitärPunkte'];
		
		//Koloniestaten erzeugen
		$this->kolonie_status = array(
    		1 => 'Dorf',
    		2 => 'Kleinstadt',
    		3 => 'Stadt',
    		4 => 'Großstadt',
    		5 => 'Metropole');
       	
    	/*KolonieRequirement setzen!*/
    	//$this->kolonie_requirement[1] = array("typ|id|level")
    		/*
    		2 = forschung
        	3 = gebäude
        	4 = bewohner*/
    	$this->kolonie_requirement[1] = array();
    	$this->kolonie_requirement[2] = array('3|1|4', '3|7|4', '4||500');
    	$this->kolonie_requirement[3] = array('3|1|6', '3|7|8', '4||3000');
    	$this->kolonie_requirement[4] = array('3|31|1', '4||10000');
    	$this->kolonie_requirement[5] = array('4||28000');
    	
    	//Kosten
    	//$this->kosten[status] = array('ID|Anzahl');
    	$this->kosten[1] = array();
    	$this->kosten[2] = array('1|15000', '2|15000');
    	$this->kosten[3] = array('1|40000', '2|35000', '6|15000', '8|7500', '5|3000');
    	$this->kosten[4] = array('1|160000', '2|120000', '6|60000', '8|45000', '5|25000', '13|5000', '14|5000');
    	$this->kosten[5] = array('1|250000', '2|225000', '6|150000', '8|120000', '5|120000', '12|25000', '13|100000', '14|100000');
	}
	
	/*gibt die KolonieId zurück*/
	function getID()
	{
		return $this->ID;
	}
		
	/*Handelt es sich bei der aktuellen Kolonie um die Hauptkolonie?*/
	function getHauptquartier()
	{
		return $this->Hauptquartier;
	}
	
	/*Gibt die Bezeichnung zurück*/
	function getBezeichnung()
	{
		return $this->Bezeichnung;
	}
	
	/*Setzt die Bezeichnung*/
	function setBezeichnung($neue_bezeichnung)
	{
		//ÜBerprüfen ob Bezeichnung leer ist
		if( empty($neue_bezeichnung) )
		{
			$neue_bezeichnung = "unnamed";	//Auf Standard belassen
		}
		//Klassenvariable setzen
		$this->Bezeichnung= $neue_bezeichnung;
		
		//Db-Daten setzen
		$this->db->query("UPDATE t_kolonie SET Bezeichnung = '$neue_bezeichnung' WHERE ID_Kolonie = $this->ID");
	}
	
	/*gibt Koloniestatus zurück*/
	function getStatus()
	{
		return $this->Status;
	}
	
	/*Diese Funkion setzt den Koloniestatus*/
	function setStatus($neuer_status)
	{
		$this->Status = $neuer_status;
		$this->db->query("UPDATE t_kolonie SET Status = $neuer_status WHERE ID_Kolonie = $this->ID");
	}
	
	/*Ich bin eine Funktion und gebe den Koloniestatusnamen wieder zurück :)*/
	function getStatusName()
	{
		return $this->kolonie_status[$this->Status];
	}
		
	/*Wem gehört die Kolonie?!*/
	function getUserID()
	{
		return $this->ID_User;
	}
	
	/*Gibt Koordinaten schön hübsch wieder zurück :)*/
	function getKoordinaten()
	{
		return $this->x.":".$this->y.":".$this->z;
	}
	
	/*X Koordinate*/
	function getX()
	{
		return $this->x;
	}
	
	/*Y Koordinate*/
	function getY()
	{
		return $this->y;
	}
	
	/*Z Koordinaten*/
	function getZ()
	{
		return $this->z;
	}
	
	/*Gibt Die Requirement zurück*/
	function getRequirement()
	{
		return $this->kolonie_requirement[$this->Status+1];
	}
	
	/*gibt kosten zurück!*/
	function getKosten()
	{
		return $this->kosten[$this->Status+1];
	}
	
	/*gibt Bauzeit zurück!*/
	function getBuildTime()
	{
		//Grundzeit
		$grund_bauzeit = 4 * 3600;	//4h
		
		//Berechnete Bauzeit!
		return $grund_bauzeit * ($this->Status);
	}
	
	/*gibt formatirte Bauzeitausgabe zurück*/
    function getFormattedBuildTime()
    {
    	//Bauzeit in tage, stunden, minuten und sekunden ermitteln
		$sekunden = $this->getBuildTime();
		unset($tage);
		unset($stunden);
		unset($minuten);
		//Zeiten berechnen		
		if( $sekunden > 59 )
		{
			$minuten 	= floor($sekunden / 60);
			$sekunden 	= $sekunden - $minuten *60;
		}
		if( $minuten > 59 )
		{
			$stunden = floor($minuten / 60);
			$minuten = $minuten - $stunden*60;
		}
		if( $stunden > 23 )
		{
			$tage = floor($stunden / 24);
			$stunden = $stunden - $tage * 24;
		}
		//Überprüfen ob 1 oder mehrere Tage
		if( $tage == 1 )
		{
			$tage = $tage." Tag";
		}
		elseif ($tage > 1 )
		{
			$tage = $tage." Tage";
		}
		//Bauzeit als formatierter STring:
		$bauzeit = sprintf("%s %02d:%02d:%02d", $tage, $stunden, $minuten, $sekunden);
		return $bauzeit;
    }
    
    /*Funktion setzt Energieniveau*/
    function setEnergieniveau($neues_niveau)
    {
    	$this->Energieniveau = $neues_niveau;
    	$this->db->query("UPDATE t_kolonie SET Energieniveau = '$neues_niveau' WHERE ID_Kolonie = $this->ID");
    }
    
    /*Funktion gibt Energnieniveau zurück*/
    function getEnergieniveau()
    {
    	return $this->Energieniveau;
    }
    
    //Setzt Datenbankverbindung
    function setDB(&$db)
    {
    	$this->db = &$db;
    }
    
    //Setze Gebäudepunkte
    function setGebäudePunkte($pkt)
    {
    	$this->db->query("UPDATE t_kolonie SET GebäudePunkte = $pkt WHERE ID_Kolonie = $this->ID");
    	$this->GebäudePunkte = $pkt;
    }
    
    //gibt Gebäudepunkte zurück
    function getGebäudePunkte()
    {
    	return $this->GebäudePunkte;
    }
}?>