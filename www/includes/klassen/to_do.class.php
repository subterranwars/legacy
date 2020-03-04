<?PHP
/*Diese Klasse stellt das Fundament des Spieles dar.
Sie lädt alle Ereignisse, eines Users und sortiert sie aufsteigend nach der Zeit, d.h. das zu erst eintretende
Ereignis steht an erster Stelle.
Da bei STW die Bauzeiten und Forschungszeiten Energieniveauabhängig sind, muss ggf. die Reihenfolge
der Ereignisse geändert werden, auch hierdrum kümmert sich diese Klasse.

History:
			19.11.2004		MvR		created
*/
class TO_DO
{
	//Objektvariablen
	var $db;
	var $user;
	var $bevoelkerung;	//Referenz auf BevölkerungsObjekt :)
	var $log_todo;
	var $kolonie;		//Objekt auf Kolonie
	var $ereignisse;	//Array, wo alle Ereignisse stehen
						//$ereignisse[0] = array(ID, Kategory, FinishTime)
	#kann später wieder gelöscht werden
	var $ereignisse_save;	//Speichert alle ereignisse einen Schritt zurück !
	var $_start_zeit;
	var $end_zeit;
	#ende
	var $_ENERGIENIVEAU;
	var $_ENERGIENIVEAU_OLD;
	var $hq;
	var $brutreaktor;
	var $kraftwerk;
	var $thermalkraftwerk;
	var $getreidefeld;
	var $chemiefabrik;
	var $schmelze;
	var $titan_schmelze;
	var $extraktor;
	var $bauzeit_verlaengerungs_faktor = 3;
	var $energieproduktion;		//Diese Variabel speichert die gesamte Energieproduktion ab!
	
	/*Standardkonstruktor*/
    function TO_DO(&$db, &$user, &$kolonie, &$bevoelkerung)
    {               
    	//OBjektvariablen setzen
        $this->db 				= &$db;					//Datenbankobjekt
        $this->user 			= &$user;				//Userobjekt
        $this->kolonie			= &$kolonie;			//Kolonieobjekt
        $this->bevoelkerung 	= &$bevoelkerung;		//BevölkerungsObjekt :)
                
        //Energieniveau setzen
        $this->_ENERGIENIVEAU 		= $this->kolonie->getEnergieniveau();
       	$this->_ENERGIENIVEAU_OLD 	= $this->_ENERGIENIVEAU;
        
        //Gebäudeobjekte erzeugen
        $this->hq 				= new HAUPTQUARTIER($this->user, $this->kolonie->getID());
        $this->getreidefeld 	= new GETREIDEFELD($this->user, $this->kolonie->getID());
        $this->chemiefabrik 	= new CHEMIEFABRIK($this->db, $this->user, $this->kolonie->getID(), $this->ENERGIENIVEAU);
        $this->schmelze 		= new SCHMELZE($this->db, $this->user, $this->kolonie->getID(), $this->ENERGIENIVEAU);
        $this->titan_schmelze 	= new TITANSCHMELZE($this->db, $this->user, $this->kolonie->getID(), $this->_ENERGIENIVEAU);
        $this->extraktor 		= new WASSERSTOFF($this->db, $this->user, $this->kolonie->getID(), $this->_ENERGIENIVEAU);
        
        //kraftwerke erzeugen
        $this->brutreaktor 		= new BRUTREAKTOR($this->db, $this->user, $this->kolonie->getID(), $this->_ENERGIENIVEAU);
        $this->thermalkraftwerk	= new THERMALKRAFTWERK($this->db, $this->user, $this->kolonie->getID());
        $this->kraftwerk		= new KRAFTWERK($this->db, $this->user, $this->kolonie->getID());
        
        //StartZeit
        $this->_start_zeit = $this->getLastActionTime();
        
        //Logs starten
        $this->log_todo 		= new LOG($this->user->getUserID()."_todo.txt");
        
        //To_Do log einleiten
        $this->log_todo->write('### To-Do geladen ###');
    }
    
    /*Diese Funktin lädt alle Ereignisse, welche bis '$end_zeit' eingetreten sind*/
    function loadEreignisse($end_zeit)
    {
    	//Aufträge löschen
    	unset($this->ereignisse);
    	
        //Lade alle Aufträge
        $i = 0;
        $this->db->query("SELECT ID_Auftrag, FinishTime, Kategory FROM t_auftrag WHERE ID_User = ".$this->user->getUserID()." AND Kategory != 'Einheiten' AND FinishTime <= $end_zeit ORDER BY FinishTime ASC");
        while( $row = $this->db->fetch_array() )
        {
        	//Ereigniss setzen => array(ID, FinishTime, Kategory)
        	$this->ereignisse[$i] = array($row[0], $row[1], $row[2]);
            $i++;
        }
        
        /*Es sind nun alle Aufträge, welche relevant sind vorhanden. Jetzt müssen noch die Missionen hinzugefügt werden, um den Zeitstreifen korrekt
        auszuführen. Dazu werden erst alle Hinflüge und anschliessend alle Rückflüge geladen!*/
        //Hinflüge laden, welche noch nicht ausgeführt wurden!
        $this->db->query("SELECT ID_Mission, Hinflug, Rückflug FROM t_mission WHERE (ID_User = ".$this->user->getUserID()." OR ID_UserOpfer = ".$this->user->getUserID().") AND (Hinflug <= $end_zeit) AND (Ausgefuehrt = 0)");
        while( $row = $this->db->fetch_array() )
        {
        	//Hinflug eintragne
        	$this->ereignisse[$i] = array($row[0], $row[1], 'Hinflug');
        	$i++;
           	//echo $this->ereignisse[$i][0], "|", $this->ereignisse[$i][1], "|",$this->ereignisse[$i][2], "<br>";
        }
        
        //Rückflug laden
        $this->db->query("SELECT ID_Mission, Hinflug, Rückflug FROM t_mission WHERE (ID_User = ".$this->user->getUserID()." OR ID_UserOpfer = ".$this->user->getUserID().") AND (Rückflug <= $end_zeit) AND (Ausgefuehrt != 0)");
        while( $row =$this->db->fetch_array() )
        {
    		//Rückflug ebenfalls eintragen!	
    	 	$this->ereignisse[$i] = array($row[0], $row[2], 'Rückflug');
    	 	$i ++;
    	 	//echo $this->ereignisse[$i][0], "|", $this->ereignisse[$i][1], "|",$this->ereignisse[$i][2], "<br>";
        }
        
        
        //Speichere $i zwischen
        $offset = $i;
        $i = 0;
        
        /*Da auch Vorkommen, insbesondere Öl, bzw. Uran-Vorkommen zur Neige gehen können, muss jedes Vorkommen überprüft werden, wann dieses zur Neige geht und
        anschliessend als Ereignis eingefügt werden!*/
        //Vorkommen laden
        $vorkommen = &$this->user->getVorkommen();
        for( $a=0; $a<count($vorkommen); $a++ )
        {
        	//Lebvenszeit ermitteln
        	$lebens_zeit = floor($vorkommen[$a]->getLebensZeit($this->_ENERGIENIVEAU)) + $this->getLastActionTime();
        	
        	//Ist Vorkommen innerhalb des letzten Ereignisses (aktuelle Zeit) erschöpft?
            if( $lebens_zeit < $end_zeit )
            {
               	$this->ereignisse[$offset + $i] = array($vorkommen[$a]->getID(), $lebens_zeit, 'ResErschöpft');
		//echo "res erschöpft...",$this->ereignisse[$offset + $i][0], "|", $this->ereignisse[$offset + $i][1], "|",$this->ereignisse[$offset + $i][2], "<br>";
               	$i++;
            }
        }
        
        //alle Ereignisse durchlaufen und ausgeben
        /*echo "<hr color=\"red\" size=\"1\">";
        for($a=0; $a<count($this->ereignisse); $a++)
        {
       		echo $this->ereignisse[$a][0], " | ", date("d.m.Y H:i:s",  $this->ereignisse[$a][1]), " | ", $this->ereignisse[$a][2], "<bR>";

        }
        echo "<hr color=\"red\" size=\"1\">";*/
        
        //Speichere Ereignisse zwischen
        $this->ereignisse_save = $this->ereignisse;
    }

    /*Diese Funktion sortiert alle Ereignisse nach dem '$schema'*/
    function sortiereEreignisse($schema = 'ASC')
    {
        switch( $schema )
        {
        	default:
        	case 'ASC' :        		
	            for( $i=0; $i<count($this->ereignisse)-1; $i++ )
	            {
	                for( $a=$i+1; $a<count($this->ereignisse); $a++ )
	                {
	                    if( $this->ereignisse[$i][1] > $this->ereignisse[$a][1] )
	                    {
	                        //ID verschieben
	                        $speicher = $this->ereignisse[$a][0];
	                        $this->ereignisse[$a][0] = $this->ereignisse[$i][0];
	                        $this->ereignisse[$i][0] = $speicher;
	                                                            
	                        //ZEit verschieben
	                        $speicher = $this->ereignisse[$a][1];
	                        $this->ereignisse[$a][1] = $this->ereignisse[$i][1];
	                        $this->ereignisse[$i][1] = $speicher;
	                                                            
	                        //Kategory verschieben
	                        $speicher = $this->ereignisse[$a][2];
	                        $this->ereignisse[$a][2] = $this->ereignisse[$i][2];
	                        $this->ereignisse[$i][2] = $speicher;
	                    }
	                }
	            }
	            break;
        }
    }

    /*Diese Funktion ermittelt das Datum der letzten ausgeführten Aktion. Dazu wird einfach die letzte Änderungszeit der Rohstoffe verwendet*/
	function getLastActionTime()
    {
        //Lade Datum (Timestamp-Format) der letzten ausgeführten Aktion
        $this->db->query("SELECT LastUpdate FROM t_userhatrohstoffe WHERE ID_User = ".$this->user->getUserID()." AND ID_Rohstoff = 1 ORDER BY LastUpdate ASC");
        return $this->db->fetch_result(0);
    }

    function getEnergieproduktion($without=0)
    {
        //Lade theoretische Energieproduktion pro Stunde
        $produktion = $this->kraftwerk->getProduktion();
        $produktion += $this->thermalkraftwerk->getProduktion();
        $produktion += $this->brutreaktor->getProduktionEnergie();
        $produktion += $this->hq->getEnergieProduktion();
           
        /*Wenn $without gesetzt wurde, dann entweder den Brutreaktor oder das Kraftwerk abziehen
        => $without = 1 ... ziehe Kraftwerk - Produktion wieder ab
        => $without = 2 ... ziehe Brutreaktor-Produktion wieder ab
        => $without = 3 ... ziehe Brutreaktor und Kraftwerk-Produktion wieder ab*/
        switch( $without )
        {
	        case 1 :
	            //Kraftwerk abziehen
	            $produktion -= $this->kraftwerk->getProduktion();
	            break;
	        case 2 :
	            //Brutreaktor Energieproduktion wieder abziehen
	            $produktion -= $this->brutreaktor->getProduktionEnergie();
	            break;
	        case 3 :
	            //Brutreaktor und Kraftwerk Energieproduktion wieder abziehen
	            $produktion -= $this->brutreaktor->getProduktionEnergie();
	            $produktion -= $this->kraftwerk->getProduktion();
	            break;
        }
        return $produktion;
    }
    
