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

## 1. Create the GitHub repository

Create a new GitHub repo named `myapp` (or whatever your app is called). Enable GitHub Pages in **Settings → Pages**, with the source set to **Deploy from a branch / main branch / root**.

Once enabled, GitHub gives you a URL of the form `https://YOURNAME.github.io/myapp/`. This is the URL your feed will live under. Pick a permanent file name now; let's say `myapp.xml`. The full feed URL is then:

```
https://YOURNAME.github.io/myapp/myapp.xml
```

!!! attention
    This URL must not change. Other feeds and your users' caches will reference it forever.

## 2. Create a template

Clone the repo and create a template:

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
  <description>
A longer description, often copied from your project's homepage.
  </description>
  <homepage>https://github.com/YOURNAME/myapp</homepage>
  <icon href="https://YOURNAME.github.io/myapp/myapp.png" type="image/png"/>

  <feed-for interface="https://YOURNAME.github.io/myapp/myapp.xml"/>

  <group license="MIT License">
    <command name="run" path="myapp"/>

    <implementation arch="Linux-x86_64" version="{version}" stability="stable">
      <manifest-digest/>
      <archive href="https://github.com/YOURNAME/myapp/releases/download/v{version}/myapp-{version}-linux-x64.tar.gz"/>
    </implementation>
    <implementation arch="Windows-x86_64" version="{version}" stability="stable">
      <manifest-digest/>
      <archive href="https://github.com/YOURNAME/myapp/releases/download/v{version}/myapp-{version}-windows-x64.zip"/>
    </implementation>
  </group>
</interface>
```

Match the runner, `<command>` and bindings to your runtime (see the relevant [guide](../guides/index.md)). The `{version}` placeholder is filled in by 0template.

## 3. Generate a feed for the first version

Tag a release in your project (e.g. `v1.0`), upload the binary archives to the GitHub release, and run:

```shell
0template myapp.xml.template version=1.0
```

This downloads each archive, computes its manifest digest, and writes `myapp-1.0.xml`. Test it locally:

```shell
0install run myapp-1.0.xml
feedlint myapp-1.0.xml
```

Fix any warnings before continuing.

## 4. Sign the feed

Set the feed URL and add an XML signature:

```shell
0publish myapp-1.0.xml --set-interface-uri=https://YOURNAME.github.io/myapp/myapp.xml --xmlsign
```

You'll be prompted for your GPG passphrase. The signed feed contains a Base64 signature block at the end as an XML comment.

`0publish` also writes a public key file (e.g. `1234ABCD.gpg`) alongside the feed. This is what users' 0install installs need to verify the signature.

## 5. Publish to GitHub Pages

Copy the signed feed to its permanent name, commit and push:

```shell
cp myapp-1.0.xml myapp.xml
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

When you tag `v1.1`, repeat steps 3 and 4:

```shell
0template myapp.xml.template version=1.1
0publish myapp-1.1.xml --set-interface-uri=https://YOURNAME.github.io/myapp/myapp.xml --xmlsign
```

Then merge the new implementation into the master feed:

```shell
0publish myapp.xml --add-from=myapp-1.1.xml --xmlsign
```

`--add-from` copies the new `<implementation>` into the existing master feed and re-signs it. Push the result.

This works fine for a single feed you publish by hand. To get CI to do the signing for you (so the GPG private key isn't on every machine you publish from), see [Automating a single feed with CI](automate-ci.md). Once you have several feeds and want stable URLs for keys and a shared catalog, switch to [Managing multiple feeds with 0repo](multi-feed.md).

## Troubleshooting

`gpg: signing failed: secret key not available`
: The feed was last signed with a key you don't have. Pass `-k YOURKEY` to `0publish` to switch keys.

`Manifest digest sha256new=... does not match expected ...`
: The archive at the upstream URL changed after you generated the feed. Re-run `0template` and re-sign.
