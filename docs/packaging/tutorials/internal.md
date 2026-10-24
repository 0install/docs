# Publishing organization-internal content

The previous tutorials assumed your feeds are public. This one covers the opposite case: an in-house catalog of feeds for software that should only be available to employees and that runs on machines without unrestricted internet access.

The mechanics are the same as for public feeds - signed XML served over HTTP - but a few details change:

- Feeds and archives live on an intranet host, not GitHub Pages.
- The signing key is your organization's, and you want every workstation to trust it automatically without each user confirming a fingerprint.
- The catalog of available apps should appear pre-populated in the Zero Install GUI.
- Workstations may not be allowed to fetch from `apps.0install.net`, so the `0install` runtime itself and any third-party dependencies must be shipped alongside your apps.

By the end you will have:

- An intranet feed repository at `https://feeds.corp.example/` (or any URL you control).
- An organization GPG key trusted automatically on every workstation.
- A custom Zero Install [catalog](../../specifications/catalog.md) listing your in-house apps.
- Optionally, [kiosk mode](../../details/kiosk-mode.md) restricting workstations to that catalog.
- A bootstrapper that installs Zero Install with all of the above pre-configured.

## 1. Host feeds on an intranet server

Pick a hostname your workstations can reach (e.g. `feeds.corp.example`) and serve a directory over HTTPS. Any static HTTP server will do (nginx, IIS, S3 with a reverse proxy, etc.). The layout is identical to the public case:

```
feeds.corp.example/
├── catalog.xml                 # signed list of apps
├── 0123ABCD0123ABCD.gpg        # the organization signing key (public)
├── keys/
│   └── 0123ABCD0123ABCD.gpg
├── apps/
│   ├── 0123ABCD0123ABCD.gpg
│   └── invoice-tool.xml
└── archives/
    └── invoice-tool-1.0.tar.gz
```

Use [0repo](../../tools/0repo.md) to maintain this directory the same way you would for a public catalog (see [Managing multiple feeds with 0repo](multi-feed.md)). The only change is `REPOSITORY_BASE_URL` in `0repo-config.py`:

```python
REPOSITORY_BASE_URL = "https://feeds.corp.example/"
```

If 0repo should host the archives on the intranet server too (in `archives/`, as shown above), also implement `upload_archives()` in `0repo-config.py`.

0repo exports the public key to `keys/` and places a symlink to it next to the catalog and in every directory that contains feeds, because Zero Install looks for the key next to the file it verifies. If your web server or upload mechanism does not follow symlinks (e.g. IIS or S3), copy them as regular files when deploying (e.g. with `cp -L` or `rsync -L`).

!!! tip
    Use HTTPS, not HTTP, even on an intranet. Signatures and manifest digests already protect feeds and archives against tampering, but HTTPS also keeps the list of software your staff use private and stops an attacker from replaying an older, validly signed feed to hold back updates.

## 2. Generate the signing key

Generate a dedicated GPG key for the repository on a machine that can stay offline (or in a CI runner with the secret as a deployment-only credential):

```shell
gpg --full-generate-key
# Real name: Corp Feeds
# Email:     feeds@corp.example
```

You don't need to export the public key yourself: 0repo does that whenever it signs the feeds (see above). Keep the private key off ordinary developer machines; only the CI that runs `0repo` needs it.

## 3. Pre-trust the organization key on every workstation

Without configuration, the first user to open an in-house feed gets a "Confirm key" dialog. If you're going to roll Zero Install out via the bootstrapper from [step 7](#7-bundle-everything-into-a-bootstrapper), you can skip this step: when 0bootstrap builds the bootstrapper, it records the fingerprint of the key that signed the app's feed, and the bootstrapper adds that key to `trustdb.xml` for the feed's domain on first run. The rest of this section is for workstations that already have Zero Install installed by other means.

To pre-trust the key by hand, ship a `trustdb.xml` file with the organization key fingerprint already trusted for `feeds.corp.example`:

```xml
<?xml version="1.0" encoding="utf-8"?>
<trusted-keys xmlns="http://zero-install.sourceforge.net/2007/injector/trust">
  <key fingerprint="0123ABCD0123ABCD0123ABCD0123ABCD0123ABCD">
    <domain value="feeds.corp.example"/>
  </key>
</trusted-keys>
```

(The `fingerprint` is the full 40-character GPG fingerprint of your signing key, with no spaces. Run `gpg --fingerprint feeds@corp.example` to read it off.)

Place this file in the system-wide configuration directory:

| OS            | Path                                              |
| ------------- | ------------------------------------------------- |
| Windows       | `%PROGRAMDATA%\0install.net\injector\trustdb.xml` |
| Linux / macOS | `/etc/xdg/0install.net/injector/trustdb.xml`      |

See [File locations](../../details/file-locations.md) for the full list of paths Zero Install reads. On Windows, Zero Install merges this file with each user's own `trustdb.xml` under `%APPDATA%`. On Linux / macOS, it only reads the first `trustdb.xml` it finds: as soon as a user has a `~/.config/0install.net/injector/trustdb.xml` of their own (created the first time they trust any key), the system-wide file is ignored for them.

The `<domain>` entry is what limits trust: the key only grants permission to sign feeds whose URL is on `feeds.corp.example`. Compromising the organization key never lets anyone publish for `apps.0install.net`.

