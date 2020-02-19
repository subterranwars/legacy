<?PHP
/*
History:
			14.11.2004		MvR		update der Gebäudebauzeiten.
			19.11.2004		FF		Gebäudebeschreibungen ersetzt.
*/

class GEBÄUDE
{
    var $db;
	var $ID;
	var $Bezeichnung;
	var $Beschreibung;
	var $BuildTime;
	var $Gebäude;				//Array welches die Gebäudedetails enthält
								//$Gebäude[$ID] = array('Bezeichnung für Terraner', 'Bezeichnung für SubTerraner', 'Beschreibung', 'Bauzeit', 'ID_Rasse', 'url', 'Energieverbrauch pro Stunde in GigaWatt');
	var $Kosten;				//Array welches die Kosten von einem Rohstoff speichert!
								//Kosten[$ID] = array('eisen', 'stein', 'stahl', 'titan');
	var $Energieverbrauch;		//Speichert den Grundenergieverbrauch des Gebäude pro Stunde in GigaWatt (GW)
	var $debug = false;			//Befindet sich das Ganze im Debugmodus?
	var $debug_counter = 1;		//Counter zur verschönerten Anischt!
	
	function GEBÄUDE()
    {        
    	//Debugmeldungen
    	$this->debug('Gebäude-Objekt erzeugt!');
        	
    	//GEbäudedaten setzen
    	//$Gebäude[$ID] = array('Bezeichnung für Terraner', 'Bezeichnung für SubTerraner', 'Beschreibung', 'Bauzeit', 'ID_Rasse', 'url', 'Energieverbrauch pro Stunde in GigaWatt');
    	$this->Gebäude[1] 	= array('Kommandozentrale','Regierungssitz', 'Dieses Gebäude ist das Herz jeder Kolonie. Von hier aus werden alle Bauvorhaben der Kolonie koordiniert sowie die Kolonieausbaustufe festgelegt. Pro Gebäudelevel erhältst du 25 Eisen, Stein & Nahrung, und es verringern sich die Bauzeiten deiner anderen Gebäude. Im weiteren Spielverlauf kannst du hier deine Kolonien zur nächsten Ausbaustufe aufsteigen lassen. Tipp: Vor allem am Anfang ist es sehrwichtig, das Hauptquartier intensiv auszubauen, um keine unmenschlichen Bauzeiten bei den anderen Gebäuden zu riskieren.',3,0, 'geb_hq.php',10, 'Hauptgebäude');
        $this->Gebäude[2] 	= array('Wohnkomplex', 'Wohnhöhle', 'Dieses Gebäude stellt den Einwohnern deiner Kolonie Wohnraum zu Verfügung. Erweiterst du die Quartiere deiner Kolonien können können sich auch mehr Leute dort niederlassen und diese brauchst du zum Unterhalt deines Militärs sehr dringed. Steigt deine Kolonieausbaustufe, so verdoppeln sich im Rahmen der Expansion auch die Kapazitäten deiner Wohngebäude. Tipp: Jeder Einwohner braucht auch Nahrung. Wenn es nicht genugt gibt, kommt der Sensenmann.', 2, 0, 'gebaeude.php', 25, '');
    	$this->Gebäude[3] 	= array(/*'Hochhaus', 'Große Wohnzelle',  'Kann eine größere Menschenmasse aufnehmen.', 4, 0, 'gebaeude.php', 50, ''*/);
    	$this->Gebäude[4] 	= array();
    	$this->Gebäude[5] 	= array();
    	$this->Gebäude[6] 	= array();
    	$this->Gebäude[7] 	= array('Rohstoffgebäude', 'Rohstoffgebäude', 'Alle direkt förderbaren Rohstoffe können hier abgebaut werden, vorausgesetzt du hast die Rohstoffe überhaupt entdeckt. Dazu suchst du am besten zuerst nach ein paar Vorkommen. Sind diese gefunden kannst du Drohnen beauftragen dort Rohstoffe abzubauen. Besonders wichtig sind Eisen, Stein und Öl. Mit jedem Level dieses Gebäudes erhältst du 5 Drohnen zusätzlich. Tipp: Eine ausreichende Grundversorgung an Rohstoffen ist von herausragender Bedeutung. Wann immer es möglich ist, solltest du hier investieren.', 1.5, 0, 'geb_rohstoff.php', 30,'Rohstoffgebäude');
    	$this->Gebäude[8] 	= array();
    	$this->Gebäude[9] 	= array('Gewächshaus', 'Nahrungsmittelfabrik', 'Wie der Name schon andeutet wird hier fleißig angebaut und geerntet, damit Deine Krieger und Einwohner auch gut versorgt sind und nicht darben müssen. Die Produktion verdoppelt sich (ebenso wie der Platz in deinen Appartements) mit jedem höheren Koloniestatus. Hinweis: Zu wenig Nahrung , und deine Bevölkerung stirbt dahin.', 1, 0, 'gebaeude.php', 10, '');
    	$this->Gebäude[10] 	= array('Schmelze', 'Schmelze', 'In diesem heißen Gebäude wird beständig Eisenerz verflüssigt und zu Stahl umgewandelt. Stahl ist für manche Gebäude und für viele Einheiten ein wichtiger Grundbaustoff. Tipp: Je höher das Level, desto mehr Stahl gibt es pro verwendetem Eisen.', 2, 0, 'geb_schmelze.php', 50, '');    	
        $this->Gebäude[11] 	= array('Chemiefabrik', 'Chemiefabrik', 'Hier wird Erdöl durch diverse komplizierte Verfahren raffiniert und zu Kunststoffen gemacht. Diese wiederum sind ein wichtiger Bestandteil moderner Waffen. Tipp: Öl wird auch für die Energieproduktion benötigt.', 2.5, 0, 'geb_chemiefabrik.php', 50, '');         	
        $this->Gebäude[12]	= array('Hydro-Fabrik', 'Hydro-Fabrik', 'Für eine ausreichende Wasserstoffproduktion sorgt hoffentlich dieses Gebäude. Das wertvolle Gas eignet sich hervorragend für Kühlprozesse, oder aber zur simplen Verbrennung. Tipp: Der Wasserstoff wird hier direkt gefördert und benötigt keine anderen Rohstoffe.', 3, 0, 'geb_extraktor.php', 50, '');
        $this->Gebäude[13]	= array('Titanschmelze', 'Titanschmelze', 'Um deinen Bedarf an Titan zu decken baust du am besten dieses Gebäude. Hier werden Titanerze in reinsten Titanstahl umgewandelt. Tipp: Hier könnte sich ein hoher Ausbaulevel lohnen, da anfangs sehr viel Titanerz für eine Tonne Titan benötigt wird.', 3.5, 0, 'geb_titanschmelze.php', 60, '');
       	$this->Gebäude[14]	= array();
        $this->Gebäude[15] 	= array('Rohstofflager', 'Rohstofflager', 'Standardmäßig hat deine Kolonie 20k Lagerplatz für alle Nicht-Radioaktiven Rohstoffe. Pro Level dieses Gebäude erhältst du für diese Rohstoffe 15k dazu. Tipp: Für Radioaktive Rohstoffe benötigst du das Sicherheitslager.', 1, 0, 'gebaeude.php', 25, '');    	
    	$this->Gebäude[16] 	= array('Sicherheitslager', 'Sicherheitslager', 'Alle Radioaktiven Rohstoffe können hier sicher gelagert werden. Pro Level kannst du 5k mehr an Plutonium und Uran lagern. Tipp: Uran und Plutonium werden Großteils für moderne Waffen benötigt.', 6, 0, 'gebaeude.php', 120, '');
    	$this->Gebäude[17] 	= array('Kraftwerk', 'Verbrenner', 'Die! Energieversorgung für deine Kolonie. Dieses Gebäude liefert maximal 100 GW pro Ausbaustufe, verbrennt allerdings dazu wertvolles Öl. Tipp: Achte darauf, dass du stets genug Leistung für deine Bauten zur Verfügung stellen kannst, und dass immer genügend Öl in Reserve ist. Bei kritischem Energieniveau dauert fast alles ein Vielfaches der eigentlichen Zeit.', 1.25, 0, 'geb_kraftwerk.php', 0, 'Energieversorgungsgebäude');
    	$this->Gebäude[18] 	= array('Solarkraftwerk', 'Thermalkraftwerk', 'Um zusätzliche Leistung zur Verfügung zu stellen, wenn gerade einmal das Öl knapp ist, oder um es gar nicht erst im Großen Stil für die Energieproduktion zu verbrauchen ist dieses Gebäude da. Es liefert schlicht Energie, ohne dafür etwas zu benötigen. Tipp: Mit jedem Level gibts 50 GW mehr.', 2, 0, 'gebaeude.php', 0, '');
    	$this->Gebäude[19] 	= array('Brutreaktor', 'Brutreaktor', 'Nicht ganz so umweltfreundlich, dafür aber noch Leistungsfähiger ist dieser Gebäudekomplex. Das Prinzip der Kernspaltung erlaubt es aus schweren instabilen Elementen wie Uran große Energiemengen freizusetzen. Als Nebenprodukt ensteht dabei das noch gefährlichere Plutonium. Tipp: Bei einem hohen Energieverbrauch bricht die Energieversorung meist komplett zusammen, wenn nicht genügend Uran zur Verfügung steht.', 5, 0, 'geb_brutreaktor.php', 0, '');    	
    	$this->Gebäude[20] 	= array(/*'Not-Strom Generator', 'Not-Strom Generator', '', 1, 0, 'gebaeude.php', 0, ''*/);
    	$this->Gebäude[21] 	= array();
    	$this->Gebäude[22] 	= array();
    	$this->Gebäude[23] 	= array();
    	$this->Gebäude[24] 	= array('Forschungszentrale', 'Forschungszentrale', 'Alles wissenschaftliche Arbeiten wird von hier aus koordiniert. Außerdem können hier neue Forscher ausgebildet werden, die die zukünftigen Forschungen beschleunigen. Allerdings kostet jeder Forscher mehr als der vorangegangene. Tipp: Mit jedem Level der verringert sich die Ausbildungsdauer deiner Forscher.', 4, 0, 'geb_forschungszentrale.php', 65, 'Wissenschaftliche Institutionen');
    	$this->Gebäude[25] 	= array('Akademie', 'Akademie', 'Dieses High-Tech Gebäude erlaubt es dir die Skillpunkte deines Einwohner gezielt zu verbessern. So kann zb. Die Kampfkraft deiner Soldaten gesteigert oder die Abbaugeschwindigkeit erhöht werden. Jeder Skillpunkt kostet mehr als der vorherige. Tipp: Skillpunkte sind derzeit noch nicht aktiviert.', 6, 0, 'gebaeude.php', 120, '');
    	$this->Gebäude[26] 	= array('Special-Ops', 'Black-Ops', 'In diesem Gebäude, das nur für hohe Regierungsmitglieder die Pforten öffnet, können geheime Forschungen gestartet, sowie eine Spezialisierung für das eigene Volk ausgesucht werden. Tipp: Dieses Gebäude hat derzeit keine Auswirkungen.', 12, 0, 'gebaeude.php', 725, '');
    	$this->Gebäude[27] 	= array('Geheimdienstzentrale', 'Untergrundbewegung', 'Noch keine exakte Funktionalität festgelegt. Noch keine Effekte.', 5, 0, 'gebaeude.php', 75, '');
    	$this->Gebäude[28] 	= array();
	   	$this->Gebäude[29] 	= array('Kaserne', 'Kaserne', 'Hier regelst du die Zusammensetzung deiner Armee. Du kannst aus verschiedenen Bauteilen hunderte unterschiedliche Infanteriebaupläne für deine Einheiten zusammenstellen und wenn du das nötige Kleingeld hast auch gleich in Auftrag geben. Für größere Einheiten benötigtst du eine Waffenfabrik. Tipp: Ein höheres Level beschleunigt den Bau deiner Soldaten.', 2.5, 0, 'geb_kaserne.php?show=1', 80, 'Militärische Einrichtungen');    	
    	$this->Gebäude[30] 	= array(/*'Verteidigungsministerium', 'Verteidigungsministerium', 'Noch keine exakte Funktion festgelegt. Hier soll die Kolonieverteidigung gesteuert werden.', 3, 0, 'gebaeude.php', 100, ''*/);
    	$this->Gebäude[31] 	= array('Waffenfabrik', 'Waffenfabrik', 'Um auch gepanzerte Fahrzeuge und Mechs in Auftrag geben zu können gibt es dieses Gebäude. Hier werden sowohl Panzerfahrzeuge als auch Mechs zusammengestellt und in Produktion gegeben. Tipp: Auch hier erniedrigt ein höheres Level die Herstellungszeiten.', 4, 0, 'geb_kaserne.php?show=2', 350, '');
    	$this->Gebäude[32] 	= array('Truppenübungsplatz', 'Truppenübungsplatz', '', 4, 0, 'gebaeude.php', 350, '');
    	$this->Gebäude[33] 	= array();
    	$this->Gebäude[34] 	= array();
    	$this->Gebäude[35] 	= array('Handelsstation', 'Schwarzmarkt', 'Soll später den Handel steuern, hat derzeit aber noch keine Funktion.', 10, 0, 'gebaeude.php', 170, 'Handelsgebäude');
    	    	
    	//Gebäudekosten
    	//Kosten[$ID] = array("eisen", "stein", "stahl", "titan");    	
    	$this->Kosten[1] = array(5000, 6000, 0, 0);				//HQ
    	$this->Kosten[2] = array(4000, 4000, 0, 0);				//Haus
    	$this->Kosten[3] = array(/*5000, 5000, 1500, 0*/);		//Hochhaus
    	$this->Kosten[4] = array();
    	$this->Kosten[5] = array();
    	$this->Kosten[6] = array();    
    	//Rohstoffgebäude
    	$this->Kosten[7] = array(5000, 5000, 0, 0);				//Rohstoffgebäude
    	$this->Kosten[8] = array();
    	$this->Kosten[9] = array(750, 750, 0, 0);				//Getreidefeld
    	$this->Kosten[10] = array(3000, 5000, 0, 0);			//Schmelze	
    	$this->Kosten[11] = array(3500, 5000, 0, 0);			//Chemiefabrik
    	$this->Kosten[12] = array(7000, 10000, 2000, 0);		//Extrkator
    	$this->Kosten[13] = array(10000, 15000, 1000, 0);		//Titanschmelze
    	$this->Kosten[14] = array();
    	$this->Kosten[15] = array(5000, 5000, 0, 0);			//Rohstofflager
    	$this->Kosten[16] = array(8000, 10000, 3500, 750);		//Sicherheitslager   
    	//Energiegebäude	
    	$this->Kosten[17] = array(2500, 2000, 0, 0);			//Kraftwerk
    	$this->Kosten[18] = array(1750, 1500, 500, 0);			//Solarkraftwerk
    	$this->Kosten[19] = array(20000, 25000, 10000, 5000);	//Brutreaktor
    	$this->Kosten[20] = array(/*2500, 2500, 0, 0*/);		//Generator
    	$this->Kosten[21] = array();
    	$this->Kosten[22] = array();
    	$this->Kosten[23] = array();
    	//Wissenschaftliche Institutionen
    	$this->Kosten[24] = array(5000, 5000, 0, 0);			//Forschungszentrale
    	$this->Kosten[25] = array(10000, 10000, 5000, 1000);	//Akademie
    	$this->Kosten[26] = array(50000,75000,10000, 5000);		//Militärdingen
    	$this->Kosten[27] = array(15000, 15000, 5000, 2500);	//Spionagezentrum
    	$this->Kosten[28] = array(); 
    	//Militärische einrichtungen
    	$this->Kosten[29] = array(4500, 4000, 0, 0);			//Kaserne  
    	$this->Kosten[30] = array(/*2000,5000,1000,0*/);		//Verteidigungsministerium
    	$this->Kosten[31] = array(25000, 25000, 8000, 6000);	//Waffenfabrik
    	$this->Kosten[32] = array(10000, 15000, 500, 0);		//Truppenübungsplatz
    	$this->Kosten[33] = array();
    	$this->Kosten[34] = array();
    	//Handelsgebäude
    	$this->Kosten[35] = array(15000, 30000, 5000, 2000);	//Handelsstation
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
        */ 
        //$this->Requirement[1] = array("typ|ID|lvl") =>typ steht für kolonie-status, forschung oder gebäude
    	$this->Requirement[1] = array();					//HQ
    	$this->Requirement[2] = array();					//Haus
    	$this->Requirement[3] = array();					//Hochhaus
    	$this->Requirement[4] = array();
    	$this->Requirement[5] = array();
    	$this->Requirement[6] = array();
    	//Rohstoffgebäude
    	$this->Requirement[7] = array();					//Rohstoffgebäude
    	$this->Requirement[8] = array();
    	$this->Requirement[9] = array();					//Getreidefeld
		$this->Requirement[10] = array('2|19|2');			//Schmelze
		$this->Requirement[11] = array('2|20|4');			//Chemiefabrik
		$this->Requirement[12] = array("2|82|6");			//Extraktor
		$this->Requirement[13] = array('2|24|1');			//Titanschmelze
		$this->Requirement[14] = array();	
		$this->Requirement[15] = array('2|9|3');			//Rohstofflager
		$this->Requirement[16] = array("2|63|3");			//Sicherheitslager
		//Energieeinrichtungen
		$this->Requirement[17] = array();					//Kraftwerk
		$this->Requirement[18] = array("2|67|5");			//Solarkraftwerk
		$this->Requirement[19] = array("2|97|8");			//Brutreaktor
		$this->Requirement[20] = array();					//Generator
		$this->Requirement[21] = array();
		$this->Requirement[22] = array();
		$this->Requirement[23] = array();
		//Wissenschaftliche Institutionen
		$this->Requirement[24] = array();					//Forschungszentrale
		$this->Requirement[25] = array("2|128|4");			//Akademie
		$this->Requirement[26] = array("2|168|15");			//Black/Special - OPS
		$this->Requirement[27] = array("2|69|5");			//Spionagezentrum
		$this->Requirement[28] = array();
		//Militärische Einrichtungen
		$this->Requirement[29] = array('2|1|3');			//Kaserne
		$this->Requirement[30] = array('2|11|2');		//Verteidigungsministerium
		$this->Requirement[31] = array('2|21|3');			//Waffenfabrik
		$this->Requirement[32] = array("2|96|2");			//Truppenübungsplatz
		$this->Requirement[33] = array();
		$this->Requirement[34] = array();
		//Handelseinrichtungen
		$this->Requirement[35] = array("2|59|5");			//Handelsstation
    }
        
