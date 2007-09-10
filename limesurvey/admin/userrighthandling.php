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
if (!isset($dbprefix) || isset($_REQUEST['dbprefix'])) {die("Cannot run this script directly");}

if (($ugid && !$surveyid) || $action == "editusergroups" || $action == "addusergroup" || $action=="usergroupindb" || $action == "editusergroup" || $action == "mailusergroup")
{
	if($ugid)
	{
		$grpquery = "SELECT * FROM ".db_table_name('user_groups')." WHERE ugid = $ugid";
		$grpresult = db_execute_assoc($grpquery);
		$grow = array_map('htmlspecialchars', $grpresult->FetchRow());
	}
	$usergroupsummary = "<table width='100%' align='center' bgcolor='#DDDDDD' border='0'>\n";
	$usergroupsummary .= "\t<tr>\n"
	. "\t\t<td colspan='2'>\n"
	. "\t\t\t<table class='menubar'>\n"
	. "\t\t\t\t<tr><td colspan='2' height='4' align='left'>"
	. "<strong>".$clang->gT("User Group")."</strong> ";
	if($ugid)
	{
		$usergroupsummary .= "{$grow['name']}</td></tr>\n";
	}
	else
	{
		$usergroupsummary .= "</td></tr>\n";
	}


	$usergroupsummary .= "\t\t\t\t<tr>\n"
	. "\t\t\t\t\t<td>\n";

	$usergroupsummary .=  "\t\t\t\t\t<img src='$imagefiles/blank.gif' alt='' width='55' height='20' border='0' hspace='0' align='left' />\n"
	. "\t\t\t\t\t<img src='$imagefiles/seperator.gif' alt='' border='0' hspace='0' align='left' />\n";

	if($ugid)
	{
		$usergroupsummary .= "<a href=\"#\" onclick=\"window.open('$scriptname?action=mailusergroup&amp;ugid=$ugid', '_top')\""
		. "onmouseout=\"hideTooltip()\""
		. "onmouseover=\"showTooltip(event,'".$clang->gT("Mail to all Members", "js")."');return false\"> " .
		"<img src='$imagefiles/invite.png' title='' align='left' alt='' name='MailUserGroup' /></a>\n" ;
	}
	$usergroupsummary .= "\t\t\t\t\t<img src='$imagefiles/blank.gif' alt='' width='135' height='20' border='0' hspace='0' align='left' />\n"
	. "\t\t\t\t\t<img src='$imagefiles/seperator.gif' alt='' border='0' hspace='0' align='left' />\n";

	if($ugid && $_SESSION['loginID'] == $grow['owner_id'])
	{
		$usergroupsummary .=  "<a href=\"#\" onclick=\"window.open('$scriptname?action=editusergroup&amp;ugid=$ugid','_top')\""
		. "onmouseout=\"hideTooltip()\""
		. "onmouseover=\"showTooltip(event,'".$clang->gT("Edit Current User Group", "js")."');return false\">" .
		"<img src='$imagefiles/edit.png' title='' alt='' name='EditUserGroup' align='left' /></a>\n" ;
	}
	else
	{
		$usergroupsummary .= "\t\t\t\t\t<img src='$imagefiles/blank.gif' alt='' width='45' height='20' border='0' hspace='0' align='left' />\n";
	}

	if($ugid && $_SESSION['loginID'] == $grow['owner_id'])
	{
		$usergroupsummary .= "\t\t\t\t\t<a href='$scriptname?action=delusergroup&amp;ugid=$ugid' onclick=\"return confirm('".$clang->gT("Are you sure you want to delete this entry.","js")."')\""
		. "onmouseout=\"hideTooltip()\""
		. "onmouseover=\"showTooltip(event,'".$clang->gT("Delete Current User Group", "js")."');return false\">"
		. "<img src='$imagefiles/delete.png' alt='' name='DeleteUserGroup' title='' align='left' border='0' hspace='0' /></a>";
	}
	else
	{
		$usergroupsummary .= "\t\t\t\t\t<img src='$imagefiles/blank.gif' alt='' width='43' height='20' border='0' hspace='0' align='left' />\n";
	}
	$usergroupsummary .= "\t\t\t\t\t<img src='$imagefiles/blank.gif' alt='' width='86' height='20' align='left' border='0' hspace='0' />\n"
	. "\t\t\t\t\t<img src='$imagefiles/seperator.gif' alt='' border='0' hspace='0' align='left' />\n"
	. "\t\t\t\t\t</td>\n"
	. "\t\t\t\t\t<td align='right' width='480'>\n"
	. "\t\t\t\t\t<img src='$imagefiles/blank.gif' alt='' align='right' border='0' width='82' height='20' />\n"
	. "\t\t\t\t\t<img src='$imagefiles/seperator.gif' alt='' align='right' border='0' hspace='0' />\n";
	
	if ($_SESSION['loginID'] == 1)
	{
		$usergroupsummary .= "<a href='$scriptname?action=addusergroup'"
		."onmouseout=\"hideTooltip()\""
		."onmouseover=\"showTooltip(event,'".$clang->gT("Add New User Group", "js")."');return false\">" .
		"<img src='$imagefiles/add.png' title='' alt='' " .
		"align='right' name='AddNewUserGroup' onclick=\"window.open('', '_top')\" /></a>\n";
	}
	$usergroupsummary .= "\t\t\t\t\t<font class=\"boxcaption\">".$clang->gT("User Groups").":</font>&nbsp;<select name='ugid' "
	. "onchange=\"window.open(this.options[this.selectedIndex].value, '_top')\">\n"
	. getusergrouplist()
	. "\t\t\t\t\t</select>\n"
	. "\t\t\t\t</td></tr>\n"
	. "\t\t\t</table>\n"
	. "\t\t</td>\n"
	. "\t</tr>\n"
	. "\n</table>\n";
}


