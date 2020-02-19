<?php
//Includes
require("../includes/login_check.php");

//Template laden
$tpl_general = new TEMPLATE("templates/".$_SESSION['skin']."/general.tpl");
$daten_detail = array(
	'AVATAR' => '',
	'USED' => '',
	'FREI' => '',
	'GESAMT' => '',
	'ANGRIFF' => '',
	'ANGRIFF_WERT' => '',
	'ANGRIFF2' => '',
	'VERTEIDIGUNG' => '',
	'VERTEIDIGUNG_WERT' => '',
	'VERTEIDIGUNG2' => '',
	'GESCHWINDIGKEIT' => '',
	'GESCHWINDIGKEIT_WERT' => '',
	'GESCHWINDIGKEIT2'=> '',
	'ZIELEN' => '',
	'ZIELEN_WERT' => '',
	'ZIELEN2' => '',
	'WENDIGKEIT' => '',
	'WENDIGKEIT_WERT' => '',
	'WENDIGKEIT2' => '',
	'ROHSTOFFOPTIMIERUNG' => '',
	'ROHSTOFFOPTIMIERUNG_WERT' => '',
	'ROHSTOFFOPTIMIERUNG2' => '',
	'FORSCHUNG' => '', 
	'FORSCHUNG_WERT' => '',
	'FORSCHUNG2' => '',
	'TEXT' => '',
	'BUTTON' => '');

//Erzeuge Generalobjekt
$general = &$_SESSION['user']->getGeneral();
$anzahl = 7;

/*ÜBerprüfen ob Knopf gedrückt wurde!*/
if( isset($_POST['send']) )
{
	//Array vorbereiten
	$array['Angriffsbonus'] 					= abs($_POST['werte'][0]);
	$array['Verteidigungsbonus'] 				= abs($_POST['werte'][1]);
	$array['Geschwindigkeitsbonus'] 			= abs($_POST['werte'][2]);
	$array['Zielenbonus'] 						= abs($_POST['werte'][3]);
	$array['Wendigkeitsbonus'] 					= abs($_POST['werte'][4]);
	$array['Rohstoffproduktionsoptimierung'] 	= abs($_POST['werte'][5]);
	$array['Forschungsvorteil'] 				= abs($_POST['werte'][6]);
	
	//Daten aktuallisieren
	$error = $general->setWerte($array);
	
	//Sind Fehler aufgetreten?
	if( $error == -1 )
	{
		//Fehlerobjekt erzeugen und Fehlermeldung ausgeben!
		$fehler = new FEHLER($db);
		$daten['CONTENT'] = $fehler->meldung(600);
	}
	elseif( $error == -2 )
	{
		//Fehlerobjekt erzeugen
		$fehler = new FEHLER($db);
		$daten['CONTENT'] = $fehler->meldung(601);
	}
}

//Avatar setzen
$avatar = $_SESSION['user']->getAvatar();
if( empty($avatar) )
{
	$avatar = '<a href="einstellungen.php">Kein Avatar ausgewählt!</a>';
}
else
{
	$avatar = "<img src=\"cache/avatar/$avatar\">";
}

//Lade maximalWert: 
$max_wert = $general->getMaxPoints();
$max_show = 150;	//Wie lang soll das img bei 100% sein?

//MAx Wert muss größer 0 sein
if( $max_wert <= 0 )
{
	$max_wert = 1;
}

//Daten setzen
$daten_detail['AVATAR'] 				= $avatar;
$daten_detail['USED']					= $general->getUsedSkillPoints();
$daten_detail['FREI']					= $general->getSkillPointsLeft();
$daten_detail['GESAMT']					= $general->getUsedSkillPoints() + $general->getSkillPointsLeft();
$daten_detail['ANGRIFF'] 				= "<img src=\"templates/images/energy_avaiable.gif\" height=\"10\" width=\"".$max_show / $max_wert * $general->getAngriffsbonus()."\">";
$daten_detail['VERTEIDIGUNG'] 			= "<img src=\"templates/images/energy_avaiable.gif\" height=\"10\" width=\"".$max_show / $max_wert * $general->getVerteidigungsbonus()."\">";
$daten_detail['GESCHWINDIGKEIT'] 		= "<img src=\"templates/images/energy_avaiable.gif\" height=\"10\" width=\"".$max_show / $max_wert * $general->getGeschwindigkeitsbonus()."\">";
$daten_detail['ZIELEN'] 				= "<img src=\"templates/images/energy_avaiable.gif\" height=\"10\" width=\"".$max_show / $max_wert * $general->getZielenbonus()."\">";
$daten_detail['WENDIGKEIT'] 			= "<img src=\"templates/images/energy_avaiable.gif\" height=\"10\" width=\"".$max_show / $max_wert * $general->getWendigkeitsbonus()."\">";
$daten_detail['ROHSTOFFOPTIMIERUNG'] 	= "<img src=\"templates/images/energy_avaiable.gif\" height=\"10\" width=\"".$max_show / $max_wert * $general->getRohstoffoptimierung()."\">";
$daten_detail['FORSCHUNG'] 				= "<img src=\"templates/images/energy_avaiable.gif\" height=\"10\" width=\"".$max_show / $max_wert * $general->getForschungsbonus()."\">";

//Daten setzen
$daten_detail['ANGRIFF_WERT']				= $general->getAngriffsbonus(); 
$daten_detail['VERTEIDIGUNG_WERT']			= $general->getVerteidigungsbonus();
$daten_detail['GESCHWINDIGKEIT_WERT']		= $general->getGeschwindigkeitsbonus();
$daten_detail['ZIELEN_WERT']				= $general->getZielenbonus();
$daten_detail['WENDIGKEIT_WERT'] 			= $general->getWendigkeitsbonus();
$daten_detail['ROHSTOFFOPTIMIERUNG_WERT']	= $general->getRohstoffoptimierung();
$daten_detail['FORSCHUNG_WERT']				= $general->getForschungsbonus();

//überprüfen ob noch skillpoints frei sind
if( $general->getSkillPointsLeft() > 0 )
{
	$daten_detail['ANGRIFF2'] 				= "<input id=\"wert1\" onChange=\"setSkillPointsLeft($anzahl)\" type=\"text\" size=\"3\" name=\"werte[]\">";	
	$daten_detail['VERTEIDIGUNG2'] 			= '<input type="text" size="3" name="werte[]">';
	$daten_detail['GESCHWINDIGKEIT2'] 		= '<input type="text" size="3" name="werte[]">';
	$daten_detail['ZIELEN2'] 				= '<input type="text" size="3" name="werte[]">';
	$daten_detail['WENDIGKEIT2'] 			= '<input type="text" size="3" name="werte[]">';
	$daten_detail['ROHSTOFFOPTIMIERUNG2'] 	= '<input type="text" size="3" name="werte[]">';
	$daten_detail['FORSCHUNG2'] 			= '<input type="text" size="3" name="werte[]">';
	$daten_detail['TEXT']					= 'Sie können Skillpunkte auf Ihren Charakter vergeben.';
	$daten_detail['BUTTON'] 				= '<input type="submit" name="send" value="Skillpunkte neu vergeben">';
}
else 
{
	$daten_detail['TEXT']					= 'Sie können keineSkillpunkte auf Ihren Charakter vergeben.';
}

//Templatedaten ersetzen
$tpl_general->setObject("general", $daten_detail);
$daten['CONTENT'] .= $tpl_general->getTemplate();

//footer einbinden
require_once("../includes/footer.php");