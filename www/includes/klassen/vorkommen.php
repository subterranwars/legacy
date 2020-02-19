<?php
class VORKOMMEN
{
	var $db;
	//var $kolonie;					//Zeiger auf Kolonie
	var $ID;
	var $Größe;
	var $ResLeft;
	var $ID_Rohstoff;
	var $Rohtoff;					//Rohstoffobjekt
	var $ID_User;
	var $ID_Kolonie;				//ID_Kolonie wo sich das Vorkommen befindet
	var $AnzahlLasterDrohnen;
	var $LastChange;				//Wann wurde das letzte mal rohstoffe geupdatet
	var $LastChangeDrohnen;			//Wann wurden das letzte mal drohnen aktuallisiert?
	var $maxLasterPerVorkommen = 10;
	var $minSize = 100000;
	var $maxSize = 500000;
	var $LastChangeCount = 3600;	//Es dürfen Drohnen immer nur alle x-h verändert werden
	var $ResPerLaster	= 75;		//Wie viele Rohstoffe kann ein Laster/eine Drohne pro Stunde transportieren
	var $debug = false;				//Soll debugt werden`?
    var $debug_counter = 1;			//Die wie vielte Fehlermeldung ist das?
  
    function VORKOMMEN(&$db)
    {
    	//Debugmeldungen
    	//$this->debug("Erzeuge Vorkommenobjekt...");
    	
    	//DAtenbankverbindung erstellen
        //$this->db 	= new DATENBANK();
        $this->db = &$db;
        //$this->kolonie = &$kolonie;
    }

    function loadVorkommen($ID)
    {
        //Debugmeldungen
    	//$this->debug("Lade Daten des Vorkommens mit der ID => $ID");
    	
    	//Lade Vorkommen
    	$this->db->query("SELECT * FROM t_vorkommen WHERE ID_Vorkommen = $ID;");
        $ergebnis = $this->db->fetch_array();

        $this->ID 					= $ID;
        $this->Größe 				= $ergebnis['Größe'];
        $this->ResLeft				= $ergebnis['ResLeft'];
        $this->ID_Rohstoff 			= $ergebnis['ID_Rohstoff'];
        $this->Rohstoff				= new ROHSTOFF();
        $this->Rohstoff->loadRohstoff($ergebnis['ID_Rohstoff']);
        $this->ID_User 				= $ergebnis['ID_User'];
        $this->AnzahlLasterDrohnen 	= $ergebnis['AnzahlLasterDrohnen'];
        $this->LastChange 			= $ergebnis['LastChange'];
        $this->LastChangeDrohnen	= $ergebnis['LastChangeDrohnen'];
        $this->ID_Kolonie			= $ergebnis['ID_Kolonie'];
    }
    
    function getID()
    {
    	return $this->ID;
    }

    function saveVorkommen($ID_Rohstoff, $ID_User, $ID_Kolonie, $time)
    {
		//Debugmeldungen
    	//$this->debug("Funktionsaufruf:<br>=>savevorkommen(\$ID_Rohstoff, \$ID_User, \$ID_Kolonie, \$time)<br>=>saveVorkommen($ID_Rohstoff, $ID_User, $ID_Kolonie, $time)");
    	
    	//Ermittle die Größe des Vorkommens
    	$Größe = $this->genSize();
        
		//Trage Vorkommen in Datenbank ein!
        $this->db->query("INSERT INTO t_vorkommen (Größe, ResLeft, ID_Rohstoff, ID_User, AnzahlLasterDrohnen, LastChange, ID_Kolonie) VALUES ($Größe, $Größe, $ID_Rohstoff, $ID_User, 0, $time, $ID_Kolonie);");
        
        //Lade Vorkommensdaten
        $this->loadVorkommen($this->db->last_insert());
    }

	function genSize()
    {    	
    	//Zufallszahlen ermitteln
       	$size = mt_rand($this->minSize, $this->maxSize);
        return $size;
   	}
    