if ($action == "adduser" || $action=="deluser" || $action == "moduser" || $action == "userrights")
{
	include("usercontrol.php");
}

if ($action == "modifyuser")
{
	$userlist = getuserlist();
	foreach ($userlist as $usr)
	{
		if ($usr['uid'] == $_POST['uid'])
		{
				$squery = "SELECT create_survey, configurator, create_user, delete_user, move_user, manage_template, manage_label FROM {$dbprefix}users WHERE uid={$usr['parent_id']}";	//		added by Dennis
				$sresult = $connect->Execute($squery);
				$parent = $sresult->FetchRow();
				break;
		}
	}
	
	if($_SESSION['loginID'] == 1 || $_SESSION['loginID'] == $_POST['uid'] || $parent['create_user'] == 1)
	{
		$usersummary = "<table width='100%' border='0'>\n\t<tr><td colspan='4' class='header'>\n"
		. "\t\t<strong>".$clang->gT("Modifying User")."</td></tr>\n"
		. "\t<tr>\n"
		. "\t\t<th>".$clang->gT("Username")."</th>\n"
		. "\t\t<th>".$clang->gT("Email")."</th>\n"
		. "\t\t<th>".$clang->gT("Full name")."</th>\n"
		. "\t\t<th>".$clang->gT("Password")."</th>\n"
		. "\t</tr>\n";
		$muq = "SELECT a.users_name, a.full_name, a.email, a.uid, b.users_name AS parent FROM ".db_table_name('users')." AS a LEFT JOIN ".db_table_name('users')." AS b ON a.parent_id = b.uid WHERE a.uid='{$_POST['uid']}'";	//	added by Dennis
		//echo($muq);

		$mur = db_select_limit_assoc($muq, 1);
		$usersummary .= "\t<tr><form action='$scriptname' method='post'>";
		while ($mrw = $mur->FetchRow())
		{
			$mrw = array_map('htmlspecialchars', $mrw);
			$usersummary .= "\t<td align='center'><strong>{$mrw['users_name']}</strong>\n"
			. "\t<td align='center'>\n\t\t<input type='text' name='email' value=\"{$mrw['email']}\" /></td>\n"
			. "\t<td align='center'>\n\t\t<input type='text' name='full_name' value=\"{$mrw['full_name']}\" /></td>\n"
			. "\t\t<input type='hidden' name='user' value=\"{$mrw['users_name']}\" /></td>\n"
			. "\t\t<input type='hidden' name='uid' value=\"{$mrw['uid']}\" /></td>\n";	
			$usersummary .= "\t<td align='center'>\n\t\t<input type='password' name='pass' value=\"\" /></td>\n";
		}
		$usersummary .= "\t</tr>\n\t<tr><td colspan='4' align='center'>\n"
		. "\t\t<input type='submit' value='".$clang->gT("Update")."' />\n"
		. "<input type='hidden' name='action' value='moduser' /></td></tr>\n"
		. "</form></table>\n";
	}
	else
	{
		include("access_denied.php");
	}
}

if ($action == "setuserrights")
{
	if($_SESSION['loginID'] != $_POST['uid'])
	{
		$usersummary = "<table width='100%' border='0'>\n\t<tr><td colspan='8' bgcolor='black' align='center'>\n"
		. "\t\t<strong><font color='white'>".$clang->gT("Set User Rights").": ".$_POST['user']."</td></tr>\n";

		$userlist = getuserlist();
		foreach ($userlist as $usr)
		{
			if ($usr['uid'] == $_POST['uid'])
			{
				$squery = "SELECT create_survey, configurator, create_user, delete_user, move_user, manage_template, manage_label FROM {$dbprefix}users WHERE uid={$usr['parent_id']}";	//		added by Dennis
				$sresult = $connect->Execute($squery);
				$parent = $sresult->FetchRow();

				if($parent['create_survey']) {
					$usersummary .= "\t\t<th align='center'>".$clang->gT("Create Survey")."</th>\n";
				}
				if($parent['configurator']) {
					$usersummary .= "\t\t<th align='center'>".$clang->gT("Configurator")."</th>\n";
				}
				if($parent['create_user']) {
					$usersummary .= "\t\t<th align='center'>".$clang->gT("Create User")."</th>\n";
				}
				if($parent['delete_user']) {
					$usersummary .= "\t\t<th align='center'>".$clang->gT("Delete User")."</th>\n";
				}
				if($parent['move_user']) {
					$usersummary .= "\t\t<th align='center'>".$clang->gT("Move User")."</th>\n";
				}
				if($parent['manage_template']) {
					$usersummary .= "\t\t<th align='center'>".$clang->gT("Manage Template")."</th>\n";
				}
				if($parent['manage_label']) {
					$usersummary .= "\t\t<th align='center'>".$clang->gT("Manage Labels")."</th>\n";
				}

				$usersummary .="\t\t<th></th>\n\t</tr>\n"
				."\t<tr><form method='post' action='$scriptname'></tr>"
				."<form action='$scriptname' method='post'>\n";
				//content
				if($parent['create_survey']) {
					$usersummary .= "\t\t<td align='center'><input type=\"checkbox\"  class=\"checkboxbtn\" name=\"create_survey\" value=\"create_survey\"";
					if($usr['create_survey']) {
						$usersummary .= " checked='checked' ";
					}
					$usersummary .=" /></td>\n";
				}
				if($parent['configurator']) {
					$usersummary .= "\t\t<td align='center'><input type=\"checkbox\"  class=\"checkboxbtn\" name=\"configurator\" value=\"configurator\"";
					if($usr['configurator']) {
						$usersummary .= " checked='checked' ";
					}
					$usersummary .=" /></td>\n";
				}
				if($parent['create_user']) {
					$usersummary .= "\t\t<td align='center'><input type=\"checkbox\"  class=\"checkboxbtn\" name=\"create_user\" value=\"create_user\"";
					if($usr['create_user']) {
						$usersummary .= " checked='checked' ";
					}
					$usersummary .=" /></td>\n";
				}
				if($parent['delete_user']) {
					$usersummary .= "\t\t<td align='center'><input type=\"checkbox\"  class=\"checkboxbtn\" name=\"delete_user\" value=\"delete_user\"";
					if($usr['delete_user']) {
						$usersummary .= " checked='checked' ";
					}
					$usersummary .=" /></td>\n";
				}
				if($parent['move_user']) {
					$usersummary .= "\t\t<td align='center'><input type=\"checkbox\"  class=\"checkboxbtn\" name=\"move_user\" value=\"move_user\"";
					if($usr['move_user']) {
						$usersummary .= " checked='checked' ";
					}
					$usersummary .=" /></td>\n";
				}
				if($parent['manage_template']) {
					$usersummary .= "\t\t<td align='center'><input type=\"checkbox\"  class=\"checkboxbtn\" name=\"manage_template\" value=\"manage_template\"";
					if($usr['manage_template']) {
						$usersummary .= " checked='checked' ";
					}
					$usersummary .=" /></td>\n";
				}
				if($parent['manage_label']) {
					$usersummary .= "\t\t<td align='center'><input type=\"checkbox\"  class=\"checkboxbtn\" name=\"manage_label\" value=\"manage_label\"";
					if($usr['manage_label']) {
						$usersummary .= " checked='checked' ";
					}
					$usersummary .=" /></td>\n";
				}

				$usersummary .= "\t\t\t<tr><form method='post' action='$scriptname'></tr>"	// added by Dennis
				."\t\n\t<tr><td colspan='8' align='center'>"
				."<input type='submit' value='".$clang->gT("Save Now")."' />"
				."<input type='hidden' name='action' value='userrights' />"
				."<input type='hidden' name='uid' value='{$_POST['uid']}' /></td></tr>"
				."</form>"
				. "</table>\n";
				continue;
			}	// if
		}	// foreach
	}	// if
	else
	{
		include("access_denied.php");
	}
}	// if

