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
require_once(dirname(__FILE__).'/sessioncontrol.php');
require_once(dirname(__FILE__).'/../qanda.php');

if (!isset($surveyid)) {$surveyid=returnglobal('sid');}
if (!isset($qid)) {$qid=returnglobal('qid');}
if (empty($surveyid)) {die("No SID provided.");}
if (empty($qid)) {die("No QID provided.");}

if (!isset($_GET['lang']) || $_GET['lang'] == "")
{
	$language = GetBaseLanguageFromSurveyID($surveyid);
} else {
	$language = $_GET['lang'];
}

$_SESSION['s_lang'] = $language;
$clang = new limesurvey_lang($language);

$qquery = 'SELECT * FROM '.db_table_name('questions')." WHERE sid='$surveyid' AND qid='$qid' AND language='{$language}'";
$qresult = db_execute_assoc($qquery);
$qrows = $qresult->FetchRow();

$ia = array(0 => $qid, 1 => "FIELDNAME", 2 => $qrows['title'], 3 => $qrows['question'], 4 => $qrows['type'], 5 => $qrows['gid'],
6 => $qrows['mandatory'], 7 => $qrows['other']);
$answers = retrieveAnswers($ia);
$thistpl="$publicdir/templates";
doHeader();
//echo "\t\t\t\t<div id='question'";
$question="<label for='$answers[0][7]'>" . $answers[0][0] . "</label>";
$answer=$answers[0][1];
$help=$answers[0][2];
$questioncode=$answers[0][5];
echo templatereplace(file_get_contents("$thistpl/preview.pstpl"));
echo "</html>\n";


exit;
?>
