<?PHP
//Includes
require_once("../includes/klassen/db.php");
require_once("../includes/klassen/gebäude/gebäude.php");
require_once("../includes/klassen/rohstoff.php");

//Datenbankobjekt
$db 	= new DATENBANK();
$geb 	= new GEBÄUDE();
$res	= new ROHSTOFF();

//Alle User laden
$db->query("SELECT ID_User, Nickname, Loginname FROM t_user WHERE Status = 'freigeschaltet' ORDER BY Nickname ASC;");
while( $row=$db->fetch_array() )
{
	$select .= "<option value=\"".$row['ID_User']."\">".$row['Nickname']."|".$row['Loginname']."</option>";
}
$select = "<select name=\"user\">".$select."</select>";

//Sollen Userdetails angezeigt werden?
if( isset($_POST['button']) )
{
	//Userdetails laden
	$db->query("SELECT * FROM t_user WHERE ID_User = ".$_POST['user']."");
	$row = $db->fetch_array();

	//Ausgabe
	$ausgabe .= "<table>";
	$ausgabe .= "<tr>";
	$ausgabe .= "<th colspan=\"2\">UserDaten</th>";
	$ausgabe .= "</tr>";
	$ausgabe .= "<tr>";
	$ausgabe .= "<td>ID_User:</td>";
	$ausgabe .= "<td>".$row['ID_User']."</td>";
	$ausgabe .= "</tr>";
	$ausgabe .= "<tr>";
	$ausgabe .= "<td>Nickname</td>";
	$ausgabe .= "<td>".$row['Nickname']."</td>";
	$ausgabe .= "</tr>";
	$ausgabe .= "<tr>";
	$ausgabe .= "<td>Loginname:</td>";
	$ausgabe .= "<td>".$row['Loginname']."</td>";
	$ausgabe .= "</tr>";
	$ausgabe .= "<tr>";
	$ausgabe .= "<td>LastLogin:</td>";
	$ausgabe .= "<td>".date("d.m.Y H:i:s", $row['LastLogin'])."</td>";
	$ausgabe .= "</tr>";
	$ausgabe .= "<tr>";
	$ausgabe .= "<td>RegisterDate:</td>";
	$ausgabe .= "<td>".date("d.m.Y H:i:s", $row['RegisterDate'])."</td>";
	$ausgabe .= "</tr>";
	$ausgabe .= "<tr>";
	$ausgabe .= "<td>Status:</td>";
	$ausgabe .= "<td>".$row['Status']."</td>";
	$ausgabe .= "</tr>";
	$ausgabe .= "<tr>";
	$ausgabe .= "<td>LastChange:</td>";
	$ausgabe .= "<td>".date("d.m.Y H:i:s",$row['LastChange'])."</td>";
	$ausgabe .= "</tr>";
	$ausgabe .= "</table>";
	
	//Lade Koloniedaten
	$data = '';
	$db->query("SELECT t_bevoelkerung.Bevölkerung, t_bevoelkerung.Wachstumsrate, t_bevoelkerung.LastChange, t_kolonie.Bezeichnung, t_kolonie.ID_Kolonie AS ID_Kolonie, t_koordinaten.X, t_koordinaten.Y, t_koordinaten.Z, t_kolonie.Hauptquartier, t_kolonie.Energieniveau FROM t_bevoelkerung, t_kolonie, t_koordinaten WHERE t_koordinaten.ID_Koordinaten = t_kolonie.ID_Koordinaten AND t_kolonie.ID_Kolonie = t_bevoelkerung.ID_Kolonie AND t_kolonie.ID_User = ".$_POST['user']."");
	while( $row = $db->fetch_array() )
	{
		//Ausgabe-Detail vorbereiten
		$data .= "<tr>";
		$data .= "<td>".$row['ID_Kolonie']."</td>";
		$data .= "<td>".$row['Bezeichnung']."</td>";
		$data .= "<td>".$row['X'].":".$row['Y'].":".$row['Z']."</td>";
		$data .= "<td>".$row['Hauptquartier']."</td>";
		$data .= "<td>".$row['Energieniveau']."</td>";
		$data .= "<td>".number_format($row['Bevölkerung'],4,",",".")."</td>";
		$data .= "<td>".($row['Wachstumsrate']*100)."%</td>";
		$data .= "<td>".date("d.m.Y H:i:s",$row['LastChange'])."</td>";
		$data .= "</tr>";
	}
	
	//Ausgabe
	$ausgabe .= "<table>";
	$ausgabe .= "<tr>";
	$ausgabe .= "<th colspan=\"8\">KolonieDaten</th>";
	$ausgabe .= "</tr>";
	$ausgabe .= "<tr>";
	$ausgabe .= "<th>ID</th>";
	$ausgabe .= "<th>Bezeichnung</th>";
	$ausgabe .= "<th>Koordinaten</th>";
	$ausgabe .= "<th>Hauptquartier?</th>";
	$ausgabe .= "<th>Energieniveau</th>";
	$ausgabe .= "<th>Bevölkerung</th>";
	$ausgabe .= "<th> > </th>";
	$ausgabe .= "<th>LastChange Bevölkerung</th>";
	$ausgabe .= $data;
	
	//LAde Gebäudedaten
	$data = '';
	$db->query("SELECT t_userhatgebaeude.*, t_kolonie.Bezeichnung FROM t_userhatgebaeude, t_kolonie WHERE t_userhatgebaeude.ID_User = ".$_POST['user']." AND t_userhatgebaeude.ID_Kolonie = t_kolonie.ID_Kolonie ORDER BY ID_Gebäude ASC");
	while( $row = $db->fetch_array() )
	{
		//GEb-Name laden
		$geb->loadGebäude($row['ID_Gebäude'], 1);
		
		//Ausgabe-Detail vorbereiten
		$data .= "<tr>";
		$data .= "<td>".$row['ID_Gebäude']."</td>";
		$data .= "<td>".$geb->getBezeichnung()."</td>";
		$data .= "<td>".$row['Level']."</td>";
		$data .= "<td>".($row['Auslastung']*100)." %</td>";
		$data .= "<td>".date("d.m.Y H:i:s", $row['LastChange'])."</td>";
		$data .= "<td>(".$row['ID_Kolonie'].")".$row['Bezeichnung']."</td>";
		$data .= "</tr>";
		
	}
	
	//Ausgabe
	$ausgabe .= "<table>";
	$ausgabe .= "<tr>";
	$ausgabe .= "<th colspan=\"6\">GebäudeDaten</th>";
	$ausgabe .= "</tr>";
	$ausgabe .= "<tr>";
	$ausgabe .= "<th>ID</th>";
	$ausgabe .= "<th>Bezeichnung</th>";
	$ausgabe .= "<th>Level</th>";
	$ausgabe .= "<th>Auslastung</th>";
	$ausgabe .= "<th>LastChange</th>";
	$ausgabe .= "<th>Kolonie</th>";
	$ausgabe .= $data;
	
	//LAde Rohstoffdaten
	$data = '';
	$db->query("SELECT t_userhatrohstoffe.*, t_kolonie.Bezeichnung FROM t_userhatrohstoffe, t_kolonie WHERE t_userhatrohstoffe.ID_User = ".$_POST['user']." AND t_userhatrohstoffe.ID_Kolonie = t_kolonie.ID_Kolonie ORDER BY ID_Rohstoff ASC");
	while( $row = $db->fetch_array() )
	{
		//GEb-Name laden
		$res->loadRohstoff($row['ID_Rohstoff']);
		
		//Ausgabe-Detail vorbereiten
		$data .= "<tr>";
		$data .= "<td>".$row['ID_Rohstoff']."</td>";
		$data .= "<td>".$res->getBezeichnung()."</td>";
		$data .= "<td>".$row['Anzahl']."</td>";
		$data .= "<td>".date("d.m.Y H:i:s", $row['LastUpdate'])."</td>";
		$data .= "<td>(".$row['ID_Kolonie'].")".$row['Bezeichnung']."</td>";
		$data .= "</tr>";
		
	}
	
	//Ausgabe
	$ausgabe .= "<table>";
	$ausgabe .= "<tr>";
	$ausgabe .= "<th colspan=\"5\">RohstoffDaten</th>";
	$ausgabe .= "</tr>";
	$ausgabe .= "<tr>";
	$ausgabe .= "<th>ID</th>";
	$ausgabe .= "<th>Bezeichnung</th>";
	$ausgabe .= "<th>Anzahl</th>";
	$ausgabe .= "<th>LastChange</th>";
	$ausgabe .= "<th>Kolonie</th>";
	$ausgabe .= $data;
	/*
	$ausgabe .= "<tr>";
	$ausgabe .= "<td></td>";
	$ausgabe .= "<td></td>";
	$ausgabe .= "</tr>";
	$ausgabe .= "<tr>";
	$ausgabe .= "<td></td>";
	$ausgabe .= "<td></td>";
	$ausgabe .= "</tr>";
	$ausgabe .= "<tr>";
	$ausgabe .= "<td></td>";
	$ausgabe .= "<td></td>";
	$ausgabe .= "</tr>";*/
}
?>

<html>
<head>
<link rel="STYLESHEET" href="style.css" type="text/css">
</head>
<body>
	<form method="post">
	<table>
		<tr>
			<th colspan="2">User auswählen</th>
		</tr>
		<tr>
			<td>Username:</td>
			<td><?=$select?></td>
		</tr>
		<tr>
			<td colspan="2"><input type="submit" value="Userdetails anzeigen" name="button"></td>
		</tr>
	</table>
</form>
<?=$ausgabe?>
</body>
</html>