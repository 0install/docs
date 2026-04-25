# Native Windows apps

This guide covers packaging pre-built Windows binaries distributed as  `.exe` files, ZIPs, `.msi` installers, etc. For .NET apps see [.NET Framework apps](dotnet-framework.md) and [.NET apps](dotnet.md).

## ZIP / portable apps

The simplest case: an archive that extracts to a folder and contains an `.exe` you can run in place.

```xml
<?xml version="1.0"?>
<interface xmlns="http://zero-install.sourceforge.net/2004/injector/interface">
  <name>MyApp</name>
  <summary>does something useful</summary>
  <homepage>https://example.com/myapp</homepage>
  <icon href="https://example.com/myapp.png" type="image/png"/>
  <icon href="https://example.com/myapp.ico" type="image/vnd.microsoft.icon"/>

  <feed-for interface="https://example.com/myapp.xml"/>

  <group license="MIT License">
    <command name="run" path="myapp.exe"/>

    <implementation arch="Windows-x86_64" version="{version}" released="{released}" stability="stable">
      <manifest-digest/>
      <archive href="https://example.com/downloads/myapp-{version}-win-x64.zip"/>
    </implementation>
    <implementation arch="Windows-aarch64" version="{version}" released="{released}" stability="stable">
      <manifest-digest/>
      <archive href="https://example.com/downloads/myapp-{version}-win-arm64.zip"/>
    </implementation>
  </group>
</interface>
```

Use `Windows-x86_64` for x64 builds, `Windows-i486` for 32-bit (`x86`), and `Windows-aarch64` for ARM64. If the same archive works everywhere, omit the `arch` attribute (which defaults to `*-*`).

## Console vs. GUI

By convention:

- `<command name="run">` is the default; on Windows shortcuts created by [Zero Install desktop integration](../../details/desktop-integration.md) treat `run` as console.
- `<command name="run-gui">` is invoked by the GUI launcher and by `0install run --no-wait`. For a Windows GUI app expose this command pointing at the same `.exe`.
- Add `<needs-terminal/>` at the top of the feed for console-only apps so launchers open a terminal.

```xml
<command name="run" path="myapp.exe"/>
<command name="run-gui" path="myapp-gui.exe"/>
```

Many Windows apps ship two `.exe` variants compiled with different subsystems (`/SUBSYSTEM:CONSOLE` vs. `/SUBSYSTEM:WINDOWS`); map each to the appropriate command.

## MSI installers

Zero Install can extract an MSI without installing it via the `application/x-msi` archive type. The `extract` attribute points at the relative path inside the MSI's CAB layout that contains the application:

```xml
<implementation arch="Windows-x86_64" version="{version}" released="{released}" stability="stable">
  <manifest-digest/>
  <archive href="https://example.com/downloads/myapp-{version}.msi"
           type="application/x-msi"
           extract="SourceDir/MyApp"/>
</implementation>
```

The `extract` value depends on how the MSI was authored.

## Apps that hard-code their working directory

Some Windows apps (often older games and tools) `cd` to a hard-coded directory or expect the current working directory to contain their data. Use the `<working-dir>` element inside the command:

```xml
<command name="run" path="bin/myapp.exe">
  <working-dir/>
</command>
```

`<working-dir>` defaults to the implementation root; pass `src="..."` to point at a subdirectory. See the [feed specification](../../specifications/feed.md#commands) for details.

## Capabilities (file associations, shortcuts)

If your app should register file associations or appear in the Start menu when the user runs `0install integrate`, add a `<capabilities>` block. See [Capabilities](../../specifications/capabilities.md) for the full schema; here is a small example for a `.myapp` file extension:

```xml
<capabilities xmlns="http://0install.de/schema/desktop-integration/capabilities">
  <file-type id="MyApp.Document">
    <description>MyApp Document</description>
    <verb name="open" args='"%V"'/>
    <extension value=".myapp" mime-type="application/x-myapp"/>
  </file-type>
</capabilities>
```

Provide an `.ico` icon as well as a PNG so shortcuts and Explorer associations look right.

## Tips

- ZIPs, 7-zip, MSIs and CAB files are all supported. See the [feed specification](../../specifications/feed.md#retrieval-methods) for the full list of MIME types.
- `0template` infers the `extract` attribute automatically when an archive contains a single top-level directory; double-check it on the generated feed.
- For installers that can't be unpacked without running, you'll need to host a re-archived ZIP yourself.
