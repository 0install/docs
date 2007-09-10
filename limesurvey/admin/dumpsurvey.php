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



// DUMP THE RELATED DATA FOR A SINGLE SURVEY INTO A SQL FILE FOR IMPORTING LATER ON OR ON ANOTHER SURVEY SETUP
// DUMP ALL DATA WITH RELATED SID FROM THE FOLLOWING TABLES
// 1. Surveys
// 2. Groups
// 3. Questions
// 4. Answers
// 5. Conditions 
// 6. Label Sets
// 7. Labels
// 8. Question Attributes
// 9. Assessments

include_once("login_check.php");

if (!isset($surveyid)) {$surveyid=returnglobal('sid');}

include_once("login_check.php");


if (!$surveyid)
{
	echo $htmlheader
	."<br />\n"
	."<table width='350' align='center' style='border: 1px solid #555555' cellpadding='1' cellspacing='0'>\n"
	."\t<tr bgcolor='#555555'><td colspan='2' height='4'><font size='1' face='verdana' color='white'><strong>"
	.$clang->gT("Export Survey")."</strong></td></tr>\n"
	."\t<tr><td align='center'>\n"
	."<br /><strong><font color='red'>"
	.$clang->gT("Error")."</font></strong><br />\n"
	.$clang->gT("No SID has been provided. Cannot dump survey")."<br />\n"
	."<br /><input type='submit' value='"
	.$clang->gT("Main Admin Screen")."' onclick=\"window.open('$scriptname', '_top')\">\n"
	."\t</td></tr>\n"
	."</table>\n"
	."</body></html>\n";
	exit;
}

$dumphead = "# LimeSurvey Survey Dump\n"
        . "# DBVersion $dbversionnumber\n"
        . "# This is a dumped survey from the LimeSurvey Script\n"
        . "# http://www.limesurvey.org/\n"
        . "# Do not change this header!\n";

//1: Surveys table
$squery = "SELECT * FROM {$dbprefix}surveys WHERE sid=$surveyid";
$sdump = BuildCSVFromQuery($squery);

//2: Surveys Languagsettings table
$slsquery = "SELECT * FROM {$dbprefix}surveys_languagesettings WHERE surveyls_survey_id=$surveyid";
$slsdump = BuildCSVFromQuery($slsquery);

//3: Groups Table
$gquery = "SELECT * FROM {$dbprefix}groups WHERE sid=$surveyid order by gid";
$gdump = BuildCSVFromQuery($gquery);

//4: Questions Table
$qquery = "SELECT * FROM {$dbprefix}questions WHERE sid=$surveyid order by qid";
$qdump = BuildCSVFromQuery($qquery);

//5: Answers table
$aquery = "SELECT {$dbprefix}answers.* FROM {$dbprefix}answers, {$dbprefix}questions WHERE {$dbprefix}answers.language={$dbprefix}questions.language AND {$dbprefix}answers.qid={$dbprefix}questions.qid AND {$dbprefix}questions.sid=$surveyid";
$adump = BuildCSVFromQuery($aquery);

//6: Conditions table
$cquery = "SELECT {$dbprefix}conditions.* FROM {$dbprefix}conditions, {$dbprefix}questions WHERE {$dbprefix}conditions.qid={$dbprefix}questions.qid AND {$dbprefix}questions.sid=$surveyid";
$cdump = BuildCSVFromQuery($cquery);

//7: Label Sets
$lsquery = "SELECT DISTINCT {$dbprefix}labelsets.lid, label_name, {$dbprefix}labelsets.languages FROM {$dbprefix}labelsets, {$dbprefix}questions WHERE {$dbprefix}labelsets.lid={$dbprefix}questions.lid AND type IN ('F', 'H', 'W', 'Z') AND sid=$surveyid";
$lsdump = BuildCSVFromQuery($lsquery);

//8: Labels
$lquery = "SELECT DISTINCT {$dbprefix}labels.lid, {$dbprefix}labels.code, {$dbprefix}labels.title, {$dbprefix}labels.sortorder,{$dbprefix}labels.language FROM {$dbprefix}labels, {$dbprefix}questions WHERE {$dbprefix}labels.lid={$dbprefix}questions.lid AND type in ('F', 'W', 'H', 'Z') AND sid=$surveyid";
$ldump = BuildCSVFromQuery($lquery);

//9: Question Attributes
$query = "SELECT {$dbprefix}question_attributes.* FROM {$dbprefix}question_attributes, {$dbprefix}questions WHERE {$dbprefix}question_attributes.qid={$dbprefix}questions.qid AND {$dbprefix}questions.sid=$surveyid";
$qadump = BuildCSVFromQuery($query);

//10: Assessments;
$query = "SELECT {$dbprefix}assessments.* FROM {$dbprefix}assessments WHERE {$dbprefix}assessments.sid=$surveyid";
$asdump = BuildCSVFromQuery($query);

$fn = "limesurvey_survey_$surveyid.csv";

header("Content-Type: application/download");
header("Content-Disposition: attachment; filename=$fn");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
Header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Pragma: no-cache");                          // HTTP/1.0

echo $dumphead, $sdump, $gdump, $qdump, $adump, $cdump, $lsdump, $ldump, $qadump, $asdump, $slsdump."\n";
exit;
?>
