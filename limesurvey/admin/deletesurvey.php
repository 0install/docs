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
if (isset($_GET['sid'])) {$surveyid = $_GET['sid'];}
if (isset($_GET['ok'])) {$ok = $_GET['ok'];}

$deletesurveyoutput = "<br />\n";
$deletesurveyoutput .= "<table class='alertbox' >\n";
$deletesurveyoutput .= "\t<tr ><td colspan='2' height='4'><font size='1'><strong>".$clang->gT("Delete Survey")."</strong></font></td></tr>\n";

if (!isset($surveyid) || !$surveyid)
{
    $deletesurveyoutput .= "\t<tr ><td align='center'>\n";
	$deletesurveyoutput .= "<br /><font color='red'><strong>".$clang->gT("Error")."</strong></font><br />\n";
	$deletesurveyoutput .= $clang->gT("You have not selected a survey to delete")."<br /><br />\n";
	$deletesurveyoutput .= "<input type='submit' value='".$clang->gT("Main Admin Screen")."' onclick=\"window.open('$scriptname', '_top')\">\n";
	$deletesurveyoutput .= "</td></tr></table>\n";
	$deletesurveyoutput .= "</body>\n</html>";
	return;
}

if (!isset($ok) || !$ok)
{
	$tablelist = $connect->MetaTables();

	$deletesurveyoutput .= "\t<tr>\n";
	$deletesurveyoutput .= "\t\t<td align='center'><br />\n";
	$deletesurveyoutput .= "\t\t\t<font color='red'><strong>".$clang->gT("Warning")."</strong></font><br />\n";
	$deletesurveyoutput .= "\t\t\t<strong>".$clang->gT("You are about to delete this survey")." ($surveyid)</strong><br /><br />\n";
	$deletesurveyoutput .= "\t\t\t".$clang->gT("This process will delete this survey, and all related groups, questions answers and conditions.")."<br /><br />\n";
	$deletesurveyoutput .= "\t\t\t".$clang->gT("We recommend that before you delete this survey you export the entire survey from the main administration screen.")."\n";

	if (in_array("{$dbprefix}survey_$surveyid", $tablelist))
	{
		$deletesurveyoutput .= "\t\t\t<br /><br />\n".$clang->gT("This survey is active and a responses table exists. If you delete this survey, these responses will be deleted. We recommend that you export the responses before deleting this survey.")."<br /><br />\n";
	}

	if (in_array("{$dbprefix}tokens_$surveyid", $tablelist))
	{
		$deletesurveyoutput .= "\t\t\t".$clang->gT("This survey has an associated tokens table. If you delete this survey this tokens table will be deleted. We recommend that you export or backup these tokens before deleting this survey.")."<br /><br />\n";
	}

	$deletesurveyoutput .= "\t\t</td>\n";
	$deletesurveyoutput .= "\t</tr>\n";
	$deletesurveyoutput .= "\t<tr>\n";
	$deletesurveyoutput .= "\t\t<td align='center'><br />\n";
	$deletesurveyoutput .= "\t\t\t<input type='submit'  value='".$clang->gT("Cancel")."' onclick=\"window.open('admin.php?sid=$surveyid', '_top')\" /><br />\n";
	$deletesurveyoutput .= "\t\t\t<input type='submit'  value='".$clang->gT("Delete")."' onclick=\"window.open('$scriptname?action=deletesurvey&amp;sid=$surveyid&amp;ok=Y','_top')\" />\n";
	$deletesurveyoutput .= "\t\t</td>\n";
	$deletesurveyoutput .= "\t</tr>\n";
	$deletesurveyoutput .= "\n";
}

else //delete the survey
{
	$tablelist = $connect->MetaTables();
	$dict = NewDataDictionary($connect);

	if (in_array("{$dbprefix}survey_$surveyid", $tablelist)) //delete the survey_$surveyid table
	{			
		$dsquery = $dict->DropTableSQL("{$dbprefix}survey_$surveyid");	
		//$dict->ExecuteSQLArray($sqlarray);		
		$dsresult = $dict->ExecuteSQLArray($dsquery) or die ("Couldn't \"$dsquery\" because <br />".$connect->ErrorMsg());
	}

	if (in_array("{$dbprefix}tokens_$surveyid", $tablelist)) //delete the tokens_$surveyid table
	{
		$dsquery = $dict->DropTableSQL("{$dbprefix}tokens_$surveyid");
		$dsresult = $dict->ExecuteSQLArray($dsquery) or die ("Couldn't \"$dsquery\" because <br />".$connect->ErrorMsg());
	}

	$dsquery = "SELECT qid FROM {$dbprefix}questions WHERE sid=$surveyid";
	$dsresult = db_execute_assoc($dsquery) or die ("Couldn't find matching survey to delete<br />$dsquery<br />".$connect->ErrorMsg());
	while ($dsrow = $dsresult->FetchRow())
	{
		$asdel = "DELETE FROM {$dbprefix}answers WHERE qid={$dsrow['qid']}";
		$asres = $connect->Execute($asdel);
		$cddel = "DELETE FROM {$dbprefix}conditions WHERE qid={$dsrow['qid']}";
		$cdres = $connect->Execute($cddel) or die ("Delete conditions failed<br />$cddel<br />".$connect->ErrorMsg());
		$qadel = "DELETE FROM {$dbprefix}question_attributes WHERE qid={$dsrow['qid']}";
		$qares = $connect->Execute($qadel);
	}

	$qdel = "DELETE FROM {$dbprefix}questions WHERE sid=$surveyid";
	$qres = $connect->Execute($qdel);

	$scdel = "DELETE FROM {$dbprefix}assessments WHERE sid=$surveyid";
	$scres = $connect->Execute($scdel);

	$gdel = "DELETE FROM {$dbprefix}groups WHERE sid=$surveyid";
	$gres = $connect->Execute($gdel);
	
	$slsdel = "DELETE FROM {$dbprefix}surveys_languagesettings WHERE surveyls_survey_id=$surveyid";
    $slsres = $connect->Execute($slsdel);

	$sdel = "DELETE FROM {$dbprefix}surveys WHERE sid=$surveyid";
	$sres = $connect->Execute($sdel);

    $srdel = "DELETE FROM {$dbprefix}surveys_rights WHERE sid=$surveyid";
	$srres = $connect->Execute($srdel);


	$deletesurveyoutput .= "\t<tr>\n";
	$deletesurveyoutput .= "\t\t<td align='center'><br />\n";
	$deletesurveyoutput .= "\t\t\t<strong>".$clang->gT("This survey has been deleted.")."<br /><br />\n";
	$deletesurveyoutput .= "\t\t\t<input type='submit' value='".$clang->gT("Main Admin Screen")."' onclick=\"window.open('$scriptname', '_top')\" />\n";
	$deletesurveyoutput .= "\t\t</strong></td>\n";
	$deletesurveyoutput .= "\t</tr>\n";
    $surveyid=false;

}
$deletesurveyoutput .= "</table><br />&nbsp;\n";
	


?>
