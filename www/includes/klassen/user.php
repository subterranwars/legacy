<?php
class USER
{
	var $db;
	var $ID;
	var $Nickname;
	var $Loginname;
	var $Passwort;
	var $Email;
	var $ICQ;
	var $Generalpunkte;
	//var $Militärpunkte;
	var $Avatar;
	var $LastLogin;
	var $RegisterDate;
	var $Status;
	var $ID_Rasse;
	var $ID_General;
	//var $AnzahlLaster;
	var $Eingeloggt;
	var $Teile;			//Array mit der ID der Teile, welche der User hat !
	var $General;		//Objekt:			General
	var $Rohstoffe;		//Objetk-Array: 	Rohstoffe
						//Rohstoffe[$ID][0] = Objekt
						//Rohstoffe[$ID][1] = Anzahl
						//Rohstoffe[$ID][2] = LastUpdate
	var $Gebäude;		//Array welches Objekt gebäude enthält und LVL vom Gebäude
	var $Vorkommen;		//Array, welches alle Vorkommen des Users beinhaltet
	//var $Kolonien;		//Array auf Kolonien!
	var $energieverbrauch;	//Speichert den Energieverbrauch des Users ab!
	//var $bevoelkerungsdaten;	//Speichert Bevölkerungsdaten!
	//var $bevoelkerung;			//Speichert Bevölkerungsobjekt
	var $Forscher;				//Speichert Forscherobjekt!
	var $Forschungen;			//speichert Forschungen
	var $ForschungsPunkte;		//Gibt die Pkt zurück, welche man durchs forschen erlangt hat!
	var $selectedSkin;			//Gibt den Namen des Skins zurück, welchen der User ausgewählt hat
	
    function USER($ID_User)
    {
        //Userdaten laden
        $this->db = new DATENBANK();
        $this->db->query("SELECT * FROM t_user WHERE ID_User = $ID_User;");
        $ergebnis = $this->db->fetch_array();
        
        $this->ID				= $ID_User;
        $this->Nickname 		= $ergebnis['Nickname'];
        $this->Loginname 		= $ergebnis['Loginname'];
        $this->Passwort			= $ergebnis['Passwort'];
        $this->Email			= $ergebnis['Email'];
        $this->ICQ				= $ergebnis['ICQ'];
        $this->Generalpunkte	= $ergebnis['Generalpunkte'];
        //$this->Militärpunkte	= $ergebnis['Militärpunkte'];
        $this->ForschungsPunkte	= $ergebnis['PunkteForschung'];
        $this->Avatar			= $ergebnis['Avatar'];
        $this->LastLogin		= $ergebnis['LastLogin'];
        $this->RegisterDate		= $ergebnis['RegisterDate'];
        $this->Status			= $ergebnis['Status'];
        $this->ID_Rasse			= $ergebnis['ID_Rasse'];
        $this->ID_General 		= $ergebnis['ID_General'];
        $this->selectedSkin		= $ergebnis['Skin'];
        $this->Eingeloggt		= false;
                
        //General selektieren
        $this->General = new GENERAL($this->db);
        $this->General->loadGeneral($this->ID_General);
        
        //Lade Kolonien
        //$this->loadKolonien();
        
        //Lade Bevoelkerungsdaten
        //$this->loadBevölkerungsdaten($this->Kolonien[0]->getID());
        //$this->loadBevölkerung($this->Kolonien[0]->getID());
        //$this->loadBevölkerung($this->getMainKolonie());
        
        //Rohstoffe laden
        //$this->getRohstoffData($this->Kolonien[0]->getID());
        $this->getRohstoffData($this->getMainKolonie());
              
        //Gebäudedaten laden
        //$this->getGebäudeData($this->Kolonien[0]->getID());
        $this->getGebäudeData($this->getMainKolonie());
        
        //Lade Vorkommen
       	//$this->loadVorkommen($this->Kolonien[0]->getID());
       	$this->loadVorkommen($this->getMainKolonie());
        
        //Energieverbrauch setzen
        $this->loadEnergieverbrauch();
        
        //Lade Forschungen
        $this->loadForschungen();
        
        //Forscher laden
        $this->loadForscher();
    }
    
