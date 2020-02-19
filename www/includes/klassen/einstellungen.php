<?php
/*einstellungen.php
Diese Klasse stellt diverse Einstellungsmöglichkeiten zur Verfügung.
Mit ihr kann zum Beispiel die AdministratorEmail aus der Datenbank geladen werden und diverse andere 
Dinge.
Zum Beispiel:
	- UserPasswort verändern
	- Emailüberprüfung
	- Loginüberprüfung etc.

History:
			23.09.2004		Markus von Rüden	updated
*/
class EINSTELLUNGEN
{
	//Objektvariablen
    var $db;							//Datenbankobjekt!
	var $img_height = 200;				//maximale Höhe eines Bildes
	var $img_width = 200;				//maximale Breite eines Bildes
	var $img_folder = "cache/avatar";	//In welcem Verzeichnis werden die Avatare gespeichert?
	var $activationkey_length = 50;		//Wie viele Zeichen hat der Aktivierungsschlüssel?
	var $min_pw_length	= 7;			//minimale Passwort länge
	var $Administrator;					//Name des Administrators
	var $AdminMail;						//Wie latuet die AdministratorEmail
	var $Status;						//Ist Spiel online|offline
	var $Grund;							//Warum ist Spiel offline?
	var $AnzahlRegUser;					//Wie viele User sind registriert?
	var $NewRegistration;				//Sind neue Registrierungen erlabut? (ja|nein)

	//Standardkonstruktor
	function EINSTELLUNGEN(&$db)
    {
    	//Datenbankobjekt setzen
        $this->db 	= &$db;
        
        //Werte laden
        $this->db->query("SELECT * FROM t_config");
        $ergebnis = $this->db->fetch_array();
        
        //Werte setzen!
        $this->Administrator 	= $ergebnis['Administrator'];
        $this->AdminMail		= $ergebnis['AdminMail'];
        $this->Status			= $ergebnis['Status'];
        $this->Grund			= $ergebnis['Grund'];
        $this->AnzahlRegUser	= $ergebnis['AnzahlRegUser'];
        $this->NewRegistration	= $ergebnis['NewRegistration'];
    }
    
    /*Funktion gibt ADministratorName zurück*/
    function getAdministrator()
    {
    	return $this->Administrator;
    }
    
    /*Funktion gibt AdminMail zurück*/
    function getAdminMail()
    {
    	return $this->AdminMail;
    }
    
    /*Funktion gibt den Onlinestatus zurück*/
    function getStatus()
    {
    	return $this->Status;
    }
    
    /*Funktion setzt den Onlinestatus*/
    function setStatus($status)
    {
    	$this->db->query("UPDATE t_config SET Status = '$status'");
    	$this->Status = $status;
    }
    
    /*WEnn das Spiel offline is, dann muss der Grund zurückgegeben werden*/
    function getGrund()
    {
    	return $this->Grund;
    }
    
    /*Funktion setzt Grund, warum Spiel offline ist*/
    function setGrund($text)
    {
    	$this->db->query("UPDATe t_config SET Grund = '$text'");
    	$this->Grund = $text;
    }
    
    /*Funktion gibt die Anzahl der registrierten Benutzer zurück*/
    function getAnzahlRegUser()
    {
    	return $this->AnzahlRegUser;
    }
    
    /*Funktion gibt den Status über NewRegistraion zurück*/
    function getNewRegistration()
    {
    	return $this->NewRegistration;
    }
    
    //ERstellt ein zufälliges passwort :)
    function lostPW()
    {
    	$string = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $length = strlen($string);
        
        mt_srand(time()*3.3);
        for( $i = 0; $i < $this->min_pw_length; $i++ )
        {
            $new_pw .= $string[mt_rand(0, $length)];
        }
        return $new_pw;
    }
    
    function getActivationKey()
    {
        $string = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $length = strlen($string);
        
        mt_srand(time()*3.3);
        for( $i = 0; $i < $this->activationkey_length; $i++ )
        {
            $key .= $string[mt_rand(0, $length)];
        }
        return $key;

    }
    
    function checkEmail($email)
    {
    	//Deklaration
    	$error = 1;
    	
        if( empty($email) )
        {
            $error = -2;
        }
        else 
        {
            $this->db->query("SELECT Email FROM t_user WHERE Email = '$email'");
            $emailSel = $this->db->fetch_result(0);
            if( strtolower($email) == strtolower($emailSel) )
            {
                $error = -1;
            }
        }
        return $error;

    }
    
    function checkLoginname($login)
    {
        $error = 1;
        
    	if( empty($login) )
        {
            $error = -2;
        }
        else 
        {
            $this->db->query("SELECT Loginname FROM t_user WHERE Loginname = '$login'");
            $login_select = $this->db->fetch_result(0);
            if( strtolower($login) == strtolower($login_select) )
            {
                $error = -1;
            }
        }
        return $error;
    }
    
