<?php
//Includes
require("../includes/login_check.php");

//Template laden
$tpl_bugreport = new TEMPLATE("templates/".$_SESSION['skin']."/bug_report.tpl");
$daten_detail = array(
	'FEHLER' => '',
	'ERFOLG' => '',
	'TITEL' => '',
	'BESCHREIBUNG' => '');

//Überprüfen ob User scdhon den button gedrückt hat
if( isset($_POST['report']) )
{
	//Formulardaten setzen!
	$daten_detail['TITEL'] = $_POST['titel'];
	$daten_detail['BESCHREIBUNG'] = $_POST['beschreibung'];
	
	//$tpl_bugreport->setObject("bug_report", $daten_detail);
	
    //Daten überprüfen
    if( empty($_POST['beschreibung']) || empty($_POST['titel']) )
    {
    	//Fehlerobjekt erzeugen
    	$fehler = new FEHLER($db);
    	
    	if( empty($_POST['beschreibung']) )
    	{
    		$daten_detail['FEHLER'] = $fehler->meldung(301);
    	}
    	else
    	{
    		$daten_detail['FEHLER'] = $fehler->meldung(300);
    	}
    }
    else
    {
		//Daten eintragen
    	$db->query("INSERT INTO t_bugs (Titel, Beschreibung, Datum, Status, ID_User) VALUES ('".$_POST['titel']."', '".$_POST['beschreibung']."', ".time().", 'waiting', ".$_SESSION['user']->getUserID().");");
    	$daten_detail['ERFOLG'] = "Daten wurden erfolgreich eingetragen und werden in Kürze von einem Administrator freigeschaltet.";
    }
}
//Templatedaten setzen
$tpl_bugreport->setObject("bug_report", $daten_detail);
$daten['CONTENT'] = $tpl_bugreport->getTemplate();

//footer einbinden
require_once("../includes/footer.php");?>