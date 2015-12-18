# -*- coding: utf-8 -*-
from sys import argv
import sys
import MySQLdb

#Our series ID, obtained after adding the series to the DB
seriesId = "2"
#Get our data from the title
#Format: ShowName Episode Number - Title
filename = argv[1]
filename = filename[:-4]
print filename
show = filename.split("Episode")[0]
print show
#episode number
episodeNumber = filename.split("Episode")[1]
print episodeNumber
episodeNumber = episodeNumber.split("–")[0].strip()
print episodeNumber
#title
title = filename.split("–")[1].strip()
print title

print "add db pass"
sys.exit()
db = MySQLdb.connect("localhost", "root", "", "tynime")
cursor = db.cursor()

sql = "INSERT INTO videos(seriesId,views,name,seriesPos) VALUES (" + seriesId + ", 0, '" + title + "', " + episodeNumber + ");"
print sql

cursor.execute(sql)
db.commit()