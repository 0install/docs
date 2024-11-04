# 0publish-gui

Publishing a program using Zero Install requires you to create an XML file listing the available versions, where to get them, and what other software they depend on. This program provides a simple graphical interface for creating and editing these feeds.

=== "Linux / macOS"
    !!! info ""
        **Maintainer:** Thomas Leonard  
        **License:** GNU Lesser General Public License  
        **Source:** <https://github.com/0install/0publish-gui>  
        **Zero Install feed:** <https://apps.0install.net/0install/0publish-gui.xml>

    See [the packaging guide](../packaging/guide-gui.md).

=== "Windows"
    !!! info ""
        **Maintainer:** Bastian Eicher  
        **License:** GNU Lesser General Public License  
        **Source:** <https://github.com/0install/0publish-gui-dotnet>  
        **Zero Install feed:** <https://apps.0install.net/0install/0publish-gui.xml>

    0publish-gui for Windows (previously known as Zero Install Publishing Tools) provides [a graphical feed editor](#feed-editor) and a [new feed wizard](#new-feed-wizard).

    You can add 0publish-gui to your start menu like this:

    ```shell
    0install integrate https://apps.0install.net/0install/0publish-gui.xml
    ```

    ## Feed Editor

    - split with screen graphical and XML view
    - changes in the graphical view are reflected in the XML view immediately and vice-versa
    - syntax-highlighting and error-underlining in the XML view

    ![Feed Editor screenshot - Main](../img/screens/0publish-win/main.png)

    ![Feed Editor screenshot - Archive](../img/screens/0publish-win/archive.png)

    ![Feed Editor screenshot - XML Editor](../img/screens/0publish-win/xml-error.png)

    ## New Feed Wizard

    - walks you through the entire feed creation process
    - detects entry points (executables) in archives
    - automatically creates appropriate `<runner>`s for Java, .NET and Python executables
    - extracts metadata (name, version number, etc.) where possible

    ![Feed Wizard screenshots](../img/screens/0publish-win/wizard.gif)
