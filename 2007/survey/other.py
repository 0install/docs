#!/usr/bin/env python
import csv, sys, os

src = 'AllResults.csv'

data = []

skipped = 0

col = int(sys.argv[1])

for line in csv.reader(file(src)):
	x = line[col]
	if x:
		data.append(x)
	else:
		skipped += 1

title = data.pop(0)
print `data`
print "(%d empty)" % skipped
print title
