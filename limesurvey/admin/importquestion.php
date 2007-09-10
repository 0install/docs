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

// A FILE TO IMPORT A DUMPED question FILE, AND CREATE A NEW SURVEY

$importquestion = "<br /><table width='100%' align='center'><tr><td>\n"
."<table width='350' align='center' style='border: 1px solid #555555' cellpadding='1' cellspacing='0'>\n"
."\t<tr bgcolor='#555555'><td colspan='2' height='4'><font size='1' face='verdana' color='white'><strong>"
.$clang->gT("Import Question")."</strong></td></tr>\n"
."\t<tr bgcolor='#CCCCCC'><td align='center'>\n";

$the_full_file_path = $tempdir . "/" . $_FILES['the_file']['name'];

if (!@move_uploaded_file($_FILES['the_file']['tmp_name'], $the_full_file_path))
{
	$importquestion .= "<strong><font color='red'>".$clang->gT("Error")."</font></strong><br />\n";
    $importquestion .= sprintf ($clang->gT("An error occurred uploading your file. This may be caused by incorrect permissions in your %s folder."),$tempdir)."<br /><br />\n"
	."<input type='submit' value='"
	.$clang->gT("Main Admin Screen")."' onclick=\"window.open('$scriptname', '_top')\">\n"
	."</td></tr></table>\n";
	unlink($the_full_file_path);
	return;
}

// IF WE GOT THIS FAR, THEN THE FILE HAS BEEN UPLOADED SUCCESFULLY
$importquestion .= "<strong><font color='green'>".$clang->gT("Success")."</font></strong><br />\n"
.$clang->gT("File upload succeeded.")."<br /><br />\n"
.$clang->gT("Reading file..")."\n";
$handle = fopen($the_full_file_path, "r");
while (!feof($handle))
{
	$buffer = fgets($handle, 10240); //To allow for very long survey welcomes (up to 10k)
	$bigarray[] = $buffer;
}
fclose($handle);

if (!$_POST['sid'])
{
	$importquestion .= $clang->gT("No SID (Survey) has been provided. Cannot import question.")."<br /><br />\n"
	."<input type='submit' value='"
	.$clang->gT("Main Admin Screen")."' onclick=\"window.open('$scriptname', '_top')\">\n"
	."</td></tr></table>\n";
	return;
}
if (!$_POST['gid'])
{
	$importquestion .= $clang->gT("No GID (Group) has been provided. Cannot import question")."<br /><br />\n"
	."</td></tr></table>\n";
	return;
}
if (substr($bigarray[0], 0, 26) != "# LimeSurvey Question Dump" && substr($bigarray[0], 0, 27) != "# PHPSurveyor Question Dump")
{
	$importquestion .= "<strong><font color='red'>".$clang->gT("Error")."</font></strong><br />\n"
	.$clang->gT("This file is not a LimeSurvey question file. Import failed.")."<br /><br />\n"
	."</td></tr></table>\n";
	return;
}


for ($i=0; $i<9; $i++) //skipping the first lines that are not needed
{
	unset($bigarray[$i]);
}
$bigarray = array_values($bigarray);

//QUESTIONS
if (array_search("# ANSWERS TABLE\n", $bigarray))
{
	$stoppoint = array_search("# ANSWERS TABLE\n", $bigarray);
}
elseif (array_search("# ANSWERS TABLE\r\n", $bigarray))
{
	$stoppoint = array_search("# ANSWERS TABLE\r\n", $bigarray);
}
else
{
	$stoppoint = count($bigarray)-1;
}
for ($i=0; $i<=$stoppoint+1; $i++)
{
	if ($i<$stoppoint-2) {$questionarray[] = $bigarray[$i];}
	unset($bigarray[$i]);
}
$bigarray = array_values($bigarray);

