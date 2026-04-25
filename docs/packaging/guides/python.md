# Python apps

This guide covers packaging Python apps with the [Python feed](https://apps.0install.net/python/python.xml) as a runner.

## Runtime feed

[`https://apps.0install.net/python/python.xml`](https://apps.0install.net/python/python.xml)
: Cross-platform Python interpreter feed. Versions match the CPython release (e.g. `3.11.4`, `3.12.0`).

The feed exposes:

- `run`: `python` (the regular interpreter, console).
- `run-gui`: `pythonw` on Windows (no console window); falls back to `python` elsewhere.

## Self-contained scripts

For a single-file Python script, treat the script itself as the implementation:

```xml
<?xml version="1.0"?>
<interface xmlns="http://zero-install.sourceforge.net/2004/injector/interface">
  <name>MyTool</name>
  <summary>does something useful</summary>
  <homepage>https://example.com/mytool</homepage>
  <needs-terminal/>

  <feed-for interface="https://example.com/mytool.xml"/>

  <group license="MIT License">
    <command name="run" path="mytool.py">
      <runner interface="https://apps.0install.net/python/python.xml">
        <version not-before="3.10"/>
      </runner>
    </command>

    <implementation version="{version}" released="{released}" stability="stable">
      <manifest-digest/>
      <archive href="https://example.com/downloads/mytool-{version}.tar.gz"/>
    </implementation>
  </group>
</interface>
```

The runner makes `python /path/to/mytool.py` the actual command, so users don't need a system Python installed.

## Apps with library dependencies

Python apps that depend on Zero Install-published libraries get the libraries via `PYTHONPATH` bindings:

```xml
<requires interface="https://example.com/somelib.xml">
  <environment name="PYTHONPATH" insert="lib"/>
</requires>
```

The `insert` value is the relative path within the library's implementation directory that contains its top-level Python packages.

For PyPI dependencies, the simplest approach is still to bundle them inside your archive (e.g. via `pip install --target=lib` at release time) rather than declaring them as Zero Install feeds.

## GUI apps

For Tk / wxPython / PyQt apps that should not open a console window on Windows, use `command="run-gui"` on the runner:

```xml
<command name="run" path="mygui.py">
  <runner interface="https://apps.0install.net/python/python.xml" command="run-gui">
    <version not-before="3.10"/>
  </runner>
</command>
```

Omit `<needs-terminal/>` for GUI apps.

## Local feeds and Git checkouts

To run a Python app from a Git checkout without a release, see [Local feeds](../local-feeds.md). The feed file lives next to the script and uses `id="."` to point at the surrounding directory. This is the recommended way to test a feed before publishing it.

## Tips

- Constrain to Python 3 with `<version not-before="3"/>`; pin a tighter range only if you actually use a feature from a specific version.
- If you need a system C library (e.g. `lxml`'s libxml2), declare a `<package-implementation>` for the relevant distributions or document that users must install it themselves. It is rarely worth packaging shared system libraries via Zero Install.
- For applications managed with `pyproject.toml` / Poetry / Hatch, consider building a wheel and shipping it as a single `<file>` archive next to a small launcher script.
