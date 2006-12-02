#!/usr/bin/env python
from zeroinstall.injector.iface_cache import iface_cache
from zeroinstall.injector.namespaces import XMLNS_IFACE

import sys, os, codecs, urllib
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

known = [line.strip() for line in file('known-interfaces')]
known.sort(key = lambda s: s.rsplit('/', 1)[1].lower())

known_feeds = {}

result = codecs.open('all-feeds.xml', 'w', encoding = 'utf-8')

result.write('<?xml version="1.0" encoding="utf-8"?>\n')
result.write('<list>\n')
for uri in known:
	assert uri.startswith('http://')
	assert not uri.startswith('http://0install.net/tests/')
	assert not uri.startswith('http://localhost')

	iface = iface_cache.get_interface(uri)

	if not iface.summary:
		print >>sys.stderr, "Unreadable:", iface.uri
		os.system("0launch -g -- '%s'" % uri)
		continue

	if iface.feed_for:
		continue
	
	for f in iface.feeds:
		known_feeds[f.uri] = True

	homepage = None
	for elem in iface.get_metadata(XMLNS_IFACE, 'homepage'):
		homepage = elem.content
		break
	if homepage is None:
		homepage = homepages.get(uri, None)

	have_icon = False
	icon_path = 'icons/%s.png' % uri.split('/')[-1]
	if not os.path.isfile(icon_path):
		for icon_elem in iface.get_metadata(XMLNS_IFACE, 'icon'):
			if icon_elem.getAttribute('type') == 'image/png':
				icon_href = icon_elem.getAttribute('href')
				urllib.urlretrieve(icon_href, icon_path)
				have_icon = True
				os.system("svn add '%s'" % icon_path)
				break
	if not have_icon:
		icon_path = 'tango/applications-system.png'

	result.write("<feed uri=%s name=%s summary=%s icon=%s" \
		% tuple(map(quoteattr, (uri, iface.name, iface.summary, icon_path))))
	
	if homepage:
		result.write(' homepage=%s' % quoteattr(homepage))
	result.write('/>\n\n')

result.write("</list>\n")

unknown = []
for uri in os.popen('0launch --list'):
	uri = uri.strip()
	if uri.startswith('/'):
		continue
	if uri.endswith('.new'):
		continue
	if uri.startswith('http://0install.net/tests/'):
		continue
	if uri.startswith('http://localhost'):
		continue
	if uri.startswith('http://www.ecs.soton.ac.uk/~tal'):
		continue
	if uri in known_feeds:
		continue
	if uri not in known:
		unknown.append(uri)
if unknown:
	print >>sys.stderr, "Not in known list:"
	for uri in unknown:
		print >>sys.stderr, uri
