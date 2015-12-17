from sys import argv
import re

with open(argv[1], 'r') as content_file:
	content = content_file.read()
	
content = re.sub(r'\r\n(<.*>)\{\\a6\}', r' align:middle line:1\n\1', content)
content = re.sub(r'\{\\fad\(.*\)\}', r'', content)

with open(argv[1], 'w') as file_writer:
	file_writer.write( content )