    //Lädt das Gebäude mit der $ID
    function loadGebäude($ID, $ID_Rasse)
    {
		//Debugmeldungen
    	$this->debug("GebäudeDaten für das Gebäude <i>$ID</i> und die Rasse <i>$ID_Rasse</i> werden geladen");
    	
    	//Setze Gebäudedaten
    	$this->ID 				= $ID;
        $this->Beschreibung 	= $this->Gebäude[$ID][2];
        $this->BuildTime		= $this->Gebäude[$ID][3];
        $this->Energieverbrauch = $this->Gebäude[$ID][6];
                
        //Lade Bezeichnung, welche Rassenabhängig ist
        if( $ID_Rasse == 1 )		//Terraner?
        {
        	$this->Bezeichnung 		= $this->Gebäude[$ID][0];
        }
        elseif( $ID_Rasse == 2 )	//Subterraner?
        {
        	$this->Bezeichnung 		= $this->Gebäude[$ID][1];
        }
    }
    
    //Gibt Kostenarray zurcük
    function getKostenArray()
    {
    	return $this->Kosten;
    }
    
    //Gibt die Kosten des gebäudes zurück
    function getKosten($level)
    {
		/*Kostenberechnungsformel:
		pro Level steigen die Kosten um x%
		Beispiel: 
			Steigerung: 	25%
			Level: 			2
			Steinkosten: 	1000 für lvl 1
			
			1000 * 1,1 = 1100
			1100 * 1,1 = 1210
			
			endpreis wäre 1210
			
		Allgemeine Berechnungsformel:
			an = a1 * q^n
			
			Endpreis	= 1000 * 1,1^2
			endpreis 	= 1210
		*/
    	//Kosten berechnen
    	/*$kosten[0] = round($this->Kosten[$this->ID][0] * pow(1.4, $level));
    	$kosten[1] = round($this->Kosten[$this->ID][1] * pow(1.4, $level));
    	$kosten[2] = round($this->Kosten[$this->ID][2] * pow(1.4, $level));
    	$kosten[3] = round($this->Kosten[$this->ID][3] * pow(1.4, $level));*/
    	
    	//Kosten berechnen
    	/*$kosten[0] = round($this->Kosten[$this->ID][0]  * exp(($level/8)));
    	$kosten[1] = round($this->Kosten[$this->ID][1]  * exp(($level/8)));
    	$kosten[2] = round($this->Kosten[$this->ID][2]  * exp(($level/8)));
    	$kosten[3] = round($this->Kosten[$this->ID][3]  * exp(($level/8)));*/
    	
    	$kosten[0] = round($this->Kosten[$this->ID][0] * pow(2,(pow($level,0.5))));
    	$kosten[1] = round($this->Kosten[$this->ID][1] * pow(2,(pow($level,0.5))));
		$kosten[2] = round($this->Kosten[$this->ID][2] * pow(2,(pow($level,0.5))));
		$kosten[3] = round($this->Kosten[$this->ID][3] * pow(2,(pow($level,0.5))));
    	    	
    	//Kosten durch 5 teilbar machen
    	for( $i=0; $i<count($kosten); $i++ )
    	{
    		$differenz = $kosten[$i] % 10;
    		$kosten[$i] -= $differenz;
    	}
    
    	//Rückgabewert
    	return $kosten;
    }
    
