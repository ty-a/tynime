# -*- coding: utf-8 -*-
from sys import argv
import sys
import MySQLdb

#Our series ID, obtained after adding the series to the DB
seriesId = str(argv[2])
#Get our data from the title
#Format: ShowName Episode Number - Title
filename = argv[1]
filename = filename[:-4]
show = filename.split("Episode")[0].strip()

#episode number
episodeNumber = filename.split("Episode")[1]
episodeNumber = episodeNumber.split("–")[0].strip()

#title
title = filename.split("–")[1].strip()

db = MySQLdb.connect("localhost", "root", "", "tynime")
cursor = db.cursor()

sql = "INSERT INTO videos(seriesId,views,name,seriesPos) VALUES (" + seriesId + ", 0, \"" + title.replace("\"", "\\\"") + "\", " + episodeNumber + ");"
cursor.execute(sql)
db.commit()