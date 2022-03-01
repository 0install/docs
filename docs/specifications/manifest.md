# Manifest files

Zero Install _implementations_ are directory trees identified by an algorithm name (e.g., "sha256"), and digest of their contents calculated using that algorithm. Adding, deleting, renaming or modifying any file in a tree will change its digest. It should be infeasibly difficult to generate a new tree with the same digest as a given tree. Thus, if you know the digest of the implementation you want, and someone gives you a tree with that digest, you can trust that it is the implementation you want.

This document describes how a digest is calculated from a directory tree.

### Algorithms

There are several different algorithms that can be used to generate a digest from a directory tree, so an implementation's identifier includes the algorithm. This allows new algorithms to be added easily if weaknesses are discovered in older ones. The currently supported algorithms are:

`sha1=XXX`
: This is supported by all versions of 0install. It is less secure than the format used with the other algorithms.

`sha1new=XXX`
: Supported from version 0.20. This is the same hash as `sha1` but with the new manifest format.

`sha256=XXX`
: Supported from version 0.20 and also requires the `hashlib` Python module to be installed.

`sha256new_XXX`
: Supported from version 1.10. This is the same as `sha256`, except that the final digest is [base32-encoded](https://en.wikipedia.org/wiki/Base32) (without padding) to make it a bit shorter, and the separator character is `_` rather than `=`, as pathnames containing `=` cause problems for some programs.

When checking a new tree (e.g., that has just been downloaded from the net and unpacked), 0install generates a 'manifest' file. The manifest lists every file, directory and symlink in the tree, and gives the digest of each file's content. Here is a sample manifest file for a tree containing two files (`README` and `src/main.c`) and using the `sha1` algorithm (you can use `0install digest --manifest` to generate this):

```plain
F 0a4d55a8d778e5022fab701977c5d840bbc486d0 1132502750 11 README
D 1132502769 /src
F 83832457b29a423c8e6daf05c6dbcba17d0514dd 1132502769 17 main.c
```

If you generate a manifest file for a directory tree and find that it is identical to the manifest file you want, then you can feel confident that you have the tree you want. This is convenient, because the manifest file is much smaller than the packaged tree.

After checking, the generated manifest file is stored in a file called `.manifest` in the top-level of the tree.

To save even more space, we can simply compare the _digests_ (rather than the _contents_) of these manifest files. For example, the digest of the three-line manifest file above is given by:

```shell
$ sha1sum .manifest
b848561cd89be1b806ee00008a503c63eb4ad56e
```

When 0install adds a new archive to the cache, the top-level directory is renamed to this final digest (so the `main.c` above would be stored as `.../sha1=b848561cd89be1b806ee00008a503c63eb4ad56e/src/main.c`, for example).

Because only the digest of the manifest is needed, it is not strictly necessary to store the `.manifest` file at all. However, if the tree is modified later somehow it can show you exactly which files were changed (rather than just letting you know that the tree has changed in some unknown way).

## Manifest file format

This description of the manifest file is based on Joachim's 12 Oct 2005 post to the zero-install-devel list:

The manifest file lists, line by line, all nodes in a directory identified as `/`, without `/` itself. All relevant numbers are coded as decimal numbers without leading zeros (unless 0 is to be coded, which is coded as `0`). Times are represented as the number of seconds since the epoch. Nodes are of one of their possible types: `D`, `F`, `X`, and `S`. Names must not contain newline characters (the tree will be rejected if they do).

The file itself is encoded as UTF-8, with Unix line-endings (`\n`) and no [BOM](https://en.wikipedia.org/wiki/Byte-order_mark). Note that some operating systems treat filenames as sequences of bytes (rather than as sequences of characters), and thus may be able to handle filenames which cannot be represented as strings. A Zero Install implementation cannot contain such filenames.

### Directories

`D` nodes correspond to directories, and their line format is:

```plain
"D", space, [mtime, space,] full path name, newline
```

So, top level directories, for example, would have a "full path name" that matches the regular expression `^/[^/\n]+$`.

The modification time is only included when using the original `sha1` algorithm, as it was not found to be useful and caused problems with many archives.

### Files

`F` and `X` nodes correspond to files and executable files, respectively, and their line formats are:

```plain
"F", space, hash, space, mtime, space, size, space, file name, newline
"X", space, hash, space, mtime, space, size, space, file name, newline
```

As opposed to directories, no full path names are given. Hence, file names match `^[^/\n]+$` . The hash is the hexadecimal representation of the digest of the contents of the respective file, using the same digest algorithm as the manifest file itself. Hexadecimal digits a through f are used (rather than A through F).

### Symlinks

`S` nodes correspond to symbolic links, and their line format is:

```plain
"S", space, hash, space, size, space, symlink name, newline
```

The symlink name is given analogously to file names in `F` and `X` nodes. The size of a symlink is the number of bytes in its target (name). The hash sum, similarly to that of files, is the digest of the target (name) of the respective symlink.

### Other files types

It is an error for a tree to contain other types of object (such as device files). Such trees are rejected.

### Ordering

These lines appear in the order of a depth-first search. Within a directory, regular files and symlinks come first (ordered lexicographically by their name, i.e., they appear in the order that `LC_ALL=C sort` would produce), and then each subdirectory (again sorted in the same way).

For the original `sha1` algorithm the sort order is slightly different: sub-directories, regular files and symlinks are all sorted together rather than the subdirectories always coming after other types.

Implementations have to abide by these rules to the letter because such a file is to be generated automatically and this process absolutely must generate the same file that the directory tree packager had computed.
