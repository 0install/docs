title: Zero Install for macOS

The [Linux version](linux.md) and macOS version of Zero Install share the same source code but the installation works slightly differently.

[TOC]

# Installation

The easiest way of installing Zero Install and its requirements is by using a package manager, to handle building both it and the dependencies for you. 

You can download a package for macOS [here](https://0install.net/injector.html#mac).

# From source

Install the Developer Tools, if needed:

```shell
$ xcode-select --install
```

Install homebrew using git, if needed:

```shell
$ git clone https://github.com/Homebrew/homebrew.git
$ export PATH=$PWD/homebrew/bin:$PWD/homebrew/sbin:$PATH
```

Install build dependencies:

```shell
$ brew install pkg-config
$ brew install gettext
```

Install GnuPG (used by 0install to check the digital signatures):

```shell
$ brew install gnupg
or
$ brew install gnupg2
```

Optionally, install gtk+ (2.12 or later needed for GUI).:

```shell
$ brew install gtk+
$ brew install gtk-engines            # for the "Clearlooks" theme
```

Fix homebrew shortcomings:

```shell
  # the gettext-tools are not linked with homebrew:
$ export PATH="`brew --prefix gettext`/bin:$PATH"
  # the libpng library is not found by homebrew:
$ export PKG_CONFIG_PATH="`brew --prefix libpng`/lib/pkgconfig:$PKG_CONFIG_PATH"
```

Use the Clearlooks theme:


```shell
$ export GTK2_RC_FILES="`brew --prefix`/share/themes/Clearlooks/gtk-2.0/gtkrc"
$ export GTK_PATH="`brew --prefix`/lib/lib/gtk-2.0"
```

Install OCaml and OPAM:

```shell
$ brew install ocaml
$ brew install opam                   # OS X Mavericks or later
or
$ brew install opam --without-aspcud  # OS X Mountain Lion or lower
```

Initialize the OPAM root, if needed:

```shell
$ export OPAMROOT=$PWD/opamroot
$ opam init
```

Install OPAM packages:

```shell
$ eval `opam config env`
$ opam install yojson xmlm ounit react lwt extlib ocurl sha
  # optional, for GUI
$ opam install lablgtk
```

Finally, install 0install itself.

```shell
$ git clone https://github.com/0install/0install.git
$ cd 0install
$ make && make install_home
$ export PATH=$HOME/bin:$PATH
```
