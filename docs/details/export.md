Zero Install automatically takes care of downloading applications and their dependencies when you run them on your computer. However, it is sometimes useful to download everything required to run an application and export it, e.g., for use on machine without an internet connection, or where the connection is very slow.

# Linux

On Linux you can use the tool [0export](../tools/0export.md) to create self-installing bundles.

# Windows

On Windows you can use the command [0install export](cli.md#export) to generate a directory with archives holding all required implementations and a small bootstrapping executable for importing them and setting up Zero Install on systems that don't have it yet.

For example:

```shell
$ 0install export --include-zero-install http://repo.roscidus.com/utils/vlc somedir
```

The resulting directory structure will look something like this:

```plain
somedir\import.cmd
```
: A batch script for importing the contents on a machine that already has Zero Install set up.

```plain
somedir\run VLC media player.exe
```
: A modified version of the [Bootstrapper](windows.md#bootstrapper) for importing the contents and then running VLC on a machine that may not have Zero Install set up yet.

```plain
somedir\content\22EA111A7E4242A4.gpg
somedir\content\85A0F0DAB46EE668.gpg
somedir\content\http%3a##0install.de#feeds#ZeroInstall.xml
somedir\content\http%3a##repo.roscidus.com#dotnet#framework.xml
somedir\content\http%3a##repo.roscidus.com#utils#vlc.xml
```
: The downloaded [feeds](../specifications/feed.md) and the GnuPG keys used to sign them.

```plain
somedir\content\sha256new_K44G7XQ4SOWRHVVFSXDW737RFQAKICZE6MAX35OJ7DJHABZKSLVQ.tbz2
somedir\content\sha256new_Z7MMJYZMBDNZMQKRUNOA3IEWGB7AXITJWCLK7RRXFIQ2EVBUX5JQ.tbz2
```
: The implementations selected for VLC and Zero Install compressed as TAR BZ2 archives and named by their digests.

## Individual implementations

You can also export individual implementations from the [cache](cache.md) using the command [0install store export](cli.md#store_export).

For example:

```shell
$ 0install store export sha256new_K44G7XQ4SOWRHVVFSXDW737RFQAKICZE6MAX35OJ7DJHABZKSLVQ vlc-win64-3.0.6.tbz2
```
