	<html>
	<head>
	<link rel="STYLESHEET" href="style.css" type="text/css">
	</head>
	<body>
		<form action="" method="post">
			<table>
				<tr>
					<th colspan=2>Forschungszentralre</th>
				</tr>
				<tr>
					<th>min Level:</th>
					<td><input type="text" name="min_lvl" value="<?echo $_POST['min_lvl']?>" size="10"></td>
				</tr>
				<tr>
					<th>max Level:</th>
					<td><input type="text" name="max_lvl" value="<?echo $_POST['max_lvl']?>" size="10"></td>
				</tr>
				<tr>
					<th colspan=2>Forscher:</th>
				</tr>
				<tr>
					<th>min Forscher:</th>
					<td><input type="text" name="min_forsch" value="<?echo $_POST['min_forsch']?>" size="10"></td>
				</tr>
				<tr>
					<th>max Forscher:</th>
					<td><input type="text" name="max_forsch" value="<?echo $_POST['max_forsch']?>" size="10"></td>
				</tr>
				<tr>
					<td colspan="2">
						<input type="submit" value="anzeigen" name="send">
					</td>
				</tr>
			</table>
		</form>
<?php
if( isset($_POST['send']) )
{
	//Includes
	require_once("../includes/klassen/forscher.php");
	require_once("../includes/klassen/db.php");
	
	//DB-Objekt erzeugen
	$db = new DATENBANK();
	
	//Wie viele Forschungszentrale simulieren? 
	for( $a=$_POST['min_lvl']; $a<=$_POST['max_lvl']; $a++ )	//bis Level 20 Forschungszentrale
	{
		//Ausgabe
		$ausgabe .= '<table>';
		$ausgabe .= '<tr>';
		$ausgabe .= "<th colspan=3>Forschungszentrale ($a)</th>";
		$ausgabe .= "</tr>";
		$ausgabe .= '<tr>';
		$ausgabe .= '<th>Forscher:</th>';
		$ausgabe .= '<th>Kosten:</th>';
		$ausgabe .= '<th>Dauer:</th>';
		$ausgabe .= '</tr>';
	
		//Wie viele Forscher sollen angezeigt werden?
		for( $i=$_POST['min_forsch']; $i<=$_POST['max_forsch']; $i++)
		{
			//Forscherobjekt erzeugen
			$forscher = new FORSCHER($db, 0);
			$forscher->Forscher = $i;
			
			//Ausgabe:
			$ausgabe .= "<tr>
							<td>
								$i				
							</td>
							<td>
								".$forscher->getForscherKosten($a)."
							</td>
							<td>
								".$forscher->getFormattedAusbildungsZeit($a)."
							</td>
						</tr>";
		}
	
	$ausgabe .= '</table>';
	}
	
	//Ausgabe
	echo $ausgabe;
}
?>
</body>
</html>