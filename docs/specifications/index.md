# Specifications overview

[Feed files](feed.md)
: This document is a formal description of the XML feed file format. An interface describes a program, library or other component. A feed provides a list of known implementations of the interface (versions of the program) and details about how to get them, how to check that they are authentic, how to run them and what other components they depend on.

[Capabilities extension](capabilities.md)
: This document is a formal description of the Capabilities extension of the feed format. Capabilities provide information for desktop integration of applications, such as supported MIME types.

[Catalog files](catalog.md)
: This document is a formal description of the XML catalog format. A catalog contains meta-data for a collection of feeds. Catalogs make it easier to find feeds for specific applications.

[Selections files](selections.md)
: This document describes the format of 0install's XML selections documents. A selections document contains all the information needed to launch a program. 0install generates a selections document by collecting information from multiple feeds and then running a solver to choose the best combination of components.

[Manifest files](manifest.md)
: Zero Install implementations are directory trees identified by an algorithm name (e.g., "sha1"), and digest of their contents calculated using that algorithm. Adding, deleting, renaming or modifying any file in a tree will change its digest. It should be infeasibly difficult to generate a new tree with the same digest as a given tree. Thus, if you know the digest of the implementation you want, and someone gives you a tree with that digest, you can trust that it is the implementation you want. This document describes how a digest is calculated from a directory tree.
