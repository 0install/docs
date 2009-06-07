<?php

function get_download_html() {
	$platforms = array(
		"Ubuntu/9.04" => "apt:zeroinstall-injector",
		"Ubuntu/8.10" => "apt:zeroinstall-injector",
		"Ubuntu/8.04" => "http://downloads.sourceforge.net/zero-install/zeroinstall-injector_0.39-1_all.deb",
		"Ubuntu" => "install-linux.html",
		"Debian" => "install-linux.html",
		"FreeBSD" => "install-unix.html",
		"Mac OS X" => "install-mac.html",
		"SUSE" => "install-linux.html",
		"Fedora" => "install-linux.html",
		"Mandriva" => "install-linux.html",
		"Red Hat" => "install-linux.html",
		"Slackware" => "install-linux.html",
		"Knoppix" => "install-linux.html",
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
