# A catalog of third-party feeds

The previous tutorials covered publishing feeds for software you maintain yourself. This one covers the harder case: keeping a curated catalog of feeds for **third-party** software up to date. This is the workflow [apps.0install.net](https://apps.0install.net/) uses to track its upstreams.

The trick is that you don't actually maintain the feeds; you maintain a small Python script per feed (the _watch script_) that knows how to query upstream for new releases. A scheduled job runs [0watch](../../tools/0watch.md) on the scripts, which calls [0template](../../tools/0template.md) for each new release, and [0repo](../../tools/0repo.md) merges the results into a signed catalog.

This tutorial assumes you already have a 0repo-managed repository running on GitHub Pages. If not, work through [Managing multiple feeds with 0repo](multi-feed.md) first.

By the end you will have:

- A template and a watch script for each third-party feed, next to the master feeds on `main`.
- A scheduled workflow that turns new upstream releases into an "Automatic updates" pull request.
- New versions that are signed and published as soon as you merge that pull request.

## How a watch script works

For each feed you want to track, you provide two files in the same directory:

- `<name>.xml.template`: a 0template template with `{version}` and `{released}` placeholders
- `<name>.watch.py`: a Python script that sets a `releases` attribute to a list of dicts, one per release

The watch script can pull data from anywhere: an HTML page, a GitHub Releases API call, an RSS feed. A typical script for a project that publishes to GitHub Releases:

```python
import sys, os
sys.path.insert(0, os.path.abspath(os.path.join(os.path.dirname(__file__), '..')))
import github  # shared helper, see below

releases = [{
    'version': release['tag_name'].lstrip('v'),
    'released': release['published_at'][:10],
} for release in github.releases('UPSTREAM/REPO') if not release['prerelease']]
```

Each dict's keys must match the placeholders in the template. The minimum is `version`; most templates also use `released` and sometimes `stability` or platform-specific values like `arch`.

The shared `github.py` helper lives at the root of the [apps repository](https://github.com/0install/apps/blob/master/github.py). Copy it into your repo and you can reuse it from every watch script. It authenticates with the `GITHUB_TOKEN` environment variable if that is set. Set it in CI, because unauthenticated calls to the GitHub API are tightly rate-limited.

## Running 0watch locally

Install it:

```shell
0install add 0watch https://apps.0install.net/0install/0watch.xml
```

For each watch script, 0watch:

1. Runs the script and reads `releases`.
2. For each release, checks whether `<name>-<version>.xml` already exists or whether `<name>.xml` already contains an `<implementation>` with that version.
3. If the version is new, calls `0template <name>.xml.template version=... released=...` and writes the result to the `--output` directory (or next to the script if there is none).

Example:

```shell
0watch myapp.watch.py --output ../incoming
```

The generated feed appears in `../incoming/myapp-1.2.3.xml`, ready to be merged by the next `0repo` run, which processes everything in `incoming/`.

To process every watch script in a tree, add a small driver script, say `watch.sh`, to the root of `main`:

```bash
#!/usr/bin/env bash
set -e
mkdir -p ../incoming

for FILE in */*.watch.py; do
    0install run https://apps.0install.net/0install/0watch.xml --output ../incoming "$FILE"
done
```

## Wiring it all up with GitHub Actions

The full pipeline has three workflows:

`update.yml`: Nightly cron (or manual trigger) that runs the driver script, collects the resulting feeds in `incoming/`, runs `0repo` on them, and opens a pull request titled "Automatic updates".

`verify.yml`: Runs on every pull request. Re-runs `0repo` without a GPG key, so nothing is signed or published, to confirm the feeds in the PR are valid before merging.

`publish.yml`: Runs on every push to `main` that changes a feed or `0repo-config.py`, e.g. when you merge the "Automatic updates" pull request. Re-signs the feeds with the production GPG key and pushes the signed results to `gh-pages`.

`verify.yml` and `publish.yml` are the ones from [Managing multiple feeds with 0repo](multi-feed.md#4-add-publish-incoming-workflows). `update.yml` is new:

```yaml
name: Update
on:
  workflow_dispatch: {}
  schedule:
    - cron: '0 2 * * *'

jobs:
  update:
    runs-on: ubuntu-latest
    permissions:
      contents: write
      pull-requests: write
    steps:
      - uses: 0install/github-actions/0repo-setup@v1
      - uses: 0install/github-actions/setup@v1

      - name: Run 0watch
        working-directory: feeds
        env:
          GITHUB_TOKEN: ${{ github.token }}
        run: ./watch.sh

      - name: Check if there are new feeds
        id: new-feeds
        run: if ls incoming/*.xml; then echo "found=true" >> "$GITHUB_OUTPUT"; fi

      - uses: 0install/github-actions/0repo@v1
        if: steps.new-feeds.outputs.found

      - name: Create pull request
        if: steps.new-feeds.outputs.found
        uses: peter-evans/create-pull-request@v7
        with:
          path: feeds
          branch: automatic-updates
          title: Automatic updates
```

`0repo-setup` checks `main` out into `feeds/`, so that is where the driver script runs, and `setup` provides the `0install` command it calls. The `0repo` step has no `gpg-key`: it merges the new versions into the master feeds in `feeds/` without signing or publishing anything, and the last step turns those commits into a pull request.

Two things to know about that pull request:

- GitHub only lets a workflow open one if **Allow GitHub Actions to create and approve pull requests** is enabled in **Settings → Actions → General**.
- A pull request opened with the default `GITHUB_TOKEN` does not trigger other workflows, so `verify.yml` will not run on it. If you want that check, pass a personal access token as the `token` input of `create-pull-request`.

The [apps.0install.net workflows](https://github.com/0install/apps/tree/master/.github/workflows) are a real-world deployment of this pipeline. Its `update.yml` additionally runs the watch scripts on Windows, macOS and Linux, because some of them only work on one platform.

## Anatomy of a typical entry

For a third-party tool, you add three files under, say, `utils/`:

```
utils/coolthing.xml           # master feed (maintained by 0repo)
utils/coolthing.xml.template  # template you maintain
utils/coolthing.watch.py      # release detector you maintain
```

After that, every new upstream release flows through the pipeline automatically: 0watch detects it overnight, 0template stamps out a per-version feed, 0repo merges and signs, GitHub Pages serves the result.

When upstream renames a binary, drops a platform, or changes their archive layout, you update the template and watch script, so that future releases come out right. 0repo only ever adds new versions to the master feed. The versions it already lists, and its metadata, stay as they are. To change those, edit the master feed on `main` directly, and `publish.yml` re-signs and publishes it.

## Tips

- Keep watch scripts deterministic. They run on every `update.yml` run and any flakiness causes false PRs.
- Filter out pre-releases / release candidates (`if not release['prerelease']`) unless you actually want to publish them as `stability="testing"`.

The fastest way to learn the patterns is to read [a handful of templates and watch scripts](https://github.com/0install/apps) in the apps repo. They cover almost every situation you'll run into.
