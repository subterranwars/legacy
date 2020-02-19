<?php
//Includes
require("../includes/login_check.php");

//Template laden
$tpl_bugs = new TEMPLATE("templates/".$_SESSION['skin']."/bugs.tpl");
$daten_detail = array("CLASS"=>"","DATUM"=>"", "STATUS"=>"", "TITEL"=>"", "BESCHREIBUNG"=>"");

//Alle daten laden
$db->query("SELECT ID_Bugs, Titel, Beschreibung, Datum, t_bugs.Status, t_user.Nickname FROM t_bugs LEFT JOIN t_user USING(ID_User) WHERE t_bugs.Status != 'waiting' ORDER BY Datum DESC");
while( $row = $db->fetch_array() )
{
	$daten_detail['DATUM'] 			= date("D, d.m.Y H:i:s", $row['Datum']);
	$daten_detail['BESCHREIBUNG'] 	= nl2br($row['Beschreibung']);
	$daten_detail['TITEL']			= $row['Titel'];
	$daten_detail['STATUS']			= $row['Status'];
	
	if( empty($row[5]) )
	{
		$daten_detail['NICKNAME'] = "unbekannt";
	}
	else
	{
		$daten_detail['NICKNAME'] = $row[5];
	}

	
	if( $row['Status'] == "gelöst")
	{
		$daten_detail['CLASS'] = "green";
	}
	else
	{
		$daten_detail['CLASS'] = "red";
	}
	
	///Templatedaten setzen
	$tpl_bugs->setObject("bugs", $daten_detail);
}
//Contentdaten ausgeben
$daten['CONTENT'] = $tpl_bugs->getTemplate();

//footer einbinden
require_once("../includes/footer.php");
?>