# Cross-platform apps

Zero Install supports two ways to ship a single user-facing feed that works on multiple operating systems:

1. **One feed, multiple `<implementation>` elements**, each with its own `arch`. Use this when all platforms can be updated in lockstep, e.g. when one upstream release has builds for every OS.
2. **One top-level feed, sub-feeds per OS**. The top-level feed contains only metadata and `<feed arch="...">` references. Each sub-feed is published on its own schedule. Use this when upstream releases are staggered per platform, or when the per-platform builds have meaningfully different versioning.

Both are illustrated below.

## Single feed, per-arch implementations

This is the simplest pattern when upstream ships per-OS archives at the same version. See [CMake's template](https://github.com/0install/apps/blob/master/devel/cmake.xml.template) for a full example; abridged:

```xml
<group license="BSD License (revised)">
  <command name="run" path="bin/cmake"/>
  <command name="run-gui" path="bin/cmake-gui"/>

  <implementation arch="Linux-x86_64" version="{version}" released="{released}" stability="stable">
    <manifest-digest/>
    <archive href="https://cmake.org/files/v{minor-version}/cmake-{version}-Linux-x86_64.tar.gz"
             extract="cmake-{version}-Linux-x86_64"/>
  </implementation>
</group>

<group license="BSD License (revised)">
  <command name="run" path="CMake.app/Contents/MacOS/CMake"/>
  <command name="run-gui" path="CMake.app/Contents/bin/cmake-gui"/>

  <implementation arch="Darwin-*" version="{version}" released="{released}" stability="stable">
    <manifest-digest/>
    <archive href="https://cmake.org/files/v{minor-version}/cmake-{version}-macos-universal.tar.gz"
             extract="cmake-{version}-macos-universal"/>
  </implementation>
</group>

<group license="BSD License (revised)">
  <command name="run" path="bin/cmake.exe"/>
  <command name="run-gui" path="bin/cmake-gui.exe"/>

  <implementation arch="Windows-x86_64" version="{version}" released="{released}" stability="stable">
    <manifest-digest/>
    <archive href="https://cmake.org/files/v{minor-version}/cmake-{version}-win64-x64.msi"
             type="application/x-msi" extract="SourceDir/CMake"/>
  </implementation>
</group>
```

Note how each `<group>` overrides the `<command>` paths to match the platform's binary layout (`bin/cmake`, `CMake.app/Contents/MacOS/CMake`, `bin/cmake.exe`). The `arch` on the `<implementation>` is what 0install uses to pick the right one.

Adding a new version with [0template](../../tools/0template.md) creates a new XML file containing all of these implementations at once.

## Top-level feed with sub-feeds

When per-OS releases are independent, publish a small top-level feed that pulls in OS-specific sub-feeds:

```xml
<?xml version="1.0"?>
<interface xmlns="http://zero-install.sourceforge.net/2004/injector/interface"
           uri="https://example.com/myapp.xml">
  <name>MyApp</name>
  <summary>does something useful</summary>
  <homepage>https://example.com/myapp</homepage>

  <feed src="https://example.com/myapp-linux.xml"   arch="Linux-*"/>
  <feed src="https://example.com/myapp-macos.xml"   arch="MacOSX-*"/>
  <feed src="https://example.com/myapp-windows.xml" arch="Windows-*"/>

  <entry-point binary-name="myapp" command="run"/>
</interface>
```

Each sub-feed is a normal feed of its own, declared `<feed-for interface="https://example.com/myapp.xml"/>`. Users add the top-level feed; 0install fetches whichever sub-feed matches their platform.

This is how the [apps repository](https://github.com/0install/apps) ships [Blender](https://apps.0install.net/gui/blender.xml), [Calibre](https://apps.0install.net/gui/calibre.xml), [Node.js](https://apps.0install.net/javascript/node.xml) and many others. Each sub-feed has its own template and its own [0watch](../../tools/0watch.md) script, so they can be updated independently.

## Choosing between the two

| If upstream releases...                                              | Use                        |
| -------------------------------------------------------------------- | -------------------------- |
| One archive per OS, all at the same version, on one schedule         | Single feed                |
| Per-OS releases on different schedules, sometimes skipping platforms | Sub-feeds                  |
| Different version numbers per OS                                     | Sub-feeds                  |
| You only care about one OS                                           | Single feed (no sub-feeds) |

The two patterns can be mixed: a top-level feed can both reference sub-feeds **and** include cross-platform implementations directly, e.g. for source builds.
