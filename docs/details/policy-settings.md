You can change policy settings to affect when 0install looks for updates and which versions it prefers.

The first part shows how to set policy settings that apply to all applications of the current user. The last section shows how to change per-application settings. Policy affects which versions 0install chooses (do you want test versions, ...).

[TOC]

# General policy settings

You can change the policy settings using the Preferences dialog. To open it run `0install config` (`0install-win config` on Windows).

If you use the GNOME/KDE menu: choose **Zero Install -> Manage Applications** from the **Applications** menu, click on the edit properties icon next to an application and click Preferences:

![0desktop --manage](../img/screens/manage-apps.png)

You can affect which versions are chosen by changing your policy. Three aspects of your policy are shown in the Preferences window: **Network use**, **Freshness** and **Help test new versions**:

![The Preferences dialog](../img/screens/preferences.png)

If you use Zero Install for Windows: click on **Options** in the bottom left to get this interface:

![The Configuration dialog](../img/screens/0install-win/config.png)

## Network use

Affects how much 0install will rely on the network. Possible values are:

Full
: Normal network use.

Minimal
: 0install will prefer cached versions over non-cached ones.

Off-line
: 0install will not use the network.

## Freshness

0install caches its feeds as well. It checks for updates to the feeds from time to time. The freshness indicates how old a feed may get before 0install automatically checks for updates to it. Note that 0install only checks for updates when you actually run a program; so if you never run something, it won't waste time checking for updates.

If you notice a feed is out of date, you can force 0install to look for updates by clicking the **Refresh all now** button

## Help test new versions

By default, 0install tries not to select new versions while they're still in the "testing" phase. If checked, 0install will instead always select the newest version, even if it's marked as "testing".

Note that all changes to your policy are saved as soon as you make them. Clicking on **Cancel** will close the window without running the program, but any changes made to the policy are not reversed.

# Per-application policy settings

You can change per-application policy settings in the application information dialog. There are multiple ways to opening this dialog:

1.  -   Run "0install run" with the `--gui` (`--customize` on Windows) option and the URI of the application
        
        `$ 0install run --gui http://rox.sourceforge.net/2005/interfaces/Edit`

        `> 0install run --customize http://repo.roscidus.com/utils/vlc`
        
    -   Run "0install update" with a shortcut you made as first argument
        
        `$ 0install update --gui rox-edit`

        `> 0install update --customize vlc`
        
    -   If you use the GNOME/KDE menu: choose **Zero Install -> Manage Applications** from the **Applications** menu, click on the edit properties icon next to the application:
        
        ![0desktop --manage](../img/screens/manage-apps.png)
        
2.  Double-click the application in the list. For example, double-clicking on **Edit** displays this dialog box:
    
    ![Properties of the Edit interface](../img/screens/edit-properties.png)
    
## Feeds

In the Feeds tab, a list of feeds shows all the places where Zero Install looks for versions of Edit. By default, there is just one feed, whose URL is simply Edit's URI; you can view it in a web browser if you're interested: [Edit's default feed](http://rox.sourceforge.net/2005/interfaces/Edit). This is an XML file with a GPG signature at the end. The downloaded feed files are stored locally in `~/.cache/0install.net/interfaces` (see [File locations](file-locations.md)).

## Versions

The Versions tab shows all the versions found in all of the feeds:

![Versions of the Edit](../img/screens/edit-versions.png)

![Zero Install for Windows - Versions](../img/screens/0install-win/versions.png)

You can use the **Preferred Stability** setting in the interface dialog to choose which versions to prefer. You can also change the stability rating of any implementation by clicking on it and choosing a new rating from the popup menu (drop-down in the **Override** column on Windows). User-set ratings are shown in capitals.

As you make changes to the policy and ratings, the selected implementation will change. The version shown in bold (or at the top of the list, in some versions) is the one that will actually be used. In addition to the ratings below, you can set the rating to **Preferred**. Such versions are always preferred above other versions, unless they're not cached and you are in Off-line mode.

The following stability ratings are allowed:

- Stable (this is the default if **Help test new versions** is unchecked)
- Testing (this is the default if **Help test new versions** is checked)
- Developer
- Buggy
- Insecure

Stability ratings are kept independently of the implementations, and are expected to change over time. When any new release is made, its stability is initially set to **Testing**. If you have selected **Help test new versions** in the Preferences dialog box then you will then start using it. Otherwise, you will continue with the previous stable release. After a while (days, weeks or months, depending on the project) with no serious problems found, the author will change the implementation's stability to **Stable** so that everyone will use it.

If problems are found, it will instead be marked as **Buggy**, or **Insecure**. Neither will be selected by default, but it is useful to see the reason (you might opt to continue using a buggy version if it works for you, but should never use an insecure one). **Developer** is like a more extreme version of **Testing**, where the program is expected to have bugs.

!!! note
    If you want to use the second item on the list because the first is buggy, for example, then it is better to mark the first version as buggy than to mark the second as preferred. This is because when a new version is available, you will want that to become the version at the top of the list, whereas a preferred version will always be first.
