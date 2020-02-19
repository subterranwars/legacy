<?php
/*einheiten_ausbilden.php
Die Datei bekommt einen Wert übergeben, welcher angibt, ob ein Infanterist, 
oder Fahrzeug bzw. Mech ausgebildet werden soll.
*/
//Includes
require("../includes/login_check.php");

//Wenn ID der Truppe via $_GET übergeben wird, die SESSION-Variable aktuallisieren
if( isset($_GET['ID']) )
{	
	//Session-Wert setzen!
	unset($_SESSION['m_typ']);
	$_SESSION['ID_Flotte'] = $_GET['ID'];
}

/*überprüfen ob ausgewählte truppe auch dir gehört!*/
$db->query("SELECT COUNT(*) FROM t_flotte WHERE ID_Flotte =".$_SESSION['ID_Flotte']." AND ID_User = ".$_SESSION['user']->getUserID()."");
$anzahl = $db->fetch_result(0);

//Gehört User die Truppe?
if( $anzahl > 0 )	//ja
{
	//Missionsobjekt erzeugen
	$mission = new MISSION($db, $_SESSION['user']);
	
	//ist Truppe verfügbar und noch keiner anderen Mission zugeordnet?
	if( $mission->checkTruppe($_SESSION['ID_Flotte']) == 1 )	//Kein Fehler
	{
		
		/*Template laden*/
		$tpl_mission = new TEMPLATE("templates/".$_SESSION['skin']."/mission.tpl");
		$daten_mission = array(
				'FEHLER' => '',
				'CONTENT' => '');
	
		/*Ist FehlerID über $_GET übergeben worden?*/
		if( isset($_GET['fehler']) )
		{
			//Fehlerobjekt erzeugen
			$fehler =new FEHLER($db);
			$daten_mission['FEHLER'] = $fehler->meldung($_GET['fehler']);
		}
				
		/*Ist Missinstyp ausgewählt worden?*/
		if( isset($_POST['m1']) )
		{
			$_SESSION['m_typ'] = $_POST['typ'];
		}
		
		/*Ist Knopf nach Koordinatenauswahl gedrückt worden?*/
		if( isset($_POST['m2']) )
		{
			//Destination koords
			$_SESSION['m_koords'][0] = $_POST['x'];
			$_SESSION['m_koords'][1] = $_POST['y'];
			$_SESSION['m_koords'][2] = $_POST['z'];
			
			//Source Koordinaten
			$_SESSION['m_koords2'][0] = $_POST['xs'];
			$_SESSION['m_koords2'][1] = $_POST['ys'];
			$_SESSION['m_koords2'][2] = $_POST['zs'];
			
			//Geschwindigkeit speichern
			$_SESSION['m_geschwindigkeit'] = $_POST['geschwindigkeit'];
		}
		
		//Missionsstatus durchlaufen
		switch( $_GET['action'] )
		{
			//Missionsauswahl fertig. Daten eintragen
			case 'finish':
			{
				//Template laden
				$tpl_detail = new TEMPLATE("templates/".$_SESSION['skin']."/mission_parameter.tpl");
				
				//Setze m_parameter auf 0!	
				$m_parameter = array(0,0,0);/*	$m_parameter[0] = Rohstoffe
												$m_parameter[1] = IdleTime
												$m_parameter[2] = Artillerie*/
				$error = 0;
				//Je nach missionsparamaeter andere Daten eintragen
				switch($_SESSION['m_typ'])
				{
					//Angriff und sonstige
					default:
					case 1:
						/*Daten auf Korrektheit überprüfen*/
						//Sind die Reihenfolgen der Rohstoffen richtig gesetzt?		
						$array_count = array_unique($_POST['m_a_res']);
						if( count($array_count) != count($_POST['m_a_res']) )
						{
							//Daten falsch!
							$_SESSION['user'] 			= serialize($_SESSION['user']);
							$_SESSION['kolonie'] 		= serialize($_SESSION['kolonie']);
							$_SESSION['bevoelkerung'] 	= serialize($_SESSION['bevoelkerung']);
							header("Location: mission.php?action=spezial&fehler=974");
							exit;
						}
						else
						{
							//Ressourcenparameter setzen
							$m_parameter[0] = '';
							for($i=1; $i<=count($_POST['m_a_res']); $i++)
							{
								$m_parameter[0] .= "".$_POST['m_a_res'][$i-1].",";
							}
							//Artilleriesupport setzen
							$m_parameter[2] = 0;
						}
						break;
					/*case 2:
						break;*/
					//Truppen verlegen
					case 3:
						break;
					//Truppe übergeben
					case 4:
						break;
					/*case 5:
						break;*/
					//Rohstoffe Transportieren
					case 6:
						//RessourcenParameter resetten
						$m_parameter[0] = '';
						$zuladung = 0;
						
						//Durchlaufe Rohstoffanzahl und schau, dass User geegebene Menge hat
						for($i=0; $i<count($_POST['m_t_res']); $i++)
						{
							//Absolutwert eintragen!
							$_POST['m_t_res'][$i] = abs($_POST['m_t_res'][$i]);
							
							//Möchte User mehr eintragen als er hat?
							if($_SESSION['user']->getRohstoffAnzahl($_POST['m_t_id'][$i]) < $_POST['m_t_res'][$i])
							{
								//Werte aktuallisieren
								$_POST['m_t_res'][$i] = (int)$_SESSION['user']->getRohstoffAnzahl($_POST['m_t_id'][$i]);
							}
						}
						
						//Hat Usre genügend Platz um Rohstoffe zu verschicken?
						if( $zuladung > $mission->getMaxZuladungRohstofftransport($_SESSION['ID_Flotte']) )
						{
							$error = -1;
						}
						//Keine Fehler Rohstoffe können verschickt werden
						else
						{
							//Durchlaufe Rohstoffanzahl und setze Datenbank-Werte bzw. ziehe User Rohstoffe ab
							for($i=0; $i<count($_POST['m_t_res']); $i++)
							{
								//Ressourcen abziehen
								$_SESSION['user']->setRohstoffAnzahl(((-1)*$_POST['m_t_res'][$i]), $_POST['m_t_id'][$i]);
									
								//Setze Zuladung um sie später zu überprüfen
								$zuladung += $_POST['m_t_res'][$i];
																
								//Ressourcenparameter setzen
								$m_parameter[0] .= $_POST['m_t_id'][$i]."|".$_POST['m_t_res'][$i].",";
							}
						}
						break;
				}
				
				//Sind bis hier her keine Fehler aufgetreten?
				if( $error == 0 )	//Keine Fehler!
				{
					//Mission starten
					$error = $mission->startMission(
											$_SESSION['m_koords2'], 
											$_SESSION['m_koords'],
											$_SESSION['ID_Flotte'], 
											$_SESSION['m_typ'],
											$_SESSION['m_geschwindigkeit'],
											$m_parameter);
					
					//Objekt serialiseren 
					$_SESSION['user'] 			= serialize($_SESSION['user']);
					$_SESSION['kolonie'] 		= serialize($_SESSION['kolonie']);
					$_SESSION['bevoelkerung'] 	= serialize($_SESSION['bevoelkerung']);
											
					/*Fehlerüberprüfung beim starten der Mission*/
					switch( $error )
					{
						//Kein fehler
						case 1:
							header("Location: spiel.php");
							exit;
							break;
						//Truppenverband bereits unterwegs
						case -1:
							header("Location: mission.php");
							exit;
							break;
						//Ziel und Quelle identisch
						case -2:
							header("Location: mission.php?action=koordinaten&fehler=970");
							exit;
							break;
						//Kein Missionstyp angegeben
						case -3:
							header("Location: mission.php?fehler=977");
							exit;
							break;
					}
				}
				else	//Fehler sind aufgetreten!
				{
					//Objekt serialiseren 
					$_SESSION['user'] 			= serialize($_SESSION['user']);
					$_SESSION['kolonie'] 		= serialize($_SESSION['kolonie']);
					$_SESSION['bevoelkerung'] 	= serialize($_SESSION['bevoelkerung']);
					
					//Fehler durchlaufen
					switch($error)		
					{
						//Zulässige GesamtZuladung wird überschritten
						case -1:		
							header("Location: mission.php?action=spezial&fehler=979");
							exit;	
							break;
					}
				}					
				break;
			}
			//Parameterspezifische Option
			case 'spezial':
			{
				/*Übergebenen Koordinaten überprüfen!*/				
				//Sind Koordinaten identisch?
				$error = $mission->checkKoordinaten($_SESSION['m_koords2'], $_SESSION['m_koords']);
				if( $error <= 0 )	//Koordinaten identisch oder nicht vorhanden!
				{
					if( $error == -1 )
					{
						$fehler = 970;
					}
					else 
					{
						$fehler = 973;
					}
					//Fehler zurückleiten
					$_SESSION['user'] 			= serialize($_SESSION['user']);
					$_SESSION['kolonie'] 		= serialize($_SESSION['kolonie']);
					$_SESSION['bevoelkerung'] 	= serialize($_SESSION['bevoelkerung']);
					header("Location: mission.php?fehler=$fehler&action=koordinaten");	
					exit;
				}
				/*Koordinaten nicht identisch!
				Überprüfen ob Koordinaten beim Angriff etc bewohnt sind*/
				elseif( $_SESSION['m_typ'] != 5 AND $mission->isBewohnt($mission->getKoordinatenID($_SESSION['m_koords'])) == -1 )
				{
					//Koordinaten unbewohnt kann Angriff etc nicht ausführen
					$_SESSION['user'] 			= serialize($_SESSION['user']);
					$_SESSION['kolonie'] 		= serialize($_SESSION['kolonie']);
					$_SESSION['bevoelkerung'] 	= serialize($_SESSION['bevoelkerung']);
					header("Location: mission.php?fehler=971&action=koordinaten");
					exit;
				}
				/*Koordinaten nicht identisch!
				Überprüfen ob Koordinaten bei Kolonisation unbewohnt sind*/
				elseif( $_SESSION['m_typ'] == 5 AND $mission->isBewohnt($mission->getKoordinatenID($_SESSION['m_koords'])) == 1 )
				{
					//Koordinaten bewohnt kann nicht kolonisieren
					$_SESSION['user'] 			= serialize($_SESSION['user']);
					$_SESSION['kolonie'] 		= serialize($_SESSION['kolonie']);
					$_SESSION['bevoelkerung'] 	= serialize($_SESSION['bevoelkerung']);
					header("Location: mission.php?fehler=972&action=koordinaten");
					exit;
				}
				else 	//Keine Fehler
				{
					//Durchlaufe Misssionparameter um entsprechendes Template zu laden
					switch($_SESSION['m_typ'])
					{
						//Angriff und sonstige
						default:
						case 1:
						{
							//Template laden!
							$tpl_detail = new TEMPLATE("templates/".$_SESSION['skin']."/mission_angriff.tpl");
							$daten_detail = array(
								'ARTILLERIE' => '',
								'ROHSTOFFE' => '');
								
							//Rohstofftemplate laden
							$tpl_angriff_res = new TEMPLATE("templates/".$_SESSION['skin']."/mission_angriff_rohstoffe.tpl");
							$daten_angriff_res = array(
								'I' => '',
								'SELECT' => '');
							
							//Rohstoffobjekt erzeugen
							$rohstoff = new ROHSTOFF();
							
							//SelectAuswahl des Rohstofftemplates setzen
							$daten_angriff_res['SELECT'] = '<select name="m_a_res[]">';
							for($i=1; $i<=count($rohstoff->Rohstoffe); $i++)
							{
								$daten_angriff_res['SELECT'] .= "<option value=\"$i\">".$rohstoff->Rohstoffe[$i][0];
							}
							$daten_angriff_res['SELECT'] .= "</select>";
							
							//Templatedaten aktuallisieren!
							for($i=1; $i<=3; $i++)
							{
								//Setze Templateselect!
								$daten_angriff_res['I'] = $i;
								
								//Templatedaten setzen
								$tpl_angriff_res->setObject("mission_angriff_rohstoffe_anzahl", $daten_angriff_res);						
								$tpl_angriff_res->setObject("mission_angriff_rohstoffe_select", $daten_angriff_res);
							}
							
							//Daten setzen
							$daten_detail['ARTILLERIE'] =	'<div class="red">Diese Option ist zur Zeit deaktiviert</div>'; 
							$daten_detail['ROHSTOFFE'] = $tpl_angriff_res->getTemplate();
							
							//Rohstoffdaten aktuallisieren
							$tpl_detail->setObject("mission_angriff", $daten_detail);							
							break;
						}
						/*case 2:
							break;*/
						//Truppe verlegen
						case 3:
							//Überprüfen ob Destination dir gehört
							$ID_Koordinaten = $mission->getKoordinatenID($_SESSION['m_koords']);
							
							//Selektiere ID_Kolonie
							$db->query("SELECT ID_Kolonie FROM t_kolonie WHERE ID_Koordinaten = $ID_Koordinaten AND ID_User = ".$_SESSION['user']->getUserID()."");
							$ID_Kolonie = $db->fetch_result(0);
							
							//Wenn ID_Kolonie != 0, dann sind koordinaten richtig
							if( $ID_Kolonie != 0 )
							{
								$_SESSION['user'] 			= serialize($_SESSION['user']);
								$_SESSION['kolonie'] 		= serialize($_SESSION['kolonie']);
								$_SESSION['bevoelkerung'] 	= serialize($_SESSION['bevoelkerung']);
								header("Location: mission.php?action=finish");
								exit;
							}
							else 
							{	$_SESSION['user'] 			= serialize($_SESSION['user']);
								$_SESSION['kolonie'] 		= serialize($_SESSION['kolonie']);
								$_SESSION['bevoelkerung'] 	= serialize($_SESSION['bevoelkerung']);
								header("Location: mission.php?action=koordinaten&fehler=976");
								exit;
							}
							break;
						case 4:
							$_SESSION['user'] 			= serialize($_SESSION['user']);
							$_SESSION['kolonie'] 		= serialize($_SESSION['kolonie']);
							$_SESSION['bevoelkerung'] 	= serialize($_SESSION['bevoelkerung']);
							header("Location: mission.php?action=finish");
							exit;
							break;
						/*case 5:
							break;*/
						//Transport
						case 6:
							//TemplateDaten
							$tpl_detail = new TEMPLATE("templates/".$_SESSION['skin']."/mission_transport.tpl");
							$daten_detail = array(
								'ID' => '',
								'NAME' => '');
							
							//Rohstoffe durchlaufen
							$rohstoffe = new ROHSTOFF();
							for($i=1; $i<=count($rohstoffe->Rohstoffe); $i++)
							{
								//Daten setzen
								$daten_detail['ID'] 	= $i;
								$daten_detail['NAME'] 	= $rohstoffe->Rohstoffe[$i][0];
								
								//Daten aktuallisieren
								$tpl_detail->setObject("mission_transport_detail", $daten_detail);
							}
							
							//Maximale Ladekapazitaet setzen
							$daten_detail['MAX_LADEKAPAZITAET'] = number_format($mission->getMaxZuladungRohstofftransport($_SESSION['ID_Flotte']),0,",",".");
							$tpl_detail->setObject("mission_transport", $daten_detail);
							break;
					}
				}
				break;
			}
			//Koordinatenauswahl
			case 'koordinaten':
			{
				/*überprüfen ob missionstyp gesetzt wurde*/
				if( !empty($_SESSION['m_typ']) )
				{
					//Template laden
					$tpl_detail = new TEMPLATE("templates/".$_SESSION['skin']."/mission_koordinaten.tpl");
					$daten_detail = array(
						'X' => '',
						'Y' => '',
						'Z' => '',
						'ENTFERNUNG' =>'',
						'MAX' => '',
						'DAUER' => '',
						'ANKUNFT' => '',
						'RÜCKKEHR' => '');
		
					//Objekte erzeugen
					$kolo = new KOLONIE($_SESSION['kolonie']->getID(), $db);
					
					//Koordssource und Destination setzen!
					$koords_source[0] = $kolo->getX();
					$koords_source[1] = $kolo->getY();
					$koords_source[2] = $kolo->getZ();
					$koords_destination[0] = $kolo->getX();
					$koords_destination[1] = $kolo->getY();
					$koords_destination[2] = $kolo->getZ();
					
					//Daten setzen
					$daten_detail['X'] 			= $kolo->getX();
					$daten_detail['Y'] 			= $kolo->getY();
					$daten_detail['Z'] 			= $kolo->getZ();
					$daten_detail['MAX'] 		= $mission->getMaxGeschwindigkeit($_SESSION['ID_Flotte']);
					$daten_detail['ENTFERNUNG'] = $mission->getEntfernung($koords_source, $koords_destination);
					$daten_detail['DAUER']		= $mission->getTime($koords_source, $koords_destination, $_SESSION['ID_Flotte'],1);
					$daten_detail['ANKUNFT'] 	= date("D, d.m.Y H:i:s",time() + $daten_detail['DAUER'] * 3600);
					$daten_detail['RÜCKKEHR'] 	= date("D, d.m.Y H:i:s",time() + $daten_detail['DAUER'] * 2 * 3600);
					
					//Templatedaten ersetzen!
					$tpl_detail->setObject("mission_koordinaten", $daten_detail);
				}
				else 	//Kein Missionstyp ausgewählt
				{
					//Userobjekt serialisieren und aufgrund eines Fehlers weiterleiten
					$_SESSION['user'] 			= serialize($_SESSION['user']);
					$_SESSION['kolonie'] 		= serialize($_SESSION['kolonie']);
					$_SESSION['bevoelkerung'] 	= serialize($_SESSION['bevoelkerung']);
					header("Location: mission.php?fehler=977");
					exit;
				}
				break;
			}
			//Standard
			default:
			{
				//Missionsparameter
				$tpl_detail = new TEMPLATE("templates/".$_SESSION['skin']."/mission_parameter.tpl");
				break;
			}
		}
		//TEmplatedaten ersetzen
		$daten_mission['CONTENT'] = $tpl_detail->getTemplate();
		$tpl_mission->setObject("mission", $daten_mission);
		$daten['CONTENT'] = $tpl_mission->getTemplate();
	}
	else	//TRuppe ist bereitst unterwegs
	{
		$fehler = new FEHLER($db);
		$daten['CONTENT'] = $fehler->meldung(978);
	}
}
else	//Truppe gehört wem anders!
{
	//Fehler erstellen
	$fehler = new FEHLER($db);	
	$daten['CONTENT'] = $fehler->meldung(953);
}

//footer einbinden
require_once("../includes/footer.php");