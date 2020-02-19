<?PHP
//INcludes
require("../includes/login_check.php");

//Deklarationen
$erfolg = false;

//Session zersötren
$erfolg = session_destroy();

if( $erfolg == true )
{
	header("Location: login.php");	//Weiterleiten
	exit;							//Weitere ausführung unterbinden
}
else
{
	//Objekt serialisieren
	$_SESSION['user'] = serialize($_SESSION['user']);
	
	//FEhler beim ausloggen
	$fehler = new FEHLER($db);
	echo $fehler->meldung(400);	
}?>