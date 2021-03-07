# 0test

!!! info ""
    **Maintainer:** Thomas Leonard  
    **License:** GNU General Public License  
    **Source:** <https://github.com/0install/0test>  
    **Zero Install feed:** <https://apps.0install.net/0install/0test.xml>

A Zero Install [feed](../specifications/feed.md) can specify a "test" command, which can be run to test the program automatically. 0test runs this command. It can also test combinations of various versions of a program and its dependencies.

Create a short-cut to it in the usual way:

```shell
0install add 0test https://apps.0install.net/0install/0test.xml
```

## Usage

Most simply, you can use it to run the tests for a program you have downloaded or checked out of version control. e.g.

```shell
$ git clone some-program
$ 0test some-program/program.xml
```

You can also use it to test released programs, e.g. to test the default version of 0compile:

```shell
$ 0test https://apps.0install.net/0install/0compile.xml
Passed
 - 0compile v0.21-post, ZeroInstall-Injector v0.51.1, 0publish v0.18-post, ROX-Lib2 v2.0.6-post
```

0test prints out the result and the versions of all components used in the test.

You can specify a particular set of versions to test:

```shell
$ 0test https://apps.0install.net/0install/0compile.xml 0.26 0.27
Passed
 - ZeroInstall-Injector v1.7 0compile v0.26, ROX-Lib2 v2.0.6, python v2.7.3, 0publish v0.20
 - ZeroInstall-Injector v1.7 0compile v0.27, ROX-Lib2 v2.0.6, python v2.7.3, 0publish v0.20
None skipped
None failed
```

A summary is printed at the end showing the result of each combination:

Passed
: the tests all passed successfully (the command returned an exit status of zero)

Skipped
: this combination of versions can't be used together (or no tests are defined)

Failed
: some tests failed (the self-test command returned a non-zero exit status)

You can specify versions of libraries the program depends on too. 0test will try all combinations. This tests three versions of 0compile against three versions of Zero Install (a total of 3 x 3 = 9 tests):

```shell
$ 0test --html results.html \
  https://apps.0install.net/0install/0compile.xml 0.19 0.20 0.21 \
  http://0install.net/2007/interfaces/ZeroInstall.xml 0.47 0.48 0.49
Passed
 - 0compile v0.20, ZeroInstall-Injector v0.48, 0publish v0.18-post, ROX-Lib2 v2.0.6-post
 - 0compile v0.20, ZeroInstall-Injector v0.49, 0publish v0.18-post, ROX-Lib2 v2.0.6-post
 - 0compile v0.21, ZeroInstall-Injector v0.48, 0publish v0.18-post, ROX-Lib2 v2.0.6-post
 - 0compile v0.21, ZeroInstall-Injector v0.49, 0publish v0.18-post, ROX-Lib2 v2.0.6-post
Skipped
 - 0compile v0.21, ZeroInstall-Injector v0.47
Failed
 - 0compile v0.19, ZeroInstall-Injector v0.47, 0publish v0.18-post, ROX-Lib2 v2.0.6-post
 - 0compile v0.19, ZeroInstall-Injector v0.48, 0publish v0.18-post, ROX-Lib2 v2.0.6-post
 - 0compile v0.19, ZeroInstall-Injector v0.49, 0publish v0.18-post, ROX-Lib2 v2.0.6-post
 - 0compile v0.20, ZeroInstall-Injector v0.47, 0publish v0.18-post, ROX-Lib2 v2.0.6-post
```

The `--html results.html` option causes it to also generate this table:

