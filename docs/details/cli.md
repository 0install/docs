title: Command-line interface

Zero Install command-lines begin with `0install`.  
On Windows you can also use <code>0install-win</code> instead. This will display outputs using windows instead of the console.

<table>
<tr>
  <th>Command</th>
  <th>Description</th>
</tr>
<tr>
  <td><a href='#select'><nobr><code>select</code></nobr></a></td>
  <td>Selects a version of the program identified by URI, and compatible versions of all of its dependencies.
<br/>Returns an exit status of <code>0</code> if it selected a set of versions, and a status of <code>1</code> if it could not find a consistent set.</td>
</tr>
<tr>
  <td><a href='#download'><nobr><code>download</code></nobr></a></td>
  <td>Behaves similarly to <code>0install select</code>, except that it also downloads the selected versions if they are not already cached.
<br/>Returns an exit status of <code>0</code> if it selected a suitable set of versions and they are now all downloaded and in the cache; returns a status of <code>1</code> otherwise.</td>
</tr>
<tr>
  <td><a href='#update'><nobr><code>update</code></nobr></a></td>
  <td>Checks for updates to the program and download them if found.
<br/>This is similar to <code>0install download --refresh</code>, except that it prints information about whether any changes were found.</td>
</tr>
<tr>
  <td><a href='#run'><nobr><code>run</code></nobr></a></td>
  <td>Behaves similarly to <code>0install download</code>, except that it also runs the program after ensuring it is in the cache.
<br/>Returns an exit status of <code>1</code> if the download step failed. Otherwise, the exit status will be the exit status of the program being run.</td>
</tr>
<tr>
  <td><a href='#import'><nobr><code>import</code></nobr></a></td>
  <td>Imports a feed from a local file, as if it had been downloaded from the network. This is useful when testing a feed file, to avoid uploading it to a remote server in order to download it again.
<br/>The file must have a trusted digital signature, as when fetching from the network.</td>
</tr>
<tr>
  <td><a href='#export'><nobr><code>export</code></nobr></a><br>(Windows only)</td>
  <td>Exports all feeds and implementations required to launch the program specified by a <code>URI</code> to a <code>DIRECTORY</code>.</td>
</tr>
<tr>
  <td><a href='#search'><nobr><code>search</code></nobr></a></td>
  <td>Searches for feeds indexed by the mirror server that match specified keywords. Note that the default mirror server indexes all known feeds, regardless of quality; you must decide whether to trust the programs before running them.</td>
</tr>
<tr>
  <td><a href='#list'><nobr><code>list</code></nobr></a></td>
  <td>Lists all locally known feed URIs. If a search term is given, only URIs containing that string are shown (case insensitive).</td>
</tr>
<tr>
  <td><a href='#catalog_search'><nobr><code>catalog search</code></nobr></a><br>(Windows only)</td>
  <td>Searches registered catalogs for applications that match the specified query. If no query is given all applications in the catalogs are listed.</td>
</tr>
<tr>
  <td><a href='#catalog_refresh'><nobr><code>catalog refresh</code></nobr></a><br>(Windows only)</td>
  <td>Refreshes (downloads) all registered catalogs.</td>
</tr>
<tr>
  <td><a href='#catalog_add'><nobr><code>catalog add</code></nobr></a><br>(Windows only)</td>
  <td>Adds an URI to the list of catalogs.</td>
</tr>
<tr>
  <td><a href='#catalog_remove'><nobr><code>catalog remove</code></nobr></a><br>(Windows only)</td>
  <td>Removes an URI from the list of catalogs.</td>
</tr>
<tr>
  <td><a href='#catalog_reset'><nobr><code>catalog reset</code></nobr></a><br>(Windows only)</td>
  <td>Resets the list of catalogs to the default source.</td>
</tr>
<tr>
  <td><a href='#catalog_list'><nobr><code>catalog list</code></nobr></a><br>(Windows only)</td>
  <td>Lists all registered catalogs.</td>
</tr>
<tr>
  <td><a href='#config'><nobr><code>config</code></nobr></a></td>
  <td>View or change configuration settings.
<br/>With no arguments, it displays all settings and their current values. With one argument, it displays the current value of the named setting. With two arguments, it sets the setting to the given value or resets it to the default value if the value <code>default</code> is given.</td>
</tr>
<tr>
  <td><a href='#add-feed'><nobr><code>add-feed</code></nobr></a></td>
  <td>Register an additional source of implementations (versions) of a program.</td>
</tr>
<tr>
  <td><a href='#remove-feed'><nobr><code>remove-feed</code></nobr></a></td>
  <td>Un-registers a feed, reversing the effect of <code>0install add-feed</code>.</td>
</tr>
<tr>
  <td><a href='#list-feeds'><nobr><code>list-feeds</code></nobr></a></td>
  <td>Lists all extra feeds added to URI using <code>0install add-feed</code>.</td>
</tr>
<tr>
  <td><a href='#digest'><nobr><code>digest</code></nobr></a></td>
  <td>Calculates the manifest digest of a directory or archive.</td>
</tr>
<tr>
  <td><a href='#store_add'><nobr><code>store add</code></nobr></a></td>
  <td>Adds the contents of a directory or archive to the cache.</td>
</tr>
<tr>
  <td><a href='#store_audit'><nobr><code>store audit</code></nobr></a></td>
  <td>Checks that all implementations in the cache are undamaged.
<br/>Additional arguments specify custom cache locations.</td>
</tr>
<tr>
  <td><a href='#store_copy'><nobr><code>store copy</code></nobr></a></td>
  <td>Copies an implementation into the cache. Similar to <code>0install store add</code>, but the digest is extracted from the directory name.
<br/>An additional arguments specifies a custom target cache location.</td>
</tr>
<tr>
  <td><a href='#store_export'><nobr><code>store export</code></nobr></a><br>(Windows only)</td>
  <td>Exports a cached implementation as an archive (ZIP, TAR, etc.). The result can be imported on another machine using <code>0install store add</code>.</td>
</tr>
<tr>
  <td><a href='#store_find'><nobr><code>store find</code></nobr></a></td>
  <td>Determines the local path of a cached implementation.</td>
</tr>
<tr>
  <td><a href='#store_list'><nobr><code>store list</code></nobr></a></td>
  <td>Lists all implementation cache directories.</td>
</tr>
<tr>
  <td><a href='#store_list-implementations'><nobr><code>store list-implementations</code></nobr></a><br>(Windows only)</td>
  <td>Lists all cached implementations. If a feed URI is specified only implementations for that particular feed are listed.</td>
</tr>
<tr>
  <td><a href='#store_manage'><nobr><code>store manage</code></nobr></a></td>
  <td>Displays a graphical user interface for managing implementations in the cache. Shows associations with cached feeds.</td>
</tr>
<tr>
  <td><a href='#store_optimise'><nobr><code>store optimise</code></nobr></a></td>
  <td>Saves disk space by merging identical files with hardlinks.
<br/>Additional arguments specify custom cache locations.</td>
</tr>
<tr>
  <td><a href='#store_purge'><nobr><code>store purge</code></nobr></a><br>(Windows only)</td>
  <td>Removes all implementations from the cache. Use this command to clean up the system before removing Zero Install. Deleting cache directories manually may be difficult due to the NTFS ACLs employed to protect implementations against modification.
<br/>Additional arguments specify custom cache locations.</td>
</tr>
<tr>
  <td><a href='#store_remove'><nobr><code>store remove</code></nobr></a></td>
  <td>Removes an implementation from the cache.</td>
</tr>
<tr>
  <td><a href='#store_verify'><nobr><code>store verify</code></nobr></a></td>
  <td>Makes sure an implementation has not been damaged (i.e. it manifest digest has not changed).</td>
</tr>
<tr>
  <td><a href='#store_add-dir'><nobr><code>store add-dir</code></nobr></a><br>(Windows only)</td>
  <td>Adds a directory to the list of custom implementation caches.</td>
</tr>
<tr>
  <td><a href='#store_remove-dir'><nobr><code>store remove-dir</code></nobr></a><br>(Windows only)</td>
  <td>Removes a directory from the list of custom implementation caches.</td>
</tr>
<tr>
  <td><a href='#central'><nobr><code>central</code></nobr></a><br>(Windows only)</td>
  <td>Opens the central graphical user interface for launching and managing applications.</td>
</tr>
<tr>
  <td><a href='#add'><nobr><code>add</code></nobr></a></td>
  <td>Add an application to the application list.</td>
</tr>
<tr>
  <td><a href='#remove'><nobr><code>remove</code></nobr></a><br>(Windows only)</td>
  <td>Removes an application from the application list and undoes any desktop environment integration.</td>
</tr>
<tr>
  <td><a href='#remove-all'><nobr><code>remove-all</code></nobr></a><br>(Windows only)</td>
  <td>Removes all applications from the application list and undoes any desktop environment integration. Use this command to clean up the system before removing Zero Install.</td>
</tr>
<tr>
  <td><nobr><code>destroy</code></nobr><br>(Linux only)</td>
  <td>Removes an application.</td>
</tr>
<tr>
  <td><nobr><code>show</code></nobr><br>(Linux only)</td>
  <td>Shows the current selections for an application</td>
</tr>
<tr>
  <td><nobr><code>whatchanged</code></nobr><br>(Linux only)</td>
  <td>Shows the differences between the current and previous selections for an application.</td>
</tr>
<tr>
  <td><nobr><code>man</code></nobr><br>(Linux only)</td>
  <td>Shows the man-page of a given command.</td>
</tr>
<tr>
  <td><a href='#integrate'><nobr><code>integrate</code></nobr></a><br>(Windows only)</td>
  <td>Adds an application to the application list (if missing) and integrate it into the desktop environment.</td>
</tr>
<tr>
  <td><a href='#alias'><nobr><code>alias</code></nobr></a><br>(Windows only)</td>
  <td>Create an alias for launching an application via 0install without always having to enter the full URI.</td>
</tr>
<tr>
  <td><a href='#list-apps'><nobr><code>list-apps</code></nobr></a><br>(Windows only)</td>
  <td>Lists all applications currently in your application list. If a search term is given, only application names containing that string are shown (case insensitive).</td>
</tr>
<tr>
  <td><a href='#update-all'><nobr><code>update-all</code></nobr></a><br>(Windows only)</td>
  <td>Updates all applications in the application list.</td>
</tr>
<tr>
  <td><a href='#repair-all'><nobr><code>repair-all</code></nobr></a><br>(Windows only)</td>
  <td>Reapplies all desktop integrations of applications in the application list.</td>
</tr>
<tr>
  <td><a href='#sync'><nobr><code>sync</code></nobr></a><br>(Windows only)</td>
  <td>Synchronizes the application list with the server.</td>
</tr>
<tr>
  <td><a href='#import-apps'><nobr><code>import-apps</code></nobr></a><br>(Windows only)</td>
  <td>Imports a set of applications and desktop integrations from an existing app-list.xml file.</td>
</tr>
<tr>
  <td><a href='#self_deploy'><nobr><code>self deploy</code></nobr></a><br>(Windows only)</td>
  <td>Deploys Zero Install to the specified <code>TARGET</code> directory or the default directory for programs and integrates it in the system.</td>
</tr>
<tr>
  <td><a href='#self_remove'><nobr><code>self remove</code></nobr></a><br>(Windows only)</td>
  <td>Removes the current instance of Zero Install from the system.</td>
</tr>
<tr>
  <td><a href='#self_update'><nobr><code>self update</code></nobr></a><br>(Windows only)</td>
  <td>Updates Zero Install itself to the most recent version.</td>
</tr>
</table>
<a name='select'></a><h1>select</h1>
<p>Selects a version of the program identified by URI, and compatible versions of all of its dependencies.
<br/>Returns an exit status of <code>0</code> if it selected a set of versions, and a status of <code>1</code> if it could not find a consistent set.</p>
<p><b>Usage:</b> <code>0install select [OPTIONS] URI</code></p>
<table>
<tr>
  <th>Option</th>
  <th>Description</th>
</tr>
<tr>
  <td><nobr><code>-?</code></nobr><br/><nobr><code>-h</code></nobr><br/><nobr><code>--help</code></nobr></td>
  <td>Show the built-in help text.</td>
</tr>
<tr>
  <td><nobr><code>--background</code></nobr><br>(Windows only)</td>
  <td>Hide the graphical user interface and use something like a tray icon instead. Has no effect in command-line mode.</td>
</tr>
<tr>
  <td><nobr><code>--batch</code></nobr><br>(Windows only)</td>
  <td>Automatically answer questions with defaults when possible. Avoid unnecessary console output (e.g. progress bars).</td>
</tr>
<tr>
  <td><nobr><code>-v</code></nobr><br/><nobr><code>--verbose</code></nobr></td>
  <td>More verbose output. Use twice for even more verbose output.</td>
</tr>
<tr>
  <td><nobr><code>--customize</code></nobr><br>(Windows only)</td>
  <td>Show the graphical policy editor. This allows you to customize which version of a program or library to use.</td>
</tr>
<tr>
  <td><nobr><code>-o</code></nobr><br/><nobr><code>--offline</code></nobr></td>
  <td>Run in off-line mode, overriding the default setting.
<br/>In off-line mode, no interfaces are refreshed even if they are out-of-date, and newer versions of programs won't be downloaded even if the injector already knows about them (e.g. from a previous refresh).</td>
</tr>
<tr>
  <td><nobr><code>-r</code></nobr><br/><nobr><code>--refresh</code></nobr></td>
  <td>Fetch fresh copies of all used feeds.</td>
</tr>
<tr>
  <td><nobr><code>--with-store <code>DIR</code></code></nobr></td>
  <td>Add <code>DIR</code> to the list of implementation caches to search.
