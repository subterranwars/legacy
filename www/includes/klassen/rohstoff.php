<?php
class ROHSTOFF
{  
    var $ID;
	var $Bezeichnung;
	var $Beschreibung;
	var $Häufigkeit;
	var $ID_Rasse;
	var $Rohstoffe;	//Array auf Rohstoffe
						//$Rohstoffe[1] = array("Bezeichnung", "Beschreibung", "Häufigkeit", "ID_Rasse");
	var $Requirement;
					
	function ROHSTOFF()
    {
        //Setze Rohstoffdaten
        //$Rohstoffe[1] = array("Bezeichnung", "Beschreibung", "Häufigkeit", "ID_Rasse");
        $this->Rohstoffe[1]	= array("Eisen", "Eisen wird für den Bau von Gebäuden benötigt. Abgebaut wird es unter der Erde und muss mit der Hilfe von Sammlern abgeholt werden.", 0.9, 0);
        $this->Rohstoffe[2] = array("Stein", "Nur aus Eisen lässt sich natürlich kein Gebäude bauen. Deshalb werden Steine benötigt.",0.9 ,0);
        $this->Rohstoffe[3] = array("Nahrung", "Nahrung wird in den Gewächshäusern und Getreidefeldern angebaut. Die Nahrung wird für die Bevölkerung benötigt. Deshalb sollte darauf geachtet werden, dass wenn Sie eines der Gebäude zur erhöhten Produktion der Bevölkerung ausbauen, stets genügend Nahrung haben, ansonsten werden die Bewohner sterben und ohne Bewohner können Sie keine Armee aufstellen und ohne Armee ist es Ihnen nicht möglich ihre Stadt vor feindlich gesinnten Mitstreitern zu verteidigen.", 1, 0);
        $this->Rohstoffe[4] = array("Öl", "Öl stellt nicht nur in unserer Welt eines der wichtigsten Rohstoffe dar, sondern auch im Spiel wird es eines der wichtigsten Rohstoffe sein. Aus ihm lässt sich Kunststoff gewinnen. Allerdings lässt sich dieser Rohstoff unterschiedlich schwer abbauen. Die Höhlenbewohner haben hier einen klaren Vorteil, welcher aber durch die schlechte Nahrungsproduktion wieder ausgeglichen wird", 0.7, 0);
 		$this->Rohstoffe[5] = array("Kunststoff", "dieser ist eine Spezialmischung aus öl und Schwefel. besonders gut bei Waffen der infanterie.", 1, 0);       
        $this->Rohstoffe[6] = array("Stahl", "Stahl wird in der Eisenschmelze erzeugt und für die Produktion von gepanzerten Fahrzeugen sowie Verteidigungsanlagen benötigt. ", 1, 0);
        $this->Rohstoffe[7] = array("Titanerz", "", 0.65, 0);
        $this->Rohstoffe[8] = array("Titan", "Titan kann leider nicht abgebaut werden, sondern wird in der Schmelze veredelt und bildet somit die Basis für die Entwicklung bzw. Produktion von Raumschiffen oder schlagkräftiges Kriegsarsenal.", 1, 0);
        $this->Rohstoffe[9] = array("Wasser", "", 0.8, 0);
        $this->Rohstoffe[10]= array("Wasserstoff", "Wasserstoff wird für die Produktion von Einheiten und Verteidigungsanlagen benötigt.", 1, 0);
        $this->Rohstoffe[11] = array("Uran", "Wer gerne Krieg führt, wird an dem Rohstoff Uran nicht vorbeikommen. Uran wird für viele Forschungen und auch für viele Ausrüstungsgegenstände benötigt, wie z.B. Munitionstypen, oder bestimmte Fahrzeuge", 0.35, 0);
        $this->Rohstoffe[12] = array("Plutonium", "Plutonium ist auch ein radioaktiver Rohstoff, welcher genau wie Uran für diverse Forschungen und Ausrüstungsgegenstände benötigt wird.", 1, 0);        
        $this->Rohstoffe[13] = array("Diamant", "Kann nur unterirdisch abgebaut werden, weshalb die Bewohner über der Erde nur schwer an diesen Rohstoff gelangen. Allerdings wird er benötigt um spezielles Werkzeug zu entwickeln, welches für die Produktion von Waffen o.ä. benötigt wird.Diamanten sind sehr wertvoll und deshalb wird jeder Spieler sich glücklich Schätzen diesen Rohstoff als sein eigen nennen zu dürfen.", 0.15, 2);
        $this->Rohstoffe[14] = array("Gold", "Gold ist ebenfalls eines der wertvollsten Rohstoffe überhaupt im Spiel. Er wird für spezielle Waffen oder auch Werkzeuge benötigt. Nur den Bewohnern auf der Erdoberfläche ist es möglich diesen Rohstoff zu fördern.", 0.15, 1);
        
        //Voraussetzungen
        /*
        	1 = kolonie-status
        		1 = dorf
        		2 = kleinstadt
        		3 = stadt
        		4 = großstadt
        		5 = metropole
        	2 = forschung
        	3 = gebäude
        	4 = rasse
        */ 
        //$this->Requirement[1] = array("typ|ID|lvl") =>typ steht für kolonie-status, forschung oder gebäude
    	$this->Requirement[1] = array();
    	$this->Requirement[2] = array();
    	$this->Requirement[3] = array();
    	$this->Requirement[4] = array();
    	$this->Requirement[5] = array();
    	$this->Requirement[6] = array();
    	$this->Requirement[7] = array('2|24|1');
		$this->Requirement[8] = array();
		$this->Requirement[9] = array();
		$this->Requirement[10] = array();
		$this->Requirement[11] = array('2|55|1');
		$this->Requirement[12] = array();
		$this->Requirement[13] = array('2|64|1', '4||2');
		$this->Requirement[14] = array('2|64|1', '4||1');
    }
    