    function changeLoginname($nick)
    {
    	//Deklarationen
    	$error = 1;
    	
    	//gucken ob Übergabeparameter gesetzt sind
    	if( empty($nick) )
    	{
    		//Leerer Nickname
    		$error = -1;
    	}
    	else
    	{
    		$this->db->query("SELECT * FROM t_user WHERE Nickname = '$nick';");
    		$anzahl = $this->db->num_rows();
    		
    		//Nickname darf nur 1x vorhanden sein!
    		if($anzahl > 1 )
    		{
    			$error = -2;
    		}   		
    	}
    	return $error;
    }
    
    function changeEmail($email)
    {
    	//Deklarationen
    	$error = 1;
    	
    	if( empty($email) )
    	{
    		$error = -1;
    	}
    	else
    	{
    		$this->db->query("SELECT * FROM t_user WHERE Email = '$email';");
    		
    		if($this->db->num_rows() > 1)
    		{
    			$error = -2;
    		}
    	}
    	return $error;
    }
    
    function checkNickname($nick)
    {
        if( empty($nick) )
        {
            $error = -2;
        }
        else 
        {
            $this->db->query("SELECT Nickname FROM t_user WHERE Nickname = '$nick'");
            $nick_select = $this->db->fetch_result(0);
            if( strtolower($nick) == strtolower($nick_select) )
            {
                $error = -1;
            }
        }
        return $error;
    }
    
    function checkEmailAdress($email)
    {
        $error = 0;
        return $error;
    }
    
   	function sendEmail( $absender_name, $absender_email, $empfaenger_email, $empfaenger_name, $betreff, $message )
    {
        //Email-Einstellungen
        $from_name = $absender_name; 
        $from_email = $absender_email; 
        $to_name = $empfaenger_name; 
        $to_email = $empfaenger_email; 
        
        //Absender
        $empfaenger = "$to_name <$to_email>";
        
        //Header für Email erzeugen
        $header  = "MIME-Version: 1.0\n";
        $header .= "Content-type: text/html; charset=iso-8859-1\n";
        //Absender in Header speichern
        $header .= "From: $from_name <$from_email>\n";
        $header .= "Reply-to: $from_email\n";
        
        //Email versenden
        mail($empfaenger, $betreff, $message, $header);
    }
    
    function uploadAvatar( $tmp, &$user )
    {
        /*
        0. Breite
        1. Höhe
        2. Typ: 1=GIF, 2=JPEG, 3=PNG
        3. Größenangaben im IMG-Tag (z.B. 'width="111" height="24"')*/

        //Deklarationen
        $error = 1;
        $ID = $user->getUserID();
        
        //Bilddaten laden
        $img_daten = getimagesize($tmp);
        if( $img_daten[2] == 2 )	//Ist Bild jpeg?
        {
            //hochladen
            $filename = $this->img_folder."/".$ID.".jpg";
            copy($tmp, $filename);
            chmod($filename, 0777);
            
            //Größe des Bildes überprüfenund ggf. bild verkleinern
            if( $img_daten[0] > $this->img_width && $img_daten[1] > $this->img_height )
            {
            	//verkleinern
                $this->resizeAvatar($filename);
            } 
			$user->setAvatar("$ID.jpg");
        }
        else 
        {
            //Bildtyp wird nich unterstützt
            $error = -1;
        }
        return $error;
    }

    function resizeAvatar($filename)
	{						
							/*0. Breite
							1. Höhe
							2. Typ: 1=GIF, 2=JPEG, 3=PNG

							3. Größenangaben im IMG-Tag (z.B. 'width="111" height="24"')*/		
		//Altes bild laden
		$image_alt = ImageCreateFromJpeg($filename);
								
		//Prozente bestimmen
		$percents = $this->img_width / imagesx($image_alt);
			
		//Bildgröße und Breite bestimmen!        
        $image_new_width 	= $this->img_width;
        $image_new_height 	= imagesy($image_alt) * $percents;
                
        //Neues Bild erzeugen
        $image_new = ImageCreateTrueColor($image_new_width, $image_new_height);
        
        //Bild verkleinern :)
        ImageCopyResampled($image_new, $image_alt, 0,0,0,0, $image_new_width, $image_new_height, imagesx($image_alt), imagesy($image_alt));
        ImageJpeg($image_new, $filename);
        ImageDestroy($image_new);
    }
    
    function changePassword($old_eingabe, $old_echt, $new, $new2)
    {
        //Deklarationen 
        $error = 1;
        
        //Überprüfen ob die Alte Passworteingabe mit der Neuen übereinstimmt
        if( md5($old_eingabe) == $old_echt )
        {
            //ist new und new2 identisch?
            if( empty($new) || empty($new2) )
            {
            	$error = -3;
            }
            elseif( $new != $new2 )
            {
                //Paswortwiederholung faslch
                $error = -2;
            }
            else 
            {
                //Alles supie :)
                //Kein Fehler
            }
        }
        else 
        {
            //Altes Passwort falsch
            $error = -1;
        }
        return $error;
    }
}?>