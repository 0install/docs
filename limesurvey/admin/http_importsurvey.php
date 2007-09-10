<?php
/*
* LimeSurvey
* Copyright (C) 2007 The LimeSurvey Project Team / Carsten Schmitz
* All rights reserved.
* License: GNU/GPL License v2 or later, see LICENSE.php
* LimeSurvey is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

//Ensure script is not run directly, avoid path disclosure
include_once("login_check.php");


// A FILE TO IMPORT A DUMPED SURVEY FILE, AND CREATE A NEW SURVEY

$importsurvey = "<br /><table width='100%' align='center'><tr><td>\n";
$importsurvey .= "<table class='alertbox'>\n";
$importsurvey .= "\t<tr ><td colspan='2' height='4'><font size='1' ><strong>"
.$clang->gT("Import Survey")."</strong></font></td></tr>\n";
$importsurvey .= "\t<tr ><td align='center'>\n";

$the_full_file_path = $tempdir . "/" . $_FILES['the_file']['name'];

if (!@move_uploaded_file($_FILES['the_file']['tmp_name'], $the_full_file_path))
{
	$importsurvey .= "<strong><font color='red'>".$clang->gT("Error")."</font></strong><br />\n";
	$importsurvey .= sprintf ($clang->gT("An error occurred uploading your file. This may be caused by incorrect permissions in your %s folder."),$tempdir)."<br /><br />\n";
	$importsurvey .= "</font></td></tr></table>\n";
	return;
}

// IF WE GOT THIS FAR, THEN THE FILE HAS BEEN UPLOADED SUCCESFULLY

$importsurvey .= "<strong><font color='green'>".$clang->gT("Success")."!</font></strong><br />\n";
$importsurvey .= $clang->gT("File upload succeeded.")."<br /><br />\n";
$importsurvey .= $clang->gT("Reading file..")."<br />\n";

$importingfrom = "http";	// "http" for the web version and "cmdline" for the command line version
include("importsurvey.php");

?>
