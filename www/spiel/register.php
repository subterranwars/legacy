<?PHP
//Session starten
session_start();

//Includes
require_once("../includes/klassen/einstellungen.php");
require_once("../includes/klassen/db.php");
require_once("../includes/klassen/text.php");
require_once("../includes/klassen/fehler.php");
require_once("../includes/klassen/template.php");
require_once("../includes/klassen/rasse.php");

//Objekte erzeugen
$db 			= new DATENBANK();			//Datenbank
$config	 		= new EINSTELLUNGEN($db);	//Einstellungsobjekt
$fehler 		= new FEHLER($db);			//Fehlerobjekt

//Defaulttemplate laden !
$tpl = new TEMPLATE("templates/index/index.tpl");
$daten = array("CONTENT"=>"");

//Sind neue Registrierungen möglich
if( $config->getNewRegistration() == 'ja' AND $config->getStatus() == 'online' )
{    	    	
	//Alle User selektieren, die vorhanden sind
    $db->query("SELECT COUNT(*) FROM t_user WHERE Nickname != 'RESERVED';");
    $anzahl_reg_users = $db->fetch_result(0);
    
    //Überprüfen ob nicht schon genügend User registriert sind
    if( $config->getAnzahlRegUser()  > ($anzahl_reg_users) )
    {
    	//Template laden
        $tpl_detail = new TEMPLATE("templates/index/register.tpl");
        $daten_detail = array(
        	'NICKNAME' => $_POST['nickname'],
        	'LOGINNAME' => $_POST['loginname'],
        	'EMAIL' => $_POST['email'],
        	'SEL1' => '',
        	'SEL2' =>'',
        	'AGB' => '',
        	'FEHLER' => '');
                  	
    	//Überprüfen ob submit Knopf gedürckt wurde
    	if( isset($_POST['reg']) )
    	{
    		//RassenTemplate setzen (weil evtl. schon selektiert ist!
    		$daten_detail["SEL".$_POST['rasse'].""] = 'selected';
    		
    		//Wenn HTTP_POST_VARS['AGB'] gesetzt, dann AGB-Template = selected
    		if( isset($_POST['agb']) )
    		{
    			$daten_detail['AGB'] = "checked";
    		}
    		            
            //Nickname, Loginname und Email auf schon vorhandenheit überprüfen
            $error_nick 	= $config->checkNickname($_POST['nickname']);
            $error_login 	= $config->checkLoginname($_POST['loginname']);
            $error_email 	= $config->checkEmail($_POST['email']);

            //Fehlerabfrage
            if( $error_nick == -1 || $error_login == -1 || $error_email == -1 || empty($_POST['rasse']) )
            {
                if( $error_nick == -1)
                {
                	//Nickname bereits vergeben
                	$daten_detail['FEHLER'] = $fehler->meldung(6);
                }
                elseif( $error_nick == -2 )
                {
                	//Nickname leer
                	$daten_detail['FEHLER'] = $fehler->meldung(17);
                }
                elseif( $error_login == -1)
                {
                	//Loginname bereits vergeben
                	$daten_detail['FEHLER'] = $fehler->meldung(3);
                }
                elseif( $error_login == -2 )
                {
                	//Loginname leer
              		$daten_detail['FEHLER'] = $fehler->meldung(11);
                }
                elseif( $error_email == -1 )
                {
                	//Emailadresse bereits besetzt
                	$daten_detail['FEHLER'] = $fehler->meldung(7);
                }
                elseif( $error_email == -2 )
                {
                	//Keine Emailadresse gesetzt
                	$daten_detail['FEHLER'] = $fehler->meldung(18);
                }
                 elseif( empty($_POST['rasse']) )
                {
                	//Keine Rasse gewählt
                	$daten_detail['FEHLER'] = $fehler->meldung(14);
                }
            }
            else 
            {
            	//Wurde Nutzungsbedingungen akzeptiert?
            	if( empty($_POST['agb']) )
            	{
            		//Nutzungsbedingung nicth akzeptiert
            		$daten_detail['FEHLER'] = $fehler->meldung(15);
            	}
            	//Ist PAsswortEingabe nicht leer?
            	elseif( empty($_POST['password']) )
            	{
            		//Passwort ist leer!
            		$daten_detail['FEHLER'] = $fehler->meldung(16);
            	}
                elseif( $_POST['password'] == $_POST['password2'] )
                {
                    //Emailadresse auf Korrektheit überprüfen
                   	$error = $config->checkEmail($_POST['email']);
                    if( $error == -1 )
                    {
                        //Email ist nicht korrekt!
                        $daten_detail['FEHLER'] = $fehler->meldung(2);
                    }
                    else 
                    {
                        
                  		//UserDaten eintragen
                  		$db->query(
                  			"INSERT INTO t_user
                  				(Nickname, Loginname, Passwort, Email, RegisterDate, Status, ID_Rasse)
                  			VALUES
                  				('".$_POST['nickname']."', '".$_POST['loginname']."', '".md5($_POST['password'])."', '".$_POST['email']."',".time().",'warten',".$_POST['rasse'].")");
                  		
                  		//Freischaltungscode an den User schicken
               			$ID_User 		= $db->last_insert();
               			$activation_key = $config->getActivationKey();
               			
               			//FreischaltCode in Db schreiben
               			$sql = "INSERT INTO t_userfreischalten (Freischaltungscode, ID_User ) VALUES ('".$activation_key."', $ID_User );";
		                $db->query($sql);
		                $ID_Key = $db->last_insert();
		                
		                 //Emailtemplate laden
		                $tpl_email 			= new TEMPLATE("templates/index/register_mail.tpl");
		                $daten_detail_email = array(
		                	'ID_KEY' => $ID_Key,
		                	'LOGINNAME' => $_POST['loginname'],
		                	'NICKNAME' => $_POST['nickname'],
		                	'PASSWORT' => $_POST['password'],
		                	'ACTIVATIONKEY' => $activation_key); 
		                
		                //Templatedaten ersetzen
		                $tpl_email->setObject("register_mail", $daten_detail_email);
		
		                //Email vesenden
		                $config->sendEmail( $config->getAdministrator(), $config->getAdminMail(), $_POST['email'], $_POST['nickname'], "SubterranWar AccountFreischaltung", $tpl_email->getTemplate() );
		                
		                //Ausgabe machen
		                $tpl_detail 	= new TEMPLATE("templates/index/register_completion.tpl");
		                $daten_detail 	= array("CONTENT" => "".$tpl_detail->getTemplate()."");
                    }
                }
                else 
                {
                    //Passwort 1 und PAsswort 2
                    //müssen übereinstimmen
                    $daten_detail['FEHLER'] = $fehler->meldung(1);
                }
			}
    	}
                
        //Templatedaten ersetzen!
        $tpl_detail->setObject("register", $daten_detail);
        $daten['CONTENT'] = $tpl_detail->getTemplate();
    }
    else 
    {
        //Zu viele User registriert
        $daten['CONTENT'] = $fehler->meldung(5);
    }
}
else 
{
    //Spiel offline
    if( $config->getStatus() != "online" )
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
    	//$daten['CONTENT'] = $fehler->meldung(4);
    }
    else	//KEine neuen Registrierungen möglich!
    {
    	$daten['CONTENT'] = $fehler->meldung(5);
    }
}
//Templatedaten ersetzen
$tpl->setObject("index", $daten);
echo $tpl->getTemplate();