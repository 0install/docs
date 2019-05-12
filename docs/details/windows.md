title: Zero Install for Windows

The Windows version of Zero Install extends the cross-platform core [Zero Install .NET](../developers/dotnet-api.md) with a GUI and various OS-specific integrations.

The Linux version and [Windows version](windows.md) of Zero Install share the same [feed format](../specifications/feed.md). For most common use-cases they behave identically on the command-line. There are however some [OS-specific differences](os-differences.md).

[TOC]

# Bootstrapper

When you [download Zero Install for Windows](https://0install.de/downloads/) from the web-site time you get a so called Bootstrapper. This is a stripped down version of Zero Install bundled into a single executable file. It uses a regular Zero Install feed to download and run a full version of Zero Install.

If you wish to install Zero Install in unattended/automatically you can [download the CLI-version of the Bootstrapper](https://0install.de/files/0install.zip). With this you can run:

- `0install.exe maintenance deploy --batch` to install for the current user.
- `0install.exe maintenance deploy --batch --machine` to install for all users.

Default settings can be integrated in the Bootstrap process via editing `0install.exe.config`.

See also: [Blog post about Bootstrapper](https://0install.de/blog/bootstrapper-part-1/?lang=en)

# Portable mode

To set up Zero Install on a USB thumb drive:

- Connect a thumb drive to the computer and make sure there are no files you still need on it.
- Format the thumb drive with NTFS (FAT32 will not work, see [technical details](#technical-details) for explanation).
- Download and run Zero Install for Windows.
- Select "Tools" and "Portable Creator" at the bottom of the window.
- Follow the instructions on screen.

You can now use your thumb drive to run Zero Install on any Windows Computer with the .NET Framework 2.0 (which is built-in starting with Vista). Zero Install stores downloaded applications directly on the stick so you can access the same applications everywhere. Please remember to always "eject" the thumb drive in Windows before disconnecting it from the computer.

## Limitations

The applications launched by Zero Install are not automatically made portable by this. They still store their settings in the usual locations. Please make sure to move these files to the thumb drive as necessary.

Portable versions of Zero Install cannot perform desktop integration (e.g. create start menu entries). Consider using regular Zero Install on multiple computers with [Zero Install Sync](sync.md) instead.

## Technical details

FAT/FAT32-formatted drives cannot be used for Zero Install because they do not store file security settings (ACLs). They also only store time with an accuracy of two seconds while Zero Install checks the exact modification time of files.

The portable creator creates a file in the destination directory called `_portable`, which instructs Zero Install to run in portable mode. When this file is detected Zero Install stores all its files in its installation directory instead of the [usual system directories](file-locations.md).

# Maintenance

Zero Install is designed to be largely maintenance-free, e.g., checking for updates automatically. However, if you want to manually ensure everything is running optimally you can use the following [commands](cli.md):

Download and install updates for Zero Install itself
: `0install self-update --batch`

Download and install updates for [integrated applications](../basics/windows.md) and remove outdated files
: `0install update-all --clean --batch`

Find and merge any duplicate files in the [cache](cache.md)
: `0install store optimise --batch`
