#!/usr/bin/env python
import csv, sys, os

from support import *

col_from, col_to = map(int, [args[0], args[1]])

data = []

skipped = 0

tally = []

results = csv.reader(file(src))
header = results.next()
headings = []

for col in range(col_from, col_to + 1):
	title, heading = header[col].split('[', 1)
	headings.append(heading.strip(' ]'))
	tally.append(0)
title = title.strip()

#print title + ", count"

for line in results:
	for col in range(col_from, col_to + 1):
		r = line[col]
		if r == 'Yes':
			tally[col - col_from] += 1
		elif r != 'No':
			print "Unknown", r

sorted_results = reversed(sorted([(tally[i], headings[i]) for i in range(0, len(headings))]))

plot_and_save(title, [(key, value) for value, key in sorted_results])