    /*Diese Funktin überprüft dsa Energieniveau für die Differenz '$time'. Je nachdem, wie das Energieniveau ist, wird das 
	Energieniveau ($this->_ENERGIENIVEAU) auf "normal" oder "critical" gesetzt*/
	function checkEnergieniveau($start_zeit, $naechste_zeit, $end_zeit)
    {
        //Nächste Zeit?
        $time = $naechste_zeit;
        $differenz = $time - $start_zeit;
        
        //echo "start: $start_zeit | next: $naechste_zeit | end: $end_zeit<br>";
       // echo "Time: ".date("d.m.Y H:i:s", $time)."($time)<br>";
        //echo "Verbrauch: ".$this->user->getEnergieVerbrauch()." | Produktion: ".$this->getEnergieproduktion(0)."<br>";
        
        //Kann theoretisch genügend Energie produziert werden?					
        if( $this->getEnergieproduktion() >= $this->user->getEnergieVerbrauch() )
        {
        	//echo "Es kann theoretisch genügend Energie produziert werden<br>";
        	
            /*Es kann theoretisch genügend Energie produziert werden.
            Nun überprüfen, ob genügend Rohstoffe für den Zeitraum bis zum nächsten Ereignis vorhanden sind*/
            //Alle Vorkommen laden
            $vorkommen = &$this->user->getVorkommen();
            for( $i=0; $i<count($vorkommen); $i++ )
            {
                switch( $vorkommen[$i]->getRohstoffID() )
                {
	                case 4 :
	                    //Wie viel Öl wird gefördert?
	                    $produktion['OEL'] += $vorkommen[$i]->getProduktion($this->user, $time, 'normal');
	                    break;
	                case 11 :
	                    //Wie viel Uran wird gefördert?
	                    $produktion['URAN'] += $vorkommen[$i]->getProduktion($this->user, $time, 'normal');
	                    break;
                }
            }
            
            /*Theoretische Uran und Öl-Menge bestimmen und anschliessend überprüfen ob die vorhandene Menge 
            an Rohstoffen den Verbrauch deckt?*/
            $rohstoffe['OEL'] 	= $produktion['OEL'] + $this->user->getRohstoffAnzahl(4);
            $rohstoffe['URAN']	= $produktion['URAN'] + $this->user->getRohstoffAnzahl(11);
            
           // echo "Rohstoffproduktion | Verbrauch<br>=>Oel: ".$rohstoffe['OEL']." | ".$this->kraftwerk->getResVerbrauch() * ($differenz / 3600)."<br>=>Uran: ".$rohstoffe['URAN']." | ".$this->brutreaktor->getResVerbrauchEnergie() * ($differenz / 3600)."<br>";
            
            if( $this->kraftwerk->getResVerbrauch() * ($differenz / 3600) <= $rohstoffe['OEL'] 
                AND 
                $this->brutreaktor->getResVerbrauchEnergie() * ($differenz / 3600) <= $rohstoffe['URAN'] )
            {
            	//echo "Rohstoffe reichen aus<br>";
                //genügend Rohstoffe vorhanden
                $this->_ENERGIENIVEAU = 'normal';
                $this->kolonie->setEnergieniveau('normal');
                //Setze Energieproduktion
                $this->energieproduktion = $this->getEnergieproduktion();
            }
            else 
            {
            	//echo "Rohstoffe reichen nicht aus<br>";
                /*Da eines der beiden Kraftwerke für den gesamten Zeitraum das Energieniveau nicht aufrecht
                erhalten kann, muss überprüft werden welches Kraftwerk "schlapp" macht und anschliessend muss geschaut werden, ob ein Kraftkwerk die gesamte Energieproduktion aufrecht erhalten kann*/

                //Gehen Öl und Uran zur Neige?
                if( $this->brutreaktor->getResVerbrauchEnergie() * ($differenz / 3600) > $rohstoffe['URAN']
                    AND
                    $this->kraftwerk->getResVerbrauch() * ($differenz / 3600) > $rohstoffe['OEL'] )
                {
                	//echo "=> Beide Kraftwerke machen schlapp<br>";
                    //Energieniveauberechnung ohne Brutreaktor und Kraftwerk
                    $energie_produktion = $this->getEnergieproduktion(3);
                    
                    /*Da beide KRaftwerke innerhalb der Zeitspanne vom letzten
                    zum nächsten EReigniss "schlapp" machen, muss nun geprüft werden, welchem
                    Kraftwerk zuerst die Luft wegbleibt*/
                    $time_brutreaktor = $rohstoffe['URAN'] / ($this->brutreaktor->getResVerbrauchEnergie()/3600);
                    $time_kraftwerk = $rohstoffe['OEL'] / ($this->kraftwerk->getResVerbrauch()/3600);
                    
                    if( $time_brutreaktor > $time_kraftwerk )
                    {
                        //Kraftwerk macht zu erst "shclapp"
                        $time_add = floor($time_brutreaktor);
                    }
                    else 
                    {
                        //Brutreaktor macht zu erst "schlapp"
                        $time_add = floor($time_kraftwerk);
                    }
                   //echo "=> Time-Add: $time_add<br>";
                   //Setze Energieproduktion
                   $this->energieproduktion = $energie_produktion;
                }
                else 
                {
                	//echo "=> Eines der beiden KRaftwerke macht zu erst schlapp<br>";
                    //Eines der beiden Kraftwerke macht "schlapp"
                    //Nun herausfinden welches
                    if( $this->brutreaktor->getResVerbrauch() * ($differenz / 3600) > $rohstoffe['URAN'] )
                    {
                        //echo "Brutreaktor macht schlapp<br>";
                    	//Brutreaktor macht "schlapp"
                        $energie_produktion = $this->getEnergieproduktion(2);
                        //Zeit ermitteln, wann Brutreaktor "schlapp" macht
                        $time_add = floor($rohstoffe['URAN'] / ($this->brutreaktor->getResVerbrauchEnergie()/3600));
                    }
                    else 
                    {
                    	//echo "Kraftwerk macht schlapp<br>";
                        //"Kraftwerk macht "schlapp"
                        $energie_produktion = $this->getEnergieproduktion(1);
                        //Zeit ermitteln, wann Kraftwerk "schlapp" macht
                        $time_add = floor($rohstoffe['OEL'] / ($this->kraftwerk->getResVerbrauch()/3600));
                    }
                  	//Setze Energieproduktion
                   	$this->energieproduktion = $energie_produktion;
                }
                
                //Ist Produktion größer als Verbrauch?
                if( $energie_produktion < $this->user->getEnergieVerbrauch() )
                {
                	//echo "Energiebedarf kann nicht gedeckt werden<br>";
                	//echo "Time-Add: $time_add<br>";
                	/*Wenn die zu addierende Zeit > 0 ist, dann neues Ereignis einfügen, 
                	ansonsten einfach nur Energieniveau auf kritisch verändern!*/
                	if( $time_add > 0 )
                	{
	                    //Ein kritisches Energieniveau wird irgendwann eintreten... Ereignis einfügen
	            		//KRitisches Energieniveau Ereignis einführen
	            		//echo "Kritisches Energieniveau-Ereignis!: <br>";
	            		//echo "Zeit: ".date("d.m.Y H:i:s", ($start_zeit+$time_add))."(".($start_zeit+$time_add).")<hr>";
	            		$this->ereignisse[] = array(0,$start_zeit+$time_add, 'Energieniveau kritisch');
	            		//Ereignisse neu sortieren
	            		$this->sortiereEreignisse();
                	}
                	else 
            		{
		            	$this->_ENERGIENIVEAU = 'critical';
		            	$this->kolonie->setEnergieniveau('critical');
		            }
                }
                else 
                {
                	//echo "Energieniveau kann aufrecht erhalten werden<br>";
                    //Energieniveau ist bis zum nächsten Ereignis 'normal'
                    $this->_ENERGIENIVEAU = 'normal';
                    $this->kolonie->setEnergieniveau('normal');
                }
            }
        }
        else 
        {
            //Es wird zu wenig Energie produziert => Energieniveau kritisch
            $this->_ENERGIENIVEAU = 'critical';
            $this->kolonie->setEnergieniveau('critical');
            //Setze Energieproduktion
            $this->energieproduktion = $this->getEnergieproduktion();
        }
        
        /*Wenn sich dsa Energieniveau verändert hat, laden wir die Ereignisdaten neu*/
        //echo "Energieniveau: $this->_ENERGIENIVEAU<br>
       // Energieniveau alt: $this->_ENERGIENIVEAU_OLD<hr>";
        if( $this->_ENERGIENIVEAU != $this->_ENERGIENIVEAU_OLD )
        {
        	/*Verändere FinishTimes von Gebäuden, Forschungen und Kolonieausbauten der ausgewählten Kolonie*/
     		$this->changeGebFinishTime($time);
     		$this->changeForschungFinishTime($time);
     		$this->changeKolonieFinishTime($time);
        	
     		//Forschung laden
     		//$forschungs_daten = $this->getForschungsBau($this->kolonie->getID());
     			   
     		//Setze Altes Energieniveau neu
        	$this->_ENERGIENIVEAU_OLD = $this->_ENERGIENIVEAU;		
        	
        	//Lade Ereignisse neu
        	$this->loadEreignisse($end_zeit);
        	$this->sortiereEreignisse();
        }
        //echo "<hr size=\"1\" color=\"orange\">";
    }
    
    /*Diese Funktion updatet den UserAccount*/
	function updateAccount($time, $counter)
    {
    	/*Rohstoffe aktualisieren*/
        $this->user->getRohstoffData($this->kolonie->getID());		//Rohstoffdaten aus Datenbank laden und somit aktualisieren
    	
    	/*Energieniveau in den einzelnen Gebäuden setzen*/
    	$this->chemiefabrik->setEnergieniveau($this->_ENERGIENIVEAU);
    	$this->schmelze->setEnergieniveau($this->_ENERGIENIVEAU);
    	$this->extraktor->setEnergieniveau($this->_ENERGIENIVEAU);
    	$this->titan_schmelze->setEnergieniveau($this->_ENERGIENIVEAU);
    	$this->brutreaktor->setEnergieniveau($this->_ENERGIENIVEAU);
               
        /*Kraftwerk-Verbrauch abziehen*/
        $this->kraftwerk->erzeugeEnergie($time);
		$this->brutreaktor->erzeugeEnergie($time);
        
		//Vorkommen des Users laden
		$vorkommen = &$this->user->getVorkommen();
		
        //Alle Vorkommen durchlaufen und bis zum aktuellen Zeitpunkt produzieren
        for( $i=0; $i < count($vorkommen); $i++ )
        {
            //Sind mehr als 0 Laster auf Vorkommen gesetzt?
            if( $vorkommen[$i]->getAnzahlLaster() > 0 )
            {
	        	//Wie viele Rohstoffe werden gefördert?
	        	$produktion = $vorkommen[$i]->getProduktion($this->user, $time, $this->_ENERGIENIVEAU);
	        	
	        	//Rohstoffe welche zu Tage gefördert wurden vom Vorkommen abziehen und dem User gutschreiben
	        	$vorkommen[$i]->setSizeLeft(($vorkommen[$i]->getSizeLeft() - $produktion));
	        	$this->user->setRohstoffAnzahl($produktion, $vorkommen[$i]->getRohstoffID());
            }
            
	        //LastUpdate des Vorkommesn neu setzen
	        $vorkommen[$i]->setLastChange($time);
        }

        /*Rohstoffe vom Hauptgebäude pro h laden*/
        //Lade Level des Hauptgebäudes
        $lvl = $this->user->getGebäudeLevel(1);
        $grund_rohstoffe = $this->hq->getGrundProduktion();
        
        /*Durchlaufe alle GrundRohstoffe und produziere sie ;)*/
        foreach( $grund_rohstoffe as $key => $value )
        {
        	//Wann wurde der Rohstoff das letzte Mal aktuallisiert?
        	$last_update = $this->user->getLastUpdateRohstoff($key);
        	
        	//Differenz ermitteln
        	$differenz = $time - $last_update;
        	$differenz = $differenz / 3600;		//Daten in h ermitteln
        	
        	//Wie viele Rohstoffe sollen hinzugefügt werden?
        	$res_update = $differenz * $value;
        	
        	//Rohstoffe in Datenbank speichern
        	$this->user->updateRohstoffAnzahl($key, $res_update, $time);
        }
        
                
        //Nahrungsproduktion ermitteln
        $nahrungs_produktion = $this->getreidefeld->getProduktion() * (($time - $this->getreidefeld->getLastChange())/3600) + $res_update;	//Res-Update steht für Grundproduktion des HQ's
       	
        /*Nahrung produzieren*/
        $this->getreidefeld->erzeugeNahrung($time);
        
        /*Kunststoff erzeugen*/
        $this->chemiefabrik->erzeugeKunststoff($time);
        
        /*Stahl erzeugen*/
        $this->schmelze->erzeugeStahl($time);
        
        /*Titan erzeugen*/
        $this->titan_schmelze->erzeugeTitan($time);
        
        /*Wasserstoff erzeugen*/
        $this->extraktor->erzeugeWasserstoff($time);
        
        /*Plutonium erzeugen*/
        $this->brutreaktor->erzeugePlutonium($time);
        
        /*Gold und Diamant - Produktion simulieren*/
        $this->user->setRohstoffAnzahl(0, 13);
        $this->user->setRohstoffAnzahl(0, 14);
        
        /*Bevölkerung wachsen lassen!*/
       	//Aktuelle Bevölkerung speichern
       	$bev_anzahl = $this->bevoelkerung->getAnzahl();
       	$lagerbestand_theoretisch = $this->bevoelkerung->erzeugeBevoelkerung($time, $nahrungs_produktion);
        
        //Ist Bevölkerung negativ?
        if( $this->bevoelkerung->getAnzahl() <= 0 ||( $lagerbestand_theoretisch >= 0 && $bev_anzahl > $this->bevoelkerung->getAnzahl()))		//ja
        {
        	//Sende Admin eine Email mit allen Daten
        	$this->debugReport($counter);
        }
        
        /*Einheiten, welche im Bau sind vollenden*/
        $this->finishAusbildung($time);
        
        /*Forschungspunkte aktualisieren*/
        $forscher = &$this->user->getForscher();
        $forscher->updateForschungspunkte($time);
    }
    
