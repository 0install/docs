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

include_once("login_check.php");  //Login Check dies also if the script is started directly
require_once($homedir."/classes/core/sha256.php");

if (!isset($_SESSION['loginID']))
{
	if($action == "forgotpass")
	{
		$loginsummary = "<br /><strong>".$clang->gT("Forgot Password")."</strong><br />\n";

		if (isset($_POST['user']) && isset($_POST['email']))
		{
			include("database.php");
			$query = "SELECT users_name, password, uid FROM ".db_table_name('users')." WHERE users_name=".$connect->qstr($_POST['user'])." AND email=".$connect->qstr($_POST['email']);
			$result = db_select_limit_assoc($query, 1) or die ($query."<br />".$connect->ErrorMsg());

			if ($result->RecordCount() < 1)
			{
				// wrong or unknown username and/or email
				$loginsummary .= "<br />".$clang->gT("User name and/or email not found!")."<br />";
				$loginsummary .= "<br /><br /><a href='$scriptname?action=forgotpassword'>".$clang->gT("Continue")."</a><br />&nbsp;\n";
			}
			else
			{
				$fields = $result->FetchRow();

				// send Mail
				$new_pass = createPassword();
				$body = $clang->gT("Your data:") . "<br />\n";;
				$body .= $clang->gT("Username") . ": " . $fields['users_name'] . "<br />\n";
				$body .= $clang->gT("New Password") . ": " . $new_pass . "<br />\n";

				$subject = 'User Data';
				$to = $_POST['email'];
				$from = $siteadminemail;
				$sitename = $siteadminname;

				if(MailTextMessage($body, $subject, $to, $from, $sitename))
				{
					$query = "UPDATE ".db_table_name('users')." SET password='".SHA256::hash($new_pass)."' WHERE uid={$fields['uid']}";
					$connect->Execute($query);
					$loginsummary .= "<br />".$clang->gT("Username").": {$fields['users_name']}<br />".$clang->gT("Email").": {$_POST['email']}<br />";
					$loginsummary .= "<br />".$clang->gT("An email with your login data was sent to you.");
					$loginsummary .= "<br /><br /><a href='$scriptname'>".$clang->gT("Continue")."</a><br />&nbsp;\n";
				}
				else
				{
					$tmp = str_replace("{NAME}", "<strong>".$fields['users_name']."</strong>", $clang->gT("Email to {NAME} ({EMAIL}) failed."));
					$loginsummary .= "<br />".str_replace("{EMAIL}", $_POST['email'], $tmp) . "<br />";
					$loginsummary .= "<br /><br /><a href='$scriptname?action=forgotpassword'>".$clang->gT("Continue")."</a><br />&nbsp;\n";
				}
			}
		}
	}
	elseif($action == "login")	// normal login
	{
		$loginsummary = "<br /><strong>".$clang->gT("Logging in...")."</strong><br />\n";

		if (isset($_POST['user']) && isset($_POST['password']))
		{
			include("database.php");
			$query = "SELECT uid, users_name, password, parent_id, email, lang FROM ".db_table_name('users')." WHERE users_name=".$connect->qstr($_POST['user']);
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $connect->SelectLimit($query, 1) or die ($query."<br />".$connect->ErrorMsg());
			if ($result->RecordCount() < 1)
			{
				// wrong or unknown username 
				$loginsummary .= "<br />".$clang->gT("Incorrect User name and/or Password!")."<br />";
				$loginsummary .= "<br /><br /><a href='$scriptname'>".$clang->gT("Continue")."</a><br />&nbsp;\n";

			}
			else
			{
				$fields = $result->FetchRow();
				if (SHA256::hash($_POST['password']) == $fields['password'])
				{
					// Anmeldung ERFOLGREICH
                    if (strtolower($_POST['password'])=='password') {$_SESSION['pw_notify']=true;} else  {$_SESSION['pw_notify']=false;} // Check if the user has changed his default password
					$_SESSION['loginID'] = intval($fields['uid']);
					$_SESSION['user'] = $fields['users_name'];
					if (isset($_POST['loginlang']) && $_POST['loginlang'])
					{
						$_SESSION['adminlang'] = $_POST['loginlang'];
						$clang = new limesurvey_lang($_SESSION['adminlang']);
						$uquery = "UPDATE {$dbprefix}users "
						. "SET lang='{$_SESSION['adminlang']}' "
						. "WHERE uid={$_SESSION['loginID']}";
						$uresult = $connect->Execute($uquery);
					}
					else
					{
						$_SESSION['adminlang'] = $fields['lang'];
						$clang = new limesurvey_lang($_SESSION['adminlang']);
					}
					$login = true;

					$loginsummary .= "<br />" .str_replace("{NAME}", $_SESSION['user'], $clang->gT("Welcome {NAME}")) . "<br />";
					$loginsummary .= $clang->gT("You logged in successfully.");

					if (isset($_POST['refererargs']) && $_POST['refererargs'] &&
						strpos($_POST['refererargs'], "action=logout") === FALSE)
					{
						$_SESSION['metaHeader']="<meta http-equiv=\"refresh\""
						. " content=\"1;URL={$scriptname}?".$_POST['refererargs']."\" />";
						$loginsummary .= "<br /><font size='1'><i>".$clang->gT("Reloading Screen. Please wait.")."</i></font>\n";
					}
					$loginsummary .= "<br /><br />\n";
					GetSessionUserRights($_SESSION['loginID']);
				}
				else
				{
					$loginsummary .= "<br />".$clang->gT("Incorrect User name and/or Password!")."<br />";
					$loginsummary .= "<br /><br /><a href='$scriptname'>".$clang->gT("Continue")."</a><br />&nbsp;\n";
				}
			}
		}
	}
}
elseif ($action == "logout")
{
//	$logoutsummary = "<br /><strong>".$clang->gT("Logout")."</strong><br />\n";

	killSession();

	$logoutsummary = $clang->gT("Logout successful.");
//	$logoutsummary .= "<br /><br /><a href='$scriptname'>".$clang->gT("Main Admin Screen")."</a><br />&nbsp;\n";
}

