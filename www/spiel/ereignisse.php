<?php
//Includes
require("../includes/login_check.php");

//Template laden
$tpl_nachrichten = new TEMPLATE("templates/".$_SESSION['skin']."/nachrichten.tpl");
$tpl_detail = new TEMPLATE("templates/".$_SESSION['skin']."/nachrichten_detail.tpl");

$daten_nachrichten = array(
	'CONTENT' => '',
	'INBOX_GESAMT' => '',
	'INBOX_NEU' => '',
	'OUTBOX_GESAMT' => '',
	'OUTBOX_NEU' => '',
	'EREIGNISSE_GESAMT' => '',
	'EREIGNISSE_NEU' => '');

$daten_detail = array(
	'DATUM' => '',
	'BETREFF' => '',
	'ABSENDER' => '',
	'ID' => '');

$daten_column = array(
	'SPALTE'=>'',
	'PHP_SELF' => $_SERVER['PHP_SELF']);
	
/*Überprüfen ob Löschen knopf gedrückt wurde!*/
if( isset($_POST['to_do']) )
{
	//Löschanweisungen durchlafuen!
	switch( $_POST['submit_del'] )
	{
		//Makierte einträge löschen
		case 1:
			for( $i=0; $i<count($_POST['del']); $i++ )
			{
				//Ereignisobjekt erzeugen!
				$msg =new EREIGNIS();
				$msg->loadEreignis($_POST['del'][$i]);
				
				//Überprüfen ob User nachricht überhaupt lesen darf!
				if( $msg->getUserID() == $_SESSION['user']->getUserID() )
				{
					//Ereignis löschen
					$db->query("DELETE FROM t_ereignis WHERE ID_Ereignis = ".$_POST['del'][$i]."");
				}
			}				
			break;
		//ALle Einträge löschen
		case 2:
			case 'ereignisse':
				$db->query("DELETE FROM t_ereignis WHERE ID_User = ".$_SESSION['user']->getUserID()." AND ID_Kolonie = ".$_SESSION['kolonie']->getID()."");
				break;
		//Gelesene Einträge löschen!
		case 3:
			$db->query("DELETE FROM t_ereignis WHERE ID_User = ".$_SESSION['user']->getUserID()." AND ID_Kolonie = ".$_SESSION['kolonie']->getID()." AND Status != 'neu'");
			break;
	}
}

/*Nachrichten Status laden
Welche Nachrichten sind neu, welche gelesen etc...*/
$db2 = new DATENBANK();
$db3 = new DATENBANK();
$db4 = new DATENBANK();
$db5 = new DATENBANK();
$db6 = new DATENBANK();

//Lade alle Nachrichten
$db->query("SELECT COUNT(*) FROM t_nachrichten WHERE ID_User = ".$_SESSION['user']->getUserID()." AND Deleted != 'empfaenger';");
$db2->query("SELECT COUNT(*) FROM t_nachrichten WHERE ID_User = ".$_SESSION['user']->getUserID()." AND Status = 'neu' AND Deleted != 'empfaenger';");
$db3->query("SELECT COUNT(*) FROM t_nachrichten WHERE ID_UserAbsender = ".$_SESSION['user']->getUserID()." AND Deleted != 'absender';");
$db4->query("SELECT COUNT(*) FROM t_nachrichten WHERE ID_UserAbsender = ".$_SESSION['user']->getUserID()." AND Status = 'neu' AND Deleted != 'absender';");
$db5->query("SELECT COUNT(*) FROM t_ereignis WHERE ID_User = ".$_SESSION['user']->getUserID().";");
$db6->query("SELECT COUNT(*) FROM t_ereignis WHERE ID_User = ".$_SESSION['user']->getUserID()." AND Status = 'neu';");

//Setze Daten
$daten_nachrichten['INBOX_GESAMT']		= $db->fetch_result(0);
$daten_nachrichten['INBOX_NEU'] 		= $db2->fetch_result(0);
$daten_nachrichten['OUTBOX_GESAMT'] 	= $db3->fetch_result(0);
$daten_nachrichten['OUTBOX_NEU'] 		= $db4->fetch_result(0);
$daten_nachrichten['EREIGNISSE_GESAMT'] = $db5->fetch_result(0);
$daten_nachrichten['EREIGNISSE_NEU'] 	= $db6->fetch_result(0);

//Lösche andere Datenbankobjekte
unset($db2); unset($db3); unset($db4); unset($db5); unset($db6);

/*Zeige Ereignisse an!*/
//Überprüfen ob Überhaupt ereignisse vorhanden sind
if( $daten_nachrichten['EREIGNISSE_GESAMT'] == 0 )	//Keine Nachrichten vorhanden
{
	$daten_nachrichten['CONTENT'] = "Keine Ereignisse vorhanden";
}
else 
{	    		    
	//Templatedaten setzen
	$daten_column['SPALTE'] = "Koordinaten:";
	$tpl_detail->setObject("nachrichten_detail_topic", $daten_column);
	
	//Lade alle Ereignisse
    $db->query("SELECT ID_Ereignis FROM t_ereignis WHERE ID_User = ".$_SESSION['user']->getUserID()." ORDER BY Datum DESC");
    while( $row = $db->fetch_array() )
    {
    	//Ereignis-Objekt laden und in Session speichern
	    $ereignis = new EREIGNIS();
        $ereignis->loadEreignis($row['ID_Ereignis']);
        
        //Templatedaten setzen
        $daten_detail['ID']			= $ereignis->getID();
        $daten_detail['DATUM'] 		= $ereignis->getDatum();
        $daten_detail['ABSENDER']	= $ereignis->getKoordinaten();
      	$daten_detail['BETREFF'] 	= "<b>".$ereignis->getBetreff()."</b><div class=\"beschreibung\">".$ereignis->getInhalt()."</div>";
      	
      	//"<a href=\"#\" onClick=\"PopUp('ereignis_show.php?ID=".$ereignis->getID()."', 350, 350)\">".$ereignis->getBetreff()."</a>";

       
        /*Ist nachricht neu?*/
        if( $ereignis->getStatus() == 'neu' )
        {
        	$daten_detail['CLASS'] = 'ereignis_new';
        }

        //Templatedaten ersetzen!
        $tpl_detail->setObject("nachrichten_detail", $daten_detail);
        
        //Werte für neuen Durchlauf setzen
        $daten_detail['CLASS'] = '';
    }
    //Templatedaten ersetzen
	$daten_nachrichten['CONTENT'] = $tpl_detail->getTemplate();
}
//Template laden
$tpl_nachrichten->setObject("nachrichten", $daten_nachrichten);
$daten['CONTENT'] = $tpl_nachrichten->getTemplate();

//Update alle NAchrichten als gelesen
$db->query("UPDATE t_ereignis SET Status = 'gelesen' WHERE ID_User = ".$_SESSION['user']->getUserID()."");

//footer einbinden
require_once("../includes/footer.php");