#!/usr/bin/env python
from zeroinstall.injector.iface_cache import iface_cache
from zeroinstall.injector.namespaces import XMLNS_IFACE

import sys, os, codecs, urllib
import xml.sax.saxutils
from xml.dom import minidom

MAX_TEASER_LENGTH = 256

known = [line.strip() for line in file('known-interfaces')]
known.sort(key = lambda s: s.rsplit('/', 1)[1].lower())

result = codecs.open('feed-translations.c', 'w', encoding = 'utf-8')

done = set()

def escaped(s):
	return s.replace('%', '%%').replace('\\', '\\\\').replace('"', '\\"').replace("'", "\\'")

assert escaped("Hello") == "Hello"
assert escaped("Hello %s") == "Hello %%s"
assert escaped('"It"') == '\\"It\\"'

def format_para(para):
	"""Turn new-lines into spaces, removing any blank lines."""
	lines = [l.strip() for l in para.split('\n')]
	return ' '.join(filter(None, lines))

def translate(s):
	if not s: return
	if s in done: return
	done.add(s)
	lines = escaped(s).split('\n')
	s = '\\n\\\n'.join(lines)
	result.write('_("%s")\n' % s)

for uri in known:
	print uri
	assert uri.startswith('http://')
	assert not uri.startswith('http://0install.net/tests/')
	assert not uri.startswith('http://localhost')

	iface = iface_cache.get_interface(uri)

	if not iface.summary:
		print >>sys.stderr, "Unreadable:", iface.uri
		os.system("0launch -g -- '%s'" % uri)
		continue
	
	result.write('\n// Translations for %s (%s)\n' % (iface.get_name(), iface.uri))
	translate(iface.summary)

	paragraphs = [format_para(p) for p in iface.description.split('\n\n')]
	translate('\n'.join(paragraphs))

result.close()

if os.system('xgettext --keyword=_ --from-code=utf-8 feed-translations.c -o feed-translations.pot'):
	raise Exception('xgettext failed')
print "Created feed-translations.pot (update the header)"
