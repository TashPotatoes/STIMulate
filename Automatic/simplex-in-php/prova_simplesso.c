/* 
 * Copyright (C) 2003, 2004, 2005, 2006 Gionata Massi
 *
 * This file is part of Simplex-in-PHP.
 *
 *  Simplex-in-PHP is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  Simplex-in-PHP is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with Foobar; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 */

#include <stdio.h>
#include <errno.h>
#include <stdlib.h>
#include <unistd.h>
#include <sys/types.h>
#include <sys/socket.h>
#include <netinet/in.h>
#include <netdb.h>
#include <string.h>
#include <sys/stat.h>
#include <time.h>

#define PORT            80
#define MAXMSG          65536
#define POST_SIZE       4096
#define MSG_SIZE        4096

void init_sockaddr (struct sockaddr_in *name,  // inizializza il socket
                    const char *hostname,
                    uint16_t port);
void write_to_server (int filede,              // scrive il msg al socket
		      char *msg);
int read_from_socket (int filedes,             // copia da socket a file
		      char *outfile);
char *mappa (char token);                      // converte i simboli speciali
char *enc (char *string);                      // stringa codificata
int create_msg (char *msg,                     // crea il msg da file
		char *SERVERHOST,
		char *URL,
		char *file_to_parse);
void usage(char *prg_name);                    // regole d'utilizzo

/* variabile globale */
char *date;

int
main (int argc, char **argv)
{
    int sock;
    struct sockaddr_in servername;
    char msg[MSG_SIZE], *SERVERHOST=argv[1], *URL=argv[2], *FILENAME=argv[3];
	time_t local_time;
		
    if (argc != 4) {
        usage(argv[0]);
        exit(1);
    }
	
	local_time = time (NULL);
	date = ctime (&local_time);
	date[strlen (date)-1]='\0';
	
	/* Cambiamo i descrittori standard */
	if (! freopen ("log/request_log", "a", stdout)) {
        fprintf(stderr,"[%s] Non riesco a redirigere lo stdout.\n", date);
        /* exit (EXIT_FAILURE); */
    }
	if (! freopen ("log/error_log", "a", stderr)) {
        fprintf(stderr,"[%s] Non riesco a redirigere lo stderr.\n", date);
        /* exit (EXIT_FAILURE); */
    }
    /* Create the message */
    if (! (create_msg (msg, SERVERHOST, URL, FILENAME))) {
        fprintf(stderr,"[%s] Parse error", date);
        exit (EXIT_FAILURE);
    }
    fprintf (stdout, "####################### BEGIN [%s] #######################\n", date);
    fprintf (stdout, "==> Richiesta al web server: <==\n\n%s", msg);

    /* Create the socket. */
    sock = socket (PF_INET, SOCK_STREAM, 0);
    if (sock < 0)
    {
        perror ("socket");
        exit (EXIT_FAILURE);
    }

    /* Connect to the server. */
    init_sockaddr (&servername, SERVERHOST, PORT);
    if (0 > connect (sock,
                     (struct sockaddr *) &servername,
                     sizeof (servername)))
    {
        perror ("connect");
        exit (EXIT_FAILURE);
    }

    /* Send data to the server. */
    write_to_server (sock, msg);
    /* Receive data to the server. */
    read_from_socket (sock, strcat (FILENAME, ".html"));
    close (sock);
    fprintf (stdout, "######################## END [%s] ########################\n\n", date);
    exit (EXIT_SUCCESS);
}

void
init_sockaddr (struct sockaddr_in *name,
               const char *hostname,
               uint16_t port)
{
    struct hostent *hostinfo;

    name->sin_family = AF_INET;
    name->sin_port = htons (port);
    hostinfo = gethostbyname (hostname);
    if (hostinfo == NULL)
    {
        fprintf (stderr, "[%s] Unknown host %s.\n", date, hostname);
        exit (EXIT_FAILURE);
    }
    name->sin_addr = *(struct in_addr *) hostinfo->h_addr;
}

void
write_to_server (int filedes, char *msg)
{
    int nbytes;

    nbytes = write (filedes, msg, strlen (msg) + 1);
    if (nbytes < 0)
    {
        perror ("write");
        exit (EXIT_FAILURE);
    }
}

