<?PHP
require_once("../includes/klassen/gebäude/gebäude.php");
require_once("../includes/klassen/db.php");

$geb = new GEBÄUDE();
$geb_array = $geb->getGebäudeArray();

for($i=1; $i<=count($geb_array); $i++ )
{
	if( $geb_array[$i][0] != "" )
	{
		//Gebdaten!
		$hq_level = 1;
		$geb->loadGebäude($i, 1);
		
		$ausgabe .= "<table width=600>
						<tr>
							<th colspan=\"6\">HQ LvL $hq_level</th>
						</tr>
						<tr>
							<th>".$geb->getBezeichnung()."</th>
							<th colspan=\"4\">Kosten:</th>
							<th>Bauzeit:</th>
						</tr>";
		
		for($a=1; $a<=30; $a++)
		{
			unset($kosten);
			$kosten = $geb->getKosten($a-1);
			$sekunden 	= $geb->getBuildTime($a, $hq_level);
			
			unset($tage);
			unset($stunden);
			unset($minuten);
			//Zeiten berechnen		
			if( $sekunden > 59 )
			{
				$minuten 	= floor($sekunden / 60);
				$sekunden 	= $sekunden - $minuten *60;
				//echo "<font color=\"blue\">Tage: $tage<br>Stunden: $stunden<br>Minuten: $minuten<br>Sekunden: $sekunden<hr></font>";
			}
			if( $minuten > 59 )
			{
				$stunden = floor($minuten / 60);
				$minuten = $minuten - $stunden*60;
				//echo "<font color=\"blue\">Tage: $tage<br>Stunden: $stunden<br>Minuten: $minuten<br>Sekunden: $sekunden<hr></font>";
			}
			if( $stunden > 23 )
			{
				$tage = floor($stunden / 24);
				$stunden = $stunden - $tage * 24;
				//echo "<font color=\"blue\">Tage: $tage<br>Stunden: $stunden<br>Minuten: $minuten<br>Sekunden: $sekunden<hr></font>";
			}
			//Überprüfen ob 1 oder mehrere Tage
			if( $tage == 1 )
			{
				$tage = $tage." Tag";
			}
			elseif ($tage > 1 )
			{
				$tage = $tage." Tage";
			}
			//Bauzeit als formatierter STring:
			$bauzeit = sprintf("%s %02d:%02d:%02d", $tage, $stunden, $minuten, $sekunden);
			
			
			$ausgabe .= "<tr>
							<th>".$a."</th>
							<td>Eisen: ".$kosten[0]."</td>
							<td>Stein: ".$kosten[1]."</td>
							<td>Stahl: ".$kosten[2]."</td>
							<td>Titan: ".$kosten[3]."</td>
							<th>".$bauzeit."</th>
						</tr>";		
		}
	}
}
$ausgabe .="</table>";?>

<html>
<head>
<link rel="STYLESHEET" href="style.css" type="text/css">
</head>
<body>
	<?=$ausgabe?>
</body>
</html>