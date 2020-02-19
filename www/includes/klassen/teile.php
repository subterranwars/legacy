<?php
/*teile.php
Stellt Klasse für die Komponenten des Spiesl zur Verfügung


History:
			21.08.2004		MvR		created
			11.11.2004	 	FF		last Change
			16.11.2004		MvR		Fehler beseitigt und wieder auf aktuellen Stand gebracht!
			21.12.2004 		FF		Verteidigungsanlagen entfernt und auf neues Konzept vorbereitet.
			
*/

class TEILE
{
	//Deklaration
	var $Teile;						//Array auf teilen, welches die Daten der Komponenten speichert
	var $Kosten;					//Kosten für die Teile
	var $Requirement;				//Requirement
	var $Infanteristen_Teile = 68;	//BIs zu welcher ID sind Infanteristen Teile1
	var $Fahrzeug_Teile = 150;		//Bis zu welcher ID sind FahrzeugTeile
	var $Mech_Teile = 209;			//Bis zu welcher ID sind MechTeile
	var $teile_anzahl;				//Array, welches die minimal und maximalen IDs von den Infanteriewaffen, chassis etc enthält..
									//Selbiges gilt für fahrzeuge und Mechs						
	var $ID;
	var $bezeichnung;
	var $beschreibung;
	var $kategory;
	var $typ_bez;
	var $typ;
	var $leistung;
	var $zuladung;
	var $wendigkeit;
	var $geschwindigkeit;
	var $zielen;
	var $lebenspunkte;
	var $angriff;
	var $panzerung;
	var $munitionstyp;
	var $bonustyp;
	var $bonus;
	var $ID_Rasse;
	
