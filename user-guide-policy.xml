<?xml version='1.0' encoding='utf-8'?>
<html>

<h2>Policy Settings</h2>

<p>
  You can change policy settings to affect when 0install looks for updates and
  which versions it prefers.
</p>

<p>
  The first part shows how to set policy settings that apply to all
  applications of the current user. The last section shows how to change
  per-application settings. Policy affects which versions 0install chooses (do
  you want test versions, ...).
</p>

<toc level="h3"/>

<h3>General policy settings</h3>

<p>
  You can change the policy settings using the Preferences dialog. To open it:
</p>
<pre>$ <b>0install config</b></pre>

<p>
  If you use the GNOME/KDE menu: choose <b>Zero Install -> Manage
    Applications</b> from the <b>Applications</b> menu, click on the
  edit properties icon next to an application and click Preferences:
</p>
<p style='text-align: center'>
  <img width="457" height="364" src="screens/manage-apps.png" alt='0desktop --manage'/>
</p>

<p>
You can affect which versions are chosen by changing your policy. Three aspects
of your policy are shown in the Preferences window: <b>Network use</b>, <b>Freshness</b>
and <b>Help test new versions</b>:
</p>

<p style='text-align: center'>
  <img width="623" height="478" src="screens/preferences.png" alt='The Preferences dialog'/>
</p>


<dl>
  <dt>Network use</dt>
  <dd>
    Affects how much 0install will rely on the network. Possible values are:

    <dl>
      <dt>Full</dt>
      <dd>Normal network use.</dd>

      <dt>Minimal</dt>
      <dd>0install will prefer cached versions over non-cached ones.</dd>

      <dt>Off-line</dt>
      <dd>0install will not use the network.</dd>
    </dl>
  </dd>
      
  <dt>Freshness</dt>
  <dd>
    <p>
    0install caches its feeds as well. It checks for updates to the feeds from
    time to time. The freshness indicates how old a feed may get before
    0install automatically checks for updates to it. Note that 0install only checks for updates
    when you actually run a program; so if you never run something, it won't waste time checking
    for updates.
    </p>
    <p>
    If you notice a feed is out of date, you can force 0install to look for updates by clicking the <b>Refresh all now</b> button
    </p>
  </dd>

  <dt>Help test new versions</dt>
  <dd>
    By default, 0install tries not to select new versions while they're still in the "testing" phase.
    If checked, 0install will instead always select the newest version, even if it's marked as
    "testing".
  </dd>
</dl>

<p>Note that all changes to your policy are saved as soon as you make them.
Clicking on <b>Cancel</b> will close the window without running the program,
but any changes made to the policy are not reversed.
</p>

<h3>Per-application policy settings</h3>
<p>
  You can change per-application policy settings in the application information
  dialog. There are multiple ways to opening this dialog:
</p>

<ol>
  <li>
    <ul>
      <li>
        <p>Run 0launch with the --gui option and the URI of the application</p>
        <pre>$ <b>0launch --gui http://rox.sourceforge.net/2005/interfaces/Edit</b></pre>
      </li>
      <li>
	<p>Run "0install update" with a shortcut you made as first argument</p>
        <pre>$ <b>0install update --gui rox-edit</b></pre>
      </li>
      <li>
        <p>
          If you use the GNOME/KDE menu: choose <b>Zero Install -> Manage
            Applications</b> from the <b>Applications</b> menu, click on the
          edit properties icon next to the application:
        </p>
        <p style='text-align: center'>
          <img width="457" height="364" src="screens/manage-apps.png" alt='0desktop --manage'/>
        </p>
      </li>
    </ul>
  </li>
  <li>
    <p>
      Double-click the application in the list.  For example, double-clicking on
      <b>Edit</b> displays this dialog box:
    </p>
    <p style='text-align: center'>
      <img width="562" height="397" src="screens/edit-properties.png"
        alt="Properties of the Edit interface" />
    </p>
  </li>
</ol>

<p>
  In the Feeds tab, a list of feeds shows all the places where Zero Install
  looks for versions of Edit.  By default, there is just one feed, whose URL is
  simply Edit's URI; you can view it in a web browser if you're interested:
  <a href="http://rox.sourceforge.net/2005/interfaces/Edit">Edit's default
    feed</a>. This is an XML file with a GPG signature at the end. The
  downloaded feed files are stored locally in
  <b>~/.cache/0install.net/interfaces</b>.
</p>

<p>
The Versions tab shows all the versions found in all of the feeds:
</p>

<p style='text-align: center'>
  <img width="562" height="397" src="screens/edit-versions.png"
  	alt="Versions of the Edit" />
</p>

<p>
You can use the <b>Preferred Stability</b> setting in the interface dialog to choose which
versions to prefer. You can also change the stability rating of any implementation by clicking on it
and choosing a new rating from the popup menu. User-set ratings are shown in capitals.
</p>

<p>
As you make changes to the policy and ratings, the selected implementation
will change. The version shown in bold (or at the top of the list, in older versions) is
the one that will actually be used. In addition to the ratings below, you can
set the rating to <b>Preferred</b>. Such versions are always preferred above
other versions, unless they're not cached and you are in Off-line mode.
</p>

<p>
  The following stability ratings are allowed:
</p>

<ul>
  <li>Stable (this is the default if <b>Help test new versions</b> is unchecked)</li>
  <li>Testing (this is the default if <b>Help test new versions</b> is checked)</li>
  <li>Developer</li>
  <li>Buggy</li>
  <li>Insecure</li>
</ul>

<p>
  Stability ratings are kept independently of the implementations, and are expected to change over
  time. When any new release is made, its stability is initially set to <b>Testing</b>. If you
  have selected <b>Help test new versions</b> in the Preferences dialog box then you will then start using it.
  Otherwise, you will continue with the previous stable release. After a while
  (days, weeks or months, depending on the project) with no serious problems
  found, the author will change the implementation's stability to <b>Stable</b> so that
  everyone will use it.
</p>

<p>
  If problems are found, it will instead be marked as <b>Buggy</b>, or <b>Insecure</b>. Neither
  will be selected by default, but it is useful to see the reason (you might opt to
  continue using a buggy version if it works for you, but should never use an insecure
  one). <b>Developer</b> is like a more extreme version of <b>Testing</b>, where the program is
  expected to have bugs.
</p>

<p>
  Note: If you want to use the second item on the list because the first is
  buggy, for example, then it is better to mark the first version as buggy than to mark the
  second as preferred. This is because when a new version is available, you will want that to
  become the version at the top of the list, whereas a preferred version will always be first.
</p>

<quicklinks>
  <link href='user-guide-apps.html' img='tango/go-prev.png'>Go back</link>
  <link href='user-guide-cache.html' img='tango/go-next.png'>Continue</link>
</quicklinks>
 
</html>
