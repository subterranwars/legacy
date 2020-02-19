<?php
//Includes
require("../includes/includes.inc.php");

//Datenbankobjekt erzeugen
$db = new DATENBANK();

//Template laden
$tpl = new TEMPLATE("templates/index/index.tpl");
$daten = array(
	'CONTENT'=>'');

//Template laden
$tpl_lostpw = new TEMPLATE("templates/index/lost_pw.tpl");
$daten_lostpw = array(
	'FEHLER' => '',
	'ERFOLG' => '');

//Deklarationen
$error = 1;

//Überprüfen ob Knopf gedrückt wurde
if( isset($_POST['send']) )
{	
	//Einstellungsobjekt erzeugen
	$config = new EINSTELLUNGEN($db);
	
	//Überprüfen ob Username vorhanden ist!
	$error = $config->checkLoginname($_POST['login']);

	//Fehler durchlaufen
	if( $error == -1 )	//Loginname vorhanden !
	{
		//Überprüfen ob Email zum user gehört
		$db->query("SELECT Email FROM t_user WHERE Loginname = '".$_POST['login']."'");
		$email = $db->fetch_result(0);
		
		if( $email == $_POST['email'] )	//Email stimmt
		{
			//Passwort generieren
			$pw = $config->lostPW();
			
			//Passwort ändern
			$db->query("UPDATE t_user SET Passwort = '".md5($pw)."' WHERE Loginname = '".$_POST['login']."'");
			
			//User eine Email zustellen
			$tpl_mail = new TEMPLATE("templates/index/lost_pw_mail.tpl");
			$daten_mail = array("PW"=>$pw);
			$tpl_mail->setObject("lost_pw_mail", $daten_mail);
			$config->sendEmail($config->getAdministrator(), $config->getAdminMail(), $email, $_POST['login'], "Neues Passwort...", $tpl_mail->getTemplate() );
			
			//Ausgabe
			$daten_lostpw['ERFOLG'] = "Das Passwort wurde an die oben angegebene Emailadresse verschickt.";
		}
		//Email nicht vorhanden!
		else
		{
			$error2 = -1;
		}
	}
	else //Loginname nicht gefunden!
	{
		$error2 = -1;
	}
	
	//Sind fehler aufgetreten?
	if( $error2 == -1 )	//Ja
	{
		$fehler = new FEHLER($db);
		$daten_lostpw['FEHLER'] = $fehler->meldung(500);
	}
	
}

//Template laden
$tpl_lostpw->setObject("lost_pw", $daten_lostpw);
$daten['CONTENT'] .= $tpl_lostpw->getTemplate();
$tpl->setObject("index", $daten);

echo $tpl->getTemplate();