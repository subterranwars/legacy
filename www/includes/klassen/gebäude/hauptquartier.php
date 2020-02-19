<?php
class HAUPTQUARTIER
extends GEBÄUDE
{

	//Deklarationen
	var $ID = 1;								//ID des Gebäudes
	var $user;									//UserObjekt
	var $ID_Kolonie;							//KolonieID
	var $grund_rohstoffe 	= array(1,2,3);		//eisen, Stein, Nahrung
	var $grund_produktion	= 25;				//pro h 25 Einheiten je Level
	var $energie_grundproduktion = 100;			//GRundproduktion an Energie von der Kolonie

    //Standardkonstruktor
	function HAUPTQUARTIER(&$user, $ID_Kolonie)
    {
    	//Debugmeldung
    	$this->debug("Erzeuge HQ - Objekt");

    	//Variablen setzen
		$this->user 		= &$user;
		$this->ID_Kolonie 	= $ID_Kolonie;
    }
    
    //lädt die Grundrohstoffproduktion
    function getGrundProduktion()
    {
    	foreach($this->grund_rohstoffe as $x)
    	{
    		$rohstoff[$x] = $this->user->getGebäudeLevel($this->ID) * $this->grund_produktion;
    	}
    	return $rohstoff;
    }
    
    //function getGrundEnergieProduktion()
    /*Lade stündliche Energieproduktion
	Hierzu muss bekannt sein, welches level das Hauptquartier hat, denn mit steigendem HQ level
	steigt auch die Grundproduktion an Energie pro Stunde.
	Ausserdem muss ermittelt werden auf welchem Level sich das Kraftwerk befindet und mit welcher Auslastung
	es betrieben wird.
	Hierzu muss die Kraftwerk-Funktion aufgerufen werden, welche die Stündliche Energieproduktion zurückggibt und 
	zugleichÖl bzw. Uran von den Grundrohstoffen abzieht.
	Ist nicht genügend Öl vorhandne, so muss das vorhanden Öl bzw. Uran vollkommen aufgebraucht werden und 
	die dafür erhältliche Energie bereit gestellt werden!*/
	//{
    //	return $this->grund_produktion * $this->user->getGebäudeLevel($this->ID);
   	//}
    
   	/*function gibt die Grundenergieproduktion der Kolonie zurück (ist Level unabhängig!)*/
   	function getEnergieProduktion()
   	{
   		return $this->energie_grundproduktion;
   	}
}