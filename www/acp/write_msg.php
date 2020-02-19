<html>
<head>
</head>
<body>
	<form action="write_msg.php" method="post">
		Betreff: <input type="text" name="betreff"><br>
		Inhalt: <textarea cols="30" rows="25" name="inhalt"></textarea><br>
		<input type="submit" value="Ereignis senden" name="send">
	</form>
</body>
</html>
<?PHP
if( isset($_POST['send']) )
{
	//Includes
	require_once("../includes/klassen/db.php");
	
	//Objekte
	$db = new DATENBANK();
	$db2 = new DATENBANK();
	
	//Alle User durchlaufen
	$db->query("
		SELECT 
			t_user.ID_User, ID_Kolonie 
		FROM 
			t_user, t_kolonie
		WHERE 
			t_user.ID_User = t_kolonie.ID_User 
		AND
			t_user.ID_User > 10 AND t_user.Status = 'freigeschaltet'");
	while( $row =$db->fetch_array() )	
	{
		echo $row[0], " | ", $row[1], "<br>";
		//Einfügen
		$db2->query("
			INSERT INTO t_ereignis
				(Titel, Betreff, Datum, Status, ID_User, ID_Kolonie) 
			VALUES
				('".$_POST['betreff']."','".$_POST['inhalt']."', ".time().",'neu', ".$row[0].",".$row[1].")");
	}
}?>