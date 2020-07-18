title: Manual walk-through

In this guide we install a program which is available through Zero Install, but _without using the Zero Install software itself_. Instead, we will perform each step manually, as an educational experience.

[TOC]

## Pick a program

Choose a program to install from the [list of feeds](https://0install.net/injector-feeds.html). A program with one dependency is ideal (the more it has, the longer it will take you to install it!). I'll be using [Edit](http://rox.sourceforge.net/2005/interfaces/Edit) for this example.

Open the feed for Edit above in your browser. The feed is an XML file listing the available versions of Edit. Your browser should render it as a web-page:

[![Edit's web page](../img/screens/edit-xslt.png)](http://rox.sourceforge.net/2005/interfaces/Edit)

Use **View Source** to see the XML. We'll use this information to download and run Edit.

**Security note:**

Why should we trust this XML document? Who made it? Could someone have tampered with it?

Each Zero Install feed has a _digital signature_. You can find it at the end of the feed. It looks something like this:

```xml
...
</interface>
<!-- Base64 Signature
iD8DBQBGBkYcrgeCgFmlPMERAuP4AJ45BlLx1w3ocxuLIFHzM4RfIAg4hgCfWRQ/0JOjU7tIjErm
U3Vrz97gJk8=

-->
```

This is a [GnuPG](http://gnupg.org/) signature, but [Base64](http://en.wikipedia.org/wiki/Base64)-encoded so that it can go in an XML document. To check it, we need to reverse the encoding, which is easily done with a little Python. Save the two lines of random-looking characters to a file (`encoded-signature`) and decode it:

```shell
$ base64 --decode < encoded-signature > signature
```

Now remove the whole signature block from the XML (every line from `<!-- Base64 Signature` onwards), save it as `Edit.xml`, and check it with GPG:

```shell
$ gpg --keyid-format=long signature 
Detached signature.
Please enter name of data file: Edit.xml
gpg: Signature made Sun 25 Mar 2007 10:51:24 BST
gpg:                using DSA key AE07828059A53CC1
gpg: Good signature from "Thomas Leonard ...
```

If you don't have the feed author's key, you can get it from various places (in the normal way), or by fetching it from the same directory as the feed, e.g.:

```shell
$ wget http://rox.sourceforge.net/2005/interfaces/AE07828059A53CC1.gpg
$ gpg --import AE07828059A53CC1.gpg 
```

This all tells you that the XML file was created by the owner of the key and hasn't been tampered with. How you decide to trust the key itself is up to you, but one way is to check it using the default key information server:

```shell
$ gpg --with-colons --fingerprint AE07828059A53CC1 | grep fpr
fpr:::::::::92429807C9853C0744A68B9AAE07828059A53CC1:
$ wget -qO - https://keylookup.appspot.com/key/92429807C9853C0744A68B9AAE07828059A53CC1
<?xml version="1.0" encoding="utf-8"?>
<key-lookup>
  <item vote="good">Thomas Leonard created Zero Install and ROX.
    This key is used to sign updates to the injector; you should accept it.
  </item>
  <item vote="good">This key belongs to a Debian Maintainer.</item>
</key-lookup>
```

In any case, you should make a note of which key was used so you can ensure it's the same later, when you check for updates.

## Pick a version

Edit's feed lists several versions. I'll pick the latest one: version 2.0. If you find that version to be buggy, you might make a note of that and try a different version. Some may have been released recently and be marked as "testing". Whether you skip such versions is up to you. The relevant section of the XML file is this bit:

```xml
  <group main="Edit/AppRun">
    <requires interface="http://rox.sourceforge.net/2005/interfaces/ROX-Lib">
      <environment insert="ROX-Lib2/python" name="PYTHONPATH"/>
    </requires>
...
    <implementation id="sha1=329e6c0191f65ef2996b49837d04c4cfe6934758"
                    released="2005-09-21" stability="stable" version="2.0">
      <archive extract="edit-2.0"
               href="http://kent.dl.sourceforge.net/sourceforge/rox/edit-2.0.tgz" size="61195"/>
    </implementation>
  </group>
```

This tells us that we can get Edit version 2.0 by downloading [edit-2.0.tgz](http://kent.dl.sourceforge.net/sourceforge/rox/edit-2.0.tgz) and extracting the `edit-2.0` directory inside it:

```shell
$ wget http://kent.dl.sourceforge.net/sourceforge/rox/edit-2.0.tgz
$ tar xzf edit-2.0.tgz edit-2.0
$ ls edit-2.0
Edit
```

**Security note:**

How do we know the archive hasn't been tampered with? The author of the XML feed calculated a _cryptographic digest_ of the archive's contents and stored it in the feed:

```xml
    <implementation id="sha1=329e6c0191f65ef2996b49837d04c4cfe6934758"
                    released="2005-09-21" stability="stable" version="2.0">
      <archive extract="edit-2.0"
               href="http://kent.dl.sourceforge.net/sourceforge/rox/edit-2.0.tgz" size="61195"/>
    </implementation>
```

We can calculate the value ourselves from the directory and compare (we already verified the signature on the feed, so we know that one's OK). Calculating the value is a little tricky; you have to create a _manifest_ file listing all the files and directories in the archive, along with _their_ digests too, by following [these instructions](../specifications/manifest.md). We'll cheat, by using `0install` to generate it for us:

```shell
$ 0install digest edit-2.0 --algorithm=sha1 --manifest --digest
D 1127294333 /Edit
F 0cfc0b0c42b4f4c077f005f31ea1801a8e43bde0 1053080001 3409 .DirIcon
...
X d6f4507353737e35ce7af5ab10a589e5a644bec5 1106998691 1472 testreplace.py
sha1=329e6c0191f65ef2996b49837d04c4cfe6934758
```

This says we have a directory called `Edit` containing a file called `.DirIcon`, and so on. The last line isn't part of the manifest; it's the digest of the manifest itself. It is this value that must match the _id_ in the feed. It's useful to save the manifest output (minus the last line) in case we want to do an audit later:

```shell
$ 0install digest edit-2.0 --algorithm=sha1 --manifest > edit-2.0/.manifest
```

This file will have the digest we require:

```shell
$ sha1sum edit-2.0/.manifest 
329e6c0191f65ef2996b49837d04c4cfe6934758  edit-2.0/.manifest 
```

## Run it!

We've downloaded and unpacked Edit. How do we run it? We don't have to guess; the answer is in the XML feed file:

```xml
  <group main="Edit/AppRun">
```

There was no `main` attribute on the `implementation` element, so we look in the containing group and find one. This tells us that we can run the program by executing the file `Edit/AppRun` inside the unpacked directory.

```shell
$ ./edit-2.0/Edit/AppRun
*** This program needs ROX-Lib2 (version 1.19.14) to run.
```

If the program you picked didn't have any dependencies then it should now run (lucky you!). But, like many programs, Edit needs libraries to work.

## Resolving dependencies

Our program needs some libraries before it will run. Which ones? Again, the feed XML tells us:

```xml
  <group main="Edit/AppRun">
    <requires interface="http://rox.sourceforge.net/2005/interfaces/ROX-Lib">
      <environment insert="ROX-Lib2/python" name="PYTHONPATH"/>
    </requires>
```

We need a library called `http://rox.sourceforge.net/2005/interfaces/ROX-Lib`. You might already have one somewhere, or your distribution might carry it. If all else fails, we could try to guess where a suitable feed might be... <http://rox.sourceforge.net/2005/interfaces/ROX-Lib> seems like a good place to start...

Go back to step 1! You need to download ROX-Lib's feed, check the signature, pick a version, download and unpack the archive, check the contents' digest, and check whether ROX-Lib in turn depends on other libraries (it doesn't).

## Running with dependencies

OK, so you've got ROX-Lib too now. Things should look like this:

```shell
$ ls
edit-2.0  Edit.xml  rox-lib2-2.0.3  ROX-Lib.xml
```

How can we tell Edit where we put ROX-Lib? Again, the answer is in Edit's XML file:

```xml
  <group main="Edit/AppRun">
    <requires interface="http://rox.sourceforge.net/2005/interfaces/ROX-Lib">
      <environment insert="ROX-Lib2/python" name="PYTHONPATH"/>
    </requires>
```

We need to add a directory inside our unpacked ROX-Lib to the `$PYTHONPATH` environment variable, and then try running Edit again:

```shell
$ export PYTHONPATH=rox-lib2-2.0.3/ROX-Lib2/python
$ ./edit-2.0/Edit/AppRun
```

Finally Edit runs!

## Review

So, what have we achieved? We've installed a program and its dependencies. We haven't touched anything outside of our test directory; no other programs on the system have been affected by this installation. We didn't need root access. We can undo the installation just by deleting our two XML files and the two unpacked archives.

We didn't need to run any code from either Edit or ROX-Lib to perform the installation (we only ran them to test that it worked). If you have software for running programs in a restricted environment, you can install and run programs without even giving them write access to their own code.

Finally, although it may have been a little tedious, everything we did could be automated. You could write a program to do all this for you (or use [ours](https://0install.net/injector.html)!).

## Tidying up

Our home directory will become a big mess if we just install things in random directories. When we want to run another program that uses ROX-Lib, we'll want to be able to find the copy we already installed rather than downloading another copy.

These files don't really need to be backed up. If we lost them, we could just download them again from the web. So, we'll put them in the `~/.cache` directory which is designed for just this purpose. In fact, to avoid conflicts with other things using this directory, we'll keep everything under `~/.cache/0install.net`, because it's all related to Zero Install.

We have two XML files and two directories. What should we call them? It's not impossible to imagine there being two programs in the world both called _Edit_, so that's not a good name; we wouldn't know what to do if we wanted both on our system at once! A good choice is to use the full URL of Edit's feed for the XML file (replacing `/` characters with %2f in the traditional web way):

```shell
$ mv Edit.xml \
     ~/.cache/0install.net/interfaces/http%3a%2f%2frox.sourceforge.net%2f2005%2finterfaces%2fEdit
```

It's a bit ugly, but at least it's unique and we can find it again. We'll store ROX-Lib's XML in the same way:

```shell
$ mv ROX-Lib.xml \
  ~/.cache/0install.net/interfaces/http%3a%2f%2frox.sourceforge.net%2f2005%2finterfaces%2fROX-Lib
```

What about the directories with the actual program files? We could use the same strategy, naming it from the URL where we got it, but this has some problems. For example, there might be lots of places where you can get the archive (mirrors, peer-to-peer, CD-ROM, etc). We don't care how we got it, all we care about is that it has the right digest. So, we'll name it after that!

```shell
$ mv edit-2.0 ~/.cache/0install.net/implementations/sha1=329e6c0191f65ef2996b49837d04c4cfe6934758
$ mv rox-lib2-2.0.3 ~/.cache/0install.net/implementations/sha1=6a2e548a80368bd8c2b5b3abedccf9a0a6cb4333
```

This is exactly the scheme that `0install` uses. We can test this quite easily:

```shell
$ 0install run --offline http://rox.sourceforge.net/2005/interfaces/Edit
```

Zero Install runs Edit without downloading anything. It is able to use the files we downloaded and placed in its cache manually.
