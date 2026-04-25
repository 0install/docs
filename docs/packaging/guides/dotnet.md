# .NET apps

This guide covers packaging modern .NET apps (.NET Core 2.x, 3.x and .NET 5+). For classic .NET Framework `.exe` binaries see [.NET Framework apps](dotnet-framework.md).

## Runtime feeds

The Zero Install [apps repository](https://github.com/0install/apps) ships ready-to-use feeds for the official Microsoft runtimes:

[`https://apps.0install.net/dotnet/runtime.xml`](https://apps.0install.net/dotnet/runtime.xml)
: The .NET runtime. Minimum needed to run a framework-dependent `.dll`.

[`https://apps.0install.net/dotnet/aspnetcore-runtime.xml`](https://apps.0install.net/dotnet/aspnetcore-runtime.xml)
: The runtime + ASP.NET Core libraries. Use this for web apps and services.

[`https://apps.0install.net/dotnet/windowsdesktop-runtime.xml`](https://apps.0install.net/dotnet/windowsdesktop-runtime.xml)
: The runtime + Windows-only WPF / Windows Forms libraries.

[`https://apps.0install.net/dotnet/sdk.xml`](https://apps.0install.net/dotnet/sdk.xml)
: The full SDK (`dotnet build`, `dotnet publish`, etc). Useful as a build dependency, not for running apps.

All four use .NET version numbers (e.g. `8.0.4`, `9.0.0`) as their feed version, so constrain them with a `<version>` element.

## Framework-dependent app (cross-platform)

For a typical `dotnet publish` framework-dependent build that ships a `MyApp.dll`:

```xml
<?xml version="1.0"?>
<interface xmlns="http://zero-install.sourceforge.net/2004/injector/interface">
  <name>MyApp</name>
  <summary>does something useful</summary>
  <homepage>https://example.com/myapp</homepage>
  <icon href="https://example.com/myapp.png" type="image/png"/>

  <feed-for interface="https://example.com/myapp.xml"/>

  <group license="MIT License">
    <command name="run" path="MyApp.dll">
      <runner interface="https://apps.0install.net/dotnet/runtime.xml">
        <version not-before="8.0" before="9.0"/>
      </runner>
    </command>

    <implementation version="{version}" released="{released}" stability="stable">
      <manifest-digest/>
      <archive href="https://example.com/downloads/myapp-{version}.tar.gz"/>
    </implementation>
  </group>
</interface>
```

The `path` is the path of the `.dll`. The runner injects `dotnet exec MyApp.dll`. The `version` constraint pins to a major version range; relax or tighten it to match the .NET version you tested against.

For ASP.NET Core or WPF apps, swap the runner for `aspnetcore-runtime.xml` or `windowsdesktop-runtime.xml`. They expose the same `run` command and accept the same version constraints.

## Self-contained, OS-specific builds

If you ship `dotnet publish --self-contained` builds, no runner is needed, but you must publish a separate implementation per architecture, with the matching native binary:

```xml
<group license="MIT License">
  <implementation arch="Windows-x86_64" version="{version}" released="{released}" stability="stable">
    <manifest-digest/>
    <archive href="https://example.com/downloads/myapp-{version}-win-x64.zip"/>
    <command name="run" path="MyApp.exe"/>
  </implementation>
  <implementation arch="Linux-x86_64" version="{version}" released="{released}" stability="stable">
    <manifest-digest/>
    <archive href="https://example.com/downloads/myapp-{version}-linux-x64.tar.gz"/>
    <command name="run" path="MyApp"/>
  </implementation>
  <implementation arch="MacOSX-aarch64" version="{version}" released="{released}" stability="stable">
    <manifest-digest/>
    <archive href="https://example.com/downloads/myapp-{version}-osx-arm64.tar.gz"/>
    <command name="run" path="MyApp"/>
  </implementation>
</group>
```

See the [.NET runtime feed template](https://github.com/0install/apps/blob/master/dotnet/runtime.xml.template) for a full example covering all OS / arch combinations Microsoft publishes.

## Library dependencies

For library DLLs that ship as separate feeds, .NET resolves them from the application directory. A `<requires>` with this binding is enough:

```xml
<requires interface="https://example.com/somelibrary.xml">
  <environment name="DOTNET_ADDITIONAL_DEPS" insert="." mode="append"/>
</requires>
```

In most cases it is simpler to bundle library DLLs directly in your app archive and only use Zero Install dependencies for components that are independently versioned and released.

## GUI vs. console apps

The default `run` command on the runtime feeds opens a console window on Windows. For GUI apps, also expose a `run-gui` command:

```xml
<command name="run-gui" path="MyApp.dll">
  <runner interface="https://apps.0install.net/dotnet/windowsdesktop-runtime.xml" command="run-gui">
    <version not-before="8.0" before="9.0"/>
  </runner>
</command>
```

Console apps additionally need `<needs-terminal/>` at the top of the feed.
