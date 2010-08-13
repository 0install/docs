HTML = index.php faq.html support.html doc.html injector.html injector-packagers.html injector-design.html injector-security.html injector-using.html injector-tests.html links.html matrix.html injector-feeds.html injector-specs.html injector-trouble.html 0publish.html howitworks.html goals.html dev.html injector-find.html package-rox.html interface-spec.html manifest-spec.html package-inkscape.html flash.html roadmap.html 0compile.html 0compile-scons.html package-scons.html sharing.html walkthrough.html feedlint.html perspectives.html distribution-integration.html legal.html survey.html 0release.html tools.html 0release-phases.html 0mirror.html make-headers.html tutorial-policy.html virtual.html tutorial-downloads.html deb2zero.html using-git.html 0export.html install-source.html install-linux.html install-mac.html install-windows.html install-unix.html 0test.html 0share.html 0release-binaries.html 0compile-dev.html pkg2zero.html ebox.html tutorial-launchers.html

all: htmlfiles
	find . -name '*.html' | sed 's!^\.!http://0install.net!' | grep -v /google > sitemap.txt

htmlfiles: ${HTML}

%.html: %.xml to_html.xsl structure.xml
	xsltproc -o $@ --stringparam file "$@" to_html.xsl "$<"

%.php: %.xml to_html.xsl structure.xml
	xsltproc -o $@ --stringparam file index.html to_html.xsl "$<"
	sed -i 's/@AUTO_DOWNLOAD_HTML@/<?php require("detect.php"); ?>/' "$@"

rox-feeds.xml:
	wget 'http://rox.sourceforge.net/desktop/rox/zero-install-feeds' -O rox-feeds.xml

injector-feeds.html: all-feeds.xml

linklint:
	linklint -error -warn -xref -forward -http -host 0install.net /@
