<?php
class THERMALKRAFTWERK
extends GEBÄUDE
{
	//Deklarationen	
	var $ID 	= 18;				//Welche ID besitzt das Thermalkraftwer? (für interne Berechnugnszwecke!)
	var $Auslastung;				//Wie hoch ist die Auslastung in % des Kraftwerkes
	var $user;						//Zeiger auf user-objekt
	var $ID_Kolonie;				//KOlonieID des Gebäudes
	var $Energie = 50;				//Gibt an wie viel GW das Kraftwerk pro Stunde erzeugt
	var $db;						//Datenbankvariable um Daten fpr dsa Kraftwerk aus der Datenbank zu holen

	function THERMALKRAFTWERK(&$db, &$user, $ID_Kolonie)
    {
    	//Debugmeldung
    	$this->debug("Erzeuge THERMALKRAFTWERK - Objekt");
    	
    	//Variablen setzen
		$this->db 			= &$db;		//Datenbankobjekt
		$this->user 		= &$user;	//Userobjekt
		$this->ID_Kolonie 	= $ID_Kolonie;	//Koloniedaten
		
		//Auslastung des Kraftwerkes laden
		$this->db->query("SELECT Auslastung FROM t_userhatgebaeude WHERE ID_Gebäude = ".$this->ID." AND ID_User = ".$this->user->getUserID()." AND ID_Kolonie = ".$this->ID_Kolonie."");
		$this->Auslastung = $this->db->fetch_result(0);
	
		//Debugmeldung
    	$this->debug("Kraftwerk des Users <i>".$this->user->getUserID()."</i> wird auf ".($this->Auslastung * 100)."% gefahren");
    } 

    function checkProduktion()
    {	
    	//DEklarationen
    	$produzierte_energie = $this->getProduktion();

    	//DEbugmeldung
    	$this->debug("Das Kraftwerk erzeugt <i>$produzierte_energie</i> GW pro Stunde");
    	
    	//Gebe tatsächliche Produktion zurück
    	return $produzierte_energie;
    }
    
    //Gibt die Energieproduktin GW/h zurück
   function getProduktion()
    {
    	//Berechne Energieproduktion pro Stunde
    	$produktion = $this->Auslastung * ($this->user->getGebäudeLevel($this->ID) * $this->Energie);
    	
    	//Debugmeldung
    	$this->debug("Die Energieproduktion pro h beträgt <i>$produktion</i> GW");
   	
    	//Rückgabewert
    	return $produktion;
    }	
}