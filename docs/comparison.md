# Comparison

## Matrix

This matrix shows some desirable features in a packaging system, and shows which systems provide them. Obviously, these things tend to be a bit biased (both in terms of what features are chosen for comparison, and of what is considered to be a 'pass') but it should give the general idea.

| Feature                            | Source tarball                      | APT                                      | AppImage                                     | Chocolatey                               | PortableApps.com                             | Zero Install                                  |
| ---------------------------------- | ----------------------------------- | ---------------------------------------- | -------------------------------------------- | ---------------------------------------- | -------------------------------------------- | --------------------------------------------- |
| Users can install software         | :material-check-all:{.green} Yes    | :material-close:{.red} No                | :material-check-all:{.green} Yes             | :material-check:{.yellow} Some packages  | :material-check-all:{.green} Yes             | :material-check-all:{.green} Yes              |
| Supports multiple platforms        | :material-check-all:{.green} Yes    | :material-close:{.red} No (Linux only)   | :material-close:{.red} No (Linux only)       | :material-close:{.red} No (Windows only) | :material-close:{.red} No (Windows only)     | :material-check-all:{.green} Yes              |
| Dependencies handled automatically | :material-close:{.red} No           | :material-check-all:{.green} Yes         | :material-check:{.yellow} Bundled            | :material-check-all:{.green} Yes         | :material-check:{.yellow} Bundled            | :material-check-all:{.green} Yes              |
| Automatic upgrading                | :material-close:{.red} No           | :material-check-all:{.green} Yes         | :material-check:{.yellow} Some packages      | :material-close:{.red} No                | :material-check-all:{.green} Yes             | :material-check-all:{.green} Yes              |
| Libraries shared between programs  | :material-check-all:{.green} Yes    | :material-check-all:{.green} Yes         | :material-close:{.red} No                    | :material-check:{.yellow} Partial        | :material-close:{.red} No                    | :material-check-all:{.green} Yes              |
| Downloads shared between users     | :material-close:{.red} No           | :material-close:{.red} No user downloads | :material-close:{.red} No                    | :material-close:{.red} No                | :material-close:{.red} No                    | :material-check-all:{.green} Yes              |
| Multiple versions coexist          | :material-check-all:{.green} Yes    | :material-close:{.red} No                | :material-check-all:{.green} Yes             | :material-close:{.red} No                | :material-check-all:{.green} Yes             | :material-check-all:{.green} Yes              |
| Uninstall                          | :material-check:{.yellow} Sometimes | :material-check-all:{.green} Yes         | :material-check-all:{.green} Yes             | :material-check:{.yellow} Some packages  | :material-check-all:{.green} Yes             | :material-check-all:{.green} Yes (cache)      |
| Digital signatures                 | :material-close:{.red} No           | :material-check-all:{.green} Yes         | :material-check-all:{.green} Yes             | :material-check-all:{.green} Yes         | :material-close:{.red} No                    | :material-check-all:{.green} Yes              |
| Conflict-free                      | :material-close:{.red} No           | :material-close:{.red} No                | :material-check-all:{.green} Yes             | :material-close:{.red} No                | :material-check-all:{.green} Yes             | :material-check-all:{.green} Yes              |
| Decentralised                      | :material-check-all:{.green} Yes    | :material-close:{.red} No                | :material-check-all:{.green} Yes             | :material-close:{.red} No                | :material-close:{.red} No                    | :material-check-all:{.green} Yes              |
| Non-root install of system         | :material-check-all:{.green} Yes    | :material-close:{.red} No                | :material-check-all:{.green} Yes             | :material-close:{.red} No                | :material-check-all:{.green} Yes             | :material-check-all:{.green} Yes              |
| Can install systems software       | :material-check-all:{.green} Yes    | :material-check-all:{.green} Yes         | :material-close:{.red} No                    | :material-check-all:{.green} Yes         | :material-close:{.red} No                    | :material-close:{.red} No                     |
| Supports sandboxing                | :material-close:{.red} No           | :material-close:{.red} No                | :material-check-all:{.green} Yes             | :material-close:{.red} No                | :material-close:{.red} No                    | :material-check-all:{.green} Yes              |
| Usable when off-line               | :material-check-all:{.green} Yes    | :material-check-all:{.green} Yes         | :material-check-all:{.green} Yes             | :material-check-all:{.green} Yes         | :material-check-all:{.green} Yes             | :material-check-all:{.green} Yes              |
| Roam applications across machines  | :material-close:{.red} No           | :material-close:{.red} No                | :material-check:{.yellow} Manual, app+config | :material-close:{.red} No                | :material-check:{.yellow} Manual, app+config | :material-check:{.yellow} Automatic, app only |
| Thousands of packages available    | :material-check-all:{.green} Yes    | :material-check-all:{.green} Yes         | :material-close:{.red} No                    | :material-check-all:{.green} Yes         | :material-close:{.red} No                    | :material-check:{.yellow} ~1500               |