//ANSWERS
if (array_search("# LABELSETS TABLE\n", $bigarray))
{
	$stoppoint = array_search("# LABELSETS TABLE\n", $bigarray);
}
elseif (array_search("# LABELSETS TABLE\r\n", $bigarray))
{
	$stoppoint = array_search("# LABELSETS TABLE\r\n", $bigarray);
}
else
{
	$stoppoint = count($bigarray)-1;
}
for ($i=0; $i<=$stoppoint+1; $i++)
{
	if ($i<$stoppoint-2) {$answerarray[] = str_replace("`default`", "`default_value`", $bigarray[$i]);}
	unset($bigarray[$i]);
}
$bigarray = array_values($bigarray);

//LABELSETS
if (array_search("# LABELS TABLE\n", $bigarray))
{
	$stoppoint = array_search("# LABELS TABLE\n", $bigarray);
}
elseif (array_search("# LABELS TABLE\r\n", $bigarray))
{
	$stoppoint = array_search("# LABELS TABLE\r\n", $bigarray);
}
else
{
	$stoppoint = count($bigarray)-1;
}
for ($i=0; $i<=$stoppoint+1; $i++)
{
	if ($i<$stoppoint-2) {$labelsetsarray[] = $bigarray[$i];}
	unset($bigarray[$i]);
}
$bigarray = array_values($bigarray);

//LABELS
if (array_search("# QUESTION_ATTRIBUTES TABLE\n", $bigarray))
{
	$stoppoint = array_search("# QUESTION_ATTRIBUTES TABLE\n", $bigarray);
}
elseif (array_search("# QUESTION_ATTRIBUTES TABLE\r\n", $bigarray))
{
	$stoppoint = array_search("# QUESTION_ATTRIBUTES TABLE\r\n", $bigarray);
}
else
{
	$stoppoint = count($bigarray)-1;
}
for ($i=0; $i<=$stoppoint+1; $i++)
{
	if ($i<$stoppoint-2) {$labelsarray[] = $bigarray[$i];}
	unset($bigarray[$i]);
}
$bigarray = array_values($bigarray);

//Question_attributes
if (!isset($noconditions) || $noconditions != "Y")
{
	$stoppoint = count($bigarray);
	for ($i=0; $i<=$stoppoint+1; $i++)
	{
		if ($i<$stoppoint-1) {$question_attributesarray[] = $bigarray[$i];}
		unset($bigarray[$i]);
	}
}
$bigarray = array_values($bigarray);

if (isset($questionarray)) {$countquestions = count($questionarray)-1;}  else {$countquestions=0;}
if (isset($answerarray)) 
    {
        $answerfieldnames=convertCSVRowToArray($answerarray[0],',','"');
        unset($answerarray[0]);
        $countanswers = count($answerarray);
    }  else {$countanswers=0;}
if (isset($labelsetsarray)) {$countlabelsets = count($labelsetsarray)-1;}  else {$countlabelsets=0;}
if (isset($labelsarray)) {$countlabels = count($labelsarray)-1;}  else {$countlabels=0;}
if (isset($question_attributesarray)) {$countquestion_attributes = count($question_attributesarray)-1;} else {$countquestion_attributes=0;}


// Let's check that imported objects support at least the survey's baselang
$langcode = GetBaseLanguageFromSurveyID($_POST['sid']);
if ($countquestions > 0)
{
	$questionfieldnames = convertCSVRowToArray($questionarray[0],',','"');
	$langfieldnum = array_search("language", $questionfieldnames);
	$qidfieldnum = array_search("qid", $questionfieldnames);
	$questionssupportbaselang = bDoesImportarraySupportsLanguage($questionarray,Array($qidfieldnum), $langfieldnum,$langcode,true);
	if (!$questionssupportbaselang)
	{
		$importquestion .= "<strong><font color='red'>".$clang->gT("Error")."</font></strong><br />\n"
		.$clang->gT("You can't import a question which doesn't support the current survey's base language")."<br /><br />\n"
		."</td></tr></table>\n";
		unlink($the_full_file_path);
		return;
	}
}

