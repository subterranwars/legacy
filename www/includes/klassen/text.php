<?php
/*text.php

History:
			06.01.2004	MvR		created
			16.11.2004	MvR		Script ohne DB-Verbindung ausführbar, sowie Entfernung der Kategorie der Replacements
*/

class TEXT
{
    var $replacements;
	var $pic_folder = "cache/icons";
	var $var_replacement;

	//Standardkonstruktor
	function TEXT()
    {
    	//Variablen Replacements
    	$this->var_replacement['FOLDER'] = $this->pic_folder;
    	
    	//Replacements setzen!
		//$this->replacements[nr] = array(Kürzel, Replacement, Kategory);
    	$this->replacements[0] = array('x(', '<img src="%FOLDER%/icon-3.gif">');
		$this->replacements[1] = array('8)', '<img src="%FOLDER%/icon-4.gif">');
		$this->replacements[2] = array(':eek:', '<img src="%FOLDER%/eek.gif">');
		$this->replacements[3] = array(':(', '<img src="%FOLDER%/icon-7.gif">');
		$this->replacements[4] = array(':ahh:', '<img src="%FOLDER%/icon-8.gif">');
		$this->replacements[5] = array(':right:', '<img src="%FOLDER%/icon-10.gif">'); 
		$this->replacements[6] = array('icon_12', '<img src="%FOLDER%/icon-12.gif">');
		$this->replacements[7] = array(':O', '<img src="%FOLDER%/icon-13.gif">');
		$this->replacements[8] = array(':up:', '<img src="%FOLDER%/icon-14.gif">');
		$this->replacements[9] = array(':down:', '<img src="%FOLDER%/icon-15.gif">');
		$this->replacements[10] = array(':)', '<img src="%FOLDER%/smile.gif">');
		$this->replacements[11] = array('=)', '<img src="%FOLDER%/smilie-8.gif">');
		$this->replacements[12] = array(':finger:', '<img src="%FOLDER%/finger.gif">');
		$this->replacements[13] = array(':moep:', '<img src="%FOLDER%/moep.gif">');
		$this->replacements[14] = array(':bussi:', '<img src="%FOLDER%/bussi.gif">');
		$this->replacements[15] = array(':love:', '<img src="%FOLDER%/1luvu.gif">');
		$this->replacements[16] = array(':-*', '<img src="%FOLDER%/kissa.gif">');
		$this->replacements[17] = array(':ditsch:', '<img src="%FOLDER%/ditsch.gif">');
		$this->replacements[18] = array(':kotz:', '<img src="%FOLDER%/kotz.gif">');
		$this->replacements[19] = array(':ficken:', '<img src="%FOLDER%/sex.gif">');
		$this->replacements[20] = array(':prost:', '<img src="%FOLDER%/prost.gif">');
		$this->replacements[21] = array(':zzz:', '<img src="%FOLDER%/sleeping.gif">');
		$this->replacements[22] = array(':fechten:', '<img src="%FOLDER%/fechtduell.gif">');
		$this->replacements[23] = array(':angst:', '<img src="%FOLDER%/angst_3.gif">');
		$this->replacements[24] = array(':dream:', '<img src="%FOLDER%/dream.gif">');
		$this->replacements[25] = array(';)', '<img src="%FOLDER%/zwinker.gif">');
		$this->replacements[26] = array(':ice:', '<img src="%FOLDER%/icecream.gif">');
		$this->replacements[27] = array(':bounce:', '<img src="%FOLDER%/bounce.gif">');
		$this->replacements[28] = array(':frech:', '<img src="%FOLDER%/frech.gif">');
		$this->replacements[29] = array(':peitsch:', '<img src="%FOLDER%/peitsch.gif">');
		$this->replacements[30] = array(':p', '<img src="%FOLDER%/tongue.gif">');
		$this->replacements[31] = array(':attention:', '<img src="%FOLDER%/icon-1.gif">');
		$this->replacements[32] = array(':D', '<img src="%FOLDER%/icon-2.gif">');
		$this->replacements[33] = array(':question:', '<img src="%FOLDER%/icon-6.gif">');
		$this->replacements[34] = array('[b]', '<b>');
		$this->replacements[35] = array('[/b]', '</b>');
		$this->replacements[36] = array('[i]', '<i>');
		$this->replacements[37] = array('[/i]', '</i>');
		$this->replacements[38] = array('[img]', '<img src="');
		$this->replacements[39] = array('[/img]', '">');
		$this->replacements[40] = array('[u]', '<u>');
		$this->replacements[41] = array('[/u]', '</u>');
		$this->replacements[42] = array('[quote]', '<ul><hr>');
		$this->replacements[43] = array('[/quote]', '<hr></ul>');
    }
    
    //Ersetzt alle Zeichen 
    function replacement($string)
    {
    	//Durchlaufe alle Replacements und ersetze diese im Text
        for( $i=0; $i<count($this->replacements); $i++ )
        {
        	//Ersetze %FOLDER% im replacement string
        	$this->replacements[$i][1] = $this->replaceVars($this->replacements[$i][1]);
        	
            //str_replace($ersetzen, $durch, $wo? )
            $string = str_replace($this->replacements[$i][0], $this->replacements[$i][1], $string);
        }
        return $string;
    }
    
    //Ersetzt vordefinierte Variablen durch bestimmte Zeichen 
    function replaceVars($string)
    {
    	foreach( $this->var_replacement as $key => $value )
    	{
    		$string = str_replace("%".$key."%","$value",$string);
    	}
    	return $string;
    }
    
    //Gibt die replacements zurück
    function getReplacements()
    {
    	return $this->replacements;
    }
}?>