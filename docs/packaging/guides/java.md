# Java apps

This guide covers packaging Java apps that run on a JDK pulled in via Zero Install.

## Runtime feeds

[`https://apps.0install.net/java/jdk.xml`](https://apps.0install.net/java/jdk.xml)
: The Java Development Kit. Use this both to run Java apps and as a build dependency.

[`https://apps.0install.net/java/jar-launcher.xml`](https://apps.0install.net/java/jar-launcher.xml)
: A small helper that launches the `Main-Class` declared in a JAR manifest while preserving `CLASSPATH`. Use this when your app loads other JARs from `CLASSPATH`. `java -jar` ignores `CLASSPATH`, which prevents Zero Install from injecting library JARs.

Versions match the JDK feature release (`1.8`, `11`, `17`, `21`, ...).

!!! note
    Starting with Java 11, Java no longer offers a separate Java Runtime Environment (JRE) redistributable. Instead, apps are expected to either run on the full JDK or bundle a trimmed-down runtime built with `jlink`. Therefore, depend on `jdk.xml` even if your app only needs to run Java code.

    The legacy [`https://apps.0install.net/java/jre.xml`](https://apps.0install.net/java/jre.xml) feed is still available for legacy apps that require Java 8.

### Picking the right JDK version

Specify the minimum Java version your app was compiled for as the lower bound of the `version` range, e.g. `version="17.."`.

Newer JDKs occasionally remove APIs and features (e.g., Java EE modules such as JAXB in Java 11, the Nashorn JavaScript engine in Java 15 or the Security Manager in Java 24). If your app is known to break on newer releases, add an upper bound to the range, e.g. `version="11..!17"` to select Java 11 up to (but not including) Java 17.

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
      <runner interface="https://apps.0install.net/java/jdk.xml" version="17..">
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
<command name="run">
  <environment name="CLASSPATH" insert="MyApp.jar"/>
  <runner interface="https://apps.0install.net/java/jar-launcher.xml" version="17.."/>
</command>

<requires interface="https://example.com/somelibrary.xml">
  <environment name="CLASSPATH" insert="somelibrary.jar"/>
</requires>
```

JAR Launcher reads the `Main-Class` attribute from the first JAR on `CLASSPATH` and invokes it normally, so any library JARs added via `<environment name="CLASSPATH">` bindings are visible.

## Distributing a folder of JARs

Some Java apps ship as a directory tree (an `app/` folder, an `lib/*.jar` folder, optional native libraries). Treat them like any other binary archive:

```xml
<group license="MIT">
  <command name="run">
    <environment name="CLASSPATH" insert="lib/myapp.jar"/>
    <environment name="CLASSPATH" insert="lib" mode="append"/>
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
