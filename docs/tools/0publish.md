**Name:** 0publish  
**Maintainer:** Thomas Leonard  
**License:** GNU Lesser General Public License  
**Source:** [Git repository](https://github.com/0install/0publish)  
**Zero Install feed:** <http://0install.net/2006/interfaces/0publish>

Making a new release of your software can be time consuming and error-prone, so you'll probably want to script as much as possible of it. The **0publish** command provides a set of useful transformations for feed files which you can integrate into your release scripts.

However, **0publish is somewhat deprecated**:

- If you're looking for a graphical environment instead, see **0publish-gui** in [the packaging guide](../packaging/guide-gui.md).
- To add a new version of a program to a feed, consider using [0template](0template.md) to generate the XML for the new version and [0repo](0repo.md) to add it to the master feed.
- [0release](0release/index.md) provides a more complete solution for managing releases (0release uses 0publish or 0repo internally, but also handles many other aspects of making releases for you).

## General operation

`0publish` edits feed files in place. It loads the file, transforms it in some way (such as setting the release date, or adding a new implementation) and then writes it back out again. If the input file was signed, it will resign it when saving with the same key by default. You can also use it to add a signature, or to change the signing key.

You can create an app for `0publish` in the usual way:

```shell
$ 0install add 0publish http://0install.net/2006/interfaces/0publish
```

## 0publish reference

Usage: `0publish [options] feed.xml`

### Options

`-h`, `--help`
: Show help message and exit.

`-a FEED`, `--add-from=FEED`
: Add the implementation(s) in `FEED` to this one, putting them in the most sensible `<group>` (so as to minimise duplication of requirements, etc).

`--add-types`
: add missing MIME-type attributes.

`--add-version=VERSION`
: Add a new implementation (use with `--archive-url`, etc).

`--archive-url=URL`, `--archive-file=FILE`, `--archive-extract=DIR`
: Change a local implementation to one with a digest and an archive.

`-c`, `--create`
: Create a new feed file (if non-existent) without prompting.

`-d ALG`, `--add-digest=ALG`
: Add extra digests using the given algorithm.

`-e`, `--edit`
: Edit with `$EDITOR`. This is useful if the file is signed, since it removes the signature at the start and resigns at the end. It also checks that the new feed is valid before overwriting the old copy.

`-g`, `--gpgsign`
: Add a GPG signature block. Deprecated; use `--xmlsign` instead.

`-kKEY`, `--key=KEY`
: Key to use for signing (if you have more than one, or if you want to resign with a different key).

`-lLOCAL`, `--local=LOCAL`
: Deprecated; use `--add-from` instead.

`--manifest-algorithm=ALG`
: Select the algorithm to use for manifest digests.

`--set-id=DIGEST`
: Set the implementation ID. Note: it's usually easier to use the `--archive-*` options, since they calculate the digest for you.

`--set-main=EXEC`
: Set the main executable.

`--set-arch=ARCH`
: Set the architecture.

`--set-released=DATE`
: Set the release date. Typically used as `0publish --set-released $(date +%F) feed.xml`, which sets today's date.

`--set-stability=STABILITY`
: Set the stability rating.

`--set-version=VERSION`
: Set the version number (used when making a release from CVS).

`-s`, `--stable`
: Mark the current testing version as stable.

`--select-version=VERSION`
: Select version to use in `--set-*` commands.

`-x`, `--xmlsign`
: Add an XML signature block. All remote feeds must be signed.

`-u`, `--unsign`
: Remove any signature.

`-v`, `--verbose`
: More verbose output (for debugging).

`-V`, `--version`
: Display version information.

## FAQ

### gpg: signing failed: secret key not available

By default, 0publish tries to sign the new version of the feed using the same key that signed the old version. You will get this error if you don't have this key (e.g. because someone else signed the old version). In that case, use `-k` to specify they key you want to use instead.
