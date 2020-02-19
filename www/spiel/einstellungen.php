<?php
//Includes
require("../includes/login_check.php");

//Einstellungsstandardtemplate laden
$tpl_einstellungen = new TEMPLATE("templates/".$_SESSION['skin']."/einstellungen.tpl");
$daten_detail = array("NICKNAME"=>"", "LOGINNAME"=>"", "EMAIL"=>"", "REG_DATE"=>"", "AVATAR"=>"");

//Wurde knopp gdürckt?
if( isset($_POST['senden']) )
{
	//OBjekte erzeugen
	$config = new EINSTELLUNGEN($db);	//Einstellungsobjekt erzeugen
	$fehler = new FEHLER($db);			//Fehlrobjekt erzeugen
			
	//Avatarkontrolle
	if( !empty($_FILES['avatar']['name']) && $_FILES['avatar']['name'] != "none")	//AVatar hochladen
	{
		$error_avatar 	= $config->uploadAvatar($_FILES['avatar']['tmp_name'], $_SESSION['user']);
	
		if( $error_avatar == -1)
		{
			$daten['CONTENT'] = $fehler->meldung(120);
		}
	}
	
	//setze Skin
	$error_skin	= $_SESSION['user']->setSkin($_POST['skin'], "templates/".$_POST['skin']."");
		
	//Fehler überprüfen
	if( $error_skin <= -1 )
	{
		//Skin konnte nichit gefunden werden
		$daten['CONTENT'] = $fehler->meldung(121);
	}
	else //Keine Fehler
	{
		//Aktualisiere Session
		$_SESSION['skin'] = $_POST['skin'];
	}
	
	//Fehler Korrektur!
	$error_mail 	= $config->changeEmail($_POST['email']);
	$error_login 	= $config->changeLoginname($_POST['loginname']);
		
	//Fehler überprüfen
	if( $error_mail <= -1 || $error_login <= -1 )
	{		
		echo "FEHLER!";
		if($error_mail == -1)
		{
			$daten['CONTENT'] = $fehler->meldung(2);
		}
		elseif( $error_mail == -2)
		{
			$daten['CONTENT'] = $fehler->meldung(7);
		}
		elseif ($error_login == -1)
		{
			$daten['CONTENT'] = $fehler->meldung(11);
		}
		elseif( $error_login == -2)
		{
			$daten['CONTENT'] = $fehler->meldung(6);
		}
	}
	else	//Keine Fehler
	{
		//Setze Email und Loginname neu
		$_SESSION['user']->setLoginname($_POST['loginname']);
		$_SESSION['user']->setEmail($_POST['email']);
	}
}

/*Skin-Daten laden und dementsprechend setzen*/
//Template-Verzeichnis auslesen
$fp = opendir("templates");
while( $datei = readdir($fp) )
{
	//$datei darf nicht index sein!
	if( $datei != '.' AND $datei != '..' AND $datei != 'index' )
	{
		//Ist das der ausgewählte skin?
		if( $datei == $_SESSION['skin'] )	//ja
		{
			$daten_detail['SKIN'] .= "<option value=\"$datei\" selected>$datei</option>";
		}
		else 								//nein
		{
			$daten_detail['SKIN'] .= "<option value=\"$datei\">$datei</option>";
		}
	}
}
//Skin-Template setzen
$daten_detail['SKIN'] = "<select name=\"skin\">".$daten_detail['SKIN']."</select>";

//Templatedaten laden
$daten_detail['LOGINNAME']	= $_SESSION['user']->getLoginname();
$daten_detail['NICKNAME'] 	= $_SESSION['user']->getNickname();
$daten_detail['REG_DATE'] 	= $_SESSION['user']->getRegisterDate();
$daten_detail['EMAIL'] 		= $_SESSION['user']->getEmail();
$daten_detail['AVATAR'] 	= "<img src=\"cache/avatar/".$_SESSION['user']->getAvatar()."\">";

//DEtail-Template setzen
$tpl_einstellungen->setObject("einstellungen", $daten_detail);
$daten['CONTENT'] .= $tpl_einstellungen->getTemplate();

//footer einbinden
require_once("../includes/footer.php");
?>