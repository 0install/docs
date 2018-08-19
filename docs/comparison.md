# Comparison

This matrix shows some desirable features in a packaging system, and shows which systems provide them. Obviously, these things tend to be a bit biased (both in terms of what features are chosen for comparison, and of what is considered to be a 'pass') but it should give the general idea.

| Feature                            | Source tarball | [APT](https://wiki.debian.org/Apt) | [Listaller](http://listaller.tenstral.net/) | [Java WS](http://java.sun.com/products/javawebstart/) | Zero Install              |
|------------------------------------|----------------|------------------------------------|---------------------------------------------|-------------------------------------------------------|---------------------------|
| Users can install software         | Yes            | No                                 | Yes                                         | Yes                                                   | Yes                       |
| Dependencies handled automatically | No             | Yes                                | Distro only                                 | Yes                                                   | Yes                       |
| Distro only                        | No             | Yes                                | Yes                                         | Yes                                                   | Yes                       |
| Automatic upgrading                | Yes            | Yes                                | Distro only                                 | Yes                                                   | Yes                       |
| Libraries shared between programs  | No             | No user downloads                  | No                                          | No                                                    | [Yes](details/sharing.md) |
| Multiple versions coexist          | Yes            | No                                 | No                                          | No                                                    | Yes                       |
| Uninstall                          | Sometimes      | Yes                                | Yes                                         | (cache)                                               | (cache)                   |
| Digital signatures                 | No             | Yes                                | Yes                                         | Yes                                                   | Yes                       |
| Conflict-free                      | No             | No                                 | Yes                                         | Yes                                                   | Yes                       |
| Decentralised                      | Yes            | No                                 | Yes                                         | Yes                                                   | Yes                       |
| Non-root install of system         | Yes            | No                                 | Yes                                         | Yes                                                   | Yes                       |
| All programming languages          | Yes            | Yes                                | Yes                                         | Only Java                                             | Yes                       |
| Supports sandboxing                | No             | No                                 | Yes                                         | Yes                                                   | Yes                       |
| Usable when off-line               | Yes            | Yes                                | Yes                                         | Yes                                                   | Yes                       |
| Thousands of packages available    | Yes            | Yes                                | No                                          | Unknown                                               | ~1500                     |

## Explanation of features

Users can install software

: A normal user without special privileges can install software using this system (without unreasonable extra effort).

Dependencies handled automatically

: If a program requires some library to function, the system will locate, download and install the library too.  
Listaller packages can depend on distribution-provided packages but not on other Listaller packages.

Automatic upgrading

: The system can check for and install upgrades automatically or at the operator's request. User does not have to perform a full install operation manually on each package.

Libraries shared between programs

: If two programs use the same library, the library is only downloaded and stored once. Upgrading a library will benefit all programs that use it.

Downloads shared between users

: If two users install/use the same program, it is only downloaded once and stored once.  
See [Sharing](details/sharing.md) for how to set this up with Zero Install.

Multiple versions coexist

: Two versions of a program or library can be installed at the same time, and the user can choose which one to run.

Uninstall

: Programs can be cleanly removed from the system easily (reversing the effects of the install).

Signatures

: Software comes with a digital signature, which is checked automatically by the system.  
Note: Java Web-Start requires all components to be signed with the same key.

Conflict-free

: If program A requires an old version of a library, and program B requires a new version, A and B can both be installed and used at the same time. The system will never refuse to install one program because some other program is installed. Listaller and 0install can both depend on native distribution packages, which may conflict, but their own packages do not. 0install will avoid such problems as long as a suitable version of the dependency is also available as a 0install package.

Decentralised

: A program packaged for this system can be installed easily, without having to be in some special centralised repository.  
Notes: Debian allows extra repositories to be added, but this is a manual step, requires root access, and is a considerable security risk.

Non-root install of system

: The packaging system itself can be easily installed without administrator privileges, and the normal selection of software will be available.

All programming languages

: All types of program can be accessed using this system.

Supports sandboxing

: If you have a way of running an application in a sandboxed environment (e.g., a Java virtual machine), then the installation system will let you install and run the program without forcing you to run any of the downloaded code outside of the sandbox.  
See the [EBox sandboxing demo](tools/ebox.md) for an example of using 0install in this way.

Usable when off-line

: Once a program has been installed, the program can be run again while disconnected.

Thousands of packages available

: The system is widely adopted.

## By project

### Java Web Start

Sun have developed a similar system to Zero Install, [Java Web Start](http://java.sun.com/products/javawebstart), although this only works for Java applications. Microsoft have an equivalent called [ClickOnce](http://msdn2.microsoft.com/en-us/library/t71a733d(VS.80).aspx).

### Konvalo

Konvalo was a very similar idea to the old Zero Install filesystem, but implemented using CODA rather than with a custom kernel module.

One disadvantage of Konvalo was that you needed to run a public CODA server to distribute software, whereas both Zero Install implementations only require a web-server serving static pages.

The project did not attract support from the community and the author abandoned the effort in April 2006, asking for links to it from this site to be removed.

### Klik

[Klik](http://en.wikipedia.org/wiki/Klik_%28packaging_method%29) allows users to install software by clicking on links in web-pages (or even just by looking at a web page). Like Zero Install, it stores each package in its own directory and sets environment variables to let it run. There is a central server which sends shell scripts to clients; executing the script causes the software to be downloaded and installed. This process is started automatically by your web browser.

Klik is mainly focused on having a large selection of packages working now, but pays little attention to security. Klik packages can be automatically converted to Zero Install ones. See my [article about Zero Install and Klik](http://rox.sourceforge.net/desktop/node/290) for more details.

Differences between Klik and Zero Install include:

- The recipes Zero Install downloads are XML files, not shell scripts (this is similar to the difference between tar and shar files).
- Zero Install downloads are GPG signed.
- Zero Install is decentralisedyou need permission from the Klik maintainers to distribute a Klik package. You don't need anyone's permission to distribute with Zero Install.
- Zero Install can check for updates automatically (by default once a month, but configurable). Klik requires you to check for updates yourself.
- Zero Install handles dynamic linking. This allows upgrading a library to benefit multiple applications, and saves space and bandwidth. It also lets you use a different version of a library if you want. Klik bundles all dependencies into the package (Zero Install also [supports](http://rox.sourceforge.net/desktop/Zero2Bundle) this mode of operation if desired).
- Zero Install can [share downloads](details/sharing.md) between users safely, using cryptographic digests.
- Zero Install supports compiling from source if you want, though not all packages support this yet.
- Zero Install lets you download older versions of programs or libraries. Klik only provides a single version for download, although you can keep using older versions once you've got them.
- Zero Install is fully OSS. Only the Klik client is.
- Klik runs the 'intellipatch' script on downloads, which does a search-and-replace for filename paths in binaries (simple text matching) and changes them. This allows some programs which aren't built to be binary relocatable to work. Zero Install doesn't support this, and requires binaries to be relocatable in the first place.
- Klik has many more packages available right now.
- Zero Install is platform independent. Klik only runs on Linux.

The above refers to Klik 1, which is no longer available. Klik 2 was never released. The successor to Klik 2, [portablelinuxapps.org](http://portablelinuxapps.org) is not working (as of 2011-08-22).

### Maven

[Maven](http://maven.apache.org/) is a build tool (like make or ant) for Java programs. Although not an installation system, it is similar to 0install in that each product has a **pom.xml** file with a list of dependencies. When building a product, Maven downloads the specified version of each dependency and stores it in a cache directory. Some differences between Maven 2.0 and 0install:

- The **pom.xml** files are not signed. An attacker can therefore cause modified POM files to be downloaded.
- There is no digest of the downloads in the POM file, so no security checks are performed to confirm that the download is OK, and downloads cannot be shared safely between users.
- Only Java is supported (everything is added to CLASSPATH, nowhere else).
- Dependencies are named using a simple two-layer system (e.g., axis/axis-jaxrpc). Therefore, a central repository is required to avoid naming conflicts.

Note that you can use Zero Install in a maven-like way for compiling programs. See [Easy GTK binary compatibility](http://rox.sourceforge.net/desktop/node/289) for an example of using Zero Install to compile a C program against an older version of a library's header files to ensure greater compatibility.

### Autopackage / Listaller

Like Zero Install, [Autopackage](http://autopackage.org/) aims to let users install software and to make software distribution decentralised. The work done by the Autopackage developers to make packages relocatable is necessary for Zero Install too. Some differences between this and Zero Install:

- A script inside each package installs the files, making sandboxing difficult. It also [makes conversion to other packaging formats troublesome](http://www.kitenet.net/~joey/blog/entry/autopackage_designed_by_monkeys-2005-03-28-14-20.html).
- Security features such as GPG signatures have not been implemented. Given that packages are executable files, the design doesn't seem to allow this to be fixed.
- Downloads cannot be safely shared between users.
- No checking for updates or support for multiple versions.
- Being closer to traditional installation, it's easier to package existing applications with Autopackage.

Note that it is quite possible to list autopackages in a Zero Install feed, as described in [this post on the Autopackage mailing list](http://thread.gmane.org/gmane.comp.autopackage.devel/5733/focus=5733). In this case, no scripts are run during installation (the package is treated as a normal archive), so not all packages will work, but many do.

Autopackage is no longer maintained, but has merged with the [Listaller project](http://listaller.tenstral.net/). The Listaller project has also taken over the [tools for making relocatable applications](http://listaller.tenstral.net/docs/doc/app-development.html), which may be useful for making 0install packages too.

### EDOS / Mancoosi

The [EDOS](http://www.edos-project.org/xwiki/bin/Main/) (_Environment for the development and Distribution of Open Source software_) project was a research project looking at dependency management, QA, and efficient distribution of large software systems.

[Mancoosi](http://www.mancoosi.org/) is a follow-on project ("Managing the Complexity of the Open Source Infrastructure"). The group invited me to give a talk (March 2009); here are [my notes](http://thread.gmane.org/gmane.comp.file-systems.zero-install.devel/2322) from the event.

### Nix

[Nix](http://nixos.org/) is a purely functional package manager. Each version of a package has its own directory. As with Zero Install, "upgrading" creates a new directory for the new version, rather than modifying the existing one. Unlike Zero Install, however, whether a package is installed affects the behaviour of the system. For example, running "firefox" when Firefox isn't installed produces an error in Nix, whereas in Zero Install it will install Firefox first if missing and then continue. In other words, installation has side-effects in Nix.

Additional feeds (e.g. for pre-built binaries) can be registered using `nix-channel --add`, which appears to work much like `0launch --feed`, although each channel can contain binaries for multiple packages. The channel `MANIFEST` file doesn't appear to have a digital signature. Presumably this will be added at some point.

Each version of a package has a digest (hash), which includes all build dependencies (e.g. the version of the compiler used), just as it does in Zero Install (for packages built using 0compile, at least).

An important difference between the two is that the Nix hash is a hash of the _inputs_ used to build the package, whereas the Zero Install hash is a hash of the _resulting binary_. Nix does this to support binaries that hard code their own paths, since the final hash needs to be known at compile time. For source (non-compiled) packages, the Nix hash is a hash of the contents, as with Zero Install. The Zero Install hash often happens to include the inputs, since it covers the `build-environment.xml` file which 0compile places in each binary package. Zero Install doesn't allow binaries to include hard-coded paths.

UpdateNix is planning to use binary hashes everywhere in future (zeroing out self-references for the purposes of calculating the hashes). The same thing was proposed a few years ago for Zero Install (the [relocation table](http://thread.gmane.org/gmane.comp.file-systems.zero-install.devel/882/focus=882)). It relies on the cache directory being at a fixed location, whereas people often have Zero Install set up to use their home directory, but it's basically a good idea.

Another difference between Nix and Zero Install is that Nix treats configurations as packages. Changing your configuration is like "upgrading" your configuration package to a new version. Rolling back a change is like reverting to a previous version. Zero Install doesn't generally handle configuration settings, preferring to let the user use subversion (or similar) for that, but it's an interesting idea.

Building a Nix package involves creating a "Nix expression" in a (custom) functional language. The expression fills the same role as a Zero Install source feedit says where to download the source, what its digest is, what the build dependencies are, and how to build it.

While Zero Install is mainly targeted at adding additional packages to an existing system, Nix aims to manage the whole system (although it installs cleanly alongside your existing package manager). Nix packages have short names (like `perl`) not full URIs, and thus it appears to assume a centrally-controlled repository.

In Nix, mutually untrusting users cannot share packages. The manual says A setuid installation should only by used if the users in the Nix group are mutually trusted, since any user in that group has the ability to change anything in the Nix store. Because the Nix hash is a hash of the inputs, it is not possible for the system to verify that a package is valid (it would have to download the sources and compile the program itself; Nix can share binaries in this case). Because Zero Install hashes are always hashes of the package contents, it does support [sharing](details/sharing.md).

### OSTree

[OSTree](https://live.gnome.org/OSTree) describes itself as "git for operating system binaries". It shares many goals with 0install (multiple versions of libraries can coexist on one system and you can roll-back easily). While 0install focuses on applications and their libraries, OSTree focuses on the OS itself. However, there is quite a bit of overlap. For example, OSTree considers GTK+ to be an OS library, while 0install might consider it to be an application dependency (which can optionally, of course, be provided by the OS).

### Glick 2

[Glick 2](http://people.gnome.org/~alexl/glick2/) has essentially the same goals as 0install, but includes all dependencies in a single bundle rather than linking libraries dynamically at run-time (for example, when a library is updated, every program using that library must be updated individually). It has support for non-relocatable applications, using some Linux-specific tricks. It might be worth using these in 0install to implement the `<mount-point>` binding, but few applications are non-relocatable these days.

### DOAPDescription of a Project

[DOAP](http://usefulinc.com/doap/) is a project to create an XML/RDF vocabulary to describe open source projects. We should investigate whether any of these elements would be useful in Zero Install feed files.

### Environment modules

The [Environment Modules](http://modules.sourceforge.net/) package provides for the dynamic modification of a user's environment via modulefiles. Each modulefile contains the information needed to configure the shell for an application. Typically modulefiles instruct the module command to alter or set shell environment variables such as `PATH`, `MANPATH`, etc. To be able to load ("install") software, it must first be installed under the `$MODULESHOME` directory which is in `/usr/local/Modules` or a shared network filesystem. It is also possible to install it in `~/.local` without root permissions, but then the modules can't be shared (due to different `$HOME`).

The module(1) command doesn't provide a method to share or distribute the applications, so modulefiles typically take advantage of transparent remote network filesystem access such as NFS and AFS. 0install can also be used in this way, with [local feeds](packaging/local-feeds.md) taking the place of the modulefiles and giving the path of the software on the network file-system rather than a URL from which it can be downloaded.

If you believe that any of the information above is inaccurate or out-of-date, please write to [mailing list](http://0install.net/support.html#lists) to let us know. Thanks!
