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

if ($action == "addquestion")
{
	$newquestionoutput =  "\t<form action='$scriptname' name='addnewquestion1' method='post'>\n"
	. "<table width='100%' border='0'>\n\n"
	. "\t<tr>\n"
	. "\t\t<td colspan='2' class='settingcaption'>"
	. "\t\t<strong>".$clang->gT("Add Question")."\n"
	. "\t\t</strong></td>\n"
	. "\t</tr>\n"
	. "\t<tr>\n"
	. "\t\t<td align='right'  width='35%'><strong>".$clang->gT("Code:")."</strong></td>\n"
	. "\t\t<td align='left'><input type='text' maxlength='20' size='20' name='title' />"
	. "<font color='red' face='verdana' size='1'> ".$clang->gT("Required")."</font></td></tr>\n"
	. "\t<tr>\n"
	. "\t\t<td align='right' width='35%'><strong>".$clang->gT("Question:")."</strong></td>\n"
	. "\t\t<td align='left'><textarea cols='50' rows='3' name='question'></textarea></td>\n"
	. "\t</tr>\n"
	. "\t<tr>\n"
	. "\t\t<td align='right' width='35%'><strong>".$clang->gT("Help:")."</strong></td>\n"
	. "\t\t<td align='left'><textarea cols='50' rows='3' name='help'></textarea></td>\n"
	. "\t</tr>\n"
	. "\t<tr>\n"
	. "\t\t<td align='right' width='35%'><strong>".$clang->gT("Type:")."</strong></td>\n"
	. "\t\t<td align='left'><select name='type' id='question_type' "
	. "onchange='OtherSelection(this.options[this.selectedIndex].value);'>\n"
	. "$qtypeselect"
	. "\t\t</select></td>\n"
	. "\t</tr>\n";

	$newquestionoutput .= "\t<tr id='Validation'>\n"
	. "\t\t<td align='right'><strong>".$clang->gT("Validation:")."</strong></td>\n"
	. "\t\t<td align='left'>\n"
	. "\t\t<input type='text' name='preg' size='50' />\n"
	. "\t\t</td>\n"
	. "\t</tr>\n";

	$newquestionoutput .= "\t<tr id='LabelSets' style='display: none'>\n"
	. "\t\t<td align='right'><strong>".$clang->gT("Label Set:")."</strong></td>\n"
	. "\t\t<td align='left'>\n"
	. "\t\t<select name='lid' >\n";
	$labelsets=getlabelsets(GetBaseLanguageFromSurveyID($surveyid));
	if (count($labelsets)>0)
	{
		$newquestionoutput .= "\t\t\t<option value=''>".$clang->gT("Please Choose...")."</option>\n";
		foreach ($labelsets as $lb)
		{
			$newquestionoutput .= "\t\t\t<option value='{$lb[0]}'>{$lb[1]}</option>\n";
		}
	}
	$newquestionoutput .= "\t\t</select>\n"
	. "\t\t</td>\n"
	. "\t</tr>\n";

	$newquestionoutput .= "\t<tr id='OtherSelection' style='display: none'>\n"
	. "\t\t<td align='right'><strong>".$clang->gT("Other:")."</strong></td>\n"
	. "\t\t<td align='left'>\n"
	. "\t\t\t<label for='OY'>".$clang->gT("Yes")."</label>"
	. "<input id='OY' type='radio' class='radiobtn' name='other' value='Y' />&nbsp;&nbsp;\n"
	. "\t\t\t<label for='ON'>".$clang->gT("No")."</label>"
	. "<input id='ON' type='radio' class='radiobtn' name='other' value='N' checked='checked' />\n"
	. "\t\t</td>\n"
	. "\t</tr>\n";

	$newquestionoutput .= "\t<tr id='MandatorySelection'>\n"
	. "\t\t<td align='right'><strong>".$clang->gT("Mandatory:")."</strong></td>\n"
	. "\t\t<td align='left'>\n"
	. "\t\t\t<label for='MY'>".$clang->gT("Yes")."</label>"
	. "<input id='MY' type='radio' class='radiobtn' name='mandatory' value='Y' />&nbsp;&nbsp;\n"
	. "\t\t\t<label for='MN'>".$clang->gT("No")."</label>"
	. "<input id='MN' type='radio' class='radiobtn' name='mandatory' value='N' checked='checked' />\n"
	. "\t\t</td>\n"
	. "\t</tr>\n";

	//Question attributes
	$qattributes=questionAttributes();

	$newquestionoutput .= "\t<tr id='QTattributes'>
						<td align='right'><strong>".$clang->gT("Question Attributes:")."</strong></td>
						<td align='left'><select id='QTlist' name='attribute_name' >
						</select>
						<input type='text' id='QTtext' name='attribute_value'  /></td></tr>\n";
	$newquestionoutput .= "\t<tr>\n"
	. "\t\t<td colspan='2' align='center'>";

	if (isset($eqrow)) {$newquestionoutput .= questionjavascript($eqrow['type'], $qattributes);}
	else {$newquestionoutput .= questionjavascript('', $qattributes);}

	$newquestionoutput .= "<input type='submit' value='"
	. $clang->gT("Add Question")."' />\n"
	. "\t\n"
	. "\t<input type='hidden' name='action' value='insertnewquestion' />\n"
	. "\t<input type='hidden' name='sid' value='$surveyid' />\n"
	. "\t<input type='hidden' name='gid' value='$gid' />\n"
	. "</td></tr></table>\n"
	. "\t</form>\n"
	. "\t<form enctype='multipart/form-data' name='importquestion' action='$scriptname' method='post' onsubmit='return validatefilename(this,\"".$clang->gT('Please select a file to import!','js')."\");'>\n"
	. "<table width='100%' border='0' >\n\t"
	. "<tr><td colspan='2' align='center'><strong>".$clang->gT("OR")."</strong></td></tr>\n"
	. "<tr><td colspan='2' class='settingcaption'>\n"
	. "\t\t<strong>".$clang->gT("Import Question")."</strong></td></tr>\n\t<tr>"
	. "\t\t<td align='right' width='35%'><strong>".$clang->gT("Select CSV File").":</strong></td>\n"
	. "\t\t<td align='left'><input name=\"the_file\" type=\"file\" size=\"50\" /></td></tr>\n"
	. "\t<tr><td colspan='2' align='center'><input type='submit' "
	. "value='".$clang->gT("Import Question")."' />\n"
	. "\t<input type='hidden' name='action' value='importquestion' />\n"
	. "\t<input type='hidden' name='sid' value='$surveyid' />\n"
	. "\t<input type='hidden' name='gid' value='$gid' />\n"
	. "\t</td></tr></table></form>\n\n";

}