// Let's assume that if the questions do support tye baselang
// Then the answers do support it as well.
// ==> So the following section is commented for now
//if ($countanswers > 0)
//{
//	$langfieldnum = array_search("language", $answerfieldnames);
//	$answercodefilednum1 =  array_search("qid", $answerfieldnames);
//	$answercodefilednum2 =  array_search("code", $answerfieldnames);
//	$answercodekeysarr = Array($answercodefilednum1,$answercodefilednum2);
//	$answerssupportbaselang = bDoesImportarraySupportsLanguage($answerarray,$answercodekeysarr,$langfieldnum,$langcode);
//	if (!$answerssupportbaselang)
//	{
//		$importquestion .= "<strong><font color='red'>".$clang->gT("Error")."</font></strong><br />\n"
//		.$clang->gT("You can't import answers which don't support current survey's base language")."<br /><br />\n"
//		."</td></tr></table>\n";
//		return;
//	}
//	
//}

if ($countlabelsets > 0)
{
	$labelsetfieldname = convertCSVRowToArray($labelsetsarray[0],',','"');
	$langfieldnum = array_search("languages", $labelsetfieldname);
	$lidfilednum =  array_search("lid", $labelsetfieldname);
	$labelsetssupportbaselang = bDoesImportarraySupportsLanguage($labelsetsarray,Array($lidfilednum),$langfieldnum,$langcode,true);
	if (!$labelsetssupportbaselang)
	{
		$importquestion .= "<strong><font color='red'>".$clang->gT("Error")."</font></strong><br />\n"
		.$clang->gT("You can't import label sets which don't support the current survey's base language")."<br /><br />\n"
		."</td></tr></table>\n";
		unlink($the_full_file_path);
		return;
	}
}
// I assume that if a labelset supports the survey's baselang,
// then it's labels do support it as well

// GET SURVEY AND GROUP DETAILS
$surveyid=$_POST['sid'];
$gid=$_POST['gid'];
$newsid=$surveyid;
$newgid=$gid;

//DO ANY LABELSETS FIRST, SO WE CAN KNOW WHAT THEY'RE NEW LID IS FOR THE QUESTIONS
if (isset($labelsetsarray) && $labelsetsarray) {
	$csarray=buildLabelsetCSArray();
    $fieldorders  =convertCSVRowToArray($labelsetsarray[0],',','"');
	foreach ($labelsetsarray as $lsa) {
        $fieldcontents=convertCSVRowToArray($lsa,',','"');
        $labelsetrowdata=array_combine($fieldorders,$fieldcontents);
		$oldcid=$labelsetrowdata["cid"];
		$oldqid=$labelsetrowdata["qid"];
		$oldlid=$labelsetrowdata["lid"];
		unset($labelsetrowdata["lid"]);
        $newvalues=array_values($labelsetrowdata);
        $newvalues=array_map(array(&$connect, "qstr"),$newvalues); // quote everything accordingly
        $lsainsert = "insert INTO {$dbprefix}labelsets (".implode(',',array_keys($labelsetrowdata)).") VALUES (".implode(',',$newvalues).")"; //handle db prefix
		$lsiresult=$connect->Execute($lsainsert);
        $newlid=$connect->Insert_ID();

		if ($labelsarray) {
            $lfieldorders  =convertCSVRowToArray($labelsarray[0],',','"');
			foreach ($labelsarray as $la) {
				//GET ORDER OF FIELDS
                $lfieldcontents=convertCSVRowToArray($la,',','"');
         		$labelrowdata=array_combine($lfieldorders,$lfieldcontents);
				$labellid=$labelrowdata['lid'];
				if ($labellid == $oldlid) {
					$labelrowdata['lid']=$newlid;
                    $newvalues=array_values($labelrowdata);
                    $newvalues=array_map(array(&$connect, "qstr"),$newvalues); // quote everything accordingly
                    $lainsert = "insert INTO {$dbprefix}labels (".implode(',',array_keys($labelrowdata)).") VALUES (".implode(',',$newvalues).")"; //handle db prefix
					$liresult=$connect->Execute($lainsert);
				}
			}
		}

		//CHECK FOR DUPLICATE LABELSETS
		$thisset="";
		$query2 = "SELECT code, title, sortorder
				   FROM {$dbprefix}labels
				   WHERE lid=".$newlid."
				   ORDER BY sortorder, code";
		$result2 = db_execute_num($query2) or die("Died querying labelset $lid<br />$query2<br />".$connect->ErrorMsg());
		while($row2=$result2->FetchRow())
		{
			$thisset .= implode('.',$row2);
		} // while
		$newcs=dechex(crc32($thisset)*1);
		if (isset($csarray))
		{
			foreach($csarray as $key=>$val)
			{
				if ($val == $newcs)
				{
					$lsmatch=$key;
				}
			}
		}
		if (isset($lsmatch))
		{
			//There is a matching labelset. So, we will delete this one and refer
			//to the matched one.
			$query = "DELETE FROM {$dbprefix}labels WHERE lid=$newlid";
			$result=$connect->Execute($query) or die("Couldn't delete labels<br />$query<br />".$connect->ErrorMsg());
			$query = "DELETE FROM {$dbprefix}labelsets WHERE lid=$newlid";
			$result=$connect->Execute($query) or die("Couldn't delete labelset<br />$query<br />".$connect->ErrorMsg());
			$newlid=$lsmatch;
		}
		else
		{
			//There isn't a matching labelset, add this checksum to the $csarray array
			$csarray[$newlid]=$newcs;
		}
		//END CHECK FOR DUPLICATES
		$labelreplacements[]=array($oldlid, $newlid);
	}
}

