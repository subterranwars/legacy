<?PHP
/*footer.php
Diese Datei wird inkludiert serialisiert das UserObjekt und berechnet die Ausfürhungszeit!

History:
			26.08.2004		MvR		created
			30.12.2004		MvR		RohstoffAnzeige von der to_do.php hierher verlegt
*/

//Setze Ausführungszeit
$end_zeit = getMicrotime();
$daten['AUSFUEHRUNG'] = number_format((($end_zeit - $anfangs_zeit)*1000), 2, ",",".");

/*Rohstoffe für die Anzeige vorbereiten*/
$tpl_res = new TEMPLATE("templates/".$_SESSION['skin']."/rohstoffe.tpl");

//Daten für die Rohstoffe
$daten_res['BEZ'] = '';
$daten_res['ANZ'] = '';
//Daten für die Forscherdetails
$daten_forscher['FORSCHER'] = '';
$daten_forscher['FORSCHUNGS_PUNKTE'] = '';
//Variablen der Bevölkerungsdaten
$daten_bev['BEV_AKTUELL'] = '';
$daten_bev['BEV_MAX'] = '';

/*Forscher setzen*/
$forscher = &$_SESSION['user']->getForscher();
$daten_forscher['FORSCHER'] = $forscher->getForscher();
$daten_forscher['FORSCHUNGS_PUNKTE'] = number_format($forscher->getForschungsPunkte(), 0,",",".");

/*Bevölkerungsdaten setzen*/
$daten_bev['BEV_AKTUELL'] = number_format($_SESSION['bevoelkerung']->getAnzahl(), 0,",",".");
$daten_bev['BEV_MAX'] = $_SESSION['bevoelkerung']->getMaxBev();

/*Template aktualisieren*/
$tpl_res->setObject("forscher", $daten_forscher);
$tpl_res->setObject("bevölkerung", $daten_bev);

//Rohstoffe laden
$res = new ROHSTOFF();
$rohstoffe = $res->getRohstoffArray();

//Alle Rohstoffe durchlaufen
$i = 1;
while( $i <= count($rohstoffe) )
{	
	//Templatedaten vorbereiten!
	$daten_res["BEZ"] = $rohstoffe[$i][0];
	$daten_res["ANZ"] = number_format(floor($_SESSION['user']->getRohstoffAnzahl($i)), 0, ",", ".");
	
	//Templatedaten aktuallisieren
	$tpl_res->setObject("rohstoffe", $daten_res);
	$i++;
}
//Templatedaten aktuallisieren
$daten['ROHSTOFFE'] = $tpl_res->getTemplate();

/*Setze Kolonie-TemplateDaten!*/
$daten['KOLONIE_STATUS'] = $_SESSION['kolonie']->getStatus();


//Energieproduktion und Verrauch laden!
$produktion_energie = floor($to_do->getEnergieproduktion_Final());
$verbrauch_energie 	= ceil($_SESSION['user']->getEnergieverbrauch());

/*Setze Energiebalken*/
$daten['ENERGIE_VERBRAUCH'] =  139*((100/($produktion_energie * 1.15) * $verbrauch_energie)/100);
$daten['ENERGIE_PRODUKTION'] =  139 - $daten['ENERGIE_VERBRAUCH'];


//Objekt serialisieren
$_SESSION['user'] 			= serialize($_SESSION['user']);
$_SESSION['kolonie'] 		= serialize($_SESSION['kolonie']);
$_SESSION['bevoelkerung'] 	= serialize($_SESSION['bevoelkerung']);

//Template laden
$tpl->setObject("spiel", $daten);
echo $tpl->getTemplate();?>