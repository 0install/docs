<?xml version='1.0' encoding='utf-8'?>
<html>

<h2>The 0install JSON API</h2>

<p>
  Starting with 0install 2.6, you can connect your programs to 0install via a JSON API. This is useful for writing 0install-based tools in other languages. Currently, the JSON API is only used by the C#/.NET GUI on Windows. Let us know if you want to expose other features this way.
</p>

<toc level='h2'/>

<h2>Introduction</h2>

<p>
  To use the JSON API, run <b>0install slave API-VERSION</b> (where <b>API-VERSION</b> is the latest protocol version you support), like this:
</p>

<pre-scrolled>$ <b>0install slave 2.7</b>
0x00000031
["invoke",null,"set-api-version",["2.6.2-post"]]
</pre-scrolled>

<p>
  0install responds first with a line indicating the length in octets of the JSON message to follow. All length headers sent by 0install are 11 octets long, including the leading "0x" and the trailing newline. After this comes the JSON message, giving the API version it will use. This will be the version you specified or the latest version it supports, whichever is smaller.
</p>

<p>
  To invoke an operation, send the message <b>["invoke", myref, op, args]</b>, where <b>myref</b> is any string you choose, which will be attached to the reply, <b>op</b> is the operation to invoke and <b>args</b> are the arguments. Each message must start with a line giving the length in octets of the following message. For example, to ask 0install to select a set of components to run GnuPG:
</p>

<pre-scrolled>
0x5d
["invoke", "1", "select", [{"interface": "http://repo.roscidus.com/security/gnupg"}, false]]
</pre-scrolled>

<p>
  All responses have the form <b>["return", myref, status, return-value]</b>, where <b>myref</b> lets you correlate this reply with the request you sent. The possible responses are <b>["ok", return-value]</b>, <b>["ok+xml", return-value]</b> and <b>["fail", error-message]</b>. In the case of <b>ok+xml</b>, the message is immediately followed by another length line and some XML. The above request might generate this response, which says to use the distribution's native version of GnuPG (2.0.22-2):
</p>

<pre-scrolled>
0x0000002f
["return","1","ok+xml",["ok",{"stale":false}]]
0x000001f5
&lt;?xml version="1.0" encoding="UTF-8"?>
&lt;selections interface="http://repo.roscidus.com/security/gnupg" xmlns="http://zero-install.sourceforge.net/2004/injector/interface">&lt;selection distributions="Arch Slack Debian RPM Gentoo Cygwin" from-feed="distribution:http://repo.roscidus.com/security/gnupg" id="package:arch:gnupg:2.0.22-2:x86_64" interface="http://repo.roscidus.com/security/gnupg" package="gnupg" quick-test-file="/var/lib/pacman/local/gnupg-2.0.22-2/desc" version="2.0.22-2"/>&lt;/selections>
</pre-scrolled>

<h2>Callbacks</h2>

<p>
In the process of handling your request, 0install may send its own invoke messages to you. You should be prepared to handle these messages:
</p>

<dl>
  <dt>set-api-version(version)</dt>
  <dd>
    This is sent immediately at the start, indicating the protocol version to be used. The "myref" field is "null", indicating that no reply is expected. The version may be earlier than the version you requested, in which case you can either fall back to the earlier version or tell the user to upgrade.
  </dd>

  <dt>confirm(message)</dt>
  <dd>If 0install needs to ask the user to confirm something, it sends this message. This is used to confirm installation of distribution-provided packages, if any. Respond with success ("ok") and a return value of "ok" or "cancel". This is currently not used, because "select" does not require it.
  </dd>

  <dt>confirm-keys(feed_url, keys)</dt>
  <dd>
    <p>A feed has been downloaded but has no trusted signature. Prompt the user to accept the keys. <b>keys</b> is a list of <b>(fingerprint, hints)</b> pairs, where <b>hints</b> is a list of <b>(vote, message)</b> hints from the key information server. Each vote is "good" or "bad". You should respond with a list of fingerprints which 0install should trust to sign updates for this domain. 0install will only ask you to confirm one feed at a time. Example:</p>
    <pre-scrolled>
