<?xml version='1.0' encoding='utf-8'?>
<html lang="en">

<h2>Local feeds</h2>

<p>
  Normally, 0install downloads a feed from the web, selects a version, downloads the archive for that version, and runs it. However, 0install can also be used locally (e.g. to run a program you are currently writing, which hasn't been released yet). There are several reasons why you might want to do this:
</p>

<ul>
  <li>0install can select and download your program's build or runtime dependencies.</li>
  <li>It provides a cross-platform way to set environment variables and start your program.</li>
  <li>You can use <a href='0release.html'>0release</a> to generate releases automatically.</li>
</ul>

<h3>A simple example</h3>

<p>
  Let's say you have a simple Python 2 program, hello.py:
</p>

<pre>
print "Hello World!"
</pre>

<p>
  You could make this runnable by specifying a <a href='http://en.wikipedia.org/wiki/Shebang_%28Unix%29'>shebang line</a>. But that wouldn't work on Windows (which doesn't support them). Also, different versions of Linux need different lines (e.g. "#!/usr/bin/python" on Debian, but "#!/usr/bin/python<b>2</b>" on Arch).
</p>

<p>
  Instead, we can create a <i>local feed</i> to say how to run it. Create <b>hello.xml</b> in the same directory:
</p>

<pre>&lt;?xml version="1.0" ?>
&lt;interface xmlns="http://zero-install.sourceforge.net/2004/injector/interface">
  &lt;name><b>Hello</b>&lt;/name>
  &lt;summary><b>minimal demonstration program</b>&lt;/summary>

  &lt;implementation <b>id="."</b> version="<b>0.1-pre</b>">
    &lt;command name='run' path='<b>hello.py</b>'>
      &lt;runner interface='<b>http://repo.roscidus.com/python/python</b>'>
        &lt;version <b>before='3'</b>/>
      &lt;/runner>
    &lt;/command>
  &lt;/implementation>
&lt;/interface>
</pre>

<p>
  Setting <b>id="."</b> says that the implementation of this interface is the directory containing the feed (whereas normally we'd specify a digest and a URL from which to download the archive).
</p>

<p>
  There are two other differences to note: there is no digital signature at the end (we assume that no attacker could intercept the file between your harddisk and you ;-), and the version number ends in a modifier (<b>-pre</b> in this case), showing that it hasn't been released.
</p>

<p>
  You can now use this feed with the usual 0install commands. For example:
</p>

<pre>
$ <b>0launch hello.xml</b>
Hello World!

$ <b>0install add hello-dev hello.xml</b>
$ <b>hello-dev</b>
Hello World!

$ <b>0install select hello.xml</b>
- URI: /home/bob/hello/hello.xml
  Version: 0.1-pre
  Path: /home/bob/hello
  - URI: http://repo.roscidus.com/python/python
    Version: 2.7.3
    Path: (package:deb:python2.7:2.7.3:x86_64)
</pre>

<p>
This will work on Linux, MacOS X, Windows, etc.
</p>

<h3>Next steps</h3>

<p>Some more things you can do with your new local feed:</p>

<ul>
  <li>Depend on other libraries or tools (see <a href='interface-spec.html'>the feed specification</a> for reference).</li>
  <li>Compile source code using <a href='0compile-dev.html'>0compile</a>.</li>
  <li>Make a release using <a href='0release.html'>0release</a>.</li>
  <li>Test against different versions of dependencies using <a href='0test.html'>0test</a>.</li>
</ul>

<p>See the <a href='templates.html'>example templates</a> for projects in different languages and using various build systems.</p>

<quicklinks>
  <link href='packaging-concepts.html' img='tango/go-prev.png'>Go back</link>
  <link href='packaging-binaries.html' img='tango/go-next.png'>Continue</link>
</quicklinks>

</html>
