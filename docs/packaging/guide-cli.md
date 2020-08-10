# Packaging guide (command-line)

This guide shows how to make software available through [Zero Install](https://get.0install.net/), using the command-line [0template](../tools/0template.md) program. New users may prefer to start with the [graphical interface](guide-gui.md) instead, but this version is useful when writing scripts to automatically publish new versions, or if you can't run the graphical version for some reason.

We will start by packaging SCons, a build system (a little like Make).

You should already be familiar with the general operation of 0install. In not, start by reading the [Introduction tutorial](../basics/index.md).

## Introduction

SCons is particularly easy to package, because it is designed to be bundled with other applications and used in their build process, and can therefore be executed in place right after unpacking. Programs that can be run in this way are the easiest to make available through Zero Install. To do this we need to create a small XML file stating:

- Which versions are available.
- Where each one can be downloaded from.
- How each version can be run.
- Any dependencies each version has on other packages.

You can write this _feed file_ with a text editor just by reading the [file format specification](../specifications/feed.md), but it's easier to use the `0template` command. `0template` will create an initial template for you, check that the file is valid, and makes many operations easier. The command is, of course, available through Zero Install. To save typing its full URI each time you run it, create an app now:

```shell
$ 0install add 0template http://0install.net/tools/0template.xml
$ 0template --help
usage: 0template.py [-h] [-o OUTPUT] [--from-feed FROM_FEED]
                    template [name=value [name=value ...]]
[...]
```

See also:

- [0template](../tools/0template.md)
- [SCons homepage](http://www.scons.org)

## Creating the template

Run `0template` now to create a new file called `SCons.xml.template`:

```shell
$ 0template SCons.xml.template
'SCons.xml.template' does not exist; creating new template.

Does your program need to be compiled before it can be used?

1) Generate a source template (e.g. for compiling C source code)
2) Generate a binary template (e.g. for a pre-compiled binary or script)

> 2

Writing SCons.xml.template
```

`0template` will create a new template, which you can open in a text editor.

Fill in the fields in the XML template. The comments should guide you, but these are the exact changes we will make now:

- Set the `summary` to a short description. Start with a lower-case letter (except for proper nouns) and don't include the name of the program. The summary is normally shown after a dash, e.g. _SCons - a software construction tool_.
- The `description` fields can be longer. Copying some text from the project's web page often works well.
- Set the `homepage` to the program's main web-site (or to your own site if you have a page for it). This is where users will go if they want more information than is in the description. Don't forget to uncomment it by removing the `<!--` and `-->` markers around it.
- Set the `icon` to the URL of a small PNG format icon if you want.
- Set the `feed-for` element to the URL where you plan to host the XML feed file.
  Since other programs that depend on this one will use the URL to find it, try to pick a URL that won't change.
- Set the `license` attribute to the project's license.
- Set the `archive` link to the download URL, with `{version}` as a place-holder for the version.
- The main program (which we ran above) is called `scons.py`, so change the `path` attribute to that.
- You can add any dependencies here too. The generated example is for a Python 3 program, and we can just leave that as it is.

Your final version should look something like this:

```xml
<?xml version="1.0"?>
<interface xmlns="http://zero-install.sourceforge.net/2004/injector/interface">
  <name>SCons</name>
  <summary>a software construction tool</summary>

  <description>
SCons is a software construction tool (build tool substitute for Make)
implemented in Python. It features a modular build engine that can be
embedded in other software. SCons configuration files are Python scripts
that call the build engine API.

It can scan files to detect dependencies automatically and maintain a global
view of all dependencies in a build tree, and uses MD5 signatures to detect
changed file contents reliably. Timestamps can be used if you prefer. SCons
also supports parallel builds and is easily extensible through user-defined
builder and scanner objects.
  </description>

  <homepage>http://www.scons.org/</homepage>

  <feed-for interface="http://example.com/scons.xml"/>

  <group license="OSI Approved :: MIT/X Consortium License">
    <command name="run" path="scons.py">
      <runner interface="https://apps.0install.net/python/python.xml">
        <version not-before="3"/>
      </runner>
    </command>

    <implementation arch="*-*" version="{version}">
      <manifest-digest/>
      <archive href="https://downloads.sourceforge.net/scons/scons-local-{version}.tar.gz"/>
    </implementation>
  </group>
</interface>
```

This is a template, from which we can create instances for particular versions.

## Creating a feed for a new version

We can apply the template to a specific version of SCons by supplying a value for the `version` placeholder, like this:

```shell
$ 0template SCons.xml.template version=3.1.1
Downloading https://downloads.sourceforge.net/scons/scons-local-3.1.1.tar.gz to .../scons-local-3.1.1.tar.gz
Writing SCons-3.1.1.xml
```

You can now execute the generated feed, like this:

```xml
$ 0install run SCons-3.1.1.xml --version
SCons by Steven Knight et al.:
...
```

If you look in the `SCons-3.1.1.xml` file, you'll see that `0template` filled in the `implementation` element:

```xml
<implementation arch="*-*" id="sha1new=fdcfde1da872a034e7f241dafa4fd2484e283df0" released="2019-09-10" version="3.1.1">
  <manifest-digest sha256new="FLGRFSTMVPN5JP4EYBMBZBAQRCXTUSRLUD5QCULJ2YVQ3E4SYCBA"/>
  <archive href="https://downloads.sourceforge.net/scons/scons-local-3.1.1.tar.gz" size="456592"/>
</implementation>
```

The `manifest-digest` gives the secure hash of the contents of the package. If the archive is changed (e.g., by someone breaking into SCons's web-server) then the hash won't match and 0install will refuse the download. Inside the `implementation` element is a list of ways of getting it. In this case, we state that a directory with the given hash can be created by downloading the named archive and extracting it.

See also:

- [Feed file format specification](../specifications/feed.md)
- [Archives](../specifications/feed.md#retrieval-methods)

## Publishing the interface

If you want to add the interface to an existing repository, you can just submit it to the repository owner.
If you want to host it yourself, the easiest way is to use the [0repo](../tools/0repo.md) tool.

```shell
$ 0install add 0repo http://0install.net/tools/0repo.xml
```

If you do not already have a GPG key-pair, create one now.
You can accept the defaults for most fields; just enter your name and email address:

```shell
$ gpg --gen-key
```

Then create the repository (identifying the key you just created as the signing key):

```shell
$ 0repo create ~/repositories/myrepo 'John Smith'
$ cd ~/repositories/myrepo
```

Edit `~/repositories/myrepo/0repo-config.py` and set `REPOSITORY_BASE_URL` to the URL of the repository
(the interface URL you chose above needs to be below this).
For example, we used `<feed-for interface="http://example.com/scons.xml"/>` above, so we'd set:

```python
REPOSITORY_BASE_URL = "http://example.com/"
```

There are lots of other things you can configure here to automate releases
(consult [0repo's README](https://github.com/0install/0repo/blob/master/README.md) for details),
but for now you can just leave everything manual.

Register the new repository so that `0repo` can find it:

```shell
$ 0repo register
Created new entry in ~/.config/0install.net/0repo/repositories.json:
http://example.com/: {"path": ".../myrepo", "type": "local"}
```

Now we can add the new version of SCons to the new repository:

```shell
$ 0repo add SCons-3.1.1.xml
[...]
Now copy .../myrepo/public to http://example.com/
Press Return when done (edit 0repo-config.py:upload_public_dir() to automate this)
```

Copy the generated `public` directory to your server and you're done!
If you want to test locally first, you can do `0repo proxy` to run a dummy web-server locally:

```shell
$ 0repo proxy
To use:
env http_proxy='http://localhost:8080/' 0install [...]
```

Then test in another window with:

```shell
$ export http_proxy='http://localhost:8080/'
$ 0install run http://example.com/scons.xml --version
SCons by Steven Knight et al.:
...
```

Once published, you can announce your new feed on the [mailing list](https://0install.net/support.html#lists),
and get your key added to the key-server's list of known keys.


## Adding more versions

You can now add more versions of SCons to your feed with two commands:

```shell
$ 0template SCons.xml.template version=$VERSION
$ 0repo add SCons-$VERSION.xml
```


## Further reading

[0template](../tools/0template.md)
: More information about templates.

[0repo](../tools/0repo.md)
: More information about managing a public repository.

[Example: Find](examples/find.md)
: Find is a Python program with a dependency on a Python library. This example shows how to depend on other components.

[Example: Inkscape](examples/inkscape.md)
: Inkscape is distributed as a binary RPM (among other formats). This guide shows how to publish these RPMs so that they can be run using Zero Install (by users without root access or on Debian systems, for example).

[Example: ROX-Filer](examples/rox.md)
: ROX-Filer is a C program, which requires different binaries for different platforms. These binaries are built and published in interface files maintained by different people. Using the injector's feed mechanism, users only need to use the main ROX-Filer interface and will automatically get a binary for their platform, from the maintainer of that binary.

[Compiling with SCons and GTK](http://rox.sourceforge.net/desktop/node/300)
: Now that we've made SCons available through Zero Install, we can use it in our build scripts. This example shows how to build a GTK application written in C using Zero Install to get the build tool and the header files automatically.