elseif ($action == "adduser" && $_SESSION['USER_RIGHT_CREATE_USER'])
{
	$addsummary = "<br /><strong>".$clang->gT("Add User")."</strong><br />\n";

	$new_user = html_entity_decode($_POST['new_user']);
	$new_email = html_entity_decode($_POST['new_email']);
	$new_full_name = html_entity_decode($_POST['new_full_name']);
    $new_user = $_POST['new_user'];
    $new_email = $_POST['new_email'];
    $new_full_name = html_entity_decode($_POST['new_full_name']);
	$valid_email = true;

	if(!validate_email($new_email))
	{
		$valid_email = false;
		$addsummary .= "<br /><strong>".$clang->gT("Failed to add User.")."</strong><br />\n" . " " . $clang->gT("Email address ist not valid.")."<br />\n";
	}
	if(empty($new_user))
	{
		if($valid_email) $addsummary .= "<br /><strong>".$clang->gT("Failed to add User.")."</strong><br />\n" . " ";
		$addsummary .= $clang->gT("Username was not supplied.")."<br />\n";
	}
	elseif($valid_email)
	{
		$new_pass = createPassword();
		$uquery = "INSERT INTO {$dbprefix}users (users_name, password,full_name,parent_id,lang,email,create_survey,create_user,delete_user,move_user,configurator,manage_template,manage_label) VALUES ('{$new_user}', '".SHA256::hash($new_pass)."', '{$new_full_name}', {$_SESSION['loginID']}, '{$defaultlang}', '{$new_email}',0,0,0,0,0,0,0)";
		$uresult = $connect->Execute($uquery);

		if($uresult)
		{
			$newqid = $connect->Insert_ID();

			// add new user to userlist
			$squery = "SELECT uid, users_name, password, parent_id, email, create_survey, configurator, create_user, delete_user, move_user, manage_template, manage_label FROM ".db_table_name('users')." WHERE uid='{$newqid}'";			//added by Dennis
			$sresult = db_execute_assoc($squery);
			$srow = $sresult->FetchRow();
			$userlist = getuserlist();
			array_push($userlist, array("user"=>$srow['users_name'], "uid"=>$srow['uid'], "email"=>$srow['email'],
			"password"=>$srow["password"], "parent_id"=>$srow['parent_id'], // "level"=>$level,
			"create_survey"=>$srow['create_survey'], "configurator"=>$srow['configurator'], "create_user"=>$srow['create_user'],
			"delete_user"=>$srow['delete_user'], "move_user"=>$srow['move_user'], "manage_template"=>$srow['manage_template'],
			"manage_label"=>$srow['manage_label']));

			// send Mail
			$body = $clang->gT("You were signed in on the site")." ".$sitename."<br />\n";
			$body .= $clang->gT("Your data:")."<br />\n";
			$body .= $clang->gT("Username") . ": " . $new_user . "<br />\n";
			$body .= $clang->gT("Password") . ": " . $new_pass . "<br />\n";
			$body .= "<a href='" . $homeurl . "/admin.php'>".$clang->gT("Login here")."</a><br />\n";

			$subject = 'Registration';
			$to = $new_email;
			$from = $siteadminemail;
			$sitename = $siteadminname;

			if(MailTextMessage($body, $subject, $to, $from, $sitename, true))
			{
				$addsummary .= "<br />".$clang->gT("Username").": $new_user<br />".$clang->gT("Email").": $new_email<br />";
				$addsummary .= "<br />".$clang->gT("An email with a generated password was sent to the user.");
			}
			else
			{
				// Muss noch mal gesendet werden oder andere M??glichkeit
				$tmp = str_replace("{NAME}", "<strong>".$new_user."</strong>", $clang->gT("Email to {NAME} ({EMAIL}) failed."));
				$addsummary .= "<br />".str_replace("{EMAIL}", $new_email, $tmp) . "<br />";
			}

			$addsummary .= "<br />\t\t\t<form method='post' action='$scriptname'>"
			."<input type='submit' value='".$clang->gT("Set User Rights")."'>"
			."<input type='hidden' name='action' value='setuserrights'>"
			."<input type='hidden' name='user' value='{$new_user}'>"
			."<input type='hidden' name='uid' value='{$newqid}'>"
			."</form>";
		}
		else{
			$addsummary .= "<br /><strong>".$clang->gT("Failed to add User.")."</strong><br />\n" . " " . $clang->gT("Username and/or email address already exists.")."<br />\n";
		}
	}
	$addsummary .= "<br /><a href='$scriptname?action=editusers'>".$clang->gT("Continue")."</a><br />&nbsp;\n";
}