<br/>However, new downloads will not be written to this directory.</td>
</tr>
<tr>
  <td><nobr><code>--command <code>COMMAND</code></code></nobr></td>
  <td>Instead of executing the default command, use <code>COMMAND</code> instead. Possible command names are defined in the program's interface.
<br/>Set to empty (<code>&quot;&quot;</code>) to ignore the command during selection.</td>
</tr>
<tr>
  <td><nobr><code>--before <code>VERSION</code></code></nobr></td>
  <td>Require a version of the main program earlier than <code>VERSION</code>. i.e., force the use of an old version the program.</td>
</tr>
<tr>
  <td><nobr><code>--not-before <code>VERSION</code></code></nobr></td>
  <td>Require a version of the main program not earlier than <code>VERSION</code>. E.g., if you want to run version 2.0 or later, use <code>--not-before=2.0</code>.</td>
</tr>
<tr>
  <td><nobr><code>--version <code>RANGE</code></code></nobr></td>
  <td>Require a version of the main program within the given <code>RANGE</code>.
<br/>Ranges are separated by pipes (<code>|</code>).
<br/>Each range is in the form <code>START..!END</code>. The range matches versions where <code>START</code> &lt;= <code>VERSION</code> &lt; <code>END</code>. The start or end may be omitted.
<br/>A single version number may be used instead of a range to match only that version, or <code>!VERSION</code> to match everything except that version.</td>
</tr>
<tr>
  <td><nobr><code>--version-for <code>URI</code> <code>RANGE</code></code></nobr></td>
  <td>For any library or sub-component with the given <code>URI</code> specifies the version <code>RANGE</code> (as for <code>--version</code>).</td>
</tr>
<tr>
  <td><nobr><code>-s</code></nobr><br/><nobr><code>--source</code></nobr></td>
  <td>Select source code rather than a binary. This is used internally by 0compile.</td>
</tr>
<tr>
  <td><nobr><code>--os <code>OS</code></code></nobr></td>
  <td>Forces the solver to target the operating system <code>OS</code>.
<br/>Supported values: <code>\*</code>, <code>POSIX</code>, <code>Linux</code>, <code>Solaris</code>, <code>FreeBSD</code>, <code>Darwin</code>, <code>MacOSX</code>, <code>Cygwin</code>, <code>Windows</code>, <code>unknown</code></td>
</tr>
<tr>
  <td><nobr><code>--cpu <code>CPU</code></code></nobr></td>
  <td>Forces the solver to target a specific <code>CPU</code>.
<br/>Supported values: <code>\*</code>, <code>i386</code>, <code>i486</code>, <code>i586</code>, <code>i686</code>, <code>x86_64</code>, <code>ppc</code>, <code>ppc64</code>, <code>armv6l</code>, <code>armv7l</code>, <code>src</code></td>
</tr>
<tr>
  <td><nobr><code>--language</code></nobr></td>
  <td>Specifies the preferred language for the implementation. Use ISO short language codes (e.g. <code>en</code> or <code>en-US</code>).
<br/>You can use this option multiple times to specify multiple acceptable languages.</td>
</tr>
<tr>
  <td><nobr><code>--xml</code></nobr></td>
  <td>Write selected versions to console as machine-readable XML.</td>
</tr>
</table>
<a name='download'></a><h1>download</h1>
<p>Behaves similarly to <a href='#select'><code>0install select</code></a>, except that it also downloads the selected versions if they are not already cached.
<br/>Returns an exit status of <code>0</code> if it selected a suitable set of versions and they are now all downloaded and in the cache; returns a status of <code>1</code> otherwise.</p>
<p><b>Usage:</b> <code>0install download [OPTIONS] URI</code></p>
<table>
<tr>
  <th>Option</th>
  <th>Description</th>
</tr>
<tr>
  <td><nobr><code>-?</code></nobr><br/><nobr><code>-h</code></nobr><br/><nobr><code>--help</code></nobr></td>
  <td>Show the built-in help text.</td>
</tr>
<tr>
  <td><nobr><code>--background</code></nobr><br>(Windows only)</td>
  <td>Hide the graphical user interface and use something like a tray icon instead. Has no effect in command-line mode.</td>
</tr>
<tr>
  <td><nobr><code>--batch</code></nobr><br>(Windows only)</td>
  <td>Automatically answer questions with defaults when possible. Avoid unnecessary console output (e.g. progress bars).</td>
</tr>
<tr>
  <td><nobr><code>-v</code></nobr><br/><nobr><code>--verbose</code></nobr></td>
  <td>More verbose output. Use twice for even more verbose output.</td>
</tr>
<tr>
  <td><nobr><code>--customize</code></nobr><br>(Windows only)</td>
  <td>Show the graphical policy editor. This allows you to customize which version of a program or library to use.</td>
</tr>
<tr>
  <td><nobr><code>-o</code></nobr><br/><nobr><code>--offline</code></nobr></td>
  <td>Run in off-line mode, overriding the default setting.
<br/>In off-line mode, no interfaces are refreshed even if they are out-of-date, and newer versions of programs won't be downloaded even if the injector already knows about them (e.g. from a previous refresh).</td>
</tr>
<tr>
  <td><nobr><code>-r</code></nobr><br/><nobr><code>--refresh</code></nobr></td>
  <td>Fetch fresh copies of all used feeds.</td>
</tr>
<tr>
  <td><nobr><code>--with-store <code>DIR</code></code></nobr></td>
  <td>Add <code>DIR</code> to the list of implementation caches to search.
<br/>However, new downloads will not be written to this directory.</td>
</tr>
<tr>
  <td><nobr><code>--command <code>COMMAND</code></code></nobr></td>
  <td>Instead of executing the default command, use <code>COMMAND</code> instead. Possible command names are defined in the program's interface.
<br/>Set to empty (<code>&quot;&quot;</code>) to ignore the command during selection.</td>
</tr>
<tr>
  <td><nobr><code>--before <code>VERSION</code></code></nobr></td>
  <td>Require a version of the main program earlier than <code>VERSION</code>. i.e., force the use of an old version the program.</td>
</tr>
<tr>
  <td><nobr><code>--not-before <code>VERSION</code></code></nobr></td>
  <td>Require a version of the main program not earlier than <code>VERSION</code>. E.g., if you want to run version 2.0 or later, use <code>--not-before=2.0</code>.</td>
</tr>
<tr>
  <td><nobr><code>--version <code>RANGE</code></code></nobr></td>
  <td>Require a version of the main program within the given <code>RANGE</code>.
<br/>Ranges are separated by pipes (<code>|</code>).
<br/>Each range is in the form <code>START..!END</code>. The range matches versions where <code>START</code> &lt;= <code>VERSION</code> &lt; <code>END</code>. The start or end may be omitted.
<br/>A single version number may be used instead of a range to match only that version, or <code>!VERSION</code> to match everything except that version.</td>
</tr>
<tr>
  <td><nobr><code>--version-for <code>URI</code> <code>RANGE</code></code></nobr></td>
  <td>For any library or sub-component with the given <code>URI</code> specifies the version <code>RANGE</code> (as for <code>--version</code>).</td>
</tr>
<tr>
  <td><nobr><code>-s</code></nobr><br/><nobr><code>--source</code></nobr></td>
  <td>Select source code rather than a binary. This is used internally by 0compile.</td>
</tr>
<tr>
  <td><nobr><code>--os <code>OS</code></code></nobr></td>
  <td>Forces the solver to target the operating system <code>OS</code>.
<br/>Supported values: <code>\*</code>, <code>POSIX</code>, <code>Linux</code>, <code>Solaris</code>, <code>FreeBSD</code>, <code>Darwin</code>, <code>MacOSX</code>, <code>Cygwin</code>, <code>Windows</code>, <code>unknown</code></td>
</tr>
<tr>
  <td><nobr><code>--cpu <code>CPU</code></code></nobr></td>
  <td>Forces the solver to target a specific <code>CPU</code>.
<br/>Supported values: <code>\*</code>, <code>i386</code>, <code>i486</code>, <code>i586</code>, <code>i686</code>, <code>x86_64</code>, <code>ppc</code>, <code>ppc64</code>, <code>armv6l</code>, <code>armv7l</code>, <code>src</code></td>
</tr>
<tr>
  <td><nobr><code>--language</code></nobr></td>
  <td>Specifies the preferred language for the implementation. Use ISO short language codes (e.g. <code>en</code> or <code>en-US</code>).
<br/>You can use this option multiple times to specify multiple acceptable languages.</td>
</tr>
<tr>
  <td><nobr><code>--xml</code></nobr></td>
  <td>Write selected versions to console as machine-readable XML.</td>
</tr>
<tr>
  <td><nobr><code>--show</code></nobr></td>
  <td>Show where the selected components are stored on the disk.</td>
</tr>
</table>
<a name='update'></a><h1>update</h1>
<p>Checks for updates to the program and download them if found.
<br/>This is similar to <a href='#download'><code>0install download --refresh</code></a>, except that it prints information about whether any changes were found.</p>
<p><b>Usage:</b> <code>0install update [OPTIONS] URI</code></p>
<table>
<tr>
  <th>Option</th>
  <th>Description</th>
</tr>
<tr>
  <td><nobr><code>-?</code></nobr><br/><nobr><code>-h</code></nobr><br/><nobr><code>--help</code></nobr></td>
  <td>Show the built-in help text.</td>
</tr>
<tr>
  <td><nobr><code>--background</code></nobr><br>(Windows only)</td>
  <td>Hide the graphical user interface and use something like a tray icon instead. Has no effect in command-line mode.</td>
</tr>
<tr>
  <td><nobr><code>--batch</code></nobr><br>(Windows only)</td>
  <td>Automatically answer questions with defaults when possible. Avoid unnecessary console output (e.g. progress bars).</td>
</tr>
<tr>
  <td><nobr><code>-v</code></nobr><br/><nobr><code>--verbose</code></nobr></td>
  <td>More verbose output. Use twice for even more verbose output.</td>
</tr>
<tr>
  <td><nobr><code>--customize</code></nobr><br>(Windows only)</td>
  <td>Show the graphical policy editor. This allows you to customize which version of a program or library to use.</td>
</tr>
<tr>
  <td><nobr><code>-o</code></nobr><br/><nobr><code>--offline</code></nobr></td>
  <td>Run in off-line mode, overriding the default setting.
<br/>In off-line mode, no interfaces are refreshed even if they are out-of-date, and newer versions of programs won't be downloaded even if the injector already knows about them (e.g. from a previous refresh).</td>
</tr>
<tr>
  <td><nobr><code>-r</code></nobr><br/><nobr><code>--refresh</code></nobr></td>
  <td>Fetch fresh copies of all used feeds.</td>
</tr>
<tr>
  <td><nobr><code>--with-store <code>DIR</code></code></nobr></td>
  <td>Add <code>DIR</code> to the list of implementation caches to search.
<br/>However, new downloads will not be written to this directory.</td>
</tr>
<tr>
  <td><nobr><code>--command <code>COMMAND</code></code></nobr></td>
  <td>Instead of executing the default command, use <code>COMMAND</code> instead. Possible command names are defined in the program's interface.
<br/>Set to empty (<code>&quot;&quot;</code>) to ignore the command during selection.</td>
</tr>
<tr>
  <td><nobr><code>--before <code>VERSION</code></code></nobr></td>
  <td>Require a version of the main program earlier than <code>VERSION</code>. i.e., force the use of an old version the program.</td>
</tr>
<tr>
  <td><nobr><code>--not-before <code>VERSION</code></code></nobr></td>
  <td>Require a version of the main program not earlier than <code>VERSION</code>. E.g., if you want to run version 2.0 or later, use <code>--not-before=2.0</code>.</td>
</tr>
<tr>
  <td><nobr><code>--version <code>RANGE</code></code></nobr></td>
  <td>Require a version of the main program within the given <code>RANGE</code>.
<br/>Ranges are separated by pipes (<code>|</code>).
<br/>Each range is in the form <code>START..!END</code>. The range matches versions where <code>START</code> &lt;= <code>VERSION</code> &lt; <code>END</code>. The start or end may be omitted.
<br/>A single version number may be used instead of a range to match only that version, or <code>!VERSION</code> to match everything except that version.</td>
</tr>
<tr>
  <td><nobr><code>--version-for <code>URI</code> <code>RANGE</code></code></nobr></td>
  <td>For any library or sub-component with the given <code>URI</code> specifies the version <code>RANGE</code> (as for <code>--version</code>).</td>
</tr>
<tr>
  <td><nobr><code>-s</code></nobr><br/><nobr><code>--source</code></nobr></td>
  <td>Select source code rather than a binary. This is used internally by 0compile.</td>
</tr>
<tr>
  <td><nobr><code>--os <code>OS</code></code></nobr></td>
  <td>Forces the solver to target the operating system <code>OS</code>.
<br/>Supported values: <code>\*</code>, <code>POSIX</code>, <code>Linux</code>, <code>Solaris</code>, <code>FreeBSD</code>, <code>Darwin</code>, <code>MacOSX</code>, <code>Cygwin</code>, <code>Windows</code>, <code>unknown</code></td>
</tr>
<tr>
  <td><nobr><code>--cpu <code>CPU</code></code></nobr></td>
  <td>Forces the solver to target a specific <code>CPU</code>.
<br/>Supported values: <code>\*</code>, <code>i386</code>, <code>i486</code>, <code>i586</code>, <code>i686</code>, <code>x86_64</code>, <code>ppc</code>, <code>ppc64</code>, <code>armv6l</code>, <code>armv7l</code>, <code>src</code></td>
</tr>
<tr>
  <td><nobr><code>--language</code></nobr></td>
  <td>Specifies the preferred language for the implementation. Use ISO short language codes (e.g. <code>en</code> or <code>en-US</code>).
