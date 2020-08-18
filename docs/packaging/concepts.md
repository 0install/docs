# Concepts

## Interfaces

An interface describes what something does (e.g. "Edit - a simple text editor").

In Zero Install, interfaces are named by globally unique URIs (like web pages). Some examples of interfaces are:

- `http://rox.sourceforge.net/2005/interfaces/Edit`
- `http://rox.sourceforge.net/2005/interfaces/ROX-Lib`

When a user asks to run a program, they give the interface URI:

```shell
0install run http://rox.sourceforge.net/2005/interfaces/Edit
```

When a program depends on a library, it gives library's interface URI:

```xml
<requires interface="http://rox.sourceforge.net/2005/interfaces/ROX-Lib">
```

## Feed files

A feed file is a list of implementations (versions) of an interface. It is called a feed because new versions get added to it when they are released, just as news items are added to an RSS feed.

Usually an interface has only one feed file, located at the interface's URI. Some examples of feeds are:

- [`http://rox.sourceforge.net/2005/interfaces/Edit`](http://rox.sourceforge.net/2005/interfaces/Edit)
- [`http://rox.sourceforge.net/2005/interfaces/ROX-Lib`](http://rox.sourceforge.net/2005/interfaces/ROX-Lib)
- `/home/tal/dev/edit/Edit.xml` (a local feed)

You can add additional local and remote feeds to an interface. A _local feed_ is located locally on your machine, whereas a _remote feed_ is located on the web (even if it is cached on your machine).

## Implementations

An _implementation_ is something that implements an interface. `Edit-1.9.6` and `Edit-1.9.7` are both implementations of `http://rox.sourceforge.net/2005/interfaces/Edit`.

Each implementation of an interface is identified by a cryptographic digest, eg:

- `sha1=235cb9dd77ef78ef2a79abe98f1fcc404bba4889`
- `sha1=c86d09f1113041f5eaaa8c3d1416fcf4dad8e2e0`

For platform independent binaries (e.g. Python code) there will be one implementation for each version. For compiled code, there will be one implementation per architecture per version.

## Launching

When you launch a program (like Edit) 0install looks up the feed files of the interface and chooses an implementation of the interface and the interfaces it depends on according to the policy settings (e.g. preferring "stable" or "testing" implementations). 0install then downloads the implementations if they are missing from the cache. Lastly, 0install uses environment variables (bindings) to tell the program where to find its dependencies; this process is known as _Dependency Injection_ (or _Inversion of Control_).
