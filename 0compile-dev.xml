<?xml version='1.0' encoding='utf-8'?>
<html lang="en">

<h2>0compile: Developers</h2>

<p>
  This page explains how to publish source code using Zero Install. Publishing source this way means that:
</p>

<ul>
  <li>Users can compile it easily <a href='0compile.html'>using 0compile</a>.</li>
  <li>Build dependencies, such as header files and build tools, can be downloaded automatically.</li>
  <li><a href='0release.html'>0release</a> can automatically compile binaries for your software during the release process.</li>
</ul>

<h2>Contents</h2>

<toc level='h2'/>

<h2>Making source available</h2>

<p>
  There are two common situations:
</p>

<ul>
  <li>You already have a source tarball somewhere and you want to publish an XML feed saying how to download and compile it (ideally, no changes should be needed to support 0install). For this, you should first read the <a href='packaging-binaries.html'>Guide to packaging binaries</a>.</li>
  <li>You have a development checkout (e.g. a Git clone or similar) and you want to say how to compile it. For this, you should first read the page about <a href='local-feeds.html'>local feeds</a>.</li>
</ul>

<p>
To make source code available for others to use you need to add source
implementations to the program's feed file. This is almost exactly the same as
adding binaries, except that you give <b>src</b> as the machine (CPU) type:
</p>

<p style='text-align: center'>
<img width="676" height="456" src="screens/add-source-impl.png" alt="Adding a source implementation" />
</p>

<p>
  (if you want to script this process, take a look at <a href='0template.html'>0template</a>)
</p>

<p>
  You can also edit the XML directly, which gives more control. A minimal source implementation might look like this:
</p>

<pre>
&lt;implementation <b>arch="*-src"</b> id="." version="0.1-pre">
  &lt;command <b>name='compile'</b> path='Makefile'>
    &lt;runner interface='http://repo.roscidus.com/devel/make'>
      &lt;arg>-f&lt;/arg>
    &lt;/runner>
  &lt;/command>
&lt;/implementation>
</pre>

<p>
  Note: you only need to use <b>arch='*-src'</b> to publish implementations for things that need to be compiled. Shell scripts, etc should not be marked as source code; use <b>arch="*-*"</b> instead (which is the default anyway if <b>arch</b> is not given). For header files (-dev packages), there will often be a source implementation that generates the header files, but the resulting headers are not source (and will often be architecture-specific, e.g. "Linux-i386").
</p>

<p>The job of the <b>compile</b> command is to call the actual build system.
It is executed inside the <b>build</b> directory (<b>$BUILDDIR</b>).
It must compile the source in <b>$SRCDIR</b>, putting the final result (ready for distribution) in <b>$DISTDIR</b>.
The path to the generated feed for the new binary is <b>$BINARYFEED</b>, if you need it during the build.
</p>

<p>
  Instead of giving a &lt;runner&gt;, you may prefer to use a shell command. This is useful if you need to run more than one command. However, if the command starts to get complicated, you should move it to a script, either inside the main source archive or in a separate dependency, and just set this attribute to the command to run the script):
</p>

<pre>
&lt;implementation arch="*-src" id="." version="0.1-pre"&gt;
  &lt;command name='compile'
    <b>shell-command='&quot;$SRCDIR/configure&quot; --prefix=&quot;$DISTDIR&quot; &amp;&amp; make install'</b>/>

  &lt;requires interface='http://repo.roscidus.com/devel/make'>
    &lt;executable-in-path name='make'/>
  &lt;/requires>
&lt;/implementation&gt;
</pre>

<div class='note'>2013-04-03: starting with 0compile 1.1, this even works on Windows (it uses win-bash).</div>

<p>
There are also some extra attributes you can add to the <b>implementation</b> element:
</p>

<dl>
 <dt>compile:binary-main</dt>
 <dd>Deprecated. Use &lt;compile:implementation&gt; instead (see below).</dd>

 <dt>compile:dup-src</dt>
 <dd>Some programs insist on creating files in their source directory, which is
typically a read-only directory when using Zero Install. In this case, set
<b>compile:dup-src='true'</b> and 0compile will copy everything in $SRCDIR
into 'build' before building.</dd>

 <dt>compile:binary-lib-mappings (binary library major mappings)</dt>
 <dd>This is needed if you want to use 0install to compile a -dev package (containing header files) that works with a distribution-provided runtime package; see <a href='make-headers.html'>MakeHeaders</a> for details.</dd>

</dl>

<h2>Customising the binary implementation</h2>

<p>
  You can specify a template &lt;implementation&gt; for the binary using &lt;compile:implementation&gt;. You can use this, for example, to add &lt;command&gt; elements to it. Here's a more complex example for a Java program:
</p>