## Explanation of features

Users can install software
: A normal user without special privileges can install software using this system (without unreasonable extra effort).

Supports multiple platforms
: The same package format and command-line works across multiple operating systems, such as Linux and Windows.  

Dependencies handled automatically
: If a program requires some library to function, the system will locate, download and install the library too.

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

Conflict-free
: If program A requires an old version of a library, and program B requires a new version, A and B can both be installed and used at the same time. The system will never refuse to install one program because some other program is installed.

Decentralised
: A program packaged for this system can be installed easily, without having to be in some special centralised repository.  
Notes: Debian allows extra repositories to be added, but this is a manual step, requires root access, and is a considerable security risk.

Non-root install of system
: The packaging system itself can be easily installed without administrator privileges, and the normal selection of software will be available.

Can install systems software
: The packaging system can be used to install low-level systems software such as device drivers.

Supports sandboxing
: If you have a way of running an application in a sandboxed environment (e.g., a Java virtual machine), then the installation system will let you install and run the program without forcing you to run any of the downloaded code outside of the sandbox.  
See the [EBox sandboxing demo](tools/ebox.md) for an example of using 0install in this way.

Usable when off-line
: Once a program has been installed, the program can be run again while disconnected.

Roam applications between machines
: The packaging system makes it easy to roam applications across machines. This may or may not include the application's configuration files. This may or may not require manual effort by the user, such as setting up an external service like Dropbox.

Thousands of packages available
: The system is widely adopted.

## By project

### AppImage

