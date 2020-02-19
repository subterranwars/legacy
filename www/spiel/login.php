<?php
//SEssion starten
session_start();

//Includes
require("../includes/includes.inc.php");

//Deklarationen
$expire = 60*60;	//Zeit, wann die Session abläuft

//Template laden
$tpl = new TEMPLATE("templates/index/index.tpl");
$daten = array(
	'CONTENT' => '');
	
//Login - Template laden
$tpl_login = new TEMPLATE("templates/index/login.tpl");
$daten_login = array(
	'FEHLER' => '');

//Objekte erzeugen
$db 	= new DATENBANK();			//DB-OBjekt
$fehler = new FEHLER($db);			//FehlerObjekt
$config = NEW EINSTELLUNGEN($db);	//EinstellungsObjekt

/*ISt LoginButton gedrückt worden?*/
if( isset($_POST['login']) )
{
	//Username selektieren
    $sql  = "SELECT ID_User, Status FROM t_user WHERE Loginname = '".$_POST['stw_login']."'";
    $sql .= "AND Passwort = '".md5($_POST['stw_password'])."';";
	$db->query($sql);
    
    //Ist user vorhanden?
    if( $db->num_rows() > 0 )	//Ja!
    {    	
		//Ergebnis speichern!
    	$result = $db->fetch_array();  

        //Überprüfen, dass User auch freigeschaltet ist!
        if( $result['Status'] == "freigeschaltet" )
        {
        	//Alle User löschen, welche bereits abgelaufen sind!
        	$db->query("DELETE FROM t_useronline WHERE Expire <= ".time()."");
        	
        	//Überprüfen ob User bereits eingeloggt is!
        	$db->query("SELECT COUNT(*) FROM t_useronline WHERE ID_User = ".$result['ID_User']."");
        	$anzahl = $db->fetch_result(0);
       	
        	//Wenn User eingeloggt ist, diesen User ausloggen!
        	if( $anzahl > 0 )
        	{
        		$db->query("UPDATE t_useronline SET Expire = 0 WHERE ID_User = ".$result['ID_User']."");
        	}
        	
        	//User Objekt erzeugen
        	$_SESSION['user'] = new USER($result['ID_User']);

        	//User als eingeloggt setzen
            $_SESSION['user']->setEingeloggt("japp");
            $_SESSION['user']->setLastLogin();
                        
            //Hole MainKolonie
            $_SESSION['kolonie'] = new KOLONIE($_SESSION['user']->getMainKolonie(), $db);

            //LadeBevölkerung
            $_SESSION['bevoelkerung'] = new BEVOELKERUNG($db, $_SESSION['user'], $_SESSION['kolonie']->getID());

            //User als eingeloggt makieren!
            $db->query("INSERT INTO t_useronline (ID_User, IP, Expire) VALUES (".$_SESSION['user']->getUserID().", '".$_SERVER['REMOTE_ADDR']."', ".(time()+$expire).")");
			$_SESSION['ID_Online']	= $db->last_insert();

			/*Skin ermitteln*/
			$_SESSION['skin'] = $_SESSION['user']->getSkin();
			
			//Ist Skin leer bzw vorhanden??
			if( empty($_SESSION['skin']) || !is_dir("templates/".$_SESSION['skin']."") )
			{
				$_SESSION['skin'] = 'stw_v02';
			}
			  
          	/*Objekte serialisieren*/
        	$_SESSION['user'] 			= serialize($_SESSION['user']);
        	$_SESSION['kolonie'] 		= serialize($_SESSION['kolonie']);
        	$_SESSION['bevoelkerung']	= serialize($_SESSION['bevoelkerung']);
                 
            header("Location: spiel.php");	//Weiterleiten
            exit;							//WEitere Ausführung unterbinden
        }
        else 
        {
            if( $result['Status'] == "gesperrt" )
            {
               //Account gesperrt
                $daten_login['FEHLER'] = $fehler->meldung(201);
            }
            else 
            {
                //Account nicht freigeschaltet
                $daten_login['FEHLER'] = $fehler->meldung(200);
            }
        }
    }
    else 
    {
        //Logindaten falsch
        $daten_login['FEHLER'] = $fehler->meldung(8);
    }
}

/*Ist Spiel offline?*/
if( $config->getStatus() == 'online' )	//ja
{
	/*Ist FehlerCode übergeben worden?*/
	if( isset($_GET['error']) )
	{
		//Setze Fehler!
		$daten_login['FEHLER'] = $fehler->meldung($_GET['error']);
	}
	
	//Template ausgeben
	$tpl_login->setObject('login', $daten_login);
	$daten['CONTENT'] = $tpl_login->getTemplate();
}
else 									//nein
{
	//OfflineTepmlate laden
	$tpl_detail = new TEMPLATE("templates/index/spiel_offline.tpl");
	$daten_detail = array(
		'GRUND' => '');
		
	//Grund laden
	$grund = $config->getGrund();
	if( empty($grund) )
	{
		$daten_detail['GRUND'] = "Keine Begründung eingetragen";
	}
	else 
	{
		$daten_detail['GRUND'] = $config->getGrund();
	}
		
	//Templatedaten ersetzen
	$tpl_detail->setObject("spiel_offline", $daten_detail);
	$daten['CONTENT'] = $tpl_detail->getTemplate();
}

//Templatedaten aktualisieren
$tpl->setObject("index", $daten);
echo $tpl->getTemplate();

//Speicheraufräumen
unset($fehler);
unset($db);
unset($config);
unset($tpl);
unset($daten);?>