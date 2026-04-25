# Native macOS apps

This guide covers packaging pre-built macOS binaries distributed as tarballs, `.app` bundles or `.dmg` images.

## Architecture names

The `arch` attribute uses `Darwin` (the open-source kernel name) and `MacOSX` (the full OS) almost interchangeably. The conventions are:

- `Darwin-*` matches anything macOS, including command-line builds.
- `MacOSX-x86_64` for Intel Macs.
- `MacOSX-aarch64` for Apple Silicon (M1/M2/M3...).
- `MacOSX-*` matches both, when a universal binary covers everything.

## Command-line tarballs

Most Unix-style tools ship as a `.tar.gz` containing a `bin/`, `lib/`, etc. directory layout. Package them like Linux apps:

```xml
<?xml version="1.0"?>
<interface xmlns="http://zero-install.sourceforge.net/2004/injector/interface">
  <name>MyApp</name>
  <summary>does something useful</summary>
  <homepage>https://example.com/myapp</homepage>

  <feed-for interface="https://example.com/myapp.xml"/>

  <group license="MIT License">
    <command name="run" path="bin/myapp"/>

    <implementation arch="MacOSX-x86_64" version="{version}" released="{released}" stability="stable">
      <manifest-digest/>
      <archive href="https://example.com/downloads/myapp-{version}-osx-x64.tar.gz"/>
    </implementation>
    <implementation arch="MacOSX-aarch64" version="{version}" released="{released}" stability="stable">
      <manifest-digest/>
      <archive href="https://example.com/downloads/myapp-{version}-osx-arm64.tar.gz"/>
    </implementation>
  </group>
</interface>
```

For single-binary downloads use `<file dest="myapp" executable="true" href="..."/>` as in the [Linux guide](linux-native.md).

## DMG images

Zero Install can extract `.dmg` images directly via the `application/x-apple-diskimage` archive type. The implementation directory ends up containing whatever was on the disk image; typically a `.app` bundle:

```xml
<implementation arch="MacOSX-*" version="{version}" released="{released}" stability="stable">
  <manifest-digest/>
  <archive href="https://example.com/downloads/myapp-{version}.dmg"
           type="application/x-apple-diskimage"/>
  <command name="run" path="MyApp.app/Contents/MacOS/myapp"/>
</implementation>
```

The `path` points at the actual executable inside the `.app` bundle. A macOS `.app` is just a directory with a known layout.

## .app bundles in tarballs

Many cross-platform apps (Calibre, Blender, Electron-based apps) ship a `.tar.gz` that contains a top-level `.app` bundle. Just point `path` into the bundle:

```xml
<command name="run" path="MyApp.app/Contents/MacOS/myapp"/>
<command name="edit" path="MyApp.app/Contents/MacOS/myapp-edit"/>
```

If the app uses multiple CLI helpers (like Calibre's `ebook-edit`, `ebook-viewer`), expose each as its own command so users can `0install run --command=edit ...`.

## Icons

Provide an `.icns` icon in addition to the PNG so that desktop integration can install a native icon:

```xml
<icon href="https://example.com/myapp.png" type="image/png"/>
<icon href="https://example.com/myapp.icns" type="image/x-icns"/>
```
