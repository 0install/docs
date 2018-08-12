<?xml version='1.0' encoding='utf-8'?>
<html>

<h2>About 0install</h2>

<p>
Zero Install is a decentralised cross-platform software
installation system available under the LGPL. It allows software
developers to publish programs directly from their own web-sites,
while supporting features familiar from centralised distribution
repositories such as shared libraries, automatic updates and digital
signatures. It is intended to complement, rather than replace, the
operating system's package management. 0install packages never
interfere with those provided by the distribution.
</p>
<p style='float: right; padding-left: 1em;'>
  <a href='injector-feeds.html'><img width="263" height="364" src="screens/manage-apps-narrow.png" alt='0desktop --manage'/></a>
</p>
<p>
0install does not define a new packaging format; unmodified tarballs
or zip archives can be used. Instead, it defines an XML metadata
format to describe these packages and the dependencies between them. A
single metadata file can be used on multiple platforms (e.g. Ubuntu,
Debian, Fedora, openSUSE, Mac OS X and Windows), assuming binary or
source archives are available that work on those systems.
</p><p>
0install also has some interesting features not often found in
traditional package managers. For example, while it will share
libraries whenever possible, it can always install multiple versions
of a package in parallel when there are conflicting requirements.
Installation is always side-effect-free (each package is unpacked to
its own directory and will not touch shared directories such as
/usr/bin), making it ideal for use with sandboxing technologies and
virtualisation.
</p><p>
The XML file describing the program's requirements can also be
included in a source-code repository, allowing full dependency
handling for unreleased developer versions. For example, a user can
clone a Git repository and build and test the program, automatically
downloading newer versions of libraries where necessary, without
interfering with the versions of those libraries installed by their
distribution, which continue to be used for other software.
</p><p>
Started in 2003, 0install is developed by volunteers from around the world;
contributors include Aleksey Lim, Anders F Björklund, Bastian Eicher, Chris
Leick, Daniel Tschan, Dave Abrahams, Frank Richter, Mark Seaborn, Michel
Alexandre Salim, Pino Toscano, Rene Lopez, Thomas Leonard, Tim Cuthbertson and
Tim Diels.
</p><p>
More than one thousand packages are currently available and you can easily
<a href='packagers.html'>publish your own programs</a>. Zero Install itself
is available from the official repositories of most Linux distributions
(including Arch, Debian, Fedora, Gentoo, Mint, openSUSE and Ubuntu).
</p>

<h3>Why 0install?</h3>

<p>
  Click one of the links below to find out why you may want to use 0install.
</p>

<dl>
    <dt><a href='perspectives.html'>By perspective</a></dt><dd>How 0install benefits users, administrators, developers and distributions.</dd>
    <dt><a href='comparison.html'>Compared to other systems</a></dt><dd>Why you may prefer 0install over another project.</dd>
    <dt><a href='features.html'>Cool features</a></dt><dd>Some key features: native packager integration, sharing and security.</dd>
    <dt><a href='why-not.html'>Why not?</a></dt><dd>Feedback collected from projects not using 0install.</dd>
</dl>

<h3>More documentation</h3>

<ul>
  <li><a href='doc.html'>Tutorials, guides and reference material</a></li>
  <li><a href='faq.html'>Frequently Asked Questions</a></li>
</ul>

</html>
