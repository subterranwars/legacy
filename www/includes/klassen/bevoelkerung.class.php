<?PHP
/*Diese Klasse stellt Funktionen zur Berechnung des Bevölkerungswachstums, sowie
den Verbrauch der Nahrung zur Verfügung.
Sollte nicht genügend Nahrung vorhanden sein, werden Bewohner sterben.

History:
			19.11.2004		MvR		created
*/

class BEVOELKERUNG
{
	//Objektvariablen
	var $db;					//Referenz auf DB-Objekt
	var $ID_Kolonie;			//Kolonie ID der aktuellen Bevölkerung
	var $ID_Nahrung = 3;		//ID_Nahrug
	var $user;					//USer ID des Users, welchem die Bevölkerung gehört!
	var $NahrungsVerbrauch = 4;	//Nahrungsverbrauch pro Einwohner
	var $bevoelkerungs_limit;	//Wie viele Bewohner haben in der Kolonie platz?
	var $ID;					//Bevölkerungs-ID
	var $bevoelkerung;			//Aktuelle Bevölkerungszahl?
	var $wachstumsrate;			//Wachstumsrate in %
	var $LastChange;			//Wann wurden die Bewohner das letzte Mal aktualisiert?
	###kann später gelöscht werden ###
	var $log_bev;

    /*Standardkonstruktor*/
    function BEVOELKERUNG(&$db, &$user, $ID_Kolonie)
    {
        //Daten setzen
        $this->db = &$db;
        $this->ID_Kolonie = $ID_Kolonie;
        $this->user = &$user;
                
        //Bevölkerungsdaten laden
       	$this->loadBevoelkerung();
    }
    