	/*Standardkonstruktor*/
	function TEILE()
	{
		//Setie teile_anzahl!
		//infanteristen
		$this->teile_anzahl[1][1][0] = 1;	//chassis-min-id
		$this->teile_anzahl[1][1][1] = 12;	//Chassis-max-id
		$this->teile_anzahl[1][3][0] = 13;	//Waffe-min-id
		$this->teile_anzahl[1][3][1] = 32;	//Waffe-max-id
		$this->teile_anzahl[1][4][0] = 51;	//Munition-min-id
		$this->teile_anzahl[1][4][1] = 68;	//Munition-max-id
		$this->teile_anzahl[1][5][0] = 33;	//Panzerungs-min-id
		$this->teile_anzahl[1][5][1] = 50;	//Panzerungs-max-id
		//Panzer
		$this->teile_anzahl[2][1][0] = 69;	//chassis-min-id
		$this->teile_anzahl[2][1][1] = 82;	//Chassis-max-id
		$this->teile_anzahl[2][2][0] = 83;	//Antrieb-min-id
		$this->teile_anzahl[2][2][1] = 94;	//Antrieb-max-id
		$this->teile_anzahl[2][3][0] = 95;	//Waffe-min-id
		$this->teile_anzahl[2][3][1] = 114;	//Waffe-max-id
		$this->teile_anzahl[2][4][0] = 115;	//Munition-min-id
		$this->teile_anzahl[2][4][1] = 130;	//Munition-max-id
		$this->teile_anzahl[2][5][0] = 131;	//Panzerungs-min-id
		$this->teile_anzahl[2][5][1] = 150;	//Panzerungs-max-id
		//Mechs
		$this->teile_anzahl[3][1][0] = 151;	//chassis-min-id
		$this->teile_anzahl[3][1][1] = 160;	//Chassis-max-id
		$this->teile_anzahl[3][2][0] = 161;	//Antrieb-min-id
		$this->teile_anzahl[3][2][1] = 170;	//Antrieb-max-id
		$this->teile_anzahl[3][3][0] = 171;	//Waffe-min-id
		$this->teile_anzahl[3][3][1] = 185;	//Waffe-max-id
		$this->teile_anzahl[3][4][0] = 186;	//Munition-min-id
		$this->teile_anzahl[3][4][1] = 197;	//Munition-max-id
		$this->teile_anzahl[3][5][0] = 198;	//Panzerungs-min-id
		$this->teile_anzahl[3][5][1] = 209;	//Panzerungs-max-id
		//LKW
		$this->teile_anzahl[4][1][0] = 151;	//chassis-min-id
		$this->teile_anzahl[4][1][1] = 160;	//Chassis-max-id
		//Truppentransporter
		$this->teile_anzahl[5][1][0] = 151;	//chassis-min-id
		$this->teile_anzahl[5][1][1] = 160;	//Chassis-max-id
		
		/*$this->Teile[$ID] = array(
			'Bezeichnung Terraner',			//Name für die Terraner
			'Bezeichnung Subterraner',		//Name für die SubTerraner
			'Beschreibung',					//Detaillierte Beschreibung des Teils!
			'Kategory',						//Handelt es sich um Chasis, Antrieb, eine Panezerung etc
											1 = Chassis
											2 = Antrieb
											3 = Waffe
											4 = Munition
											5 = Panzerung
											6 = Turm
			'Typ',							//Was für eine Waffe, Chasis, Antrieb, Panzerung etc ist es?
											Die Werte 1,2,3,4,5 stammen von der Kategory!
												var $typ_bez[1] = array(0, 'Infanteristen', 'Fahrzeuge', 'Mechs');
												var $typ_bez[2] = array(0, '', 'Fahrzeugantrieb', 'Mechantrieb');
												var $typ_bez[3] = array(0, 'Projektilwaffen', 'Explosionswaffen', 'Energiewaffen');
												var $typ_bez[4] = array(0, 'Projektile', 'Rohrmunition', 'Raketen', 'Energiezellen', 'Artillerie');
												var $typ_bez[5] = array(0, 'Projektilpanzerung', 'Ballistische Panzerung', 'Energieschilde');
												var $typ_bez[6] = array(0, 'Anti-Inf-Turm', 'Anti-Fahrzeug-Turm', 'Anti-Mech-Turm');
			'Leistung',						//LEistungszuwachs, -verbrauch
			'Zuladung',						//Maximale Zuladung und Zuladungsverbrauch
			'Wendigkeit',					//Manövrierfähigkeit einer Einheit
			'Geschwindigkeit',				//Wie Schnell fährt das Teil (km/h)
			'Zielen',						//Zielenwert
			'Lebenspunkte',					//Wie viele LEben bringt das Teil?
			'Angriff',						//Angriffswert
			'Panzerung',					//Verteidigungswert			
			'Munitionstyp',					//Nur für Waffen (mit welcher Munition soll die Waffe betrieben werden können?)
											1 = Projektile 
											2 = Rohrmunition
											3 = Raketen
											4 = Energiezellen
											5 = Artillierie
			'Bonustyp',						Panzerung hat Bonus gegen Waffen!
											Waffen haben Bonus gegen Infanteristen, Fahrzeuge und Mechs
											Fahrzeuge bonus gegenüber mechs
											Mechs haben Vorteile gegen Infanterie
											Infanterie hat Vorteile gegenüber Fahrzeuge
											1 = gegen Infanterie
											2 = gegen Fahrzeuge
											3 = gegen Mechs
			'Bonus'							//Wie groß ist der Bonustyp-Bonus?
			'ID_Rasse');					//Welche Rasse kann das Bauteil bauen?
			
		/*Werte für den Typ Bezeichnung setzen!*/
		$this->typ_bez[1] = array(0, 'Infanteristen', 'Fahrzeuge', 'Mechs');									//Chassis
		$this->typ_bez[2] = array(0, '', 'Fahrzeugantrieb', 'Mechantrieb');										//Antriebe
		$this->typ_bez[3] = array(0, 'Projektilwaffen', 'Explosionswaffen', 'Energiewaffen');					//Waffen
		$this->typ_bez[4] = array(0, 'Projektile', 'Rohrmunition', 'Raketen', 'Energiezellen', 'Artillerie');	//Munition
		$this->typ_bez[5] = array(0, 'Projektilpanzerung', 'Ballistische Panzerung', 'Energieschilde');			//Panzerung
		
		/*Setze Teile werte*/
		/*$this->Teile[$ID] = array('Bez_Terraner', 'Bez_SubTerraner', 'Beschreibung', 'Kategory', 'Typ', 
									'Leistung', 'Zuladung', 'Wendigkeit', 'Geschwindigkeit', 'Zielen',
									'Lebenspunkte', 'Angriff', 'Panzerung', 'Munitionstyp','Bonustyp',
									'Bonus', 'ID_Rasse');*/
		
		/*Infanteristen*/
		//Chasis
		$this->Teile[1] = array('Dorfmiliz', 'Dorfmiliz', 'Infanteristenchassis. Der Feind ist im Anmarsch und alle Truppen sind im Einsatz??? Macht nichts! Auf die Jungs hier ist immer Verlass. Nur leicht bewaffnet, nicht allzu wendig und schlecht gepanzert bleibt ihnen nur eines - ihr Wille. Auch wenn sie nur als Kanonenfutter und letzte Verteidigungsbastion dienen, lohnen sich diese Herrschaften immer.', 1, 1, 4, 5, 15, 10, 14, 75, 0, 0, 0, 2, 0.2, 0); 
		$this->Teile[2] = array('Musketier', 'Musketier', 'Infanteristenchassis. Die ersten Fußtruppen mit echter militärischer Ausbildung. Entschlossen stellen sie sich jedem Feind. Ihr einziges Ziel: Der Kampf für die Heimat. In der Geschichte hat dieser Truppentyp schon einige harte Schlachten für sich gewinnen können, doch sollten sie nicht lange das militärische Rückrat bilden.', 1, 1, 5, 8, 18, 12, 14, 100, 0, 0, 0, 2, 0.2, 0);
		$this->Teile[3] = array('Schütze', 'Schütze', 'Infanteristenchassis. Ausgerüstet mit doch recht modernen Waffen und einer guten Ausbildung sind diese Soldaten unersetzbar für jede Truppe. Ihr Eid auf die Nation verbietet ihnen jegliche Disziplinlosigkeit und unloyales Verhalten gegenüber ihren Vorgesetzten. In Massen stampft man hiermit die Feinde in Grund und Boden.', 1, 1, 10, 10, 20, 15, 15, 125, 0, 0, 0, 2, 0.2, 0);
		$this->Teile[4] = array('Soldat', 'Soldat', 'Infanteristenchassis. Perfekt ausgebildete Truppen, bis an die Zähne bewaffnet und furchtlos vor jedem Feind: Das sind die Eigenschaften dieser zähen Männer und Frauen. Für ihren Kommandanten geben sie alles und für ihre Heimat ihr Leben. Am besten reichlich davon ausbilden!', 1, 1, 12, 14, 20, 15, 17, 150, 0, 0, 0, 2, 0.2, 0);
		$this->Teile[5] = array('Special Agent', 'Special Agent', 'Infanteristenchassis. Die Nacht bricht heran und überall nur Dunkel. Weder Mond noch Sterne sind am Himmel zusehen. Das ist das Umfeld wo sich dieser Infanterist am wohlsten fühlt. In kleinen Gruppen ist er höchst effizient.', 1, 1, 14, 18, 21, 15, 18, 175, 0, 0, 0, 2, 0.2, 0);
		$this->Teile[6] = array('Hightech Soldat', 'Hightech Soldat', 'Infanteristenchassis. Wie ein riesen Ungetüm schleppt dieser Kerl seine Technik über das Schlachtfeld. Modernste Technik ist der Garant für viele tote Feinde. Unbedingt ausbilden!', 1, 1, 17, 24, 22, 20, 20, 200, 0, 0, 0, 2, 0.2,0);
		$this->Teile[7] = array('Mutant', 'Mutant', 'Infanteristenchassis. Das Nonplusultra der Gen-Forschung. Durch Genmanipulation perfektionierten Soldaten sind zäher, stärker und blutrünstiger als jeder Naturmensch. Aber Horrorgeschichten erzählen, dass einige schon ihren Kopf unter dem Arm in die Schlacht getragen haben.', 1, 1, 20, 29, 25, 20, 22, 225, 0, 0, 0, 2, 0.2, 0);
		$this->Teile[8] = array('Cyborg', 'Cyborg', 'Infanteristenchassis. Durch das Einpflanzen von Chips in Gehirne und direkte Montage von Waffen an den menschlichen Körper lässt sich die Mordeffizienz kämpferischer Naturen durchaus noch steigern. Für einen Heeresführer sind diese Einheiten nur zu empfehlen.', 1, 1, 25, 35, 30, 25, 24, 250, 0, 0, 0, 2, 0.2, 0);
		$this->Teile[9] = array();
		$this->Teile[10] = array();
		$this->Teile[11] = array();
		$this->Teile[12] = array();
		//Waffen
		$this->Teile[13] = array('Flinte', 'Flinte', 'Infanteriewaffe,Anti-Infanterie,Projektil. Schonmal mit Opas alter Jagdflinte geschossen? Nein? Ist auch besser so! Sie ist ungenau, hat keine Durchschlagskraft und feuert langsam. Aber immerhin noch besser als ohne Waffe auf das Schlachtfeld zu gehen.', 3, 1, 1, 1, 0, 0, 0, 0, 20, 0, 1, 1, 0.2, 0);
		$this->Teile[14] = array('Gatling', 'Gatling', 'Infanteriewaffe,Anti-Infanterie,Projektil. Die Wissenschaftler haben sich daran gemacht ein leistungsfähiges Infanteriegeschütz zu entwerfen. Dabei herausgekommen ist das erste vollautomatische Geschütz - Die Gatling. Das heißt 150 Schuss pro Minute tödlicher Kugelhagel.', 3, 1, 3, 2, 0, 0, 0, 0, 25, 0, 1, 1, 0.2, 0);
		$this->Teile[15] = array('MP', 'MP', 'Infanteriewaffe,Anti-Infanterie,Projektil. Einen Meilenstein in der Geschichte stellt diese Feuerwaffe da. Sie ist leicht und hat eine schnelle Schussfolge. Leider lässt ihre Durchschlagskraft ein paar Wünsche offen. Mit der richtigen Munition ist diese Waffe allerdings absolut tödlich.', 3, 1, 8, 2, 0, 0, 0, 0, 30, 0, 1, 1, 0.2, 0);
		$this->Teile[16] = array('Sturmgewehr', 'Sturmgewehr', 'Infanteriewaffe,Anti-Infanterie,Projektil. Etwas größer als die MP ist das Sturmgewehr eine solide Basiswaffe. Mit ähnlich schneller Feuergeschwindigkeit aber deutlich besserer Durchschlagskraft sollten diese Gewehre zur Standardausrüstung ihrer Truppen gehören.', 3, 1, 12, 3, 0, 0, 0, 0, 40, 0, 1, 1, 0.2, 0);
		$this->Teile[17] = array('Maschinengewehr', 'Maschinengewehr', 'Infanteriewaffe,Anti-Infanterie,Projektil. Diese schwere Waffe lässt ihre kleineren Geschwisten im Schatten stehen. Ihr größeres Magazin, die gewaltige Durchschlagkraft und enorme Feuerrate machen sie zu der perfekten Allroundwaffe.', 3, 1, 15, 4, 0, 0, 0, 0, 45, 0, 1, 1, 0.2, 0);
		$this->Teile[18] = array('Flammenwerfer', 'Flammenwerfer', 'Infanteriewaffe,Anti-Infanterie,Energie. Wieder mal an der Überzahl feindlicher Infanterie gescheitert ?! Dann ist dieses heisse Gerät genau das Richtige. Sehr effektiv im Kampf gegen Fußsoldaten wird hiermit jede Schlacht zum Grillfest.', 3, 3, 17, 6, 0, 0, 0, 0, 55, 0, 3, 1, 0.2, 0);
		$this->Teile[19] = array('Plasmawerfer', 'Plasmawerfer', 'Infanteriewaffe,Anti-Infanterie,Energie. Mit dieser Waffe in den Händen geht dir keiner mehr ans Leder. Will ein Fußsoldat einen Treffer aus dem Plasmawerfer überleben, benötigt er eine sehr dicke Haut.', 3, 3, 20, 5, 0, 0, 0, 0, 70, 0, 3, 1, 0.2, 0);
		$this->Teile[20] = array('Panzerabwehrgewehr', 'Panzerabwehrgewehr', 'Infanteriewaffe,Anti-Panzer,Projektil. Die erste Waffe gegen gepanzerte Einheiten. Nicht das Nonplusultra aber gegen die ersten Panzerfahrzeuge durchaus annehmbar.', 3, 1, 1, 2, 0, 0, 0, 0, 150, 0, 1, 2, 0.2, 0);
		$this->Teile[21] = array('Panzerfaust', 'Panzerfaust', 'Infanteriewaffe,Anti-Panzer,Explosiv. Eine raketengestützte Panzerabwehrwaffe. Großkalibrig und schlagkräftig verwandelt die Panzerfaust jeden Panzer in Altmetall; Allerdings nur auf kurze Entfernung.', 3, 2, 2, 3, 0, 0, 0, 0, 180, 0, 2, 2, 0.2, 0);
		$this->Teile[22] = array('Tragbarer Raketenwerfer', 'Tragbarer Raketenwerfer', 'Infanteriewaffe,Anti-Panzer,Explosiv. Sehr effektive Waffe gegen Fahrzeuge. Wird sie eingesetzt haben die Blechkumpanen deiner Feinde nichts mehr zu lachen.', 3, 2, 2, 3, 0, 0, 0, 0, 220, 0, 2, 2, 0.2, 0);
		$this->Teile[23] = array('Plasmagewehr', 'Plasmagewehr', 'Infanteriewaffe,Anti-Panzer,Energie. Mit extrem heissem Plasma brennt das Geschoss des Plasmagewehrs ein Loch in jede Fahrzeugpanzerung. Optimal um auch mittelschwere Panzer aufzuhalten.', 3, 3, 10, 2, 0, 0, 0, 0, 270, 0, 3, 2, 0.2, 0);
		$this->Teile[24] = array('Plasmadisruptor', 'Plasmadisruptor', 'Infanteriewaffe,Anti-Panzer,Energie. Dieser verbesserte Version des Plasmagewehrs schmilzt in Mikrosekunden die Hülle jedes Panzers auf und geht auch mit der innensitzenden Besatzung nicht gerade zimperlich um.', 3, 3, 13, 4, 0, 0, 0, 0, 350, 0, 3, 2, 0.2, 0);
		$this->Teile[25] = array('Gravitonpulser', 'Gravitonpulser', 'Infanteriewaffe,Anti-Panzer,Energie. Als finales Instrument gegen schwere Panzer kann ich dir diese Waffe nur wärmstens ans Herz legen. Der ausgesandte Geschossstrahl lässt die Atome in der Fahrzeugpanzerung zusammenfallen und ist durch nichts aufzuhalten.', 3, 3, 18, 5, 0, 0, 0, 0, 450, 0, 3, 2, 0.2, 0);
		$this->Teile[26] = array('Roter Laser', 'Roter Laser', 'Infanteriewaffe,Anti-Mech,Energie. Um auch gegen Mechs vorgehen zu können, rüste deine Infanteristen   hiermit aus. Der rote Laser feuert schnell und hat eine gute Reichweite.', 3, 3, 16, 3, 0, 0, 0, 0, 70, 0, 3, 3, 0.2, 0);
		$this->Teile[27] = array('Blauer Laser', 'Blauer Laser', 'Infanteriewaffe,Anti-Mech,Energie. Schlagkräftiger als sein rotes Gegenstück sollte dies die Primärwaffe der Infanterie sein, wenn Mechs anrücken.', 3, 3, 18, 3, 0, 0, 0, 0, 85, 0, 3, 3, 0.2, 0);
		$this->Teile[28] = array('UV Laser', 'UV Laser', 'Infanteriewaffe,Anti-Mech,Energie. Obwohl der UV Laser das Beste ist, was man Fußsoldaten gegen Mechs in die Hand drücken kann, sollte man sich bewusste sein, dass ein Infaterist einem modernen Mech einfach in jeder Hinsicht unterlegen ist.', 3, 3, 22, 4, 0, 0, 0, 0, 90, 0, 3, 3, 0.2, 0);
		$this->Teile[29] = array();
		$this->Teile[30] = array();
		$this->Teile[31] = array();
		$this->Teile[32] = array();
		//Panzerung
		$this->Teile[33] = array('Lederweste', 'Lederweste', 'Weste, Projektilschutz. Die erste Körperpanzerung für deine Fußtruppen. Primitiv wie sie ist, bietet sie kaum Schutz und sollte daher nur in der Anfangsphase genutzt werden. Manch einer finden sicher noch eine andere Verwendung dafür.', 5, 1, 0, 2, 0, 0, 0, 0, 0, 2, 0, 0, 0.2, 0);
		$this->Teile[34] = array('Lederweste PLUS', 'Lederweste PLUS', 'Weste, Projektilschutz. Dieses Nachfolgemodel der Lederweste kann schon etwas mehr Schaden absorbieren. Kugelsicher werden ihre Infanteristen hierdurch aber trotzdem nicht.', 5, 1, 0, 3, 0, 0, 0, 0, 0, 5, 0, 0, 0.2, 0);
		$this->Teile[35] = array('Uniform mit Helm', 'Uniform mit Helm', 'Weste, Projektilschutz. Endlich haben wir die Möglichkeit unsere Truppen auch vor unliebsamen Kopfschmerzen zu bewahren. Ein solider Stahlhelm bietet mäßigen Schutz gegen Projektile. Den Kopf sollte auf dem Schlachtfeld man aber trotzdem nicht zu hoch halten.', 5, 1, 0, 3, 0, 0, 0, 0, 0, 8, 0, 0,0.2, 0);
		$this->Teile[36] = array('Kevlarweste', 'Kevlarweste', 'Weste, Projektilschutz. Diese Weste ist eine Barriere für fast alle Projektile. Aus einem kugelsicheren und dehnbaren Material hergestellt ist dieses Produkt ein wirkungsvoller Schutz für deine Truppen.', 5, 1, 0, 6, 0, 0, 0, 0, 0, 15, 0, 0,0.2, 0);
		$this->Teile[37] = array('Spectrashield Weste', 'Spectrashield Weste', 'Weste, Projektilschutz. Eine sehr gute leichte Projektilpanzerung ist die Spectrashield Weste. Sie besteht aus einem extrem leichten und widerstandsfähigen Material. Als Standardkörperpanzerung für ihre Einheiten unabdingbar.', 5,1, 0, 10, 0, 0, 0, 0, 0, 23, 0, 0,0.2, 0);
		$this->Teile[38] = array('Vollkörperpanzerung', 'Vollkörperpanzerung', 'Weste, Projektilschutz. Diese Panzerung, bestehend aus einem 5 teiligen Set, schützt wirklich jede Körperstelle die von Kugeln durchsiebt werden könnte. Aber Vorsicht... dieser Schutz kann nur gegen Projektilwaffen aufrecht erhalten werden.', 5, 1, 0, 13, 0, 0, 0, 0, 0, 32, 0, 0,0.2, 0);
		$this->Teile[39] = array('Universalweste', 'Universalweste', 'Weste, Projektilschutz. Hier siehst du das Nonplusultra gegen alle Projektilgeschosse. Für jeden Soldaten ein absolutes Muss.', 5, 1, 0, 17, 0, 0, 0, 0, 0, 43, 0,0, 0.2, 0);
		$this->Teile[40] = array('Schutzweste', 'Schutzweste', 'Weste, Explosivschutz. In rudimentärer Schutz gegen Explosivwaffen wie Panzergranaten undArtilleriemunition. Allerdings muss der Träger Einbußen beim Schutz gegen Projektile hinnehmen.', 5, 2, 0, 4, 0, 0, 0 ,0 ,0, 15, 0, 0,0.2, 0);
		$this->Teile[41] = array('Schutzweste PLUS', 'Schutzweste PLUS', 'Weste, Explosivschutz. Diese verbesserte Version der Schutzweste kann mehr Schaden absorbieren und sollte daher sofort an alle Truppen ausgegeben werden!', 5, 2, 0, 6, 0, 0, 0, 0, 0, 21, 0,0, 0.2, 0);
		$this->Teile[42] = array('Schwere Schutzweste', 'Schwere Schutzweste', 'Weste, Explosivschutz. Noch besserer Sitz und damit verbesserte Tragbarkeit kombiniert mit einer festeren Schutzschicht bietet dieses Modell. Damit sind sie erstmals gut gegen Explosionen geschützt.', 5, 2, 0, 9 ,0,0,0,0,0,28,0,0,0.2,0);
		$this->Teile[43] = array('Granatenfänger', 'Granatenfänger', 'Weste, Explosivschutz. Durchbrüche auf dem Gebiet der Schutzwestenforschung ermöglichen dieses Rund-Um-Schutzpaket. Sei nicht so herzlos und schütze deine Truppen mit dieser fortschrittlichen Weste gegen Explosivgeschosse.', 5, 2, 0, 12,0,0,0,0,0,36,0,0,0.2,0);
		$this->Teile[44] = array('Granatenfänger Plus', 'Granatenfänger Plus', 'Weste, Explosivschutz. Wie der Name schon sagt, fängt diese Schutzweste die Explosivgranaten quasi aus der Luft und verhindert so eine direkte Detonation auf dem Körper. Äußerst Effektiv im Personenschutz !',5,2,0,13,0,0,0,0,0,45,0,0,0.2,0);
		$this->Teile[45] = array('Integritätsweste', 'Integritätsweste', 'Weste, Explosivschutz. Um Verformungen des zu schützenden menschlichen Körpers zu vermeiden, stabilisiert die Integritätsweste ihren Schützling während einer Explosion und minimiert damit den angerichteten Schaden', 5,2,0,15,0,0,0,0,0,55,0,0,0.2,0);
		$this->Teile[46] = array('IDA Weste', 'IDA Weste', 'Weste, Explosivschutz. Die Instant-Defense-and-Action-Weste schützt und bietet gleichzeitig maximale Beweglichkeit. Sie kann auch heftigere Explosionen wegstecken, ohne zu schwächeln.', 5,2,0,18,0,0,0,0,0,61,0,0,0.2,0);
		$this->Teile[47] = array('Ultimaanzug', 'Ultimaanzug', 'Weste, Explosivschutz. Wie der Name schon sagt, ist die Ultima-Weste DIE Weste um sich vor explosiven Dingen auf dem Schlachtfeld zu schützen. Wer in dieser Weste steckt, überlebt auch eine Kernschmelze - Im Reaktor versteht sich.', 5,2,0,21,0,0,0,0,0,72,0,0,0.2,0);		$this->Teile[48] = array();
		$this->Teile[49] = array();
		$this->Teile[50] = array();	
		//Munition
		$this->Teile[51] = array('Schießpulver', 'Schießpulver', 'Infanterie-Projektil. Mit der Entwicklung der Schießpulvers sind sie nun in der Lage, kleine Geschosse zu beschleunigen. Nichts weltbewegendes, aber besser als nichts.', 4, 1, 0, 1, 0,0,0,0,1,0,0,0,0,0);
		$this->Teile[52] = array('Einfache Patrone', 'Einfache Patrone', 'Infanterie-Projektil. Hiermit wird das Geschoss (Kugel) mit der Treibladung (Pulver) kombiniert in einer Verpackung. Es verbessert sich als deutlich die Handhabung der Munition, wenn die Truppen damit ausgerüstet sind.', 4,1, 0, 1, 0, 0, 1, 0, 2,0,0,0,0,0);
		$this->Teile[53] = array('Standardpatrone', 'Standardpatrone', 'Infanterie-Projektil. Um Leistung und Produktion von Munition zu verbessern werden die Waffen und Munitionstypen in Kaliber unterteilt. Dies erhöht die Nachladerate, und verbessert die Durchschlagskraft, da die Geschosse nun speziell für die einzelnen Waffen angefertigt werden.', 4,1,0,1,0,0,1,0,3,0,0,0,0,0);
		$this->Teile[54] = array('Munitionsketten', 'Munitionsketten', 'Infanterie-Projektil. Hier sind die einzelnen Patronen in einer langen Kette untergebracht. Moderne Waffen ziehen die Ketten einfach durch den Einzug ein und erhöhen damit drastisch die Feuergeschwindigkeit.', 4,1,0,2,0,0,0,0,5,0,0,0,0,0);
		$this->Teile[55] = array('Vollmantelgeschoss', 'Vollmantelgeschoss', 'Infanterie-Projektil. Diese Geschosse sind mit einem schützenden Mantel aus Stahl umgeben, der die Flugbahn stabilisiert und das Eindringen des Geschosses erleichtert. Für fast jede Waffe zu empfehlen.', 4,1,0,2,0,0,1,0,6,0,0,0,0,0);
		$this->Teile[56] = array('Stahlkerngeschoss', 'Stahlkerngeschoss', 'Infanterie-Projektil. Zusätzlich zum Stahlmantel sind diese Projektile mit einem Stahlkern versehen. Dieser erhöht deutlich die Geschosswucht und erlaubt das Durchschlagen leichter Körperpanzerungen.', 4,1,0,2,0,0,2,0,7,0,0,0,0,0);
		$this->Teile[57] = array('Titanmantelgeschoss', 'Titanmantelgeschoss', 'Infanterie-Projektil. Die Härte des Titans macht dieses Geschoss zum Allround-Projektil. Hiermit können auch schon mal Stahlplatten durchlöchert werden.',4,1,0,3,0,0,2,0,9,0,0,0,0,0);
		$this->Teile[58] = array('Urankerngeschoss', 'Urankerngeschoss', 'Infanterie-Projektil. Wie der Name schon sagt ist hier der Stahlkern durch Uran ersetzt worden. Damit müssen deine Truppen zwar mehr Rückschlag beim Abfeuern einstecken, aber die Wirkung auf feindliche Truppen ist verheerend.', 4,1,0,4,0,0,0,0,11,0,0,0,0,0);
		$this->Teile[59] = array('HV Geschosse', 'HV Geschosse', 'Infanterie-Projektil. Eine ganz besondere Entwicklung stellt diese Munition dar. Das HighVelocity-Geschoss tötet jeden Gegner zuverlässig. Es heißt, ein Treffer durch diese Muniton verursacht einen Nervenschock und tötet sofort, auch wenn vielleicht nur der kleine Finger in Mitleidenschaft gezogen wurde.', 4,1,0,5,0,0,3,0,14,0,0,0,0,0);
		$this->Teile[60] = array('Rakete', 'Rakete', 'Infanterie-Rakete. Um Waffen wie zum Beispiel die Panzerfaust abzufeuern benötigen sie diese einfache und ungelenkte Rakete. Ihr Gefechtskopf erlaubt das mühelose Zerstören leicht gepanzerter Fahrzeuge.',4,2,0,3,0,0,3,0,25,0,0,0,0,0);
		$this->Teile[61] = array('Hornet', 'Hornet', 'Infanterie-Rakete. Als Weiterentwicklung der Rakete besitzt dieses Modell Flügelstabilsierungen, einen Infrarot-gelenkten Gefechtskopf der auch mittelschweren Fahrzeugpanzerungen mit hoher Wahrscheinlichkeit durchschlägt.',4,2,0,4,0,0,5,0,40,0,0,0,0,0);
		$this->Teile[62] = array('Kleine Energiezelle', 'Kleine Energiezelle', 'Infanterie-Energiequelle. Brauchen deine Waffen Energie ? Hast du es satt, den Hausanschluss mit 300 km Verlängerungskabel zum Schlachtfeld zu benötigen ? Kein Thema! Diese Energiezelle versorgt dich mit ausreichend Power, um leichte Energiegewehre zu betreiben.', 4,3,0,3,0,0,1,0,5,0,0,0,0,0);
		$this->Teile[63] = array('Mittlere Energiezelle', 'Mittlere Energiezelle', 'Infanterie-Energiequelle. Das etwas größere Modell. Mit dementsprechend mehr Saft und längerem Durchhaltevermögen kann diese Energiezelle einfach länger. Kompatibel mit den meisten Energiewaffen solltest du nicht zögern, deine Truppen damit auszurüsten.', 4,3,0,4,0,0,0,0,9,0,0,0,0,0);
		$this->Teile[64] = array('Große Energiezelle', 'Große Energiezelle', 'Infanterie-Energiequelle. MEHR POWER! ist das Motto einer nicht ganz unbekannten Person, und dieses Bauteil macht diesem Motto alle Ehre. Genug, um jeden soldatischen Heimwerker zufriedenzustellen.', 4,3,0,5,0,0,0,0,15,0,0,0,0,0);
		$this->Teile[65] = array();
		$this->Teile[66] = array();
		$this->Teile[67] = array();
		$this->Teile[68] = array();		
		//Fahrzeuge
		//Chassis
		$this->Teile[69] = array('Kettenpanzer', 'Kettenpanzer', 'Panzerchassis. Eine kleine gepanzerte Einheit. Aufgrund ihrer ungünstig klobigen Form ist dieser Panzer jedoch sehr anfällig für Angriffe von Fußsoldaten. Ausserdem ist er recht langsam, also für Überraschungsangriffe nicht geeignet.', 1,2, 85, 210, 7, 0.4, 19, 950, 0,0,0,3,0.2,0);
		$this->Teile[70] = array('Tank', 'Tank', 'Panzerchassis. Guter leichter Panzer mit angemessener Geschwindigkeit. Seine etwas dickere Aussenhaut sichert ihm einige Vorteile gegenüber seinem Vorgänger. Die größere Wanne bieten gleichfalls deutlich mehr Platz für Einbauten.', 1,2, 110,315,6,0.5,20, 1080, 0,0,0,3,0.2,0);
		$this->Teile[71] = array('Kampfpanzer', 'Kampfpanzer', 'Panzerchassis. Dieser schnelle, mittelschwerer Panzer ist eine Allzweckwaffe. Seine starken Panzerplatten und abgeschrägten Seiten lassen viele Granaten einfach abprallen. Deine Gegner zittern jetzt schon.', 1,2,165,465,8,0.57,22,1260,0,0,0,3,0.2,0);
		$this->Teile[72] = array('Main Battle Tank', 'Main Battle Tank', 'Panzerchassis. Dieser Koloss bildet das Rückgrat einer jeden starken und modernen Armee. Die nochmals verbesserte Formgebung kombiniert mit einer starken Hauptwaffe haben ihn schon viele Schlachten gewinnen lassen.', 1,2,285,620,12,0.65,23,1490,0,0,0,3,0.2,0);
		$this->Teile[73] = array('Schwebepanzer', 'Schwebepanzer', 'Panzerchassis. Weniger gut gepanzert als die anderen modernen Panzer, dafür aber enorm schnell. Einen Vorteil hat dieses Gerät noch... Er bewegt sich im Gegensatz zu konventionellen Panzern fast lautlos vorwärts.', 1,2,385,650,25,1.2,19,1220,0,0,0,3,0.2,0);
		$this->Teile[74] = array('Omegatank', 'Omegatank', 'Panzerchassis. Kein Wunder, dass jeder nur an ein Erdbeben denkt, wenn ein solcher Titan auf dem Schlachtfeld erscheint. Die gewaltige Panzerung und sein enormes Gewicht sind Markenzeichen für dieses Ungetüm. Wo er ist, ist keiner mehr sicher.',1,2,500,1100,16,0.75,25,1600,0,0,0,3,0.2,0);
		$this->Teile[75] = array();
		$this->Teile[76] = array();
		$this->Teile[77] = array();
		$this->Teile[78] = array();
		$this->Teile[79] = array();
		$this->Teile[80] = array();
		$this->Teile[81] = array();
		$this->Teile[82] = array();
		//Antriebe
		$this->Teile[83] = array('Verbrennungsmotor MK1', 'Verbrennungsmotor MK1', 'Panzerantrieb. Die kontrollierte Verbrennung von Erdöl oder Erdgasen ermöglich es dieser Maschine Ketten- oder Radfahrzeuge aller Art anzutreiben.', 2,2,5,7,2,50,0,0,0,0,0,0,0,0);
		$this->Teile[84] = array('Verbrennungsmotor MK2', 'Verbrennungsmotor MK2', 'Panzerantrieb. Eine verbesserte Kolbenform und zusätzliche Kühlaggregate steigern die Leistung des Verbrennungsmotors deutlich.', 2,2,15,15,2,70,0,0,0,0,0,0,0,0);
		$this->Teile[85] = array('Hybridmotor', 'Hybridmotor', 'Panzerantrieb. Diese Mischung aus Elektromotor und konventionellem Verbrennungsantrieb kann in besonderen Bedarfssituationen mehr Leistung zur Verfügung stellen und ist damit einen Einbau durchaus Wert.', 2,2,30,25,3,85,0,0,0,0,0,0,0,0);
		$this->Teile[86] = array('Wasserstoffantrieb', 'Wasserstoffantrieb', 'Panzerantrieb. Das hochexplosive Gas Wasserstoff eignet sich nicht nur hervorragend, um daraus Energie zu gewinnen, sondern es schont auch noch die Umwelt, da beim Verbrennungsvorgang andere Giftstoffe mitverbrannt werden.', 2,2,40,38,3,95,0,0,0,0,0,0,0,0);
		$this->Teile[87] = array('Mini-Atomreaktor', 'Mini-Atomreaktor', 'Panzerantrieb. Dank modernster Technik ist es nun möglich Atom-Reaktoren auch auf kleinstem Raum zum Funktionieren zu bringen. Die Strahlungsbelastung für die Fahzeugcrew ist dabei zu vernachlässigen.',2,2,55,50,4,115,0,0,0,0,0,0,0,0);
		$this->Teile[88] = array('Ionenantrieb', 'Ionenantrieb', 'Panzerantrieb. Der Rückstoß dieser hochbeschleunigten Ionen erzeugt dank jahrelanger Entwicklung nun auch genug Schub, um ein Fahrzeug zu bewegen. Aber Vorsicht: Immer genügend Abstand zum Auspuff halten !', 2,2,70,62,5,125,0,0,0,0,0,0,0,0);
		$this->Teile[89] = array('Mikrofusionsreaktor', 'Mikrofusionsreaktor', 'Panzerantrieb. Das Verschmelzen von Atomkernen liefert für jeden Antrieb gegüngend Saft. Kombiniert mit einem Antriebsmodul ist der Mikro-Fusionsreaktor äußerst effizient.', 2,2,92,75,5,140,0,0,0,0,0,0,0,0);
		$this->Teile[90] = array('Antimaterieantrieb', 'Antimaterieantrieb', 'Panzerantrieb. Extrem Reaktive Antimaterie an Bord zu haben, ist für viele ein Grund, fristlos das Fahrerdasein zu beenden. Andereseits bietet keine andere Technologie derart viel Energie und damit verbunden derart viel Schnelligkeit.', 2,2,117,90,7,200,0,0,0,0,0,0,0,0);
		$this->Teile[91] = array();
		$this->Teile[92] = array();
		$this->Teile[93] = array();
		$this->Teile[94] = array();
		//Waffen
		$this->Teile[95] = array('GMG', 'GMG', 'Panzerwaffe, Anti-Infanterie, Explosiv. Das Granat-Maschinen-Gewehr verschießt zig Granaten in der Sekunde und ist damit prädestiniert um Infanteristenverbände großflächig zu bekämpfen.', 3, 2, 45,85,0,0,0,0,75,0,2,1,0.2,0);
		$this->Teile[96] = array('Inferno', 'Inferno', 'Panzerwaffe, Anti-Infanterie, Energie. Vorsicht: Heiß!  Dieser Flammenwerfer eignet sich optimal um ungeschütztes Fußvolk einzuäschern.', 3,3,205,110, 0,0,0,0,95,0,4,1,0.2,0);
		$this->Teile[97] = array('Leichter Bordlaser', 'Leichter Bordlaser', 'Panzerwaffe, Anti-Mech, Energie. Dieser Laserturm kann auf fast alle Panzerfahrzeuge montiert werden und richtet besonders viel Schaden gegen Mechs an. Gegen Infaterie ist er allerdings eher unbrauchbar.',3,3,100,65,0,0,0,0,215,0,4,3,0.2,0);
		$this->Teile[98] = array('Pulslaser', 'Pulslaser', 'Panzerwaffe, Anti-Mech, Energie. Durch Bündelung der Laserstrahlen zu kurzen Energiepulsen erreicht man eine wesentlich bessere Schadenswirkung an Mech-Einheiten.', 3,3,150,85,0,0,0,0,235,0,4,3,0.2,0);
		$this->Teile[99] = array('Gammalaser', 'Gammalaser', 'Panzerwaffe, Anti-Mech, Energie. Erhöht man die Frequenz des strahlenden Laserlichtes erhöht sich auch die Strahlungsstärke und damit der Schaden. Mechs sollten sich vor dieser Waffe fürchten.', 3,3,210,115,0,0,0,0,290,0,4,3,0.2,0);
		$this->Teile[100] = array('HADES Laser', 'HADES Laser', 'Panzerwaffe, Anti-Mech, Energie. Wie der Name schon sagt ist mit dem H.A.D.E.S-Laser nicht gut Zuckerschlecken. In der Tat zersägt er so ziemlich jeden Mech auf dem Schlachtfeld ohne Probleme. Seines Großen Energiehunger muss das Chassis allerdings decken können.',3,3,280,190,0,0,0,0,345,0,4,3,0.2,0);
		$this->Teile[101] = array('20mm Bordkanone','20mm Bordkanone','Panzerwaffe, Anti-Panzer, Explosiv. Die leichte 20mm Bordkanone verfeuert Exploivgeschosse und versucht damit die gegnerische Panzerung zu durchschlagen. Allerdings scheitert sie spätestens bei mittelschweren Panzerungen, da ihre Durchschlagskraft einfach zu gering ist.',3,2,20,95,0,0,0,0,150,0,2,2,0.2,0);
		$this->Teile[102] = array('50mm Bordkanone','50mm Bordkanone','Panzerwaffe, Anti-Panzer, Explosiv. Eine durchaus lohnende Investition in Feuerkraft stellt dieses Bordgeschütz dar. Das deutlich längere und dickere Rohr ermöglicht einen Durchschuss fast aller leichter Panzerungstypen.',3,2,45,125,0,0,0,0,170,0,2,2,0.2,0);
		$this->Teile[103] = array('90mm Bordkanone','90mm Bordkanone','Panzerwaffe, Anti-Panzer, Explosiv. Zwar ist die 90mm Bordkanone noch immer nicht unschlagbar, aber den Vergleich mit früheren Modellen braucht sie nicht zu scheuen. Bessere Gußtechniken sowie kaltes Schmieden des Laufes ermöglichen eine höhere Präzission und eine längerer Haltbarkeit.',3,2,70,155,0,0,0,0,210,0,2,2,0.2,0);
		$this->Teile[104] = array('120mm Bordkanone','120mm Bordkanone','Panzerwaffe, Anti-Panzer, Explosiv. Mit dieser Waffe, der 120mm Glattrohrkanone ausgerüstete Fahrzeuge brauchen sich vor leichten Panzern nicht mehr zu fürchten. Durch die hohe Austrittstgeschwindigkeit erreicht das Geschoss auch auf große Distanzen mit ausreichend Wucht sein Ziel um zumindest erheblichen Schaden zu hinterlassen.',3,2,105,215,0,0,0,0,265,0,2,2,0.2,0);
		$this->Teile[105] = array('Raketenwerfer','Raktenwerfer','Panzerwaffe, Anti-Panzer, Explosiv. Da der Geschossballistik physikalische Grenzen gesetzt sind setzen moderne Kommandanten eher auf raketengestützte Panzerabwehrwaffen. Raktenen fliegen weiter und können noch im Flug ihr Ziel ändern oder der Bewegung des Opfer folgen. Damit erhöht sich die Trefferquote enorm.',3,2,135,180,0,0,0,0,315,0,3,2,0.2,0);
		$this->Teile[106] = array('Mehrfachraketenwerfer','Mehrfachraketenwerfer','Panzerwaffe, Anti-Panzer, Explosiv. Jeder Panzerkommandant liebt das Geräusch, wenn eine Rakete mit lautem Zischen die Abschussvorrichtung verlässt und Sekunden später das gegnerische Fahrzeug in Flammen aufgeht. Um diese Freude noch etwas zu erhöhen ist diese Waffe gleich mit 2 Raketenrampen ausgestattet.',3,2,190,435,0,0,0,0,480,0,3,2,0.2,0);
		$this->Teile[107] = array('Leichte Haubitze','Leichte Haubitze','Artilleriewaffe, Explosiv. Ermüdet vom andauernden Nahkampf ? Nerven die andauernden Verluste ? Dann ist die Haubitze genau das Richtige! Vor jedem Gefecht kann sie den Gegner zermürben und so die eigenen Verluste minimieren.',3,2,60,110,0,0,0,0,600,0,5,0,0,0);
		$this->Teile[108] = array('Schwere Haubitze','Schwere Haubitze','Artilleriewaffe, Explosiv. Um die zunehmend stärkeren Verteidigungslinien deiner Gegner effektiver unter Beschuss nehmen zu können wurde diese Waffe erfunden. Ihre vergrößerte Reichweite und das verlängerte Rohr bieten hierfür die nötigen Vorraussetzungen.',3,2,95,265,0,0,0,0,900,0,5,0,0,0);
		$this->Teile[109] = array("500mm Haubitze","500mm Haubitze",'Artilleriewaffe, Explosiv. Je größer desto besser, sagt ein altes Sprichwort und in diesem Fall hat es definitiv recht. Diese Artillerie verschießt über viele Kilometer verheerend wirkende Granaten und fügt jeder Verteidigung schwerste Schäden zu.',3,2,245,550,0,0,0,0,1500,0,5,0,0,0);
		$this->Teile[110] = array();
		$this->Teile[111] = array();
		$this->Teile[112] = array();
		$this->Teile[113] = array();
		$this->Teile[114] = array();
		//Munition
		$this->Teile[115] = array("Panzergranate","Panzergranate","Panzer-Rohrmunition. Eine einfache Panzergranate für alle Bordkanonen.",4,2,0,5,0,0,2,0,10,0,0,0,0,0);
		$this->Teile[116] = array("Schwere Panzergranate","Schwere Panzergranate","Panzer-Rohrmunition. Diese verbesserte Version der Panzergranate richtet etwas mehr Schaden an.",4,2,0,7,0,0,2,0,15,0,0,0,0,0);
		$this->Teile[117] = array("He Granate","He Granate","Panzer-Rohrmunition. Mit hochexplosiven Sprengstoff gefüllt reisst dieses Geschoss ein Loch in jede schwache Panzerung.",4,2,0,15,0,0,2,0,20,0,0,0,0,0);
		$this->Teile[118] = array("SABOT Granate","SABOT Granate","Panzer-Rohrmunition. Ein relativ dünner, aber schwerer Metallstab wird mit enormer Wucht gegen die Panzerung des Gegners geschleudert. Da sich die gesamte Energie auf einen sehr kleinen Bereich konzentriert, wird beinahe jede Panzerung auf Anhieb durchstoßen.",4,2,0,23,0,0,3,0,25,0,0,0,0,0);
		$this->Teile[119] = array("TICAP Granate","TICAP Granate","Panzer-Rohrmunition. Die Ticap ist nur eine geringfügige Abwandlung der Sabot, bietet aber deutlich verbesserte Flugeigenschaften.",4,2,0,40,0,0,3,0,35,0,0,0,0,0);
		$this->Teile[120] = array("IR Rakete","IR Rakete","Panzer-Rakete. Um auch aus großer Entfernung noch eine optimale Durchschlagsleistung zu erzielen, werden raketenbasierte Anti-Panzer Waffen zunehmend auch auf Panzern selbst eingesetzt. Der einfachste Typ sucht dabei mittels Wärmesuchkopf nach seinem Ziel.",4,3,0,25,0,0,3,0,20,0,0,0,0,0);
		$this->Teile[121] = array("Radargelenkte Rakete","Radargelenkte Rakete","Panzer-Rakete. Gleiches Grundprinzip wie bei der IR-Rakete. Jedoch wird bei diesem Modell das Gelände mit Radarstrahlen abgetastet und aus dem Radarecho auf das Ziel geschlossen. Die Trefferquote ist recht beachtlich.",4,3,0,60,0,0,6,0,40,0,0,0,0,0);
		$this->Teile[122] = array("IMS","IMS","Panzer-Rakete. Das Intelligent Missile System ist die Erfüllung für jeden Kommandanten. Multipe Zielerfassung und -Verfolgung, Trefferoptimierung, Flugbahninterpolation usw.usf.: eben perfekt. Zumindest fast. Denn auch diverse kombinierte Zielerfassungen garantieren leider nich keinen 100-prozentigen Treffer.",4,3,0,85,0,0,8,0,70,0,0,0,0,0);
		$this->Teile[123] = array("Akkumulator","Akkumulator","Panzer-Energiequelle. Wer kennt ihn nicht, den guten alten Akku! In seinem Innern wird die Energie chemisch gespeichert und kann durch eine Umkehrung der Reaktion auch wieder entnommen werden. Leider reicht dieses Prinzip nur für Geräte mit kleinem Energiebedarf.",4,4,0,30,0,0,1,0,35,0,0,0,0,0);
		$this->Teile[124] = array("Energiezelle","Energiezelle","Panzer-Energiequelle. Ein völlig neues Energiespeicherungsprinzip ermöglicht es eine deutlich größere Energiemenge zu speichern und bei Bedarf zur Verfügung zu stellen.",4,4,0,35,0,0,2,0,47,0,0,0,0,0);
		$this->Teile[125] = array("Mikrofusionszelle","Mikrofusionszelle","Panzer-Energiequelle. Dieses Wunder der Technik speichert die Energie direkt in den Atomkernen und kann so eine nahezu unbegrenzte Leistung aufnehmen und wieder abgeben. Besonders Waffen mit großem Energiehunger können von dieser Technologie profitieren.",4,4,0,55,0,0,2,0,70,0,0,0,0,0);
		$this->Teile[126] = array("Mörsergranate","Mörsergranate","Artilleriegranate. Die Mörsergranate ist eine Abwandlung der Rohrmunition für Panzer um auch die großen Artilleriegeschütze füttern zu können. Die Durchschlagskraft ist enorm, wenngleich auch die Zielsicherheit durch das indirekte Feuern etwas eingeschränkt wird.",4,5,0,5,0,0,-5,0,150,0,0,0,0,0);
		$this->Teile[127] = array("Schwere Mörsergranate","Schwere Mörsergranate","Artilleriegranate. Mit noch mehr hochexplosiven Sprengstoff gefüllt reisst diese Granate ein Loch in die gängisten Verteidigungsanlangen. Ausgedehntes Sperrfeuer auf gegnerische Stellungen hinterlässt oft nur noch Schutt und Asche.",4,5,0,25,0,0,-5,0,350,0,0,0,0,0);
		$this->Teile[128] = array("Clustergranate","Clustergranate","Artilleriegranate. Da Artilleriefeuer niemals exakt treffen wird, kompensiert diese Granate diesen Nachteil einfach mit noch mehr und vor allem flächendeckender Feuerkraft. Mag sein, dass man das Ziel nicht direkt getroffen hat, aber irgendwas hat man sicher erwischt.",4,5,0,67,0,0,-10,0,600,0,0,0,0,0);
		$this->Teile[129] = array();
		$this->Teile[130] = array();	
		//Panzerung
		$this->Teile[131] = array("Hitzepanzerung","Hitzepanzerung","Panzer-Energieschutz. Die Hitzepanzerung ist ein erster Versuch sich gegen wärmebasierte besser zu schützen. Sie zeichnet sich vor allem durch verbesserte Wärmeleitfähigkeiten aus, die die Hitze schnell gleichmäßig verteilt. Gegen herkömmliche Granaten ist sie nicht so wirkungsvoll wie vergleichbare kinetische Panzerungen.",5,3,0,70,0,0,0,0,0,85,0,0,0,0);
		$this->Teile[132] = array("Energieabweisende Panzerung","Energieabweisende Panzerung","Panzer-Energieschutz. Eine spezielle Legierung mit Molybdän ermöglicht es dieser Panzerung trotz ihrer Härte ernome Wärmebelastungen zu überdauern. Ähnliche Legierungen finden sich auch in den Raketenantrieben wieder.",5,3,0,140,0,0,0,0,0,120,0,0,0,0);
		$this->Teile[133] = array("Energieschild","Energieschild","Panzer-Energieschutz. Im Gegensatz zur klassischen Panzerung aus immer dickeren Schichten irgendwelcher Hartmetalllegierungen arbeitet der Energieschild mit Energiefeldern. Dem Beschuss durch Strahlen oder  kleinen geladenen Partikeln wird durch ein neutralisierndes Feld entgegengewirkt. Leider lassen sich dadurch ladungsneutrale Obejkte wie Granaten nicht beeinflussen.",5,3,95,200,0,0,0,0,0,140,0,0,0,0);
		$this->Teile[134] = array("Deflektorschild","Deflektorschild","Panzer-Energieschutz. Die konsequente Weiterentwicklung des einfachen Energieschildes führt auf diese Technologie. Der Deflektorschild kann fast alle Energiestrahlen abwehren und so das Fahrzeug auch bei starkem Beschuss sichern.",5,3,215,265,0,0,0,0,0,190,0,0,0,0);
		$this->Teile[135] = array("Stahlpanzerung","Stahlpanzerung","Panzer-Explosivschutz. Der Klassiker schlechthin wenn es um den Schutz vor Explosionen oder Projektilen geht. Nur wenige Projektile sind in der Lage auch nur diese einfache Panzerung zu durchschlagen. Granaten sind etwas besser dran, und moderne Energiewaffen lösen diese Hülle in Sekundenbruchteilen auf.",5,2,0,40,0,0,0,0,0,35,0,0,0,0);
		$this->Teile[136] = array("Gehärtete Fahrzeugpanzerung","Gehärtete Fahrzeugpanzerung","Panzer-Explosivschutz. Neue Legierungen sorgen für verbesserten Fahrzeugschutz. Oft prallen Granatsplitter und Explosivkörper dank anders geneigter Panzerplatten einfach ab. Der Schaden hält sich dabei meist in Grenzen.",5,2,0,65,0,0,0,0,0,50,0,0,0,0);
		$this->Teile[137] = array("Keramikpanzerung","Keramikpanzerung","Panzer-Explosivschutz. Bei der Keramikpanzerung kommen aufgesetze Keramikplatten zum Einsatz. Diese schützen das Fahrzeug, indem sie die Explosionenergie absorbieren und so die darunter liegende Stahlpanzerung verschont bleibt. Dem Beschuss durch einfache und mittlere Explosivkörper hält diese Panzerung meistens gut stand.",5,2,0,145,0,0,0,0,0,75,0,0,0,0);
		$this->Teile[138] = array("Mehrfachpanzerung","Mehrfachpanzerung","Panzer-Explosivschutz. Eine Vielzahl an Schichten aus unterschiedlichsten Materialien garantiert noch höheren Schutz. Die zusätzlich verstärkten Stahlplatten können dank neuester Fertigungsmethoden einen Großteil aller Geschosse aufhalten.",5,2,0,170,0,0,0,0,0,100,0,0,0,0);
		$this->Teile[139] = array("Reaktivpanzerung","Reaktivpanzerung","Panzer-Explosivschutz. Um auch die allerneuesten panzerbrechenden Geschosse überlisten zu können, registriert die Reaktivpanzerung anfliegende Granaten, und schleudert dem Geschoss ein Plattensegment entgegen. Dabei soll dieses Segment den Zünder des Geschosses vor dem eigentlichen Aufschlag auslösen lassen und so den Schaden deutlich verringern.",5,2,0,175,0,0,0,0,0,130,0,0,0,0);
		$this->Teile[140] = array("Femtopanzerung","Femtopanzerung","Panzer-Explosivschutz. Die Femtopanzerung kehrt wieder zum Prinzip der klassischen Panzerplatte zurück. Allerdings konnten die Stahlplattenatome auf kleinster Ebene miteinander verschbunden werden. Diese neue Bindungsart enthält ausreichend Bindungsenergie um nahzu jeder Explosion zu trotzen.",5,2,0,215,0,0,0,0,0,170,0,0,0,0);
		$this->Teile[141] = array("Chi-Hülle","Chi-Hülle","Panzer-Explosivschutz. Die Chi-Hülle ist eine Mixtur aus ordinärer Panzerung und Energiefeld. Während die Panzerplatten das Grundgerüst liefern verstärkt das Chi-Feld den Zusammenhalt der einzelnen Plattenteile. Je nach Bedarf lässt sich so kurz vor einem Einschlag die Feldenergie im bedrohten Gebiet verfielfachen und so ein Maximum an Widerstand erzeugen. Gewöhnliche Granaten sind hier fast vollkommen wirkungslos.",5,2,0,305,0,0,0,0,0,225,0,0,0,0);
		$this->Teile[142] = array();
		$this->Teile[143] = array();
		$this->Teile[144] = array();
		$this->Teile[145] = array();
		$this->Teile[146] = array();
		$this->Teile[147] = array();
		$this->Teile[148] = array();
		$this->Teile[149] = array();
		$this->Teile[150] = array();
		//Mechs
		//Chassis
		$this->Teile[151] = array('Hunter', 'Hunter', 'Mechchassis. Der Hunter ist ein leichter Mech. Es hat sich herausgestellt, dass sich Mechs besonders gut eignen um lästige Infanterie loszuwerden. Der Hunter ist zwar nicht allzuschwer gepanzert aber seine Wendigkeit und der Kugelhagel, den er auf seine Feinde niederregnen lassen kann lassen den Hunter zu einer guten Waffe werden.', 1,3,140,220,23,0.6,14,600,0,0,0,1,0.2,0);	
		$this->Teile[152] = array('Wolf', 'Wolf', 'Mechchassis. Als etwas schwerer Mech ist der Wolf zu klassifizieren. Auch hier sind schwache Panzerung aber hohe Agilität zu erahnen. Die erneuerten Beinmotoren sorgen für die nötige Power unterm Hintern.', 1,3,165,250,22,0.55,16,685,0,0,0,1,0.2,0);	
		$this->Teile[153] = array('Centaur', 'Centaur', 'Mechchassis. Zwar ist der Centaur immer noch eher ein Leichtgewicht, aber sein Name lässt schon vermuten, dass im Falle eines Falles durchaus mit etwas mehr Feuerkraft zu rechnen ist als bei seinen Vorgängern. Durch das Anbringen des Geschützturmes unter dem Kinn des Giganten eignet er sich noch besser, um Fußsoldaten den Gar auszumachen.', 1,3,195,280,25,0.45,17,780,0,0,0,1,0.2,0);	
		$this->Teile[154] = array('Guardian', 'Guardian', 'Mechchassis. Ein mittschwerer Zweibeiner, der sich auch mal gegen leichte Panzer zu verteidigen weiß, vorrausgesetzt natürlich, er hat die entsprechende Bewaffnung an Bord. Ungeschlagen ist er allerdings im Aufspüren versteckter Feinde. Moderne Technik wie Wärmebildkamera und Laserzielerfassung sind bei ihm schon standardmäßig eingebaut.', 1,3,295,340,18,0.5,16,890,0,0,0,1,0.2,0);	
		$this->Teile[155] = array('Mammut', 'Mammut', 'Mechchassis. Der Mamut ist das erste vierbeinige Mechchassis. Neueste Computersimulationen ergaben diese Konfiguration als die sicherste Fortbewegungsmethode. Die Vorteile sind nicht von der Hand zu weisen: Deutlich mehr Platz für Waffen und Panzerung. Im Galopp-Modus kann er sogar recht beachtliche Geschwindigkeiten erreichen.', 1,3,340,355,24,0.65,19,970,0,0,0,1,0.2,0);	
		$this->Teile[156] = array('Speeder', 'Speeder', 'Mechchassis. Obwohl er relativ klobig wirkt und seine Größe nun wahrhaft nicht viel Raum für Wünsche übrig lässt hat der Speeder noch einen extra-Kniff impetto. Dank perfektionierter Bewegungsabläufe kann der Speeder erheblich höhere Geschwindigkeiten erreichen als seine Konkurrenten in der selben Gewichtsklasse. Er überrennt den Feind förmlich, bevor dieser abdrücken kann.', 1,3,475,395,26,0.9,18,1050,0,0,0,1,0.2,0);	
		$this->Teile[157] = array('Herkules', 'Herkules', 'Mechchassis. Die Immense Kraft die diesem Ungetüm innewohnt lässt jede Verteidigungslinie erzittern. Sechs Beine, zwei seperate Panzerkabinen für Richtschützen und Kommander, anpassungsfähige Halterungen für jede Art von Panzerung und Bewaffnung kombiniert mit modernster Erfassungstechnik und der Kampferfahrung der besten Piloten der Mechtruppen ergeben eine tödliche Kampfmaschine, die sich vor nur sehr wenigen Feinden fürchten muss.', 1,3,600,415,27,0.72,22,1170,0,0,0,1,0.2,0);	
		$this->Teile[158] = array();	
		$this->Teile[159] = array();
		$this->Teile[160] = array();
		//Antriebe	
		$this->Teile[161] = array('Elektromotor', 'Elektromotor', 'Mechantrieb. Da die ersten Mechs auf zumeist alte Modelle bereits existierender Techniken zurückgraifen müssen, wurden Elektromotoren kurzerhand so modifiziert, dass sie problemlos in einem Mech funktionieren. Fast alle Bein- und Gelenks-Motoren sind einfach mit Elektrizität zu versorgen und bieten daher eine kostengünstige Alternative zu speziellen Mechmotoren.',2,3,20,4,0,60,0,0,0,0,0,0,0,0);	
		$this->Teile[162] = array('Servoantrieb', 'Servoantrieb', 'Mechantrieb. Der Servoantrieb bietet weitaus mehr Flexibilität als die simplen Elektromotoren. Viele kleine einzelne Aggregate können je nach Bedarf zusammenarbeiten und steigern so die maximal zur Verfügung stehende Motorleistung. Im Normalbetrieb sind sie deutlich sparsamer als ihre Billigausführung.',2,3,35,7,1,75,0,0,0,0,0,0,0,0);	
		$this->Teile[163] = array('Flüssiggasmotor', 'Flüssiggasmotor', 'Mechantrieb. Für den Antrieb immer schwererer Mechs benötigen diese auch zunehmend mehr Energie. Ein Flüssiggasmotor, der flüssigen Sauerstoff und Wasserstoff verbrennt schafft da Abhilfe. Hier kann genug Energie gewonnen werden, um den Mech anzutreiben.',2,3,40,12,1,85,0,0,0,0,0,0,0,0);	
		$this->Teile[164] = array('Materiekonverter', 'Materiekonverter', 'Mechantrieb. Wem die ordinäre Verbrennung nicht ausreicht kann unter anderem auf diese Erfindung zurückgreifen. Der Materiekonverter wandelt höherwertige Materie in niederwertige um. Der entstehende Abfall wird einfach durch einen Auspuff entfernt. Nicht entfernt sonder verbraucht wird allerdings die dabei frei werdende Energie.', 2,3,45,15,3,90,0,0,0,0,0,0,0,0);	
		$this->Teile[165] = array('NCE', 'NCE', 'Mechantrieb. Der NCE oder Nuclear claimant Engine ist einem Atomreaktor vergleichbar. Auf dem Vorgänger, dem Materieconverter, aufbauend, kann er allerdings mit wesentlich schwereren Elementen arbeiten. Dass dabei radioaktiver Abfall frei wird und die Crew an Bord des Mech permanent verstrahlt wird, bleibt aber bitte unter uns !', 2,3,60,15,4,110,0,0,0,0,0,0,0,0);	
		$this->Teile[166] = array('Orbitalbeschleuniger', 'Orbialbeschleuniger','Mechantrieb. Der Orbitalbeschleuniger ist der beste derzeit bekannte Antrieb für Mechchassis. Indem er Beschusskerne mittels seines Mikrobeschleunigerringes auf hohe Geschwindigkeiten erhöht und diese dann gegen im Labor hergestellte Spaltmateriealen schießt, lässt der Orbitalbeschleuniger mehr Energie frei werden, als jeder Mech eigentlich jemals benötigen wird.', 2,3,80,20,5,150,0,0,0,0,0,0,0,0);	
		$this->Teile[167] = array();	
		$this->Teile[168] = array();	
		$this->Teile[169] = array();	
		$this->Teile[170] = array();
		//Waffen
		$this->Teile[171] = array('Minigun', 'Minigun', 'Mechwaffe, Anti-Infanterie, Projektil. Eines der berühmtesten Waffendesigns gibt eigent sich besonders gut für leichte Mechs: Die Minigun. Dabei sind mehrere Läufe zylindrisch angeordnet. Ein kleiner Motor lässt sie rotieren, und bei jedem Vorbeikommen eines Laufes wird ein Schuss abgefeuert. Dies ermöglich besonders hohe Schussraten und schont gleichzeitig die einzelnen Läufe.',3,1,35,30,0,0,0,0,190,0,1,1,0.2,0);	
		$this->Teile[172] = array('Maschinenkanone', 'Maschinenkanone','Mechwaffe, Anti-Infanterie, Projektil. Diese etwas größere Version eines Maschinengewehrs verschiesst Geschosse von 30mm Durchmesser. Obwohl im Gegensatz zur Minigun nur mit einem Lauf gefeuert wird, werden hohe Schussraten und eine nicht zu verachtende Feuerkraft erreicht.',3,1,45,38,0,0,0,0,220,0,1,1,0.2,0);	
		$this->Teile[173] = array('Schalldruckkanone', 'Schalldruckkanone', 'Mechwaffe, Anti-Infanterie, Energie. Auch wenn es zuerst ungewöhnlich klingen mag, so kann man inzwischen Waffen bauen, die mittels Schallemissionen töten können. Zwar lassen sich hiermit Fahrzeuge nur schwerlich beschädigen, aber gewöhnliche Soldaten platzen zumeist einfach auf, wenn sie getroffen werden.',3,1,75,47,0,0,0,0,245,0,3,1,0.2,0);	
		$this->Teile[174] = array('Neutronenblaster', 'Neutronenblaster', 'Mechwaffe, Anti-Infanterie, Energie. Da die meisten modernen Infanteristen auch bereits schwer gepanzert sind, sind effiziente Waffen gefragt, die gewöhnlichen Körperschutz durchdringen können. Der Neutronenblaster ist so eine Waffe. Das Energiefeld, dass die hochbeschleunigten Neutronen begleitet kann von keiner bekannten Infanteriepanzerung gestoppt werden.', 3,1,215,80,0,0,0,0,265,0,3,1,0.2,0);	
		$this->Teile[175] = array('Xterm Laser', 'Xterm Laser', 'Mechwaffe, Anti-Infanterie, Energie. Der Xterm ist ein extrem tödliches Lasergeschütz für Mechs. Mehrere Gelenkmotoren können den Laser binnen Sekundenbruchteilen auf sein Ziel ausrichten. Kombiniert mit einer hohen Schlagkraft und einer äußerst hohen Feuergeschwindigkeit ist er nahezu perfekt um lästiger Infanterie den Garaus zu machen.', 3,1,340,115,0,0,0,0,290,0,3,1,0.2,0);	
		$this->Teile[176] = array('Streuplasma', 'Streuplasma', 'Mechwaffe, Anti-Infanterie, Energie. Die enorme Hitze die Geschosse dieser Waffe aussenden reicht, um selbst in mehreren Metern Entfernung noch Eisen zum Schmelzen zu bringen. Zu allem Überfluss ist bei dieser Waffe ein Modus verfügbar, der dieses brennende Plasma auch noch fein streut und so ganze Reihen verschanzter Feinde zum Glühen bringen kann.', 3,1,390,155,0,0,0,0,335,0,3,1,0.2,0);	
		$this->Teile[177] = array('Panzerabwehrkanone', 'Panzerabwehrkanone', 'Mechwaffe, Anti-Fahrzeug, Explosiv. Mechs sind nicht für den Einsatz gegen Panzer gedacht, und das macht sich schon an dieser Waffe bemerkbar. Eigentlich nur eine umgebaute 120mm Kanone, passt die Panzerabwehrkanone nicht so recht auf die Waffenklammer der gängisten Mechs und so müssen leider Einbußen hingenommen werden.',3,2,25,60,0,0,0,0,170,0,2,2,0.2,0);	
		$this->Teile[178] = array('Demolition Gun', 'Demolition Gun', 'Mechwaffe, Anti-Fahrzeug, Explosiv. Im Gegensatz zur PAK, die ja nur eine notdürftige Lösung darstellt, ist die Demolition Gun eine eigene Entwicklung für Mechs. Im Gegensatz zum Vorgänger wurde ein fast perfekt passendes Waffenmodul für die kleinen Waffenklammern der Mechs entwickelt, was eine deutlich verbesserte Schlagkraft zur Folge hat.', 3,2,50,110,0,0,0,0,300,0,2,2,0.2,0);	
		$this->Teile[179] = array('MRK', 'MRK', 'Mechwaffe, Anti-Fahrzeug, Energie. Die Magnet-Resonanz-Kanone kann extrem starkt H-Felder erzeugen. Diese eigenständigen Magnetfelder können eine unglaubliche Stärke erreichen, wenn sie in bestimmten Abständen auf ihr Ziel treffen. Bei einem Panzer oder Mech wird die äußere Verschalung quasi abgerissen, die Elektronik an Bord wird zerfetzt und überhaupt alles, was metallisch ist sollte einen großen Bogen machen.',3,3,115,180,0,0,0,0,360,0,3,2,0.2,0);	
		$this->Teile[180] = array('AM Laser', 'AM Laser', 'Mechwaffe, Anti-Mech, Energie. Ein Monster von einem Laserturm. Leider hat die Größe in diesem Fall nichts zu sagen, denn der AM-Laser nutzt zwar das maximale Waffengewicht aus, leistet aber deutlich weniger als vergleichbare stationäre Lasertürme. Dennoch muss man fairerweise sagen, reicht seine Feuerkraft aus, um sich im Kampf gegen Mechs zu bewähren.',3,3,65,55,0,0,0,0,180,0,3,3,0.2,0);	
		$this->Teile[181] = array('Synchroton Laser', 'Synchroton Laser', 'Mechwaffe, Anti-Mech, Energie. Dieser etwas neuere Laser arbeitet auf Basis von eingentlich nicht kohärenten Synchroton Strahlung. Eine neue Technik erlaubt es aber seit kurzem die Laser dieser Bauweise in einem Gefechtsturm unterzubringen und somit für Mechs verfügbar zu machen.', 3,3,165,85,0,0,0,0,215,0,3,3,0.2,0);	
		$this->Teile[182] = array('Ionenstrahler', 'Ionenstrahler', 'Mechwaffe, Anti-Mech, Energie. Da die Lasertechnik langfristig festgefahren erschien und keine nennenswerten Fortschritte mehr machte, entschied man sich dazu, andere Strahlenwaffen zu bauen. Der Ionenstrahler ist die unmittelbare Folge dieser Entscheidung. Er ist zwar deutlich teurer in der Herstellung aber seine Feuerkraft ist bis dato unübertroffen.', 3,3,225,120,0,0,0,0,265,0,3,3,0.2,0);	
		$this->Teile[183] = array('Quantenfluktuator', 'Quantenfluktuator', 'Mechwaffe, Anti-Mech, Energie. Vollständig neu ist das Prinzip dieser Waffe. Der Quantenfluktuator destabilisiert die Energie innerhalb der Materie die er beschießt und kann so innerhalb kürzester Zeit wichtige Teile an gegnerischen Mechs oder Fahrzeugen zerfallen lassen. Die Wirkung einer solchen Waffe sucht Ihresgleichen.',3,3,355,160,0,0,0,0,320,0,3,3,0.2,0);	
		$this->Teile[184] = array();	
		$this->Teile[185] = array();
		//Munition	
		$this->Teile[186] = array('Einfache Mechpatrone', 'Einfache Mechpatrone','Mech-Projektil. Die Einfache Mechpatrone eignet sich hervorragend für alle Projektilwaffen sämtlicher Mechs. Sie kann zwar keine Besonderheiten vorweisen, aber durschlägt eine einfache Rüstung wie Papier.',4,1,0,4,0,0,1,0,15,0,0,0,0,0);	
		$this->Teile[187] = array('Giftprojektile', 'Giftprojektile', 'Mech-Projektil. Ganz anders als gewöhnliche Patronen setzen die Erfinder dieser heimtückischen Munition nicht auf deren Durchschlagskraft. Vielmehr durchdringen die haudünnen Projektile ohnehin fast jedes Gewebe und setzen dann im Körper des Feindes blitzschnell hochgiftige Stoffe frei.',4,1,0,8,0,0,3,0,22,0,0,0,0,0);	
		$this->Teile[188] = array('Karbongeschoss', 'Karbongeschoss', 'Mech-Rohrmunition. Eine Standard Rohrmunition für alle Mechgeschütze.',4,2,0,12,0,0,2,0,30,0,0,0,0,0);	
		$this->Teile[189] = array('Diamantgeschosse', 'Diamantgeschosse', 'Mech-Rohrmunition. Die Diamantspitze dieses Geschosses bohrt sich beim Aufprall auf das Ziel durch die Eigendrehung des Geschosses in die Panzerung hinein und fräst sich quasi durch diese hindurch.',4,2,0,18,0,0,3,0,45,0,0,0,0,0);	
		$this->Teile[190] = array('MPS', 'MPS', 'Mech-Energiequelle. Die Mech-Power-Supply, oder kurz MPS ist eine speziell für Mechs ausgelegte Energiezelle. Sie liefert genug Energie, um leichte bis mittelschwere Waffen versorgen zu können.', 4,4,0,15,0,0,1,0,25,0,0,0,0,0);	
		$this->Teile[191] = array('AMPS', 'AMPS', 'Mech-Energiequelle. Das Advanced-Mech-Power-Supply-Versorgungssystem basiert auf dem seines Vorgängers und bietet ausreichend Waffenenergie bei verhältnismäßig leichtem Bau.', 4,4,0,22,0,0,2,0,40,0,0,0,0,0);	
		$this->Teile[192] = array('Quantenenergiezelle', 'Quantenenergiezelle','Mech-Energiequelle. Der sprunghaft angestiegene Verbrauch an Energie bei modernen Waffensystemen macht eine Einführung leistungsstarker Energielieferanten unumgänglich. Die Quantenenergiezelle ist speziell in diesem Fall eine gute Lösung aller Energienöte.',4,4,0,30,0,0,4,0,55,0,0,0,0,0);	
		$this->Teile[193] = array('Bordreaktor', 'Bordreaktor', 'Mech-Energiequelle. Der Bordreaktor benötigt zwar erheblich mehr Platz als alle vorangehenden Energiequellen, aber er spendet dafür auch deutlich mehr Leistung an alle angeschlossenen Waffen. Für moderne Mechs eine gute Wahl.', 4,4,0,40,0,0,3,0,75,0,0,0,0,0);	
		$this->Teile[194] = array('Nihilationszelle', 'Nihilationszelle', 'Mech-Energiequelle. Die ultimative Energiequelle für alle Mechs schlechthin: Die Nihiationszelle schafft es, selbst auf engstem Raum Materie vollständig in Energie umzuwandeln und daher mit nur kleinsten Treibstoffmengen auszukommen. Damit lässt sich jede Waffe versorgen.', 4,4,0,52,0,0,4,0,95,0,0,0,0,0);	
		$this->Teile[195] = array();
		$this->Teile[196] = array();	
		$this->Teile[197] = array();
		//Panzerung	
		$this->Teile[198] = array('Reflektorhülle', 'Reflektorhülle', 'Mech-Energieschutz. Die Reflektorhülle bietet trotz ihrer relativ dünnen Schicht auf der Außenhaut des Mechs relativ viel Schutz vor Energiewaffen. In heiß umkämpften Gebieten kann dies durchaus von Vorteil sein. Dennoch empfiehlt es sich, sofern möglich, modernere Energieschutztechniken zu verwenden, da die Reflektorhülle bei Weitem nicht unzerstörbar ist.',	5,3,0,55 ,0 ,0,0,0,0,130,0,0,0,0);	
		$this->Teile[199] = array('Schwacher Energieschild', 'Schwacher Energieschild', 'Mech-Energieschutz. Wie für die Panzerfahrzeuge gibt es nun auch für Mech-Kampfroboter Energieschildsysteme. Sie bieten rundum gleich viel Schutz , auch an baulich schwächeren Stellen und können so manchen Laserstrahl abhalten.', 5,3,130,25,0,0,0,0,0,150,0,0,0,0);	
		$this->Teile[200] = array('Starker Energieschild', 'Starker Energieschild', 'Mech-Energieschutz. Im Gegensatz zu seinem kleinen Bruder ist der starke Energieschild durchaus in der Lage auch mal etwas mehr Beschuss zu überdauern. Allerdings benötigt er dafür auch deutlich mehr Energie vom Chassis, die ein großer Kampfroboter allerdings allemal zur Verfügung stellen kann.', 5,3,155,45,0,0,0,0,0,170,0,0,0,0);	
		$this->Teile[201] = array('Absorberfeld', 'Absorberfeld','Mech-Energieschutz. Eine Neuhiet auf dem Gebiet des Energiewaffenschutzes ist das Absorberfeld. Wird es beschossen, so kann es die Strahlungsenergie aufnehmen und dem eigenen Kreislauf hinzufügen. So verstärkt sich die Schilddichte und das Objekt ist noch besser geschützt. Die einzige Möglichkeit das Absorberfeld zu vernichten ist, durch intensiven Beschuss seine Kühlkreisläufe zu überlasten.',5,3,215,90,0,0,0,0,0,195,0,0,0,0);	
		$this->Teile[202] = array('HGRS', 'HGRS', 'Mech-Energieschutz. Das High Gray Rejection Shield gehört zum Bestern, womit man seine Mechs ausrüsten kann. Zwar spiegelt sich solche Qualität auch im Preis wieder, dafür gibt es allerdings auch kaum noch Strahlenwaffen, die auf Anhieb Schaden anrichten können.Unverbindlicher Tipp des Herstellers: Kaufen, Einbauen, Glücklich sein.', 5,3,240,110,0,0,0,0,0,230,0,0,0,0);	
		$this->Teile[203] = array('Rumpfverstärkung Light', 'Rumpfverstärkung light', 'Mech-Projektilschutz. Die kleine Rumpfverstärkung ist eine speziell gegn Projektile schützende Panzerbeschichtung. Dabei werden auch besonders die Teile geschützt, die sonst auch durch Infanteriebeschuss Schaden nehmen könnte, wie z.B. Beingelenke oder aussen angebrachte Sensorenelemente.',5,1,0,80,0,0,0,0,0,65,0,0,0,0);	
		$this->Teile[204] = array('Rumpfverstärkung Medium', 'Rumpfverstärkung medium','Mech-Projektilschutz. Dieses variierte Modell der Rumpfverstärkung bietet deutlich mehr Schutz von Kugeln, Splitten und Kleingeschossen aller Art. Die veränderte Zusammensetzung der Schutzschicht und die vergrößerten Schutzplatten sorgen für noch höhere Sicherheit in entsprechend ausgestatteten Mechs.',5,1,0,110,0,0,0,0,0,85,0,0,0,0);	
		$this->Teile[205] = array('Anti-Durchschlagsplatten', 'Anti-Durchschlagsplatten', 'Mech-Explosivschutz. Obwohl Mechs gegenüber Explosionen recht empfindlich reagieren, müssten sie dank dieser Panzerung nicht sofort kaputt gehen. Die Anti-Druchschlagsplatten tragen zumindest Sorge, dass nicht auch kleinere Explsionen einen ganzen Mech ausser Gefecht setzen.', 5,2,0,145,0,0,0,0,0,100,0,0,0,0);	
		$this->Teile[206] = array('Impulsblocker', 'Impulsblocker', 'Mech-Explosivschutz. Der Impulsblocker ist ein weitentwickeltes Defensivsystem, dass deutlich über bloße Panzerplatten hinausgeht. Integrierte Sensoren prüfen jederzeit den Zustand und die verbleibende Dicke der Panzerung und warnen die Piloten bei kritischen Treffern. Darüberhinaus kann der Impulsblocker so Einiges ab, bevor seine Schutzwirkung zusammenbricht.', 5,2,0,165,0,0,0,0,0,125,0,0,0,0);	
		$this->Teile[207] = array('Sigma Schutzsystem', 'Sigma Schutzsystem', 'Mech-Explosivschutz. Wer es sich leisten kann, sollte es sich leisten. Das SigmaSchutzSystem oder kurz SSS verkraftet mehr als alle anderen Mechpanzerungen. Ein hiermit ausgestatteter Mech kann bei passender Bewaffnung kleinen und mittleren Panzern durchaus zu nahe treten und diese zu Pottasche verarbeiten.', 5,2,0,215,0,0,0,0,0,160,0,0,0,0);	
		$this->Teile[208] = array();	
		$this->Teile[209] = array();	
		//LkWs
		$this->Teile[210] = array('Kleiner LKW', 'Kleiner LKW', 'Der Kleine LKW kann 5.000 Rohstoffeinheiten transportieren. Er ist unbewaffnet und kann nicht kämpfen. Da er nur ein geringes Gewicht schleppen muss ist seine Geschwindigkeit recht beträchtlich und beträgt etwa 100 km/h.', 1, 4, 0,5000, 0, 100, 0, 50, 0,0,0,0,0,0);
		$this->Teile[211] = array('LKW', 'LKW', 'Größer und mit mehr Ladevolumen ausgestattet als sein Vorgänger bietet der Standard-LKW Platz für 15.000 Rohstoffeinheiten und kann diese mit bis zu 90 Stundenkilomentern transportieren.', 1, 4, 0, 15000, 0, 90, 0, 50, 0,0,0,0,0,0);
		$this->Teile[212] = array('Großer LKW', 'Großer LKW', 'Der große Bruder unter den Lastkraftwagen. Der große LKW fasst bis zu 40.000 Rohstoffeinheiten und ist damit nicht zu überbieten. Leider ist seine Geschwindigkeit hierdurch nicht ganz so hoch, wie die der kleineren Modelle. Er bringt es auch maximal 80 km/h.', 1, 4, 0, 40000, 0, 80, 0, 50, 0,0,0,0,0,0);
		$this->Teile[213] = array('Truppentransporter', 'Truppentransporter', 'Der Truppentransporter ist ein umgebauter großer LKW. Auf den Auslagen der Ladeflächen finden bis zu 20 Einheiten Platz. Seine Maximalgeschwindigkeit beträgt 80 km/h. Wie die Rohstofftransporter auch kann der Truppentransporter nicht kämpfen und wird sich im Falle eines Falles lieber ergeben als zu sterben.',1,5,0,20,0,80,0,50,0,0,0,0,0,0);
		$this->Teile[214] = array();
		$this->Teile[215] = array();
		$this->Teile[216] = array();
		$this->Teile[217] = array();
		
		//Verteidigungsanlagen
		/*
		$this->Teile[210] = array('MG-Stellung','MG-Stellung','',6,1,0,0,0,0,20); 
		$this->Teile[211] = array('','','',);	
		$this->Teile[212] = array();	
		$this->Teile[213] = array();	
		$this->Teile[214] = array();	
		$this->Teile[215] = array();	
		$this->Teile[216] = array();
		$this->Teile[217] = array();
		*/
		
		//Reserve 
		/*
		$this->Teile[218] = array();	
		$this->Teile[219] = array();	
		$this->Teile[220] = array();	
		$this->Teile[221] = array();	
		$this->Teile[222] = array();	
		$this->Teile[223] = array();	
		$this->Teile[224] = array();	
		*/
		
		//Kosten
		//$this->Kosten[$ID] = array('Eisen', 'Stahl', 'Titan','Kunststoff','Wasserstoff', 'Uran', 'Plutonium', 'Gold', 'Diamant', 'Bevölkerung');
		//Chassis
		$this->Kosten[1] = array(150,0,0,0,0,0,0,0,0,100);
		$this->Kosten[2] = array(250,0,0,0,0,0,0,0,0,100);
		$this->Kosten[3] = array(450,50,25,100,0,0,0,0,0,100);
		$this->Kosten[4] = array(550,75,45,200,0,0,0,0,0,100);
		$this->Kosten[5] = array(750,150,100,500,20,15,0,5,5,100);
		$this->Kosten[6] = array(850,350,200,600,100,35,0,15,15,100);
		$this->Kosten[7] = array(1000,250,450,750,150,45,20,35,35,100);
		$this->Kosten[8] = array(1200,300,750,500,200,65,30,50,50,100);
		$this->Kosten[9] = array();
		$this->Kosten[10] = array();
		$this->Kosten[11] = array();
		$this->Kosten[12] = array();
		//Waffen
		$this->Kosten[13] = array(100,0,0,0,0,0,0,0,0,0);
		$this->Kosten[14] = array(250,0,0,0,0,0,0,0,0,0);
		$this->Kosten[15] = array(400,25,10,50,0,0,0,0,0,0);
		$this->Kosten[16] = array(600,75,50,100,10,5,0,2,2,0);
		$this->Kosten[17] = array(750,100,75,150,25,15,0,5,5,0);
		$this->Kosten[18] = array(650,250,150,50,50,0,40,20,20,0);
		$this->Kosten[19] = array(750,450,250,150,100,0,80,40,40,0);
		$this->Kosten[20] = array(450,25,10,100,0,0,0,0,0,0);
		$this->Kosten[21] = array(650,50,25,200,0,0,0,0,0,0);
		$this->Kosten[22] = array(850,200,250,350,200,75,0,10,10,0);
		$this->Kosten[23] = array(1000,600,400,500,400,150,50,25,25,0);
		$this->Kosten[24] = array(1200,300,600,800,500,200,100,50,50,0);
		$this->Kosten[25] = array(1500,400,800,1200,600,350,200,100,100,0);
		$this->Kosten[26] = array(750,100,200,250,75,100,0,15,15,0);
		$this->Kosten[27] = array(1200,120,250,350,150,50,25,35,35,0);
		$this->Kosten[28] = array(1500,150,350,480,300,75,50,55,55,0);
		$this->Kosten[29] = array();
		$this->Kosten[30] = array();
		$this->Kosten[31] = array();
		$this->Kosten[32] = array();
		//Panzerung
		$this->Kosten[33] = array(350,0,0,0,0,0,0,0,0,0);
		$this->Kosten[34] = array(500,0,0,0,0,0,0,0,0,0);
		$this->Kosten[35] = array(450,50,25,50,0,0,0,0,0,0);
		$this->Kosten[36] = array(550,100,50,75,0,0,0,0,0,0);
		$this->Kosten[37] = array(650,150,100,100,0,0,0,0,0,0);
		$this->Kosten[38] = array(750,200,150,150,0,0,0,0,0,0);
		$this->Kosten[39] = array(850,300,250,200,0,0,10,50,50,0);
		$this->Kosten[40] = array(500,100,75,75,0,0,0,0,0,0);
		$this->Kosten[41] = array(650,120,100,150,0,0,0,0,0,0);
		$this->Kosten[42] = array(780,150,150,250,0,0,0,0,0,0);
		$this->Kosten[43] = array(850,175,200,300,0,0,0,5,5,0);
		$this->Kosten[44] = array(1000,200,250,360,0,0,0,10,10,0);
		$this->Kosten[45] = array(1250,220,300,450,0,0,10,20,20,0);
		$this->Kosten[46] = array(1450,240,350,520,0,0,20,40,40,0);
		$this->Kosten[47] = array(1600,250,400,600,0,0,30,60,60,0);
		$this->Kosten[48] = array();
		$this->Kosten[49] = array();
		$this->Kosten[50] = array();
		//Munition
		$this->Kosten[51] = array(50,0,0,0,0,0,0,0,0,0);
		$this->Kosten[52] = array(75,0,0,0,0,0,0,0,0,0);
		$this->Kosten[53] = array(100,0,0,0,0,0,0,0,0,0);
		$this->Kosten[54] = array(200,100,25,100,0,0,0,0,0,0);
		$this->Kosten[55] = array(300,150,50,120,0,0,0,0,0,0);
		$this->Kosten[56] = array(400,500,100,150,0,0,0,0,0,0);
		$this->Kosten[57] = array(500,200,500,180,0,75,0,60,60,0);
		$this->Kosten[58] = array(650,300,200,220,0,500,40,80,80,0);
		$this->Kosten[59] = array(780,400,250,250,0,100,75,100,100,0);
		$this->Kosten[60] = array(400,400,500,200,0,0,0,0,0,0);
		$this->Kosten[61] = array(450,500,550,400,250,150,0,15,15,0);
		$this->Kosten[62] = array(350,50,0,0,150,75,0,25,25,0);
		$this->Kosten[63] = array(550,100,0,0,100,100,50,50,50,0);
		$this->Kosten[64] = array(800,150,0,0,75,120,60,65,65,0);
		$this->Kosten[65] = array();
		$this->Kosten[66] = array();
		$this->Kosten[67] = array();
		$this->Kosten[68] = array();
		//Fahrzeuge
		//Chassis
		
		$this->Kosten[69] = array(850,100,250,350,0,0,0,0,0,1000);
		$this->Kosten[70] = array(1000,250,450,500,0,0,0,0,0,1000);
		$this->Kosten[71] = array(5000,1000,1300,1800,0,3500,0,2000,2000,1000);
		$this->Kosten[72] = array(10000,1800,2000,3000,500,6500,1200,3000,3000,1000);
		$this->Kosten[73] = array(15000,2000,2500,3500,1000,7500,2500,3500,3500,1000);
		$this->Kosten[74] = array(25000,2500,4500,5000,1500,8000,4500,5000,5000,1000);
		$this->Kosten[75] = array();
		$this->Kosten[76] = array();
		$this->Kosten[77] = array();
		$this->Kosten[78] = array();
		$this->Kosten[79] = array();
		$this->Kosten[80] = array();
		$this->Kosten[81] = array();
		$this->Kosten[82] = array();		
		//Antriebe
		$this->Kosten[83] = array(3500,100,500,100,0,0,0,0,0,0);
		$this->Kosten[84] = array(4000,250,1500,250,0,0,0,0,0,0);
		$this->Kosten[85] = array(4500,450,2000,450,1000,0,0,500,500,0);
		$this->Kosten[86] = array(5000,750,3000,750,1400,2000,0,1000,1000,0);
		$this->Kosten[87] = array(5500,1000,3500,1000,1800,4000,500,2000,2000,0);
		$this->Kosten[88] = array(6000,1400,4000,1400,2200,5000,1000,3000,3000,0);
		$this->Kosten[89] = array(6500,1800,5000,1800,2600,7000,1500,4000,4000,0);
		$this->Kosten[90] = array(7000,2200,5500,2200,3000,8000,2000,5000,5000,0);
		$this->Kosten[91] = array();
		$this->Kosten[92] = array();
		$this->Kosten[93] = array();
		$this->Kosten[94] = array();
		//Waffen
		$this->Kosten[95] = array(3000,500,300,400,0,50,0,20,20,0);
		$this->Kosten[96] = array(4000,550,350,450,500,250,65,75,75,0);
		$this->Kosten[97] = array(4000,550,350,450,450,350,0,75,75,0);
		$this->Kosten[98] = array(6000,500,500,350,600,500,90,120,120,0);
		$this->Kosten[99] = array(6500,500,500,350,600,650,250,175,175,0);
		$this->Kosten[100] = array(7000,500,500,350,600,800,500,250,250,0);
		$this->Kosten[101] = array(2000,450,250,350,0,0,0,0,0,0);
		$this->Kosten[102] = array(3000,500,300,400,0,0,0,0,0,0);
		$this->Kosten[103] = array(4000,550,350,450,0,0,0,0,0,0);
		$this->Kosten[104] = array(5000,600,400,500,400,150,0,50,50,0);
		$this->Kosten[105] = array(7500,1500,1000,1200,1000,800,150,150,150,0);
		$this->Kosten[106] = array(10000,2200,1800,2000,2000,1500,350,250,250,0);
		$this->Kosten[107] = array(10000,2200,1800,2000,0,0,0,0,0,0);
		$this->Kosten[108] = array(15000,2500,2500,3500,250,500,0,200,200,0);
		$this->Kosten[109] = array(25000,5000,4500,5000,550,1000,500,500,500,0);
		$this->Kosten[110] = array();
		$this->Kosten[111] = array();
		$this->Kosten[112] = array();
		$this->Kosten[113] = array();
		$this->Kosten[114] = array();
		//Munition
		$this->Kosten[115] = array(350,50,200,50,0,0,0,0,0,0);
		$this->Kosten[116] = array(450,100,350,100,0,0,0,0,0,0);
		$this->Kosten[117] = array(500,200,500,180,0,75,0,60,60,0);
		$this->Kosten[118] = array(650,300,200,220,0,500,0,80,80,0);
		$this->Kosten[119] = array(780,400,250,250,0,100,75,100,100,0);
		$this->Kosten[120] = array(450,500,550,400,250,150,15,15,15,0);
		$this->Kosten[121] = array(750,500,750,800,350,250,45,50,50,0);
		$this->Kosten[122] = array(1200,500,850,1200,450,400,65,75,75,0);
		$this->Kosten[123] = array(350,50,0,0,150,75,0,25,25,0);
		$this->Kosten[124] = array(550,100,0,0,100,100,50,50,50,0);
		$this->Kosten[125] = array(800,150,0,0,75,120,100,65,65,0);
		$this->Kosten[126] = array(1000,600,900,500,0,0,0,0,0,0);
		$this->Kosten[127] = array(1500,800,1500,1000,0,350,0,75,75,0);
		$this->Kosten[128] = array(2000,1200,2000,1500,0,550,75,150,150,0);
		$this->Kosten[129] = array();
		$this->Kosten[130] = array();
		//Panzerung
		$this->Kosten[131] = array(1450,240,350,520,0,250,0,100,100,0);
		$this->Kosten[132] = array(1600,250,700,600,0,500,0,200,200,0);
		$this->Kosten[133] = array(1800,500,1200,800,0,750,150,350,350,0);
		$this->Kosten[134] = array(2200,1000,2000,1200,0,1000,350,500,500,0);
		$this->Kosten[135] = array(1000,200,250,360,0,0,0,0,0,0);
		$this->Kosten[136] = array(1250,220,300,450,0,0,0,0,0,0);
		$this->Kosten[137] = array(1450,240,350,520,0,100,0,50,50,0);
		$this->Kosten[138] = array(1600,250,400,600,0,200,0,100,100,0);
		$this->Kosten[139] = array(2200,300,450,950,0,400,100,200,200,0);
		$this->Kosten[140] = array(2900,350,550,1300,0,650,200,300,300,0);
		$this->Kosten[141] = array(3500,400,650,1750,0,850,350,400,400,0);
		$this->Kosten[142] = array();
		$this->Kosten[143] = array();
		$this->Kosten[144] = array();
		$this->Kosten[145] = array();
		$this->Kosten[146] = array();
		$this->Kosten[147] = array();
		$this->Kosten[148] = array();
		$this->Kosten[149] = array();
		$this->Kosten[150] = array();
		//Mechs
		//Chassis
		$this->Kosten[151] = array(3000,400,350,400,200,250,0,250,250,500);
		$this->Kosten[152] = array(3500,800,650,800,400,500,0,500,500,500);
		$this->Kosten[153] = array(5000,1000,950,1500,500,2500,0,1000,1000,500);
		$this->Kosten[154] = array(8000,1400,1300,1800,650,3500,400,2000,2000,500);
		$this->Kosten[155] = array(10000,1800,1700,2500,750,5500,800,2500,2500,500);
		$this->Kosten[156] = array(15000,2300,2200,3000,820,6000,1200,3000,3000,500);
		$this->Kosten[157] = array(20000,2700,2600,3500,900,6500,1600,3500,3500,500);
		$this->Kosten[158] = array();
		$this->Kosten[159] = array();
		$this->Kosten[160] = array();
		//Antriebe
		$this->Kosten[161] = array(3500,100,500,100,450,100,0,100,100,0);
		$this->Kosten[162] = array(4000,250,1500,250,750,250,0,250,250,0);
		$this->Kosten[163] = array(4500,450,2000,450,1000,500,50,500,500,0);
		$this->Kosten[164] = array(5000,750,3000,750,1400,2000,250,1000,1000,0);
		$this->Kosten[165] = array(5500,1000,3500,1000,1800,4000,500,2000,2000,0);
		$this->Kosten[166] = array(6000,1400,4000,1400,2200,5000,1000,3000,3000,0);
		$this->Kosten[167] = array();
		$this->Kosten[168] = array();
		$this->Kosten[169] = array();
		$this->Kosten[170] = array();
		//Waffen
		$this->Kosten[171] = array(400,100,75,50,25,15,0,25,25,0);
		$this->Kosten[172] = array(500,100,75,75,50,15,0,50,50,0);
		$this->Kosten[173] = array(650,250,150,100,100,50,40,75,75,0);
		$this->Kosten[174] = array(750,450,250,150,200,150,80,125,125,0);
		$this->Kosten[175] = array(900,600,500,200,350,350,140,175,175,0);
		$this->Kosten[176] = array(1300,900,800,300,500,650,200,250,250,0);
		$this->Kosten[177] = array(4000,550,350,450,0,0,0,0,0,0);
		$this->Kosten[178] = array(5000,600,400,500,400,150,0,50,50,0);
		$this->Kosten[179] = array(7500,1500,1000,1200,1000,800,150,150,150,0);
		$this->Kosten[180] = array(2000,300,200,250,300,350,0,45,45,0);
		$this->Kosten[181] = array(4000,550,350,450,450,350,40,75,75,0);
		$this->Kosten[182] = array(6000,500,500,350,600,500,90,120,120,0);
		$this->Kosten[183] = array(6500,500,500,350,600,650,250,120,120,0);
		$this->Kosten[184] = array();
		$this->Kosten[185] = array();
		//Munition
		$this->Kosten[186] = array(500,200,500,180,0,75,0,60,60,0);
		$this->Kosten[187] = array(650,300,200,220,0,500,0,80,80,0);
		$this->Kosten[188] = array(500,200,500,180,0,75,0,60,60,0);
		$this->Kosten[189] = array(650,300,200,220,0,500,50,80,80,0);
		$this->Kosten[190] = array(350,50,0,0,150,75,0,25,25,0);
		$this->Kosten[191] = array(550,100,0,0,100,100,50,50,50,0);
		$this->Kosten[192] = array(800,150,0,0,75,120,100,65,65,0);
		$this->Kosten[193] = array(1200,200,0,0,100,180,150,85,85,0);
		$this->Kosten[194] = array(1500,250,0,0,150,250,200,110,110,0);
		$this->Kosten[195] = array();
		$this->Kosten[196] = array();
		$this->Kosten[197] = array();
		//Panzerung
		$this->Kosten[198] = array(1000,150,250,400,0,150,0,25,25,0);
		$this->Kosten[199] = array(1200,200,300,450,0,200,0,50,50,0);
		$this->Kosten[200] = array(1450,240,350,520,0,250,50,100,100,0);
		$this->Kosten[201] = array(1600,250,700,600,0,500,100,200,200,0);
		$this->Kosten[202] = array(1800,500,1200,800,0,750,150,350,350,0);
		$this->Kosten[203] = array(1450,240,350,520,0,100,0,50,50,0);
		$this->Kosten[204] = array(1650,350,550,600,0,200,50,100,100,0);
		$this->Kosten[205] = array(1450,240,350,520,0,100,0,50,50,0);
		$this->Kosten[206] = array(1600,250,400,600,0,200,50,100,100,0);
		$this->Kosten[207] = array(2200,300,450,950,0,400,100,200,200,0);
		$this->Kosten[208] = array();
		$this->Kosten[209] = array();
		
		//LkWs und Truppentransporter
		$this->Kosten[210] = array(750,200,100,100,0,0,0,0,0,0);
		$this->Kosten[211] = array(3500,1500,1000,950,0,0,0,0,0,0);
		$this->Kosten[212] = array(8000,2500,1800,1700,0,0,0,0,0,0);
		$this->Kosten[213] = array(2500,1350,5000,800,0,0,0,0,0,0);
		$this->Kosten[214] = array();
		$this->Kosten[215] = array();
		$this->Kosten[216] = array();
		$this->Kosten[217] = array();
		
		//Verteidigungsanlagen
		/*
		$this->Kosten[210] = array();	
		$this->Kosten[211] = array();	
		$this->Kosten[212] = array();	
		$this->Kosten[213] = array();	
		$this->Kosten[214] = array();	
		$this->Kosten[215] = array();	
		$this->Kosten[216] = array();	
		$this->Kosten[217] = array();
		*/
				
		//Reserve
		/*	
		$this->Kosten[218] = array();	
		$this->Kosten[219] = array();	
		$this->Kosten[220] = array();	
		$this->Kosten[221] = array();	
		$this->Kosten[222] = array();	
		$this->Kosten[223] = array();	
		$this->Kosten[224] = array();	
		*/
		
		//Requirement
		//Chassis
		$this->Requirement[1] = array("2|1|4");
		$this->Requirement[2] = array("2|5|5");
		$this->Requirement[3] = array("2|16|4");
		$this->Requirement[4] = array("2|31|4");
		$this->Requirement[5] = array("2|91|3");
		$this->Requirement[6] = array("2|91|5");
		$this->Requirement[7] = array("2|115|9");
		$this->Requirement[8] = array("2|138|7");
		$this->Requirement[9] = array();
		$this->Requirement[10] = array();
		$this->Requirement[11] = array();
		$this->Requirement[12] = array();
		//Waffen
		$this->Requirement[13] = array("2|3|3");
		$this->Requirement[14] = array("2|12|6");
		$this->Requirement[15] = array("2|28|2");
		$this->Requirement[16] = array("2|61|3");
		$this->Requirement[17] = array("2|61|8");
		$this->Requirement[18] = array("2|117|6");
		$this->Requirement[19] = array("2|153|6");
		$this->Requirement[20] = array("2|22|4");
		$this->Requirement[21] = array("2|44|4");
		$this->Requirement[22] = array("2|88|6");
		$this->Requirement[23] = array("2|100|7");
		$this->Requirement[24] = array("2|158|9");
		$this->Requirement[25] = array("2|164|10");
		$this->Requirement[26] = array("2|90|6");
		$this->Requirement[27] = array("2|104|6");
		$this->Requirement[28] = array("2|112|8");
		$this->Requirement[29] = array();
		$this->Requirement[30] = array();
		$this->Requirement[31] = array();
		$this->Requirement[32] = array();
		//Panzerung
		$this->Requirement[33] = array("2|2|2");
		$this->Requirement[34] = array("2|7|2");
		$this->Requirement[35] = array("2|16|3");
		$this->Requirement[36] = array("2|36|4");
		$this->Requirement[37] = array("2|57|5");
		$this->Requirement[38] = array("2|66|7");
		$this->Requirement[39] = array("2|101|6");
		$this->Requirement[40] = array("2|25|2");
		$this->Requirement[41] = array("2|30|4");
		$this->Requirement[42] = array("2|42|5");
		$this->Requirement[43] = array("2|80|7");
		$this->Requirement[44] = array("2|109|8");
		$this->Requirement[45] = array("2|121|10");
		$this->Requirement[46] = array("2|143|7");
		$this->Requirement[47] = array("2|157|8");
		$this->Requirement[48] = array();
		$this->Requirement[49] = array();
		$this->Requirement[50] = array();
		//Munition
		$this->Requirement[51] = array("2|4|1");
		$this->Requirement[52] = array("2|8|3");
		$this->Requirement[53] = array("2|10|3");
		$this->Requirement[54] = array("2|23|2");
		$this->Requirement[55] = array("2|32|2");
		$this->Requirement[56] = array("2|39|4");
		$this->Requirement[57] = array("2|56|7");
		$this->Requirement[58] = array("2|106|5");
		$this->Requirement[59] = array("2|113|6");
		$this->Requirement[60] = array("2|44|5");
		$this->Requirement[61] = array("2|68|5");
		$this->Requirement[62] = array("2|78|3");
		$this->Requirement[63] = array("2|118|5");
		$this->Requirement[64] = array("2|155|8");
		$this->Requirement[65] = array();
		$this->Requirement[66] = array();
		$this->Requirement[67] = array();
		$this->Requirement[68] = array();

		//Fahrzeuge
		//Chassis
		$this->Requirement[69] = array("2|34|3");
		$this->Requirement[70] = array("2|41|5");
		$this->Requirement[71] = array("2|65|6");
		$this->Requirement[72] = array("2|110|7");
		$this->Requirement[73] = array("2|148|9");
		$this->Requirement[74] = array("2|152|10");
		$this->Requirement[75] = array();
		$this->Requirement[76] = array();
		$this->Requirement[77] = array();
		$this->Requirement[78] = array();
		$this->Requirement[79] = array();
		$this->Requirement[80] = array();
		$this->Requirement[81] = array();
		$this->Requirement[82] = array();
		//Antriebe
		$this->Requirement[83] = array("2|26|2");
		$this->Requirement[84] = array("2|38|3");
		$this->Requirement[85] = array("2|67|3");
		$this->Requirement[86] = array("2|88|4");
		$this->Requirement[87] = array("2|116|6");
		$this->Requirement[88] = array("2|127|8");
		$this->Requirement[89] = array("2|161|7");
		$this->Requirement[90] = array("2|166|8");
		$this->Requirement[91] = array();
		$this->Requirement[92] = array();
		$this->Requirement[93] = array();
		$this->Requirement[94] = array();
		//Waffen
		$this->Requirement[95] = array("2|53|3");
		$this->Requirement[96] = array("2|100|5");
		$this->Requirement[97] = array("2|79|5");
		$this->Requirement[98] = array("2|104|4");
		$this->Requirement[99] = array("2|149|9");
		$this->Requirement[100] = array("2|159|9");
		$this->Requirement[101] = array("2|37|2");
		$this->Requirement[102] = array("2|40|3");
		$this->Requirement[103] = array("2|43|5");
		$this->Requirement[104] = array("2|54|5");
		$this->Requirement[105] = array("2|122|5");
		$this->Requirement[106] = array("2|141|7");
		$this->Requirement[107] = array("2|33|3");
		$this->Requirement[108] = array("2|54|7");
		$this->Requirement[109] = array("2|108|9");
		$this->Requirement[110] = array();
		$this->Requirement[111] = array();
		$this->Requirement[112] = array();
		$this->Requirement[113] = array();
		$this->Requirement[114] = array();
		//Munition
		$this->Requirement[115] = array("2|32|3");
		$this->Requirement[116] = array("2|39|5");
		$this->Requirement[117] = array("2|52|2");
		$this->Requirement[118] = array("2|72|6");
		$this->Requirement[119] = array("2|98|7");
		$this->Requirement[120] = array("2|122|9");
		$this->Requirement[121] = array("2|144|8");
		$this->Requirement[122] = array("2|151|10");
		$this->Requirement[123] = array("2|78|6");
		$this->Requirement[124] = array("2|118|8");
		$this->Requirement[125] = array("2|161|10");
		$this->Requirement[126] = array("2|27|2");
		$this->Requirement[127] = array("2|52|5");
		$this->Requirement[128] = array("2|131|3");
		$this->Requirement[129] = array();
		$this->Requirement[130] = array();
		//Panzerungen
		$this->Requirement[131] = array("2|81|2");
		$this->Requirement[132] = array("2|87|6");
		$this->Requirement[133] = array("2|132|4");
		$this->Requirement[134] = array("2|145|7");
		$this->Requirement[135] = array("2|29|3");
		$this->Requirement[136] = array("2|35|5");
		$this->Requirement[137] = array("2|84|4");
		$this->Requirement[138] = array("2|86|9");
		$this->Requirement[139] = array("2|99|5");
		$this->Requirement[140] = array("2|114|6");
		$this->Requirement[141] = array("2|154|5");
		$this->Requirement[142] = array();
		$this->Requirement[143] = array();
		$this->Requirement[144] = array();
		$this->Requirement[145] = array();
		$this->Requirement[146] = array();
		$this->Requirement[147] = array();
		$this->Requirement[148] = array();
		$this->Requirement[149] = array();
		$this->Requirement[150] = array();
		//Mechs
		//Chassis
		$this->Requirement[151] = array("2|60|3");
		$this->Requirement[152] = array("2|60|6");
		$this->Requirement[153] = array("2|73|8");
		$this->Requirement[154] = array("2|119|5");
		$this->Requirement[155] = array("2|125|8");
		$this->Requirement[156] = array("2|146|8");
		$this->Requirement[157] = array("2|163|9");
		$this->Requirement[158] = array();
		$this->Requirement[159] = array();
		$this->Requirement[160] = array();
		//Antriebe
		$this->Requirement[161] = array("2|58|1");
		$this->Requirement[162] = array("2|87|7");
		$this->Requirement[163] = array("2|102|4");
		$this->Requirement[164] = array("2|130|5");
		$this->Requirement[165] = array("2|162|8");
		$this->Requirement[166] = array("2|167|10");
		$this->Requirement[167] = array();
		$this->Requirement[168] = array();
		$this->Requirement[169] = array();
		$this->Requirement[170] = array();
		//Waffen
		$this->Requirement[171] = array("2|53|4");
		$this->Requirement[172] = array("2|53|8");
		$this->Requirement[173] = array("2|111|5");
		$this->Requirement[174] = array("2|126|7");
		$this->Requirement[175] = array("2|156|8");
		$this->Requirement[176] = array("2|158|8");
		$this->Requirement[177] = array("2|62|3");
		$this->Requirement[178] = array("2|108|7");
		$this->Requirement[179] = array("2|150|8");
		$this->Requirement[180] = array("2|89|5");
		$this->Requirement[181] = array("2|112|9");
		$this->Requirement[182] = array("2|120|6");
		$this->Requirement[183] = array("2|147|9");
		$this->Requirement[184] = array();
		$this->Requirement[185] = array();
		//Munition
		$this->Requirement[186] = array("2|56|4");
		$this->Requirement[187] = array("2|77|5");
		$this->Requirement[188] = array("2|56|2");
		$this->Requirement[189] = array("2|113|8");
		$this->Requirement[190] = array("2|78|5");
		$this->Requirement[191] = array("2|123|7");
		$this->Requirement[192] = array("2|130|9");
		$this->Requirement[193] = array("2|140|6");
		$this->Requirement[194] = array("2|166|10");
		$this->Requirement[195] = array();
		$this->Requirement[196] = array();
		$this->Requirement[197] = array();
		//Panzerungen
		$this->Requirement[198] = array("2|81|5");
		$this->Requirement[199] = array("2|124|7");
		$this->Requirement[200] = array("2|132|8");
		$this->Requirement[201] = array("2|156|10");
		$this->Requirement[202] = array("2|160|10");
		$this->Requirement[203] = array("2|74|2");
		$this->Requirement[204] = array("2|99|7");
		$this->Requirement[205] = array("2|74|7");
		$this->Requirement[206] = array("2|129|8");
		$this->Requirement[207] = array("2|154|9");
		$this->Requirement[208] = array();
		$this->Requirement[209] = array();
		
		//LkWs und Truppentransporter
		$this->Requirement[210] = array("2|26|3");
		$this->Requirement[211] = array("2|65|2");
		$this->Requirement[212] = array("2|110|2");
		$this->Requirement[213] = array("2|51|2");
		$this->Requirement[214] = array();
		$this->Requirement[215] = array();
		$this->Requirement[216] = array();
		$this->Requirement[217] = array();
		
		
		//Verteidigungsanlagen
		/*
		$this->Requirement[210] = array();	
		$this->Requirement[211] = array();	
		$this->Requirement[212] = array();	
		$this->Requirement[213] = array();	
		$this->Requirement[214] = array();	
		$this->Requirement[215] = array();	
		$this->Requirement[216] = array();	
		$this->Requirement[217] = array();	
		*/
		
		//Reserve
		/*
		$this->Requirement[218] = array();	
		$this->Requirement[219] = array();	
		$this->Requirement[220] = array();	
		$this->Requirement[221] = array();	
		$this->Requirement[222] = array();	
		$this->Requirement[223] = array();	
		$this->Requirement[224] = array();	
		*/
	}
	
