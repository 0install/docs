<?xml version='1.0' encoding='utf-8'?>
<html lang="en">

<toc level='h2'/>

<h2>Testing developer versions using Git</h2>

<p>
If you want to work on the code, or test a fix that has just been made,
you'll want to get the latest developer version. We use Git for version
control, so make sure you have that. You'll also need '<a href='http://www.gnu.org/software/gettext/'>gettext-tools</a>' to build
the translations.
</p>

<p>
To install these on Ubuntu, open a terminal emulator and run this command:
</p>

<pre>$ sudo apt-get install git gitk gettext</pre>

<p>(gitk is a largish visualisation tool and is not strictly necessary, but highly recommended)</p>

<p>Click on the <b>SCM</b> link on any program's page to see its Git page
(for example, <a href='https://github.com/0install/0install'>0install.git</a> for 0install itself).
The link for cloning is displayed there; use it like this:</p>

<pre>
$ git clone https://github.com/0install/0install.git
$ cd 0install
</pre>

<p>
The directory contains the latest version, plus a single (hidden) .git
directory with all the git-related bits.
</p>

<p>To see the log:</p>

<pre>
$ git log
</pre>

<p>
This doesn't require network access; your clone has the whole history.
</p>

<p>
To view a visualisation of the history:
</p>

<pre>
$ gitk --all
</pre>

<p>(--all shows the history of all branches and tags, not just the main trunk)</p>

<h2>Fetching updates</h2>

<p>To download the latest updates into your copy:</p>

<pre>
$ git pull --rebase
</pre>

<p>
(The --rebase option says that if you've committed some changes locally, they should be
reapplied on top of the latest version. Otherwise, it would create a merge commit, which
is usually not what you want.)
</p>

<p>
You can also pull from other places. If someone posts to the mailing list,
they will tell you where to pull from to try the feature out. If they send
a patch, you can apply it with:
</p>

<pre>$ git am the.patch</pre>

<h2>Understanding the code</h2>

<p>
  Most modules have two files - a <tt>.ml</tt> file containing the implementation and a <tt>.mli</tt>
  file describing the module's public interface. You should always start by reading the <tt>.mli</tt>
  file. <a href='https://github.com/0install/0install/blob/master/ocaml/zeroinstall/sigs.mli'>sigs.mli</a> describes several abstract interfaces used in the code.
</p>

<p>
  <a href='http://roscidus.com/blog/blog/archives/'>Thomas Leonard's blog</a>
  has many blog posts describing various aspects of 0install.
  For example, <a href='http://roscidus.com/blog/blog/2014/09/17/simplifying-the-solver-with-functors/'>Simplifying the Solver With Functors</a> explains how 0install chooses a compatible set of libraries to run a program, while <a href='http://roscidus.com/blog/blog/2013/11/28/asynchronous-python-vs-ocaml/'>Asynchronous Python vs OCaml</a> describes the code for downloading things.
</p>

<h2>Making patches</h2>

<p>
If you've changed the code in some way then you can commit the changes like
this (this just stores them on your own computer, in the .git sub-directory).
</p>

<pre>$ git commit -a</pre>

<p>Enter a log message. The first line should be a short summary (like the
subject of an email). Then leave a blank line, then write a longer description.
</p>

<p>To view your patch after committing:</p>

<pre>$ git show</pre>

<p>If you realised you made a mistake, correct it and then do:</p>

<pre>$ git commit -a --amend</pre>

<p>
Finally, to make a patch file ready to send to the <a href='support.html'>mailing list</a>:
</p>

<pre>$ git format-patch origin/master</pre>

<h2>Making a new translation</h2>

<p>
  Note: translations are not currently working - see <a href='http://stackoverflow.com/questions/26192129/gettext-support-in-ocaml'>Gettext support in OCaml</a>.
</p>

<p>Note: if you prefer, you can also use the <a href='https://www.transifex.net/projects/p/0install/'>Transifex web interface</a> to work on translations.
</p>

<p>The steps are:</p>

<ol>
  <li>Create the .pot (.po template) file.</li>
  <li>Create a new directory <b>share/locale/<i>$locale</i>/LC_MESSAGES</b> inside the Git checkout.</li>
  <li>Copy the .pot file inside it with a .po extension.</li>
</ol>

<p>
  e.g. to make a French translation:
</p>

<pre>
  $ make share/locale/zero-install.pot
  $ mkdir -p share/locale/<b>fr</b>/LC_MESSAGES
  $ cp share/locale/zero-install.pot share/locale/<b>fr</b>/LC_MESSAGES/zero-install.po
</pre>

<p>
  Then edit the .po file to give a translation for each string. When you're done, create the .mo
  file from the .po file and test:
</p>

<pre>
  $ make translations
  $ ./0launch
</pre>

<p>
  Finally, <a href='support.html'>send us</a> the new .po file.
</p>

</html>
