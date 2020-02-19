<?
/*Diese Datei berechnet punkte

History:
			03.01.2005		MvR		created
*/

class PUNKTE
{
	//Deklarationen
	var $db;								//DB-Objekt	
	var $db2;								//Db-Objekt
	var $user;								//Referenz auf Userobjekt
	var $geb_teil_faktor = 5000;			//Die Gebäudekosten werden durch diesen Faktor geteilt
	var $forschungs_teil_faktor = 100;		//Die Forschungspunkte werden durch diesen Faktor geteilt
	
	//Standardkonstruktor
	function PUNKTE(&$user)
	{
		//Datenbankobjekte erzeugen
		$this->db = new DATENBANK();
		$this->db2 = new DATENBANK();
		
		//UserObjekt setzen
		$this->user = &$user;
	}
	
	/*Diese Funktion ermittelt alle GebäudePunkte des Users => $ID_User auf der Kolonie => $ID_Kolonie*/
	function berechneGebPunkte($ID_Kolonie)
	{
		//Deklarationen
		$stahl_faktor = 1.25;		//Faktor mit dem Stahl multipliziert werden soll
		$titan_faktor = 1.75;		//Faktor mit dem Titan multipliziert werden soll
		$geb = new GEBÄUDE();		//Gebäudeobjekt erzeugen
		
		//Gebäude laden, die der User besitzt
		$this->db->query("SELECT ID_Gebäude, Level FROM t_userhatgebaeude WHERE ID_Kolonie = $ID_Kolonie");
		while( $row = $this->db->fetch_array() )
		{
			//Gebäudedaten laden
			$geb->loadGebäude($row[0], $row[3]);
			
			//Level durchlaufen udn Kosten berechnen
			for( $i=1; $i<=$row[1]; $i++)
			{
				//Gebäudekosten laden
				$kosten = $geb->getKosten($i-1);
		
				//Summieren mit nem faktor
				$summe = $kosten[0] + $kosten[1] + $kosten[2] * $stahl_faktor + $kosten[3] * $titan_faktor;	
				$gesamt += round($summe / $this->geb_teil_faktor);	
			}
		}
		//Gebe Gebäudepunkte zurück
		return $gesamt;
	}
	
	/*Diese Funktion berechnet alle Forschungspunkte*/
	function berechneForschungsPunkte()
	{
		//Forschungsobjekt erzeugen
		$forschung = new FORSCHUNG();
		
		//Alle Forschungen laden
		$this->db->query("SELECT ID_Forschung, Level FROM t_userhatforschung WHERE ID_User = ".$this->user->getUserID()."");
		while( $row = $this->db->fetch_array() )
		{ 
			//Durchlaufe Level
			for( $i = 1; $i<=$row[1]; $i++ )
			{
				//Forschungskosten laden
				$forschung->loadForschung($row[0]);
				$summe = $forschung->getKosten($i-1);
						
				//Summiere Forschungspunkte auf Gesamtpunkte!
				$gesamt += round($summe/$this->forschungs_teil_faktor);
			}
		}
		//Rückgabewert
		return $gesamt;
	}
	
	/*Diese Funktion berechnet Punkte eines Users*/
	function berechnePunkte()
	{
		/*Lade alle Kolonien des Users*/
		$this->db->query("SELECT t_kolonie.ID_Kolonie AS ID_Kolonie, t_user.ID_User AS ID_User FROM t_user RIGHT JOIN t_kolonie USING (ID_User) WHERE t_kolonie.ID_User = ".$this->user->getUserID()."");
		while( $row =$this->db->fetch_array() )
		{	
			//Kolonie laden
			$kolo = new KOLONIE($row['ID_Kolonie'], $this->db2);
			
			//Punkte berechnen
			$geb_punkte = $this->berechneGebPunkte($row['ID_Kolonie']);	//Gebäudepunkte
			$forschungs_punkte += $this->berechneForschungsPunkte();						//Forschungspunkte
			//Punkte setzen
			$kolo->setGebäudePunkte($geb_punkte);
		}
		//Setze Forschungspunkte des Users!
		$this->user->setForschungsPunkte($forschungs_punkte);
	}
}