# Managing multiple feeds with 0repo

Once you have more than one feed, the per-repo `0template` + `0publish` workflow from the [previous tutorial](automate-ci.md) starts to chafe. Each app needs its own GPG key copy, its own `gh-pages` branch, its own merge logic. There's no shared catalog, no shared key, no consistent enforcement of policies like "every feed must declare a license" or "release dates must be set".

[0repo](../../tools/0repo.md) replaces the per-repo plumbing with a single, central feed repository. It:

- Validates each incoming feed against your repository's policies (license required, release date set, signed by an authorized key, ...).
- Merges new implementations into the right main feed.
- Signs the result with the repository's GPG key.
- Generates a top-level catalog and a directory listing.

The key shift from the previous tutorial: each app's `*.xml.template` keeps living in the app's own source repo (alongside the source code). The app's CI builds the release, runs `0template`, attaches the per-version feed to a GitHub Release, and tells the central feed repo to pick it up. The central repo runs `0repo` and publishes.

This tutorial assumes you've worked through [Automating a single feed with CI](automate-ci.md), so the source-repo side will look familiar.

By the end you will have:

- A central `feeds` repository with `0repo-config.py` on `main` and signed feeds on `gh-pages`. Served at `https://YOURNAME.github.io/feeds/`.
- One or more app source repositories, each with its own `*.xml.template` and a CI workflow that releases through the central repo.
- An `Incoming` workflow on the central repo that runs `0repo` whenever an app finishes a release.

## 1. Create the central feed repository

Create a new repo `feeds` (any name works). Initialise an empty `gh-pages` orphan branch and push it:

```shell
git clone https://github.com/YOURNAME/feeds.git
cd feeds
git checkout --orphan gh-pages
git rm -rf .
echo "" > archives.db
git add archives.db
git commit -m "Initial gh-pages"
git push -u origin gh-pages
git checkout main
```

In **Settings → Pages**, set the source to **Deploy from a branch / gh-pages / root**. Note the URL; let's say `https://YOURNAME.github.io/feeds/`. This is your repository base URL.

## 2. Initialise 0repo

```shell
0install add 0repo https://apps.0install.net/0install/0repo.xml
0repo create ~/repos/feeds 'Your Name'
```

`0repo create` writes a `0repo-config.py`. Move it into your Git checkout and edit `REPOSITORY_BASE_URL` and `GPG_SIGNING_KEY`:

```python
REPOSITORY_BASE_URL = "https://YOURNAME.github.io/feeds/"
GPG_SIGNING_KEY = None if os.getenv('NO_SIGN') else "0xYOURKEYFINGERPRINT"
```

The `NO_SIGN` escape hatch lets CI run `0repo` in dry-run mode for pull-request validation without exposing the production key.

Commit `0repo-config.py` to `main` and push.

## 3. Add the GPG key as a repository secret

Export the private key:

```shell
gpg --export-secret-keys --armor YOURKEY
```

In the **feeds** repo's **Settings → Secrets and variables → Actions**, create a secret named `GPG_KEY` with the armored key as its value.

## 4. Add publish & incoming workflows

Two workflows on the **feeds** repo do the heavy lifting:

- `publish.yml` runs whenever `0repo-config.py` changes on `main` (so policy edits take effect without a feed change).
- `incoming.yml` is a manually triggered workflow that pulls a fresh feed from a URL, runs `0repo` to merge and sign it, and pushes `gh-pages`. App repos kick this off with the `0repo-submit` action.

