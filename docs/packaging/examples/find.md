<?xml version='1.0' encoding='utf-8'?>
<html lang="en">

<h2>Packaging Guide Example: Find</h2>

<p>
In the <a href='injector-packagers.html'>main packaging guide</a> (which should be read before
this one) we saw how to make a simple Python program available. When the user asked to run the
program, the injector downloaded it for them and cached it. However, most programs depend on
other programs, libraries and resources and these can also be fetched using the injector.
Our example package will be <a href="http://www.hayber.us/rox/Find">Find</a>, a little
utility for searching for files which depends on the ROX-Lib Python library.
</p>

<toc level='h2'/>

<h2>Running Find directly</h2>

<p>
Start by downloading <a href='http://www.hayber.us/rox/find/Find-006.tgz'>Find-006</a>. This is just a
normal application, not specially designed for the injector. If you try to run it, you should get an
error:
</p>

<pre>
$ <b>wget http://www.hayber.us/rox/find/Find-006.tgz</b>
$ <b>tar xzf Find-006.tgz</b>
$ <b>cd Find</b>
$ <b>./AppRun</b>
*** This program needs ROX-Lib2 (version 2.0.0) to run.
</pre>

<p>
Note: If it runs without an error, then either you've installed ROX-Lib
manually (not using the injector) or your PYTHONPATH already points to it.
</p>

<h2>Creating the interface file</h2>

<p>
Start by creating an XML interface file (<b>Find.xml</b>) as we did before:
</p>

<pre>
$ <b>0publish Find.xml</b>
</pre>

<p>
Fill in the fields as before. The only difference is the addition of the <b>requires</b> element,
which states that this program requires <b>ROX-Lib</b>, and the <b>main</b> attribute which is now
<b>AppRun</b>. The final result should look something like this:
</p>

<pre><![CDATA[
<?xml version="1.0" ?>
<?xml-stylesheet type='text/xsl'
     href='http://0install.net/2006/stylesheets/interface.xsl'?>
 
<interface xmlns="http://zero-install.sourceforge.net/2004/injector/interface">
  <name>Find</name>
  <summary>a find-in-files utility</summary>
  <description>
Searches files for specific text, displaying the results in a window. Double click
on the result line(s) to open the file at that point.
 
Configuration options are available to customize the search command and the editor with which to
open the files.
  </description>
  <homepage>http://www.hayber.us/rox/Find</homepage>
  <icon type='image/png' href='http://www.hayber.us/0install/Find.png'/>
 
  <group main='AppRun'>
    <requires interface="http://rox.sourceforge.net/2005/interfaces/ROX-Lib">
      <environment insert="ROX-Lib2/python" name="PYTHONPATH"/>
    </requires>
    <implementation id='.' version='6'/>
  </group>
</interface>
]]></pre>

<p>
Let's go over the group part in detail:</p>

<ul>
<li>
The <b>&lt;group&gt;</b> element contains a set of implementations of the
interface (versions of <b>Find</b>) and their requirements. The group saves us having
to repeat the requirements for each implementation (since they often don't change). We have
also given the <b>main</b> attribute here, since all versions of <b>Find</b> are run using
a script with this name.
</li>

<li>
We have a single requirement, ROX-Lib, which is identified by the URI of its interface.
The URI is chosen by the publisher of the ROX-Lib interface, just as we chose the URI for
our SCons interface in the previous tutorial.
</li>

<li>
The <b>&lt;environment&gt;</b> element inside tells the injector how to make its
choice known to <b>Find</b>. In this case, it is by inserting
<b>/path/of/cache/DIGEST/ROX-Lib2/python</b> to the beginning of the <b>PYTHONPATH</b>
environment variable. When Find (a Python program) does <b>import rox</b>, it
will then get the chosen version of <b>ROX-Lib</b>.
</li>

<li>
Also inside the group we have a single (local) implementation, as before. The <b>id</b> gives the
location of the implementation directory relative to the interface file. In this case, we are assuming
that the <b>Find.xml</b> file is in the same directory as the <b>AppRun</b> script.
</li>
</ul>

<p>
Save the interface using the default file name (a temporary file chosen by <b>0publish</b>) and try running it:
</p>

<pre>
$ <b>0launch ./Find.xml</b>
</pre>

<p>
If you don't already have ROX-Lib, you will be prompted to download it as usual. Once ROX-Lib is available,
Find runs:
</p>

<p style='text-align: center'>
  <img width="469" height="325" src="find.png" alt='Find running' />
</p>

<p>
As usual, you can run <b>0launch -g ./Find.xml</b> to force the 0launch GUI to appear. You will see that there
is a single version of Find available, but that you can choose any version of ROX-Lib to use with it.
</p>

<h2>Adding the archive</h2>

<p>
We will now change the implementation as we did before so that the injector will download <b>Find</b> for us
instead of requiring it to be on the machine already:
</p>

<pre>
$ <b>0publish Find.xml \
  --set-version=6 \
  --set-released=today \
  --archive-url=http://www.hayber.us/rox/find/Find-006.tgz \
  --archive-file=../Find-006.tgz \
  --archive-extract=Find</b>
</pre>

<p>
As the archive file isn't in the current directory, we give its location with
<b>--archive-file</b>. <b>0publish</b> needs this to get the digest, but it
doesn't download the archive from the network because it wouldn't be able to check
that it hasn't been tampered with (although if the program's author doesn't provide
a signature then there may be no way to check anyway).
</p>

