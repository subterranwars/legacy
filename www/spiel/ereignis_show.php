<?php
//Includes
require("../includes/login_check.php");

//Template laden
$tpl_nachrichten = new TEMPLATE("templates/".$_SESSION['skin']."/nachrichten_show.tpl");
$daten_nachrichten = array("DATUM"=>"", "INHALT"=>"", "BETREFF"=>"", "ABSENDER"=>"", "SPALTE"=>"");

//Neues Template laden
$tpl_nachrichten = new TEMPLATE("templates/".$_SESSION['skin']."/ereignis_show.tpl");

//Nachricht laden
$msg = new EREIGNIS();
$error = $msg->loadEreignis($_GET['ID']);

//Überprüfen ob Nachricht vorhanden ist
if( $error == -1 )
{
    //Fehlerobjekt erzeugen
    $fehler = new FEHLER($db);
    echo $fehler->meldung(103);
}
//ISt Ereignis an User addressiert
elseif( $msg->getUserID() != $_SESSION['user']->getUserID() )
{
	//Fehlerobjekt erzeugen
    $fehler = new FEHLER($db);
    echo $fehler->meldung(105);	//Nachricht nicht an Sie addressiert
}
else 
{
    //Status als gelesen makieren
    $msg->editStatus("gelesen");
    
	//Nachricht vorhandne und kann geladen werden
    $daten_nachrichten['SPALTE'] 	= "Koordinaten:";
    $daten_nachrichten['DATUM'] 	= $msg->getDatum();
    $daten_nachrichten['BETREFF'] 	= $msg->getBetreff();
    $daten_nachrichten['ABSENDER'] 	= $msg->getKoordinaten();
    $daten_nachrichten['INHALT']	= $msg->getInhalt();

    //Templates ersetzen
    $tpl_nachrichten->setObject("nachrichten_show", $daten_nachrichten);
    echo $tpl_nachrichten->getTemplate();
}
//Objekt serialisieren
$_SESSION['user'] 			= serialize($_SESSION['user']);
$_SESSION['kolonie'] 		= serialize($_SESSION['kolonie']);
$_SESSION['bevoelkerung'] 	= serialize($_SESSION['bevoelkerung']);