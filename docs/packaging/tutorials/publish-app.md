# Publishing your own app

This tutorial takes you from "I have a binary release" to "anyone in the world can run my app with `0install run https://example.com/myapp.xml`". We'll use [0template](../../tools/0template.md) to generate the feed, [0publish](../../tools/0publish.md) to sign it, and GitHub Pages to host the result.

By the end you will have:

- A signed feed at `https://YOURNAME.github.io/myapp/myapp.xml`.
- A GPG public key alongside it, so 0install can verify signatures.
- A repeatable command to add new versions.

## Prerequisites

- A GPG key you control. If you don't have one, run `gpg --full-generate-key` and accept the defaults. Just make sure to set a name and email address.
- A binary release of your app accessible over HTTPS, e.g. an attached release on GitHub, an S3 bucket, or your own web server.
- The Zero Install tools:

```shell
0install add 0template https://apps.0install.net/0install/0template.xml
0install add 0publish https://apps.0install.net/0install/0publish.xml
0install add feedlint https://apps.0install.net/0install/feedlint.xml
```

## 1. Set up the GitHub repository

Use your app's GitHub repo, say `myapp`, or create one. Enable GitHub Pages in **Settings → Pages**, with the source set to **Deploy from a branch / main branch / root**.

Once enabled, GitHub gives you a URL of the form `https://YOURNAME.github.io/myapp/`. This is the URL your feed will live under. Pick a permanent file name now; let's say `myapp.xml`. The full feed URL is then:

```
https://YOURNAME.github.io/myapp/myapp.xml
```

!!! attention
    This URL must not change. Other feeds and your users' caches will reference it forever.

## 2. Create a template

