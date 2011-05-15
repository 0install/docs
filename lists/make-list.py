#!/usr/bin/env python
from zeroinstall.injector.config import load_config
from zeroinstall.injector.namespaces import XMLNS_IFACE
from zeroinstall.support import tasks

import sys, os, codecs, urllib, shutil, subprocess
from xml.dom import minidom

config = load_config()

MAX_TEASER_LENGTH = 256
MAX_ICON_SIZE = 64

top_dir = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
feed_icons_dir = os.path.join(top_dir, 'feed_icons')

def get_icon(elem, iface):
	uri = iface.uri
	have_icon = False
	name = uri.rsplit('/', 1)[-1]
	if name.endswith('.xml'):
		name = name[:-4]

	icon_path = 'feed_icons/%s.png' % name
	full_icon_path = os.path.join(top_dir, icon_path)
	if os.path.isfile(full_icon_path):
		have_icon = True
	else:
		# Get it from the cache
		cached_icon = config.iface_cache.get_icon_path(iface)
		if not cached_icon:
			download = config.fetcher.download_icon(iface)
			if download:
				print "Downloading icon", icon_path
				tasks.wait_for_blocker(download)
			cached_icon = config.iface_cache.get_icon_path(iface)
		if cached_icon:
			shutil.copyfile(cached_icon, full_icon_path)
			have_icon = True
			os.system("svn add '%s'" % full_icon_path)
			
	if not have_icon:
		icon_path = 'tango/applications-system.png'
		full_icon_path = os.path.join(top_dir, icon_path)
		print "No icon for", uri, name
	
	icon_meta = {}
	child = subprocess.Popen(["pngmeta", "--all", "--", full_icon_path], stdout = subprocess.PIPE)
	stdout, _ = child.communicate()
	child.wait()
	for line in stdout.split('\n'):
		if ':' in line:
			key, value = line.split(':', 1)
			icon_meta[key] = value

	width = int(icon_meta['image-width'])
	height = int(icon_meta['image-height'])

	if width > MAX_ICON_SIZE or height > MAX_ICON_SIZE:
		scale = float(MAX_ICON_SIZE) / max(width, height)
		width *= scale
		height *= scale
	width, height = map(int, (width, height))

	elem.setAttribute('width', str(width))
	elem.setAttribute('height', str(height))
	elem.setAttribute('icon', icon_path)

def add_details(elem, iface):
	elem.setAttribute('uri', iface.uri)
	elem.setAttribute('name', iface.name)
	elem.setAttribute('summary', iface.summary)

	get_icon(elem, iface)

	homepage = None
	for child in iface.get_metadata(XMLNS_IFACE, 'homepage'):
		homepage = child.content
		break
	if homepage:
		elem.setAttribute('homepage', homepage)

	description = iface.description
	if len(description) > MAX_TEASER_LENGTH:
		description = description[:MAX_TEASER_LENGTH - 3]
		description = description[:description.rindex(' ')]
		description += ' ...'
	description_elem = elem.ownerDocument.createTextNode(description)
	elem.appendChild(description_elem)

def get_details(uris):
	doc = minidom.parseString("<list/>")
	root = doc.documentElement

	for uri in uris:
		feed = doc.createElement('feed')
		iface = config.iface_cache.get_interface(uri)
		add_details(feed, iface)
		root.appendChild(feed)
	return doc

for f in sys.argv[1:]:
	assert f.endswith('.lst'), 'Not a list file: %s' % f
	stem = f[:-4]
	interfaces = [line.strip() for line in open(f)]
	doc = get_details(interfaces)
	results = stem + '.xml'
	with open(results, 'w') as s:
		doc.writexml(s, newl='\n', addindent='  ')
	print "Wrote", results