// QUESTIONS, THEN ANSWERS FOR QUESTIONS IN A NESTED FORMAT!
if (isset($questionarray) && $questionarray) {
    $qafieldorders=convertCSVRowToArray($questionarray[0],',','"');
    unset($questionarray[0]);
	foreach ($questionarray as $qa) {
        $qacfieldcontents=convertCSVRowToArray($qa,',','"');
		$newfieldcontents=$qacfieldcontents;
    	$questionrowdata=array_combine($qafieldorders,$qacfieldcontents);
		$oldqid = $questionrowdata['qid'];
		$oldsid = $questionrowdata['sid'];
		$oldgid = $questionrowdata['gid'];
    	// Remove qid field
		unset($questionrowdata['qid']);
		$questionrowdata["sid"] = $newsid;
		$questionrowdata["gid"] = $newgid;

        $qmaxqo = "select max(question_order) as maxqo from ".db_table_name('questions')." where sid=$newsid and gid=$newgid";
		$qres = db_execute_assoc($qmaxqo) or die ("<strong>".$clang->gT("Error")."</strong> Failed to find out maximum question order value<br />\n$qmaxqo<br />\n".$connect->ErrorMsg()."</body>\n</html>");
        $qrow=$qres->FetchRow();
		$questionrowdata["question_order"]= $qrow['maxqo']+1; // echo $questionrowdata["question_order"];
        // Now we will fix up the label id 
		$type = $questionrowdata["type"]; //Get the type
		if ($type == "F" || $type == "H" || $type == "W" || $type == "Z") 
            {//IF this is a flexible label array, update the lid entry
			if (isset($labelreplacements)) {
				foreach ($labelreplacements as $lrp) {
					if ($lrp[0] == $questionrowdata["lid"]) {
						$questionrowdata["lid"]=$lrp[1];
					   }
				    }
			     }
            }
		$other = $questionrowdata["other"]; //Get 'other' field value
		$oldlid = $questionrowdata["lid"];
        $questionrowdata=array_map('convertCsvreturn2return', $questionrowdata);
        $newvalues=array_values($questionrowdata);
        $newvalues=array_map(array(&$connect, "qstr"),$newvalues); // quote everything accordingly
        $qinsert = "insert INTO {$dbprefix}questions (".implode(',',array_keys($questionrowdata)).") VALUES (".implode(',',$newvalues).")"; 
		$qres = $connect->Execute($qinsert) or die ("<strong>".$clang->gT("Error")."</strong> Failed to insert question<br />\n$qinsert<br />\n".$connect->ErrorMsg()."</body>\n</html>");
		$newqid=$connect->Insert_ID();
		$newrank=0;
		//NOW DO NESTED ANSWERS FOR THIS QID
    	if (isset($answerarray) && $answerarray) {
    		foreach ($answerarray as $aa) {
                $answerfieldcontents=convertCSVRowToArray($aa,',','"');
                $answerrowdata=array_combine($answerfieldnames,$answerfieldcontents);
    			$code=$answerrowdata["code"];
    			$thisqid=$answerrowdata["qid"];
				$answerrowdata["qid"]=$newqid;
                        $newvalues=array_values($answerrowdata);
                        $newvalues=array_map(array(&$connect, "qstr"),$newvalues); // quote everything accordingly
                        $ainsert = "insert INTO {$dbprefix}answers (".implode(',',array_keys($answerrowdata)).") VALUES (".implode(',',$newvalues).")"; 
				$ares = $connect->Execute($ainsert) or die ("<strong>".$clang->gT("Error")."</strong> Failed to insert answer<br />\n$ainsert<br />\n".$connect->ErrorMsg()."</body>\n</html>");
				
				if ($type == "M" || $type == "P") {
					$fieldnames[]=array("oldcfieldname"=>$oldsid."X".$oldgid."X".$oldqid,
					"newcfieldname"=>$newsid."X".$newgid."X".$newqid,
					"oldfieldname"=>$oldsid."X".$oldgid."X".$oldqid.$code,
					"newfieldname"=>$newsid."X".$newgid."X".$newqid.$code);
					if ($type == "P") {
						$fieldnames[]=array("oldcfieldname"=>$oldsid."X".$oldgid."X".$oldqid."comment",
						"newcfieldname"=>$newsid."X".$newgid."X".$newqid.$code."comment",
						"oldfieldname"=>$oldsid."X".$oldgid."X".$oldqid.$code."comment",
						"newfieldname"=>$newsid."X".$newgid."X".$newqid.$code."comment");
					}
				}
				elseif ($type == "A" || $type == "B" || $type == "C" || $type == "F" || $type == "H") {
					$fieldnames[]=array("oldcfieldname"=>$oldsid."X".$oldgid."X".$oldqid.$code,
					"newcfieldname"=>$newsid."X".$newgid."X".$newqid.$code,
					"oldfieldname"=>$oldsid."X".$oldgid."X".$oldqid.$code,
					"newfieldname"=>$newsid."X".$newgid."X".$newqid.$code);
				}
				elseif ($type == "R") {
					$newrank++;
				}
    		}
    		if (($type == "A" || $type == "B" || $type == "C" || $type == "M" || $type == "P" || $type == "L") && ($other == "Y")) {
    			$fieldnames[]=array("oldcfieldname"=>$oldsid."X".$oldgid."X".$oldqid."other",
    			"newcfieldname"=>$newsid."X".$newgid."X".$newqid."other",
    			"oldfieldname"=>$oldsid."X".$oldgid."X".$oldqid."other",
    			"newfieldname"=>$newsid."X".$newgid."X".$newqid."other");
    			if ($type == "P") {
    				$fieldnames[]=array("oldcfieldname"=>$oldsid."X".$oldgid."X".$oldqid."othercomment",
    				"newcfieldname"=>$newsid."X".$newgid."X".$newqid."othercomment",
    				"oldfieldname"=>$oldsid."X".$oldgid."X".$oldqid."othercomment",
    				"newfieldname"=>$newsid."X".$newgid."X".$newqid."othercomment");
    			}
    		}
    		if ($type == "R" && $newrank >0) {
    			for ($i=1; $i<=$newrank; $i++) {
    				$fieldnames[]=array("oldcfieldname"=>$oldsid."X".$oldgid."X".$oldqid.$i,
    				"newcfieldname"=>$newsid."X".$newgid."X".$newqid.$i,
    				"oldfieldname"=>$oldsid."X".$oldgid."X".$oldqid.$i,
    				"newfieldname"=>$newsid."X".$newgid."X".$newqid.$i);
    			}
    		}
    		if ($type != "A" && $type != "B" && $type != "C" && $type != "R" && $type != "M" && $type != "P") {
    			$fieldnames[]=array("oldcfieldname"=>$oldsid."X".$oldgid."X".$oldqid,
    			"newcfieldname"=>$newsid."X".$newgid."X".$newqid,
    			"oldfieldname"=>$oldsid."X".$oldgid."X".$oldqid,
    			"newfieldname"=>$newsid."X".$newgid."X".$newqid);
    			if ($type == "O") {
    				$fieldnames[]=array("oldcfieldname"=>$oldsid."X".$oldgid."X".$oldqid."comment",
    				"newcfieldname"=>$newsid."X".$newgid."X".$newqid."comment",
    				"oldfieldname"=>$oldsid."X".$oldgid."X".$oldqid."comment",
    				"newfieldname"=>$newsid."X".$newgid."X".$newqid."comment");
    			}
    		}
    	} else {
    		$fieldnames[]=array("oldcfieldname"=>$oldsid."X".$oldgid."X".$oldqid,
    		"newcfieldname"=>$newsid."X".$newgid."X".$newqid,
    		"oldfieldname"=>$oldsid."X".$oldgid."X".$oldqid,
    		"newfieldname"=>$newsid."X".$newgid."X".$newqid);
    	}
    }
}


