#ifndef DB_
#define DB_
/*db.h
Headerdatei für die Datenbankklasse

History:
		07.09.2004	Markus von Rüden	created
*/

#include <mysql/mysql.h>
#include <stdio.h>
#include "define.h"

class DATENBANK
{
	//Objektvariablen
	private:
		int iShowErrors;
		MYSQL dbHandle;
	public:
		MYSQL_RES *dbResult;
		
	//MEthoden
	public:
		/*Standardkonstruktor*/
		DATENBANK(const char *psHost, int port, const char *psUser, const char *psPasswort, const char *psDatenbank);
		
		/*Dekonstruktor*/
		~DATENBANK();
		
		/*Funktion verbindet zum Server*/
		void db_connect(char *psHost, char *psUser, char *psPasswort, char *psDatenbank);
				
		/*Funktion führt Query aus*/
		void query(char *psQuery);
		
		/*schliesst db-verbindung*/
		void db_close(void);
		
		/*Debug-_Funktion gibt bei gesetztem iShowErrors-Wert fehlermeldungen aus!*/
		void debug(char *psString);
		
		unsigned int num_fields(void);
		
		void free_result(void);
		
		/*gibt mir die letzte id eines update bzw. insert befehls zurück*/
		int insert_id(void);
		
		//Gibt Anzahl von Zeilen zurück, welche vom Ergebnis befallen sind
		int num_rows(void);
};
#endif

