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

if ($action == "addgroup")
{
    $grplangs = GetAdditionalLanguagesFromSurveyID($surveyid);
    $baselang = GetBaseLanguageFromSurveyID($surveyid);
    $grplangs[] = $baselang;
    $grplangs = array_reverse($grplangs);

    $newgroupoutput = "<form action='$scriptname' name='addnewgroupfrom' method='post'>"
               ."<table width='100%' border='0' class='tab-page'><tr>\n"
               ."\t<td colspan='2' class='settingcaption'>\n\t\t<strong>".$clang->gT("Add Group")."</strong></td>"
               ."</tr></table>\n";


    $newgroupoutput .="<table width='100%' border='0'  class='tab-page'>\n\t<tr><td>\n"
    . '<div class="tab-pane" id="tab-pane-1">';
    foreach ($grplangs as $grouplang)
    {
        $newgroupoutput .= '<div class="tab-page"> <h2 class="tab">'.GetLanguageNameFromCode($grouplang,false);
        if ($grouplang==$baselang) {$newgroupoutput .= '('.$clang->gT("Base Language").')';}
        $newgroupoutput .= "</h2>"
        . "<table width='100%' border='0' class='form2columns'>"
        . "\t\t<tr><td align='right'><strong>".$clang->gT("Title").":</strong></td>\n"
        . "\t\t<td><input type='text' size='80' maxlength='100' name='group_name_$grouplang' /><font color='red' face='verdana' size='1'> ".$clang->gT("Required")."</font></td></tr>\n"
        . "\t<tr><td align='right'><strong>".$clang->gT("Description:")."</strong></td>\n"
        . "\t\t<td><textarea cols='80' rows='8' name='description_$grouplang'></textarea></td></tr>\n"
        . "</table></div>";
    }

    $newgroupoutput.= "</div>" 
    . "\t<input type='hidden' name='action' value='insertnewgroup' />\n"
    . "\t<input type='hidden' name='sid' value='$surveyid' /></td></tr>"
    . "\t<tr><td colspan='2' align='center'><input type='submit' value='".$clang->gT("Add Group")."' />\n"
    . "\t</td></tr></table>\n"
    . "</form></td></tr>\n"
    . "<tr><td align='center' class='tab-page'><strong>".$clang->gT("OR")."</strong></td></tr>\n"
    . "<tr><td><form enctype='multipart/form-data' name='importgroup' action='$scriptname' method='post' onsubmit='return validatefilename(this,\"".$clang->gT('Please select a file to import!','js')."\");'>\n"
    . "<table width='100%' border='0' class='form2columns'>\n\t<tr><th colspan='3'>\n"
    . "\t\t<strong>".$clang->gT("Import Group")."</strong></th></tr>\n\t<tr>"
    . "\t\n"
    . "\t\t<td><strong>".$clang->gT("Select CSV File:")."</strong></td>\n"
    . "\t\t<td><input name=\"the_file\" type=\"file\" size=\"35\" /></td></tr>\n"
    . "\t<tr><td colspan='2'class='centered'><input type='submit' value='".$clang->gT("Import Group")."' />\n"
    . "\t<input type='hidden' name='action' value='importgroup' />\n"
    . "\t<input type='hidden' name='sid' value='$surveyid' />\n"
    . "\t</td></tr>\n</table></form>\n";
}


