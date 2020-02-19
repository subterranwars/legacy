<?php
/*einheit.php
Diese Klasse wird von Bauplan abgeleitet und entält wichtige Informationen bezüglich
der einheit.
Wichtigsten Informationen hierbei sind
Erfahrung und Lebensprozent


History:
			01.09.2004		MvR		created
*/

class EINHEIT
extends BAUPLAN
{
	//Deklarationen
	var $ID_Einheit;
	var $Erfahrung;
	var $LebenProzent;
	var $HP;
	
	//Standardkonstruktor
	function EINHEIT(&$db, &$user, $ID_Einheit, $ID_Bauplan)
	{
		//Standardkonstruktor von Vaterklasse aufrufen
		$this->BAUPLAN($db, $user);
		$this->loadBauplan($ID_Bauplan);
		
		//ID_Einheit setzen
		$this->ID_Einheit = $ID_Einheit;
		
		//Einheitendaten laden
		$this->db->query("SELECT Erfahrung, LebenProzent FROM t_einheit WHERE ID_Einheit = $ID_Einheit");
		$ergebnis = $this->db->fetch_array();
		
		//Werte setzen!
		$this->Erfahrung = $ergebnis['Erfahrung'];
		$this->LebenProzent = $ergebnis['LebenProzent'];
		$this->HP			= $this->lebenspunkte * $this->LebenProzent;
	}
	
	/*setzt LEbnespunkte neu*/
	function addLebenspunkte($add)
	{
		//Wenn Mehr Lebenspunkte als 100% sind, dann nur 100% machen
		if( $this->HP + $add > $this->lebenspunkte )
		{
			$this->HP = $this->lebenspunkte;
			$this->LebenProzent = 1;
		}
		else 
		{
			$this->HP += $add;
			$this->LebenProzent = $this->lebenspunkte / 100 * $this->HP;
		}
	}
	
	/*gibt Lebenspunkte zurück*/
	function getLebenspunkte()
	{
		return $this->HP;
	}
}?>