    function getEnergieverbrauch($level)
    {
    	/*Formel zur Energieberechnung
    	Der Energieverbrauch soll pro steigendes Level um 30% steigen.
    	Für die berechnung wird die Formel der geometrischen Reihe verwendet:
    		an = a1 * q^n-1
    			=> an gibt den Energieverbrauch für das $level an
    			=> a1 ist der grundenergieverbrauch
    			=> q beträgt in unserem Beispiel 30%, also 1.3
    			=> n-1 ist die LEvelanzahl - 1, also $level-1, da aber i.d.R der Benutzer lvl 10 besitzt und 
    				die kosten von level 11 will, wird nur Level übergeben also 10 und man bekommt den
    				Energieverbrauch von Level 11 zurück ;)
    	*/
    	$energieverbrauch = ($this->Energieverbrauch * $level) + $this->Energieverbrauch;
    	return $energieverbrauch;
    }
    
    function getGebäudeArray()
    {
    	return $this->Gebäude;
    }
    
    function getBuildTime($level, $hauptgeb_level)
    {
    	/*Bauzeit berechnen
    	pro Level des Gebäudes steigt die Bauzeit um x Stunden
    	
    	Beispiel:
    		zu bauendes level	: 	10
    		standardzeit		: 	1h
    		hauptquartierlevel	:	2
    		Steigung pro Stunde : 	50%
    		    		
    		endzeit = ((3600 * 1h) * 1.1^10) / 1.2
    			Wenn Gebäudelevel auf 3 dann durhc 1.3 teilen... 
    			
    		Damit die Endzeit nicht so komische Werte hat, muss diese immer durhc 5 teilbar sein
    		und es darf kein Rest entstehen
    	*/
		//Bauzeit berechnen    	
    	$grund_bauzeit = $this->BuildTime * 3600;	
    	//$time = ceil(($grund_bauzeit / $hauptgeb_level) * pow(1.4, $level));
    	//$time = ceil($grund_bauzeit * pow(1.4, $level-$hauptgeb_level));
    	//$time = ceil(($grund_bauzeit) * exp(pow($hauptgeb_level, 0.25)*($level-1)/$hauptgeb_level));
    	$time = $grund_bauzeit * pow(1.8,2*($level-1)/$hauptgeb_level);
    	
    	
    	//Zeit durch 5 teilbar
    	$differenz = $time % 5;
    	$time -= $differenz;
    	
    	//Debugmeldung
    	$this->debug("Bauzeit berechnen<br>=>Level: $level<br>=>Hauptgebäude: $hauptgeb_level<br>=>Länge: $laenge<br>=>Div: $div<br>=>Bauzeit: $time");
    	
    	return $time;
    }
    
