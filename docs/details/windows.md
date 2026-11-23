# Zero Install for Windows

The Windows version of Zero Install extends the cross-platform core [Zero Install .NET](../developers/dotnet-api.md) with a GUI and various OS-specific integrations.

The [Linux version](linux.md) and Windows version of Zero Install share the same [feed format](../specifications/feed.md). For most common use-cases they behave identically on the command-line. There are however some [OS-specific differences](os-differences.md).

## Bootstrapper

When you [download Zero Install for Windows](https://get.0install.net/#windows) from the web-site you get a so called Bootstrapper. This is a stripped down version of Zero Install bundled into a single executable file. It contains just enough functionality to download and run a full version of Zero Install, which is distributed as regular feed: <https://apps.0install.net/0install/0install-win.xml>

### Deployment

When Zero Install is started by the Bootstrapper it is running from the [cache](cache.md) and is not integrated into the system. There are no start menu entries, the command-line tools are not in the `PATH`, etc.. This is by design; after all you may only need Zero Install once on that particular machine or may decide you don't like it. In that case you can simply delete the Bootstrapper EXE when you're done.

If you then decide you wish to keep using Zero Install you can "Click to setup...". This applies desktop integration for Zero Install (start menu entry, command-line tools in `PATH`). It also installs the [Store Service](sharing.md#windows) if you choose to setup Zero Install "For all users".

![The Bootstrapper downloads Zero Install, which then sets itself up for the current user](../img/screens/0install-win/bootstrap.gif)

This is different from the [desktop integration](desktop-integration.md) Zero Install performs for other applications. For these Zero Install will create little stub executables in the appropriate locations that point to the application's feed. However, for Zero Install itself the entire binaries need to be copied to a permanent location. Otherwise each of these stubs would need to bundle all the functionality of the Bootstrapper in order to locate (or potentially download) Zero Install.

Having a specific version of Zero Install copied to a fixed location would seem to undermine many of the advantages of Zero Install, such as background updates of applications and running multiple versions side-by-side. However, Zero Install can still download and run other versions of itself from the cache. When you tell your deployed instance of Zero Install to [update itself](#maintenance) it does just that: The new version is downloaded and launched from the cache and instructed to deploy itself to the same location as the existing deployment. The old files are securely replaced using the Windows Restart Manager and rollbacks in case of error.

### Command-line

The regular Bootstrapper (`zero-install.exe`) is a GUI application, but there is also a command-line version (`0install.exe`) available. If you pass command-line arguments to this executable it will first download the full version of `0install` and then pass those arguments through. This makes it great for single-use applications or scripting:

```shell
Invoke-WebRequest https://get.0install.net/0install.exe -OutFile 0install.exe
.\0install.exe run http://example.com/somefeed.xml
```

You can also use the command-line version of the Bootstrapper to integrate Zero Install into the desktop environment:

```shell
.\0install.exe self deploy
```

## Branding

Zero Install for Windows can present its progress windows as part of the application being downloaded rather than as Zero Install itself. This is called **branding**. It uses metadata from the feed of the application:

| Feed element                                                                       | Used for                                                                                                            |
| ---------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------- |
| [`<name>`](../specifications/feed.md#introduction)                                 | Window title, tray icon tooltip and notification title (instead of "Zero Install")                                  |
| [`<icon type="image/vnd.microsoft.icon">`](../specifications/feed.md#introduction) | Window icon, tray icon and notifications                                                                            |
| [`<splash-screen type="image/png">`](../specifications/feed.md#introduction)       | Image shown at the top of the progress window                                                                       |
| [`app-id`](../specifications/feed.md#entry-points) on the `run` entry point        | Taskbar grouping, only in [library mode](library-mode.md#windows-treats-zero-installs-windows-as-your-applications) |

Branding is applied by every command that works on a single application, such as `0install run`, `0install download`, `0install update`, `0install add` and `0install integrate`.

### When branding is available

Zero Install only looks for branding in places it has already stored locally. It does not download anything just to show branding, because that would delay the actual work. Branding therefore appears only if:

- the feed is already in the [cache](cache.md), and
- the icon and splash screen have already been downloaded for [desktop integration](desktop-integration.md).

The icon and splash screen are downloaded when the application is [integrated](cli.md#integrate) (`0install add`, `0install integrate` or the GUI). They are also included in [exports](export.md).

In practice, this means:

- The first `0install run` of a feed that has never been used on the machine shows the generic Zero Install window.
- Once the application has been integrated into the desktop, all later downloads and updates for it (e.g., when launching it from the start menu) show the branding.
- A [0bootstrap](../tools/0bootstrap.md) installer embeds the splash screen in the EXE and shows it in its own window right away. With `--integrate-args`, Zero Install integrates the application, so the branding is also available for all later updates.

### Designing a splash screen

The splash screen area of the progress window is 560×250 pixels at 100% display scaling. Images with a different size are scaled to fit, keeping their aspect ratio. Provide a PNG file with a 560:250 aspect ratio, ideally 1120×500 pixels, so that the image stays sharp on high-DPI displays.

Progress bars and the list of downloaded components are shown below the image. When more than one component is being downloaded, the list overlaps the bottom quarter of the image, so avoid putting important content there.

## Maintenance

Zero Install is designed to be mostly maintenance-free (automatic update checks, etc.). You can use the following [commands](cli.md) for additional control where needed:

Deploy Zero Install for the current user
: `.\0install.exe self deploy`

Deploy Zero Install for all users
: `.\0install.exe self deploy --machine`

Deploy Zero Install in [library mode](library-mode.md), as a component of another application
: `.\0install.exe self deploy --library`

Remove Zero Install from the system
: `0install self remove`

Download and install updates for Zero Install itself
: `0install self update`

Download and install updates for [integrated applications](desktop-integration.md) and remove outdated files
: `0install update-all --clean`

Find and merge any duplicate files in the [cache](cache.md)
: `0install store optimise`

If you wish to run these commands in automated scripts, add the argument `--batch` to prevent interactive prompts.

On managed machines, administrators can pre-set Zero Install's configuration centrally and prevent users from changing it using [Windows Group Policy](policy-settings.md#windows-group-policy).

## Portable mode

To set up Zero Install on a USB thumb drive:

- Connect a thumb drive to the computer and make sure there are no files you still need on it.
- Format the thumb drive with NTFS (FAT32 will not work, see [technical details](#technical-details) for explanation).
- Download and run Zero Install for Windows.
- Select "Tools" and "Portable Creator" at the bottom of the window.
- Follow the instructions on screen.

![The Portable Creator](../img/screens/0install-win/portable-creator.png)

You can now use your thumb drive to run Zero Install on any computer running a recent version of Windows. Zero Install stores downloaded applications directly on the stick so you can access the same applications everywhere. Please remember to always "eject" the thumb drive in Windows before disconnecting it from the computer.

### Limitations

The applications launched by Zero Install are not automatically made portable by this. They still store their settings in the usual locations. Please make sure to move these files to the thumb drive as necessary.

Portable versions of Zero Install cannot perform desktop integration (e.g. create start menu entries). Consider using regular Zero Install on multiple computers with [Zero Install Sync](sync.md) instead.

### Technical details

FAT/FAT32-formatted drives cannot be used for Zero Install because they do not store file security settings (ACLs). They also only store time with an accuracy of two seconds while Zero Install checks the exact modification time of files.

The portable creator creates a file in the destination directory called `_portable`, which instructs Zero Install to run in portable mode. When this file is detected Zero Install stores all its files in its installation directory instead of the [usual system directories](file-locations.md).