<br/>You can use this option multiple times to specify multiple acceptable languages.</td>
</tr>
<tr>
  <td><nobr><code>--xml</code></nobr></td>
  <td>Write selected versions to console as machine-readable XML.</td>
</tr>
<tr>
  <td><nobr><code>--show</code></nobr></td>
  <td>Show where the selected components are stored on the disk.</td>
</tr>
</table>
<a name='run'></a><h1>run</h1>
<p>Behaves similarly to <a href='#download'><code>0install download</code></a>, except that it also runs the program after ensuring it is in the cache.
<br/>Returns an exit status of <code>1</code> if the download step failed. Otherwise, the exit status will be the exit status of the program being run.</p>
<p><b>Usage:</b> <code>0install run [OPTIONS] URI [ARGS]</code></p>
<table>
<tr>
  <th>Option</th>
  <th>Description</th>
</tr>
<tr>
  <td><nobr><code>-?</code></nobr><br/><nobr><code>-h</code></nobr><br/><nobr><code>--help</code></nobr></td>
  <td>Show the built-in help text.</td>
</tr>
<tr>
  <td><nobr><code>--background</code></nobr><br>(Windows only)</td>
  <td>Hide the graphical user interface and use something like a tray icon instead. Has no effect in command-line mode.</td>
</tr>
<tr>
  <td><nobr><code>--batch</code></nobr><br>(Windows only)</td>
  <td>Automatically answer questions with defaults when possible. Avoid unnecessary console output (e.g. progress bars).</td>
</tr>
<tr>
  <td><nobr><code>-v</code></nobr><br/><nobr><code>--verbose</code></nobr></td>
  <td>More verbose output. Use twice for even more verbose output.</td>
</tr>
<tr>
  <td><nobr><code>--customize</code></nobr><br>(Windows only)</td>
  <td>Show the graphical policy editor. This allows you to customize which version of a program or library to use.</td>
</tr>
<tr>
  <td><nobr><code>-o</code></nobr><br/><nobr><code>--offline</code></nobr></td>
  <td>Run in off-line mode, overriding the default setting.
<br/>In off-line mode, no interfaces are refreshed even if they are out-of-date, and newer versions of programs won't be downloaded even if the injector already knows about them (e.g. from a previous refresh).</td>
</tr>
<tr>
  <td><nobr><code>-r</code></nobr><br/><nobr><code>--refresh</code></nobr></td>
  <td>Fetch fresh copies of all used feeds.</td>
</tr>
<tr>
  <td><nobr><code>--with-store <code>DIR</code></code></nobr></td>
  <td>Add <code>DIR</code> to the list of implementation caches to search.
<br/>However, new downloads will not be written to this directory.</td>
</tr>
<tr>
  <td><nobr><code>--command <code>COMMAND</code></code></nobr></td>
  <td>Instead of executing the default command, use <code>COMMAND</code> instead. Possible command names are defined in the program's interface.
<br/>Set to empty (<code>&quot;&quot;</code>) to ignore the command during selection.</td>
</tr>
<tr>
  <td><nobr><code>--before <code>VERSION</code></code></nobr></td>
  <td>Require a version of the main program earlier than <code>VERSION</code>. i.e., force the use of an old version the program.</td>
</tr>
<tr>
  <td><nobr><code>--not-before <code>VERSION</code></code></nobr></td>
  <td>Require a version of the main program not earlier than <code>VERSION</code>. E.g., if you want to run version 2.0 or later, use <code>--not-before=2.0</code>.</td>
</tr>
<tr>
  <td><nobr><code>--version <code>RANGE</code></code></nobr></td>
  <td>Require a version of the main program within the given <code>RANGE</code>.
<br/>Ranges are separated by pipes (<code>|</code>).
<br/>Each range is in the form <code>START..!END</code>. The range matches versions where <code>START</code> &lt;= <code>VERSION</code> &lt; <code>END</code>. The start or end may be omitted.
<br/>A single version number may be used instead of a range to match only that version, or <code>!VERSION</code> to match everything except that version.</td>
</tr>
<tr>
  <td><nobr><code>--version-for <code>URI</code> <code>RANGE</code></code></nobr></td>
  <td>For any library or sub-component with the given <code>URI</code> specifies the version <code>RANGE</code> (as for <code>--version</code>).</td>
</tr>
<tr>
  <td><nobr><code>-s</code></nobr><br/><nobr><code>--source</code></nobr></td>
  <td>Select source code rather than a binary. This is used internally by 0compile.</td>
</tr>
<tr>
  <td><nobr><code>--os <code>OS</code></code></nobr></td>
  <td>Forces the solver to target the operating system <code>OS</code>.
<br/>Supported values: <code>\*</code>, <code>POSIX</code>, <code>Linux</code>, <code>Solaris</code>, <code>FreeBSD</code>, <code>Darwin</code>, <code>MacOSX</code>, <code>Cygwin</code>, <code>Windows</code>, <code>unknown</code></td>
</tr>
<tr>
  <td><nobr><code>--cpu <code>CPU</code></code></nobr></td>
  <td>Forces the solver to target a specific <code>CPU</code>.
<br/>Supported values: <code>\*</code>, <code>i386</code>, <code>i486</code>, <code>i586</code>, <code>i686</code>, <code>x86_64</code>, <code>ppc</code>, <code>ppc64</code>, <code>armv6l</code>, <code>armv7l</code>, <code>src</code></td>
</tr>
<tr>
  <td><nobr><code>--language</code></nobr></td>
  <td>Specifies the preferred language for the implementation. Use ISO short language codes (e.g. <code>en</code> or <code>en-US</code>).
<br/>You can use this option multiple times to specify multiple acceptable languages.</td>
</tr>
<tr>
  <td><nobr><code>--xml</code></nobr></td>
  <td>Write selected versions to console as machine-readable XML.</td>
</tr>
<tr>
  <td><nobr><code>--show</code></nobr></td>
  <td>Show where the selected components are stored on the disk.</td>
</tr>
<tr>
  <td><nobr><code>-m <code>MAIN</code></code></nobr><br/><nobr><code>--main <code>MAIN</code></code></nobr></td>
  <td>Run the specified executable <code>MAIN</code> instead of the default. If it starts with <code>/</code> or <code>\</code> then the path is relative to the implementation's top-level directory, whereas otherwise it is relative to the directory containing the default main program.
<br/>May not contain command-line arguments! Whitespaces do not need to be escaped.</td>
</tr>
<tr>
  <td><nobr><code>-w <code>COMMAND</code></code></nobr><br/><nobr><code>--wrapper <code>COMMAND</code></code></nobr></td>
  <td>Instead of executing the chosen program directly, run <code>COMMAND</code> <code>PROGRAM</code> <code>ARGS</code>. This is useful for running debuggers and tracing tools on the program (rather than on Zero Install!).
<br/>Note that the wrapper is executed in the environment selected by the program; hence, this mechanism cannot be used for sandboxing.
<br/>May contain command-line arguments. Whitespaces must be escaped!</td>
</tr>
<tr>
  <td><nobr><code>--no-wait</code></nobr><br>(Windows only)</td>
  <td>Immediately returns once the chosen program has been launched instead of waiting for it to finish executing. On Windows the exit code is the process ID of the launched program.</td>
</tr>
</table>
<a name='import'></a><h1>import</h1>
<p>Imports a feed from a local file, as if it had been downloaded from the network. This is useful when testing a feed file, to avoid uploading it to a remote server in order to download it again.
<br/>The file must have a trusted digital signature, as when fetching from the network.</p>
<p><b>Usage:</b> <code>0install import FEED-FILE [...]</code></p>
<table>
<tr>
  <th>Option</th>
  <th>Description</th>
</tr>
<tr>
  <td><nobr><code>-?</code></nobr><br/><nobr><code>-h</code></nobr><br/><nobr><code>--help</code></nobr></td>
  <td>Show the built-in help text.</td>
</tr>
<tr>
  <td><nobr><code>--background</code></nobr><br>(Windows only)</td>
  <td>Hide the graphical user interface and use something like a tray icon instead. Has no effect in command-line mode.</td>
</tr>
<tr>
  <td><nobr><code>--batch</code></nobr><br>(Windows only)</td>
  <td>Automatically answer questions with defaults when possible. Avoid unnecessary console output (e.g. progress bars).</td>
</tr>
<tr>
  <td><nobr><code>-v</code></nobr><br/><nobr><code>--verbose</code></nobr></td>
  <td>More verbose output. Use twice for even more verbose output.</td>
</tr>
</table>
<a name='export'></a><h1>export</h1>
<p>Exports all feeds and implementations required to launch the program specified by a <code>URI</code> to a <code>DIRECTORY</code>.</p>
<p><b>Usage:</b> <code>0install export [OPTIONS] URI DIRECTORY</code></p>
<table>
<tr>
  <th>Option</th>
  <th>Description</th>
</tr>
<tr>
  <td><nobr><code>-?</code></nobr><br/><nobr><code>-h</code></nobr><br/><nobr><code>--help</code></nobr></td>
  <td>Show the built-in help text.</td>
</tr>
<tr>
  <td><nobr><code>--background</code></nobr><br>(Windows only)</td>
  <td>Hide the graphical user interface and use something like a tray icon instead. Has no effect in command-line mode.</td>
</tr>
<tr>
  <td><nobr><code>--batch</code></nobr><br>(Windows only)</td>
  <td>Automatically answer questions with defaults when possible. Avoid unnecessary console output (e.g. progress bars).</td>
</tr>
<tr>
  <td><nobr><code>-v</code></nobr><br/><nobr><code>--verbose</code></nobr></td>
  <td>More verbose output. Use twice for even more verbose output.</td>
</tr>
<tr>
  <td><nobr><code>--customize</code></nobr><br>(Windows only)</td>
  <td>Show the graphical policy editor. This allows you to customize which version of a program or library to use.</td>
</tr>
<tr>
  <td><nobr><code>-o</code></nobr><br/><nobr><code>--offline</code></nobr></td>
  <td>Run in off-line mode, overriding the default setting.
<br/>In off-line mode, no interfaces are refreshed even if they are out-of-date, and newer versions of programs won't be downloaded even if the injector already knows about them (e.g. from a previous refresh).</td>
</tr>
<tr>
  <td><nobr><code>-r</code></nobr><br/><nobr><code>--refresh</code></nobr></td>
  <td>Fetch fresh copies of all used feeds.</td>
</tr>
<tr>
  <td><nobr><code>--with-store <code>DIR</code></code></nobr></td>
  <td>Add <code>DIR</code> to the list of implementation caches to search.
<br/>However, new downloads will not be written to this directory.</td>
</tr>
<tr>
  <td><nobr><code>--command <code>COMMAND</code></code></nobr></td>
  <td>Instead of executing the default command, use <code>COMMAND</code> instead. Possible command names are defined in the program's interface.
<br/>Set to empty (<code>&quot;&quot;</code>) to ignore the command during selection.</td>
</tr>
<tr>
  <td><nobr><code>--before <code>VERSION</code></code></nobr></td>
  <td>Require a version of the main program earlier than <code>VERSION</code>. i.e., force the use of an old version the program.</td>
</tr>
<tr>
  <td><nobr><code>--not-before <code>VERSION</code></code></nobr></td>
  <td>Require a version of the main program not earlier than <code>VERSION</code>. E.g., if you want to run version 2.0 or later, use <code>--not-before=2.0</code>.</td>
</tr>
<tr>
  <td><nobr><code>--version <code>RANGE</code></code></nobr></td>
  <td>Require a version of the main program within the given <code>RANGE</code>.
<br/>Ranges are separated by pipes (<code>|</code>).
<br/>Each range is in the form <code>START..!END</code>. The range matches versions where <code>START</code> &lt;= <code>VERSION</code> &lt; <code>END</code>. The start or end may be omitted.
<br/>A single version number may be used instead of a range to match only that version, or <code>!VERSION</code> to match everything except that version.</td>
</tr>
<tr>
  <td><nobr><code>--version-for <code>URI</code> <code>RANGE</code></code></nobr></td>
  <td>For any library or sub-component with the given <code>URI</code> specifies the version <code>RANGE</code> (as for <code>--version</code>).</td>
</tr>
<tr>
  <td><nobr><code>-s</code></nobr><br/><nobr><code>--source</code></nobr></td>
  <td>Select source code rather than a binary. This is used internally by 0compile.</td>
</tr>
<tr>
  <td><nobr><code>--os <code>OS</code></code></nobr></td>
  <td>Forces the solver to target the operating system <code>OS</code>.
<br/>Supported values: <code>\*</code>, <code>POSIX</code>, <code>Linux</code>, <code>Solaris</code>, <code>FreeBSD</code>, <code>Darwin</code>, <code>MacOSX</code>, <code>Cygwin</code>, <code>Windows</code>, <code>unknown</code></td>
</tr>
<tr>
  <td><nobr><code>--cpu <code>CPU</code></code></nobr></td>
  <td>Forces the solver to target a specific <code>CPU</code>.
<br/>Supported values: <code>\*</code>, <code>i386</code>, <code>i486</code>, <code>i586</code>, <code>i686</code>, <code>x86_64</code>, <code>ppc</code>, <code>ppc64</code>, <code>armv6l</code>, <code>armv7l</code>, <code>src</code></td>
</tr>
<tr>
  <td><nobr><code>--language</code></nobr></td>
  <td>Specifies the preferred language for the implementation. Use ISO short language codes (e.g. <code>en</code> or <code>en-US</code>).
