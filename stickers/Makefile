.PHONY: all
all: featurecard-h.png \
featurecard.png \
smallsticker.png \
smallsticker2.png

.PHONY: clean
clean:
	rm featurecard-h.png \
featurecard.png \
smallsticker.png \
smallsticker2.png

featurecard-h.png: firefox-logo.png Makefile
	convert -define png firefox-logo.png -thumbnail 468x60 -gravity center -background transparent -extent 468x60 featurecard-h.png
	optipng -o9 featurecard-h.png
featurecard.png: firefox-logo.png Makefile
	convert -define png firefox-logo.png -thumbnail 200x250 -gravity center -background transparent -extent 200x250 featurecard.png
	optipng -o9 featurecard.png
smallsticker.png: firefox-logo.png Makefile
	convert -define png firefox-logo.png -thumbnail 200x100 -gravity center -background transparent -extent 200x100 smallsticker.png
	optipng -o9 smallsticker.png
smallsticker2.png: firefox-logo.png Makefile
	convert -define png firefox-logo.png -thumbnail 165x90 -gravity center -background transparent -extent 165x90 smallsticker2.png
	optipng -o9 smallsticker2.png
