# Developer overview

This section is for people developing 0install itself, or integrating it into their own systems (for example, if you want to use 0install to manage plugins for your application).

If you want to make programs available through 0install, see the [Packagers Documentation](../packaging/index.md) instead.

[Using Git](using-git.md)
: How to get the very latest developer versions using Git.

[Design](design.md)
: A more detailed description of the design of Zero Install.

[Solver](solver.md)
: A description of the dependency solver in Zero Install.

**API**

The `0install` command-line interface to Zero Install is sufficient for most purposes. However, sometimes you may want to use the Python library interface. This is used by programs such as `0compile` and `0publish`, and is also a useful reference for people wanting to modify 0install itself.

[Python API](python-api.md)
: Use 0install's functions in your own Python applications with a native library.

[.NET API](dotnet-api.md)
: Use 0install's functions in your own .NET applications with a native library.

[JSON API](json-api.md)
: Use 0install's functions in any language via a JSON-based stdin/stdout API.

[Using Zero-Install as a Plugin Manager](http://gfxmonk.net/2011/08/02/using-zero-install-as-a-plugin-manager.html)
: A blog post showing how 0install can be used to manage plugins for your application.
