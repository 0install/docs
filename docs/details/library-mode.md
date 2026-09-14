# Library mode

!!! note
    This is currently only supported in [Zero Install for Windows](windows.md).

**Library mode** deploys Zero Install as a supporting component of another application rather than as a product in its own right. The end user gets your application; Zero Install runs underneath it, updating and repairing it, without ever presenting itself as something they installed or need to manage.

This is what you want when you ship an application that happens to use Zero Install for delivery and updates. The alternative, a [normal deployment](windows.md#deployment), is what you want when the user deliberately chose to install Zero Install itself.

## Enabling it

Pass `--library` to [`0install self deploy`](cli.md#self-deploy):

```shell
.\0install.exe self deploy --library
```

Add `--machine` to deploy for all users instead of just the current one:

```shell
.\0install.exe self deploy --library --machine
```

`--library` cannot be combined with `--portable`; a [portable installation](windows.md#portable-mode) is never registered with the system, so there is nothing to hide.

Library mode is recorded in the registry next to the deployment path, as a `LibraryMode` value under `HKEY_CURRENT_USER\SOFTWARE\Zero Install` (or `HKEY_LOCAL_MACHINE\SOFTWARE\Zero Install` for `--machine`). It is a property of the deployment, not a configuration setting, and it is preserved across [self-updates](cli.md#self-update).

Zero Install deploys itself in library mode on its own, when a command that requires a deployed instance is run from an instance that is not deployed, and there is no other deployment on the system to hand the command to.

The commands that require a deployed instance are the ones that write [desktop integration](desktop-integration.md), because the shortcuts and stub executables they create must point at a permanent location. When one of these runs from a non-deployed instance (typically a copy running out of the [cache](cache.md) after being started by a [bootstrapper](windows.md#bootstrapper)) Zero Install:

1. Looks for an existing deployment on the system and re-runs the command there if it finds one. Library mode is not involved in this case.
2. If there is none, runs `0install self deploy --library` for itself, adding `--machine` if the original command asked for a machine-wide operation.
3. Re-runs the original command in the newly deployed instance. The re-run is flagged internally so that it cannot bounce a second time.

!!! tip
    This is how most library-mode deployments come about in practice: an installer built with [`0bootstrap --integrate-args=…`](../tools/0bootstrap.md) runs `0install integrate` out of the cache on a machine that has never seen Zero Install, and that deploys it in library mode. That is the intended outcome: the user installed *your application*, not Zero Install, so Zero Install stays out of their start menu and their list of installed programs. You rarely run `0install self deploy --library` by hand.

## What changes

### Zero Install stays out of the user interface

|                                                          | Normal deployment | Library mode |
| -------------------------------------------------------- | ----------------- | ------------ |
| Entry in **Apps & features** / **Programs and Features** | yes               | no           |
| **Zero Install** shortcut in the start menu              | yes               | no           |
| Installation directory added to `PATH`                   | yes               | yes          |

The user can neither uninstall Zero Install from the Windows settings nor open the [Zero Install GUI](cli.md#central) from the start menu. The command-line tools are still on the `PATH`, but nothing invites the user to manage it.

Because the user has no obvious way to remove Zero Install, library mode removes it automatically: when the last [integrated application](desktop-integration.md) is removed (via `0install remove` or `0install remove-all`), Zero Install runs `0install self remove` for itself in the background. A library-mode `self remove` also defaults to purging the [implementation cache](cache.md) when no other Zero Install deployment is left on the machine, so uninstalling your application really does reclaim the disk space.

### Windows treats Zero Install's windows as your application's

Zero Install's progress windows and notifications normally identify themselves as Zero Install. In library mode they instead adopt the Application User Model ID declared by the [`app-id` attribute](../specifications/feed.md#entry-points) of the feed's `<entry-point>`. Download progress for your application groups under your application's taskbar button and its toast notifications carry your application's name and icon, rather than appearing as a second, unrelated program.

This only applies if the feed actually declares an `app-id`; without one, Zero Install falls back to its generic windows.

### Maintenance happens on its own

A normal deployment relies on the user opening the GUI to check for updates, clean out old versions or run an integrity check. In library mode there is no GUI to open, so Zero Install does that work by itself:

Removing outdated implementations
:   Machine-wide library-mode deployments get a second scheduled task, **Zero Install → Update apps**, which runs `0install update-all --machine --clean` weekly (alongside the **Self update** task that every deployment gets). Per-user library-mode deployments instead start a background `0install update-all --clean` after a `run` or `download` that did not need to fetch anything new, rate-limited so it happens at most once a day.

Tolerating missing permissions
:   `update-all --clean` normally refuses to run when removing cached implementations would require administrator rights. In library mode it proceeds anyway and silently skips the implementations it cannot touch, rather than failing in a background task nobody sees.

Repairing the cache
:   If launching an application fails because an executable file is missing from the cache, or if an existing desktop integration is modified through the GUI, Zero Install automatically runs an [integrity check](cli.md#store-audit) of the implementation cache instead of reporting an error the user cannot act on.

### Bootstrappers may reconfigure it

A [bootstrapper](windows.md#bootstrapper) normally refuses to overwrite the configuration of an existing Zero Install deployment — it assumes the user configured it deliberately. A library-mode deployment is treated as unconfigured: a bootstrapper for a second application will apply its own embedded [configuration](policy-settings.md) and [catalog](../specifications/catalog.md) on top of it.

This lets several applications that each bundle Zero Install be installed in any order without the first one's settings winning permanently. Keep it in mind if you rely on a specific configuration surviving: pin it with a machine-wide config file instead.

## Interaction with normal deployments

A machine can hold one per-user and one machine-wide deployment, and library mode is tracked separately for each. If a user later installs Zero Install normally, the new deployment is registered without library mode and behaves like any other. Your library-mode deployment in the other scope is unaffected and keeps its own registration.

Deploying normally over a library-mode deployment in the same scope (`0install self deploy` without `--library`) upgrades it: the uninstall entry and start menu shortcut appear, the extra scheduled task is removed, and the automatic maintenance behavior described above stops.