if ($action == "copyquestion")
{
	$questlangs = GetAdditionalLanguagesFromSurveyID($surveyid);
	$baselang = GetBaseLanguageFromSurveyID($surveyid);
	array_unshift($questlangs,$baselang);
	$qattributes=questionAttributes();
	$editquestion ="<table width='100%' border='0' class='form2columns'>\n\t<tr><th>"
	. "\t\t".$clang->gT("Copy Question")."</th></tr></table>\n"
	. "<form name='frmeditquestion' action='$scriptname' method='post'>\n"
	. '<div class="tab-pane" id="tab-pane-1">';
	foreach ($questlangs as $language)
	{
    	$egquery = "SELECT * FROM ".db_table_name('questions')." WHERE sid=$surveyid AND gid=$gid AND qid=$qid and language=".db_quoteall($language);
        $egresult = db_execute_assoc($egquery);
	    $eqrow = $egresult->FetchRow();
		$eqrow = array_map('htmlspecialchars', $eqrow);
    	$editquestion .= '<div class="tab-page"> <h2 class="tab">'.getLanguageNameFromCode($eqrow['language'],false);
    	if ($eqrow['language']==GetBaseLanguageFromSurveyID($surveyid)) 
        {
            $editquestion .= "(".$clang->gT("Base Language").")</h2>"
        	. "\t<div class='settingrow'><span class='settingcaption'>".$clang->gT("Code:")."</span>\n"
        	. "\t\t<span class='settingentry'><input type='text' size='20' maxlength='20' name='title' value='' /> ".$clang->gT("Note: You MUST enter a new question code!")."\n"
        	. "\t</span></div>\n";
        }
    	else {
    	        $editquestion .= '</h2>';
             }    
		$editquestion .=  "\t<div class='settingrow'><span class='settingcaption'>".$clang->gT("Question:")."</span>\n"
		. "\t\t<span class='settingentry'><textarea cols='50' rows='4' name='question_{$eqrow['language']}'>{$eqrow['question']}</textarea>\n"
		. "\t</span></div>\n"
		. "\t<div class='settingrow'><span class='settingcaption'>".$clang->gT("Help:")."</span>\n"
		. "\t\t<span class='settingentry'><textarea cols='50' rows='4' name='help_{$eqrow['language']}'>{$eqrow['help']}</textarea>\n"
		. "\t</span></div>\n"
        . "\t<div class='settingrow'><span class='settingcaption'></span>\n"
        . "\t\t<span class='settingentry'>\n"
        . "\t</span></div>\n";
		$editquestion .= '</div>';
    }
    $editquestion .= "\t<table class='form2columns'><tr>\n"
	. "\t\t<td align='right'><strong>".$clang->gT("Type:")."</strong></td>\n"
	. "\t\t<td><select name='type' onchange='OtherSelection(this.options[this.selectedIndex].value);'>\n"
	. getqtypelist($eqrow['type'])
	. "\t\t</select></td>\n"
	. "\t</tr>\n";

	$editquestion .= "\t<tr id='Validation'>\n"
	. "\t\t<td align='right'><strong>".$clang->gT("Validation:")."</strong></td>\n"
	. "\t\t<td>\n"
	. "\t\t<input type='text' name='preg' size='50' value=\"".$eqrow['preg']."\" />\n"
	. "\t\t</td>\n"
	. "\t</tr>\n";

	$editquestion .= "\t<tr id='LabelSets' style='display: none'>\n"
	. "\t\t<td><strong>".$clang->gT("Label Set:")."</strong></td>\n"
	. "\t\t<td>\n"
	. "\t\t<select name='lid' >\n";
	$labelsets=getlabelsets(GetBaseLanguageFromSurveyID($surveyid));
		if (count($labelsets)>0)
		{
			if (!$eqrow['lid'])
			{
				$editquestion .= "\t\t\t<option value=''>".$clang->gT("Please Choose...")."</option>\n";
			}
			foreach ($labelsets as $lb)
			{
				$editquestion .= "\t\t\t<option value='{$lb[0]}'";
				if ($eqrow['lid'] == $lb[0]) {$editquestion .= " selected";}
				$editquestion .= ">{$lb[1]}</option>\n";
			}
		}
		$editquestion .= "\t\t</select>\n"
		. "\t\t</td>\n"
		. "\t</tr>\n"
		. "\t<tr>\n"
		. "\t\t<td ><strong>".$clang->gT("Group:")."</strong></td>\n"
		. "\t\t<td><select name='gid'>\n"
		. getgrouplist3($eqrow['gid'])
		. "\t\t\t</select></td>\n"
		. "\t</tr>\n";

		$editquestion .= "\t<tr id='OtherSelection' style='display: none'>\n"
		. "\t\t<td><strong>".$clang->gT("Other:")."</strong></td>\n";

		$editquestion .= "\t\t<td>\n"
		. "\t\t\t".$clang->gT("Yes")." <input type='radio' class='radiobtn' name='other' value='Y'";
		if ($eqrow['other'] == "Y") {$editquestion .= " checked";}
		$editquestion .= " />&nbsp;&nbsp;\n"
		. "\t\t\t".$clang->gT("No")." <input type='radio' class='radiobtn' name='other' value='N'";
		if ($eqrow['other'] == "N") {$editquestion .= " checked";}
		$editquestion .= " />\n"
		. "\t\t</td>\n"
		. "\t</tr>\n";

		$editquestion .= "\t<tr id='MandatorySelection'>\n"
		. "\t\t<td><strong>".$clang->gT("Mandatory:")."</strong></td>\n"
		. "\t\t<td>\n"
		. "\t\t\t".$clang->gT("Yes")." <input type='radio' class='radiobtn' name='mandatory' value='Y'";
		if ($eqrow['mandatory'] == "Y") {$editquestion .= " checked='checked'";}
		$editquestion .= " />&nbsp;&nbsp;\n"
		. "\t\t\t".$clang->gT("No")." <input type='radio' class='radiobtn' name='mandatory' value='N'";
		if ($eqrow['mandatory'] != "Y") {$editquestion .= " checked='checked'";}
		$editquestion .= " />\n"
		. "\t\t</td>\n"
		. "\t</tr>\n"
		. "\t<tr>\n"
		. "\t\t<td align='right'>";

		$editquestion .= questionjavascript($eqrow['type'], $qattributes);

		if ($eqrow['type'] == "J" || $eqrow['type'] == "I")
		{
			$editquestion .= "\t<tr>\n"
			. "\t\t<input type='hidden' name='copyanswers' value='Y'>\n"
			. "\t\t<td colspan='2' class='centered'><input type='submit' value='".$clang->gT("Copy Question")."' />\n"
			. "\t\t<input type='hidden' name='action' value='copynewquestion' />\n"
			. "\t\t<input type='hidden' name='sid' value='$sid' />\n"
			. "\t\t<input type='hidden' name='oldqid' value='$qid' />\n"
			. "\t\t<input type='hidden' name='gid' value='$gid' />\n"
			. "\t</td></tr>\n"
			. "</table></form>\n";
		}
		else
		{

			$editquestion .= "<strong>".$clang->gT("Copy Answers?")."</strong></td>\n"
			. "\t\t<td><input type='checkbox' class='checkboxbtn' checked name='copyanswers' value='Y' />"
			. "</td>\n"
			. "\t</tr>\n"
			. "\t<tr>\n"
			. "\t\t<td ><strong>".$clang->gT("Copy Attributes?")."</strong></td>\n"
			. "\t\t<td><input type='checkbox' class='checkboxbtn' checked name='copyattributes' value='Y' />"
			. "</td>\n"
			. "\t</tr>\n"
			. "\t<tr>\n"
			. "\t\t<td colspan='2'  class='centered'><input type='submit' value='".$clang->gT("Copy Question")."' />\n"
			. "\t\t<input type='hidden' name='action' value='copynewquestion' />\n"
			. "\t\t<input type='hidden' name='sid' value='$surveyid' />\n"
			. "\t\t<input type='hidden' name='oldqid' value='$qid' />\n"
			. "\t</td></tr>\n"
			. "</table>\n</form>\n";
		}
}

