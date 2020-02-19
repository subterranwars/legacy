<?PHP
/*Klasse ist für Forscher zuständig und ermittelt wie viele Forschungspunkte produziert
werden und wie viele forscher vorhanden sind!

History:
			MvR		13.08.2004		created
*/
class FORSCHER
{
	//Deklarationen
	var $Forscher;			//Anzahl der Forscher
	var $Forschungspunkte;	//vorhandenen Forschungspunkte
	var $LastChange;		//Wann wurden die Forschungspunkte das letzte mal aktuallisiert
	var $db;				//Db objekt
	var $ID_User;				//UserObjekt
	
	/*Standardkonstruktor*/
	function FORSCHER(&$db, $ID_User)
	{
		//Datenbankverbindung setzen!
		$this->db = &$db;
		$this->ID_User = $ID_User;
		
		//Forschungsdaten laden
		$this->db->query("SELECT Forscher, Forschungspunkte, LastChange FROM t_user WHERE ID_User = ".$this->ID_User."");
		$ergebnis = $this->db->fetch_array();
		
		//Daten setzen
		$this->Forschungspunkte = $ergebnis['Forschungspunkte'];
		$this->Forscher			= $ergebnis['Forscher'];
		$this->LastChange		= $ergebnis['LastChange'];
	}

	/*gibt Anzahl Forscher zurück*/
    function getForscher()
    {
    	return $this->Forscher;
    }
    
    /*Setzt Anzahl Forscher!*/
    function setForscher($anzahl)
    {
    	$this->Forscher = $anzahl;
    	$this->db->query("UPDATE t_user SET Forscher = $anzahl WHERE ID_User = ".$this->ID_User."");
    }
    
    /*Gibt Anzahl Forschungspunkte zurück*/
    function getForschungspunkte()
    {
    	return floor($this->Forschungspunkte);
    }
    
    /*Setzt Forschungspunkte auf neues Niveau*/
    function setForschungspunkte($neuer_wert)
    {
    	$this->Forschungspunkte = $neuer_wert;
    	$this->db->query("UPDATE t_user SET Forschungspunkte = $neuer_wert WHERE ID_User = ".$this->ID_User."");
    }
    
    /*Funktion ermittelt die Stündliche Forscherpunkte produktion*/
    function getForschungspunkteProduktion()
    {
    	//Deklarationen
    	$produktion_pro_stunde = 2;		//Punkte pro Stunde pro Forscher
    	
    	//Produktion wird ermittelt
    	$produktion = $this->Forscher * $produktion_pro_stunde;
    	return $produktion;
    }
    
    /*Updatet die Forschungspunkte in einem gewissen Zeitraum*/
    function updateForschungspunkte($time)
    {
    	//Ermittle Differenz in Sekunden
    	$differenz = $time - $this->LastChange;
    	//Differenz in Stunden!
    	$differenz = $differenz / 3600;
    	
    	//Produziere Forschungspunkte
    	$produktion = $this->getForschungspunkteProduktion() * $differenz;
    	
    	//Werte aktuallisieren
    	$this->Forschungspunkte += $produktion;
    	$this->db->query("UPDATE t_user SET Forschungspunkte = (Forschungspunkte + $produktion) WHERE ID_User = ".$this->ID_User."");
    	
    	//LastChange setzen
    	$this->LastChange = $time;
    	$this->db->query("UPDATE t_user SET LastChange = $time WHERE ID_User = ".$this->ID_User."");
    }
    
    /*Funktion ermittelt Kosten für den nächsten Wissenschaftler
    $forshcungszentrale_level ist zur ForscherKosten-Optimierung da, denn
    mit steigendem Level der Forschugnszentrale sollten die Kosten sinken!
    */
    function getForscherKosten($forschungszentrale_level)
    {
    	/*Formel:
    		Steigerung um 80% pro Wissenschaftler
    	
    	Kosten sind Nahrung!*/
    	
    	//Deklarationen
    	$kosten_standard = 100;	//Wie viel Nahrung kostet der Wissenschaftler lvl1?
    	    	
    	//Wie viele Forscher sind da?
    	if( $this->Forscher == 0 )
    	{
    		//kein Faktor!
    		$kosten = $kosten_standard;
    	}
    	else 
    	{
	    	//Kostenberechnung
	    	$kosten = ($kosten_standard / $forschungszentrale_level) * pow(1.09,$this->Forscher-1);
	    	$kosten = round($kosten);
    	}
    	
    	//Rückgabe
    	return $kosten;
    }
    
    /*Ermittelt die Forscherbauzeit*/
    function getAusbildungsZeit($forschungszentrale_level)
    {
    	//Ausbildungszeit für 1. Forscher
    	$ausbildungs_zeit_standard = 600;	//ca. 5 minuten
    	
    	//Berechne Zeit
    	//$ausbildungszeit = ($ausbildungs_zeit_standard / $forschungszentrale_level) * pow(1.10, ($this->Forscher-1));
    	$ausbildungszeit = $ausbildungs_zeit_standard * pow(1.5,1.1*($this->Forscher-1)/$forschungszentrale_level);
    	
    	//Zeit durch 5 teilbar
    	$differenz = $ausbildungszeit % 10;
    	$ausbildungszeit -= $differenz;
    	
    	return $ausbildungszeit;
    }
    
    /*ermittelt formatierte Ausbildungszeit*/
    function getFormattedAusbildungsZeit($forschungszentrale_level)
    {
    	$sekunden = $this->getAusbildungsZeit($forschungszentrale_level);
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

    /*Beende Ausbildung*/
    /*function finishAusbildung($finish_time)
    {
    	$this->db->query("SELECT ID_Kolonie FROM t_userbautforscher WHERE ID_User = ".$this->ID_User." AND FinishTime <= $finish_time");
    	if( $this->db->num_rows() > 0 )
    	{
    		//Ergebnis laden
    		$ID_Kolonie = $this->db->fetch_result(0);
    		
    		//Forscher erhöhen
    		$this->setForscher($this->Forscher+1);
    		
    		//Ausbildung entfernen
    		$this->db->query("DELETE FROM t_userbautforscher WHERE ID_User = ".$this->ID_User." AND ID_Kolonie = $ID_Kolonie");	
    	}
    }*/   
}?>