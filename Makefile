build:
	docker run --rm -it -v $(PWD):/app mkdocs build

serve:
	docker run --rm -it -v $(PWD):/app -p 8000:8000 mkdocs serve -a 0.0.0.0:8000

image:
	docker build . -t mkdocs
