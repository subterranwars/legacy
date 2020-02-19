<?php
/*bauplan_erstellen_select.php
Stellt Weiche zur Verfügung, welche auswählen lässt was für eine Einheit erstellt
werden soll.
Infanterist, Fahrzeug oder Mech.

History:
			23.09.2004			Markus von Rüden		created
*/

//Includes
require("../includes/login_check.php");

//Template daten setzen
$tpl_bauplan = new TEMPLATE("templates/".$_SESSION['skin']."/bauplan_erstellen.tpl");
$daten_bauplan = array(
	'FEHLER' => '',
	'CONTENT' => '');

$tpl_detail = new TEMPLATE("templates/".$_SESSION['skin']."/bauplan_select.tpl");
$daten_bauplan['CONTENT'] = $tpl_detail->getTemplate();

//Templatedaten ersetzen
$tpl_bauplan->setObject("bauplan", $daten_bauplan);
$daten['CONTENT'] = $tpl_bauplan->getTemplate();

//footer einbinden
require_once("../includes/footer.php");