    //Holt alle Kolonien des users
    /*function loadKolonien()
    {
    	//Wenn Variablen vorhanden, bitte liöschen!!
    	unset($this->Kolonien);
    	
    	//Kolonien laden!
    	$this->db->query("SELECT ID_Kolonie FROM t_kolonie WHERE ID_User = ".$this->ID." ORDER BY Hauptquartier ASC");
    	$i = 0;
    	while( $row = $this->db->fetch_array() )
    	{
    		$this->Kolonien[$i] = new KOLONIE($row['ID_Kolonie'], $this->db);
    		$i++;
    	}
    }*/
    
    //Hole Kolonien
    /*function getKolonien()
    {
    	return $this->Kolonien;
    }
    
    //Liefert das entsprechende Kolonieobjekt zurück
    function getKolonie($ID)
    {
    	//Deklarationen
    	$i = 0;
    	
    	//Durchläuft alle Kolonien und sucht nach der geforderten!
    	foreach($this->Kolonien as $x)
    	{
    		if( $x->getID() == $ID )
    		{
    			break;
    		}
    		$i++;
    	}
    	return $this->Kolonien[$i];
    }*/
    			
    /*Liefert ID der Hauptkolonie*/
    function getMainKolonie()
    {
    	$this->db->query("SELECT ID_Kolonie FROM t_kolonie WHERE ID_User = ".$this->ID." AND Hauptquartier = 'ja'");
    	return $this->db->fetch_result(0);
    }
    
    //Setzt Datenbankverbindung
    function setDB(&$db)
    {
    	$this->db = new DATENBANK();
    }
    
    //Hole alle Gebäude, welche der User hat
    function getGebäudeData($ID_Kolonie)
    {
		//Alle Gebäudedaten löschen
		unset($this->Gebäude);
    	
    	//Lade alle Gebäude vom User
        $this->db->query("SELECT * FROM t_userhatgebaeude WHERE ID_User = $this->ID AND ID_Kolonie = $ID_Kolonie ORDER BY ID_Gebäude ASC");
        while( $row = $this->db->fetch_array() )
        {
            //$this->Gebäude[$row['ID_Gebäude']][0] = new GEBÄUDE();
            //$this->Gebäude[$row['ID_Gebäude']][0]->loadGebäude($row['ID_Gebäude']);
            $this->Gebäude[$row['ID_Gebäude']][1] = $row['Level'];
            $this->Gebäude[$row['ID_Gebäude']][2] = $row['Auslastung'];
            
        }
    }

    //funktion setzt die Gebäudeauslastung des Arrays um
    /*function setGebäudeAuslastung($ID, $auslastung)
    {
    	//Neue Auslastung setzen
    	$this->Gebäude[$ID][2] = $auslastung;
    	//Da durch die Auslastung der Energieverbrauch steigt, diesen neu laden
    	$this->loadEnergieverbrauch();
    }*/
       
    //Funktion gibt das GebäudeObjekt zurück, welches mittels $ID verlangt wird
    /*function getGebäude($ID)
    {
        return $this->Gebäude[$ID][0];
    }*/
    
    //Funktion gibt den Level des GEbäudes zurück welches mit $ID definiert wurde
    function getGebäudeLevel($ID)
    {
        return $this->Gebäude[$ID][1];
    }
        		
    //LAde alle Rohstoffdaten des Users 
    function getRohstoffData($ID_Kolonie)
    {
		//Rohstoffe selektieren
		$this->db->query("SELECT * FROM t_userhatrohstoffe WHERE ID_User = $this->ID AND ID_Kolonie = $ID_Kolonie");
        while( $row = $this->db->fetch_array() )
        {
            //$this->Rohstoffe[$row['ID_Rohstoff']][0] = new ROHSTOFF();
            //$this->Rohstoffe[$row['ID_Rohstoff']][0]->loadRohstoff($row['ID_Rohstoff']);
           	//echo "ID_User: $this->ID | ID: ".$row['ID_Rohstoff']." | Anzahl: ".$row['Anzahl']." | LastUpdate: ".date("d.m.Y H:i:s", $row['LastUpdate'])."<br>";
            $this->Rohstoffe[$row['ID_Rohstoff']][1] = $row['Anzahl'];
            $this->Rohstoffe[$row['ID_Rohstoff']][2] = $row['LastUpdate'];
        }	        
    }
    
