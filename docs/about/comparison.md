# Comparison

There are many ways to get software onto a computer: `apt install`, `winget install`, the Microsoft Store, Flatpak, `npm install`, etc. Zero Install looks superficially similar, but works differently from all of them.

In every one of those systems, **installing is an event that changes your machine**: files land in shared system directories, a database records what is present, and afterwards the program is either "installed" or "not installed".

In Zero Install there is no required install step. [`0install run URI`](../details/cli.md#run) resolves the program's dependencies, downloads whatever is missing into a read-only [cache](../details/cache.md) and runs it, without touching anything outside the cache, running any code from the package, or needing administrator rights. Shortcuts, file associations and `PATH` aliases are available separately, at any time (see [Desktop integration](../details/desktop-integration.md)).

Programs are named by URL rather than by a name in a repository, so there is no central namespace to be admitted to (see [Packaging](../packaging/index.md)). Versions are chosen per program rather than per machine, so there is no such thing as "the installed version" of anything.

## Matrix

This matrix shows some desirable features in a packaging system, and which systems provide them.

:material-check-all:{.green} yes · :material-check:{.yellow} partial or conditional · :material-close:{.red} no

| Feature                            | APT / DNF                                | Flatpak                                   | Snap                              | AppImage                          | winget                                  | Scoop                                | Microsoft Store                       | Homebrew                                  | Zero Install                                  |
| ---------------------------------- | ---------------------------------------- | ----------------------------------------- | --------------------------------- | --------------------------------- | --------------------------------------- | ------------------------------------ | ------------------------------------- | ----------------------------------------- | --------------------------------------------- |
| Non-admins can install software    | :material-close:{.red}                   | :material-check-all:{.green}              | :material-close:{.red}            | :material-check-all:{.green}      | :material-check:{.yellow} Some          | :material-check-all:{.green}         | :material-check-all:{.green}          | :material-check-all:{.green}              | :material-check-all:{.green}                  |
| Supports multiple platforms        | :material-close:{.red} Linux             | :material-close:{.red} Linux              | :material-close:{.red} Linux      | :material-close:{.red} Linux      | :material-close:{.red} Windows          | :material-close:{.red} Windows       | :material-close:{.red} Windows        | :material-check:{.yellow} macOS and Linux | :material-check-all:{.green}                  |
| Dependencies handled automatically | :material-check-all:{.green}             | :material-check:{.yellow} Runtimes        | :material-check:{.yellow} Bundled | :material-check:{.yellow} Bundled | :material-check:{.yellow} Bundled       | :material-check-all:{.green}         | :material-check:{.yellow} Frameworks  | :material-check-all:{.green}              | :material-check-all:{.green}                  |
| Automatic upgrading                | :material-check-all:{.green}             | :material-check-all:{.green}              | :material-check-all:{.green}      | :material-check:{.yellow} Some    | :material-check:{.yellow} On request    | :material-check:{.yellow} On request | :material-check-all:{.green}          | :material-check:{.yellow} On request      | :material-check-all:{.green}                  |
| Libraries shared between programs  | :material-check-all:{.green}             | :material-check:{.yellow} Runtimes        | :material-check:{.yellow} Bases   | :material-close:{.red}            | :material-close:{.red}                  | :material-check:{.yellow}            | :material-check:{.yellow} Frameworks  | :material-check-all:{.green}              | :material-check-all:{.green}                  |
| Downloads shared between users     | :material-check-all:{.green} System-wide | :material-check:{.yellow} System installs | :material-check-all:{.green}      | :material-close:{.red}            | :material-close:{.red}                  | :material-close:{.red}               | :material-check-all:{.green}          | :material-close:{.red}                    | :material-check-all:{.green}                  |
| Multiple versions coexist          | :material-close:{.red}                   | :material-check:{.yellow} Runtimes        | :material-close:{.red}            | :material-check-all:{.green}      | :material-close:{.red}                  | :material-check:{.yellow} Switchable | :material-close:{.red}                | :material-check:{.yellow} Some            | :material-check-all:{.green}                  |
| Digital signatures                 | :material-check-all:{.green}             | :material-check-all:{.green}              | :material-check-all:{.green}      | :material-check-all:{.green}      | :material-check:{.yellow} Hashes        | :material-check:{.yellow} Hashes     | :material-check-all:{.green}          | :material-check:{.yellow} Hashes          | :material-check-all:{.green}                  |
| Decentralised                      | :material-close:{.red}                   | :material-check-all:{.green}              | :material-close:{.red}            | :material-check-all:{.green}      | :material-check:{.yellow} Extra sources | :material-check-all:{.green} Buckets | :material-close:{.red}                | :material-check-all:{.green} Taps         | :material-check-all:{.green}                  |
| Can install systems software       | :material-check-all:{.green}             | :material-close:{.red}                    | :material-close:{.red}            | :material-close:{.red}            | :material-check-all:{.green}            | :material-close:{.red}               | :material-close:{.red}                | :material-close:{.red}                    | :material-close:{.red}                        |
| Roam applications across machines  | :material-close:{.red}                   | :material-close:{.red}                    | :material-close:{.red}            | :material-check:{.yellow} Manual  | :material-close:{.red}                  | :material-close:{.red}               | :material-check:{.yellow} Re-download | :material-close:{.red}                    | :material-check:{.yellow} Automatic, app only |
| Large package ecosystem            | :material-check-all:{.green}             | :material-check-all:{.green}              | :material-check-all:{.green}      | :material-check:{.yellow} ~2000   | :material-check-all:{.green}            | :material-check-all:{.green}         | :material-check-all:{.green}          | :material-check-all:{.green}              | :material-check:{.yellow} ~2000               |

### Explanation of features

Non-admins can install software
: A normal user without administrator/root privileges can install software using this system (without unreasonable extra effort).  
AppImage, Scoop, Homebrew and Zero Install also don't require administrator privileges to set up *themselves*; the other systems require administrator rights to install, or come with the operating system.

Supports multiple platforms
: The same package format and command-line works across multiple operating systems, such as Linux and Windows.

Dependencies handled automatically
: If a program requires some library to function, the system will locate, download and install the library too. "Bundled" means the dependency problem is solved by shipping a copy inside each package instead.

Libraries shared between programs
: If two programs use the same library, the library is only downloaded and stored once. Upgrading a library will benefit all programs that use it.

Downloads shared between users
: If two users install/use the same program, it is only downloaded once and stored once.  
See [Sharing](../details/sharing.md) for how to set this up with Zero Install.

Multiple versions coexist
: Several versions of the same program or library can be present and in use at the same time. This is also what makes a system conflict-free: if program A requires an old version of a library and program B a new one, both can be installed and used at once, and the system will never refuse to install one program because some other program is installed. Systems that bundle their dependencies (see above) avoid such conflicts too, but at the cost of a separate copy per program.

Digital signatures
: Software comes with a digital signature, which is checked automatically by the system. "Hashes" means integrity is verified against a checksum, but the origin of that checksum is not cryptographically established.

Decentralised
: A program packaged for this system can be installed easily, without having to be in some special centralised repository.  
Debian and Fedora allow extra repositories to be added, but this is a manual step, requires root access, and is a considerable security risk. winget supports additional sources, but the default repository is central and moderated.

Roam applications across machines
: The packaging system makes it easy to roam applications across machines. This may or may not include the application's configuration files, and may or may not require manual effort by the user.  
See [Sync](../details/sync.md) for Zero Install's approach.

Large package ecosystem
: The system is widely adopted.  
Zero Install's feed count is an estimate based on the [public mirror](https://roscidus.com/0mirror/).

## By system

### APT, DNF and other distribution package managers

This is Zero Install's closest relative; the differences are mostly about who is in charge.

A distribution package manager owns `/usr`, draws on a set of configured repositories, and keeps one version of each library for the whole machine. Adding a repository is a privileged, all-or-nothing trust decision. The new source can replace any package on the system, and what arrives from it has been packaged and patched by a distribution maintainer rather than by the software's author.

Zero Install inverts each of these. `sudo` is never required, because installing affects only your own cache and there is nothing to protect. Packages come from any URL, so adding a source confers no authority over anything else you have. Upstream publishes directly, for better (fast releases, and one fix benefits everyone) and for worse (no distribution QA, see [Perspectives](perspectives.md)). Any number of versions of a library coexist, so an upgrade never breaks another program. And Zero Install writes only to its cache: it replaces `/usr` and `/opt` for the programs you run through it, and leaves `/etc`, `/var` and your distribution's own files alone.

The two are not mutually exclusive, and Zero Install deliberately does not try to replace yours: if a dependency is already installed as a distribution package, Zero Install will use it rather than downloading a second copy; see [Distribution integration](../details/distribution-integration.md).

What you give up: Zero Install cannot install kernels, drivers or system services, and its ecosystem is a few thousand programs rather than tens of thousands. It is for adding applications to a system your distribution already manages.

### winget

[winget](https://learn.microsoft.com/windows/package-manager/) is Microsoft's package manager for Windows. Some differences between this and Zero Install:

- winget is a front-end for the vendor's existing installer. It downloads an MSI, EXE or MSIX and runs it. Whether that installer needs elevation, uninstalls cleanly, or modifies anything else is the vendor's responsibility. Zero Install unpacks an archive into a read-only cache and runs nothing until you launch the program.
- Uninstalling means deleting a cache directory, rather than relying on the vendor having written correct uninstall logic.

See also [Windows-specific behaviour](../details/windows.md).

### Chocolatey

[Chocolatey](https://chocolatey.org/) is a package manager for Windows, built around PowerShell install scripts. It relies on each package to implement reliable install and uninstall logic itself; Zero Install unpacks an archive into a read-only cache and runs no code from a package until you launch the program.

Chocolatey could in future serve the role of the native package manager on Windows. See [Distribution integration](../details/distribution-integration.md).

### Scoop

[Scoop](https://scoop.sh/) unpacks portable Windows applications into a per-user directory (`~\scoop\apps\<app>\<version>`) and puts small shim executables on `PATH`. Of the Windows systems here it is the closest to Zero Install: no administrator rights, no vendor installers, declared dependencies that are fetched automatically, and third-party buckets that make it genuinely decentralised. Some differences:

- Applications are named by bucket and short name, so names have to be unique within a bucket and the same name means different things in different buckets. Zero Install names programs by URL, which is globally unique without anyone curating a namespace.
- Old versions stay on disk, but only one is active at a time via the `current` junction; switching is a machine-wide action (`scoop reset`). With Zero Install different programs can use different versions of the same dependency simultaneously, and a version is chosen per launch rather than per machine.
- Manifests carry a checksum, but buckets are unsigned git repositories, so what the checksum attests to is whoever could push to the bucket. Zero Install feeds are GPG-signed and the digest covers the unpacked contents, which is what allows the cache to be [shared](../details/sharing.md) between users; Scoop's install directory and download cache are per-user.
- Many manifests include `installer`, `pre_install` or `post_install` PowerShell that runs while installing. Zero Install runs no code from a package until you launch the program.

### Microsoft Store

The [Microsoft Store](https://apps.microsoft.com/) distributes MSIX-packaged applications on Windows. The store model and the Zero Install model disagree above all about **who decides what you may run**:

- A store is a single gatekeeper: publishing requires acceptance, and removal cuts off existing users. Zero Install has no party in that position: a feed is a signed XML file on a web server, and anyone can host one. See [Features](features.md).
- Applications are identified by a publisher certificate and store identity, rather than by a URL the author controls.
- Roaming means signing in on another machine and reinstalling. Zero Install's equivalent is [Sync](../details/sync.md), which roams your list of applications (not their data).

Per-user installation without administrator rights is one thing the two approaches genuinely have in common. Where a store relies on its sandbox, Zero Install relies on GPG signatures over feeds and cryptographic digests over contents; see [Security](../details/security.md).

### Flatpak

[Flatpak](https://flatpak.org/) distributes sandboxed desktop applications for Linux, built against shared runtimes and delivered as OSTree repositories. Some differences between this and Zero Install:

- Dependencies below the runtime boundary are shared; everything above it is bundled into the application. Zero Install has no such boundary: every dependency is an independent feed, downloaded once and shared by every program that can use it.
- A runtime is selected by name and branch. Zero Install resolves version constraints across the whole dependency graph with a [SAT solver](../developers/solver.md).
- Flatpak is decentralised in principle, but Flathub is in practice where software comes from. Zero Install has no equivalent; the closest thing, the [mirror](../tools/0mirror.md), is an optional fallback rather than a source of truth.

### Snap

[Snap](https://snapcraft.io/) packages applications as compressed images mounted at run time, managed by the `snapd` daemon. Some differences between this and Zero Install:

- The Snap Store is the only source; `snapd` has no supported mechanism for third-party stores at all, not merely an unused one. Zero Install has no central source.
- Updates are applied automatically and are difficult to decline. Zero Install lets you [pin versions and control update frequency](../details/policy-settings.md), and always lets you go back to an older version.

### AppImage

[AppImage](https://appimage.org/) packages Linux applications into self-contained, single-file executables. Some differences between this and Zero Install:

- Signature checking, update checking and sharing between users are properties of each individual app rather than of the system.
- Automatic updating is implemented by an [additional tool](https://github.com/AppImage/AppImageUpdate) and only supported for AppImages that contain the required metadata. However, unlike Zero Install, it supports delta updates.

For projects that do not provide official cross-distribution builds, AppImages are actually good candidates for being published via Zero Install. They can easily be referenced using the `<file>` [retrieval method](../specifications/feed.md#retrieval-methods) with `executable='true'`.

### Homebrew

[Homebrew](https://brew.sh/) installs software into a prefix owned by the user, on macOS and Linux. Of all the systems here it overlaps with Zero Install the most: user-level installs, no admin rights, a cache, real dependency resolution, and third-party taps that make it genuinely decentralised. Some differences:

- All packages share one prefix, with symlinks into `bin`, `lib` and so on. This means packages can conflict, and only one version of a formula is normally active (`keg-only` and versioned formulae are the exceptions).
- Because the prefix is owned by one user, downloads cannot be safely shared between mutually untrusting users. Zero Install's cache is keyed by a digest of the contents, which makes [sharing](../details/sharing.md) safe.
- Formulae are Ruby scripts that execute during installation. Zero Install runs no code from a package while caching it.

### Language package managers (npm, pip, Maven, NuGet)

These resolve dependencies from a central registry into a cache or a per-project directory. Zero Install can fill the same role as a build-time dependency fetcher (see [Library mode](../details/library-mode.md) and [0compile](../tools/0compile/index.md)) with some differences:

- They are tied to one language and one mechanism for exposing dependencies (the `CLASSPATH`, `sys.path`, `node_modules`). Zero Install feeds can depend on programs and libraries of any kind, exposed through [bindings](../specifications/feed.md#bindings).
- Coordinates are names in a flat, centrally-operated namespace, which is why a central registry is required to avoid conflicts. Zero Install coordinates are URLs, so they are globally unique without anyone operating a registry.
- Project files such as `pom.xml` or `package.json` are not signed, and the coordinates in them do not pin a digest. Registries publish checksums and, in Maven Central's case, signatures alongside artefacts, but checking them is opt-in tooling rather than a property of the dependency declaration. A Zero Install feed is GPG-signed and carries the expected digest of each implementation, which is what allows downloads to be shared safely between users.
- The cache is per-project or per-user rather than a verified, machine-wide store.

### Nix

[Nix](https://nixos.org/) is a purely functional package manager. Each version of a package has its own directory, and "upgrading" creates a new directory rather than modifying the existing one. Unlike Zero Install, however, whether a package is installed affects the behaviour of the system: running `firefox` when Firefox isn't installed produces an error in Nix, whereas Zero Install fetches it first and then continues. In other words, installation has side-effects in Nix.

The most important difference is what the digest covers. The Nix hash is a hash of the _inputs_ used to build the package; the Zero Install hash is a hash of the _resulting binary_. Nix does this to support binaries that hard-code their own paths, since the final hash needs to be known at compile time; Zero Install doesn't allow binaries to include hard-coded paths. The consequence is that Nix cannot verify that a binary in the store is what it claims to be without rebuilding it, so a shared store requires its users to trust each other, whereas Zero Install can always verify and so supports [sharing](../details/sharing.md) between mutually untrusting users.

Building a Nix package involves creating a "Nix expression" in a custom functional language, filling the same role as a Zero Install source feed. Nix also treats configurations as packages, which Zero Install does not attempt at all. And while Zero Install is aimed at adding applications to an existing system, Nix aims to manage the whole system; its packages have short names (like `perl`) rather than URIs, and thus assume a centrally-curated set of package definitions.
