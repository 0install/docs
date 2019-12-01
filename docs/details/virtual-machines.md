As well as allowing sharing between users, you can also use Zero Install to share packages between virtual machines. That is, the package is downloaded and stored once, but all virtual machines can run it.

[TOC]

## VirtualBox

[VirtualBox](http://www.virtualbox.org/) is a popular free (GPL) virtualisation system. To share your host's cache with a guest:

- Create a new guest and install an operating system as usual. The guest OS does not need to be the same as the host OS.
- Install the guest tools (choose **Install Guest Additions...** from the **Devices** menu).
- Add a shared folder. **Folder Path** is your implementation cache on the host. This will be `/var/cache/0install.net/implementations` if you have [sharing](sharing.md) enabled, or `~/.cache/0install.net/implementations` if not. **Folder Name** can be anything, e.g. **ZeroInstall**.
- In the guest, mount the new file-system under `/var/cache/0install.net/implementations` (regardless of where it is on the host):

```shell
$ sudo mount -t vboxsf ZeroInstall /var/cache/0install.net/implementations
```

Notes:

1. If you used the host's `/var/cache` directory then the guest won't be able to write to the host's cache, which is good for security. Anything installed by the guest will be available only on that guest. If you want to configure sharing between guest users, however, you'll need to configure a second shared directory, one for guest-wide packages and one for host packages (`man 0store` for details).
    
2. If you shared the `~/.cache` directory, then root in the guest will be able to write to the host cache, which is good for sharing but not so good for security. If you also configure [sharing](sharing.md) in the guest, then guest users can install to the host's cache. Here, you are trusting the guest OS to check the digests correctly.
    
3. It is also possible (though more difficult) to set things up so that an untrusted guest OS can put things in the host's cache (verified by the host). For this, you will need to make your own version of the `0store-secure-add-helper` script that passes the directory to the host for verification.
    
4. Because Zero Install packages are named by their digest, there are no problems with sharing a single cache between different architectures (whether virtual machines or physical machines with a network file-system). Packages that can be shared will be shared automatically, packages that can't will co-exist peacefully.
    
!!! warning
    Older versions of the VirtualBox guest additions have problems with symlinks in shared folders. If you allow guests to write to a shared cache on the host, you may get the error `Incorrect manifest -- archive is corrupted`, even though the archive is actually OK. Guest additions 4.0.4 is known to not work. Version 4.2.0 seems fine.

## Vagrant

[Vagrant](http://www.vagrantup.com/) is a tool for managing virtual development environments using VirtualBox.

In your Vagrantfile, use something like this:

```vagrantfile
Vagrant::Config.run do |config|
  [...]
  config.vm.share_folder "host-cache",
			 "/var/cache/0install.net/implementations",
			 "/var/cache/0install.net/implementations"

  # This next bit is convenient but less secure...

  config.vm.customize ["setextradata", :id,
  	"VBoxInternal2/SharedFoldersEnableSymlinksCreate/shared-guest-cache", "1"]
  config.vm.share_folder "shared-guest-cache",
  	"/home/vagrant/.cache/0install.net/implementations",
  	"/home/me/.cache/shared-vagrant-guest-cache"
end
```

or, for later versions:

```vagrantfile
Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
  [...]
  config.vm.synced_folder "/var/cache/0install.net/implementations",
			  "/var/cache/0install.net/implementations"

  # This next bit is convenient but less secure...

  config.vm.provider :virtualbox do |vb|
    vb.customize ["setextradata", :id,
	  "VBoxInternal2/SharedFoldersEnableSymlinksCreate/shared-guest-cache", "1"]
  end

  config.vm.synced_folder "/home/me/.cache/shared-vagrant-guest-cache",
			  "/home/vagrant/.cache/0install.net/implementations"
end
```

Change the `/home/me/.cache/shared-vagrant-guest-cache` line to the path where you want to store the shared cache on the host. Ensure you have version 4.2.0 or later of the guest additions installed, or symlinks might not work.

Here, the VMs will have read-only access to the host's cache (assuming you've set up [sharing](sharing.md) on the host), plus read-write access to a cache shared between the guests. Note that a malicious guest can corrupt this cache, and thus affect other VMs using it. However, assuming non-malicious guests, there is no problem sharing the cache between different VM types (e.g. Debian, Fedora, 32-bit, 64-bit, etc).
