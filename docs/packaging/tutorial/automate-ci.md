# Automating a single feed with CI

The [previous tutorial](publish-app.md) had you running `0template` and `0publish` on your machine and pushing the signed feed by hand. That works, but it means the GPG private key has to live on every machine you publish from, and "publish a release" is a checklist of commands you might occasionally get wrong.

This tutorial moves the work to GitHub Actions. When you push a tag, CI builds the archive, generates the per-version feed, signs it with a key stored as a repository secret, and updates the master feed on GitHub Pages. You keep using [0template](../../tools/0template.md) and [0publish](../../tools/0publish.md).

This tutorial assumes you've worked through [Publishing your own app](publish-app.md): you have a `myapp.xml.template`, a feed URL, and a GPG key.

By the end you will have:

- `myapp.xml.template` checked into the same repo as your application source.
- A `gh-pages` branch holding the signed master feed and the public key.
- A GitHub Actions workflow that publishes a new version every time you push a `v*` tag.

## 1. Move the template into the source repo

Up to now the template lived in a dedicated `myapp` repo whose only job was hosting the feed. From here on, keep it next to the source code, the way [0capture](https://github.com/0install/0capture/blob/master/0capture.xml.template) and [0template](https://github.com/0install/0template/blob/master/0template.xml.template) do. Move `myapp.xml.template` into the source repository alongside the build script:

```
myapp/
├── .github/
│   └── workflows/
│       └── publish.yml
├── src/
├── build.sh
├── myapp.xml.template
└── README.md
```

The template still references the public feed URL on GitHub Pages, e.g. `https://YOURNAME.github.io/myapp/myapp.xml`. The feed URL is decoupled from where the template lives.

## 2. Create an orphan `gh-pages` branch

The signed master feed and the public key are served from a `gh-pages` branch. CI checks it out, updates it, and pushes back. Initialise it once:

```shell
git checkout --orphan gh-pages
git rm -rf .
cp ../myapp.xml .       # the master feed from the previous tutorial
cp ../*.gpg .           # the public key from the previous tutorial
git add myapp.xml *.gpg
git commit -m "Initial gh-pages"
git push -u origin gh-pages
git checkout main
```

In **Settings → Pages**, set the source to **Deploy from a branch / gh-pages / root**. Confirm the feed is still reachable at `https://YOURNAME.github.io/myapp/myapp.xml`.

## 3. Add the GPG key as a repository secret

Export the private key (the `--armor` output is what we'll paste):

```shell
gpg --export-secret-keys --armor YOURKEY
```

In **Settings → Secrets and variables → Actions**, create a new repository secret named `GPG_KEY` and paste the armored key as the value. Treat it as you would any other deployment credential.

## 4. Write the workflow

Create `.github/workflows/publish.yml`:

```yaml
name: Publish
on:
  push:
    tags: ['v*']

jobs:
  publish:
    runs-on: ubuntu-latest
    permissions:
      contents: write
    steps:
      - uses: actions/checkout@v4
        with: { path: source }
      - uses: actions/checkout@v4
        with: { path: public, ref: gh-pages }

      - name: Build release archive
        working-directory: source
        run: ./build.sh ${GITHUB_REF_NAME#v}

      - name: Import GPG key
        run: echo "${{ secrets.GPG_KEY }}" | gpg --batch --import -

      - name: Install Zero Install
        run: |
          curl -sSfL https://get.0install.net/0install.sh -o 0install.sh
          chmod +x 0install.sh

      - name: Generate per-version feed
        working-directory: source
        run: |
          version=${GITHUB_REF_NAME#v}
          ../0install.sh run https://apps.0install.net/0install/0template.xml \
            myapp.xml.template version=$version

      - name: Sign and merge into master feed
        run: |
          version=${GITHUB_REF_NAME#v}
          cp source/myapp-$version.xml public/
          cd public
          ../0install.sh run https://apps.0install.net/0install/0publish.xml \
            myapp-$version.xml \
            --set-interface-uri=https://YOURNAME.github.io/myapp/myapp.xml \
            --xmlsign
          ../0install.sh run https://apps.0install.net/0install/0publish.xml \
            myapp.xml --add-from=myapp-$version.xml --xmlsign
          rm myapp-$version.xml

      - name: Push gh-pages
        working-directory: public
        run: |
          git config user.name 'CI'
          git config user.email 'ci@example.com'
          git add myapp.xml
          git commit -m "Publish ${GITHUB_REF_NAME#v}"
          git push
```

The workflow:

1. Checks out the source on `main` and the published feed on `gh-pages` into separate directories.
2. Builds the release archive at the tag's version. Replace `./build.sh` with whatever produces the archive your template expects (and uploads it to wherever the `<archive href>` points to — typically a GitHub Release attached to the same tag).
3. Imports the GPG key into the runner's keyring. `gpg --batch` skips the passphrase prompt; the key must be exported without a passphrase, or you must also store and feed in the passphrase via a separate secret.
4. Runs `0template` to compute the manifest digest and stamp out `myapp-$version.xml`.
5. Runs `0publish --xmlsign` twice — once to sign the per-version feed and once to merge it into the master `myapp.xml` and re-sign.
6. Commits and pushes `gh-pages`.

## 5. Tag a release

```shell
git tag v1.2
git push origin v1.2
```

Watch the workflow run in the **Actions** tab. When it finishes, the new version is live at `https://YOURNAME.github.io/myapp/myapp.xml` and existing users will pick it up the next time their cache becomes stale.

## When to outgrow this

This setup is fine for one feed and one developer. It starts to creak when:

- You publish more than one feed and want them validated against the same policies (license set, release date present, signed by an authorised key).
- You want a public key with a stable URL independent of any single feed.
- You want a browsable directory listing or catalog at the repo root.
- Multiple people are pushing tags and you need the merge of the master feed to be a single transactional operation.

At that point, switch to [0repo](../../tools/0repo.md) — the [next tutorial](multi-feed.md) shows how.

## Troubleshooting

`gpg: skipped "YOURKEY": Inappropriate ioctl for device`
: The key is encrypted with a passphrase. Either re-export without one (`gpg --pinentry-mode loopback --export-secret-keys ...`) or set `GPG_TTY` and pipe the passphrase in via `--passphrase-fd`.

`fatal: Authentication failed for 'https://github.com/...'`
: The default `GITHUB_TOKEN` can push to the same repo but only if the workflow has `permissions: contents: write`, as above. Without it the push to `gh-pages` is rejected.

`Manifest digest sha256new=... does not match expected ...`
: The build produced a different archive than the one referenced in the template — usually because the build is non-reproducible or the upload is racing with the workflow. Pin tool versions and upload before running `0template`.
