This page explains how to build in a `chroot` sandbox environment [using 0compile](../0compile/index.md). It offers a few advantages:

-   Building doesn't affect the host environment, and the host doesn't influence the build.
-   Builds can target a different architecture, such as building x86 packages from a x64 host.
-   The build sandbox is smaller than a virtual machine, and the download is typically smaller.

The build system (`chroot`) comes with development tools such as gcc and make, and the `0launch` command.

!!! warning
    This page is very out-of-date. Consider using Docker to sandbox builds instead.

**Contents:**

[TOC]

# Chroot setup

Alternatives:

-   Build on a [Debian system](#debian) (including Ubuntu), using APT and .deb with `pbuilder`
-   Build on a [Red Hat system](#red-hat) (including Fedora), using Yum and .rpm with `mock`
-   Build on a [Linux From Scratch](#linux-from-scratch) system, using only source tarballs with `chroot`

For all systems we use a `build` directory, that is bind-mounted inside the chroot, to hold our build results.

## Debian

<http://pbuilder.alioth.debian.org/>

Install and configure the software:

```shell
$ sudo apt-get install pbuilder

$ mkdir -p build
$ echo "BINDMOUNTS='build'" >> /etc/pbuilderrc
```

Prepare the build environment cache:

```shell
MIRROR=http://ftp.debian.org/debian/
DIST=lenny

ARCH=i386 # or amd64
CPU=i486 # or x86_64

BASEDIR=/var/cache/pbuilder
TGZ=${BASEDIR}/${DIST}-${ARCH}.tgz

$ sudo pbuilder --create --basetgz $TGZ --mirror $MIRROR \
                --distribution $DIST --architecture $ARCH \
                --extrapackages "zeroinstall-injector"
```

Enter the build environment chroot:

```shell
$ setarch $CPU sudo pbuilder --login --basetgz $TGZ
# cat /etc/debian_version
5.0.8
# 0launch --version
0launch (zero-install) 0.34
Copyright (C) 2007 Thomas Leonard
...
```

Has `build-essential` dependencies:

-   `dpkg-dev`
    -   `dpkg`
    -   `perl5`
    -   `perl-modules`
    -   `cpio`
    -   `bzip2`
    -   `lzma`
    -   `patch`
    -   `make`
    -   `binutils`
    -   `libtimedate-perl`
    -   `gcc | c-compiler`
-   `g++`
-   `libc6-dev | libc-dev`
-   `make`

## Red Hat

<https://fedorahosted.org/mock/>

Install and configure the software:

```shell
$ su -c "yum install mock"
$ su -c "usermod -G mock $USER"

$ mkdir -p build
$ echo "config_opts['plugin_conf']['bind_mount_opts']['dirs'].append(('./build', '/build'))" >> /etc/mock/site-defaults.cfg
```

Prepare the build environment cache:

```shell
ARCH=i386 # or x86_64

ROOT=epel-5-$ARCH

$ mock --root=$ROOT --arch=$ARCH --init
$ mock --root=$ROOT --arch=$ARCH --install "zeroinstall-injector"
```

Enter the build environment chroot:

```shell
$ mock --root=$ROOT --arch=$ARCH --shell
> cat /etc/redhat-release
CentOS release 5.6 (Final)
> 0launch --version
0launch (zero-install) 0.38
Copyright (C) 2007 Thomas Leonard
...

$ mock --root=$ROOT --arch=$ARCH --clean
```

Has `buildsys-build` dependencies:

-   `bash`
-   `buildsys-macros`
-   `bzip2`
-   `coreutils`
-   `cpio`
-   `diffutils`
-   `elfutils`
-   `gcc-c++`
-   `gcc`
-   `gzip`
-   `make`
-   `patch`
-   `perl`
-   `redhat-release`
-   `redhat-rpm-config`
-   `rpm-build`
-   `sed`
-   `tar`
-   `unzip`
-   `which`

## Linux From Scratch

<http://www.linuxfromscratch.org/lfs/>

Build LFS:

-   [Read the Linux From Scratch Book](http://www.linuxfromscratch.org/lfs/view/stable/)

Enter chroot:

```shell
export LFS=/mnt/lfs

sudo mount -v --bind /dev $LFS/dev

sudo mount -vt devpts devpts $LFS/dev/pts
sudo mount -vt tmpfs shm $LFS/dev/shm
sudo mount -vt proc proc $LFS/proc
sudo mount -vt sysfs sysfs $LFS/sys

sudo mkdir -pv $LFS/build
sudo mount -v --bind ./build $LFS/build

$ sudo chroot $LFS /usr/bin/env -i \
    HOME=/root TERM="$TERM" PS1='\u:\w\$ ' \
    PATH=/bin:/usr/bin:/sbin:/usr/sbin \
    /bin/bash --login
# cat /etc/lfs-release
6.8
```

BLFS packages:

-   [OpenSSL](http://www.linuxfromscratch.org/blfs/view/svn/postlfs/openssl.md) (Python dependency)
-   [Python](http://www.linuxfromscratch.org/blfs/view/svn/general/python.md)
-   [GnuPG](http://www.linuxfromscratch.org/blfs/view/svn/postlfs/gnupg.md)
-   [PCRE](http://www.linuxfromscratch.org/blfs/view/svn/general/pcre.md) (Glib dependency)
-   [GLib](http://www.linuxfromscratch.org/blfs/view/svn/general/glib2.md) (PyGObject dependency)
-   [PyGObject](http://www.linuxfromscratch.org/blfs/view/svn/general/python-modules.html#pygobject)

Zero Install itself:

-   [ZeroInstall-Injector](../../details/linux.md#from-source)

# 0compile setup

Now we have a chroot with `0launch`, and can add `0compile`:

```shell
CMD="0compile"
URI="http://0install.net/2006/interfaces/0compile.xml"

$ yes Y | /usr/bin/0launch -cd $URI
$ 0alias -d /usr/bin $CMD $URI
```

!!! note
    Depending on your build OS and Python version, you might need to use an older version of 0compile.

# 0compile build

Begin with downloading the source code, in console mode:

```shell
$ 0launch -cd -s http://www.example.com/interfaces/foo.xml
```

Then we setup the build sub-directory using the source feed:

```shell
$ cd /build
$ 0compile setup http://www.example.com/interfaces/foo.xml foo
```

Next we proceed with building the binary from the source:

```shell
$ cd foo
$ 0compile build
```

Finally we tell 0compile to prepare the binary feed/archive:

```shell
$ 0compile publish http://www.example.com/implementations
```

After exiting the chroot, we can find the results in build/foo.

# Publish results

The new binary feed is now ready to be merged with our source feed, signed (using `0publish --xmlsign`), and published with the archives.

Since we used a new clean chroot to build the binary, we can be reasonably sure that all dependencies are included in the source feed.

# Future directions

In the future it might be possible to use the [Open Build Service](http://open-build-service.org/) (OBS), to build Zero Install packages using a distributed development platform.

Currently it (OBS) supports building RPM and Debian packages, those can be converted to Zero Install feeds using [pkg2zero](../pkg2zero.md) (when relocatable).
