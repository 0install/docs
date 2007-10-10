#!/usr/bin/env python
import csv, sys, os
from support import *

data = []

skipped = 0

col = int(args[0])

for line in csv.reader(file(src)):
	x = line[col]
	if x:
		x = x[0].capitalize() + x[1:]
		x = x.replace('&', '&amp;')
		data.append(x.replace('\\\'', "'").strip())
	else:
		skipped += 1

title = data.pop(0)
#print `data`
#print "(%d empty)" % skipped
#print title

if len(args) > 1:
	assert args[1] == 'long'
	long = True
else:
	long = False

template = file('charts/template.html', 'a')
if long:
	if '[Other]' in title:
		template.write("""<h3>Other comments</h3>""")
	else:
		template.write("""<h3>%s</h3>""" % title)

	template.write('\n'.join("<blockquote><p><q>%s</q></p></blockquote>" % quote.replace('<', '&lt;') for quote in data))
else:
	if '[Other]' in title:
		template.write("""<blockquote><p><strong>Other comments: </strong>""")
	else:
		template.write("""<h3>%s</h3><blockquote><p>""" % title)

	template.write(', '.join("<q>%s</q>" % quote.replace('<', '&lt;') for quote in data))

	template.write(".</p></blockquote>")
