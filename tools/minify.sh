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
	
	echo Edit all links to $JS ...
	for HTML in `grep -rl $JS $1 | grep -v /js/minify.txt | grep -v \.git | grep -v \.svn | grep -v \.hg | grep -v system/cache`
	do
		echo '    Processing '$HTML ...
		eval 'sed -i -e "s/js\/'$JS'/js\/'$JSMIN'/g" '$HTML;
	done
done
exit 0;
