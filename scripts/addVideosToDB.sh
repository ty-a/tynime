#!/bin/bash

for g in *.mp4
do
	# Checks if seriesId is populated
	if [ -z "$seriesId" ]
	then
		seriesId=$(python /var/www/scripts/getSeriesId.py "$g")
		echo "New series ID created for $g, it is $seriesId!"
	fi
	
	python /var/www/scripts/addVideosToDB.py "$g" "$seriesId"
done
