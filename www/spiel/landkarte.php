<?php
//Includes
require("../includes/login_check.php");

//Template laden
$tpl_landkarte = new TEMPLATE("templates/".$_SESSION['skin']."/landkarte.tpl");
$daten_landkarte = array("X"=>"", "Y"=>"");
$daten_detail = array("KOLONIE"=>"", "NAME"=>"", "PKT"=>"", 'ID'=>'');

/*Wenn kein Knopf gedrückt wurde, die Seite also zum 1. Mal angezeigt werden
soll, dann bitte die Koordinaten der aktuellen ausgewählten Kolonie anzeigen
	Beispiel: 
				aktuelle Kolonie: 7:7:4,
				dann Anzeige von Koordinaten: 7:7
*/
if( !isset($_POST['x1']) && !isset($_POST['x2']) && !isset($_POST['y1']) && !isset($_POST['y2']))
{		
	$_POST['x'] = $_SESSION['kolonie']->getX();
	$_POST['y'] = $_SESSION['kolonie']->getY();
}
/*Wenn die Seite aktualisiert werden soll, also z.B. ein Knopf gedrückt wurde (x--, Y++ etc)
dann die entsprechenden Koordinaten anzeigen*/
else 
{
	//Nun überprüfen welcher Knopf (x++, x--, y++, y--) gedrückt wurde :)
	if( isset($_POST['x1']) )
	{
		$_POST['x']--;
	}
	elseif( isset($_POST['x2']) )
	{
		$_POST['x']++;
	}
	elseif( isset($_POST['y1']) )
	{
		$_POST['y']--;
	}
	elseif( isset($_POST['y2']) )
	{
		$_POST['y']++;
	}
}

//Maximale X  Koordinate holen!
$db->query("SELECT MAX(X) FROM t_koordinaten");
$max_x = $db->fetch_result(0);
//Maximale Y  Koordinate holen!
$db->query("SELECT MAX(Y) FROM t_koordinaten");
$max_y = $db->fetch_result(0);

//Minimale X Koordinate holen!
$db->query("SELECT MIN(X) FROM t_koordinaten");
$min_x = $db->fetch_result(0);

//Minimale Y Koordinate holen!
$db->query("SELECT MIN(Y) FROM t_koordinaten");
$min_y = $db->fetch_result(0);

//überprüfen ob X im angemessenen Bereich ist!
if( $_POST['x'] > $max_x )
{
	$_POST['x'] = $min_x;
}
elseif( $_POST['x'] < $min_x )
{
	$_POST['x'] = $max_x;
}
//überprüfen ob Y im angemessenen Bereich ist!
if( $_POST['y'] > $max_y )
{
	$_POST['y'] = $min_y;
}
elseif( $_POST['y'] < $min_y )
{
	$_POST['y'] = $max_y;
}

//Selektiere alle Benutzer, welche in der jetzigen Koordinate leben! aber oberhalb von Z = 0 !
$db->query("
	SELECT 
		koor.ID_Koordinaten, koor.X, koor.Y, koor.Z, kol.ID_Kolonie, 
		kol.Bezeichnung, kol.ID_user, u.Nickname, kol.Gebäudepunkte 
	FROM 
		t_koordinaten koor LEFT JOIN t_kolonie AS kol USING(ID_Koordinaten) 
		LEFT JOIN t_user u USING(ID_User) 
	WHERE
		koor.X = ".$_POST['x']." AND koor.Y = ".$_POST['y']." 
		AND koor.Z > 0;");
while( $row = $db->fetch_array() )
{
	//Daten vorbereiten
	$daten_detail['KOLONIE'] 	= $row[3];
	$daten_detail['PKT']		= $row[8];
	
	//Name setzen!
	if( empty($row[6]) ) //es wurde kein Name gewählt!
	{
		$daten_detail['NAME'] = "-";
	}
	else 
	{
		$daten_detail['NAME'] = "<a href=\"#\" onClick=\"PopUp('user_info.php?ID=".$row[6]."&KOLO_ID=".$row[4]."',400,500)\">".$row[7]." - ".$row[5]."</a>";
	}
	

	//Template setzen
	$tpl_landkarte->setObject("landkarte_detail_oben", $daten_detail);
}

//Selektiere alle Benutzer, welche in der jetzigen Koordinate leben! aber unterhalb von Z = 0 !
$db->query("SELECT koor.ID_Koordinaten, koor.X, koor.Y, koor.Z, kol.ID_Kolonie, kol.Bezeichnung, kol.ID_user, u.Nickname, kol.Gebäudepunkte FROM t_koordinaten koor LEFT JOIN t_kolonie AS kol USING(ID_Koordinaten) LEFT JOIN t_user u USING(ID_User) WHERE koor.X = ".$_POST['x']." AND koor.Y = ".$_POST['y']." AND koor.Z < 0;");
while( $row = $db->fetch_array() )
{
	//Daten vorbereiten
	$daten_detail['KOLONIE'] 	= $row[3];
	$daten_detail['PKT']		= $row[8];
		
	//Name setzen!
	if( empty($row[6]) ) //es wurde kein Name gewählt!
	{
		$daten_detail['NAME'] = "-";
	}
	else 
	{
		$daten_detail['NAME'] = "<a href=\"#\" onClick=\"PopUp('user_info.php?ID=".$row[6]."&KOLO_ID=".$row[4]."',400,500)\">".$row[7]." - ".$row[5]."</a>";
	}
	
	//Template setzen
	$tpl_landkarte->setObject("landkarte_detail_unten", $daten_detail);
}

//Templatedaten setzen
$daten_landkarte['X'] = $_POST['x'];
$daten_landkarte['Y'] = $_POST['y'];
$tpl_landkarte->setObject("landkarte", $daten_landkarte);

//Template laden
$daten['CONTENT'] = $tpl_landkarte->getTemplate();

//footer einbinden
require_once("../includes/footer.php");