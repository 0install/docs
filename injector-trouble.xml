<?xml version='1.0' encoding='utf-8'?>
<html lang="en">

<h2>Trouble-shooting</h2>

<toc level='h2'/>

<h2>0install doesn't select any versions / the expected versions</h2>

<div class='note'>Note: reporting the reasons for failed selections improved a lot in 0install 1.13, so please upgrade to that first if you have trouble.</div>

<p>If 0install cannot find a compatible set of versions for a program, you may see an error like this:</p>

<img src='screens/problem.png' width='543' height='336' alt='A problem' class='figure'/>

<p>Or, if not using the GUI, like this:</p>

<pre-scrolled>
Can't find all required implementations:
- http://repo.roscidus.com/java/swt -> <b>(problem)</b>
    http://www.serscis.eu/0install/serscis-access-modeller 0.15.1 requires 3.7 &lt;= version
- http://repo.roscidus.com/utils/graphviz -> 2.28.0-3.2.3 (package:rpm:graphviz:2.28.0-3.2.3:i586)
- http://repo.roscidus.com/java/openjdk-jre -> 6.18-1.8.7-2 (sha1new=6b7c9f98bd1d8bec5bbb5ddb41271c862c8e8529)
- http://repo.roscidus.com/java/iris -> 0.6.0 (sha1new=daf7bfada93ec758baeef1c714f3239ce0a5a462)
- http://www.serscis.eu/0install/serscis-access-modeller -> 0.15.1 (sha1new=7899dbe75c1c332c4e70f272b2d76026714392a6)
    User requested 0.10 &lt;= version
</pre-scrolled>

<p>
  To find the cause, start by double-clicking on "<span style='color: black; background: #faa'>(problem)</span>" in the GUI to get the list of available versions for that component. Right-click over the version you think it should have chosen and choose "Explain this decision" from the menu:
</p>

<img src='screens/explain.png' width='685' height='533' alt='Explain this decision' class='figure'/>

<p>
  In this example, the user requested serscis-access-modeller >= 0.10 and all such versions needed SWT >= 3.7 so there was no possible selection (note: this conflict was invented for the sake of this tutorial). The line "User requested implementation ..." shows the version which was selected from the list; the solver was constrained to choose this version.
</p>

<p>There are several common causes:</p>

<dl>
  <dt>There is no binary for your platform</dt>
  <dd>(e.g. you are using 32-bit Windows, but there are only binaries for 64-bit Linux). In this case, you can use 0install to compile a new binary from source (if source is provided in the feed). See <a href='0compile.html'>0compile</a> for details. If no source is provided, you will need to go to the project's web-site and compile manually.</dd>
  <dt>The dependency is only provided by distribution packages, and your distribution isn't listed</dt>
  <dd>Have a look at the feed's XML (View Page Source in your browser) to see if your distribution package is listed. See <a href='distribution-integration.html'>Distribution Integration</a> for details.</dd>
</dl>

<p>
  Sometimes, the failure to select a version is due to complex inter-dependencies between components. If a valid set of versions exists, 0install will always find it, but if no valid set exists then it can be hard for it to explain why. Rather that showing a proof that none of the billions of possible combinations is valid, 0install tries to find the best example of a failed selection to show you. It works like this:
</p>

<ol>
  <li>When the solve fails, 0install switches to debug mode and runs the solve again.</li>
  <li>Debug mode adds a fake implementation of each component. This fake version is fully compatible with everything, but less preferable than all the real versions (so it will only be selected if there is no other option).</li>
  <li>This always results in a "solution", but whenever a fake implementation is selected it is reported as "(problem)", as shown above.</li>
</ol>

<p>
This is quite good at suggesting where the fault is, but the problem may be elsewhere. For example, if a program has versions available for Python 3 and Python 2, and you only have Python 2, then 0install should select the Python 2 version. But if it can't find any valid selections (because some other library required for the Python 2 version is missing), then it might report that the lack of Python 3 is the issue (when, in fact, that's just one possible way of fixing the problem).
</p>

<p>
  To investigate further, you can use the <b>--version-for</b> option to fix the versions of multiple components. For example, to find out why you can't run 0compile 1.1 with 0publish 0.20 and 0install 2.1:
</p>

<pre-scrolled>
$ <b>0install select -c http://0install.net/2006/interfaces/0compile.xml
  --version 1.1 \
  --version-for http://0install.net/2006/interfaces/0publish 0.20 \
  --version-for http://0install.net/2007/interfaces/ZeroInstall.xml 2.1</b>
Can't find all required implementations:
- http://0install.net/2006/interfaces/0compile.xml -> 1.1 (sha1new=5d11d6a774f261b408f3c57dce8819481d842f90)
    User requested version 1.1
