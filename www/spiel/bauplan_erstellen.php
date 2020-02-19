<?php
/*einheiten_ausbilden.php
Die Datei bekommt einen Wert übergeben, welcher angibt, ob ein Infanterist, 
oder Fahrzeug bzw. Mech ausgebildet werden soll.
*/
//Includes
require("../includes/login_check.php");

//Template daten setzen
$tpl_bauplan = new TEMPLATE("templates/".$_SESSION['skin']."/bauplan_erstellen.tpl");
$daten_bauplan = array(
	'FEHLER' => '',
	'CONTENT' => '');

//Neues Teileobjekt erzeugen!
$teile = new TEILE();

/*Welcher Knopf wurde gedrückt?*/
if( isset($_POST['but_chassis']) )
{
	//Teil laden!
	$teile->loadTeile($_POST['chassis'], $_SESSION['user']->getRasseID());
	$_SESSION['typ'] = $teile->getTyp();
	$_SESSION['chassis'] = $_POST['chassis'];
	
	//Alle nachfolgenden Werte, welche schong esetzt wurden müssen jetzt wieder gelöscht werden
	unset($_SESSION['profil_name']);
	unset($_SESSION['antrieb']);
	unset($_SESSION['munition']);
	unset($_SESSION['waffe']);
	unset($_SESSION['panzerung']);
	
}
if( isset($_POST['but_equipment']) )
{
	unset($_SESSION['profil_name']);
	unset($_SESSION['munition']);
	unset($_SESSION['waffe']);
	unset($_SESSION['panzerung']);
	
	//SessionWert setzen.
	$_SESSION['munition'] = $_POST['munition'];
	$_SESSION['waffe'] = $_POST['waffe'];
	$_SESSION['panzerung'] = $_POST['panzerung'];
}
if( isset($_POST['but_antrieb']) )
{
	//SessionWert setzen.
	$_SESSION['antrieb'] = $_POST['antrieb'];
	
	//Alle nachfolgenden Werte, welche schong esetzt wurden müssen jetzt wieder gelöscht werden
	unset($_SESSION['profil_name']);
	unset($_SESSION['munition']);
	unset($_SESSION['waffe']);
	unset($_SESSION['panzerung']);
}

if( isset($_POST['but_nameselect']) )
{
	//SessionWert setzen.
	$_SESSION['profil_name'] = $_POST['bezeichnung'];
}

if( !isset($_GET['typ']) )
{
	$_GET['typ'] = $_SESSION['typ'];
}
/*Wenn Chassis oder Antrieb bzw. was andere sschon ausgewählt wurde, dann bitte $_GET variable
evtl. neu setzen, da böser User sie löschen wollte!*/
/*if( !empty($_SESSION['chassis']) AND ! )
{
	//Teil laden!
	$teile->loadTeile($_POST['chassis'], $_SESSION['user']->getRasseID());
	
	//Typ des Teils laden und entsprechende $_GET['typ'] setzen
	$_GET['typ'] = $_SESSION['typ'];
}*/

/*Ist Fehlervariable $_GET['fehler'] übergeben worden?*/
if( isset($_GET['fehler']) )	//ja
{
	//Fehler objekt erzeugen und anschliessend Fehler ausgeben!
	$fehler = new FEHLER($db);
	$daten_bauplan['FEHLER'] = $fehler->meldung($_GET['fehler']);
}
//echo $_GET['typ'];
/*Überprüfen um welchen Typ es sich handelt!*/
switch( $_GET['typ'] )
{
	case 1:		//INfanterist
		$_GET['typ'] = 1;			//Falls kein Typ übergeben wurd, jetzt setzen!
		$geb_requirement = 29;		//ID der Kaserne
		break;
	case 2:		//Fahrzeug
	case 3:		//Mech
		$geb_requirement = 31;		//ID der Waffenfabrik
		break;
}

//Hat user die oben festgelegten gebäude
$geb_requirement = $_SESSION['user']->getGebäudeLevel($geb_requirement);

