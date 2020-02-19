<?PHP
//Includes
require("../includes/login_check.php");

//Template laden
$tpl_rohstoffe = new TEMPLATE("templates/".$_SESSION['skin']."/rohstoff_anzeige.tpl");
$daten_rohstoffe = array(
	'NAME' => '',
	'PRODUKTION' => '',
	'VERBRAUCH' => '',
	'GESAMT' => '',
	'CLASS_PROD' => '',
	'CLASS_VERB' => '',
	'CLASS_GES' => '');
	
$daten_lager = array(
	'NORMALE_ROHSTOFFE' => '',
	'RADIOAKTIVE_ROHSTOFFE' => '');
	
$daten_bev = array(
	'BEVÖLKERUNG' => '',
	'WACHSTUM' => '',
	'WOHNPLÄTZE' => '');

/*Rohstoffe laden!*/
$res = new ROHSTOFF();
$res_array = $res->getRohstoffArray();
$produktion = array();	//Array einleiten

/*Gebäudeobjekte erzeugen*/
$hq 			= new HAUPTQUARTIER($_SESSION['user'], $_SESSION['kolonie']->getID());
$brutreaktor	= new BRUTREAKTOR($db, $_SESSION['user'], $_SESSION['kolonie']->getID(), $_SESSION['kolonie']->getEnergieniveau());
$chemiefabrik	= new CHEMIEFABRIK($db, $_SESSION['user'], $_SESSION['kolonie']->getID(), $_SESSION['kolonie']->getEnergieniveau());
$schmelze		= new SCHMELZE($db, $_SESSION['user'], $_SESSION['kolonie']->getID(), $_SESSION['kolonie']->getEnergieniveau());
$titanschmelze	= new TITANSCHMELZE($db, $_SESSION['user'], $_SESSION['kolonie']->getID(), $_SESSION['kolonie']->getEnergieniveau());
$extraktor		= new WASSERSTOFF($db, $_SESSION['user'], $_SESSION['kolonie']->getID(), $_SESSION['kolonie']->getEnergieniveau());
$getreidefeld 	= new GETREIDEFELD($_SESSION['user'], $_SESSION['kolonie']->getID());

/*Bevölkerungsdaten setzen*/
$daten_bev['BEVÖLKERUNG'] 	= floor($_SESSION['bevoelkerung']->getAnzahl());
$daten_bev['WACHSTUM']		= $_SESSION['bevoelkerung']->getWachstumsrate() * 100;
$daten_bev['WOHNPLÄTZE']	= $_SESSION['bevoelkerung']->getMaxBev();
$tpl_rohstoffe->setObject("rohstoff_bevölkerung", $daten_bev);


/*Gebäuderohstoffproduktionen und verbrauche laden*/
//Brutreaktor 
$produktion[$brutreaktor->ID_Rohstoff_Gewinn] 	+= $brutreaktor->checkProduktion();
$verbrauch[$brutreaktor->ID_Rohstoff]			+= $brutreaktor->getResVerbrauch();
$verbrauch[$brutreaktor->ID_Rohstoff]			+= $brutreaktor->getResVerbrauchEnergie();	//Energieverbrauch für die Kraftwerk abteilung setzen!
//Chemiefabrik
$produktion[$chemiefabrik->ID_Rohstoff_Gewinn] 	+= $chemiefabrik->checkProduktion();
$verbrauch[$chemiefabrik->ID_Rohstoff]			+= $chemiefabrik->getResVerbrauch();
//Schmelze
$produktion[$schmelze->ID_Rohstoff_Gewinn] 		+= $schmelze->checkProduktion();
$verbrauch[$schmelze->ID_Rohstoff]				+= $schmelze->getResVerbrauch();
//Titanschmelze
$produktion[$titanschmelze->ID_Rohstoff_Gewinn] += $titanschmelze->checkProduktion();
$verbrauch[$titanschmelze->ID_Rohstoff]			+= $titanschmelze->getResVerbrauch();
//Extraktor
$produktion[$extraktor->ID_Rohstoff_Gewinn] 	+= $extraktor->checkProduktion();
$verbrauch[$extraktor->ID_Rohstoff]				+= $extraktor->getResVerbrauch();
//Getreidefeld
$produktion[$getreidefeld->ID_Rohstoff]				+= $getreidefeld->getProduktion();
$verbrauch[$_SESSION['bevoelkerung']->ID_Nahrung]	+= $_SESSION['bevoelkerung']->getResVerbrauch();

