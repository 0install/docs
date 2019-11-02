title: Zero Install for Linux

The Linux version and [Windows version](windows.md) of Zero Install share the same [feed format](../specifications/feed.md). For most common use-cases they behave identically on the command-line. There are however some [OS-specific differences](os-differences.md).

[TOC]

# Generic binaries

You can download download various distribution-specific packages as well as generic binaries [here](https://0install.net/injector.html#linux).

You may need to install `libcurl3` before using the generic binaries, e.g. with `apt-get install libcurl3` on Ubuntu or `pacman -Sy libcurl-compat` on Arch Linux.

Once you have downloaded an archive with pre-compiled binaries, unpack it and `cd` into the newly created directory.

To install for all users on the system (with root access):

```shell
$ sudo ./install.sh local
```

To install only for the current user (without root access):

```shell
$ ./install.sh home
$ export PATH=$HOME/bin:$PATH
```

You can also just run `./files/0install` directly, but  some features won't work unless `0install` is in `PATH`.

# From source

You can download the 0install source code for releases versions from the [GitHub Releases page](https://github.com/0install/0install/releases).

Alternatively you can get the latest development version using Git:

```shell
git clone https://github.com/0install/0install.git
```

To install for all users on the system (with root access):

```shell
$ make
$ sudo make install
```

To install only for the current user (without root access):

```shell
$ make && make install_home
$ export PATH=$HOME/bin:$PATH
```