Clone the repo (if you haven't already) and create a template:

```shell
git clone https://github.com/YOURNAME/myapp.git
cd myapp
0template myapp.xml.template
# choose option 2 (binary template)
```

Open `myapp.xml.template` in an editor and fill in the metadata. A minimal template for a cross-platform app might look like:

```xml
<?xml version="1.0"?>
<interface xmlns="http://zero-install.sourceforge.net/2004/injector/interface">
  <name>MyApp</name>
  <summary>does something useful</summary>
  <description>A longer description, often copied from your project's homepage.</description>
  <homepage>https://github.com/YOURNAME/myapp</homepage>
  <icon href="https://YOURNAME.github.io/myapp/myapp.png" type="image/png"/>

  <feed-for interface="https://YOURNAME.github.io/myapp/myapp.xml"/>

  <group license="MIT">
    <implementation arch="Linux-x86_64" version="{version}" stability="stable">
      <command name="run" path="myapp"/>
      <manifest-digest/>
      <archive href="https://github.com/YOURNAME/myapp/releases/download/v{version}/myapp-{version}-linux-x64.tar.gz"/>
    </implementation>
    <implementation arch="Windows-x86_64" version="{version}" stability="stable">
      <command name="run" path="myapp.exe"/>
      <manifest-digest/>
      <archive href="https://github.com/YOURNAME/myapp/releases/download/v{version}/myapp-{version}-windows-x64.zip"/>
    </implementation>
  </group>
</interface>
```

Adjust the `<command>` elements and add whatever runner, dependencies and bindings your runtime needs (see the relevant [guide](../guides/index.md)). The `{version}` placeholder is filled in by 0template.

The `<icon>` points at a `myapp.png` that you will publish next to the feed in [step 5](#5-publish-to-github-pages). Add that file to the repo now, or leave the `<icon>` element out.

## 3. Generate a feed for the first version

Tag a release in your project (e.g. `v1.0`), upload the binary archives to the GitHub release, and run:

```shell
0template myapp.xml.template version=1.0
```

This downloads each archive into the directory of the template, computes its manifest digest, and writes `myapp-1.0.xml`. Neither the downloaded archives nor the per-version feeds belong in the repo, so add them to `.gitignore`. Test the feed locally:

```shell
0install run myapp-1.0.xml
feedlint myapp-1.0.xml
```

!!! tip
    Fix any [feedlint](../../tools/feedlint.md) warnings before continuing. They catch missing metadata, broken URLs and other issues that would surface later when users try to install your feed.

## 4. Create and sign the master feed

The per-version file produced by `0template` is an intermediate artifact; users will fetch the master feed at the permanent URL, so that is the one that needs to be signed. Create it from the per-version feed and add an XML signature:

```shell
0publish myapp.xml --add-from=myapp-1.0.xml --xmlsign
```

Because `myapp.xml` does not exist yet, `0publish` turns the per-version feed into the master feed: it sets the canonical URL to the one in the template's `<feed-for>` and removes that element, which only belongs in per-version feeds.

You'll be prompted for your GPG passphrase. The signed feed contains a Base64 signature block at the end as an XML comment.

`0publish` also writes a public key file alongside the feed, named after the 16-digit ID of your key (e.g. `0123456789ABCDEF.gpg`). This is what users' 0install installs need to verify the signature.

## 5. Publish to GitHub Pages

Commit and push the feed, the public key and the icon your template's `<icon>` points to:

```shell
git add myapp.xml *.gpg myapp.png
git commit -m "Publish 1.0"
git push
```

Once GitHub Pages has rebuilt (a minute or two), the feed is live. Test it from a fresh machine or by clearing your local cache:

```shell
0install run https://YOURNAME.github.io/myapp/myapp.xml
```

The first time someone runs your feed, 0install fetches the `.gpg` file from the same directory and asks the user to confirm the key fingerprint. After that the feed is cached and signature-checked automatically.

## 6. Adding new versions

When you tag `v1.1`, generate the per-version feed and merge it straight into the master feed:

```shell
0template myapp.xml.template version=1.1
0publish myapp.xml --add-from=myapp-1.1.xml
```

`--add-from` copies the new `<implementation>` into the existing master feed and re-signs it automatically with the same key the master feed was already signed with. The per-version `myapp-1.1.xml` is intermediate and doesn't need to be signed or published. Push the updated master feed.

This works fine for a single feed you publish by hand. To get CI to do the signing for you (so the GPG private key isn't on every machine you publish from), see [Automating a single feed with CI](automate-ci.md). Once you have several feeds and want stable URLs for keys and a shared catalog, switch to [Managing multiple feeds with 0repo](multi-feed.md).

## 7. Provide a native installer (optional)

End-users who don't yet have Zero Install installed can't `0install run` your feed. To give them a download that "just works", wrap the feed in a bootstrapper using [0bootstrap](../../tools/0bootstrap.md).

=== "Windows"
    ```shell
    0install add 0bootstrap https://apps.0install.net/0install/0bootstrap.xml
    0bootstrap https://YOURNAME.github.io/myapp/myapp.xml
    ```

    This produces a single `run MyApp.exe` (named after the feed's `<name>`, with the feed's icon; pass `--output=` to choose another name) that downloads Zero Install on first launch if necessary and then runs your app. You can host the resulting `.exe` next to the feed on GitHub Pages or attach it to your release.

    To make the generated executable register the app in the Start menu and file associations, pass the desired [integration arguments](../../details/cli.md#integrate) like this:

    ```shell
    0bootstrap https://YOURNAME.github.io/myapp/myapp.xml --integrate-args="--add-standard"
    ```

    The file is then named `integrate MyApp.exe`. On a machine that doesn't already have Zero Install, this makes the installer deploy it in [library mode](../../details/library-mode.md).

    For a fully offline installer, combine `0bootstrap` with [`0install export`](../../details/export.md) so the bootstrapper bundles the implementation alongside Zero Install:

    ```shell
    0install export --include-zero-install https://YOURNAME.github.io/myapp/myapp.xml myapp-export
    0bootstrap https://YOURNAME.github.io/myapp/myapp.xml --content=myapp-export\content
    ```

=== "Linux / macOS"
    See the [0bootstrap docs](../../tools/0bootstrap.md) for instructions how to produce a `.deb` or `.rpm` package.

## Troubleshooting

`gpg: signing failed: secret key not available`
: The feed was last signed with a key you don't have. Pass `-k YOURKEY` to `0publish` to switch keys.

`Incorrect manifest -- archive is corrupted` or `An implementation's manifest hash does not match the expected value`
: The archive at the upstream URL changed after you generated the feed. Re-run `0template` and re-sign.