[AppImage](https://appimage.org/) is a system for packaging Linux applications into self-contained, single-file executables. Some differences between this and Zero Install:

- All dependencies that cannot reasonably be expected to come with all target systems (Linux distributions) in their default installation ("base system") are bundled into a single file and can therefore not be shared between apps.
- Automatic updating is implemented by an [additional tool](https://github.com/AppImage/AppImageUpdate) and only supported for AppImages that contain the required metadata. However, unlike Zero Install, it supports delta updates.
- Only supports Linux.

For projects that do not provide official cross-distribution builds, AppImages are actually good candidates for being published via Zero Install. They can easily be referenced using the `<file>` [retrieval method](specifications/feed.md#retrieval-methods) with `executable='true'`.

### Chocolatey

[Chocolatey](https://chocolatey.org/) is a package manager for Windows. Some differences between this and Zero Install:

- Relies on packages to implement reliable install and uninstall logic themselves. Cannot guarantee conflict-free or side-by-side installation.
- Uses a central, moderated package repository rather than decentralized files on the web.
- Most packages require administrative permissions for installation.
- Only supports Windows.

Chocolatey could in future serve the role of the native package manager on Windows. See [Distribution integration](details/distribution-integration.md).

### PortableApps.com

[PortableApps.com](https://portableapps.com/) is a collection of portable applications for Windows. Some differences between this and Zero Install:

- All dependencies are bundled and can therefore not be shared between apps.
- Only supports Windows.

PortableApps write their config in the same directory as the installed applications. This makes them inherently incompatible with Zero Install, since it requires (and enforces) cached implementations to be read-only.

### Java Web Start

Sun have developed a similar system to Zero Install, Java Web Start, although this only works for Java applications and has been deprecated. Microsoft have an equivalent called [ClickOnce](https://docs.microsoft.com/en-us/visualstudio/deployment/clickonce-security-and-deployment).

### Maven

[Maven](http://maven.apache.org/) is a build tool (like make or ant) for Java programs. Although not an installation system, it is similar to 0install in that each product has a `pom.xml` file with a list of dependencies. When building a product, Maven downloads the specified version of each dependency and stores it in a cache directory. Some differences between Maven 2.0 and 0install:

- The `pom.xml` files are not signed. An attacker can therefore cause modified POM files to be downloaded.
- There is no digest of the downloads in the POM file, so no security checks are performed to confirm that the download is OK, and downloads cannot be shared safely between users.
- Only Java is supported (everything is added to `CLASSPATH`, nowhere else).
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

The EDOS] (_Environment for the development and Distribution of Open Source software_) project was a research project looking at dependency management, QA, and efficient distribution of large software systems.

[Mancoosi](http://www.mancoosi.org/) is a follow-on project ("Managing the Complexity of the Open Source Infrastructure"). The group invited me to give a talk (March 2009); here are [my notes](http://thread.gmane.org/gmane.comp.file-systems.zero-install.devel/2322) from the event.

### Nix

[Nix](http://nixos.org/) is a purely functional package manager. Each version of a package has its own directory. As with Zero Install, "upgrading" creates a new directory for the new version, rather than modifying the existing one. Unlike Zero Install, however, whether a package is installed affects the behaviour of the system. For example, running "firefox" when Firefox isn't installed produces an error in Nix, whereas in Zero Install it will install Firefox first if missing and then continue. In other words, installation has side-effects in Nix.

Additional feeds (e.g. for pre-built binaries) can be registered using `nix-channel --add`, which appears to work much like [`0install add-feed`](details/cli.md#add-feed), although each channel can contain binaries for multiple packages. The channel `MANIFEST` file doesn't appear to have a digital signature. Presumably this will be added at some point.

Each version of a package has a digest (hash), which includes all build dependencies (e.g. the version of the compiler used), just as it does in Zero Install (for packages built using 0compile, at least).

An important difference between the two is that the Nix hash is a hash of the _inputs_ used to build the package, whereas the Zero Install hash is a hash of the _resulting binary_. Nix does this to support binaries that hard code their own paths, since the final hash needs to be known at compile time. For source (non-compiled) packages, the Nix hash is a hash of the contents, as with Zero Install. The Zero Install hash often happens to include the inputs, since it covers the `build-environment.xml` file which 0compile places in each binary package. Zero Install doesn't allow binaries to include hard-coded paths.

Update: Nix is planning to use binary hashes everywhere in future (zeroing out self-references for the purposes of calculating the hashes). The same thing was proposed a few years ago for Zero Install (the [relocation table](http://thread.gmane.org/gmane.comp.file-systems.zero-install.devel/882/focus=882)). It relies on the cache directory being at a fixed location, whereas people often have Zero Install set up to use their home directory, but it's basically a good idea.

Another difference between Nix and Zero Install is that Nix treats configurations as packages. Changing your configuration is like "upgrading" your configuration package to a new version. Rolling back a change is like reverting to a previous version. Zero Install doesn't generally handle configuration settings, preferring to let the user use subversion (or similar) for that, but it's an interesting idea.

Building a Nix package involves creating a "Nix expression" in a (custom) functional language. The expression fills the same role as a Zero Install source feed: it says where to download the source, what its digest is, what the build dependencies are, and how to build it.

While Zero Install is mainly targeted at adding additional packages to an existing system, Nix aims to manage the whole system (although it installs cleanly alongside your existing package manager). Nix packages have short names (like `perl`) not full URIs, and thus it appears to assume a centrally-controlled repository.

In Nix, mutually untrusting users cannot share packages. The manual says A setuid installation should only by used if the users in the Nix group are mutually trusted, since any user in that group has the ability to change anything in the Nix store. Because the Nix hash is a hash of the inputs, it is not possible for the system to verify that a package is valid (it would have to download the sources and compile the program itself; Nix can share binaries in this case). Because Zero Install hashes are always hashes of the package contents, it does support [sharing](details/sharing.md).

### OSTree

[OSTree](https://ostree.readthedocs.io/) describes itself as "git for operating system binaries". It shares many goals with 0install (multiple versions of libraries can coexist on one system and you can roll-back easily). While 0install focuses on applications and their libraries, OSTree focuses on the OS itself. However, there is quite a bit of overlap. For example, OSTree considers GTK+ to be an OS library, while 0install might consider it to be an application dependency (which can optionally, of course, be provided by the OS).

### Glick 2

[Glick 2](http://people.gnome.org/~alexl/glick2/) has essentially the same goals as 0install, but includes all dependencies in a single bundle rather than linking libraries dynamically at run-time (for example, when a library is updated, every program using that library must be updated individually). It has support for non-relocatable applications, using some Linux-specific tricks. It might be worth using these in 0install to implement the `<mount-point>` binding, but few applications are non-relocatable these days.

### DOAPDescription of a Project

[DOAP](https://github.com/ewilderj/doap/wiki) is a project to create an XML/RDF vocabulary to describe open source projects. We should investigate whether any of these elements would be useful in Zero Install feed files.

### Environment modules

The [Environment Modules](http://modules.sourceforge.net/) package provides for the dynamic modification of a user's environment via modulefiles. Each modulefile contains the information needed to configure the shell for an application. Typically modulefiles instruct the module command to alter or set shell environment variables such as `PATH`, `MANPATH`, etc. To be able to load ("install") software, it must first be installed under the `$MODULESHOME` directory which is in `/usr/local/Modules` or a shared network filesystem. It is also possible to install it in `~/.local` without root permissions, but then the modules can't be shared (due to different `$HOME`).

The module(1) command doesn't provide a method to share or distribute the applications, so modulefiles typically take advantage of transparent remote network filesystem access such as NFS and AFS. 0install can also be used in this way, with [local feeds](packaging/local-feeds.md) taking the place of the modulefiles and giving the path of the software on the network file-system rather than a URL from which it can be downloaded.

If you believe that any of the information above is inaccurate or out-of-date, please write to [mailing list](https://0install.net/support.html#lists) to let us know. Thanks!