if($action == "setnewparents")
{
	// muss noch eingeschraenkt werden ...
	if($_SESSION['USER_RIGHT_MOVE_USER'])
	{
		$uid = $_POST['uid'];
		$newparentid = $_POST['parent'];
		$oldparent = -1;
		$query = "SELECT parent_id FROM ".db_table_name('users')." WHERE uid = ".$uid;
		$result = $connect->Execute($query) or die($connect->ErrorMsg());
		if($srow = $result->FetchRow()) {
			$oldparent = $srow['parent_id'];
		}
		$query = "SELECT create_survey, configurator, create_user, delete_user, move_user, manage_template, manage_label FROM ".db_table_name('users')." WHERE uid = ".$newparentid;
		$result = $connect->Execute($query) or die($connect->ErrorMsg());
		$srow = $result->FetchRow();
		$query = "UPDATE ".db_table_name('users')." SET parent_id = ".$newparentid.", create_survey = IF({$srow['create_survey']} = 1, create_survey, {$srow['create_survey']}), configurator = IF({$srow['configurator']} = 1, configurator, {$srow['configurator']}), create_user = IF({$srow['create_user']} = 1, create_user, {$srow['create_user']}), delete_user = IF({$srow['delete_user']} = 1, delete_user, {$srow['delete_user']}), move_user = IF({$srow['move_user']} = 1, move_user, {$srow['move_user']}), manage_template = IF({$srow['manage_template']} = 1, manage_template, {$srow['manage_template']}), manage_label = IF({$srow['manage_label']} = 1, manage_label, {$srow['manage_label']}) WHERE uid = ".$uid;
		$connect->Execute($query) or die($connect->ErrorMsg()." ".$query);
		$query = "UPDATE ".db_table_name('users')." SET parent_id = ".$oldparent." WHERE parent_id = ".$uid;
		$connect->Execute($query) or die($connect->ErrorMsg()." ".$query);
		$usersummary = "<br /><strong>".$clang->gT("Setting new Parent")."</strong><br />"
		. "<br />".$clang->gT("Set Parent successful.")."<br />"
		. "<br /><a href='$scriptname?action=editusers'>".$clang->gT("Continue")."</a><br />&nbsp;\n";
	}
	else
	{
		include("access_denied.php");
	}
}

