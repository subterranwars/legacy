/*db.cpp
Diese Klasse integriert die vom Header gelieferten Deklarationen und 
stellt diese Funktionen dann zur Verfügung

History:
			07.09.2004	MvR		created
*/

#include "db.h"

/*Standardkonstruktor*/
DATENBANK::DATENBANK()
{
	#if URL == 1	//oflinemodus
		sprintf(this->szHostname, "localhost");
		sprintf(this->szPasswort, "");
		sprintf(this->szUser, "root");
		sprintf(this->szDatenbank, "usr_web26_3");
		this->iShowErrors = 0;
	#else
		sprintf(this->szHostname, "localhost");
		sprintf(this->szPasswort, "O2TQk5");
		sprintf(this->szUser, "web0");
		sprintf(this->szDatenbank, "usr_web0_1");
		this->iShowErrors = 0;
	#endif

	//Datenbankverbindung herstellen
	this->db_connect(this->szHostname, this->szUser, this->szPasswort, this->szDatenbank);
}

/*Dekonstruktor*/
DATENBANK::~DATENBANK()
{
}

/*Funktion verbindet zum Server*/
void DATENBANK::db_connect(char *psHost, char *psUser, char *psPasswort, char *psDatenbank)
{
	this->dbHandle = mysql_init(0);
	this->dbHandle = mysql_real_connect(this->dbHandle,
		psHost,  	 /* on what host */
        psUser,      /* the user */
        psPasswort,  /* no password */
        psDatenbank, /* the database */
        0,           /* don't change the port number */
        0,           /* don't change the UNIX socket */
        0);          /* client flag */

	//Wenn Fehleraufgetreten bitte Debug-Meldung ausgeben
	if( this->dbHandle == NULL )
	{
		this->debug("Konnte DatenbankVerbindung nicht herstellen");
	}
}
			
/*Funktion führt Query aus*/
void DATENBANK::query(char *psQuery)
{
	//Erfolg beim Query?
	if( 0 != mysql_query(this->dbHandle, psQuery) )
	{
		this->debug("Fehler in Query");
	}
	else
	{
		//Kein Fehler selektiere Query!
		this->dbResult = mysql_store_result(this->dbHandle);
		
		//ISt Fehler beim Ergebnis-Selektieren aufgetreten?
		if( this->dbResult == NULL )
		{
			this->debug("Problem mit Result");
		}	
		else	//Kein Fehler => Ergebnis kann ausgelesen werden
		{
		}
	} 
}

/*schliesst db-verbindung*/
void DATENBANK::db_close(void)
{
	mysql_close(this->dbHandle);
}

unsigned int DATENBANK::num_fields(void)
{
	return mysql_num_fields(this->dbResult);
}

void DATENBANK::free_result(void)
{
	mysql_free_result(this->dbResult);
}

/*Debug-_Funktion gibt bei gesetztem iShowErrors-Wert fehlermeldungen aus!*/
void DATENBANK::debug(char *psString)
{
	if( this->iShowErrors == 1 )
	{
		printf("###Fehler!###\n");
		printf("%s", psString);
		printf("\n\tmysql-meldet: %s (#%s)", mysql_error(this->dbHandle), mysql_errno(this->dbHandle) );
		printf("\n\n");
	}
}

int DATENBANK::insert_id(void)
{
	return mysql_insert_id(this->dbHandle);
}

int DATENBANK::num_rows(void)
{
	return mysql_num_rows(this->dbResult);
}

