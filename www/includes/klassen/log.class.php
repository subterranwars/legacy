<?PHP
/*Diese Klasse stellt LOGGING-Funktionen zur Verfügung

History:
			19.11.2004		MvR		created
*/
class LOG
{
	//Deklarationen
	var $fp;		//FilePointer
	var $pfad = "../spiel/cache/logs";
	
	/*Standardkonstruktor*/
	function LOG($file)
	{
		$this->open($file);
	}
	
	/*Diese Funktion öffnet eine Datei und setzt den Dateihandler => $fp*/
	function open($file)
	{
		$this->fp = fopen($this->pfad."/".$file, 'a+');
	}
	
	/*Diese Funktion schreibt einen '$text' an das Ende der Datei und schiebt vor den
	eigentlich einzufügenden Text das aktuelle Datum ein*/
	function write($text)
	{
		//Setze Text
		$input = date("(d.m.Y H:i:s)", time());
		$input .= $text;
		$input .= "\n";
		
		//Text hineinschreiben
		fwrite($this->fp, $input);
	}
	
	/*Diese Funktion schliesst eine Dateiverbindung wieder*/
	function close()
	{
		fclose($this->fp);
	}
}?>