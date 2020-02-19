<?php
class SICHERHEITSLAGER
extends GEBÄUDE
{

	//Deklarationen
	var $ID = 16;					//ID des Gebäudes
	var $level;						//Gibt den Level des Gebäudes an!
	var $grund_kapazitaet = 0;	//Wie viele Rohstoffe sollen Standardmäßig platz haben?
	var $kapazitaet_add = 5000;		//Die Rohstofflagerung steigt um 5000 pro level
	var $radioaktive_rohstoffe = array(11, 12);	//Welche Rohstoffe sind radioaktiv?
												//11 = Uran
												//12 = Plutonium

    //Standardkonstruktor
	function SICHERHEITSLAGER($level)
    {
    	//Debugmeldung
    	$this->debug("Erzeuge ROHSTOFFLAGER - Objekt");
    	
    	//Variablen setzen
		$this->level = $level;
    }
    
    /*Überprüft ob übergebener Rohstoff radioaktiv ist!
    Gibt 1 bei erfolg und -1 bei fehler zurück*/
    function checkRadioaktivitaet($ID_Rohstoff)
    {
    	//Deklarationen
    	$error = -1;	//Standardmäßig keine Radioaktivität gegeben!
    	
    	//Alle Rohstoffe durchlaufen!
    	for( $i=0; $i<count( $this->radioaktive_rohstoffe); $i++ )
    	{
    		//Rohstoff ist RAdioaktiv
    		if( $this->radioaktive_rohstoffe[$i] == $ID_Rohstoff )
    		{
    			$error = 1;	//RAdioaktivität gegeben
    			break;		//foreach beenden
    		}
	   	}
   		return $error;
    }
    		
    //lädt die Lagergröße
    function getKapazitaet()
    {
    	//Ladekapazität ermitteln
    	$kapazitaet = ($this->grund_kapazitaet) + ( $this->level * $this->kapazitaet_add );
    	return $kapazitaet;
    }
}