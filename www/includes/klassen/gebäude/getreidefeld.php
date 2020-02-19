<?php
class GETREIDEFELD
extends GEBÄUDE
{

	//Deklarationen
	var $ID = 9;					//ID des Gebäudes
	var $ID_Rohstoff = 3;
	var $user;
	var $ID_Kolonie;
	var $kolonie;					//Kolonieobjekt
	var $kapazitaet_add = 200;		//Wie viel Nahrung wird pro Level produziert?

    //Standardkonstruktor
	function GETREIDEFELD(&$user, $ID_Kolonie)
    {
    	//Debugmeldung
    	$this->debug("Erzeuge ROHSTOFFLAGER - Objekt");
   	
    	//Variablen setzen
		$this->user 		= &$user;
		$this->ID_Kolonie 	= $ID_Kolonie;
		
		//Kolonieobjekt erzeugen
		$this->kolonie = new KOLONIE($this->ID_Kolonie, $this->user->db);
    }
    
   //lädt die Lagergröße
    function getProduktion()
    {
    	//Lade Koloniestatus
    	$status = $this->kolonie->getStatus();
    	$kapazitaet_add = $this->kapazitaet_add * pow(2, $status);

    	//Ladekapazität ermitteln
    	$produktion = $kapazitaet_add * $this->user->getGebäudeLevel($this->ID); 
    	return $produktion;
    }
    
    //Gibt lastChange zurück
    function getLastChange()
    {
    	//Lade Lastchange
   		$this->user->db->query("SELECT LastChange FROM t_userhatgebaeude WHERE ID_User = ".$this->user->getUserID()." AND ID_Kolonie = ".$this->ID_Kolonie." AND ID_Gebäude = ".$this->ID."");
   		$last_change = $this->user->db->fetch_result(0);
   		
   		//Rückgabewert
   		return $last_change;
    }
    
    //Produziert nahrung
    function erzeugeNahrung($time)
    {
    	//Lade Lastchange
   		$last_change = $this->getLastChange();
   		
    	//Differenz ermitteln
    	$differenz = $time - $last_change;	//in Sekunden
    	$differenz = $differenz / 3600;		//in Stunden
    	
    	//Ermittle Gesamtproduktion an Nahrung!
    	$produktion = $this->getProduktion() * $differenz;
    	$this->user->setRohstoffAnzahl($produktion, $this->ID_Rohstoff);
    	//Setze LastChange
    	$this->user->db->query("UPDATE t_userhatgebaeude SET LastChange = $time WHERE ID_User = ".$this->user->getUserID()." AND ID_Kolonie = ".$this->ID_Kolonie." AND ID_Gebäude = ".$this->ID."");
    }
}?>