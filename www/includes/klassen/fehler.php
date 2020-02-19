<?php
//Includes
//require_once("includes/klassen/text.php");

class FEHLER
extends TEXT
{
	var $db;
    var $message;	//fehlermeldung
	var $template;	//TemplateObjekt, für die Fehlerausgabe

    function FEHLER(&$db)
    {
       //variablen setzen
       $this->db = &$db;
       $this->template = new TEMPLATE("templates/index/fehler.tpl");
    }
    
    //Fehler-Meldung erzeugen
    function meldung($code)
    {
        //Lade Fehlermeldung
        $this->db->query("SELECT Meldung FROM t_fehler WHERE Nummer = $code");
        $this->message = $this->db->fetch_result(0);
        
        //Erzeuge Ausgabe
        $this->message = $this->setTemplate($this->message, $code);
        $this->message = parent::replacement($this->message);
        return $this->message;
    }
    
    //Ausgabe machen
    function setTemplate($msg, $code)
    {
        //TEmplatedaten setzen
        $daten['ERROR_MSG'] 	= $msg;
        $daten['ERROR_CODE']	= $code; 
        
        //Setze Templatedaten
        $this->template->setObject("error", $daten);
        return $this->template->getTemplate();
    }
}
?>