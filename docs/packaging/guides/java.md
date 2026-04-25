# Java apps

This guide covers packaging Java apps that run on a JRE or JDK pulled in via Zero Install.

## Runtime feeds

[`https://apps.0install.net/java/jre.xml`](https://apps.0install.net/java/jre.xml)
: The Java Runtime Environment. Minimum needed to run a Java app.

[`https://apps.0install.net/java/openjdk.xml`](https://apps.0install.net/java/openjdk.xml)
: The OpenJDK Development Kit (compiler + tools). Use this as a build dependency.

[`https://apps.0install.net/java/jar-launcher.xml`](https://apps.0install.net/java/jar-launcher.xml)
: A small helper that launches the `Main-Class` declared in a JAR manifest while preserving `CLASSPATH`. Use this when your app loads other JARs from `CLASSPATH`. `java -jar` ignores `CLASSPATH`, which prevents Zero Install from injecting library JARs.

Versions match the JRE/JDK feature release (`1.8`, `11`, `17`, `21`, ...).

## Running a self-contained JAR

If your JAR has a `Main-Class` and bundles all dependencies, the simplest feed is:

```xml
<?xml version="1.0"?>
<interface xmlns="http://zero-install.sourceforge.net/2004/injector/interface">
  <name>MyApp</name>
  <summary>does something useful</summary>
  <homepage>https://example.com/myapp</homepage>

  <feed-for interface="https://example.com/myapp.xml"/>

  <group license="Apache License 2.0">
    <command name="run">
      <runner interface="https://apps.0install.net/java/jre.xml" command="run">
        <version not-before="17"/>
        <arg>-jar</arg>
        <arg>$ZEROINSTALL_IMPL/MyApp.jar</arg>
      </runner>
    </command>

    <implementation version="{version}" released="{released}" stability="stable">
      <manifest-digest/>
      <file href="https://example.com/downloads/myapp-{version}.jar" dest="MyApp.jar" size="..."/>
    </implementation>
  </group>
</interface>
```

`<file>` (instead of `<archive>`) downloads a single JAR straight into the implementation directory. The runner invokes `java -jar /path/to/MyApp.jar`.

## Apps with library JAR dependencies

!!! attention
    When your app needs other JARs that are themselves Zero Install feeds, `java -jar` won't work, since it ignores `CLASSPATH`. Use [JAR Launcher](https://apps.0install.net/java/jar-launcher.xml) instead:

```xml
<command name="run">
  <environment name="CLASSPATH" insert="MyApp.jar"/>
  <runner interface="https://apps.0install.net/java/jar-launcher.xml" command="run">
    <version not-before="17"/>
  </runner>
</command>

<requires interface="https://example.com/somelibrary.xml">
  <environment name="CLASSPATH" insert="somelibrary.jar"/>
</requires>
```

JAR Launcher reads the `Main-Class` attribute from the first JAR on `CLASSPATH` and invokes it normally, so any library JARs added via `<environment name="CLASSPATH">` bindings are visible.

For a GUI app that should not open a console window on Windows, use `command="run-gui"` on the runner.

## Distributing a folder of JARs

Some Java apps ship as a directory tree (an `app/` folder, an `lib/*.jar` folder, optional native libraries). Treat them like any other binary archive:

```xml
<group license="GPL v3 (GNU General Public License)">
  <command name="run">
    <environment name="CLASSPATH" insert="lib/myapp.jar"/>
    <environment name="CLASSPATH" insert="lib" mode="append"/>
    <runner interface="https://apps.0install.net/java/jar-launcher.xml" command="run"/>
  </command>

  <implementation version="{version}" released="{released}" stability="stable">
    <manifest-digest/>
    <archive extract="myapp-{version}" href="https://example.com/downloads/myapp-{version}.tar.gz"/>
  </implementation>
</group>
```

If the app needs native libraries (JNI), add an `<environment name="LD_LIBRARY_PATH" insert="lib"/>` (or `PATH` on Windows, `DYLD_LIBRARY_PATH` on macOS) so the JVM can find them.

If the libraries differ per platform, follow the [cross-platform](cross-platform.md) recipe and ship one implementation per `arch`.

## Building from source

!!! tip
    Use [openjdk.xml](https://apps.0install.net/java/openjdk.xml) (or another JDK feed) as a build-only dependency in `<command name="compile">` to avoid forcing it on end-users at runtime. See [0compile](../../tools/0compile/index.md) for how compile-time dependencies are wired in.