<br/>You can use this option multiple times to specify multiple acceptable languages.</td>
</tr>
<tr>
  <td><nobr><code>--xml</code></nobr></td>
  <td>Write selected versions to console as machine-readable XML.</td>
</tr>
<tr>
  <td><nobr><code>--show</code></nobr></td>
  <td>Show where the selected components are stored on the disk.</td>
</tr>
<tr>
  <td><nobr><code>--no-implementations</code></nobr></td>
  <td>Do not include implementation archives in the export. Only export feeds.</td>
</tr>
<tr>
  <td><nobr><code>--include-zero-install</code></nobr></td>
  <td>Include Zero Install itself in the export alongside the application.</td>
</tr>
<tr>
  <td><nobr><code>--bootstrap <code>VALUE</code></code></nobr></td>
  <td>Choose the type of Bootstrapper to place alongside the export.
<br/>Supported values: <code>None</code>, <code>Run</code>, <code>Integrate</code></td>
</tr>
</table>
<a name='search'></a><h1>search</h1>
<p>Searches for feeds indexed by the mirror server that match specified keywords. Note that the default mirror server indexes all known feeds, regardless of quality; you must decide whether to trust the programs before running them.</p>
<p><b>Usage:</b> <code>0install search QUERY</code></p>
<table>
<tr>
  <th>Option</th>
  <th>Description</th>
</tr>
<tr>
  <td><nobr><code>-?</code></nobr><br/><nobr><code>-h</code></nobr><br/><nobr><code>--help</code></nobr></td>
  <td>Show the built-in help text.</td>
</tr>
<tr>
  <td><nobr><code>--background</code></nobr><br>(Windows only)</td>
  <td>Hide the graphical user interface and use something like a tray icon instead. Has no effect in command-line mode.</td>
</tr>
<tr>
  <td><nobr><code>--batch</code></nobr><br>(Windows only)</td>
  <td>Automatically answer questions with defaults when possible. Avoid unnecessary console output (e.g. progress bars).</td>
</tr>
<tr>
  <td><nobr><code>-v</code></nobr><br/><nobr><code>--verbose</code></nobr></td>
  <td>More verbose output. Use twice for even more verbose output.</td>
</tr>
</table>
<a name='list'></a><h1>list</h1>
<p>Lists all locally known feed URIs. If a search term is given, only URIs containing that string are shown (case insensitive).</p>
<p><b>Usage:</b> <code>0install list [PATTERN]</code></p>
<table>
<tr>
  <th>Option</th>
  <th>Description</th>
</tr>
<tr>
  <td><nobr><code>-?</code></nobr><br/><nobr><code>-h</code></nobr><br/><nobr><code>--help</code></nobr></td>
  <td>Show the built-in help text.</td>
</tr>
<tr>
  <td><nobr><code>--background</code></nobr><br>(Windows only)</td>
  <td>Hide the graphical user interface and use something like a tray icon instead. Has no effect in command-line mode.</td>
</tr>
<tr>
  <td><nobr><code>--batch</code></nobr><br>(Windows only)</td>
  <td>Automatically answer questions with defaults when possible. Avoid unnecessary console output (e.g. progress bars).</td>
</tr>
<tr>
  <td><nobr><code>-v</code></nobr><br/><nobr><code>--verbose</code></nobr></td>
  <td>More verbose output. Use twice for even more verbose output.</td>
</tr>
</table>
<a name='catalog_search'></a><h1>catalog search</h1>
<p>Searches registered catalogs for applications that match the specified query. If no query is given all applications in the catalogs are listed.</p>
<p><b>Usage:</b> <code>0install catalog search [QUERY]</code></p>
<table>
<tr>
  <th>Option</th>
  <th>Description</th>
</tr>
<tr>
  <td><nobr><code>-?</code></nobr><br/><nobr><code>-h</code></nobr><br/><nobr><code>--help</code></nobr></td>
  <td>Show the built-in help text.</td>
</tr>
<tr>
  <td><nobr><code>--background</code></nobr><br>(Windows only)</td>
  <td>Hide the graphical user interface and use something like a tray icon instead. Has no effect in command-line mode.</td>
</tr>
<tr>
  <td><nobr><code>--batch</code></nobr><br>(Windows only)</td>
  <td>Automatically answer questions with defaults when possible. Avoid unnecessary console output (e.g. progress bars).</td>
</tr>
<tr>
  <td><nobr><code>-v</code></nobr><br/><nobr><code>--verbose</code></nobr></td>
  <td>More verbose output. Use twice for even more verbose output.</td>
</tr>
</table>
<a name='catalog_refresh'></a><h1>catalog refresh</h1>
<p>Refreshes (downloads) all registered catalogs.</p>
<p><b>Usage:</b> <code>0install catalog refresh</code></p>
<table>
<tr>
  <th>Option</th>
  <th>Description</th>
</tr>
<tr>
  <td><nobr><code>-?</code></nobr><br/><nobr><code>-h</code></nobr><br/><nobr><code>--help</code></nobr></td>
  <td>Show the built-in help text.</td>
</tr>
<tr>
  <td><nobr><code>--background</code></nobr><br>(Windows only)</td>
  <td>Hide the graphical user interface and use something like a tray icon instead. Has no effect in command-line mode.</td>
</tr>
<tr>
  <td><nobr><code>--batch</code></nobr><br>(Windows only)</td>
  <td>Automatically answer questions with defaults when possible. Avoid unnecessary console output (e.g. progress bars).</td>
</tr>
<tr>
  <td><nobr><code>-v</code></nobr><br/><nobr><code>--verbose</code></nobr></td>
  <td>More verbose output. Use twice for even more verbose output.</td>
</tr>
</table>
<a name='catalog_add'></a><h1>catalog add</h1>
<p>Adds an URI to the list of catalogs.</p>
<p><b>Usage:</b> <code>0install catalog add URI</code></p>
<table>
<tr>
  <th>Option</th>
  <th>Description</th>
</tr>
<tr>
  <td><nobr><code>-?</code></nobr><br/><nobr><code>-h</code></nobr><br/><nobr><code>--help</code></nobr></td>
  <td>Show the built-in help text.</td>
</tr>
<tr>
  <td><nobr><code>--background</code></nobr><br>(Windows only)</td>
  <td>Hide the graphical user interface and use something like a tray icon instead. Has no effect in command-line mode.</td>
</tr>
<tr>
  <td><nobr><code>--batch</code></nobr><br>(Windows only)</td>
  <td>Automatically answer questions with defaults when possible. Avoid unnecessary console output (e.g. progress bars).</td>
</tr>
<tr>
  <td><nobr><code>-v</code></nobr><br/><nobr><code>--verbose</code></nobr></td>
  <td>More verbose output. Use twice for even more verbose output.</td>
</tr>
<tr>
  <td><nobr><code>--skip-verify</code></nobr></td>
  <td>Skip downloading the catalog to verify it is valid before adding it to the list.</td>
</tr>
</table>
<a name='catalog_remove'></a><h1>catalog remove</h1>
<p>Removes an URI from the list of catalogs.</p>
<p><b>Usage:</b> <code>0install catalog remove URI</code></p>
<table>
<tr>
  <th>Option</th>
  <th>Description</th>
</tr>
<tr>
  <td><nobr><code>-?</code></nobr><br/><nobr><code>-h</code></nobr><br/><nobr><code>--help</code></nobr></td>
  <td>Show the built-in help text.</td>
</tr>
<tr>
  <td><nobr><code>--background</code></nobr><br>(Windows only)</td>
  <td>Hide the graphical user interface and use something like a tray icon instead. Has no effect in command-line mode.</td>
</tr>
<tr>
  <td><nobr><code>--batch</code></nobr><br>(Windows only)</td>
  <td>Automatically answer questions with defaults when possible. Avoid unnecessary console output (e.g. progress bars).</td>
</tr>
<tr>
  <td><nobr><code>-v</code></nobr><br/><nobr><code>--verbose</code></nobr></td>
  <td>More verbose output. Use twice for even more verbose output.</td>
</tr>
</table>
<a name='catalog_reset'></a><h1>catalog reset</h1>
<p>Resets the list of catalogs to the default source.</p>
<p><b>Usage:</b> <code>0install catalog reset</code></p>
<table>
<tr>
  <th>Option</th>
  <th>Description</th>
</tr>
<tr>
  <td><nobr><code>-?</code></nobr><br/><nobr><code>-h</code></nobr><br/><nobr><code>--help</code></nobr></td>
  <td>Show the built-in help text.</td>
</tr>
<tr>
  <td><nobr><code>--background</code></nobr><br>(Windows only)</td>
  <td>Hide the graphical user interface and use something like a tray icon instead. Has no effect in command-line mode.</td>
</tr>
<tr>
  <td><nobr><code>--batch</code></nobr><br>(Windows only)</td>
  <td>Automatically answer questions with defaults when possible. Avoid unnecessary console output (e.g. progress bars).</td>
</tr>
<tr>
  <td><nobr><code>-v</code></nobr><br/><nobr><code>--verbose</code></nobr></td>
  <td>More verbose output. Use twice for even more verbose output.</td>
</tr>
</table>
<a name='catalog_list'></a><h1>catalog list</h1>
<p>Lists all registered catalogs.</p>
<p><b>Usage:</b> <code>0install catalog list</code></p>
<table>
<tr>
  <th>Option</th>
  <th>Description</th>
</tr>
<tr>
  <td><nobr><code>-?</code></nobr><br/><nobr><code>-h</code></nobr><br/><nobr><code>--help</code></nobr></td>
  <td>Show the built-in help text.</td>
</tr>
<tr>
  <td><nobr><code>--background</code></nobr><br>(Windows only)</td>
  <td>Hide the graphical user interface and use something like a tray icon instead. Has no effect in command-line mode.</td>
</tr>
<tr>
  <td><nobr><code>--batch</code></nobr><br>(Windows only)</td>
  <td>Automatically answer questions with defaults when possible. Avoid unnecessary console output (e.g. progress bars).</td>
</tr>
<tr>
  <td><nobr><code>-v</code></nobr><br/><nobr><code>--verbose</code></nobr></td>
  <td>More verbose output. Use twice for even more verbose output.</td>
</tr>
</table>
<a name='config'></a><h1>config</h1>
<p>View or change configuration settings.
<br/>With no arguments, it displays all settings and their current values. With one argument, it displays the current value of the named setting. With two arguments, it sets the setting to the given value or resets it to the default value if the value <code>default</code> is given.</p>
<p><b>Usage:</b> <code>0install config [NAME [VALUE|default]]</code></p>
<table>
<tr>
  <th>Option</th>
  <th>Description</th>
</tr>
<tr>
  <td><nobr><code>-?</code></nobr><br/><nobr><code>-h</code></nobr><br/><nobr><code>--help</code></nobr></td>
  <td>Show the built-in help text.</td>
</tr>
<tr>
  <td><nobr><code>--background</code></nobr><br>(Windows only)</td>
  <td>Hide the graphical user interface and use something like a tray icon instead. Has no effect in command-line mode.</td>
</tr>
<tr>
  <td><nobr><code>--batch</code></nobr><br>(Windows only)</td>
  <td>Automatically answer questions with defaults when possible. Avoid unnecessary console output (e.g. progress bars).</td>
</tr>
<tr>
  <td><nobr><code>-v</code></nobr><br/><nobr><code>--verbose</code></nobr></td>
  <td>More verbose output. Use twice for even more verbose output.</td>
</tr>
<tr>
  <td><nobr><code>--tab <code>TAB</code></code></nobr></td>
  <td>Switch to a specific tab in the configuration GUI. Possible <code>TAB</code>s are <code>updates</code>, <code>storage</code>, <code>catalog</code>, <code>trust</code>, <code>sync</code>, <code>language</code>, <code>language</code> and <code>advanced</code>.
<br/>Has no effect in text-mode.</td>
</tr>
</table>
<a name='add-feed'></a><h1>add-feed</h1>
<p>Register an additional source of implementations (versions) of a program.</p>
<p><b>Usage:</b> <code>0install add-feed [OPTIONS] [INTERFACE] FEED</code></p>
<table>
<tr>
  <th>Option</th>
  <th>Description</th>
</tr>
<tr>
  <td><nobr><code>-?</code></nobr><br/><nobr><code>-h</code></nobr><br/><nobr><code>--help</code></nobr></td>
  <td>Show the built-in help text.</td>
</tr>
<tr>
  <td><nobr><code>--background</code></nobr><br>(Windows only)</td>
  <td>Hide the graphical user interface and use something like a tray icon instead. Has no effect in command-line mode.</td>
</tr>
<tr>
  <td><nobr><code>--batch</code></nobr><br>(Windows only)</td>
  <td>Automatically answer questions with defaults when possible. Avoid unnecessary console output (e.g. progress bars).</td>
</tr>
<tr>
  <td><nobr><code>-v</code></nobr><br/><nobr><code>--verbose</code></nobr></td>
  <td>More verbose output. Use twice for even more verbose output.</td>
</tr>
<tr>
  <td><nobr><code>-o</code></nobr><br/><nobr><code>--offline</code></nobr></td>
  <td>Run in off-line mode, overriding the default setting.
<br/>In off-line mode, no interfaces are refreshed even if they are out-of-date, and newer versions of programs won't be downloaded even if the injector already knows about them (e.g. from a previous refresh).</td>
</tr>
<tr>
  <td><nobr><code>-r</code></nobr><br/><nobr><code>--refresh</code></nobr></td>
  <td>Fetch fresh copies of all used feeds.</td>
