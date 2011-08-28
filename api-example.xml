<?xml version='1.0' encoding='utf-8'?>
<html>

<h2>Using the Python API: a simple example</h2>

<p>
  The API is quite large and can be a little daunting at first. Here is a simple example, showing how
  to download a feed and import it into the cache (including downloading any missing GPG keys and confirming
  them with the user):
</p>

<pre>
from zeroinstall.injector.config import load_config
from zeroinstall.support import tasks

config = load_config()

@tasks.async
def download_info(feed_url):
	print "Downloading", feed_url

	feed_download = config.fetcher.download_and_import_feed(feed_url)
	yield feed_download
	tasks.check(feed_download)

	print "Download complete"

	feed = config.iface_cache.get_feed(feed_url)
	print "Name:", feed.name
	print "Summary:", feed.summary

url = "http://rox.sourceforge.net/2005/interfaces/ROX-Filer"
tasks.wait_for_blocker(download_info(url))
</pre>

<p>
  The first step is to create a <a href='python-api/html/zeroinstall.injector.config.Config-class.html'>Config</a> object. <b>load_config</b> creates one from the configuration files in the user's home directory.
</p>

<p>
  0install uses Python's generators to manage parallel processes (e.g. so that multiple downloads can happen
  in parallel without blocking each other). This is a kind of light-weight alternative to threads: control
  only passes between functions at the <b>yield</b> statements. To create a function that can operate like
  this, just annotate it with the <b>@tasks.async</b> decorator.
</p>

<p>
  In this case the <b>download_info</b> function uses a <a href='python-api/html/zeroinstall.injector.fetch.Fetcher-class.html'>Fetcher</a> to start downloading the feed. <b>download_and_import_feed</b> returns a <a href='python-api/html/zeroinstall.support.tasks.Blocker-class.html'>Blocker</a> for the result. Yielding this suspends the <b>download_info</b> function until the download is complete.
</p>

<p>
  When the blocker is done, we call <a href='python-api/html/zeroinstall.support.tasks-module.html#check'>tasks.check</a> on it to check whether it was successful (tasks.check will throw an exception if not). If successful, we can get the updated feed from the <a href='python-api/html/zeroinstall.injector.iface_cache.IfaceCache-class.html'>IfaceCache</a>.
</p>

<h3>Other interesting starting points</h3>

<ul>
  <li>If you want to download all the feeds needed to run a program, use a <a href='python-api/html/zeroinstall.injector.driver.Driver-class.html'>Driver</a>.</li>
  <li>To load and execute a saved set of selections, load them using a <a href='python-api/html/zeroinstall.injector.selections.Selections-class.html'>Selections</a> object and pass them to <a href='python-api/html/zeroinstall.injector.run-module.html'>run.execute_selections</a>.</li>
  <li>To see how the <b>0install</b> command is implemented, look in the <a href='python-api/html/zeroinstall.cmd-module.html'>zeroinstall.cmd</a> package.</li>
</ul>

<h3>Depending on 0install</h3>

<p>
  Just running the example above using e.g. "python example.py" would get the "zeroinstall" library from the system installation of 0install. It's better to create a local feed for your program and give 0install as a dependency. Then you can be sure your program will get a version of the libraries that it is compatible with (the system version may be too old). Here's a sample feed you can use:
</p>

<pre>
&lt;?xml version="1.0" ?>
&lt;interface xmlns="http://zero-install.sourceforge.net/2004/injector/interface">
  &lt;name>api-example&lt;/name>
  &lt;summary>shows how to use the 0install Python API&lt;/summary>

  &lt;group>
    &lt;command name="run" path="<b>example.py</b>">
      &lt;runner interface="http://repo.roscidus.com/python/python">
	&lt;version before="3"/>
      &lt;/runner>
    &lt;/command>
    &lt;requires interface="http://0install.net/2007/interfaces/ZeroInstall.xml">
      &lt;version not-before="1.0"/>
      &lt;environment insert="" name="PYTHONPATH"/>
    &lt;/requires>
    &lt;implementation id="." version="0.1-pre"/>
  &lt;/group>
&lt;/interface>
</pre>

<h3>The full API docs</h3>

<ul>
  <li><a href='python-api/html/index.html'>zeroinstall API docs</a></li>
</ul>

</html>
