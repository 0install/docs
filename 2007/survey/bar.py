#!/usr/bin/env python
import csv, sys, os

src = 'AllResults.csv'

col_from, col_to = map(int, [sys.argv[1], sys.argv[2]])

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

print title + ", count"

for line in results:
	for col in range(col_from, col_to + 1):
		r = line[col]
		if r == 'Yes':
			tally[col - col_from] += 1
		elif r != 'No':
			print "Unknown", r

sorted_results = reversed(sorted([(tally[i], headings[i]) for i in range(0, len(headings))]))

import plot
surface = plot.plot(title, [(key, value) for value, key in sorted_results])
surface.write_to_png('charts/%s.png' % title.replace('/', '_').replace(' ', '_').replace('?', ''))
