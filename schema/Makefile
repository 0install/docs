all:
	java -jar trang.jar feed-schema.rnc feed-schema.rng
	for x in ../*/*/*.xml; do xmllint --noout --relaxng feed-schema.rng "$$x"; done