if ($action == "editusers")
{
	$usersummary = "<table width='100%' border='0'>\n"
	. "\t\t\t\t<tr><td colspan='6' class='header'>"
	. $clang->gT("User Control")
    ."</td></tr>\n"
	. "\t<tr>\n"
	. "\t\t<th width='20%'>".$clang->gT("Username")."</th>\n"
	. "\t\t<th width='20%'>".$clang->gT("Email")."</th>\n"
	. "\t\t<th width='20%'>".$clang->gT("Full name")."</th>\n"
	. "\t\t<th width='15%'>".$clang->gT("Password")."</th>\n"
	. "\t\t<th width='15%'>".$clang->gT("Created by")."</th>\n"
	. "\t\t<th></th>\n"
	. "\t</tr>\n";

	$userlist = getuserlist();
	$ui = count($userlist);
	$usrhimself = $userlist[0];
	unset($userlist[0]);

	//	output users
	// output admin user only if the user logged in has user management rights
	if ($_SESSION['USER_RIGHT_DELETE_USER']||$_SESSION['USER_RIGHT_CREATE_USER']||$_SESSION['USER_RIGHT_MOVE_USER']){
		$usersummary .= "\t<tr class='oddrow'>\n"
		. "\t<td class='oddrow' align='center'><strong>{$usrhimself['user']}</strong></td>\n"
		. "\t<td class='oddrow' align='center'><strong>{$usrhimself['email']}</strong></td>\n"
		. "\t\t<td class='oddrow' align='center'><strong>{$usrhimself['full_name']}</strong></td>\n"
		. "\t\t<td class='oddrow' align='center'><strong>********</strong></td>\n";
		
		if(isset($usrhimself['parent_id']) && $usrhimself['parent_id']!=0) { 
			$usersummary .= "\t\t<td class='oddrow' align='center'>{$userlist[$usrhimself['parent_id']]['user']}</td>\n";
		}
		else
		{
			$usersummary .= "\t\t<td class='oddrow' align='center'><strong>---</strong></td>\n";
		}
		$usersummary .= "\t\t<td class='oddrow' align='center' style='padding:3px;'>\n";
		
		if ($_SESSION['loginID'] == "1")
		{
			$usersummary .= "\t\t\t<form method='post' action='$scriptname'>"
			."<input type='submit' value='".$clang->gT("Edit User")."' />"
			."<input type='hidden' name='action' value='modifyuser' />"
			."<input type='hidden' name='uid' value='{$usrhimself['uid']}' />"
			."</form>";
		}
		// users are allowed to delete all successor users (but the admin not himself)
		if ($usrhimself['parent_id'] != 0 && ($_SESSION['USER_RIGHT_DELETE_USER'] == 1 || ($usrhimself['uid'] == $_SESSION['loginID'])))
		{
			$usersummary .= "\t\t\t<form method='post' action='$scriptname?action=deluser'>"
			."<input type='submit' value='".$clang->gT("Delete")."' onclick='return confirm(\"".$clang->gT("Are you sure you want to delete this entry.","js")."\")' />"
			."<input type='hidden' name='action' value='deluser' />"
			."<input type='hidden' name='user' value='{$usrhimself['user']}' />"
			."<input type='hidden' name='uid' value='{$usrhimself['uid']}' />"
			."</form>";
		}
	
		$usersummary .= "\t\t</td>\n"
		. "\t</tr>\n";
	
		// empty row
		if(count($userlist) > 0) $usersummary .= "\t<tr>\n\t<td height=\"20\" colspan=\"6\"></td>\n\t</tr>";
	}

	
	// other users
	$row = 0;
	$usr_arr = $userlist;
	for($i=1; $i<=count($usr_arr); $i++)
	{
		if (!isset($bgcc)) {$bgcc="evenrow";}
		else
		{
			if ($bgcc == "evenrow") {$bgcc = "oddrow";}
			else {$bgcc = "evenrow";}
		}
		$usr = $usr_arr[$i];
		$usersummary .= "\t<tr class='$bgcc'>\n";

		$usersummary .= "\t<td class='$bgcc' align='center'>{$usr['user']}</td>\n"
		. "\t<td class='$bgcc' align='center'><a href='mailto:{$usr['email']}'>{$usr['email']}</a></td>\n"
		. "\t<td class='$bgcc' align='center'>{$usr['full_name']}</td>\n";

		// passwords of other users will not be displayed
		$usersummary .=  "\t\t<td class='$bgcc' align='center'>******</td>\n";

		// Get Parent's User Name
		$uquery = "SELECT users_name FROM ".db_table_name('users')." WHERE uid=".$usr['parent_id'];
		$uresult = db_execute_assoc($uquery);
		$userlist = array();
		$srow = $uresult->FetchRow();
		$usr['parent'] = $srow['users_name'];
		/*
		if($_SESSION['USER_RIGHT_MOVE_USER'])
		{
			$usersummary .= "\t\t<td align='center'>"
			."<form name='parentsform{$usr['uid']}'action='$scriptname?action=setnewparents' method='post'>"
			."<input type='hidden' name='uid' value='{$usr['uid']}' />";
			//."<select name='parent' size='1' onchange='document.getElementById(\"button{$usr['uid']}\").innerHTML = \"<input type=\\\"submit\\\" value=\\\"".$clang->gT("Change")."\\\">\"'>"
			//."<select name='parent' size='1' onchange='document.getElementById(\"button{$usr['uid']}\").createElement(\"input\")'>";
			if($usr['uid'] != $usrhimself['uid'])
			{
				//$usersummary .= "<option value='{$usrhimself['uid']}'";
				if($usr['parent_id'] == $usrhimself['uid']) {
					$usersummary .= $usrhimself['user'];
				}
			}
			$usersummary .= "<div id='button{$usr['uid']}'></div>\n";
			$usersummary .= "</form></td>\n";
		}
		else
		{*/
			
			
			//TODO: Find out why parent isn't set
			if (isset($usr['parent']))
			{
				$usersummary .= "\t\t<td class='$bgcc' align='center'>{$usr['parent']}</td>\n";
			} else 
			{
				$usersummary .= "\t\t<td class='$bgcc' align='center'>-----</td>\n";
			}
		//}
		
		$usersummary .= "\t\t<td class='$bgcc' align='center' style='padding:3px;'>\n";
		// users are allowed to delete all successor users (but the admin not himself)
		//  || ($usr['uid'] == $_SESSION['loginID']))
		if ($_SESSION['loginID'] == "1" || ($_SESSION['USER_RIGHT_DELETE_USER'] == 1  && $usr['parent_id'] == $_SESSION['loginID']))
		{
			$usersummary .= "\t\t\t<form method='post' action='$scriptname?action=deluser'>"
			."<input type='submit' value='".$clang->gT("Delete")."' onclick='return confirm(\"".$clang->gT("Are you sure you want to delete this entry.","js")."\")' />"
			."<input type='hidden' name='action' value='deluser' />"
			."<input type='hidden' name='user' value='{$usr['user']}' />"
			."<input type='hidden' name='uid' value='{$usr['uid']}' />"
			."</form>";
		}
		if ($_SESSION['loginID'] == "1" || ($_SESSION['USER_RIGHT_CREATE_USER'] == 1 && ($usr['parent_id'] == $_SESSION['loginID'])))
		{
			$usersummary .= "\t\t\t<form method='post' action='$scriptname'>"
			."<input type='submit' value='".$clang->gT("Set User Rights")."' />"
			."<input type='hidden' name='action' value='setuserrights' />"
			."<input type='hidden' name='user' value='{$usr['user']}' />"
			."<input type='hidden' name='uid' value='{$usr['uid']}' />"
			."</form>";
		}
		if ($_SESSION['loginID'] == "1" || $usr['uid'] == $_SESSION['loginID'] || ($_SESSION['USER_RIGHT_CREATE_USER'] == 1 && $usr['parent_id'] == $_SESSION['loginID']))
		{
			$usersummary .= "\t\t\t<form method='post' action='$scriptname'>"
			."<input type='submit' value='".$clang->gT("Edit User")."' />"
			."<input type='hidden' name='action' value='modifyuser' />"
			."<input type='hidden' name='uid' value='{$usr['uid']}' />"
			."</form>";
		}
		$usersummary .= "\t\t</td>\n"
		. "\t</tr>\n";
		$row++;
	}
    $usersummary .= "</table><br />";

	if($_SESSION['USER_RIGHT_CREATE_USER'])
	{
		$usersummary .= "\t\t<form action='$scriptname' method='post'>\n"
		. "\t\t<table width='100%' borders='0'><tr>\n"
        . "\t\t<th colspan='6'>".$clang->gT("Add User")."</th>\n"
        . "\t\t</tr><tr>\n"
		. "\t\t<td align='center' width='20%'><input type='text' name='new_user' /></td>\n"
		. "\t\t<td align='center' width='20%'><input type='text' name='new_email' /></td>\n"
		. "\t\t<td align='center' width='20%' ><input type='text' name='new_full_name' /></td><td width='15%'>&nbsp;</td><td width='15%'>&nbsp;</td>\n"
		. "\t\t<td align='center' width='15%'><input type='submit' value='".$clang->gT("Add User")."' />"
		. "<input type='hidden' name='action' value='adduser' /></td>\n"
		. "\t</tr></table></form>\n";
	}
	
}

