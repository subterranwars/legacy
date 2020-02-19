<?PHP
/*punkt_update.php
blablablaba

History:

		31.07.2004	MvR		created
		03.01.2005	MvR		updated
*/
require_once("klassen/punkte.class.php");
require_once("../includes/includes.inc.php");

//Deklarationen
$db = new DATENBANK();
$config = new EINSTELLUNGEN($db);

/*Spiel offline nehmen*/
$config->setStatus('offline');
$config->setGrund('Es wird ein Punkteupdate durchgeführt<br>Bitte versuchen Sie es später noch einmal.');

/*Lade alle User!*/
$db->query("SELECT ID_User FROM t_user WHERE ID_User > 10");
while( $row = $db->fetch_array() )
{
	//Userobjekt erzeugne
	$user = new USER($row['ID_User']);
	
	//Punkte aktualisieren
	$pkt = new PUNKTE($user);
	$pkt->berechnePunkte();
}

/*Spiel wieder online bringen*/
$config->setStatus('online');?>