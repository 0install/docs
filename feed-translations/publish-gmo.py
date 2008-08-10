#!/usr/bin/env python
import os, subprocess, sys

print "Making all .gmo files..."

for trans in os.listdir('launchpad-export'):
	if trans.endswith('.po'):
		lang = trans.split('-', 2)[2].split('.', 1)[0]
		output = os.path.join('lang', lang + '.gmo')
		sys.stdout.write(lang + ": ")
		sys.stdout.flush()
		subprocess.check_call(['msgfmt', '--statistics',
				       'launchpad-export/' + trans,
				       '-o', output])