    function getSize()
    {
       return $this->Größe;
    }
    
   	function setSizeLeft($neuer_wert)
    {
        //Debugmeldungen
    	//$this->debug("Funktionsaufruf:<br>=>setSizeLeft(\$neuer_wert)<br>=>setSizeLeft($neuer_wert)");
    	
   		//Setze nochzu verbleibende rohstoffe
    	$this->ResLeft = $neuer_wert;
        $this->db->query("UPDATE t_vorkommen SET ResLeft = $this->ResLeft WHERE ID_Vorkommen = $this->ID;");
    }
    
    function getSizeLeft()
    {
    	return $this->ResLeft;
    }
    
    function getRohstoffID()
    {
        return $this->ID_Rohstoff;
    }
    
   	function getRohstoffBezeichnung()
    {
        return $this->Rohstoff->getBezeichnung();
    }
    

   	function getUserID()
    {
        return $this->ID_User;
    }
   
    function getAnzahlLaster()
    {
        return $this->AnzahlLasterDrohnen;
    }
    
   	function getLastChange()
    {
        return $this->LastChange;
    }
    
    function getLastChangeDrohnen()
    {
    	return $this->LastChangeDrohnen;
    }
    
    function setLastChange($time)
    {
        $this->LastChange = $time;
        $this->db->query("UPDATE t_vorkommen SET LastChange = $this->LastChange WHERE ID_Vorkommen = $this->ID");
    }
    
    function setLastChangeDrohnen()
    {
    	$this->LastChangeDrohnen = time();
    	$this->db->query("UPDATE t_vorkommen SET LastChangeDrohnen = $this->LastChangeDrohnen WHERE ID_Vorkommen = $this->ID");
    }
         
	function setAnzahlLaster($neue_anzahl, $vorhandene_anzahl)
    /*$neue_anzahl speichert den WErt, welcher das vorkommen haben soll
    $vorhandene_anzahl speichert den Wert, wie viele Laster der User noch zur Verfügung hat*/
   	{
        //Debugmeldung
        $this->Debug("Funktionsaufruf:<br>=> setAnzahlLaster(\$neue_anzahl, \$vorhandene_anzahl)<br>=>setAnzahlLaster($neue_anzahl, $vorhandene_anzahl)");
        
    	//Deklarationen
        $error = 1;
        
        //Überprüfen ob der User genügend Drohnen hat, um die neue Anzahl zu setzen
        //$vorhanden_anzahl muss größer sein als die zu setzenden Drhonen
        if(	($vorhandene_anzahl < $neue_anzahl) && ($vorhandene_anzahl > 0))
        {
        	//Da der User nicht genügend Drohnen hat, werden die maximal
        	//zur Verfügung stehenden drohnen gesetzt!
        	$neue_anzahl = $vorhandene_anzahl;	
        }
        elseif( $vorhandene_anzahl <= 0)	//Zu wenig Vorhandene Drohnen!
        {
        	//Setze neue Anzahl auf 0
        	$neue_anzahl = 0;
        }
        
        //Findet der Bau nicht zu schnell statt?
        if( ($this->LastChangeCount + $this->LastChangeDrohnen) > time() )
        {
            //Debugmeldung
	        $this->Debug("Drohnen dürfen nur alle ".$this->LastChangeCount." Sekunden gändert werden");
        
	       	//Fehler = 131
            //Nur alle x Minuten dürfen Laster
            //gechanget werden
            $error = -1;
        }
        //Überschreiten die Drohnen nicht die maximale Anzahl pro Vorkommen?
        elseif( $neue_anzahl > $this->maxLasterPerVorkommen )
        {
        	//Debugmeldung
	        $this->Debug("Zu viele Drohnen<br>=>maximal: $this->maxLasterPerVorkommen<br>=>Ist: $neue_anzahl");
	        	        
            //Nur maximal x Laster pro
            //Vorkommen
            $error = -2;
        } 
		else 
        {            
        	//Update Anzahl
	       	$this->db->query("UPDATE t_vorkommen SET AnzahlLasterDrohnen = $neue_anzahl WHERE ID_Vorkommen = $this->ID");
        	$this->AnzahlLasterDrohnen = $neue_anzahl;
        }
		return $error;
    }

