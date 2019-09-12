**Name:** 0watch  
**Maintainer:** Bastian Eicher  
**License:** GNU Lesser General Public License  
**Source:** [Git repository](https://github.com/0install/0watch)  
**Zero Install feed:** <http://0install.de/feeds/0watch.xml>

0watch scans websites for new releases using arbitrary Python code snippets.
When new releases are detected [0template](0template.md) is used to create/update a Zero Install feed.

To make the `0watch` command available on your command-line you can run:

```shell
$ 0install add 0watch http://0install.de/feeds/0watch.xml
```

To use 0watch you need both a template file named like `MyApp.xml.template` and watch file named like `MyApp.watch.py` in the same directory. You can then run:

```shell
$ 0watch MyApp.watch.py
```

## Details

A watch file is a Python script that pulls a list of releases from a website. It must set an attribute named `releases` to an array of dictionaries. Each array element represents to a single release and each dictionary tuple is a variable substitution for the template.

A basic watch file could look like this:

```python
from urllib import request
import json
data = request.urlopen(request.Request('https://api.github.com/repos/myproj/myapp/releases')).read()
releases = [{'version': release['tag_name'], 'released': release['published_at'][0:10]} for release in json.loads(data)]
```

For each release reported by the watch file 0watch attempts to determine whether the version is already known. It does this by:

 * checking if a file named `MyApp-VERSION.xml` exists in the same directory and
 * checking if a file named `MyApp.xml` exists in the same directory and contains an implementation with the version.

0watch then calls 0template once for each new release.
