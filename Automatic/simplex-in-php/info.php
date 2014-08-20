<?php
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
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
  <title>Documentazione tesina Ricerca Operativa - Gionata Massi</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta name="author" content="Gionata Massi" >
  <meta name="keywords" content=
  "metodo del simplesso, programmazione lineare, forma standard, forma canonica, tableau, metodo delle due fasi, programmazione lineare intera, metodo dei piani di taglio, variabili slack, variabili surplus, variabili ausiliarie, variabili artificiali, forma di inammissibilit&agrave;, regione di ammissibilit&agrave;, Gionata Massi">
  <link rel="StyleSheet" href="info.css" type="text/css" media="screen">
  <script language="javascript" type="text/javascript">
    function showFile(dataForm) {
<?php
    $path = dirname($_SERVER["SCRIPT_FILENAME"]);
    $path .= "/esempi";
    $d = dir("$path");
    $form = array();
    $i = 0;
    while($entry=$d->read()) {
      if ( ! strcmp (filetype ("$path/$entry"), "file") and ! preg_match ("/\.html$|\.sh|Makefile|~$|\.txt$|\.css|prova_simpless.*|\.dll|images/i", $entry, $file)) {
        // lo mettiamo nella form
        $form[] = $entry;
        $i++;
      }
    }
    $d->close();
    sort ($form);
    $elem = count($form);
    echo "       txt = new Array ($elem);\n";
    for($i=0;$i<count($form);/*$i++*/) {
      $htmlFile = implode ('', file ("esempi/$form[$i]"));
      $htmlFile = addcslashes ($htmlFile, "\0..\31\"");
      echo "       txt[$i]=\"$htmlFile\";\n";
      if (++$i < count($form))
        echo "\n";
    }

?>
       dataForm.txtFile.value = txt[dataForm.page.selectedIndex];
    }
  </script>
</head>