if ($action == "addusergroup")
{
	if ($_SESSION['loginID'] == 1)
	{
		$usersummary = "<form action='$scriptname'  method='post'><table width='100%' border='0'>\n\t<tr><th colspan='2'>\n"
		. "\t\t<strong>".$clang->gT("Add User Group")."</strong></th></tr>\n"
		. "\t<tr>\n"
		. "\t\t<td><strong>".$clang->gT("Name:")."</strong></td>\n"
		. "\t\t<td><input type='text' size='50' name='group_name' /><font color='red' face='verdana' size='1'> ".$clang->gT("Required")."</font></td></tr>\n"
		. "\t<tr><td><strong>".$clang->gT("Description:")."</strong></td>\n"
		. "\t\t<td><textarea cols='50' rows='4' name='group_description'></textarea></td></tr>\n"
		. "\t<tr><td colspan='2' class='centered'><input type='submit' value='".$clang->gT("Add Group")."' />\n"
		. "\t<input type='hidden' name='action' value='usergroupindb' />\n"
		. "\t</td></table>\n"
		. "</form>\n";
	}
}

if ($action == "editusergroup")
{
	if ($_SESSION['loginID'] == 1)
	{
		$query = "SELECT * FROM ".db_table_name('user_groups')." WHERE ugid = ".$_GET['ugid']." AND owner_id = ".$_SESSION['loginID'];
		$result = db_select_limit_assoc($query, 1);
		$esrow = $result->FetchRow();
		$usersummary = "<form action='$scriptname' name='editusergroup' method='post'>"
		. "<table width='100%' border='0' class='form2columns'>\n\t<tr><th colspan='2'>\n"
		. "\t\t<strong>".$clang->gT("Edit User Group (Owner: ").$_SESSION['user'].")</strong></th></tr>\n"
		. "\t<tr>\n"
		. "\t\t<td><strong>".$clang->gT("Name:")."</strong></td>\n"
		. "\t\t<td><input type='text' size='50' name='name' value=\"{$esrow['name']}\" /></td></tr>\n"
		. "\t<tr><td><strong>".$clang->gT("Description:")."</strong></td>\n"
		. "\t\t<td><textarea cols='50' rows='4' name='description'>{$esrow['description']}</textarea></td></tr>\n"
		. "\t<tr><td colspan='2' class='centered'><input type='submit' value='".$clang->gT("Update User Group")."' />\n"
		. "\t<input type='hidden' name='action' value='editusergroupindb' />\n"
		. "\t<input type='hidden' name='owner_id' value='".$_SESSION['loginID']."' />\n"
		. "\t<input type='hidden' name='ugid' value='$ugid' />\n"
		. "\t</td></tr>\n"
		. "</table>\n"
		. "\t</form>\n";
	}
}

