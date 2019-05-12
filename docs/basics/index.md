<?xml version='1.0' encoding='utf-8'?>
<html>

<h2>Introduction</h2>
<p>
  A 0install application is distributed using a <i>feed file</i>. A feed is an XML
  file that tells 0install how to run the application, and which versions are
  available. The feed file is normally published on the web; the URL of the
  feed is the unique identifier of the application.
</p>
<p>
  For example, OpenTTD is available via 0install, it has 
  <a href='http://repo.roscidus.com/games/openttd'>this
  feed file</a> and it is uniquely identified as
  <a href='http://repo.roscidus.com/games/openttd'>
  http://repo.roscidus.com/games/openttd</a>.
</p>
<p>
  To get a 0install application:
  <ol>
    <li>
      <p>
        Find a program you want to run on the web:
      </p>
      <p>
        <img src='screens/find-url.png' alt='' width='400' height='314'/>
      </p>
    </li>
    <li>
      <p>
        Either launch it directly:
      </p>
      <pre>$ <b>0launch http://repo.roscidus.com/games/openttd</b></pre>
      <p>
        Or <a href='user-guide-shortcuts.html'>generate a shortcut</a> to launch it later:
      </p>
      <p>
        <img src='screens/add-url.png' alt='' width='400' height='364'/>
      </p>
    </li>
  </ol>
</p>
<p>
  Upon first launch of a feed, 0install may ask you to accept keys and be
  allowed to download files.
</p>

<quicklinks>
  <link href='user-guide-first-launch.html' img='tango/go-next.png'>Continue</link>
</quicklinks>

</html>