["invoke","1","confirm-keys",[
  "http://repo.roscidus.com/lib/readline6",{
    "DA9825AECAD089757CDABD8E07133F96CA74D8BA":[
      ["good","Thomas Leonard created Zero Install and ROX.
      This key is used to sign updates to the injector; you should accept it.
      It was announced on the Zero Install mailing list on 2009-05-31."]]
    }]]</pre-scrolled>

  <p>If the server is slow to respond, you will get a "pending" message instead, followed by a call to "update-key-info" later:</p>

  <pre-scrolled>
["invoke","1","confirm-keys",[
  "http://repo.roscidus.com/lib/readline6",{
    "DA9825AECAD089757CDABD8E07133F96CA74D8BA":["pending"]}]]
</pre-scrolled>
  </dd>

  <dt>update-key-info</dt>
  <dd><p>If the key information server is slow to respond, 0install may call <b>confirm-keys</b> before the hints have arrived. It will then send this message when the information arrives (or fails), so you can update the display. Note: the format here is a list of keys rather than a map. Example:</p>
    <pre-scrolled>
["invoke","2","update-key-info",
  ["DA9825AECAD089757CDABD8E07133F96CA74D8BA",[
    ["bad","Error fetching key info: ..."]]]]</pre-scrolled>
  </dd>
</dl>

<p>
  If 0install is able to use its own GUI, it will use that rather than these callbacks. Start it with <b>--console</b> if you want to use your own GUI in all cases.
</p>

<h2>Operations</h2>

<p>
  Currently, only the "select" operation is supported (let us know if you want more; they're easy to add):
</p>

<dt>
  <dt>select(requirements, refresh)</dt>
  <dd>
    <p>Return a set of selections to run the given program. If <b>refresh</b> is <b>true</b>, 0install will always try to download a fresh copy of the feeds. Otherwise, it will reply immediately if possible. <b>requirements</b> is a JSON object with these keys (only <b>interface</b> is required):</p>
    <dl>
      <dt>interface</dt><dd>The URI of the program to run.</dd>
      <dt>command</dt><dd>The &lt;command&gt; to run (e.g. "run", "test", "compile" or null).</dd>
      <dt>source</dt><dd>Whether to select source code (and build dependencies) rather than a binary.</dd>
      <dt>extra_restrictions</dt><dd>An object mapping interface URIs to version expressions, e.g. <b>{"http://repo.roscidus.com/python/python": "..!3"}</b> to require a version of Python less than 3.</dd>
      <dt>os</dt><dd>Select implementations for the given OS (e.g. "Linux")</dd>
      <dt>cpu</dt><dd>Select implementations for the given CPU (e.g. "x86_64" or "src")</dd>
      <dt>message</dt><dd>A message to display if 0install uses its own GUI ("I need this because ...")</dd>
      <dt>may_compile</dt><dd>(0install >= 2.9; default False) Treat source implementations as potential binaries. If a source implementation is selected, it will be tagged with <b>requires-compilation="true"</b> to indicate this.</dd>
    </dl>
    <p>
      Returns <b>["ok",{"stale":stale-flag}]</b> on success. If stale-flag is true, the selections are based on old information. Consider using <b>refresh</b> to check for updates.
    </p>
  </dd>
</dt>

<h2>Sample code</h2>

<p>
  There is some <a href='https://github.com/0install/0install/blob/master/ocaml/sample_client.py'>sample Python client code</a> available.
</p>

<h2>Backwards compatibility</h2>

<p>
  The text above documents the latest version of the protocol. The differences are:
</p>

<dl>
  <dt>2.6</dt>
  <dd>In this version, <b>select</b> returns <b>"ok"</b> without the information about staleness.</dd>
</dl>

</html>
