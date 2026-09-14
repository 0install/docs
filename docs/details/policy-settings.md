# Policy settings

You can change policy settings to affect when 0install looks for updates and which versions it prefers.

The first part shows how to set policy settings that apply to all applications of the current user. The last section shows how to change per-application settings. Policy affects which versions 0install chooses (do you want test versions, ...).

## General policy settings

=== "Linux"

    You can change the policy settings using the Preferences dialog.

    To open it run `0install config` or choose **Zero Install -> Manage Applications** from the **Applications** menu, click on the edit properties icon next to an application and click Preferences.

    ![The Preferences dialog](../img/screens/preferences.png)

=== "Windows"

    You can change the policy settings using the Configuration dialog.

    To open it run `0install-win config` or click on the **Options** in the bottom left of the main GUI.

    ![The Configuration dialog](../img/screens/0install-win/config.png)

    Administrators can pre-set and lock any of these settings using [Windows Group Policy](#windows-group-policy).

### Network use

Affects how much 0install will rely on the network. Possible values are:

| Value    | Effect                                                     |
| -------- | ---------------------------------------------------------- |
| Full     | Normal network use.                                        |
| Minimal  | 0install will prefer cached versions over non-cached ones. |
| Off-line | 0install will not use the network.                         |

### Freshness

0install caches feeds and checks for updates from time to time. The freshness indicates how old a feed may get before 0install automatically checks for updates to it. Note that 0install only checks for updates when you actually run a program; so if you never run something, it won't waste time checking for updates.

### Help test new versions

By default, 0install tries not to select new versions while they're still in the "testing" phase. If checked, 0install will instead always select the newest version, even if it's marked as "testing".

### Windows Group Policy

On Windows, 0install reads settings from two registry keys in addition to its [config files](file-locations.md):

```
HKEY_LOCAL_MACHINE\SOFTWARE\Policies\Zero Install
HKEY_CURRENT_USER\SOFTWARE\Policies\Zero Install
```

A machine policy takes precedence over a user policy, and both take precedence over any config file. A setting that is present in either key is also **locked**: `0install config` and the Configuration dialog refuse to change it.

0install ships an ADMX template so the settings appear in the Group Policy editor. Get [`0install.admx` and `*\0install.adml`](https://github.com/0install/0install-win/tree/master/group-policies) and copy them into either

- `%WINDIR%\PolicyDefinitions` on a single machine, or
- `\\<domain>\SYSVOL\<domain>\Policies\PolicyDefinitions` for the domain Central Store

The settings then show up under **Computer Configuration → Administrative Templates → Zero Install** and under **User Configuration** for the per-user variant.

#### Setting values directly

If you provision the registry yourself, write the values under the policy key using the same names 0install uses in its config file:

| Value name               | Type        | Notes                                                |
| ------------------------ | ----------- | ---------------------------------------------------- |
| `network_use`            | `REG_SZ`    | `full`, `minimal` or `off-line`                      |
| `freshness`              | `REG_DWORD` | In seconds. `604800` is one week.                    |
| `help_with_testing`      | `REG_DWORD` | `1` or `0`                                           |
| `auto_approve_keys`      | `REG_DWORD` | `1` or `0`                                           |
| `max_parallel_downloads` | `REG_DWORD` | 1 to 128                                             |
| `feed_mirror`            | `REG_SZ`    | Empty string disables the [feed mirror](servers.md). |
| `key_info_server`        | `REG_SZ`    | Empty string disables the key information server.    |
| `self_update_uri`        | `REG_SZ`    | Empty string disables self-update.                   |
| `prefer_sat_solver`      | `REG_DWORD` | `1` or `0`                                           |
| `sync_server`            | `REG_SZ`    | See [Sync](sync.md).                                 |
| `sync_server_user`       | `REG_SZ`    |                                                      |
| `sync_server_pw`         | `REG_SZ`    | Stored as plain text; see the warning below.         |
| `sync_server_kerberos`   | `REG_DWORD` | `1` or `0`                                           |
| `sync_crypto_key`        | `REG_SZ`    | Stored as plain text; see the warning below.         |
| `kiosk_mode`             | `REG_DWORD` | `1` or `0`. See [Kiosk mode](kiosk-mode.md).         |

Booleans accept either a `REG_DWORD` of `1` / `0` or a `REG_SZ` of `True` / `False`.

!!! attention
    `sync_server_pw` and `sync_crypto_key` are read from the registry verbatim. In the config file these are stored base64-encoded (as `sync_server_pw_base64` and `sync_crypto_key_base64`), but that is obfuscation rather than encryption and does not apply here at all. Anyone who can read the policy key can read these values. Prefer `sync_server_kerberos` for authentication, and deploy per-user secrets by another route.

!!! tip
    To check what a machine actually ended up with, run `0install config` with no arguments. It prints the effective value of every setting, including the ones coming from group policy.

## Per-application policy settings

You can change per-application policy settings in the application information dialog. To open this dialog:

=== "Linux"

    1.  Run `0install run` with the `--gui` option and the URI of the application:
        ```shell
        0install run --gui http://rox.sourceforge.net/2005/interfaces/Edit
        ```

        -or-

        Choose **Zero Install -> Manage Applications** from the **Applications** menu, click on the edit properties icon next to the application.  
        ![0desktop --manage](../img/screens/manage-apps.png)

    2.  Double-click the application in the list. For example, double-clicking on **Edit** displays this dialog box:  
        ![Properties of Edit](../img/screens/edit-properties.png)  
        ![Versions of Edit](../img/screens/edit-versions.png)

=== "Windows"

    1.  Run `0install run` with the `--customize` option and the URI of the application:
        ```shell
        0install run --customize https://apps.0install.net/gui/vlc.xml
        ```

        -or-

        In the main GUI open the dropdown menu next to an App's **Run** button, select **Run with options**, set the **Customize version** checkbox and click **OK**.  
        ![Run options](../img/screens/0install-win/run-options.png)

    2.  Click on the **Change** link next to the application. This displays this dialog box:  
        ![Versions](../img/screens/0install-win/versions.png)

### Feeds

In the Feeds tab, a list of feeds shows all the places where Zero Install looks for versions of the app. By default, there is just one feed with the URL you just entered. You can register additional feeds to be considered (e.g., a [local feed](../packaging/local-feeds.md) with custom builds or an alternate remote feed). This can be done either using the GUI or with the [`0install add-feed`](cli.md#add-feed) command.

### Versions

In the Versions tab, you can use the **Preferred Stability** setting in the interface dialog to choose which versions to prefer. You can also change the stability rating of any implementation by clicking on it and choosing a new rating from the popup menu (drop-down in the **Override** column on Windows). User-set ratings are shown in capitals.

As you make changes to the policy and ratings, the selected implementation will change. The version shown in bold (or at the top of the list, in some versions) is the one that will actually be used. In addition to the ratings below, you can set the rating to **Preferred**. Such versions are always preferred above other versions, unless they're not cached and you are in Off-line mode.

The following stability ratings are allowed:

- Stable (this is the default if **Help test new versions** is unchecked)
- Testing (this is the default if **Help test new versions** is checked)
- Developer
- Buggy
- Insecure

Stability ratings are kept independently of the implementations, and are expected to change over time. When any new release is made, its stability is initially set to **Testing**. If you have selected **Help test new versions** in the Preferences dialog box then you will then start using it. Otherwise, you will continue with the previous stable release. After a while (days, weeks or months, depending on the project) with no serious problems found, the author will change the implementation's stability to **Stable** so that everyone will use it.

If problems are found, it will instead be marked as **Buggy**, or **Insecure**. Neither will be selected by default, but it is useful to see the reason (you might opt to continue using a buggy version if it works for you, but should never use an insecure one). **Developer** is like a more extreme version of **Testing**, where the program is expected to have bugs.

!!! tip
    If you want to use the second item on the list because the first is buggy, for example, then it is better to mark the first version as buggy than to mark the second as preferred. This is because when a new version is available, you will want that to become the version at the top of the list, whereas a preferred version will always be first.