if ($action == "editquestion" || $action == "editattribute" || $action == "delattribute" || $action == "addattribute")
{
	
		$questlangs = GetAdditionalLanguagesFromSurveyID($surveyid);
		$baselang = GetBaseLanguageFromSurveyID($surveyid);
		$questlangs[] = $baselang;
		$questlangs = array_flip($questlangs);
		$egquery = "SELECT * FROM ".db_table_name('questions')." WHERE sid=$surveyid AND gid=$gid AND qid=$qid";
		$egresult = db_execute_assoc($egquery);
		while ($esrow = $egresult->FetchRow())
		{
			if(!array_key_exists($esrow['language'], $questlangs)) // Language Exists, BUT ITS NOT ON THE SURVEY ANYMORE.
			{
				$egquery = "DELETE FROM ".db_table_name('questions')." WHERE sid='{$surveyid}' AND gid='{$gid}' AND qid='{$qid}' AND language='".$esrow['language']."'";
				$egresultD = $connect->Execute($egquery);
			} else {
				$questlangs[$esrow['language']] = 99;
			}
			if ($esrow['language'] == $baselang) $basesettings = array('lid' => $esrow['lid'],'question_order' => $esrow['question_order'],'other' => $esrow['other'],'mandatory' => $esrow['mandatory'],'type' => $esrow['type'],'title' => $esrow['title'],'preg' => $esrow['preg'],'question' => $esrow['question'],'help' => $esrow['help']);

		}
	
		while (list($key,$value) = each($questlangs))
		{
			if ($value != 99)
			{
                if ($databasetype=='odbc_mssql') {@$connect->Execute("SET IDENTITY_INSERT ".db_table_name('questions')." ON");}
				$egquery = "INSERT INTO ".db_table_name('questions')." (qid, sid, gid, type, title, question, preg, help, other, mandatory, lid, question_order, language)"
				." VALUES ('{$qid}','{$surveyid}', '{$gid}', '{$basesettings['type']}', '{$basesettings['title']}',"
				." '{$basesettings['question']}', '{$basesettings['preg']}', '{$basesettings['help']}', '{$basesettings['other']}', '{$basesettings['mandatory']}', '{$basesettings['lid']}','{$basesettings['question_order']}','{$key}')";
				$egresult = $connect->Execute($egquery);
                if ($databasetype=='odbc_mssql') {@$connect->Execute("SET IDENTITY_INSERT ".db_table_name('questions')." OFF");}
			}
		}
	
	$eqquery = "SELECT * FROM {$dbprefix}questions WHERE sid=$surveyid AND gid=$gid AND qid=$qid AND language='{$baselang}'";
	$eqresult = db_execute_assoc($eqquery);
	$editquestion ="<table width='100%' border='0'>\n\t<tr><td class='settingcaption'>"
	. "\t\t".$clang->gT("Edit Question")."</td></tr></table>\n"
	. "<form name='frmeditquestion' action='$scriptname' method='post'>\n"
	. '<div class="tab-pane" id="tab-pane-1">';
	
    $eqrow = $eqresult->FetchRow();  // there should be only one datarow, therefore we don't need a 'while' construct here.
                                     // Todo: handler in case that record is not found  

	$editquestion .= '<div class="tab-page"> <h2 class="tab">'.getLanguageNameFromCode($eqrow['language'],false);
	$editquestion .= '('.$clang->gT("Base Language").')';
	$eqrow  = array_map('htmlspecialchars', $eqrow);
	$editquestion .= '</h2>';
	$editquestion .= "\t<div class='settingrow'><span class='settingcaption'>".$clang->gT("Code:")."</span>\n"
	. "\t\t<span class='settingentry'><input type='text' size='20' maxlength='20'  name='title' value=\"{$eqrow['title']}\" />\n"
	. "\t</span></div>\n";
	$editquestion .=  "\t<div class='settingrow'><span class='settingcaption'>".$clang->gT("Question:")."</span>\n"
	. "\t\t<span class='settingentry'><textarea cols='50' rows='4' name='question_{$eqrow['language']}'>{$eqrow['question']}</textarea>\n"
	. "\t</span></div>\n"
	. "\t<div class='settingrow'><span class='settingcaption'>".$clang->gT("Help:")."</span>\n"
	. "\t\t<span class='settingentry'><textarea cols='50' rows='4' name='help_{$eqrow['language']}'>{$eqrow['help']}</textarea>\n"
	. "\t</span></div>\n"
	. "\t<div class='settingrow'><span class='settingcaption'>&nbsp;</span>\n"
	. "\t\t<span class='settingentry'>&nbsp;\n"
	. "\t</span></div>\n";
	$editquestion .= '</div>';
	
	$aqquery = "SELECT * FROM {$dbprefix}questions WHERE sid=$surveyid AND gid=$gid AND qid=$qid AND language != '{$baselang}'";
	$aqresult = db_execute_assoc($aqquery);
	while (!$aqresult->EOF) 
	{
    $aqrow = $aqresult->FetchRow();
		$editquestion .= '<div class="tab-page"> <h2 class="tab">'.getLanguageNameFromCode($aqrow['language'],false);
		$aqrow  = array_map('htmlspecialchars', $aqrow);
		$editquestion .= '</h2>';
		$editquestion .=  "\t<div class='settingrow'><span class='settingcaption'>".$clang->gT("Question:")."</span>\n"
		. "\t\t<span class='settingentry'><textarea cols='50' rows='4' name='question_{$aqrow['language']}'>{$aqrow['question']}</textarea>\n"
		. "\t</span></div>\n"
		. "\t<div class='settingrow'><span class='settingcaption'>".$clang->gT("Help:")."</span>\n"
		. "\t\t<span class='settingentry'><textarea cols='50' rows='4' name='help_{$aqrow['language']}'>{$aqrow['help']}</textarea>\n"
		. "\t</span></div>\n"
		. "\t<div class='settingrow'><span class='settingcaption'>&nbsp;</span>\n"
		. "\t\t<span class='settingentry'>&nbsp;\n"
		. "\t</span></div>\n";
		$editquestion .= '</div>';
	}
	
		
 		//question type:
  		$editquestion .= "\t<table><tr>\n"
  		. "\t\t<td align='right'><strong>".$clang->gT("Type:")."</strong></td>\n";
  		if ($activated != "Y")
  		{
  			$editquestion .= "\t\t<td align='left'><select id='question_type' name='type' "
  			. "onchange='OtherSelection(this.options[this.selectedIndex].value);'>\n"
  			. getqtypelist($eqrow['type'])
  			. "\t\t</select></td>\n";
  		}
  		else
  		{
  			$editquestion .= "\t\t<td align='left'>[{$eqrow['type']}] - ".$clang->gT("Cannot be modified")." - ".$clang->gT("Survey is currently active.")."\n"
  			. "\t\t\t<input type='hidden' name='type' id='question_type' value='{$eqrow['type']}' />\n"
  			. "\t\t</td>\n";
  		}
  
  		$editquestion  .="\t</tr><tr id='LabelSets' style='display: none'>\n"
  		. "\t\t<td align='right'><strong>".$clang->gT("Label Set:")."</strong></td>\n"
  		. "\t\t<td align='left'>\n";
  		
		$qattributes=questionAttributes();
  		if ($activated != "Y")
  		{
  			$editquestion .= "\t\t<select name='lid' >\n";
  			$labelsets=getlabelsets(GetBaseLanguageFromSurveyID($surveyid));
  			if (count($labelsets)>0)
  			{
  				if (!$eqrow['lid'])
  				{
  					$editquestion .= "\t\t\t<option value=''>".$clang->gT("Please Choose...")."</option>\n";
  				}
  				foreach ($labelsets as $lb)
  				{
  					$editquestion .= "\t\t\t<option value='{$lb[0]}'";
  					if ($eqrow['lid'] == $lb[0]) {$editquestion .= " selected='selected'";}
  					$editquestion .= ">{$lb[1]}</option>\n";
  				}
  			}
  			$editquestion .= "\t\t</select>\n";
  		}
  		else
  		{
  			$editquestion .= "[{$eqrow['lid']}] - ".$clang->gT("Cannot be modified")." - ".$clang->gT("Survey is currently active.")."\n"
 			. "\t\t\t<input type='hidden' name='lid' value=\"{$eqrow['lid']}\" />\n";
  		}
  		
  		$editquestion .= "\t\t</td>\n"
  		. "\t</tr>\n"
  		. "\t<tr>\n"
  		. "\t<td align='right'><strong>".$clang->gT("Group:")."</strong></td>\n"
  		. "\t\t<td align='left'><select name='gid'>\n"
  		. getgrouplist3($eqrow['gid'])
  		. "\t\t</select></td>\n"
  		. "\t</tr>\n";
  		$editquestion .= "\t<tr id='OtherSelection'>\n"
  		. "\t\t<td align='right'><strong>".$clang->gT("Other:")."</strong></td>\n";
  		
  		if ($activated != "Y")
  		{
  			$editquestion .= "\t\t<td align='left'>\n"
  			. "\t\t\t<label for='OY'>".$clang->gT("Yes")."</label><input id='OY' type='radio' class='radiobtn' name='other' value='Y'";
  			if ($eqrow['other'] == "Y") {$editquestion .= " checked";}
  			$editquestion .= " />&nbsp;&nbsp;\n"
  			. "\t\t\t<label for='ON'>".$clang->gT("No")."</label><input id='ON' type='radio' class='radiobtn' name='other' value='N'";
  			if ($eqrow['other'] == "N" || $eqrow['other'] == "" ) {$editquestion .= " checked='checked'";}
  			$editquestion .= " />\n"
  			. "\t\t</td>\n";
  		}
  		else
  		{
  			$editquestion .= "<td align='left'> [{$eqrow['other']}] - ".$clang->gT("Cannot be modified")." - ".$clang->gT("Survey is currently active.")."\n"
  			. "\t\t\t<input type='hidden' name='other' value=\"{$eqrow['other']}\" /></td>\n";
  		}
  		$editquestion .= "\t</tr>\n";
  
  		$editquestion .= "\t<tr id='MandatorySelection'>\n"
  		. "\t\t<td align='right'><strong>".$clang->gT("Mandatory:")."</strong></td>\n"
  		. "\t\t<td align='left'>\n"
  		. "\t\t\t<label for='MY'>".$clang->gT("Yes")."</label><input id='MY' type='radio' class='radiobtn' name='mandatory' value='Y'";
  		if ($eqrow['mandatory'] == "Y") {$editquestion .= " checked='checked'";}
  		$editquestion .= " />&nbsp;&nbsp;\n"
  		. "\t\t\t<label for='MN'>".$clang->gT("No")."</label><input id='MN' type='radio' class='radiobtn' name='mandatory' value='N'";
  		if ($eqrow['mandatory'] != "Y") {$editquestion .= " checked='checked'";}
  		$editquestion .= " />\n"
  		. "\t\t</td>\n"
  		. "\t</tr>\n";
  		
  		$editquestion .= "\t<tr id='Validation'>\n"
  		. "\t\t<td align='right'><strong>".$clang->gT("Validation:")."</strong></td>\n"
  		. "\t\t<td align='left'>\n"
  		. "\t\t<input type='text' name='preg' size='50' value=\"".$eqrow['preg']."\" />\n"
  		. "\t\t</td>\n"
  		. "\t</tr>\n";
	
	
	$editquestion .= "\t<tr><td align='center' colspan='2'><input type='submit' value='".$clang->gT("Update Question")."' />\n"
	. "\t<input type='hidden' name='action' value='updatequestion' />\n"
	. "\t<input type='hidden' name='sid' value='$surveyid' />\n"
	. "\t<input type='hidden' name='qid' value='$qid' /></td></tr></table></div></form>\n"
	. "\t\n";
	

	$qidattributes=getQuestionAttributes($qid);
	$editquestion .= "\t\t\t<table id='QTattributes' width='40%' >
					   <tr>
					    <td colspan='2' align='center'>
						  <form action='$scriptname' method='post'><table class='attributetable'>
						  <tr>
						    <th colspan='4'>".$clang->gT("Question Attributes:")."</th>
   					      </tr>
						  <tr><th colspan='4' height='5'></th></tr>
                          <tr>  			  
						  <td nowrap='nowrap' width='50%' ><select id='QTlist' name='attribute_name' >
						  </select></td><td align='center' width='20%'><input type='text' id='QTtext'  name='attribute_value'  /></td>
						  <td align='center'><input type='submit' value='".$clang->gT("Add")."' />
						  <input type='hidden' name='action' value='addattribute' />
						  <input type='hidden' name='sid' value='$surveyid' />
					      <input type='hidden' name='qid' value='$qid' />
					      <input type='hidden' name='gid' value='$gid' /></td></tr>
					      <tr><th colspan='4' height='10'></th></tr>\n";
	$editquestion .= "\t\t\t</table></form>\n";
	
	foreach ($qidattributes as $qa)
	{
		$editquestion .= "\t\t\t<table class='attributetable' width='90%' border='0' cellspacing='0'>"
		."<tr><td width='85%'>"
		."<form action='$scriptname' method='post'>"
		."<table width='100%'><tr><td width='65%'>"
		.$qa['attribute']."</td>
					   <td align='center' width='25%'><input type='text' name='attribute_value' value='"
		.$qa['value']."' /></td>
					   <td ><input type='submit' value='"
		.$clang->gT("Save")."' />
					   <input type='hidden' name='action' value='editattribute' />\n
					   <input type='hidden' name='sid' value='$surveyid' />\n
					   <input type='hidden' name='gid' value='$gid' />\n
					   <input type='hidden' name='qid' value='$qid' />\n
					   <input type='hidden' name='qaid' value='".$qa['qaid']."' />\n"
		."\t\t\t</td></tr></table></form></td><td>
					   <form action='$scriptname' method='post'><table width='100%'><tr><td width='5%'>
					   <input type='submit' value='"
		.$clang->gT("Delete")."' />"
		. "\t<input type='hidden' name='action' value='delattribute' />\n"
		. "\t<input type='hidden' name='sid' value='$surveyid' />\n"
		. "\t<input type='hidden' name='qid' value='$qid' />\n"
		. "\t<input type='hidden' name='gid' value='$gid' />\n"
		. "\t<input type='hidden' name='qaid' value='".$qa['qaid']."' />\n"
		. "</td></tr></table>\n"
		. "</form>\n</table>";
	}
    $editquestion .= "</td></tr></table>";
	$editquestion .= questionjavascript($eqrow['type'], $qattributes);
}

