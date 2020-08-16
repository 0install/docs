title: Home

# Zero Install

Zero Install is a decentralised cross-platform software installation system available under the LGPL. It allows software developers to publish programs directly from their own web-sites, while supporting features familiar from centralised distribution repositories such as shared libraries, automatic updates and digital signatures. It is intended to complement, rather than replace, the operating system's package management. 0install packages never interfere with those provided by the distribution.

0install does not define a new packaging format; unmodified tarballs or zip archives can be used. Instead, it defines an XML metadata format to describe these packages and the dependencies between them. A single metadata file can be used on multiple platforms (e.g. Ubuntu, Debian, Fedora, openSUSE, Mac OS X and Windows), assuming binary or source archives are available that work on those systems.

0install also has some interesting features not often found in traditional package managers. For example, while it will share libraries whenever possible, it can always install multiple versions of a package in parallel when there are conflicting requirements. Installation is always side-effect-free (each package is unpacked to its own directory and will not touch shared directories such as /usr/bin), making it ideal for use with sandboxing technologies and virtualisation.

The XML file describing the program's requirements can also be included in a source-code repository, allowing full dependency handling for unreleased developer versions. For example, a user can clone a Git repository and build and test the program, automatically downloading newer versions of libraries where necessary, without interfering with the versions of those libraries installed by their distribution, which continue to be used for other software.

Started in 2003, 0install is developed by volunteers from around the world; contributors include Aleksey Lim, Anders F Björklund, Bastian Eicher, Chris Leick, Daniel Tschan, Dave Abrahams, Frank Richter, Mark Seaborn, Michel Alexandre Salim, Pino Toscano, Rene Lopez, Thomas Leonard, Tim Cuthbertson and Tim Diels.

More than one thousand packages are currently available and you can easily publish your own programs. Zero Install itself is available from the official repositories of most Linux distributions (including Arch, Debian, Fedora, Gentoo, Mint, openSUSE and Ubuntu).

[Get Zero Install](https://get.0install.net/){: .md-button .md-button--primary }

## Why?

Click one of the links below to find out why you may want to use 0install.

[By perspective](perspectives.md)
: How 0install benefits users, administrators, developers and distributions.

[Compared to other systems](comparison.md)
: Why you may prefer 0install over another project.

[Cool features](features.md)
: Some key features: native packager integration, sharing and security.

[FAQ](faq.md)
: Frequently Asked Questions

## More documentation

[Basics](basics/index.md)
: How to use 0install to download and run programs.

[Details](details/index.md)
: How 0install works.

[Developers](developers/index.md)
: How to contribute to 0install itself, or integrate it with your own software.

[Packaging](packaging/index.md)
: How to make software available through 0install.

[Specifications](specifications/index.md)
: Formal specifications for the file formats used by 0install. 

[Tools](tools/index.md)
: Programs to make publishing and using 0install programs easier.
