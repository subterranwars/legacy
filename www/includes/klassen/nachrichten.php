<?PHP
/*nachrichten.php

History:
			25.01.2004	MvR		created
*/

class NACHRICHT 
extends TEXT
{
	//Deklarationen
	var $db;
	var $ID;
	var $ID_Absender;
	var $Absender;
	var $ID_Empfaenger;
	var $Empfaenger;
	var $Betreff;
	var $Inhalt;
	var $Datum;
	var $Status;
	var $Deleted;
    	
	function NACHRICHT()
    {
        $this->db = new DATENBANK();
    	parent::TEXT($this->db);
    }
    
    function selectAbsender()
    {
        $this->db->query("SELECT Nickname FROM t_user WHERE ID_User = $this->ID_Absender;");
        return $this->db->fetch_result(0);
    }
    
    function selectEmpfaenger()
    {
        $this->db->query("SELECT Nickname FROM t_user WHERE ID_User = $this->ID_Empfaenger;");
        return $this->db->fetch_result(0);
    }
    
    function delMessage($wer)
		/*$wer kann absender oder empfaenger sein*/
    {
    	if( $this->Deleted != "keiner" && $this->Deleted != $wer )
        {
        	//Deleted enthält empfaenger oder absender, kann also hiermit gelöscht werden
            $this->db->query("DELETE FROM t_nachrichten WHERE ID_Nachricht = $this->ID;");
        }
        else 
        {
            //$this->Deleted ist leer, kann also auf $wer gesetzt werden
            $this->editDeleted($wer);
        }
    }
    
    function getMessage()
    {
        return parent::replacement( nl2br($this->Inhalt) );
    }
    
    //Gibt den Inhalt ohne Formatierungen zurück, d.h. :) wird nich durch das bild ersetzt etc.
    function getUnformattedMessage()
    {
    	return $this->Inhalt;
    }
    
	function getBetreff()
    {
    	return  parent::replacement($this->Betreff);
    }
    
    //Funktion gibt den Betreff nicht als <img src=""> zurück, sondern als Zeichen, sprich :) etc.
    function getUnformattedBetreff()
    {
    	return $this->Betreff;
    }
   
    function getAbsender()
    {
        return $this->Absender;
    }
    
    function getAbsenderID()
    {
        return $this->ID_Absender;
    }
    
    function getEmpfaenger()
    {
        return $this->Empfaenger;
    }
    
    function getEmpfaengerID()
    {
        return $this->ID_Empfaenger;
    }
    
    function getDatum()
    {
        return date("D, d.m.Y - H:i:s",$this->Datum);
    }
    
    function getStatus()
    {
        return $this->Status;
    }
    
    function getID()
    {
        return $this->ID;
    }
    
    function getDeleted()
    {
        return $this->Deleted;
    }
    
    function editDeleted($status)
    {
        $this->db->query("UPDATE t_nachrichten SET Deleted = '$status' WHERE ID_Nachricht = $this->ID");
        $this->Deleted = $status;
    }
    
    function editStatus($status)
    {
        $this->db->query("UPDATE t_nachrichten SET Status = '$status' WHERE ID_Nachricht = $this->ID");
        $this->Status = $status;
    }
    
    function loadMessage( $ID )
    {
        //Deklarationen
        $error = 1;
               
        $this->ID = $ID;
        $this->db->query("SELECT * FROM t_nachrichten WHERE ID_Nachricht = $this->ID;");
        
        //Überprüfen ob Eintrag vorhanden ist
        if( $this->db->num_rows() > 0 )
        {
            //Eintrag vorhanden
            $ergebnis = $this->db->fetch_array();
            
            $this->ID_Absender		= $ergebnis['ID_UserAbsender'];
            $this->Absender 		= $this->selectAbsender();
            $this->ID_Empfaenger 	= $ergebnis['ID_User'];
            $this->Empfaenger 		= $this->selectEmpfaenger();
            $this->Betreff 			= $ergebnis['Betreff'];
            $this->Inhalt 			= $ergebnis['Inhalt'];
            $this->Datum 			= $ergebnis['Datum'];
            $this->Status 			= $ergebnis['Status'];
            $this->Deleted 			= $ergebnis['Deleted'];
        }
        else 
        {
            //Eintrag nicht vorhandne
            $error = -1;
        }
        return $error;
    }
    
    function saveMessage($Betreff, $Inhalt, $ID_Absender, $ID_Empfaenger)
    {
        //Deklarationen
        $error = 1;
	
        if( empty($ID_Empfaenger) )
        {
        	//Kein Empfaenger! 
        	$error = -2;
        }
        else
        {
	        $this->ID_Absender 		= $ID_Absender;
	        $this->ID_Empfaenger 	= $ID_Empfaenger;
	        
	        $error_absender		= $this->selectAbsender();
	        $error_empfaenger	= $this->selectEmpfaenger();
	        
	        //aufpassen das empfaenger id nicht kleiner als 10 sein darf... !!! WICHTIG !!!! ID_Empfanger >10
	        if( ($error_absender == 0 || $error_empfaenger == 0) && $this->ID_Empfaenger <= 10)
	        {
	            if( $error_absender == 0 )
	            {
	                //Fehler: 100
	                $error = -1;
	            }
	            elseif ($error_absender == 0 )
	            {
	                //Fehler 101
	                $error = -2;
	            }
	            else
	            {
	            	$error = -2;
	            }
	        }
	        else 
	        {
	            if( $this->ID_Absender == $this->ID_Empfaenger )
	            {
	                //Fehler 102
	                $error = -3;
	            }
	            else 
	            {
	                if( empty($Betreff) )
	                {
	                    $Betreff = "kein Betreff";
	                }
	                if( empty($Inhalt) )
	                {
	                    //Fehler 104
	                    $error = -4;
	                }
	                else 
	                {
	                    $this->Betreff 			= trim(strip_tags(addslashes($Betreff)));
	                    $this->Inhalt			= trim(strip_tags(addslashes($Inhalt)));
	                    $this->Absender 		= $this->selectAbsender();
	                    $this->Empfaenger		= $this->selectEmpfaenger();
	                    $this->Datum			= time();
	                    $this->Status 			= 'neu';
	                    $this->Deleted			= 'keiner';
	                    
	                    $sql  = "INSERT INTO t_nachrichten (Betreff, Inhalt, Datum, Status, Deleted, ID_User, ID_UserAbsender)";
	                    $sql .= " VALUES ('$this->Betreff', '$this->Inhalt', $this->Datum, '$this->Status', '$this->Deleted', $this->ID_Empfaenger, $this->ID_Absender);";
	                    
	                    $this->db->query($sql);
	                }
	            }
	        }
        }
        return $error;
    }
}?>