elseif ($action == "deluser" && ($_SESSION['USER_RIGHT_DELETE_USER'] || ($_POST['uid'] == $_SESSION['loginID'])))
{
	$addsummary = "<br /><strong>".$clang->gT("Deleting User")."</strong><br />\n";

	$adminquery = "SELECT uid FROM {$dbprefix}users WHERE parent_id=0";
	$adminresult = db_select_limit_assoc($adminquery, 1);
	$row=$adminresult->FetchRow();

	if($row['uid'] == $_POST['uid'])	// it's the superadmin !!!
	{
		$addsummary .= "<br />".$clang->gT("Admin cannot be deleted!")."<br />\n";
	}
	else
	{
		if (isset($_POST['uid']))
		{
			// is the user allowed to delete?
			$userlist = getuserlist();
			foreach ($userlist as $usr)
			{
				if ($usr['uid'] == $_POST['uid'])
				{
					$isallowed = true;
					continue;
				}
			}

			if($isallowed)
			{
				// Wenn ein Benutzer gel??scht wird, werden die von ihm erstellten Benutzer dem Benutzer
				// zugeordnet, von dem er selbst erstellt wurde
				$squery = "SELECT parent_id FROM {$dbprefix}users WHERE uid={$_POST['uid']}";
				$sresult = $connect->Execute($squery);
				$fields = $sresult->FetchRow($sresult);

				if (isset($fields[0]))
				{
					$uquery = "UPDATE ".db_table_name('users')." SET parent_id={$fields[0]} WHERE parent_id={$_POST['uid']}";	//		added by Dennis
					$uresult = $connect->Execute($uquery);
				}

				//DELETE USER FROM TABLE
				$dquery="DELETE FROM {$dbprefix}users WHERE uid={$_POST['uid']}";	//	added by Dennis
				$dresult=$connect->Execute($dquery);

				if($_POST['uid'] == $_SESSION['loginID']) killSession();	// user deleted himself

				$addsummary .= "<br />".$clang->gT("Username").": {$_POST['user']}<br />\n";
			}
			else
			{
				include("access_denied.php");
				//$addsummary .= "<br />".$clang->gT("You are not allowed to perform this operation!")."<br />\n";
			}
		}
		else
		{
			$addsummary .= "<br />".$clang->gT("Could not delete user. User was not supplied.")."<br />\n";
		}
	}
	$addsummary .= "<br /><br /><a href='$scriptname?action=editusers'>".$clang->gT("Continue")."</a><br />&nbsp;\n";
}

