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
				//nachrichtenobjekt erzeugen
				$nachricht = new NACHRICHT();
				$nachricht->loadMessage($_POST['del'][$i]);
				$nachricht->delMessage("absender");	
			}
			break;		
		//ALle Einträge löschen
		case 2:	
			//Alle NAchrichten, die dem  User gehören durchlaufen!
			$db->query("SELECT ID_Nachricht FROM t_nachrichten WHERE ID_UserAbsender = ".$_SESSION['user']->getUserID()."");
			while( $row = $db->fetch_array() )
			{
				//nachrichtenobjekt erzeugen
				$nachricht = new NACHRICHT();
				$nachricht->loadMessage($row['ID_Nachricht']);
				$nachricht->delMessage("absender");	
			}		
			break;
		//Gelesene Einträge löschen!
		case 3:
			//Alle NAchrichten, die dem  User gehören durchlaufen!
			$db->query("SELECT ID_Nachricht FROM t_nachrichten WHERE ID_UserAbsender = ".$_SESSION['user']->getUserID()." AND Status = 'gelesen'");
			while( $row = $db->fetch_array() )
			{
				//nachrichtenobjekt erzeugen
				$nachricht = new NACHRICHT();
				$nachricht->loadMessage($row['ID_Nachricht']);
				$nachricht->delMessage("absender");	
			}	
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

/*Zeige Postausgang an!*/
//Überprüfen ob Überhaupt Nachrichten vorhanden sind
if( $daten_nachrichten['OUTBOX_GESAMT'] == 0 )	//Keine Nachrichten vorhanden
{
	$daten_nachrichten['CONTENT'] = "Keine Nachrichten vorhanden";
}
else 
{	    
	//Templatedaten setzen
	$daten_column['SPALTE'] = "Empfänger:";
	$tpl_detail->setObject("nachrichten_detail_topic", $daten_column);
	
	//Lade alle Ereignisse
    $db->query("SELECT ID_Nachricht FROM t_nachrichten WHERE ID_UserAbsender = ".$_SESSION['user']->getUserID()." AND Deleted != 'absender' ORDER BY Datum DESC");
    
    //Wenn keine Nachrichten vorhanden sind	    
    while( $row = $db->fetch_array() )
    {
        //Ereignis-Objekt laden und in Session speichern
        $msg = new NACHRICHT();
        $msg->loadMessage($row['ID_Nachricht']);
        
        //Templatedaten setzen
        $daten_detail['ID']			= $msg->getID();
        $daten_detail['DATUM'] 		= $msg->getDatum();
        $daten_detail['ABSENDER']	= $msg->getEmpfaenger();
        $daten_detail['BETREFF'] 	= "<a href=\"#\" onClick=\"PopUp('nachrichten_show.php?action=outbox&ID=".$msg->getID()."', 550, 550)\">".$msg->getBetreff()."</a>";
         
        /*Ist nachricht neu?*/
        if( $msg->getStatus() == 'neu' )
        {
        	$daten_detail['CLASS'] = 'msg_new';
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

//footer einbinden
require_once("../includes/footer.php");