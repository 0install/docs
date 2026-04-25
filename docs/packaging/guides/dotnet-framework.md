# .NET Framework apps

This guide covers packaging .NET Framework apps, i.e. classic `.exe` binaries that run on the .NET Framework on Windows and on [Mono](https://www.mono-project.com/) on POSIX. For modern .NET (.NET Core, .NET 5+) see [.NET apps](dotnet.md).

## Choosing the right runner

There are two interchangeable runtime feeds:

[`https://apps.0install.net/dotnet/clr.xml`](https://apps.0install.net/dotnet/clr.xml)
: A "virtual" feed which selects the .NET Framework on Windows and Mono on POSIX. Pick this when your app does **not** load .NET DLLs published as separate Zero Install feeds.

[`https://apps.0install.net/dotnet/clr-monopath.xml`](https://apps.0install.net/dotnet/clr-monopath.xml)
: Same as `clr.xml`, but adds a small shim on Windows so that the `MONO_PATH` environment variable is honored. Pick this when you want to inject library DLLs from other Zero Install feeds.

Both feeds use .NET Framework profile numbers (e.g. `2.0`, `3.5`, `4.5`, `4.6.2`) as their version, so you constrain the runtime via `<version not-before="..."/>`.

!!! tip
    `clr.xml` and `clr-monopath.xml` both treat the runtime as a runner, so 0install will choose a compatible .NET Framework / Mono version. You don't need to detect the framework yourself.

## A self-contained .exe

For a `.exe` that bundles all its dependencies (or only relies on the BCL), use `clr.xml` as the runner:

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
    <command name="run" path="MyApp.exe">
      <runner interface="https://apps.0install.net/dotnet/clr.xml">
        <version not-before="4.5"/>
      </runner>
    </command>

    <implementation version="{version}" released="{released}" stability="stable">
      <manifest-digest/>
      <archive href="https://example.com/downloads/myapp-{version}.zip"/>
    </implementation>
  </group>
</interface>
```

When 0install runs this on Windows it locates the .NET Framework (or installs it if missing) and launches `MyApp.exe`. On Linux/macOS it does the same with Mono.

!!! tip
    Prefer self-contained ZIP releases over installers; .NET Framework installers (`.msi`) can be used via the `application/x-msi` archive type but require the app to behave with arbitrary install locations.

## Apps with library dependencies

If your app loads DLLs published as separate feeds, switch the runner to `clr-monopath.xml` and add a `<requires>` for each library that binds it into `MONO_PATH`:

```xml
<command name="run" path="MyApp.exe">
  <runner interface="https://apps.0install.net/dotnet/clr-monopath.xml">
    <version not-before="4.5"/>
  </runner>
</command>

<requires interface="https://example.com/somelibrary.xml">
  <environment name="MONO_PATH" insert="." mode="append"/>
</requires>
```

!!! note
    `MONO_PATH` is named after Mono but `clr-monopath.xml` makes Windows honor it too. .NET DLLs are not loaded via `PATH`.

The library feed itself is just a feed without a `<command>`:

```xml
<interface xmlns="http://zero-install.sourceforge.net/2004/injector/interface">
  <name>SomeLibrary</name>
  <summary>helpers for doing X</summary>

  <implementation version="1.0">
    <manifest-digest/>
    <archive href="https://example.com/downloads/somelibrary-1.0.zip"/>
  </implementation>
</interface>
```

## GUI vs. console apps

By convention there are two commands:

- `run`: The default, used when invoked from a terminal.
- `run-gui`: Used by Windows shortcuts and by callers that don't want a console window.

For a Windows Forms / WPF app you typically want both, with `run-gui` going through `command="run-gui"` of the runner so a console isn't allocated:

```xml
<command name="run" path="MyApp.exe">
  <runner interface="https://apps.0install.net/dotnet/clr.xml" command="run">
    <version not-before="4.5"/>
  </runner>
</command>
<command name="run-gui" path="MyApp.exe">
  <runner interface="https://apps.0install.net/dotnet/clr.xml" command="run-gui">
    <version not-before="4.5"/>
  </runner>
</command>
```

Console apps should set `<needs-terminal/>` at the command level; GUI apps should omit it.

!!! tip
    See [Cross-platform apps](cross-platform.md) if you ship a native fallback for POSIX rather than relying on Mono.