- http://0install.net/2006/interfaces/0publish -> 0.20 (sha1new=3a62c59321720a1736899dec9ef7deb0b29b7b43)
    User requested version 0.20
- http://0install.net/2007/interfaces/ZeroInstall.xml -> (problem)
    http://0install.net/2006/interfaces/0compile.xml 1.1 requires 2.1 &lt;= version
    http://0install.net/2006/interfaces/0publish 0.20 requires version &lt; 1.11-post
    User requested version 2.1
    No usable implementations satisfy the restrictions:
      /home/tal/Projects/zero-install/0install (2.1-post): Incompatible with user-specified requirements
      sha1new=4f860b217bb94723ad6af9062d25dc7faee6a7ae (2.1): incompatible with restrictions
      sha1new=3fa607f49966f7eb00682336a4391c78d13a3d8b (2.0): Incompatible with user-specified requirements
      sha1new=cc7a0dcf44d42714bcf1efd27e8ec1f1810ce7da (1.16): Incompatible with user-specified requirements
      sha1new=ab6ca6165cd57a1bb95ddf5af9c51cdf325e1db8 (1.15): Incompatible with user-specified requirements
      ...
- http://repo.roscidus.com/python/python -> 2.7.3-4 (package:arch:python2:2.7.3-4:x86_64)
</pre-scrolled>

<p>
Here, we can see that 0publish 0.20 requires an old version of 0install, while 0compile 1.1 requires a newer one.
</p>

<h2 id='env-vars'>Why is this environment variable set?</h2>

<p>
When you run a program, each component (library) can ask for certain environment variables to be set (this only affects the program being run, not all programs). If variables are being set incorrectly, you'll need to track down why. e.g.
</p>

<pre>
$ <b>sam</b>
Picked up _JAVA_OPTIONS: -XstartOnFirstThread
Unrecognized option: -XstartOnFirstThread
Could not create the Java virtual machine.
</pre>

<p>
To debug this, ask 0install to output the selections as XML and look for the setting inside it. xmllint is useful to format the XML nicely:
</p>

<pre>
$ <b>0install select --xml sam | xmllint --format - > selections.xml</b>
</pre>

<p>
(if "sam" is an old-style alias rather than an app, use "alias:sam" instead)
</p>

<p>
In this case, we find the selections.xml document contains:
</p>

<pre>
  &lt;selection arch="MacOSX-x86_64" version="3.6.1"
             interface="http://repo.roscidus.com/java/swt" ...>
    ...
    &lt;environment mode="prepend" name="_JAVA_OPTIONS"
                 separator=" " value="-XstartOnFirstThread"/>
  &lt;/selection>
</pre>

<p>
This tells us that the OS X implementation of version 3.6.1 of the SWT library requested this setting. To test whether this is the problem, remove the &lt;environment&gt; element and try running it:
</p>

<pre>
$ <b>0install run selections.xml</b>
</pre>

<p>
Having identified the problem, you can now file a bug report against the SWT feed.
</p>

