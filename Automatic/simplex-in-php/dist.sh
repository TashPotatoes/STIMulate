#!/bin/sh

bin="esempi/prova_simplesso"
css="info.css"
esempi="esempi/"
images="images/"
misc="Makefile"
shell_script="dist.sh"
src_c="prova_simplesso.c"
src_php="immagini.php immissione_dati_0.php immissione_dati_1.php index.php info.php matrice.php razionale.php show_esempi.php show_src.php simplesso.php template.php testata.php util.php"

echo "Fase 1: rimozione backup"
rm -f *~ esempi/*~
echo "Fase 2: rigenerazione degli esempi compilati e dei log"
cd esempi
make realclean
make
cd ..
echo "Fase 3: archiviazione"
tar czf ro.tar.gz $bin $css $esempi $images $misc $shell_script $src_c $src_php
echo "estrarre con 'tar xvzpf ro.tar.gz' nella dir '~user/public_html/ro'"
