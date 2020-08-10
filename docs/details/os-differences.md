# OS-specific differences

The [Linux version](linux.md) and [Windows version](windows.md) of Zero Install share the same [feed format](../specifications/feed.md). For most common use-cases they behave identically on the command-line. There are however some OS-specific differences.

## Version number
The version numbers of the Linux and Windows versions of Zero Install are loosely coupled. If the first two components of the version number are the same (e.g. 2.1 and 2.1.3) they generally support the same features.

## Command-line interface

The [command-line interface](cli.md) of the Linux and Windows versions are largely identical, with the following exceptions:

`0install add|destroy|show|whatchanged`
: These [app](../basics/using-apps.md) commands are only available in the Linux version so far.

`0install man`
: This man-page integration command is only available in the Linux version.

`0install catalog search|refresh|add|remove|reset|list`
: These [catalog](../specifications/catalog.md) commands are only available in the Windows version so far.

`0install add|remove|alias|integrate|update-all|repair-apps|sync`
: These [desktop integration](../basics/windows.md) commands are only available in the Windows version so far.

`0install store list-implementations|purge`
: These [cache](cache.md) management commands are only available in the Windows version so far.

`0install export` and `0install store export`
: These [implementation exporting](export.md) commands are only available in the Windows version so far.

`0install self deploy|remove|update`
: These [maintenance](windows.md#maintenance) commands are only available in the Windows version.

`0install run --no-wait`
: On *nix systems Zero Install replaces itself with the application it launches using `exec()`. Since there is no direct `exec()` equivalent on Windows Zero Install launches a child process and waits for it to exit. This Windows-only command-line argument causes Zero Install to return immediately instead without waiting for the child to exit.

`0install --dry-run`
: Not implemented in the Windows version.

`0install --console|gui`
: These command-line arguments are not available in the Windows version. Instead it uses separate executables, `0install` and `0install-win`, to select console or GUI mode.

`0install --background|batch`
: These command-line arguments are only available in the Windows version.

`0install select|download|run --customize`
: This command-line argument is only available in the Windows version.

## Configuration files and caches

The Linux and Windows versions mostly use the same formats for configuration files and caches. However, due to some limitations of the Windows filesystem they are not directly interchangeable.

`~/.config/0install.net/injector/interfaces` on Linux contains file names with colons in them. In the Windows counterpart `%appdata%\0install.net\injector\interfaces` these are encoded as `%3a`.

When extracting implementations Zero Install preserves executable-bits and symlinks and considers them when calculating [manifest digests](../specifications/manifest.md).  
Windows does not have a concept of executable bits. Instead, Zero Install for Windows stores this information in a file called `.xbit` in the top-level directory of each implementation.  
On Windows Administrator privileges are required to create symlinks. Therefore Zero Install creates [Cygwin-style symlinks](http://cygwin.com/cygwin-ug-net/using.html#pathnames-symlinks) instead of "real" NTFS symlinks.

See also: [File locations](file-locations.md)

## Other differences

The Windows version:

- uses NTFS ACLs instead of POSIX octets to make implementation directories read-only.
- provides in-process extraction code for all supported archive formats, since there is usually no `tar`, `unzip`, etc. in the `PATH` on Windows systems
- transparently handles Unix-style `$ENVIRONMENT_VARIABLES` rather than expecting them in the platform-specific `%WINDOWS%` style.
- creates binaries instead of shell scripts for command-line aliases and `<executable-in-*>` bindings.

## Cross-platform use

The Windows version of Zero Install is written in in C#. It is primarily intended to be be used on Windows NT-based operating systems. However, the [Zero Install .NET API](../developers/dotnet-api.md) it is based on is written with cross-platform support in mind and works on Linux using .NET Core.

The Linux version of Zero Install is writtin in OCaml. It is primarily intended to be be used on Unixoid operating systems such as Linux and OS X. It can also be compiled for Windows, however, it lacks a number of Windows-specific features such as support for NTFS ACLs.

The Windows version of Zero Install internally uses parts of the Linux version via the [JSON API](../developers/json-api.md).

## Feature comparison

|                                                                  | Windows Version                                          | Linux Version                                         |
| ---------------------------------------------------------------- | -------------------------------------------------------- | ----------------------------------------------------- |
| Feed format                                                      | :material-check-all:{.green} Full support                | :material-check-all:{.green} Full support             |
| Shared cache                                                     | :material-check-all:{.green} Yes (using Windows service) | :material-check-all:{.green} Yes (using store helper) |
| Native package manager integration                               | :material-close:{.red} No                                | :material-check-all:{.green} Yes                      |
| Apps with cached selection                                       | :material-close:{.red} No                                | :material-check-all:{.green} Yes                      |
| Catalog (recommended feeds and short names)                      | :material-check-all:{.green} Yes                         | :material-close:{.red} No                             |
| Desktop integration (menu entries, file type associations, etc.) | :material-check-all:{.green} Yes                         | :material-check:{.yellow} Limited                     |
| App list synchronization                                         | :material-check-all:{.green} Yes                         | :material-close:{.red} No                             |
| Portable mode                                                    | :material-check-all:{.green} Yes                         | :material-close:{.red} No                             |
| Runs on Windows                                                  | :material-check-all:{.green} Yes                         | :material-check:{.yellow} Limited                     |
| Runs on Linux                                                    | :material-check:{.yellow} Limited                        | :material-check-all:{.green} Yes                      |
| Runs on MacOS X                                                  | :material-check:{.yellow} Limited                        | :material-check-all:{.green} Yes                      |
