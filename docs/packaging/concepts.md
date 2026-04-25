# Concepts

## Interfaces

An interface describes what something does (e.g. "VLC media player").

In Zero Install, interfaces are named by globally unique URIs (like web pages). Some examples of interfaces are:

- `https://apps.0install.net/gui/vlc.xml`
- `https://apps.0install.net/lib/zlib.xml`

When a user asks to run a program, they give the interface URI:

```shell
0install run https://apps.0install.net/gui/vlc.xml
```

When a program depends on a library, it gives library's interface URI:

```xml
<requires interface="https://apps.0install.net/lib/zlib.xml">
```

## Feed files

A feed file is a list of implementations (versions) of an interface. It is called a feed because new versions get added to it when they are released, just as news items are added to an RSS feed.

Usually an interface has only one feed file, located at the interface's URI. Some examples of feeds are:

- [`https://apps.0install.net/gui/vlc.xml`](https://apps.0install.net/gui/vlc.xml)
- [`https://apps.0install.net/lib/zlib.xml`](https://apps.0install.net/lib/zlib.xml)
- `/home/user/dev/vlc/vlc.xml` (a local feed)

You can add additional local and remote feeds to an interface. A _local feed_ is located locally on your machine, whereas a _remote feed_ is located on the web (even if it is cached on your machine).

## Implementations

An _implementation_ is something that implements an interface. `vlc-3.0.22` and `vlc-3.0.23` are both implementations of `https://apps.0install.net/gui/vlc.xml`.

Each implementation of an interface is identified by a cryptographic digest, eg:

- `sha256new=RB425FJGG2VCKSZVBUHC76LRXKR7VNXDJGB4P4ULZ3UEFIMG7XIQ`
- `sha256new=7AUVW6L2HCERLAOFBSFA7J245HHCRRDQ3MRZ3MRKEYNCGPMGNYBA`

For platform independent binaries (e.g. Python code) there will be one implementation for each version. For compiled code, there will be one implementation per architecture per version.

!!! note
    The digest covers the full content of the implementation directory. Any change to the upstream archive &ndash; even a re-zip with different timestamps &ndash; produces a different digest, and the feed must be regenerated.

## Launching

When you launch a program (like VLC) 0install looks up the feed files of the interface and chooses an implementation of the interface and the interfaces it depends on according to the policy settings (e.g. preferring "stable" or "testing" implementations). 0install then downloads the implementations if they are missing from the cache. Lastly, 0install uses environment variables (bindings) to tell the program where to find its dependencies; this process is known as _Dependency Injection_ (or _Inversion of Control_).
