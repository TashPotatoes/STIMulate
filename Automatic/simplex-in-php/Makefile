# DEBUG="-ggdb3"
WARN=-Wall
SRC=prova_simplesso.c
OBJ=esempi/prova_simplesso
OUTPUT=-o $(OBJ)
OPT=-O2

all: $(OBJ)

$(OBJ): $(SRC)
	gcc -O2 $(WARN) $(DEBUG) $(DEFINE) $(OUTPUT) $(SRC)

clean:
	rm -f $(OBJ)
