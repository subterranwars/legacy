<?php
/*Diese Script zeigt alle UserInfos an.
via $_GET['ID'] wird die UserId übergeben und alle Daten geladen
via $_GET['KOLO_ID'] wird eine KolonieID übergeben, welche ggf. gehighlighted werden muss!

History:
			11.01.2005		MvR		created
*/
//Includes
require("../includes/login_check.php");

//Template laden
$tpl_info = new TEMPLATE("templates/".$_SESSION['skin']."/user_info.tpl");
$daten = array(
	'NICKNAME' => '',
	'AVATAR' => '',
	'RASSE' => '',
	'PUNKTE' => '',
	'KOORDINATEN' =>'',
	'EMPFAENGER' =>'',
	'GEB_PKT' => '',
	'FORSCHUNGS_PKT' => '',
	'GESAMT_PKT' => '');

$daten_kolo = array(
	'KOLO_NAME'	=> '',
	'KOLO_KOORDS' => '',
	'KOLO_PKT' => '',
	'CLASS' => '');

	
/*Lade alle Userrelevanten Informationen!*/
$db->query("SELECT ID_User, Nickname, ID_Rasse, Avatar, PunkteForschung FROM t_user WHERE ID_User = ".$_GET['ID']."");
$user_daten = $db->fetch_array();

//Rasse Objekt erzeugen
$rasse = new RASSE($user_daten[2]);

//Neues Datenbankobjekt erzeugen
$db2 = new DATENBANK();

/*Lade Alle Kolonien des Users*/
$db->query("SELECT ID_Kolonie FROM t_kolonie WHERE ID_User = ".$user_daten['ID_User']."");
while( $row = $db->fetch_array() )
{
	//Kolonieobjekt erzeugen
	$kolo = new KOLONIE($row['ID_Kolonie'], $db2);
	
	//Kolonietemplatedaten setzen
	$daten_kolo['KOLO_NAME'] 	= $kolo->getBezeichnung();
	$daten_kolo['KOLO_KOORDS'] 	= $kolo->getKoordinaten();
	$daten_kolo['KOLO_PKT'] 	= $kolo->getGebäudePunkte();
	
	//Setze Klasse für Koloniezeile
	if( $_GET['KOLO_ID'] == $row['ID_Kolonie'] )
	{
		$daten_kolo['CLASS'] = 'yellow';
	}
	else 
	{
		$daten_kolo['CLASS'] = '';
	}
	
	//Pkt addieren
	$gesamt +=  $kolo->getGebäudePunkte();
	
	//Templatedaten ersetzen
	$tpl_info->setObject('user_info_kolo', $daten_kolo);
}
	
/*Templatedaten setzen!*/
$daten['NICKNAME'] 		= $user_daten['Nickname'];
$daten['RASSE']			= $rasse->getBezeichnung();
$daten['KOORDINATEN']	= $kolo->getKoordinaten();
$daten['EMPFAENGER']	= $user_daten['Nickname'];
$daten['GEB_PKT']		= $gesamt;
$daten['FORSCHUNGS_PKT']= $user_daten['PunkteForschung'];
$daten['GESAMT_PKT']	= $gesamt + $user_daten['PunkteForschung'];

//Überprüfung des Avatars
if( $user_daten[3] == "" || empty($user_daten[3]) )
{
	$daten['AVATAR'] = "kein Avatar gewählt";
}
else 
{
	$daten['AVATAR'] = "<img src=\"cache/avatar/".$user_daten[3]."\">";
}

//Objekt serialisieren
$_SESSION['user']			= serialize($_SESSION['user']);
$_SESSION['kolonie'] 		= serialize($_SESSION['kolonie']);
$_SESSION['bevoelkerung'] 	= serialize($_SESSION['bevoelkerung']);

//Templatedaten ersetzen
$tpl_info->setObject("user_info", $daten);
echo $tpl_info->getTemplate();