    //Gibt den Wert zurück, wie viel Rohstoffe ein Laster/Drohne pro Stunde transportieren kann
    function getResPerLaster()
    {
        return $this->ResPerLaster;
    }
    
    /*funktion gibt h-liche Rohstoffproduktion zurück*/
    function checkProduktion($_ENERGIENIVEAU)
    {
    	/*sind noch Rohstoffe im Vorkommen vorhanden?
    	wenn nein, dann wird auch nix produziert!*/
	   	//if( $this->ResLeft > 0 )
    	//{
	    	//Wichtige Daten laden!
		    $anzahl_laster	= $this->getAnzahlLaster();
		
		    //Rohstoffe aktuallisieren
		    $produktion 		= $anzahl_laster * $this->getResPerLaster();
		    //echo "<font color=\"blue\">Differenz: $differenz<br>Lasteranzahl: $anzahl_laster<br>ResPerLaster:".$vorkommen[$i]->getResPerLaster()."<hr size=\"1\" color=\"light_blue\">";
			/*
			    # Ein Laster kann z.B. 100 Res pro Stunde transportieren.
			    	--> $this->getResPerLaster()
			    # Wie viele Laster hat ein Vorkommen?
			    	--> $anzahl_laster
			    # Wie viele Rohstoffe werden also insgesamt pro Stunde gefördert?
			    	--> $produktion
			    # Beispiel:
			    	Wenn ich 7 Laster habe, wären das pro Stunde 700 Rohstoffe, von einem bestimmten Vorkommen.
			    */
					
			//Überprüfen ob der Rohstoff Öl oder Uran ist, denn dieser ist vom Energieniveau unabhängig
			if( $this->ID_Rohstoff == 4 || $this->ID_Rohstoff == 11 )
			{
				//Rohstoff ist vom Typ Öl oder Uran und ist somit Energieunabhängig!
			}
			//Überprüfen ob genügend Energie vorhanden ist... wenn nicht fördern Drohnen nur noch 25%
			elseif( $_ENERGIENIVEAU == 'critical' )
			{
				$produktion = $produktion * 0.25;
			}
    	/*}
    	else //wird nix produziert!
    	{
    		$produktion = 0;
    	}*/
		
		//Gebe Rohstoffproduktion zurück!
		return $produktion;	
    }
    
