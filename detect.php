<?php

function get_download_html() {
	$platforms = array(
		"Ubuntu/9.10" => "apt:zeroinstall-injector",
		"Ubuntu/9.04" => "http://downloads.sourceforge.net/zero-install/zeroinstall-injector_0.41-1_all.deb",
		"Ubuntu/8.10" => "apt:zeroinstall-injector",
		"Ubuntu/8.04" => "http://downloads.sourceforge.net/zero-install/zeroinstall-injector_0.39-1_all.deb",
		"Ubuntu" => "install-linux.html#ubuntu",
		"Debian" => "install-linux.html#debian",
		"FreeBSD" => "install-unix.html#freebsd",
		"Mac OS X" => "install-mac.html",
		"SUSE" => "install-linux.html#suse",
		"Fedora" => "install-linux.html#fedora",
		"Mandriva" => "install-linux.html#mandriva",
		"Red Hat" => "install-linux.html#redhat",
		"Slackware" => "install-linux.html#slackware",
		"Knoppix" => "install-linux.html#knoppix",
		"Linux" => "install-linux.html",
		"Gentoo" => "http://gentoo-portage.com/rox-base/zeroinstall-injector",
		"Windows" => "install-windows.html",
	);

	$platform = "";
	$link = 'injector.html';
	$a = $_SERVER['HTTP_USER_AGENT'];
	foreach ($platforms as $p => $l) {
		if (strpos($a, $p) !== false) {
			$platform = " for <b>$p</b>";
			$link = $l;
			break;
		}
	}

	return "<a href='$link'><div class='autodownload'>Get Zero Install$platform</div></a>";
}

echo get_download_html();
?>
