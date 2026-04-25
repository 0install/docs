# Tutorials

These tutorials walk through end-to-end publishing workflows. Each one builds on the previous, but they can be read independently.

[Publishing your own app](publish-app.md)
: Take a single binary release and publish it as a Zero Install feed using [0template](../../tools/0template.md), [0publish](../../tools/0publish.md) and GitHub Pages.

[Automating a single feed with CI](automate-ci.md)
: Move the manual `0template` + `0publish` steps into GitHub Actions so a tag push publishes a new version automatically.

[Managing multiple feeds with 0repo](multi-feed.md)
: Once you have more than one feed (a CLI, a GUI, a couple of libraries), use [0repo](../../tools/0repo.md) to maintain a coherent, signed, browsable repository on GitHub Pages.

[A catalog of third-party feeds](catalog.md)
: Keep feeds for software you don't author up to date automatically with [0watch](../../tools/0watch.md), 0template, 0repo and GitHub Actions. The same setup that powers [apps.0install.net](https://apps.0install.net/).

If you only want to test a feed locally before going through any of this, see [Local feeds](../local-feeds.md). If you want a reference for the XML format itself, see the [feed specification](../../specifications/feed.md).
