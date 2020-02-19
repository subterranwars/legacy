<?php
//Überprüfen ob User eingeloggt is
require_once(_PFAD."/login_check.php");

//Deklarationen
$time = time();		//Zeit setzen, welche für alle OPerationen gleich sein muss!
mt_srand($time);	//Zufallsgenerator einleiten!

/*Funktion gibt Microsekunden zurück!*/
function getMicrotime()
{
	$microtime = explode(" ", microtime());
	return (float)$microtime[0] + (float)$microtime[1];
}

//Wann wurde das Script gestartet?
$anfangs_zeit = getMicrotime();

//Neue To_Do-Klasse anlegen				
					
$to_do = new TO_DO($db, $_SESSION['user'], $_SESSION['kolonie'], $_SESSION['bevoelkerung']);
$to_do->durchlaufe_Ereignisse($time);