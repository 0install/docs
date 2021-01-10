# Cache

Everything 0install downloads from the net by default goes in `~/.cache/0install.net/` on Linux or `%localappdata%\0install.net` on Windows (see [File locations](file-locations.md)). Every application/library unpacks into a directory in that cache. So, there's one directory for Visual Studio Code, and another for Blender, etc. In fact, there's one directory for every version of Visual Studio Code, in case you want more than one available. Every directory is uniquely named, so you'll never get conflicts when trying to install two different programs.

The idea is that you don't need to backup `~/.cache`, because you can always download the stuff again. For example, if you delete the whole `~/.cache/0install.net/` directory and then click on Visual Studio Code, it will just prompt you to download it again. The cache is just to make things faster (and work when offline), but you don't really need to worry about it.

## Sharing Implementations

### Between users of the same system

0install can be configured to store its cache in `/var/cache/0install.net/` on Linux or `C:\ProgramData\0install.net` on Windows. This allows sharing between users. The use of cryptographic digests makes this safe; users don't need to trust each other not to put malicious code in the shared cache.

*   See: [Enabling sharing between users](sharing.md)

### Between virtual machines

You can also share the cache between virtual machines:

*   See: [Enabling sharing between virtual machines](virtual-machines.md)

### Between machines using P2P

!!! warning
    This is still experimental.

Using [0share](../tools/0share.md) you can locally distribute your implementations (versions of programs) via a peer-to-peer protocol.

## Removing Implementations

If for some reason you would like to remove implementations from the cache (it does not make your system any 'cleaner', but it does free some disk space), you can do so using the Zero Install Cache dialog.

=== "Linux / macOS"
    Click on the **Show Cache** button in the **Manage Programs** box to get the cache explorer (or run `0install store manage`). Select the versions you don't need anymore and click on **Delete**.

    ![Uninstalling programs](../img/screens/injector-cache.png)

=== "Windows"
    Open the main GUI of Zero Install and click on **Tools** and **Cache management** (or run `0install store manage`). Select the versions you don't need anymore and click on **Remove**.

    ![Zero Install for Windows - Cache management](../img/screens/0install-win/cache-management.png)

    You can also run [`0install update-all --clean`](cli.md#update-all) to update all apps registered with [desktop integration](desktop-integration.md) and remove any old versions from the cache afterwards.

!!! tip
    You can delete the entire cache, 0install will redownload whatever it needs later.