    /*Funktion gibt die Rohstoffproduktion des Vorkommens zurück!*/
    function getProduktion(&$user, $time, $_ENERGIENIVEAU)
    {    	
    	//Wichtige Daten laden!
    	//$size 			= $this->getSize();
	    //$size_left 		= $this->getSizeLeft();
	    //$last_update 	= $this->getLastChange();
	
	    //Rohstoffe aktuallisieren
	    $differenz 	= $time - $this->getLastChange();
	    $produktion = $this->checkProduktion($_ENERGIENIVEAU) * ($differenz/3600);
	    
		//Überprüfen ob das Vorkommen noch so viele Rohstoffe hat
		//if( $size_left != 0 )
		//{			
			//if( ( $produktion / $size_left ) < 1 )	//Die Produktion ist kleiner als die noch zur Verfügung stehenden Ressourcen
			//{
				//nix!
			//}
			//else //Es können mehr Rohstoffe gefördert werden, wie vorhanden sind!
			//{				
				//maximale Produktion sind somit die restlichen, noch vorhandenen Rohstoffe
			/*	$produktion = $size_left;
				
				//Alle Drohnen vom Vorkommen abziehen und diesse somit als erschöpft makieren
				//Der User kann dieses dann später löschen oder wird vom System automatsich
				//gelöscht, dies steht noch nicht agnz fest!
				$this->db->query("UPDATE t_vorkommen SET AnzahlLasterDrohnen = 0 WHERE ID_Vorkommen = ".$this->ID."");
			
				//Nachrichtendaten für User vorbereiten
				$betreff 	= "Rohstoffvorkommen erschöpft...";
				$msg 		= "Eines Ihrer <b>".$this->getRohstoffBezeichnung()."</b> Rohstoffvorkommen mit der ursprünglichen Größe von <b>".$size."</b> ist nun erschöpft. Sie müssen ein neues Vorkommen suchen!";
				
				//Nachricht versenden
				$ereignis = new EREIGNIS();
				$ereignis->saveEreignis($msg, $betreff, $_SESSION['user']->getUserID(), $_SESSION['kolonie']);
				$ereignis->setDatum($time);
				
				//Vorkommen alle, deshalb daten neu laden
				$user->loadVorkommen($this->ID_Kolonie);
			}
		}
		else 	//Vorkommen alle $produktion = 0
		{
			$produktion = 0;
		}*/
					
		//Gebe Rohstoffproduktion zurück!
		return $produktion;	
    }
    
    /*Diese funktion gibt die Lebensdauer in Sekunden zurück, wie lange noch damit zu rechnen ist, dass
    das Vorkommen bei aktuellem Energieniveau => $_ENERGIENIVEAU abgearbeitet wird*/
    function getLebensZeit($_ENERGIENIVEAU)
    {
    	/*Wenn Produktion pro Stunde = 0, dann ist Produktionszeit so lange wie die aktuelle
    	Zeit, weil es sehr unwahrscheinlich sein wird, dass ein user sich
    	ca 35 jahre nicht einloggt ;)*/
    	if( $this->getAnzahlLaster() == 0 )
    	{
    		$produktions_zeit = time();
    	}
    	else 
    	{
    		//Lade stündliche Rohstoffproduktion
    		$produktion_pro_h = $this->checkProduktion($_ENERGIENIVEAU);
    		//Ermittle Zeit, wie lange produziert werden kann (in h)
    		$produktions_zeit = $this->getSizeLeft() / $produktion_pro_h;
    		$produktions_zeit = $produktions_zeit * 3600;
    	}
    	return $produktions_zeit;
    }
    
    /*Diese Funktion nimmt alle Laster vom Vorkommen und erzeugt eine Ereignismeldung*/
    function delVorkommen($time)
    {
    	//Laster auf 0 setzen
    	$this->AnzahlLasterDrohnen = 0;
    	$this->db->query("UPDATE t_vorkommen SET AnzahlLasterDrohnen = 0 WHERE ID_Vorkommen = ".$this->ID."");
    	
    	//Ereignismeldung schreiben
    	$topic = "Vorkommen erschöpft.";
    	$meldung = "Eines Ihrer <b>".$this->Rohstoff->getBezeichnung()."</b>-Vorkommen ist erschöpft. Die ursprügliche Größe von ".$this->getSize()." Einheiten ist somit vollständig abgebaut worden.";
   
    	$ereignis = new EREIGNIS();
    	$ereignis->saveEreignis($meldung, $topic, $this->ID_User, $this->ID_Kolonie);
    	$ereignis->setDatum($time);
    }
    
    //Debugmeldungsfunktion
    function debug($text)
    {
    	//Ist Debuggen erwünscht?
    	if( $this->debug == true )
    	{
    		if($this->debug_counter == 1 )
    		{
    			echo "<hr>";
    		}
			echo "<font color=\"green\" size=\"2\"><b>###(".$this->debug_counter.") Debugging:###</b><br>$text<br></font>";
			$this->debug_counter++;
    	}
    }
}?>