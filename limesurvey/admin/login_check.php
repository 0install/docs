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


if (!isset($dbprefix) || isset($_REQUEST['dbprefix'])) {die("Cannot run this script directly");}
if (!isset($action)) {$action=returnglobal('action');}

// check data for login
if(isset($_POST['user']) && isset($_POST['password']) || ($action == "forgotpass") || ($action == "login") || ($action == "logout"))	// added by Dennis
{
	include("usercontrol.php");
}


// login form
if(!isset($_SESSION['loginID']) && $action != "forgotpass" && ($action != "logout" || ($action == "logout" && !isset($_SESSION['loginID'])))) // && $action != "login")	// added by Dennis
{
	if($action == "forgotpassword")
	{
		$loginsummary = "<form name='forgot' id='forgot' method='post' action='$rooturl/admin/admin.php' ><br /><strong>".$clang->gT("You have to enter user name and email.")."</strong><br />	<br />
							<table>
								<tr>
									<td><p>".$clang->gT("Username")."</p></td>
									<td><input name='user' type='text' id='user' size='40' maxlength='40' value='' /></td>
								</tr>
								<tr>
									<td><p>".$clang->gT("Email")."</p></td>
									<td><input name='email' id='email' type='text' size='40' maxlength='40' value='' /></td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td><input type='hidden' name='action' value='forgotpass' />
									<input class='action' type='submit' value='".$clang->gT("Check Data")."' /><br />&nbsp;\n</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td><a href='$scriptname'>".$clang->gT("Main Admin Screen")."</a></td>
								</tr>
							</table>						
						</form>";	
	}
	elseif (!isset($loginsummary))
	{ // could be at login or after logout 
		$refererargs=''; // If this is a direct access to admin.php, no args are given
		// If we are called from a link with action and other args set, get them
		if (isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'])
		{
			$refererargs = html_escape($_SERVER['QUERY_STRING']);
		}

		$hidden_loginlang = '';
		if (isset($_POST['lang']) && $_POST['lang'])
		{
			$hidden_loginlang = "<input type='hidden' name='loginlang' value='".$_POST['lang']."' />";
		}
        
		if (!isset($logoutsummary))
		{
			$loginsummary = "<form name='login' id='login' method='post' action='$rooturl/admin/admin.php' ><br /><strong>".$clang->gT("You have to login first.")."</strong><br />	<br />";
		}
		else
		{
			$loginsummary = "<form name='login' id='login' method='post' action='$rooturl/admin/admin.php' ><br /><strong>".$logoutsummary."</strong><br />	<br />";
		}

		$loginsummary .= "
							<table>
								<tr>
									<td>".$clang->gT("Username")."</td>
									<td><input name='user' type='text' id='user' size='40' maxlength='40' value='' /></td>
								</tr>
								<tr>
									<td>".$clang->gT("Password")."</td>
									<td><input name='password' id='password' type='password' size='40' maxlength='40' /></td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td align='center'><input type='hidden' name='action' value='login' />
									<input type='hidden' name='refererargs' value='".$refererargs."' />
									$hidden_loginlang
									<input class='action' type='submit' value='".$clang->gT("Login")."' /><br />&nbsp;\n</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td><a href='$scriptname?action=forgotpassword'>".$clang->gT("Forgot Your Password?")."</a><br />&nbsp;\n</td>
								</tr>
							</table>
						</form>";

		// Language selection
		$loginsummary .=  "\t<form name='language' id='language' method='post' action='$rooturl/admin/admin.php' >"
		. "\t<table><tr>\n"
		. "\t\t<td align='center' >\n"
		. "\t\t\t".$clang->gT("Current Language").":\n"
		. "\t\t</td><td>\n"
		. "\t\t\t<select name='lang' onchange='form.submit()'>\n";
		foreach (getlanguagedata() as $langkey=>$languagekind)
		{
			$loginsummary .= "\t\t\t\t<option value='$langkey'";
			if (isset($_SESSION['adminlang']) && $langkey == $_SESSION['adminlang']) {$loginsummary .= " selected='selected'";}
			// in case it is a logout, session has already been killed
			if (!isset($_SESSION['adminlang']) && $langkey == $clang->getlangcode() ){$loginsummary .= " selected='selected'";}
			$loginsummary .= ">".$languagekind['description']." - ".$languagekind['nativedescription']."</option>\n";
		}
		$loginsummary .= "\t\t\t</select>\n"
		. "\t\t\t<input type='hidden' name='action' value='changelang' />\n"
		. "\t\t</td>\n"
		. "\t</tr>\n"
		. "</table>"
		. "</form><br />";
	}
}

if (isset($loginsummary)) {

	$adminoutput.= "<table width='100%' border='0' cellpadding='0' cellspacing='0'>\n"
	."\t<tr>\n"
    ."\t\t<td valign='top' align='center' bgcolor='#F8F8FF'>\n";
	
	if(isset($_SESSION['loginID']))
	{
		$adminoutput.= showadminmenu();
	}
	$adminoutput.= $loginsummary;
	
	$adminoutput.= "\t\t</td>\n";
	$adminoutput.= "\t</tr>\n";
	$adminoutput.= "</table>\n";
}

?>