	/*Lädt Teiledetails für bestimmtes Teil!*/
	function loadTeile($ID, $ID_Rasse)
	{										
		//Setze Variablen
		$this->ID 				= $ID;
		$this->beschreibung 	= $this->Teile[$ID][2];
		$this->kategory 		= $this->Teile[$ID][3];
		$this->typ 				= $this->Teile[$ID][4];
		$this->leistung 		= $this->Teile[$ID][5];
		$this->zuladung 		= $this->Teile[$ID][6];
		$this->wendigkeit 		= $this->Teile[$ID][7];
		$this->geschwindigkeit 	= $this->Teile[$ID][8];
		$this->zielen 			= $this->Teile[$ID][9];
		$this->lebenspunkte 	= $this->Teile[$ID][10];
		$this->angriff	 		= $this->Teile[$ID][11];
		$this->panzerung 		= $this->Teile[$ID][12];
		$this->munitionstyp		= $this->Teile[$ID][13];
		$this->bonustyp			= $this->Teile[$ID][14];
		$this->bonus			= $this->Teile[$ID][15];
		$this->ID_Rasse	 		= $this->Teile[$ID][16];
		
		//Welche Bezeichung?
		if( $ID_Rasse == 1 )		//Terraner
		{
			$this->bezeichnung = $this->Teile[$ID][0];
		}
		else 						//Subterraner
		{
			$this->bezeichnung = $this->Teile[$ID][1];
		}
	}
	
