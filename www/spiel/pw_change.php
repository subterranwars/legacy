<?php
//Includes
require("../includes/login_check.php");

//Template laden
$tpl_pwchange = new TEMPLATE("templates/".$_SESSION['skin']."/pw_change.tpl");
$daten_pwchange = array(
	'FEHLER' => '',
	'BESTAETIGUNG' => '');

//Überprüfen ob Knopf gedrückt wurde
if( isset($_POST['send']) )
{
	//Einstellungsobjekt erzeugen
	$config = new EINSTELLUNGEN($db);
	
	//Neues Passwort speichern!
	$error = $config->changePassword($_POST['pw_old'], $_SESSION['user']->getPasswort(), $_POST['pw_new'], $_POST['pw_new2']);

	//Fehler durchlaufen
	if( $error < 1 )
	{
		//Fehler vorhanden, objekt erzeugen
		$fehler = new FEHLER($db);
		
		switch($error)
		{
			//Altes passwort falsch
			case -1:
				$daten_pwchange['FEHLER'] = $fehler->meldung(12);
				session_destroy();
				break;
			//Passwörter stimmen nicht überein
			case -2:
				$daten_pwchange['FEHLER'] = $fehler->meldung(1);
				break;
			//Passwörter sind zu kurz, oder leer
			case -3:
				$daten_pwchange['FEHLER'] = $fehler->meldung(13);
				break;
		}
	}
	else //Keine Fehler
	{
		//Bestätigung setzen!
		$daten_pwchange['BESTAETIGUNG'] = "Ihr Passwort wurde erfolgreich geändert. Bitte vergessen Sie es nicht, da bei Verlust aus Sicherheitsgründen ein Neues generiert werden muss!";
		//Passwort ändern
		$_SESSION['user']->setPasswort(md5($_POST['pw_new']));
	}
}
//Template laden
$tpl_pwchange->setObject("pw_change", $daten_pwchange);
$daten['CONTENT'] .= $tpl_pwchange->getTemplate();

//footer einbinden
require_once("../includes/footer.php");