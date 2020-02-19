<?php
/*

History:
			14.11.04 		FaFner		kleine Änderungen
			21.12.2004 		FaFner		Texte eingefügt. Achtung ! Auch Requirements und Namen teilweise geändert.
	    								Forschungspunktekosten der Forschungen erhöht auf 100-150-200-250-300 : Dorf -> Metropole.

*/
class FORSCHUNG
{
	//Deklaratinoen
	var $ID;
	var $Bezeichnung;
	var $Bauzeit;
	var $Kosten;		//Kosten an Forschungspunkten
	var $Beschreibung;
	var $ID_Rasse;
	var $user;
	var $Forschungen;
	var $Requirement;
	
	function FORSCHUNG(/*&$user, $ID_Rasse*/)
    {
        //Variablen setzen
        $this->user 		= &$user;
        $this->ID_Rasse 	= $ID_Rasse;
        
        //Daten
		//Dorf-Forschungen
        //$this->Forschungen[] = array("Terraner-Name", "Sub-Name", "Beschreibung", "ID_Rasse", "Kosten", "Zeit");
		$this->Forschungen[1] = array("Militärdienst", "Militärdienst", "Ein geregelter Militärdienst ist ein erster und notwendiger Schritt zur Verteidigung deiner Kolonien. Die Erforschung bringt das grundlegende Wissen zum Rekrutieren von einfachen Infanteristen und zum Bau erster militärischer Anlagen.", 0, 100, 2);
        $this->Forschungen[2] = array("Handwerk", "Handwerk", "Eine solide handwerkliche Basis ist notwendig um erste Rüstungen zu fertigen und die Bauten deiner Kolonie gegen die Naturgewalten standhaft zu machen.", 0,100,2);
        $this->Forschungen[3] = array("Schmiedekunst", "Schmiedekunst", "Ohne eine perfektioniert Schmiedkunst gibt es keinerlei hochwertige Eisenwaren und damit auch keine Waffen.", 0,100,2);
        $this->Forschungen[4] = array("Alchemie", "Alchemie", "Das Verständnis grundlegender chemischer Reaktionen, wie z.B. der Verbrennung, erlaubt es unter anderem Treibladungen für Geschosse zu entwickeln.", 0, 100, 2);
        $this->Forschungen[5] = array("Militärischer Drill", "Militärischer Drill", "Strenger Gehorsam und unbedingter Aufopferungswillen für seine Heimat zeichnen einen guten Soldaten aus. Der militärische Drill erlaubt es diszipliniertere Einheiten auszubilden.", 0, 100, 2);
        $this->Forschungen[6] = array("Zunft", "Zunft","In Zünften organisiertes Handwerk bietet viele Vorteile. Einheitliche Produktionsverfahren steigern die Produktivität einzelner Handwerkszweige deutlich.", 0,100,2);
        $this->Forschungen[7] = array("Nieten und Stanzen", "Nieten und Stanzen", "Durch die Erfindung der Nieten gelingt es unterschiedliche Materialien miteinander zu verbinden, ohne diese wie bisher zu vernähen. So lassen sich auch starre Teile wie z.B. Eisenplatten an Lederwesten befestigen.", 0, 100, 2);
        $this->Forschungen[8] = array("Gusstechnik", "Gusstechnik", "Das Schmelzen von Blei wird hier gleich mit neuen Zündungsmethoden vereint. So kann man nun auf das langwierige Nachladeverfahren bisheriger Waffen verzichten und so die Feuergeschwindigeit erhöhen.", 0, 100, 2);
        $this->Forschungen[9] = array("Gilden", "Gilden", "Das Gildensystem verbessert den Warentransport und -austausch innerhalb der Kolonien. So kann nun besser abgeschätzt werden, welche Rohstoffe wo benötigt werden und alle überschüssigen Ressourcen einlagern.", 0, 100, 2);
        $this->Forschungen[10] = array("Magazinsystem", "Magazinsystem", "Bisherige Waffen müssen ihre Munition immer noch in einer Trommel speichern oder jeden Schuss extra zugeführt bekommen. Mit dem Magazinsystem ist es möglich eine deutlich größere Anzahl an Patronen in Reserve zu haben.", 0, 100, 2);
        $this->Forschungen[11] = array("Verteidigungsanlagen", "Verteidigungsanlagen", "Stationäre Einrichtungen die deine Kolonie beschützen können im Gefecht unter Umständen die entscheidende Rolle spielen. Das Erforschen dieser Technologie legt den Grundstein dazu.", 0, 100, 2);
        $this->Forschungen[12] = array("Gatling AG", "Gatling AG", "Nachdem es nun bereits gelungen ist, ausreichend Munition in Reserve zu halten, ist es das erklärte Ziel der Gatling -Forschungsgruppe eine Waffe zu bauen, die die Kugeln ebenso rasch zu verschießen vermag.", 0, 100, 2);
        $this->Forschungen[13] = array("Anorganische Chemie", "Anorganische Chemie", "Die konsequente Weiterentwicklung alchemistischer Anwandlungen für früher oder später auf den Pfad der Anorganischen Chemie, die im Prinzip nur bisherige Erkenntnisse neu strukturiert und damit den Weg für größere Waffen freigibt.", 0, 100, 2);
        $this->Forschungen[14] = array();
        $this->Forschungen[15] = array();
        
        //Kleinstadt-Forschungen
	    //$this->Forschungen[] = array("Terraner-Name", "Sub-Name", "Beschreibung", "ID_Rasse", "Kosten", "Zeit");
  		$this->Forschungen[16] = array("Heereswesen", "Heereswesen", "Die Präsenz neuer Truppentypen und Fahrzeuge auf dem Schlachtfeld machen eine andere Art der militärischen Führung und Ausstattung der Infanteristen notwendig.", 0, 150,2);
  		$this->Forschungen[17] = array("Freie Wirtschaft", "Freie Wirtschaft", "Wo andere Wirtschaftsformen versagen, setzt sich die Freie Wirtschaft durch. Sie fordert einen uneingeschränkten Wareaustausch und gnadenlos Konkurrenz. Damit kann die Produktivität nochmals enorm angehoben werden.", 0, 150,2);
  		$this->Forschungen[18] = array("Schnelllader", "Schnelllader", "Die Einführung von Schnellladern ermöglich es auch alte Waffen konkurrenzfähig zu halten. So können Revolver oder Kanonenrohre binnen Sekunden nachgeladen werden.", 0, 150,2);
  		$this->Forschungen[19] = array("Verbesserte Werkstoffe", "Verbesserte Werkstoffe", "Zwar ist Eisen ein elementarer Grundstoff der meisten Gebäude und doch ist er nicht unzerstörbar. Der wesentlich widerstandsfähigere und auch biegsamere Stahl hingegen hat diese Probleme nicht. Die Verbesserten Werkstoffe ermöglichen das Herstellen desselben.", 0, 150,2);
  		$this->Forschungen[20] = array("Petrochemie", "Petrochemie", "Ein weiterer Schritt im Verständnis des chemischen Verhaltens bestimmter Stoffe ist getan. So ist es nun möglich diverse Bestandteile des Erdöls von einander zu trennen und diese vereinzelt weiterzuverarbeiten.", 0, 150,2);
  		$this->Forschungen[21] = array("Industrielle Fertigung", "Industrielle Fertigung", "Da viele Bauteile oft in großen Mengen benötigt werden, um beispielsweise Waffen- oder Fahrzeugteile zu produzieren, wurde kurzerhand das Fließband erfunden. Für Fahrzeuge aller Art ist dies eine wichtige Vorraussetzung.", 0, 150,2);
  		$this->Forschungen[22] = array("Stellungskampf", "Stellungskampf", "Häufig hat es sich als zu verlustbringend herausgestellt, wenn man den Feind angreift. In diesem Fall ist es vielleicht sinnvoller auszuharren und die Schlacht hinauszuzögern.", 0, 150,2);
  		$this->Forschungen[23] = array("Scharniere", "Scharniere", "Indem man Kleinteile durch Scharniere miteinander verbindet, ermöglicht man es, ganze Ketten mit Patronen herzustellen. Dies löst das mittlerweile veraltetet Magazinsystem ab.", 0, 150,2);
  		$this->Forschungen[24] = array("Entdeckung von Titan", "Entdeckung von Titan", "Die Forscher können es kaum glauben, denn sie haben ein neues Element entdeckt. Im Moment zanken sie sich nur noch um den Namen...", 0, 150,2);
  		$this->Forschungen[25] = array("Textile Fasern", "Textile Fasern", "Stahl- und Eisenplatten liegen einem Soldaten im Einsatz doch recht schwer auf der Schulter. Stattdessen entscheiden sich die Forscher dieses Gebietes dazu, einen Körperschutz ganz ohne verstärkende aber schwere Platten zu realisieren.", 0, 150,2);
  		$this->Forschungen[26] = array("Verbrennung", "Verbrennung", "Die neuen großen Gefechtsmaschinen benötigen natürlich auch einen Antrieb, da sie viel zu schwer sind, um von Hand bewegt zu werden. Hier wird erforscht, ob sich raffiniertes Erdöl zur Verbrennung eignet.", 0, 150,2);
  		$this->Forschungen[27] = array("Splittergeschosse", "Splittergeschosse", "Große Bunker und Verteidigungstürme machen es dem Angreifer schwer. Umso wichtiger ist es, passende Munition zu haben, die Verteidigungsstellungen auch auf große Distanz wirksam beschädigen kann.", 0, 150,2);
  		$this->Forschungen[28] = array("Automatische Waffen", "Automatische Waffen", "Dank unserem Erfindungsgeist glauben die Forscher, es könnte möglich sein, Waffen zu bauen, die leicht sind und gleichzeitig sehr schnell feuern können.", 0, 150,2);
  		$this->Forschungen[29] = array("Gehärteter Stahl", "Gehärteter Stahl", "Eine Kombination aus verschiedenen Stählen ermöglich es Schutzplatten herzustellen, die deutlich mehr Projektile abfangen als andere Panzerungen.", 0, 150,2);
  		$this->Forschungen[30] = array("Klebstoffe", "Klebstoffe", "Bei der Veredelung von Rohöl fallen diverse Nebenprodukte an, die sich unter anderem dazu eignen Objekte oder Fasern zusammenzuhalten. Somit können äußerst stabile Verbindungen geschaffen werden.", 0, 150,2);
  		$this->Forschungen[31] = array("Berufssoldaten", "Berufssoldaten", "Wie jeder weiß, braucht es Zeit ein Heer zu mobilisieren. Dieses Problem kann umgangen werden, indem ein stehendes Heer gebildet wird, das jederzeit einsatzbereit ist. Dies erfordert einen vollständig anderen Typ Soldat als bisher.", 0, 150,2);
  		$this->Forschungen[32] = array("Durchschlagende Munition", "Durchschlagende Munition", "Die zunehmende Widerstandskraft moderner Panzerungen verlangt nicht nur nach größeren Waffen sondern auch besserer Munition, die in der Lage ist auch diese Schutzhüllen zu perforieren.", 0, 150,2);
  		$this->Forschungen[33] = array("Langrohr", "Langrohr", "Seit Kurzem ist es der metallverarbeitenden Industrie möglich längere und stabilere Geschützrohre als jemals zuvor zu produzieren. Dies ermöglich uns auch große oder entferntere Ziele effektiv zu bekämpfen.", 0, 150,2);
  		$this->Forschungen[34] = array("Kettenfahrzeuge", "Kettenfahrzeuge", "Während sich gewöhnliche Räder als wenig brauchbar für den Kampfeinsatz erweisen, sind Ketten geradezu ideal. Sie sind stabiler, schwerer zu treffen und außerdem total trendy unter Panzerkommandanten.", 0, 150,2);
  		$this->Forschungen[35] = array("Hartmetalllegierungen", "Hartmetalllegierungen", "Das ewige Wettrüsten macht auch und erst recht bei den Panzerungen nicht halt. Soeben haben Wissenschaftler entdeckt, dass es durchaus stabilisierend wirken kann, wenn man mehrere verschiedene Metalle kombiniert.", 0, 150,2);
  		$this->Forschungen[36] = array("Verbesserte Textilien", "Verbesserte Textilien", "Noch bis vor kurzen war es nur dicken und schweren Bleiwesten möglich eine Kugel am Eindringen zu hindern. Die Erfindung einer neuartigen Faser kombiniert mit speziellen Webetechniken machen das schwere Blei überflüssig.", 0, 150,2);
  		$this->Forschungen[37] = array("Bordwaffen", "Bordwaffen", "Die neuen, großen Kampffahrzeuge benötigen natürlich auch eigene Waffen. Schließlich kann man mit Handfeuerwaffen kaum etwas ausrichten. In diesem Forschungszweig wird intensiv nach derartigen Waffen geforscht.", 0, 150,2);
  		$this->Forschungen[38] = array("Explosive Gemische", "Explosive Gemische", "Eine einfache Verbrennung von Treibstoffen bringt leider kaum genug Kraft um die monströsen Kampfmaschinen zu bewegen. Eine Beschleunigung der Verbrennungsreaktion bringt könnte hier Abhilfe verschaffen.", 0, 150,2);
  		$this->Forschungen[39] = array("Panzerbrechende Munition", "Panzerbrechende Munition", "Im Gegensatz zu den Vorgängern können Panzerbrechende Geschosse durchaus auch eigentlich durschusssichere Hüllen und Westen durchschlagen. Der Trick ist hierbei meist ein schwerer und fester Munitionskern.", 0, 150,2);
  		$this->Forschungen[40] = array("Mittlere Bordwaffen", "Mittlere Bordwaffen", "Wie (fast) überall gilt auch hier: Je größer desto besser. Allerdings benötigt es einiges an Erfahrung und auch etwas Tüftelei die schwereren Bordwaffen geschickt in den Fahrzeugen zu verstauen.", 0, 150,2);
  		$this->Forschungen[41] = array("Schwere Kettenfahrzeuge", "Schwere Kettenfahrzeuge", "Vergrößert man die Wanne eines Fahrzeugs so müssen auch diverse andere Anpassungen an Antrieb, Turm und Ketten vorgenommen werden. Dies erfordert durchaus Zeit und Forschungsarbeit.", 0, 150,2);
  		$this->Forschungen[42] = array("Hochwertige Textilien", "Hochwertige Textilien", "Weitere Forschung auf dem Gebiet der Textile verspricht neue Werkstoffe und vor allem noch bessere Ausrüstung.", 0, 150,2);
  		$this->Forschungen[43] = array("Schwere Bordwaffen", "Schwere Bordwaffen", "Hierbei handelt es sich um die konsequente Fortsetzung der Waffenforschung noch schwererer Geschütze.", 0, 150,2);
  		$this->Forschungen[44] = array("Fernkampf", "Fernkampf", "Die jüngsten Entwicklungen der Kriegsgeräte lassen es zu, einen Gegner auch über große Distanzen unter Feuer zu nehmen. Dies kann sich insbesondere bei der Fahrzeugsbekämpfung als nützlich erweisen, da Fußsoldaten außer Reichweite der schweren Kolossen bleiben können.", 0, 150,2);
  		$this->Forschungen[45] = array();
		$this->Forschungen[46] = array();
		$this->Forschungen[47] = array();
		$this->Forschungen[48] = array();
		$this->Forschungen[49] = array();
		$this->Forschungen[50] = array();
		
			
		//Stadt-Forschungen
	    //$this->Forschungen[] = array("Terraner-Name", "Sub-Name", "Beschreibung", "ID_Rasse", "Kosten", "Zeit");
		$this->Forschungen[51] = array("Blitzkrieg", "Blitzkrieg", "Seine Gegner zu überraschen stellt eine elementare Kriegstaktik dar. Um dies endlich auch mit Fußsoldaten zu ermöglichen, wird schon seit langem nach einem adäquaten Transportmittel für selbige geforscht.", 0, 200,2);
		$this->Forschungen[52] = array("Explosive Geschosse","Explosive Geschosse","Einen vorläufigen Höhepunkt bei der Geschoss- und Munitionsentwicklung stellen Explosive Geschosse dar. Sie schädigen den Gegner nicht nur durch den Aufprall sondern zusätzlich durch eine oder mehrere Explosionen.",0,200,2);
		$this->Forschungen[53] = array("Großkaliber Automatikwaffen","Großkaliber Automatikwaffen","Inzwischen ist man schon soweit, dass Projektile von der Größe ganzer Granaten in großen, aber automatischen Waffen verschossen werden können. Zumindest theoretisch. An der Praxis wird hier noch gefeilt.",0,200,2);
		$this->Forschungen[54] = array("Großkampfwaffen","Großkampfwaffen","Um in einer langen, nerven-& materialaufreibenden Schlacht bestehen zu können benötigt man die besten und mächtigsten Waffen. An der Erfindung und Herstellung solcher Waffen wird hier intensiv geforscht.",0,200,2);
		$this->Forschungen[55] = array("Entdeckung von Uran","Entdeckung von Uran","Einigen Gerüchten zufolge soll ein bisher unbekanntes Elemente entdeckt worden sein, dass lichtempfindliche Fotoplatten ohne direkten Kontakt schwärzen kann. Jeder deiner Wissenschaftler will als erster den entscheidenden Fund machen.",0,200,2);
		$this->Forschungen[56] = array("Extrafeste Materialien","Extrafeste Materialien","Wem Stabiles nicht ausreicht und er noch härtere und durchschlagsfähigere Dinge sucht, der sollte sich einmal hier versuchen. Erste Versuche versprechen große Erfolge.",0,200,2);
		$this->Forschungen[57] = array("Verbundfasern","Verbundfasern","Statt wie bisher nur einfach gewöhnliche Fasern in bestimmten Mustern zu verweben versucht man sich hier mit Faserkombinationen in verschiedene Webschichten, aus verschiedenen Rohmaterialien. Der moderne Personenschutz verspricht sich viel hiervon.",0,200,2);			$this->Forschungen[58] = array("Elektrik","Elektrik","Zwar ist die Elektrizität schon eine Weile bekannt, aber dennoch fehlen bisher brauchbare Anwendungsmöglichkeiten. Eine junge Forschergruppe strotz geradezu voller Ideen. Sie will die elektrische Kraft vor allem in mechanische Arbeit umsetzen.",0,200,2);
		$this->Forschungen[59] = array("Börsensystem","Börsensystem","Auch wenn es auf den ersten Blick nicht so aussehen mag, erfordert ein gut funktionierendes Wirtschafts- und Handelssystem eine ganze Menge Planung. Hier zu investieren kann niemals verkehrt sein.",0,200,2);
		$this->Forschungen[60] = array("Kampfroboter","Kampfroboter","Mächtige Maschinen aus Stahl, bis an die Zähne bewaffnet und unbesiegbar auf dem Schlachtfeld. So oder so ähnlich schwebt es deinen Forschern vor. Wer würde angesichts derartiger Hoffnungen keine Gelder hierfür bewilligen ?",0,200,2);
		$this->Forschungen[61] = array("Moderne Infanteriewaffen","Moderne Infanteriewaffen","Leichter, mobiler und tödlicher sollen sie werden, die Handfeuerwaffen der Fußtruppen. Dazu muss allerdings erst einmal viel in die Entwicklung gesteckt werden.",0,200,2);
		$this->Forschungen[62] = array("Durchschlagende Mechwaffen","Durchschlagende Mechwaffen","Mechs sind hervorragende Anti-Infanteriewaffen, allerdings erleiden sie hohe Verluste gegen Panzerdivisionen. Diese Forschung kann helfen, das Ungleichgewicht einzudämmen.",0,200,2);
		$this->Forschungen[63] = array("Strahlungsschutz","Strahlungsschutz","Wie sich schnell herausstellt sind einige neu entdeckte Elemente radioaktiv und können den menschlichen Organismus schädigen. Die verängstigten Bewohner wollen daher ihre Dinge nicht mehr neben diesen Elementen lagern und fordern einen sicheren Schutz.",0,200,2);
		$this->Forschungen[64] = array("Edelmetall- & Edelsteinverwertung","Edelmetall- & Edelsteinverwertung","Bis vor kurzem waren seltene Materialien wie Gold oder Diamant nur bei eitlen Einwohnern aller Provinzen begehrt. Nun aber verlangt auch die Wissenschaft danach, denn ihre besonderen chemischen Eigenschaften ermöglichen spezielle hochtechnische Bauteile.",0,200,2);
		$this->Forschungen[65] = array("Maschinenbau","Maschinenbau","Maschinen sind aus der heutigen Industrie kaum mehr wegzudenken. Von Verbesserungen bei der Maschinenproduktion profitieren hauptsächlich Fabriken, die mit besonders schweren Bauteilen, wie z.B. Stahlrümpfe, arbeiten müssen.",0,200,2);
		$this->Forschungen[66] = array("Adaptive Fasern","Adaptive Fasern","Einige Wissenschaftler sagen, Kunststoffe können nach einer Deformation wieder in ihre Ausgangsform springen. Geplant sind Schutzwesten, die Projektile abfangen und sich danach wieder zurückbiegen, als wäre die Weste nie getroffen worden.",0,200,2);
		$this->Forschungen[67] = array("Alternative Energiekonzepte","Alternative Energiekonzepte","Unter wie über der Erde sammeln sich fortwährend mehr und mehr Müll und Abfallprodukte an. Daher wird intensiv nach umweltfreundlichen Methoden zu Energiegewinnung und Verarbeitung gesucht.",0,200,2);
		$this->Forschungen[68] = array("Elektronik","Elektronik","Die Entdeckung von Halbleitern erlaubt die Herstellung und Verschaltung elektronischer Bauteile. Bei richtiger Verarbeitung entstehen dabei komplexe und durchaus schnelle Rechenmaschinen.",0,200,2);
		$this->Forschungen[69] = array("Künstliche Intelligenz","Künstliche Intelligenz","Wie verrückt versuchen Scharen von Forschern einen Computer zu erschaffen, der intelligent und selbstständig handelt. Zwar ist dieses Vorhaben beinahe aussichtslos, aber es fallen viele zumindest intelligent erscheinende Maschinen dabei ab.",0,200,2);
		$this->Forschungen[70] = array("Kohärenz","Kohärenz","Das physikalische Prinzip der Kohärenz ermöglicht es einige spezielle Effekte zu erzeugen. Laser sind berühmt für ihre Kohärenz.",0,200,2);
		$this->Forschungen[71] = array("Maschinelle Verarbeitung","Maschinelle Verarbeitung","Würde es gelingen ganze Produktionsketten mit Maschinen statt mit Menschen zu betreiben könnten erheblich Kosten gespart und die Geschwindigkeit vieler Arbeits- und Förderschritte erhöht werden.",0,200,2);
		$this->Forschungen[72] = array("Waffenfähiges Uran","Waffenfähiges Uran","In diesem Forschungszweig versuchen Forscher ein bestimmtes Uranisotop zu isolieren das nur schwach strahlt. Dieses zwar kaum radioaktive aber dennoch schwere Isotop kann dann beispielsweise in panzerbrechenden Projektilen zum Einsatz kommen.",0,200,2);
		$this->Forschungen[73] = array("Große Kampfroboter","Große Kampfroboter","Auf dem Schlachtfeld haben sich Mechs als äußerst effektiv erwiesen. Es wird daher Zeit eine neue, noch stärkere Generation dieser Killermaschinen zu entwickeln.",0,200,2);
		$this->Forschungen[74] = array("Leichte Mechpanzerungen","Leichte Mechpanzerungen","Genau wie Panzer benötigen auch Mechs einen gewissen physischen Schutz um die Attacken ihrer Feinde zu überdauern. Hier wird in dieser Richtung geforscht.",0,200,2);
		$this->Forschungen[75] = array("Infanteriestellungen","Infanteriestellungen","Die schlechte Position von Fußsoldaten bei Abwehrschlachten verlangt nach Erfindungsgeist. Mit Hochdruck wird nach einem optimierten Grabensystem zu Verteidigungszwecken gesucht.",0,200,2);
		$this->Forschungen[76] = array("Soldatenstopp","Soldatenstopp","-----",0,200,2);
		$this->Forschungen[77] = array("Ultraleichte Munition","Ultraleichte Munition","Statt wie bisher massive Projektile zu verfeuern, setzen einige Wissenschaftler darauf, lieber viele kleine, spitze und vergiftete Metallpfeile zu verschießen. Zwar trifft nicht jeder, aber sobald einer die Rüstung durchdrungen hat, ist das Opfer binnen Sekunden tot.",0,200,2);
		$this->Forschungen[78] = array("Energiespeicher","Energiespeicher","Energiespeicher aller sind ein radikaler Fortschritt in der Wissenschaft. Zwar können selbst die derzeit besten Speicher nicht unbegrenzt Energie aufnehmen, aber für viele Anwendungen reicht die begrenzte Kapazität der Speicher vollkommen aus.",0,200,2);			
		$this->Forschungen[79] = array("Extreme Kohärenz","Extreme Kohärenz","Indem die Kohärenzlänge der Laserapparaturen erhöht wird können in größeren Entfernungen noch Kohärenzerscheinungen beobachtet werden. Dies verbessert die Forschungsmöglichkeiten im Laserbereich.",0,200,2);
		$this->Forschungen[80] = array("Intelligente Rüstung","Intelligente Rüstung","Die Überlebensfähigkeit eines Soldaten lässt sich deutlich steigern, wenn Sensoren und Kommunikationsgeräte in die Rüstung integriert werden.",0,200,2);
		$this->Forschungen[81] = array("Energieschutz","Energieschutz","Seit der Entwicklung von Energie- und Strahlenwaffen wird mit Hochdruck nach Möglichkeiten gesucht, um sich vor ihnen zu schützen. Erste Versuche waren zwar erfolgreich, zeigen aber auch, dass Fußsoldaten in nächster Zeit wahrscheinlich nicht in den Genuss dieser Panzerungen kommen werden, da sie schlichtweg zu schwer sind.",0,200,2);
		$this->Forschungen[82] = array("Tiefkalte Gase","Tiefkalte Gase","Um in einer trockenen Welt wie dieser an Wasserstoff heranzukommen benötigt man spezielle Verfahren, um ihn aus der Atmosphäre herauszufiltern. Dazu wird die Umgebungsluft verflüssigt und nur der begehrte Wasserstoff bleibt übrig.",0,200,2);
		$this->Forschungen[83] = array("Stabilere Bauten","Stabilere Bauten","Der Expansionsdruck in den Kolonien erzwingt das Forschen nach besseren Materialien und Bauarten, um höhere und größere Gebäude zu erschaffen. Dies nützt auch den Architekten, die unterirdische Sicherheitsräume gestalten.",0,200,2);
		$this->Forschungen[84] = array("Verbesserter Ballistischer Schutz","Verbesserter Ballistischer Schutz","Moderne Panzerabwehrgeschosse schlagen nicht nur mit ihrer reinen Aufprallswucht auf die Panzerung, sondern erzeugen vor dem Aufprall einen brennenden Plasmastrahl, der sich durch die Panzerung brennen soll. Natürlich wird alles versucht, um einen Schutz davor zu entwickeln.",0,200,2);
		$this->Forschungen[85] = array("Überregionales Management","Überregionales Management","Mehrere Kolonien zu verwalten bringt einen enormen Verwaltungsaufwand mit sich. Hier arbeiten die klügsten Köpfe zusammen, um im Voraus alles zu bedenken, was bei einer weiteren Kolonie bedacht werden muss.",0,200,2);
		$this->Forschungen[86] = array("Intermetallische Legierungen","Intermetallische Legierungen","Die derzeitige Hoffnung im Bereich Fahrzeugforschung ist eine neue Art, Panzerungen zu vereinen. Mehrere Metallschichten verschmelzen dabei zu besonderen Legierungen, die deutlich länger schwerem Beschuss standhalten.",0,200,2);
		$this->Forschungen[87] = array("Verbesserte Wärmeabfuhr","Verbesserte Wärmeabfuhr","Nicht nur für Panzerungen ist eine effektive Wärmeabfuhr von Nöten. Die meisten modernen Motoren und Antriebe produzieren derart viel Wärme, dass sie ohne ein funktionierendes Kühlsystem binnen kürzester Zeit überhitzen würden.",0,200,2);			$this->Forschungen[88] = array("Hochwertige Verbrennung","Hochwertige Verbrennung","Bei den meisten Verbrennungsvorgängen wird das gewünschte Gemisch nicht vollständig verbrannt und es verbleiben Rückstände im Verbrennungsraum. Durch gezieltes Forschen ließen sich diese Rückstände eventuell verringern und so die Motorleistung erheblich verbessern. Auch Raketentriebwerke würden hiervon profitieren.",0,200,2);
		$this->Forschungen[89] = array("Mobile Lasertechnik","Mobile Lasertechnik","Die extrem sperrigen Bauteile, die eine Laserwaffe derzeit noch benötigt ließen sich durch konsequente Arbeit durchaus verkleinern. Eine Anwendung für die schwächer konstruierten Mechs wäre schon bald möglich. ",0,200,2);
		$this->Forschungen[90] = array("Tragbare Laser","Tragbare Laser","Laserwaffen sind noch immer viel zu schwer für einen Fußsoldaten. Jedoch gibt ein richtiger Tüftler nicht so schnell auf und hat stets die Hoffnung auf Erfolg.",0,200,2);
		$this->Forschungen[91] = array("Technikboom","Technikboom","Die rasanten Durchbrüche auf allen Gebieten der Wissenschaft sorgen für den nötigen Schwung beim Erforschen komplizierter Vorgänge. Vor allem das Militär treibt die Forschung stark voran: Im Häuserkampf scheint es noch große Defizite zu geben.",0,200,2);
		$this->Forschungen[92] = array();
		$this->Forschungen[93] = array();
		$this->Forschungen[94] = array();
		$this->Forschungen[95] = array();
				
		//Grossstadt-Forschungen
	    //$this->Forschungen[] = array("Terraner-Name", "Sub-Name", "Beschreibung", "ID_Rasse", "Kosten", "Zeit");
		$this->Forschungen[96] = array("Psychotraining","Psychotraining","Wie sich herausgestellt hat, kann die Leistung eines Soldaten oder eines ganzen Teams durch psychologische Schulung und Konditionierung deutlich gesteigert werden. Dieses Wissen könnte in Ausbildungszentren zu unseren Gunsten genutzt werden.",0,250,2);				$this->Forschungen[97] = array("Kernschmelzensichere Gebäude","Kernschmelzensichere Gebäude","Eine atomare Explosion ist das derzeit schlimmstmögliche Szenario das eintreten könnte. Um sich vor derartigen Katastrophen zu schützen wird fieberhaft an einem Schutzraum entwickelt. Dies könnte uns auch die bisher unsichere Kernspaltungsenergie verfügbar machen.",0,250,2);
		$this->Forschungen[98] = array("Höhere Projektilmechanik","Projektilmechanik","Die Kunst perfekte Geschosse herzustellen, die eine besonders stabile Flugbahn und gleichzeitig effektiv gegen Feinde aller Art vorgeht erfordert eine ganze Menge Forschungsarbeit.",0,250,2);
		$this->Forschungen[99] = array("Hohlladungsschutz","Hohlladungsschutz","In der Hoffnung es könnte eines Tages einen effektiven Schutz vor Hohlladungssprengkörpern geben wird unter großen Anstrengungen nach einem derartigen Schutz gesucht. Leider ist hierzu ein erheblicher Materialaufwand nötig.",0,250,2);
		$this->Forschungen[100] = array("Höchsteffiziente Wärmeleitung","Höchsteffiziente Wärmeleitung","Nach neusten Wissenschaftlichen Erkenntnissen soll es möglich sein, verschiedene extrem gute Wärmeleiter in einer gel-artigen Substanz zu vereinen. Mit dieser Substanz wäre es möglich sehr heiße Bauteile zu kühlen.",0,250,2);
		$this->Forschungen[101] = array("Durchschlagsichere Westen","Durchschlagssichere Westen","Besondere Aufmerksamkeit sollte dieser Theorie gewidmet werden. Dabei gehen führende Forscher davon aus, dass eine absolut undurchdringliche Faserschicht möglich wäre. Leider können damit keine Schusstraumata verhindert werden, da diese Schicht sehr elastisch ist.",0,250,2);
		$this->Forschungen[102] = array("Kalte Fusion","Kalte Fusion","Seit langer Zeit gibt es versuche, bei niedrigen Temperaturen Atome verschmelzen zu lassen und die auf kleinem Raum entstehenden Leistung für Energiezellen oder Antriebe zu nutzen.",0,250,2);
		$this->Forschungen[103] = array("KI-Verwaltete Planwirtschaft","KI-Verwaltete Planwirtschaft","Die Planwirtschaft hat bekanntlich keinen guten Ruf, da ihr fehlerhafte und ungenaue Berechnungen zu Grunde liegen. Ein neues Ansatz mit einem extrem präzisen und hochintelligenten Großrechner könnte diesen Mangel eventuell kompensieren und somit die wirtschaftliche Leistungsfähigkeit erhöhen.",0,250,2);
		$this->Forschungen[104] = array("Perfektionierte Linsen","Perfektionierte Linsen","Zwar liefern moderne Lasergeräte inzwischen äußerst kohärentes Licht, jedoch wird diese Kohärenz durch die Linsen, die der Strahl passieren muss wieder teilweise verschlechtert. Eine perfekte Linse ohne jeden Fehler würde hier sicher weiterhelfen.  ",0,250,2);
		$this->Forschungen[105] = array("Radikale Defensivmaßnahmen","Radikale Defensivmaßnahmen","Da im Krieg ja bekanntlich alles erlaubt ist, und man jede Chance nützen sollte, um sich vor seinen Feinden zu schützen, wird hier unter anderem nach wirkungsvollen, wenn auch heimtückischen Waffen gegen Angreifer gesucht.",0,250,2);
		$this->Forschungen[106] = array("Radioaktive Abfallbeseitigung","Radioaktive Abfallbeseitigung","Der Beseitigung radioaktiver Abfälle kommt eine besonders große Rolle zu. Da aber die wirklich sicheren Lagerstätten sehr begrenzt sind, und einmal produzierter Abfall nicht mehr verschwindet wird hier über andere Möglichkeiten der Entsorgung oder Weiterverwertung nachgedacht.",0,250,2);
		$this->Forschungen[107] = array("","","-----",0,250,2);
		$this->Forschungen[108] = array("Großkaliber Deluxe","Großkaliber Deluxe","Ein wahrer General strebt stets danach die besten und größten Waffen auf seiner Seite des Schlachtfeldes zu sehen. ",0,250,2);
		$this->Forschungen[109] = array("Autokonjunktive Fasern","Autokonjunktive Fasern","Der Stoff aus dem diese Fasern sind reagiert auf Druck von außen, indem er verklumpt. Richtig angeordnet könnte man hiermit möglicherweise leichte Schutzwesten herstellen, die bei Beschuss kurzfristig hart wie Stahl werden.",0,250,2);
		$this->Forschungen[110] = array("Autonome Fahrzeuge","Autonome Fahrzeuge","Ein Kampfeinsatz ist stets gefährlich für Menschen. Könnten man Fahrzeuge komplett ohne Besatzung aus der Ferne zuverlässig steuern, ließe sich die Gefahr für den Menschen reduzieren. Natürlich benötigen derartige Fahrzeuge noch mehr technisches Personal statt der Besatzung.",0,250,2);
		$this->Forschungen[111] = array("Akkustische Bündel","Akkustische Bündel","Durch eine geschickte Anordnung gelang es erstmals Schallwellen zu bündeln. Experten glauben, dass sich der Bündelungsgrad auch durchaus noch steigern lässt und dabei Wellen mit besonders hohem Schalldruck entstünden.",0,250,2);
		$this->Forschungen[112] = array("Hochfrequente Laserstrahlen","Hochfrequente Laserstrahlen","Je höher die Frequenz einer Welle desto mehr Energie beherbergt sie. Auf Basis dieses Wissens versucht man die Wellenlängen aller Laser zu verkürzen um somit noch mehr Zerstörungskraft zu erhalten.",0,250,2);
		$this->Forschungen[113] = array("Wirbelfreier Fluss","Wirbelfreier Fluss","Ließe sich ein Körper so formen, dass er keinerlei Wirbel erzeugt, wenn er durch Wasser oder Luft fliegt, so könnte er deutlich weiter fliegen, als bisherige Flugkörper es können. Dies wäre vor allem für Kampfgeschosse von besonderer Bedeutung.",0,250,2);
		$this->Forschungen[114] = array("Femtoverknüpfung","Femtoverknüpfung","Eine Verbindung von Elementen auf atomarer statt molekularer Ebene würde enorme Stabilität bewirken. Eine derart hergestellte Platte wäre äußerst schwergewichtig aber gleichzeitig fast undurchschlagbar.",0,250,2);
		$this->Forschungen[115] = array("Genetische Variation","Genetische Variation","Seit langem schon träumt der Mensch davon, sich selbst zu verbessern. Frische Ideen aus einigen Labors könnten hierbei nun zum Durchbruch verhelfen um das menschliche Erbgut endlich perfekt zu machen.",0,250,2);
		$this->Forschungen[116] = array("Komprimierte Reaktoren","Komprimierte Reaktoren","Atomreaktoren sind leider äußerst unhandlich. Es wäre ein immenser Vorteil, ließen sie sich etwas verkleinern und vielleicht sogar als Antriebsaggregat nutzen.",0,250,2);
		$this->Forschungen[117] = array("Wärmeisolieranzüge","Wärmeisolieranzüge","Einige Gerätschaften und Waffen sind leider bisher viel zu heiß, um sie von Personen tragen zu lassen. Ein Anzug, der keinerlei Wärme durchlässt wäre optimal und zukünftig auch Fußsoldaten mit heißen Geräten zu versehen.",0,250,2);
		$this->Forschungen[118] = array("Einschlussfelder","Einschlussfelder","Durch das Entdecken der Ringfalle ist es möglich geworden einzelne oder ganze Haufen an geladenen Teilchen zu speichern. So können äußerst große Mengen an Energie auf kleinstem Raum gehalten werden.",0,250,2);
		$this->Forschungen[119] = array("Biologische Module","Biologische Module","Herkömmliche CPUs sind trotz mittlerweile extrem vieler Transistoren auf kleinstem Raum nicht ausreichend Leistungsfähig um die komplexen Geh-Berechungen für mehrere Beinpaare auszurechnen. Biologische Rechner könnten das eventuell schaffen.",0,250,2);
		$this->Forschungen[120] = array("Alpha-Stabilisierung","Alpha-Stabilisierung","Die Stabilisierung und Destabilisierung eines oder besser vieler Atomkerne würde es ermöglichen in großem Maße Ionenstrahlen zu emittieren. Auf kurze Distanz kann diese sehr gefährlich sein.",0,250,2);
		$this->Forschungen[121] = array("Naniten","Naniten","Winzig kleine Arbeitsroboter, um ein Vielfaches kleiner als ein Haar, eigenen sich hervorragend um beschädigte Strukturen von Innen heraus zu reparieren oder gar erst herzustellen. In Menschen oder Tieren werden sind sie ungefährlich, da sie von der Immunabwehr zerstört werden.",0,250,2);
		$this->Forschungen[122] = array("Schwere Raketenantriebe","Schwere Raketenantriebe","Besonders große Flugkörper benötigen auch einen besonders großen Antrieb der sie in die Lüfte hebt. Dies trifft natürlich auch auf Raketen zu, weswegen auch hier eifrig geforscht wird.",0,250,2);				
		$this->Forschungen[123] = array("Feldimplosionstheorie","Feldimplosionstheorie","Ein elektrisches Feld kann so stark überladen werden, dass es in sich selbst zusammenfällt. In dieser elektrischen Singularität ist dann eine sehr große Menge Energie auf kleinstem Raum gespeichert und kann mit etwas Raffinesse auch abgerufen werden.",0,250,2);
		$this->Forschungen[124] = array("Schwache Abschirmfelder","Schwache Abschirmfelder","Im Gegensatz zu Materie kann Strahlung durch ein Gegenfeld abgeschirmt werden. Ein entsprechender Feldgenerator ist allerdings äußerst kompliziert zu bauen.",0,250,2);
		$this->Forschungen[125] = array("Schlachtroboter","Schlachtroboter","Auf dem Schlachtfeld von heute sind große und schwer gepanzerte Kampfroboter ein absolutes Muss, nicht zuletzt um gegen die immer stärker werdende Infanterie adäquat vorgehen zu können.",0,250,2);
		$this->Forschungen[126] = array("Eta-Stabilisierung","Eta-Stabilisierung","Kerne lassen sich möglicherweise auch so manipulieren, dass sie mehrere Neutronen abgeben. Eines wird dabei auf extrem gefährliche Geschwindigkeiten gebracht während die anderen sich auflösen um die nötige Energie herbeizuschaffen.",0,250,2);
		$this->Forschungen[127] = array("Zivile Partikelnutzung","Zivile Partikelnutzung","Beschleunigte Teilchen lassen sich statt für Waffen auch für den Antrieb nutzen. Zwar ist der einzelne Rückstoß recht gering aber wenn die entsprechende Masse verwenden wird, kann durchaus auch ein Panzer bewegt werden.",0,250,2);
		$this->Forschungen[128] = array("Lichtschnelle Schalter","Lichtschnelle Schalter","Jede CPU, welcher Bauart sie auch sei, ist durch die Schaltzeit ihrer Bauteile ausgebremst. Ließen sich fast lichtschnelle Schalter erfinden, so entspräche dies einem enormen Wissensanstieg und würde alle Wissenschaften beflügeln.",0,250,2);
		$this->Forschungen[129] = array("Abfangmodule","Abfangmodule","Konventionelle Panzerungen benötigen dringend zusätzliche Abwehrmaßnahmen um gegen Hightechwaffen zu bestehen. Eine Möglichkeit scheint es, die Panzerplatten mit kleinen Modulen zu bestücken, die dem Projektil vor dem eigentlichen Aufprall entgegengeschleudert werden.",0,250,2);
		$this->Forschungen[130] = array("Quantenwandler","Quantenwandler","Eines der vorrangigsten Ziele ist es gewöhnlich Materie wie ein Butterbrot in eine andere gewünschte Form zu bringen. Dies birgt vor allem für Antriebsforschung große Hoffnung.",0,250,2);
		$this->Forschungen[131] = array("Streufeldmaximierung","Streufeldmaximierung","Um eine maximale Abdeckung bei Streuvorgängen zu erhalten wird heutzutage ein Streukraftfeld benutzt. Jedoch ist die Technik noch unausgereift und Unfälle mit schlecht verteilten Granaten sind an der Tagesordnung.",0,250,2);
		$this->Forschungen[132] = array("Starke Abschirmfelder","Starke Abschirmfelder","Je stärker ein Abschirmfeld desto mehr Energie kann es natürlich auch abwehren. Leider ist man derzeit nicht unbegrenzt was die Feldstärken angeht. Forschen ist angesagt.",0,250,2);
		$this->Forschungen[133] = array();	
		$this->Forschungen[134] = array();
		$this->Forschungen[135] = array();
		$this->Forschungen[136] = array();
		$this->Forschungen[137] = array();
				
		//Metropole-Forschungen
       	//$this->Forschungen[] = array("Terraner-Name", "Sub-Name", "Beschreibung", "ID_Rasse", "Kosten", "Zeit");
		$this->Forschungen[138] = array("Humanrobotik","Humanrobotik","Genetisch verbesserte Lebewesen sind zwar schon ganz brauchbare Kämpfer, aber in Kombination mit maschinellen Bauteilen ließe sich eine nahezu unaufhaltbare Kampfmaschine züchten.",0,300,2);
		$this->Forschungen[139] = array("Müllrückführung","Müllrückführung","Der wirtschaftliche Verlust durch Verpackungsmaterial ist auf ein Jahr hochgerechnet enorm. Die Müllrückführung, auch unter Recycling bekannt, kann diese Verluste eindämmen und somit das wirtschaftliche Vermögen der Kolonien steigern.",0,300,2);
		$this->Forschungen[140] = array("Quarksverbrenner","Quarksverbrenner","Die Bausteine der Materie sind eigentlich unmöglich zu trennen geschweige denn aufzulösen. Gelänge es sie in nutzbare Energie zu verwandeln und aus einem handlichen Reaktor zu gewinnen, wäre ein erstklassige Energiequelle geschaffen.",0,300,2);
		$this->Forschungen[141] = array("Parallelzielverfolgung","Parallelzielverfolgung","Dank vorangegangener Entwicklungen in der Computerwissenschaft können nun mehrere Ziele erfasst und verfolgt werden. Derzeit können mindestes 50 Zieldaten gleichzeitig ausgewertet werden. Von mehreren tausend träumen die Forscher hier.",0,300,2);
		$this->Forschungen[142] = array("","","",0,300,2);
		$this->Forschungen[143] = array("Westenairbags","Westenairbags","Sogar hochmoderne Schutzwesten können die Geschossenergie nicht vollständig auffangen und so sind stumpfe Verletzungen bei Beschuss noch immer Alltag. Integrierte Lufttaschen sollen die Aufprallwucht nun über die gesamte Weste verteilen und so den Soldaten unverwundbar machen - zumindest im Bereich der Schutzweste.",0,300,2);
		$this->Forschungen[144] = array("Fernfeldverständnis","Fernfeldverständnis","Das perfekte und exakte Verständnis des elektromagnetischen Fernfeldes bei Beugungen an Kanten ist elementar um eine Rakete mit minimaler Radarstrahlstärke genau ins Ziel zu lenken. ",0,300,2);
		$this->Forschungen[145] = array("Feldlinienkohäsion","Feldlinienkohäsion","Schafft man es die elektrischen Feldlinien einer Quelle so dicht zueinander zu bringen, dass sie sich vereinigen, so entsteht ein elektromagnetisch nicht zu durchdringendes Feld. Vielleicht ließe sich das für Abschirmfelder verwenden.",0,300,2);
		$this->Forschungen[146] = array("Bionik","Bionik","Obwohl der Mensch der Natur in vielen Dingen überlegen ist, gibt es doch auch noch viele Beispiele die zeigen, wie ausgefeilt Mutter Natur doch ist. Statt das Rad also neu zu erfinden sucht man lieber die Bewegungen und Strukturen der Natur nachzuahmen.",0,300,2);
		$this->Forschungen[147] = array("Leptonenadiabatik","Leptonenadiabatik","In diese spezielle Theorie der hochmodernen Physik einzusteigen würde mehrere Semester Studium erfordern. Daher sei nur kurz erwähnt, dass sich mit den hieraus resultierenden Techniken furchtbare Waffen formen lassen.",0,300,2);
		$this->Forschungen[148] = array("Potenzialvernichtung","Potenzialvernichtung","Die Erdanziehung ist uns allen bisweilen ein Dorn im Auge. Daher wird mit viel Sorgfalt an einer Technologie geforscht, die die Erdanziehung zumindest lokal aufheben kann. Damit ließen sich schwebende Fahrzeuge erschaffen.",0,300,2);
		$this->Forschungen[149] = array("Strahlungspaketbündelung","Strahlungspaketbündelung","Lichtwellen kommen leider bisher immer noch in getrennten einzelnen Strahlungspaketen an. Eine Bündelung dieser Energiepakete würde die Leistungsfähigkeit von Strahlenwaffen bedeutsam erhöhen.",0,300,2);
		$this->Forschungen[150] = array("Magnetische Überlagerung","Magnetische Überlagerung","Schafft man es, mit Magnetfeldern das magnetische Moment in Materie so zu überlagern, dass diese sich nicht mehr zusammenhalten kann, so ließe sich damit mit Sicherheit eine sehr gefährliche Waffe erschaffen.",0,300,2);
		$this->Forschungen[151] = array("Nahfeldextrapolation","Nahfeldextrapolation","Auf Basis der Fernfeld-Erfahrungen müsste es derzeit möglich sein, auch Rückschlüsse auf sehr sehr nahe Bereiche zu ziehen. Dies ist nötig, um kleinste Schwankungen in der Umgebung ausfindig zu machen und damit Feinde besser entdecken zu können. Für Raketenwaffen ist dies besonders wichtig.",0,300,2);
		$this->Forschungen[152] = array("Extensionsstabiles Material","Extensionsstabiles Material","Früher hatten Materialien aller Art das Problem, dass sie sich bei Temperaturschwankungen ausdehnten oder zusammenzogen. Nun kann dies unterbunden werden und somit werden sehr große Strukturen ermöglicht.",0,300,2);
		$this->Forschungen[153] = array("Fermionenkorroidale","Fermionenkorroidale","Nach neuesten Erkenntnissen baut sich die gesamte Materie aus Fermionen auf. Ein wirres und glühend heißes Gemisch aus den verschiedenen Bausteinen, wird auch als Plasma bezeichnet. Damit lassen sich brandneue Waffen erdenken.",0,300,2);					$this->Forschungen[154] = array("Reorganisierende Materie","Reorganisierende Materie","Was bisher einigen Kunstoffen vorbehalten war, lässt sich nun auch auf anderes Material übertragen. So können sich inzwischen verforme Metallplatten von selbst vollständig zurückbiegen. Dies ist vor allem für Panzerungen eine interessante Eigenschaft.",0,300,2);
		$this->Forschungen[155] = array("Antimateriespeicherung","Antimateriespeicherung","Einschlussfelder sind zwar äußerst nützlich, allerdings lassen sich dort nicht ohne Weiteres gefährliche Antimaterieteilchen speichern. Es sind viele Modifikationen nötig um dies zu ermöglichen und die gespeicherte Energiemenge noch weiter zu steigern.",0,300,2);
		$this->Forschungen[156] = array("Materieresonanz","Materieresonanz","Kann die Resonanzfrequenz in Materiebindungen gefunden und angeregt werden, so hat dies ein Explodieren selbiger zur Folge. Dies lässt sich sowohl für den Angriff als auch zur Verteidigung nutzen.",0,300,2);
		$this->Forschungen[157] = array("Duromere","Duromere","Die Entwicklung organischer Stoffe, die sich nach ihrer Aushärtung weder schmelzen noch verformen lassen bringt einen enormen Fortschritt in den Materialwissenschaften und dem Personenschutz.",0,300,2);
		$this->Forschungen[158] = array("Energiedichtenvariation","Energiedichtenvariation","Variiert man die elektrische Felddichte in der Flugbahn eines Plasmastrahls, so weitet sich dieser kontrolliert auf. Mit etwas Geschick kann der Plasmastrahl sehr stark verbreitert werden und ist so noch gefährlicher für alles, was ihm im Weg steht.",0,300,2);
		$this->Forschungen[159] = array("Magnetische Ladungsträger","Magnetische Ladungsträger","Seit langem schon sind die elektrischen Ladungsträger bekannt. Jedoch konnten bisher keine magnetischen Ladungsträger nachgewiesen werden. Bis jetzt. Dies ist ein unglaublicher Fortschritt der vor allem für Strahlwaffen genutzt werden könnte.",0,300,2);
		$this->Forschungen[160] = array("Räumliche Krümmung","Räumliche Krümmung","Früher glaubte man, dass der Raum und die Zeit fest und unveränderlich seien. Aber dem ist bei weitem nicht so. Der Raum lässt sich mit ausreichend Energie krümmen und verzerren. Wofür dies allerdings gut sein soll, weiß man noch nicht so genau.",0,300,2);
		$this->Forschungen[161] = array("Kontrollierte Kernfusion","Kontrollierte Kernfusion","In Sternen findet die Kernfusion im großen Maßstab und unkontrolliert statt. Eine schier unerschöpfliche Energiequelle ließe sich aus einer kontrollierten und kleinen Fusionsanlage gewinnen. ",0,300,2);
		$this->Forschungen[162] = array("Kernresonanz","Kernresonanz","Es gibt gleich mehrere neue Verfahren, deren Ziel es ist, die Resonanzenergie der überlagerten Wellenfunktionen von Kernteilchen zu gewinnen. Diese Energie lässt sich hervorragend für Antriebe nutzen.",0,300,2);
		$this->Forschungen[163] = array("Mesonenharze","Mesonenharze","Mesonen sind äußerst instabile Teilchen. Jedoch lassen sich sich unter ganz bestimmten Umständen stabilisieren und dann sogar zu einem harzigen Gemisch kombinieren. Dieses Harz weißt einige fantastische Eigenschaften auf die besonders der modernen Robotik nutzen könnten.",0,300,2);
		$this->Forschungen[164] = array("Higgsmatrix","Higgsmatrix","Erst kürzlich ist es gelungen, die schwer auszumachenden Higgs-Teilchen nachzuweisen. Mit ihrer Hilfe könnte es gelingen eine Waffe auf Basis der Gravitation zu erschaffen. Auf jeden Fall ein großes Unterfangen.",0,300,2);
		$this->Forschungen[165] = array("Raumspaltenerzeugung","Raumspaltenerzeugung","Der Raum ist ja bekanntermaßen nicht fest, sondern lässt sich biegen, formen und seit kurzem sogar aufreißen. Diese Tatsache macht man sich zunutze um sich gegen anfliegende Strahlen zu schützen. Man erzeugt einfach eine Raumspalte in deren Flugbahn.",0,300,2);
		$this->Forschungen[166] = array("Annihilationsversuche","Annihilationsversuche","Lässt man gewöhnliche Materie und Antimaterie aufeinander Treffer lösen sie sich gegenseitig auf und verwandeln sich dabei ungeheure Mengen Strahlungsenergie. Kontrolliert man diese Zerstrahlung, hat man eine enorm effiziente Energiequelle zur Verfügung.",0,300,2);
		$this->Forschungen[167] = array("Gleichspinpaarung","Gleichspinpaarung","Eigentlich wird eine Paarung von Teilchen, die gleichen Spin haben von der Physik verboten. Aber: Ausnahmen bestätigen die Regel und so lässt sich diese Ausnahme wunderbar für Antriebe nutzen.",0,300,2);
		$this->Forschungen[168] = array("Große Vereinheitlichte Theorie","Große Vereinheitlichte Theorie","Das finale Ziel aller physikalischer Forschungsbemühungen ist diese Theorie. Seit einer halben Ewigkeit träumen Wissenschaftler davon. Mit ihr lässt sich fast alles berechnen, vorausgesetzt man versteht die Gleichungen.",0,300,2);
		$this->Forschungen[169] = array();
		$this->Forschungen[170] = array();
		$this->Forschungen[171] = array();
		$this->Forschungen[172] = array();
		$this->Forschungen[173] = array();

        //Voraussetzungen
        /*
        	1 = kolonie-status
        		1 = dorf
        		2 = kleinstadt
        		3 = großstadt
        		4 = metropole
        		5 = hypercity
        	2 = forschung
        	3 = gebäude
        */ 
        //$this->Requirement[1] = array("typ|ID|lvl") =>typ: "1" für Kolonie-Status, "2" für Forschung und "3" für Gebäude
        //Dorf Requirements
		$this->Requirement[1] = array();
        $this->Requirement[2] = array();
        $this->Requirement[3] = array();
        $this->Requirement[4] = array();
        $this->Requirement[5] = array("2|1|1");
        $this->Requirement[6] = array("2|2|1");
        $this->Requirement[7] = array("2|3|1");
        $this->Requirement[8] = array("2|4|1", "2|7|1");
        $this->Requirement[9] = array("2|6|1");
        $this->Requirement[10] = array("2|7|1","2|5|1");
        $this->Requirement[11] = array("2|5|1");
        $this->Requirement[12] = array("2|8|1","2|9|1","2|10|1");
        $this->Requirement[13] = array("2|8|1");
        $this->Requirement[14] = array();
        $this->Requirement[15] = array();
   
        //Kleinstadt Requirements
        $this->Requirement[16] = array("1||2", "2|11|1");
        $this->Requirement[17] = array("1||2", "2|9|1");
        $this->Requirement[18] = array("1||2", "2|7|1");
        $this->Requirement[19] = array("1||2", "2|7|1");
        $this->Requirement[20] = array("1||2", "2|13|1");
        $this->Requirement[21] = array("1||2", "2|17|1");
        $this->Requirement[22] = array("1||2", "2|16|1");
        $this->Requirement[23] = array("1||2", "2|18|1","2|19|1");
        $this->Requirement[24] = array("1||2", "2|20|1");
        $this->Requirement[25] = array("1||2", "2|20|1");
        $this->Requirement[26] = array("1||2", "2|20|1");
        $this->Requirement[27] = array("1||2", "2|23|1");
        $this->Requirement[28] = array("1||2", "2|23|1");
        $this->Requirement[29] = array("1||2", "2|19|1");
        $this->Requirement[30] = array("1||2", "2|25|1");
        $this->Requirement[31] = array("1||2", "2|22|1");
        $this->Requirement[32] = array("1||2", "2|27|1");
        $this->Requirement[33] = array("1||2", "2|22|1","2|28|1");
        $this->Requirement[34] = array("1||2", "2|23|1","2|29|1");
		$this->Requirement[35] = array("1||2", "2|24|1");
		$this->Requirement[36] = array("1||2", "2|30|1");
		$this->Requirement[37] = array("1||2", "2|33|1", "2|34|1");
		$this->Requirement[38] = array("1||2", "2|26|1");
		$this->Requirement[39] = array("1||2", "2|32|1");
		$this->Requirement[40] = array("1||2", "2|37|1");
		$this->Requirement[41] = array("1||2", "2|34|1", "2|35|1");
		$this->Requirement[42] = array("1||2", "2|36|1");
		$this->Requirement[43] = array("1||2", "2|40|1");
		$this->Requirement[44] = array("1||2", "2|40|1", "2|41|1");
		$this->Requirement[45] = array("1||2");
		$this->Requirement[46] = array("1||2");
		$this->Requirement[47] = array("1||2");
		$this->Requirement[48] = array("1||2");
		$this->Requirement[49] = array("1||2");
		$this->Requirement[50] = array("1||2");
			
		//Stadt Requirements
		$this->Requirement[51] = array("1||3","2|31|1");
		$this->Requirement[52] = array("1||3","2|39|1");
		$this->Requirement[53] = array("1||3","2|43|1");
		$this->Requirement[54] = array("1||3","2|43|1");
		$this->Requirement[55] = array("1||3");
		$this->Requirement[56] = array("1||3","2|35|1");
		$this->Requirement[57] = array("1||3","2|42|1");
		$this->Requirement[58] = array("1||3");
		$this->Requirement[59] = array("1||3");
		$this->Requirement[60] = array("1||3","2|51|1");
		$this->Requirement[61] = array("1||3","2|53|1");
		$this->Requirement[62] = array("1||3","2|54|1");
		$this->Requirement[63] = array("1||3","2|55|1");
		$this->Requirement[64] = array("1||3","2|56|1");
		$this->Requirement[65] = array("1||3","2|56|1");
		$this->Requirement[66] = array("1||3","2|56|1","2|57|1");
		$this->Requirement[67] = array("1||3","2|58|1");
		$this->Requirement[68] = array("1||3","2|58|1");
		$this->Requirement[69] = array("1||3","2|68|1");
		$this->Requirement[70] = array("1||3","2|68|1");
		$this->Requirement[71] = array("1||3","2|65|1");
		$this->Requirement[72] = array("1||3","2|63|1");
		$this->Requirement[73] = array("1||3","2|60|1");
		$this->Requirement[74] = array("1||3","2|60|1");
		$this->Requirement[75] = array("1||3","2|60|1","2|61|1");
		$this->Requirement[76] = array("1||3","2|61|1","2|62|1");
		$this->Requirement[77] = array("1||3","2|62|1");
		$this->Requirement[78] = array("1||3","2|67|1");
		$this->Requirement[79] = array("1||3","2|70|1");
		$this->Requirement[80] = array("1||3","2|66|1","2|69|1");
		$this->Requirement[81] = array("1||3","2|71|1");
		$this->Requirement[82] = array("1||3","2|78|1");
		$this->Requirement[83] = array("1||3","2|75|1");
		$this->Requirement[84] = array("1||3","2|71|1");
		$this->Requirement[85] = array("1||3","2|71|1");
		$this->Requirement[86] = array("1||3","2|71|1");
		$this->Requirement[87] = array("1||3","2|81|1");
		$this->Requirement[88] = array("1||3","2|82|1");
		$this->Requirement[89] = array("1||3","2|78|1","2|79|1");
		$this->Requirement[90] = array("1||3","2|89|1");
		$this->Requirement[91] = array("1||3","2|73|1");
		$this->Requirement[92] = array("1||3");
		$this->Requirement[93] = array("1||3");
		$this->Requirement[94] = array("1||3");
		$this->Requirement[95] = array("1||3");
	
		//Großstadt Requirements
        $this->Requirement[96] = array("1||4","2|91|1");
		$this->Requirement[97] = array("1||4","2|83|1");
		$this->Requirement[98] = array("1||4");
		$this->Requirement[99] = array("1||4","2|86|1");
		$this->Requirement[100] = array("1||4","2|87|1");
		$this->Requirement[101] = array("1||4","2|80|1");
		$this->Requirement[102] = array("1||4","2|88|1");
		$this->Requirement[103] = array("1||4","2|69|1");
		$this->Requirement[104] = array("1||4","2|90|1");
		$this->Requirement[105] = array("1||4","2|96|1");
		$this->Requirement[106] = array("1||4","2|97|1");
		$this->Requirement[107] = array("1||4");
		$this->Requirement[108] = array("1||4","2|99|1");
		$this->Requirement[109] = array("1||4","2|101|1");
		$this->Requirement[110] = array("1||4","2|103|1");
		$this->Requirement[111] = array("1||4","2|104|1");
		$this->Requirement[112] = array("1||4","2|104|1");
		$this->Requirement[113] = array("1||4","2|98|1");
		$this->Requirement[114] = array("1||4","2|108|1");
		$this->Requirement[115] = array("1||4","2|105|1","2|106|1");
		$this->Requirement[116] = array("1||4","2|106|1");
		$this->Requirement[117] = array("1||4","2|100|1");
		$this->Requirement[118] = array("1||4","2|100|1","2|112|1");
		$this->Requirement[119] = array("1||4","2|115|1");
		$this->Requirement[120] = array("1||4","2|116|1");
		$this->Requirement[121] = array("1||4","2|109|1","2|114|1");
		$this->Requirement[122] = array("1||4","2|102|1");
		$this->Requirement[123] = array("1||4","2|118|1");
		$this->Requirement[124] = array("1||4","2|118|1");
		$this->Requirement[125] = array("1||4","2|119|1");
		$this->Requirement[126] = array("1||4","2|120|1");
		$this->Requirement[127] = array("1||4","2|120|1");
		$this->Requirement[128] = array("1||4","2|114|1","2|120|1");
		$this->Requirement[129] = array("1||4","2|121|1","2|123|1");
		$this->Requirement[130] = array("1||4","2|126|1");
		$this->Requirement[131] = array("1||4","2|123|1");
		$this->Requirement[132] = array("1||4","2|123|1","2|124|1");
		$this->Requirement[133] = array("1||4");
		$this->Requirement[134] = array("1||4");
		$this->Requirement[135] = array("1||4");
		$this->Requirement[136] = array("1||4");
		$this->Requirement[137] = array("1||4");
	
		//Metropole Requirements
		$this->Requirement[138] = array("1||5","2|125|1");
		$this->Requirement[139] = array("1||5","2|130|1");
		$this->Requirement[140] = array("1||5","2|130|1");
		$this->Requirement[141] = array("1||5","2|128|1");
		$this->Requirement[142] = array("1||5");
		$this->Requirement[143] = array("1||5","2|129|1");
		$this->Requirement[144] = array("1||5","2|122|1");
		$this->Requirement[145] = array("1||5","2|132|1");
		$this->Requirement[146] = array("1||5","2|138|1");
		$this->Requirement[147] = array("1||5","2|140|1");
		$this->Requirement[148] = array("1||5","2|131|1","2|144|1","2|145|1");
		$this->Requirement[149] = array("1||5","2|145|1");
		$this->Requirement[150] = array("1||5");
		$this->Requirement[151] = array("1||5","2|144|1");
		$this->Requirement[152] = array("1||5","2|146|1","2|147|1");
		$this->Requirement[153] = array("1||5","2|147|1");
		$this->Requirement[154] = array("1||5","2|146|1","2|148|1");
		$this->Requirement[155] = array("1||5","2|148|1");
		$this->Requirement[156] = array("1||5","2|149|1");
		$this->Requirement[157] = array("1||5","2|152|1");
		$this->Requirement[158] = array("1||5","2|153|1");
		$this->Requirement[159] = array("1||5","2|150|1");
		$this->Requirement[160] = array("1||5","2|150|1","2|154|1","2|155|1");
		$this->Requirement[161] = array("1||5","2|155|1");
		$this->Requirement[162] = array("1||5","2|155|1","2|156|1");
		$this->Requirement[163] = array("1||5","2|157|1");
		$this->Requirement[164] = array("1||5","2|158|1");
		$this->Requirement[165] = array("1||5","2|160|1","2|161|1");
		$this->Requirement[166] = array("1||5","2|161|1");
		$this->Requirement[167] = array("1||5","2|162|1");
		$this->Requirement[168] = array("1||5","2|163|1","2|164|1","2|159|1","2|165|1","2|166|1","2|167|1");
		$this->Requirement[169] = array("1||5");
		$this->Requirement[170] = array("1||5");
		$this->Requirement[171] = array("1||5");
		$this->Requirement[172] = array("1||5");
		$this->Requirement[173] = array("1||5");
    }

