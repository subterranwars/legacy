<?PHP
include("../includes/klassen/db.php");

$inaktive_zeit = 14 * 3600 * 24; 	//Tage nach dem NEUE Nachrichten und Ereignisse gelöscht werden sollen
$inaktive_zeit2 = 28 * 3600 * 24;	//Tage nach dem GELESENE Nachrichten und Ereignisse gelöscht werden sollen
$db = new DATENBANK();
$db2 = new DATENBANK();

//Nachrichten selektieren
$db->query("SELECT ID_Nachricht, Betreff, Datum, Status FROM t_nachrichten WHERE (Status = 'neu' AND Datum <=".(time() - $inaktive_zeit).") OR (Status != 'neu' AND Datum <= ".(time() - $inaktive_zeit2).") ORDER BY Datum ASC");
$anzahl_msgs = $db->num_rows();
while( $row = $db->fetch_array() )
{
	//Ausgabe!
	$nachrichten .= "Nachricht <i>(".$row['Status'].") <b>".$row['Betreff']." (#".$row['ID_Nachricht'].")</b> ist vom ".date("D, d.m.Y - H:i:s", $row['Datum'])."<br>";
	
	//Lösche 
	if( isset($_POST['del']))
	{
		//Lösche Nachricht
		$db2->query("DELETE FROM t_nachrichten WHERE ID_Nachricht =$row[0]");
		$nachrichten .= "<li>Lösche Nachricht...</li><br>";	
	}
}


//Ereignisse selektieren
$db->query("SELECT ID_Ereignis, Titel, Datum, Status FROM t_ereignis WHERE (Status = 'neu' AND Datum <=".(time() - $inaktive_zeit).") OR (Status != 'neu' AND Datum <= ".(time() - $inaktive_zeit2).") ORDER BY Datum ASC");
$anzahl_ereignisse = $db->num_rows();
while( $row = $db->fetch_array() )
{
	//Ausgabe!
	$ereignisse .= "Ereignis <i>(".$row['Status'].")</i> <b>".$row['Titel']." (#".$row['ID_Ereignis'].")</b> ist vom ".date("D, d.m.Y - H:i:s", $row['Datum'])."<br>";
	
	//Lösche 
	if( isset($_POST['del2']))
	{
		//Lösche Nachricht
		$db2->query("DELETE FROM t_ereignis WHERE ID_Ereignis =$row[0]");
		$ereignisse .= "<li>Lösche Ereignis...</li><br>";	
	}
}

?>
<html>
<head>
</head>
<body>
	<form action="zeige_msgs.php" method="post">
		<h1>Veraltete Nachrichten: (<?echo $anzahl_msgs?>)</h1>
		<?echo $nachrichten?>
		<input type="submit" name="del" value="Lösche nicht gelesene oder veraltete Nachrichten."><br><hr>
		
		<h1>Veraltete Ereignisse: (<?echo $anzahl_ereignisse?>)</h1>
		<?echo $ereignisse?>
		<input type="submit" name="del2" value="Lösche nicht gelesene oder veraltete Nachrichten.">
	</form>
</body>
</html>