title: ROX-Filer

This guide shows some more advanced features of Zero Install:

- Creating platform-specific binaries (ROX-Filer is written in C).
- Local interfaces in source releases.
- Adding extra feeds (the maintainer of the PPC and ix86 interfaces are different people, but users get the right binary automatically).

This guide assumes you've already read [the packaging guide](../guide-gui.md), which explains how to create and publish interface files. We won't explain all the concepts in detail again, we'll just show how to script the steps.

[TOC]

# General operation

`0publish` edits interface files in place. It loads the file, transforms it in some way (such as setting the release date, or adding a new implementation) and then writes it back out again. If the input file was signed, it will resign it when saving with the same key by default. You can also use it to add a signature, or to change the signing key.

You can create an alias for `0publish` in the usual way, to save typing:

```shell
$ 0install add 0publish http://0install.net/2006/interfaces/0publish
```

# Creating a binary of ROX-Filer

For this example we'll compile a binary of ROX-Filer for our platform (we'll assume there isn't one already) and make a feed for other people with the same platform (I'll use ppc64 for the examples). Start by creating an archive as normal:

1\. Download the ROX-Filer source release (and detached signature):

```shell
$ wget http://heanet.dl.sourceforge.net/sourceforge/rox/rox-2.4.1.tgz
$ wget http://heanet.dl.sourceforge.net/sourceforge/rox/rox-2.4.1.tgz.sig
$ gpg rox-2.4.1.tgz.sig
gpg: Signature made Fri 30 Dec 2005 17:32:53 GMT using DSA key ID 59A53CC1
gpg: Good signature from "Thomas Leonard <...>"
```

2\. Build as normal:

```shell
$ tar xzf rox-2.4.1.tgz
$ cd rox-2.4.1
$ ./ROX-Filer/AppRun --compile
```

3\. Delete the debugging symbols and the `build` and `src` directories to save space:

```shell
$ rm -r ROX-Filer/{ROX-Filer.dbg,build,src}
```

4\. The ROX-Filer source download includes a _local interface_ file called `ROX-Filer.xml`. This allows people to register local versions using `0install add-feed`. Set the release date and architecture in it:

```shell
$ 0publish --set-released 2006-02-26 ROX-Filer.xml
$ 0publish --set-arch Linux-ppc64 ROX-Filer.xml
```

You can also edit it to change the name and description if you want. The file should now look like this:

```xml
<?xml version='1.0'?>
<interface xmlns='http://zero-install.sourceforge.net/2004/injector/interface'>
  <name>ROX-Filer-ppc64</name>
  <summary>PPC64 binaries for ROX-Filer</summary>
  <description>
    ROX-Filer is a fast and powerful graphical file manager. It has full drag-and-drop support
    and background file operations, and is highly configurable. It can also act as a pinboard,
    allowing you to pin frequently used files to the desktop background.
  </description>
  <feed-for interface='http://rox.sourceforge.net/2005/interfaces/ROX-Filer'/>
  <group main='ROX-Filer/AppRun'>
    <implementation id="." version="2.4.1" released='2006-02-26' arch='Linux-ppc64'/>
  </group>
</interface>
```
    
5\. Add the architecture to the directory name and tar it all up (we include the architecture in the directory and archive names for clarity only; you can name them whatever you like):

```shell
$ cd ..
$ mv rox{,-linux-ppc64}-2.4.1
$ tar czf rox-linux-ppc64-2.4.1{.tgz,}
```

6\. Upload it somewhere. I'll assume `http://example.org/rox-linux-ppc64-2.4.1.tgz` in the following examples.

# Adding the archive to the interface

To make our new binary available through Zero Install:

1\. Take a copy of the local interface from the archive. We'll use `0publish` to change the `id` from `.` to the archive's digest and to add an `<archive>` element:

```shell
$ cp rox-linux-ppc64-2.4.1/ROX-Filer.xml ROX-Filer-ppc64
$ 0publish ROX-Filer-ppc64 \
    --archive-url http://example.org/rox-linux-ppc64-2.4.1.tgz  \
    --archive-file rox-linux-ppc64-2.4.1.tgz \
    --archive-extract rox-linux-ppc64-2.4.1
```

The local .tgz file is used by `0publish` to get the size and manifest digest. The extract value is used as the `<archive>`'s extract attribute and must match the name of the top-level directory in the archive.

If you now view the `ROX-Filer-ppc64` file, you should see that `0publish` has converted the old `<implementation>` to give the digest and download location:

```xml
  <group main="ROX-Filer/AppRun">
    <implementation arch="Linux-ppc64" id="sha1=2bce88f31415898760373fff900890a8719ab1e6" released="2006-02-26" version="2.4.1">
      <archive extract="rox-linux-ppc64-2.4.1" href="http://example.org/rox-linux-ppc64-2.4.1.tgz" size="1375566"/>
    </implementation>
  </group>
```

You should be able to download and test your binary with this command:

```shell
$ 0install run ./ROX-Filer-ppc64
```

# Publishing the interface with Zero Install

1\. Set the uri at the top of the file to where-ever you're going to upload it:

```xml
<?xml version="1.0" ?>
<interface xmlns="http://zero-install.sourceforge.net/2004/injector/interface"
	   uri='http://example.com/2006/0launch/ROX-Filer-ppc'>
```

TODO: 0publish should have a working `--local` option! (note: from version 0.3, 0publish does support --local in this case, when creating a new interface)

2\. Sign and upload the interface (see the [packaging guide](../guide-gui.md) for information about creating, exporting and uploading your GPG key):

```shell
$ 0publish --gpgsign ROX-Filer-ppc64
$ mv ROX-Filer-ppc64 /var/www/...
```

Other users of ppc64 machines can now either run this directly, or add it as a feed (so it will be used by other programs trying to run ROX-Filer):

```shell
$ 0install add-feed http://example.com/2006/0launch/ROX-Filer-ppc
```

You should now tell the maintainer of the master feed about this one, so that they can add a `<feed>` element to the master copy to save users from having to add the feed manually. For an example of a master interface with feeds for different architectures, take a look at the `<feed>` elements in [the real ROX-Filer interface](http://rox.sourceforge.net/2005/interfaces/ROX-Filer).
