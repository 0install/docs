title: JSON API

Starting with 0install 2.6, you can connect your programs to 0install via a JSON API. This is useful for writing 0install-based tools in other languages.

[TOC]

# Introduction

To use the JSON API, run `0install slave API-VERSION` (where `API-VERSION` is the latest protocol version you support), like this:

```shell
$ 0install slave 2.7
0x00000031
["invoke",null,"set-api-version",["2.6.2-post"]]
```

0install responds first with a line indicating the length in octets of the JSON message to follow. All length headers sent by 0install are 11 octets long, including the leading "0x" and the trailing newline. After this comes the JSON message, giving the API version it will use. This will be the version you specified or the latest version it supports, whichever is smaller.

To invoke an operation, send the message `["invoke", myref, op, args]`, where `myref` is any string you choose, which will be attached to the reply, `op` is the operation to invoke and `args` are the arguments. Each message must start with a line giving the length in octets of the following message. For example, to ask 0install to select a set of components to run GnuPG:

```plain
0x5d
["invoke", "1", "select", [{"interface": "http://repo.roscidus.com/security/gnupg"}, false]]
```

All responses have the form `["return", myref, status, return-value]`, where `myref` lets you correlate this reply with the request you sent. The possible responses are `["ok", return-value]`, `["ok+xml", return-value]` and `["fail", error-message]`. In the case of `ok+xml`, the message is immediately followed by another length line and some XML. The above request might generate this response, which says to use the distribution's native version of GnuPG (2.0.22-2):

```plain
0x0000002f
["return","1","ok+xml",["ok",{"stale":false}]]
0x000001f5
<?xml version="1.0" encoding="UTF-8"?>
<selections interface="http://repo.roscidus.com/security/gnupg" xmlns="http://zero-install.sourceforge.net/2004/injector/interface"><selection distributions="Arch Slack Debian RPM Gentoo Cygwin" from-feed="distribution:http://repo.roscidus.com/security/gnupg" id="package:arch:gnupg:2.0.22-2:x86_64" interface="http://repo.roscidus.com/security/gnupg" package="gnupg" quick-test-file="/var/lib/pacman/local/gnupg-2.0.22-2/desc" version="2.0.22-2"/></selections>
```

# Callbacks

In the process of handling your request, 0install may send its own invoke messages to you. You should be prepared to handle these messages:

`set-api-version(version)`
: This is sent immediately at the start, indicating the protocol version to be used. The "myref" field is "null", indicating that no reply is expected. The version may be earlier than the version you requested, in which case you can either fall back to the earlier version or tell the user to upgrade.

`confirm(message)`
: If 0install needs to ask the user to confirm something, it sends this message. This is used to confirm installation of distribution-provided packages, if any. Respond with success ("ok") and a return value of "ok" or "cancel". This is currently not used, because "select" does not require it.

`confirm-keys(feed_url, keys)`
: A feed has been downloaded but has no trusted signature. Prompt the user to accept the keys. `keys` is a list of `(fingerprint, hints)` pairs, where `hints` is a list of `(vote, message)` hints from the key information server. Each vote is "good" or "bad". You should respond with a list of fingerprints which 0install should trust to sign updates for this domain. 0install will only ask you to confirm one feed at a time. Example:

```plain
["invoke","1","confirm-keys",[
  "http://repo.roscidus.com/lib/readline6",{
    "DA9825AECAD089757CDABD8E07133F96CA74D8BA":[
      ["good","Thomas Leonard created Zero Install and ROX.
      This key is used to sign updates to the injector; you should accept it.
      It was announced on the Zero Install mailing list on 2009-05-31."]]
    }]]
```

If the server is slow to respond, you will get a "pending" message instead, followed by a call to "update-key-info" later:

```plain
["invoke","1","confirm-keys",[
  "http://repo.roscidus.com/lib/readline6",{
    "DA9825AECAD089757CDABD8E07133F96CA74D8BA":["pending"]}]]
```

`update-key-info`
: If the key information server is slow to respond, 0install may call `confirm-keys` before the hints have arrived. It will then send this message when the information arrives (or fails), so you can update the display. Note: the format here is a list of keys rather than a map. Example:

```plain
["invoke","2","update-key-info",
  ["DA9825AECAD089757CDABD8E07133F96CA74D8BA",[
    ["bad","Error fetching key info: ..."]]]]
```

If 0install is able to use its own GUI, it will use that rather than these callbacks. Start it with `--console` if you want to use your own GUI in all cases.

# Operations

Currently, only the `select` operation is supported (let us know if you want more; they're easy to add):

**select(requirements, refresh)**

Return a set of selections to run the given program. If `refresh` is `true`, 0install will always try to download a fresh copy of the feeds. Otherwise, it will reply immediately if possible. `requirements` is a JSON object with these keys (only `interface` is required):

`interface`
: The URI of the program to run.

`command`
: The `<command>` to run (e.g. `run`, `test`, `compile` or `null`).

`source`
: Whether to select source code (and build dependencies) rather than a binary.

`extra_restrictions`
: An object mapping interface URIs to version expressions, e.g. `{"http://repo.roscidus.com/python/python": "..!3"}` to require a version of Python less than 3.

`os`
: Select implementations for the given OS (e.g. "Linux")

`cpu`
: Select implementations for the given CPU (e.g. "x86_64" or "src")

`message`
: A message to display if 0install uses its own GUI ("I need this because ...")

`may_compile`
: (0install >= 2.9; default `false`)
: Treat source implementations as potential binaries. If a source implementation is selected, it will be tagged with `requires-compilation="true"` to indicate this.
: Returns `["ok",{"stale":stale-flag}]` on success. If stale-flag is true, the selections are based on old information. Consider using `refresh` to check for updates.

# Sample code

There is some [sample Python client code](https://github.com/0install/0install/blob/master/ocaml/sample_client.py) available.

# Backwards compatibility

The text above documents the latest version of the protocol. The differences are:

2.6
: In this version, `select` returns `"ok"` without the information about staleness.