//Constructing the interface here...
if($action == "orderquestions")
{
    if (isset($_POST['questionordermethod']))
    {
       switch($_POST['questionordermethod'])
       {
        // Pressing the Up button
    	case $clang->gT("Up", "unescaped"):
    	$newsortorder=$_POST['sortorder']-1;
    	$oldsortorder=$_POST['sortorder'];
    	$cdquery = "UPDATE ".db_table_name('questions')." SET question_order=-1 WHERE gid=$gid AND question_order=$newsortorder";
    	$cdresult=$connect->Execute($cdquery) or die($connect->ErrorMsg());
    	$cdquery = "UPDATE ".db_table_name('questions')." SET question_order=$newsortorder WHERE gid=$gid AND question_order=$oldsortorder";
    	$cdresult=$connect->Execute($cdquery) or die($connect->ErrorMsg());
    	$cdquery = "UPDATE ".db_table_name('questions')." SET question_order='$oldsortorder' WHERE gid=$gid AND question_order=-1";
    	$cdresult=$connect->Execute($cdquery) or die($connect->ErrorMsg());
    	break;

        // Pressing the Down button
    	case $clang->gT("Dn", "unescaped"):
    	$newsortorder=$_POST['sortorder']+1;
    	$oldsortorder=$_POST['sortorder'];
    	$cdquery = "UPDATE ".db_table_name('questions')." SET question_order=-1 WHERE gid=$gid AND question_order=$newsortorder";
    	$cdresult=$connect->Execute($cdquery) or die($connect->ErrorMsg());
    	$cdquery = "UPDATE ".db_table_name('questions')." SET question_order='$newsortorder' WHERE gid=$gid AND question_order=$oldsortorder";
    	$cdresult=$connect->Execute($cdquery) or die($connect->ErrorMsg());
    	$cdquery = "UPDATE ".db_table_name('questions')." SET question_order=$oldsortorder WHERE gid=$gid AND question_order=-1";
    	$cdresult=$connect->Execute($cdquery) or die($connect->ErrorMsg());
    	break;
     }
  }

    //Get the questions for this group
    $baselang = GetBaseLanguageFromSurveyID($surveyid);
    $oqquery = "SELECT * FROM ".db_table_name('questions')." WHERE sid=$surveyid AND gid=$gid AND language='".$baselang."' order by question_order" ;
    $oqresult = db_execute_assoc($oqquery);
    
    $orderquestions = "<table width='100%' border='0'>\n\t<tr ><td colspan='2' class='settingcaption'>"
    	. "\t\t".$clang->gT("Change Question Order")."</td></tr>"
//        . "<tr> <td >".("Question Name")."</td><td>".("Action")."</td></tr>"
        . "</table>\n";

// Get the condition dependecy array for all questions in this array and group
$questdepsarray = GetQuestDepsForConditions($surveyid,$gid);
if (!is_null($questdepsarray))
{
	$orderquestions .= "<li class='movableNode'><strong><font color='orange'>".$clang->gT("Warning").":</font> ".$clang->gT("Current group is using conditional questions")."</strong><br /><br /><i>".$clang->gT("Re-ordering questions in this group is restricted to ensure that questions on which conditions are based aren't reordered after questions having the conditions set")."</i></strong><br /><br/>".$clang->gT("See the conditions marked on the following questions").":<ul>\n";
	foreach ($questdepsarray as $depqid => $depquestrow)
	{
		foreach ($depquestrow as $targqid => $targcid)
		{
			$listcid=implode("-",$targcid);
			$orderquestions .= "<li><a href='#' onclick=\"window.open('admin.php?sid=".$surveyid."&amp;gid=".$gid."&amp;qid=".$depqid."&amp;action=conditions&amp;markcid=".$listcid."')\"> [QID: ".$depqid."] </a> ";
		}
		$orderquestions .= "</li>\n";
	}
	$orderquestions .= "</ul></li>";
}

    $orderquestions	.= "<form method='post'>";	

    $questioncount = $oqresult->RecordCount();        
$oqarray = $oqresult->GetArray();
for($i=0; $i < $questioncount ; $i++)
{
	$downdisabled = "";
	$updisabled = "";
	if ( !is_null($questdepsarray) && $i < $questioncount-1 &&
	  array_key_exists($oqarray[$i+1]['qid'],$questdepsarray) &&
	  array_key_exists($oqarray[$i]['qid'],$questdepsarray[$oqarray[$i+1]['qid']]) )
	{
		$downdisabled = "disabled=\"true\" class=\"disabledbtn\"";
	}
	if ( !is_null($questdepsarray) && $i !=0  &&
	  array_key_exists($oqarray[$i]['qid'],$questdepsarray) &&
	  array_key_exists($oqarray[$i-1]['qid'],$questdepsarray[$oqarray[$i]['qid']]) )
	{
		$updisabled = "disabled=\"true\" class=\"disabledbtn\"";
	}

	$orderquestions.="<li class='movableNode'>\n" ;
	$orderquestions.= "\t<input style='float:right;";
	if ($i == 0) {$orderquestions.="visibility:hidden;";}
	$orderquestions.="' type='submit' name='questionordermethod' value='".$clang->gT("Up")."' onclick=\"this.form.sortorder.value='{$oqarray[$i]['question_order']}'\" ".$updisabled."/>\n";
	if ($i < $questioncount-1)
	{
		// Fill the sortorder hiddenfield so we now what fi        eld is moved down
		$orderquestions.= "\t<input type='submit' style='float:right;' name='questionordermethod' value='".$clang->gT("Dn")."' onclick=\"this.form.sortorder.value='{$oqarray[$i]['question_order']}'\" ".$downdisabled."/>\n";
	}
	$orderquestions.=$oqarray[$i]['title'].": ".$oqarray[$i]['question']."</li>\n" ;
}

  	$orderquestions.="</ul>\n"
  	. "\t<input type='hidden' name='sortorder' />"
  	. "\t<input type='hidden' name='action' value='orderquestions' />" 
      . "</form>" ;
  	$orderquestions .="<br />" ;

}	

