<?xml version='1.0' encoding='utf-8'?>
<html lang="en">

<program name='0repo'
	 author='Thomas Leonard'
	 git='https://github.com/0install/0repo'
	 license='GNU Lesser General Public License'>

<p>
<b>0repo</b> provides an easy and reliable way to maintain a repository of 0install software for others to use.
</p>
</program>

<p>
  Figure 1 shows a simple deployment where a single developer provides a set of packages using 0install. The developer
  runs 0repo to create a repository on their local machine and rsyncs the generated files to their static web hosting.
  To make a new release, the developer uses <a href='tools.html'>tools</a> such as <a href='0template.html'>0template</a>
  or <a href='0release.html'>0release</a> to create a new release, which 0repo then adds to the repository.
</p>

<div class='figure'>
<img src='diagrams/0repo.png' width='640' height='159' alt='Single-developer 0repo deployment' style='padding: 1em'/>
<p>Figure 1: Typical single-developer deployment of 0repo</p>
</div>

<p>
  0repo is new and a little experimental, but <a href='support.html'>feedback</a> is welcome! We hope soon to support
  multi-developer deployments, where 0repo runs on a server and accepts contributions from a set of developers (as
  shown in figure 2).
</p>

<div class='figure'>
<img src='diagrams/0repo-multi.png' width='640' height='357' alt='Multi-developer 0repo deployment' style='padding: 1em'/>
<p>Figure 2: Multi-developer deployment</p>
</div>

<h2>Installation</h2>

<p>
To get it:
</p>

<pre-scrolled>
$ 0install add 0repo http://0install.net/tools/0repo.xml
</pre-scrolled>

<p>
  Full instructions can be found in <a href='https://github.com/0install/0repo/blob/master/README.md'>0repo's README</a>.
</p>

</html>
