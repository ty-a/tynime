#!/bin/bash
for f in *.ass
do
	ass-to-vtt "$f" >> "${f%.enUS.ass}.vtt"
done

for g in *.vtt
do
	echo $g
	python /var/www/scripts/finish-convert.py "$g"
done

