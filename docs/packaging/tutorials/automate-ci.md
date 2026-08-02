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

The [Zero Install GitHub Actions](https://github.com/0install/github-actions) wrap the tools, so the workflow does not have to bootstrap 0install or shell out to it. Create `.github/workflows/publish.yml`:

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
      - uses: actions/checkout@v7
      - uses: actions/checkout@v7
        with:
          ref: gh-pages
          path: gh-pages

      - name: Determine version
        id: version
        run: echo "version=${GITHUB_REF_NAME#v}" >> "$GITHUB_OUTPUT"

      - name: Build release archive
        run: ./build.sh ${{ steps.version.outputs.version }}

      - name: Generate per-version feed
        id: feed
        uses: 0install/github-actions/0template@v1
        with:
          template: myapp.xml.template
          version: ${{ steps.version.outputs.version }}

      - name: Merge into master feed
        uses: 0install/github-actions/0publish@v1
        with:
          feed: gh-pages/myapp.xml
          add-from: ${{ steps.feed.outputs.feed }}
          gpg-key: ${{ secrets.GPG_KEY }}

      - name: Push gh-pages
        working-directory: gh-pages
        run: |
          git config user.name 'CI'
          git config user.email 'ci@example.com'
          git add -A
          git commit -m "Publish ${{ steps.version.outputs.version }}"
          git push
```

The workflow:

1. Checks out the source on `main` and the published feed on `gh-pages` into separate directories.
2. Derives the version number from the tag name. The actions take the version as an input rather than deriving it themselves, so you can compute it however you like — from the tag, from a file in the repo, or with a tool such as [GitVersion](https://gitversion.net/).
3. Builds the release archive at that version. Replace `./build.sh` with whatever produces the archive your template expects (and uploads it to wherever the `<archive href>` points to; typically a GitHub Release attached to the same tag).
4. Runs `0template` to compute the manifest digest and stamp out `myapp-$version.xml`. The `feed` output holds the path of the generated file, and `archive` the path of any archive generated alongside it.
5. Runs `0publish --add-from` to merge the per-version feed into the master `myapp.xml`, importing the GPG key beforehand and resigning the result with it.
6. Commits and pushes `gh-pages`.

!!! tip
    If your template generates the archive itself (see [Generating archives](../../tools/0template.md#generating-archives)), tell the 0template action where it will end up and it rewrites the relative `href` for you. Setting `github-release` is shorthand for the GitHub Release of the current tag; use `archive-url` for anywhere else:

    ```yaml
      - name: Generate per-version feed
        id: feed
        uses: 0install/github-actions/0template@v1
        with:
          template: source/myapp.xml.template
          version: ${{ steps.version.outputs.version }}
          github-release: true
    ```

    You can then attach `${{ steps.feed.outputs.feed }}` and `${{ steps.feed.outputs.archive }}` to a GitHub Release for that tag.

### Sharing `gh-pages` with a generated site

Many projects already publish something to `gh-pages` — API documentation, a project website — using an action such as [actions-gh-pages](https://github.com/peaceiris/actions-gh-pages). Such actions usually replace the entire branch on every run (`force_orphan: true`), which would delete a feed that CI had committed there separately.

Rather than fighting over the branch, hand the feed to the same publishing step. The `public` checkout and the `0publish --add-from` merge stay exactly as they are; only the final "Push gh-pages" step changes. Copy the updated master feed and the public key into the directory the site generator produced, and let the publishing action commit the branch:

```yaml
      - name: Copy feed into the site
        run: cp public/myapp.xml public/*.gpg source/site/

      - name: Publish site
        uses: peaceiris/actions-gh-pages@v4
        with:
          github_token: ${{ github.token }}
          force_orphan: true
          publish_dir: source/site
```

The `public` checkout is now only used to read the previous master feed and merge the new version into it. The generated site keeps being rebuilt from scratch on every release, while the feed and the public key are carried forward from one release to the next. [TypedRest CodeGeneration](https://github.com/TypedRest/CodeGeneration/blob/master/.github/workflows/build.yml) publishes its API documentation and its feed this way.

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

At that point, switch to [0repo](../../tools/0repo.md). The [next tutorial](multi-feed.md) shows how.

## Troubleshooting

`gpg: skipped "YOURKEY": Inappropriate ioctl for device`
: The key is encrypted with a passphrase. Either strip the passphrase first (`gpg --passwd YOURKEY`, then leave the new passphrase empty) and re-export, or pipe the passphrase in via `--passphrase-fd` together with `--pinentry-mode loopback`.

`fatal: Authentication failed for 'https://github.com/...'`
: The default `GITHUB_TOKEN` can push to the same repo but only if the workflow has `permissions: contents: write`, as above. Without it the push to `gh-pages` is rejected.

`Manifest digest sha256new=... does not match expected ...`
: The build produced a different archive than the one referenced in the template, usually because the build is non-reproducible or the upload is racing with the workflow. Pin tool versions and upload before running `0template`.
