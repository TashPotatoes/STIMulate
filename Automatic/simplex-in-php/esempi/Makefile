all: clean
	for problema in `ls`; do if file $$problema | grep -iq 'ASCII text$$'; then ./prova_simplesso 127.0.0.1 /~gim/ro/simplesso.php $$problema; touch -mr $$problema $$problema.html; fi; done

txt: all
	for html in `ls *.html`; do w3m -dump $$html > $${html%html}txt; done

clean:
	rm -f *.html *~ *.txt ../images/tmp/*.png ../images/tmp/esempi/*.png

realclean: clean
	rm -f log/error_log log/request_log

