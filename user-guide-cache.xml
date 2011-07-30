<?xml version='1.0' encoding='utf-8'?>
<html lang="en">

<h2>The Cache</h2>

<p>
  Everything 0install downloads from the net by default goes in <b>~/.cache/0install.net/</b>.
  Every application/library unpacks into a directory in that cache. So,
  there's one directory for ROX-Filer, and another for ClanBomber, etc. In fact,
  there's one directory for every version of ROX-Filer, in case you want more than one
  available. Every directory is uniquely named, so you'll never get conflicts
  when trying to install two different programs.
</p>

<p>
  The idea is that you don't need to backup <b>~/.cache</b>, because you can
  always download the stuff again. For example, if you delete the whole
  <b>~/.cache/0install.net/</b> directory and then click on ROX-Filer, it will
  just prompt you to download it again. The cache is just to make things faster
  (and work when offline), but you don't really need to worry about it.
</p>

<toc level="h3"/>

<h3>Sharing Implementations</h3>

<h4>Between users of the same system</h4>

<p>
  0install can be configured to store its cache in <b>/var/cache/0install.net/</b>.
  This allows sharing between users. The use of cryptographic digests makes
  this safe; users don't need to trust each other not to put malicious code in
  the shared cache.
</p>

<ul>
  <li>See: <a href='sharing.html'>Enabling sharing between users</a></li>
</ul>


<h4>Between virtual machines</h4>

<p>
  You can also share the cache between virtual machines:
</p>

<ul>
  <li>See: <a href="virtual.html">Enabling sharing between virtual machines</a></li>
</ul>

<h4>Between machines using P2P</h4>

<p>
  Note: this is still experimental
</p>

<p>
  Using <a href='0share.html'>0share</a> you can locally distribute your implementations (versions of
  programs) via a peer-to-peer protocol.
</p>

<h3>Removing Implementations</h3>

<p>
  If for some reason you would like to remove implementations from the
  cache (it does not make your system any 'cleaner', but it does free some
  disk space), you can do so using the Zero Install Cache dialog.
</p>

<p>
  Click on the <b>Show Cache</b> button in the <b>Manage Programs</b> box to
  get the cache explorer (or run "<b>0store manage</b>"). Select the versions
  you don't need anymore and click on <b>Delete</b>.
</p>

<p style='text-align: center'>
  <img width="812" height="334" src="injector-cache.png"
  	alt='Uninstalling programs'/>
</p>

<p>
  Note: you can delete the entire cache, 0install will redownload whatever it needs later.
</p>


<quicklinks>
  <link href='user-guide-policy.html' img='tango/go-prev.png'>Go back</link>
  <link href='servers.html' img='tango/go-next.png'>Continue</link>
</quicklinks>
 
</html>