<p>
We also use the <b>--archive-extract</b> attribute. This is because each
<b>Find</b> archive contains a single top-level directory, which we don't need
(and the name might change in different versions, e.g. if the author decides
to include the version number). Extracting just the contents means the every
version will have the same structure, which makes it easier for other programs
to depend on it. This is mostly useful for libraries like ROX-Lib, where we need
to know that the path will always be <b>lib</b>, not <b>libfoo-1.1/lib</b> with
version 1.1 and <b>libfoo-1.2/lib</b> with version 1.2, since a fixed path has to go
in the <b>environment</b> element above.
</p>

<p>
The resulting file will contain this:
</p>

<pre><![CDATA[
    <implementation id='sha1=ff9d9e11fde0a146c7e1781511fd9afb17752e34' released="2006-05-19" version='6'>
      <archive href="http://www.hayber.us/rox/find/Find-006.tgz" size="23161" extract='Find'/>
    </implementation>
]]></pre>

<p>
The attributes of <b>&lt;archive&gt;</b> are:
</p>

<dl>
 <dt>href</dt> <dd>a URL from which the archive can be downloaded (in escaped
 form, so a space is written as %20, etc).</dd>
 <dt>size</dt> <dd>the size of the archive (for progress bars).</dd>
 <dt>extract</dt> <dd>(optional) a subdirectory of the archive to use. We could have omitted this and
 changed the implementation's <b>main</b> to <b>Find/AppRun</b> instead, but it's better to keep the
 main attribute the same, if possible.</dd>
</dl>

<p>
If you run the new <b>Find.xml</b>, the injector should download and unpack the
archive into the cache, and run Find from it.
</p>

<div class="note" style='text-align: left'>
<h3>Note on weaknesses in SHA-1</h3>
<p>Some weaknesses in the SHA-1 algorithm have been discovered. At present, it
is still strong enough for our use, but you may wish to use some other algorithm, with the
<b> --manifest-algorithm</b> option. See the <a href='injector-specs.html'>specification</a>
for a list of available secure hashing algorithms and which versions of the
injector support them.
</p>
<p>See the <a href="http://www.cryptography.com/cnews/hash.html">HASH COLLISION Q&amp;A</a> for
more details.</p>
</div>

<h2>Publishing the interface</h2>

<p>
The <b>Find.xml</b> interface file can now be signed and published as described before:
</p>

<pre>
$ <b>0publish Find.xml --set-interface-uri=http://www.hayber.us/0install/Find</b>
$ <b>0publish Find.xml --xmlsign</b>
</pre>

<h2>Registering the local feed</h2>

<p>
We have already seen how to use a local <b>Find.xml</b> file inside the <b>Find</b> directory
to run the local version with a chosen version of <b>ROX-Lib</b> (i.e., by setting the <b>id</b>
attribute to "."). It is quite useful to add this file to your CVS (or similar
system) to let developers test new versions easily, since it will get the libraries for them.
</p>

<p>
In fact, we'd often like to see both downloadable implementations (e.g., official releases) and local
versions (e.g., developer CVS checkouts) of Find together. To do this, all we need is to add a <b>feed-for</b>
line in the local <b>Find/Find.xml</b> file (the one where the implementation <b>id</b> is <b>.</b>):
</p>

<pre><![CDATA[
<?xml version="1.0" ?>
<interface xmlns="http://zero-install.sourceforge.net/2004/injector/interface">
  <feed-for interface='http://www.hayber.us/0install/Find'/>
]]></pre>

<p>
You can now register the local feed in the normal way:
</p>

<pre>
$ <b>0launch --feed Find.xml</b>
</pre>

<p>
When another program runs <b>Find</b> using its official URI, this local
version will be included in the list of available versions that can be
selected. It is traditional to add <b>.0</b> to the end of the version number
for CVS versions, so that the CVS version will be preferred to the previous
release.
</p>

<h2>Problems with manifest digests</h2>

<p>
There is one possible problem with the digests, where the 'actual' manifest changes each time the archive is extracted!
This happens when you include only some deep subdirectories in the archive, but not the top-level directory. Eg:
</p>

<pre>
$ tar czf archive.tgz deeply/nested/path
</pre>

<p>
When tar extracts the archive, it restores the original mtime of 'path', but creates 'deeply' and 'nested' with the current
time. This is what causes the digest to change. There are two possible solutions:
</p>

<ul>
 <li>Always list all top-level directories when creating archives.</li>
 <li>Use any algorithm except <b>sha1</b>, as this is the only one that includes directory mtimes in the
 digest (for backwards compatibility).</li>
</ul>

</html>