    /*Diese Funktion lädt Bevölkerungsdaten aus der Datenbank*/
	function loadBevoelkerung()
    {
        //Daten laden
        $this->db->query(
        			"SELECT 
        				ID_Bevölkerung, Bevölkerung, Wachstumsrate, LastChange
        			FROM
        				t_bevoelkerung
        			WHERE 
        				ID_User = ".$this->user->getUserID()."
        			AND
        				ID_Kolonie = ".$this->ID_Kolonie."");
        $row = $this->db->fetch_array();
        
        //Daten setzen
        $this->ID				= $row['ID_Bevölkerung'];
        $this->bevoelkerung 	= $row['Bevölkerung'];
        $this->wachstumsrate 	= $row['Wachstumsrate'];
        $this->LastChange 		= $row['LastChange'];
        
        //Lade Bevölkerungslimit
        $haus = new HAUS($this->user, $this->ID_Kolonie);
        
        //Maximale Bevölkerungszahl
        $this->bevoelkerungs_limit = $haus->getKapazitaet();
    }
    
    /*Gibt Id der Bevölkerung zurück*/
    function getID()
    {
    	return $this->ID;
    }
    
    //Gibt max bev zurück
    function getMaxBev()
    {
        return $this->bevoelkerungs_limit;
    }
    /*berechnet wie viel nahrung pro h verbraucht wird!*/
	function getResVerbrauch()
    {
        //So viel Nahrung wird pro Stunde verbraucht!    	
        $nahrungsverbrauch = $this->NahrungsVerbrauch * $this->bevoelkerung;
        return  $nahrungsverbrauch;

    }
    
    /*gibt NahrungsId zurück*/
	function getNahrungsID()
    {
        return $this->ID_Nahrung;
    }
    
    
    /*Diese Funktion fügt zum aktuellen Bevölkerungsstand '$add' Bewohner hinzu und trägt diese
	Daten in die Datenbank*/
	function setBevölkerung($add)
    {
    	//aktuelle Zeit bestimmen
        $time = time();
        
        //Daten in Datenbank aktualisieren
        $this->db->query("UPDATE t_bevoelkerung SET Bevölkerung = (Bevölkerung + $add) WHERE ID_Bevölkerung = ".$this->ID."");
        //$this->db->query("UPDATE t_bevoelkerung SET LastChange = $time WHERE ID_Bevölkerung = ".$this->ID."");
        
        //Klassenvariablen aktualisieren
        $this->bevoelkerung 	+= $add;
        //$this->LastChange = $time;
    }
    
    /*gibt Anzahl der Bevölkerung zurück*/
    function getAnzahl()
    {
    	return $this->bevoelkerung;
    }
    
    /*gibt Wachstumsrate zurück*/
    function getWachstumsrate()
    {
    	return $this->wachstumsrate;
    }
    
    /*Diese Funktion setzt die Referenz auf das Datenbank-Objekt der Klasse neu!*/
    function setDB(&$db)
    {
    	$this->db = &$db;
    }
    
    /*Diese Funktion setzt Referenz auf UserObjekt der Klasse neu!*/
    function setUser(&$user)
    {
    	$this->user = &$user;
    }
    
    /*lässt die Population der Menschen wachsen :)*/
	function erzeugeBevoelkerung($time, $nahrungs_produktion)
    {
    	//Deklarationen
    	/*$offset 		= 1;													//DEr Offset besagt, alle wie viele Stunden die Bevölkerung aktualisiert werden soll
    	$rohstofflager 	= new ROHSTOFFLAGER($this->user->getGebäudeLevel(15));	//Rohstofflager erzeugen
    	$nahrungslager	= 0;
    	$wachstum		= 0;
    	
    	//Ermittle maximalen Nahrungslagerbestand
    	$nahrungslager = $this->user->getRohstoffAnzahl($this->ID_Nahrung) + $nahrungs_produktion;
    	
    	//Ermittle wie viele Bewohner bei dem Nahrungsbestand leben können :)
    	$bewohner_theoretisch = $nahrungslager / $this->NahrungsVerbrauch;
    	
    	//Sollten $bewohner_theoretisch kleiner sein als aktuelle Bevölkerung, so müssen Bewohner sterben
    	if( $bewohner_theoretisch < $this->bevoelkerung )	//zu wenig nahrung vorhanden. Bewohner ausrotten
    	{
    		//Abzug ermitteln
    		$abzug = $this->bevoelkerung - $bewohner_theoretisch-1;
    		
    		//Bevölkerung dezimieren    		
    		$this->setBevölkerung((-1)*$abzug);
    	}
    	else 
    	{
    		//Ermitteln des Bevölkerungswachstums über den Zeitraum ($time - $this->LastChange) in h
    		$wachstum = ($this->bevoelkerung*$this->wachstumsrate) / $offset;
    		$wachstum = $wachstum * (($time-$this->LastChange)/3600);
    		
    		//Kann neue Bevölkerung versorgt werden? und ist überhaupt genügend Wohnraum da?
    		if( ($wachstum + $this->bevoelkerung) > $this->bevoelkerungs_limit )
    		{
    			//neue Bevölkerung übersteigt Bevölkerungslimit
    			$wachstum = $this->bevoelkerungs_limit - $this->bevoelkerung;
    			
    			//Wachstum muss min 1 sein
    			if( $wachstum < 1 )
    			{
    				$wachstum = 0;
    			}
    		}
    		elseif( ($wachstum + $this->bevoelkerung) > $bewohner_theoretisch )
    		{
    			//Nicht genügend Nahrung für neue Bevölkerungszahl wachstum korrigieren
    			$wachstum = $bewohner_theoretisch - $this->bevoelkerung;
    			
    			//Wachstum muss min 1 sein
    			if( $wachstum < 1 )
    			{
    				$wachstum = 0;
    			}
    		}
    	}
    	
    	//Setze Bevölkerung
    	$this->setBevölkerung($wachstum);
        	
    	//Nun Nahrung für Zeitraum abziehen
    	$verbrauch = $this->getResVerbrauch() * (($time - $this->LastChange) / 3600 );
    	
    	 //ÜBerprüfen ob theoretischer Lagerbestand > als maximales lager ist
        if( ($nahrungslager-$verbrauch) >= $rohstofflager->getKapazitaet() )
        {
        	//Es wird mehr produziert als verbraucht. Der Verbrauch ist folglich 0 !
        	$verbrauch = 0;
        }
        //ÜBerprüfen ob Verbrauch > Kapazität ist
        elseif( $verbrauch > $this->user->getRohstoffAnzahl($this->ID_Nahrung) )
        {
        	$verbrauch = $this->user->getRohstoffAnzahl($this->ID_Nahrung);
        }
        //Nahrung abziehen
        $this->user->setRohstoffAnzahl($verbrauch*(-1), $this->ID_Nahrung);
                       
        //Aktualisiere LastChange
        $this->db->query("UPDATE t_bevoelkerung SET LastChange = $time WHERE ID_Bevölkerung = $this->ID");
        $this->LastChange = $time;*/
    	
        //Deklarationen
        $offset = 1;	//Der OFfset besagt, alle wie viele Stunden die Bevölkerung aktualisiert werden soll
            	
		//Log
		$this->log_bev = new LOG($this->user->getUserID()."_bevoelkerung.txt");
		$this->log_bev->write("### Bevölkerungs update ###");
		$this->log_bev->write("Alte Bevölkerung: ".$this->getAnzahl());
        
        //Lasse Bevölkerung wachsen!
        $wachstum = $this->bevoelkerung * $this->wachstumsrate;
        //Wachstum pro Stunde bestimmen
        $wachstum_pro_stunde = $wachstum / $offset;	
        
        //Wachstum über den Zeitraum noch setzen
        $wachstum = $wachstum_pro_stunde * ((($time - $this->LastChange) / 3600));	
        
        //Log
        $this->log_bev->write("aktuelle Zeit: ".date("d.m.Y H:i:s", $time)." ($time) | LastChange: ".date("d.m.Y H:i:s", $this->LastChange)." ($this->LastChange) | Differenz: ".($time-$this->LastChange)."");
		$this->log_bev->write("Wachstumsrate: ".$this->wachstumsrate." | Wachstum: ".$wachstum." Bevölkerungslimit: ".$this->bevoelkerungs_limit);
		
		//Ist Wachstum korrekt?
        if(  ($wachstum  + $this->bevoelkerung) > $this->bevoelkerungs_limit )
        {
            //Wachstum anpassen!
            $wachstum = $this->bevoelkerungs_limit - $this->bevoelkerung;
            //Wenn Wachstum < 1 dann wachstum =0
            if( $wachstum < 1 )
            {
                $wachstum = 0;
            }
            
            //Log
            $this->log_bev->write("Zu wenig Platz vorhanden... Wachstum wird korrigiert auf $wachstum");
        }
        //Nahrung verbrauchen
        $verbrauch = $this->getResVerbrauch() * (($time - $this->LastChange) / 3600 );
        
        //EBerechne theoreitschen Lagerbestand
        $lager_bestand_theoretisch = $this->user->getRohstoffAnzahl($this->ID_Nahrung) + $nahrungs_produktion - $verbrauch;
        
        //Rohstofflager erzeugen
        $rohstofflager 		= new ROHSTOFFLAGER($this->user->getGebäudeLevel(15));	//Rohstofflager
        
        //ÜBerprüfen ob theoretischer Lagerbestand > als maximales lager ist
        if( $lager_bestand_theoretisch >= $rohstofflager->getKapazitaet() )
        {
        	//Es wird mehr produziert als verbraucht. Der Verbrauch ist folglich 0 !
        	$verbrauch = 0;
        }
        
        //Überprüfen ob genügend Nahrung vorhanden ist!
        if( ($this->user->getRohstoffAnzahl($this->ID_Nahrung)) < $verbrauch )
        {
            //zu wenig Nahrung, Nahrung auf 0 setzen
            $verbrauch = $this->user->getRohstoffAnzahl($this->ID_Nahrung);
                		
            //Wie viele leute sterben?
            //Sterberate 10%!
            $sterbe_rate = $this->bevoelkerung * 0.1 * ((($time - $this->LastChange)) / 3600 );    		
                	
            //liegt sterbe-rate über der bevölkerungsanzahl?
            if( $this->bevoelkerung - $sterbe_rate < 1 )	
            {
            	$sterbe_rate = $this->bevoelkerung-1;
            }
                       
            //Zu wenig nahrung vorhanden... leute verrecken lassen 10%!
            $this->setBevölkerung((-1) * $sterbe_rate);
        }
            
        //Log
        $this->log_bev->write("Nahrungsproduktion: $nahrungs_produktion");
        $this->log_bev->write("Nahrungslager: ".$this->user->getRohstoffAnzahl($this->ID_Nahrung)."");
        $this->log_bev->write("Verbrauch: $verbrauch");
        $this->log_bev->write("Lager Gesamt: ".($nahrungs_produktion+$this->user->getRohstoffAnzahl($this->ID_Nahrung))."");
        $this->log_bev->write("Lager Theorie: $lager_bestand_theoretisch");
            
        //Nahrung abziehen
        $this->user->setRohstoffAnzahl($verbrauch*-1, $this->ID_Nahrung);
               
        //Neue Bevölkerung setzen
        $this->setBevölkerung($wachstum);
                
        //Aktualisiere LastChange
        $this->db->query("UPDATE t_bevoelkerung SET LastChange = $time WHERE ID_Bevölkerung = $this->ID");
        $this->LastChange = $time;
        
        //Log
		$this->log_bev->write("neue Bevölkeörung: ".$this->getAnzahl());
        
        //Rückgabewert
        return $lager_bestand_theoretisch;
    }
}?>