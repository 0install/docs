# Guides

These guides are reference recipes for packaging specific kinds of software with Zero Install. They assume you've already read [Concepts](../concepts.md) and skimmed the [publishing tutorial](../tutorial/publish-app.md), and focus on the parts of the feed that differ between platforms and runtimes: which `arch`, which `<command>`, which dependencies, which environment bindings.

Each guide is self-contained, so you can jump to the one that matches what you're packaging:

[.NET Framework apps](dotnet-framework.md)
: Windows-only apps targeting `.exe` binaries built against the .NET Framework (Mono on POSIX).

[.NET apps](dotnet.md)
: Cross-platform apps targeting modern .NET (.NET Core, .NET 5+) with a `.dll` entry point.

[Java apps](java.md)
: Java apps distributed as a JAR, with a JRE pulled in as a dependency.

[Native Windows apps](windows-native.md)
: Pre-built Windows binaries, including `.exe`, `.zip` and `.msi` distributions.

[Native Linux apps](linux-native.md)
: Pre-built Linux binaries, including tarballs, AppImages, RPMs and DEBs.

[Native macOS apps](macos-native.md)
: Pre-built macOS binaries, including `.app` bundles and `.dmg` images.

[Cross-platform apps](cross-platform.md)
: How to combine OS-specific feeds into a single user-facing feed using `<feed arch="...">`.

[Python apps](python.md)
: Python scripts and apps, using the bundled Python feed as a runner.

[Libraries](libraries.md)
: Components meant to be consumed by other feeds rather than run directly.

## What every feed needs

Regardless of platform, a publishable feed should set:

- `<name>`, `<summary>` and `<description>`
- `<homepage>`
- At least one `<icon>` (PNG; add `.ico` for Windows and `.icns` for macOS apps that get desktop integration)
- A `license` attribute on the implementation or its enclosing group
- A `<feed-for>` element (in templates) pointing at the published URL

For everything else, see the [feed specification](../../specifications/feed.md).
