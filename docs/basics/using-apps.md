# Using apps

Note: This is currently not supported in Zero Install for Windows. Please see [Desktop integration](windows.md) for a functionally similar replacement.

Apps (introduced in 0install 1.9) work a bit like aliases, e.g.

```shell
$ 0install add rox http://rox.sourceforge.net/2005/interfaces/ROX-Filer
$ rox --version
ROX-Filer 2.11
```

The main difference is that apps store their current selections (in `~/.config/0install.net/apps/rox/selections.xml` in this case). This means that they start faster, because the solver isn't needed:

```shell
$ 0alias rox-alias http://rox.sourceforge.net/2005/interfaces/ROX-Filer
$ 0install add rox-app http://rox.sourceforge.net/2005/interfaces/ROX-Filer

$ time rox-alias --version > /dev/null
rox-alias --version > /dev/null  0.12s user 0.02s system 91% cpu 0.144 total

$ time rox-app --version > /dev/null
rox-app --version > /dev/null  0.06s user 0.02s system 92% cpu 0.082 total
```

When run, they still trigger a background update if they haven't been updated for a while, and you can also update them manually:

```shell
$ 0install update rox
No updates found. Continuing with version 2.11.
```

They also remember any restrictions (e.g. --before).

Each app also stores past selections (max one set per day) so if an update goes wrong you can see what changed and roll-back easily:

```shell
$ 0install whatchanged 0publish
Last checked    : Wed Jun 27 20:24:19 2012
Last update     : 2012-06-27
Previous update : 2012-06-16

http://0install.net/2007/interfaces/ZeroInstall.xml: 1.8-post -> 1.9-post
http://repo.roscidus.com/security/gnupg: new -> 1.4.12-4
```

To run using the previous selections, use:

```shell
$ 0install run /home/tal/.config/0install.net/apps/0publish/selections-2012-06-16.xml
```

Starting with 0install 1.14, `0alias` is deprecated, and trying to add an alias will add an app instead.
