<?php
class SCHMELZE
extends GEBÄUDE
{

	//Deklarationen
	var $Faktor = 1.5;				//Umrechnugnsfaktor... wie viel Eisen wird für eine Einheit Stahl benötigt?
	var $ID = 10;					//Welche ID besitzt die schmelze? (für interne Berechnugnszwecke!)
	var $Auslastung;				//Wie hoch ist die Auslastung in % der Schmelze
	var $user;						//Zeiger auf user-objekt
	var $ID_Kolonie;				//KOlonieID des Gebäudes
	var $ID_Rohstoff = 1;			//Die ID des Eisen - Rohstoffes wird vverbraucht
	var $ID_Rohstoff_Gewinn = 6;	//Stahl wird produziert
	var $Produktion_pro_level = 50;	//Wie hoch ist die Kunststoffproduktion pro Level?
	var $db;						//Datenbankvariable
	var $ENERGIENIVEAU;				//Speichert das ENERGIENIVEAU

    function SCHMELZE(&$db, &$user, $ID_Kolonie, $ENERGIE)
    {
    	//Debugmeldung
    	$this->debug("Erzeuge SCHMELZE - Objekt");
    	
    	//Variablen setzen
		$this->db 				= &$db;			//Datenbankobjekt
		$this->user 			= &$user;		//Userobjekt
		$this->ID_Kolonie 		= $ID_Kolonie;	//Koloniedaten
		$this->ENERGIENIVEAU	= $ENERGIE;		//Energieniveau ;)
		
		//Auslastung der schmelze laden
		$this->db->query("SELECT Auslastung FROM t_userhatgebaeude WHERE ID_Gebäude = ".$this->ID." AND ID_User = ".$this->user->getUserID()." AND ID_Kolonie = ".$this->ID_Kolonie."");
		$this->Auslastung = $this->db->fetch_result(0);
		
		//Debugmeldung
    	$this->debug("schmelze des Users <i>".$this->user->getUserID()."</i> wird auf ".($this->Auslastung * 100)."% gefahren");
    }
     
    /*Diese Funktion setzt den Energieverbrauch*/
    function setEnergieniveau($_ENERGIENIVEAU)
    {
    	$this->ENERGIENIVEAU = $_ENERGIENIVEAU;
    }
    
    function getResVerbrauch()
    {
    	/*ResVerbrauchberechnung
    	Der Stahl Verbrauch steigt pro Level um 90%
    		Formel:
    			Sn = a1 * ( 1 - q^n / ( 1 - q) )
    	*/
    	
    	//ResVerbrauch
    	$res_verbrauch = ($this->Faktor * $this->Produktion_pro_level) * (  (1 - pow(0.9, $this->user->getGebäudeLevel($this->ID))) / (1 - 0.9)) * $this->Auslastung;
    	return $res_verbrauch;
    }
    	
    
    function erzeugeStahl($time)
    {    	
    	//Lade die Zeit, wann das Gebäude das letzte mal aktuallisiert wurde
    	$this->db->query("SELECT LastChange FROM t_userhatgebaeude WHERE ID_User = ".$this->user->getUserID()." AND ID_Kolonie = ".$this->ID_Kolonie." AND ID_Gebäude = ".$this->ID."");
    	$last_change = $this->db->fetch_result(0);
    	    	    	
    	//Ermitteln wie lange produziert werden soll
    	$differenz = $time-$last_change;	//in Sekunden
    	$differenz = $differenz / 3600;		//in Stunden
        	
    	//Wie viel Öl wird verbraucht
    	$res_verbrauch = $this->getResVerbrauch() * $differenz;
        	
    	//Überprüfen ob genügend Öl vorhanden sind um Kunststoff zu produzieren
    	if( $this->user->getRohstoffAnzahl($this->ID_Rohstoff) < $res_verbrauch )
    	{
    		//Zu wenig Rohstoffe
    		$differenz = $this->user->getRohstoffAnzahl($this->ID_Rohstoff) / $this->getResVerbrauch();
    		
    		//Ölverbrauch erneut bestimmen!
    		$res_verbrauch = $this->getResVerbrauch() * $differenz;
    	}
    	
    	//Kunststoffproduktion 
    	$produktion = $this->getProduktion() * $differenz;
        	
    	//Ölverbrauch vom Öl abziehen
    	$this->user->setRohstoffAnzahl((-1)*$res_verbrauch, $this->ID_Rohstoff);
    	//Kunstoff user gutschreiben
    	$this->user->setRohstoffAnzahl($produktion, $this->ID_Rohstoff_Gewinn);
    	
    	//echo "erzeuge $produktion Kunststoff und verbrauche $res_verbrauch innerhalb von $differenz<br>";
    	
    	//Update last_change
    	$this->db->query("UPDATE t_userhatgebaeude SET LastChange = ".$time." WHERE ID_Gebäude = ".$this->ID." AND ID_User = ".$this->user->getUserID()." AND ID_Kolonie = ".$this->ID_Kolonie."");
    	
    	//Debugmeldung!
    	$this->debug("Der User verbraucht <i>".$produktion."</i> Einheiten Öl und Produziert <i>".$this->getProduktion()."</i> Einheiten Kunststoff pro Stunde");
    }  
}