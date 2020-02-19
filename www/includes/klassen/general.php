<?php
class GENERAL
{
	var $db;
	var $ID;
	var $Angriffsbonus;
	var $Verteidigungsbonus;
	var $Geschwindigkeitsbonus;
	var $Zielenbonus;
	var $Wendigkeitsbonus;
	var $Rohstoffoptimierung;
	var $Forschungsvorteil;
	var $SkillpointsLeft;
	var $Abstand = 15;
	var $ID_User;
	
   	function GENERAL(&$db)
    {
        $this->db 	= &$db;
    }
    
    function loadGeneral ($ID)
    {
        $this->db->query("SELECT * FROM t_general WHERE ID_General = $ID;");
        $ergebnis = $this->db->fetch_array();
        
        $this->ID 						= $ID;
        $this->Angriffsbonus 			= $ergebnis['Angriffsbonus'];
        $this->Verteidigungsbonus 		= $ergebnis['Verteidigungsbonus'];
        $this->Geschwindigkeitsbonus 	= $ergebnis['Geschwindigkeitsbonus'];
        $this->Zielenbonus				= $ergebnis['Zielenbonus'];
        $this->Wendigkeitsbonus			= $ergebnis['Wendigkeitsbonus'];
        $this->Rohstoffproduktionsoptimierung		= $ergebnis['Rohstoffproduktionsoptimierung'];
        $this->Forschungsvorteil		= $ergebnis['Forschungsvorteil'];
        $this->SkillpointsLeft			= $ergebnis['SkillpointsLeft'];
        $this->ID_User					= $ergebnis['ID_User'];
    }

    function getAngriffsbonus()
    {
        return $this->Angriffsbonus;
    }
    
   	function getVerteidigungsbonus()
    {
    	return $this->Verteidigungsbonus;
   	}

    function getGeschwindigkeitsbonus()
    {
        return $this->Geschwindigkeitsbonus;
    }


	function getZielenbonus()
    {
        return $this->Zielenbonus;
    }

	function getWendigkeitsbonus()
    {
        return $this->Wendigkeitsbonus;
    }

    function getRohstoffoptimierung()
    {
        return $this->Rohstoffproduktionsoptimierung;
    }

    function getForschungsbonus()
    {
        return $this->Forschungsvorteil;
    }

    function getSkillpointsLeft()
    {
        return $this->SkillpointsLeft;
    }

    function getUserID()
    {
        return $this->ID_User;
    }
    
    function getUsedSkillPoints()
    {
    	return (
    		$this->Angriffsbonus +
    		$this->Verteidigungsbonus +
    		$this->Geschwindigkeitsbonus +
    		$this->Zielenbonus +
    		$this->Wendigkeitsbonus +
    		$this->Rohstoffproduktionsoptimierung +
    		$this->Forschungsvorteil);
    }
        
    function getMaxPoints()
    {
    	//Array erstellen
    	$array = array(
    		$this->Angriffsbonus,
    		$this->Verteidigungsbonus,
    		$this->Geschwindigkeitsbonus,
    		$this->Zielenbonus,
    		$this->Wendigkeitsbonus,
    		$this->Rohstoffproduktionsoptimierung,
    		$this->Forschungsvorteil);
    		
    	//Array sortieren
    	rsort($array);
    	return $array[0];
    }
        
    function setWerte($array)
    {
        //Deklarationen
        $error = 1;
        $skill_left = $this->SkillpointsLeft;
 		$array_puffer = $array;			//Übergabearray für interne Werte sichern!
        
        //Durhclaufe übergebene Werte und addiere die vorhanden Werte hinzu!
        foreach( $array_puffer as $key => $value )
        {
        	$array_puffer[$key] += $this->$key;
        }
        
        //Min-Wert ermittlen
        sort($array_puffer);			//aufsteigend sortieren
        $min = $array_puffer[0];
        
        //Min-Wert ermitteln
        rsort($array_puffer);			//ursprungsarray absteigend sortiere
        $max = $array_puffer[0];
         
        //Sind Min und Max-Wert korrekt!
        if( ($max - $min) <= $this->Abstand )
        {
	        //Alle Skillpionts durchlaufe und von den verbleibenden abziehen
	        foreach( $array as $x )
	        {
	            $skill_left = $skill_left - $x;
	        }
	        if( $skill_left >= 0 )
	        {
	            //Durhclaufe alle Skillpoints und setze
	            //Die Skillpoints auf bestimmte gebiete
	            //und erniedrige die verbleibenden Points
	            foreach( $array as $key => $value )
	            {
	            	//Ist Wert 0?
	            	if( empty($value) )
	            	{
	            		$value = 0;
	            	}
	            	
	                //Werte erhöhen
	                $this->$key			+= $value;
	                $this->db->query("UPDATE t_general SET `$key` = ($key + $value) WHERE ID_General = $this->ID;");
	
	                //verbleibende Skillpoints löschen
	                $this->SkillpointsLeft	-= $value;
	                $this->db->query("UPDATE t_general SET SkillpointsLeft = ".$this->SkillpointsLeft." WHERE ID_General = $this->ID");
	            }
	        }
	        else 
	        {
	            //Zu viele Skillpoints gesetzt
	            $error = -1;
	        }
        }
        else 
        {
        	//Abstand stimmt nicht!
        	$error = -2;
        }
        return $error;
    }
}?>