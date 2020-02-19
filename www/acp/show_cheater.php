<?PHP
require_once("../includes/klassen/db.php");

//OBjekte erzeugen
$db = new DATENBANK();

$sql = 'SELECT SUM( AnzahlLasterDrohnen ) , t_user.Nickname, t_userhatgebaeude.Level
		FROM t_vorkommen, t_user, t_userhatgebaeude
		WHERE t_user.ID_User = t_vorkommen.ID_User AND t_user.ID_User = t_userhatgebaeude.ID_User AND t_userhatgebaeude.ID_Gebäude =7
		GROUP BY t_vorkommen.ID_User
		ORDER BY t_user.Nickname';

//Query ausführen
$db->query($sql);
while( $row = $db->fetch_array() )
{		
	//Überprüfen ob User "legal" spielt
	if( $row[2]*5 >= $row[0] )		//Spieler spielt normal
	{
		$ausgabe .= "<b>$row[1] spielt <font color=\"green\">fair</font> (Level: $row[2] | Drohnen( $row[0] von ".$row[2]*5.0.")</b><br>";
	}
	else 							//Spieler betrügt
	{
		$ausgabe .= "<b>$row[1] spielt <font color=\"red\">unfair</font> (Level: $row[2] | Drohnen( $row[0] von ".$row[2]*5.0.")</b><br>";
	}
}
?>

<html>
<head>
<link rel="STYLESHEET" href="style.css" type="text/css">
</head>
<body>
	<?=$ausgabe?>
</body>
</html>