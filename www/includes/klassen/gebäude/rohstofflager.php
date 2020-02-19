<?php
class ROHSTOFFLAGER
extends GEBÄUDE
{

	//Deklarationen
	var $ID = 15;					//ID des Gebäudes
	var $level;						//Gibt den Level des Gebäudes an!
	var $grund_kapazitaet = 20000;	//Wie viele Rohstoffe sollen Standardmäßig platz haben?
	var $kapazitaet_add = 15000;	//Die Rohstofflagerung steigt um x einheiten pro level

    //Standardkonstruktor
	function ROHSTOFFLAGER($level)
    {
    	//Debugmeldung
    	$this->debug("Erzeuge ROHSTOFFLAGER - Objekt");
   	
    	//Variablen setzen
		$this->level = $level;
    }
    
    //lädt die Lagergröße
    function getKapazitaet()
    {
    	//Ladekapazität ermitteln
    	$kapazitaet = $this->grund_kapazitaet + ( $this->level * $this->kapazitaet_add );
    	return $kapazitaet;
    }
}