if ($action == "mailusergroup")
{
	$query = "SELECT a.ugid, a.name, a.owner_id, b.uid FROM ".db_table_name('user_groups') ." AS a LEFT JOIN ".db_table_name('user_in_groups') ." AS b ON a.ugid = b.ugid WHERE a.ugid = {$ugid} AND uid = {$_SESSION['loginID']} ORDER BY name";
	$result = db_execute_assoc($query);
	$crow = $result->FetchRow();


	$usersummary = "<form action='$scriptname' name='mailusergroup' method='post'>"
	. "<table width='100%' border='0' class='form2columns'>\n\t<tr><th colspan='2'>\n"
	. "\t\t<strong>".$clang->gT("Mail to all Members")."</strong></th></tr>\n"
	. "\t<tr>\n"
	. "\t\t<td><strong>".$clang->gT("Send me a copy:")."</strong></td>\n"
	. "\t\t<td><input name='copymail' type='checkbox' class='checkboxbtn' value='1' /></td></tr>\n"
	. "\t<tr>\n"
	. "\t\t<td><strong>".$clang->gT("Subject:")."</strong></td>\n"
	. "\t\t<td><input type='text' size='50' name='subject' value='' /></td></tr>\n"
	. "\t<tr><td><strong>".$clang->gT("Message:")."</strong></td>\n"
	. "\t\t<td><textarea cols='50' rows='4' name='body'></textarea></td></tr>\n"
	. "\t<tr><td colspan='2' class='centered'><input type='submit' value='".$clang->gT("Send")."' />\n"
	. "<input type='reset' value='".$clang->gT("Reset")."' /><br />"
	. "\t<input type='hidden' name='action' value='mailsendusergroup' />\n"
	. "\t<input type='hidden' name='ugid' value='$ugid' />\n"
	. "\t</td></tr>\n"
	. "</table>\n"
	. "\t</form>\n";
}

if ($action == "delusergroup")
{
		if ($_SESSION['loginID'] == 1)
	{
	$usersummary = "<br /><strong>".$clang->gT("Deleting User Group")."</strong><br />\n";

	if(!empty($_GET['ugid']) && $_GET['ugid'] > -1)
	{
		$query = "SELECT ugid, name, owner_id FROM ".db_table_name('user_groups')." WHERE ugid = ".$_GET['ugid']." AND owner_id = ".$_SESSION['loginID'];
		$result = db_select_limit_assoc($query, 1);
		if($result->RecordCount() > 0)
		{
			$row = $result->FetchRow();

			$remquery = "DELETE FROM ".db_table_name('user_groups')." WHERE ugid = {$_GET['ugid']} AND owner_id = {$_SESSION['loginID']}";
			if($connect->Execute($remquery))
			{
				$usersummary .= "<br />".$clang->gT("Group Name").": {$row['name']}<br />\n";
			}
			else
			{
				$usersummary .= "<br />".$clang->gT("Could not delete user group.")."<br />\n";
			}
		}
		else
		{
			include("access_denied.php");
		}
	}
	else
	{
		$usersummary .= "<br />".$clang->gT("Could not delete user group. No group selected.")."<br />\n";
	}
	$usersummary .= "<br /><a href='$scriptname?action=editusergroups'>".$clang->gT("Continue")."</a><br />&nbsp;\n";
	}
}

if ($action == "usergroupindb") {
	$usersummary = "<br /><strong>".$clang->gT("Adding User Group")."...</strong><br />\n";

	$group_name = $_POST['group_name'];
	$group_description = $_POST['group_description'];
	if(isset($group_name) && strlen($group_name) > 0)
	{
		$ugid = addUserGroupInDB($group_name, $group_description);
		if($ugid > 0)
		{
			$usersummary .= "<br />".$clang->gT("Group Name").": {$group_name}<br />\n";

			if(isset($group_description) && strlen($group_description) > 0)
			{
				$usersummary .= $clang->gT("Description: ").$group_description."<br />\n";
			}

         	$usersummary .= "<br /><strong>".$clang->gT("User group successfully added!")."</strong><br />\n";
			$usersummary .= "<br /><a href='$scriptname?action=editusergroups&amp;ugid={$ugid}'>".$clang->gT("Continue")."</a><br />&nbsp;\n";
		}
		else
		{
			$usersummary .= "<br /><strong>".$clang->gT("Failed to add Group!")."</strong><br />\n"
			. $clang->gT("Group already exists!")."<br />\n"
			. "<br /><a href='$scriptname?action=editusergroups'>".$clang->gT("Continue")."</a><br />&nbsp;\n";
		}
	}
	else
	{
		$usersummary .= "<br /><strong>".$clang->gT("Failed to add Group!")."</strong><br />\n"
		. $clang->gT("Group name was not supplied!")."<br />\n"
		. "<br /><a href='$scriptname?action=addusergroup'>".$clang->gT("Continue")."</a><br />&nbsp;\n";
	}
}

