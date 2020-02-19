<?PHP
require_once("../includes/klassen/template.php");

//Template laden
$tpl 			= new TEMPLATE("templates/index/index.tpl");
$daten 	= array(
	'CONTENT' => '');
	
$tpl_impressum 	= new TEMPLATE("templates/index/impressum.tpl");

//Templatedaten setzen
$daten['CONTENT'] = $tpl_impressum->getTemplate();
$tpl->setObject("index", $daten);
echo $tpl->getTemplate();
?>