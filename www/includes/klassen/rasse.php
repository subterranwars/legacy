<?php
class RASSE
{
    var $ID;
	var $Bezeichnung;
	var $Beschreibung;
	var $Rasse; 	//Hier werden nacher alle Rassen gespeichert! (statisch)
					//Rasse[0] = array("Bezeichnung", "Beschreibung");

	function RASSE($ID)
    {
    	//Erzeuge Rassen
        $this->Rasse[1] = array("Terraner", "leben über der Erde");
        $this->Rasse[2] = array("SubTerraner", "leben unter der Erde");
        
        //Weise Rassendaten zu!
    	$this->ID = $ID;
        $this->Bezeichnung = $this->Rasse[$ID][0];
        $this->Beschreibung = $this->Rasse[$ID][1];
    }
    
    function getBeschreibung()
    {
        return $this->Beschreibung;
    }
    
    function getBezeichnung()
    {
        return $this->Bezeichnung;
    }
    
    function getID()
    {
        return $this->ID;
    }
}?>