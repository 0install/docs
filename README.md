# Zero Install Documentation

This repository holds the sources for the [Zero Install documentation website](https://docs.0install.net/).

We use [MkDocs](https://www.mkdocs.org/) to generate static web pages from the Markdown files (`*.md`) located in the [docs/](docs/) directory.

## Preview

To get a preview of the website while editing the content run:

    pip install -r requirements.txt
    mkdocs serve

If you have Docker you can instead run:

    docker-compose up

You can now open an automatically refreshing preview in you browser at: http://127.0.0.1:8000/

## Building

Commits to the `master` branch are automatically built and published.

To generate the HTML files locally run:

    mkdocs build

If you have Docker you can instead run:

    docker-compose run mkdocs build

You can now find the generated HTML files in the `site/` directory.