    //Gibt aktuele Rohstoffanzahl zurück
    function getRohstoffAnzahl($ID_Rohstoff)
    {
        return $this->Rohstoffe[$ID_Rohstoff][1];
    }
    
    //Neue Rohstoffanzahl setzen
    function setRohstoffAnzahl($add, $ID_Rohstoff)
    {		
    	//Deklarationen
        $error = 1;
                
        //Falls $add negativer Betrag, muss überprüft werden, ob "aktuelle Anzahl an Rohstoffen" - "$add" größer als Null ist, also kein negativen Wert erreicht
        if( ($this->Rohstoffe[$ID_Rohstoff][1] + $add) >= 0 )
        {
        	//Objekte erzeugen
        	$rohstofflager 		= new ROHSTOFFLAGER($this->getGebäudeLevel(15));	//Rohstofflager
        	$sicherheitslager 	= new SICHERHEITSLAGER($this->getGebäudeLevel(16));	//Sicherheitslager
        	        	   
        	//Rohstoff auf Radioaktivität überprüfen
        	if( $sicherheitslager->checkRadioaktivitaet($ID_Rohstoff) == 1 )	//Radioaktivität gegeben
        	{
        		//LAgermengen laden
        		$lager_menge = $sicherheitslager->getKapazitaet();	//Sicherheitslager        		
        	}
        	else //nicht radioaktiv 
        	{  
        		//LAgermengen laden
        		$lager_menge  = $rohstofflager->getKapazitaet();	//Rohstofflager	
        	}
        
        	//Genügend Platz vorhanden?
        	if( $lager_menge < ($this->Rohstoffe[$ID_Rohstoff][1] + $add) )
        	{
        		$add = $lager_menge - $this->Rohstoffe[$ID_Rohstoff][1];
        	}
        	        		
            //Anzahl aktualisieren
        	$this->Rohstoffe[$ID_Rohstoff][1] = $this->Rohstoffe[$ID_Rohstoff][1] + $add;
            $this->db->query("UPDATE t_userhatrohstoffe SET Anzahl = ".$this->Rohstoffe[$ID_Rohstoff][1]." WHERE ID_User = ".$this->ID." AND ID_Rohstoff = ".$ID_Rohstoff."");
        }
        else 
        {
        	//Nicht genügend Rohstoffe
            $error = -1;
        }
    }
    
    //Aktuallisiere Rohstoffanzahl
    function updateRohstoffAnzahl($ID_Rohstoff, $anzahl, $time)
    {
    	//Rohstoffe aktuallisieren
        $this->setRohstoffAnzahl($anzahl, $ID_Rohstoff);
        
        //LastUpdate time setzen
        $this->db->query("UPDATE t_userhatrohstoffe SET LastUpdate = ".$time." WHERE ID_User = ".$this->ID." AND ID_Rohstoff = $ID_Rohstoff");
        $this->Rohstoffe[$ID_Rohstoff][2] = $time;
    }
    
    //Gib llastUpdateStatus eines bestimmten Rohstoffes zurück
    function getLastUpdateRohstoff($ID_Rohstoff)
    {
        return $this->Rohstoffe[$ID_Rohstoff][2];
    }
    
    function getNickname()
    {
        return $this->Nickname;
    }
    
    function getLoginname()
    {
        return $this->Loginname;
    }
    
    function setLoginname($login)
    {
    	$this->Loginname = $login;
        $this->db->query("UPDATE t_user SET Loginname = '$login' WHERE ID_User = $this->ID");
    }
    
    function getPasswort()
    {
        return $this->Passwort;
    }
    
    function setPasswort($verschl_pw)
    {
        $this->Passwort = $verschl_pw;
        $this->db->query("UPDATE t_user SET Passwort = '$verschl_pw' WHERE ID_User = $this->ID");
    }
    
    function getEmail()
    {
        return $this->Email;
    }
    
    function setEmail($email)
    {
    	$this->Email = $email;
        $this->db->query("UPDATE t_user SET Email = '$email' WHERE ID_User = $this->ID");
    }
    