<body>
  <h1 align="center">Metodo del Simplesso in PHP</h1>

  <h2>Prefazione</h2>

  <p>Questa tesina nasce dalla volont&agrave; di capire pi&ugrave; approfonditamente
  il modo in cui opera il <span class="keyword">metodo del
  simplesso</span> e creare un programma che risolva, in modo del
  tutto simile al modo di procedere degli studenti, i problemi di
  solito proposti negli scritti di Ricerca Operativa.</p>

  <p>L'implementazione per calcolatore di un algoritmo comporta o,
  meglio, dovrebbe comportare l'analisi di tutte le possibili
  evenienze che possono accadere nel processare i dati in ingresso e
  costringe il programmatore ad acquisire delle conoscenze in merito
  non superficiali. Particolare attenzione avrebbe dovuto essere
  riservata, in questo caso, ai possibili metodi di ricerca nello
  spazio delle soluzioni al fine di riconoscere e trovare nel minor
  tempo possibile la soluzione di un problema di minimo. Lo scopo con
  cui &egrave; nato il progetto per&ograve; si limita a fornire una soluzione basata
  su passi iterati di pivoting e perci&ograve; non sono state implementate
  altre funzioni di ricerca euristica o di semplice verifica di non
  ricorrenza. In questo modo l'algoritmo non d&agrave; garanzia di giungere
  alla soluzione.</p>

  <p>L'algoritmo per girare deve rappresentare i dati in notazioni
  (forme) interne convenienti e considerare nel far ci&ograve; tutti i
  possibili casi. Sono state fatte delle scelte volte ad eliminare
  delle variabili artificiali dove non strettamente necessarie, anche
  se ci&ograve; comporta una diversa rappresentazione del problema in
  esame.</p>

  <p>Il programma &egrave; stato implementato allo scopo di fornire in output
  la risoluzione passo passo di un problema di <span class=
  "keyword">programmazione lineare</span>, in molto del tutto simile
  all'analisi eseguita manualmente. Si &egrave; scelto, anche per facilitare
  le operazioni di debugging e di riscontro della correttezza
  dell'algoritmo, di rappresentare tutti i passaggi: visualizzazione
  del <font color= "maroon">problema immesso</font>, del problema
  portato prima in <span class= "keyword">forma standard</span> e
  successivamente in <span class= "keyword">forma canonica</span>,
  rappresentazione del <span class= "keyword">tableau</span> anche nel
  caso eventuale di utilizzo del <span class="keyword">metodo delle
  due fasi</span> con indicazione ad ogni passo dell'<span
  class="keyword">insieme degli indici di base</span> e del valore
  della <span class="keyword">soluzione di base</span> associata ad
  ogni tabella. Si &egrave; scelto di risolvere il problema di <span class=
  "keyword">programmazione lineare intera</span> mediante il <span
  class= "keyword">metodo dei piani di taglio</span>. Inoltre per
  evitare i problemi di convergenza nelle operazioni di pivoting,
  quindi fornire una soluzione corretta e conforme all'analisi
  eseguita manualmente, anzich&egrave; usare l'aritmetica in virgola mobile
  del calcolatore si &egrave; fatto uso di <em>operazioni su numeri
  razionali</em> implementando un'apposita classe. In aggiunta, sempre
  per rimanere aderenti alla soluzione su carta di semplici problemi,
  si rappresenta l'<span class="keyword">analisi grafica</span> dei
  problemi che coinvolgono due sole variabili di decisione.</p>

  <p>Anche se non detto esplicitamente sopra &egrave; evidente che la scelta
  di realizzare questo tipo di tesina &egrave; dettata dalla voglia di
  <em>sviluppare un programma</em> che giri in rete<!-- e farlo sotto
  <span class="free">Linux</span>-->.</p>

  <h2>Quali problemi risolve il programma</h2>

  Vengono risolti i problemi di programmazione lineare, eventualmente
  a variabili intere, espressi nella forma <font size="+2">A</font>
  <b>x</b> &theta; <b>b</b>, dove <font size="+2">A</font> &egrave; una
  matrice <i>m</i> x <i>n</i>, <b>b</b> un vettore a <i>m</i> componenti e &theta; un
  operatore relazionale scelto fra {"&gt;=", "&lt;=" o "="}. Non &egrave; garantito
  che venga trovata la soluzione perch&egrave; non sono state implementate le
  funzioni necessarie a riconoscere se il vertice che viene esplorato
  sia gi&agrave; stato visitato. Pertanto potrebbero verificarsi dei cicli. In
  tal caso dopo un certo numero di iterazioni l'esecuzione termina
  avvisando della situazione occorsa.

  <h2>Esempi</h2>

  <p>Di seguito viene presentata la soluzione di un problema con due
  variabili di decisione.</p>

  <?php

    /* Sfrutta la caratteristica di avere delimitato il corpo della
    risoluzione fra due <hr> */

    $esempio = implode ('', file ('esempi/es_03i.html'));
    $esempio = strstr ($esempio, "  <!-- TAGLIA 1 -->");
    $lunghezza_corpo = strpos ($esempio, "  <!-- TAGLIA 2 -->");
    $esempio = substr ($esempio, 0, $lunghezza_corpo);
    echo $esempio;
  ?>

  <p>Per vedere altri esempi senza immettere manualmente i dati del
  problema &egrave; sufficiente selezionare dall'elenco seguente il nome con
  cui viene rappresentato un problema, poi premendo il pulsante
  "Procedi" verr&agrave; visualizzata la soluzione.</p>

  <form method="get" action="show_esempi.php" name="formEsempi" target=
  "esempi">
    <select name="page" onchange="showFile(this.form)">
<?php

    for($i=0; $i<count($form);/*$i++*/) {
      echo "      <option value=\"esempi/$form[$i]\">\n";
      echo "        $form[$i]\n";
      echo "      </option>\n";
      if (++$i < count($form))
        echo "\n";
    }

