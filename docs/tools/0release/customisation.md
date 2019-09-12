[0release](../0release/index.md) can be used to create releases of your software from a version control system. It uses sensible defaults, allowing it to create releases for simple projects with very little configuration. For more complex projects, you can specify extra commands that should be run during the release process using the syntax described here.

**Contents:**

[TOC]

# Example

For example, imagine that our hello-world example program now prints out a banner with its version number when run. `hello.py` now looks like this:

```shell
#!/usr/bin/env python
version='0.1'
print "Welcome to Hello World version %s" % version
print "Hello World!"
```

We want to make sure that the number in the hello.py file is updated automatically when we make a new release. To do this, add a <release:management> element to your feed, like this:

```xml
<interface xmlns="http://zero-install.sourceforge.net/2004/injector/interface">
  <name>HelloWorld</name>
  <summary>minimal demonstration package for 0release</summary>
  <description>
    This program outputs the message "Hello World". You can create new releases of it
    using 0release.
  </description>

  <release:management xmlns:release='http://zero-install.sourceforge.net/2007/namespaces/0release'>
    <release:action phase='commit-release'>sed -i "s/^version='.*'$/version='$RELEASE_VERSION'/" hello.py</release:action>
  </release:management>
  ...
</interface>
```

This tells 0release that during the `commit-release` phase (in which it updates the version number to the number chosen for the release) it should execute the given command, which updates the version line in the Python code. Of course, you can perform any action you want.

# Phase: commit-release

Current directory
: The working copy (under version control), as specified by the `id` attribute in the feed.

`$RELEASE_VERSION`
: The version chosen for the new release.

These actions are run after the user has entered the version number for the new release. After the actions are run, 0release will update the local feed file with the new version number and commit all changes to the version control system.

Any changes made to the working copy will therefore appear in both the history and also in the release archive.

If your script fails (returns a non-zero exit status), 0release will abort but will not revert any changes made by the actions. You will have to manually revert any changes before 0release will allow you to restart the release process.

# Phase: generate-archive

Current directory
: A temporary directory created by unpacking the archive exported from the SCM.

`$RELEASE_VERSION`
: The version chosen for the new release.

Once the release version is committed to version control, 0release exports that revision to a temporary directory. After running all the actions in this phase, the release tarball is created from the final state of the directory. Use this phase to generate files that should be in the release archive but not in the tagged revision under version control. Typical actions here are:

- Running `autoconf` to create a `configure` script.
- Building translations (`.mo` files) from source `.po` files.
- Building documentation (e.g. HTML from DocBook sources).

Notice that all the above generate _platform independent_ files. _Do not_ compile to platform-specific binaries here (e.g. do not compile C source files to executables). For such programs, you need one source package and multiple binary packages (one for each architecture). See [Releases with source and binary packages](compiled-binaries.md) for that.

# <add-toplevel-directory\>

Adding this element causes 0release to put everything in a sub-directory, named after the feed. This is probably only useful for ROX applications, where the version control system contains e.g. just `AppRun`but the release should contain `archive-2.2/Archive/AppRun`. This is done using:

```xml
  <release:management xmlns:release="http://zero-install.sourceforge.net/2007/namespaces/0release">
    <release:add-toplevel-directory/>
  </release:management>
```
