title: .NET library

This tutorial explains how to create feeds for a .NET application with a dependency on a .NET library using the [Zero Install Publishing Tools](../../tools/0publish-win.md).

## The application (EXE)

When using the "New Feed Wizard" for creating the feed for the EXE make sure to set "External dependencies" to `True` on the "Fill in missing details" page. This will automatically add the following line to your feed:

```xml
<runner interface="https://apps.0install.net/dotnet/clr-monopath.xml" command="run-gui"/>
```

This enables Zero Install to inject DLLs provided by feeds into .NET applications. This is required because, unlike regular DLLs, .NET DLLs are not located via the `PATH` environment variable.

You can then add a dependency on your library feed by adding something like this inside the `<implementation>` tag:

```xml
<requires interface="http://somedomain.com/somelibrary.xml">
  <environment insert="." mode="append" name="MONO_PATH"/>
</requires>
```

Note that `MONO_PATH` does not mean that this requires Mono. It just shares the same environment variable name for uniformity.

## The library (DLL)

To create a feed for the library start by selecting "New Empty Feed" and copying this into the XML view of the editor:

```xml
<interface uri="http://somedomain.com/somelibrary.xml" xmlns="http://zero-install.sourceforge.net/2004/injector/interface">
  <name>Some library</name>
  <summary>a library for something</summary>
  <implementation version="1.0">
    <archive href="http://somedomain.com/somelibrary.zip"/>
  </implementation>
</interface>
```

You can then fill in the placeholder names and URLs. Once you are done, select the `archive` node in the tree-view and click on the "Add missing" button on the right hand-side. This performs the same archive download, extraction and hashing that the "New Feed Wizard" normally would.
