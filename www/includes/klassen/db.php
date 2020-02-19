<?php
/* db_klasse.php
Selbsterklärend !

History: 
               04.11.2003  Marskuh  created !
*/

class DATENBANK
{
   //instanzvariablen
   var $hostname = "db";        //Hostname
   var $username = "stw";    	//Ussername
   var $passwort = "stw";       //Passwort
   var $datenbank= "stw";       //Datenbank, welche selektiert werden soll
   var $show_error = true;      //Debugmodus ? false/true
   var $db_connid= false;       //Connect ID
   var $query_result = false;   //Querry ID
     
   //Standardkonstruktor stellt verbindung her !      
   function DATENBANK()
   {
      $this->db_connect($this->hostname,$this->username,$this->passwort,$this->datenbank);
   }
   
   //Verbindung zur Datenbank wird hergestellt
   function db_connect($host,$user,$pass,$dbname)
   {
      $this->db_connid = @mysql_connect($host,$user,$pass);
      if(!$this->db_connid)
		{
			$this->error("Datenbankverbindung zu ".$this->uesrname."@".$this->hostname." nicht möglich");
		}
      	mysql_set_charset("utf8", $this->db_connid);
		$this->db_choose($dbname);
   }
   
   //Datenbank wird angewählt
   function db_choose($dbname)
   {
		$this->datenbank = $dbname;
		if(!@mysql_select_db($dbname,$this->db_connid))
		{
			$this->error("Datenbank: ".$this->datenbank." kann nicht benutzt werden");
		}
   }
   
   //Abfrage an Datenbank senden oder Fehler ausgeben
   function query($query)
   {
      $this->query_result = @mysql_query($query, $this->db_connid);
		if(!$this->query_result)
		{
			$this->error("Fehlerhafte SQL-Anweisung: \"<i>$query</i>\"");
		}
   }
   
   //Ergebnis der Abfrage auswerten und in Array speichern
   function fetch_array()
   {
		return @mysql_fetch_array($this->query_result);
   }
   
	//Ergebnis der Abfrage auswerten
	function fetch_result($spalte)
	{
		return @mysql_result($this->query_result,$spalte);
	}
	
	//Anzahl der betroffenen Zeilen der SQL-Anweisung selektieren
	function num_rows()
	{
		return @mysql_num_rows($this->query_result);
	}
	
	//Lastinsert id selektieren
	function last_insert()
 	{
		return @mysql_insert_id($this->db_connid);
	}
	
	function  affected_rows()
	{
		return @mysql_affected_rows($this->db_connid);
	}

   //Funktion übernimmt Fehler und gibt diese aus
   function error($error)
   {
		$errmeldung = mysql_error();
		$errno = mysql_errno();
		$ausgabe = "<b><font color=\"red\">### Error: ###</b><br>$error<br>";
		$ausgabe .= "mySQL meldet: \"<i>$errmeldung (#$errno)</i>\"</font>";
		if($this->show_error == true)
		{
			echo $ausgabe;
		}
   }
   
   //Verbindung wird geschlossen !
   function db_connect_close()
   {
      @mysql_close($this->db_connid);
   }
}?>
