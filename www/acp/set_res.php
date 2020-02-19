<?PHP
/*punkt_update.php
blablablaba

History:

		31.07.2004	MvR		created
*/
require_once("../includes/klassen/db.php");

$db = new DATENBANK();
$db2= new DATENBANK();

//Lade alle Rohstoffe
$db->query("SELECT MAX(ID_Rohstoff), ID_Rohstoff, ID_Kolonie, ID_User FROM t_userhatrohstoffe GROUP BY ID_User");
while( $row = $db->fetch_array() )
{
	$db2->query("INSERT INTO t_userhatrohstoffe (ID_Rohstoff, Anzahl, ID_User, ID_Kolonie) VALUES (13, 0, ".$row['ID_User'].",".$row['ID_Kolonie'].")");
	$db2->query("INSERT INTO t_userhatrohstoffe (ID_Rohstoff, Anzahl, ID_User, ID_Kolonie) VALUES (14, 0, ".$row['ID_User'].",".$row['ID_Kolonie'].")");
}
?>