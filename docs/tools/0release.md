**Name:** 0release  
**Maintainer:** Thomas Leonard  
**License:** GNU Lesser General Public License  
**Source:** [Git repository](https://github.com/0install/0release)  
**Zero Install feed:** [http://0install.net/2007/interfaces/0release.xml](http://0install.net/2007/interfaces/0release.xml)

**0release** can be used to make new releases of your software. It handles details such as setting the version number and release date, tagging the release in your version control system and updating your Zero Install feed.

The general process for an architecture-independent package (e.g. a Python program) is shown in the diagram below ([releasing a source package and multiple binary packages](0release/compiled-binaries.md) is also possible):

![The release process](../img/uml/release-process.png)

After doing some development (so you have something to release!) you use 0release to prepare a new release. It will:

1.  Commit a release revision in your version control system (e.g. with a version of `1.3`) on a new temporary branch.
2.  Export the release revision and create a tarball for distribution.
3.  Unpack the release and run the unit tests.
4.  Update the version numbers in your version control system again (e.g. to `1.3-post`).

You can then run any final (manual) tests on the release. If you're happy with the result, then 0release can publish it (e.g. upload the tarball to an FTP server, update the Zero Install feed, and push the new commits and tag to your public version control system). Otherwise, 0release will discard the temporary branch so that you can fix the problems and try again.

Note: you don't _need_ to use this program to make your software available through Zero Install. You can just create a tarball using your normal process and then [publish a feed](../packaging/index.md) for it. However, 0release can automate some of the steps for you. It's especially useful for new projects, where you don't yet have an established process. Having a program to handle new releases brings several advantages over doing it manually:

- Making a new release is quicker, since many steps are automated.
- You can't forget some steps (did you forget to tag version 1.2? did you remember to compile the translations in 1.4? etc).
- You get a consistent structure each time (are your archives called `myprog-V.VV-linux.tgz` or `My-Prog-Linux-V.VV.tar.gz`?).
- If someone else needs to make a release, they will follow the same process.

Version control systems currently supported

Git is fully supported. It should be fairly easy to support other (distributed) version control systems.

**Contents:**

[TOC]

## Preparing your source repository

You'll need a [local feed](../packaging/local-feeds.md) within your source directory (under version control). This contains the same information as a normal published feed would (name, description, dependencies, etc). The only differences are:

- The local feed refers to a local directory (e.g. `id="."` for the directory containing the local feed) rather than a secure hash.
- It has no digital signature.
- The version will be a development version (e.g `1.2-post` if your last released version was `1.2`).

Having a local feed is useful even if you don't use `0release`, because it lets people check out a development snapshot of your program and then register it (using `0launch --feed`) or run it directly with Zero Install handling its dependencies.

A minimal Hello World example is available for testing. You can check it out like this, using the [Git](http://git-scm.com/) version control system:

```shell
$ git clone git://github.com/0install/hello-python.git
```

To check that you can run it, use [0launch](http://0install.net/injector.html) on the feed:

```shell
$ cd hello-python
$ 0launch HelloWorld.xml
Hello World!
```

`HelloWorld.xml` is the local feed. Its contents look like this:

```xml
<?xml version="1.0" ?>
<interface xmlns="http://zero-install.sourceforge.net/2004/injector/interface">
  <name>HelloWorld</name>
  <summary>minimal demonstration package for 0release</summary>
  <description>
    This program outputs the message "Hello World". You can create new releases of it
    using 0release.
  </description>

  <homepage>http://0install.net/0release.xml</homepage>

  <feed-for interface='http://0install.net/tests/HelloWorld.xml'/>

  <implementation id="." version="0.1-pre">
    <command name='run' path='hello.py'>
      <runner interface='http://repo.roscidus.com/python/python'>
        <version before='3'/>
      </runner>
    </command>
  </implementation>
</interface>
```

Note the `<feed-for>` element. This is where the main feed is (or will be) published. If you want to follow this tutorial, change it to point to a location to which you can upload files (e.g. `http://localhost/~me/testing/HelloWorld.xml`) and commit the change (`git commit -a`).

You should add any dependencies inside the `<implementation>` element (see the [feed specification](../specifications/feed.md) for details, or edit the feed using [0publish-gui](../packaging/guide-gui.md) if you want a graphical interface). This example program is so simple it doesn't have any dependencies beyond its interpreter: Python < 3

## Creating the releases directory

Each time you create a new release, the resulting files go in your `releases` directory. Create the directory now and then run `0release` inside it, giving it the location of your local feed.

```shell
$ mkdir -p ~/releases/hello
$ cd ~/releases/hello
$ 0launch http://0install.net/2007/interfaces/0release.xml ~/hello/HelloWorld.xml
Setting up releases directory for HelloWorld
Success - created script:
 ~/releases/hello/make-release
Now edit it with your local settings.
Then, create new releases by running it.
```

This will create a single executable file in the directory, called `make-release`. Run this whenever you want to create a new release of your software.

- The `make-release` file contains local configuration information (e.g. the location of the local feed on your computer).
- General information about your program goes in the source directory so it can be shared by other developers.

**Warning:** Do not put `make-release` under the project's version control! First, because it contains user-specific information, and secondly because if you make a mistake in e.g. the upload command and then fix it, 0release will make you retract the release and restart the whole release process from the beginning because you changed a file that's part of the release... this is not fun ;-)

### Use with 0repo

0release >= 0.15 contains support for the new [0repo](0repo.md) repository management system. If you use this, most of the settings in the `make-release` file can be removed. For example, the complete configuration for 0release itself (0install.net uses 0repo) is:

```shell
#!/bin/sh
cd `dirname "$0"`
exec 0launch http://0install.net/2007/interfaces/0release.xml \
  --release /home/tal/Projects/zero-install/0release/0release.xml \
  --public-scm-repository=origin \
  "$@"
```

### Use without 0repo (deprecated)

Open the file in a text editor. There are five settings, with comments explaining what is needed. The only one you need to set is the first (`ARCHIVE_DIR_PUBLIC_URL`), which is where people will download the release from. In my case, I'll be uploading them to SourceForge so I set it to:

```shell
ARCHIVE_DIR_PUBLIC_URL='http://downloads.sourceforge.net/project/zero-install/hello/$RELEASE_VERSION'
```

Note that `$RELEASE_VERSION` is used because SourceForge puts each archive in a different directory, whose name is the version number. If you put all your archives in a single directory, you don't need this. e.g. you could use:

```shell
ARCHIVE_DIR_PUBLIC_URL='http://localhost/~me/testing'
```

The other settings allow 0release to push files to the remote server automatically, but you can leave them as they are and do it manually if you prefer.

## Creating a release candidate

When you want to make a new release, simply run the `make-release` script, like this:

```shell
$ cd ~/releases/hello
$ ./make-release
Releasing HelloWorld
Snapshot version is 0.1-pre
Version number for new release [0.1]:
```

You are prompted to enter the version number for the new release. You can just press Return to accept the default of `0.1` (since the version in the local feed was `0.1-pre`). It then prints:

```shell
Releasing version 0.1
HEAD is now at 387535a Updated feed-for to localhost for testing
SKIPPING unit tests for ~/releases/hello/0.1/helloworld-0.1 (no 'self-test' attribute set)
Wrote source feed as helloworld-0.1.xml
Wrote changelog from start to here as changelog-0.1

Candidate release archive: helloworld-0.1.tar.bz2
(extracted to ~/releases/hello/0.1/helloworld-0.1 for inspection)

Please check candidate and select an action:
P) Publish candidate (accept)
F) Fail candidate (delete release-status file)
(you can also hit CTRL-C and resume this script when done)
Publish/Fail:
```

0release has now created a candidate archive for you to examine. You might like to try running the program now. Note that the archive only contains files that are under version control.

You can either leave 0release running while you check it, or you can press CTRL-C to exit and run the make-release script again later. It will remember where it was (it stores the current status in a new `release-status` file).

As well as exporting the release archive, 0release also updates your Git repository by committing two new revisions. You can see them using `gitk --all`:

![New revisions created by 0release](../img/screens/0release-rc.png)

The lowest two revisions are the history you started with. The `master` branch adds the commit where you changed the `<feed-for>` element. This is also the currently checked-out version. 0release has created a new branch called `0release-tmp` with two new revisions. `Release 0.1` is the version that will be released. Its local feed has the version `0.1` and today's date as the release date. The archive was created from this revision. The next revision has a version of `0.1-post` and removes the release date again. Note that the release hasn't been tagged yet in Git, but 0release has recorded the revision ID in case you decide to accept the release candidate.

If you discover any problems you can go ahead and commit a fix, which will appear on the master branch (not on the `0release-tmp` branch, which will be discarded if you fail the release).

## Accepting the release candidate

We'll just check that the release works:

```shell
$ 0launch 0.1/helloworld-0.1/HelloWorld.xml
Hello World!
```

Looks good. If you killed the release script (with CTRL-C), run it again now to return to the Publish/Fail prompt. Choose `Publish` (you can just type `p<Return>`).

The temporary files (`release-status` and the extracted `helloworld-0.1` directory) are removed, and you will be prompted to enter your GPG passphrase to sign the release (use `--key` if you don't want to use the default key).

```shell
Tagged as v0.1
HEAD is now at 07f3c9e Start development series 0.1-post
Deleted branch 0release-tmp.
Changing key from 'None' to 'YOUR-KEY'
Exported public key as '~/releases/hello/YOUR-KEY.gpg'
```

A new file then appears: `HelloWorld.xml`. This is the master feed for the program, which you should publish on your web-site. It will list all versions of the program (although currently it only contains one version, of course). Finally, you will also find a `.gpg` file containing your GPG public key.

If you check your Git repository, you'll see that 0release has now tagged the release, and updated the "master" branch to the tip of the temporary branch:

![Release tagged](../img/screens/0release-tagged.png)

If, instead, you had found a problem with the release you would have selected `Fail` at the prompt. 0release would have removed the temporary branch (leaving `master` where it was) and deleted the temporary files.

## Uploading the files

**Note: Uploading directly with 0release is deprecated:** Consider using [0repo](0repo.md) instead to upload the files. That way, if you publish several different programs on your site you only have to configure things once. To use 0release with 0repo, just do `0repo register` so that 0release can find the repository to use.

If you didn't set an upload command in the make-release configuration file, 0release will prompt you to upload the files now:

```shell
Upload status:
- helloworld-0.1.tar.bz2 : Upload required

Upload 0.1/helloworld-0.1.tar.bz2 as
  http://downloads.sourceforge.net/project/zero-install/hello/0.1/helloworld-0.1.tar.bz2
No upload command is set => please upload the archive manually now
Press Return once the archive is uploaded.
```

Copy the archive (`helloworld-0.1.tar.bz2`) to your web-server. When you press Return, 0release will check that the archive was uploaded correctly:

```shell
Testing URL http://downloads.sourceforge.net/project/zero-install/hello/0.1/helloworld-0.1.tar.bz2...

Upload status:
- helloworld-0.1.tar.bz2 : Upload has been checked (exists and has correct size)
```

Finally, it will prompt you to upload the feed and the new Git commits:

```shell
Upload ~/releases/hello/HelloWorld.xml into http://0install.net/tests
NOTE: No feed upload command set => you'll have to upload them yourself!
Push changes to public SCM repository...
NOTE: No public repository set => you'll have to push the tag and trunk yourself.
```

After uploading `HelloWorld.xml` (to the URL you put in the feed-for) and `<KEYID>.gpg` (to the same directory), you should then be able to download and run the new release, using the URL you chose at the start:

```shell
$ 0launch http://localhost/~me/testing/HelloWorld.xml
Hello World!
```

You can edit `make-release` to set some commands to automatically upload the files and to push the branch and tag (`master` and `v0.1` in this case) to your public Git repository.

Tip: to check that all files are present, use [FeedLint](feedlint.md):

```shell
$ 0launch http://0install.net/2007/interfaces/FeedLint.xml http://localhost/~me/testing/HelloWorld.xml
```

## Customising the release process

For more information, see [release phases](0release/customisation.md).

## Source and binaries

If your program needs to be compiled, see [Releasing binaries](0release/compiled-binaries.md).

## Aborting a release

You can abort a release easily at any point before the Publish step. Once you select `Publish`, externally-visible changes start to be made (e.g. archives are uploaded to your file-server).

To abort before publishing
: Just select `Fail` from the menu. This deletes the `release-status` file (which you could also do manually). To avoid confusion, selecting `Fail` also removes the temporary release branch from Git and renames the release directory (to `$version~`) to make it clear that they're not being used.

To abort after publishing has started
: Follow the steps in [Unpublishing a release](#unpublishing-a-release) below to undo any publicly visible changes. Then delete the `release-status` file.

## Unpublishing a release

So, you didn't test the release properly, and now you want to pull it down, eh?

The best way to do this is to use 0publish-gui to set the stability to BUGGY, and then publish a new fixed release, with a new version number.

But if you _really_ insist on trying to unpublish a release and pretending it never happened, here's what you have to do:

1.  Edit the master feed (e.g. with `0publish-gui`) and delete the new `<implementation>`. Don't just use a text editor; the signature needs updating too! Push the new version to your server.
    
    Note: if you keep your feed under version control then you could revert the change. However, if anyone got the new version before you reverted it, then `0launch` will refuse to go back to the previous version, assuming that this is a replay attack. So create a new signature, with a fresh time-stamp.
    
2.  Reset `HEAD` to before the release (e.g. `git reset --hard v0.1^`) and delete the tag itself (e.g. `git tag -d v0.1`). Delete the remote tag at the server (e.g. `git push origin :v0.1 master`). Like `0launch`, if anyone saw the release in Git, their Git will refuse to go back to an older version. Tell them to use `-f`.
    
3.  Delete the tarball from your server.

## Uploading to SourceForge.net

To upload to SourceForge's File Release System, you need the project name, user name, program name and release version number. I use a [script to upload archives to SourceForge](http://0install.net/scripts/0release-upload-archive-sf). To use it:

1.  Put the script in your `$PATH` as `0release-upload-archive-sf`.
2.  Edit it to contain your sf.net username, not mine (the line `user = 'tal197'`)
3.  In your `make-release` script, use these settings:  
    `PROJECT=zero-install`  
    `PROGRAM=0release`  
    `ARCHIVE_DIR_PUBLIC_URL="http://downloads.sourceforge.net/project/$PROJECT/$PROGRAM/"'$RELEASE_VERSION'`  
    `ARCHIVE_UPLOAD_COMMAND="0release-upload-archive-sf $PROJECT $PROGRAM "'"$@"'`  
    The tricky quoting is because `$PROJECT` and `$PROGRAM` expand when the script is read, but `$RELEASE_VERSION` and `$@` expand when the command is later executed.
4.  Replace `zero-install` with the name of your project and `0release` with the name of your program.

These instructions are correct for sf.net as of 2009-08-15.

## Do I need to keep the releases directory?

It's best to keep the releases directory:

- You want to keep the make-release script so you don't have to write it again each time.
- You need the master feed so that 0release can add new versions to it, rather than creating a new feed each time. However, you can get the latest version of the feed from the cache (or directly from the web) if you lose the local copy.
- Keeping the previous archive allows diffing against it as an extra check when making a new release.

So, there's nothing too critical in the directory, but it's easiest to keep everything.

TODO: 0release should compare the local copy of the feed with the one on the web and warn if the local one is missing or out of sync. This would also be useful for allowing several people to publish new releases for a single feed easily.

TODO: 0release should also be able to diff against the previous version using the cache (downloading the archive if missing), rather than relying on the previous archive being in the releases directory.