!!! tip
    On Windows, instead of shipping a hand-written `trustdb.xml`, you can let `0install` create or update the file for you with [`0install trust add`](../../details/cli.md#trust-add). Run this elevated, from your provisioning script:

    ```shell
    0install trust add --machine 0123ABCD0123ABCD0123ABCD0123ABCD0123ABCD feeds.corp.example
    ```

    `--machine` writes to the machine-wide file under `%PROGRAMDATA%`; without it, the key is only trusted for the current user. Unlike copying a file into place, this keeps any keys the file already contains. This subcommand is Windows only; on Linux / macOS ship the `trustdb.xml` directly as shown above.

## 4. Provide a custom catalog

Ship a [catalog](../../specifications/catalog.md) so users see the in-house apps in the Zero Install GUI without having to know URLs by heart. `0repo` produces a signed `catalog.xml` automatically. Register it on each workstation:

!!! attention
    The `0install catalog` subcommands shown below are Windows only. On Linux / macOS, point users at the feed URLs directly.

```shell
0install catalog add https://feeds.corp.example/catalog.xml
```

To make this the only catalog, follow up with `0install catalog remove https://apps.0install.net/catalog.xml`.

`0install catalog` always changes the list of catalogs of the user who runs it. To configure all users of a machine at once, have your provisioning script (Group Policy startup task, MDM, Ansible playbook, ...) write the machine-wide list to `%PROGRAMDATA%\0install.net\catalog-sources` instead, one catalog URI per line:

```
https://feeds.corp.example/catalog.xml
```

A user's own list, if they have one, takes precedence over the machine-wide one, unless kiosk mode is enabled (see [step 5](#5-restrict-workstations-to-the-catalog-optional)).

After that, the main GUI's app list and `0install catalog search` will include your apps:

```shell
0install catalog refresh
0install catalog search invoice
```

See [`0install catalog`](../../details/cli.md#catalog-add) for the full set of subcommands.

## 5. Restrict workstations to the catalog (optional)

Registering your catalog makes the in-house apps easy to find, but it does not stop a user from running any other feed they come across. If the machines should only ever run what you publish, turn on [kiosk mode](../../details/kiosk-mode.md) (Windows only):

```shell
0install config kiosk_mode true
```

Zero Install then refuses any feed URI that is not listed in a registered catalog. Combine it with the machine-wide `catalog-sources` file from [step 4](#4-provide-a-custom-catalog): in kiosk mode the machine-wide file takes precedence over the per-user one, so a user cannot add a catalog of their own to widen the selection.

To make the setting stick, deploy it as a group policy rather than a config file: a `REG_DWORD` value named `kiosk_mode` set to `1` under `HKEY_LOCAL_MACHINE\SOFTWARE\Policies\Zero Install` (see [Windows Group Policy](../../details/policy-settings.md#windows-group-policy)). Options set there cannot be changed by the user.

## 6. Ship upstream dependencies

Your in-house feeds probably depend on feeds you don't control, e.g. a runtime such as `https://apps.0install.net/dotnet/framework.xml`, and Zero Install itself comes from `https://apps.0install.net/0install/0install-win.xml`. If workstations cannot reach those servers, download everything on a machine that can and carry it over with [`0install export`](../../details/export.md). The export contains the feeds, the keys they are signed with and the selected implementations of the app and all of its dependencies, plus Zero Install itself if you pass `--include-zero-install`.

Workstations then run the app from what was imported, without needing the upstream servers. To roll out newer versions of the dependencies, or of Zero Install itself, export again and distribute the result. The bootstrapper in the next step bundles such an export.

## 7. Bundle everything into a bootstrapper

Rolling out a fresh laptop should be one click, not "follow these six steps". Use [0bootstrap](../../tools/0bootstrap.md) on top of [`0install export`](../../details/export.md) to produce a single executable that installs Zero Install offline along with an in-house app:

```shell
0install export --include-zero-install https://feeds.corp.example/my-app.xml export
0bootstrap https://feeds.corp.example/my-app.xml --content=export/content --integrate-args="--add-standard" --output=my-app-setup.exe
```

Distribute the resulting `my-app-setup.exe` via your normal software-deployment channel (Group Policy, MDM, file share). Running it on a fresh workstation installs Zero Install for the current user, imports the bundled feeds and keys, trusts the organization key for `feeds.corp.example` (0bootstrap recorded which key signed `my-app.xml` when it built the bootstrapper), and registers the app's Start menu entries and file associations. No separate `trustdb.xml` provisioning is needed when the bootstrapper handles the install. To install Zero Install and the app for all users instead, run `my-app-setup.exe --machine` as an administrator.

The bootstrapper can also carry the configuration from the previous steps, so a fresh machine comes up already pointed at your catalog and already restricted:

```shell
0bootstrap https://feeds.corp.example/my-app.xml \
  --content=export/content --integrate-args="--add-standard" --output=my-app-setup.exe \
  --catalog-uri=https://feeds.corp.example/catalog.xml \
  --config kiosk_mode=true
```

Bundled settings are only applied if Zero Install is not already deployed on the machine (or is deployed in library mode, see below), so they will not override the configuration of a workstation somebody has already set up.

!!! note
    Because the bootstrapper integrates the app (`--integrate-args`), a workstation that doesn't have Zero Install yet gets it in [library mode](../../details/library-mode.md): the user sees your app rather than Zero Install, with no "Zero Install" entry in **Apps & features** and no Zero Install shortcut in the start menu, and Zero Install performs its maintenance in the background because nobody will open its GUI.

## 8. Updating

The day-to-day workflow is unchanged from [Managing multiple feeds with 0repo](multi-feed.md): app teams push a tag, CI runs `0template`, the central repo's `Incoming` workflow merges and signs, and the `gh-pages` equivalent (your intranet host's docroot) gets the new version.

Workstations pick up updates automatically via Zero Install's freshness checks. If a workstation is offline for a while, the user sees the cached version until they reconnect; there is no "this app expired" failure mode.
