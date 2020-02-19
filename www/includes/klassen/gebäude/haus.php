<?php
class HAUS
extends GEBÄUDE
{

	//Deklarationen
	var $ID = 2;					//ID des Gebäudes
	var $user;
	var $ID_Kolonie;
	var $kolonie;					//Kolonieobjekt
	var $grund_kapazitaet = 5;		//Wie viele Bewohner können vorhanden sein bei Haus lvl 0 ?
	var $kapazitaet_add = 100;		//Wie viele Bewohner kommen pro Level pro Ausbaustufe hinzu?

    //Standardkonstruktor
	function HAUS(&$user, $ID_Kolonie)
    {
    	//Debugmeldung
    	$this->debug("Erzeuge HAUS - Objekt");
    	
    	//Variablen setzen
		$this->user = &$user;
		$this->ID_Kolonie = $ID_Kolonie;
		
		//Kolonieobjekt erzeugen
		$this->kolonie = new KOLONIE($this->ID_Kolonie, $this->user->db);
    }
    
    //lädt die Hauskapazitaet
    function getKapazitaet()
    {
    	//KolonieObjekt erzeugen
    	$status = $this->kolonie->getStatus();
    	
    	//Ladekapazität ermitteln
    	$kapazitaet_add = $this->kapazitaet_add * pow(2, $status-1);
    	$kapazitaet = $this->grund_kapazitaet + ( $this->user->getGebäudeLevel($this->ID) * $kapazitaet_add );
    	return $kapazitaet;
    }
}