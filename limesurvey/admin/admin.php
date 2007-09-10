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


require_once(dirname(__FILE__).'/../config.php');  // config.php itself includes common.php

@ini_set('session.gc_maxlifetime', $sessionlifetime);

if (!isset($adminlang)) {$adminlang=returnglobal('adminlang');} // Admin language
if (!isset($surveyid)) {$surveyid=returnglobal('sid');}         //SurveyID
if (!isset($ugid)) {$ugid=returnglobal('ugid');}                //Usergroup-ID
if (!isset($gid)) {$gid=returnglobal('gid');}                   //GroupID
if (!isset($qid)) {$qid=returnglobal('qid');}                   //QuestionID
if (!isset($lid)) {$lid=returnglobal('lid');}                   //LabelID
if (!isset($code)) {$code=returnglobal('code');}                // ??
if (!isset($action)) {$action=returnglobal('action');}          //Desired action
if (!isset($subaction)) {$subaction=returnglobal('subaction');} //Desired subaction
if (!isset($ok)) {$ok=returnglobal('ok');}                      // ??
if (!isset($fp)) {$fp=returnglobal('filev');}                   //??
if (!isset($elem)) {$elem=returnglobal('elem');}                //??

if ($action != "showprintablesurvey")
{
  $adminoutput = helpscreenscript();
  $adminoutput .= "<table width='100%' border='0' cellpadding='0' cellspacing='0' >\n"
  ."\t<tr>\n"
  ."\t\t<td valign='top' align='center' bgcolor='#F8F8FF'>\n";
} else {$adminoutput='';}
include_once("login_check.php");