int
read_from_socket (int filedes, char *outfile)
{
    char buffer[MAXMSG], *html;
    int nbytes;
    FILE *output;
	
    nbytes = read (filedes, buffer, MAXMSG);
    if (nbytes < 0)
    {
        /* Read error. */
        perror ("read");
        exit (EXIT_FAILURE);
    }
    else if (nbytes == 0)
        /* End-of-file. */
        return -1;
    else {
		/* In fondo al buffer mettiamo uno '\0`, che probabilmente e' ridondante */
		buffer[nbytes] = '\0';
		/* Se il file di ouptput gia' esiste CANCELLALO */
		remove  (outfile);
		/* Data read. */
		output = /*popen ("less ", "w");*/ fopen (outfile ,"w");
		if (!output) {
			fprintf (stderr,
					 "\n\n[%s] Non riesco ad aprire '%s`.\n", date, outfile);
			return EXIT_FAILURE;
        }
		if (chmod (outfile, S_IRUSR | S_IRGRP | S_IROTH | S_IWUSR | S_IWGRP | S_IWOTH))
        {
            fprintf (stderr,
                     "\n\n[%s] Non riesco ad settare i permessi di '%s`.\n", date, outfile);
            return EXIT_FAILURE;
        }
		
		/* controlla che il codice di risposta sia "200 OK" */
		if (! strstr (buffer, "200 OK"))
			/* cerca "Content-Length: " e scan ("%d", &len); */
			/* html = strstr (buffer, "Content-Length: ");
			 * {
			 * if (html) {
			 * 	html += strlen ("Content-Length: ");
			 *	sscanf (html, "%d", &len);
			 *}else
			 *	len = -1;
		     *} else
		     *	len = -1;
             */
			fprintf (stderr, "\n\n[%s] Il web server non e' in grado di trovare o generare la pagina.\n", date);
		
        fprintf (stdout, "\n\n==> Header inviati dal web server: <==\n\n");
		html = buffer;
		/* fino a "\r\n\r\n" si tratta  di header */
		while (strncasecmp (html, "\r\n\r\n", 4) && html[0]) {
			putchar (html[0]);
			html++;
		}
		
		for (nbytes=0; nbytes<4 && html[0]; nbytes++, html++)
			/*putchar (html[0])*/;

        fprintf (stdout, "\n\n==> Scrittura del file %s. <==\n", outfile);
		/*        fprintf (output, "%s", html); */
        while (/*len-- &&*/ html[0]) {
			fputc (html[0], output);
			html++;
        }
		
        if (/*pclose (output)*/ fclose (output) != 0)
        {
            fprintf (stderr,
                     /*"[%s] Could not run more or other error.\n", date*/
					 "\n\nNon riesco a chiudere '%s`.\n", outfile);
        }
    }
    return EXIT_SUCCESS;
}

char *
mappa (char token)
{
	char *str;
	
    /* '<' == 3C;'=' == 3D; '>' == 3E; */
    if (token == '<')
        return "%3C";
    if (token == '=')
        return "%3D";
    if (token == '>')
        return "%3E";
    if (token == '/')
        return "%2F";

	str = calloc (2, 1);
	str[0] = token;
	
    return str;
}

char *
enc (char *string)
/* codifica string */
{
	char *tmp;
	int len, i;
	
	len = strlen (string);
	tmp = calloc (1, 5*len);
	if (tmp == NULL)
		exit (EXIT_FAILURE);
	for (i=0; i<len; i++)
		strcat (tmp, mappa (string[i]));
	
	return tmp;
}

