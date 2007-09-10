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
include_once("login_check.php");  //Login Check dies also if the script is started directly

$activateoutput='';
if (!isset($_GET['ok']) || !$_GET['ok'])
{
	if (isset($_GET['fixnumbering']) && $_GET['fixnumbering'])
	{
		//Fix a question id - requires renumbering a question
		$oldqid = $_GET['fixnumbering'];
		$query = "SELECT qid FROM {$dbprefix}questions ORDER BY qid DESC";
		$result = db_select_limit_assoc($query, 1) or die("$query<br />".$connect->ErrorMsg());
		while ($row=$result->FetchRow()) {$lastqid=$row['qid'];}
		$newqid=$lastqid+1;
		$query = "UPDATE {$dbprefix}questions SET qid=$newqid WHERE qid=$oldqid";
		$result = $connect->Execute($query) or die("$query<br />".$connect->ErrorMsg());
		//Update conditions.. firstly conditions FOR this question
		$query = "UPDATE {$dbprefix}conditions SET qid=$newqid WHERE qid=$oldqid";
		$result = $connect->Execute($query) or die("$query<br />".$connect->ErrorMsg());
		//Now conditions based upon this question
		$query = "SELECT cqid, cfieldname FROM {$dbprefix}conditions WHERE cqid=$oldqid";
		$result = db_execute_assoc($query) or die("$query<br />".$connect->ErrorMsg());
		while ($row=$result->FetchRow())
		{
			$switcher[]=array("cqid"=>$row['cqid'], "cfieldname"=>$row['cfieldname']);
		}
		if (isset($switcher))
		{
			foreach ($switcher as $switch)
			{
				$query = "UPDATE {$dbprefix}conditions
						  SET cqid=$newqid,
						  cfieldname='".str_replace("X".$oldqid, "X".$newqid, $switch['cfieldname'])."'
						  WHERE cqid=$oldqid";
				$result = $connect->Execute($query) or die("$query<br />".$connect->ErrorMsg());
			}
		}
		//Now question_attributes
		$query = "UPDATE {$dbprefix}question_attributes SET qid=$newqid WHERE qid=$oldqid";
		$result = $connect->Execute($query) or die("$query<br />".$connect->ErrorMsg());
		//Now answers
		$query = "UPDATE {$dbprefix}answers SET qid=$newqid WHERE qid=$oldqid";
		$result = $connect->Execute($query) or die("$query<br />".$connect->ErrorMsg());
	}
	//CHECK TO MAKE SURE ALL QUESTION TYPES THAT REQUIRE ANSWERS HAVE ACTUALLY GOT ANSWERS
	//THESE QUESTION TYPES ARE:
	//	# "L" -> LIST
	//  # "O" -> LIST WITH COMMENT
	//  # "M" -> MULTIPLE OPTIONS
	//	# "P" -> MULTIPLE OPTIONS WITH COMMENTS
	//	# "A", "B", "C", "E", "F", "H", "^" -> Various Array Types
	//  # "R" -> RANKING
	//  # "U" -> FILE CSV MORE
	//  # "I" -> FILE CSV ONE


	$chkquery = "SELECT qid, question, gid FROM {$dbprefix}questions WHERE sid={$_GET['sid']} AND type IN ('L', 'O', 'M', 'P', 'A', 'B', 'C', 'E', 'F', 'R', 'J', '!', '^')";
	$chkresult = db_execute_assoc($chkquery) or die ("Couldn't get list of questions<br />$chkquery<br />".$connect->ErrorMsg());
	while ($chkrow = $chkresult->FetchRow())
	{
		$chaquery = "SELECT * FROM {$dbprefix}answers WHERE qid = {$chkrow['qid']} ORDER BY sortorder, answer";
		$charesult=$connect->Execute($chaquery);
		$chacount=$charesult->RecordCount();
		if (!$chacount > 0)
		{
			$failedcheck[]=array($chkrow['qid'], $chkrow['question'], ": ".$clang->gT("This question is a multiple answer type question but has no answers."), $chkrow['gid']);
		}
	}

	//NOW CHECK THAT ALL QUESTIONS HAVE A 'QUESTION TYPE' FIELD
	$chkquery = "SELECT qid, question, gid FROM {$dbprefix}questions WHERE sid={$_GET['sid']} AND type = ''";
	$chkresult = db_execute_assoc($chkquery) or die ("Couldn't check questions for missing types<br />$chkquery<br />".$connect->ErrorMsg());
	while ($chkrow = $chkresult->FetchRow())
	{
		$failedcheck[]=array($chkrow['qid'], $chkrow['question'], ": ".$clang->gT("This question does not have a question 'type' set."), $chkrow['gid']);
	}

	
	

	//CHECK THAT FLEXIBLE LABEL TYPE QUESTIONS HAVE AN "LID" SET
	$chkquery = "SELECT qid, question, gid FROM {$dbprefix}questions WHERE sid={$_GET['sid']} AND type IN ('F', 'H', 'W', 'Z') AND (lid = 0 OR lid is null)";
	$chkresult = db_execute_assoc($chkquery) or die ("Couldn't check questions for missing LIDs<br />$chkquery<br />".$connect->ErrorMsg());
	while($chkrow = $chkresult->FetchRow()){
		$failedcheck[]=array($chkrow['qid'], $chkrow['question'], ": ".$clang->gT("This question requires a Labelset, but none is set."), $chkrow['gid']);
	} // while
	
	
	//NOW check that all used labelsets have all necessary languages
	$chkquery = "SELECT qid, question, gid, lid FROM {$dbprefix}questions WHERE sid={$_GET['sid']} AND type IN ('F', 'H', 'W', 'Z') AND (lid > 0) AND (lid is not null)";
	$chkresult = db_execute_assoc($chkquery) or die ("Couldn't check questions for missing LID languages<br />$chkquery<br />".$connect->ErrorMsg());
	$slangs = GetAdditionalLanguagesFromSurveyID($surveyid); 
	$baselang = GetBaseLanguageFromSurveyID($surveyid);
	array_unshift($slangs,$baselang);
	while ($chkrow = $chkresult->FetchRow())
	{
	    foreach ($slangs as $surveylanguage)
			{
			$chkquery2 = "SELECT lid FROM {$dbprefix}labels WHERE language='$surveylanguage' AND (lid = {$chkrow['lid']}) ";
			$chkresult2 = db_execute_assoc($chkquery2);
			if ($chkresult2->RecordCount()==0)
	            {
				$failedcheck[]=array($chkrow['qid'], $chkrow['question'], ": ".$clang->gT("The labelset used in this question is missing a translation."), '');
	    		}
			}  //foreach
	} //while 

	//CHECK THAT ALL CONDITIONS SET ARE FOR QUESTIONS THAT PRECEED THE QUESTION CONDITION
	//A: Make an array of all the qids in order of appearance
	//	$qorderquery="SELECT * FROM {$dbprefix}questions, {$dbprefix}groups WHERE {$dbprefix}questions.gid={$dbprefix}groups.gid AND {$dbprefix}questions.sid={$_GET['sid']} ORDER BY {$dbprefix}groups.sortorder, {$dbprefix}questions.title";
	//	$qorderresult=$connect->Execute($qorderquery) or die("Couldn't generate a list of questions in order<br />$qorderquery<br />".$connect->ErrorMsg());
	//	$qordercount=$qorderresult->RecordCount();
	//	$c=0;
	//	while ($qorderrow=$qorderresult->FetchRow())
	//		{
	//		$qidorder[]=array($c, $qorderrow['qid']);
	//		$c++;
	//		}
	//TO AVOID NATURAL SORT ORDER ISSUES, FIRST GET ALL QUESTIONS IN NATURAL SORT ORDER, AND FIND OUT WHICH NUMBER IN THAT ORDER THIS QUESTION IS
	$qorderquery = "SELECT * FROM {$dbprefix}questions WHERE sid=$surveyid AND type not in ('S', 'D', 'T', 'Q')";
	$qorderresult = db_execute_assoc($qorderquery) or die ("$qorderquery<br />".$connect->ErrorMsg());
	$qrows = array(); //Create an empty array in case FetchRow does not return any rows
	while ($qrow = $qorderresult->FetchRow()) {$qrows[] = $qrow;} // Get table output into array
	usort($qrows, 'CompareGroupThenTitle'); // Perform a case insensitive natural sort on group name then question title of a multidimensional array
	$c=0;
	foreach ($qrows as $qr)
	{
		$qidorder[]=array($c, $qrow['qid']);
		$c++;
	}
	$qordercount="";
	//1: Get each condition's question id
	$conquery= "SELECT {$dbprefix}conditions.qid, cqid, {$dbprefix}questions.question, "
	. "{$dbprefix}questions.gid "
	. "FROM {$dbprefix}conditions, {$dbprefix}questions, {$dbprefix}groups "
	. "WHERE {$dbprefix}conditions.qid={$dbprefix}questions.qid "
	. "AND {$dbprefix}questions.gid={$dbprefix}groups.gid ORDER BY {$dbprefix}conditions.qid";
	$conresult=db_execute_assoc($conquery) or die("Couldn't check conditions for relative consistency<br />$conquery<br />".$connect->ErrorMsg());
	//2: Check each conditions cqid that it occurs later than the cqid
	while ($conrow=$conresult->FetchRow())
	{
		$cqidfound=0;
		$qidfound=0;
		$b=0;
		while ($b<$qordercount)
		{
			if ($conrow['cqid'] == $qidorder[$b][1])
			{
				$cqidfound = 1;
				$b=$qordercount;
			}
			if ($conrow['qid'] == $qidorder[$b][1])
			{
				$qidfound = 1;
				$b=$qordercount;
			}
			if ($qidfound == 1)
			{
				$failedcheck[]=array($conrow['qid'], $conrow['question'], ": ".$clang->gT("This question has a condition set, however the condition is based on a question that appears after it."), $conrow['gid']);
			}
			$b++;
		}
	}
	//CHECK THAT ALL THE CREATED FIELDS WILL BE UNIQUE
	$fieldmap=createFieldMap($surveyid, "full");
	if (isset($fieldmap))
	{
		foreach($fieldmap as $fielddata)
		{
			$fieldlist[]=$fielddata['fieldname'];
		}
		$fieldlist=array_reverse($fieldlist); //let's always change the later duplicate, not the earlier one
	}
	$checkKeysUniqueComparison = create_function('$value','if ($value > 1) return true;');
	@$duplicates = array_keys (array_filter (array_count_values($fieldlist), $checkKeysUniqueComparison));
	if (isset($duplicates))
	{
		foreach ($duplicates as $dup)
		{
			$badquestion=arraySearchByKey($dup, $fieldmap, "fieldname", 1);
			$fix = "[<a href='$scriptname?action=activate&amp;sid=$surveyid&amp;fixnumbering=".$badquestion['qid']."'>Click Here to Fix</a>]";
			$failedcheck[]=array($badquestion['qid'], $badquestion['question'], ": Bad duplicate fieldname $fix", $badquestion['gid']);
		}
	}

	//IF ANY OF THE CHECKS FAILED, PRESENT THIS SCREEN
	if (isset($failedcheck) && $failedcheck)
	{
		$activateoutput .= "<br />\n<table bgcolor='#FFFFFF' width='500' align='center' style='border: 1px solid #555555' cellpadding='6' cellspacing='0'>\n";
		$activateoutput .= "\t\t\t\t<tr bgcolor='#555555'><td height='4'><font size='1' face='verdana' color='white'><strong>".$clang->gT("Activate Survey")." ($surveyid)</strong></font></td></tr>\n";
		$activateoutput .= "\t<tr>\n";
		$activateoutput .= "\t\t<td align='center' bgcolor='#ffeeee'>\n";
		$activateoutput .= "\t\t\t<font color='red'><strong>".$clang->gT("Error")."</strong><br />\n";
		$activateoutput .= "\t\t\t".$clang->gT("Survey does not pass consistency check")."</font>\n";
		$activateoutput .= "\t\t</td>\n";
		$activateoutput .= "\t</tr>\n";
		$activateoutput .= "\t<tr>\n";
		$activateoutput .= "\t\t<td>\n";
		$activateoutput .= "\t\t\t<strong>".$clang->gT("The following problems have been found:")."</strong><br />\n";
		$activateoutput .= "\t\t\t<ul>\n";
		foreach ($failedcheck as $fc)
		{
			$activateoutput .= "\t\t\t\t<li> Question qid-{$fc[0]} (\"<a href='$scriptname?sid=$surveyid&amp;gid=$fc[3]&amp;qid=$fc[0]'>{$fc[1]}</a>\"){$fc[2]}</li>\n";
		}
		$activateoutput .= "\t\t\t</ul>\n";
		$activateoutput .= "\t\t\t".$clang->gT("The survey cannot be activated until these problems have been resolved.")."\n";
		$activateoutput .= "\t\t</td>\n";
		$activateoutput .= "\t</tr>\n";
		$activateoutput .= "</table><br />&nbsp;\n";

		return;
	}

	$activateoutput .= "<br />\n<table class='alertbox'>\n";
	$activateoutput .= "\t\t\t\t<tr><td height='4'><strong>".$clang->gT("Activate Survey")." ($surveyid)</strong></td></tr>\n";
	$activateoutput .= "\t<tr>\n";
	$activateoutput .= "\t\t<td align='center' bgcolor='#ffeeee'>\n";
	$activateoutput .= "\t\t\t<font color='red'><strong>".$clang->gT("Warning")."</strong><br />\n";
	$activateoutput .= "\t\t\t".$clang->gT("READ THIS CAREFULLY BEFORE PROCEEDING")."\n";
	$activateoutput .= "\t\t\t</font>\n";
	$activateoutput .= "\t\t</td>\n";
	$activateoutput .= "\t</tr>\n";
	$activateoutput .= "\t<tr>\n";
	$activateoutput .= "\t\t<td>\n";
	$activateoutput .= $clang->gT("You should only activate a survey when you are absolutely certain that your survey setup is finished and will not need changing.")."<br /><br />\n";
	$activateoutput .= $clang->gT("Once a survey is activated you can no longer:")."<ul><li>".$clang->gT("Add or delete groups")."</li><li>".$clang->gT("Add or remove answers to Multiple Answer questions")."</li><li>".$clang->gT("Add or delete questions")."</li></ul>\n";
	$activateoutput .= $clang->gT("However you can still:")."<ul><li>".$clang->gT("Edit (change) your questions code, text or type")."</li><li>".$clang->gT("Edit (change) your group names")."</li><li>".$clang->gT("Add, Remove or Edit pre-defined question answers (except for Multi-answer questions)")."</li><li>".$clang->gT("Change survey name or description")."</li></ul>\n";
	$activateoutput .= $clang->gT("Once data has been entered into this survey, if you want to add or remove groups or questions, you will need to de-activate this survey, which will move all data that has already been entered into a separate archived table.")."<br /><br />\n";
	$activateoutput .= "\t\t</td>\n";
	$activateoutput .= "\t</tr>\n";
	$activateoutput .= "\t<tr>\n";
	$activateoutput .= "\t\t<td align='center'>\n";
	$activateoutput .= "\t\t\t<input type='submit' value=\"".$clang->gT("Activate Survey")."\" onclick=\"window.open('$scriptname?action=activate&amp;ok=Y&amp;sid={$_GET['sid']}', '_top')\" />\n";
	$activateoutput .= "\t\t<br />&nbsp;</td>\n";
	$activateoutput .= "\t</tr>\n";
	$activateoutput .= "</table><br />&nbsp;\n";

}
else
{
	//Create the survey responses table
	$createsurvey = "id I NOTNULL AUTO PRIMARY,\n";
	$createsurvey .= " submitdate T,\n";
	$createsurvey .= " startlanguage C(20) NOTNULL ,\n";
	//Check for any additional fields for this survey and create necessary fields (token and datestamp)
	$pquery = "SELECT private, allowregister, datestamp, ipaddr, refurl FROM {$dbprefix}surveys WHERE sid={$_GET['sid']}";
	$presult=db_execute_assoc($pquery);
	while($prow=$presult->FetchRow())
	{
		if ($prow['private'] == "N")
		{
			$createsurvey .= "  token C(36),\n";
			$surveynotprivate="TRUE";
		}
		if ($prow['allowregister'] == "Y")
		{
			$surveyallowsregistration="TRUE";
		}
		if ($prow['datestamp'] == "Y")
		{
			$createsurvey .= " datestamp T NOTNULL,\n";
		}
		if ($prow['ipaddr'] == "Y")
		{
			$createsurvey .= " ipaddr X,\n";
		}
		//Check to see if 'refurl' field is required.
		if ($prow['refurl'] == "Y")
		{
			$createsurvey .= " refurl X,\n";
		}
	}
	//Get list of questions for the base language
	$aquery = " SELECT * FROM ".db_table_name('questions').", ".db_table_name('groups')
	." WHERE ".db_table_name('questions').".gid=".db_table_name('groups').".gid "
	." AND ".db_table_name('questions').".sid={$_GET['sid']} "
	." AND ".db_table_name('groups').".language='".GetbaseLanguageFromSurveyid($_GET['sid']). "' "
	." AND ".db_table_name('questions').".language='".GetbaseLanguageFromSurveyid($_GET['sid']). "' "
	." ORDER BY ".db_table_name('groups').".group_order, title";
	$aresult = db_execute_assoc($aquery);
	while ($arow=$aresult->FetchRow()) //With each question, create the appropriate field(s)
	{
		if ( substr($createsurvey, strlen($createsurvey)-2, 2) != ",\n") {$createsurvey .= ",\n";}

		if ($arow['type'] != "M" && $arow['type'] != "A" && $arow['type'] != "B" &&
		$arow['type'] != "C" && $arow['type'] != "E" && $arow['type'] != "F" &&
		$arow['type'] != "H" && $arow['type'] != "P" && $arow['type'] != "R" &&
		$arow['type'] != "Q" && $arow['type'] != "^" && $arow['type'] != "J")
		{
			$createsurvey .= "  `{$arow['sid']}X{$arow['gid']}X{$arow['qid']}`";
			switch($arow['type'])
			{
				case "N":  //NUMERICAL
				$createsurvey .= " C(20)";
				break;
				case "S":  //SHORT TEXT
				$createsurvey .= " C(255)";
				break;
				case "L":  //LIST (RADIO)
				case "!":  //LIST (DROPDOWN)
				case "W":
				case "Z":
					$createsurvey .= " C(5)";
					if ($arow['other'] == "Y")
					{
						$createsurvey .= ",\n`{$arow['sid']}X{$arow['gid']}X{$arow['qid']}other` X";
					}
					break;
				case "I":  // CSV ONE
				$createsurvey .= " C(5)";
				break;
				case "O": //DROPDOWN LIST WITH COMMENT
				$createsurvey .= " C(5),\n `{$arow['sid']}X{$arow['gid']}X{$arow['qid']}comment` X";
				break;
				case "T":  //LONG TEXT
				$createsurvey .= " X";
				break;
				case "U":  //HUGE TEXT
				$createsurvey .= " X";
				break;
				case "D":  //DATE
				$createsurvey .= " D";
				break;
				case "5":  //5 Point Choice
				case "G":  //Gender
				case "Y":  //YesNo
				case "X":  //Boilerplate
				$createsurvey .= " C(1)";
				break;
			}
		}
		elseif ($arow['type'] == "M" || $arow['type'] == "A" || $arow['type'] == "B" ||
		$arow['type'] == "C" || $arow['type'] == "E" || $arow['type'] == "F" ||
		$arow['type'] == "H" || $arow['type'] == "P" || $arow['type'] == "^")
		{
			//MULTI ENTRY
			$abquery = "SELECT a.*, q.other FROM {$dbprefix}answers as a, {$dbprefix}questions as q"
                       ." WHERE a.qid=q.qid AND sid={$_GET['sid']} AND q.qid={$arow['qid']} "
                       ." AND a.language='".GetbaseLanguageFromSurveyid($_GET['sid']). "' "
                       ." AND q.language='".GetbaseLanguageFromSurveyid($_GET['sid']). "' "
                       ." ORDER BY a.sortorder, a.answer";
			$abresult=db_execute_assoc($abquery) or die ("Couldn't get perform answers query<br />$abquery<br />".$connect->ErrorMsg());
			while ($abrow=$abresult->FetchRow())
			{
				$createsurvey .= "  `{$arow['sid']}X{$arow['gid']}X{$arow['qid']}{$abrow['code']}` C(5),\n";
				if ($abrow['other']=="Y") {$alsoother="Y";}
				if ($arow['type'] == "P")
				{
					$createsurvey .= "  `{$arow['sid']}X{$arow['gid']}X{$arow['qid']}{$abrow['code']}comment` C(255),\n";
				}
			}
			if ((isset($alsoother) && $alsoother=="Y") && ($arow['type']=="M" || $arow['type']=="P"))
			{
				$createsurvey .= " `{$arow['sid']}X{$arow['gid']}X{$arow['qid']}other` C(255),\n";
				if ($arow['type']=="P")
				{
					$createsurvey .= " `{$arow['sid']}X{$arow['gid']}X{$arow['qid']}othercomment` C(255),\n";
				}
			}
		}
		elseif ($arow['type'] == "Q")
		{
			$abquery = "SELECT a.*, q.other FROM {$dbprefix}answers as a, {$dbprefix}questions as q WHERE a.qid=q.qid AND sid={$_GET['sid']} AND q.qid={$arow['qid']} "
                                   ." AND a.language='".GetbaseLanguageFromSurveyid($_GET['sid']). "' "
                                   ." AND q.language='".GetbaseLanguageFromSurveyid($_GET['sid']). "' "
                                   ." ORDER BY a.sortorder, a.answer";
			$abresult=db_execute_assoc($abquery) or die ("Couldn't get perform answers query<br />$abquery<br />".$connect->ErrorMsg());
			while ($abrow = $abresult->FetchRow())
			{
				$createsurvey .= "  `{$arow['sid']}X{$arow['gid']}X{$arow['qid']}{$abrow['code']}` C(255),\n";
			}
		}
/*		elseif ($arow['type'] == "J")
		{
			$abquery = "SELECT {$dbprefix}answers.*, {$dbprefix}questions.other FROM {$dbprefix}answers, {$dbprefix}questions WHERE {$dbprefix}answers.qid={$dbprefix}questions.qid AND sid={$_GET['sid']} AND {$dbprefix}questions.qid={$arow['qid']} ORDER BY {$dbprefix}answers.sortorder, {$dbprefix}answers.answer";
			$abresult=db_execute_assoc($abquery) or die ("Couldn't get perform answers query<br />$abquery<br />".$connect->ErrorMsg());
			while ($abrow = $abresultt->FetchRow())
			{
				$createsurvey .= "  `{$arow['sid']}X{$arow['gid']}X{$arow['qid']}{$abrow['code']}` C(5),\n";
			}
		}*/
		elseif ($arow['type'] == "R")
		{
			//MULTI ENTRY
    		$abquery = "SELECT a.*, q.other FROM {$dbprefix}answers as a, {$dbprefix}questions as q"
                       ." WHERE a.qid=q.qid AND sid={$_GET['sid']} AND q.qid={$arow['qid']} "
                       ." AND a.language='".GetbaseLanguageFromSurveyid($_GET['sid']). "' "
                       ." AND q.language='".GetbaseLanguageFromSurveyid($_GET['sid']). "' "
                       ." ORDER BY a.sortorder, a.answer";
			$abresult=$connect->Execute($abquery) or die ("Couldn't get perform answers query<br />$abquery<br />".$connect->ErrorMsg());
			$abcount=$abresult->RecordCount();
			for ($i=1; $i<=$abcount; $i++)
			{
				$createsurvey .= "  `{$arow['sid']}X{$arow['gid']}X{$arow['qid']}$i` C(5),\n";
			}
		}

	}

	// If last question is of type MCABCEFHP^QJR let's get rid of the ending coma in createsurvey
	$createsurvey = rtrim($createsurvey, ",\n")."\n"; // Does nothing if not ending with a coma
	$tabname = "{$dbprefix}survey_{$_GET['sid']}"; # not using db_table_name as it quotes the table name (as does CreateTableSQL)

	$taboptarray = array('mysql' => 'TYPE='.$databasetabletype.'  CHARACTER SET utf8');
	$dict = NewDataDictionary($connect);
	$sqlarray = $dict->CreateTableSQL($tabname, $createsurvey, $taboptarray);

	$execresult=$dict->ExecuteSQLArray($sqlarray,1);
	if ($execresult==0 || $execresult==1)
	{
	$activateoutput .= "<br />\n<table width='350' align='center' style='border: 1px solid #555555' cellpadding='1' cellspacing='0'>\n" .
	"<tr bgcolor='#555555'><td height='4'><font size='1' face='verdana' color='white'><strong>".$clang->gT("Activate Survey")." ($surveyid)</strong></font></td></tr>\n" .
	"<tr><td>\n" .
	"<font color='red'>".$clang->gT("Survey could not be actived.")."</font><br />\n" .
	"<center><a href='$scriptname?sid={$_GET['sid']}'>".$clang->gT("Main Admin Screen")."</a></center>\n" .
	"DB ".$clang->gT("Error").":<br />\n<font color='red'>" . $connect->ErrorMsg() . "</font>\n" .
	"<pre>$createsurvey</pre>\n" .
	"</td></tr></table></br>&nbsp;\n" .
	"</body>\n</html>";
	}
	if ($execresult != 0 && $execresult !=1)
	{
		$anquery = "SELECT autonumber_start FROM {$dbprefix}surveys WHERE sid={$_GET['sid']}";
		if ($anresult=db_execute_assoc($anquery))
		{
			//if there is an autonumber_start field, start auto numbering here
			while($row=$anresult->FetchRow())
			{
				if ($row['autonumber_start'] > 0)
				{
					$autonumberquery = "ALTER TABLE {$dbprefix}survey_{$_GET['sid']} AUTO_INCREMENT = ".$row['autonumber_start'];
					if ($result = $connect->Execute($autonumberquery))
					{
						//We're happy it worked!
					}
					else
					{
						//Continue regardless - it's not the end of the world
					}
				}
			}
		}

		$activateoutput .= "<br />\n<table class='alertbox'>\n";
		$activateoutput .= "\t\t\t\t<tr><td height='4'><strong>".$clang->gT("Activate Survey")." ($surveyid)</td></tr>\n";
		$activateoutput .= "\t\t\t\t<tr><td align='center'><font color='green'>".$clang->gT("Survey has been activated. Results table has been successfully created.")."<br /><br />\n";

		$acquery = "UPDATE {$dbprefix}surveys SET active='Y' WHERE sid={$_GET['sid']}";
		$acresult = $connect->Execute($acquery);

// Private means data privacy, not closed access survey
//		if (isset($surveynotprivate) && $surveynotprivate) //This survey is tracked, and therefore a tokens table MUST exist
//		{
//			$activateoutput .= $clang->gT("This is not an anonymous survey. A token table must also be created.")."<br /><br />\n";
//			$activateoutput .= "<input type='submit' value='".$clang->gT("Initialise Tokens")."' onclick=\"window.open('$scriptname?action=tokens&amp;sid={$_GET['sid']}&amp;createtable=Y', '_top')\" />\n";
//		}
//		elseif (isset($surveyallowsregistration) && $surveyallowsregistration == "TRUE")
		if (isset($surveyallowsregistration) && $surveyallowsregistration == "TRUE")
		{
			$activateoutput .= $clang->gT("This survey allows public registration. A token table must also be created.")."<br /><br />\n";
			$activateoutput .= "<input type='submit' value='".$clang->gT("Initialise Tokens")."' onclick=\"window.open('$scriptname?action=tokens&amp;sid={$_GET['sid']}&amp;createtable=Y', '_top')\" />\n";
		}
		else
		{
			$activateoutput .= $clang->gT("This survey is now active, and responses can be recorded.")."<br /><br />\n";
			$activateoutput .= "<strong>".$clang->gT("Open-access mode").":</strong> ".$clang->gT("No invitation code is needed to complete the survey.")."<br />".$clang->gT("You can switch to the closed-access mode by initialising a token table with the button below.")."<br /><br />\n";
			$activateoutput .= $clang->gT("Optional").": <input type='submit' value='".$clang->gT("Switch to closed-access mode")."' onclick=\"window.open('$scriptname?action=tokens&amp;sid={$_GET['sid']}&amp;createtable=Y', '_top')\" />\n";
		}
		$activateoutput .= "\t\t\t\t</font></font></td></tr></table><br />&nbsp;\n";
	}

}

?>