if(isset($_SESSION['loginID']) && $action!='login')
{
  //VARIOUS DATABASE OPTIONS/ACTIONS PERFORMED HERE
  if ($action == "delsurvey"         || $action == "delgroup"       || $action == "delgroupall"       ||
      $action == "delquestion"       || $action == "delquestionall" || $action == "insertnewsurvey"   ||
      $action == "copynewquestion"   || $action == "insertnewgroup" || $action == "insertCSV"         ||
      $action == "insertnewquestion" || $action == "updatesurvey"   || $action == "updatesurvey2"     || 
      $action == "updategroup"       || $action == "deactivate"     ||
      $action == "updatequestion"    || $action == "modanswer"      || $action == "renumberquestions" ||
      $action == "delattribute"      || $action == "addattribute"   || $action == "editattribute")
  {
      include("database.php");
  }

sendcacheheaders();

/* Check user right actions for validity  
   Currently existing user rights:
    `configurator`
    `create_survey`
    `create_user`
    `delete_user`
    `manage_label`
    `manage_template`
    `move_user`
*/
    
if ($action == "importsurvey") 
  { 
      if ($_SESSION['USER_RIGHT_CREATE_SURVEY']==1)	{include("http_importsurvey.php");}
	    else { include("access_denied.php");}
  }      
elseif ($action == "dumpdb") 
  { 
      if ($_SESSION['USER_RIGHT_CONFIGURATOR']==1)  {include("dumpdb.php");}
        else { include("access_denied.php");}
  }      
elseif ($action == "dumplabel") 
  { 
      if ($_SESSION['USER_RIGHT_MANAGE_TEMPLATE']==1)  {include("dumplabel.php");}
        else { include("access_denied.php");}
  }      
elseif ($action == "checkintegrity") 
  { 
      if ($_SESSION['USER_RIGHT_CONFIGURATOR']==1)  {include("integritycheck.php");}
        else { include("access_denied.php");}
  }      
elseif ($action=="labels" || $action=="newlabelset" || $action=="insertlabelset" ||
        $action=="deletelabelset" || $action=="editlabelset" || $action=="modlabelsetanswers" || 
        $action=="updateset" || $action=="importlabels")
  { 
      if ($_SESSION['USER_RIGHT_MANAGE_LABEL']==1)  {include("labels.php");}
        else { include("access_denied.php");}
  }      
elseif ($action=="templates" || $action=="templatecopy" || $action=="templatesavechanges" || 
        $action=="templaterename" || $action=="templateupload" || $action=="templatefiledelete" || 
        $action=="templatezip")
  { 
      if ($_SESSION['USER_RIGHT_MANAGE_TEMPLATE']==1)  {include("templates.php");}
        else { include("access_denied.php");}
  }      

  
  
/* Check survey right actions for validity  
   Currently existing survey rights:
    `edit_survey_property`
    `define_questions`
    `browse_response`
    `export`
    `delete_survey`
    `activate_survey`
*/ 

if ($surveyid)
{
$surquery = "SELECT * FROM {$dbprefix}surveys_rights WHERE sid=$surveyid AND uid = ".$_SESSION['loginID']; //Getting rights for this survey
$surresult = db_execute_assoc($surquery);   
$surrows = $surresult->FetchRow();
}

if ($action == "activate")
    {
    if($surrows['activate_survey'])    {include("activate.php");}
        else { include("access_denied.php");}    
    }
elseif ($action == "conditions")
    {
    if($surrows['define_questions'])    {include("conditions.php");}
        else { include("access_denied.php");}    
    }    
elseif ($action == "dumpsurvey")
    {
    if($surrows['export'])    {include("dumpsurvey.php");}
        else { include("access_denied.php");}    
    }    
elseif ($action == "dumpquestion")
    {
    if($surrows['export'])    {include("dumpquestion.php");}
        else { include("access_denied.php");}    
    }    
elseif ($action == "dumpgroup")
    {
    if($surrows['export'])    {include("dumpgroup.php");}
        else { include("access_denied.php");}    
    }    
elseif ($action == "deactivate")
    {
    if($surrows['activate_survey'])    {include("deactivate.php");}
        else { include("access_denied.php");}    
    }
elseif ($action == "deletesurvey")
    {
    if($surrows['delete_survey'])    {include("deletesurvey.php");}
        else { include("access_denied.php");}    
    }    
elseif ($action == "importgroup")
    {
    if($surrows['define_questions'])    {include("importgroup.php");}
        else { include("access_denied.php");}    
    }
elseif ($action == "importquestion")
    {
    if($surrows['define_questions'])    {include("importquestion.php");}
        else { include("access_denied.php");}    
    }    
elseif ($action == "listcolumn")
    {
    if($surrows['browse_response'])    {include("listcolumn.php");}
        else { include("access_denied.php");}    
    }    
elseif ($action == "previewquestion")
    {
    if($surrows['define_questions'])    {include("preview.php");}
        else { include("access_denied.php");}    
    }
elseif ($action=="addgroup" || $action=="editgroup")        
    {
    if($surrows['define_questions'])    {include("grouphandling.php");}
        else { include("access_denied.php");}    
    }
elseif ($action == "vvexport")
    {
    if($surrows['browse_response'])    {include("vvexport.php");}
        else { include("access_denied.php");}    
    }    
elseif ($action == "vvimport")
    {
    if($surrows['browse_response'])    {include("vvimport.php");}
        else { include("access_denied.php");}    
    }    
elseif ($action == "importoldresponses")
    {
    if($surrows['browse_response'])    {include("importoldresponses.php");}
        else { include("access_denied.php");}    
    }    
elseif ($action == "saved")
    {
    if($surrows['browse_response'])    {include("saved.php");}
        else { include("access_denied.php");}    
    }    
elseif ($action == "exportresults")
    {
    if($surrows['export'])    {include("exportresults.php");}
        else { include("access_denied.php");}    
    }    
elseif ($action == "exportspss")
    {
    if($surrows['export'])    {include("spss.php");}
        else { include("access_denied.php");}    
    }    
elseif ($action == "statistics")
    {
    if($surrows['browse_response'])    {include("statistics.php");}
        else { include("access_denied.php");}    
    }    
elseif ($action == "dataentry")
    {
    if($surrows['browse_response'])    {include("dataentry.php");}
        else { include("access_denied.php");}    
    }    
elseif ($action == "browse")
    {
    if($surrows['browse_response'])    {include("browse.php");}               
        else { include("access_denied.php");}    
    }    
elseif ($action == "tokens")
    {
    if($surrows['activate_survey'])    {include("tokens.php");}               
        else { include("access_denied.php");}    
    }    
elseif ($action=="showprintablesurvey")  
    { 
        include("printablesurvey.php"); //No special right needed to show the printable survey
    } 
elseif ($action=="assessments" || $action=="assessmentdelete" || $action=="assessmentedit" || $action=="assessmentadd" || $action=="assessmentupdate")
    {
    if($surrows['define_questions'])    {include("assessments.php");}
        else { include("access_denied.php");}    
    }    


    
 if (!isset($assessmentsoutput) && !isset($statisticsoutput) && !isset($browseoutput) && !isset($savedsurveyoutput) &&             
     !isset($dataentryoutput) && !isset($conditionsoutput) && !isset($importoldresponsesoutput) && 
     !isset($vvoutput) && !isset($tokenoutput) && !isset($exportoutput) && !isset($templatesoutput) &&    
     (isset($surveyid) || $action=="listurveys" || $action=="changelang" ||  $action=="checksettings" ||       //Still to check
      $action=="editsurvey" || $action=="updatesurvey" || $action=="ordergroups"  ||
      $action=="uploadf" || $action=="newsurvey" || $action=="listsurveys" ||   
      $action=="surveyrights") ) include("html.php");

 if ($action=="addquestion" || $action=="copyquestion" || $action=="editquestion" || 
     $action=="orderquestions" || $action=="editattribute" || $action=="delattribute" || 
     $action=="addattribute" )
    {if($surrows['define_questions'])    {include("questionhandling.php");}
        else { include("access_denied.php");}    
    }    

      
 if ($action=="adduser" || $action=="deluser" || $action=="moduser" ||                                        //Still to check 
     $action=="userrights" || $action=="modifyuser" || $action=="editusers" || 
     $action=="addusergroup" || $action=="editusergroup" || $action=="mailusergroup" ||
     $action=="delusergroup" || $action=="usergroupindb" || $action=="mailsendusergroup" || 
     $action=="editusergroupindb" || $action=="editusergroups" || $action=="deleteuserfromgroup" ||
     $action=="addusertogroup" || $action=="setuserrights") include ("userrighthandling.php");

  
  // For some output we dont want to have the standard admin menu bar
  if (!isset($labelsoutput)  && !isset($templatesoutput) && !isset($printablesurveyoutput) && 
      !isset($assessmentsoutput) && !isset($tokenoutput) && !isset($browseoutput) &&
      !isset($dataentryoutput) && !isset($statisticsoutput)&& !isset($savedsurveyoutput) &&
      !isset($exportoutput) && !isset($importoldresponsesoutput) && !isset($conditionsoutput) &&
      !isset($vvoutput) && !isset($listcolumnoutput)) 
      {
        $adminoutput.= showadminmenu();
      }
    
                                                                        
  if (isset($databaseoutput))  {$adminoutput.= $databaseoutput;} 	
  if (isset($templatesoutput)) {$adminoutput.= $templatesoutput;}
  if (isset($accesssummary  )) {$adminoutput.= $accesssummary;}	
  if (isset($surveysummary  )) {$adminoutput.= $surveysummary;}
  if (isset($usergroupsummary)){$adminoutput.= $usergroupsummary;}
  if (isset($usersummary    )) {$adminoutput.= $usersummary;}
  if (isset($groupsummary   )) {$adminoutput.= $groupsummary;}
  if (isset($questionsummary)) {$adminoutput.= $questionsummary;}
  if (isset($vasummary      )) {$adminoutput.= $vasummary;}
  if (isset($addsummary     )) {$adminoutput.= $addsummary;}
  if (isset($answersummary  )) {$adminoutput.= $answersummary;}
  if (isset($cssummary      )) {$adminoutput.= $cssummary;}
  if (isset($listcolumnoutput)) {$adminoutput.= $listcolumnoutput;}

  
  if (isset($editgroup)) {$adminoutput.= $editgroup;}
  if (isset($editquestion)) {$adminoutput.= $editquestion;}
  if (isset($editsurvey)) {$adminoutput.= $editsurvey;}
  if (isset($labelsoutput)) {$adminoutput.= $labelsoutput;}
  if (isset($listsurveys)) {$adminoutput.= $listsurveys; }
  if (isset($integritycheck)) {$adminoutput.= $integritycheck;}
  if (isset($ordergroups)){$adminoutput.= $ordergroups;}
  if (isset($orderquestions)) {$adminoutput.= $orderquestions;}
  if (isset($surveysecurity)) {$adminoutput.= $surveysecurity;}
  if (isset($newsurvey)) {$adminoutput.= $newsurvey;}
  if (isset($newgroupoutput)) {$adminoutput.= $newgroupoutput;}
  if (isset($newquestionoutput)) {$adminoutput.= $newquestionoutput;}
  if (isset($newanswer)) {$adminoutput.= $newanswer;}
  if (isset($editanswer)) {$adminoutput.= $editanswer;}
  if (isset($assessmentsoutput)) {$adminoutput.= $assessmentsoutput;}

  if (isset($importsurvey)) {$adminoutput.= $importsurvey;}
  if (isset($importgroup)) {$adminoutput.= $importgroup;}
  if (isset($importquestion)) {$adminoutput.= $importquestion;}
  if (isset($printablesurveyoutput)) {$adminoutput.= $printablesurveyoutput;}
  if (isset($activateoutput)) {$adminoutput.= $activateoutput;} 	
  if (isset($deactivateoutput)) {$adminoutput.= $deactivateoutput;} 	
  if (isset($tokenoutput)) {$adminoutput.= $tokenoutput;} 	
  if (isset($browseoutput)) {$adminoutput.= $browseoutput;} 	
  if (isset($dataentryoutput)) {$adminoutput.= $dataentryoutput;} 	
  if (isset($statisticsoutput)) {$adminoutput.= $statisticsoutput;} 	
  if (isset($exportoutput)) {$adminoutput.= $exportoutput;} 	
  if (isset($savedsurveyoutput)) {$adminoutput.= $savedsurveyoutput;} 	
  if (isset($importoldresponsesoutput)) {$adminoutput.= $importoldresponsesoutput;} 	
  if (isset($conditionsoutput)) {$adminoutput.= $conditionsoutput;} 	
  if (isset($deletesurveyoutput)) {$adminoutput.= $deletesurveyoutput;} 	
  if (isset($vvoutput)) {$adminoutput.= $vvoutput;} 	
  if (isset($dumpdboutput)) {$adminoutput.= $dumpdboutput;}     
                                                                        
  
  if (!isset($printablesurveyoutput) && ($subaction!='export'))
  {  
  if (!isset($_SESSION['metaHeader'])) {$_SESSION['metaHeader']='';}
  
  $adminoutput = getAdminHeader($_SESSION['metaHeader']).$adminoutput;  // All future output is written into this and then outputted at the end of file
  unset($_SESSION['metaHeader']);    
  $adminoutput.= "\t\t</td>\n".helpscreen()
              . "\t</tr>\n"
              . "</table>\n"
              . getAdminFooter("http://docs.limesurvey.org", $clang->gT("LimeSurvey Online Manual"));
  }
  
}
  else
  { //not logged in
    if (!isset($_SESSION['metaHeader'])) {$_SESSION['metaHeader']='';}
    $adminoutput = getAdminHeader($_SESSION['metaHeader']).$adminoutput;  // All future output is written into this and then outputted at the end of file
    unset($_SESSION['metaHeader']);    
    $adminoutput.= "\t\t</td>\n".helpscreen()
                . "\t</tr>\n"
                . "</table>\n"
                . getAdminFooter("http://docs.limesurvey.org", $clang->gT("LimeSurvey Online Manual"));
  
  }

