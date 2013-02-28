HTML = index.php \
       0bootstrap.html \
       0compile-chroot.html \
       0compile-dev.html \
       0compile.html \
       0compile-scons.html \
       0export.html \
       0install-2.0.html \
       0mirror.html \
       0publish.html \
       0release-binaries.html \
       0release.html \
       0release-phases.html \
       0share.html \
       0test.html \
       api-example.html \
       comparison.html \
       deb2zero.html \
       dev.html \
       dev-site.html \
       distribution-integration.html \
       doc.html \
       ebox.html \
       faq.html \
       features.html \
       feedlint.html \
       get-involved.html \
       user-guide-cache.html \
       user-guide-first-launch.html \
       user-guide-intro.html \
       user-guide-policy.html \
       user-guide-shortcuts.html \
       user-guide-apps.html \
       injector-design.html \
       injector-feeds.html \
       injector-find.html \
       injector.html \
       injector-packagers.html \
       injector-security.html \
       injector-specs.html \
       injector-trouble.html \
       install-linux.html \
       install-mac.html \
       install-source.html \
       install-unix.html \
       install-windows.html \
       interface-spec.html \
       legal.html \
       local-feeds.html \
       make-headers.html \
       manifest-spec.html \
       news.html \
       packagers.html \
       package-inkscape.html \
       package-rox.html \
       package-scons.html \
       packaging-binaries.html \
       packaging-concepts.html \
       perspectives.html \
       pkg2zero.html \
       roadmap.html \
       servers.html \
       sharing.html \
       solver.html \
       support.html \
       survey.html \
       templates.html \
       tools.html \
       users.html \
       using-git.html \
       virtual.html \
       walkthrough.html \
       why.html \
       why-not.html

all: htmlfiles
	(ls *.html; find python-api -name '*.html') | sed 's!^\(\./\)\?!http://0install.net/!' | grep -v /google > sitemap.txt

htmlfiles: ${HTML}

%.html: %.xml to_html.xsl structure.xml
	xsltproc -o $@ --stringparam file "$@" to_html.xsl "$<"

%.php: %.xml to_html.xsl structure.xml
	xsltproc --stringparam file index.html to_html.xsl "$<" | \
	sed -e 's/@AUTO_DOWNLOAD_HTML@/<?php require("detect.php"); ?>/' > "$@"

lists/%.xml: lists/%.lst
	./lists/make-list.py $<

index.php: index.xml news.xml

injector-feeds.html: lists/featured.xml lists/0tools.xml

linklint:
	linklint -error -warn -xref -forward -http -host 0install.net /@