elseif ($action == "moduser")
{
	
	$addsummary = "<br /><strong>".$clang->gT("Modifying User")."</strong><br />\n";

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
	
	if($_POST['uid'] == $_SESSION['loginID'] || $_SESSION['loginID'] == 1 || $parent['create_user'] == 1)
	{
		$users_name = html_entity_decode($_POST['user']);
		$email = html_entity_decode($_POST['email']);
		$pass = html_entity_decode($_POST['pass']);
		$full_name = html_entity_decode($_POST['full_name']);
		$valid_email = true;

		if(!validate_email($email))
		{
			$valid_email = false;
			$failed = true;
			$addsummary .= "<br /><strong>".$clang->gT("Could not modify User Data.")."</strong><br />\n" . " ".$clang->gT("Email address ist not valid.")."<br />\n";
		}
		elseif($valid_email)
		{
			$failed = false;
			if(empty($pass))
			{
				$uquery = "UPDATE ".db_table_name('users')." SET email='{$email}', full_name='{$full_name}' WHERE uid={$_POST['uid']}";
			} else {
				$uquery = "UPDATE ".db_table_name('users')." SET email='{$email}', full_name='{$full_name}', password='".SHA256::hash($pass)."' WHERE uid={$_POST['uid']}";
			}
			
			$uresult = $connect->Execute($uquery);

			if($uresult && empty($pass))
			{
				$addsummary .= "<br />".$clang->gT("Username").": $users_name<br />".$clang->gT("Password").": {".$clang->gT("Unchanged")."}<br />\n";
			} elseif($uresult && !empty($pass))
			{
				$addsummary .= "<br />".$clang->gT("Username").": $users_name<br />".$clang->gT("Password").": $pass<br />\n";
			}
			else
			{
				// Username and/or email adress already exists.
				$addsummary .= "<br /><strong>".$clang->gT("Could not modify User Data.")."</strong><br />\n" . " ".$clang->gT("Email address already exists.")."<br />\n";
			}
		}
		if($failed)
		{
			$addsummary .= "<br /><br /><form method='post' action='$scriptname'>"
			."<input type='submit' value='".$clang->gT("Back")."'>"
			."<input type='hidden' name='action' value='modifyuser'>"
			."<input type='hidden' name='uid' value='{$_POST['uid']}'>"
			."</form>";
		}
		else
		{
			$addsummary .= "<br /><br /><a href='$scriptname?action=editusers'>".$clang->gT("Continue")."</a><br />&nbsp;\n";
		}
	}
	else
	{
		include("access_denied.php");
	}
}

elseif ($action == "userrights")
{
	$addsummary = "<br /><strong>".$clang->gT("Set User Rights")."</strong><br />\n";

	if($_POST['uid'] != $_SESSION['loginID'])
	{
		$userlist = getuserlist();
		foreach ($userlist as $usr)
		{
			if ($usr['uid'] == $_POST['uid'])
			{
				$isallowed = true;
				continue;
			}
		}

		if($isallowed)
		{
			$rights = array();

			if(isset($_POST['create_survey']))$rights['create_survey']=1;		else $rights['create_survey']=0;
			if(isset($_POST['configurator']))$rights['configurator']=1;			else $rights['configurator']=0;
			if(isset($_POST['create_user']))$rights['create_user']=1;			else $rights['create_user']=0;
			if(isset($_POST['delete_user']))$rights['delete_user']=1;			else $rights['delete_user']=0;
			if(isset($_POST['move_user']))$rights['move_user']=1;			else $rights['move_user']=0;
			if(isset($_POST['manage_template']))$rights['manage_template']=1;	else $rights['manage_template']=0;
			if(isset($_POST['manage_label']))$rights['manage_label']=1;			else $rights['manage_label']=0;

			setuserrights($_POST['uid'], $rights);
			$addsummary .= "<br />".$clang->gT("Update user rights successful.")."<br />\n";
			$addsummary .= "<br /><br /><a href='$scriptname?action=editusers'>".$clang->gT("Continue")."</a><br />&nbsp;\n";
		}
		else
		{
			include("access_denied.php");
		}
	}
	else
	{
		$addsummary .= "<br />".$clang->gT("You are not allowed to change your own rights!")."<br />\n";
		$addsummary .= "<br /><br /><a href='$scriptname?action=editusers'>".$clang->gT("Continue")."</a><br />&nbsp;\n";
	}
}

?>
