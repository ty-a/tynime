#!/bin/bash
for g in *.mp4
do
	echo $g
	python /var/www/scripts/addVideosToDB.py "$g"
done