/* Crea il messaggio in POST */
int
create_msg (char *msg, char *SERVERHOST, char *URL, char *file_to_parse)
{
    FILE *fp;
    struct stat f;
    char *string_to_parse, post[POST_SIZE], tmp[512], *token;
    const char delimiters[] = " =,\t\n";
    const char subdelimiters[] = " ,\t\n";
    int uscita, riga;

    /* Apre il file con il problema */
    if ((fp = fopen(file_to_parse, "r")) == NULL) {
        fprintf(stderr, "[%s] Impossibile aprire il file '%s`\n", date, file_to_parse);
        exit (EXIT_FAILURE);
    }
    if  (fstat (fileno(fp), &f) < 0) {
        perror("stat");
        exit(EXIT_FAILURE);
    }
    /* Copia il file da processare in una stringa */
    if (! (string_to_parse = (char *)malloc (f.st_size))) {
        perror("memory");
        exit(EXIT_FAILURE);
    }
    fread (string_to_parse, 1, f.st_size, fp);
    fclose (fp);
    post[0] = '\0';
    token = strtok (string_to_parse, delimiters);
    while ( token != NULL ) {
		if (! strncasecmp ("#", token, 1))  // commento fino al prossimo #
			token = strtok (NULL, "#");			
        if (! strcasecmp ("minmax", token)) { // minmax (intero)
            token = strtok (NULL, delimiters);
            if (token == NULL) {
                fprintf (stderr, "[%s] Parse error: expected 'min' or 'max' after 'minmax = '\n", date);
                exit(EXIT_FAILURE);
            }
            sprintf (tmp, "minmax=%s&", token);
            strcat ( post, tmp);
        } else if (! strcasecmp ("numConstraints", token)) { // numConstraints (intero)
            token = strtok (NULL, delimiters);
            if (token == NULL) {
                fprintf (stderr, "[%s] Parse error: expected #num# after 'numConstraints = '\n", date);
                exit(EXIT_FAILURE);
            }
            sprintf (tmp, "numConstraints=%d&", atoi(token));
            strcat (post, tmp);
        } else if (! strcasecmp ("numVariables", token)) { // numVariables (intero)
            token = strtok (NULL, delimiters);
            if (token == NULL) {
                fprintf (stderr, "[%s] Parse error: expected #num# after 'numVariables = '\n", date);
                exit(EXIT_FAILURE);
            }
            sprintf (tmp, "numVariables=%d&", atoi(token));
            strcat (post, tmp);
        } else if (! strcasecmp ("intera", token)) { // intera (stringa)
            token = strtok (NULL, delimiters);
            if (! strcasecmp ("true", token))
                strcat ( post, "intera=true&");
			else
				strcat ( post, "intera=false&");
        } else if (! strcasecmp ("d", token)) { // d (frazione)
            token = strtok (NULL, delimiters);
            if (token == NULL) {
                fprintf (stderr, "[%s] Parse error: expected #num# after 'd = '\n", date);
                exit(EXIT_FAILURE);
            }
            sprintf (tmp, "d=%s&", enc(token));
            strcat (post, tmp);
        } else if (! strcasecmp ("c", token)) { // c (frazione)
            token = strtok (NULL, delimiters);
            // prima un "["
            if ( strcasecmp (token, "[")) {
                fprintf (stderr, "[%s] Parse error: expected '[ ' after 'c = '\n", date);
                exit(EXIT_FAILURE);
            }
            token = strtok (NULL, delimiters);
            uscita = 1;
            while ( token != NULL && uscita != 0 ) {
                if (token == NULL) {
                    fprintf (stderr, "[%s] Parse error: expected #num# after '[ '\n", date);
                    exit(EXIT_FAILURE);
                } else if (! strcasecmp (token, "]"))
                    uscita = 0;
                else {
                    sprintf (tmp, "c%%5B%d%%5D=%s&", uscita, enc(token));
                    strcat (post, tmp);
                    token = strtok (NULL, delimiters);
                    uscita++;
                }
            }
        } else if (! strcasecmp ("b", token)) { // b (frazione)
            token = strtok (NULL, delimiters);
            // prima un "["
            if ( strcasecmp (token, "[")) {
                fprintf (stderr, "[%s] Parse error: expected '[ ' after 'b = '\n", date);
                exit(EXIT_FAILURE);
            }
            token = strtok (NULL, delimiters);
            uscita = 1;
            while ( token != NULL && uscita != 0 ) {
                if (token == NULL) {
                    fprintf (stderr, "[%s] Parse error: expected #num# after '[ '\n", date);
                    exit(EXIT_FAILURE);
                } else if (! strcasecmp (token, "]"))
                    uscita = 0;
                else {
                    sprintf (tmp, "b%%5B%d%%5D=%s&", uscita, enc(token));
                    strcat (post, tmp);
                    token = strtok (NULL, delimiters);
                    uscita++;
                }
            }
        } else if (! strcasecmp ("lge", token)) { // lge ("=, =<, >=")
            token = strtok (NULL, delimiters);
            // prima un "["
            if ( strcasecmp (token, "[")) {
                fprintf (stderr, "[%s] Parse error: expected '[ ' after 'lge = '\n", date);
                exit(EXIT_FAILURE);
            }
            token = strtok (NULL, subdelimiters);
            uscita = 1;
            while ( token != NULL && uscita != 0 ) {
                if (token == NULL) {
                    fprintf (stderr, "[%s] Parse error: expected #num# after '[ '\n", date);
                    exit(EXIT_FAILURE);
                } else if (! strcasecmp (token, "]"))
                    uscita = 0;
                else {
                    sprintf (tmp, "lge%%5B%d%%5D=%s&", uscita, enc(token));
                    strcat (post, tmp);
                    token = strtok (NULL, subdelimiters);
                    uscita++;
                }
            }
        } else if (! strcasecmp ("a", token)) { // a (frazione)
            token = strtok (NULL, delimiters);
            // prima un "["
            if ( strcasecmp (token, "[")) {
                fprintf (stderr, "[%s] Parse error: expected '[ ' after 'a = '\n", date);
                exit(EXIT_FAILURE);
            }
            token = strtok (NULL, delimiters);
            uscita = 1;
            riga= 1;
            while ( token != NULL && uscita != 0 ) {
                if (token == NULL) {
                    fprintf (stderr, "[%s] Parse error: expected #num# or ' ; ' after '[ '\n", date);
                    exit(EXIT_FAILURE);
                } else if (! strcasecmp (token, "]"))
                    uscita = 0;
                else if (! strcasecmp (token, ";")) {
                    riga++;
                    uscita = 1;
                    token = strtok (NULL, delimiters);
                } else {
                    sprintf (tmp, "a%%5B%d%%5D%%5B%d%%5D=%s&", riga, uscita, enc(token));
                    strcat (post, tmp);
                    token = strtok (NULL, delimiters);
                    uscita++;
                }
            }
        }

        /* avanza al prossimo token */
        token = strtok (NULL, delimiters);
    }

    /* rimuovi l'ultimo '&' */
    strcat (post, "name=");
    strcat (post, file_to_parse);
    
    /* XDEBUG_PROFILE */
    strcat (post, "&XDEBUG_PROFILE");

    /* iniziamo il messaggio */
    strcpy (msg, "POST ");
    strcat (msg, URL);
    strcat (msg, " HTTP/1.0\r\n");
    strcat (msg, "Connection: close\r\n");
    strcat (msg, "User-Agent: Nemo (by gim)\r\n");
//    strcat (msg, "Referer: \r\n");
    strcat (msg, "Pragma: no-cache\r\n");
    strcat (msg, "Cache-control: no-cache\r\n");
    strcat (msg, "Accept: text/*, image/jpeg, image/png, image/*, */*\r\n");
//    strcat (msg, "Accept-Encoding: x-gzip, x-deflate, gzip, deflate, identity\r\n");
    strcat (msg, "Accept-Charset: iso-8859-1, iso-8859-15, utf-8;q=0.5, *;q=0.5\r\n");
    strcat (msg, "Accept-Language: it, en\r\n");
    strcat (msg, "Host: ");
    strcat (msg, SERVERHOST);
    strcat (msg, "\r\n");
    strcat (msg, "Content-Type: application/x-www-form-urlencoded\r\n");
    sprintf (tmp, "Content-Length: %d\r\n\r\n", strlen(post));
    strcat (msg, tmp);
    strcat (msg, post);

    free (string_to_parse);
    //free (post);

    return 1;
}