Both use the [Zero Install GitHub Actions](https://github.com/0install/github-actions): `0repo-setup` prepares the working directory and `0repo` runs the tool.

Note that neither workflow starts with `actions/checkout`. 0repo needs `main` and `gh-pages` checked out side by side rather than at the workspace root, so `0repo-setup` performs both checkouts itself and creates the surrounding structure:

```
feeds/           # main: 0repo-config.py, the unsigned feeds and templates
public/          # gh-pages: the published (signed) feeds and archives.db
incoming/        # empty, holds feeds waiting to be merged
0repo-config.py  # symlink to feeds/0repo-config.py
archives.db      # symlink to public/archives.db
```

This mirrors the layout you set up by hand for local runs in [step 6](#6-local-previewing). 0repo commits its results into both checkouts, and the GitHub Action optionally pushes them back when it is done.

`.github/workflows/publish.yml`:

```yaml
name: Publish
on:
  workflow_dispatch: {}
  push:
    branches: [main]
    paths: ['0repo-config.py']
concurrency: { group: publish }

jobs:
  publish:
    runs-on: ubuntu-latest
    steps:
      - uses: 0install/github-actions/0repo-setup@v1
      - uses: 0install/github-actions/0repo@v1
        with:
          gpg-key: ${{ secrets.GPG_KEY }}
          push-public: true
```

`.github/workflows/incoming.yml`:

```yaml
name: Incoming
on:
  workflow_dispatch:
    inputs:
      feed_url:
        required: true
        description: URL of the per-version feed to merge in
      archive_url:
        required: false
        description: URL of the archive to register in archives.db
concurrency: { group: publish }

jobs:
  incoming:
    runs-on: ubuntu-latest
    steps:
      - uses: 0install/github-actions/0repo-setup@v1
      - uses: 0install/github-actions/0repo@v1
        with:
          gpg-key: ${{ secrets.GPG_KEY }}
          incoming-feed-url: ${{ inputs.feed_url }}
          incoming-archive-url: ${{ inputs.archive_url }}
          push-feeds: true
          push-public: true
```

Given an `incoming-feed-url`, the `0repo` action downloads the feed into `incoming/` before running the tool, and if the archive is hosted outside the repository it registers its name, hash and URL in `archives.db`. 0repo then validates the feed, merges it into the master feed, signs everything and regenerates the catalog.

Leaving out `gpg-key` makes the `0repo` action set the `NO_SIGN` environment variable, which is why `0repo-config.py` reads it. Leave out the `push-*` inputs as well and you have a third workflow that validates pull requests without access to the production key and without changing anything:

```yaml
name: Verify
on: pull_request

jobs:
  verify:
    runs-on: ubuntu-latest
    steps:
      - uses: 0install/github-actions/0repo-setup@v1
      - uses: 0install/github-actions/0repo@v1
```

As an extra safeguard, `public/` is never pushed when `gpg-key` is unset, even if `push-public` is `true`: an unsigned run must not replace the signed feeds.

The [apps.0install.net workflows](https://github.com/0install/apps/tree/master/.github/workflows) are a real-world deployment of exactly these three workflows, and worth reading when you scale up.

## 5. Wire up an app's source repo

Each app keeps its own template and source code together. Concretely, in the app's repo (say `myapp`):

```
myapp/
├── .github/
│   └── workflows/
│       └── build.yml
├── src/
├── build.sh
├── myapp.xml.template
└── README.md
```

The template's `<feed-for>` points at the central repo, **not** at the app's own GitHub Pages:

```xml
<feed-for interface="https://YOURNAME.github.io/feeds/myapp.xml"/>
```

The build workflow at `myapp/.github/workflows/build.yml`:

```yaml
name: Build
on:
  push:
    tags: ['v*']

jobs:
  build:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v7
        with:
          fetch-depth: 0

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

      - name: Create GitHub Release
        uses: softprops/action-gh-release@v3
        with:
          files: |
            ${{ steps.feed.outputs.feed }}
            ${{ steps.feed.outputs.archive }}

      - name: Submit feed to central repository
        uses: 0install/github-actions/0repo-submit@v1
        with:
          repository: YOURNAME/feeds
          feed-url: ${{ steps.feed.outputs.feed }}
          archive-url: ${{ steps.feed.outputs.archive }}
          token: ${{ secrets.PERSONAL_TOKEN }}
```

The paths that `0template` produced are attached to the GitHub Release and then handed to `0repo-submit` as-is: a relative path is resolved against the Release for the current tag, so you don't have to spell the download URL out twice.

Note that the feed itself keeps the relative `href` from the template; it is `archive-url` that tells the central repository where the archive actually lives. 0repo records that in `archives.db` and rewrites the `href` when it generates the published feed.

`PERSONAL_TOKEN` is a fine-grained personal access token with **Actions: write** on the `feeds` repo. The default `GITHUB_TOKEN` only has permissions on the current repo, so it can't trigger workflows elsewhere. Store it as a secret in the app's repo.

The flow is:

1. You push tag `v1.2` on the `myapp` repo.
2. CI builds the archive and runs `0template myapp.xml.template version=1.2`, producing `myapp-1.2.xml`.
3. CI uploads both the feed and the archive as release assets.
4. CI calls `gh workflow run` on the `feeds` repo, passing the URLs of both.
5. The `Incoming` workflow on `feeds` downloads them, runs `0repo`, signs, and pushes `gh-pages`.

A few minutes later, `https://YOURNAME.github.io/feeds/myapp.xml` carries the new version. Existing users pick it up the next time their cache becomes stale.

This is exactly the pattern [0capture](https://github.com/0install/0capture/blob/master/.github/workflows/build.yml) and [0template](https://github.com/0install/0template) use to publish into [apps.0install.net](https://apps.0install.net/).

## 6. Local previewing

For changes to `0repo-config.py` itself, or for hand-staged feeds, you can still run 0repo on your laptop. Tell 0repo where the working copy lives:

```shell
cd ~/Code/feeds
ln -s feeds/0repo-config.py .
ln -s public/archives.db .
mkdir incoming
0repo register
```

Then drop a per-version feed into `incoming/` and run:

```shell
0repo
```

To preview the result without pushing:

```shell
0repo proxy
# in another terminal:
http_proxy=http://localhost:8080/ 0install run https://YOURNAME.github.io/feeds/myapp.xml
```

## What's next

- [A catalog of third-party feeds](catalog.md): when the upstream is someone else, you replace the source-repo CI with a `*.watch.py` script that polls for new releases.
- [0repo's README](https://github.com/0install/0repo/blob/master/README.md) covers multi-developer setups, archive hosting, and the rest of `0repo-config.py`.
