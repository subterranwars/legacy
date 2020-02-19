<?php
//Includes
require("../includes/login_check.php");

//Template laden
$tpl_highscore = new TEMPLATE("templates/".$_SESSION['skin']."/highscore.tpl");
$daten_detail = array(
	'PLATZ' => '',
	'NAME' => '',
	'PKT_GESAMT' => '',
	'GEB_PKT' => '',
	'FORSCH_PKT' => '',
	'RASSE' => '');

//2. DB-Objekt erzeugen
$db2 = new DATENBANK();
	
//User laden
$db->query("SELECT Nickname, PunkteForschung, ID_Rasse, ID_User FROM t_user WHERE Status = 'freigeschaltet'");
while( $row = $db->fetch_array() )
{
	/*Lade alle Kolonien des Users*/
	$db2->query("SELECT SUM(Gebäudepunkte) FROM t_kolonie WHERE ID_User = ".$row['ID_User']."");
	$geb_pkt = $db2->fetch_result(0);
	
	//Userpkt bestimmen
	$pkt[] = array(
				'ID_User' => $row['ID_User'],
				'Nickname' => $row['Nickname'],
				'ID_Rasse' => $row['ID_Rasse'],
				'Gesamt' => $row[1] + $geb_pkt,
				'Forschung' => $row[1],
				'Geb' => $geb_pkt);
	
	//echo $pkt[$i]['Nickname'],"<bR>";
}

//alle pkt durchlaufen und sortieren
for($i=0; $i<count($pkt)-1; $i++)
{
	for($a=$i+1; $a<count($pkt); $a++)
	{
		if( $pkt[$a]['Gesamt'] > $pkt[$i]['Gesamt'] )
		{
			$save = $pkt[$i];
			$pkt[$i] = $pkt[$a];
			$pkt[$a] = $save;
		}
	}
}

//Nun alles anzeigen
for( $i=0; $i<count($pkt); $i++ )
{
	//Templatedaten setzen
	$daten_detail['PLATZ'] 		= $i+1;
	$daten_detail['PKT_GESAMT'] = $pkt[$i]['Gesamt'];
	$daten_detail['GEB_PKT'] 	= $pkt[$i]['Geb'];
	$daten_detail['FORSCH_PKT'] = $pkt[$i]['Forschung'];
	
	//Rasseobjekt laden und daten setzen
	$rasse = new RASSE($pkt[$i]['ID_Rasse']);
	$daten_detail['RASSE'] = $rasse->getBezeichnung();
	
	//Usernamen andersfarbig darstellen, wenn es der eigene name ist!
	if( $pkt[$i]['Nickname'] == $_SESSION['user']->getNickname() )	
	{
		$daten_detail['NAME'] = '<div class="yellow">'.$pkt[$i]['Nickname'].'</div>';
	}
	else 
	{
		$daten_detail['NAME'] = "<a href=\"#\" onClick=\"PopUp('user_info.php?ID=".$pkt[$i]['ID_User']."', 400, 500)\">".$pkt[$i]['Nickname']."</a>";
	}
	
	//Templateobjekt verändern
	$tpl_highscore->setObject("highscore", $daten_detail);
}
//Template laden
$daten['CONTENT'] = $tpl_highscore->getTemplate();

//footer einbinden
require_once("../includes/footer.php");