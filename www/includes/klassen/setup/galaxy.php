<?PHP
/*galaxy.php

Diese Datei erstellt eine Landkarte mit x,y,z koordinaten

History:
			13.07.2004	Markus von Rüden	created
*/

require_once("../db.php");

class GALAXY
{
	var $db;
	var $iAnzahlX = 9;
	var $iAnzahlY = 9;
	var $iAnzahlZ = 10;

    function GALAXY(&$db)
    {
        $this->db = &$db;
    }
    
    function zufall($obergrenze)
    {
        $zufall = mt_rand(2, $obergrenze);
        return $zufall;

    }

    function setGalaxy()
    {
        mt_srand(time());
        
    	//Über der Erde
        for( $i = 1; $i <= $this->iAnzahlX; $i++ )
        {
            for( $a = 1; $a <= $this->iAnzahlY; $a++ )
            {
                for( $j = 1; $j <= $this->zufall($this->iAnzahlZ+1); $j++ )
                {
                    $this->db->query("INSERT INTO t_koordinaten (X, Y, Z) values ($i, $a, $j);");
                }
            }
        }
        //Unter der Erde
        for( $i = 1; $i <= $this->iAnzahlX; $i++ )
        {
            for( $a = 1; $a <= $this->iAnzahlY; $a++ )
            {
                for( $j = 1; $j <= $this->zufall($this->iAnzahlZ+1); $j++ )
                {
                    $z = $j*-1;
                    $this->db->query("INSERT INTO t_koordinaten (X, Y, Z) values ($i, $a, $z);");
                }
            }
        }
    }
}

$db = new DATENBANK();
$galaxy = new GALAXY($db);
$galaxy->setGalaxy();
