<?xml version='1.0' encoding='utf-8'?>
<html>

<h2>First Launch</h2>
<p>
  When launching a feed for the first time, 0install will download the
  necessary files for you and store them in <a href='user-guide-cache.html'>the cache</a>.
  Next time, 0install will use these stored files, so you don't need to download the
  program again. From time to time 0install will check for updates and offer to
  download them. For example:
</p>

<pre>
  $ 0launch http://repo.roscidus.com/games/openttd
</pre>

<table class='howitworks'>
 <tr><th colspan='2'>1. Click Download</th></tr>
 <tr>
  <td class='image'><img src='screens/find-libraries.png' alt='' width='400' height='263'/></td>
  <td>
  <p>The downloaded file says that <b>OpenTTD</b> requires various libraries (Freetype2, SDL, libgcc1, etc).
Each library is identified by a web address (URL) in the same way that the main <b>OpenTTD</b> program was.
0install downloads information about them in the same way and selects a compatible set of versions.
The window displays a dialog box showing the program and all required libraries. Click <b>Download</b>.</p>
  </td>
 </tr>

 <tr><th colspan='2'>2. Wait for the download to finish</th></tr>
 <tr>
  <td class='image'><img src='screens/download-foo.png' alt='' width='400' height='336'/></td>
  <td>
  <p>0install now downloads all the required archives (supported formats include tar.gz, tar.bz2, zip, 
rpm and deb). It unpacks each one into its own directory and
checks its contents against the <i>cryptographic digest</i>
given in the (signed) feed file. If the archive has been changed since the feed was signed,
the download will be rejected. If the archive hasn't been tampered with, it is stored in a cache directory
(see <a href='sharing.html'>sharing</a> for more information) in its own
subdirectory, named after the digest. This ensures that no two downloads can
conflict with each other.
  </p>
  </td>
 </tr>
</table>

<quicklinks>
  <link href='user-guide-intro.html' img='tango/go-prev.png'>Go back</link>
  <link href='user-guide-shortcuts.html' img='tango/go-next.png'>Continue</link>
</quicklinks>
 
</html>