function questionjavascript($type, $qattributes)
{
    $newquestionoutput = "<script type='text/javascript'>\n"
    ."if (navigator.userAgent.indexOf(\"Gecko\") != -1)\n"
    ."window.addEventListener(\"load\", init_gecko_select_hack, false);\n";    
    $jc=0;
    $newquestionoutput .= "\t\t\tvar qtypes = new Array();\n";
    $newquestionoutput .= "\t\t\tvar qnames = new Array();\n\n";
    foreach ($qattributes as $key=>$val)
    {
        foreach ($val as $vl)
        {
            $newquestionoutput .= "\t\t\tqtypes[$jc]='".$key."';\n";
            $newquestionoutput .= "\t\t\tqnames[$jc]='".$vl['name']."';\n";
            $jc++;
        }
    }
    $newquestionoutput .= "\t\t\t function buildQTlist(type)
                {
                document.getElementById('QTattributes').style.display='none';
                for (var i=document.getElementById('QTlist').options.length-1; i>=0; i--)
                    {
                    document.getElementById('QTlist').options[i] = null;
                    }
                for (var i=0;i<qtypes.length;i++)
                    {
                    if (qtypes[i] == type)
                        {
                        document.getElementById('QTattributes').style.display='';
                        document.getElementById('QTlist').options[document.getElementById('QTlist').options.length] = new Option(qnames[i], qnames[i]);
                        }
                    }
                }";
    $newquestionoutput .="\nfunction OtherSelection(QuestionType)\n"
    . "\t{\n"
    . "if (QuestionType == '') {QuestionType=document.getElementById('question_type').value;}\n"
    . "\tif (QuestionType == 'M' || QuestionType == 'P' || QuestionType == 'L' || QuestionType == '!')\n"
    . "\t\t{\n"
    . "\t\tdocument.getElementById('OtherSelection').style.display = '';\n"
    . "\t\tdocument.getElementById('LabelSets').style.display = 'none';\n"
    . "\t\tdocument.getElementById('Validation').style.display = 'none';\n"
    . "\t\tdocument.getElementById('MandatorySelection').style.display='';\n"
    . "\t\t}\n"
    . "\telse if (QuestionType == 'F' || QuestionType == 'H' || QuestionType == 'W' || QuestionType == 'Z')\n"
    . "\t\t{\n"
    . "\t\tdocument.getElementById('LabelSets').style.display = '';\n"
    . "\t\tdocument.getElementById('OtherSelection').style.display = 'none';\n"
    . "\t\tdocument.getElementById('Validation').style.display = 'none';\n"
    . "\t\tdocument.getElementById('MandatorySelection').style.display='';\n"
    . "\t\t}\n"
    . "\telse if (QuestionType == 'S' || QuestionType == 'T' || QuestionType == 'U' || QuestionType == 'N' || QuestionType=='')\n"
    . "\t\t{\n"
    . "\t\tdocument.getElementById('Validation').style.display = '';\n"
    . "\t\tdocument.getElementById('OtherSelection').style.display ='none';\n"
    . "\t\tdocument.getElementById('ON').checked = true;\n"
    . "\t\tdocument.getElementById('LabelSets').style.display='none';\n"
    . "\t\tdocument.getElementById('MandatorySelection').style.display='';\n"
    . "\t\t}\n"
    . "\telse if (QuestionType == 'X')\n"
    . "\t\t{\n"
    . "\t\tdocument.getElementById('Validation').style.display = 'none';\n"
    . "\t\tdocument.getElementById('OtherSelection').style.display ='none';\n"
    . "\t\tdocument.getElementById('LabelSets').style.display='none';\n"
    . "\t\tdocument.getElementById('MandatorySelection').style.display='none';\n"
    . "\t\t}\n"
    . "\telse\n"
    . "\t\t{\n"
    . "\t\tdocument.getElementById('LabelSets').style.display = 'none';\n"
    . "\t\tdocument.getElementById('OtherSelection').style.display = 'none';\n"
    . "\t\tdocument.getElementById('ON').checked = true;\n"
    . "\t\tdocument.getElementById('Validation').style.display = 'none';\n"
    . "\t\tdocument.getElementById('MandatorySelection').style.display='';\n"
    //. "\t\tdocument.addnewquestion.other[1].checked = true;\n"
    . "\t\t}\n"
    . "\tbuildQTlist(QuestionType);\n"
    . "\t}\n"
    . "\tOtherSelection('$type');\n"
    . "</script>\n";

    return $newquestionoutput;
}


?>