<h2>ImportError: No module named pygtk</h2>
<dd>You need to install the <i>python-gtk2</i> package (the name may vary on different
distributions; <i>python-gnome2</i> is another possibility.</dd>

<h2 id='verbose'>Verbose logging</h2>

<p>
If 0install isn't doing what you expect, run it with logging turned up
using either <b>-v</b> (verbose) or <b>-vv</b> (very verbose!). Use <b>-c</b>
to prevent it switching to GUI mode too. Eg:
</p>

<pre-scrolled>
$ <b>0launch -vvc http://rox.sourceforge.net/2005/interfaces/Edit</b>
INFO:root:Running 0launch 1.12 ['http://rox.sourceforge.net/2005/interfaces/Edit']; Python 2.7.3 (default, Aug 26 2012, 11:57:48)
[GCC 4.7.1]
INFO:0install:Loading configuration from /home/me/.config/0install.net/injector/global
DEBUG:0install:Loading cached information for http://rox.sourceforge.net/2005/interfaces/Edit from /home/me/.cache/0install.net/interfaces/http%3a%2f%2frox.sourceforge.net%2f2005%2finterfaces%2fEdit
INFO:0install:Note: @main on document element is deprecated in &lt;Feed http://rox.sourceforge.net/2005/interfaces/Edit>
DEBUG:0install:Supported systems: '{None: 3, 'POSIX': 2, 'Linux': 1}'
DEBUG:0install:Supported processors: '{'x86_64': 0, 'i586': 2, 'i486': 3, 'i686': 1, 'i386': 4, None: 5}'
DEBUG:0install:Initialising new interface object for http://rox.sourceforge.net/2005/interfaces/Edit
DEBUG:0install:Loading cached information for http://rox.sourceforge.net/2005/interfaces/Edit from /home/me/.cache/0install.net/interfaces/http%3a%2f%2frox.sourceforge.net%2f2005%2finterfaces%2fEdit
INFO:0install:Note: @main on document element is deprecated in &lt;Feed http://rox.sourceforge.net/2005/interfaces/Edit>
DEBUG:0install:Processing feed http://rox.sourceforge.net/2005/interfaces/Edit
DEBUG:0install:Location of 'implementation-dirs' config file being used: 'None'
DEBUG:0install:Added system store '/var/cache/0install.net/implementations'
DEBUG:0install:Initialising new interface object for http://rox.sourceforge.net/2005/interfaces/ROX-Lib
DEBUG:0install:Loading cached information for http://rox.sourceforge.net/2005/interfaces/ROX-Lib from /home/me/.cache/0install.net/interfaces/http%3a%2f%2frox.sourceforge.net%2f2005%2finterfaces%2fROX-Lib
INFO:0install:Note: @main on document element is deprecated in &lt;Feed http://rox.sourceforge.net/2005/interfaces/ROX-Lib>
DEBUG:0install:Processing feed http://rox.sourceforge.net/2005/interfaces/ROX-Lib
DEBUG:0install:Initialising new interface object for http://repo.roscidus.com/python/python
DEBUG:0install:Loading cached information for http://repo.roscidus.com/python/python from /home/me/.cache/0install.net/interfaces/http%3a%2f%2frepo.roscidus.com%2fpython%2fpython
DEBUG:0install:Processing feed http://repo.roscidus.com/python/python
DEBUG:0install:Skipping '&lt;Feed from http://0install.de/feeds/Python.xml>'; unsupported architecture Windows-None
DEBUG:0install:Processing feed http://repo.roscidus.com/python/python/upstream.xml
DEBUG:0install:Loading cached information for http://repo.roscidus.com/python/python/upstream.xml from /home/me/.cache/0install.net/interfaces/http%3a%2f%2frepo.roscidus.com%2fpython%2fpython%2fupstream.xml
DEBUG:0install:Is feed-for http://repo.roscidus.com/python/python
DEBUG:0install:Staleness for &lt;Feed http://rox.sourceforge.net/2005/interfaces/ROX-Lib> is 273.80 hours
DEBUG:0install:Staleness for &lt;Feed http://repo.roscidus.com/python/python> is 273.80 hours
DEBUG:0install:Staleness for &lt;Feed http://repo.roscidus.com/python/python/upstream.xml> is 273.80 hours
DEBUG:0install:Staleness for &lt;Feed http://rox.sourceforge.net/2005/interfaces/Edit> is 273.80 hours
INFO:0install:PYTHONPATH=/var/cache/0install.net/implementations/sha256=ccefa7b1873926de15430341b912466929fbff8116b6d0ad67c4df6d0c06243e/ROX-Lib2/python
INFO:0install:Executing: [u'/usr/bin/python2.7', u'/var/cache/0install.net/implementations/sha256=ba3b495324192bb6c3fc1a2d9af3db2ced997fc8ce3177f08c926bebafcf16b9/Edit/AppRun']
</pre-scrolled>

<h2>Download problems / proxies / HTTP errors</h2>

<p>
Here are some known problems, and their solutions:
</p>

<ul>
 <li><a href="http://news.gmane.org/find-root.php?message_id=%3c76ebe6440511150110t3eb4f4e5p98c59f96b1c8ae5e%40mail.gmail.com%3e">TypeError: iterable argument required</a> (badly formed http_proxy setting)</li>
</ul>

<p>
If you get other download errors, try fetching the failing URL using wget, e.g.:
</p>

<pre-scrolled>
$ <b>0launch http://...</b>
Error downloading 'http://osdn.dl.sourceforge.net/sourceforge/zero-install/injector-gui-0.16.tgz':
HTTP Error 403: Forbidden: header 'Content-Type' value denied
$ <b>wget http://...</b>
</pre-scrolled>

<p>
If wget also fails, try opening the URL in your web browser. If one of these works, but 0launch
doesn't, it may be that you are using an HTTP proxy. Your web browser is configured to use it,
but your <b>http_proxy</b> environment variable is not set. Check your browser configuration
and ensure that <b>http_proxy</b> is set correctly, e.g.:
</p>

<pre>
$ <b>export http_proxy=http://myproxy.server:444</b>
$ <b>0launch http://...</b>
</pre>

<h2>Other problems</h2>

<p>
If you still have problems, please <a href='support.html'>write to the mailing
list</a>, and send the output of the commands above.
</p>
 
</html>
