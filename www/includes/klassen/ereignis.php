<?php
class EREIGNIS
extends TEXT
{
	var $db;
	var $ID;
	var $Inhalt;
	var $Datum;
	var $Betreff;
	var $ID_User;
	var $Status;
	var $ID_Kolonie;

    function EREIGNIS()
    {
        $this->db = new DATENBANK();
    	parent::TEXT($this->db);
    }
    
    function getID()
    {
    	return $this->ID;
    }
    
    function getInhalt()
    {
        return $this->Inhalt;
    }
    
    function getDatum()
    {
        return date("D, d.m.Y - H:i:s",$this->Datum);
    }
    
    function setDatum($datum)
    {
    	$this->Datum = $datum;
    	$this->db->query("UPDATE t_ereignis SET Datum = $datum WHERE ID_Ereignis = $this->ID");
    }
    
   	function getBetreff()
    {
        return $this->Betreff;
    }
    
    function getStatus()
    {
        return $this->Status;
    }
    
    function editStatus($neuer_status)
    {
        $this->Status = $neuer_status;
		$this->db->query("UPDATE t_ereignis SET Status = '$neuer_status' WHERE ID_Ereignis = $this->ID");
    }
   
    function loadEreignis($ID)
    {
        $error = 1;
        
    	$this->ID = $ID;
        $this->db->query("SELECT * FROM t_ereignis WHERE ID_Ereignis = $this->ID"); 
        
        if( $this->db->num_rows() > 0 )
        {
	        $result = $this->db->fetch_array();
	        
	        $this->Inhalt		= $result['Betreff'];
	        $this->Datum		= $result['Datum'];
	        $this->Betreff 		= $result['Titel'];
	        $this->ID_User		= $result['ID_User'];
	        $this->Status		= $result['Status'];
	        $this->ID_Kolonie	= $result['ID_Kolonie'];
        }
        else
        {
        	$error = -1;
        }
        return $error;
    }
    
    /*Gibt UserID zurück*/
    function getUserID()
    {
    	return $this->ID_User;
    }
    
    function saveEreignis($Inhalt, $Betreff, $ID_User, $ID_Kolonie)
    {
        $Status		= 'neu';
        $Datum = time();

        $sql  = "INSERT INTO t_ereignis ( Titel, Betreff, Datum, Status, ID_User, ID_Kolonie)";
        $sql .= "VALUES ( '$Betreff', '$Inhalt', $Datum, '$Status', $ID_User, $ID_Kolonie );";
        $this->db->query($sql);
        
        $this->loadEreignis($this->db->last_insert());
    }
    
    //Liefert den Koordinatenstring, welcher auf ID_Koordinaten verweist
    function getKoordinaten()
    {
        //Kolonie-objekt erzeugen
        $kolo = new KOLONIE($this->ID_Kolonie, $this->db);
        
        //Koordinaten holen
        return $kolo->getKoordinaten();
    }
}?>