    /*Diese Funktion durchläuft alle Ereignisse bis $end_zeit und stellt so quasi die Schnittstelle zu allen anderen Klassen bereit*/
	function durchlaufe_Ereignisse($end_zeit)
	{
		//Setze Endzeit:
		$this->end_zeit = $end_zeit;
		
		/*Rohstoffe aktualisieren*/
        $this->user->getRohstoffData($this->kolonie->getID());		//Rohstoffdaten aus Datenbank laden und somit aktualisieren
     
        //Lade alle Ereignisse
        $this->loadEreignisse($end_zeit);
        //Ereignisse sortieren
       	$this->sortiereEreignisse();
        
        //Ermittle Startzeit, wann Zeitstreifen gestartet werden soll
        $start_zeit = $this->getLastActionTime();
       
        //Log schreiben
		$this->log_todo->write("StartZeit: ".date("d.m.Y H:i:s", $start_zeit)." ($start_zeit)");
		
        /*Da es vorkommen kann, das kein weiteres Ereignis im ZEitrahmen vorhanden ist, muss zu erst überprüft werden, ob Ereignisse vorhanden sind*/
        if( count($this->ereignisse) <= 0 )
        {
            $naechste_zeit = $end_zeit;
        }
        else 
        {
            $naechste_zeit = $this->ereignisse[0][1];
        }
        
        /*Überprüfen ob das Energieniveau bis zum nächsten Ereignis aufrecht erhalten bleiben kann*/
        $this->checkEnergieniveau($start_zeit, $naechste_zeit, $end_zeit);
         
        /*Da jetzt das Energieniveau überprüft wurde, müssen jetzt alle Ereignisse durchlaufen werden!
        Dazu wird von Ereignis zu Ereignis gesprungen, dieses überprüft und dort Aktionen ausgeführt!*/
        $i=0;
        while( $i < count($this->ereignisse) )
        {
            //Zeit des nächsten EReignisses?!
            $time = $this->ereignisse[$i][1];
            
            //Log schreiben
            $this->log_todo->write(($i+1).". Ereignis: ".date("d.m.Y H:i:s", $time)." ($time) => ".$this->ereignisse[$i][2]."");
            
            /*Rohstoffe aktualisieren*/
            $this->updateAccount($time, $i);
            
            /*Ereignis spezifische Funktion ausführen*/
            switch( $this->ereignisse[$i][2] )
            {
	            case 'Gebäude' :
	                $this->finishBuilding($this->ereignisse[$i][0], $this->kolonie->getID());
	                break;
	            case 'Forschung' :
	                $this->finishForschung($this->ereignisse[$i][0]);
	                break;
	            case 'Kolonieausbau' :
	                $this->finishKolonieausbau($this->ereignisse[$i][0]);
	                break;
	            case 'Forscher' :
	                $this->finishForscher($this->ereignisse[$i][0]);
	                break;
	            case 'Vorkommensuche' :
	                //Vorkommen fertigstellen
	                $this->finishSearching($this->ereignisse[$i][0]);
	                //Vorkommen neu laden
	                $this->user->loadVorkommen($this->kolonie->getID());
	                break;
	            case 'ResErschöpft' :
	            	//echo $this->ereignisse[$i][0],"<br>";
	            	//Vorkommen löschen
	            	$vorkommen = new VORKOMMEN($this->db);
	            	$vorkommen->loadVorkommen($this->ereignisse[$i][0]);
	            	$vorkommen->delVorkommen($time);
	            	//Vorkommen neu laden
	                $this->user->loadVorkommen($this->kolonie->getID());
	                break;
	            case 'Hinflug' :
	                //Missionsdaten laden!
	                $this->db->query("SELECT
	                					ID_Mission, Parameter, Hinflug, ID_User, ID_UserOpfer, ID_KoordinatenDestination, ID_KoordinatenSource,
                						ID_UserOpfer
	                				 FROM 
	                					t_mission 
	                				WHERE 
	                					ID_Mission = ".$this->ereignisse[$i][0]);
	               	$row = $this->db->fetch_array();
	                
	                //Ist Parametertyp Angriff und ich NICHt das Opfer!
					if( $row['Parameter'] == 'Angriff' AND $row['ID_UserOpfer'] != $this->user->getUserID() )
					{
						//Kolonie ermitteln, auf der Kampf stattfindet
						$this->db->query("SELECT ID_Kolonie FROM t_kolonie WHERE ID_Koordinaten = ".$row['ID_KoordinatenDestination']."");
						$ID_Kolonie = $this->db->fetch_result(0);
					
						//OBjekte erzeugenn
						$db2		= new DATENBANK();
						$user_feind = new USER($row['ID_UserOpfer']);
						$bev_feind 	= new BEVOELKERUNG($db2, $user_feind, $ID_Kolonie);
						
						
						//Kampf_todo ausführen
						$to_do = new TO_DO($db2, $user_feind, new KOLONIE($ID_Kolonie, $db2), $bev_feind);
						$to_do->durchlaufe_ereignisse($row['Hinflug']-1);
					}
					//Kampf ausführen
					// 1 db host
					// 2 db port
					// 3 db user
					// 4 db password
					// 5 db name
					// 6 missions id
					exec("../cpp/finish_mission db 3306 stw stw stw ".$row['ID_Mission']."");
	                break;
	            case 'Rückflug':
	            	//Missionsdaten laden
	                $this->db->query("SELECT
	                					ID_Mission, ID_User, Ressources, Parameter
	                				 FROM 
	                					t_mission 
	                				WHERE 
	                					ID_Mission = ".$this->ereignisse[$i][0]);
	               	$row = $this->db->fetch_array();
	               	
	            	//War dieMission eine Angriffsmission?
	            	if( $row['Parameter'] == 'Angriff' )
	            	{
	            		/*überprüfen ob ich eingeloggt bin, oder das Opfer*/
	            		if( $row['ID_User'] != $this->user->getUserID() ) //Opfer ist eingeloggt
	            		{
	            			$user = new USER($row['ID_User']);	
	            		}
	            		else 
	            		{
	            			$user = &$this->user;
	            		}
	            		
	               		//Ressourcen String teilen, um Anzahl der geplünderten Rohstoffe zu ermitteln
						$res_getrennt = explode(",", $row['Ressources']);	//String nach , Trennen
						
						//Durchlaufe alle Rohstoffe
						for($a=0; $a<count($res_getrennt)-1; $a++)
						{
							//Trenne ID von Anzahl
							$res_detail = explode("|", $res_getrennt[$a]);							
							//Rohstoff aktuallisierne
							$user->setRohstoffAnzahl($res_detail[1], $res_detail[0]);
						}
					}	
					//Lösche aktuelle Missionen, weil abgeschlossen!
					$this->db->query("DELETE FROM t_mission WHERE ID_Mission = ".$this->ereignisse[$i][0]." AND Ausgefuehrt != 0");
	                break;
	            case 'Energieniveau kritisch' :
	            	//Energieniveau verändern
	            	$this->_ENERGIENIVEAU = 'critical';
	            	
	            	/*Bauzeiten verändern!*/
	            	$this->changeGebFinishTime($this->ereignisse[$i][1]);
	            	$this->changeForschungFinishTime($this->ereignisse[$i][1]);
	            	$this->changeKolonieFinishTime($this->ereignisse[$i][1]);
	            	
	            	//Energieniveau Alt auch abändern!
	            	$this->_ENERGIENIVEAU_OLD = $this->_ENERGIENIVEAU;
	                break;
            }
                            
            /*ÜBerprüfen ob noch ein weiteres Ereignis vorhanden ist.
            Wenn dem so ist, dann ist die naechste zeit, die Zeit des nächsten auftretenden Ereignisses, ansonsten
            die End_Zeit*/
            if( count($this->ereignisse) == ($i+1) )	//Keine weiteren Ereignisse vorhanden
            {
            	$naechste_zeit = $end_zeit;
            }
            else 
            {
            	 $naechste_zeit = $this->ereignisse[$i+1][1];
            }      
            
            //Startzeit von letztem Ereignis auf aktuelles Ereignis setzen
            $start_zeit = $this->ereignisse[$i][1];
            
            //Energieniveau prüfen, bei Veränderung Ereignisse neu laden
            $this->checkEnergieniveau($start_zeit, $naechste_zeit, $end_zeit);
            $i++;
        }

        /*Alle Ereignisse wurden durchlaufen.
        Nun müssen noch Rohstoffe vom letzten Ereignis bis zur aktuellen Zeit (login-Zeit) berechnet werden*/
        //Endzeit setzen
        $time = $end_zeit;
        
        //Log schreiben
        $this->log_todo->write("Endzeit Soll: ".date("d.m.Y H:i:s", $end_zeit)." ($end_zeit)");
        $this->log_todo->write("Endzeit Ist: ".date("d.m.Y H:i:s", $time)." ($time)");
        $this->log_todo->write("Differenz: ".($end_zeit - $time)."");
        
        /*Rohstoffe aktualisieren*/
        $this->updateAccount($time, 0);
    }
    
    /*Diese Funktion beendet einen Gebäudebau*/
    function finishBuilding($ID_Auftrag, $ID_Kolonie)
    {
    	//Lade Daten aus Datenbank
    	$this->db->query(
    				"SELECT
    					t_auftrag.ID_Auftrag as ID_Auftrag, ID_Kolonie, FinishTime, ID_Gebäude, Kategory
    				FROM
    					t_auftrag, t_auftraggebaeude
    				WHERE
    					t_auftrag.ID_Auftrag = $ID_Auftrag
    				AND
    					t_auftrag.ID_Auftrag = t_auftraggebaeude.ID_Auftrag");
    	$row = $this->db->fetch_array();
    	
    	//Level ermitteln
		$level = $this->user->getGebäudeLevel($row['ID_Gebäude']);
		
		//Besitzt User dieses Gebäude bereits?
		if( $level == 0 )
		{
			$this->db->query("INSERT INTO t_userhatgebaeude (ID_User, ID_Gebäude, Level, ID_Kolonie, Auslastung, LastChange) VALUES (".$this->user->getUserID().", ".$row['ID_Gebäude'].", 1, ".$row['ID_Kolonie'].", 1, ".$row['FinishTime'].")");
		}
		else 
		{
			$this->db->query("Update t_userhatgebaeude SET Level = (Level+1) WHERE ID_User = ".$this->user->getUserID()." AND ID_Kolonie = ".$row['ID_Kolonie']." AND ID_Gebäude = ".$row['ID_Gebäude']."");
		}
		
		/*Ereignismeldung vorbereiten*/
		//Gebäudeobjekt erzeugen
		$geb = new GEBÄUDE();
		$geb->loadGebäude($row['ID_Gebäude'], $this->user->getRasseID());
		
		//Ereignisdaten setzen
		$topic 		= "Konstruktion abgeschlossen...";
    	$meldung 	= "Gebäudekonstruktion von \"<b>".$geb->getBezeichnung()."</b> (Stufe: ".($level+1).")\" abgeschlossen!";
		
    	//Dem User eine Nachricht schicken
		$this->sendMessage($topic, $meldung, $row['FinishTime'], $row['ID_Kolonie']);

		//Alle Bauaufträge löschen
       	$this->db->query("DELETE FROM t_auftraggebaeude WHERE ID_Auftrag = ".$row['ID_Auftrag']."");
        $this->db->query("DELETE FROM t_auftrag WHERE ID_Auftrag = ".$row['ID_Auftrag']."");
        
        //Wurde Gebäude auf der ausgewählten Kolonie fertig gestellt?
        if( $ID_Kolonie == $row['ID_Kolonie'] )
        {
        	//Daten aktualisieren
       	 	$this->user->getGebäudeData($ID_Kolonie);
	   	 	$this->user->loadEnergieverbrauch($ID_Kolonie);
	   	 	$this->bevoelkerung->loadBevoelkerung();
        }
    }
    
    /*Diese Funktion beendet eine Forschung*/
    function finishForschung($ID_Auftrag)
    {
    	//Lade Daten aus Datenbank
    	$this->db->query(
    				"SELECT
    					t_auftrag.ID_Auftrag as ID_Auftrag, ID_Kolonie, FinishTime, ID_Forschung, Kategory
    				FROM
    					t_auftrag, t_auftragforschung
    				WHERE
    					t_auftrag.ID_Auftrag = $ID_Auftrag
    				AND
    					t_auftrag.ID_Auftrag = t_auftragforschung.ID_Auftrag");
    	$row = $this->db->fetch_array();
    	
    	//Level ermitteln
		$level =  $this->user->getForschungsLevel($row['ID_Forschung']);
		
		//Besitzt User dieses Gebäude bereits?
		if( $level == 0 )
		{
			$this->db->query("INSERT INTO t_userhatforschung (ID_User, ID_Forschung, Level) VALUES (".$this->user->getUserID().", ".$row['ID_Forschung'].", 1)");
		}
		else 
		{
			$this->db->query("Update t_userhatforschung SET Level = (Level+1) WHERE ID_User = ".$this->user->getUserID()." AND ID_Forschung = ".$row['ID_Forschung']."");
		}
		
		
		/*Ereignismeldung vorbereiten*/
		//Gebäudeobjekt erzeugen
		$forschung = new FORSCHUNG($this->user, $this->user->getRasseID());
		$forschung->loadForschung($row['ID_Forschung']);
				
		//Ereignisdaten setzen
    	$topic 		= "Forschungsbericht...";
    	$meldung 	= "Ihre Wissenschaftler können einen Erfolg bei der Erforschung von \"<b>".$forschung->getBezeichnung()."</b> (Stufe: ".($level+1).")\" verzeichnen!";
    			
    	//Dem User eine Nachricht schicken
		$this->sendMessage($topic, $meldung, $row['FinishTime'], $row['ID_Kolonie']);

		//Alle Bauaufträge löschen
        $this->db->query("DELETE FROM t_auftragforschung WHERE ID_Auftrag = ".$row['ID_Auftrag']."");
        $this->db->query("DELETE FROM t_auftrag WHERE ID_Auftrag = ".$row['ID_Auftrag']."");
        
        //Daten aktualisieren
        $this->user->loadForschungen();
    }
   
    /*Diese Funktion beendet einen Kolonieausbau*/
    function finishKolonieausbau($ID_Auftrag)
    {
    	//Lade Daten aus Datenbank
    	$this->db->query(
    				"SELECT
    					ID_Auftrag, ID_Kolonie, FinishTime, Kategory
    				FROM
    					t_auftrag
    				WHERE
    					t_auftrag.ID_Auftrag = $ID_Auftrag");
    	$row = $this->db->fetch_array();
    	
    	//Kolonieobjekt erzeugen
		$kolonie = new KOLONIE($row['ID_Kolonie'], $this->db);
					
		//Aktualisiere Koloniestatus
		$level = $kolonie->getStatus();	//Level bestimmen
		$kolonie->setStatus($level+1);	//Status aktualisieren
			
		/*Ereignismeldung vorbereiten*/
		$topic 		= "Expansion abgeschlossen...";
    	$meldung	= "Ihr(e) <b>".$kolonie->kolonie_status[$level]."</b> wurde zur <b>".$kolonie->getStatusName()."</b> erweitert. Sie können sich nun weiteren Forschungen, Konstruktionen oder Kriegsplänen widmen.";
		    			
    	//Dem User eine Nachricht schicken
		$this->sendMessage($topic, $meldung, $row['FinishTime'], $row['ID_Kolonie']);
		
        //Bauauftrag löschen
        $this->db->query("DELETE FROM t_auftrag WHERE ID_Auftrag = ".$row['ID_Auftrag']."");
        $this->db->query("DELETE FROM t_auftragkolonie WHERE ID_Auftrag = ".$row['ID_Auftrag']."");
    }
    
    /*Diese Funktion beendet einen Forscher, der fertig gestellt wurde*/
    function finishForscher($ID_Auftrag)
    {
    	//Lade Daten aus Datenbank
    	$this->db->query(
    				"SELECT
    					ID_Auftrag, ID_Kolonie, FinishTime, Kategory
    				FROM
    					t_auftrag
    				WHERE
    					t_auftrag.ID_Auftrag = $ID_Auftrag");
    	$row = $this->db->fetch_array();
    	
    	//Forscher aktualisieren
    	$forscher = &$this->user->getForscher();
    	$forscher->setForscher($forscher->getForscher()+1);
    	    	
    	/*Ereignismeldung vorbereiten*/
    	$topic		= "Wissenssteigerung...";
    	$meldung	= "Einer Ihrer Bürger hat mit Erfolg sein Diplom an der Universität erworben. 
    				Ihnen steht nun ein weiterer Forscher zur Verfügung. Ihre derzeitige Forscheranzahl beträgt: <b>".$forscher->getForscher()."</b>.";

    	//Dem User eine Nachricht schicken
		$this->sendMessage($topic, $meldung, $row['FinishTime'], $row['ID_Kolonie']);
		
		//Auftrag löschen
		$this->db->query("DELETE FROM t_auftrag WHERE ID_Auftrag = ".$row['ID_Auftrag']."");
    }
    
    /*Diese Funktion beendet Einheiten, welche im Bau sind!*/
    function finishAusbildung($time)
    {
    	//Selektiere alle Auftrgäe, die Fertig sind!
    	$this->db->query("
    		SELECT 
    			t_auftrag.ID_Auftrag, ID_Kolonie, FinishTime, ID_Bauplan, Anzahl,
    			Fertig, Dauer 
    		FROM 
    			t_auftrag, t_auftrageinheit 
    		WHERE
    			t_auftrag.ID_User =".$this->user->getUserID()." 
    			AND t_auftrag.ID_Auftrag = t_auftrageinheit.ID_Auftrag
    			AND Kategory = 'Einheiten'
    			AND ID_Kolonie = ".$this->kolonie->getID()."");
    	while( $row = $this->db->fetch_array() )
    	{
    		/*Überprüfen ob Einheit fertig ist!
    		Jede Einheit muss sofort fertig gestellt werden, d.h. wenn eine Einheit
    		10 minuten dauert und die komplette FinishTime ist 3h später... so muss
    		mindestens eine Enheit fertiggestellt werdne, wenn die aktuelle Zeit >= 10 minuten vom
    		Startzeitpunkt aus sind!*/
    		$start_zeit = $row['FinishTime'] - ($row['Anzahl'] * $row['Dauer']);
    		
    		//Wie viele Einheiten können theoretisch fertig sein
    		$anzahl = ($time - $start_zeit) / $row['Dauer'];
    		//Wie viele Einheiten wurden theoretisch fertig?
    		if( $anzahl > 1 AND $time >= $start_zeit )	//Min 1 Einheit fertig!
    		{
    			//Wenn Mehrere Einheiten als möglich produziert wurden dann Anzahl = max Anzahl
    			if( $anzahl > $row['Anzahl'] )
    			{
	  				$anzahl = $row['Anzahl'];
    			}
    			
    			//Wert abrunden, weil es könnten theoretisch kommaenheiten auftreten!
    			$anzahl = floor($anzahl);

    			//es werden $anzahl-Einheiten gutgeschrieben!
    			for( $i=0; $i<$anzahl; $i++ )
    			{
	    			//Einheiten gutschreiben!
	    			$this->user->db->query("
	    				INSERT INTO 
	    					t_einheit 
	    					(ID_Bauplan, Erfahrung, LebenProzent, ID_User, ID_Kolonie, ID_Flotte)
	    				VALUES
	    					(".$row[3].", 0, 1, ".$this->user->getUserID().", ".$row[1].",0)");
    			}
    			
    			//Anzahl auf 0 setzen, da alle Einheiten fertig sind!
    			$this->user->db->query("UPDATE t_auftrageinheit SET Anzahl=Anzahl-$anzahl WHERE ID_Auftrag = ".$row[0]."");
    			$this->user->db->query("UPDATE t_auftrageinheit SET Fertig = Fertig+$anzahl WHERE ID_Auftrag = ".$row[0]."");
    		}
    		
    		//Ist FinishTime <= $time?
    		if( $row['FinishTime'] <= $time )
    		{
    			/*Daten für Ereignismeldung vorbereiten*/
    			//Bauplan objekt erzeugen
    			$bauplan = new BAUPLAN($this->db, $this->user, $this->kolonie->getID());
    			$bauplan->loadBauplan($row['ID_Bauplan']);
    			
    			//Ereignisdaten setzen
    			$topic = "Ausbildung abgeschlossen";
    			$text = "Die Ausbildung von ".$row['Anzahl']." ".$bauplan->getBezeichnung()." ist soeben abgeschlossen worden.";
    			
    			//Nachricht senden
    			$this->sendMessage($topic, $text, $row['FinishTime'], $row['ID_Kolonie']);
				
    			//Lösche alle aufträge!
    			$this->user->db->query("DELETE FROM t_auftrag WHERE ID_Auftrag = ".$row[0]."");
    			$this->user->db->query("DELETE FROM t_auftrageinheit WHERE ID_Auftrag = ".$row[0]."");
    		}
    	}    	
    }
    
    /*Diese Funktion beendet eine Vorkommensuche*/
    function finishSearching($ID_Auftrag)
    {    	    
    	//Lade Daten aus Datenbank
    	$this->db->query(
    				"SELECT
    					t_auftrag.ID_Auftrag as ID_Auftrag, ID_Kolonie, FinishTime, ID_Rohstoff, Erfolg, FinishReal, Kategory
    				FROM
    					t_auftrag, t_auftragvorkommensuche
    				WHERE
    					t_auftrag.ID_Auftrag = $ID_Auftrag
    				AND
    					t_auftrag.ID_Auftrag = t_auftragvorkommensuche.ID_Auftrag");
    	$row = $this->db->fetch_array();
    	
    	/*war Suche erfolgreich?*/
    	if( $row['Erfolg'] == 'ja' )	//ja
    	{    		
			//Ihre Suchtrupps haben ein neues Vorkommen entdeckt
            $vorkommen = new VORKOMMEN(new DATENBANK());
            $vorkommen->saveVorkommen($row['ID_Rohstoff'], $this->user->getUserID(), $row['ID_Kolonie'], $row['FinishTime']);
                
            //Setze Größe des Vorkommens
            $anzahl = $vorkommen->getSize();            
            
            /*ÜBerprüfen ob Vorkommen aktuallisiert werden muss!*/
            if( $ID_Kolonie == $row['ID_Kolonie'] )
            {
            	$user->loadVorkommen($ID_Kolonie);
            }
            /*Ereignismeldung vorbereiten*/
            $res = new ROHSTOFF();
    		$res->loadRohstoff($row['ID_Rohstoff']);
    		$topic 		= "Vorkommensuche...";
    		$meldung	= "Die Suche nach <b>".$res->getBezeichnung()."</b> verlief <font color=\"green\">erfolgreich</font><br>";
        }
        else 	//Vorkommenssuche war erfolglos!
        {     	
        	/*Ereignismeldung vorbereiten*/
    		$res		= new ROHSTOFF();
    		$res->loadRohstoff($row['ID_Rohstoff']);
    		$topic		= "Vorkommensuche...";
    		$meldung	= "Die Suche nach <b>".$res->getBezeichnung()."</b> verlief <font color=\"red\">erfolglos</font></br>";
        }

        //Schreibe User einer Ereignismeldung
        $this->sendMessage($topic, $meldung, $row['FinishTime'], $row['ID_Kolonie']);
              	
        //Lösche Auftrag
        $this->db->query("DELETE FROM t_auftrag WHERE ID_Auftrag = $ID_Auftrag");
        $this->db->query("DELETE FROM t_auftragvorkommensuche WHERE ID_Auftrag = $ID_Auftrag");
    }
    
    /*Sendet Nachricht, wenn Ereignis eingetreten ist!*/
    function sendMessage($topic, $text, $time, $ID_Kolonie)
    {    	
    	//Nachricht verschicken!
    	$ereignis = new EREIGNIS();
		$ereignis->saveEreignis($text, $topic, $this->user->getUserID(), $ID_Kolonie); 
		$ereignis->setDatum($time);
    }
    
    /*Diese Funktion überprüft ob bereits gebaut wird und leitet ein Gebäudebau ein*/
    function startBau($ID_Gebäude, $ID_Kolonie)
    {    	
    	//Deklarationen
        $error = 1;
                
        //Überprüfen ob gebaut werden kann
        $error = $this->checkBau($ID_Kolonie);
        if( $error == 1 )
        {            
            //Gebäudedaten laden
            $geb = new GEBÄUDE();										//Gebäudeobjekt erzeugen
            $geb->loadGebäude($ID_Gebäude, $this->user->getRasseID());	//Objektdaten laden
            
            ///Überprüfen ob User Requirements erfüllt!
            $erfuellt = $geb->checkRequirement($this->user, $ID_Kolonie);
            
            //erfüllt User requirements?
            if( $erfuellt == 1 )
            {
	            //Gebäudekosten laden
	           	$level = $_SESSION['user']->getGebäudeLevel($ID_Gebäude);
	            $kosten = $geb->getKosten($level);
	            
	            //Kosten überprüfen
	            if( ($this->user->getRohstoffAnzahl(1) < $kosten[0]) || ($this->user->getRohstoffAnzahl(2) < $kosten[1]) || 
	            	($this->user->getRohstoffAnzahl(6) < $kosten[2]) || ($this->user->getRohstoffAnzahl(8) < $kosten[3]) )
	            {
	            	//User hat nicht genügend rohstoffe
	            	$error = -2;
	            }
	            else	//Genügend Rohstoffe vorhanden
	            {            
		        	//Bauzeit erimtteln
		        	if( $this->_ENERGIENIVEAU == 'critical' )
		        	{
		        		$bauzeit = $geb->getBuildTime($level, $_SESSION['user']->getGebäudeLevel(1)) * $this->bauzeit_verlaengerungs_faktor;
		        	}
		        	else 
		        	{
		        		$bauzeit = $geb->getBuildTime($level, $_SESSION['user']->getGebäudeLevel(1));
		        	}
		        	
	            	//Fertigstellung ist bauzeit + aktuelle Zeit
		        	$Completion = time() + $bauzeit;
		            
		            //Bauauftrag in Tabelle eintragen
	            	$this->db->query("INSERT INTO t_auftrag (ID_User, FinishTime, ID_Kolonie, Kategory)  VALUES (".$this->user->getUserID().", $Completion, $ID_Kolonie, 'Gebäude');");
	            	$auftrags_id = $this->db->last_insert();
	            	$this->db->query("INSERT INTO t_auftraggebaeude (ID_Auftrag, ID_Gebäude, StartTime, LastChange, Prozent) VALUES ($auftrags_id, $ID_Gebäude, ".time().", ".time().", 0);");
	            	            	
	            	/*Rohstoffe vom User abziehen*/	            	
	            	$this->user->setRohstoffAnzahl((-1)*$kosten[0], 1); //Eisen
	            	$this->user->setRohstoffAnzahl((-1)*$kosten[1], 2); //Stein
	            	$this->user->setRohstoffAnzahl((-1)*$kosten[2], 6); //Stahl
	            	$this->user->setRohstoffAnzahl((-1)*$kosten[3], 8); //Titan
	            }
	        }
	        else 
	        {
	        	//Requirements werden nicht erfüllt!
        		$error = -3;
	        }
        }
        else 
        {
        	//Fehler: Es sind noch Bauaufträge vorhanden!
            $error = -1;
        }
        return $error;  
    }
    
    /*Diese Funktion überprüft ob bereits geforscht wird und leitet eine Forschung ein*/
	function startForschung($ID_Forschung, $ID_Kolonie)
    {
    	//Deklarationen
        $error = 1;
                
        //Überprüfen ob gebaut werden kann
        $error = $this->checkForschung($ID_Kolonie);
        if( $error == 1 )
        {            
            //Überprüfen ob User sich geäbude leisten kann!
            $forsch = new FORSCHUNG($this->user, $this->user->getRasseID());
            $forsch->loadForschung($ID_Forschung);
            
            //erfüllt User requirements?
            $erfuellt = $forsch->checkRequirement($this->user, $ID_Kolonie);
            if( $erfuellt == 1 )
            {
	            //Forschungskosten laden
	            $level 	= $_SESSION['user']->getForschungsLevel($ID_Forschung);
	            $kosten = $forsch->getKosten($level);
	                 
	            //Forscherobjekt erzeugen!
	            $forscher = &$this->user->getForscher();
	                
	            //Kosten überprüfen
            	if( $kosten > $forscher->getForschungspunkte() )
	            {
	            	//User hat nicht genügend rohstoffe
	            	$error = -2;
	            }
	            else	//Genügend Rohstoffe vorhanden
	            {    
	            	//Bauzeit erimtteln
		        	if( $this->_ENERGIENIVEAU == 'critical' )
		        	{
		        		$bauzeit = $forsch->getBuildTime($level, $forscher->getForscher()) * $this->bauzeit_verlaengerungs_faktor;
		        		
		        	}
		        	else 
		        	{
		        		$bauzeit = $forsch->getBuildTime($level, $forscher->getForscher());
		        	}
		        			            
		            //Bauauftrag in Tabelle eintragen
	            	$this->db->query("INSERT INTO t_auftrag (ID_User, FinishTime, ID_Kolonie, Kategory)  VALUES (".$this->user->getUserID().", ".(time()+$bauzeit).", $ID_Kolonie, 'Forschung');");
	            	$auftrags_id = $this->db->last_insert();
	            	$this->db->query("INSERT INTO t_auftragforschung (ID_Auftrag, ID_Forschung, StartTime, LastChange, Prozent) VALUES ($auftrags_id, $ID_Forschung, ".time().", ".time().",0);");
	            	               	         	
	            	/*Rohstoffe aktuallisieren*/
	            	$forscher->setForschungspunkte($forscher->getForschungspunkte() - $kosten);	//Forschungspunkte abziehen!          
	            }
            }
            else 	//Requirements werden nicht erfüllt!
            {
            	$error = -3;
            }
        }
        else 
        {
        	//Fehler: Es wird bereits geforscht!
            $error = -1;
        }
        return $error;
    }
    
    /*Funktion leitet Ausbau einer Kolonie ein!*/
    function startKolonieAusbau()
    {
    	//Deklarationen
    	$error = 1;
    	
    	/*Wird bereits eine Kolonie ausgebaut?*/
    	$this->db->query("SELECT COUNT(*) FROM t_auftrag WHERE Kategory = 'Kolonieausbau' AND ID_User = ".$this->user->getUserID()." AND ID_Kolonie = ".$this->kolonie->getID()."");
    	$anzahl = $this->db->fetch_result(0);
    	
    	//Wird kolonie bereits ausgebaut?
    	if( $anzahl <= 0 )	//nein!
    	{
	    	//Überprüfe Requirements und Kosten!
	    	$req = $this->kolonie->getRequirement();
	    	
	    	//Durchlaufe Requirements
	    	for( $i=0; $i<count($req); $i++ )
	    	{
	    		//Zerteile requirement
				$req_detail = explode("|", $req[$i]);
				
				//Überprüfe ob vorhanden
				switch($req_detail[0])
				{
					//Forschung
					case 2:
						break;
					//Gebäude
					case 3:
						//LVl ermitteln
						$lvl = $this->user->getGebäudeLevel($req_detail[1]);
						break;
					//Bewohner
					case 4:
						//Bevölkerung laden
						$lvl = $this->bevoelkerung->getAnzahl();
						break;
				}	
				
				//Überprüfen ob Requirement erfüllt wird!
				if( $lvl < $req_detail[2] )
				{
					//Fehler Requirement wird nicht erfüllt!
					$error = -2;
					break;
				}
	    	}
	    	
	    	//Kostenarray
	    	$kosten = $this->kolonie->getKosten();
	    	
	    	//Durchlaufe Kosten
	    	for( $i=0; $i<count($kosten); $i++ )
	    	{
	    		//Kostenarray zerlegen => id|anzahl
				$kosten_array = explode("|", $kosten[$i]);
									
				//HAt user Anzahl?
				if( $this->user->getRohstoffAnzahl($kosten_array[0]) < $kosten_array[1] )
				{
					//Fehler zu wenige Rohstoffe vorhanden!
					$error = -3;
					break;
				}
	    	}
	    	
	    	/*Wenn keine Fehler, dann weiter machen!*/
	    	if( $error == 1 )
	    	{
	    		//FinishTime bestimmen
	    		$bauzeit = $this->kolonie->getBuildTime();
	    		
	    		//Wenn Energieniveau schlecht ist Bauzeit verdreifachen
	    		if( $this->_ENERGIENIVEAU == 'critical' )
	    		{
	    			$bauzeit = $bauzeit * 3;
	    		}
	    		
				//Auftrag in Db eintragen!
				$this->db->query("INSERT INTO t_auftrag (ID_User, FinishTime, ID_Kolonie, Kategory) VALUES (".$this->user->getUserID().", ".(time() + $bauzeit).", ".$this->kolonie->getID().", 'Kolonieausbau')");
				$auftrags_id = $this->db->last_insert();
				$this->db->query("INSERT INTO t_auftragkolonie (ID_Auftrag, StartTime, LastChange, Prozent) VALUES ($auftrags_id, ".time().", ".time().", 0)");
				
				//Rohstoffe abziehen!
				for($i=0; $i<count($kosten); $i++)
				{
					//Kostenarray zerlegen => id|anzahl
					$kosten_array = explode("|", $kosten[$i]);
					
					//Rohstoffe abziehen
					$this->user->setRohstoffAnzahl((-1)*$kosten_array[1], $kosten_array[0]);
				}
	    	}
    	}
    	else //Fehler... Wird bereits ausgebaut!
    	{
    		$error = -1;
    	}
    	
    	//Rückgabe
    	return $error;
    }
    
    /*Starte Forscherausbildung*/
    function startForscherAusbildung()
    {
    	//Deklarationen
    	$error 					= 1;	//Keine Fehler!
    	$forschungszentrale_id	= 24;
    	
    	//Überprüfen ob schongebaut wird
    	$this->db->query("SELECT COUNT(*) FROM t_auftrag WHERE ID_User = ".$this->user->getUserID()." AND ID_Kolonie = ".$this->kolonie->getID()." AND Kategory = 'Forscher'");
    	$anzahl = $this->db->fetch_result(0);
    	
    	//Aufträge vorhanden?
    	if( $anzahl <= 0 )	//Kein Auftrag vorhanden!
    	{
    		/*überprüfen ob User sich Ausbildung leisten kann!*/
    		//Forscherobjekt laden
    		$forscher = &$this->user->getForscher();
    		//kosten laden!
			$kosten = $forscher->getForscherKosten($_SESSION['user']->getGebäudeLevel(24));
			//HAt User genügend Nahrung?
			if( $this->user->getRohstoffAnzahl(3) >= $kosten )	//Genügend Res da!
			{					
				//ÜBerprüfung
				if( $this->bevoelkerung->getAnzahl()-1 >= 1 )
				{					
		    		//Bevölkerung um einen herabsetzen
		    		$this->bevoelkerung->setBevölkerung(-1);
		    		//Nahrung abziehen
		    		$this->user->setRohstoffAnzahl((-1)*$kosten, 3);
		    		
		    		//FinishTime bestimmen
			    	$finish_time = time() + $forscher->getAusbildungsZeit($this->user->getGebäudeLevel($forschungszentrale_id));
			    	$this->db->query("INSERT INTO t_auftrag (ID_User, ID_Kolonie, FinishTime, Kategory) VALUES (".$this->user->getUserID().", ".$this->kolonie->getID().", $finish_time, 'Forscher')");
					
				}
				else 	//Zu wenig Bevölkerung
				{
					$error = -3;
				}
			}
			else 		//Zu wenig Nahrung
			{
				$error = -2;	
			}
    	}
    	else //Keine Ausbildung möglich, da bereits ausgebildet wird!
    	{
    		$error = -1;
    	}
    	return $error;
    }
    
    /*Funktion leitet die Ausbildung einer Einheit ein!
	$typ gibt an ob es sich um einen Infanteristen oder Fahrzeug handelt
	1 => Infanterist
	2 => MEch, FAhrzeug...*/
    function startAusbildung($ID_Bauplan, $anzahl, $ID_Kolonie, $typ)
    {
    	//Deklarationen
    	$error = 1;
    	
    	/*Wird bereits eine Einheit ausgebildet und offset bestimmen!*/
    	$time_offset = $this->checkAusbildung($ID_Kolonie, $typ);
    	
    	//ISt OFfset gesett?
    	if( !empty($time_offset) ) //Offset gesetzt & Einheit wird bereits ausgebildet
    	{
    		//Startzeit setzen!
    		$start = $time_offset;
    	}
    	else 	//Kein Offset vorhanden!
    	{
    		//Startzeit setzen!
    		$start = time();
    	}

    	//Bauplanobjekt erzeugen
    	$bauplan = new BAUPLAN($this->db, $this->user, $ID_Kolonie);
    	$bauplan->loadBauplan($ID_Bauplan);
    	    	
    	//Kosten laden
    	$kosten = $bauplan->getKosten();	/*$this->kosten[0] 	//Eisen (ID = 1)
											$this->kosten[1] 	//Stahl (ID = 6)
											$this->kosten[2]	//Titan (ID = 8)
											$this->kosten[3]	//Kunststoff (ID = 5)
											$this->kosten[4] 	//Wasserstoff (ID = 10)
											$this->kosten[5] 	//Uran (ID = 11)
											$this->kosten[6] 	//Plutonium (ID = 12)
											$this->kosten[7] 	//Gold (ID = 14)
											$this->kosten[8] 	//Diamant (ID = 13)
											$this->kosten[9] 	//Bevölkerung*/
		//Genügend Rohstoffe vorhanden?		
		if( ($kosten[0] * $anzahl) <= $this->user->getRohstoffAnzahl(1) 
			&& 
			($kosten[1] * $anzahl) <= $this->user->getRohstoffAnzahl(6)
			&&
			($kosten[2] * $anzahl) <= $this->user->getRohstoffAnzahl(8)
			&&
			($kosten[3] * $anzahl) <= $this->user->getRohstoffAnzahl(5)
			&&
			($kosten[4] * $anzahl) <= $this->user->getRohstoffAnzahl(10)
			&&
			($kosten[5] * $anzahl) <= $this->user->getRohstoffAnzahl(11)
			&&
			($kosten[6] * $anzahl) <= $this->user->getRohstoffAnzahl(12)
			&&
			($kosten[7] * $anzahl) <= $this->user->getRohstoffAnzahl(14)
			&&
			($kosten[8] * $anzahl) <= $this->user->getRohstoffAnzahl(13)
			&&
			($kosten[9] * $anzahl) < $this->bevoelkerung->getAnzahl() )			/*nur < , weil sonst keine Bevölkerung mehr da :(*/
		{	
			//Rohstoffe abziehen!
			$this->user->setRohstoffAnzahl((-1)*$kosten[0] * $anzahl, 1);	//Eisen
			$this->user->setRohstoffAnzahl((-1)*$kosten[1] * $anzahl, 6);	//Stahl
			$this->user->setRohstoffAnzahl((-1)*$kosten[2] * $anzahl, 8);	//Titan
			$this->user->setRohstoffAnzahl((-1)*$kosten[3] * $anzahl, 15);	//Kunststoff
			$this->user->setRohstoffAnzahl((-1)*$kosten[4] * $anzahl, 10);	//Wasserstoff
			$this->user->setRohstoffAnzahl((-1)*$kosten[5] * $anzahl, 11);	//Uran
			$this->user->setRohstoffAnzahl((-1)*$kosten[6] * $anzahl, 12);	//Plutonium
			$this->user->setRohstoffAnzahl((-1)*$kosten[7] * $anzahl, 14);	//Gold
			$this->user->setRohstoffAnzahl((-1)*$kosten[8] * $anzahl, 13);	//Diamant
			$this->bevoelkerung->setBevölkerung((-1)*$kosten[9] * $anzahl);		//Bevölkerungsdaten setzen
			
			//Überprüfen von welchem Typ die Einheit ist
			if( $bauplan->getChassis() == 1 )	//Infanterist
			{
				//Kaserne zur Bauzeitoptimierung verwenden
				$geb_level = $this->user->getGebäudeLevel(29);
			}
			else								//Panzer, Mech oder ziviles Fahrzeug
			{
				//Waffenfabrik zur Bauzeitoptimierung verwenden
				$geb_level = $this->user->getGebäudeLevel(31);		
			}
						
			//Dauer setzen
			$dauer = $bauplan->getBuildTime($geb_level);
			//Finish_time bestimmen
			$finish_time = $start + $dauer*$anzahl;
					
			//Daten in Tabelle eintragen!
			$this->db->query("
				INSERT INTO 
					t_auftrag
					(ID_User, FinishTime, ID_Kolonie, Kategory )
				VALUES
					(".$this->user->getUserID().", $finish_time, $ID_Kolonie, 'Einheiten')");
			
			//LastInsertId holen
			$ID_Auftrag = $this->db->last_insert();
			
			//Vervollständige Daten
			$this->db->query("
				INSERT INTO
					t_auftrageinheit
					(ID_Auftrag, ID_Bauplan, Anzahl, Fertig, Dauer, Typ)
				VALUES
					($ID_Auftrag, $ID_Bauplan, $anzahl, 0, $dauer, $typ)");	
		}
		else 	//Nicht genügend Rohstoffe vorhanden!
		{
			$error = -1;
		}
		
    	//Rückgabe
    	return $error;
    }
    
    /*Diese Funktion leitet eine Vorkommensuche ein*/
	function searchVorkommen($ID_Rohstoff, $Dauer)
    {    	
    	//DEklarationen
        $error 		= 1;	//Fehlervariabel
        $FinishTime = 0;	//Stunden, wann Vorkommen wohl gefunden wird
        $max_search	= 3;	//Es darf maximal nach 3 Vorkommen gesucht werden
                
        //Rohstoffobjekt erzeugen
        $res = new ROHSTOFF();
        $res->loadRohstoff($ID_Rohstoff);
        
        //Darf User nach diesem Rohstoff suchen?
        if( $res->checkRequirement($this->user, $this->kolonie->getID()) == 1 )
        {
	        //Vorkommen suchen!
	    	$this->db->query("SELECT COUNT(*) FROM t_auftrag WHERE ID_User = ".$this->user->getUserID()." AND ID_Kolonie = ".$this->kolonie->getID()." AND Kategory = 'Vorkommensuche';");
	        $anzahl = $this->db->fetch_result(0);
	        
	    	//Kann Vorkommen gebaut werden!
	        if( $anzahl < $max_search )
	        {
	     		/*Es kann ein Vorkommen gesucht werden und hier muss nun die 
	     		Zeit ermittelt werden, wann die Vorkommenssuche erfolgreich ist, und ob sie überhaupt
	     		erfolgreich ist.
	     		Dazu muss mit Hilfe einer Formel berechnet werden, wie hoch die Wahrscheinlichkeit ist, etwas zu finden
	     		Dazu wird die Häufigkeit des Rohstoffes (also wie oft es vorkommt) in % (0.01 - 1.0) geladen
	     		
	     		Die Wahrscheinlichkeit steigt pro h um 1-3% (zufällig)
	     		Die Werte werden addiert und anschliessend zu der Standardwahrscheinlichkeit addiert
	     		Ist dieser Wert dann >= 0.95 (also 95%) wird das Vorkommen genau nach der Dauer gefunden.
	     		
	     		Sollte die WAahrschenlichkeit bereits nach 3h die 95% überschreiben wird die Fertigstellung der Zeit 
	     		auf time() + 3h gesetzt!
	     		*/
	     		
	            //Hole Häufigkeit
	            $häufigkeit = $res->getHäufigkeit();
	 			//Wahrscheinlichkeit setzen
	 			$wahrscheinlichkeit = $häufigkeit;		//Grundwert der Wahrscheinlichkeit setzen
	 			
	            //Durchlaufe Dauer (in h)
	            for( $i = 1; $i <= $Dauer; $i++ )
	            {
	            	//Ermittle Zuwachs an %
	            	$zuwachs_prozent = mt_rand(1, 11);				//Bereich 1-10%
	            	$zuwachs_prozent = $zuwachs_prozent / 100;		//da Werte 1-3 falsh sind, sondern 0.01-0.03 muss durch 100 geteilt werden
	
	            	//Setze Wahrschenlichkeit
	            	$wahrscheinlichkeit += $zuwachs_prozent;          
	            	
	            	//Ist die Wahrscheinlichkeit >= 95%?
	                if( $wahrscheinlichkeit >= 0.95 )
	                {
	                    //Vorkommen wird nach $i Stunden beendet!
	                	$FinishTime = $i;
	                    break;
	                }
	            }
	            
	            //Wann wird die Vorkommenssuche beendet!
	            if( $FinishTime != 0 )		//Vorkommen wird innerhalb der Dauer gefunden!
	            {
	             	$FinishTime = time() + 60*60 * $FinishTime;
	             	$Erfolg 	= 'ja';
	            }
	            else 						//Vorkommen wird nicht gefunden
	            {
	            	$FinishTime = time() + 60*60*$Dauer;
	            	$Erfolg 	= 'nein';
	            }
	            
	            //Vorkommen eintragen!
	            $FinishReal		= time() + 60*60 * $Dauer;	//Wann wird das Vorkommen wohl gefunden werden?
	            $this->db->query("INSERT INTO t_auftrag (ID_User, FinishTime, ID_Kolonie, Kategory) VALUES (".$this->user->getUserID().", $FinishTime, ".$this->kolonie->getID().", 'Vorkommensuche')");
	            $auftrags_id = $this->db->last_insert();
	            $this->db->query("INSERT INTO t_auftragvorkommensuche (ID_Auftrag, ID_Rohstoff, Dauer, Erfolg, FinishReal) VALUES ($auftrags_id, $ID_Rohstoff, $Dauer, '$Erfolg', $FinishReal)");
	        }
	        else 
	        {
	        	//Fehler 132
	            $error = -1;
	        }
        }
        else 	//Requirements werden nicht erfüllt!
        {
        	$error = -2;
        }
        return $error;
    }
    
    /*Überprüft ob ein Gebäude gebaut wird oder nicht und gibt ggf. die FinishTime zurück!*/
    function getGebBau($ID_Kolonie)
    {
    	//Lade BebäudebauAufträge der Kolonie
    	$this->db->query("SELECT ID_Gebäude, FinishTime, Prozent, StartTime, LastChange, t_auftrag.ID_Auftrag AS ID_Auftrag FROM t_auftrag, t_auftraggebaeude WHERE t_auftrag.ID_Auftrag = t_auftraggebaeude.ID_Auftrag AND ID_Kolonie = $ID_Kolonie");
    	$ergebnis = $this->db->fetch_array();
    	    	    	
    	//Rückgabewert!
    	return $ergebnis;
    }
    
    /*Überprüft ob ein Forschung geforscht wird oder nicht und gibt ggf. die FinishTime zurück!*/
    function getForschungsBau($ID_Kolonie)
    {
    	//Lade Gebäudebauten der Kolonie
    	$this->db->query("SELECT ID_Forschung, FinishTime, Prozent, StartTime, LastChange, t_auftrag.ID_Auftrag AS ID_Auftrag FROM t_auftrag, t_auftragforschung WHERE t_auftrag.ID_Auftrag = t_auftragforschung.ID_Auftrag AND ID_Kolonie = $ID_Kolonie");
    	$ergebnis = $this->db->fetch_array();  	
    	
    	//Rückgabewert!
    	return $ergebnis;
   	}
   	
   	/*Funktion gibt in Auftrag gegebene Kolonieupdates zurück*/
   	function getKolonieausbau($ID_Kolonie)
   	{
   		//LAde Auftrag
   		$this->db->query("SELECT FinishTime, Prozent, StartTime, LastChange, t_auftrag.ID_Auftrag as ID_Auftrag FROM t_auftrag, t_auftragkolonie WHERE t_auftrag.ID_Auftrag = t_auftragkolonie.ID_Auftrag AND ID_Kolonie = ".$this->kolonie->getID().";");
   		$ergebnis = $this->db->fetch_array();
   		
   		//Rückgabewert
   		return $ergebnis;
   	}
   	
   	 /*Überprüft ob Einheiten ausgebildet werden
    und gibt die BauplanBezeichnung, sowie Anzahl und verbleibende Zeit
    nach der FinishTime aufsteigend zurück*/
    function getAusbildung($ID_Kolonie)
    {
    	//Werden Einheiten ausgebildet
    	$this->db->query("
    		SELECT 
    			t_auftrag.ID_Auftrag, ID_Bauplan, FinishTime, Anzahl, Fertig 
    		FROM 
    			t_auftrag, t_auftrageinheit 
    		WHERE 
    			t_auftrag.ID_Auftrag = t_auftrageinheit.ID_Auftrag 
    			AND t_auftrag.ID_Kolonie = $ID_Kolonie 
    			AND ID_User = ".$this->user->getUserID()."
    		ORDER BY 
    			t_auftrag.FinishTime ASC;");
    	$i = 0;
    	while( $row = $this->db->fetch_array() )
    	{
    		//Bauplanobjekt erzeugen um profilnamen zue rmitteln
    		$bauplan = new BAUPLAN(new DATENBANK(), $this->user, $ID_Kolonie);
    		$bauplan->loadBauplan($row['ID_Bauplan']);
    		
    		//Ergebnisdaten setzen
    		$daten['ID_Auftrag'][$i]	= $row['ID_Auftrag'];
    		$daten['Bauplan'][$i] 		= $bauplan->getBezeichnung();
    		$daten['FinishTime'][$i] 	= $row['FinishTime'];
    		$daten['Anzahl'][$i] 		= $row['Anzahl'];
    		$daten['Fertig'][$i] 		= $row['Fertig'];
    		$i++;
    	}
    	
    	//Rückgabe
    	return $daten;
    }
   	
   	/*Gibt ForscherAusbildung*/
    function getForscherAusbildung()
    {
    	$this->db->query("SELECT FinishTime FROM t_auftrag WHERE ID_User = ".$this->user->getUserID()." AND ID_Kolonie = ".$this->kolonie->getID()." AND Kategory = 'Forscher'");
    	$finish_time = $this->db->fetch_result(0);
    	return $finish_time;
    }
   	
   	//Diese Funktion überprüft ob geaut werden kann
    function checkBau($ID_Kolonie)
    {
    	//Deklarationen
    	$error = 1;

    	//Gebäudebau laden
    	$this->db->query("SELECT COUNT(*) FROM t_auftrag WHERE ID_User = ".$this->user->getUserID()." AND ID_Kolonie = $ID_Kolonie AND Kategory = 'Gebäude'");
    	$anzahl = $this->db->fetch_result(0);
    	
    	if( $anzahl > 0 )
    	{
    		$error = -1;
    	}
    	return $error;
    }
    
     /*Diese funktion überprüft ob geforscht wird*/
    function checkForschung($ID_Kolonie)
    {
    	//Deklarationen
    	$error = 1;
    	
    	//Gebäudebau laden
    	$this->db->query("SELECT COUNT(*) FROM t_auftrag WHERE ID_User = ".$this->user->getUserID()." AND ID_Kolonie = $ID_Kolonie AND Kategory = 'Forschung'");
    	$anzahl = $this->db->fetch_result(0);
    	
    	if( $anzahl > 0 )
    	{
    		$error = -1;
    	}
    	return $error;
    }
    
    /*Diese funktion überprüft ob eine Einheit gebaut wird
    Ist dies der Fall, so wird der maximale Completion-Wert zurückgeliefert um diesen anschliessend
    auf die neue Dauer hinzuzufügen.
    Ausserdem muss ein Typ-Wert übergeben werden, damit überprüft werden kann
    ob es sich um eine Infanteristen-Ausbildung oder um eine Fahrzeugausbildung handelt
    $typ = 1 //Infanterist
    $typ = 2 //Mech, Panzer,...*/
    function checkAusbildung($ID_Kolonie, $typ)
    {
    	//Deklarationen
    	$time_offset = 0;	//Wie viele Sekunden müssen zur Bauzeit hinzugefügt werden
    	
    	//Gebäudebau laden
    	$this->db->query("SELECT Max(FinishTime) FROM t_auftrag, t_auftrageinheit WHERE t_auftrag.ID_Auftrag = t_auftrageinheit.ID_Auftrag AND t_auftrageinheit.Typ = $typ AND ID_User = ".$_SESSION['user']->getUserID()." AND ID_Kolonie = $ID_Kolonie");
   		$time_offset = $this->db->fetch_result(0);
    	
    	//Rückgabe des Timeoffsets
    	return $time_offset;
	}   
    
    function changeGebFinishTime($time)
    {
    	//Gebäude laden
 		$geb_daten = $this->getGebBau($this->kolonie->getID());
 		
 		//Wenn Gebäude gebaut wird, Bauzeit anpassen
 		if(!empty($geb_daten['ID_Gebäude']))
 		{
 			/*Bauzeit anhand des Gebäudes ermitteln*/
 			$geb = new GEBÄUDE();
 			$geb->loadGebäude($geb_daten['ID_Gebäude'], $this->user->getRasseID());

 			//Bauzeit laden
 			$bauzeit = $geb->getBuildTime($this->user->getGebäudeLevel($geb_daten['ID_Gebäude']), $this->user->getGebäudeLevel(1));
 			
 			//Ist Energienivieau kritisch gewesen?
 			if( $this->_ENERGIENIVEAU_OLD == 'critical' )
 			{
 				//Energieniveau war kritisch, Bauzeit um 3h verlängern
 				$bauzeit = $bauzeit*$this->bauzeit_verlaengerungs_faktor;
 			}
 			
 			/*Zeit ermitteln wie lange schon gebaut wurde*/
 			//echo "Aktuelle Zeit: ".date("d.m.Y H:i:s", $time)." ($time) und lastChange: ".date("d.m.Y H:i:s", $geb_daten['LastChange'])." (".$geb_daten['LastChange'].")<br>";
 			$zeit_gebaut = $time - $geb_daten['LastChange'];
 			     			
 			//Von der Bauzeit die Prozente abziehen
 			$bauzeit = $bauzeit - $bauzeit * ($geb_daten['Prozent']/100);
 			
 			/*Prozentualen Anteil bestimmen, welcher gebaut wurde und addieren*/
 			$prozent = 100/$bauzeit * $zeit_gebaut;
 			$this->db->query("UPDATE t_auftraggebaeude SET Prozent = (Prozent + $prozent) WHERE ID_Auftrag = ".$geb_daten['ID_Auftrag']."");
 			
 			/*echo "Sie haben ".($zeit_gebaut / 3600)." h bereits gebaut<br>";
 			echo "Bauzeit: $bauzeit<br>";
 			echo "Das sind $prozent %<br>";*/
 			
 			/*Fertigstellungszeit setzen*/
 			//Bauzeit erneut laden
 			$bauzeit = $geb->getBuildTime($this->user->getGebäudeLevel($geb_daten['ID_Gebäude']), $this->user->getGebäudeLevel(1));
 			//noch verbleibende Zeit ermitteln
 			$bauzeit = $bauzeit - ((($prozent + $geb_daten['Prozent'])/100) * $bauzeit);
 			     			     			
 			//Ist Energienivieau kritisch?
 			if( $this->_ENERGIENIVEAU == 'critical' )
 			{
 				//Energieniveau ist kritisch, Bauzeit um 3h verlängern
 				$bauzeit = $bauzeit*$this->bauzeit_verlaengerungs_faktor;
 			}
 			 			
 			//FinishTime setzen
			$this->db->query("UPDATE t_auftraggebaeude SET LastChange = $time WHERE ID_Auftrag = ".$geb_daten['ID_Auftrag']."");
 			$this->db->query("UPDATE t_auftrag SET FinishTime = ($time + $bauzeit) WHERE ID_Auftrag = ".$geb_daten['ID_Auftrag']."");
 		}
    }   
    
    /*FUnktion verlängert oder verkürzt (je nach Energieniveau) Forschungszeiten der ausgewählten Kolonie*/
    function changeForschungFinishTime($time)
    {
    	//Gebäude laden
    	$forsch_daten = $this->getForschungsBau($this->kolonie->getID());
 		
 		//Wenn Gebäude gebaut wird, Bauzeit anpassen
 		if(!empty($forsch_daten['ID_Forschung']))
 		{
 			/*Bauzeit anhand des Gebäudes ermitteln*/
 			$forschung = new FORSCHUNG($this->user, $this->user->getRasseID());
 			$forschung->loadForschung($forsch_daten['ID_Forschung']);
 			
 			//Forscherobjekt laden
 			$forscher = &$this->user->getForscher();
 			
 			//Bauzeit laden
 			$bauzeit = $forschung->getBuildTime($this->user->getForschungsLevel($forsch_daten['ID_Forschung']), $forscher->getForscher());
 			 			
 			//Ist Energienivieau kritisch gewesen?
 			if( $this->_ENERGIENIVEAU_OLD == 'critical' )
 			{
 				//Energieniveau war kritisch, Bauzeit um 3h verlängern
 				$bauzeit = $bauzeit*$this->bauzeit_verlaengerungs_faktor;
 			}
 			
 			/*Zeit ermitteln wie lange schon gebaut wurde*/
 			//echo "Aktuelle Zeit: ".date("d.m.Y H:i:s", $time)." ($time) und lastChange: ".date("d.m.Y H:i:s", $forsch_daten['LastChange'])." (".$forsch_daten['LastChange'].")<br>";
 			$zeit_gebaut = $time - $forsch_daten['LastChange'];
 			     			
 			//Von der Bauzeit die Prozente abziehen
 			$bauzeit = $bauzeit - $bauzeit * ($forsch_daten['Prozent']/100);
 			
 			/*Prozentualen Anteil bestimmen, welcher gebaut wurde und addieren*/
 			$prozent = 100/$bauzeit * $zeit_gebaut;
 			$this->db->query("UPDATE t_auftraggebaeude SET Prozent = (Prozent + $prozent) WHERE ID_Auftrag = ".$forsch_daten['ID_Auftrag']."");
 			
 			/*echo "Sie haben ".($zeit_gebaut / 3600)." h bereits gebaut<br>";
 			echo "Bauzeit: $bauzeit<br>";
 			echo "Das sind $prozent %<br>";*/
 			
 			/*Fertigstellungszeit setzen*/
 			//Bauzeit erneut laden
 			$bauzeit = $forschung->getBuildTime($this->user->getForschungsLevel($forsch_daten['ID_Forschung']), $forscher->getForscher());
 			//noch verbleibende Zeit ermitteln
 			$bauzeit = $bauzeit - ((($prozent + $geb_daten['Prozent'])/100) * $bauzeit);
 			     			     			
 			//Ist Energienivieau kritisch?
 			if( $this->_ENERGIENIVEAU == 'critical' )
 			{
 				//Energieniveau ist kritisch, Bauzeit um 3h verlängern
 				$bauzeit = $bauzeit*$this->bauzeit_verlaengerungs_faktor;
 			}
 			 			
 			//FinishTime setzen
			$this->db->query("UPDATE t_auftragforschung SET LastChange = $time WHERE ID_Auftrag = ".$forsch_daten['ID_Auftrag']."");
 			$this->db->query("UPDATE t_auftrag SET FinishTime = ($time + $bauzeit) WHERE ID_Auftrag = ".$forsch_daten['ID_Auftrag']."");
 		}
    }  
    
    function changeKolonieFinishTime($time)
    {
    	//KolonieausbauDaten laden
    	$kolo_daten = $this->getKolonieausbau($this->kolonie->getID());
    	 		
 		//Wenn Gebäude gebaut wird, Bauzeit anpassen
 		if(!empty($kolo_daten['ID_Auftrag']))
 		{
 			//Bauzeit laden
 			$bauzeit = $this->kolonie->getBuildTime();
 			 			
 			//Ist Energienivieau kritisch gewesen?
 			if( $this->_ENERGIENIVEAU_OLD == 'critical' )
 			{
 				//Energieniveau war kritisch, Bauzeit um 3h verlängern
 				$bauzeit = $bauzeit*$this->bauzeit_verlaengerungs_faktor;
 			}
 			
 			/*Zeit ermitteln wie lange schon gebaut wurde*/
 			//echo "Aktuelle Zeit: ".date("d.m.Y H:i:s", $time)." ($time) und lastChange: ".date("d.m.Y H:i:s", $kolo_daten['LastChange'])." (".$kolo_daten['LastChange'].")<br>";
 			$zeit_gebaut = $time - $kolo_daten['LastChange'];
 			     			
 			//Von der Bauzeit die Prozente abziehen
 			$bauzeit = $bauzeit - $bauzeit * ($kolo_daten['Prozent']/100);
 			
 			/*Prozentualen Anteil bestimmen, welcher gebaut wurde und addieren*/
 			$prozent = 100/$bauzeit * $zeit_gebaut;
 			$this->db->query("UPDATE t_auftragkolonie SET Prozent = (Prozent + $prozent) WHERE ID_Auftrag = ".$kolo_daten['ID_Auftrag']."");
 			
 			/*echo "Sie haben ".($zeit_gebaut / 3600)." h bereits gebaut<br>";
 			echo "Bauzeit: $bauzeit<br>";
 			echo "Das sind $prozent %<br>";*/
 			
 			/*Fertigstellungszeit setzen*/
 			//Bauzeit erneut laden
 			$bauzeit = $this->kolonie->getBuildTime();
 			
 			//noch verbleibende Zeit ermitteln
 			$bauzeit = $bauzeit - ((($prozent + $kolo_daten['Prozent'])/100) * $bauzeit);
 			     			     			
 			//Ist Energienivieau kritisch?
 			if( $this->_ENERGIENIVEAU == 'critical' )
 			{
 				//Energieniveau ist kritisch, Bauzeit um 3h verlängern
 				$bauzeit = $bauzeit*$this->bauzeit_verlaengerungs_faktor;
 			}

 			//FinishTime setzen
			$this->db->query("UPDATE t_auftragkolonie SET LastChange = $time WHERE ID_Auftrag = ".$kolo_daten['ID_Auftrag']."");
 			$this->db->query("UPDATE t_auftrag SET FinishTime = ($time + $bauzeit) WHERE ID_Auftrag = ".$kolo_daten['ID_Auftrag']."");
 		}
    	
    }
    
    /*Diese funktion lädt alle Aufträge, welche in Auftrag gegeben wurden!*/
    function getAuftraege()
    {
    	//Aufträge laden
    	$this->db->query("SELECT 
    						ID_Auftrag, ID_User, FinishTime, ID_Kolonie, Kategory
    					FROM
    						t_auftrag
    					WHERE
    						ID_User = ".$this->user->getUserID()."
    					AND
    						Kategory != 'Vorkommensuche'
    					ORDER BY
    						FinishTime ASC;");
    	while( $row = $this->db->fetch_array() )
    	{
    		$auftraege[] = array(
    							'ID' => $row['ID_Auftrag'], 
    							'ID_User' => $row['ID_User'],
    							'FinishTime' => $row['FinishTime'], 
    							'ID_Kolonie' => $row['ID_Kolonie'],
    							'Kategory' => $row['Kategory']);
    	}
    	
    	//Lade alle Vorkommensuche
    	$this->db->query("
    		SELECT 
    			t_auftrag.ID_Auftrag AS ID_Auftrag, ID_User, FinishReal, ID_Kolonie, Kategory
    		FROM
    			t_auftrag, t_auftragvorkommensuche
    		WHERE 
    			ID_User = ".$this->user->getUserID()."
    		AND
    			Kategory = 'Vorkommensuche'
    		AND
    			t_auftrag.ID_Auftrag = t_auftragvorkommensuche.ID_Auftrag
    		ORDER BY
    			FinishReal ASC;");
    	while( $row = $this->db->fetch_array() )
    	{
    		$auftraege[] = array(
    							'ID' => $row['ID_Auftrag'], 
    							'ID_User' => $row['ID_User'],
    							'FinishTime' => $row['FinishReal'], 
    							'ID_Kolonie' => $row['ID_Kolonie'],
    							'Kategory' => $row['Kategory']);
    	}
    	
    	
    	
    	/*Sortiere Aufträge!*/    	
		for($i=0; $i<count($auftraege)-1; $i++)
		{
			for($a=$i+1; $a<count($auftraege); $a++)
			{
				if( $auftraege[$i]['FinishTime'] > $auftraege[$a]['FinishTime'] )
				{
					$save = $auftraege[$a];
					$auftraege[$a] = $auftraege[$i];
					$auftraege[$i] = $save;
				}
			}
		}    	
    	//rückgabewert
    	return $auftraege;
    }
    
    /*Diese Funktion gibt die Energieproduktion zurück*/
    function getEnergieproduktion_Final()
    {
    	return $this->energieproduktion;
    }
    
    /*Diese Funktion erstellt einen Debugreport, welche der User bekommt*/
    function debugReport($counter)
    {	
    	//Deklarationen
    	$empfaenger = "support@subterranwars.de";
    	
    	//Debugtemplate laden
    	$tpl_debug = new TEMPLATE("templates/stw_v02/debug.tpl");
    	$daten_debug = array(
    		'DATUM' => '',
    		'UHRZEIT' => '',
    		'ID_USER' => '',
    		'USER' => '',
    		'EREIGNISSE' => '',
    		'EREIGNIS_DETAIL' => '',
    		'START_ZEIT' => '',
    		'END_ZEIT' => '',
    		'EREIGNIS_FEHLER' => '');
    		
    	//Daten setzen
    	$daten_debug['DATUM'] 	= date("d.m.Y");
    	$daten_debug['UHRZEIT'] = date("H:i:s");
    	$daten_debug['ID_USER'] = $this->user->getUserID();
    	$daten_debug['USER'] 	= $this->user->getNickname();
    	
    	//Ereignissdaten setzen
    	for( $i=0; $i<count($this->ereignisse_save); $i++ )
    	{
    		$daten_debug['EREIGNISSE'] .= "<tr>";
    		$daten_debug['EREIGNISSE'] .= "<td><b>".($i+1)."</b></td>";
    		$daten_debug['EREIGNISSE'] .= "<td>#".$this->ereignisse_save[$i][0].", ".$this->ereignisse_save[$i][2]."</td>";
    		$daten_debug['EREIGNISSE'] .= "<td>".(date("d.m.Y H:i:s", $this->ereignisse_save[$i][1]))."	(".$this->ereignisse_save[$i][1].")</td>";
    		$daten_debug['EREIGNISSE'] .= "</tr>";
    	}    	
    	$daten_debug['EREIGNISSE'] = "<table><tr><th>Nr</th><th>Ereigniss</th><th>Datum</th></tr>".$daten_debug['EREIGNISSE']."</table>";
    	
    	//Bei welchem Ereigniss tritt Fehler auf?
    	$daten_debug['EREIGNIS_FEHLER'] = $counter+1;
    	
    	//Start-Zeit setzen
    	$daten_debug['START_ZEIT'] = date("d.m.Y H:i:s", $this->_start_zeit)." ( ".$this->_start_zeit." )";
    	$daten_debug['END_ZEIT'] = date("d.m.Y H:i:s", $this->end_zeit)." ( ".$this->end_zeit." )";
    	
    	
    	//Zeit zwischen Start und 1. Ereignis
    	$daten_debug['EREIGNIS_DETAIL'] .= "<tr>";
    	$daten_debug['EREIGNIS_DETAIL'] .= "<td><b>Start bis 1.</b></td>";
    	$daten_debug['EREIGNIS_DETAIL'] .= "<td>".($this->ereignisse_save[0][1]-$this->_start_zeit)."</td>";
    	$daten_debug['EREIGNIS_DETAIL'] .= "</tr>";
    	
    	//Ereignisdetails durchlaufen
    	for( $i=0; $i<count($this->ereignisse_save)-1; $i++ )
    	{
    		$daten_debug['EREIGNIS_DETAIL'] .= "<tr>";
    		$daten_debug['EREIGNIS_DETAIL'] .= "<td><b>".($i+1)." bis ".($i+2)."</b></td>";
    		$daten_debug['EREIGNIS_DETAIL'] .= "<td>".($this->ereignisse_save[$i+1][1]-$this->ereignisse_save[$i][1])."</td>";
    		$daten_debug['EREIGNIS_DETAIL'] .= "</tr>";
    	}    	
    	
    	//Zeit zwischen End und letzte Ereignis
    	$daten_debug['EREIGNIS_DETAIL'] .= "<tr>";
    	$daten_debug['EREIGNIS_DETAIL'] .= "<td><b>".($i+1)." bis Ende</b></td>";
    	$daten_debug['EREIGNIS_DETAIL'] .= "<td>".($this->end_zeit-$this->ereignisse_save[count($this->ereignisse_save)-1][1])."</td>";
    	$daten_debug['EREIGNIS_DETAIL'] .= "</tr>";
    	
    	//Tabelle vervollständigen
    	$daten_debug['EREIGNIS_DETAIL'] = "<table><tr><th>Nr</th><th>Differenz</th></tr>".$daten_debug['EREIGNIS_DETAIL']."</table>";
    	
    	//Templatedaten ersetzen
    	$tpl_debug->setObject("account", $daten_debug);
    	
   		
    	//Email-Header
		$header  = "MIME-Version: 1.0\n";
        $header .= "Content-type: text/html; charset=iso-8859-1\n";
        $header .= "From: $empfaenger <$empfaenger>\n";
        $header .= "Reply-to: $empfaenger\n";
				
		/* Verschicken der Mail */
		//@mail($empfaenger, "".$this->user->getNickname()."_".$this->user->getUserID()."", $tpl_debug->getTemplate(), $header);
		
		//Neue Nachricht verfassen
		/*$topic = "negative Bevölkerung";
		$message = "Hallo.<br>
					So eben wurde festgestellt, dass deine Bevölkerung ein 
					negatives Niveau erreicht hat.
					Dieser Fehler ist bereits bekannt, allderings ist noch keine
					Lösung des Problems gefunden worden.
					Aus diesem Grund wurde soeben eine Email vom System automatisch
					an einen Admin an support@subterranwars.de gesand.
					Wir hoffen durch die Versendung accountrelevanter Daten den Fehler endlich finden zu können.
					Das STW-Team bittet um Entschuldigung.
					Deine Bevölkerung wird schnellstmöglich wieder auf einen positiven Wert gesetzt.
					Solltest du noch Fragen haben, so wende dich einfach an mich (Marskuh).
					<br><br>
					Gruß <br>
					das STW - Team";
		
		//Nachricht dem User schicken
		$sql  = "INSERT INTO t_nachrichten (Betreff, Inhalt, Datum, Status, Deleted, ID_User, ID_UserAbsender)";
	    $sql .= " VALUES ('$topic', '$message', ".time().", 'neu', 'keiner', ".$this->user->getUserID().", 136);";
	    $this->db->query($sql);*/
    }
}?>