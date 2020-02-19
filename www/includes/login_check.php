<?php
//Includes
require_once("../includes/includes.inc.php");

//Session starten
session_start();


//Objekte erzeugen
$db 		= new DATENBANK();			//Datenbankobjekt
$config 	= new EINSTELLUNGEN($db);	//Einstellungsobjekt

//Überprüfen ob der Aufruf von stw.marskuh.de o.ä. is
/*if( $_SERVER['HTTP_HOST'] == "stw.marskuh.de" || $_SERVER['HTTP_HOST'] == "www.stw.marskuh.de" )
{*/
	//Ist Page online?
	if( $config->getStatus() == "online" )
	{
	    //Überprüfen ob $_SESSION['user'] gesetszt ist!
	    if( isset($_SESSION['user']) && is_string($_SESSION['user']) && !empty($_SESSION['user']))
	    {
	    	
			//Template laden
			$tpl = new TEMPLATE("templates/".$_SESSION['skin']."/spiel.tpl");
			$daten = array(
				'CONTENT' => '',
				'ROHSTOFFE' => '',
				'SERVER_TIME' => '',
				'AUSFUEHRUNG' => '',
				'KOLONIE_STATUS' => '');
			$daten['SERVER_TIME'] = date("D, d.m.Y H:i:s",time());

	    	/*Session ist gesetzt, user-objekt wiederholen und Datenbankverbindung wieder
	    	herstellen*/
	    	//UserOBjekt
	    	$_SESSION['user'] = unserialize($_SESSION['user']);
	    	$_SESSION['user']->setDB($db);
	    	//KolonieObjekt
	    	$_SESSION['kolonie'] = unserialize($_SESSION['kolonie']);
	    	$_SESSION['kolonie']->setDB($db);
	    	//BevölkerungsObjekt
	    	$_SESSION['bevoelkerung'] = unserialize($_SESSION['bevoelkerung']);
	    	$_SESSION['bevoelkerung']->setDB($db);
	    	$_SESSION['bevoelkerung']->setUser($_SESSION['user']);
	    	   
	    	/*Alle User laden, welche nicht mehr eingeloggt sind!*/
	    	$db->query("DELETE FROM t_useronline WHERE Expire <= ".time()."");
	    	
	    	/*Lade UserOnlineDaten aus Datenbank!*/
	    	$db->query("SELECT ID_User, IP, Expire FROM t_useronline WHERE ID_Online = ".$_SESSION['ID_Online']."");
	    	$ergebnis = $db->fetch_array();
	    		
	    	//Login_Daten überprüfen
		    if( $_SESSION['user']->getEingeloggt() == "japp" AND $ergebnis['Expire'] > time() AND $ergebnis['Expire'] > 0 AND $ergebnis['IP'] == $_SERVER['REMOTE_ADDR'] )
		    {
		    	/*Session is noch gültig!
		        Und nun wird die zu includierende
		        Datei aufgerufen*/
		        require("../includes/to_do.php");
		    }
		    else 
		    {
		        if( $_SESSION['user']->getEingeloggt() != "japp" )
		        {
		        	//User nicht eingeloggt
		            header("Location: login.php?error=110");	//Weiterleiten
		        	exit;										//Weitere Ausführung unterbinden
		        }
		        elseif( $ergebnis['IP'] != $_SERVER['REMOTE_ADDR'] && !empty($ergebnis['IP']) )
		        {
		        	/*IP Adresse hat sich geändert!*/
		        	header("Location: login.php?error=151");	//WEiterleiten
		        	exit;										//weitere Ausfürhung unterbinden
		        }
		        else 
		        {
		            /*Session abgelaufen*/
		        	header("Location: login.php?error=150");	//Weiterleiten
		        	exit;										//Weitere Ausführung unterbinden
		        }
		    }
	    }
	    else
	    { 
	    	/*Session nicht gestartet*/
	    	header("Location: login.php?error=110");			//Weiterleiten
		    exit;												//Weitere Ausführung unterbinden
	    }   	
	}
	else 
	{
		/*Wegen Wartungsarbeiten offline!*/
	    header("Location: login.php?error=4");				//weiterleiten
	    unset($_SESSION);									//Session variablen löschen
	    exit;												//Weitere Ausführung unterbinden!
	}/*
}
else 
{
	//Seite wurde nicht von stw.marskuh.de aufgerufen
	echo "Bitte loggen Sie sich über www.stw.marskuh.de in das Spiel ein und versuchen Sie nicht
		von ausserhalb auf das Spiel zuzugreifen. Dies wird nicht geduldet";
	exit;
}*/