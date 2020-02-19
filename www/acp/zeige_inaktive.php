<?PHP
include("../includes/klassen/db.php");

$inaktive_zeit = 28 * 3600 * 24; //4 wochen
$db = new DATENBANK();
$db2 = new DATENBANK();

$db->query("SELECT * FROM t_user WHERE Nickname != 'RESERVED' AND LastLogin <= ".(time() - $inaktive_zeit)." AND Status = 'freigeschaltet'");
while( $row = $db->fetch_array() )
{
	//Ausgabe!
	echo "User <b>".$row['Nickname']." (#".$row['ID_User'].")</b> hat sich zum letzten mal am ".date("D, d.m.Y - H:i:s", $row['LastLogin'])." im Spiel eingeloggt!<br>";
	
	//Lösche 
	if( isset($_POST['del']))
	{
		$db2->query("DELETE FROM t_bevoelkerung WHERE ID_User = ".$row['ID_User']."");
		echo "<ul><li>lösche Bevölkerungsdaten</li>";
		
		$db2->query("DELETE FROM t_ereignis WHERE ID_User = ".$row['ID_User']);
		echo "<li>lösche Ereignisnachrichten</li>";
		
		$db2->query("DELETE FROM t_nachrichten WHERE ID_User = ".$row['ID_User']." OR ID_UserAbsender = ".$row['ID_User']."");
		echo "<li>lösche Nachrichten</li>";
		
		$db2->query("DELETE FROM t_general WHERE ID_General = ".$row['ID_General']."");
		echo "<li>lösche den General</li>";
		
		$db2->query("DELETE FROM t_kolonie WHERE ID_User = ".$row['ID_User']."");
		echo "<li>lösche alle Kolonien des Users</li>";
		
		$db2->query("DELETE FROM t_userbautgebaeude  WHERE ID_User = ".$row['ID_User']);
		echo "<li>lösche alle Bauaufträge</li>";
		
		$db2->query("DELETE FROM t_userhatgebaeude WHERE ID_User = ".$row['ID_User']."");
		echo "<li>entferne Gebäude des Users</li>";
		
		$db2->query("DELETE FROM t_userhatrohstoffe WHERE ID_User =".$row['ID_User']."");
		echo "<li>entferne Rohstoffe</li>";
		
		$db2->query("DELETE FROM t_usersuchtvorkommen WHERE ID_User = ".$row['ID_User']."");
		echo "<li>entferne Vorkommenssuchen</li>";
		
		$db2->query("DELETE FROM t_vorkommen WHERE ID_User = ".$row['ID_User']."");
		echo "<li>entferne Vorkommen</li>";
		
		$db2->query("DELETE FROM t_user WHERE ID_User = ".$row['ID_User']."");
		echo "<li>lösche User aus Datenbank</li></ul>";
	}
}
?>
<html>
<head>
</head>
<body>
	<form action="zeige_inaktive.php" method="post">
		<input type="submit" name="del" value="Lösche inaktive Spieler">
	</form>
</body>
</html>