    /*ermittelt formatierte bauzeit*/
    function getFormattedBuildTime($level, $hauptgeb_level)
    {
    	$sekunden = $this->getBuildTime($level, $hauptgeb_level);
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
    
    function getID()
    {
    	return $this->ID;
    }

    function getBezeichnung()
    {
        return $this->Bezeichnung;
    }

    function getBeschreibung()
    {
        return $this->Beschreibung;
    }
    
    /*Diese Funktion bekommt die neue Auslastung der schmelze übergeben und überprüft diese
    auf Korrektheit und trägt anschliessend den Wert in die Datenbank ein.*/
    function setAuslastung($neue_auslastung)
    {
    	//Deklarationen
    	$error = 1;
    	
    	/*Neue Auslastung auf Gültigkeit überprüfen*/
    	if( $neue_auslastung < 0.00 )
    	{
    		//auslastung zu klein... kleiner 5%
    		$error = -1;
    	}
    	elseif( $neue_auslastung > 1 )
    	{
    		//Auslastung zu groß... größer 100%
    		$error = -2;
    	}
    	else 
    	{
	    	//DEbugmeldung
	    	$this->debug("Setze neue Auslastung der schmelze auf ".($neue_auslastung*100)."%");
	    	
	    	//Neue Auslastung setzen
	    	$this->Auslastung = $neue_auslastung;
	    	$this->db->query("UPDATE t_userhatgebaeude SET Auslastung = $neue_auslastung WHERE ID_Gebäude = ".$this->ID." AND ID_User = ".$this->user->getUserID()." AND ID_Kolonie = ".$this->ID_Kolonie.";");
	    	$this->user->loadEnergieverbrauch();
    	}
    	//Rückggabewert!
    	return $error;
    }
    
    //Gibt die Auslastung des Gebäudes zurück!
    function getAuslastung()
    {
    	return $this->Auslastung;
    }

   
    /*Gibt Requirement zurück*/
    function getRequirement()
    {
    	return $this->Requirement[$this->ID];
    }
    
    /*Diese Funktin überprüft ob der User genügend Kunststoff  innerhalb einer Stunde produzieren kann
    Der Wert $verbrauchte_energie stellt dabei die Energie da, welche vom User
    bereits verbraucht wird
    Es wird die Energie zurückggeben, welche tatsächlich produziert wird!*/
    function checkProduktion()
    {	
    	//DEklarationen
    	$produzierte_menge = 0;
    	
    	//Ressourcenverbrauch innerhalb einer Stunde
    	$res_verbrauch = $this->getResVerbrauch();

    	//Genügend Eisen da?
    	if( $this->user->getRohstoffAnzahl($this->ID_Rohstoff) < $res_verbrauch )	//nein
    	{
    		//Fehler, da nicht gneügend Eisen... es wird weniger Energie produziert als erforderlich!
    		$produzierte_menge = ($this->user->getRohstoffAnzahl($this->ID_Rohstoff) / $this->getProduktion());
    	}
    	else 
    	{
    		$produzierte_menge = $this->getProduktion();
    	}
   	
    	//ÜBerprüfen ob Energieniveau kritisch ist
    	$produzierte_menge = $this->checkEnergieniveau($produzierte_menge);
    	
    	//DEbugmeldung
    	$this->debug("Die brutreaktor erzeugt <i>$produzierte_menge</i> Kunststoff pro Stunde");
    	
    	//Gebe tatsächliche Produktion zurück
    	return $produzierte_menge;
    }
    
    //Überprüfen ob Energienivaeu kritisch ist... wenn ja, dann 25% der REssourcen nur zurückgeben
    function checkEnergieniveau($produktions_menge)
    {
    	//ÜBerprüfen ob Energieniveau kritisch ist
    	if( $this->ENERGIENIVEAU == 'critical' )	//zu wenig energie
    	{
    		$produktions_menge = $produktions_menge * 0.25;
    	}
    	return $produktions_menge;
    }
    
    //Gibt die RohstoffProduktion pro Stunde zurück
    function getProduktion()
    {
    	//Berechne Energieproduktion pro Stunde
    	$produktion = $this->Auslastung * ($this->user->getGebäudeLevel($this->ID) * $this->Produktion_pro_level);
    	$produktion = $this->checkEnergieniveau($produktion);
    	
    	//Debugmeldung
    	$this->debug("Die Kunststoffproduktion pro h beträgt <i>$produktion</i> Einheiten");
    	
    	//Rückgabewert
    	return $produktion;
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
			}
										
			//ERfüllt User Requirement?
			if( $lvl < $requirement_detail[2] )
			{
				$erfuellt = -1;
			}
		}
		
		//Rückgabewert!
		return $erfuellt;
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