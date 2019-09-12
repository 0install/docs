title: Overview

To make software available through 0install, you need to publish a signed "feed" XML file on your web page. This file lists the available versions and their dependencies and says how to run the program. There are lots of ways to create this XML file.

To see an example of the XML, go to any 0install program's feed in your browser (e.g. [0export](http://0install.net/tools/0export.xml)) and _View Page Source_.

Before you start, have a look at [Concepts](concepts.md) to make sure you understand some key terms and ideas.

**I want to...**

## Publish an XML file for an existing binary release

- Read the [binary packaging guide](guide-gui.md). This tutorial shows how to create an XML file describing the Blender 3D-animation application. The binary archive is published by the upstream authors and requires no modifications.

## Publish XML for a source release

- Start by reading the [binary packaging guide](guide-gui.md). Most of the steps are the same.
- Read the [0compile user guide](../tools/0compile/index.md) to understand how users compile 0install software.
- Read the [0compile developer guide](../tools/0compile/developers.md) for a tutorial showing how to publish the GNU Hello example package.

## Create an XML file describing my own software

- Read the [0release](../tools/0release/index.md) documentation, which shows how to add a [local feed](local-feeds.md) to your source repository. Users can use this to run your program from a Git checkout, and you can use it to generate new releases automatically.
- Have a look at the [template projects](templates.md) for examples in various programming languages (Python, Java, .NET, C).

# Other useful documentation

[Feed specification](../specifications/feed.md)
: The specification of the XML format.

[Templates](templates.md)
: Sample code packages which you can use as templates when creating a new program that will be distributed using 0install, or as examples for your own programs.

[Tools](../tools/index.md)
: An index of the tools provided by the 0install project for generating feeds.

## Articles

[Binary distribution with 0install](https://opam.ocaml.org/blog/0install-intro/)
: Blog article describing how to make packages using the [0template](../tools/0template.md) command-line tool.

[Compiling with SCons and GTK](http://rox.sourceforge.net/desktop/node/300)
: Article showing how to use Zero Install in your build scripts to download the SCons build system and use it to compile your program.

[Easy GTK binary compatibility](http://rox.sourceforge.net/desktop/node/289)
: This blog article shows how to use Zero Install to compile your program against older versions of library headers than are the default on your system. Binaries created this way work on a wider range of systems (all systems with a GTK version newer than the headers). Also, since they download the required headers automatically, users don't need to have the headers already on their system in order to compile your program. The binaries produced this way do not depend on Zero Install, so you can use Zero Install as part of your build process even if you don't distribute the resulting binaries that way.
