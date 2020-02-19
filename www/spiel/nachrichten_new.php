<?php
//Includes
require("../includes/login_check.php");

//Template laden
$tpl_nachrichten = new TEMPLATE("templates/".$_SESSION['skin']."/nachrichten_new.tpl");
$daten_nachrichten = array(
	'INHALT' => '',
	'EMPFAENGER' => '',
	'BETREFF' => '',
	'FEHLER' => '',
	'SMILEYS' => '');

//Gucken ob Empfaenger und Inhaltsdaten übergeben wurden... ist dann der fall, 
//Wenn auf eine nachricht geantwortet wird
if( isset($_POST['name']) )
{
	//Nachrichtenobjekt erzeugen
	$msg = new NACHRICHT();
	$msg->loadMessage($_POST['ID']);
	
	//Templatedaten setzen
	$daten_nachrichten['EMPFAENGER'] 	= $_POST['absender'];
    $daten_nachrichten['INHALT']	 	= "[quote]".$msg->getUnformattedMessage()."[/quote]";
    $daten_nachrichten['BETREFF']		= "Re: ".$msg->getUnformattedBetreff();
}

//wird das Script von der Userübersicht aufgerufen?
if( isset($_GET['empfaenger']) )
{
	$daten_nachrichten['EMPFAENGER']	= $_GET['empfaenger'];
}

//Wurde formular auf Grund von Fehlern aufgerufen?
if( isset($_POST['send']) )
{
	//ID des Empfängers holen
	$db->query("SELECT ID_User FROM t_user WHERE Nickname = '".$_POST['empfaenger']."';");
	$ID_Empfaenger = $db->fetch_result(0);
		
	//NachrichtenObjekt erzeugen
	$msg = new NACHRICHT();

	//Nachricht speichern
	$error = $msg->saveMessage($_POST['betreff'], $_POST['inhalt'], $_SESSION['user']->getUserID() ,$ID_Empfaenger );
	
	//Überprüfen ob Fehler aufgetreten sind
	if( $error < 1 )	//Fehler aufgetreten
	{
		//Fehlerobjekt erzeugen
		$fehler = new FEHLER($db);
		
		switch($error)
		{
			case -1:
				$daten_nachrichten['FEHLER'] .= $fehler->meldung(100);
				break;
			case -2:
				$daten_nachrichten['FEHLER'] .= $fehler->meldung(101);
				break;
			case -3:
				$daten_nachrichten['FEHLER'] .= $fehler->meldung(102);
				break;
			case -4:
				$daten_nachrichten['FEHLER'] .= $fehler->meldung(104);
				break;
		}
		
		//Überprüfen ob Fehler aufgetreten sind
		$daten_nachrichten['EMPFAENGER'] 	= $_POST['empfaenger'];
    	$daten_nachrichten['INHALT']	 	= $_POST['inhalt'];
    	$daten_nachrichten['BETREFF'] 		= $_POST['betreff'];
	}
	else
	{
		echo "Nachricht versendet";
	}
}

/*Smileys laden*/
$text = new TEXT();
$smileys = $text->getReplacements();

//Zeige 25 Smileys an!
for( $i=0; $i<34; $i++ )
{
	$daten_nachrichten['SMILEYS'] .= "<a href=\"#\" onClick=\"addSmiley('".$smileys[$i][0]."')\">".$text->replaceVars($smileys[$i][1])."</a> ";
}

//Objekt serialisieren
$_SESSION['user'] 			= serialize($_SESSION['user']);
$_SESSION['kolonie'] 		= serialize($_SESSION['kolonie']);
$_SESSION['bevoelkerung'] 	= serialize($_SESSION['bevoelkerung']);

//TPL-Daten setzen
$tpl_nachrichten->setObject("nachrichten_new", $daten_nachrichten);
echo $tpl_nachrichten->getTemplate();?>