<table>
  <tr>
     <th/>
     <th colspan="3">ZeroInstall-Injector</th>
  </tr>
  <tr>
     <th>0compile</th>
     <th>0.47</th>
     <th>0.48</th>
     <th>0.49</th>
  </tr>
  <tr>
     <th>0.19</th>
     <td style="background:red">0publish 0.18-post ROX-Lib2 2.0.6-post</td>
     <td style="background:red">0publish 0.18-post ROX-Lib2 2.0.6-post</td>
     <td style="background:red">0publish 0.18-post ROX-Lib2 2.0.6-post</td>
  </tr>
  <tr>
     <th>0.20</th>
     <td style="background:red">0publish 0.18-post ROX-Lib2 2.0.6-post</td>
     <td style="background:green">0publish 0.18-post ROX-Lib2 2.0.6-post</td>
     <td style="background:green">0publish 0.18-post ROX-Lib2 2.0.6-post</td>
  </tr>
  <tr>
     <th>0.21</th>
     <td style="background:yellow">skipped</td>
     <td style="background:green">0publish 0.18-post ROX-Lib2 2.0.6-post</td>
     <td style="background:green">0publish 0.18-post ROX-Lib2 2.0.6-post</td>
  </tr>
</table>

Green = Passed, Red = Failed, Yellow = Skipped. The contents of the cells show the versions of components used in the tests where you didn't specify the version (so 0test selected one for you).

We can see in this example that the unit-tests for 0compile 0.19 no longer pass, regardless of the version of Zero Install used. This problem was caused by a change to the GNU-Hello test program it uses within its own tests. Ideally, programs should depend on their test data using `<requires>` (see below), but 0compile actually needed to test downloading of the test code itself. 0compile 0.20 fails with older version of Zero Install due to a bug in the way "0launch --get-selections" generated the XML. 0compile 0.21 depends on a later version explicitly, so the broken combination is skipped.

If you specify a test matrix with more than two dimensions, 0test will generate a series of tables.

## Other ways to specify versions

As well as passing simple version numbers, you can also allow 0test to choose a suitable version given some constraints. This is useful in test scripts:

### Ranges

You can use `,` to give a range of possible versions. This is useful with native packages where you don't know the exact version. For example, to test against any Python version 2.6.x (2.6 <= version < 2.7):

```shell
$ 0test prog.xml https://apps.0install.net/python/python.xml 2.6,2.7
Passed
 - prog 0.1, python v2.6.8-0.2
```

### %nonlocal

Don't select a local (i.e. unreleased) version to test against:

```shell
$ 0test prog.xml
Passed
 - prog 0.1, lib v1.11-post

$ 0test prog.xml http://example.com/lib %nonlocal
Passed
 - prog 0.1, lib v1.11
```

## Passing test arguments

You can pass extra arguments to the test command, but you must put them after `--` to stop 0test interpreting them as arguments or versions. e.g. to run 0test on 0compile with verbose output:

```shell
$ 0test https://apps.0install.net/0install/0compile.xml -- -v
```

## Specifying a test command

The `--test-command` (or `-t`) option can be used to run the shell command of your choice, rather than the "test" `<command>` given in the feed. The command that would be executed if the implementation were run normally is available as `$*`.

For example, to check that `rox --version` worked in versions 2.8 and 2.9 of ROX-Filer:

```shell
$ 0test -t '$* --version > /dev/null' \
    http://rox.sourceforge.net/2005/interfaces/ROX-Filer 2.8 2.9
Passed
 - ROX-Filer v2.8
 - ROX-Filer v2.9
None skipped
None failed
```

You can use `--command` to select a different `<command>`. By default, the command used is `test` when run normally, or `run` when using `-t`. You can also set this to the empty string to select no command (useful with libraries). In that case, `$1` is the directory itself rather than the command.

## Test-only dependencies

You can pass extra arguments and specify test-specific dependencies in the `<command>`, as usual. For example:

```xml
  <group>
    <requires interface="http://example.com/somelib.xml">
      ...
    </requires>
    <command name="test" path="tests/testall.py">
      <requires interface="http://testing.com/testframework.xml">
        <environment insert="" mode="replace" name="TEST_FRAMEWORK"/>
      </requires>
    </command>
    <implementation id="." version="0.1-pre"/>
  </group>
```
