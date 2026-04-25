# Libraries

Library feeds are like application feeds but without a `<command name="run">`. They're meant to be consumed by other feeds, not run directly. Each kind of library makes itself available through bindings in the consumer's `<requires>` element.

## A minimal library feed

```xml
<?xml version="1.0"?>
<interface xmlns="http://zero-install.sourceforge.net/2004/injector/interface">
  <name>SomeLibrary</name>
  <summary>helpers for doing X</summary>
  <homepage>https://example.com/somelibrary</homepage>

  <feed-for interface="https://example.com/somelibrary.xml"/>

  <group license="MIT License">
    <implementation arch="*-*" version="{version}" released="{released}" stability="stable">
      <manifest-digest/>
      <archive href="https://example.com/downloads/somelibrary-{version}.tar.gz"/>
    </implementation>
  </group>
</interface>
```

The library is published exactly like an app, but consumers reference it through `<requires>` instead of running it.

!!! tip
    A library feed often has no `<command>` at all. `0install run` will refuse to run it directly. If you want users to be able to `0install run` your library to launch a built-in test or REPL, expose a named command (e.g. `<command name="repl" path="..."/>`).

## Bindings by ecosystem

The binding chosen depends on how the library is loaded by its consumer.

### Native shared libraries (`.so`, `.dll`, `.dylib`)

```xml
<!-- in the consuming feed -->
<requires interface="https://example.com/somelibrary.xml">
  <environment name="LD_LIBRARY_PATH"   insert="lib"/>  <!-- Linux -->
  <environment name="DYLD_LIBRARY_PATH" insert="lib"/>  <!-- macOS -->
  <environment name="PATH"              insert="bin"/>  <!-- Windows -->
</requires>
```

For C/C++ projects that need headers at build time, ship a separate `*-dev` feed with the headers and a `<environment name="CFLAGS" insert="include" mode="append"/>` binding or simply provide both files in one feed and let the consumer choose what to bind.

!!! tip
    Libraries with native components (e.g. Python wheels with `.so` files) should ship one implementation per `arch` so the right binary is picked up.

### Python packages

```xml
<requires interface="https://example.com/somelib.xml">
  <environment name="PYTHONPATH" insert="lib"/>
</requires>
```

The library's archive should contain a `lib/somelib/__init__.py` (or similar), so `import somelib` works once `PYTHONPATH` includes the `lib` directory.

### Java libraries (JARs)

```xml
<requires interface="https://example.com/somelib.xml">
  <environment name="CLASSPATH" insert="somelib.jar"/>
</requires>
```

Use [JAR Launcher](java.md#apps-with-library-jar-dependencies) on the consumer side so `CLASSPATH` is honored.

### .NET DLLs

```xml
<requires interface="https://example.com/somelib.xml">
  <environment name="MONO_PATH" insert="." mode="append"/>
</requires>
```

Use [`clr-monopath.xml`](dotnet-framework.md#choosing-the-right-runner) as the runner so Windows honors `MONO_PATH`.

### Build tools (`make`, `cmake`, `dotnet`, ...)

When a feed only needs another tool to run a sub-process, use an executable binding rather than an environment binding:

```xml
<requires interface="https://apps.0install.net/devel/cmake.xml">
  <executable-in-path name="cmake"/>
</requires>
```

`<executable-in-path>` adds the tool to `PATH` so the consumer can invoke it by name. Use `<executable-in-var name="CC"/>` to expose the path through a single environment variable instead.

## Constraining the version

Consumers should pin the version range they tested against:

```xml
<requires interface="https://example.com/somelib.xml" version="2.0..!3"/>
```

This accepts `2.x` versions but excludes `3.0` and later. See [Constraints](../../specifications/feed.md#constraints) for the syntax.

!!! tip
    See [Local feeds](../local-feeds.md) for how to register a development checkout of a library so dependent apps pick it up automatically.
