<?php
/*bauplan.php
Klasse bekommt Teile-Array übergeben, überprüft ob die Konstelation gültig ist
und speichert ggf. die TeileKonstelation als Bauplan ab....
Unter anderem Setzt diese Funktion eine Einheit aus einem Bauplan zusammen!


History:
			23.08.2004		MvR		created
*/

class BAUPLAN
{
	//Deklarationen
	var $ID;				//ID des Bauplans!
	var $db;				//Referenz auf Datenbank objekt
	var $ID_Kolonie;		//KolonieID, um maximalen Koloniestatus zu erlangen!
	var $user;				//Referenz auf UserObjekt
	var $Teile;				//Array auf teilen, welches die Daten der Komponenten speichert
	var $bezeichnung;		//Profilbezeichnung!
	var $kosten;			//Kosten an Rohstoffen pro Teil!
	var $max_zuladung;		//Maximale Zuladung
	var $max_leistung;		//Maximale Leistung
	var $zuladung;			//Zuladungsverbrauch
	var $leistung;			//Leistungsverbrauch
	var $angriff;			//Angriffswert
	var $panzerung;			//Verteidigungswert
	var $lebenspunkte;		//Lebenspunkte
	var $wendigkeit;		//Wendigkeit
	var $geschwindigkeit;	//Geschwindigkeit
	var $geschwindigkeit_faktor;	//Chassis beinflusst Antriebsgeschwindigkeit mit einem Faktor (90% => 0.9; 30% => 0.3)
	var $zielen;			//Zielgenauigkeit
	var $waffentyp;			//WelcheWaffe hat die Einheit? Projektilwaffe, Energiewaffe etc
	var $waffen_bonus_typ;	//Gegen welches Chassis hat die Waffe einen Bonus Infanterist, Fahrzeug, Mech
	var $waffen_bonus;		//Die höhe des Bonus
	var $chassistyp;		//Handelt es sich um Fahrzeug, MEch oder Infanterist
	var $chassis_bonus_typ;	//Gegen welches Chassis hat die Einheit einen Vorteil (Infanterist, Fahrzeug, MEch)
	var $chassis_bonus;		//Höhe des Bonus
	var $panzertyp;			//Was für einen Panzer hat die Einheit (Ballastistische Panzerung, ProjektilPanzerung, Energieschild)
	var $panzerbonus;		//Wie hoch ist der Bonus?
	var $buildtime;			//GrundBauzeit des Profils in Sekunden
	
	//Standardkonstruktor
	function BAUPLAN(&$db, &$user, $ID_Kolonie)
	{
		$this->db 			= &$db;
		$this->user 		= &$user;
		$this->ID_Kolonie 	= $ID_Kolonie;
	}
	
	/*gibt bauplanid zurück*/
	function getID()
	{
		return $this->ID;
	}
	
