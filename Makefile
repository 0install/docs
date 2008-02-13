HTML = index.html compare.html faq.html install.html technical.html support.html docs.html security.html packagers.html packagers-cs-1.html packagers-cs-2.html injector.html injector-packagers.html injector-design.html injector-security.html injector-using.html injector-tests.html filesystem.html doc.html links.html fs-faq.html matrix.html injector-feeds.html injector-specs.html injector-trouble.html 0publish.html howitworks.html goals.html dev.html injector-find.html package-rox.html interface-spec.html manifest-spec.html package-inkscape.html flash.html roadmap.html 0compile.html 0compile-scons.html package-scons.html sharing.html walkthrough.html feedlint.html perspectives.html distribution-integration.html legal.html survey.html 0release.html tools.html 0release-phases.html 0mirror.html

all: htmlfiles
	find . -name '*.html' | sed 's!^\.!http://0install.net!' | grep -v /google > sitemap.txt

htmlfiles: ${HTML}

%.html: %.xml to_html.xsl structure.xml
	xsltproc -o $@ --stringparam file "$@" to_html.xsl "$<"
	#xmllint --noout --valid $@

rox-feeds.xml:
	wget 'http://rox.sourceforge.net/desktop/rox/zero-install-feeds' -O rox-feeds.xml

injector-feeds.html: all-feeds.xml

linklint:
	linklint -error -warn -xref -forward -http -host 0install.net /@
