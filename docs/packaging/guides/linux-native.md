# Native Linux apps

This guide covers packaging pre-built Linux binaries distributed as tarballs, AppImages, RPMs or DEBs.

## Generic tarballs

If upstream ships a "generic" `.tar.gz` or `.tar.xz` that works on most distributions, this is the easiest case:

```xml
<?xml version="1.0"?>
<interface xmlns="http://zero-install.sourceforge.net/2004/injector/interface">
  <name>MyApp</name>
  <summary>does something useful</summary>
  <homepage>https://example.com/myapp</homepage>
  <icon href="https://example.com/myapp.png" type="image/png"/>

  <feed-for interface="https://example.com/myapp.xml"/>

  <group license="GPL v3 (GNU General Public License)">
    <command name="run" path="bin/myapp"/>

    <implementation arch="Linux-x86_64" version="{version}" released="{released}" stability="stable">
      <manifest-digest/>
      <archive href="https://example.com/downloads/myapp-{version}-linux-x64.tar.gz"/>
    </implementation>
    <implementation arch="Linux-aarch64" version="{version}" released="{released}" stability="stable">
      <manifest-digest/>
      <archive href="https://example.com/downloads/myapp-{version}-linux-arm64.tar.gz"/>
    </implementation>
  </group>
</interface>
```

`arch="Linux-x86_64"` covers AMD64 CPUs. Other common values are `Linux-i486` (32-bit), `Linux-aarch64` (ARM64) and `Linux-armv7l` (ARMv7).

## Single-binary downloads

For tools shipped as a single executable (common for Go and Rust binaries), use `<file>` instead of `<archive>` and mark it executable:

```xml
<implementation arch="Linux-x86_64" version="{version}" released="{released}" stability="stable">
  <manifest-digest/>
  <file dest="myapp"
        executable="true"
        href="https://github.com/example/myapp/releases/download/v{version}/myapp-linux-amd64"/>
  <command name="run" path="myapp"/>
</implementation>
```

## RPMs and DEBs

Zero Install can extract `.rpm` and `.deb` packages without installing them. The contents are unpacked into a per-user cache, so users without root can run them and the app must work outside `/usr`.

```xml
<implementation arch="Linux-x86_64" version="{version}" released="{released}" stability="stable">
  <manifest-digest/>
  <archive href="https://example.com/downloads/myapp-{version}.x86_64.rpm"
           type="application/x-rpm"/>
  <command name="run" path="usr/bin/myapp"/>
</implementation>
```

The `path` is relative to the unpacked tree, so a binary that the package installs at `/usr/bin/myapp` is at `usr/bin/myapp` after extraction.

This only works if the binary is _relocatable_. Apps that hard-code paths like `/usr/share/myapp` will need either a binreloc-aware build or a `<recipe>` to patch the install. The original [Inkscape RPM](https://github.com/0install/apps/blob/master/gui/inkscape.xml) was recompiled with binreloc support specifically for this reason.

## AppImages

AppImages are self-mounting ELF files; they cannot be unpacked with the built-in archive types. Either:

- Re-archive them as a tarball (`./myapp.AppImage --appimage-extract && tar czf myapp.tar.gz squashfs-root/`) and host the result yourself, or
- Ask upstream to publish a regular tarball alongside the AppImage.

Once re-archived, package as a regular tarball with `command="run" path="AppRun"` (the standard AppDir entry point).

## Shared library dependencies

If your app links against shared libraries published as their own feeds, add a `<requires>` and bind the library directory into `LD_LIBRARY_PATH`:

```xml
<requires interface="https://example.com/somelibrary.xml">
  <environment name="LD_LIBRARY_PATH" insert="lib"/>
</requires>
```

For libraries provided by the host distribution (glibc, libc++, etc.), use `<package-implementation>` to declare the dependency without trying to repackage system libraries:

```xml
<package-implementation distributions="Debian Ubuntu" package="libcurl4"/>
<package-implementation distributions="RPM" package="libcurl"/>
```

See [Distribution integration](../../details/distribution-integration.md) for the list of recognized distribution names.

## Tips

- Set `<needs-terminal/>` on console apps so GUI launchers open a terminal.
- Add a [Capabilities](../../specifications/capabilities.md) block if you want the app to register `.desktop` entries when the user runs `0install integrate`.