</tr>
</table>
<a name='remove-feed'></a><h1>remove-feed</h1>
<p>Un-registers a feed, reversing the effect of <a href='#add-feed'><code>0install add-feed</code></a>.</p>
<p><b>Usage:</b> <code>0install remove-feed [OPTIONS] [INTERFACE] FEED</code></p>
<table>
<tr>
  <th>Option</th>
  <th>Description</th>
</tr>
<tr>
  <td><nobr><code>-?</code></nobr><br/><nobr><code>-h</code></nobr><br/><nobr><code>--help</code></nobr></td>
  <td>Show the built-in help text.</td>
</tr>
<tr>
  <td><nobr><code>--background</code></nobr><br>(Windows only)</td>
  <td>Hide the graphical user interface and use something like a tray icon instead. Has no effect in command-line mode.</td>
</tr>
<tr>
  <td><nobr><code>--batch</code></nobr><br>(Windows only)</td>
  <td>Automatically answer questions with defaults when possible. Avoid unnecessary console output (e.g. progress bars).</td>
</tr>
<tr>
  <td><nobr><code>-v</code></nobr><br/><nobr><code>--verbose</code></nobr></td>
  <td>More verbose output. Use twice for even more verbose output.</td>
</tr>
<tr>
  <td><nobr><code>-o</code></nobr><br/><nobr><code>--offline</code></nobr></td>
  <td>Run in off-line mode, overriding the default setting.
<br/>In off-line mode, no interfaces are refreshed even if they are out-of-date, and newer versions of programs won't be downloaded even if the injector already knows about them (e.g. from a previous refresh).</td>
</tr>
<tr>
  <td><nobr><code>-r</code></nobr><br/><nobr><code>--refresh</code></nobr></td>
  <td>Fetch fresh copies of all used feeds.</td>
</tr>
</table>
<a name='list-feeds'></a><h1>list-feeds</h1>
<p>Lists all extra feeds added to URI using <a href='#add-feed'><code>0install add-feed</code></a>.</p>
<p><b>Usage:</b> <code>0install list-feeds [OPTIONS] URI</code></p>
<table>
<tr>
  <th>Option</th>
  <th>Description</th>
</tr>
<tr>
  <td><nobr><code>-?</code></nobr><br/><nobr><code>-h</code></nobr><br/><nobr><code>--help</code></nobr></td>
  <td>Show the built-in help text.</td>
</tr>
<tr>
  <td><nobr><code>--background</code></nobr><br>(Windows only)</td>
  <td>Hide the graphical user interface and use something like a tray icon instead. Has no effect in command-line mode.</td>
</tr>
<tr>
  <td><nobr><code>--batch</code></nobr><br>(Windows only)</td>
  <td>Automatically answer questions with defaults when possible. Avoid unnecessary console output (e.g. progress bars).</td>
</tr>
<tr>
  <td><nobr><code>-v</code></nobr><br/><nobr><code>--verbose</code></nobr></td>
  <td>More verbose output. Use twice for even more verbose output.</td>
</tr>
</table>
<a name='digest'></a><h1>digest</h1>
<p>Calculates the manifest digest of a directory or archive.</p>
<p><b>Usage:</b> <code>0install digest (DIRECTORY | ARCHIVE [SUBDIR])</code></p>
<table>
<tr>
  <th>Option</th>
  <th>Description</th>
</tr>
<tr>
  <td><nobr><code>-?</code></nobr><br/><nobr><code>-h</code></nobr><br/><nobr><code>--help</code></nobr></td>
  <td>Show the built-in help text.</td>
</tr>
<tr>
  <td><nobr><code>--background</code></nobr><br>(Windows only)</td>
  <td>Hide the graphical user interface and use something like a tray icon instead. Has no effect in command-line mode.</td>
</tr>
<tr>
  <td><nobr><code>--batch</code></nobr><br>(Windows only)</td>
  <td>Automatically answer questions with defaults when possible. Avoid unnecessary console output (e.g. progress bars).</td>
</tr>
<tr>
  <td><nobr><code>-v</code></nobr><br/><nobr><code>--verbose</code></nobr></td>
  <td>More verbose output. Use twice for even more verbose output.</td>
</tr>
<tr>
  <td><nobr><code>--manifest</code></nobr></td>
  <td>Display the manifest itself (one line for each file or directory).</td>
</tr>
<tr>
  <td><nobr><code>--digest</code></nobr></td>
  <td>Display the manifest's digest (enabled by default if --manifest is not set).</td>
</tr>
<tr>
  <td><nobr><code>--algorithm <code>HASH</code></code></nobr></td>
  <td>The <code>HASH</code> algorithm to use for the digest.
