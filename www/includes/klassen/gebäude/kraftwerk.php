<?php
class KRAFTWERK
extends GEBÄUDE
{
	//Deklarationen	
	var $Faktor = 1.5;		//Umrechnugnsfaktor... wie viel Öl wird für ein GW Energie benötigt?
	var $ID = 17;			//Welche ID besitzt das Kraftwerk? (für interne Berechnugnszwecke!)
	var $Auslastung;		//Wie hoch ist die Auslastung in % des Kraftwerkes
	var $user;				//Zeiger auf user-objekt
	var $ID_Kolonie;		//Zu welcher ID gehört das Kraftwerk?
	var $ID_Rohstoff = 4;	//Die ID des ÖL - Rohstoffes
	var $Energie = 100;		//Gibt an wie viel GW pro Stunde das Kraftwerk erzeugt!
	var $db;				//Datenbankvariable um Daten fpr dsa Kraftwerk aus der Datenbank zu holen

    function KRAFTWERK(&$db, &$user, $ID_Kolonie)
    {
    	//Debugmeldung
    	$this->debug("Erzeuge KRAFTWERK - Objekt");
    	
    	//Variablen setzen
		$this->db 			= &$db;			//Datenbankobjekt
		$this->user 		= &$user;		//Userobjekt
		$this->ID_Kolonie 	= $ID_Kolonie;	//Koloniedaten
		
		//Auslastung des Kraftwerkes laden
		$this->db->query("SELECT Auslastung FROM t_userhatgebaeude WHERE ID_Gebäude = ".$this->ID." AND ID_User = ".$this->user->getUserID()." AND ID_Kolonie = ".$this->ID_Kolonie."");
		$this->Auslastung = $this->db->fetch_result(0);
		
		//Debugmeldung
    	$this->debug("Kraftwerk des Users <i>".$this->user->getUserID()."</i> wird auf ".($this->Auslastung * 100)."% gefahren");
    } 
        
    //Gibt die Energieproduktion GW/h zurück
    function getProduktion()
    {
    	//Berechne Energieproduktion pro Stunde
    	$produktion = $this->Auslastung * ($this->user->getGebäudeLevel($this->ID) * $this->Energie);
    	
    	//Debugmeldung
    	$this->debug("Die Energieproduktion pro h beträgt <i>$produktion</i> GW");
    	
    	//Rückgabewert
    	return $produktion;
    }
            
    function getResVerbrauch()
    {
    	/*ResVerbrauchberechnung
    	Der Öl Verbrauch steigt pro Level um 92,5%
    		Formel:
    			Sn = a1 * ( 1 - q^n / ( 1 - q) )
    	*/
    	
    	//ResVerbrauch
    	$res_verbrauch = ($this->Faktor * $this->Energie) * (  (1 - pow(0.95, $this->user->getGebäudeLevel($this->ID))) / (1 - 0.95)) * $this->Auslastung;
    	return round($res_verbrauch);
    }
        
    function checkProduktion()
    {	
    	//DEklarationen
    	$produzierte_energie = 0;
    	    	
    	//Genügend Öl da?
    	if( $this->user->getRohstoffAnzahl($this->ID_Rohstoff) > 0 )	//ja
    	{
    		$produzierte_energie = $this->getProduktion();
    	}
    	
    	//DEbugmeldung
    	$this->debug("Das Kraftwerk erzeugt <i>$produzierte_energie</i> GW pro Stunde");
    	
    	//Gebe tatsächliche Produktion zurück
    	return $produzierte_energie;
    }
        	 
    function erzeugeEnergie($time)
    {    	
    	//Lade die Zeit, wann das KRaftwerk das letzte mal aktuallisiert wurde
    	$this->db->query("SELECT LastChange FROM t_userhatgebaeude WHERE ID_User = ".$this->user->getUserID()." AND ID_Kolonie = ".$this->ID_Kolonie." AND ID_Gebäude = ".$this->ID."");
    	$last_change = $this->db->fetch_result(0);
    	
    	//Differenz setzen!
    	$differenz = $time-$last_change;	//in Sekunden
    	$differenz = $differenz / 3600;		//in Stunden
    	   	    	
    	//Wie viel Öl wird verbraucht
    	$res_verbrauch = $this->getResVerbrauch() * $differenz;
    	//Überprüfen ob genügend Öl vorhanden sind um die Energie zu produzieren
    	if( $this->user->getRohstoffAnzahl($this->ID_Rohstoff) < $res_verbrauch )
    	{
    		//Zu wenig Rohstoffe
    		$produktion = $this->user->getRohstoffAnzahl($this->ID_Rohstoff);
    	}
    	else 
    	{
    		//Ölverbrauch setzen!
    		$produktion = $this->getResVerbrauch() * $differenz;
    	}
    	//Ölverbrauch vom Öl abziehen
    	$this->user->setRohstoffAnzahl((-1)*$produktion, $this->ID_Rohstoff);	

    	//Update last_change
    	$this->db->query("UPDATE t_userhatgebaeude SET LastChange = ".$time." WHERE ID_Gebäude = ".$this->ID." AND ID_User = ".$this->user->getUserID()." AND ID_Kolonie = ".$this->ID_Kolonie."");
    }    	
}