if (($action=='showphpinfo') && ($_SESSION['USER_RIGHT_CONFIGURATOR'] == 1)) {phpinfo();}
else {echo $adminoutput;}


  function helpscreenscript()
  // returns the script part for online help to be included outside a table
  {
  	$helpoutput= "<script type='text/javascript'>\n"
    ."\tfunction showhelp(action)\n"
    ."\t\t{\n"
    ."\t\tvar name='help';\n"
    ."\t\tif (action == \"hide\")\n"
    ."\t\t\t{\n"
    ."\t\t\tdocument.getElementById(name).style.display='none';\n"
    ."\t\t\t}\n"
    ."\t\telse if (action == \"show\")\n"
    ."\t\t\t{\n"
    ."\t\t\tdocument.getElementById(name).style.display='';\n"
    ."\t\t\t}\n"
    ."\t\t}\n"
    ."</script>\n"; 
    return $helpoutput;
  }


  function helpscreen()
  // This functions loads the nescessary helpscreens for each action and hides the help window
  // 
  {
  	global $homeurl, $langdir,  $imagefiles;
  	global $surveyid, $gid, $qid, $action, $clang;

    $helpoutput="\t\t<td id='help' width='200' valign='top' style='display: none' bgcolor='#CCCCCC'>\n"
  	."\t\t\t<table width='100%'><tr><td>"
  	."<table width='100%' align='center' cellspacing='0'>\n"
  	."\t\t\t\t<tr>\n"
  	."\t\t\t\t\t<td bgcolor='#555555' height='8'>\n"
  	."\t\t\t\t\t\t<font color='white' size='1'><strong>"
  	.$clang->gT("Help")."</strong>\n"
  	."\t\t\t\t\t</font></td>\n"
  	."\t\t\t\t</tr>\n"
  	."\t\t\t\t<tr>\n"
  	."\t\t\t\t\t<td align='center' bgcolor='#AAAAAA' style='border-style: solid; border-width: 1; border-color: #555555'>\n"
  	."\t\t\t\t\t\t<img src='$imagefiles/blank.gif' alt='' width='20' hspace='0' border='0' align='left' />\n"
  	."\t\t\t\t\t\t<input type='image' src='$imagefiles/close.gif' name='CloseHelp' align='right' onclick=\"showhelp('hide')\" />\n"
  	."\t\t\t\t\t</td>\n"
  	."\t\t\t\t</tr>\n"
  	."\t\t\t\t<tr>\n"
  	."\t\t\t\t\t<td bgcolor='silver' height='100%' style='border-style: solid; border-width: 1; border-color: #333333'>\n";
  	//determine which help document to show
  	if (!$surveyid && $action != "editusers")
  	{
  		$helpdoc = "$langdir/admin.html";
  	}
  	elseif (!$surveyid && $action=="editusers")
  	{
  		$helpdoc = "$langdir/users.html";
  	}
  	elseif ($surveyid && !$gid)
  	{
  		$helpdoc = "$langdir/survey.html";
  	}
  	elseif ($surveyid && $gid && !$qid)
  	{
  		$helpdoc = "$langdir/group.html";
  	}
  	//elseif ($surveyid && $gid && $qid && !$_GET['viewanswer'] && !$_POST['viewanswer'])
  	elseif ($surveyid && $gid && $qid && !returnglobal('viewanswer'))
  	{
  		$helpdoc = "$langdir/question.html";
  	}
  	elseif ($surveyid && $gid && $qid && (returnglobal('viewanswer')))
  	{
  		$helpdoc = "$langdir/answer.html";
  	}
  	$helpoutput.= "\t\t\t\t\t\t<iframe width='200' height='400' src='$helpdoc' marginwidth='2' marginheight='2'>\n"
  	."\t\t\t\t\t\t</iframe>\n"
  	."\t\t\t\t\t</td>"
  	."\t\t\t\t</tr>\n"
  	."\t\t\t</table></td></tr></table>\n"
  	."\t\t</td>\n";
  	return $helpoutput;
  }
  

  

?>
