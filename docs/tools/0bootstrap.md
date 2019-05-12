<?xml version='1.0' encoding='utf-8'?>
<html lang="en">

<program name='0bootstrap'
	 author='Thomas Leonard'
	 git='https://github.com/0install/0bootstrap'
	 feed='http://0install.net/tools/0bootstrap.xml'
	 license='GNU General Public License'>

<p>
<b>0bootstrap</b> generates a native package that installs a 0install launcher.
</p>

<p><strong>This program is not ready for use yet.</strong></p>

<p>
Normally, a launcher is created by passing its name (a URI) to the 0desktop
command (or "Add New Program"). This downloads the appropriate feeds, their
icons - if available, and makes a shortcut (creates a launcher, a .desktop file).
</p>

<p>
However, it is sometimes useful to bundle such a launcher and its icon
together in a .deb package (for example), so that it can be installed
on machines that don't have Zero Install available initially -
by using the standard native package installation procedure.
</p>

</program>

<p>
0bootstrap takes the URI of a program and creates a native package;
currently the .deb and .rpm package formats are supported by the tool.
</p>

<p>
Programs launched using these packages are added to the Zero Install cache
and are therefore still <a href='sharing.html'>shared between users</a>,
and will get updates over the web where possible.
</p>

<toc level='h2'/>

<h2>Using a 0bootstrap package</h2>

<p>Open the file with your file manager, or run "<b>gdebi-gtk edit.deb</b>" in a terminal:</p>

<p style='text-align: center'>
  <img width="552" height="432" src="screens/0bootstrap-install.png" alt='Installing a 0bootstrap native package'/>
</p>

<p>The package will require Zero Install in order to install, as seen in the Details view:</p>

<p style='text-align: center'>
  <img width="402" height="232" src="screens/0bootstrap-details.png" alt='Dependency details'/>
</p>

<p>Installing native packages requires authentication.</p>

<p style='text-align: center'>
  <img width="491" height="299" src="screens/0bootstrap-authenticate.png" alt='Authenticate'/>
</p>

<p>The installation will add the program to your menus.</p>

<p style='text-align: center'>
  <img width="509" height="228" src="screens/0bootstrap-finish.png" alt='Installation finished'/>
</p>

<h2>Current status</h2>

<p>
This program is not yet released.
</p>

<h2>Installing 0bootstrap</h2>

<p>
You can download <b>0bootstrap</b> and create a short-cut to it in the usual way:
</p>

<pre>$ 0install add 0bootstrap http://0install.net/tools/0bootstrap.xml</pre>


<p>
Before the program is released, you need to run it from the source repository:
</p>

<pre>$ git clone git://zero-install.git.sourceforge.net/gitroot/zero-install/bootstrap
$ cd bootstrap
$ 0launch --feed 0bootstrap.xml
$ 0install add 0bootstrap 0bootstrap.xml</pre>

<h2>Creating a package for your program</h2>

<p>
Run 0bootstrap, passing in the package format and the name (URI) of the main program.
For example, to create an Ubuntu package for Edit:
</p>

<pre>
$ 0bootstrap --format=deb http://rox.sourceforge.net/2005/interfaces/Edit
</pre>

<p>
The resulting edit.deb package can now be installed on a Ubuntu machine.
</p>

<h2>FAQ</h2>

<dl>
<dt>What about security?</dt>

<dd>Installing a package isn't a great way to make a shortcut. The normal
Zero Install process of dragging a feed link to a trusted installation program
is much better. However, distributions have been very slow to support this.
0bootstrap is an attempt to boot-strap the adoption process. The native package
is required to work with the operating system's package installation tools,
and can be automatically created by a web service given the feed's URI.
</dd>

</dl>
</html>
