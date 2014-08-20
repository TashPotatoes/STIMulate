#!/bin/bash
for f in $(find . -maxdepth 1 -type f)
do
       	if file $f | grep -q FORTRAN
	then  
		if echo $f | grep -q txt;
		then
			continue
		fi
		echo $f | sed -e 's/\.\///' | xargs echo ./prova_simplesso 127.0.0.1 /develop/Simplex-in-PHP/simplesso.php
		echo $f | sed -e 's/\.\///' | xargs ./prova_simplesso 127.0.0.1 /develop/Simplex-in-PHP/simplesso.php
		touch -r $f ${f}.html
	fi
done

