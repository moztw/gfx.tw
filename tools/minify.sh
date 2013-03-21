#!/bin/sh

if [ -z $1] || [ ! -d $1 ]; then
        echo 'Usage: '$0' [Web root]';
        exit 1;
fi

if [ ! -e $1/js/minify.txt ]; then
	echo 'minify.txt does not exist.';
	exit 1;
fi

for JS in `cat $1/js/minify.txt`
do
	JSMIN=`echo $JS | sed -e s/\.js$/.min.js/g`;

	echo Minify $JS ...
	$(dirname $0)/closure-complier.pl < $1/js/$JS > $1/js/$JSMIN;
done
exit 0;