/* Riepilogo sull'uso del programma */
void
usage(char *prg_name)
{
    fprintf(stderr, "Uso: %s [server_host] [url] [input_file]\n", prg_name);
}

/* Sintassi per il parser:

COMMENTI:
         definiti fra due cancelletti
        
         esempio:
		   # questo e' un commento valido #
           #anche
            questo#
           # questo non funziona perche' non ha termine
ASSEGNAMENTI:
         keyword = value_in_keyword_range
KEYWORD e relativi tipi:

         minmax          ==>      stringa con valore in {"min", "max}
        
         numConstrints   ==>      intero

         numVariables    ==>      intero

         intera          ==>      stringa con valore in {"true", "false"}

         a

         b

		 c

		 d

		 lge


Il file per generare una richiesta valida deve contenere TUTTE le stringhe keyword.

Uno strano esempio di file corrispondente alle regole grammaticali e'
 il seguente:

 #######################################################
##                                                     #
##   Test sulla sintassi accettata da prova_simplesso  #
##                                                     #
#######################################################
#

# commento semplice su una riga #

#
commento che occupa
piu' di una riga
#

### ancora un commento valido #

# le stringhe a sinistra del simbolo '=' sono CASE INSENSITIVE #
MinMax = max
Numvariables = 2
numconstraints = 3
INTERA = true

# 
i ritorni a capo '\n' contenuti fra '[' e `]`vengono ignorati 
e il punto e virgola ';' rimane necessario per separare le rghe
della matrice 'a'
#

### i commenti non sono validi all'interno delle quadre #

C = [ 2 1 ]
d = 0 
A = [ 1 -2 ;
     -3  1 ;
       1 1 ]
b = [ 2 3 4 ]
lge = [ =<
        =<
        =< ]


*/