<br/>Supported values: <code>sha256new</code>, <code>sha256</code>, <code>sha1new</code></td>
</tr>
</table>
<a name='store_add'></a><h1>store add</h1>
<p>Adds the contents of a directory or archive to the cache.</p>
<p><b>Usage:</b> <code>0install store add DIGEST (DIRECTORY | (ARCHIVE [EXTRACT [MIME-TYPE [...]]))</code></p>
<table>
<tr>
  <th>Option</th>
  <th>Description</th>
</tr>
<tr>
  <td><nobr><code>-?</code></nobr><br/><nobr><code>-h</code></nobr><br/><nobr><code>--help</code></nobr></td>
  <td>Show the built-in help text.</td>
</tr>
<tr>
  <td><nobr><code>--background</code></nobr><br>(Windows only)</td>
  <td>Hide the graphical user interface and use something like a tray icon instead. Has no effect in command-line mode.</td>
</tr>
<tr>
  <td><nobr><code>--batch</code></nobr><br>(Windows only)</td>
  <td>Automatically answer questions with defaults when possible. Avoid unnecessary console output (e.g. progress bars).</td>
</tr>
<tr>
  <td><nobr><code>-v</code></nobr><br/><nobr><code>--verbose</code></nobr></td>
  <td>More verbose output. Use twice for even more verbose output.</td>
</tr>
</table>
<a name='store_audit'></a><h1>store audit</h1>
<p>Checks that all implementations in the cache are undamaged.
<br/>Additional arguments specify custom cache locations.</p>
<p><b>Usage:</b> <code>0install store audit [CACHE-DIR+]</code></p>
<table>
<tr>
  <th>Option</th>
  <th>Description</th>
</tr>
<tr>
  <td><nobr><code>-?</code></nobr><br/><nobr><code>-h</code></nobr><br/><nobr><code>--help</code></nobr></td>
  <td>Show the built-in help text.</td>
</tr>
<tr>
  <td><nobr><code>--background</code></nobr><br>(Windows only)</td>
  <td>Hide the graphical user interface and use something like a tray icon instead. Has no effect in command-line mode.</td>
</tr>
<tr>
  <td><nobr><code>--batch</code></nobr><br>(Windows only)</td>
  <td>Automatically answer questions with defaults when possible. Avoid unnecessary console output (e.g. progress bars).</td>
</tr>
<tr>
  <td><nobr><code>-v</code></nobr><br/><nobr><code>--verbose</code></nobr></td>
  <td>More verbose output. Use twice for even more verbose output.</td>
</tr>
</table>
<a name='store_copy'></a><h1>store copy</h1>
<p>Copies an implementation into the cache. Similar to <a href='#store_add'><code>0install store add</code></a>, but the digest is extracted from the directory name.
<br/>An additional arguments specifies a custom target cache location.</p>
<p><b>Usage:</b> <code>0install store copy DIRECTORY [CACHE]</code></p>
<table>
<tr>
  <th>Option</th>
  <th>Description</th>
</tr>
<tr>
  <td><nobr><code>-?</code></nobr><br/><nobr><code>-h</code></nobr><br/><nobr><code>--help</code></nobr></td>
  <td>Show the built-in help text.</td>
</tr>
<tr>
  <td><nobr><code>--background</code></nobr><br>(Windows only)</td>
  <td>Hide the graphical user interface and use something like a tray icon instead. Has no effect in command-line mode.</td>
</tr>
<tr>
  <td><nobr><code>--batch</code></nobr><br>(Windows only)</td>
  <td>Automatically answer questions with defaults when possible. Avoid unnecessary console output (e.g. progress bars).</td>
</tr>
<tr>
  <td><nobr><code>-v</code></nobr><br/><nobr><code>--verbose</code></nobr></td>
  <td>More verbose output. Use twice for even more verbose output.</td>
</tr>
</table>
<a name='store_export'></a><h1>store export</h1>
<p>Exports a cached implementation as an archive (ZIP, TAR, etc.). The result can be imported on another machine using <a href='#store add'><code>0install store add</code></a>.</p>
<p><b>Usage:</b> <code>0install store export DIGEST OUTPUT-ARCHIVE [MIME-TYPE]</code></p>
<table>
<tr>
  <th>Option</th>
  <th>Description</th>
</tr>
<tr>
  <td><nobr><code>-?</code></nobr><br/><nobr><code>-h</code></nobr><br/><nobr><code>--help</code></nobr></td>
  <td>Show the built-in help text.</td>
</tr>
<tr>
  <td><nobr><code>--background</code></nobr><br>(Windows only)</td>
  <td>Hide the graphical user interface and use something like a tray icon instead. Has no effect in command-line mode.</td>
</tr>
<tr>
  <td><nobr><code>--batch</code></nobr><br>(Windows only)</td>
  <td>Automatically answer questions with defaults when possible. Avoid unnecessary console output (e.g. progress bars).</td>
</tr>
<tr>
  <td><nobr><code>-v</code></nobr><br/><nobr><code>--verbose</code></nobr></td>
  <td>More verbose output. Use twice for even more verbose output.</td>
</tr>
</table>
<a name='store_find'></a><h1>store find</h1>
<p>Determines the local path of a cached implementation.</p>
<p><b>Usage:</b> <code>0install store find DIGEST</code></p>
<table>
<tr>
  <th>Option</th>
  <th>Description</th>
</tr>
<tr>
  <td><nobr><code>-?</code></nobr><br/><nobr><code>-h</code></nobr><br/><nobr><code>--help</code></nobr></td>
  <td>Show the built-in help text.</td>
</tr>
<tr>
  <td><nobr><code>--background</code></nobr><br>(Windows only)</td>
  <td>Hide the graphical user interface and use something like a tray icon instead. Has no effect in command-line mode.</td>
</tr>
<tr>
  <td><nobr><code>--batch</code></nobr><br>(Windows only)</td>
  <td>Automatically answer questions with defaults when possible. Avoid unnecessary console output (e.g. progress bars).</td>
</tr>
<tr>
  <td><nobr><code>-v</code></nobr><br/><nobr><code>--verbose</code></nobr></td>
  <td>More verbose output. Use twice for even more verbose output.</td>
</tr>
</table>
<a name='store_list'></a><h1>store list</h1>
<p>Lists all implementation cache directories.</p>
<p><b>Usage:</b> <code>0install store list</code></p>
<table>
<tr>
  <th>Option</th>
  <th>Description</th>
</tr>
<tr>
  <td><nobr><code>-?</code></nobr><br/><nobr><code>-h</code></nobr><br/><nobr><code>--help</code></nobr></td>
  <td>Show the built-in help text.</td>
</tr>
<tr>
  <td><nobr><code>--background</code></nobr><br>(Windows only)</td>
  <td>Hide the graphical user interface and use something like a tray icon instead. Has no effect in command-line mode.</td>
</tr>
<tr>
  <td><nobr><code>--batch</code></nobr><br>(Windows only)</td>
  <td>Automatically answer questions with defaults when possible. Avoid unnecessary console output (e.g. progress bars).</td>
</tr>
<tr>
  <td><nobr><code>-v</code></nobr><br/><nobr><code>--verbose</code></nobr></td>
  <td>More verbose output. Use twice for even more verbose output.</td>
</tr>
</table>
<a name='store_list-implementations'></a><h1>store list-implementations</h1>
<p>Lists all cached implementations. If a feed URI is specified only implementations for that particular feed are listed.</p>
<p><b>Usage:</b> <code>0install store list-implementations [FEED-URI]</code></p>
<table>
<tr>
  <th>Option</th>
  <th>Description</th>
</tr>
<tr>
  <td><nobr><code>-?</code></nobr><br/><nobr><code>-h</code></nobr><br/><nobr><code>--help</code></nobr></td>
  <td>Show the built-in help text.</td>
</tr>
<tr>
  <td><nobr><code>--background</code></nobr><br>(Windows only)</td>
  <td>Hide the graphical user interface and use something like a tray icon instead. Has no effect in command-line mode.</td>
</tr>
<tr>
  <td><nobr><code>--batch</code></nobr><br>(Windows only)</td>
  <td>Automatically answer questions with defaults when possible. Avoid unnecessary console output (e.g. progress bars).</td>
</tr>
<tr>
  <td><nobr><code>-v</code></nobr><br/><nobr><code>--verbose</code></nobr></td>
  <td>More verbose output. Use twice for even more verbose output.</td>
</tr>
</table>
<a name='store_manage'></a><h1>store manage</h1>
<p>Displays a graphical user interface for managing implementations in the cache. Shows associations with cached feeds.</p>
<p><b>Usage:</b> <code>0install store manage</code></p>
<table>
<tr>
  <th>Option</th>
  <th>Description</th>
</tr>
<tr>
  <td><nobr><code>-?</code></nobr><br/><nobr><code>-h</code></nobr><br/><nobr><code>--help</code></nobr></td>
  <td>Show the built-in help text.</td>
</tr>
<tr>
  <td><nobr><code>--background</code></nobr><br>(Windows only)</td>
  <td>Hide the graphical user interface and use something like a tray icon instead. Has no effect in command-line mode.</td>
</tr>
<tr>
  <td><nobr><code>--batch</code></nobr><br>(Windows only)</td>
  <td>Automatically answer questions with defaults when possible. Avoid unnecessary console output (e.g. progress bars).</td>
</tr>
<tr>
  <td><nobr><code>-v</code></nobr><br/><nobr><code>--verbose</code></nobr></td>
  <td>More verbose output. Use twice for even more verbose output.</td>
</tr>
</table>
<a name='store_optimise'></a><h1>store optimise</h1>
<p>Saves disk space by merging identical files with hardlinks.
<br/>Additional arguments specify custom cache locations.</p>
<p><b>Usage:</b> <code>0install store optimise [CACHE-DIR+]</code></p>
<table>
<tr>
  <th>Option</th>
  <th>Description</th>
</tr>
<tr>
  <td><nobr><code>-?</code></nobr><br/><nobr><code>-h</code></nobr><br/><nobr><code>--help</code></nobr></td>
  <td>Show the built-in help text.</td>
</tr>
<tr>
  <td><nobr><code>--background</code></nobr><br>(Windows only)</td>
  <td>Hide the graphical user interface and use something like a tray icon instead. Has no effect in command-line mode.</td>
</tr>
<tr>
  <td><nobr><code>--batch</code></nobr><br>(Windows only)</td>
  <td>Automatically answer questions with defaults when possible. Avoid unnecessary console output (e.g. progress bars).</td>
</tr>
<tr>
  <td><nobr><code>-v</code></nobr><br/><nobr><code>--verbose</code></nobr></td>
  <td>More verbose output. Use twice for even more verbose output.</td>
</tr>
</table>
<a name='store_purge'></a><h1>store purge</h1>
<p>Removes all implementations from the cache. Use this command to clean up the system before removing Zero Install. Deleting cache directories manually may be difficult due to the NTFS ACLs employed to protect implementations against modification.
<br/>Additional arguments specify custom cache locations.</p>
<p><b>Usage:</b> <code>0install store purge [CACHE-DIR+]</code></p>
<table>
<tr>
  <th>Option</th>
  <th>Description</th>
</tr>
<tr>
  <td><nobr><code>-?</code></nobr><br/><nobr><code>-h</code></nobr><br/><nobr><code>--help</code></nobr></td>
  <td>Show the built-in help text.</td>
</tr>
<tr>
  <td><nobr><code>--background</code></nobr><br>(Windows only)</td>
  <td>Hide the graphical user interface and use something like a tray icon instead. Has no effect in command-line mode.</td>
</tr>
<tr>
  <td><nobr><code>--batch</code></nobr><br>(Windows only)</td>
  <td>Automatically answer questions with defaults when possible. Avoid unnecessary console output (e.g. progress bars).</td>
</tr>
<tr>
  <td><nobr><code>-v</code></nobr><br/><nobr><code>--verbose</code></nobr></td>
  <td>More verbose output. Use twice for even more verbose output.</td>
</tr>
</table>
<a name='store_remove'></a><h1>store remove</h1>
<p>Removes an implementation from the cache.</p>
<p><b>Usage:</b> <code>0install store remove DIGEST+</code></p>
<table>
<tr>
  <th>Option</th>
  <th>Description</th>
</tr>
<tr>
  <td><nobr><code>-?</code></nobr><br/><nobr><code>-h</code></nobr><br/><nobr><code>--help</code></nobr></td>
  <td>Show the built-in help text.</td>
</tr>
<tr>
  <td><nobr><code>--background</code></nobr><br>(Windows only)</td>
  <td>Hide the graphical user interface and use something like a tray icon instead. Has no effect in command-line mode.</td>
</tr>
<tr>
  <td><nobr><code>--batch</code></nobr><br>(Windows only)</td>
  <td>Automatically answer questions with defaults when possible. Avoid unnecessary console output (e.g. progress bars).</td>
</tr>
<tr>
  <td><nobr><code>-v</code></nobr><br/><nobr><code>--verbose</code></nobr></td>
  <td>More verbose output. Use twice for even more verbose output.</td>
</tr>
</table>
<a name='store_verify'></a><h1>store verify</h1>
<p>Makes sure an implementation has not been damaged (i.e. if manifest digest has not changed).</p>
<p><b>Usage:</b> <code>0install store verify [DIRECTORY] DIGEST</code></p>
<table>
<tr>
  <th>Option</th>
  <th>Description</th>
</tr>
<tr>
  <td><nobr><code>-?</code></nobr><br/><nobr><code>-h</code></nobr><br/><nobr><code>--help</code></nobr></td>
  <td>Show the built-in help text.</td>
</tr>
<tr>
  <td><nobr><code>--background</code></nobr><br>(Windows only)</td>
  <td>Hide the graphical user interface and use something like a tray icon instead. Has no effect in command-line mode.</td>
</tr>
<tr>
  <td><nobr><code>--batch</code></nobr><br>(Windows only)</td>
  <td>Automatically answer questions with defaults when possible. Avoid unnecessary console output (e.g. progress bars).</td>
</tr>
<tr>
  <td><nobr><code>-v</code></nobr><br/><nobr><code>--verbose</code></nobr></td>
  <td>More verbose output. Use twice for even more verbose output.</td>
</tr>
</table>
<a name='store_add-dir'></a><h1>store add-dir</h1>
<p>Adds a directory to the list of custom implementation caches.</p>
<p><b>Usage:</b> <code>0install store add-dir PATH</code></p>
<table>
<tr>
  <th>Option</th>
  <th>Description</th>
</tr>
<tr>
  <td><nobr><code>-?</code></nobr><br/><nobr><code>-h</code></nobr><br/><nobr><code>--help</code></nobr></td>
  <td>Show the built-in help text.</td>
</tr>
<tr>
  <td><nobr><code>--background</code></nobr><br>(Windows only)</td>
  <td>Hide the graphical user interface and use something like a tray icon instead. Has no effect in command-line mode.</td>
</tr>
<tr>
  <td><nobr><code>--batch</code></nobr><br>(Windows only)</td>
  <td>Automatically answer questions with defaults when possible. Avoid unnecessary console output (e.g. progress bars).</td>
</tr>
<tr>
  <td><nobr><code>-v</code></nobr><br/><nobr><code>--verbose</code></nobr></td>
  <td>More verbose output. Use twice for even more verbose output.</td>
</tr>
<tr>
  <td><nobr><code>-m</code></nobr><br/><nobr><code>--machine</code></nobr><br>(Windows only)</td>
  <td>Apply the configuration machine-wide (for the entire computer) instead of just for the current user.</td>
</tr>
</table>
<a name='store_remove-dir'></a><h1>store remove-dir</h1>
<p>Removes a directory from the list of custom implementation caches.</p>
<p><b>Usage:</b> <code>0install store remove-dir PATH</code></p>
<table>
<tr>
  <th>Option</th>
  <th>Description</th>
</tr>
<tr>
  <td><nobr><code>-?</code></nobr><br/><nobr><code>-h</code></nobr><br/><nobr><code>--help</code></nobr></td>
  <td>Show the built-in help text.</td>
</tr>
<tr>
  <td><nobr><code>--background</code></nobr><br>(Windows only)</td>
  <td>Hide the graphical user interface and use something like a tray icon instead. Has no effect in command-line mode.</td>
</tr>
<tr>
  <td><nobr><code>--batch</code></nobr><br>(Windows only)</td>
  <td>Automatically answer questions with defaults when possible. Avoid unnecessary console output (e.g. progress bars).</td>
</tr>
<tr>
  <td><nobr><code>-v</code></nobr><br/><nobr><code>--verbose</code></nobr></td>
  <td>More verbose output. Use twice for even more verbose output.</td>
</tr>
<tr>
  <td><nobr><code>-m</code></nobr><br/><nobr><code>--machine</code></nobr><br>(Windows only)</td>
  <td>Apply the configuration machine-wide (for the entire computer) instead of just for the current user.</td>
</tr>
</table>
<a name='central'></a><h1>central</h1>
<p>Opens the central graphical user interface for launching and managing applications.</p>
<p><b>Usage:</b> <code>0install central [OPTIONS]</code></p>
<table>
<tr>
  <th>Option</th>
  <th>Description</th>
</tr>
<tr>
  <td><nobr><code>-?</code></nobr><br/><nobr><code>-h</code></nobr><br/><nobr><code>--help</code></nobr></td>
  <td>Show the built-in help text.</td>
</tr>
<tr>
  <td><nobr><code>--background</code></nobr><br>(Windows only)</td>
  <td>Hide the graphical user interface and use something like a tray icon instead. Has no effect in command-line mode.</td>
</tr>
<tr>
  <td><nobr><code>--batch</code></nobr><br>(Windows only)</td>
  <td>Automatically answer questions with defaults when possible. Avoid unnecessary console output (e.g. progress bars).</td>
</tr>
<tr>
  <td><nobr><code>-v</code></nobr><br/><nobr><code>--verbose</code></nobr></td>
  <td>More verbose output. Use twice for even more verbose output.</td>
</tr>
<tr>
  <td><nobr><code>-m</code></nobr><br/><nobr><code>--machine</code></nobr><br>(Windows only)</td>
  <td>Apply the configuration machine-wide (for the entire computer) instead of just for the current user.</td>
</tr>
</table>
<a name='add'></a><h1>add</h1>
<p>Add an application to the application list.</p>
<p>
  <b>Usage Linux:</b> <code>0install add [OPTIONS] NAME URI</code><br>
  <b>Usage Windows:</b> <code>0install add [OPTIONS] [NAME] URI</code>
</p>
<table>
<tr>
  <th>Option</th>
  <th>Description</th>
</tr>
<tr>
  <td><nobr><code>-?</code></nobr><br/><nobr><code>-h</code></nobr><br/><nobr><code>--help</code></nobr></td>
  <td>Show the built-in help text.</td>
</tr>
<tr>
  <td><nobr><code>--background</code></nobr><br>(Windows only)</td>
  <td>Hide the graphical user interface and use something like a tray icon instead. Has no effect in command-line mode.</td>
</tr>
<tr>
  <td><nobr><code>--batch</code></nobr><br>(Windows only)</td>
  <td>Automatically answer questions with defaults when possible. Avoid unnecessary console output (e.g. progress bars).</td>
</tr>
<tr>
  <td><nobr><code>-v</code></nobr><br/><nobr><code>--verbose</code></nobr></td>
  <td>More verbose output. Use twice for even more verbose output.</td>
</tr>
<tr>
  <td><nobr><code>-o</code></nobr><br/><nobr><code>--offline</code></nobr></td>
  <td>Run in off-line mode, overriding the default setting.
<br/>In off-line mode, no interfaces are refreshed even if they are out-of-date, and newer versions of programs won't be downloaded even if the injector already knows about them (e.g. from a previous refresh).</td>
</tr>
<tr>
  <td><nobr><code>-r</code></nobr><br/><nobr><code>--refresh</code></nobr></td>
  <td>Fetch fresh copies of all used feeds.</td>
</tr>
<tr>
  <td><nobr><code>-m</code></nobr><br/><nobr><code>--machine</code></nobr><br>(Windows only)</td>
  <td>Apply the configuration machine-wide (for the entire computer) instead of just for the current user.</td>
</tr>
<tr>
  <td><nobr><code>--no-download</code></nobr><br>(Windows only)</td>
  <td>Do not download the application itself yet. Will be automatically downloaded on first use instead.</td>
</tr>
</table>
<a name='remove'></a><h1>remove</h1>
<p>Removes an application from the application list and undoes any desktop environment integration.</p>
<p><b>Usage:</b> <code>0install remove [OPTIONS] (ALIAS|INTERFACE)</code></p>
<table>
<tr>
  <th>Option</th>
  <th>Description</th>
</tr>
<tr>
  <td><nobr><code>-?</code></nobr><br/><nobr><code>-h</code></nobr><br/><nobr><code>--help</code></nobr></td>
  <td>Show the built-in help text.</td>
</tr>
<tr>
  <td><nobr><code>--background</code></nobr><br>(Windows only)</td>
  <td>Hide the graphical user interface and use something like a tray icon instead. Has no effect in command-line mode.</td>
</tr>
<tr>
  <td><nobr><code>--batch</code></nobr><br>(Windows only)</td>
  <td>Automatically answer questions with defaults when possible. Avoid unnecessary console output (e.g. progress bars).</td>
</tr>
<tr>
  <td><nobr><code>-v</code></nobr><br/><nobr><code>--verbose</code></nobr></td>
  <td>More verbose output. Use twice for even more verbose output.</td>
</tr>
<tr>
  <td><nobr><code>-o</code></nobr><br/><nobr><code>--offline</code></nobr></td>
  <td>Run in off-line mode, overriding the default setting.
<br/>In off-line mode, no interfaces are refreshed even if they are out-of-date, and newer versions of programs won't be downloaded even if the injector already knows about them (e.g. from a previous refresh).</td>
</tr>
<tr>
  <td><nobr><code>-r</code></nobr><br/><nobr><code>--refresh</code></nobr></td>
  <td>Fetch fresh copies of all used feeds.</td>
</tr>
<tr>
  <td><nobr><code>-m</code></nobr><br/><nobr><code>--machine</code></nobr><br>(Windows only)</td>
  <td>Apply the configuration machine-wide (for the entire computer) instead of just for the current user.</td>
</tr>
</table>
<a name='remove-all'></a><h1>remove-all</h1>
<p>Removes all applications from the application list and undoes any desktop environment integration. Use this command to clean up the system before removing Zero Install.</p>
<p><b>Usage:</b> <code>0install remove-all [OPTIONS]</code></p>
<table>
<tr>
  <th>Option</th>
  <th>Description</th>
</tr>
<tr>
  <td><nobr><code>-?</code></nobr><br/><nobr><code>-h</code></nobr><br/><nobr><code>--help</code></nobr></td>
  <td>Show the built-in help text.</td>
</tr>
<tr>
  <td><nobr><code>--background</code></nobr><br>(Windows only)</td>
  <td>Hide the graphical user interface and use something like a tray icon instead. Has no effect in command-line mode.</td>
</tr>
<tr>
  <td><nobr><code>--batch</code></nobr><br>(Windows only)</td>
  <td>Automatically answer questions with defaults when possible. Avoid unnecessary console output (e.g. progress bars).</td>
</tr>
<tr>
  <td><nobr><code>-v</code></nobr><br/><nobr><code>--verbose</code></nobr></td>
  <td>More verbose output. Use twice for even more verbose output.</td>
</tr>
<tr>
  <td><nobr><code>-o</code></nobr><br/><nobr><code>--offline</code></nobr></td>
  <td>Run in off-line mode, overriding the default setting.
<br/>In off-line mode, no interfaces are refreshed even if they are out-of-date, and newer versions of programs won't be downloaded even if the injector already knows about them (e.g. from a previous refresh).</td>
</tr>
<tr>
  <td><nobr><code>-r</code></nobr><br/><nobr><code>--refresh</code></nobr></td>
  <td>Fetch fresh copies of all used feeds.</td>
</tr>
<tr>
  <td><nobr><code>-m</code></nobr><br/><nobr><code>--machine</code></nobr><br>(Windows only)</td>
  <td>Apply the configuration machine-wide (for the entire computer) instead of just for the current user.</td>
</tr>
</table>
<a name='integrate'></a><h1>integrate</h1>
<p>Adds an application to the application list (if missing) and integrate it into the desktop environment.</p>
<p><b>Usage:</b> <code>0install integrate [OPTIONS] (ALIAS|INTERFACE)</code></p>
<table>
<tr>
  <th>Option</th>
  <th>Description</th>
</tr>
<tr>
  <td><nobr><code>-?</code></nobr><br/><nobr><code>-h</code></nobr><br/><nobr><code>--help</code></nobr></td>
  <td>Show the built-in help text.</td>
</tr>
<tr>
  <td><nobr><code>--background</code></nobr><br>(Windows only)</td>
  <td>Hide the graphical user interface and use something like a tray icon instead. Has no effect in command-line mode.</td>
</tr>
<tr>
  <td><nobr><code>--batch</code></nobr><br>(Windows only)</td>
  <td>Automatically answer questions with defaults when possible. Avoid unnecessary console output (e.g. progress bars).</td>
</tr>
<tr>
  <td><nobr><code>-v</code></nobr><br/><nobr><code>--verbose</code></nobr></td>
  <td>More verbose output. Use twice for even more verbose output.</td>
</tr>
<tr>
  <td><nobr><code>-o</code></nobr><br/><nobr><code>--offline</code></nobr></td>
  <td>Run in off-line mode, overriding the default setting.
<br/>In off-line mode, no interfaces are refreshed even if they are out-of-date, and newer versions of programs won't be downloaded even if the injector already knows about them (e.g. from a previous refresh).</td>
</tr>
<tr>
  <td><nobr><code>-r</code></nobr><br/><nobr><code>--refresh</code></nobr></td>
  <td>Fetch fresh copies of all used feeds.</td>
</tr>
<tr>
  <td><nobr><code>-m</code></nobr><br/><nobr><code>--machine</code></nobr><br>(Windows only)</td>
  <td>Apply the configuration machine-wide (for the entire computer) instead of just for the current user.</td>
</tr>
<tr>
  <td><nobr><code>--no-download</code></nobr><br>(Windows only)</td>
  <td>Do not download the application itself yet. Will be automatically downloaded on first use instead.</td>
</tr>
<tr>
  <td><nobr><code>--add-standard</code></nobr></td>
  <td>Add the recommended standard access points.</td>
</tr>
<tr>
  <td><nobr><code>--add-all</code></nobr></td>
  <td>Add all available access points.</td>
</tr>
<tr>
  <td><nobr><code>--add <code>CATEGORY</code></code></nobr></td>
  <td>Add all access points of a specific <code>CATEGORY</code>.
<br/>Supported values: <code>capabilities</code>, <code>menu</code>, <code>desktop</code>, <code>send-to</code>, <code>aliases</code>, <code>auto-start</code>, <code>default-app</code></td>
</tr>
<tr>
  <td><nobr><code>--remove-all</code></nobr></td>
  <td>Remove all access points.</td>
</tr>
<tr>
  <td><nobr><code>--remove <code>CATEGORY</code></code></nobr></td>
  <td>Remove all access points of a specific <code>CATEGORY</code>.
<br/>Supported values: <code>capabilities</code>, <code>menu</code>, <code>desktop</code>, <code>send-to</code>, <code>aliases</code>, <code>auto-start</code>, <code>default-app</code></td>
</tr>
</table>
<a name='alias'></a><h1>alias</h1>
<p>Create an alias for launching an application via 0install without always having to enter the full URI.</p>
<p><b>Usage:</b> <code>0install alias ALIAS [INTERFACE [COMMAND]]</code></p>
<table>
<tr>
  <th>Option</th>
  <th>Description</th>
</tr>
<tr>
  <td><nobr><code>-?</code></nobr><br/><nobr><code>-h</code></nobr><br/><nobr><code>--help</code></nobr></td>
  <td>Show the built-in help text.</td>
</tr>
<tr>
  <td><nobr><code>--background</code></nobr><br>(Windows only)</td>
  <td>Hide the graphical user interface and use something like a tray icon instead. Has no effect in command-line mode.</td>
</tr>
<tr>
  <td><nobr><code>--batch</code></nobr><br>(Windows only)</td>
  <td>Automatically answer questions with defaults when possible. Avoid unnecessary console output (e.g. progress bars).</td>
</tr>
<tr>
  <td><nobr><code>-v</code></nobr><br/><nobr><code>--verbose</code></nobr></td>
  <td>More verbose output. Use twice for even more verbose output.</td>
</tr>
<tr>
  <td><nobr><code>-o</code></nobr><br/><nobr><code>--offline</code></nobr></td>
  <td>Run in off-line mode, overriding the default setting.
<br/>In off-line mode, no interfaces are refreshed even if they are out-of-date, and newer versions of programs won't be downloaded even if the injector already knows about them (e.g. from a previous refresh).</td>
</tr>
<tr>
  <td><nobr><code>-r</code></nobr><br/><nobr><code>--refresh</code></nobr></td>
  <td>Fetch fresh copies of all used feeds.</td>
</tr>
<tr>
  <td><nobr><code>-m</code></nobr><br/><nobr><code>--machine</code></nobr><br>(Windows only)</td>
  <td>Apply the configuration machine-wide (for the entire computer) instead of just for the current user.</td>
</tr>
<tr>
  <td><nobr><code>--no-download</code></nobr><br>(Windows only)</td>
  <td>Do not download the application itself yet. Will be automatically downloaded on first use instead.</td>
</tr>
<tr>
  <td><nobr><code>--resolve</code></nobr></td>
  <td>Print the interface URI for the given alias.</td>
</tr>
<tr>
  <td><nobr><code>--remove</code></nobr></td>
  <td>Remove an existing alias.</td>
</tr>
</table>
<a name='list-apps'></a><h1>list-apps</h1>
<p>Lists all applications currently in your application list. If a search term is given, only application names containing that string are shown (case insensitive).</p>
<p><b>Usage:</b> <code>0install list-apps [PATTERN]</code></p>
<table>
<tr>
  <th>Option</th>
  <th>Description</th>
</tr>
<tr>
  <td><nobr><code>-?</code></nobr><br/><nobr><code>-h</code></nobr><br/><nobr><code>--help</code></nobr></td>
  <td>Show the built-in help text.</td>
</tr>
<tr>
  <td><nobr><code>--background</code></nobr><br>(Windows only)</td>
  <td>Hide the graphical user interface and use something like a tray icon instead. Has no effect in command-line mode.</td>
</tr>
<tr>
  <td><nobr><code>--batch</code></nobr><br>(Windows only)</td>
  <td>Automatically answer questions with defaults when possible. Avoid unnecessary console output (e.g. progress bars).</td>
</tr>
<tr>
  <td><nobr><code>-v</code></nobr><br/><nobr><code>--verbose</code></nobr></td>
  <td>More verbose output. Use twice for even more verbose output.</td>
</tr>
<tr>
  <td><nobr><code>-o</code></nobr><br/><nobr><code>--offline</code></nobr></td>
  <td>Run in off-line mode, overriding the default setting.
<br/>In off-line mode, no interfaces are refreshed even if they are out-of-date, and newer versions of programs won't be downloaded even if the injector already knows about them (e.g. from a previous refresh).</td>
</tr>
<tr>
  <td><nobr><code>-r</code></nobr><br/><nobr><code>--refresh</code></nobr></td>
  <td>Fetch fresh copies of all used feeds.</td>
</tr>
<tr>
  <td><nobr><code>-m</code></nobr><br/><nobr><code>--machine</code></nobr><br>(Windows only)</td>
  <td>Apply the configuration machine-wide (for the entire computer) instead of just for the current user.</td>
</tr>
</table>
<a name='update-all'></a><h1>update-all</h1>
<p>Updates all applications in the application list.</p>
<p><b>Usage:</b> <code>0install update-all [OPTIONS]</code></p>
<table>
<tr>
  <th>Option</th>
  <th>Description</th>
</tr>
<tr>
  <td><nobr><code>-?</code></nobr><br/><nobr><code>-h</code></nobr><br/><nobr><code>--help</code></nobr></td>
  <td>Show the built-in help text.</td>
</tr>
<tr>
  <td><nobr><code>--background</code></nobr><br>(Windows only)</td>
  <td>Hide the graphical user interface and use something like a tray icon instead. Has no effect in command-line mode.</td>
</tr>
<tr>
  <td><nobr><code>--batch</code></nobr><br>(Windows only)</td>
  <td>Automatically answer questions with defaults when possible. Avoid unnecessary console output (e.g. progress bars).</td>
</tr>
<tr>
  <td><nobr><code>-v</code></nobr><br/><nobr><code>--verbose</code></nobr></td>
  <td>More verbose output. Use twice for even more verbose output.</td>
</tr>
<tr>
  <td><nobr><code>-o</code></nobr><br/><nobr><code>--offline</code></nobr></td>
  <td>Run in off-line mode, overriding the default setting.
<br/>In off-line mode, no interfaces are refreshed even if they are out-of-date, and newer versions of programs won't be downloaded even if the injector already knows about them (e.g. from a previous refresh).</td>
</tr>
<tr>
  <td><nobr><code>-r</code></nobr><br/><nobr><code>--refresh</code></nobr></td>
  <td>Fetch fresh copies of all used feeds.</td>
</tr>
<tr>
  <td><nobr><code>-m</code></nobr><br/><nobr><code>--machine</code></nobr><br>(Windows only)</td>
  <td>Apply the configuration machine-wide (for the entire computer) instead of just for the current user.</td>
</tr>
<tr>
  <td><nobr><code>-c</code></nobr><br/><nobr><code>--clean</code></nobr></td>
  <td>Remove implementations no longer required after the update.</td>
</tr>
</table>
<a name='repair-all'></a><h1>repair-all</h1>
<p>Reapplies all desktop integrations of applications in the application list.</p>
<p><b>Usage:</b> <code>0install repair-all [OPTIONS]</code></p>
<table>
<tr>
  <th>Option</th>
  <th>Description</th>
</tr>
<tr>
  <td><nobr><code>-?</code></nobr><br/><nobr><code>-h</code></nobr><br/><nobr><code>--help</code></nobr></td>
  <td>Show the built-in help text.</td>
</tr>
<tr>
  <td><nobr><code>--background</code></nobr><br>(Windows only)</td>
  <td>Hide the graphical user interface and use something like a tray icon instead. Has no effect in command-line mode.</td>
</tr>
<tr>
  <td><nobr><code>--batch</code></nobr><br>(Windows only)</td>
  <td>Automatically answer questions with defaults when possible. Avoid unnecessary console output (e.g. progress bars).</td>
</tr>
<tr>
  <td><nobr><code>-v</code></nobr><br/><nobr><code>--verbose</code></nobr></td>
  <td>More verbose output. Use twice for even more verbose output.</td>
</tr>
<tr>
  <td><nobr><code>-o</code></nobr><br/><nobr><code>--offline</code></nobr></td>
  <td>Run in off-line mode, overriding the default setting.
<br/>In off-line mode, no interfaces are refreshed even if they are out-of-date, and newer versions of programs won't be downloaded even if the injector already knows about them (e.g. from a previous refresh).</td>
</tr>
<tr>
  <td><nobr><code>-r</code></nobr><br/><nobr><code>--refresh</code></nobr></td>
  <td>Fetch fresh copies of all used feeds.</td>
</tr>
<tr>
  <td><nobr><code>-m</code></nobr><br/><nobr><code>--machine</code></nobr><br>(Windows only)</td>
  <td>Apply the configuration machine-wide (for the entire computer) instead of just for the current user.</td>
</tr>
</table>
<a name='sync'></a><h1>sync</h1>
<p>Synchronizes the application list with the server.</p>
<p><b>Usage:</b> <code>0install sync [OPTIONS]</code></p>
<table>
<tr>
  <th>Option</th>
  <th>Description</th>
</tr>
<tr>
  <td><nobr><code>-?</code></nobr><br/><nobr><code>-h</code></nobr><br/><nobr><code>--help</code></nobr></td>
  <td>Show the built-in help text.</td>
</tr>
<tr>
  <td><nobr><code>--background</code></nobr><br>(Windows only)</td>
  <td>Hide the graphical user interface and use something like a tray icon instead. Has no effect in command-line mode.</td>
</tr>
<tr>
  <td><nobr><code>--batch</code></nobr><br>(Windows only)</td>
  <td>Automatically answer questions with defaults when possible. Avoid unnecessary console output (e.g. progress bars).</td>
</tr>
<tr>
  <td><nobr><code>-v</code></nobr><br/><nobr><code>--verbose</code></nobr></td>
  <td>More verbose output. Use twice for even more verbose output.</td>
</tr>
<tr>
  <td><nobr><code>-o</code></nobr><br/><nobr><code>--offline</code></nobr></td>
  <td>Run in off-line mode, overriding the default setting.
<br/>In off-line mode, no interfaces are refreshed even if they are out-of-date, and newer versions of programs won't be downloaded even if the injector already knows about them (e.g. from a previous refresh).</td>
</tr>
<tr>
  <td><nobr><code>-r</code></nobr><br/><nobr><code>--refresh</code></nobr></td>
  <td>Fetch fresh copies of all used feeds.</td>
</tr>
<tr>
  <td><nobr><code>-m</code></nobr><br/><nobr><code>--machine</code></nobr><br>(Windows only)</td>
  <td>Apply the configuration machine-wide (for the entire computer) instead of just for the current user.</td>
</tr>
<tr>
  <td><nobr><code>--reset <code>MODE</code></code></nobr></td>
  <td>Reset the synchronization data.
<br/>Supported values: <code>none</code> (merge data from client and server normally), <code>client</code> (replace all data on client with data from server) and <code>server</code> (replace all data on server with data from client).</td>
</tr>
</table>
<a name='import-apps'></a><h1>import-apps</h1>
<p>Imports a set of applications and desktop integrations from an existing app-list.xml file.</p>
<p><b>Usage:</b> <code>0install import-apps APP-LIST-FILE [OPTIONS]</code></p>
<table>
<tr>
  <th>Option</th>
  <th>Description</th>
</tr>
<tr>
  <td><nobr><code>-?</code></nobr><br/><nobr><code>-h</code></nobr><br/><nobr><code>--help</code></nobr></td>
  <td>Show the built-in help text.</td>
</tr>
<tr>
  <td><nobr><code>--background</code></nobr><br>(Windows only)</td>
  <td>Hide the graphical user interface and use something like a tray icon instead. Has no effect in command-line mode.</td>
</tr>
<tr>
  <td><nobr><code>--batch</code></nobr><br>(Windows only)</td>
  <td>Automatically answer questions with defaults when possible. Avoid unnecessary console output (e.g. progress bars).</td>
</tr>
<tr>
  <td><nobr><code>-v</code></nobr><br/><nobr><code>--verbose</code></nobr></td>
  <td>More verbose output. Use twice for even more verbose output.</td>
</tr>
<tr>
  <td><nobr><code>-o</code></nobr><br/><nobr><code>--offline</code></nobr></td>
  <td>Run in off-line mode, overriding the default setting.
<br/>In off-line mode, no interfaces are refreshed even if they are out-of-date, and newer versions of programs won't be downloaded even if the injector already knows about them (e.g. from a previous refresh).</td>
</tr>
<tr>
  <td><nobr><code>-r</code></nobr><br/><nobr><code>--refresh</code></nobr></td>
  <td>Fetch fresh copies of all used feeds.</td>
</tr>
<tr>
  <td><nobr><code>-m</code></nobr><br/><nobr><code>--machine</code></nobr><br>(Windows only)</td>
  <td>Apply the configuration machine-wide (for the entire computer) instead of just for the current user.</td>
</tr>
<tr>
  <td><nobr><code>--no-download</code></nobr><br>(Windows only)</td>
  <td>Do not download the application itself yet. Will be automatically downloaded on first use instead.</td>
</tr>
</table>
<a name='self_deploy'></a><h1>self deploy</h1>
<p>Deploys Zero Install to the specified <code>TARGET</code> directory or the default directory for programs and integrates it in the system.</p>
<p><b>Usage:</b> <code>0install self deploy [TARGET]</code></p>
<table>
<tr>
  <th>Option</th>
  <th>Description</th>
</tr>
<tr>
  <td><nobr><code>-?</code></nobr><br/><nobr><code>-h</code></nobr><br/><nobr><code>--help</code></nobr></td>
  <td>Show the built-in help text.</td>
</tr>
<tr>
  <td><nobr><code>--background</code></nobr><br>(Windows only)</td>
  <td>Hide the graphical user interface and use something like a tray icon instead. Has no effect in command-line mode.</td>
</tr>
<tr>
  <td><nobr><code>--batch</code></nobr><br>(Windows only)</td>
  <td>Automatically answer questions with defaults when possible. Avoid unnecessary console output (e.g. progress bars).</td>
</tr>
<tr>
  <td><nobr><code>-v</code></nobr><br/><nobr><code>--verbose</code></nobr></td>
  <td>More verbose output. Use twice for even more verbose output.</td>
</tr>
<tr>
  <td><nobr><code>-m</code></nobr><br/><nobr><code>--machine</code></nobr><br>(Windows only)</td>
  <td>Apply the configuration machine-wide (for the entire computer) instead of just for the current user.</td>
</tr>
<tr>
  <td><nobr><code>-p</code></nobr><br/><nobr><code>--portable</code></nobr></td>
  <td>Create a portable installation that can be moved around (e.g., on a thumb drive).</td>
</tr>
<tr>
  <td><nobr><code>--restart-central</code></nobr></td>
  <td>Restart the <a href='#central'><code>0install central</code></a> GUI after the update.</td>
</tr>
</table>
<a name='self_remove'></a><h1>self remove</h1>
<p>Removes the current instance of Zero Install from the system.</p>
<p><b>Usage:</b> <code>0install self remove</code></p>
<table>
<tr>
  <th>Option</th>
  <th>Description</th>
</tr>
<tr>
  <td><nobr><code>-?</code></nobr><br/><nobr><code>-h</code></nobr><br/><nobr><code>--help</code></nobr></td>
  <td>Show the built-in help text.</td>
</tr>
<tr>
  <td><nobr><code>--background</code></nobr><br>(Windows only)</td>
  <td>Hide the graphical user interface and use something like a tray icon instead. Has no effect in command-line mode.</td>
</tr>
<tr>
  <td><nobr><code>--batch</code></nobr><br>(Windows only)</td>
  <td>Automatically answer questions with defaults when possible. Avoid unnecessary console output (e.g. progress bars).</td>
</tr>
<tr>
  <td><nobr><code>-v</code></nobr><br/><nobr><code>--verbose</code></nobr></td>
  <td>More verbose output. Use twice for even more verbose output.</td>
</tr>
</table>
<a name='self_update'></a><h1>self update</h1>
<p>Updates Zero Install itself to the most recent version.</p>
<p><b>Usage:</b> <code>0install self update [OPTIONS]</code></p>
<table>
<tr>
  <th>Option</th>
  <th>Description</th>
</tr>
<tr>
  <td><nobr><code>-?</code></nobr><br/><nobr><code>-h</code></nobr><br/><nobr><code>--help</code></nobr></td>
  <td>Show the built-in help text.</td>
</tr>
<tr>
  <td><nobr><code>--background</code></nobr><br>(Windows only)</td>
  <td>Hide the graphical user interface and use something like a tray icon instead. Has no effect in command-line mode.</td>
</tr>
<tr>
  <td><nobr><code>--batch</code></nobr><br>(Windows only)</td>
  <td>Automatically answer questions with defaults when possible. Avoid unnecessary console output (e.g. progress bars).</td>
</tr>
<tr>
  <td><nobr><code>-v</code></nobr><br/><nobr><code>--verbose</code></nobr></td>
  <td>More verbose output. Use twice for even more verbose output.</td>
</tr>
<tr>
  <td><nobr><code>--customize</code></nobr><br>(Windows only)</td>
  <td>Show the graphical policy editor. This allows you to customize which version of a program or library to use.</td>
</tr>
<tr>
  <td><nobr><code>-o</code></nobr><br/><nobr><code>--offline</code></nobr></td>
  <td>Run in off-line mode, overriding the default setting.
<br/>In off-line mode, no interfaces are refreshed even if they are out-of-date, and newer versions of programs won't be downloaded even if the injector already knows about them (e.g. from a previous refresh).</td>
</tr>
<tr>
  <td><nobr><code>-r</code></nobr><br/><nobr><code>--refresh</code></nobr></td>
  <td>Fetch fresh copies of all used feeds.</td>
</tr>
<tr>
  <td><nobr><code>--with-store <code>DIR</code></code></nobr></td>
  <td>Add <code>DIR</code> to the list of implementation caches to search.
<br/>However, new downloads will not be written to this directory.</td>
</tr>
<tr>
  <td><nobr><code>--command <code>COMMAND</code></code></nobr></td>
  <td>Instead of executing the default command, use <code>COMMAND</code> instead. Possible command names are defined in the program's interface.
<br/>Set to empty (<code>&quot;&quot;</code>) to ignore the command during selection.</td>
</tr>
<tr>
  <td><nobr><code>--before <code>VERSION</code></code></nobr></td>
  <td>Require a version of the main program earlier than <code>VERSION</code>. i.e., force the use of an old version the program.</td>
</tr>
<tr>
  <td><nobr><code>--not-before <code>VERSION</code></code></nobr></td>
  <td>Require a version of the main program not earlier than <code>VERSION</code>. E.g., if you want to run version 2.0 or later, use <code>--not-before=2.0</code>.</td>
</tr>
<tr>
  <td><nobr><code>--version <code>RANGE</code></code></nobr></td>
  <td>Require a version of the main program within the given <code>RANGE</code>.
<br/>Ranges are separated by pipes (<code>|</code>).
<br/>Each range is in the form <code>START..!END</code>. The range matches versions where <code>START</code> &lt;= <code>VERSION</code> &lt; <code>END</code>. The start or end may be omitted.
<br/>A single version number may be used instead of a range to match only that version, or <code>!VERSION</code> to match everything except that version.</td>
</tr>
<tr>
  <td><nobr><code>--version-for <code>URI</code> <code>RANGE</code></code></nobr></td>
  <td>For any library or sub-component with the given <code>URI</code> specifies the version <code>RANGE</code> (as for <code>--version</code>).</td>
</tr>
<tr>
  <td><nobr><code>-s</code></nobr><br/><nobr><code>--source</code></nobr></td>
  <td>Select source code rather than a binary. This is used internally by 0compile.</td>
</tr>
<tr>
  <td><nobr><code>--os <code>OS</code></code></nobr></td>
  <td>Forces the solver to target the operating system <code>OS</code>.
<br/>Supported values: <code>\*</code>, <code>POSIX</code>, <code>Linux</code>, <code>Solaris</code>, <code>FreeBSD</code>, <code>Darwin</code>, <code>MacOSX</code>, <code>Cygwin</code>, <code>Windows</code>, <code>unknown</code></td>
</tr>
<tr>
  <td><nobr><code>--cpu <code>CPU</code></code></nobr></td>
  <td>Forces the solver to target a specific <code>CPU</code>.
<br/>Supported values: <code>\*</code>, <code>i386</code>, <code>i486</code>, <code>i586</code>, <code>i686</code>, <code>x86_64</code>, <code>ppc</code>, <code>ppc64</code>, <code>armv6l</code>, <code>armv7l</code>, <code>src</code></td>
</tr>
<tr>
  <td><nobr><code>--language</code></nobr></td>
  <td>Specifies the preferred language for the implementation. Use ISO short language codes (e.g. <code>en</code> or <code>en-US</code>).
<br/>You can use this option multiple times to specify multiple acceptable languages.</td>
</tr>
<tr>
  <td><nobr><code>--xml</code></nobr></td>
  <td>Write selected versions to console as machine-readable XML.</td>
</tr>
<tr>
  <td><nobr><code>--show</code></nobr></td>
  <td>Show where the selected components are stored on the disk.</td>
</tr>
<tr>
  <td><nobr><code>-m <code>MAIN</code></code></nobr><br/><nobr><code>--main <code>MAIN</code></code></nobr></td>
  <td>Run the specified executable <code>MAIN</code> instead of the default. If it starts with <code>/</code> or <code>\</code> then the path is relative to the implementation's top-level directory, whereas otherwise it is relative to the directory containing the default main program.
<br/>May not contain command-line arguments! Whitespaces do not need to be escaped.</td>
</tr>
<tr>
  <td><nobr><code>-w <code>COMMAND</code></code></nobr><br/><nobr><code>--wrapper <code>COMMAND</code></code></nobr></td>
  <td>Instead of executing the chosen program directly, run <code>COMMAND</code> <code>PROGRAM</code> <code>ARGS</code>. This is useful for running debuggers and tracing tools on the program (rather than on Zero Install!).
<br/>Note that the wrapper is executed in the environment selected by the program; hence, this mechanism cannot be used for sandboxing.
<br/>May contain command-line arguments. Whitespaces must be escaped!</td>
</tr>
<tr>
  <td><nobr><code>--no-wait</code></nobr></td>
  <td>Immediately returns once the chosen program has been launched instead of waiting for it to finish executing. On Windows the exit code is the process ID of the launched program.</td>
</tr>
<tr>
  <td><nobr><code>--force</code></nobr></td>
  <td>Perform the update even if the currently installed version is the same or newer.</td>
</tr>
<tr>
  <td><nobr><code>--restart-central</code></nobr></td>
  <td>Restart the <a href='#central'><code>0install central</code></a> GUI after the update.</td>
</tr>
</table>