if ($action == "editgroup")
{
    $grplangs = GetAdditionalLanguagesFromSurveyID($surveyid);
    $baselang = GetBaseLanguageFromSurveyID($surveyid);

    $grplangs[] = $baselang;
    $grplangs = array_flip($grplangs);

    $egquery = "SELECT * FROM ".db_table_name('groups')." WHERE sid=$surveyid AND gid=$gid";
    $egresult = db_execute_assoc($egquery);
    while ($esrow = $egresult->FetchRow())
    {
        if(!array_key_exists($esrow['language'], $grplangs)) // Language Exists, BUT ITS NOT ON THE SURVEY ANYMORE.
        {
            $egquery = "DELETE FROM ".db_table_name('groups')." WHERE sid='{$surveyid}' AND gid='{$gid}' AND language='".$esrow['language']."'";
            $egresultD = $connect->Execute($egquery);
        } else {
            $grplangs[$esrow['language']] = 99;
        }
        if ($esrow['language'] == $baselang) $basesettings = array('group_name' => $esrow['group_name'],'description' => $esrow['description'],'group_order' => $esrow['group_order']);

    }

    while (list($key,$value) = each($grplangs))
    {
        if ($value != 99)
        {
            //die("INSERT:".$key);
            $egquery = "INSERT INTO ".db_table_name('groups')." (gid, sid, group_name, description,group_order,language) VALUES ('{$gid}', '{$surveyid}', '{$basesettings['group_name']}', '{$basesettings['description']}','{$basesettings['group_order']}', '{$key}')";
            $egresult = $connect->Execute($egquery);
        }
    }
    
    $egquery = "SELECT * FROM ".db_table_name('groups')." WHERE sid=$surveyid AND gid=$gid AND language='$baselang'";
    $egresult = db_execute_assoc($egquery);
    $editgroup ="<table width='100%' border='0'>\n\t<tr><td class='settingcaption'>"
    . "\t\t".$clang->gT("Edit Group")."</td></tr></table>\n"
    . "<form name='editgroup' action='$scriptname' method='post'>\n"
    . '<div class="tab-pane" id="tab-pane-1">';

    $esrow = $egresult->FetchRow();
    $editgroup .= '<div class="tab-page"> <h2 class="tab">'.getLanguageNameFromCode($esrow['language'],false);
    $editgroup .= '('.$clang->gT("Base Language").')';
    $esrow = array_map('htmlspecialchars', $esrow);
    $editgroup .= '</h2>';
    $editgroup .= "\t<div class='settingrow'><span class='settingcaption'>".$clang->gT("Title").":</span>\n"
    . "\t\t<span class='settingentry'><input type='text' maxlength='100' size='80' name='group_name_{$esrow['language']}' value=\"{$esrow['group_name']}\" />\n"
    . "\t</span></div>\n"
    . "\t<div class='settingrow'><span class='settingcaption'>".$clang->gT("Description:")."</span>\n"
    . "\t\t<span class='settingentry'><textarea cols='70' rows='8' name='description_{$esrow['language']}'>{$esrow['description']}</textarea>\n"
    . "\t</span></div><div class='settingrow'></div></div>"; // THis empty div class is needed for forcing the tabpage border under the button


    $egquery = "SELECT * FROM ".db_table_name('groups')." WHERE sid=$surveyid AND gid=$gid AND language!='$baselang'";
    $egresult = db_execute_assoc($egquery);
    while ($esrow = $egresult->FetchRow())
    {
        $editgroup .= '<div class="tab-page"> <h2 class="tab">'.getLanguageNameFromCode($esrow['language'],false);
        $esrow = array_map('htmlspecialchars', $esrow);
        $editgroup .= '</h2>';
        $editgroup .= "\t<div class='settingrow'><span class='settingcaption'>".$clang->gT("Title").":</span>\n"
        . "\t\t<span class='settingentry'><input type='text' maxlength='100' size='80' name='group_name_{$esrow['language']}' value=\"{$esrow['group_name']}\" />\n"
        . "\t</span></div>\n"
        . "\t<div class='settingrow'><span class='settingcaption'>".$clang->gT("Description:")."</span>\n"
        . "\t\t<span class='settingentry'><textarea cols='70' rows='8' name='description_{$esrow['language']}'>{$esrow['description']}</textarea>\n"
        . "\t</span></div><div class='settingrow'></div></div>"; // THis empty div class is needed for forcing the tabpage border under the button
    }
    $editgroup .= '</div>';
    $editgroup .= "\t<p><input type='submit' class='standardbtn' value='".$clang->gT("Update Group")."' />\n"
    . "\t<input type='hidden' name='action' value='updategroup' />\n"
    . "\t<input type='hidden' name='sid' value=\"{$surveyid}\" />\n"
    . "\t<input type='hidden' name='gid' value='{$gid}' />\n"
    . "\t<input type='hidden' name='language' value=\"{$esrow['language']}\" />\n"
    . "\t</p>\n"
    . "</form>\n";
}

  
?>
