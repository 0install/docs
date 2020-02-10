title: Python API

The API is quite large and can be a little daunting at first. Here is a simple example, showing how to download a feed and import it into the cache (including downloading any missing GPG keys and confirming them with the user):

```python
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
```

The first step is to create a `Config` object. `load_config` creates one from the configuration files in the user's home directory.

0install uses Python's generators to manage parallel processes (e.g. so that multiple downloads can happen in parallel without blocking each other). This is a kind of light-weight alternative to threads: control only passes between functions at the `yield` statements. To create a function that can operate like this, just annotate it with the `@tasks.async` decorator.

In this case the `download_info` function uses a `Fetcher` to start downloading the feed. `download_and_import_feed` returns a `Blocker` for the result. Yielding this suspends the `download_info` function until the download is complete.

When the blocker is done, we call `tasks.check` on it to check whether it was successful (tasks.check will throw an exception if not). If successful, we can get the updated feed from the `IfaceCache`.

### Other interesting starting points

- If you want to download all the feeds needed to run a program, use a `Driver`.
- To load and execute a saved set of selections, load them using a `Selections` object and pass them to `run.execute_selections`.
- To see how the `0install` command is implemented, look in the `zeroinstall.cmd` package.

### Depending on 0install

Just running the example above using e.g. `python example.py` would get the `zeroinstall` library from the system installation of 0install. It's better to create a local feed for your program and give 0install as a dependency. Then you can be sure your program will get a version of the libraries that it is compatible with (the system version may be too old). Here's a sample feed you can use:

```xml
<?xml version="1.0" ?>
<interface xmlns="http://zero-install.sourceforge.net/2004/injector/interface">
  <name>api-example</name>
  <summary>shows how to use the 0install Python API</summary>

  <group>
    <command name="run" path="example.py">
      <runner interface="https://apps.0install.net/python/python.xml">
	<version before="3"/>
      </runner>
    </command>
    <requires interface="http://0install.net/2007/interfaces/ZeroInstall.xml">
      <version not-before="1.0"/>
      <environment insert="" name="PYTHONPATH"/>
    </requires>
    <implementation id="." version="0.1-pre"/>
  </group>
</interface>
```