<pre>
  ... xmlns:compile="http://zero-install.sourceforge.net/2006/namespaces/0compile" ...

  &lt;implementation <b>arch="*-src"</b> id="." version="0.1-pre">

    <b>&lt;command name="compile" path="src/Makefile"></b>
      &lt;runner interface='http://repo.roscidus.com/devel/make'>
        &lt;arg>-f&lt;/arg>
      &lt;/runner>

      <b>&lt;compile:implementation arch='*-*'></b>
        &lt;environment name='CLASSPATH' insert='.'/>
        &lt;requires interface="http://repo.roscidus.com/utils/graphviz"/>
        <b>&lt;command name='run'></b>
          &lt;runner interface='http://repo.roscidus.com/java/openjdk-jre'/>
          &lt;arg>com.example.MainClass&lt;/arg>
        &lt;/command>
      &lt;/compile:implementation>
    &lt;/command>

    &lt;requires interface="http://repo.roscidus.com/java/iris" compile:include-binary='true'/>
    &lt;requires interface="http://repo.roscidus.com/java/openjdk-jdk">
      &lt;environment name='PATH' insert='bin'/>
    &lt;/requires>
  &lt;/implementation>
</pre>

<p>The interesting bits here are:</p>

<dl>
  <dt>arch="*-src"</dt><dd>tells us that the root &lt;implementation&gt; describes some source code.</dd>
  <dt>&lt;compile:implementation&gt;</dt><dd>this is the template for the implementation that will be created by the compile.</dd>
  <dt>arch='*-*'</dt><dd>indicates that the generated binary is platform independent (Java bytecode).</dd>
  <dt>&lt;command name='run'&gt;</dt><dd>says how to run the resulting binary (by using the Java runtime).</dd>
</dl>

<p>The dependencies are:</p>

<dl>
<dt>graphviz and openjdk-jre</dt>
<dd>only used at run-time (since they occur inside the &lt;compile:implementation&gt;).</dd>

<dt>openjdk-jdk</dt>
<dd>only used at compile-time (occurs directly inside the source &lt;implementation&gt;).</dd>

<dt>iris</dt>
<dd>used at compile-time and at run-time (has compile:include-binary attribute).</dd>
</dl>

<p>
  Finally, there is a <b>compile:if-0install-version</b> attribute that you can place on any element in the template.
  0compile will convert this to a plain <b>if-0install-version</b> in the generated output
  (you can't use <b>if-0install-version</b> directly because 0install would strip them out as appropriate for its version before 0compile saw them).
</p>

<h2>Pinning version ranges</h2>

<p>
  Sometimes, you have a build time dependency with a wide range of possible versions, but the generated binary will have a runtime
  dependency on whichever version was used to compile it.
  The <b>compile:pin-components</b> attribute on a <b>&lt;version&gt;</b> element in the template will expand into <b>before</b> and <b>after</b>
  attributes that require the binary version to match the first <i>n</i> components of the version used to compile it. For example, if
  this program is compiled using Python 2.7.3, then the binary will have <b>&lt;version not-before='2.7' before='2.8'/></b>:
</p>

<pre>
&lt;compile:implementation>
  &lt;runner interface='http://repo.roscidus.com/python/python'>
    &lt;version compile:pin-components="2"/>
  &lt;/runner>
&lt;/compile:implementation>
</pre>

<p>(added in 0compile 1.4)</p>


<h2>Tips</h2>

<dl>
<dt>Using a separate source feed</dt>
<dd>
<p>
You can keep the source implementations in a separate file (<b>MyProg-src.xml</b>) and add a feed from
the main feed, e.g.:
</p>

<pre>
  &lt;feed src='http://mysite/interfaces/MyProg<b>-src</b>.xml' <b>arch='*-src'</b>/&gt;
</pre>

<p>
The <b>arch</b> attribute lets the injector know that it doesn't need to fetch this file unless
it's looking for source code.
</p>
</dd>

<dt>Making library headers available (-dev packages)</dt>
<dd>
<p>
See <a href='make-headers.html'>Make-headers</a> for information about publishing library source and -dev packages.
</p>
</dd>

 <dt>Python distutils</dt>
 <dd><p>
 You should use the <b>--build-base</b> option to make distutils build to 0compile's build directory, not under the source code (which is read-only). Unfortunately, this option
 isn't available with the <b>install</b> command, so you have to do the build in two steps. A typical command is:
 </p>
 <pre>cd "$SRCDIR" &amp;&amp;
python setup.py build --build-base="$BUILDDIR/build" &amp;&amp;
cd "$BUILDDIR" &amp;&amp;
python "$SRCDIR"/setup.py install --home="$DISTDIR" --skip-build</pre>
 </dd>
</dl>

<h2>Examples</h2>

<p>There are some <a href='templates.html'>template projects</a> which can be used as a starting point for publishing your own software for various languages and build systems.</p>

<h2>Further reading</h2>

<dl>
 <dt><a href='0compile-scons.html'>Example: SCons</a></dt>
 <dd>This example shows how to compile a simple "Hello world" program using
 the SCons build system. Both the source and SCons are fetched using Zero
 Install.</dd>
</dl>

</html>
