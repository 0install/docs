Decentralised; anyone can distribute software

: The traditional Linux distribution system, using centralised repositories, creates an interesting chicken-and-egg situation: distributions won't package software until it becomes popular, but software won't become popular until it's easy to install.

: You don't need to be blessed by a distribution (or anyone else) to be part of 0install; all you need is a web page.

: It's easier too: you can make a single archive that works on all platforms (for platform-independent applications such as Python or Java programs), or have 0install download the correct archive automatically (for platform-specific binaries).

: See the "[Decentralised Installation Systems](http://osnews.com/story.php/16956/Decentralised-Installation-Systems)" essay on OSNews for a more complete introduction to the subject.

: See the [Packaging Guide](../packaging/index.md) for information on distributing software using 0install.

Security is central

: Security is sometimes seen as the enemy of usability, but we see it as the foundation of a usable system. Good security doesn't just mean not having your data destroyed by viruses - it means freedom to experiment with new software.

: The problems with a centrally-controlled "app store" as the only means to get software are clear to everyone: applications that compete with the store owner's interests are banned. Innovation and competition suffer. But the same effect can be achieved without overt restrictions if getting software from outside the distribution repository is simply too risky for users to consider.

: See the [security page](../details/security.md) for more information about 0install's security features.

You control your own computer

: When you install a package with a traditional installer, you have no way of knowing what it will do. Will it add itself to a menu somewhere? Start a service whenever you turn on the computer? Stop another program from working?

: 0install merely _caches_ programs, each version of each package in its own directory. Changes to the environment, such as adding a menu entry, only happen in response to a deliberate action on your part.

Conflict free

: If two programs want the same version of a library, they'll share it. Otherwise, they'll use separate copies.

: You're free to try the very latest development version of a program (along with all the bleeding-edge libraries it needs) without destabilising the rest of your system. And you can always revert back to an older version... or run old and new versions of the same program side-by-side!

Shared binaries/cache

: 0install supports sharing of binaries (the implementation cache) [between users](../details/sharing.md) and [between virtual machines](../details/virtual-machines.md).

: If one user installs a 200 Mb application, another user can run it without downloading it again. Most packaging systems solve this problem by only allowing root to install software. The systems which don't have this limitation typically end up downloading and storing multiple copies of a program; one for each user. 0install shares downloads (safely) between users.

: With 0install, each user downloads a small _feed file_ which gives the cryptographic digest of the full package. The digest can be used to check that a package already on the computer (downloaded by another user) hasn't been tampered with. Most simply, each user can make a copy of the original download this way (which shares the download but not the disk space). With a special helper, even the disk copies can be safely shared.

Cross-Platform and Cross-Distribution

: A single 0install package can be used across multiple Linux distributions, OS X, Unix and Windows systems (given that the packaged application itself is written to be cross-platform as well).

Automatic updates

: When you run a program and it has been a month since the last check, 0install will quietly check for updates in the background. If any are available, you will be notified.

: The frequency of these checks is configurable, and you can choose not to use the latest version if you prefer.

Binary and source packages

: 0install supports both [compiling applications from source](../tools/0compile/index.md) and downloading binaries.

Native package manager integration

: If you have already installed a package using your distribution's installer then 0install can use that instead of downloading a second copy.

: It can also use use PackageKit to install system packages using the distribution's package manager, if no 0install package is available.

: See the [distro integration page](../details/distribution-integration.md) for more information.

Run without granting root privileges

: When installing a package, most installation systems execute pre- and post-install scripts inside the package as root, giving the package full access to your machine. Even if they didn't, the fact that the package can unpack files to directories such as `/usr/bin` or `/usr/lib` effectively gives them root access anyway.

: By contrast, when 0install installs (caches) a package, it does not run any code from the package and it does not write to any of the traditional software directories like `/usr/bin` or `/usr/lib`. Instead it simply puts each unpacked archive into its own directory.

Run without being root

: Since installing software with 0install does not allow it to affect the system as a whole, there is no need to restrict it to users with root privileges. Therefore, there is no need to give users root privileges just so that they can install software.
