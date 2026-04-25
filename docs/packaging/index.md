title: Packaging overview

# Packaging overview

To make software available through Zero Install, you publish a signed XML _feed_ that lists the available versions, where to download them, how to run them, and what they depend on. Anyone with a web server (or a static host like GitHub Pages) can publish feeds; there is no central registry.

A feed looks roughly like this:

```xml
<?xml version="1.0"?>
<interface xmlns="http://zero-install.sourceforge.net/2004/injector/interface">
  <name>MyApp</name>
  <summary>does something useful</summary>
  <homepage>https://example.com/myapp</homepage>

  <group license="MIT License">
    <command name="run" path="myapp"/>

    <implementation arch="Linux-x86_64" version="1.0" released="2026-04-01" stability="stable">
      <manifest-digest sha256new="..."/>
      <archive href="https://example.com/myapp-1.0-linux-x64.tar.gz"/>
    </implementation>
  </group>
</interface>
```

You don't usually write this from scratch. The [tools](../tools/index.md) generate and update feeds for you.

## Where to start

Before reading the guides below, skim [Concepts](concepts.md) to make sure you're familiar with the terms _interface_, _feed_, _implementation_ and _command_.

[Tutorials](tutorial/index.md) walk through end-to-end workflows.

[Guides](guides/index.md) are reference recipes for packaging specific kinds of software.

!!! tip
    If you only want to run a program from a Git checkout, you may not need to publish anything at all. See [Local feeds](local-feeds.md).

## Reference

- [Feed file specification](../specifications/feed.md) &ndash; the formal XML format.
- [Tools](../tools/index.md) &ndash; the publishing tools (0template, 0publish, 0publish-gui, 0repo, 0release, 0watch, 0compile, 0test, feedlint).
- [apps.0install.net](https://github.com/0install/apps) &ndash; a large public repository of feeds, useful as a reference.
