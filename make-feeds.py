#!/usr/bin/env python
from zeroinstall.injector.iface_cache import iface_cache
from zeroinstall.injector.namespaces import XMLNS_IFACE

import sys, os
import xml.sax.saxutils
from xml.dom import minidom

rox_homepages = minidom.parse('rox-feeds.xml')
homepages = {}
for x in rox_homepages.documentElement.getElementsByTagName('feed'):
	homepages[x.getAttribute('uri')] = x.getAttribute('homepage')

def quoteattr(x):
	if x:
		return xml.sax.saxutils.quoteattr(x)
	return "''"

print '<?xml version="1.0" encoding="utf-8"?>'
print '<list>'
for uri in file('known-interfaces'):
	uri = uri.strip()

	if uri.startswith('/'):
		continue
	if uri.startswith('http://0install.net/tests/'):
		continue
	if uri.startswith('http://localhost'):
		continue

	assert uri.startswith('http://')

	iface = iface_cache.get_interface(uri)

	if not iface.summary:
		print >>sys.stderr, "Unreadable:", iface.uri
		os.system("0launch -g -- '%s'" % uri)
		continue

	if iface.feed_for:
		continue

	homepage = homepages.get(uri, None)
	icon = 'tango/applications-system.png'
	for icon_elem in iface.get_metadata(XMLNS_IFACE, 'icon'):
		if icon_elem.getAttribute('type') == 'image/png':
			icon = icon_elem.getAttribute('href')

	print "<feed uri=%s name=%s summary=%s icon=%s" \
		% tuple(map(quoteattr, (uri, iface.name, iface.summary, icon)))
	
	if homepage:
		print ' homepage=%s' % quoteattr(homepage)
	print '/>\n'

print "</list>"