if ($action == "mailsendusergroup")
{
	$usersummary = "<br /><strong>".$clang->gT("Mail to all Members")."</strong><br />\n";

	// user must be in user group
	$query = "SELECT uid FROM ".db_table_name('user_in_groups') ." WHERE ugid = {$ugid} AND uid = {$_SESSION['loginID']}";
	$result = db_execute_assoc($query);

	if($result->RecordCount() > 0)
	{

    	$eguquery = "SELECT * FROM ".db_table_name("user_in_groups")." AS a INNER JOIN ".db_table_name("users")." AS b ON a.uid = b.uid WHERE ugid = " . $ugid . " AND b.uid != {$_SESSION['loginID']} ORDER BY b.users_name";
    	$eguresult = db_execute_assoc($eguquery);
    	$addressee = '';
    	$to = '';
    	while ($egurow = $eguresult->FetchRow())
    	{
    		$to .= $egurow['users_name']. ' <'.$egurow['email'].'>'. ', ' ;
    		$addressee .= $egurow['users_name'].', ';
    	}
    	$to = substr("$to", 0, -2);
    	$addressee = substr("$addressee", 0, -2);

		$from_user = "SELECT email, users_name FROM ".db_table_name("users")." WHERE uid = " .$_SESSION['loginID'];
		$from_user_result = db_execute_assoc($from_user);
		$from_user_row = $from_user_result->FetchRow();

		$from = $from_user_row['users_name'].' <'.$from_user_row['email'].'> ';

		$ugid = $_POST['ugid'];
		$body = $_POST['body'];
		$subject = $_POST['subject'];

		if(isset($_POST['copymail']) && $_POST['copymail'] == 1)
		{
			$to .= ", " . $from;
		}

		$body = str_replace("\n.", "\n..", $body);
		$body = wordwrap($body, 70);

    
        //echo $body . '-'.$subject .'-'.'<pre>'.htmlspecialchars($to).'</pre>'.'-'.$from;
		if (MailTextMessage( $body, $subject, $to, $from,''))
		{
			$usersummary = "<br /><strong>".$clang->gT("Message(s) sent successfully!")."</strong><br />\n"
			. "<br />".$clang->gT("To:")." $addressee<br />\n"
			. "<br /><a href='$scriptname?action=editusergroups&amp;ugid={$ugid}'>".$clang->gT("Continue")."</a><br />&nbsp;\n";
		}
		else
		{
			$usersummary .= "<br /><strong>".$clang->gT("Mail not sent!")."</strong><br />\n";
			$usersummary .= "<br /><a href='$scriptname?action=mailusergroup&amp;ugid={$ugid}'>".$clang->gT("Continue")."</a><br />&nbsp;\n";
		}
	}
	else
	{
		include("access_denied.php");
	}
}

if ($action == "editusergroupindb"){

	$ugid = $_POST['ugid'];
	$name = $_POST['name'];
	$description = $_POST['description'];

	if(updateusergroup($name, $description, $ugid))
	{
		$usersummary = "<br /><strong>".$clang->gT("Edit User Group Successfully!")."</strong><br />\n";
		$usersummary .= "<br />".$clang->gT("Name").": {$name}<br />\n";
		$usersummary .= $clang->gT("Description: ").$description."<br />\n";
		$usersummary .= "<br /><a href='$scriptname?action=editusergroups&amp;ugid={$ugid}'>".$clang->gT("Continue")."</a><br />&nbsp;\n";
	}
	else $usersummary .= "<br /><strong>".$clang->gT("Failed to update!")."</strong><br />\n"
	. "<br /><a href='$scriptname?action=editusergroups'>".$clang->gT("Continue")."</a><br />&nbsp;\n";
}

if ($action == "editusergroups"  )
{
	if(isset($_GET['ugid']))
	{
		$ugid = $_GET['ugid'];

		$query = "SELECT a.ugid, a.name, a.owner_id, a.description, b.uid FROM ".db_table_name('user_groups') ." AS a LEFT JOIN ".db_table_name('user_in_groups') ." AS b ON a.ugid = b.ugid WHERE a.ugid = {$ugid} AND uid = {$_SESSION['loginID']} ORDER BY name";
		$result = db_execute_assoc($query);
		$crow = $result->FetchRow();

		if($result->RecordCount() > 0)
		{

			if(!empty($crow['description']))
			{
				$usergroupsummary .= "<table width='100%' border='0'>\n"
				. "\t\t\t\t<tr><td align='justify' colspan='2' height='4'>"
				. "<font size='2' ><strong>".$clang->gT("Description: ")."</strong>"
				. "{$crow['description']}</font></td></tr>\n"
				. "</table>";
			}


			$eguquery = "SELECT * FROM ".db_table_name("user_in_groups")." AS a INNER JOIN ".db_table_name("users")." AS b ON a.uid = b.uid WHERE ugid = " . $ugid . " ORDER BY b.users_name";
			$eguresult = db_execute_assoc($eguquery);
			$usergroupsummary .= "<table  width='100%' border='0'>\n"
			. "\t<tr>\n"
			. "\t\t<th>".$clang->gT("Username")."</th>\n"
			. "\t\t<th>".$clang->gT("Email")."</th>\n"
			. "\t\t<th width='25%'>".$clang->gT("Action")."</th>\n"
			. "\t</tr>\n";

			$query2 = "SELECT ugid FROM ".db_table_name('user_groups')." WHERE ugid = ".$ugid." AND owner_id = ".$_SESSION['loginID'];
			$result2 = db_select_limit_assoc($query2, 1);
			$row2 = $result2->FetchRow();

			$row = 1;
			$usergroupentries='';
			while ($egurow = $eguresult->FetchRow())
			{
				if (!isset($bgcc)) {$bgcc="evenrow";}
				else
				{
					if ($bgcc == "evenrow") {$bgcc = "oddrow";}
					else {$bgcc = "evenrow";}
				}

				if($egurow['uid'] == $crow['owner_id'])
				{
					$usergroupowner = "\t<tr class='$bgcc'>\n"
					. "\t<td align='center'><strong>{$egurow['users_name']}</strong></td>\n"
					. "\t<td align='center'><strong>{$egurow['email']}</strong></td>\n"
					. "\t\t<td align='center'>&nbsp;</td></tr>\n";
					continue;
				}
				//	output users
				
				if($row == 1){ $usergroupentries .= "\t<tr>\n\t<td height=\"20\" colspan=\"6\"></td>\n\t</tr>"; $row++;}
				//if(($row % 2) == 0) $usergroupentries .= "\t<tr  bgcolor='#999999'>\n";
				//else $usergroupentries .= "\t<tr>\n";
				$usergroupentries .= "\t<tr class='$bgcc'>\n";
				$usergroupentries .= "\t<td align='center'>{$egurow['users_name']}</td>\n"
				. "\t<td align='center'>{$egurow['email']}</td>\n"
				. "\t\t<td align='center' style='padding-top:10px;'>\n";

				// owner and not himself    or    not owner and himself
				if((isset($row2['ugid']) && $_SESSION['loginID'] != $egurow['uid']) || (!isset($row2['ugid']) && $_SESSION['loginID'] == $egurow['uid']))
				{
					$usergroupentries .= "\t\t\t<form method='post' action='$scriptname?action=deleteuserfromgroup&amp;ugid=$ugid'>"
					." <input type='submit' value='".$clang->gT("Delete")."' onclick='return confirm(\"".$clang->gT("Are you sure you want to delete this entry.","js")."\")' />"
					." <input type='hidden' name='user' value='{$egurow['users_name']}' />"
					." <input name='uid' type='hidden' value='{$egurow['uid']}' />"
					." <input name='ugid' type='hidden' value='{$ugid}' />";
				}
				$usergroupentries .= "</form>"
				. "\t\t</td>\n"
				. "\t</tr>\n";
				$row++;
			}
			$usergroupsummary .= $usergroupowner;
            if (isset($usergroupentries)) {$usergroupsummary .= $usergroupentries;};

			if(isset($row2['ugid']))
			{
				$usergroupsummary .= "\t\t<form action='$scriptname?ugid={$ugid}' method='post'>\n"
				. "\t\t<tr><td></td>\n"
				. "\t\t\t<td></td>"
				. "\t\t\t\t<td align='center'><select name='uid'>\n"
				. getgroupuserlist()
				. "\t\t\t\t</select>\n"
				. "\t\t\t\t<input type='submit' value='".$clang->gT("Add User")."' />\n"
				. "\t\t\t\t<input type='hidden' name='action' value='addusertogroup' /></td></form>\n"
				. "\t\t\t</td>\n"
				. "\t\t</tr>\n"
				. "\t</form>\n";
			}
		}
		else
		{
			include("access_denied.php");
		}
	}
}

