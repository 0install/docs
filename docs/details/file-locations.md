# File locations

Zero Install reads and writes config files and caches during operation. This page documents the filesystem paths used on various operating systems:

[TOC]

## Linux

On Linux Zero Install follows the [XDG Base Directory Specification](https://specifications.freedesktop.org/basedir-spec/basedir-spec-latest.html).

General settings
: - `~/.config/0install.net/injector`
- `/etc/xdg/0install.net/injector`

Interface-specific settings
: - `~/.config/0install.net/injector/interfaces`

Feed-specific settings
: - `~/.config/0install.net/injector/feeds`

Feed cache
: - `~/.cache/0install.net/interfaces`

[Implementation cache](cache.md)
: - `~/.cache/0install.net/implementations`
- Custom locations specified in `~/.config/0install.net/injector/implementation-dirs`
- `/var/cache/0install.net/implementations` ([shared between users](sharing.md#linux))

[0install apps](../basics/using-apps.md)
: - `~/.config/0install.net/apps`

## Windows

On Windows Zero Install uses the well-known `%AppData%`, `%LocalAppData%` and `%ProgramData%` directories.

You can use the [potable mode](windows.md#portable-mode) to store all files in one directory (e.g. for use on a USB thumb drive) instead of using the following directories.

Executables:
: - `%AppData%\Programs\Zero Install`
- `%ProgramFiles%\Zero Install`

General settings
: - `C:\Users\Username\AppData\Roaming\0install.net\injector`
- `C:\ProgramData\0install.net\injector`

Interface-specific settings
: - `C:\Users\Username\AppData\Roaming\0install.net\injector\interfaces`

Feed-specific settings
: - `C:\Users\Username\AppData\Roaming\0install.net\injector\feeds`

Feed cache
: - `C:\Users\Username\AppData\Local\0install.net\interfaces`

Icon cache
: - `C:\Users\Username\AppData\Local\0install.net\icons`

[Implementation cache](cache.md)
: - `C:\Users\Username\AppData\Local\0install.net\implementations`
- Custom locations specified in `C:\Users\Username\AppData\Roaming\0install.net\injector\implementation-dirs`
- `C:\ProgramData\0install.net\implementations`  ([shared between users](sharing.md#windows))

[Desktop integration](../basics/windows.md)
: - `C:\Users\Username\AppData\Roaming\0install.net\desktop-integration`
- `C:\ProgramData\0install.net\desktop-integration`
