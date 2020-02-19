<?PHP
//Includes laden
require_once("../includes/klassen/template.php");
	
//Templatedaten setzen
$tpl_agb 	= new TEMPLATE("templates/index/agb.tpl");
echo $tpl_agb->getTemplate();?>