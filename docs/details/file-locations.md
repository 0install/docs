# File locations

This page documents the filesystem paths Zero Install reads from and writes to during normal operation.

!!! hint
    On Windows you can use [Portable mode](windows.md#portable-mode) to store all files in a single directory (e.g., for use on a USB thumb drive).

!!! note
    On Linux Zero Install follows the [XDG Base Directory Specification](https://specifications.freedesktop.org/basedir-spec/basedir-spec-latest.html).

|                                            | Linux, MacOS                                                                   | Windows                                                                        |
| ------------------------------------------ | ------------------------------------------------------------------------------ | ------------------------------------------------------------------------------ |
| [General settings](policy-settings.md)     | `~/.config/0install.net/injector`                                              | `%APPDATA%\0install.net\injector`                                              |
|                                            | `/etc/xdg/0install.net/injector`                                               | `%PROGRAMDATA%\0install.net\injector`                                          |
| Interface-specific settings                | `~/.config/0install.net/injector/interfaces`                                   | `%APPDATA%\0install.net\injector\interfaces`                                   |
| Feed-specific settings                     | `~/.config/0install.net/injector/feeds`                                        | `%APPDATA%\0install.net\injector\feeds`                                        |
| Feed cache                                 | `~/.cache/0install.net/interfaces`                                             | `%LOCALAPPDATA%\0install.net\interfaces`                                       |
| [Implementation cache](cache.md)           | `~/.cache/0install.net/implementations`                                        | `%LOCALAPPDATA%\0install.net\implementations`                                  |
|                                            | Custom dirs specified in `~/.config/0install.net/injector/implementation-dirs` | Custom dirs specified in `%APPDATA%\0install.net\injector\implementation-dirs` |
|                                            | `/var/cache/0install.net/implementations`                                      | `%PROGRAMDATA%\0install.net\implementations`                                   |
| [0install apps](apps.md)                   | `~/.config/0install.net/apps`                                                  | -                                                                              |
| Icon cache                                 | -                                                                              | `%LOCALAPPDATA%\0install.net\icons`                                            |
| [Desktop integration](desktop-integration) | -                                                                              | `%APPDATA%\0install.net\desktop-integration`                                   |
|                                            | -                                                                              | `%PROGRAMDATA%\0install.net\desktop-integration`                               |
| Log files                                  | -                                                                              | `%TEMP%\0install %USERNAME% Log.txt`                                            |
|                                            | -                                                                              | `%TEMP%\0install-win %USERNAME% Log.txt`                                        |
