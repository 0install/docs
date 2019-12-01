title: Using Git

[TOC]

## Testing developer versions using Git

If you want to work on the code, or test a fix that has just been made, you'll want to get the latest developer version. We use Git for version control, so make sure you have that. You'll also need '[gettext-tools](http://www.gnu.org/software/gettext/)' to build the translations.

To install these on Ubuntu, open a terminal emulator and run this command:

```shell
$ sudo apt-get install git gitk gettext
```

(gitk is a largish visualisation tool and is not strictly necessary, but highly recommended)

Click on the **SCM** link on any program's page to see its Git page (for example, [0install.git](https://github.com/0install/0install) for 0install itself). The link for cloning is displayed there; use it like this:

```shell
$ git clone https://github.com/0install/0install.git
$ cd 0install
```

The directory contains the latest version, plus a single (hidden) .git directory with all the git-related bits.

To see the log:

```shell
$ git log
```

This doesn't require network access; your clone has the whole history.

To view a visualisation of the history:

```shell
$ gitk --all
```

(`--all` shows the history of all branches and tags, not just the main trunk)

## Fetching updates

To download the latest updates into your copy:

```shell
$ git pull --rebase
```

(The `--rebase` option says that if you've committed some changes locally, they should be reapplied on top of the latest version. Otherwise, it would create a merge commit, which is usually not what you want.)

You can also pull from other places. If someone posts to the mailing list, they will tell you where to pull from to try the feature out. If they send a patch, you can apply it with:

```shell
$ git am the.patch
```

## Understanding the OCaml code

Most modules have two files - a `.ml` file containing the implementation and a `.mli` file describing the module's public interface. You should always start by reading the `.mli` file. [sigs.mli](https://github.com/0install/0install/blob/master/ocaml/zeroinstall/sigs.mli) describes several abstract interfaces used in the code.

[Thomas Leonard's blog](http://roscidus.com/blog/blog/archives/) has many blog posts describing various aspects of 0install. For example, [Simplifying the Solver With Functors](http://roscidus.com/blog/blog/2014/09/17/simplifying-the-solver-with-functors/) explains how 0install chooses a compatible set of libraries to run a program, while [Asynchronous Python vs OCaml](http://roscidus.com/blog/blog/2013/11/28/asynchronous-python-vs-ocaml/) describes the code for downloading things.

## Making patches

If you've changed the code in some way then you can commit the changes like this (this just stores them on your own computer, in the `.git` sub-directory).

```shell
$ git commit -a
```

Enter a log message. The first line should be a short summary (like the subject of an email). Then leave a blank line, then write a longer description.

To view your patch after committing:

```shell
$ git show
```

If you realised you made a mistake, correct it and then do:

```shell
$ git commit -a --amend
```

Finally, to make a patch file ready to send to the [mailing list](https://0install.net/support.html#lists):

```shell
$ git format-patch origin/master
```

## Making a new translation

!!! note
    Translations are not currently working - see [Gettext support in OCaml](http://stackoverflow.com/questions/26192129/gettext-support-in-ocaml).

!!! note
    If you prefer, you can also use the [Transifex web interface](https://www.transifex.net/projects/p/0install/) to work on translations.

The steps are:

1.  Create the `.pot` (`.po` template) file.
2.  Create a new directory `share/locale/_$locale_/LC_MESSAGES` inside the Git checkout.
3.  Copy the `.pot` file inside it with a `.po` extension.

e.g. to make a French translation:

```shell
$ make share/locale/zero-install.pot
$ mkdir -p share/locale/fr/LC_MESSAGES
$ cp share/locale/zero-install.pot share/locale/fr/LC_MESSAGES/zero-install.po
```

Then edit the `.po` file to give a translation for each string. When you're done, create the `.mo` file from the `.po` file and test:

```shell
$ make translations
$ ./0launch
```

Finally, [send us](https://0install.net/support.html#lists) the new `.po` file.
