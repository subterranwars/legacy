<?php
/*mission.php

History:
			09.09.2004		Markus von Rüden	created
			25.01.2005		Markus von Rüden	getMission($time) - Funktion überarbeitet
*/

class MISSION
{
    //Deklarationen
    var $db;
    var $user;
    var $debug = false;
    var $debug_counter = 1;
    
    /*Standardkonstruktor*/
    function MISSION(&$db, &$user)
    {
		//Debugmeldung
		$this->debug("Erzeuge Bauobjekt!");
				
		//Datenbankverbindung setzen
    	$this->db 	= &$db;
    	//Userobjekt setzen
    	$this->user = &$user;
    }
    
    /*Diese Funktion selektiert alle Missionen, welche zur Zeit im Gange sind und
    gibt ein Array zurück!*/
    function getMission($time)
    {
    	//Selektiere alle laufenden Missionen
    	$this->db->query("SELECT ID_Mission, Hinflug, Rückflug, ID_User, Parameter FROM t_mission WHERE ID_User =".$this->user->getUserID()." OR ID_UserOpfer = ".$this->user->getUserID()."");
    	while( $row = $this->db->fetch_array() )
    	{
    		//Überprüfen ob Hinflug schon vorbei ist
    		if( $row['Hinflug'] > $time )
    		{
    			$array[0][] = $row['Hinflug'];
    			$array[1][] = $row['ID_Mission'];
    		}
    		
    		//Wenn ID_User = session_user, dann rückflug anzeigen und es darf sich um keine Übergabe bzw. kein verlegen handeln
    		if( ($row['ID_User'] == $this->user->getUserID()) AND ($row['Parameter'] != 'Uebergabe' AND $row['Parameter'] != 'Verlegen') )
    		{
    			//Rückflug Daten selektieren!
    			$array[0][] = $row['Rückflug'];
    			$array[1][] = $row['ID_Mission'];
    		}
    	}
    	
    	//Array nach Zeit sortieren
    	for( $i=0; $i<count($array[0]); $i++ )
    	{
    		for( $a=$i+1; $a<count($array[0])-1; $a++ )
    		{
    			if( $array[0][$a] < ($array[0][$i]) )	//tauschen
    			{
    				//Daten zwischenspeichern
    				$spacer 	= $array[0][$a];
    				$spacer2 	= $array[1][$a];
    				//Daten setzen!
    				$array[0][$a] = $array[0][$i];
    				$array[1][$a] = $array[1][$i];
    				//Daten setzen
    				$array[0][$i] = $spacer;
    				$array[1][$i] = $spacer2;
    			}
    		}
    		//Nun Missionen laden
    		$this->db->query("
    			SELECT
    				ID_Mission, Parameter, Hinflug, Rückflug, ID_KoordinatenSource, 
    				ID_KoordinatenDestination, t_flotte.Bezeichnung As Flotte, ID_UserOpfer, t_mission.ID_User
    			FROM 
    				t_mission, t_flotte 
    			WHERE 
    				t_flotte.ID_Flotte = t_mission.ID_Flotte AND
    				ID_Mission = ".$array[1][$i]."");
    		$row = $this->db->fetch_array();
    		
    		//Wird hingeflogen oder zurückgekehrt
    		if( $row['Hinflug'] != $array[0][$i] )	//wenn $row['Hinflug'] != $array[0][$i], so wird zurückgekehrt!
    		{
    			//Rückkehr-Parameter setzen!
    			$row['Parameter'] = "Rückkehr";
    			//Da es sich um die Rückkehr handelt ID_KoordinatenSource und ID_KoordinatenDestination tauschen
    			$save							 	= $row['ID_KoordinatenSource'];
    			$row['ID_KoordinatenSource'] 		= $row['ID_KoordinatenDestination'];
    			$row['ID_KoordinatenDestination'] 	= $save;
    		}
			
    		//Gehört User die Mission, dann Flottenbezeichnung anzeigen!
			if( $row['ID_User'] == $this->user->getUserID() )
			{
				//Flottenbezeichnung setzen
				$mission['Flotte'][$i] 		= $row['Flotte']; 
				$mission['Color'][$i]		= "green";
			}
			else	//Diese Mission wurde von einem feindlichen oder freundlichem Spieler gestartet
			{
				//Überprüfen ob es Kampf oder Normal is!
				if( $row['Parameter'] == 'Angriff' )
				{
					$mission['Color'][$i] = 'red';
				}
				else 
				{
					$mission['Color'][$i] = 'yellow';
				}
			}
				
    		//Daten speichern
    		$mission['ID'][$i] 			= $row['ID_Mission'];
    		$mission['Parameter'][$i] 	= $row['Parameter'];
    		$mission['Time'][$i] 		= $array[0][$i];  
    		$mission['ID_UserOpfer'][$i]= $row['ID_UserOpfer'];
    		    		
    		//Lade KolonieID der Source
    		$this->db->query("SELECT ID_Kolonie FROM t_kolonie WHERE ID_Koordinaten =  ".$row['ID_KoordinatenSource']."");
    		$mission['ID_KoloSource'][$i] = $this->db->fetch_result(0);
    		//Lade KolonieID der Destination
    		$this->db->query("SELECT ID_Kolonie FROM t_kolonie WHERE ID_Koordinaten =  ".$row['ID_KoordinatenDestination']."");
    		$mission['ID_KoloDest'][$i]	= $this->db->fetch_result(0);
    	}
    	
    	//RÜCKGABE
    	return $mission;
    }
    
    /*Funktion startet mission
    $m_parameter[0] = Rohstoffe
    $m_parameter[1] = IdleTime
    $m_parameter[2] = Artillerie*/
    function startMission($koords_source, $koords_destination, $ID_Truppe, $missionstyp, $geschwindigkeit, $m_parameter)
    {
    	//Deklaration
    	$error = 1;	//Fehlervariabel 1= kein Fehler <0 => Fehler
    	
    	//Überprüfen, dass Missionstyp gesetzt wurde!
    	if( !empty($missionstyp) )
    	{
	    	//Truppe überprüfen, ob Truppe frei is
	    	if( $this->checkTruppe($ID_Truppe) == 1 )
	    	{
	    		//Koordinaten überprüfen
	    		if( $this->checkKoordinaten($koords_source, $koords_destination) == 1 )
	    		{
	    			//Werte ermitteln für Missionsparameter
	    			$ID_Koordinaten_Source		= $this->getKoordinatenID($koords_source);
	    			$ID_Koordinaten_Destination = $this->getKoordinatenID($koords_destination);
	    			
	    			//Dauer
	    			$dauer 		= $this->getTime($koords_source, $koords_destination, $ID_Truppe, $geschwindigkeit);
	    			$hinflug 	= time() + $dauer*3600;
	    			$rueckflug 	= time() + $dauer*3600*2;
	    			
	    			//Lade ID_User der Zielkoordinaten
	    			$ID_UserOpfer = $this->getUserID($ID_Koordinaten_Destination);
	    			
	    			//Missionstyp bestimmen
	    			switch( $missionstyp )
	    			{	
	    				//Angriff und undefined
	    				default:
	    				case 1:
	    					$parameter = 'Angriff';
	    					break;
	    				/*case 2:
	    					$parameter = '';
	    					break;*/
	    				case 3:
	    					$parameter = 'Verlegen';
	    					break;
	    				case 4:
	    					$parameter = 'Uebergabe';
	    					break;
	    				/*case 5:
	    					$parameter = 'Kolonisieren';
	    					break;*/
	    				case 6:
	    					$parameter = 'Transport';
	    					break;
	    			}
	    			//Daten eintragen
	    			$this->db->query("
	    				INSERT INTO t_mission
	    					(Parameter, Hinflug, Rückflug, Ressources, IdleTime, ID_KoordinatenDestination, ID_KoordinatenSource, ID_Flotte, ID_User, ID_UserOpfer, Artillerie, Ausgefuehrt)
	    				VALUES
	    					('$parameter', $hinflug, $rueckflug, '".$m_parameter[0]."', ".$m_parameter[1].", $ID_Koordinaten_Destination, $ID_Koordinaten_Source, $ID_Truppe, ".$this->user->getUserID().", $ID_UserOpfer, ".$m_parameter[2].", 0)");
	    		}
	    		else 	//Fehlr Koordinaten sind identisch!
	    		{
	    			$error = -2;
	    		}
	    	}
	    	else 	//Flotte wird bereits bentutz
	    	{
	    		$error = -1;
	    	}
    	}
    	else	//Missionstyp wurde nicht angegeben!
    	{
    		$error = -3;
    	}
    	
    	//Rückgabewert
    	return $error;
    }
    
    /*function liefert mir die ID der Koordinaten zurück*/
    function getKoordinatenID($koords)
    {
    	$this->db->query("SELECT ID_Koordinaten FROM t_koordinaten WHERE X = '".$koords[0]."' AND Y = '".$koords[1]."' AND Z = '".$koords[2]."'");
    	return $this->db->fetch_result(0);
    }
    
    /*überprüft ob koordinaten bewohnt sind oder nicht
    sind die koordinaten bewohnt, wird 1 zurückgegeben
    sind die koordinaten unbewohnt, wird -1 zurückggeben
    */
    function isBewohnt($ID)
    {
    	$this->db->query("SELECT COUNT(*) FROM t_kolonie WHERE ID_Koordinaten = $ID");
    	$anzahl = $this->db->fetch_result(0);
    	
    	//Ist Anzahl > 0 (also koordinaten belegt)?
    	if($anzahl > 0 )	//ja
    	{
    		$error = 1;
    	}
    	else 				//nein
    	{
    		$error = -1;
    	}
    	
    	//Rückgabe
    	return $error;
    }
    
    /*funktion überprüft Ziel-Koordinaten und Quellkoordinaten ob diese nicht identisch sind
    liefert 1 bei keinem fehler und 
    -1 wenn fehler aufgetreten sind
    -2 wenn destination koordinaten nicht existieren*/
    function checkKoordinaten($koords_source, $koords_destination)
    {
    	//Sind koordinaten gleich?
    	if( $koords_source[0] == $koords_destination[0]	AND 
    		$koords_source[1] == $koords_destination[1] AND
    		$koords_source[2] == $koords_destination[2] )
    	{														//Ja
    		$error = -1;
    	}
    	else 													//Nein
    	{
    		//Nun wird überprüft ob Koordinaten überhaupt vorhanden sind!
    		$this->db->query("SELECT ID_Koordinaten FROM t_koordinaten WHERE X = '".$koords_destination[0]."' AND Y = '".$koords_destination[1]."' AND Z = '".$koords_destination[2]."'");
    		if( $this->db->num_rows() > 0 )
    		{
    			$error = 1;
    		}
    		else 
    		{
    			$error = -2;
    		}
    	}
    	return $error;
    }
    			
    /*Funktin überprüft ob Truppe nicht bereits in einer Mission verwickelt ist!*/
    function checkTruppe($ID_Truppe)
    {
    	//Abfrage
    	$this->db->query("SELECT ID_Mission FROM t_mission WHERE ID_Flotte = $ID_Truppe");
    	$ergebnis = $this->db->fetch_result(0);
    	
    	//ID_Mission
    	if( $ergebnis > 0 )	//Fehler
    	{
    		$error = -1;
    	}
    	else 				//kein Fehler
    	{
    		$error = 1;
    	}
    	
    	//Rückgabewert
    	return $error;
    }
    	
    /*Entfernungsberechnung
    $koords_source[0] = x der Quelle
    $koords_source[1] = y der Quelle
    $koords_source[2] = z der Quelle*/
    function getEntfernung($koords_source, $koords_destination)
    {
    	//Deklarationen
    	$z_entfernung = 30;
    	$y_entfernung = 50;
    	$x_entfernung = 100;
    	
    	//Entfernungsberechnung
    	$entfernung = 	sqrt(
    						(pow(($koords_source[0] - $koords_destination[0])*$x_entfernung,2)) + 
	    					(pow(($koords_source[1] - $koords_destination[1])*$y_entfernung,2)) + 
	    					(pow(($koords_source[2] - $koords_destination[2])*$z_entfernung,2)));
    	return round($entfernung);
    }
    
    /*Ermittle minimale-Geschwindigkeit der Einheiten, denn diese ist die maxGeschwindigkeit
    der Truppe*/
    function getMaxGeschwindigkeit($ID_Truppe)
    {
    	//Als 1. Alle Truppentransporter laden!
    	$this->db->query("SELECT SUM(MaxZuladung) FROM t_profil, t_einheit WHERE t_einheit.ID_Flotte = $ID_Truppe AND t_einheit.ID_Bauplan = t_profil.ID_Profil AND t_profil.ChassisTyp = 5");
    	$platz_fuer_infanteristen = $this->db->fetch_result(0);
    	
    	//Anzahl aller Infanteristen bestimmen!
    	$this->db->query("SELECT COUNT(*) FROM t_einheit, t_profil WHERE t_einheit.ID_Flotte = $ID_Truppe AND t_einheit.ID_Bauplan = t_profil.ID_Profil AND t_profil.ChassisTyp = 1 GROUP BY t_profil.ChassisTyp");
    	$anzahl_infanteristen = $this->db->fetch_result(0);
    	
    	/*Wenn alle Infanteristen in den Truppentransporter passen, dann ist die
    	minimale Geschwindigkeit nicht die der Infanteristen!*/
    	if( $anzahl_infanteristen <= $platz_fuer_infanteristen )
    	{
    		$query = "SELECT MIN(Geschwindigkeit) FROM t_profil, t_einheit WHERE t_einheit.ID_Flotte = $ID_Truppe AND t_einheit.ID_Bauplan = t_profil.ID_Profil AND t_profil.ChassisTyp != 1";
    	}
    	else 
    	{
    		$query = "SELECT MIN(Geschwindigkeit) FROM t_profil, t_einheit WHERE t_einheit.ID_Flotte = $ID_Truppe AND t_einheit.ID_Bauplan = t_profil.ID_Profil";
    	}
    	
    	//Lade minimale Geschwindigkeit
    	$this->db->query($query);
    	$min_geschwindigkeit = $this->db->fetch_result(0);
    	
    	//Rückgabewert
    	return $min_geschwindigkeit;
    }
    
    /*ermittle Zeit, die gebraucht wird um Ziel zu erreichen*/
    function getTime($koords_source, $koords_destination, $ID_Truppe, $geschwindigkeit_prozent)
    {
    	//Min-Geschwindigkeit
    	$geschwindigkeit = $this->getMaxGeschwindigkeit($ID_Truppe) * $geschwindigkeit_prozent;
    	$entfernung = $this->getEntfernung($koords_source, $koords_destination);
    	
    	//Wie lange brauch User für diese Entfernung
    	$time = $entfernung / $geschwindigkeit;		//Angabe in h
    	return $time;
    }
    
    /*funktion bekommt eine KoordinatenID übergeben und selektiert dessen benutzer*/
    function getUserID($ID_Koordinaten)
    {
    	$this->db->query("SELECT ID_User FROM t_kolonie WHERE ID_Koordinaten = $ID_Koordinaten");
    	return $this->db->fetch_result(0);
    }
    
    /*Funktion gibt die Maximale Zuladung eines RohstoffTransporters (LKW) zurück*/
    function getMaxZuladungRohstofftransport($ID_Truppe)
    {
    	//Lade Maximale Zuladung für einen RohstoffTransport
    	$this->db->query("SELECT SUM(MaxZuladung) FROM t_profil, t_einheit WHERE t_profil.ID_Profil = t_einheit.ID_Bauplan AND t_einheit.ID_Flotte = $ID_Truppe AND t_profil.ChassisTyp = 4");	
    	return $this->db->fetch_result(0);
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