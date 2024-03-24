# Templates

This page lists some example code packages which you can use as templates when creating a new program that will be distributed using 0install, or as examples for your own programs.

## C

[GNU-Hello](http://0install.net/tests/GNU-Hello.xml)
: A sample C program built using autoconf and make. Shows how to make a source package that can be used by [0compile](../tools/0compile/index.md) to create a binary release. See [0release: binaries](../tools/0release/compiled-binaries.md) for details.

[hello-scons](https://github.com/0install/hello-scons)
: Another C program, but this time using SCons instead of Make as the build tool. See [0compile: Scons](../tools/0compile/example-scons.md) for details.

[hello-c-cmake](https://github.com/0install/hello-c-cmake)
: A C program built using CMake.

## Java

[hello-java](https://github.com/0install/hello-java)
: A Java program, built using SCons. Gets SCons, the Java JDK and the Java JRE through 0install.

[Maven integration with pom2feed](https://github.com/0install/pom2feed)
: Build your project with Maven, creating a 0install feed for it automatically.

## .NET

[Packaging .NET libraries](examples/dotnet-lib.md)
: This tutorial explains how to create feeds for a .NET application with a dependency on a .NET library using 0publish-gui.

## Python

[hello-python](https://github.com/0install/hello-python)
: A simple Python program. Depends on Python 2.x using 0install. See the [0release tutorial](../tools/0release/index.md) for more information.
