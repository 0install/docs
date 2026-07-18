# Publishing organization-internal content

The previous tutorials assumed your feeds are public. This one covers the opposite case: an in-house catalog of feeds for software that should only be available to employees and that runs on machines without unrestricted internet access.

The mechanics are the same as for public feeds - signed XML served over HTTP - but a few details change:

- Feeds and archives live on an intranet host, not GitHub Pages.
- The signing key is your organization's, and you want every workstation to trust it automatically without each user confirming a fingerprint.
- The catalog of available apps should appear pre-populated in the Zero Install GUI.
- Workstations may not be allowed to fetch from `apps.0install.net`, so the `0install` runtime itself and any third-party dependencies must be mirrored.

By the end you will have:

- An intranet feed repository at `https://feeds.corp.example/` (or any URL you control).
- An organization GPG key trusted automatically on every workstation.
- A custom Zero Install [catalog](../../specifications/catalog.md) listing your in-house apps.
- A bootstrapper that installs Zero Install with all of the above pre-configured.

## 1. Host feeds on an intranet server

Pick a hostname your workstations can reach (e.g. `feeds.corp.example`) and serve a directory over HTTPS. Any static HTTP server will do (nginx, IIS, S3 with a reverse proxy, etc.). The layout is identical to the public case:

```
feeds.corp.example/
├── 0123ABCD.gpg                # the organization signing key (public)
├── catalog.xml                 # signed list of apps
├── apps/
│   ├── invoice-tool.xml
│   └── invoice-tool-1.0.tar.gz
└── mirror/
    ├── 0install-win.xml
    └── ...                     # mirrored upstream feeds (see step 5)
```

Use [0repo](../../tools/0repo.md) to maintain this directory the same way you would for a public catalog (see [Managing multiple feeds with 0repo](multi-feed.md)). The only change is `REPOSITORY_BASE_URL` in `0repo-config.py`:

```python
REPOSITORY_BASE_URL = "https://feeds.corp.example/"
```

!!! attention
    Use HTTPS, not HTTP, even on an intranet. Zero Install will warn about plain-HTTP feed URLs and most browsers refuse to download `.gpg` keys from `http://`.

## 2. Generate the signing key

Generate a dedicated GPG key for the repository on a machine that can stay offline (or in a CI runner with the secret as a deployment-only credential):

```shell
gpg --full-generate-key
# Real name: Corp Feeds
# Email:     feeds@corp.example
gpg --export --armor feeds@corp.example > 0123ABCD.gpg
```

Drop the exported public key into the repository root so workstations can fetch it. Keep the private key off ordinary developer machines; only the CI that runs `0repo` needs it.

## 3. Pre-trust the organization key on every workstation

Without configuration, the first user to open an in-house feed gets a "Confirm key" dialog. If you're going to roll Zero Install out via the bootstrapper from [step 5](#5-bundle-everything-into-a-bootstrapper), you can skip this step: the bootstrapper imports the signing key embedded in its bundled content into `trustdb.xml` automatically on first run. The rest of this section is for workstations that already have Zero Install installed by other means.

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

| OS              | Path                                              |
| --------------- | ------------------------------------------------- |
| Windows         | `%PROGRAMDATA%\0install.net\injector\trustdb.xml` |
| Linux / macOS   | `/etc/xdg/0install.net/injector/trustdb.xml`      |

See [File locations](../../details/file-locations.md) for the full list of paths Zero Install reads. Settings under `%PROGRAMDATA%` / `/etc/xdg` apply to every user on the machine; per-user overrides go under `%APPDATA%` / `~/.config`.

The `<domain>` entry is what limits trust: the key only grants permission to sign feeds whose URL is on `feeds.corp.example`. Compromising the organization key never lets anyone publish for `apps.0install.net`.

!!! tip
    On Windows, instead of shipping a hand-written `trustdb.xml`, you can let `0install` create or update the file for you with [`0install trust add`](../../details/cli.md#trust-add):

    ```shell
    0install trust add 0123ABCD0123ABCD0123ABCD0123ABCD0123ABCD feeds.corp.example
    ```

    Useful for running inside a provisioning script that updates an existing per-user `trustdb.xml` rather than overwriting it. This subcommand is Windows only; on Linux / macOS ship the `trustdb.xml` directly as shown above.

## 4. Provide a custom catalog

Ship a [catalog](../../specifications/catalog.md) so users see the in-house apps in the Zero Install GUI without having to know URLs by heart. `0repo` produces a signed `catalog.xml` automatically. Register it on each workstation:

!!! attention
    The `0install catalog` subcommands shown below are Windows only. On Linux / macOS the catalog is the GUI's app list; point users at the feed URLs directly or distribute the bootstrapper from [step 5](#5-bundle-everything-into-a-bootstrapper) instead.

```shell
0install catalog add https://feeds.corp.example/catalog.xml
```

To make this the only catalog, follow up with `0install catalog remove https://apps.0install.net/catalog.xml`. To roll the change out machine-wide, run the same command in your provisioning script (Group Policy startup task, MDM, Ansible playbook, ...).

After that, the main GUI's app list and `0install catalog search` will include your apps:

```shell
0install catalog refresh
0install catalog search invoice
```

See [`0install catalog`](../../details/cli.md#catalog-add) for the full set of subcommands.

## 5. Bundle everything into a bootstrapper

Rolling out a fresh laptop should be one click, not "follow these five steps". Use [0bootstrap](../../tools/0bootstrap.md) on top of [`0install export`](../../details/export.md) to produce a single executable that installs Zero Install offline along with an in-house app:

```shell
0install export --include-zero-install https://feeds.corp.example/my-app.xml export
0bootstrap https://feeds.corp.example/my-app.xml --content=export/content --integrate-args="--add-standard" --output=my-app-setup.exe
```

Distribute the resulting `my-app-setup.exe` via your normal software-deployment channel (Group Policy, MDM, file share). Running it on a fresh workstation installs Zero Install for the current user, imports the bundled feeds and signing keys (including adding the organization key to `trustdb.xml`), and registers the app's Start menu entries and file associations. No separate `trustdb.xml` provisioning is needed when the bootstrapper handles the install. Pass `--machine` to install Zero Install and the app for all users instead.

## 6. Updating

The day-to-day workflow is unchanged from [Managing multiple feeds with 0repo](multi-feed.md): app teams push a tag, CI runs `0template`, the central repo's `Incoming` workflow merges and signs, and the `gh-pages` equivalent (your intranet host's docroot) gets the new version.

Workstations pick up updates automatically via Zero Install's freshness checks. If a workstation is offline for a while, the user sees the cached version until they reconnect; there is no "this app expired" failure mode.
