# Local feeds

Normally, 0install downloads a feed from the web, selects a version, downloads the archive for that version, and runs it. However, 0install can also be used locally (e.g. to run a program you are currently writing, which hasn't been released yet). There are several reasons why you might want to do this:

- 0install can select and download your program's build or runtime dependencies.
- It provides a cross-platform way to set environment variables and start your program.
- You can use [0release](../tools/0release/index.md) to generate releases automatically.

## A simple example

Let's say you have a simple Python 2 program, `hello.py`:

```python
print "Hello World!"
```

You could make this runnable by specifying a [shebang line](http://en.wikipedia.org/wiki/Shebang_%28Unix%29). But that wouldn't work on Windows (which doesn't support them). Also, different versions of Linux need different lines (e.g. `#!/usr/bin/python` on Debian, but `#!/usr/bin/python2` on Arch).

Instead, we can create a _local feed_ to say how to run it. Create `hello.xml` in the same directory:

```xml
<?xml version="1.0" ?>
<interface xmlns="http://zero-install.sourceforge.net/2004/injector/interface">
  <name>Hello</name>
  <summary>minimal demonstration program</summary>

  <implementation id="." version="0.1-pre">
    <command name='run' path='hello.py'>
      <runner interface='http://repo.roscidus.com/python/python'>
        <version before='3'/>
      </runner>
    </command>
  </implementation>
</interface>
```

Setting `id="."` says that the implementation of this interface is the directory containing the feed (whereas normally we'd specify a digest and a URL from which to download the archive).

There are two other differences to note: there is no digital signature at the end (we assume that no attacker could intercept the file between your harddisk and you ;-), and the version number ends in a modifier (`-pre` in this case), showing that it hasn't been released.

You can now use this feed with the usual 0install commands. For example:

```
$ 0launch hello.xml
Hello World!

$ 0install add hello-dev hello.xml
$ hello-dev
Hello World!

$ 0install select hello.xml
\- URI: /home/bob/hello/hello.xml
  Version: 0.1-pre
  Path: /home/bob/hello
  \- URI: http://repo.roscidus.com/python/python
    Version: 2.7.3
    Path: (package:deb:python2.7:2.7.3:x86_64)
```

This will work on Linux, MacOS X, Windows, etc.

## Next steps

Some more things you can do with your new local feed:

- Depend on other libraries or tools (see [the feed specification](../specifications/feed.md) for reference).
- Compile source code using [0compile](../tools/0compile/developers.md).
- Make a release using [0release](../tools/0release/index.md).
- Test against different versions of dependencies using [0test](../tools/0test.md).

See the [example templates](templates.md) for projects in different languages and using various build systems.
