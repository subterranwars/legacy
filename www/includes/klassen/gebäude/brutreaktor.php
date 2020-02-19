<?php
class BRUTREAKTOR
extends GEBÄUDE
{

	//Deklarationen
	var $Faktor = 1;				//Umrechnugnsfaktor... wie viel Uran wird für eine Einheit Plutonium benötigt?
	var $Faktor_Energie = 0.25;		//Wie viel Uran wird für ein GW Energie benötigt
	var $ID = 19;					//Welche ID besitzt die brutreaktor? (für interne Berechnugnszwecke!)
	var $Auslastung;				//Wie hoch ist die Auslastung in % der brutreaktor
	var $user;						//Zeiger auf user-objekt
	var $ID_Kolonie;				//KOlonieID des Gebäudes
	var $ID_Rohstoff = 11;			//Die ID des Uran - Rohstoffes wird vverbraucht
	var $ID_Rohstoff_Gewinn = 12;	//Plutonium wird produziert
	var $Produktion_pro_level = 75;	//Wie hoch ist die produktion pro Level?
	var $Energie = 300;				//Wie viel Energie wird pro Stunde pro Level produziert?
	var $db;						//Datenbankvariable
	var $ENERGIENIVEAU;				//Speichert das ENERGIENIVEAU

	//Standardkonstruktor :)
   function BRUTREAKTOR(&$db, &$user, $ID_Kolonie, $ENERGIE)
    {
    	//Debugmeldung
	   	$this->debug("Erzeuge BRUTREAKTOR - Objekt");
    	
    	//Variablen setzen
		$this->db 				= &$db;			//Datenbankobjekt
		$this->user 			= &$user;		//Userobjekt
		$this->ID_Kolonie 		= $ID_Kolonie;	//Koloniedaten
		$this->ENERGIENIVEAU	= $ENERGIE;		//Energieniveau ;)
		
		//Auslastung der brutreaktor laden
		$this->db->query("SELECT Auslastung FROM t_userhatgebaeude WHERE ID_Gebäude = ".$this->ID." AND ID_User = ".$this->user->getUserID()." AND ID_Kolonie = ".$this->ID_Kolonie."");
		$this->Auslastung = $this->db->fetch_result(0);
		
		//Debugmeldung
    	$this->debug("brutreaktor des Users <i>".$this->user->getUserID()."</i> wird auf ".($this->Auslastung * 100)."% gefahren");
    }
       
    /*Diese Funktion setzt den Energieverbrauch*/
    function setEnergieniveau($_ENERGIENIVEAU)
    {
    	$this->ENERGIENIVEAU = $_ENERGIENIVEAU;
    }
    
    function getResVerbrauch()
    {
    	/*ResVerbrauchberechnung
    	Der Verbrauch steigt pro Level um 90%
    		Formel:
    			Sn = a1 * ( 1 - q^n / ( 1 - q) )
    	*/
    	
    	//ResVerbrauch
    	$res_verbrauch = ($this->Faktor * $this->Produktion_pro_level) * (  (1 - pow(0.9, $this->user->getGebäudeLevel($this->ID))) / (1 - 0.9)) * $this->Auslastung;
    	return round($res_verbrauch);
    } 	
    
    function erzeugePlutonium($time)
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
    	
    	//Update last_change
    	$this->db->query("UPDATE t_userhatgebaeude SET LastChange = ".$time." WHERE ID_Gebäude = ".$this->ID." AND ID_User = ".$this->user->getUserID()." AND ID_Kolonie = ".$this->ID_Kolonie."");
    	
    	//Debugmeldung!
    	$this->debug("Der User verbraucht <i>".$produktion."</i> Einheiten Uran und Produziert <i>".$this->getProduktion()."</i> Einheiten Plutonium pro Stunde");
    }  

    #### Funktionen zur Energieberechnung ####
    function checkProduktionEnergie()
    {	
    	/*//DEklarationen
    	$produzierte_energie = 0;
    	
    	//Ressourcenverbrauch innerhalb einer Stunde
    	$res_verbrauch = $this->getResVerbrauchEnergie();
    	
    	//Genügend Öl da?
    	if( $this->user->getRohstoffAnzahl($this->ID_Rohstoff) < $res_verbrauch )	//nein
    	{
    		//Fehler, da nicht gneügend ÖL... es wird weniger Energie produziert als erforderlich!
    		$produzierte_energie = ($this->user->getRohstoffAnzahl($this->ID_Rohstoff) / $this->getProduktionEnergie());
    	}
    	else 
    	{
    		$produzierte_energie = $this->getProduktionEnergie();
    	}
    	
    	//DEbugmeldung
    	$this->debug("Das Kraftwerk erzeugt <i>$produzierte_energie</i> GW pro Stunde");
    	
    	//Gebe tatsächliche Produktion zurück
    	return $produzierte_energie;*/
    
    	//DEklarationen
    	$produzierte_energie = 0;
    	    
    	//Genügend Uran da?
    	if( $this->user->getRohstoffAnzahl($this->ID_Rohstoff) > 0 )	//ja
    	{
    		$produzierte_energie = $this->getProduktion();
    	}
    	
    	//DEbugmeldung
    	$this->debug("Das Kraftwerk erzeugt <i>$produzierte_energie</i> GW pro Stunde");
    	
    	//Gebe tatsächliche Produktion zurück
    	return $produzierte_energie;
    }
    
    //Gibt die Energieproduktin GW/h zurück
    function getProduktionEnergie()
    {
    	//Berechne Energieproduktion pro Stunde
    	$produktion = $this->Auslastung * ($this->user->getGebäudeLevel($this->ID) * $this->Energie);
    	
    	//Debugmeldung
    	$this->debug("Die Energieproduktion pro h beträgt <i>$produktion</i> GW");
    	
    	//Rückgabewert
    	return $produktion;
    }
            
    function getResVerbrauchEnergie()
    {
    	/*ResVerbrauchberechnung
    	Der Öl Verbrauch steigt pro Level um 90%
    		Formel:
    			Sn = a1 * ( 1 - q^n / ( 1 - q) )
    	*/
    	
    	//ResVerbrauch
    	$res_verbrauch = ($this->Faktor_Energie * $this->Energie) * (  (1 - pow(0.925, $this->user->getGebäudeLevel($this->ID))) / (1 - 0.925)) * $this->Auslastung;
    	return round($res_verbrauch);
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
    	$res_verbrauch = $this->getResVerbrauchEnergie() * $differenz;
    	    	 	
    	//Überprüfen ob genügend Öl vorhanden sind um die Energie zu produzieren
    	if( $this->user->getRohstoffAnzahl($this->ID_Rohstoff) < $res_verbrauch )
    	{
    		//Zu wenig Rohstoffe
    		$produktion = $this->user->getRohstoffAnzahl($this->ID_Rohstoff);
    	}
    	else 
    	{
    	    //Uranverbrauch setzen!
    		$produktion = $this->getResVerbrauchEnergie() * $differenz;
    	}
    	
    	//Ölverbrauch vom Öl abziehen
    	$this->user->setRohstoffAnzahl((-1)*$produktion, $this->ID_Rohstoff);	
    	//Update last_change
    	//$this->db->query("UPDATE t_userhatgebaeude SET LastChange = ".$time." WHERE ID_Gebäude = ".$this->ID." AND ID_User = ".$this->user->getUserID()." AND ID_Kolonie = ".$this->ID_Kolonie."");
    	
    	//Debugmeldung!
    	$this->debug("Der User verbraucht <i>".$produktion."</i> Einheiten Öl und Produziert <i>".$this->getProduktionEnergie()."</i> GW Energie pro Stunde");
    }
}