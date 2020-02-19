<?PHP
require_once("../includes/klassen/template.php");

//Template laden
$tpl 			= new TEMPLATE("templates/index/index.tpl");
$daten 	= array(
	'CONTENT' => '');
	
$tpl_noobhelp 	= new TEMPLATE("templates/index/noob_hilfe.tpl");

//Templatedaten setzen
$daten['CONTENT'] = $tpl_noobhelp->getTemplate();
$tpl->setObject("index", $daten);
echo $tpl->getTemplate();
?>