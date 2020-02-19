<?PHP
require_once("../includes/klassen/db.php");
require_once("../includes/klassen/template.php");

//Tpl-laden
$tpl = new TEMPLATE("templates/index/index.tpl");
$daten = array('CONTENT'=>'');

$daten['CONTENT'] = '<table>';

//Datenbankobjekt laden
$db = new DATENBANK();
$db->query("SELECT Betreff, DATE_FORMAT(Datum, '%a, %d.%c.%Y - %H:%i:%s'), Inhalt FROM t_news ORDER BY Datum DESC");
while( $row = $db->fetch_array() )
{
	$daten['CONTENT'] .= "<tr>";
	$daten['CONTENT'] .= "<th class=\"blue\">".$row['Betreff']."</th>";
	$daten['CONTENT'] .= "</tr>";
	$daten['CONTENT'] .= "<tr>";
	$daten['CONTENT'] .= "<td><div class=\"beschreibung\">".$row[1]."</div>".$row['Inhalt']."</td>";
	$daten['CONTENT'] .= "</tr>";
}
$daten['CONTENT'] .= "</table>";

$tpl->setObject("index", $daten);
echo $tpl->getTemplate();?>