/*Grundrohstoffe addieren*/
$grund_rohstoffe = $hq->getGrundProduktion();
//Grundrohstoffe durchlaufen
foreach( $grund_rohstoffe as $key => $value )
{
	$produktion[$key] += $value;
}

/*Vorkommensproduktionen pro Stunde laden!*/
//Vorkommen laden
$vorkommen = &$_SESSION['user']->getVorkommen();

//Alle Vorkommen durchlaufen
for($i=0; $i<count($vorkommen); $i++)
{
	$produktion[$vorkommen[$i]->getRohstoffID()] += $vorkommen[$i]->checkProduktion($_SESSION['kolonie']->getEnergieniveau());
}

/*EnergieÖlVerbrauch hinzufügen*/
$kraftwerk = new KRAFTWERK($db, $_SESSION['user'], $_SESSION['kolonie']->getID());
$verbrauch[$kraftwerk->ID_Rohstoff] += $kraftwerk->getResVerbrauch();

/*Template daten setzen!*/
for( $i=1; $i<=count($res_array); $i++ )
{
	//Rohstoffobjekt erzeugen
	$res->loadRohstoff($i);
		
	//Ausgabe hübsch machen
	if( $produktion[$i] == 0 )
	{
		$produktion[$i] = "";
	}
	if( $verbrauch[$i] == 0 )
	{
		$verbrauch[$i] = "";
	}	
	
	//Templatedaten setzen
	$daten_rohstoffe['NAME'] = $res->getBezeichnung();
	$daten_rohstoffe['PRODUKTION'] 	= number_format(floor($produktion[$i]), 0, ",",".");
	$daten_rohstoffe['VERBRAUCH']	= number_format($verbrauch[$i], 0, ",",".");
	$daten_rohstoffe['GESAMT'] 		= number_format(($produktion[$i] - $verbrauch[$i]), 0, ",",".");	
	
	//Ausgabe verfeinern!
	$daten_rohstoffe['CLASS_PROD'] = "green";	
	$daten_rohstoffe['CLASS_VERB'] = "red";
	
	//ist gesamtmenge positiv oder negativ?
	if( $daten_rohstoffe['GESAMT'] > 0 )
	{
		$daten_rohstoffe['CLASS_GES'] = "green";
	}
	elseif( $daten_rohstoffe['GESAMT'] < 0 )
	{	
		$daten_rohstoffe['CLASS_GES'] = "red";
	}
	else 
	{
		$daten_rohstoffe['CLASS_GES'] = "";
	}
	
	//Templatedaten ersetzen
	$tpl_rohstoffe->setObject("rohstoff_anzeige", $daten_rohstoffe);
}

/*Energiedaten setzen!*/
$daten_rohstoffe['NAME'] 		= "Energie";
$daten_rohstoffe['PRODUKTION'] 	= $to_do->getEnergieproduktion_Final();
$daten_rohstoffe['VERBRAUCH']	= $_SESSION['user']->getEnergieverbrauch();
$daten_rohstoffe['GESAMT']		= $daten_rohstoffe['PRODUKTION'] - $daten_rohstoffe['VERBRAUCH'];

//Wird genügend Energie produziert?
if($daten_rohstoffe['PRODUKTION']  > $daten_rohstoffe['VERBRAUCH'] )
{
	$daten_rohstoffe['CLASS_GES']	= "green";
}
else 
{
	$daten_rohstoffe['CLASS_GES']	= "red";
}

//Templatedaten ersetzen
$tpl_rohstoffe->setObject("rohstoff_anzeige", $daten_rohstoffe);

/*Rohstofflager setzen*/
$rohstofflager 		= new ROHSTOFFLAGER($_SESSION['user']->getGebäudeLevel(15));
$sicherheitslager 	= new SICHERHEITSLAGER($_SESSION['user']->getGebäudeLevel(16));

//Templatedaten setzen
$daten_lager['NORMALE_ROHSTOFFE'] 		= $rohstofflager->getKapazitaet();
$daten_lager['RADIOAKTIVE_ROHSTOFFE'] 	= $sicherheitslager->getKapazitaet();

//Template ersetzen
$tpl_rohstoffe->setObject("rohstoff_lager", $daten_lager);
$daten['CONTENT'] = $tpl_rohstoffe->getTemplate();

//footer einbinden
require_once("../includes/footer.php");
?>