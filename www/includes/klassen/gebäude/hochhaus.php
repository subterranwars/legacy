<?php
class HOCHHAUS
extends GEBÄUDE
{

	//Deklarationen
	var $ID = 3;					//ID des Gebäudes
	var $user;
	var $kapazitaet_add = 350;		//Die Rohstofflagerung steigt um 5000 pro level

    //Standardkonstruktor
	function HOCHHAUS(&$user)
    {
    	//Debugmeldung
    	$this->debug("Erzeuge HOCHHAUS - Objekt");
    	
    	//Variablen setzen
		$this->user = &$user;
    }
    
    //lädt die Hauskapazitaet
    function getKapazitaet()
    {
    	//Ladekapazität ermitteln
    	$kapazitaet = $this->user->getGebäudeLevel($this->ID) * $this->kapazitaet_add;
    	return $kapazitaet;
    }
}