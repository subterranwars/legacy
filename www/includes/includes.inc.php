<?php
/*In dieser Datei werden alle Includes gespeichert
*/
DEFINE("_PFAD", "../includes");

// Config
require_once("config.inc.php");

//Klassenincludes
//require_once("klassen/allianz.php");
//require_once("klassen/einheit.php");
require_once(_PFAD."/klassen/bauplan.php");
require_once(_PFAD."/klassen/teile.php");
require_once(_PFAD."/klassen/db.php");
require_once(_PFAD."/klassen/text.php");
require_once(_PFAD."/klassen/fehler.php");
require_once(_PFAD."/klassen/einstellungen.php");
require_once(_PFAD."/klassen/template.php");
require_once(_PFAD."/klassen/general.php");
require_once(_PFAD."/klassen/nachrichten.php");
require_once(_PFAD."/klassen/rasse.php");
require_once(_PFAD."/klassen/rohstoff.php");
require_once(_PFAD."/klassen/einheit.php");
require_once(_PFAD."/klassen/ereignis.php");
require_once(_PFAD."/klassen/user.php");
require_once(_PFAD."/klassen/mission.php");
//require_once("klassen/verteidigungsanlagen.php");
require_once(_PFAD."/klassen/vorkommen.php");
//require_once(_PFAD."/klassen/auftrag.php");
//require_once(_PFAD."/klassen/kampf_todo.php");
require_once(_PFAD."/klassen/kolonie.php");
require_once(_PFAD."/klassen/forschung.php");
require_once(_PFAD."/klassen/forscher.php");
require_once(_PFAD."/klassen/bevoelkerung.class.php");
require_once(_PFAD."/klassen/to_do.class.php");
require_once(_PFAD."/klassen/log.class.php");


//GEbäudeklassen
require_once(_PFAD."/klassen/gebäude/gebäude.php");
require_once(_PFAD."/klassen/gebäude/kraftwerk.php");
require_once(_PFAD."/klassen/gebäude/chemiefabrik.php");
require_once(_PFAD."/klassen/gebäude/schmelze.php");
require_once(_PFAD."/klassen/gebäude/wasserstoff.php");
require_once(_PFAD."/klassen/gebäude/brutreaktor.php");
require_once(_PFAD."/klassen/gebäude/titanschmelze.php");
require_once(_PFAD."/klassen/gebäude/rohstofflager.php");
require_once(_PFAD."/klassen/gebäude/sicherheitslager.php");
require_once(_PFAD."/klassen/gebäude/hauptquartier.php");
require_once(_PFAD."/klassen/gebäude/getreidefeld.php");
require_once(_PFAD."/klassen/gebäude/haus.php");
require_once(_PFAD."/klassen/gebäude/thermalkraftwerk.php");
//require_once(_PFAD."/klassen/gebäude/rohstoffgebäude.php");
/*
require_once(_PFAD."/klassen/gebäude/truppenübungsplatz.php");*/?>