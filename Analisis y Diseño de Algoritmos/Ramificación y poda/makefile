#Georg Usin, X8174555X

all: target

target:
	g++ maze_bb.cc -o maze_bb -O3

tar: maze_bb.cc
	tar cvfz X8174555X.tar.gz maze_bb.cc makefile memoria.pdf

clean: 
	-rm maze_bb