// Finally the question attributes
if (isset($question_attributesarray) && $question_attributesarray) {//ONLY DO THIS IF THERE ARE QUESTION_ATTRIBUES
    $fieldorders  =convertCSVRowToArray($question_attributesarray[0],',','"');
    unset($question_attributesarray[0]);
	foreach ($question_attributesarray as $qar) {
        $fieldcontents=convertCSVRowToArray($qar,',','"');
        $qarowdata=array_combine($fieldorders,$fieldcontents);
		$qarowdata["qid"]=$newqid;
		unset($qarowdata["qaid"]);

        $newvalues=array_values($qarowdata);
        $newvalues=array_map(array(&$connect, "qstr"),$newvalues); // quote everything accordingly
        $qainsert = "insert INTO {$dbprefix}question_attributes (".implode(',',array_keys($qarowdata)).") VALUES (".implode(',',$newvalues).")"; 
		$result=$connect->Execute($qainsert) or die ("Couldn't insert question_attribute<br />$qainsert<br />".$connect->ErrorMsg());
	}
}



$importquestion .= "<strong><font color='green'>".$clang->gT("Success")."</font></strong><br /><br />\n"
."<strong><u>".$clang->gT("Question Import Summary")."</u></strong><br />\n"
."\t<li>".$clang->gT("Questions").": ";
if (isset($countquestions)) {$importquestion .= $countquestions;}
$importquestion .= "</li>\n"
."\t<li>".$clang->gT("Answers").": ";
if (isset($countanswers)) {$importquestion .= $countanswers;}
$importquestion .= "</li>\n"
."\t<li>".$clang->gT("Label Sets").": ";
if (isset($countlabelsets)) {$importquestion .= $countlabelsets;}
$importquestion .= " (";
if (isset($countlabels)) {$importquestion .= $countlabels;}
$importquestion .= ")</li>\n";
$importquestion .= "\t<li>".$clang->gT("Question Attributes:");
if (isset($countquestion_attributes)) {$importquestion .= $countquestion_attributes;}
$importquestion .= "</li></ul><br />\n";

$importquestion .= "<strong>".$clang->gT("Import of Survey is completed.")."</strong><br />&nbsp;\n"
."</td></tr></table><br/>\n";


unlink($the_full_file_path);


?>
