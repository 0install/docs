# 0bootstrap

**0bootstrap** generates a native package or installer that installs 0install itself along with a an app launched via 0install.

=== "Linux / macOS"
    !!! info ""
        **Maintainer:** Thomas Leonard  
        **License:** GNU General Public License  
        **Source:** <https://github.com/0install/0bootstrap>  
        **Zero Install feed:** <https://apps.0install.net/0install/0bootstrap.xml>

    !!! warning
        The Linux version of this program is still experimental.

    Normally, a launcher is created by passing a feed URI to the ["Add Zero Install Program" utility](../basics/menu.md). This downloads the appropriate feeds, their icons - if available, and makes a shortcut (creates a launcher, a .desktop file).

    However, it is sometimes useful to bundle such a launcher and its icon together in a .deb package (for example), so that it can be installed on machines that don't have Zero Install available initially - by using the standard native package installation procedure.

    0bootstrap takes the URI of a program and creates a native package; currently the .deb and .rpm package formats are supported by the tool.

    Programs launched using these packages are added to the Zero Install cache and are therefore still [shared between users](../details/sharing.md), and will get updates over the web where possible.

    ## Using a 0bootstrap package

    Open the file with your file manager, or run `gdebi-gtk edit.deb` in a terminal:

    ![Installing a 0bootstrap native package](../img/screens/0bootstrap-install.png)

    The package will require Zero Install in order to install, as seen in the Details view:

    ![Dependency details](../img/screens/0bootstrap-details.png)

    Installing native packages requires authentication.

    ![Authenticate](../img/screens/0bootstrap-authenticate.png)

    The installation will add the program to your menus.

    ![Installation finished](../img/screens/0bootstrap-finish.png)

    ## Installing 0bootstrap

    Since this program is not yet released, you need to run it from the source repository:

    ```shell
    git clone git://zero-install.git.sourceforge.net/gitroot/zero-install/bootstrap
    cd bootstrap
    0install add-feed 0bootstrap.xml
    0install add 0bootstrap 0bootstrap.xml
    ```

    ## Creating a package for your program

    Run 0bootstrap, passing in the package format and the name (URI) of the main program. For example, to create an Ubuntu package for Edit:

    ```shell
    0bootstrap --format=deb http://rox.sourceforge.net/2005/interfaces/Edit
    ```

    The resulting edit.deb package can now be installed on a Ubuntu machine.

=== "Windows"
    !!! info ""
        **Maintainer:** Bastian Eicher  
        **License:** GNU Lesser General Public License  
        **Source:** <https://github.com/0install/0bootstrap-dotnet>  
        **Zero Install feed:** <https://apps.0install.net/0install/0bootstrap.xml>

    To setup 0bootstrap on your system you can run:

    ```
    0install add 0bootstrap https://apps.0install.net/0install/0bootstrap.xml
    ```

    You can then pass it a feed for which you'd like to create a bootstrapper as a command-line argument.

    !!! example
        ```shell
        0bootstrap https://apps.0install.net/gui/vlc.xml
        ```

        This will create a new file named `VLC media player.exe`, name taken from the `<name>` tag in the feed, in the current working directory. The file will have the VLC icon, taken from `<icon>` tag in the feed.

    By default, this bootstrapper will simply download 0install (if it is not already present) and then use it to run the feed. You can also configure the bootstrapper to perform [desktop integration](../details/desktop-integration.md). You can use `--integrate-args=` to specify which arguments should be passed to [`0install integrate`](../details/cli.md#integrate).

    !!! example
        ```shell
        0bootstrap https://apps.0install.net/gui/vlc.xml --integrate-args="--add-standard"
        ```

        This causes the bootstrapper to run `0install integrate https://apps.0install.net/gui/vlc.xml --add-standard`, adding the app to the start menu and registering its supported file types.

    You can bundle the output of [`0install export`](../details/export.md) into a bootstrapper to create a self-contained "offline installer" using `--content=`.

    !!! example
        ```shell
        0install export https://apps.0install.net/gui/vlc.xml vlc-export
        0bootstrap https://apps.0install.net/gui/vlc.xml --content=vlc-export\content
        ```

    For further command-line arguments, see the output of `0bootstrap --help`.

## FAQ

What about security?
:   Installing a package isn't a great way to make a shortcut. The normal Zero Install process of dragging a feed link to a trusted installation program is much better. However, distributions have been very slow to support this. 0bootstrap is an attempt to boot-strap the adoption process. The native package is required to work with the operating system's package installation tools, and can be automatically created by a web service given the feed's URI.