if($action == "deleteuserfromgroup") {
	$ugid = $_POST['ugid'];
	$uid = $_POST['uid'];
	$usersummary = "<br /><strong>".$clang->gT("Delete User")."</strong><br />\n";

	$query = "SELECT ugid, owner_id FROM ".db_table_name('user_groups')." WHERE ugid = ".$ugid." AND ((owner_id = ".$_SESSION['loginID']." AND owner_id != ".$uid.") OR (owner_id != ".$_SESSION['loginID']." AND $uid = ".$_SESSION['loginID']."))";
	$result = db_execute_assoc($query);
	if($result->RecordCount() > 0)
	{
		$remquery = "DELETE FROM ".db_table_name('user_in_groups')." WHERE ugid = {$ugid} AND uid = {$uid}";
		if($connect->Execute($remquery))
		{
			$usersummary .= "<br />".$clang->gT("Username").": {$_POST['user']}<br />\n";
		}
		else
		{
			$usersummary .= "<br />".$clang->gT("Could not delete user. User was not supplied.")."<br />\n";
		}
	}
	else
	{
		include("access_denied.php");
	}
	if($_SESSION['loginID'] != $_POST['uid'])
	{
		$usersummary .= "<br /><a href='$scriptname?action=editusergroups&amp;ugid=$ugid'>".$clang->gT("Continue")."</a><br />&nbsp;\n";
	}
	else
	{
		$usersummary .= "<br /><a href='$scriptname?action=editusergroups'>".$clang->gT("Continue")."</a><br />&nbsp;\n";
	}
}




if($action == "addusertogroup")
{
	$addsummary = "<br /><strong>".$clang->gT("Adding User to group")."...</strong><br />\n";

	$query = "SELECT ugid, owner_id FROM ".db_table_name('user_groups')." WHERE ugid = ".$_GET['ugid']." AND owner_id = ".$_SESSION['loginID']." AND owner_id != ".$_POST['uid'];
	$result = db_execute_assoc($query);
	if($result->RecordCount() > 0)
	{
		if($_POST['uid'] > 0)
		{
			$isrquery = "INSERT INTO {$dbprefix}user_in_groups VALUES(".$_GET['ugid'].",". $_POST['uid'].")";
			$isrresult = $connect->Execute($isrquery);

			if($isrresult)
			{
				$addsummary .= "<br />".$clang->gT("User added.")."<br />\n";
			}
			else  // ToDo: for this to happen the keys on the table must still be set accordingly
			{
				// Username already exists.
				$addsummary .= "<br /><strong>".$clang->gT("Failed to add User.")."</strong><br />\n" . " " . $clang->gT("Username already exists.")."<br />\n";
			}
			$addsummary .= "<br /><a href='$scriptname?action=editusergroups&amp;ugid=".$_GET['ugid']."'>".$clang->gT("Continue")."</a><br />&nbsp;\n";
		}
		else
		{
			$addsummary .= "<br /><strong>".$clang->gT("Failed to add User.")."</strong><br />\n" . " " . $clang->gT("No Username selected.")."<br />\n";
			$addsummary .= "<br /><a href='$scriptname?action=editusergroups&amp;ugid=".$_GET['ugid']."'>".$clang->gT("Continue")."</a><br />&nbsp;\n";
		}
	}
	else
	{
		include("access_denied.php");
	}
}