    function loadForschung($ID)
    {
        //Forschungsdaten laden
        $this->ID 			= $ID;
        $this->Beschreibung = $this->Forschungen[$ID][2];
        $this->Bauzeit		= $this->Forschungen[$ID][5];
        $this->Kosten		= $this->Forschungen[$ID][4];
        
        //Rassenbezeichnung setzen
        if( $this->ID_Rasse == 1 )	//Terraner
        {
        	$this->Bezeichnung 	= $this->Forschungen[$ID][0];
        }
        else
        {
        	$this->Bezeichnung	= $this->Forschungen[$ID][1];
        }

    }

    function getBezeichnung()
    {
        return $this->Bezeichnung;
    }

    function getID()
    {
        return $this->ID;
    }

    function getBeschreibung()
    {
        return $this->Beschreibung;
    }

    function getKosten($lvl)
    {
        /*Kosten steigen pro Level um 30%
        an = a1 * q^n-1*/
        return round($this->Kosten * pow(1.42, $lvl));
    }
    
    function getForschungsArray()
    {
        return $this->Forschungen;
    }

    function getRequirement()
    {
        return $this->Requirement[$this->ID];
    }
    
    /*gibt Bauzeit zurück*/
    function getBuildTime($level, $forscher)
    {
    	/*Die Bauzeit der Forschung hängt vom Level der Forschung, sowie
    	der Anzahl der Forscher ab
    	Bei einem Forscher = normale Bauzeit
    	Bei zwei Forscher  = weniger Bauziert
    	
    	=>	Bauzeit steigt pro Level um 40%
    		also 	lvl 1 = 100 sekunden
    				lvl 2 = 140 sekunden etc...
    	
    	=>	Bauzeit wird um 1.25% pro Forscher reduziert
    		d.h. bei 100 Sekuden Bauzeit ergibt sich eine Bauzeit von 
    		98,75 Sekunden....
    	
    	=> Formel:
    		an = a1 * q^n-1
    	*/
    	
		//Bauzeit berechnen    	
    	$grund_bauzeit = $this->Bauzeit * 1800;
    	
    	//Bauzeit ermitteln
    	$bauzeit = $grund_bauzeit * pow(1.563, $level);
    	
    	//Verbesserung durch Forscher
    	$bauzeit = round($bauzeit * pow(0.9875, $forscher));
    	
    	//ergebnis abrunden!
    	$differenz = $bauzeit % 5;
    	$bauzeit -= $differenz;    	
    	return $bauzeit;
    }
    
    /*gibt formatirte Bauzeitausgabe zurück*/
    function getFormattedBuildTime($level, $forscher)
    {
    	//Bauzeit in tage, stunden, minuten und sekunden ermitteln
		$sekunden = $this->getBuildTime($level, $forscher);
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
}