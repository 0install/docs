# Capabilities extension

This document is a formal description of the Capabilities extension of the [feeds format](feed.md). A capability tells the desktop environment what an application can do (e.g., suported file types) and in which fashion this can be represented to the user. This is used for [desktop integration](../details/desktop-integration.md) (currently only supported on Windows).

[:fontawesome-regular-file-code: XML Schema Definition](capabilities.xsd)

## Syntax

Capability extensions for feeds have following syntax (`?` follows optional items, `*` means zero-or-more, order of elements is not important):

```xml
<?xml version='1.0'?>
<interface xmlns='http://zero-install.sourceforge.net/2004/injector/interface' uri='...'>
  ...

  <capabilities xmlns='http://0install.de/schema/desktop-integration/capabilities' os='...' ?>
    [capability] *
  </capabilities> *
</interface>
```

`os`
: Specifies for which operating system the capabilities are applicable (e.g., `os="Windows"` for Windows systems).

## Capability types

### File types

An application's ability to open certain file types. 

```xml
<file-type id='...' explicit-only='true' ?>
  <extension value='...' mime-type='...' ? perceived-type='...' ?/> *
  <description xml:lang='...' ?>...</description> *
  [verb] *
  [icon] *
</file-type>
```

`id`
: An ID that uniquely identifies this file type within the feed. Must be a [safe ID](#safe-id).

`explicit-only`
: When set to `true` the app is not set as the default handler for this file type without explicit confirmation from the user.  
  Use this to exclude exotic capabilities from default integration categories.

`<extension>`
: A file extension used to identify a file type.  
`value` is the file extension including the leading dot (e.g., `.jng`).  
`mime-type` is the (optional) MIME type associated with the file extension.  
`perceived-type` specifies the broad category of file types this file type falls into. Well-known values on Windows are: `folder`, `text`, `image`, `audio`, `video`, `compressed`, `document`, `system`, `application`

`<description>`
: A (localized) description of the file type.

See: [Verbs](#verbs), [Icons](#icons)

### URL protocols

An application's ability to handle certain URL protocols/schemas such as HTTP. 

```xml
<url-protocol id='...' explicit-only='true' ?>
  <known-prefix value='...'/> *
  <description xml:lang='...' ?>...</description> *
  [verb] *
  [icon] *
</url-protocol>
```

`id`
: If you are registering an application-specific URL protocols (e.g., `myapp:...`) this value must be the schema name (e.g., `myapp`).  
If you are registering support for a well-known protocol such as HTTP, this value instead is just a unique identifier within the feed. The schema is instead specified using `<known-prefix>` (see below).

`explicit-only`
: When set to `true` the app is not set as the default handler for this URL protocol without explicit confirmation from the user.

`<known-prefix>`
: Names a well-known protocol such as `http` or `ftp`. Not for application-specific protocols!

`<description>`
: A (localized) description of the URL protocol.

See: [Verbs](#verbs), [Icons](#icons)

### Context menu entries

Entries in the file manager's context menu for all file types. 

```xml
<context-menu id='...' target='...' ? explicit-only='true' ?>
  <description xml:lang='...' ?>...</description> *
  <extension value='...'/> *
  [verb] *
  [icon] *
</context-menu>
```

`id`
: An ID that uniquely identifies this context menu entry within the feed. Must be a [safe ID](#safe-id).

`target`
: Controls whether the context menu entry is display for all files (`files`), only executable files (`executable-files`), all directories (`directories`) or all filesystem objects (`all`). Defaults to `files` if not set.

`explicit-only`
: When set to `true` this context menu entry is not added without explicit confirmation from the user.

`<extension>` - since version 2.21
: File extension this context menu entry is displayed for. Only applicable when `target` is `files` or unset.  
  The context menu is shown for all file types when no extensions are specified.

See: [Verbs](#verbs), [Icons](#icons)

- a single `<verb>`: creates a simple context menu, named using the `<description>` inside `<verb>`
- multiple `<verb>`s (since version 2.18): creates a cascading context menu, named using the `<description>` inside `<context-menu>`, with sub-entries named using the `<description>` inside `<verb>`

### AutoPlay handlers

An application's ability to handle AutoPlay events. 

```xml
<auto-play id='...' provider='...' ?>
  <event name='...'/> *
  <description xml:lang='...' ?>...</description> *
  [verb]
  [icon] *
</auto-play>
```

`id`
: An ID that uniquely identifies this AutoPlay handler within the feed. Must be a [safe ID](#safe-id).

`provider`
: The name of the application as shown in the AutoPlay selection list.

`<event>`
: A specific AutPlay event. Well-known values on Windows are: `PlayCDAudioOnArrival`, `PlayDVDAudioOnArrival`, `PlayMusicFilesOnArrival`, `PlayVideoCDMovieOnArrival`, `PlaySuperVideoCDMovieOnArrival`, `PlayDVDMovieOnArrival`, `PlayBluRayOnArrival`, `PlayVideoFilesOnArrival`, `HandleCDBurningOnArrival`, `HandleDVDBurningOnArrival`, `HandleBDBurningOnArrival`

`<description>`
: A (localized) description of the AutoPlay handler.

See: [Verbs](#verbs), [Icons](#icons)

### Registration

Indicates that an application should be listed in the "Set your Default Programs" UI (Windows Vista and later).

```xml
<registration id='...' capability-reg-path='...' />
```

`id`
: An ID that uniquely identifies this hook within the feed. Must be a [safe ID](#safe-id).

`capability-reg-path`
: The registry path relative to `HKEY_CURRENT_USER` or `HKEY_LOCAL_MACHINE` which should be used to store the application's capability registration information.

### Default programs

Ability to act as default programs for well-known services such web-browser or e-mail client.

```xml
<default-program id='...' service='...' explicit-only='true' ? />
```

`id`
: An ID that uniquely identifies this default program registration within the feed. Must be a [safe ID](#safe-id).  
Also serves as a programmatic identifier within the desktop environment. In case of conflicts, the first default program registration listed with a specific ID will take precedence.

`service`
: The name of the service the application provides.  
  Well-known values on Windows are: `Mail`, `Media`, `IM`, `JVM`, `Calender`, `Contacts`, `Internet Call`

`explicit-only`
: When set to `true` the app is not registered as a default program without explicit confirmation from the user.

### Browser native messaging

An application's ability to act as a [browser native messaging host](https://developer.chrome.com/docs/extensions/develop/concepts/native-messaging). Supported since version 2.28.

```xml
<native-messaging id='...' name='...' browser='...' command='...' ? explicit-only='true' ?>
  <browser-extension id='...'/> *
</native-messaging>
```

`id`
: An ID that uniquely identifies this native messaging entry within the feed. Must be a [safe ID](#safe-id).

`name`
: The name used to call the native messaging host from browser extensions.  
  Can only contain lowercase alphanumeric characters, underscores (`_`) and dots (`.`). Can't start or end with a dot, and a dot can't be followed by another dot.

`browser`
: Space-separated list of browsers the native messaging host can be registered in.  
  Well-known values currently are:`Firefox`,  `Chrome`, `Chromium`, `Edge`, `Opera`, `Brave`, `Vivaldi`

`command`
: The name of the command in the feed to use. Defaults to `run` if not set.

`explicit-only`
: When set to `true` this context menu entry is not added without explicit confirmation from the user.

`<browser-extension>`
: Browser extension that should have access to the native messaging host. `id` contains the ID of the browser extension, without prefixes like `chrome-extension://`.

### Remove hook

A hook/callback into the application to be called during [`0install remove`](../details/cli.md#remove). Supported since version 2.23.

The hook will not be called if the application is not in the [cache](../details/cache.md) or if `--batch` is specified.

```xml
<remove-hook id='...' command='...' ?>
  <arg> ... </arg> *
</remove-hook>
```

`id`
: An ID that uniquely identifies this hook within the feed. Must be a [safe ID](#safe-id).

`command`
: The name of the command in the feed to use when a removal of the app is requested. Defaults to `run` if not set.

`<arg>`
: Command-line argument to be passed to the command. Will be automatically escaped to allow proper concatenation of multiple arguments containing spaces.

## Verbs

Some capabilities require you to map verbs/actions to specific [commands](feed.md#commands) in the feed.

```xml
<verb name='...' command='...' ? args='...' ? single-element-only='true' ? extended='true' ?>
  <description xml:lang='...' ?>...</description> *
  <arg> ... </arg> *
</verb>
```

`name`
: The name of the verb. Must be a [safe ID](#safe-id).  
  Use canonical names (`open`, `opennew`, `edit`, `play`, `print`, `Preview`) to get automatic localization; specify `<description>`s otherwise.

`command`
: The name of the command to use when launching via this capability. Defaults to `run` if not set.

`args`
: Command-line arguments to be passed to the command in escaped form. `%V` gets replaced with the path of the file being opened.
  This is ignored if any `<arg>` elements are specified.

`single-element-only` - since version 2.21
: Set this to true to hide the verb if more than one element is selected.  
  Use this to help avoid running out of resources if the user opens too many files.

`extended`
: Set this to `true` to hide the verb in the Windows context menu unless the Shift key is pressed when opening the menu.

`<description>`
: A (localized) description of the verb.

`<arg>` - since version 2.18
: Command-line argument to be passed to the command. Will be automatically escaped to allow proper concatenation of multiple arguments containing spaces.
  `${item}` gets replaced with the path of the file being opened.

## Icons

Some capabilities allow you to specify an icon.

```xml
<icon xmlns='http://zero-install.sourceforge.net/2004/injector/interface' type='...' href='...' ?/>
```

!!! attention
    Since `<icon>` is defined by the regular [feed format](feed.md), you need to explicitly specify the XML namespace as shown above, when placing `<icon>`s within capabilities.

`type`
: The MIME type of the icon. Should be `image/vnd.microsoft.icon` (`.ico`) for Windows.

`href`
: The URL where the icon can be downloaded.

## Safe ID

A safe ID may only contain alphanumeric characters, spaces (` `), dots (`.`), underscores (`_`), hyphens (`-`) and plus signs (`+`).  
It also serves as a programmatic identifier within the desktop environment. In case of conflicts, the first element listed with a specific ID will take precedence.

## Further reading

- Paper: [Desktop Integration Management for Portable, Zero-Install and Virtualized Applications](https://0install.de/files/papers/desktop_integration.pdf)
