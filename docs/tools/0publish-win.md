# 0publish-win

!!! info ""
    **Maintainer:** Bastian Eicher  
    **License:** GNU Lesser General Public License  
    **Source:** <https://github.com/0install/0publish-win>  
    **Zero Install feed:** <https://apps.0install.net/0install/0publish-win.xml>

The Zero Install Publishing Tools allow you to create your own feeds on Windows.

You need Zero Install to run the Publishing Tools. You can either search for "Zero Install Publishing Tools" in the Catalog or type this on the command-line:

```shell
0install run https://apps.0install.net/0install/0publish-win.xml
```

The Zero Install Publishing Tools provide [a graphical feed editor](#feed-editor) and a [new feed wizard](#new-feed-wizard).

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
