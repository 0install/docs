# Java apps

This guide covers packaging Java apps that run on a JRE or JDK pulled in via Zero Install.

## Runtime feeds

[`https://apps.0install.net/java/jre.xml`](https://apps.0install.net/java/jre.xml)
: The Java Runtime Environment. Minimum needed to run a Java app.

[`https://apps.0install.net/java/jdk.xml`](https://apps.0install.net/java/jdk.xml)
: The Java Development Kit (compiler + tools). Use this as a build dependency.

[`https://apps.0install.net/java/jar-launcher.xml`](https://apps.0install.net/java/jar-launcher.xml)
: A small helper that launches the `Main-Class` declared in a JAR manifest while preserving `CLASSPATH`. Use this when your app loads other JARs from `CLASSPATH`. `java -jar` ignores `CLASSPATH`, which prevents Zero Install from injecting library JARs.

The JRE and JDK feeds pick a suitable OpenJDK build for the current platform (Eclipse Temurin on Linux and macOS, Microsoft Build of OpenJDK on Windows) or use a Java version installed by the system's package manager. To pin a specific distribution, depend on [`openjdk.xml`](https://apps.0install.net/java/openjdk.xml), [`temurin-jdk.xml`](https://apps.0install.net/java/temurin-jdk.xml) or [`microsoft-jdk.xml`](https://apps.0install.net/java/microsoft-jdk.xml) directly.

Versions match the JRE/JDK feature release (`8`, `11`, `17`, `21`, ...).

## Running a self-contained JAR

If your JAR has a `Main-Class` and bundles all dependencies, the simplest feed template is:

```xml
<?xml version="1.0"?>
<interface xmlns="http://zero-install.sourceforge.net/2004/injector/interface">
  <name>MyApp</name>
  <summary>does something useful</summary>
  <homepage>https://example.com/myapp</homepage>

  <feed-for interface="https://example.com/myapp.xml"/>

  <group license="Apache-2.0">
    <command name="run" path="MyApp.jar">
      <runner interface="https://apps.0install.net/java/jre.xml" version="17..">
        <arg>-jar</arg>
      </runner>
    </command>

    <implementation version="{version}" released="{released}" stability="stable">
      <manifest-digest/>
      <file href="https://example.com/downloads/myapp-{version}.jar" dest="MyApp.jar"/>
    </implementation>
  </group>
</interface>
```

`<file>` (instead of `<archive>`) downloads a single JAR straight into the implementation directory. The runner invokes `java -jar /path/to/MyApp.jar`.

## Apps with library JAR dependencies

!!! attention
    When your app needs other JARs that are themselves Zero Install feeds, `java -jar` won't work, since it ignores `CLASSPATH`. Use [JAR Launcher](https://apps.0install.net/java/jar-launcher.xml) instead:

```xml
<command name="run" path="MyApp.jar">
  <environment name="CLASSPATH" insert="MyApp.jar"/>
  <runner interface="https://apps.0install.net/java/jar-launcher.xml"/>
</command>

<requires interface="https://example.com/somelibrary.xml">
  <environment name="CLASSPATH" insert="somelibrary.jar"/>
</requires>
<restricts interface="https://apps.0install.net/java/jre.xml" version="17.."/>
```

JAR Launcher reads the `Main-Class` attribute from the JAR passed as the command's `path` and invokes it normally, so any library JARs added via `<environment name="CLASSPATH">` bindings are visible. The JAR itself must also be on `CLASSPATH`.

Since the runner is JAR Launcher rather than the JRE, use `<restricts>` to constrain the Java version.

## Distributing a folder of JARs

Some Java apps ship as a directory tree (an `app/` folder, an `lib/*.jar` folder, optional native libraries). Treat them like any other binary archive:

```xml
<group license="MIT">
  <command name="run" path="lib/myapp.jar">
    <environment name="CLASSPATH" insert="lib/myapp.jar"/>
    <environment name="CLASSPATH" insert="lib/*" mode="append"/>
    <runner interface="https://apps.0install.net/java/jar-launcher.xml"/>
  </command>

  <implementation version="{version}" released="{released}" stability="stable">
    <manifest-digest/>
    <archive extract="myapp-{version}" href="https://example.com/downloads/myapp-{version}.tar.gz"/>
  </implementation>
</group>
```

If the app needs native libraries (JNI), add an `<environment name="LD_LIBRARY_PATH" insert="lib"/>` (or `PATH` on Windows, `DYLD_LIBRARY_PATH` on macOS) so the JVM can find them.

If the libraries differ per platform, follow the [cross-platform](cross-platform.md) recipe and ship one implementation per `arch`.
