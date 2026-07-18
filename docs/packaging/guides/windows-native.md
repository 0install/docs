# Native Windows apps

This guide covers packaging pre-built Windows binaries distributed as `.exe` files, ZIPs, `.msi` installers, etc. For .NET apps see [.NET Framework apps](dotnet-framework.md) and [.NET apps](dotnet.md).

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

  <group license="MIT">
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

Use `Windows-x86_64` for x64 builds, `Windows-i486` for 32-bit (`x86`), and `Windows-aarch64` for ARM64.

## Console vs. GUI

On Windows, an `.exe` is compiled for one of two subsystems: *console* (a command-line program that expects a terminal) or *GUI* (a windowed program that must not block a terminal). Zero Install needs to know which kind it is launching so it can behave correctly, and you tell it via the `<needs-terminal>` element.

### Marking a command with `<needs-terminal>`

Add `<needs-terminal/>` to an `<entry-point>` (or to the feed as a whole) to declare that the command it names is a console program:

```xml
<needs-terminal/>

<entry-point command="run" binary-name="myapp">
  <needs-terminal/>
</entry-point>
```

`<needs-terminal>` changes three things when someone runs or integrates your app:

- **Which launcher runs it.** For a terminal command Zero Install uses `0install.exe`, which stays attached to the console and waits for the program to exit. For a GUI command it uses `0install-win.exe`, so no console window appears and the launcher returns immediately once the window is up.
- **The stub `.exe` and shortcuts.** When `0install integrate` creates Start-menu entries, desktop icons or aliases, it compiles a small stub `.exe`. A terminal entry point produces a *console*-subsystem stub (a console window opens); a GUI entry point produces a *GUI*-subsystem stub (no flash of a console window). Shortcuts likewise point at `0install.exe` or `0install-win.exe` to match.
- **Which integrations are offered.** `0install integrate` only suggests a command-line **alias** for entry points marked `<needs-terminal/>`. A GUI program doesn't get one, since it isn't meant to be invoked from a shell. Start-menu entries and desktop icons are offered for both `run` and `run-gui` entry points regardless.

### Shipping both variants

Some Windows apps ship two `.exe` variants compiled with different subsystems, one console and one GUI. Map them to two commands and give each its own entry point:

```xml
<group license="MIT">
  <command name="run" path="myapp-cli.exe"/>
  <command name="run-gui" path="myapp.exe"/>

  <entry-point command="run" binary-name="myapp-cli">
    <needs-terminal/>
  </entry-point>
  <entry-point command="run-gui" binary-name="myapp"/>

  <implementation arch="Windows-x86_64" version="{version}" released="{released}" stability="stable">
    <manifest-digest/>
    <archive href="https://example.com/downloads/myapp-{version}-win-x64.zip"/>
  </implementation>
</group>
```

By convention:

- `<command name="run">` is the console variant. Mark its entry point `<needs-terminal/>`.
- `<command name="run-gui">` is the GUI variant. Leave its entry point without `<needs-terminal/>`.

Users run the GUI variant with `0install run --command=run-gui https://example.com/myapp.xml`. After `0install integrate`, the console variant gets a command-line alias while both variants can appear in the Start menu.

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

!!! tip
    ZIPs, 7-zip, MSIs and CAB files are all supported. See the [feed specification](../../specifications/feed.md#retrieval-methods) for the full list of MIME types. For installers that can't be unpacked without running, you'll need to host a re-archived ZIP yourself.

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
