<?php
//includes
require_once("../includes/klassen/db.php");

$db = new DATENBANK();

for($i=0; $i<50; $i++)
{
	$db->query("INSERT INTO t_einheit (ID_Bauplan, Erfahrung, LebenProzent, ID_User, ID_Kolonie, ID_Flotte) VALUES (1372, 0, 1, 136, 1, 0);");
}

for($i=0; $i<50; $i++)
{
	$db->query("INSERT INTO t_einheit (ID_Bauplan, Erfahrung, LebenProzent, ID_User, ID_Kolonie, ID_Flotte) VALUES (1083, 0, 1, 136, 1, 0);");
}
?>