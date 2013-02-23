<?xml version='1.0' encoding='utf-8'?>
<html>

<h2>Understanding 0install: concepts</h2>

<h3>Interfaces</h3>
<p>
  An interface describes what something does (e.g. "Edit - a simple text editor").
</p>
<p>
  In Zero Install, interfaces are named by globally unique URIs (like web
  pages). Some examples of interfaces are:
</p>
<ul>
  <li>http://rox.sourceforge.net/2005/interfaces/Edit</li>
  <li>http://rox.sourceforge.net/2005/interfaces/ROX-Lib</li>
</ul>

<p>
  When a user asks to run a program, they give the interface URI:
</p>

<pre>0launch http://rox.sourceforge.net/2005/interfaces/Edit</pre>

<p>
  When a program depends on a library, it gives library's interface URI:
</p>

<pre>
&lt;requires interface="http://rox.sourceforge.net/2005/interfaces/ROX-Lib">
</pre>

<h3>Feed files</h3>
<p>
  A feed file is a list of implementations (versions) of an interface. It is called a feed because new
  versions get added to it when they are released, just as news items are added to an RSS feed.
</p>
<p>
  Usually an interface has only one feed file, located at the interface's URI.
  Some examples of feeds are:
</p>

<ul>
  <li><a href="http://rox.sourceforge.net/2005/interfaces/Edit">http://rox.sourceforge.net/2005/interfaces/Edit</a></li>
  <li><a href="http://rox.sourceforge.net/2005/interfaces/ROX-Lib">http://rox.sourceforge.net/2005/interfaces/ROX-Lib</a></li>
  <li><b>/home/tal/dev/edit/Edit.xml</b> (a local feed)</li>
</ul>

<p>
  You can add additional local and remote feeds to an interface. A <i>local feed</i>
  is located locally on your machine, whereas a <i>remote feed</i> is located on the
  web (even if it is cached on your machine).
</p>

<h3>Implementations</h3>
<p>
  An <i>implementation</i> is something that implements an interface. Edit-1.9.6 and Edit-1.9.7 are both implementations
  of http://rox.sourceforge.net/2005/interfaces/Edit.
</p>
<p>
  Each implementation of an interface is identified by a cryptographic digest, eg:
</p>
<ul>
  <li><b>sha1=235cb9dd77ef78ef2a79abe98f1fcc404bba4889</b></li>
  <li><b>sha1=c86d09f1113041f5eaaa8c3d1416fcf4dad8e2e0</b></li>
</ul>

<p>
  For platform independent binaries (e.g. Python code) there will be one implementation for each version.
  For compiled code, there will be one implementation per architecture per version.
</p>

<h3>Launching</h3>
<p>
  When you launch a program (like Edit) 0install looks up the feed files of the
  interface and chooses an implementation of the interface and the interfaces
  it depends on according to the policy settings (e.g. preferring "stable" or "testing"
  implementations). 0install then downloads the implementations if they are
  missing from the cache. Lastly, 0install uses environment variables (bindings)
  to tell the program where to find its dependencies; this process
  is known as <i>Dependency Injection</i> (or <i>Inversion of Control</i>).
</p>

<quicklinks>
  <link href='injector-packagers.html' img='tango/go-prev.png'>Go back</link>
  <link href='local-feeds.html' img='tango/go-next.png'>Continue</link>
</quicklinks>

</html>