	/*gibt ID zurück*/
	function getID()
	{
		return $this->ID;
	}
	
	/*gibt bezeichnung zurück*/
	function getBezeichnung()
	{
		return $this->bezeichnung;
	}

	/*gibt beschreibung zurück*/
	function getBeschreibung()
	{
		return $this->beschreibung;
	}
	
	/*gibt kategory zurück*/
	function getKategory()
	{
		return $this->kategory;
	}
	
	/*gibt Typ zurück*/
	function getTyp()
	{
		return $this->typ;
	}
		
	/*gibt leistung zurück*/
	function getLeistung()
	{
		return $this->leistung;
	}
	
	/*gibt zuladung zurück*/
	function getZuladung()
	{
		return $this->zuladung;
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
	
	/*gibt zielen zurück*/
	function getZielen()
	{
		return $this->zielen;
	}
	
	/*gibt lebenspunkte zurück*/
	function getLebenspunkte()
	{
		return $this->lebenspunkte;
	}
	
	/*gibt angriffswert zurück*/
	function getAngriff()
	{
		return $this->angriff;
	}
	
	/*gibt panzerungswert zurück*/
	function getPanzerung()
	{
		return $this->panzerung;
	}
	
	/*gibt munitionstyp zurück*/
	function getMunitionstyp()
	{
		return $this->munitionstyp;
	}
	
	/*gibt bonustyp zurück*/
	function getBonustyp()
	{
		return $this->bonustyp;
	}
	
	/*gibt bonus zurück*/
	function getBonus()
	{
		return $this->bonus;
	}
	
	/*gibt id_rasse zurück*/
	function getRasseID()
	{
		return $this->ID_Rasse;
	}
	
	/*Gibt minimale ID des Typs zurück*/
	function getMinID($chassis_typ, $typ)
	{
		return $this->teile_anzahl[$chassis_typ][$typ][0];
	}
	
	/*Gibt maximale ID des Typs zurück*/
	function getMaxID($chassis_typ, $typ)
	{
		return $this->teile_anzahl[$chassis_typ][$typ][1];
	}
	
	/*Liefert Anzahl der Infanterieteile*/
	function getInfanterieAnzahl()
	{
		return $this->Infanteristen_Teile;
	}
	
	/*Liefert Anzahl der Fahrzeugteile*/
	function getFahrzeugAnzahl()
	{
		return $this->Fahrzeug_Teile;
	}
	
	/*liefert Anzahl der Mechteile*/
	function getMechAnzahl()
	{
		return $this->Mech_Teile;
	}
	
	/*Gibt Kosten zurück*/
	function getKosten()
	{
		return $this->Kosten[$this->ID];
	}
	
	/*Gibt die Bauzeit zurück!*/
	function getBuildTime()
	{
		/*Die Bauzeit wird anhand der Kosten berechnet. Die Bevölkerung wird dabei aussen vor
		gelassen. Da allerdings Bauteile, welche Uran, Plutonium kosten wertvoller sein
		werden, als Bauteile, die nur Eisen benötigen, muss dieses auch berücksichtigt werden.
		Aus diesem Grund wird für jeden Rohstoff ein gewisser Faktor einbezogen.
		
		zum Beispiel:
				Eisen: 1
				Stahl: 1,25
				Titan: 1,5
				
		So würde eine Einheit, welche 1 Eisen, 1 Stahl und 1 Titan benötigt in 3,75 Sekunden
		gefertigt. */
		
		//FaktorArray setzen
		$faktor_array = array(
			1,			//Eisen
			1.25,		//Stahl
			1.25,		//Kunststoff
			1.5, 		//Titan
			1.5,		//Wasserstoff
			1.75,		//Uran
			2,			//Plutonium
			3,			//Gold
			3			//Diamant
			);
		
		//Lade Kosten
		$kosten = $this->getKosten();
		
		//Durchlaufe alle Kosten
		for( $i=0; $i<count($kosten)-1; $i++ )	//Deshalb count($kosten)-1, da die Bevölkerung nicht einbezogen werden darf!
		{
			/*Zeit in Sekunden ermitteln
			$kosten[$i] * $faktor_array[$i]*/
			$build_time += $kosten[$i] * $faktor_array[$i];
		}		
		
		//Rückgabewert
		return $build_time;
	}
	
	/*ermittelt formatierte bauzeit*/
    function getFormattedBuildTime()
    {
    	$sekunden = $this->getBuildTime();
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
	
	/*Funktion printet alle Ergebnise tabellarisch auf*/
    function printTeile()
    {
        //AusgabeTopic erstellen
        $ausgabe  = '<table>';
        $ausgabe .= '<tr>';
        $ausgabe .= "<th colspan=\"".(count($this->Teile[1])+1)."\">Teile</th>";
        $ausgabe .= '</tr>';
        
        //Alle Teile durchlaufen
        for($i=1; $i<count($this->Teile); $i++)
        {
                //ISt Array gesett?
                if( !empty($this->Teile[$i][0]) )
                {
                        //Ausgabe setzen
                        $ausgabe .= '<tr>';
                        $ausgabe .= "<th>".($i)."</th>";
                        
                        //Teiledaten durchlaufen
                        for($a=0; $a<count($this->Teile[$i]); $a++)
                        {
                                //Wenn wert = 0, dann bitte - anzeigen
                                if( empty($this->Teile[$i][$a]) )
                                {
                                        $wert = "-";
                                }
                                else 
                                {
                                        $wert = $this->Teile[$i][$a];
                                }
                                
                                //Ausgabe setzen
                                $ausgabe .= "<td>".$wert."</td>";
                        }
                        
                        //Ausgabe setzen
                        $ausgabe .= '</tr>';
                }
        }
        $ausgabe .= '</table>';

        //Rückgabe
        return $ausgabe;
    }

    /*Testfunktion Teile!
	überprüft alle Teile auf korrektheit etc
	$param gibt an, was getestet werden soll!
	$param = 0 => STandardtest (überprüfen der Teiledaten)
	$param = 1 => Kostentest (sind kosten gesetzt worden?)
	$param = 2 => Requirementtest (bestitzt jedes teil nen Requirement)
	$param = 3 => ausführlicher Datentest
	$param = 9 => kompletter Test!*/
	function test($param)
	{		
		//Parameter überprüfen
		switch($param)
		{
			/*kompletter Test*/
			case 9:
			{
				//Deklarationen
				$topic = "Kompletter Test";
				
				//Ausgabe ssetzen
				$ausgabe = $this->test(0);
				$ausgabe .= $this->test(1);
				$ausgabe .= $this->test(2);
				break;
			}
				
			/*ausführlicher DatenTest durchführen. Überprüfen ob Daten im richtigen Bereich liegen etc*/
			case 3:
			{

				break;
			}			 
			//Requirement Test
			case 2:
			{	
				//Deklarationen
				$topic = "RequirementTest";
				
				//überprüfen ob alle Kosten gesetzt sind!
				for($i=1; $i<count($this->Teile); $i++)
				{
					//Kosten da?
					if( !isset($this->Requirement[$i]) )
					{
						$ausgabe .= "<li>";
						$ausgabe .= "Teil (#ID: $i) besitzt keine RequirementDaten. Bitte auch leere Arrays anlegen!<br>";
						$ausgabe .= "</li>";
					}
				}
				break;
			}
			//Kostentest
			case 1:
			{
				//ANzahl Kosten
				$anzahl = 10;
				$topic = "Kostentest";
				
				//überprüfen ob alle Kosten gesetzt sind!
				for($i=1; $i<count($this->Teile); $i++)
				{
					//Kosten da?
					if( count($this->Kosten[$i]) != $anzahl AND $this->Teile[$i] != NULL )
					{
						$ausgabe .= "<li>";
						$ausgabe .= "Teil (#ID: $i) weist zu wenig Kosten auf!<br>";
						$ausgabe .= "<i>Soll: $anzahl</i><br>";
						$ausgabe .= "<i>Ist : ".count($this->Kosten[$i])."";
						$ausgabe .= "</li>";
					}
				}
				break;
			}
			//Standardtest
			case 0:
			{
				//Deklarationen
				$anzahl = 17;
				$topic = "Datentest";
				
				//Überprüfen ob alle werte gestzt sind, keiner zu viel oder zu wenig!
				for($i=1; $i<count($this->Teile); $i++)
				{
					//Zu wenig oder zu Viele Werte gesetzt!
					if( (count($this->Teile[$i]) != $anzahl) AND $$this->Teile[$i][0] != NULL )
					{
						$ausgabe .= "<li>";
						$ausgabe .= "Teil (#ID: $i) weist zu wenig Daten auf!<br>";
						$ausgabe .= "<i>Soll: $anzahl</i><br>";
						$ausgabe .= "<i>Ist : ".count($this->Teile[$i])."";
						$ausgabe .= "</li>";
					}
				}
				break;
			}
		}
		
		//evtl Ausgabe setzen!
		if( $ausgabe == NULL )
		{
			$ausgabe = "Keine Fehler aufgetreten";
		}
		
		//Ausgabe!
		$final = "<table>";
		$final .= "<tr>";
		$final .= "<th>";
		$final .= "$topic";
		$final .= "</th>";
		$final .= "</tr>";
		$final .= "<tr>";
		$final .= "<td>";
		$final .= "$ausgabe";
		$final .= "</td>";
		$final .= "</tr>";
		$final .= "</table>";
		
		return $final;
	}
}?>