?>
    </select><br>

    <p>Dal testo seguente vengono estratti i dati processati dal programma.
    </p>

    <textarea name="txtFile" rows="15" cols="80" disabled>Premi su "Mostra" per visualizzare il file.</textarea><br>

    <table border="0" summary="invio form">
      <thead>
        <tr>
          <th><input type="button" value=" Mostra "
          onClick="showFile(this.form)"></th>
          <th><input type="submit" value=" Procedi "></th>
        </tr>
      </thead>
    </table>


  </form>

  <h1><a name="scelte">Scelte implementative</a></h1>

  <h2>Come funziona</h2>

  <p>Primo compito del programma &egrave; acquisire i dati del problema. Allo scopo
  vengono create due pagine: <a href=
  "show_src.php?script=immissione_dati_0.php" target=
  "sorgenti">immissione_dati_0.php</a> e <a href=
  "show_src.php?script=immissione_dati_1.php" target=
  "sorgenti">immissione_dati_1.php</a>. La prima richiede informazioni sul tipo
  di problema (massimo o minimo,continuo o intero) e sulle dimensioni; il
  secondo acquisisce i coefficienti. In entrambe le pagine si &egrave; fatto uso di
  funzioni <span class="free">JavaScript</span> per verificare il tipo di dati
  immessi e le dimensioni del problema. Il controllo viene poi passato a
  <a href="show_src.php?script=simplesso.php" target=
  "sorgenti">simplesso.php</a> che si occupa, passo per passo, della
  visualizzazione dei risultati delegando lo svolgimento di molte delle
  operazioni sul tableau al codice in <a href="show_src.php?script=matrice.php"
  target="sorgenti">matrice.php</a>. Si &egrave; optato di lavorare con numeri
  razionali piuttosto che con numeri in virgola mobile, perch&egrave; spesso i
  problemi sono posti in tali termini ed &egrave; pi&ugrave; agevole verificare che la
  soluzione sia a numeri interi evitando gli errori dovuti agli arrotondamenti.
  Allo scopo &egrave; stata implementata una classe: <a href=
  "show_src.php?script=razionale.php" target="sorgenti">razionale.php</a>.
  Altre routine ausiliarie per la visualizzazione e verifica dei dati o delle
  propriet&agrave; del problema sono in <a href="show_src.php?script=util.php" target=
  "sorgenti">util.php</a>. <a href="show_src.php?script=template.php" target=
  "sorgenti">template,php</a> e <a href="show_src.php?script=testata.php"
  target="sorgenti">testata.php</a> contengono informazioni relative alla
  formattazione e il primo dei due file fornisce i metodi per una
  visualizzazione uniforme delle pagine. Nel caso di problemi immessi con
  due variabili di decisione viene presentata la soluzione grafica grazie alle
  funzioni definite in <a href="show_src.php?script=immagini.php" 
  target="sorgenti">immagini.php</a> che si appoggiano alla libreria grafica
  <span class="free">GD</span>. Per la visualizzazione dei sorgenti si &egrave; fatto
  uso di un semplice script: <a href="show_src.php?script=show_src.php"
  target="sorgenti">show_src.php</a>, mentre un programma leggermente pi&ugrave;
  complesso si &egrave; reso necessario per la gestione degli esempi: <a href=
  "show_src.php?script=show_esempi.php" target="sorgenti">show_esempi.php</a>.
  Quest'ultimo si appoggia ad un <a href=
  "show_src.php?script=prova_simplesso.c" target="sorgenti">programma</a>
  scritto in <span class="free">C</span> che da una definizione del problema di
  programmazione lineare espressa con una sintassi particolare genera la
  richiesta di esecuzione dell'algoritmo del simplesso al web server.</p>

  <h3><a name="immissione_dati_0" href=
  "show_src.php?script=immissione_dati_0.php" target=
  "sorgenti">immissione_dati_0.php</a></h3>

  <p>L'unica operazione di cui &egrave; responsabile il file &egrave; l'immissione dei dati
  del primo modulo. I dati numerici immessi devono essere di tipo intero,
  frazionario o decimale.<br>
  L'output dovrebbe apparire simile al seguente:</p>

  <table border="1" bgcolor="silver" summary=
  "La form di immissione_dati_0.php">
    <tbody>
      <tr>
        <td>
          <form name="form0" action="info.html">
            <p>Il problema &egrave; di <input type="radio" name="minmax" value=
            "min"><strong>minimo</strong> <input type="radio" name="minmax"
            value="max" checked="checked"><strong>massimo</strong></p>

            <p>Numero delle variabili di decisione: <input type="text" name=
            "numVariables" size="3" maxlength="3" value="2"></p>

            <p>Numero dei vincoli: <input type="text" name="numConstraints"
            size="3" maxlength="3" value="2"></p>

            <p>Tutte le variabili sono intese non negative. Spunta la casella
            se devono essere <input type="checkbox" name="intera" value="true"
            checked="checked">INTERE.</p>

            <table border="0" summary="invio form">
              <thead>
                <tr>
                  <th><input type="button" value=" Procedi "></th>

                  <th><input type="reset" value=" Cancella "></th>
                </tr>
              </thead>
            </table>
          </form>
        </td>
      </tr>
    </tbody>
  </table>

  <h3><a name="immissione_dati_1" href=
  "show_src.php?script=immissione_dati_1.php" target=
  "sorgenti">immissione_dati_1.php</a></h3>

  <p>Crea in modo dinamico il modulo per l'immissione del testo del problema.
  Accetta interi, numeri decimali, frazioni e la stringa vuota.<br>
  L'output nel caso di un PLI di massimo con 2 variabili di decisione e due
  vincoli dovrebbe essere il seguente:</p>

  <table border="1" bgcolor="silver" summary=
  "la form di immissione_dati_1.php">
    <tbody>
      <tr>
        <td>
          <form name="form1" action="info.html">
            <strong>max z = <input type="text" name="c[1]" size="5" maxlength=
            "5" value="1"> x<sub>1</sub> + <input type="text" name="c[2]" size=
            "5" maxlength="5" value="1"> x<sub>2</sub> + <input type="text"
            name="d" size="5" maxlength="5"><br>
            <br>
            Soggetto a<br>
            <br>
            1) <input type="text" name="a[1][1]" size="5" maxlength="5" value=
            "1"> x<sub>1</sub>+ <input type="text" name="a[1][2]" size="5"
            maxlength="5" value=""> x<sub>2</sub> <select name="lge[1]">
              <option>
                =&lt;
              </option>

              <option>
                &gt;=
              </option>

              <option>
                =
              </option>
            </select> <input type="text" name="b[1]" size="5" maxlength="5"
            value="3"><br>
            2) <input type="text" name="a[2][1]" size="5" maxlength="5" value=
            "1"> x<sub>1</sub>+ <input type="text" name="a[2][2]" size="5"
            maxlength="5" value="1"> x<sub>2</sub> <select name="lge[2]">
              <option>
                =&lt;
              </option>

              <option>
                &gt;=
              </option>

              <option>
                =
              </option>
            </select> <input type="text" name="b[2]" size="5" maxlength="5"
            value="5"><br>
            &nbsp; &nbsp; x<sub>i</sub> &gt;= 0 e INTERI &nbsp; per i
            =1,...,2</strong><br>

            <table border="0" summary="invio/cancellazione form">
              <thead>
                <tr>
                  <th><input type="button" value=" Procedi "></th>

                  <th><input type="reset" value=" Cancella "></th>
                </tr>
              </thead>
            </table>
          </form>
        </td>
      </tr>
    </tbody>
  </table>

  <h3><a name="simplesso" href="show_src.php?script=simplesso.php" target=
  "sorgenti">simplesso.php</a></h3>

  <p>Questo, insieme a <a href="show_src.php?script=matrice.php" target=
  "sorgenti">matrice.php</a> e <a href="show_src.php?script=razionale.php"
  target="sorgenti">razionale.php</a> &egrave; fra gli script pi&ugrave; importanti. Compie
  in sequenza tutta una serie di operazioni:</p>

  <ul>
    <li>visualizza il problema immesso;</li>

    <li>porta il problema in forma standard;</li>

    <li>mostra la forma standard;</li>

    <li>aggiunge eventuali variabili artificiali e calcola la forma di
    inammissibilit&agrave;;</li>

    <li>mostra il problema in forma canonica;</li>

    <li>se necessario esegue la prima fase del metodo delle due fasi;</li>

    <li>esegue il metodo del simplesso;</li>

    <li>se il problema &egrave; a variabili intere applica il metodo dei piani di
    taglio;</li>

    <li>visualizza il risultato di ogni operazione effettuata sul tableau e le
    condizioni di uscita.</li>
  </ul><br>
  <br>

  <p>Per visualizzare il problema immesso (operazione necessaria per avere
  riscontro sulla correttezza dei dati introdotti) &egrave; stata implementata una
  funzione apposita in <a href="show_src.php?script=util.php" target=
  "sorgenti">util.php</a>.</p>

  <p>Per portare il problema in forma standard e anticipare alcune operazioni
  atte a ridurlo in forma canonica occorrono alcuni passi:</p>

  <ul>
    <li>se il problema &egrave; di massimo occorre trasformarlo nel problema di minimo
    equivalente cambiando di segno i coefficienti di costo;</li>

    <li>si aggiungono le variabili ausiliarie <em>slack</em> o <em>surplus</em>
    e vengono aggiunte le variabili slack fra le variabili di base. Questo per
    risparmiare operazioni inutili per valutare l'aggiunta di eventuali
    variabili artificiali.</li>
  </ul><br>
  <br>

  <p>A questo punto viene rappresentato il problema in <em>forma standard.</em>
  </p>

  <p>Per portare il problema in forma canonica per prima cosa si richiede di
  avere tutti i coeficienti delle risorse positivi:</p>
  <ul>
    <li>se un vincolo &egrave; espresso con un una risorsa negativa viene moltiplicata
    la riga del vincolo per -1 e viene invertito il verso della disequazione;
    </li>
  </ul><br>
  <br>

  <p>Successivamente vengono impiegate due strategie:
  o l'aggiunta di una variabile artificiale o, se possibile, la divisione della
  riga per un opportuno coefficiente, in modo da far entrare in base una
  variabile di decisione anzich&egrave; introdurre un'ulteriore variabile. Nel secondo
  caso si devono verificare tutte le seguenti condizioni:</p>

  <ul>
    <li>la variabile <em>x<sub>j</sub></em> &egrave; presente nel vincolo con
    coefficiente <em>a<sub>ij</sub></em> positivo</li>

    <li><em>x<sub>j</sub></em> non compare nella funzione obiettivo</li>

    <li><em>x<sub>j</sub></em> non compare negli altri vincoli</li>
  </ul>In tal caso dividendo la riga <em>i</em> per <em>a<sub>ij</sub></em>
  <em>x<sub>j</sub></em> entra in base.<br>
  <br>

  <p>Nel caso in cui siano state aggiunte variabili artificiali o sia stata
  divisa qualche riga il problema differisce da quello in forma standard e
  pertanto ne viene rappresentata la <em>forma canonica</em>.</p>

  <p>Se sono presenti <em>variabili artificiali</em> si esegue la <em>prima
  fase</em> del <em>metodo delle due fasi</em>. L'algoritmo consiste in un
  ciclo, eseguito al massimo 10 volte per ragioni di tempo e di eventuale non
  convergenza dell'algoritmo (che si sarebbe potuta verificare o in modo
  semplice, ma non certo controllando se il valore assunto dalla funzione
  obiettivo nei vari passi si mantiene costante o, mediante un algoritmo pi&ugrave;
  complesso controllando che il tableau sia diverso da uno di quelli
  precedenti).</p>

  <p>In questa fase per prima cosa viene aggiunto in testa al <em>tableau</em>
  la riga della <em>forma di inammissibilit&agrave;</em> da minimizzare. Il loop
  consiste nell'eseguire un passo di pivot e controllare lo stato del
  <em>tableau</em>. Le condizioni considerate sono:</p>

  <ol type="1">
    <li>
      <dl>
        <dt><em>&rho;</em> &egrave; all'ottimo.</dt>

        <dd>
          Vengono controllati i casi:

          <ol type="a">
            <li>
              <dl>
                <dt><em>&rho;</em> = 0;</dt>

                <dd>
                  tutte le <em>variabili artificiali</em> sono uscite dalla
                  base?

                  <ol type="i">
                    <li>
                      <dl>
                        <dt>Si:</dt>

                        <dd>si pu&ograve; procedere alla <em>seconda fase</em>.</dd>
                      </dl>
                    </li>

                    <li>
                      <dl>
                        <dt>No, ma &egrave; possibile effettuare un'operazione di
                        pivot per estrarle dalla base:</dt>

                        <dd>ulteriori pivot per estrarle dalla base.</dd>
                      </dl>
                    </li>

                    <li>
                      <dl>
                        <dt>No, e non &egrave; possibile estrarle dalla base:</dt>

                        <dd>un'equazione del P.L. in forma standard &egrave;
                        combinazione lineare delle altre;</dd>
                      </dl>
                    </li>
                  </ol>
                </dd>
              </dl>
            </li>

            <li>
              <dl>
                <dt><em>&rho;</em> &lt; 0;</dt>

                <dd><strong>impossibile</strong>: <em>&rho;</em> &egrave; somma di
                variabili non negative;</dd>
              </dl>
            </li>

            <li>
              <dl>
                <dt><em>&rho;</em> &gt; 0;</dt>

                <dd>la <em>regione di ammissibilit&agrave;</em> di <em>z</em> &egrave;
                vuota;</dd>
              </dl>
            </li>
          </ol>
        </dd>
      </dl>
    </li>

    <li>
      <dl>
        <dt><em>&rho;</em> &egrave; illimitata inferiormente;</dt>

        <dd><strong>impossibile</strong>: <em>&rho;</em> &egrave; per costruzione non
        negativa;</dd>
      </dl>
    </li>

    <li>
      <dl>
        <dt>la <em>regione di ammissibilit&agrave;</em> &egrave; vuota;</dt>

        <dd><strong>impossibile</strong>: esiste sempre almeno una soluzione di
        base.</dd>
      </dl>
    </li>
  </ol><br>
  <br>

  <p>Se la regione di ammissibilit&agrave; del P.L. non &egrave; vuota si deve ripristinare
  il tableau rimuovendo la forma di inammissibilit&egrave; e le variabili artificiali.
  Inoltre va corretto l'assegnamento (variabile di base =&gt; riga
  risorsa).</p>

  <p>A questo punto si esegue il <em>metodo del simplesso</em>: un ciclo che
  itera secondo lo schema seguente:</p>

  <ol type="1">
    <li>
      <!-- # 1 # -->

      <dl>
        <dt><em>z</em> &egrave; all'ottimo.</dt>

        <dd>
          Vengono controllati i casi:

          <ol type="a">
            <li>
              <!-- # 1_a # -->

              <dl>
                <dt>Soluzione ottima unica;</dt>

                <dd>l'algoritmo termina;</dd>
              </dl>
            </li>

            <li>
              <!-- # 1_b # -->

              <dl>
                <dt>Pi&ugrave; soluzioni ottime;</dt>

                <dd>l'algoritmo termina senza cercare le altre soluzioni;</dd>
              </dl>
            </li>
          </ol>
        </dd>
      </dl>
    </li>

    <li>
      <!-- # 2 # -->

      <dl>
        <dt><em>z</em> &egrave; illimitata;</dt>

        <dd>l'algoritmo si arresta;</dd>
      </dl>
    </li>

    <li>
      <!-- # 3 # -->

      <dl>
        <dt>la soluzione &egrave; migliorabile;</dt>

        <dd>si effetta un'altra operazione di pivoting.</dd>
      </dl>
    </li>
  </ol><br>
  <br>

  <p>La scelta dell'elemento di pivot per il metodo del simplesso:</p>

  <ol type="1">
    <li>
      <!-- # 1 # -->

      <dl>
        <dt>Cerca il coefficiente di costo <em>c<sub>j</sub></em> pi&ugrave;
        piccolo.</dt>

        <dd>
          Vengono controllati i casi:

          <ol type="a">
            <li>
              <!-- # 1_a # -->

              <dl>
                <dt><em>c<sub>j</sub></em> &gt;= 0</dt>

                <dd>nessun pivot in quanto la soluzione &egrave; gi&agrave; ottima;</dd>
              </dl>
            </li>

            <li>
              <!-- # 1_b # -->

              <dl>
                <dt><em>c<sub>j</sub></em> &lt; 0;</dt>

                <dd>seleziona la colonna di indice <em>j</em>;</dd>
              </dl>
            </li>
          </ol>
        </dd>
      </dl>
    </li>

    <li>
      <!-- # 2 # -->

      <dl>
        <dt>se per ogni <em>i</em> <em>a<sub>ij</sub></em> =&lt; 0;</dt>

        <dd>allora la soluzione &egrave; illimitata e non serve il pivot;</dd>
      </dl>
    </li>

    <li>
      <!-- # 3 # -->

      <dl>
        <dt>valuta per ogni <em>i</em> tale che <em>a<sub>ij</sub></em> &gt; 0
        il rapporto <em>b<sub>i</sub></em> <big>/</big>
        <em>a<sub>ij</sub></em></dt>

        <dd>scegli <em>i</em> per cui il rapporto &egrave; minimo.</dd>
      </dl>
    </li>
  </ol><br>
  <br>

  <p>Quando il problema &egrave; a variabili intere viene ad ogni passo valutato se la
  soluzione e le variabili sono a valori interi. Se non &egrave; cos&igrave; viene generato
  un vincolo da aggiungere al problema iniziale secondo il metodo dei piani di
  taglio. A differenza della risoluzione precedente viene impiegato il metodo
  del simplesso duale.</p>

  <p>La scelta dell'elemento di pivot per il metodo del simplesso duale:</p>

  <ol type="1">
    <li>
      <!-- # 1 # -->

      <dl>
        <dt>Cerca l'elemento <em>b<sub>i</sub></em> pi&ugrave; piccolo del vettore
        delle risorse.</dt>

        <dd>
          Vengono controllati i casi:

          <ol type="a">
            <li>
              <!-- # 1_a # -->

              <dl>
                <dt><em>b<sub>i</sub></em> &gt;= 0</dt>

                <dd>nessun pivot in quanto la soluzione &egrave; gi&agrave; ottima;</dd>
              </dl>
            </li>

            <li>
              <!-- # 1_b # -->

              <dl>
                <dt><em>b<sub>i</sub></em> &lt; 0;</dt>

                <dd>seleziona la riga di indice <em>i</em>;</dd>
              </dl>
            </li>
          </ol>
        </dd>
      </dl>
    </li>

    <li>
      <!-- # 2 # -->

      <dl>
        <dt>se per ogni <em>j</em> <em>a<sub>ij</sub></em> &gt; 0;</dt>

        <dd>allora la soluzione &egrave; inammisibile e non serve il pivot;</dd>
      </dl>
    </li>

    <li>
      <!-- # 3 # -->

      <dl>
        <dt>valuta per ogni <em>j</em> tale che <em>a<sub>ij</sub></em> &lt; 0
        il rapporto <em>c<sub>i</sub></em> <big>/</big>
        <em>(-a<sub>ij</sub>)</em></dt>

        <dd>scegli <em>j</em> per cui il rapporto &egrave; minimo.</dd>
      </dl>
    </li>
  </ol><br>
  <br>

  <h2>Efficienza</h2>

  <p>Nel progettare l'applicazione non si &egrave; curata in alcun modo l'efficienza,
  tranne in rari casi,
  a vantaggio di una chiara esposizione dei passaggi richiesti dal metodo del
  simplesso. Si eseguono pi&ugrave; operazioni del necessario per rappresentare ogni
  singolo passo, senza utilizzare il <em>simplesso revisionato</em>; la
  costruzione del tableau avrebbe potuto essere fatta in un solo passaggio;
  vengono allocate pi&ugrave; variabili di quante strettamente necessarie, ad esempio
  per tenere traccia degli indici di base.<br>
  L'indicazione delle strutture dati e delle funzioni da ottimizzare &egrave;
  certamente non esaustiva.</p>

  <h1><a name="varie">Varie</a></h1>

  <h2>Linguaggi</h2>
  <dl>
    <dt><span class="free">PHP</span>:</dt>

    <dd><abbr>PHP</abbr> &egrave; l'acronicmo ricorsivo di <q>PHP: Hypertext
    Preprocessor</q>. Si tratta di un linguaggio interpretato
    comunemente impiegato per lo sviluppo di presentazioni Web. La
    sintassi deriva dal C, dal Java, e dal Perl. Due caratteristiche
    importanti sono la possibilit&agrave; di programare mediante il paradigma
    orientato agli oggetti e la semplicit&agrave; d'uso. Scopo principale di
    questo linguaggio &egrave; scrivere pagine che vengano generate
    dinamicamente dal web server. Le specifiche del linguaggio sono
    disponibili all'indirizzo <a
    href="http://www.php.net/docs.php">http://www.php.net/docs.php</a>.</dd>

    <dt><span class="free">HTML</span>:</dt>

    <dd><q>HyperText Markup Language</q> &egrave; il linguaggio di markup
    utilizzato per descrivere la struttura dei contenuti di un
    documento ipertestuale. Si limita a descrivere gli elementi del
    documento, definendo il contenuto e non la formattazione. Il suo
    impiego riguarda la pubblicazione di siti Web.</dd>

    <dt><span class="free">CSS</span>:</dt>

    <dd><q>Cascading Style Sheets</q> &egrave; un linguaggio usato per
    descrivere come formattare gli elementi di una pagina HTML.</dd>

    <dt><span class="free">JavaScript</span>:</dt>

    <dd>E' un linguaggio che permette di estendere e variare
    dinamicamente il contenuto di una pagina HTML. Viene interpretato
    dal browser e consente di arricchire le funzionalit&agrave;
    dell'interfaccia. E' object oriented e la sintassi si richiama al
    Java, ma &egrave; pi&ugrave; semplice e pi&ugrave; elastica.</dd>

    <dt><span class="free">C</span>:</dt>

    <dd>E' un linguaggio imperativo di alto livello che offre funzioni
    per gestire in modo estremamente efficiente l'hardware. E' nato per
    scrivere il sistema operativo UNIX e praticamente tutti i calcolatori
    dotati di un sistema operativo Unix-like forniscono un compilatore
    per tale linguaggio.</dd>
  </dl>
  <h2>Software utilizzato</h2>

  <!-- <p>Questa tesina &egrave; stata realizzata con <span class="free">Software
  Libero</span> su un <span class="free">Sistema Operativo Libero</span> ed &egrave;
  orgogliosa di non aver utilizzato nessun programma Microsoft e di non
  presentare alcuna immagine GIF.</p> 

  <p>I linguaggi usati (<span class="free">PHP4</span>, <span class=
  "free">JavaScript</span> e <span class="free">HTML</span>) sono tutti
  standardizzati pertanto pi&ugrave; produttori di software possono implementarli. La
  tesina pu&ograve; girare su qualsiasi server web che includa il modulo per il PHP e
  pu&ograve; essere vista con qualsiasi browser che supporti <span class=
  "free">JavaScript</span> o che permetta l'invio del form senza dover premere
  bottoni.<br> -->
  Per lo sviluppo e i test sulla portabilit&egrave; ho usato:<br>

  <dl>
    <dt>Sistemi Operativi:</dt>

    <dd><span class="free">Debian GNU/Linux</span></dd>

    <dd><span class="free">Red Hat Linux</span></dd>

    <dd><span class="free">Slackware Linux</span></dd>

    <dt>Web Servers, moduli e librerie:</dt>

    <dd><span class="free">Apache/1.3.23, 1.3.26, 1.3.27</span></dd>

    <dd><span class="free">Apache/2.0.40, 2.0.47</span></dd>

    <dd><span class="free">PHP/4.2.2, 4.3.3</span></dd>

    <dd><span class="free">GD/2.0.15</span></dd>

    <dd><span class="free">libpng/1.2.2</span></dd>

    <dt>Editor:</dt>

    <dd><span class="free">Emacs</span></dd>

    <dd><span class="free">Quanta</span></dd>

    <dt>Browser:</dt>

    <dd><span class="free">Galeon</span></dd>

    <dd><span class="free">Konqueror</span></dd>

    <dd><span class="free">links</span></dd>

    <dd><span class="free">Mozilla Firebird</span></dd>
 
    <dd>Netscape</dd>
 
    <dt>Sistemi per controllo versione:</dt>

    <dd><span class="free">cvs</span></dd>

    <dt>Utility per controllare la sintassi HTML:</dt>

    <dd><span class="free">HTML Tidy for Linux</span></dd>

    <dt>Compilatore C:</dt>

    <dd><span class="free">gcc</span></dd>

    <dt>Creazione e manipolazione delle immagini:</dt>

    <dd><span class="free">Gimp</span></dd>

    <dd><span class="free">TeX</span></dd>

  </dl><br>
  <br>

  <h2>Effetti collaterali</h2>

  <p>Realizzare questa tesina ha comportato anche alcuni effetti non correlati
  al metodo del simplesso. In primis ha richiesto di approfondire le conoscenze
  sul Web Server <span class="free">Apache</span> e dei linguaggi
  <span class="free">PHP4</span>, <span class="free">JavaScript</span> e
  <span class="free">HTML</span>. In aggiunta per automatizzare la richiesta
  di risoluzione di problemi particolarmente interessanti, sia ai fini di
  debugging del programma che per fornire un modo rapido per valutare
  questa tesina, &egrave; stato realizzato un <a href=
  "show_src.php?script=prova_simplesso.c" target="sorgenti">programma</a> in
  <span class="free">C</span> che, processando un file di puro testo, genera
  l'appropriata richiesta a <a href="show_src.php?script=simplesso.php" target=
  "sorgenti">simplesso.php</a>. Ci&ograve; ha comportato il rispolvero di alcune
  competenze su tale linguaggio e sul <span class="free">protocollo
  HTTP/1.1</span>, oltre che sull'uso dei <em>socket</em>.</p>

  <!-- <p>Inoltre ho passato buona parte dell'estate ad abbronzarmi davanti al
  monitor del computer ;)</p> -->
  <hr>

  <!--
  <p align="center"><img src="images/msfree.png" alt=
  "orgoglioso di essere libero da M$ al 100%" align="middle"></p>
  -->
</body>
</html>
