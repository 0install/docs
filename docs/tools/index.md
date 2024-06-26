# Tools overview

This section contains various utility programs that make using Zero Install easier.

## Publishing and updating feeds

[0downstream](http://gfxmonk.net/dist/0install/0downstream.xml)
: **0downstream** can be used to automatically create and update feed files for an existing open source project page on a site like github, pypi or rubygems.org.

[0publish](0publish.md)
: **0publish** edits feed files in place. It loads the file, transforms it in some way (such as setting the release date, or adding a new implementation) and then writes it back out again. If the input file was signed, it will resign it when saving with the same key by default. You can also use it to add a signature, or to change the signing key. It is particularly useful in release scripts.

[0publish-gui](0publish-gui.md)
: **0publish-gui** provides a simple graphical interface for creating and editing these feeds.

[0release](0release/index.md)
: **0release** can be used to make new releases of your software. It handles details such as setting the version number and release date, tagging the release in your version control system and updating your Zero Install feed.

[0repo](0repo.md)
: **0repo** helps you to maintain a repository of 0install software for others to use. It takes a new release generated by e.g. **0template** or **0release** and adds it to its collection. Then it generates a set of static files which you can upload to your web hosting provider.

[mkzero](http://gfxmonk.net/dist/0install/mkzero.xml)
: **mkzero** is a light-weight alternative to 0release. It doesn't support version control integration, tagging, or local feeds. It is useful for quickly publishing simple packages.

[0template](0template.md)
: **0template** generates the XML for one version of a program from a template. It is a useful replacement for **0downstream** for more complex programs (e.g. source code that must be compiled or programs with dependencies).

[0capture](0capture.md)
: **0capture** captures snapshots of system state and diffs them to generate Zero Install [desktop integration](../details/desktop-integration.md).

[pkg2zero](pkg2zero.md)
: **pkg2zero** publishes a Debian or RPM package in a Zero Install feed.

[pom2feed](https://github.com/0install/pom2feed)
: The Zero Install Maven Integration connects the world of Zero Install with Apache Maven. With this project Zero Install gets access to the huge number of Java projects available at Maven Central. This is made possible by two components: the pom2feed-service and the pom2feed-maven-plugin.

## Compiling

[0compile](0compile/index.md)
: **0compile** creates binaries from source code, either for your own use or ready for publishing on the web through Zero Install. It can use Zero Install to download any build dependencies (compilers, header files, build tools, etc). This is useful if there is no binary for your platform, or if you wish to modify the program in some way.

[Make-headers](make-headers.md)
: **Make-headers** is an extremely simple script for creating `-dev` packages (packages containing only header files) from ordinary source releases.

## Testing

[0test](0test.md)
: **0test** runs the self-tests for a given program with various version combinations.

[FeedLint](feedlint.md)
: If you maintain a number of feeds, each with several versions of your programs, how do you know that all the download links are still OK? Run **FeedLint** on your feeds from time-to-time to check.

## Sharing

[0export](0export.md)
: **0export** creates a self-extracting installer for a given program, for distribution on CDs, etc.

[0bootstrap](0bootstrap.md)
: **0bootstrap** creates a native package that installs 0install and a given program

[0mirror](0mirror.md)
: **0mirror** keeps an archive of Zero Install feeds and GnuPG keys and exports them for publishing on a web-server.

[0share](0share.md)
: **0share** allows peer-to-peer sharing of Zero Install packages.

## Security

[ebox](ebox.md)
: **ebox** proof-of-concept sandboxing system.
