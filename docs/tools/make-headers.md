title: Make-headers

**Name:** Make-headers  
**Maintainer:** Thomas Leonard  
**License:** GNU General Public License  
**Source:** [Git repository](http://repo.or.cz/w/make-headers.git)  
**Zero Install feed:** <http://0install.net/2007/interfaces/Make-headers.xml>

**Make-headers** is an extremely simple script for creating `-dev` packages (packages containing only header files) from ordinary source releases. It performs the following steps:

1. Runs `$SRCDIR/configure --prefix $DESTDIR && make install`
2. Notes the version numbers in any lib*.so symlinks (see below)
3. Deletes `$DESTDIR/{bin,man,share}`, any library binaries (`.so`, `.a` and `.la` files in `lib`), and any `lib/python`* directories.
4. Edits any pkg-config files (`lib/pkgconfig/*.pc`) to use a relative prefix of `${pcfiledir}/../..`.

In other words, it does a full compile-and-install to $DESTDIR, deletes anything that isn't needed in a `-dev` package, and makes the result relocatable. It can be used as the compile command in a source implementation. For example, [the GLib-dev feed](http://0install.net/2006/interfaces/GLib-dev) contains this entry:

```xml
  <group arch="*-src" compile:command='"$BUILD_COMMAND"'>
    <implementation id="sha1new=fd1cf4afd14067866e626a2c91f9839f4639e604"
                    released="2007-03-04" stability="stable"
		    version="2.4.8">
      <archive extract="glib-2.4.8"
               href="ftp://ftp.gtk.org/pub/gtk/v2.4/glib-2.4.8.tar.bz2"
	       size="2152755"
	       type="application/x-bzip-compressed-tar"/>
    </implementation>
    <requires interface="http://0install.net/2007/interfaces/Make-headers.xml">
      <environment insert="make-headers.py" name="BUILD_COMMAND"/>
    </requires>
```

This allows [0compile](0compile/index.md) to create the GLib-dev package from the upstream source automatically.

Note: If your package creates script files in `bin` that are part of the `-dev` package (i.e. they are used when building programs that use the library) then use `--keep=bin` to prevent them from being deleted.

# Major version mappings

There is a particular issue that comes up if you want to provide the header files (`*.h`) through Zero Install, but have the user get the run-time files (*.so) through their distribution. First, some background:

## Library versioning on Linux/Unix systems

The obvious way to store a shared library object in a package is to give it a simple name like `libfoo.so`. The packaging system selects which version of this file to use and the program loads it. Easy. This is how a pure Zero Install system would work.

However, in a traditional packaging system (apt, make install, etc), where library files go in a single directory (e.g. `/usr/lib`), this would make it impossible to have two versions of a library installed at once. As a special work-around, shared library objects include their version number in their name (e.g. `libfoo.so.1.2.3`). You (or your package manager) then adds two symlinks:

- `libfoo.so` is the symlink used when compiling
- `libfoo.so.1` is the symlink used when running (here `1` is the "major" part of the version number from our example of `1.2.3`)

The idea is that you make sure `libfoo.so` points to the correct version and then compile your code. The compiled binary takes the first part of the version number (here `1`) and stores `libfoo.so.1` as its dependency. When run, it uses the second symlink to find the actual library version. Minor (compatible) upgrades to the library have the same major version. For example, after installing a minor update the symlinks will point to `libfoo.so.1.3.0`, still with major version `1`.

For major (incompatible) changes, the major version number is changed. After installing a major upgrade (`2.0.0`), you have three symlinks:

- `libfoo.so.1` to `libfoo.1.2.3` (from the previous version)
- `libfoo.so.2` to `libfoo.2.0.0`
- `libfoo.so` to `libfoo.2.0.0`

Programs that need version 1 of the library can no longer be compiled (they try to use `libfoo.so` and fail), but any existing binaries will still run (they try to use `libfoo.so.1` and succeed). This is pretty horrible, but it's the way it works. The real problem is that the source code doesn't say what major version of the library it needs; it just fails to compile if you get it wrong, and you can only have the ability to compile against one version at a time.

Obviously, this scheme doesn't work in Zero Install, since installing a package is always side-effect-free. Preventing old programs from compiling would clearly be a side-effect.

In a pure Zero Install system, you can always use the simple scheme above and everything works correctly. In fact, to avoid changing existing libraries, we usually do include the version number in the library name, and we include both symlinks in the runtime package. This doesn't do any harm, because Zero Install keeps files from different packages in different places.

## Using Zero Install -dev packages with distribution runtime packages

However, what if you want to combine both systems? That is, what if you want to get the header files through Zero Install but get the runtime shared object through your distribution's packaging system? Then there is a small problem. In Zero Install, we have:

- A library package contains the `libfoo.so` and `libfoo.so.version` files.
- A `-dev` package contains the header files.

In a distribution package:

- A library package contains the `libfoo.so.version` file only.
- A `-dev` package contains headers and the `libfoo.so` file (only one `-dev` package can be installed at once).

So, if you tried to use a Zero Install `-dev` package with a distribution library package, no one provides the `libfoo.so` file and the link fails.

To fix this, a Zero Install `-dev` package can specify the mappings from library names to major version numbers, like this:

```shell
<interface uri='.../libfoo-dev'>
  ...
  <implementation compile:lib-mappings="foo:1" ... />
```

When 0compile compiles anything that depends on this `-dev` package, it searches for `libfoo.so.1` (provided by the distribution) in the library search path and creates a symlink to it named `libfoo.so` in a temporary directory, which it adds to the search path. Programs should then compile correctly without modifications. Multiple mappings can be given in the attribute, separated by spaces.

To be clear: a Zero Install source package depends on the library `-dev` package using the normal Zero Install mechanism (`<requires ...>` `<version ...>`). Having selected a suitable version of the `-dev` package, Zero Install uses the mappings inside it to work out what the compiled binary should link against.

The source for a `-dev` package can use `compile:binary-lib-mappings` to have this value placed in the generated `-dev` "binary" package. However, starting with version 0.3, Make-headers can automatically work out the correct values and add them to the feed.

In summary:

- If you are creating a source package that depends on a library, ignore all this and just put in a normal Zero Install dependency. 0compile will handle the mappings for you.
- If you are publishing an existing `-dev` package for a library that isn't in Zero Install, remember add the `lib-mappings` attribute.
- If you are publishing source for a `-dev` package, Make-headers should add the correct values for you automatically.
