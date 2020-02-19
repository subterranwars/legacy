<?php
//Includes
require_once("../includes/klassen/db.php");
require_once("../includes/klassen/einstellungen.php");
require_once("../includes/klassen/text.php");
require_once("../includes/klassen/fehler.php");
require_once("../includes/klassen/template.php");
require_once("../includes/klassen/rohstoff.php");

//Datenbankverbindung herstellen
$db = new DATENBANK();

//Defaulttemplate laden !
$tpl = new TEMPLATE("templates/index/index.tpl");
$daten = array(
	'CONTENT' => '');

//Configuration laden
$config = new EINSTELLUNGEN($db);

//Fehlerobjekt erzeugen
$fehler = new FEHLER($db);

//Admiral Einstellungen
$admiral_skillpoints = 100;

//Gebäude
$buildings 	= array(
				1		//Hauptquartier
				); 

//Rohstoffe welche User gutgeschrieben bekommt!
$rohstoffe_detail = array(1,2,3,4);
$anzahl_rohstoffe = 10000;

//Alle Rohstoffe!
$res = new ROHSTOFF();
$rohstoffe = $res->getRohstoffArray();

//Bevölkerungsdaten
$bev['Bevölkerung'] 	= 5;
$bev['Wachstumsrate'] 	= 0.07;

//Willkommenstemplate laden und Text setzen
$tpl_welcome = new TEMPLATE("templates/index/welcome_msg.tpl");
$welcome_msg 		= $tpl_welcome->geTtemplate();
$welcome_betreff	= "Herrzlich willkommen.";
 
//Überprüfen ob Pageonline ist!
if( $config->getStatus() == "online" )
{
    //Überprüfen ob Werte gesetzt sind
    if( isset($_GET['ID']) && isset($_GET['CODE']))
    {
    	
		//KEy auf Gültigkeit überprüfen
	    $db->query("SELECT ID_User FROM t_userfreischalten WHERE ID_Key = ".$_GET['ID']." AND Freischaltungscode = '".$_GET['CODE']."';");
	    if( $db->num_rows() > 0 ) //Daten richtig!
	    {
	        //Daten holen
	        $ID_User = $db->fetch_result(0);
	     	$db->query("SELECT ID_User, ID_Rasse FROM t_user WHERE ID_User = $ID_User");
	     	$result = $db->fetch_array();
	     	
	     	//Welcher Rasse gehört User an?
	     	if( $result['ID_Rasse'] == 1 )
	     	{
				//Koordinaten größer 0 für Terraner
	     		$type = "WHERE t_koordinaten.Z > 0";
	     	}
	     	elseif( $result['ID_Rasse'] == 2 )
	     	{
	     		//Koordinaten kleiner 0 für Subterraner
	     		$type = "WHERE t_koordinaten.Z < 0";
	     	}

			//Kolonieanzahl auswählen
	        $db->query("SELECT COUNT(*) FROM t_koordinaten ".$type."");
	        $anzahl = $db->fetch_result(0);
	     	
	    	//Kolonie erstellen!
	    	$error = -1;
	        do
	        {	            
	            //zufallskoordinaten wählen
	            mt_srand(time());
	            $zufall = mt_rand(1, $anzahl);
	           	            
	            $db->query("SELECT t_koordinaten.ID_Koordinaten, t_kolonie.ID_Kolonie FROM t_koordinaten LEFT JOIN t_kolonie USING(ID_Koordinaten) ".$type." LIMIT $zufall, 1;");
	            $koords = $db->fetch_array();
		            
	            //Überprüfen ob die Kolonie vorhanden ist
				if( empty($koords[1]) )
	            {
	                //Kolonie nicht vorhanden!
	                $error = 1;
	
	                //Kolonie dem user geben :D
	                $db->query("INSERT INTO t_kolonie (Bezeichnung, Status, ID_User, ID_Koordinaten) VALUES ('unnamed', 1, ".$result['ID_User'].", ".$koords['ID_Koordinaten'].");");
	                $ID_Kolonie = $db->last_insert();
	            }
	            else 
	            {
	                //Kolonie bereits besetzt
	                $error = -1;
	            }
	        }while( $error == -1 );
	        
	        /*überprüfen ob User auch wirklich eine Kolonie zugewisen bekommen hat!*/
	        if( empty($ID_Kolonie) )
	        {
	        	//Keine Kolonie vorhanden => Fehlermeldung ausgeben
	        	$daten['CONTENT'] = $fehler->meldung(9);
	        }
	        else
	        {	        
	        
		        //Gebäude eintragen
		        foreach( $buildings as $x )
		        {
		            $db->query("INSERT INTO t_userhatgebaeude (ID_User, ID_Gebäude, Level, ID_Kolonie, Auslastung, LastChange) VALUES ( ".$result['ID_User'].", $x, 1, $ID_Kolonie, 1, ".time().");");
		        }
		        //Alle Rohstoffe eintragen
		        for( $i=1; $i<=count($rohstoffe); $i++ )
		        {
		        	$db->query("INSERT INTO t_userhatrohstoffe (ID_User, ID_Rohstoff, Anzahl, LastUpdate, ID_Kolonie) VALUES (".$result['ID_User'].", $i, 0,".time().", $ID_Kolonie);");
		        }
		        //RohstoffDetails eintragen
		        foreach( $rohstoffe_detail as $x )
		        {
		            $db->query("UPDATE t_userhatrohstoffe SET Anzahl = $anzahl_rohstoffe WHERE ID_Rohstoff = $x AND ID_User = ".$result['ID_User']." AND ID_Kolonie = $ID_Kolonie");
		        }
		        
		        //Dem User seine Bevölkerungsdaten setzen
		        $db->query("INSERT INTO t_bevoelkerung VALUES ('', ".$bev['Bevölkerung'].", ".$bev['Wachstumsrate'].", ".time().", ".$result['ID_User'].", ".$ID_Kolonie.")");
		        
		        //Generaldaten setzen
		        $db->query("INSERT INTO t_general VALUES ('', 0,0,0,0,0,0,0,$admiral_skillpoints)");
		        $db->query("UPDATE t_user SET ID_General = ".$db->last_insert()." WHERE ID_User = ".$result['ID_User']."");
		        
		        //Status des Spielers aktuallisieren
		        $db->query("UPDATE t_user SET Status = 'freigeschaltet' WHERE ID_User = ".$result['ID_User'].";");
		        
		        //Spieler eine ingame-nachricht schicken
		        $db->query("INSERT INTO t_ereignis (Titel, Betreff, Datum, Status, ID_User, ID_Kolonie) VALUES ('$welcome_betreff','$welcome_msg',".time().", 'neu', ".$result['ID_User'].", ".$ID_Kolonie.");");
		        
		        //Aktivierungskey löschen
		        $db->query("DELETE FROM t_userfreischalten WHERE ID_Key = ".$_GET['ID'].";");
		        
		        //Template daten laden
				$tpl_activation = new TEMPLATE("templates/index/activation_completion.tpl");
				$daten['CONTENT'] = $tpl_activation->getTemplate();
	        }
	    }
	    else 
	    {
	        //Fehler!
	        $daten['CONTENT'] = $fehler->meldung(9);
	    }
    } 
    else 
    {
        //Fehler!
        $daten['CONTENT'] = $fehler->meldung(9);
    }
    
}
else 
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

//Templatedaten ersetzen
$tpl->setObject("index", $daten);
echo $tpl->getTemplate();