# Kiosk mode

!!! note
    This is currently only supported in [Zero Install for Windows](windows.md).

**Kiosk mode** restricts Zero Install to the applications listed in the [catalogs](../specifications/catalog.md) registered on the machine. With it enabled, a user can still run, update and integrate anything the catalog offers, but cannot point Zero Install at an arbitrary feed URL of their own.

It is intended for managed machines (shared workstations, point-of-sale terminals, exam or lab computers, locked-down corporate desktops, etc.) where the set of available software is decided by whoever administers the machine rather than by whoever is sitting at it.

## Enabling it

Kiosk mode is a [configuration setting](policy-settings.md) named `kiosk_mode`. It is off by default.

=== "Command-line"

    ```shell
    0install config kiosk_mode true
    ```

    Check the current value with `0install config kiosk_mode`, and turn it off again with `0install config kiosk_mode default`.

=== "GUI"

    Open the Configuration dialog (`0install-win config` or **Options** in the main GUI), switch to the **Advanced** tab, confirm the warning, and set **Kiosk mode** under **Restrictions**.

=== "Bootstrapper"

    A [0bootstrap](../tools/0bootstrap.md)-generated installer can set the option and the catalog at the same time, so a fresh machine comes up already restricted:

    ```shell
    0bootstrap https://feeds.corp.example/my-app.xml \
      --config kiosk_mode=true \
      --catalog-uri=https://feeds.corp.example/catalog.xml
    ```

    Bundled settings are only applied if Zero Install is not already deployed on the target machine (or is deployed in [library mode](library-mode.md)).

Written to a config file, the setting lives in the `[global]` section of `injector\global` — under `%APPDATA%` for the current user or `%PROGRAMDATA%` for the whole machine (see [File locations](file-locations.md)):

```ini
[global]
kiosk_mode = True
```

## What changes

### Feed URIs are checked against the catalog

Before acting on a feed URI, Zero Install checks that it appears in a registered catalog and refuses otherwise:

```shell
$ 0install run https://apps.0install.net/gui/vlc.xml
Kiosk mode is enabled and https://apps.0install.net/gui/vlc.xml is not listed in the catalog.
```

The check applies to the URI you pass to [`select`](cli.md#select), [`download`](cli.md#download), [`update`](cli.md#update), [`run`](cli.md#run), [`add`](cli.md#add), [`integrate`](cli.md#integrate), [`add-feed`](cli.md#add-feed) and [`remove-feed`](cli.md#remove-feed) — the entry points through which a user names software. The [self-update feed](cli.md#self-update) is always exempt, so Zero Install can keep updating itself even if it is not in the catalog.

The cached copy of the catalog is consulted first; only if the URI is not found there does Zero Install re-download the catalog to check whether it has been added since.

### Machine-wide catalog sources take precedence

Zero Install reads its list of catalog sources from a `injector\catalog-sources` file, and normally a per-user file under `%APPDATA%` overrides the machine-wide one under `%PROGRAMDATA%`. In kiosk mode the precedence is reversed: if a machine-wide `catalog-sources` file exists, it is the one that counts, and a user cannot widen the set of available applications by registering an extra catalog of their own with [`0install catalog add`](cli.md#catalog-add).

If there is no machine-wide `catalog-sources` file, the per-user one is still used — so write the machine-wide file as part of the same step that turns kiosk mode on:

```shell
0install catalog add https://feeds.corp.example/catalog.xml
0install catalog remove https://apps.0install.net/catalog.xml
```

Run this elevated, from your provisioning script, so that it lands in `%PROGRAMDATA%`.

## Locking it down with group policy

A user who can write to their own config file can turn `kiosk_mode` back off. To make the setting stick, set it as a [Windows Group Policy](policy-settings.md#windows-group-policy) instead.

## Limitations

Kiosk mode is a guard rail for ordinary use, not a security boundary. It constrains what Zero Install will do on a user's behalf; it does not constrain a user who can run arbitrary code by other means. In particular:

- Only the URI you name is checked. Dependencies pulled in by a catalog-listed feed are resolved normally, so the catalog's authors decide transitively what can be downloaded.
- If the catalog cannot be downloaded and the URI is not in the cached copy, the request is allowed rather than blocked. This keeps a machine usable when the catalog server is unreachable, but it means kiosk mode cannot be relied upon to hold while the network is degraded.
- A user who can write to the registry policy key, install their own copy of Zero Install, or simply download and run a program without Zero Install is not stopped by any of this.

For stronger guarantees, combine kiosk mode with the usual operating-system controls — a non-administrative account, application allow-listing, and a machine-wide Zero Install deployment the user cannot replace.
