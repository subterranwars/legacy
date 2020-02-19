<?PHP
//Includes
require("../includes/login_check.php");

//Template laden
$tpl_kolonien = new TEMPLATE("templates/".$_SESSION['skin']."/kolonien.tpl");
$daten_detail = array("KOORDINATEN"=>"", "ID"=>"", "BEZEICHNUNG"=>"", "STATUS"=>"", "HAUPTPLANET"=>"");

//Überprüfen ob User schon knopp gedrückt hat
if( isset($_POST['send']) )
{
	//Kolonien durchlaufen udn daten laden
	for( $i=0; $i<count($_POST['koloid']); $i++ )
	{
		//Koloniedaten laden
		$kolo = new KOLONIE($_POST['koloid'][$i], $db);
		
		//Koloniename setzen!
		$kolo->setBezeichnung($_POST['koloname'][$i]);
	}
	//Kolonien wieder löschen
	unset($kolo);
	
	/*Aktuelle ausgewählte (_SESSION['kolonie']) neu laden*/
	$_SESSION['kolonie'] = new KOLONIE($_SESSION['kolonie']->getID(), $db);
}

/*Lade Alle kolonien des Users*/
$db2 = new DATENBANK();
$db->query("SELECT ID_Kolonie FROM t_kolonie WHERE ID_User = ".$_SESSION['user']->getUserID()."");
while( $row = $db->fetch_array() )
{
	//Kolonieobjekt erzeugen
	$kolo = new KOLONIE($row['ID_Kolonie'], $db2);
	
	//Templatedaten setzen
	$daten_detail['KOORDINATEN'] 	= $kolo->getKoordinaten();
	$daten_detail['BEZEICHNUNG']	= $kolo->getBezeichnung();
	$daten_detail['STATUS']			= $kolo->getStatusName();
	$daten_detail['ID']				= $kolo->getID();
	
	if( $kolo->getHauptquartier() == "ja" )
	{
		$daten_detail['HAUPTPLANET'] = "ja";
	}
	else
	{
		$daten_detail['HAUPTPLANET'] = "&nbsp;";
	}
	$tpl_kolonien->setObject("kolonien", $daten_detail);
}
//Template laden
$daten['CONTENT'] = $tpl_kolonien->getTemplate();

//footer einbinden
require_once("../includes/footer.php");
?>