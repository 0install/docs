# pkg2zero

!!! info ""
    **Maintainer:** Thomas Leonard  
    **License:** GNU General Public License  
    **Source:** <http://repo.or.cz/w/deb2zero.git>  
    **Zero Install feed:** <http://0install.net/2009/interfaces/pkg2zero.xml>

**pkg2zero** takes a Debian or RPM package and publishes it in a Zero Install feed.

You can always use [0publish](0publish.md) to publish any tarball, RPM, Deb, Zip or Autopackage file, but you have to enter the meta-data (name, summary, description, etc) manually. Since Debian and RPM packages already contain this information, it's easier to extract it and generate the feed automatically. This is what pkg2zero does.

You can download **pkg2zero** and create a short-cut to it in the usual way:

```shell
0install add pkg2zero http://0install.net/2009/interfaces/pkg2zero.xml
```

## Creating the feed

Select the Debian or RPM package you want to publish. You need the full URL. Since packages sometimes move, you should probably make a copy of it on your own server and use that URL, but for this demo we'll link directly to Debian's copy of the program (GQView for this tutorial):

```shell
pkg2zero http://ftp.uk.debian.org/debian/pool/main/g/gqview/gqview_2.0.1-1_i386.deb GQView.xml
```

This downloads the Debian package to the current directory and creates a feed called `GQView.xml`.

Alternatively, to create the feed from an RPM:

```shell
pkg2zero http://dag.wieers.com/rpm/packages/gqview/gqview-1.4.5-1.el5.rf.i386.rpm GQView.xml
```

You will be prompted to give a URL for the program's icon, since Debian packages don't have them.

You will then be prompted to "Enter the URI for this feed". This is the URL from which other people will download your feed file.

!!! note
    pkg2zero guesses some things (such as which binary to run by default if the package contains several) so you should check the feed file manually and edit if required. In this case, no editing is needed.

## Testing

When testing, you should make sure that the program isn't already installed (e.g. by `apt-get`). Some programs contain hard-coded paths, and will therefore appear to work correctly... but only on a system which already has the program! Also, Zero Install may select the natively-installed version, depending on your policy settings.

To test the feed:

```shell
$ 0install run -g ./GQView.xml
```

Note that pkg2zero added the downloaded package's contents to the Zero Install cache, so it will show up as being already cached.

## Publishing

If you want to publish the feed so that others can use it, you'll also need to sign it, which can be done by giving the GPG key to use with the `--key` option:

```shell
$ pkg2zero -k Bob http://.../package.deb
```

Note that if this version was already added then it pkg2zero won't do anything, so if you already made an unsigned feed then delete it first, or use [0publish --xmlsign](0publish.md) to resign it.

See the [packaging tutorial](../packaging/index.md) for more details about signing feeds.

## Adding more versions

To add a new version to your feed later, just run the command again with the new URL:

```shell
$ pkg2zero http://.../new-version.deb GQView.xml
```

## Using a Debian Packages file

As an alternative to specifying the URL of the Debian package directly, you can download an index file to the current directory and then just give the package name. e.g. to use the current Debian/stable package for the amd64 (`x86_64`) architecture:

```shell
$ wget ftp://ftp.debian.org/debian/dists/stable/main/binary-amd64/Packages.bz2
$ pkg2zero --packages-file=Packages.bz2 gqview GQView.xml
```

## Using RPM Repository Metadata

As an alternative to specifying the URL of the RPM package directly, you can download the repodata to the current directory and then just give the package name. e.g.

```shell
$ mkdir -p repodata
$ wget -N -P repodata http://download.fedoraproject.org/pub/epel/5/i386/repodata/repomd.xml
$ wget -N -P repodata http://download.fedoraproject.org/pub/epel/5/i386/repodata/...-primary.xml.gz
$ pkg2zero --repomd-file=repodata/repomd.xml -m http://download.fedoraproject.org/pub/epel --path 5/i386 gqview GQView.xml
```

## Dependencies

pkg2zero can process the `Depends` field in a Debian package and generate a corresponding `<requires>` element in the feed. For this to work, you need to create a file called `~/.config/0install.net/pkg2zero/mappings` (see the [freedesktop.org basedir specification](http://www.freedesktop.org/wiki/Specifications/basedir-spec) for details about configuration file locations). Each line of the file gives a mapping from a Debian package name to the corresponding Zero Install feed URI. For example, if you specify:

```
libfoo: http://0install.net/2008/3rd-party/libfoo.xml
```

then `pkg2zero` will turn

```
Depends: libfoo
```

into

```xml
  <requires interface="http://0install.net/2008/3rd-party/libfoo.xml">
    <environment insert="usr/lib" name="LD_LIBRARY_PATH"/>
  </requires>
```

Note that setting `LD_LIBRARY_PATH` is just a guess. It may be that the package depends on `libfoo` in some other way (e.g. it needs a binary in `$PATH`). In that case, you'll need to edit the feed to correct it.

When you create a new feed, pkg2zero automatically appends the new mapping to the mappings file for you. The last line of the file is also used to suggest a default when naming new feeds.

## Security notes

pkg2zero does not verify anything about the archive it downloads when you use a URL. However, if a file with the same name already exists in the current directory, it uses that instead. Therefore, if you have a secure way of getting the .deb file (e.g. because you created it), use that.

pkg2zero calculates the digest of the package and stores it in the feed it creates, so anyone using the feed can at least verify that the package they download is identical to the one you used.

If you use a Packages index file, then it does check the digest against the one in the Packages file.
