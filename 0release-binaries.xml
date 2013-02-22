<?xml version='1.0' encoding='utf-8'?>
<html lang="en">

<h2>Releases with source and binary packages</h2>

<p>
<a href='0release.html'>0release</a> can be used to create releases of your software from a version control system.
The main page described how to make releases of programs which are architecture-independent (e.g. programs written
in Python) where a single package is produced. This page explains what happens for programs which must be compiled
for different architectures (e.g. C programs).
</p>

<p>
The extended release process looks like this:
</p>

<p>
<img src='UML/release-process-binaries.png' width='515' height='655' alt='The release process with binary packages'/>
</p>

<p>TODO: 0release doesn't currently unit-test the binaries it produces</p>

<!--
<p>
The main complication here is that compiling the binaries may require sending
the release candidate source package to other machines (real or virtual) and
then collecting the resulting packages.
</p>
-->

<p>
After generating an archive and a feed for the source code release candidate (where <b>arch='*-src'</b>), 0release also
compiles a binary for the host system (using <a href='0compile.html'>0compile</a>). It uploads both the source and binary
archive and publishes both in the Zero Install feed.
</p>

<p>
For an example of a simple binary package that works this way, have a look at the
<a href='http://repo.or.cz/w/0release.git?a=blob;f=tests/c-prog.tgz;h=ae1f06864c70f65fdef5a00065fb82eec809d6dc;hb=a7bce06b6494407b2d80124c65f13493e3b44378'>c-prog.tgz</a> package in 0release's tests directory:
</p>

<pre>
$ <b>tar xzf c-prog.tgz</b>
$ <b>mkdir release-c-prog</b>
$ <b>cd release-c-prog</b>
$ <b>0launch http://0install.net/2007/interfaces/0release.xml ../c-prog/c-prog.xml</b>
</pre>

<h2>Compiling on multiple systems</h2>
  
<p>
  To build binaries for multiple architectures, you'll need to create a configuration file listing the available <i>builders</i>.
  0release uses the <a href='http://www.freedesktop.org/wiki/Specifications/basedir-spec'>Base Directory Specification</a> to find
  its configuration files; with the default settings, you need to create the file <b>~/.config/0install.net/0release/builders.conf</b>.
</p>

<p>
  The <b>builders.conf</b> file has a [global] section listing the builders to use, followed by one section for each builder. Each builder can have three commands
  specified: one to start the builder (optional), one the actually do the build, and one to shutdown the builder (optional). Here is an example configuration:
</p>

<pre-scrolled>
[global]
builders = host, freebsd

[builder-host]
build = 0launch http://0install.net/2007/interfaces/0release.xml --build-slave "$@"

[builder-precise32]
build = build-on-vm precise32-build-slave
</pre-scrolled>

<p>
  This defines two builders named "host" and "precise32". "host" simply runs 0release in build-slave mode on the local machine (in fact, you don't need to specify this section
  because it exists by default). The "precise32" builder run a script (see <a href='#vagrant'>below</a>) to bring up a VirtualBox virtual machine, submit the build to it, and then shut it down again.
</p>

<h3>The build command</h3>

<p>
  The build command is called with four arguments:
</p>

<ol>
  <li>The name of the generated XML feed file for the source release candidate.</li>
  <li>The name of the generated source archive.</li>
  <li>The URL of the directory where the release will be hosted eventually.</li>
  <li>The name of the binary feed to be generated.</li>
</ol>

<p>
  The three names are of files in the current directory without the directory part; this simplifies the copying. The build command must do three things:
</p>

<ol>
  <li>Copy the input files (the source feed and archive) to the build system.</li>
  <li>Invoke "0release --build-slave" to do the build.</li>
  <li>Copy the results (the binary feed and archive) back to the local system.</li>
</ol>

<h2 id='vagrant'>Setting up a Vagrant build slave</h2>

<p>
  First, we'll need to create a "box" with the build system. <a href='http://docs.vagrantup.com/'>Create a Vagrantfile</a> for the new box, e.g.
</p>

<pre>
Vagrant::Config.run do |config|
  config.vm.box = "precise32"
  config.vm.box_url = "http://files.vagrantup.com/precise32.box"
end
</pre>

<p>
  The bring the machine up and install the basic build environment. The only
  package required by 0release is 0install itself, but you must also install
  any system packages that are needed by the software to be built (i.e. those
  which can't be installed by 0install automatically):
</p>

<pre>
vagrant up
vagrant ssh -c 'sudo apt-get update &amp;&amp; 
  sudo apt-get install -y zeroinstall-injector build-essential &amp;&amp;
  mkdir -p ~vagrant/.cache/0install.net'
</pre>

<p>
  Now package the VM into a new box and add it.
  You might wish to create a Vagrantfile.pkg to enable a shared 0install cache (see <a href='virtual.html#vagrant'>Sharing / Vagrant</a>).
</p>

<pre>
vagrant package --vagrantfile Vagrantfile.pkg
vagrant box add precise32-build-slave package.box
</pre>

<p>
  Create the <b>build-on-vm</b> script, make it executable, and place it in your $PATH (on the host):
</p>

<pre>
#!/bin/bash
set -eux

if [ -f Vagrantfile ]; then
  vagrant destroy -f &amp;&amp; rm Vagrantfile
fi

vagrant init "$1"
shift
vagrant up
vagrant ssh-config > .ssh-config
ssh -F .ssh-config default \
  'cd /vagrant &amp;&amp; 0launch --not-before 0.10 \
     http://0install.net/2007/interfaces/0release.xml \
     --build-slave "$@"' "$@" &amp;&amp; \
  vagrant destroy -f &amp;&amp; rm Vagrantfile
</pre>

<p>
You can then use it in your <b>builders.conf</b>, as above.
</p>

</html>
