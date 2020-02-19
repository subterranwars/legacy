<?PHP
//Session starten
session_start();

//Includes
require_once("../includes/klassen/einstellungen.php");
require_once("../includes/klassen/db.php");
require_once("../includes/klassen/text.php");
require_once("../includes/klassen/template.php");

//Objekte erzeugen
$db 			= new DATENBANK();			//Datenbank
$config	 		= new EINSTELLUNGEN($db);	//Einstellungsobjekt

//Defaulttemplate laden !
$tpl 		= new TEMPLATE("templates/index/index.tpl");
$daten 	= array(
	'CONTENT' => '');

//Spiel_Info-Template
$tpl_detail = new TEMPLATE("templates/index/spiel_status.tpl");
$daten_detail = array(
	'ANZAHL' => '',
	'ANZAHL_MAX' => '',
	'ANZAHL_SPIELEND' => '',
	'ANZAHL_WARTEND' => '',
	'ANZAHL_GESPERRT' => '',
	'ANZAHL_TERRANER' => '',
	'ANZAHL_SUBTERRANER' => '',
	'ANZAHL_LOGIN' => '');
	
//Daten laden
$db->query("SELECT COUNT(*) FROM t_user WHERE ID_User > 10");
$anzahl = $db->fetch_result(0);

//Restliche Daten laden
$db->query("SELECT COUNT(*) FROM t_user WHERE ID_User > 10 AND Status = 'warten'");
$anzahl_wartend = $db->fetch_result(0);

//Gesperrt
$db->query("SELECT COUNT(*) FROM t_user WHERE ID_User > 10 AND Status = 'gesperrt'");
$anzahl_gesperrt = $db->fetch_result(0);

//Spielend:
$anzahl_spielend = $anzahl-$anzahl_wartend - $anzahl_gesperrt;

//Anzahl Terraner und Anzahl Subterraner
$db->query("SELECT COUNT(*) FROM t_user WHERE ID_User > 10 AND ID_Rasse = 1");
$anzahl_rassen[0] = $db->fetch_result(0);

//SubTerraner
$db->query("SELECT COUNT(*) FROM t_user WHERE ID_User > 10 AND ID_Rasse = 2");
$anzahl_rassen[1] = $db->fetch_result(0);

/*Alle User laden, welche nicht mehr eingeloggt sind!*/
$db->query("DELETE FROM t_useronline WHERE Expire <= ".time()."");
//Eingeloggt:
$db->query("SELECT COUNT(*) FROM t_useronline");
$anzahl_login = $db->fetch_result(0);

//TemplateDaten setzen
$daten_detail['ANZAHL'] 			= $anzahl;
$daten_detail['ANZAHL_MAX'] 		= $config->getAnzahlRegUser();
$daten_detail['ANZAHL_SPIELEND'] 	= $anzahl_spielend;
$daten_detail['ANZAHL_WARTEND'] 	= $anzahl_wartend; 
$daten_detail['ANZAHL_GESPERRT'] 	= $anzahl_gesperrt; 
$daten_detail['ANZAHL_SUBTERRANER'] = $anzahl_rassen[1]; 
$daten_detail['ANZAHL_TERRANER']	= $anzahl_rassen[0];
$daten_detail['ANZAHL_LOGIN'] 		= $anzahl_login;

//Templatedaten ersetzen
$tpl_detail->setObject("spiel_status", $daten_detail);
$daten['CONTENT'] = $tpl_detail->getTemplate();
$tpl->setObject("index", $daten);
echo $tpl->getTemplate();