    function getICQ()
    {
        return $this->ICQ;
    }
   
    /*function getGeneralpunkte()
    {
        return $this->Generalpunkte;
    }*/
    
    function getMilitärpunkte()
    {
        return $this->Militärpunkte;
    }
    
    /*function getGebäudepunkte()
    {
        return $this->Gebäudepunkte;
    }*/
    
    function getAvatar()
    {
        return $this->Avatar;
    }

    function setAvatar($bild)
    {
    	$this->db->query("UPDATE t_user SET Avatar = '$bild' WHERE ID_User = $this->ID"); 	
       	$this->Avatar = $bild;
    }
       
    function getLastLogin()
    {
        return $this->LastLogin;
    }
    
    function setLastLogin()
    {
    	$this->LastLogin = time();
        $this->db->query("UPDATE t_user SET LastLogin = $this->LastLogin WHERE ID_User = $this->ID");
    }
    
    function getRegisterDate()
    {
        return date("D, d.m.Y - H:i:s",$this->RegisterDate);
    }
    
    function getStatus()
    {
        return $this->Status;
    }
    
    function getRasseID()
    {
        return $this->ID_Rasse;
    }
    
    function getGeneralID()
    {
        return $this->ID_General;
    }
  
    function getGeneral()
    {
    	return $this->General;
    }
    /*function getAnzahlLaster()
    {
    	return $this->AnzahlLaster;
    }*/
    
    /*function setAnzahlLaster($neue_anzahl)
    {
    	//in datenbank speichern
    	$this->db->query("UPDATE t_user SET AnzahlLaster = $neue_anzahl WHERE ID_User = $this->ID");
    	
    	//Klassenvariable setzen
    	$this->AnzahlLaster = $neue_anzahl;
    }*/
    
    function getEingeloggt()
    {
        return $this->Eingeloggt;
    }
    
    function setEingeloggt($wert)
    {
        $this->Eingeloggt = $wert;
    }
    
    //Lädt alle Forschungen des Users
    function loadForschungen()
    {
    	//Forschungen löschen!
    	unset($this->Forschungen);
    	
    	//Forschungen laden
    	$this->db->query("SELECT ID_Forschung, Level FROM t_userhatforschung WHERE ID_User = ".$this->ID."");
    	while( $row = $this->db->fetch_array() )
    	{
    		$this->Forschungen[$row[0]] = $row[1];	//Level
    	}
    }
    
    //Gibt Forschungslevel zurück
    function getForschungsLevel($ID)
    {
    	//echo "ID => ".$this->Forschungen[$ID],"<br>";
    	return $this->Forschungen[$ID];
    }
    
    //Holt alle Verteidigungsanlagen des Users
    /*function getVerteidigungsanlagen()
    {
        //Deklarationen
        $i = 0;
    
        //Lade daten
        $this->db->query("SELECT ID_Verteidigungsanlage FROM t_userhatverteidigugnsanlagen WHERE ID_User = $this->ID;");
        while( $row = $this->db->fetch_array() )
        {
            $anlagen[$i] = $row['ID_Verteidigungsanlage'];
            $i++;
        }
        return $anlagen;

    }*/

    //Funktion lädt alle Vorkommen, die der User hat
    function loadVorkommen($ID_Kolonie)
    {
        //Lade alle Vorkommen des Users
        unset($this->Vorkommen);
        $i = 0;
        $this->db->query("SELECT ID_Vorkommen FROM t_vorkommen WHERE ID_User = $this->ID AND ID_Kolonie = $ID_Kolonie ORDER BY ID_Rohstoff ASC, Größe DESC;");
        while( $row = $this->db->fetch_array() )
        {
        	$vorkommen_id[$i] = $row['ID_Vorkommen'];
        	$i++;
        }
        
        //Nun alle Vorkommenobjekte erzeugen!
        for( $i = 0; $i<count($vorkommen_id); $i++ )
        {
            //VorkommenObjekt erzeugen
            $this->Vorkommen[$i] = new VORKOMMEN($this->db);
            $this->Vorkommen[$i]->loadVorkommen($vorkommen_id[$i]);
        }
    }

    //Liefert das Vorkommen-Array zurück
    function &getVorkommen()
    {
        return $this->Vorkommen;
    }
    
