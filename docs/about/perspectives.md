Depending on who you are, you'll be interested in different aspects of Zero Install. Choose the type below that most closely describes you:

[TOC]

# Novice user

Linux distributions come with a lot of general purpose software, but sometimes a program you want isn't there. Maybe it's very new, or very specialised. Zero Install makes it easy for you to use extra software, but only if someone has gone to the trouble of making it available this way.

-   You can get packages from anywhere, not just from your distribution.
-   On the other hand, there's no guarantee that these packages are any good. Be careful.
-   You don't need to enter the root password to install things.
-   Installing programs only affects you, not other users of the computer.

If you know the program you want is available this way, go to the [downloads page](https://0install.net/injector.html) and install the Zero Install package for your distribution.

If you don't have a particular package in mind and you're just browsing, then you've probably come to the wrong place. Only a small number of programs are currently available this way, and we're mainly interested in building the tools that help other people to make packages rather than in making lots of packages ourselves.

# Power user

Power users are probably the ideal audience for Zero Install. Zero Install is all about putting you in control of your computer:

-   You can get packages from anywhere, not just from your distribution.
-   When installing a package, the package can't change your configuration. Packages can't add anything to your boot sequence, for example.
-   Of course, actually _running_ them might do bad things. But Zero Install makes it easy to share programs and libraries between sandboxes.
-   You can upgrade to the latest testing version, or roll back to any previous version of any program or library.
-   You can download binaries or compile from source.

**How do I ...**

... get started?
: Go to the [downloads page](https://0install.net/injector.html) and install the Zero Install Injector package. Then follow the tutorial.

... change to a different version of something?
: Different desktop environments provide different ways to do this, but you can always use the command line. `0launch --list` shows the URI of every program in your cache. `0launch -g URI` opens the GUI for selecting different versions of that program.

... compile from source?
: Open the GUI (see above), right click on the program or library to compile and choose **Compile...** from the menu. See the [0compile page](https://0install.net/0compile.html) for details.

... reinstall something?
: You should never need to reinstall anything. Zero Install puts each package in a read-only cache directory, indexed by a cryptographic digest of the contents. Therefore, reinstalling is guaranteed to have no effect! If you think the cache has got corrupted somehow, you can run `0store audit` to check it. Deleting the program's configuration files may help, though.

# System administrator

Your users want you to install all kinds of odd programs for them, but you know that installing a bad package for one user could mess up all your other users. It's a tough choice. You either risk it, or tell them to install the program in their home directory. But that's hard work for them, and it wastes your disk space and network bandwidth if several users want the same program. Also, they never remember to check for updates.

Zero Install lets your users install programs in a way that's as secure as installing to their home directories, but is easier, allows sharing between users and makes it easy for them to get automatic updates.

**How do I ...**

... get started?
: Go to the [downloads page](https://0install.net/injector.html) and install the Zero Install Injector package. Your users can now install programs to their home directories easily.

... set up sharing of downloads?
: Follow the [setting up sharing instructions](../details/sharing.md). Now when a user installs a program, it ends up in `/var/cache/0install.net` (the sharing instructions explain why this is safe).

... add programs to everyone's desktop?
: You can add launchers to `$PATH` using `0install add`, and you can add menu entries using `0desktop`. Put the scripts or .desktop files in a common directory, just as with any other program. You should set up sharing before doing this, or every user will have to download a separate copy of the program.

... limit which programs can be installed?
: This is not yet supported. Talk to us!

# Software developer

You don't have much free time, and you want to spend as much of it as possible writing code, not hanging around on distribution mailing lists and IRC channels trying to find someone to review the dozens of packages you've had to create. Life's too short for that.

Creating a Zero Install feed for your programs is easy (it's essentially the same information about download locations and dependencies you would put on your web-page anyway, but in a machine-readable form). Then anyone with Zero Install can use your program.

-   Create one package that works everywhere, not one package per Linux distribution.
-   No need to be approved by a distribution before people can start using your programs.
-   Specify dependencies in a distribution-neutral format.
-   Use dependencies which aren't available in all distributions.
-   Provide new versions immediately. No more waiting six months for the distribution's next release before you can get a fix out.

**How do I ...**

... create a feed for my program?
: Follow the [Packaging Guide](../packaging/index.md).

... push updates to my users?
: Users poll for updates (as with RSS), by default once a month. You should initially mark new releases as 'testing' so that only more advanced users (who know how to roll back to previous versions if something goes wrong) get them.

... check that my feed is OK?
: Use [FeedLint](../tools/feedlint.md) to test it.

# Distribution maintainer

Installing anything that's not in your distribution is too hard for most of your users, so you've had to package up thousands of programs with minimal testing. Keeping on top of security updates and new releases is taking up all your time, and release testing drags on for months as you try to find a set of versions that supports all packages simultaneously.

-   Instead of doing everything yourself, let the upstream maintainers do most of the work for you. They'll create packages, test them, provide updates and deal with bug reports. Unless you actually want to change something, you don't need to do anything.
-   Dependencies are resolved on a per-program basis. If two programs can use the same version of a library then that's great; they'll share it. If not, then two copies of the library will co-exist. This is all automatic.

**How do I ...**

... override a package's default configuration?
: Put your defaults in `/etc` as normal and the package should pick them up (though it depends on the program, of course).

... override a dependency of a Zero Install package to use my custom packaged library?
: You can register an additional feed in `/usr/share/0install.net`. See [Distribution Integration](../details/distribution-integration.md) for details.

... suggest suitable Zero Install packages for my users?
: See the instructions for administrators above. You just provide launchers. Each program will get installed when the first user runs it.

... sign off upstream versions I've tested?
: This is not yet supported. Talk to us!

In general, we welcome better integration with distributions, so write to our [mailing list](https://0install.net/support.html#lists) and we'll work something out!
