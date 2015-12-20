#!/usr/bin/python
# -*- coding: utf-8 -*-

from sys import argv
import sys
import MySQLdb

#Get our data from the title
#Format: ShowName Episode Number - Title
filename = argv[1]
filename = filename[:-4]
show = filename.split("Episode")[0].strip()

db = MySQLdb.connect("localhost", "root", "", "tynime")
cursor = db.cursor()

sql = "SELECT seriesId FROM series WHERE seriesName = \"%s\";" % show
cursor.execute(sql)
if cursor.rowcount == 1:
	sys.stdout.write(str(cursor.fetchone()[0]))
else:
	sql = "INSERT INTO series(seriesName) VALUES (\"%s\");" % show
	cursor.execute(sql)
	sys.stdout.write(str(cursor.lastrowid))
db.commit()