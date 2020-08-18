# Export

Zero Install automatically takes care of downloading applications and their dependencies when you run them on your computer. However, it is sometimes useful to download everything required to run an application and export it, e.g., for use on machine without an internet connection, or where the connection is very slow.

=== "Windows"

    You can use the command [`0install export`](cli.md#export) to generate a directory with archives holding all required implementations and a small [Bootstrapper](windows.md#bootstrapper) executable for importing them and setting up Zero Install on systems that don't have it yet.

    !!! example
        ```shell
        0install export --include-zero-install https://apps.0install.net/gui/vlc.xml somedir
        ```

        The resulting directory structure would look like this:

        ```
        somedir
         ├─ import.cmd
         │ Script for importing the content on a machine that already has 0install.
         │
         ├─ run VLC media player.exe
         │ Version of the bootstapper pre-configured for importing the content and then running VLC.
         │ Also works on a machine that does not have 0install set up yet.
         │
         └─ content
             ├─ https%3a##apps.0install.net##gui#vlc.xml
             ├─ https%3a##apps.0install.net#0install#0install-win.xml
             ├─ https%3a##apps.0install.net#dotnet#framework.xml
             │ The downloaded feeds.
             │
             ├─ 22EA111A7E4242A4.gpg
             ├─ 85A0F0DAB46EE668.gpg
             │ GnuPG keys used to sign the feeds.
             │
             ├─ sha256new_K44G7XQ4SOWRHVVFSXDW737RFQAKICZE6MAX35OJ7DJHABZKSLVQ.tbz2
             └─ sha256new_Z7MMJYZMBDNZMQKRUNOA3IEWGB7AXITJWCLK7RRXFIQ2EVBUX5JQ.tbz2
              Implementations selected for VLC and Zero Install compressed as archives named by digest.
        ```

    You can also export individual implementations from the [cache](cache.md) using the command [`0install store export`](cli.md#store_export).

    !!! example
        ```shell
        0install store export sha256new_K44G7XQ4SOWRHVVFSXDW737RFQAKICZE6MAX35OJ7DJHABZKSLVQ vlc-win64-3.0.6.tbz2
        ```

=== "Linux / macOS"

    You can use the tool [0export](../tools/0export.md) to create self-installing bundles.
    