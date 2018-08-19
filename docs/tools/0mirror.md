**Name:** 0mirror  
**Maintainer:** Thomas Leonard  
**License:** GNU General Public License  
**Source:** [Git repository](http://repo.or.cz/w/0mirror.git)

With Zero Install, each software author publishes a feed on their web-site giving details about their software. Users can use these feeds to download the software and to receive notification of new versions. However, the author's site may become unavailable, either temporarily (e.g. due to network problems) or permanently (e.g. if the software is abandoned). Therefore, mirror sites are used to keep an up-to-date copy of the original feeds.

Mirror sites can be public (such as the [roscidus.com mirror](http://roscidus.com/0mirror/)), or internal to your organisation.

0mirror takes a list of feed URLs and exports each one from your local Zero Install cache ready for publishing on a web-server (such as Apache). The published site contains:

- All known versions of the feed XML, with a symlink pointing to the most recent.
- All GPG keys used to sign the feeds.
- An Atom feed with news about the most recent changes.

Note that it does not export the actual programs themselves, although that would certainly be useful for sites with plenty of bandwidth (which we don't have currently; sorry!).

## Status

0mirror is not yet officially released, although the GIT version works well enough to create the roscidus.com mirror. 0launch 0.33 has built-in support for using the mirror service. On older versions, the GPG keys and feeds can be added manually (e.g. using `0launch --import feed.xml`).