	/*Funktion bekommt eine Profil Id übergeben und lädt an Hand dieses Profils die Teile der Einheit!*/
	function loadBauplan($ID)
	{
		//Query
		$this->db->query("
					SELECT 
						ID_Profil, 
						Bezeichnung,
						ChassisTyp,
						MaxLeistung, 
						MaxZuladung, 
						Leistung, 
						Zuladung, 
						Wendigkeit, 
						Geschwindigkeit,
						Lebenspunkte,
						Angriff, 
						Panzerung, 
						Zielen, 
						Eisen, 
						Stahl, 
						Titan, 
						Kunststoff, 
						Wasserstoff, 
						Uran, 
						Plutonium,
						Gold, 
						Diamant, 
						Bevölkerung,
						Bauzeit,
						Waffentyp, 
						WaffenbonusTyp, 
						Waffenbonus, 
						BonustypChassis, 
						ChassisBonus,
						Panzertyp, 
						Panzerbonus 
					FROM 
						t_profil
					WHERE
						ID_Profil = $ID");
		$ergebnis = $this->db->fetch_array();
		
		//Setze Daten
		$this->ID = $ID;
		$this->bezeichnung 			= $ergebnis['Bezeichnung'];
		$this->chassistyp			= $ergebnis['ChassisTyp'];
		$this->max_leistung			= $ergebnis['MaxLeistung'];
		$this->max_zuladung			= $ergebnis['MaxZuladung'];
		$this->leistung				= $ergebnis['Leistung'];
		$this->zuladung				= $ergebnis['Zuladung'];
		$this->wendigkeit			= $ergebnis['Wendigkeit'];
		$this->geschwindigkeit		= $ergebnis['Geschwindigkeit'];
		$this->lebenspunkte			= $ergebnis['Lebenspunkte'];
		$this->angriff				= $ergebnis['Angriff'];
		$this->panzerung			= $ergebnis['Panzerung'];
		$this->zielen				= $ergebnis['Zielen'];
		$this->kosten[0]			= $ergebnis['Eisen'];
		$this->kosten[1]			= $ergebnis['Stahl'];
		$this->kosten[2]			= $ergebnis['Titan'];
		$this->kosten[3]			= $ergebnis['Kunststoff'];
		$this->kosten[4]			= $ergebnis['Wasserstoff'];
		$this->kosten[5]			= $ergebnis['Uran'];
		$this->kosten[6]			= $ergebnis['Plutonium'];
		$this->kosten[7]			= $ergebnis['Diamant'];
		$this->kosten[8]			= $ergebnis['Gold'];
		$this->kosten[9]			= $ergebnis['Bevölkerung'];
		$this->waffentyp			= $ergebnis['Waffentyp'];
		$this->waffen_bonus_typ		= $ergebnis['WaffenbonusTyp'];
		$this->waffen_bonus			= $ergebnis['Waffenbonus'];
		$this->chassis_bonus_typ	= $ergebnis['BonustypChassis'];
		$this->chassis_bonus		= $ergebnis['ChassisBonus'];
		$this->panzertyp			= $ergebnis['Panzertyp'];
		$this->panzerbonus			= $ergebnis['Panzerbonus'];
		$this->buildtime			= $ergebnis['Bauzeit'];
	}
	
	/*Funktion dient dazu einen neuen Bauplan zu erstellen
	Dazu muss als erstes die Konstelation überprüft werden und anschliessen
	der Bauplan abgespeichert werden!
		$teile = Array auf Teilen
			$teile[0] = chasis
			$teile[1] = antrieb (ausser Infanteristen)
			$teile[2] = Waffe
			$teile[3] = Munition
			$teile[4] = Panzerung
		$typ = Infanterist, Fahrzeug, Mech*/
	function saveEinheit($teile_array, $typ, $profilname)
	{		
		//Deklarationen
		$error = 1;				//Fehler variable
		$teile = new TEILE();	//Teile-Objekt
			
		//Lade die maximal und minimal erlaubte TeileID
		switch( $typ )
		{
			case 1:			//Infanterist
				$min_teile_id = 1;
				$max_teile_id = $teile->getInfanterieAnzahl();
				break;
			case 2:			//Fahrzeug
				$min_teile_id = $teile->getInfanterieAnzahl() + 1;
				$max_teile_id = $teile->getFahrzeugAnzahl();
				break;
			case 3:			//Mech
				$min_teile_id = $teile->getFahrzeugAnzahl() + 1;
				$max_teile_id = $teile->getMechAnzahl();
				break;
		}
				
		/*Überprüfen ob Teile zur richtigen Kategorie gehören
		nur Infanteristen-, Fahrzeug-, oder Mechteile*/
		for( $i=0; $i<count($teile_array); $i++ )
		{
			//Lade Teiledaten
			$teile->loadTeile($teile_array[$i], $this->user->getRasseID());
			
			/*Überprüfen, dass Werte auch gesetzt sein müssen!*/
			if( empty($teile_array[$i]) )	//Wert nicht gesetzt
			{
				//Ist Wert zufällig = Antrieb und Typ = Infanterist? dann ist es richtig
				if( $typ != 1 AND $i == 1 )
				{
					//Wert fehlt!
					$error = -4;
					break;
				}		
			}
					
			//Ist ID im richtigen bereich
			if( $teile->getID() > $max_teile_id OR ( $teile->getID() < $min_teile_id AND $teile->getID() > 0) )
			{
				/*Teile passen nicht zur gewählten Kategory
				Infanterist, Fahrzeug, Mech*/
				$error = -1;
				break;
			}
		}
				
		//Passen Teile zusammen?
		if( $error == 1 )
		{
			//Waffenobjekt laden
			$teile->loadTeile($teile_array[2], $this->user->getRasseID());
			$waffen_munition = $teile->getMunitionstyp();
			
			//Munitionsobjekt laden
			$teile->loadTeile($teile_array[3], $this->user->getRasseID());
			$munition		= $teile->getTyp();
			
			/*Ist die richtige Munition gewählt*/
			if( $waffen_munition == $munition )	//Richtige Munition
			{
				//Setze Einheitendetails
				$error = $this->setzeEinheitZusammen($teile_array, $typ);
				
				//Überprüfen ob Konstelation so stimmt!
				if( $error == 1 )
				{
					/*Ist Profilname angegeben*/
					if( !empty($profilname) )
					{						
						//Profil speichern
						$sql = "INSERT INTO t_profil
							(
								Bezeichnung,  
								ChassisTyp,
							   	MaxLeistung,
								MaxZuladung,
								Leistung,
							   	Zuladung,
							   	Wendigkeit,
							  	Geschwindigkeit,
							   	Lebenspunkte,
							   	Angriff,
							 	Panzerung,
							   	Zielen,
							   	Eisen,
							   	Stahl, 
							   	Titan,
							   	Kunststoff,
							   	Wasserstoff,  
							   	Uran,
							   	Plutonium,
							   	Gold,
								Diamant,
							   	Bevölkerung,
								Bauzeit,
							   	Waffentyp,
								WaffenbonusTyp,
							   	Waffenbonus,
							   	BonustypChassis,
							   	ChassisBonus,
							   	Panzertyp,
							   	Panzerbonus,
								ID_User
							)
							VALUES
							(
								'$profilname',
								".$this->chassistyp.",
								".$this->max_leistung.",
								".$this->max_zuladung.",
								".$this->leistung.",
								".$this->zuladung.",
								".$this->wendigkeit.",
								".$this->geschwindigkeit.",
								".$this->lebenspunkte.",
								".$this->angriff.",
								".$this->panzerung.",
								".$this->zielen.",
								".$this->kosten[0].",
								".$this->kosten[1].",
								".$this->kosten[2].",
								".$this->kosten[3].",
								".$this->kosten[4].",
								".$this->kosten[5].",
								".$this->kosten[6].",
								".$this->kosten[7].",
								".$this->kosten[8].",
								".$this->kosten[9].",
								".$this->buildtime.",
								".$this->waffentyp.",
								".$this->waffen_bonus_typ.",
								".$this->waffen_bonus.",
								".$this->chassis_bonus_typ.",
								".$this->chassis_bonus.",
								".$this->panzertyp.",
								".$this->panzerbonus.",
								".$this->user->getUserID()."	
							)";

						//Profil in Datenbank speichern
						$this->db->query($sql);
						$this->ID = $this->db->last_insert();
						
						//Alle TEile ebenso einfügen
						for( $i=0; $i<count($teile_array); $i++ )
						{
							if( !empty($teile_array[$i]) )
							{
								$this->db->query("INSERT INTO t_profilhatteile (ID_Profil, ID_Teile) VALUES (".$this->ID.", ".$teile_array[$i].")");
							}
						}
					}
					else 	//Kein Profilname angegeben
					{
						$error = -5;
					}
				}
				else //Maximale Zuladung oder Leistungw urde überschritten oder Requirement stimmen nicht zusammen
				{
					//Maximale Zuladung oder Leistung flasch?
					if( $error == -1 )	//maximale Zuladung bzw. Leistung überschritten
					{
						$error = -3;
					}
					else 				//Requirement wird nicht unterstützt!
					{
						$error = -6;
					}
				}
			}
			else 	//falsche Munitionstyp gewählt!
			{
				$error = -2;
			}
		}
		
		/*Rückgabe wert  
		bei erfolg = 1
		bei fehler < 0*/
		return $error;	
	}
	
	/*Setzt Einheitenwerte zusammen!
	$typ gibt an ob die Teile zur Infanterie, Fahrzeug oder Mech gehören*/
	function setzeEinheitZusammen($teile_array, $typ)
	{
		//Deklarationen
		$error = 1;				//Fehlervariable
		$teile = new TEILE();	//TeilObjekt
		
		/*Requirements überprüfen*/
		for( $i=0; $i<count($teile_array); $i++ )
		{
			//Teildaten laden!
			$teile->loadTeile($teile_array[$i], $this->user->getRasseID());
			
			//Wird Requirement erfüllt?
			if( $teile->checkRequirement($this->user, $this->ID_Kolonie) != 1 )
			{
				//Fehler ist aufgetreten
				$error = -1;
				break;
			}
		}
		
		//Wenn Requirements nicht utnerstützt werden fehler ausgeben
		if( $error != -1 )
		{
			//ChasisDaten laden!
			$teile->loadTeile($teile_array[0], $this->user->getRasseID());
					
			//Teile passen zusammen. Nun Maximale Zuladung und Maximale LEistung laden
			$this->max_zuladung = $teile->getZuladung();
			$this->max_leistung = $teile->getLeistung();
			
			//GeschwindigkeitsMallus bestimmen
			if( $typ == 1 )	//Infanterist
			{
				/*Infanteristen haben keinen Antrieb, folglich ist die 
				Geschwindigkeit vom Chassis abhängig, ergo kein Mallus (100%)*/
				$this->geschwindigkeit_faktor = 1;	//100%
				$this->geschwindigkeit = 1;
			}
			else 
			{
				//Cahssis hat Einfluss auf Endgeschwindigkeit
				$this->geschwindigkeit_faktor = $teile->getGeschwindigkeit();
			}
					
			/*Alle Werte laden!*/
			for( $i=0; $i<count($teile_array); $i++ )
			{
				//TeilDaten setzen
				$teile->loadTeile($teile_array[$i], $this->user->getRasseID());
							
				//Alle Werte addieren
				$this->lebenspunkte += $teile->getLebenspunkte();
				$this->zielen		+= $teile->getZielen();
				$this->wendigkeit 	+= $teile->getWendigkeit();
				$this->leistung		+= $teile->getLeistung();
				$this->zuladung		+= $teile->getZuladung();
				$this->angriff		+= $teile->getAngriff();
				$this->panzerung	+= $teile->getPanzerung();
				$this->geschwindigkeit += $teile->getGeschwindigkeit();
				$this->buildtime	+= $teile->getBuildTime();
							
				//Kosten selektieren
				$kosten = $teile->getKosten();
				//$this->Kosten[$ID] = array('Eisen', 'Stahl', 'Titan', 'Kunststoff', 'Wasserstoff', 'Uran', 'Plutonium', 'Gold', 'Diamant', 'Bevölkerung');
				$this->kosten[0] += $kosten[0];		//Eisen
				$this->kosten[1] += $kosten[1];		//Stahl
				$this->kosten[2] += $kosten[2];		//Titan
				$this->kosten[3] += $kosten[3];		//Kunststoff
				$this->kosten[4] += $kosten[4];		//Wasserstoff
				$this->kosten[5] += $kosten[5];		//Uran
				$this->kosten[6] += $kosten[6];		//Plutonium
				$this->kosten[7] += $kosten[7];		//Gold
				$this->kosten[8] += $kosten[8];		//Diamant
				$this->kosten[9] += $kosten[9];		//Bevölkerung
			}
			
			//Geschwindigkeit, zuladung und leistung vom Chassis wieder abziehen
			$this->geschwindigkeit 	-= $this->geschwindigkeit_faktor;
			$this->leistung 		-= $this->max_leistung;
			$this->zuladung			-= $this->max_zuladung;
							
			//Geschwindigkeit mit Faktor multiplizieren
			$this->geschwindigkeit = $this->geschwindigkeit * $this->geschwindigkeit_faktor;
			
			
			//Chassisdaten laden
			$teile->loadTeile($teile_array[0], $this->user->getRasseID());
			$this->chassistyp 			= $teile->getTyp();
			$this->chassis_bonus_typ 	= $teile->getBonustyp();
			$this->chassis_bonus		= $teile->getBonus();
		
			//Waffentyp laden!
			$teile->loadTeile($teile_array[2], $this->user->getRasseID());
			$this->waffentyp 		= $teile->getTyp();
			$this->waffen_bonus_typ	= $teile->getBonustyp();
			$this->waffen_bonus 	= $teile->getBonus();
			
			//Panzervorzugsdaten laden!
			$teile->loadTeile($teile_array[4], $this->user->getRasseID());
			$this->panzertyp 		= $teile->getTyp();
			$this->panzerbonus		= $teile->getBonus();
					
			/*Überprüfen ob Leistungsverbrauch und Energieverbrauch überhaupt verfügbar sind*/
			if( ($this->max_leistung < $this->leistung) OR ($this->max_zuladung < $this->zuladung) )
			{
				//Es wird entweder zu viel Energie oder Gewicht verbraucht
				$error = -2;
			}
		}
		
		/*Rückgabe 
		bei Erfolg = 1
		Misserfolg < 0*/
		return $error;
	}
	
	/*Funktion gibt Bezeichnung zurück*/
	function getBezeichnung()
	{
		return $this->bezeichnung;
	}
	
	/*gibt angriffswert zurück*/
	function getAngriff()
	{
		return $this->angriff;
	}
	
	/*gibt klasse zurück
	Infanterie, Fahrzeug, Mech*/
	function getChassis()
	{
		return $this->chassistyp;
	}
	
	/*gibt ChassisBonusTyp zurück*/
	function getChassisBonusTyp()
	{
		return $this->chassis_bonus_typ;
	}
	
	/*Gibt den ChassisBonus zurück*/
	function getChassisBonus()
	{
		return $this->chassis_bonus;
	}
	
	/*gibt panzerungswert zurück*/
	function getPanzerung()
	{
		return $this->panzerung;
	}
	
	/*Panzerbonus!*/
	function getPanzerBonus()
	{
		return $this->panzerbonus;
	}
	
	/*gibt Panzertyp zurück*/
	function getPanzerTyp()
	{
		return $this->panzertyp;
	}
		
	/*gibt wendigkeit zurück*/
	function getWendigkeit()
	{
		return $this->wendigkeit;
	}
	
	/*gibt geschwindigkeit zurück*/
	function getGeschwindigkeit()
	{
		return $this->geschwindigkeit;
	}
	
	/*gibt zielen wert zurück*/
	function getZielen()
	{
		return $this->zielen;
	}
	
	/*gibt kosten zurück!*/
	function getKosten()
	{
		return $this->kosten;
	}
	
	/*gibt Waffentyp zurück*/
	function getWaffenTyp()
	{
		return $this->waffentyp;
	}
	
	/*Gibt waffenbonustyp zurück!*/
	function getWaffenBonusTyp()
	{
		return $this->waffen_bonus_typ;
	}
	
	/*größe des Waffenbonus*/
	function getWaffenBonus()
	{
		return $this->waffen_bonus;
	}
	
	/*gibt Lebenspunkte der Einheit zurück*/
	function getLebenspunkte()
	{
		return $this->lebenspunkte;
	}
	
	/*gibt die KomponentenTeile zurück*/
	function getTeileArray()
	{
		//Alle Teile laden
		$this->db->query("SELECT ID_Teile FROM t_profilhatteile WHERE ID_Profil = $this->ID ORDER BY ID_Teile ASC");
		while( $row = $this->db->fetch_array() )
		{
			$this->Teile[] = $row['ID_Teile'];
		}
		
		//Rückgabe
		return $this->Teile;
	}
	
	/*gibt die Bauzeit zurück*/
	function getBuildTime($geb_level)
	{
		/*Die Bauzeit wird pro Level des Kraftwerkes oder Waffenfabrik (je nach Verwendung)
		um 5% gesenkt
		
		Beispiel1:
				Grundbauzeit: 	100 Sekunden
				Gebäudelevel: 	1
				Vorteil:		0%
				Endbauzeit:		100 Sekunden
				
		Beispiel2:
				Grundbauzeit:	100 Sekunden
				Gebäudelevel:	2
				Vorteil:		5%
				Endbauzeit:		95 Sekunden
				
		Formel:
			Endbauzeit = Bauzeit - ($level-1)*0.05*Bauzeit
			Endbauzeit = 100 Sekunden - (2-1)*0.05*100
			Endbauzeit = 100 - 0.05*100
			Endbauzeit = 100 - 5
			Endbauzeit = 95 Sekunden
		*/
		//Optiomierungsfaktor
		$optimierung = 0.05;	//5%
				
		//Rückgabe der Bauzeit:
		return $this->buildtime - ($geb_level-1)*$this->buildtime*$optimierung;
	}
	
	/*ermittelt formatierte bauzeit*/
    function getFormattedBuildTime($geb_level)
    {
    	$sekunden = $this->getBuildTime($geb_level);
    	unset($tage);
		unset($stunden);
		unset($minuten);
		//Zeiten berechnen		
		if( $sekunden > 59 )
		{
			$minuten 	= floor($sekunden / 60);
			$sekunden 	= $sekunden - $minuten *60;
		}
		if( $minuten > 59 )
		{
			$stunden = floor($minuten / 60);
			$minuten = $minuten - $stunden*60;
		}
		if( $stunden > 23 )
		{
			$tage = floor($stunden / 24);
			$stunden = $stunden - $tage * 24;
		}
		//Überprüfen ob 1 oder mehrere Tage
		if( $tage == 1 )
		{
			$tage = $tage." Tag";
		}
		elseif ($tage > 1 )
		{
			$tage = $tage." Tage";
		}
		//Bauzeit als formatierter STring:
		$bauzeit = sprintf("%s %02d:%02d:%02d", $tage, $stunden, $minuten, $sekunden);
		return $bauzeit;
    }
}?>