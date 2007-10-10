#!/usr/bin/env python
import csv, sys, os
from support import *

mappings = {
	'german': 'de',
	'deutsch': 'de',
	'spanish': 'es',
	'english': 'en',
	'english)': 'en',
	'french': 'fr',
	'fi, ru': 'fi',
	'czech': 'cs',
	'en_gb': 'en',
}

col = int(args[0])
if len(args) > 1:
	fn = args[1]
	out_fn = args[2]
else:
	fn = 'x'
	out_fn = 'x'

data = []

skipped = 0

for line in csv.reader(file(src)):
	x = line[col]
	if x:
		data.append(x)
	else:
		skipped += 1

title = data.pop(0)
#print `data`
#print "(%d empty)" % skipped
#print title

if '[' in title:
	title = title.split('[', 1)[1].strip(']')

count = {}

for item in data:
	if item.lower() in mappings:
		item = mappings[item.lower()]
	x = eval(fn, {'x': item})
	#print item, "->", x

	if x not in count:
		count[x] = 0
	count[x] += 1

if 'Important' in count:
	sorted_keys = ['Very important', 'Important', 'Nice to have', "Don't care", "Unwanted"]
	for k in sorted_keys:
		if k not in count:
			count[k] = 0
	assert len(sorted_keys) == len(count)
elif "I didn't know about this" in count:
	sorted_keys = ["I use this", "Don't need this", "I couldn't get this work", "I didn't know about this", ]
	for k in sorted_keys:
		if k not in count:
			count[k] = 0
	assert len(sorted_keys) == len(count)
elif out_fn == 'x':
	sorted_results = reversed(sorted((count[key], key) for key in count))
	sorted_keys = [key for value, key in sorted_results]
else:
	sorted_keys = sorted(count.keys())

def apply_out_fn(x): return eval(out_fn, {'x': x})
plot_and_save(title, [(apply_out_fn(key), count[key]) for key in sorted_keys])