    function loadRohstoff($ID)
    {
    	//Rohstoffadten holen
        $this->ID 			= $ID;
        $this->Bezeichnung 	= $this->Rohstoffe[$ID][0];
        $this->Beschreibung	= $this->Rohstoffe[$ID][1];
        $this->Häufigkeit 	= $this->Rohstoffe[$ID][2];
        $this->ID_Rasse		= $this->Rohstoffe[$ID][3]; 
    }
    
    function getRohstoffArray()
    {
    	return $this->Rohstoffe;
    }
    
    function getBezeichnung()
    {
        return $this->Bezeichnung;
    }
    
    function getBeschreibung()
    {
        return $this->Beschreibung;
    }
    
   	function getHäufigkeit()
    {
        return $this->Häufigkeit;
    }
    
    /*Gibt Requirement zurück*/
    function getRequirement()
    {
    	return $this->Requirement[$this->ID];
    }
    
    /*Funktion überprüft ob Gebäude gebaut werden kann!*/
    function checkRequirement(&$user, $ID_Kolonie)
    {
    	//Lade Requirements
    	$erfuellt = 1;		//Gebäude kann gebaut werden!
    	$requirement = $this->getRequirement();
    	
    	//Durchlaufe Requirement
		for( $a=0; $a<count($requirement); $a++ )
		{		
			//Array zersetzen
			$requirement_detail = explode("|", $requirement[$a]);
			/*	$requirement_detail[0]
					1 = kolonie-status
					2 = forschung
					3 = gebäude	
				$requirement_detail[1] 
					ID
				$requirement_detail[2]
					LEVEL*/
			
			//Hat User die Voraussetzung
			switch( $requirement_detail[0] )
			{
				//Koloniestatus
				case 1:
					//Neue Datenbank verbindung herstellen
					$db = new DATENBANK();
					//Neues Kolonieobjekt erzeugen
					$kolo = new KOLONIE($ID_Kolonie, $db);
					$lvl = $kolo->getStatus();	
					break;
				//Forschung
				case 2:	
					$lvl = $user->getForschungsLevel($requirement_detail[1]);
					break;
				//Gebäude
				case 3:
					$lvl = $user->getGebäudeLevel($requirement_detail[1]);
					break;
				//Rasse
				case 4:
					$lvl = $user->getRasseID();
					break;
			}
										
			//ERfüllt User Requirement?
			if( $lvl < $requirement_detail[2] AND $requirement_detail[0] != 4 )	//Rasse muss extra abgefragt werden
			{
				$erfuellt = -1;
			}
			//Handelt es sich um die Kategory 'Rasse?'
			elseif( $requirement_detail[0] == 4 )	//Kategory = Rasse
			{
				//Rasse entspricht nicht der Requirement
				if( $lvl != $requirement_detail[2] )
				{
					$erfuellt = -1;
				}
			}
		}
		
		//Rückgabewert!
		return $erfuellt;
    }
}?>