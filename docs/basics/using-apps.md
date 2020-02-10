!!! note
    This is currently not supported in Zero Install for Windows. Please see [Desktop integration](windows.md) for a functionally similar replacement.

Apps allow you to create command-line launchers:

```shell
$ 0install add rox http://rox.sourceforge.net/2005/interfaces/ROX-Filer
$ rox --version
ROX-Filer 2.11
```

Apps store their current selections (in `~/.config/0install.net/apps/rox/selections.xml` in this case). This means that they start slightly faster than using `0install run URI`, because the solver isn't needed.

When run, they still trigger a background update if they haven't been updated for a while, and you can also update them manually:

```shell
$ 0install update rox
No updates found. Continuing with version 2.11.
```

They also remember any restrictions (e.g. `--before`).

Each app also stores past selections (max one set per day) so if an update goes wrong you can see what changed and roll-back easily:

```shell
$ 0install whatchanged 0publish
Last checked    : Wed Jun 27 20:24:19 2012
Last update     : 2012-06-27
Previous update : 2012-06-16

http://0install.net/2007/interfaces/ZeroInstall.xml: 1.8-post -> 1.9-post
https://apps.0install.net/utils/gnupg.xml: new -> 1.4.12-4
```

To run using the previous selections, use:

```shell
$ 0install run /home/tal/.config/0install.net/apps/0publish/selections-2012-06-16.xml
```
