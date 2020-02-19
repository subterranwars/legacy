<?php
//Includes
require("../includes/login_check.php");

//Template laden
$tpl_nachrichten = new TEMPLATE("templates/".$_SESSION['skin']."/nachrichten_show.tpl");
$daten_nachrichten = array("DATUM"=>"", "INHALT"=>"", "BETREFF"=>"", "ABSENDER"=>"", "SPALTE"=>"");

switch( $_GET['action'])
{
	case 'inbox':
	{
		//Nachricht laden
		$msg = new NACHRICHT();
		$error = $msg->loadMessage($_GET['ID']);
		
		//Überprüfen ob Nachricht vorhanden ist
		if( $error == -1 )
		{
		    //Fehlerobjekt erzeugen
		    $fehler = new FEHLER($db);
		    echo $fehler->meldung(103);
		}
		//Darf user nachricht lesen?
		elseif( $msg->getEmpfaengerID() != $_SESSION['user']->getUserID() )	
		{
			//Fehlerobjekt erzeugen
			$fehler = new FEHLER($db);
			echo $fehler->meldung(105);
		}
		else 
		{
		    //Nachricht als gelesen makieren
		    $msg->editStatus("gelesen");
		    
			//Nachricht vorhandne und kann geladen werden
		    $daten_nachrichten['SPALTE'] 	= "Absender:";
		    $daten_nachrichten['DATUM'] 	= $msg->getDatum();
		    $daten_nachrichten['BETREFF'] 	= $msg->getBetreff();
		    $daten_nachrichten['ABSENDER'] 	= $msg->getAbsender();
		    $daten_nachrichten['INHALT']	= $msg->getMessage();
		    $daten_nachrichten['ID']		= $msg->getID();
		
		    //Templates ersetzen
		    $tpl_nachrichten->setObject("nachrichten_show", $daten_nachrichten);
		    echo $tpl_nachrichten->getTemplate();
		}
		break;
	}
	
	case 'outbox':
	{
		//Nachricht laden
		$msg = new NACHRICHT();
		$error = $msg->loadMessage($_GET['ID']);
		
		//Überprüfen ob Nachricht vorhanden ist
		if( $error == -1 )
		{
		    //Fehlerobjekt erzeugen
		    $fehler = new FEHLER($db);
		    echo $fehler->meldung(103);
		}
		//Darf user nachricht lesen?
		elseif( $msg->getAbsenderID() != $_SESSION['user']->getUserID() )	
		{
			//Fehlerobjekt erzeugen
			$fehler = new FEHLER($db);
			echo $fehler->meldung(105);
		}
		else 
		{
		    //Nachricht vorhandne und kann geladen werden
		    $daten_nachrichten['SPALTE'] 	= "Empfänger:";
		    $daten_nachrichten['DATUM'] 	= $msg->getDatum();
		    $daten_nachrichten['BETREFF'] 	= $msg->getBetreff();
		    $daten_nachrichten['ABSENDER'] 	= $msg->getEmpfaenger();
		    $daten_nachrichten['INHALT']	= $msg->getMessage();
		    $daten_nachrichten['ID']		= $msg->getID();
		
		    //Templates ersetzen
		    $tpl_nachrichten->setObject("nachrichten_show", $daten_nachrichten);
		    echo $tpl_nachrichten->getTemplate();
		}
		break;
	}
}

//Objekt serialisieren
$_SESSION['user'] 			= serialize($_SESSION['user']);
$_SESSION['kolonie']		= serialize($_SESSION['kolonie']);
$_SESSION['bevoelkerung'] 	= serialize($_SESSION['bevoelkerung']);