/*Überprüfen ob User Gebäude überhaupt hat!*/
if ( $geb_requirement != 0 ) 
{				
	/*Script-Status durchlaufen*/
	switch( $_GET['action'] )
	{
		/*Profil speichern!*/
		case 'finish':
		{
			//Neues Bauplan objekt erstellen
			$bauplan =new BAUPLAN($db, $_SESSION['user'], $_SESSION['kolonie']);
			
			//Teile setzen!
			$teile_array[0] = $_SESSION['chassis'];
			$teile_array[1] = $_SESSION['antrieb'];
			$teile_array[2] = $_SESSION['waffe'];
			$teile_array[3] = $_SESSION['munition'];
			$teile_array[4] = $_SESSION['panzerung'];
			
			//Bauplan speichern
			$error = $bauplan->saveEinheit($teile_array, $_GET['typ'], $_SESSION['profil_name']);
			
			//Objekt serialisieren
			$_SESSION['user'] 			= serialize($_SESSION['user']);
			$_SESSION['kolonie'] 		= serialize($_SESSION['kolonie']);
			$_SESSION['bevoelkerung'] 	= serialize($_SESSION['bevoelkerung']);
			
			//Fehler abfangen
			if( $error < 1 )	//Fehler aufgetreten
			{												
				switch( $error )
				{
					case -1:		//Manche Teile pasen nicht in das Chassis! (Aufgrund ChassisKategoryAbweichung)
						header("Location: bauplan_erstellen.php?action=chassis&fehler=924");
						exit;
						break;
					case -2:		//Munitionstyp gehört nicht zur Waffe!
						header("Location: bauplan_erstellen.php?action=equipment&fehler=923");
						exit;
						break;
					case -3:		//Maximale Zuladung oder Leistung wurde überschritten!
						header("Location: bauplan_erstellen.php?action=equipment&fehler=922");
						exit;
						break;
					case -4:		//Kein Antrieb
						header("Location: bauplan_erstellen.php?action=antrieb&fehler=921");
						exit;
						break;
					case -5:		//Kein Profilname!
						header("Location: bauplan_erstellen.php?action=select_name&fehler=920");
						exit;
						break;
					case -6:		//Requirements werden nicht erfüllt
						header("Location: bauplan_erstellen.php?action=chassis&fehler=928");
						exit;
						break;
				}
			}
			else 
			{
				//Session variablen löschen!
				unset($_SESSION['chassis']);
				unset($_SESSION['antrieb']);
				unset($_SESSION['waffe']);
				unset($_SESSION['munition']);
				unset($_SESSION['panzerung']);
				unset($_SESSION['typ']);
			
				//Typ abfragen und in das gebäude wechseln
				if( $_GET['typ'] == 1 )	//Kaserne
				{
					header("Location: geb_kaserne.php?show=1");
					exit;
				}
				else 
				{
					header("Location: geb_kaserne.php?show=2");
					exit;
				}
			}
			break;
		}
		/*Profilnamen auswählen*/
		case 'select_name':
		{
			/*Profil_name selektieren!*/
			if( isset($_SESSION['waffe']) AND isset($_SESSION['munition']) AND isset($_SESSION['panzerung']) )
			{
				//Templatedaeti laden
				$tpl_detail = new TEMPLATE("templates/".$_SESSION['skin']."/bauplan_nameselect.tpl");
				
				//Templatedaten setzen
				$daten_bauplan['CONTENT'] = $tpl_detail->getTemplate();				
			}
			else 
			{
				//nicht alle Angaben gemacht!
				$_SESSION['user'] 			= serialize($_SESSION['user']);
				$_SESSION['kolonie'] 		= serialize($_SESSION['kolonie']);
				$_SESSION['bevoelkerung'] 	= serialize($_SESSION['bevoelkerung']);
				header("Location: bauplan_erstellen.php?action=equipment&fehler=926");
				exit;
			}
			break;
		}
		/*Waffen und Munitionsauswahl*/
		case 'equipment':
		{
			/*Als erstes überprüfen ob der User einen Antrieb ausgewählt hat, falls erforderlich!*/
			if( isset($_SESSION['antrieb']) OR $_GET['typ'] == 1 )	//Infanterist oder Antrieb gesetzt
			{
				//Template daten laden
				$tpl_detail = new TEMPLATE("templates/".$_SESSION['skin']."/bauplan_equipment.tpl");
				$daten_detail = array(
					'WAFFEN' => '',
					'PANZERUNG' => '');
				
				//Waffen->Template laden
				$tpl_waffen = new TEMPLATE("templates/".$_SESSION['skin']."/bauplan_waffen.tpl");
				$daten_waffen = array(
					'ID' => '',
					'NAME' => '',
					'ANGRIFF' => '',
					'LEISTUNG' => '',
					'ZULADUNG' => '',
					'ZIELEN' => '-',
					'MUNITION' => '',
					'ROWSPAN' => '');
				
				//Munitionsdaten (Projektile) setzen!
				$tpl_munition[1] = new TEMPLATE("templates/".$_SESSION['skin']."/bauplan_waffen_munition.tpl");
				$daten_munition[1] = array(
					'ID_WAFFE' => '',
					'ID' => '',
					'NAME' => '',
					'ANGRIFF' => '',
					'ZIELEN' => '',
					'ZULADUNG' => '',
					'LEISTUNG' => '-');
				
				//Munitionsdaten (Explosivmunition) setzen!
				$tpl_munition[2] = new TEMPLATE("templates/".$_SESSION['skin']."/bauplan_waffen_munition.tpl");
				$daten_munition[2] = array(
					'ID_WAFFE' => '',
					'ID' => '',
					'NAME' => '',
					'ANGRIFF' => '',
					'ZIELEN' => '',
					'ZULADUNG' => '',
					'LEISTUNG' => '-');
					
				//Munitionsdaten (Energiemunition) setzen!
				$tpl_munition[3] = new TEMPLATE("templates/".$_SESSION['skin']."/bauplan_waffen_munition.tpl");
				$daten_munition[3] = array(
					'ID_WAFFE' => '',
					'ID' => '',
					'NAME' => '',
					'ANGRIFF' => '',
					'ZIELEN' => '',
					'ZULADUNG' => '',
					'LEISTUNG' => '-');
				
				//Munitionsanzahl auf 0 setzen!
				$munition_anzahl[1] = 0;
				$munition_anzahl[2] = 0;
				$munition_anzahl[3] = 0;					
				
				/*Alle Munitionen laden*/
				//Lade Min und max ID der Munition für dne Typ _GET['typ']
				$min_teile_id = $teile->getMinID($_GET['typ'], 4);
				$max_teile_id = $teile->getMaxID($_GET['typ'], 4);
								
				//Munitionsen durchlaufen
				for( $i=$min_teile_id; $i<=$max_teile_id; $i++ )
				{
					//Teildaten laden
					$teile->loadTeile($i, $_SESSION['user']->getRasseID());
					
					//Kann Teil gebaut werden
					$erfuellt = $teile->checkRequirement($_SESSION['user'], $_SESSION['kolonie']);
		
					//Kann gebaut werden?
					if( $erfuellt == 1 )
					{
						//Projektilwaffen
						if( $teile->getTyp() == 1 )
						{
							//Templatedaten setzen
							$daten_munition[1]['ID'] 		= $teile->getID();
							$daten_munition[1]['NAME'] 		= $teile->getBezeichnung();
							$daten_munition[1]['ANGRIFF'] 	= $teile->getAngriff();
							$daten_munition[1]['ZIELEN']	= $teile->getZielen();
							$daten_munition[1]['ZULADUNG']	= $teile->getZuladung();
						
							//Daten ersetzen
							$tpl_munition[1]->setObject('bauplan_waffen_munition', $daten_munition[1]);
							$munition_anzahl[1]++;
						}
						//Explosiv
						elseif( $teile->getTyp() == 2 )
						{
							//Templatedaten setzen
							$daten_munition[2]['ID'] 		= $teile->getID();
							$daten_munition[2]['NAME'] 		= $teile->getBezeichnung();
							$daten_munition[2]['ANGRIFF'] 	= $teile->getAngriff();
							$daten_munition[2]['ZIELEN']	= $teile->getZielen();
							$daten_munition[2]['ZULADUNG']	= $teile->getZuladung();
						
							//Daten ersetzen
							$tpl_munition[2]->setObject('bauplan_waffen_munition', $daten_munition[2]);
							$munition_anzahl[2]++;
						}
						//Energie
						elseif( $teile->getTyp() == 3)
						{
							//Templatedaten setzen
							$daten_munition[3]['ID'] 		= $teile->getID();
							$daten_munition[3]['NAME'] 		= $teile->getBezeichnung();
							$daten_munition[3]['ANGRIFF'] 	= $teile->getAngriff();
							$daten_munition[3]['ZIELEN']	= $teile->getZielen();
							$daten_munition[3]['ZULADUNG']	= $teile->getZuladung();
						
							//Daten ersetzen
							$tpl_munition[3]->setObject('bauplan_waffen_munition', $daten_munition[3]);
							$munition_anzahl[3]++;
						}	
					}					
				}
					
				/*Waffen laden!*/
				//Lade Min und max ID der Waffen für dne Typ _GET['typ']
				$min_teile_id = $teile->getMinID($_GET['typ'], 3);
				$max_teile_id = $teile->getMaxID($_GET['typ'], 3);
					
				//Waffen durchlaufen
				for( $i=$min_teile_id; $i<=$max_teile_id; $i++ )
				{					
					//Waffendetails laden!
					$teile->loadTeile($i, $_SESSION['user']->getRasseID());

					//Bestitz Waffe daten?
					if( $teile->getBezeichnung() != Null )
					{	
						//Kann Teil gebaut werden
						$erfuellt = $teile->checkRequirement($_SESSION['user'], $_SESSION['kolonie']);
		
						//Kann gebaut werden?
						if( $erfuellt == 1 )		
						{			
							//WaffenTemplateDaten setzen!
							$daten_waffen['ID']			= $teile->getID();
							$daten_waffen['NAME']		= $teile->getBezeichnung();
							$daten_waffen['ANGRIFF']	= $teile->getAngriff();
							$daten_waffen['LEISTUNG']	= $teile->getLeistung();
							$daten_waffen['ZULADUNG']	= $teile->getZuladung();
							$daten_waffen['ROWSPAN']	= $munition_anzahl[$teile->getTyp()]+1;
							
							//Ist von diesem Typ min 1 Munitionstyp vorhanden?
							if( $munition_anzahl[$teile->getTyp()] <= 0 )
							{
								//Fehlerobjekt erzeugne
								$fehler =new FEHLER($db);
								
								//Fehlerdaten setzen!
								$daten_waffen['MUNITION'] = $fehler->meldung(933);
							}
							else 	//min 1 Munition vorhanden
							{
								$daten_waffen['MUNITION'] = $tpl_munition[$teile->getTyp()]->getTemplate();	//Munitionsdaten hinzufügen
							}
							//Templatedaten ersetzen
							$tpl_waffen->setObject('bauplan_waffen', $daten_waffen);
							
							//Anzahl setzen wie viele Teile gebaut wurden
							$anzahl_avaiable_teile_waffen++;
						}		
					}
				}
					
				/*Panzerungen laden*/
				//Panzerungs-Template laden
				$tpl_panzerung = new TEMPLATE("templates/".$_SESSION['skin']."/bauplan_panzerung.tpl");
				$daten_panzerung = array(
					'ID' => '',
					'NAME' => '',
					'PANZERUNG' => '',
					'ZULADUNG' => '',
					'LEISTUNG' => '');
					
				/*Panzerungen laden!*/
				//Lade Min und max ID der Panzerungen für dne Typ _GET['typ']
				$min_teile_id = $teile->getMinID($_GET['typ'], 5);
				$max_teile_id = $teile->getMaxID($_GET['typ'], 5);
				
				//Panzerungen durchlaufen
				for( $i=$min_teile_id; $i<=$max_teile_id; $i++ )
				{
					//LAde Panzerungsdetails!
					$teile->loadTeile($i, $_SESSION['user']->getRasseID());
					
					//bestitzt Teil informationen?
					if( $teile->getBezeichnung() != NULL )
					{	
						//Kann Teil gebaut werden
						$erfuellt = $teile->checkRequirement($_SESSION['user'], $_SESSION['kolonie']);
			
						//Kann gebaut werden?
						if( $erfuellt == 1 )
						{
							//Templatedaten setzen!
							$daten_panzerung['ID'] 			= $teile->getID();
							$daten_panzerung['NAME'] 		= $teile->getBezeichnung();
							$daten_panzerung['PANZERUNG'] 	= $teile->getPanzerung();
							$daten_panzerung['ZULADUNG']	= $teile->getZuladung();
							$daten_panzerung['LEISTUNG']	= $teile->getLeistung();
							
							//Templateobjekt hinzufügen
							$tpl_panzerung->setObject("bauplan_panzerung", $daten_panzerung);
							
							//Anzahl setzen wie viele Teile gebaut wurden
							$anzahl_avaiable_teile_panzerung++;
						}
					}
				}
				
				//Wenn mehr als 1 Teil vorhanden ist, dann anzeige machen, ansonsten Fehler!
				if( $anzahl_avaiable_teile_waffen <= 0 )
				{
					//Fehlerobjekt erzeugen
					$fehler = new FEHLER($db);
					
					//Equipment Template setzen
					$daten_detail['WAFFEN'] 	= $fehler->meldung(931);
				}
				//Keine Panzerung vorhanden
				if( $anzahl_avaiable_teile_panzerung <= 0 )
				{
					//Fehlerobjekt erzeugen
					$fehler = new FEHLER($db);
					
					//Equipment Template setzen
					$daten_detail['PANZERUNG'] 	= $fehler->meldung(932);
				}
				//Sollen Waffen angezeigt werden?
				if( $anzahl_avaiable_teile_waffen > 0 )
				{
					
					$daten_detail['WAFFEN'] 	= $tpl_waffen->getTemplate();
					
				}
				//Sollen Panzerungen angezeigt werden?
				if( $anzahl_avaiable_teile_panzerung > 0 )
				{
					//Equipment Template setzen
					$daten_detail['PANZERUNG']	= $tpl_panzerung->getTemplate();
				}
								
				//Bauplan erstellenTemplate setzen
				$tpl_detail->setObject("bauplan_equipment", $daten_detail);
				$daten_bauplan['CONTENT'] = $tpl_detail->getTemplate();				
			}
			else 	//Kein Antrieb gewählt
			{
				//Zur Antriebsauswahl springen
				$_SESSION['user'] 			= serialize($_SESSION['user']);
				$_SESSION['kolonie'] 		= serialize($_SESSION['kolonie']);
				$_SESSION['bevoelkerung'] 	= serialize($_SESSION['bevoelkerung']);
				header("Location: bauplan_erstellen.php?action=antrieb");
			}
			break;
		}
		/*Antriebsauswahl*/
		case 'antrieb':
		{
			/*ISt Chassis schon ausgwählt worden?*/
			if( !empty($_SESSION['chassis']) )
			{
				/*Ist Chassis ein InfanteristenChassis?*/
				$teile->loadTeile($_SESSION['chassis'], $_SESSION['user']->getRasseID());
				$chassis_typ = $teile->getTyp();
				
				if($chassis_typ == 1 ) 		//Infanterist
				{
					//Auswahl überspringen!
					$_SESSION['user'] 			= serialize($_SESSION['user']);
					$_SESSION['kolonie'] 		= serialize($_SESSION['kolonie']);
					$_SESSION['bevoelkerung'] 	= serialize($_SESSION['bevoelkerung']);
					header("Location: bauplan_erstellen.php?action=equipment");
					exit;
				}
				else 		//Antriebsauswahl erstellen!
				{
					//Template daten setzen
					$tpl_detail 	= new TEMPLATE("templates/".$_SESSION['skin']."/bauplan_antrieb.tpl");
					$daten_detail = array(
						'ID' => '',
						'NAME' => '',
						'GESCHWINDIGKEIT' => '',
						'WENDIGKEIT' => '',
						'LEISTUNG' => '',
						'ZULADUNG' => '');
					
					//Lade Min und max ID der Antriebe für dne Typ _GET['typ']
					$min_teile_id = $teile->getMinID($_GET['typ'], 2);
					$max_teile_id = $teile->getMaxID($_GET['typ'], 2);
					
					/*Alle Antriebe für den Typ Fahrzeug oder Mech laden!*/
					for( $i=$min_teile_id; $i<=$max_teile_id; $i++ )
					{
						//ANtriebsdetails laden
						$teile->loadTeile($i, $_SESSION['user']->getRasseID());
						
						//Ist Teil mit Werten belegt?
						if( $teile->getBezeichnung() != NULL )
						{
							//Kann Teil gebaut werden
							$erfuellt = $teile->checkRequirement($_SESSION['user'], $_SESSION['kolonie']);
		
							//Kann gebaut werden?
							if( $erfuellt == 1 )
							{
								//TemplateDaten setzen!
								$daten_detail['ID']				= $teile->getID();
								$daten_detail['NAME']			= $teile->getBezeichnung();
								$daten_detail['GESCHWINDIGKEIT']= $teile->getGeschwindigkeit();
								$daten_detail['WENDIGKEIT']		= $teile->getWendigkeit();
								$daten_detail['LEISTUNG']		= $teile->getLeistung();
								$daten_detail['ZULADUNG']		= $teile->getZuladung();
							
								//Templatedaten ersetzen
								$tpl_detail->setObject('bauplan_antrieb', $daten_detail);	
								
								//Anzahl setzen wie viele Teile gebaut wurden
								$anzahl_avaiable_teile++;
							}
						}
					}
					
					//Wenn mehr als 1 Teil vorhanden ist, dann anzeige machen, ansonsten Fehler!
					if( $anzahl_avaiable_teile > 0 )
					{
						$daten_bauplan['CONTENT'] = $tpl_detail->getTemplate();	//Templatedaten ersetzen
					}
					else 
					{
						//Fehlerobjekt erzeugen
						$fehler = new FEHLER($db);
						$daten_bauplan['CONTENT'] = $fehler->meldung(930);
					}
				}
			}
			else	//Kein Chassis ausgewählt
			{
				//Userobjekt serialisieren und weiterleiten
				$_SESSION['user'] 			= serialize($_SESSION['user']);
				$_SESSION['kolonie'] 		= serialize($_SESSION['kolonie']);
				$_SESSION['bevoelkerung'] 	= serialize($_SESSION['bevoelkerung']);
				header("Location: bauplan_erstellen.php?typ=".$_GET['typ']."&fehler=925");
				exit;
			}
			break;
		}
		/*Chassisauswahl*/
		default:
		{
			//Template laden
			$tpl_detail = new TEMPLATE("templates/".$_SESSION['skin']."/bauplan_chassis.tpl");
			$daten_detail = array(
				'ID' => '',
				'NAME' => '',
				'HP' => '',
				'MAX_LEISTUNG' =>'',
				'MAX_ZULADUNG' => '',
				'GESCHWINDIGKEIT' => '',
				'WENDIGKEIT' => '',
				'ZIELEN' => '');
								
			//Lade Min und max ID der Chassis für dne Typ _GET['typ']
			$min_teile_id = $teile->getMinID($_GET['typ'], 1);
			$max_teile_id = $teile->getMaxID($_GET['typ'], 1);

			/*Durchlaufe alle Chassis zur Chassisauswahl!*/
			for( $i=$min_teile_id; $i<=$max_teile_id; $i++ )
			{
				//Chassisdetail laden
				$teile->loadTeile($i, $_SESSION['user']->getRasseID());
				
				//Teil darf nicht leer sein
				if( $teile->getBezeichnung() != null )
				{
					//Kann Teil gebaut werden
					$erfuellt = $teile->checkRequirement($_SESSION['user'], $_SESSION['kolonie']);
		
					//Kann gebaut werden?
					if( $erfuellt == 1 )
					{
						//TemplateDaten setzen!
						$daten_detail['ID']				= $teile->getID();
						$daten_detail['NAME'] 			= $teile->getBezeichnung();
						$daten_detail['HP']				= $teile->getLebenspunkte();
						$daten_detail['MAX_LEISTUNG']	= $teile->getLeistung();
						$daten_detail['MAX_ZULADUNG']	= $teile->getZuladung();
						$daten_detail['WENDIGKEIT']		= $teile->getWendigkeit();
						$daten_detail['ZIELEN']			= $teile->getZielen();
					
				
						/*Überprüfen um was für einen Chassistyp es sich handelt und bei
						Panzern bzw. Mechs die Geschwindigkeit in % angeben!*/
						if( $teile->getTyp() == 2 || $teile->getTyp() == 3 )	//Panzer odre mech!
						{
							$daten_detail['GESCHWINDIGKEIT']	 = $teile->getGeschwindigkeit() * 100;
							$daten_detail['GESCHWINDIGKEIT'] 	.= "%";
						}
						else 
						{
							$daten_detail['GESCHWINDIGKEIT']= $teile->getGeschwindigkeit()." km/h";
						}
						
						//Templatedaten ersetzen
						$tpl_detail->setObject('bauplan_chassis', $daten_detail);	
						
						//Anzahl setzen wie viele Teile gebaut wurden
						$anzahl_avaiable_teile++;
					}
				}
			}
			
			//Wenn mehr als 1 Teil vorhanden ist, dann anzeige machen, ansonsten Fehler!
			if( $anzahl_avaiable_teile > 0 )
			{
				$daten_bauplan['CONTENT'] = $tpl_detail->getTemplate();	//Templatedaten ersetzen
			}
			else 
			{
				//Fehlerobjekt erzeugen
				$fehler = new FEHLER($db);
				$daten_bauplan['CONTENT'] = $fehler->meldung(929);
			}
			break;
		}
	}	
	//Templatedaten ersetzen
	$tpl_bauplan->setObject("bauplan", $daten_bauplan);
	$daten['CONTENT'] = $tpl_bauplan->getTemplate();
}
else 
{
	//Fehlerobjekt erzeugen
	$fehler = new FEHLER($db);
	$daten['CONTENT'] = $fehler->meldung(141);
}

//footer einbinden
require_once("../includes/footer.php");