    //Lädt die verbrauchten Drohnen eines Users
    function getUsedDrohnen($ID_Kolonie)
    {
    	//Lade alle verbrauchten Drohnen der kolonie und gebe sie zurück
    	$this->db->query("SELECT SUM(AnzahlLasterDrohnen) FROM t_vorkommen WHERE ID_User = $this->ID AND ID_Kolonie = $ID_Kolonie");
    	return $this->db->fetch_result(0);
    }
    
    function getUserID()
    {
    	return $this->ID;
    }
    
    //Gebe Energieverbrauch zurück
    function getEnergieverbrauch()
    {
		//Gebe Energieverbrauch zurück
		return $this->energieverbrauch;
    }
    
    //Lade Energieverbrauch
    /*Lädt Energieverbrauch der ausgewählten Kolonie!*/
    function loadEnergieverbrauch()
    {
    	//Vorherigen Energieverbrauch auf 0 setzen!
    	$this->energieverbrauch = 0;

    	//Gebäudedaten aktualisieren
    	$this->getGebäudeData($this->getMainKolonie());
    	
    	//Gebäudedaten laden!
    	$geb = new GEBÄUDE();				//Gebäudeobjekt erzeugen
		$gebäude = $geb->getGebäudeArray();	//Alle Gebäude laden
		
		//GEbäude durchlaufen um überprüfen zu können ob user dieses gebäude bestitzt
		for( $i=1; $i < count($gebäude); $i++ )
		{	
			//Hat user das gebäude?
			if( $this->getGebäudeLevel($i) > 0 )
			{
				//GEbäudedaten laden
				$geb->loadGebäude($i, $this->getRasseID());
				//Energieverbrauch erhöhen
				$this->energieverbrauch += ($geb->getEnergieverbrauch($this->getGebäudeLevel($i)-1) * $this->Gebäude[$geb->getID()][2]);
			}
		}
    }
        
   /*	function loadBevölkerung($ID_Kolonie)
    {    	
    	$this->bevoelkerung = new BEVOELKERUNG($this->db, $this, $ID_Kolonie);
    }*/
    
    /*function &getBevölkerung()
    {
    	return $this->bevoelkerung;
    }*/
         
    /*Lädt Forscherobjekt!*/
    function loadForscher()
    {
    	$this->Forscher = new FORSCHER($this->db, $this->ID);
    }
    
    /*Gibt Forschungsobjekt zurück!*/
    function &getForscher()
    {
    	return $this->Forscher;
    }

    /*Diese Funktion setzt Forschungspunkte*/
    function setForschungspunkte($pkt)
    {
    	$this->ForschungsPunkte = $pkt;
    	$this->db->query("UPDATE t_user SET PunkteForschung = $pkt WHERE ID_User = $this->ID");
    }
    
    /*Gibt Forschungspunkte zurück*/
    function getForschungspunkte()
    {
    	return $this->ForschungsPunkte;
    }
    /*function getGrundEnergieProduktion()
    {
    	//Deklarationen
    	$grund_produktion = 25;
    	
    	//Produzierte Energie des Hauptquartiers hinzufügen
		$erzeugte_energie += $this->getGebäudeLevel(1) * $grund_produktion;
		
		//echo '<font color="blue">lade GrundEnergieProduktion...<br></font>';
		
		//Rückgabe
		return $erzeugte_energie;
    }*/
    
    /*diese Funktion gibt den ausgewählten Skin zurück*/
    function getSkin()
    {
    	return $this->selectedSkin;
    }
    
    /*Diese Funktion setzt den ausgewählten Skin*/
    function setSkin($skin_name, $skin_folder)
    {    	
    	//Deklarationen
    	$error = 1;		//kein Fehler
    	
    	//Überprüfen auf Korrektheit
    	if( is_dir($skin_folder) )
    	{
    		$this->db->query("UPDATe t_user SET Skin = '$skin_name' WHERE ID_User = ".$this->ID."");
    		$this->selectedSkin = $skin_name;
    	}
    	else 	//Skin existiert nicht!
    	{
    		$error = -1;
    	}
    	
    	//Rückgabewert
    	return $error;    		
    }
}?>