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

$date = date('YmdHis'); //'Hi' adds 24hours+minutes to name to allow multiple deactiviations in a day
$deactivateoutput='';
if (!isset($_GET['ok']) || !$_GET['ok'])
{
	$deactivateoutput .= "<br />\n<table class='alertbox'>\n";
	$deactivateoutput .= "\t\t\t\t<tr ><td height='4'><strong>".$clang->gT("Deactivate Survey")." ($surveyid)</strong></td></tr>\n";
	$deactivateoutput .= "\t<tr>\n";
	$deactivateoutput .= "\t\t<td align='center' bgcolor='#FFEEEE'>\n";
	$deactivateoutput .= "\t\t\t<font color='red'><strong>";
	$deactivateoutput .= $clang->gT("Warning")."<br />".$clang->gT("READ THIS CAREFULLY BEFORE PROCEEDING");
	$deactivateoutput .= "\t\t</strong></font></td>\n";
	$deactivateoutput .= "\t</tr>\n";
	$deactivateoutput .= "\t<tr>";
	$deactivateoutput .= "\t\t<td>\n";
	$deactivateoutput .= "\t\t\t".$clang->gT("In an active survey, a table is created to store all the data-entry records.")."\n";
	$deactivateoutput .= "\t\t\t<p>".$clang->gT("When you deactivate a survey all the data entered in the original table will be moved elsewhere, and when you activate the survey again, the table will be empty. You will not be able to access this data using LimeSurvey any more.")."</p>\n";
	$deactivateoutput .= "\t\t\t<p>".$clang->gT("Deactivated survey data can only be accessed by system administrators using a Database data access tool like phpmyadmin. If your survey uses tokens, this table will also be renamed and will only be accessible by system administrators.")."</p>\n";
	$deactivateoutput .= "\t\t\t<p>".$clang->gT("Your responses table will be renamed to:")." {$dbprefix}old_{$_GET['sid']}_{$date}</p>\n";
	$deactivateoutput .= "\t\t\t<p>".$clang->gT("Also you should export your responses before deactivating.")."</p>\n";
	$deactivateoutput .= "\t\t</td>\n";
	$deactivateoutput .= "\t</tr>\n";
	$deactivateoutput .= "\t<tr>\n";
	$deactivateoutput .= "\t\t<td align='center'>\n";
	$deactivateoutput .= "\t\t\t<input type='submit' value='".$clang->gT("Deactivate Survey")."' onclick=\"window.open('$scriptname?action=deactivate&amp;ok=Y&amp;sid={$_GET['sid']}', '_top')\">\n";
	$deactivateoutput .= "\t\t<br />&nbsp;</td>\n";
	$deactivateoutput .= "\t</tr>\n";
	$deactivateoutput .= "</table><br />&nbsp;\n";
}

else
{
	//See if there is a tokens table for this survey
	$tablelist = $connect->MetaTables();
	if (in_array("{$dbprefix}tokens_{$_GET['sid']}", $tablelist))
	{
		$toldtable="{$dbprefix}tokens_{$_GET['sid']}";
		$tnewtable="{$dbprefix}old_tokens_{$_GET['sid']}_{$date}";
		$tdeactivatequery = db_rename_table($toldtable ,$tnewtable);
		$tdeactivateresult = $connect->Execute($tdeactivatequery) or die ("Couldn't deactivate tokens table because:<br />".htmlspecialchars($connect->ErrorMsg())."<br /><br />Survey was not deactivated either.<br /><br /><a href='$scriptname?sid={$_GET['sid']}'>".$clang->gT("Main Admin Screen")."</a>");
	}

    // IF there are any records in the saved_control table related to this survey, they have to be deleted
    $query = "DELETE FROM {$dbprefix}saved_control WHERE sid={$_GET['sid']}";
    $result = $connect->Execute($query);
    
	$oldtable="{$dbprefix}survey_{$_GET['sid']}";
	$newtable="{$dbprefix}old_survey_{$_GET['sid']}_{$date}";

	//Update the auto_increment value from the table before renaming
	$new_autonumber_start=0;
	$query = "SELECT id FROM $oldtable ORDER BY id desc";
	$result = db_select_limit_assoc($query, 1,-1, false, false);
	if ($result)
	{
        while ($row=$result->FetchRow())
    	{
    		if (strlen($row['id']) > 12) //Handle very large autonumbers (like those using IP prefixes)
    		{
    			$part1=substr($row['id'], 0, 12);
    			$part2len=strlen($row['id'])-12;
    			$part2=sprintf("%0{$part2len}d", substr($row['id'], 12, strlen($row['id'])-12)+1);
    			$new_autonumber_start="{$part1}{$part2}";
    		}
    		else
    		{
    			$new_autonumber_start=$row['id']+1;
    		}
    	}
    }
	$query = "UPDATE {$dbprefix}surveys SET autonumber_start=$new_autonumber_start WHERE sid=$surveyid";
	@$result = $connect->Execute($query); //Note this won't die if it fails - that's deliberate.

	$deactivatequery = db_rename_table($oldtable,$newtable);
	$deactivateresult = $connect->Execute($deactivatequery) or die ("Couldn't make backup of the survey table. Please try again. The database reported the following error:<br />".htmlspecialchars($connect->ErrorMsg())."<br /><br />Survey was not deactivated either.<br /><br /><a href='$scriptname?sid={$_GET['sid']}'>".$clang->gT("Main Admin Screen")."</a>");
	
	$deactivatequery = "UPDATE {$dbprefix}surveys SET active='N' WHERE sid=$surveyid";
	$deactivateresult = $connect->Execute($deactivatequery) or die ("Couldn't deactivate because:<br />".htmlspecialchars($connect->ErrorMsg())."<br /><br /><a href='$scriptname?sid={$_GET['sid']}'>Admin</a>");
	$deactivateoutput .= "<br />\n<table class='alertbox'>\n";
	$deactivateoutput .= "\t\t\t\t<tr ><td height='4'><strong>".$clang->gT("Deactivate Survey")." ($surveyid)</strong></td></tr>\n";
	$deactivateoutput .= "\t<tr>\n";
	$deactivateoutput .= "\t\t<td align='center'>\n";
	$deactivateoutput .= "\t\t\t<strong>".$clang->gT("Survey Has Been Deactivated")."\n";
	$deactivateoutput .= "\t\t</strong></td>\n";
	$deactivateoutput .= "\t</tr>\n";
	$deactivateoutput .= "\t<tr>\n";
	$deactivateoutput .= "\t\t<td>\n";
	$deactivateoutput .= "\t\t\t".$clang->gT("The responses table has been renamed to: ")." $newtable.\n";
	$deactivateoutput .= "\t\t\t".$clang->gT("The responses to this survey are no longer available using LimeSurvey.")."\n";
	$deactivateoutput .= "\t\t\t<p>".$clang->gT("You should note the name of this table in case you need to access this information later.")."</p>\n";
	if (isset($toldtable) && $toldtable)
	{
		$deactivateoutput .= "\t\t\t".$clang->gT("The tokens table associated with this survey has been renamed to: ")." $tnewtable.\n";
	}
	$deactivateoutput .= "\t\t</td>\n";
	$deactivateoutput .= "\t</tr>\n";
	$deactivateoutput .= "</table><br/>&nbsp;\n";
}

?>
