<?php
/* template.php
Hier wird ein Template geladen und der Instanzvariabel $template zugeweisen um
später auf diese zugreifen und ausgeben zu können

History: 
               04.11.2003	Marskuh 	created !
               21.05.2004 	Marskuh		um einige Features erweitert!
*/
class template 
{
	//Instanzvariablen
	var $anfangsstring = "<!-- TemplateBeginEditable";
	var $endstring	   = "<!-- TemplateEndEditable -->";
	var $template;		//Template vorlage
	var $obj;			/*Verschiedene Objekte des Templates
							[0] = Schablone 
							[1] = name*/
	var $show;			//Enthält eigentlichen Inhalt der verschiedenen Objekte
	var $debug = false;	//Sollen Debugmeldungen angezeigt werden?
	
	//Templatedatei wird geladen ;)          
	function template($datei)
	{	
		//Debugmeldung
		$this->debug("Standardkonstruktor aufgerufen");
	
		//Templates laden
	  	$this->template = implode("", file("$datei"));
		
	  	//Debugmeldung
		$this->debug("Datei $datei geladen <br>".htmlspecialchars($this->template));
	  	
		//Teiltemplates laden
	  	$anfang = 1;
	  	while( $anfang != false )
	  	{
	  		$anfang = $this->getPos(0)-1;																//Anfang
	  		  			  		
	  		//Wurde ein Templateteil gefunden?
	  		if( $anfang != -4)	//Teilstring gefunden
	  		{
	  			//Ende des Templateteils bestimmen
	  			$ende 	= strpos($this->template, $this->endstring, $anfang) + strlen($this->endstring);	
	  				  			
	  			//Bezeichnung des Teilsstrings suchen 
				$tpl_anfang 	= strpos($this->template, "name=\"", $anfang)+strlen("name=\"");;	//Begin des Namens
				$tpl_ende = strpos($this->template, "\"", $tpl_anfang);								//Ende des Namens
				$tpl_name = substr($this->template, $tpl_anfang, $tpl_ende - $tpl_anfang); 			//Teil-Template-Namen holen
				
				//Teilstring-objekt erzeugen			
				$this->obj[$tpl_name]	= substr($this->template, $anfang, $ende-$anfang);
				
				//Debugmeldung
				$this->debug("Teilstring \$anfang, \$ende: ".$tpl_anfang.",".$tpl_ende."");
				$this->debug("Objektname '$tpl_name' erzeugt: <br>".$this->obj[$tpl_name]."<br>".htmlspecialchars($this->obj[$tpl_name]["schablone"]));
				
				//Template löschen
				$this->template	 = substr($this->template, 0, $anfang)."{".$tpl_name."}".substr($this->template, $ende);
				
				//Debugmeldung
				$this->debug("Template bearbeitet:<br>".htmlspecialchars($this->template));
	  		}
	  		else
	  		{
	  			$anfang = false;
	  		}
	  	}
	}
	
	//Letzte Position des Anfangstemplate holen
	function getPos($anfang)
	{
		//DEbugmeldung
		$this->debug("getPos(\$anfang) aufgerufen<br>getPos($anfang)");
		
		//Position ermitteln
		$pos = strpos($this->template, $this->anfangsstring, $anfang);
		$end = strpos($this->template, $this->endstring, $anfang);
		
		//Ist keine Position vorhanden ?
		if( $pos == false AND $end != false )	
		{
			//DEbugmeldung
			$this->debug("kein neues TeilTemplate mehr vorhanden");
			
			//Rekursionsanker
			$ret = $anfang;
		}
		elseif( $end == false)	//Fehlerfall
		{
			$ret = -3;
		}
		else
		{
			//DEbugmeldung
			$this->debug("Teiltemplate noch vorhanden!");
			
			//Sich selber aufrufen
			$ret = $this->getPos($pos+1);
		}			
		return $ret;
	}
	
	//Setzt schabloneninhalt neu
	function setSchablone($obj, $inhalt)
	{
		//echo htmlspecialchars($this->obj[$obj]);
                $this->obj[$obj] = $inhalt;
	}	
	
	//Objekte des Templates setszen
	function setObject($object, $inhalt)
	{
		//Debugmeldung
		$this->debug("setObject(\$object, \$inhalt) aufgerufen<br>setObject($object, $inhalt)");
		
		//Objektinhalt setzen
		$this->show[$object] .= $this->obj[$object];
		$this->show[$object] = $this->ersetzen($object,$inhalt);
	}
		
	//Werte im Template ersetzen
	function ersetzen($obj, $inhalt)
	{
		//DEbugmeldung
		$this->debug("Funktion zum ersetzen aufgerufen!");
		
		//Daten ersetzen 
		$var = $this->show[$obj];
		foreach($inhalt as $key => $value)
		{
			//DEbugmeldung
			$this->debug("Ersetzungsarray durchlaufen: $key => $value");
			
			//Daten ersetezn
			$var = str_replace("%".$key."%","$value",$var);
		}	
		return $var;
	}
		
	//Template ausgeben
	function getTemplate()
	{				
		//Alle Teiltemplates im gesamten Template einfügen
		$this->obj = @array_reverse($this->obj);
		foreach( $this->obj as $key => $value )
		{			
			if( isset($this->show[$key]) )
			{
				$this->template = str_replace("{".$key."}", $this->show[$key], $this->template);
			}
			else
			{
				$this->template = str_replace("{".$key."}", $value , $this->template);
			}
		}
		
		//Template zurückgeben
		return $this->template;
	}
	
	//Debugfunktion
	function debug($string)
	{
		if( $this->debug == true )
		{
			echo "<font color=\"green\"><b>### Debugging: ###</b><br>".$string."</font><hr>";
		}
	}
}?>