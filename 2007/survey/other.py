#!/usr/bin/env python
import csv, sys, os
from support import *

data = []

skipped = 0

col = int(sys.argv[1])

for line in csv.reader(file(src)):
	x = line[col]
	if x:
		x = x[0].capitalize() + x[1:]
		data.append(x.replace('\\\'', "'"))
	else:
		skipped += 1

title = data.pop(0)
print `data`
print "(%d empty)" % skipped
print title

template = file('charts/template.html', 'a')
if '[Other]' in title:
	template.write("""<p><strong>Other comments: </strong>""")
else:
	template.write("""<h3>%s</h3><p>""" % title)

template.write(', '.join("<q>%s</q>" % quote.replace('<', '&lt;') for quote in data))

template.write(".</p>")
