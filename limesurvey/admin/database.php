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

if (!isset($action)) {$action=returnglobal('action');}

if (get_magic_quotes_gpc())
$_POST  = array_map('stripslashes', $_POST);

/*
 * Return a sql statement for renaming a table
 */
function db_rename_table($oldtable, $newtable)
{
	global $connect;
	switch ($connect->databaseType) {
		case 'mysql'	  : return "RENAME TABLE $oldtable TO $newtable";
		case 'odbc_mssql' : return "EXEC sp_rename $oldtable, $newtable";
		default: die ("Couldn't create 'rename table' query for connection type '$connect->databaseType'"); 
	}		
}


/*
* Gets the maximum question_order field value for a group
* @gid: The id of the group
*/
function get_max_question_order($gid)
{
	global $connect ;
	global $dbprefix ;
	$query="SELECT MAX(question_order) as maxorder FROM {$dbprefix}questions where gid=".$gid ;
	$result = db_execute_assoc($query);
	$gv = $result->FetchRow();
	return $gv['maxorder'];
}

$databaseoutput ='';

if(isset($surveyid))
{
	$actsurquery = "SELECT define_questions, edit_survey_property, delete_survey FROM {$dbprefix}surveys_rights WHERE sid=$surveyid AND uid = ".$_SESSION['loginID']; //Getting rights for this survey
	$actsurresult = db_execute_assoc($actsurquery);
	$actsurrows = $actsurresult->FetchRow();

	if ($action == "delattribute" && $actsurrows['define_questions'])
	{
		settype($_POST['qaid'], "integer");
		$query = "DELETE FROM ".db_table_name('question_attributes')."
				  WHERE qaid={$_POST['qaid']} AND qid={$_POST['qid']}";
		$result=$connect->Execute($query) or die("Couldn't delete attribute<br />".htmlspecialchars($query)."<br />".htmlspecialchars($connect->ErrorMsg()));
	}
	elseif ($action == "addattribute" && $actsurrows['define_questions'])
	{
		if (isset($_POST['attribute_value']) && $_POST['attribute_value'])
		{
			$_POST  = array_map('db_quote', $_POST);
			$query = "INSERT INTO ".db_table_name('question_attributes')."
					  (qid, attribute, value)
					  VALUES ('{$_POST['qid']}', '{$_POST['attribute_name']}', '{$_POST['attribute_value']}')";
			$result = $connect->Execute($query) or die("Error<br />".htmlspecialchars($query)."<br />".htmlspecialchars($connect->ErrorMsg()));
		}
	}
	elseif ($action == "editattribute" && $actsurrows['define_questions'])
	{
		if (isset($_POST['attribute_value']) && $_POST['attribute_value'])
		{
			settype($_POST['qaid'], "integer");
			$query = "UPDATE ".db_table_name('question_attributes')."
					  SET value='{$_POST['attribute_value']}' WHERE qaid='{$_POST['qaid']}' AND qid='{$_POST['qid']}'";
			$result = $connect->Execute($query) or die("Error<br />".htmlspecialchars($query)."<br />".htmlspecialchars($connect->ErrorMsg()));
		}
	}
	elseif ($action == "insertnewgroup" && $actsurrows['define_questions'])
	{
  		$grplangs = GetAdditionalLanguagesFromSurveyID($_POST['sid']);
  		$baselang = GetBaseLanguageFromSurveyID($_POST['sid']);
  		$grplangs[] = $baselang;
        $errorstring = '';  
        foreach ($grplangs as $grouplang)
        {
          if (!$_POST['group_name_'.$grouplang]) { $errorstring.= GetLanguageNameFromCode($grouplang,false)."\\n";}
		}
        if ($errorstring!='') 
        {
            $databaseoutput .= "<script type=\"text/javascript\">\n<!--\n alert(\"".$clang->gT("Group could not be added.\\n\\nIt is missing the group name for the following languages","js").":\\n".$errorstring."\")\n //-->\n</script>\n";
        }  

		else
		{
			$_POST  = array_map('db_quote', $_POST);
            $first=true;
    		foreach ($grplangs as $grouplang)
	       	{
		      	if ($first)
                  {
                    $query = "INSERT INTO ".db_table_name('groups')." (sid, group_name, description,group_order,language) VALUES ('{$_POST['sid']}', '{$_POST['group_name_'.$grouplang]}', '{$_POST['description_'.$grouplang]}',".getMaxgrouporder($_POST['sid']).",'{$grouplang}')";
                    $result = $connect->Execute($query);
                    $groupid=$connect->Insert_Id();
                    $first=false;
                  }
                  else{
                        $query = "INSERT INTO ".db_table_name('groups')." (gid, sid, group_name, description,group_order,language) VALUES ('{$groupid}','{$_POST['sid']}', '{$_POST['group_name_'.$grouplang]}', '{$_POST['description_'.$grouplang]}',".getMaxgrouporder($_POST['sid']).",'{$grouplang}')";
                        if ($connect->databaseType == 'odbc_mssql') $query = "SET IDENTITY_INSERT ".db_table_name('groups')." ON; " . $query . "SET IDENTITY_INSERT ".db_table_name('groups')." OFF;";
                        $result = $connect->Execute($query) or die("Error<br />".htmlspecialchars($query)."<br />".htmlspecialchars($connect->ErrorMsg()));
                     }
				if (!$result)
				{
					$databaseoutput .= $clang->gT("Error: The database reported the following error:")."<br />\n";
					$databaseoutput .= "<font color='red'>" . htmlspecialchars($connect->ErrorMsg()) . "</font>\n";
					$databaseoutput .= "<pre>".htmlspecialchars($query)."</pre>\n";
					$databaseoutput .= "</body>\n</html>";
					exit;
				}
			}
		    // This line sets the newly inserted group as the new group
            if (isset($groupid)){$gid=$groupid;}     

		}
	}

	elseif ($action == "updategroup" && $actsurrows['define_questions'])
	{
		$_POST  = array_map('db_quote', $_POST);
		$grplangs = GetAdditionalLanguagesFromSurveyID($_POST['sid']);
		$baselang = GetBaseLanguageFromSurveyID($_POST['sid']);
		array_push($grplangs,$baselang);
		foreach ($grplangs as $grplang)
		{
			if (isset($grplang) && $grplang != "")
			{
				$ugquery = "UPDATE ".db_table_name('groups')." SET group_name='{$_POST['group_name_'.$grplang]}', description='{$_POST['description_'.$grplang]}' WHERE sid={$_POST['sid']} AND gid={$_POST['gid']} AND language='{$grplang}'";
				$ugresult = $connect->Execute($ugquery);
				if ($ugresult)
				{
					$groupsummary = getgrouplist($_POST['gid']);
				}
				else
				{
					$databaseoutput .= "<script type=\"text/javascript\">\n<!--\n alert(\"".$clang->gT("Group could not be updated","js")."\")\n //-->\n</script>\n";
				}
			}
		}

	}

	elseif ($action == "delgroupnone" && $actsurrows['define_questions'])
	{
		if (!isset($gid)) $gid=returnglobal('gid');
		$query = "DELETE FROM ".db_table_name('groups')." WHERE sid=$surveyid AND gid=$gid";
		$result = $connect->Execute($query) or die($connect->ErrorMsg()) ;
		
		if ($result)
		{
			$gid = "";
			$groupselect = getgrouplist($gid);
			fixsortorderGroups();
		}
		else
		{
			$databaseoutput .= "<script type=\"text/javascript\">\n<!--\n alert(\"".$clang->gT("Group could not be deleted","js")."\n$error\")\n //-->\n</script>\n";
		}
	}

	elseif ($action == "delgroup" && $actsurrows['define_questions'])
	{
		if (!isset($gid)) $gid=returnglobal('gid');
		$query = "SELECT qid FROM ".db_table_name('groups').", ".db_table_name('questions')." WHERE ".db_table_name('groups').".gid=".db_table_name('questions').".gid AND ".db_table_name('groups').".gid=$gid";
		if ($result = db_execute_assoc($query))
		{
			if (!isset($total)) $total=0;
			$qtodel=$result->RecordCount();
			while ($row=$result->FetchRow())
			{
				$dquery = "DELETE FROM ".db_table_name('conditions')." WHERE qid={$row['qid']}";
				if ($dresult=$connect->Execute($dquery)) {$total++;}
				$dquery = "DELETE FROM ".db_table_name('answers')." WHERE qid={$row['qid']}";
				if ($dresult=$connect->Execute($dquery)) {$total++;}
				$dquery = "DELETE FROM ".db_table_name('questions')." WHERE qid={$row['qid']}";
				if ($dresult=$connect->Execute($dquery)) {$total++;}
			}
			if ($total != $qtodel*3)
			{
				$databaseoutput .= "<script type=\"text/javascript\">\n<!--\n alert(\"".$clang->gT("Group could not be deleted","js")."\")\n //-->\n</script>\n";
			}
		}
		$query = "DELETE FROM ".db_table_name('groups')." WHERE sid=$surveyid AND gid=$gid";
		$result = $connect->Execute($query) or die($connect->ErrorMsg()) ;
		if ($result)
		{
			$gid = "";
			$groupselect = getgrouplist($gid);
			fixsortorderGroups();
		}
		else
		{
			$databaseoutput .= "<script type=\"text/javascript\">\n<!--\n alert(\"".$clang->gT("Group could not be deleted","js")."\n$error\")\n //-->\n</script>\n";
		}
	}

	elseif ($action == "insertnewquestion" && $actsurrows['define_questions'])
	{
		if (!$_POST['title'])
		{
			$databaseoutput .= "<script type=\"text/javascript\">\n<!--\n alert(\"".$clang->gT("Answer could not be added. You must insert a code in the mandatory field","js")."\")\n //-->\n</script>\n";
		}
		else
		{
			$_POST  = array_map('db_quote', $_POST);
			if (!isset($_POST['lid']) || $_POST['lid'] == '') {$_POST['lid']="0";}
			$baselang = GetBaseLanguageFromSurveyID($_POST['sid']);	
			$query = "INSERT INTO ".db_table_name('questions')." (sid, gid, type, title, question, preg, help, other, mandatory, lid, question_order, language)"
			." VALUES ('{$_POST['sid']}', '{$_POST['gid']}', '{$_POST['type']}', '{$_POST['title']}',"
			." '{$_POST['question']}', '{$_POST['preg']}', '{$_POST['help']}', '{$_POST['other']}', '{$_POST['mandatory']}', '{$_POST['lid']}',".(getMaxquestionorder($_POST['gid'])+1).",'{$baselang}')";
			$result = $connect->Execute($query);
			// Get the last inserted questionid for other languages
			$qid=$connect->Insert_ID();

			// Add other languages
			if ($result)
			{
				$addlangs = GetAdditionalLanguagesFromSurveyID($_POST['sid']);
				foreach ($addlangs as $alang)
				{
					if ($alang != "")
					{	
						$query = "INSERT INTO ".db_table_name('questions')." (qid, sid, gid, type, title, question, preg, help, other, mandatory, lid, question_order, language)"
						." VALUES ('$qid','{$_POST['sid']}', '{$_POST['gid']}', '{$_POST['type']}', '{$_POST['title']}',"
						." '{$_POST['question']}', '{$_POST['preg']}', '{$_POST['help']}', '{$_POST['other']}', '{$_POST['mandatory']}', '{$_POST['lid']}',".getMaxquestionorder($_POST['gid']).",'{$alang}')";
                        if ($connect->databaseType == 'odbc_mssql') $query = "SET IDENTITY_INSERT ".db_table_name('questions')." ON; " . $query . "SET IDENTITY_INSERT ".db_table_name('questions')." OFF;";
						$result2 = $connect->Execute($query);
						if (!$result2)
						{
							$databaseoutput .= "<script type=\"text/javascript\">\n<!--\n alert(\"".$clang->gT("Question in lang={$alang} could not be created.","js")."\\n".$connect->ErrorMsg()."\")\n //-->\n</script>\n";

						}
					}
				}
			}
			
			
			if (!$result)
			{
				$databaseoutput .= "<script type=\"text/javascript\">\n<!--\n alert(\"".$clang->gT("Question could not be created.","js")."\\n".$connect->ErrorMsg()."\")\n //-->\n</script>\n";

			}
			if (isset($_POST['attribute_value']) && $_POST['attribute_value'])
			{
				$query = "INSERT INTO ".db_table_name('question_attributes')."
					  (qid, attribute, value)
					  VALUES
					  ($qid, '".$_POST['attribute_name']."', '".$_POST['attribute_value']."')";
				$result = $connect->Execute($query);
			}
		}
	}
	elseif ($action == "renumberquestions" && $actsurrows['define_questions'])
	{
		//Automatically renumbers the "question codes" so that they follow
		//a methodical numbering method
		$question_number=1;
		$group_number=0;
		$gselect="SELECT a.qid, a.gid\n"
		."FROM ".db_table_name('questions')." as a, ".db_table_name('groups')."\n"
		."WHERE a.gid=".db_table_name('groups').".gid\n"
		."AND a.sid=$surveyid\n"
		."group BY a.gid, a.qid\n"
		."ORDER BY ".db_table_name('groups').".group_order, question_order";
		$gresult=db_execute_assoc($gselect) or die ("Error: ".htmlspecialchars($connect->ErrorMsg()));
		$grows = array(); //Create an empty array in case FetchRow does not return any rows
		while ($grow = $gresult->FetchRow()) {$grows[] = $grow;} // Get table output into array
//		usort($grows, 'CompareGroupThenTitle');
		foreach($grows as $grow)
		{
			//Go through all the questions
			if ((isset($_GET['style']) && $_GET['style']=="bygroup") && (!isset($group_number) || $group_number != $grow['gid']))
			{
				$question_number=1;
				$group_number++;
			}
			$usql="UPDATE ".db_table_name('questions')."\n"
			."SET title='".str_pad($question_number, 4, "0", STR_PAD_LEFT)."'\n"
			."WHERE qid=".$grow['qid'];
			//$databaseoutput .= "[$sql]";
			$uresult=$connect->Execute($usql) or die("Error: ".htmlspecialchars($connect->ErrorMsg()));
			$question_number++;
			$group_number=$grow['gid'];
		}
	}

	elseif ($action == "updatequestion" && $actsurrows['define_questions'])
	{
		$cqquery = "SELECT type, gid FROM ".db_table_name('questions')." WHERE qid={$_POST['qid']}";
		$cqresult=db_execute_assoc($cqquery) or die ("Couldn't get question type to check for change<br />".htmlspecialchars($cqquery)."<br />".htmlspecialchars($connect->ErrorMsg()));
		$cqr=$cqresult->FetchRow();
        	$oldtype=$cqr['type'];
		$oldgid=$cqr['gid'];

    		$keepanswers = "1"; // Generally we try to keep answers if the question type has changed
		
		// These are the questions types that have no answers and therefore we delete the answer in that case
		if (($_POST['type']== "5") || ($_POST['type']== "D") || ($_POST['type']== "G") ||
            ($_POST['type']== "I") || ($_POST['type']== "N") || ($_POST['type']== "S") ||        
            ($_POST['type']== "T") || ($_POST['type']== "U") || ($_POST['type']== "X") ||        
            ($_POST['type']=="Y"))
		{
			$keepanswers = "0";
		}

		// These are the questions types that have the other option therefore we set everything else to 'No Other'
		if (($_POST['type']!= "L") && ($_POST['type']!= "!") && ($_POST['type']!= "P") && ($_POST['type']!="M"))
		{
			$_POST['other']='N';
		}

		if ($oldtype != $_POST['type'])
		{
			//Make sure there are no conditions based on this question, since we are changing the type
			$ccquery = "SELECT * FROM ".db_table_name('conditions')." WHERE cqid={$_POST['qid']}";
			$ccresult = db_execute_assoc($ccquery) or die ("Couldn't get list of cqids for this question<br />".htmlspecialchars($ccquery)."<br />".htmlspecialchars($connect->ErrorMsg()));
			$cccount=$ccresult->RecordCount();
			while ($ccr=$ccresult->FetchRow()) {$qidarray[]=$ccr['qid'];}
			if (isset($qidarray) && $qidarray) {$qidlist=implode(", ", $qidarray);}
		}
		$_POST  = array_map('db_quote', $_POST);
		if (isset($cccount) && $cccount)
		{
			$databaseoutput .= "<script type=\"text/javascript\">\n<!--\n alert(\"".$clang->gT("Question could not be updated. There are conditions for other questions that rely on the answers to this question and changing the type will cause problems. You must delete these conditions before you can change the type of this question.","js")." ($qidlist)\")\n //-->\n</script>\n";
		}
		else
		{
			if (isset($_POST['gid']) && $_POST['gid'] != "")
			{
				
				$array_result=checkMovequestionConstraintsForConditions($_POST['sid'],$_POST['qid'], $_POST['gid']);
				// If there is no blocking conditions that could prevent this move
				if (is_null($array_result['notAbove']) && is_null($array_result['notBelow']))
				{

					$questlangs = GetAdditionalLanguagesFromSurveyID($_POST['sid']);
					$baselang = GetBaseLanguageFromSurveyID($_POST['sid']);
					array_push($questlangs,$baselang);
					foreach ($questlangs as $qlang)
					{
						if (isset($qlang) && $qlang != "")
						{ // ToDo: Sanitize the POST variables !
							$uqquery = "UPDATE ".db_table_name('questions')
							. "SET type='{$_POST['type']}', title='{$_POST['title']}', "
							. "question='{$_POST['question_'.$qlang]}', preg='{$_POST['preg']}', help='{$_POST['help_'.$qlang]}', "
							. "gid='{$_POST['gid']}', other='{$_POST['other']}', "
							. "mandatory='{$_POST['mandatory']}'";
	        				if ($oldgid!=$_POST['gid'])
						{
							if ( getGroupOrder($_POST['sid'],$oldgid) > getGroupOrder($_POST['sid'],$_POST['gid']) )
							{
								// Moving question to a 'upper' group
								// insert question at the end of the destination group
								// this prevent breaking conditions if the target qid is in the dest group
								$insertorder = getMaxquestionorder($_POST['gid']) + 1;
								$uqquery .=', question_order='.$insertorder.' '; 
							}
							else
							{
								// Moving question to a 'lower' group
								// insert question at the beginning of the destination group
								shiftorderQuestions($_POST['sid'],$_POST['gid'],1); // makes 1 spare room for new question at top of dest group
								$uqquery .=', question_order=0 ';
							}
						}
							if (isset($_POST['lid']) && trim($_POST['lid'])!="")
							{
								$uqquery.=", lid='{$_POST['lid']}' ";
							}
							$uqquery.= "WHERE sid='{$_POST['sid']}' AND qid='{$_POST['qid']}' AND language='{$qlang}'";
							$uqresult = $connect->Execute($uqquery) or die ("Error Update Question: ".htmlspecialchars($uqquery)."<br />".htmlspecialchars($connect->ErrorMsg()));
							if (!$uqresult)
							{
								$databaseoutput .= "<script type=\"text/javascript\">\n<!--\n alert(\"".$clang->gT("Question could not be updated","js")."\n".$connect->ErrorMsg()."\")\n //-->\n</script>\n";
							}
						}
					}
					// if the group has changed then fix the sortorder of old and new group
					if ($oldgid!=$_POST['gid']) 
	                		{
	                    			fixsortorderQuestions(0,$oldgid);
	                    			fixsortorderQuestions(0,$_POST['gid']);

						// If some questions have conditions set on this question's answers
						// then change the cfieldname accordingly
						fixmovedquestionConditions($_POST['qid'], $oldgid, $_POST['gid']);
	                		}
					if ($keepanswers == "0")
					{
						$query = "DELETE FROM ".db_table_name('answers')." WHERE qid={$_POST['qid']}";
						$result = $connect->Execute($query) or die("Error: ".htmlspecialchars($connect->ErrorMsg()));
						if (!$result)
						{
							$databaseoutput .= "<script type=\"text/javascript\">\n<!--\n alert(\"".$clang->gT("Answers can't be deleted","js")."\n".htmlspecialchars($connect->ErrorMsg())."\")\n //-->\n</script>\n";
						}
					}
				}
				else
				{
					// There are conditions constraints: alert the user
					$errormsg="";
					if (!is_null($array_result['notAbove']))
					{
						$errormsg.=$clang->gT("This question relies on other question's answers and can't be moved above groupId:","js")
							. " " . $array_result['notAbove'][0][0] . " " . $clang->gT("in position","js")." ".$array_result['notAbove'][0][1]."\\n"
							. $clang->gT("See conditions:")."\\n";
						
						foreach ($array_result['notAbove'] as $notAboveCond)
						{
							$errormsg.="- cid:". $notAboveCond[3]."\\n";	
						}
						
					}
					if (!is_null($array_result['notBelow']))
					{
						$errormsg.=$clang->gT("Some questions rely on this question's answers. You can't move this question below groupId:","js")
							. " " . $array_result['notBelow'][0][0] . " " . $clang->gT("in position","js")." ".$array_result['notBelow'][0][1]."\\n"
							. $clang->gT("See conditions:")."\\n";
						
						foreach ($array_result['notBelow'] as $notBelowCond)
						{
							$errormsg.="- cid:". $notBelowCond[3]."\\n";	
						}
					}

					$databaseoutput .= "<script type=\"text/javascript\">\n<!--\n alert(\"$errormsg\")\n //-->\n</script>\n";
					$gid= $oldgid; // group move impossible ==> keep display on oldgid
				}
			}
			else
			{
				$databaseoutput .= "<script type=\"text/javascript\">\n<!--\n alert(\"".$clang->gT("Question could not be updated","js")."\")\n //-->\n</script>\n";
			}
		}
	}

	elseif ($action == "copynewquestion" && $actsurrows['define_questions'])
	{

		if (!$_POST['title'])
		{
			$databaseoutput .= "<script type=\"text/javascript\">\n<!--\n alert(\"".$clang->gT("Question could not be added. You must insert a code in the mandatory field","js")."\")\n //-->\n</script>\n";
		}
		else
		{
			$_POST  = array_map('db_quote', $_POST);
    		$questlangs = GetAdditionalLanguagesFromSurveyID($_POST['sid']);
    		$baselang = GetBaseLanguageFromSurveyID($_POST['sid']);
    		
			if (!isset($_POST['lid']) || $_POST['lid']=='') {$_POST['lid']=0;}
			//Get maximum order from the question group
			$max=get_max_question_order($_POST['gid'])+1 ;
            // Insert the base language of the question
			$query = "INSERT INTO {$dbprefix}questions (sid, gid, type, title, question, help, other, mandatory, lid, question_order, language) 
                      VALUES ({$_POST['sid']}, {$_POST['gid']}, '{$_POST['type']}', '{$_POST['title']}', '".$_POST['question_'.$baselang]."', '".$_POST['help_'.$baselang]."', '{$_POST['other']}', '{$_POST['mandatory']}', '{$_POST['lid']}',$max,".db_quoteall($baselang).")";
			$result = $connect->Execute($query) or die($connect->ErrorMsg());
			$newqid = $connect->Insert_ID();
			if (!$result)
			{
				$databaseoutput .= "<script type=\"text/javascript\">\n<!--\n alert(\"".$clang->gT("Question could not be created.","js")."\\n".htmlspecialchars($connect->ErrorMsg())."\")\n //-->\n</script>\n";

			}
            
            foreach ($questlangs as $qlanguage)
            { 
            if ($databasetype=='odbc_mssql') {@$connect->Execute("SET IDENTITY_INSERT ".db_table_name('questions')." ON");}
			$query = "INSERT INTO {$dbprefix}questions (qid, sid, gid, type, title, question, help, other, mandatory, lid, question_order, language) 
                      VALUES ($newqid,{$_POST['sid']}, {$_POST['gid']}, '{$_POST['type']}', '{$_POST['title']}', '".$_POST['question_'.$qlanguage]."', '".$_POST['help_'.$qlanguage]."', '{$_POST['other']}', '{$_POST['mandatory']}', '{$_POST['lid']}',$max,".db_quoteall($qlanguage).")";
			$result = $connect->Execute($query) or die($connect->ErrorMsg());
            if ($databasetype=='odbc_mssql') {@$connect->Execute("SET IDENTITY_INSERT ".db_table_name('questions')." OFF");}
			}
			if (!$result)
			{
				$databaseoutput .= "<script type=\"text/javascript\">\n<!--\n alert(\"".$clang->gT("Question could not be created.","js")."\\n".htmlspecialchars($connect->ErrorMsg())."\")\n //-->\n</script>\n";

			}
			if (returnglobal('copyanswers') == "Y")
			{
				$q1 = "SELECT * FROM {$dbprefix}answers WHERE qid="
				. returnglobal('oldqid')
				. " ORDER BY code";
				$r1 = db_execute_assoc($q1);
				while ($qr1 = $r1->FetchRow())
				{
					$qr1 = array_map('db_quote', $qr1);
					$i1 = "INSERT INTO {$dbprefix}answers (qid, code, answer, default_value, sortorder, language) "
					. "VALUES ('$newqid', '{$qr1['code']}', "
					. "'{$qr1['answer']}', '{$qr1['default_value']}', "
					. "'{$qr1['sortorder']}', '{$qr1['language']}')";
					$ir1 = $connect->Execute($i1);
				}
			}
			if (returnglobal('copyattributes') == "Y")
			{
				$q1 = "SELECT * FROM {$dbprefix}question_attributes
				   WHERE qid=".returnglobal('oldqid')."
				   ORDER BY qaid";
				$r1 = db_execute_assoc($q1);
				while($qr1 = $r1->FetchRow())
				{
					$qr1 = array_map('db_quote', $qr1);
					$i1 = "INSERT INTO {$dbprefix}question_attributes
					   (qid, attribute, value)
					   VALUES ('$newqid',
					   '{$qr1['attribute']}',
					   '{$qr1['value']}')";
					$ir1 = $connect->Execute($i1);
				} // while
			}
		}
	}
	elseif ($action == "delquestion" && $actsurrows['define_questions'])
	{
		if (!isset($qid)) {$qid=returnglobal('qid');}
		//check if any other questions have conditions which rely on this question. Don't delete if there are.
		$ccquery = "SELECT * FROM {$dbprefix}conditions WHERE cqid=$qid";
		$ccresult = db_execute_assoc($ccquery) or die ("Couldn't get list of cqids for this question<br />".htmlspecialchars($ccquery)."<br />".htmlspecialchars($connect->ErrorMsg()));
		$cccount=$ccresult->RecordCount();
		while ($ccr=$ccresult->FetchRow()) {$qidarray[]=$ccr['qid'];}
		if (isset($qidarray)) {$qidlist=implode(", ", $qidarray);}
		if ($cccount) //there are conditions dependant on this question
		{
			$databaseoutput .= "<script type=\"text/javascript\">\n<!--\n alert(\"".$clang->gT("Question could not be deleted. There are conditions for other questions that rely on this question. You cannot delete this question until those conditions are removed","js")." ($qidlist)\")\n //-->\n</script>\n";
		}
		else
		{
			$result = db_execute_assoc("SELECT gid FROM ".db_table_name('questions')." WHERE qid='{$qid}'");
			$row=$result->FetchRow();
			$gid = $row['gid'];
			//see if there are any conditions/attributes/answers for this question, and delete them now as well
			$cquery = "DELETE FROM {$dbprefix}conditions WHERE qid=$qid";
			$cresult = $connect->Execute($cquery);
			$query = "DELETE FROM {$dbprefix}question_attributes WHERE qid=$qid";
			$result = $connect->Execute($query);
			$cquery = "DELETE FROM {$dbprefix}answers WHERE qid=$qid";
			$cresult = $connect->Execute($cquery);
			$query = "DELETE FROM {$dbprefix}questions WHERE qid=$qid";
			$result = $connect->Execute($query);
			fixsortorderQuestions(0,$gid);
			if ($result)
			{
				$qid="";
				$_POST['qid']="";
				$_GET['qid']="";
			}
			else
			{
				$databaseoutput .= "<script type=\"text/javascript\">\n<!--\n alert(\"".$clang->gT("Question could not be deleted","js")."\n$error\")\n //-->\n</script>\n";
			}
		}
	}

	elseif ($action == "delquestionall" && $actsurrows['define_questions'])
	{
		if (!isset($qid)) {$qid=returnglobal('qid');}
		//check if any other questions have conditions which rely on this question. Don't delete if there are.
		$ccquery = "SELECT * FROM {$dbprefix}conditions WHERE cqid={$_GET['qid']}";
		$ccresult = db_execute_assoc($ccquery) or die ("Couldn't get list of cqids for this question<br />".htmlspecialchars($ccquery)."<br />".htmlspecialchars($connect->ErrorMsg()));
		$cccount=$ccresult->RecordCount();
		while ($ccr=$ccresult->FetchRow()) {$qidarray[]=$ccr['qid'];}
		if (isset($qidarray) && $qidarray) {$qidlist=implode(", ", $qidarray);}
		if ($cccount) //there are conditions dependant on this question
		{
			$databaseoutput .= "<script type=\"text/javascript\">\n<!--\n alert(\"".$clang->gT("Question could not be deleted. There are conditions for other questions that rely on this question. You cannot delete this question until those conditions are removed","js")." ($qidlist)\")\n //-->\n</script>\n";
		}
		else
		{
			//First delete all the answers
			if (!isset($total)) {$total=0;}
			$query = "DELETE FROM {$dbprefix}answers WHERE qid=$qid";
			if ($result=$connect->Execute($query)) {$total++;}
			$query = "DELETE FROM {$dbprefix}conditions WHERE qid=$qid";
			if ($result=$connect->Execute($query)) {$total++;}
			$query = "DELETE FROM {$dbprefix}questions WHERE qid=$qid";
			if ($result=$connect->Execute($query)) {$total++;}
		}
		if ($total==3)
		{
			$qid="";
			$_POST['qid']="";
			$_GET['qid']="";
		}
		else
		{
			$databaseoutput .= "<script type=\"text/javascript\">\n<!--\n alert(\"".$clang->gT("Question could not be deleted","js")."\n$error\")\n //-->\n</script>\n";
		}
	}

	elseif ($action == "modanswer" && $actsurrows['define_questions'])
	{
		switch($_POST['method'])
		{
			// Add a new answer button
			case $clang->gT("Add new Answer", "unescaped"):
			if (isset($_POST['insertcode']) && $_POST['insertcode']!='' && $_POST['insertcode'] != "0")
			{
				//$_POST  = array_map('db_quote', $_POST);//Removed: qstr is used in SQL below
				$_POST['insertcode']=sanitize_paranoid_string($_POST['insertcode']);
				$query = "select max(sortorder) as maxorder from ".db_table_name('answers')." where qid='$qid'";
				$result = $connect->Execute($query);
				$newsortorder=sprintf("%05d", $result->fields['maxorder']+1);
				$anslangs = GetAdditionalLanguagesFromSurveyID($surveyid);
				$baselang = GetBaseLanguageFromSurveyID($surveyid);
				$query = "select * from ".db_table_name('answers')." where code=".$connect->qstr($_POST['insertcode'])." and language='$baselang' and qid={$_POST['qid']}";
                $result = $connect->Execute($query);				
                if (isset($result) && $result->RecordCount()>0)
                
				{
					$databaseoutput .= "<script type=\"text/javascript\">\n<!--\n alert(\"".$clang->gT("Error adding answer: You can't use the same answer code more than once.","js")."\")\n //-->\n</script>\n";
				}
				 else
				 {
        
        
        				// Add new Answer for Base Language Question
        				$query = "INSERT INTO ".db_table_name('answers')." (qid, code, answer, sortorder, default_value,language) VALUES ('{$_POST['qid']}', ".$connect->qstr($_POST['insertcode']).", ".$connect->qstr($_POST['insertanswer']).", '{$newsortorder}', 'N','$baselang')";
        	       		if (!$result = $connect->Execute($query))
        				{
        					$databaseoutput .= "<script type=\"text/javascript\">\n<!--\n alert(\"".$clang->gT("Failed to insert answer","js")." - ".$query." - ".$connect->ErrorMsg()."\")\n //-->\n</script>\n";
        				}
        				foreach ($anslangs as $anslang)
        				{
        					if(!isset($_POST['default'])) $_POST['default'] = "";
        	    				$query = "INSERT INTO ".db_table_name('answers')." (qid, code, answer, sortorder, default_value,language) VALUES ('{$_POST['qid']}', ".$connect->qstr($_POST['insertcode']).",".$connect->qstr($_POST['insertanswer']).", '{$newsortorder}', 'N','$anslang')";
        	       		    		if (!$result = $connect->Execute($query))
        					{
        						$databaseoutput .= "<script type=\"text/javascript\">\n<!--\n alert(\"".$clang->gT("Failed to insert answer","js")." - ".$query." - ".$connect->ErrorMsg()."\")\n //-->\n</script>\n";
        					}
        				}
        		}
			} else {
				$databaseoutput .= "<script type=\"text/javascript\">\n<!--\n alert(\"".$clang->gT("Invalid or empty answer code supplied","js")."\")\n //-->\n</script>\n";
			}
		break;
		// Save all answers with one button
		case $clang->gT("Save All", "unescaped"):
			//Determine autoids by evaluating the hidden field		
            $sortorderids=explode(' ', trim($_POST['sortorderids']));
            $codeids=explode(' ', trim($_POST['codeids']));
            $count=0;
            $invalidCode = 0;
            $duplicateCode = 0;
            
            $testarray = array();
            
            for ($i=0; $i<count($sortorderids); $i++)
            {
            	if (isset($codeids[$i])) array_push($testarray,$_POST['code_'.$codeids[$i]]);
            }
             //die(print_r($testarray));
 			$dupanswers = array();
			for ($i=0; $i<=count($testarray); $i++)
			{
				$value = $testarray[$i];
				if (count($testarray) > 1 )
				{
					unset($testarray[$i]);
					if (in_array($value, $testarray))
					{
						array_push($dupanswers,$value);
					}
				}
			}

         	foreach ($sortorderids as $sortorderid)
        	{
        		$langid=substr($sortorderid,0,strrpos($sortorderid,'_')); 
        		$orderid=substr($sortorderid,strrpos($sortorderid,'_')+1,20);
        		if ($_POST['code_'.$codeids[$count]] != "0" && trim($_POST['code_'.$codeids[$count]]) != "" && !in_array($_POST['code_'.$codeids[$count]],$dupanswers))
        		{
     				$oldcode=false;
        			$query = "SELECT code from ".db_table_name('answers')." WHERE qid=".$connect->qstr($qid)." and sortorder=".$connect->qstr($orderid)." ";
     				$result = $connect->SelectLimit($query, 1);
     				if($result->RecordCount() > 0)
					{
						$tmpcode = $result->FetchRow();
						$oldcode=$tmpcode['code'];
					}
        			$_POST['code_'.$codeids[$count]]=sanitize_paranoid_string($_POST['code_'.$codeids[$count]]);
        			$query = "UPDATE ".db_table_name('answers')." SET code=".$connect->qstr($_POST['code_'.$codeids[$count]]).
                         	 ",	answer=".$connect->qstr($_POST['answer_'.$sortorderid])." WHERE qid=".$connect->qstr($qid)." and sortorder=".$connect->qstr($orderid)." and language=".$connect->qstr($langid)."";
                    if (!$result = $connect->Execute($query))
        			{
        				$databaseoutput .= "<script type=\"text/javascript\">\n<!--\n alert(\"".$clang->gT("Failed to update answers","js")." - ".$query." - ".$connect->ErrorMsg()."\")\n //-->\n</script>\n";
    				}
    				if ($oldcode != false)
    				{
    					// Update the Answer code in conditions (updates if row exists right)
    					$query = "UPDATE ".db_table_name('conditions')." SET value = ".$connect->qstr($_POST['code_'.$codeids[$count]])." where cqid='$qid' and value='$oldcode'";
        				$result = $connect->Execute($query);
    				}
        		} else {
        			if ($_POST['code_'.$codeids[$count]] == "0" || trim($_POST['code_'.$codeids[$count]]) == "") $invalidCode = 1;
        			if (in_array($_POST['code_'.$codeids[$count]],$dupanswers)) $duplicateCode = 1;
        		}
    			$count++;
    			if ($count>count($codeids)-1) {$count=0;}
		    }
		    if ($invalidCode == 1) $databaseoutput .= "<script type=\"text/javascript\">\n<!--\n alert(\"".$clang->gT("Answers with a code of 0 (zero) or blank code are not allowed, and will not be saved","js")."\")\n //-->\n</script>\n";
			if ($duplicateCode == 1) $databaseoutput .= "<script type=\"text/javascript\">\n<!--\n alert(\"".$clang->gT("Duplicate codes found, these entries won't be updated","js")."\")\n //-->\n</script>\n";
		break;

		// Pressing the Up button
		case $clang->gT("Up", "unescaped"):
		$newsortorder=$_POST['sortorder']-1;
		$oldsortorder=$_POST['sortorder'];
		$cdquery = "UPDATE ".db_table_name('answers')." SET sortorder=-1 WHERE qid=$qid AND sortorder='$newsortorder'";
		$cdresult=$connect->Execute($cdquery) or die($connect->ErrorMsg());
		$cdquery = "UPDATE ".db_table_name('answers')." SET sortorder=$newsortorder WHERE qid=$qid AND sortorder=$oldsortorder";
		$cdresult=$connect->Execute($cdquery) or die($connect->ErrorMsg());
		$cdquery = "UPDATE ".db_table_name('answers')." SET sortorder='$oldsortorder' WHERE qid=$qid AND sortorder=-1";
		$cdresult=$connect->Execute($cdquery) or die($connect->ErrorMsg());
		break;

        // Pressing the Down button
		case $clang->gT("Dn", "unescaped"):
		$newsortorder=$_POST['sortorder']+1;
		$oldsortorder=$_POST['sortorder'];
		$cdquery = "UPDATE ".db_table_name('answers')." SET sortorder=-1 WHERE qid=$qid AND sortorder='$newsortorder'";
		$cdresult=$connect->Execute($cdquery) or die($connect->ErrorMsg());
		$cdquery = "UPDATE ".db_table_name('answers')." SET sortorder='$newsortorder' WHERE qid=$qid AND sortorder=$oldsortorder";
		$cdresult=$connect->Execute($cdquery) or die($connect->ErrorMsg());
		$cdquery = "UPDATE ".db_table_name('answers')." SET sortorder=$oldsortorder WHERE qid=$qid AND sortorder=-1";
		$cdresult=$connect->Execute($cdquery) or die($connect->ErrorMsg());
		break;
		
		// Delete Button
		case $clang->gT("Del", "unescaped"):
			$query = "DELETE FROM ".db_table_name('answers')." WHERE qid={$qid} AND sortorder='{$_POST['sortorder']}'";
			if (!$result = $connect->Execute($query))
			{
				$databaseoutput .= "<script type=\"text/javascript\">\n<!--\n alert(\"".$clang->gT("Failed to delete answer","js")." - ".$query." - ".$connect->ErrorMsg()."\")\n //-->\n</script>\n";
			}
            fixsortorderAnswers($qid);
		break;
		
		// Default Button
		case $clang->gT("Default", "unescaped"):
			$query = "SELECT default_value from ".db_table_name('answers')." where qid={$qid} AND sortorder='{$_POST['sortorder']}' GROUP BY default_value";
			$result = db_execute_assoc($query);
			$row = $result->FetchRow();
			if ($row['default_value'] == "Y")
			{
				$query = "UPDATE ".db_table_name('answers')." SET default_value='N' WHERE qid={$qid} AND sortorder='{$_POST['sortorder']}'";
				if (!$result = $connect->Execute($query))
				{
					$databaseoutput .= "<script type=\"text/javascript\">\n<!--\n alert(\"".$clang->gT("Failed to make answer not default","js")." - ".$query." - ".$connect->ErrorMsg()."\")\n //-->\n</script>\n";
				}
			} else {	
				$query = "SELECT type from ".db_table_name('questions')." where qid={$qid} GROUP BY qid";
				$result = db_execute_assoc($query);
				$row = $result->FetchRow();
				if ($row['type'] == "O" || $row['type'] == "L" || $row['type'] == "!")
				{   // SINGLE CHOICE QUESTION, SET ALL RECORDS TO N, THEN WE SET ONE TO Y
					$query = "UPDATE ".db_table_name('answers')." SET default_value='N' WHERE qid={$qid}";
					$result = $connect->Execute($query);
				}
				$query = "UPDATE ".db_table_name('answers')." SET default_value='Y' WHERE qid={$qid} AND sortorder='{$_POST['sortorder']}'";
				if (!$result = $connect->Execute($query))
				{
					$databaseoutput .= "<script type=\"text/javascript\">\n<!--\n alert(\"".$clang->gT("Failed to make answer default","js")." - ".$query." - ".$connect->ErrorMsg()."\")\n //-->\n</script>\n";
				}
			}
		break;
		}
		/*
		
		if ((!isset($_POST['olddefault']) || ($_POST['olddefault'] != $_POST['default']) && $_POST['default'] == "Y") || ($_POST['default'] == "Y" && $_POST['ansaction'] == $clang->gT("Add"))) //TURN ALL OTHER DEFAULT SETTINGS TO NO
		{
			$query = "UPDATE {$dbprefix}answers SET default_value = 'N' WHERE qid={$_POST['qid']}";
			$result=$connect->Execute($query) or die("Error occurred updating default_value settings");
		}
		if (isset($_POST['code'])) $_POST['code'] = db_quote($_POST['code']);
		if (isset($_POST['oldcode'])) {$_POST['oldcode'] = db_quote($_POST['oldcode']);}
		if (isset($_POST['answer'])) $_POST['answer'] = db_quote($_POST['answer']);
		if (isset($_POST['oldanswer'])) {$_POST['oldanswer'] = db_quote($_POST['oldanswer']);}
		if (isset($_POST['default_value'])) {$_POST['oldanswer'] = db_quote($_POST['default_value']);}
		switch ($_POST['ansaction'])
		{
			case $clang->gT("Fix Sort", "unescaped"):
			fixsortorderAnswers($_POST['qid']);
			break;
			case $clang->gT("Sort Alpha", "unescaped"):
			$uaquery = "SELECT * FROM {$dbprefix}answers WHERE qid='{$_POST['qid']}' ORDER BY answer";
			$uaresult = db_execute_assoc($uaquery) or die("Cannot get answers<br />".htmlspecialchars($uaquery)."<br />".htmlspecialchars($connect->ErrorMsg()));
			while($uarow=$uaresult->FetchRow())
			{
				$orderedanswers[]=array("qid"=>$uarow['qid'],
				"code"=>$uarow['code'],
				"answer"=>$uarow['answer'],
				"default_value"=>$uarow['default_value'],
				"sortorder"=>$uarow['sortorder']);
			} // while
			$i=0;
			foreach ($orderedanswers as $oa)
			{
				$position=sprintf("%05d", $i);
				$upquery = "UPDATE {$dbprefix}answers SET sortorder='$position' WHERE qid='{$oa['qid']}' AND code='{$oa['code']}'";
				$upresult = $connect->Execute($upquery);
				$i++;
			} // foreach
			break;
			case $clang->gT("Add", "unescaped"):
			if ((trim($_POST['code'])=='') || (trim($_POST['answer'])==''))
			{
				$databaseoutput .= "<script type=\"text/javascript\">\n<!--\n alert(\"".$clang->gT("Answer could not be added. You must include both a Code and an Answer","js")."\")\n //-->\n</script>\n";
			}
			else
			{
				$uaquery = "SELECT * FROM {$dbprefix}answers WHERE code = '{$_POST['code']}' AND qid={$_POST['qid']}";
				$uaresult = $connect->Execute($uaquery) or die ("Cannot check for duplicate codes<br />".htmlspecialchars($uaquery)."<br />".htmlspecialchars($connect->ErrorMsg()));
				$matchcount = $uaresult->RecordCount();
				if ($matchcount) //another answer exists with the same code
				{
					$databaseoutput .= "<script type=\"text/javascript\">\n<!--\n alert(\"".$clang->gT("Answer could not be added. There is already an answer with this code","js")."\")\n //-->\n</script>\n";
				}
				else
				{
					$cdquery = "INSERT INTO {$dbprefix}answers (qid, code, answer, sortorder, default_value) VALUES ('{$_POST['qid']}', '{$_POST['code']}', '{$_POST['answer']}', '{$_POST['sortorder']}', '{$_POST['default']}')";
					$cdresult = $connect->Execute($cdquery) or die ("Couldn't add answer<br />".htmlspecialchars($cdquery)."<br />".htmlspecialchars($connect->ErrorMsg()));
				}
			}
			break;
			case $clang->gT("Save", "unescaped"):
			if ((trim($_POST['code'])=='') || (trim($_POST['answer'])==''))
			{
				$databaseoutput .= "<script type=\"text/javascript\">\n<!--\n alert(\"".$clang->gT("Answer could not be updated. You must include both a Code and an Answer","js")."\")\n //-->\n</script>\n";
			}
			else
			{
				if ($_POST['code'] != $_POST['oldcode']) //code is being changed. Check against other codes and conditions
				{
					$uaquery = "SELECT * FROM {$dbprefix}answers WHERE code = '{$_POST['code']}' AND qid={$_POST['qid']}";
					$uaresult = $connect->Execute($uaquery) or die ("Cannot check for duplicate codes<br />".htmlspecialchars($uaquery)."<br />".htmlspecialchars($connect->ErrorMsg()));
					$matchcount = $uaresult->RecordCount();
					$ccquery = "SELECT * FROM {$dbprefix}conditions WHERE cqid={$_POST['qid']} AND value='{$_POST['oldcode']}'";
					$ccresult = db_execute_assoc($ccquery) or die ("Couldn't get list of cqids for this answer<br />".htmlspecialchars($ccquery)."<br />".htmlspecialchars($connect->ErrorMsg()));
					$cccount=$ccresult->RecordCount();
					while ($ccr=$ccresult->FetchRow()) {$qidarray[]=$ccr['qid'];}
					if (isset($qidarray)) {$qidlist=implode(", ", $qidarray);}
				}
				if (isset($matchcount) && $matchcount) //another answer exists with the same code
				{
					$databaseoutput .= "<script type=\"text/javascript\">\n<!--\n alert(\"".$clang->gT("Answer could not be updated. There is already an answer with this code","js")."\")\n //-->\n</script>\n";
				}
				else
				{
					if (isset($cccount) && $cccount) // there are conditions dependent upon this answer to this question
					{
						$databaseoutput .= "<script type=\"text/javascript\">\n<!--\n alert(\"".$clang->gT("Answer could not be updated. You have changed the answer code, but there are conditions to other questions which are dependant upon the old answer code to this question. You must delete these conditions before you can change the code to this answer.","js")." ($qidlist)\")\n //-->\n</script>\n";
					}
					else
					{
						$cdquery = "UPDATE {$dbprefix}answers SET qid='{$_POST['qid']}', code='{$_POST['code']}', answer='{$_POST['answer']}', sortorder='{$_POST['sortorder']}', default_value='{$_POST['default']}' WHERE code='{$_POST['oldcode']}' AND qid='{$_POST['qid']}'";
						$cdresult = $connect->Execute($cdquery) or die ("Couldn't update answer<br />".htmlspecialchars($cdquery)."<br />".htmlspecialchars($connect->ErrorMsg()));
					}
				}
			}
			break;
			case $clang->gT("Del", "unescaped"):
			$ccquery = "SELECT * FROM {$dbprefix}conditions WHERE cqid={$_POST['qid']} AND value='{$_POST['oldcode']}'";
			$ccresult = db_execute_assoc($ccquery) or die ("Couldn't get list of cqids for this answer<br />".htmlspecialchars($ccquery)."<br />".htmlspecialchars($connect->ErrorMsg()));
			$cccount=$ccresult->RecordCount();
			while ($ccr=$ccresult->FetchRow()) {$qidarray[]=$ccr['qid'];}
			if (isset($qidarray) && $qidarray) {$qidlist=implode(", ", $qidarray);}
			if ($cccount)
			{
				$databaseoutput .= "<script type=\"text/javascript\">\n<!--\n alert(\"".$clang->gT("Answer could not be deleted. There are conditions for other questions that rely on this answer. You cannot delete this answer until those conditions are removed","js")." ($qidlist)\")\n //-->\n</script>\n";
			}
			else
			{
				$cdquery = "DELETE FROM {$dbprefix}answers WHERE code='{$_POST['oldcode']}' AND answer='{$_POST['oldanswer']}' AND qid='{$_POST['qid']}'";
				$cdresult = $connect->Execute($cdquery) or die ("Couldn't update answer<br />".htmlspecialchars($cdquery)."<br />".htmlspecialchars($connect->ErrorMsg()));
			}
			fixsortorderAnswers($qid);
			break;
			case $clang->gT("Up", "unescaped"):
			$newsortorder=sprintf("%05d", $_POST['sortorder']-1);
			$replacesortorder=$newsortorder;
			$newreplacesortorder=sprintf("%05d", $_POST['sortorder']);
			$cdquery = "UPDATE {$dbprefix}answers SET sortorder='PEND' WHERE qid=$qid AND sortorder='$newsortorder'";
			$cdresult=$connect->Execute($cdquery) or die(htmlspecialchars($connect->ErrorMsg()));
			$cdquery = "UPDATE {$dbprefix}answers SET sortorder='$newsortorder' WHERE qid=$qid AND sortorder='$newreplacesortorder'";
			$cdresult=$connect->Execute($cdquery) or die(htmlspecialchars($connect->ErrorMsg()));
			$cdquery = "UPDATE {$dbprefix}answers SET sortorder='$newreplacesortorder' WHERE qid=$qid AND sortorder='PEND'";
			$cdresult=$connect->Execute($cdquery) or die(htmlspecialchars($connect->ErrorMsg()));
			break;
			case $clang->gT("Dn", "unescaped"):
			$newsortorder=sprintf("%05d", $_POST['sortorder']+1);
			$replacesortorder=$newsortorder;
			$newreplacesortorder=sprintf("%05d", $_POST['sortorder']);
			$newreplace2=sprintf("%05d", $_POST['sortorder']);
			$cdquery = "UPDATE {$dbprefix}answers SET sortorder='PEND' WHERE qid=$qid AND sortorder='$newsortorder'";
			$cdresult=$connect->Execute($cdquery) or die(htmlspecialchars($connect->ErrorMsg()));
			$cdquery = "UPDATE {$dbprefix}answers SET sortorder='$newsortorder' WHERE qid=$qid AND sortorder='{$_POST['sortorder']}'";
			$cdresult=$connect->Execute($cdquery) or die(htmlspecialchars($connect->ErrorMsg()));
			$cdquery = "UPDATE {$dbprefix}answers SET sortorder='$newreplacesortorder' WHERE qid=$qid AND sortorder='PEND'";
			$cdresult=$connect->Execute($cdquery) or die(htmlspecialchars($connect->ErrorMsg()));
			break;
			default:
			break;
		}*/
	}

	elseif ($action == "insertCSV" && $actsurrows['define_questions'])
	{
		if (get_magic_quotes_gpc() == "0")
		{
			$_POST['svettore'] = addcslashes($_POST['svettore'], "'");
		}
		$vettore = explode ("^", $svettore );
		$band = 0;
		$indice = $_POST['numcol'] - 1;
		foreach ($vettore as $k => $v)
		{
			$vettoreriga = explode ($elem, $v);
			if ($band == 1)
			{
				$valore = $vettoreriga[$indice];
				$valore = trim($valore);
				if (!is_null($valore))
				{
					$cdquery = "INSERT INTO {$dbprefix}answers (qid, code, answer, sortorder, default_value) VALUES ('{$_POST['qid']}', '$k', '$valore', '00000', 'N')";
					$cdresult = $cdresult = $connect->Execute($cdquery) or die(htmlspecialchars($connect->ErrorMsg()));
				}
			}
			$band = 1;
		}
	}

	elseif ($action == "updatesurvey" && $actsurrows['edit_survey_property'])
	{
		if ($_POST['url'] == "http://") {$_POST['url']="";}
		$_POST  = array_map('db_quote', $_POST);

		if (trim($_POST['expires'])=="")
		{
			$_POST['expires']='1980-01-01';
		}
		else
		{
			$_POST['expires']="'".$_POST['expires']."'";
		}

		CleanLanguagesFromSurvey($_POST['sid'],$_POST['languageids']);
		FixLanguageConsistency($_POST['sid'],$_POST['languageids']);

		$usquery = "UPDATE {$dbprefix}surveys \n"
		. "SET admin='{$_POST['admin']}', useexpiry='{$_POST['useexpiry']}',\n"
		. "expires={$_POST['expires']}, adminemail='{$_POST['adminemail']}',\n"
		. "private='{$_POST['private']}', faxto='{$_POST['faxto']}',\n"
		. "format='{$_POST['format']}', template='{$_POST['template']}',\n"
		. "url='{$_POST['url']}', \n"
		. "language='{$_POST['language']}', additional_languages='{$_POST['languageids']}',\n"
		. "datestamp='{$_POST['datestamp']}', ipaddr='{$_POST['ipaddr']}', refurl='{$_POST['refurl']}',\n"
		. "usecookie='{$_POST['usecookie']}', notification='{$_POST['notification']}',\n"
		. "allowregister='{$_POST['allowregister']}', attribute1='{$_POST['attribute1']}',\n"
		. "attribute2='{$_POST['attribute2']}', allowsave='{$_POST['allowsave']}',\n"
		. "autoredirect='{$_POST['autoredirect']}', allowprev='{$_POST['allowprev']}'\n"
		. "WHERE sid={$_POST['sid']}";
		
		$usresult = $connect->Execute($usquery) or die("Error updating<br />".htmlspecialchars($usquery)."<br /><br /><strong>".htmlspecialchars($connect->ErrorMsg()));
		$sqlstring ='';
		foreach (GetAdditionalLanguagesFromSurveyID($surveyid) as $langname)
		{
			if ($langname)
			{
				$sqlstring .= "and surveyls_language <> '".$langname."' ";
			}
		}
		// Add base language too
		$sqlstring .= "and surveyls_language <> '".GetBaseLanguageFromSurveyID($surveyid)."' ";

		$usquery = "Delete from ".db_table_name('surveys_languagesettings')." where surveyls_survey_id={$_POST['sid']} ".$sqlstring;
		$usresult = $connect->Execute($usquery) or die("Error deleting obsolete surveysettings<br />".htmlspecialchars($usquery)."<br /><br /><strong>".htmlspecialchars($connect->ErrorMsg()));

		foreach (GetAdditionalLanguagesFromSurveyID($surveyid) as $langname)
		{
			if ($langname)
			{
				$usquery = "select * from ".db_table_name('surveys_languagesettings')." where surveyls_survey_id={$_POST['sid']} and surveyls_language='".$langname."'";
				$usresult = $connect->Execute($usquery) or die("Error deleting obsolete surveysettings<br />".htmlspecialchars($usquery)."<br /><br /><strong>".htmlspecialchars($connect->ErrorMsg()));
				if ($usresult->RecordCount()==0)
				{
//					$usquery = "insert into ".db_table_name('surveys_languagesettings')." SET surveyls_survey_id={$_POST['sid']}, surveyls_language='".$langname."', surveyls_title=''";
					$usquery = "insert into ".db_table_name('surveys_languagesettings')." (surveyls_survey_id,surveyls_language,surveyls_title) VALUES ({$_POST['sid']}, '".$langname."', '')";
					$usresult = $connect->Execute($usquery) or die("Error deleting obsolete surveysettings<br />".htmlspecialchars($usquery)."<br /><br /><strong>".htmlspecialchars($connect->ErrorMsg()));
				}
			}
		}



		if ($usresult)
		{
			$surveyselect = getsurveylist();
		}
		else
		{
			$databaseoutput .= "<script type=\"text/javascript\">\n<!--\n alert(\"".$clang->gT("Survey could not be updated","js")."\n".$connect->ErrorMsg() ." ($usquery)\")\n //-->\n</script>\n";
		}
	}

	elseif ($action == "delsurvey" && $actsurrows['delete_survey']) //can only happen if there are no groups, no questions, no answers etc.
	{
		$query = "DELETE FROM {$dbprefix}surveys WHERE sid=$surveyid";
		$result = $connect->Execute($query);
		if ($result)
		{
			$surveyid = "";
			$surveyselect = getsurveylist();
		}
		else
		{
			$databaseoutput .= "<script type=\"text/javascript\">\n<!--\n alert(\"".$clang-gT("ERROR deleting Survey id","js")." ($surveyid)!\n$error\")\n //-->\n</script>\n";

		}
	}


	// Save the 2nd page from the survey-properties
	elseif ($action == "updatesurvey2" && $actsurrows['edit_survey_property'])
	{
		$_POST  = array_map('db_quote', $_POST);
		$languagelist = GetAdditionalLanguagesFromSurveyID($surveyid);
		$languagelist[]=GetBaseLanguageFromSurveyID($surveyid);
		foreach ($languagelist as $langname)
		{
			if ($langname)
			{
				$usquery = "UPDATE ".db_table_name('surveys_languagesettings')." \n"
				. "SET surveyls_title='".$_POST['short_title_'.$langname]."', surveyls_description='".$_POST['description_'.$langname]."',\n"
				. "surveyls_welcometext='".$_POST['welcome_'.$langname]."',\n"
				. "surveyls_urldescription='".$_POST['urldescrip_'.$langname]."', surveyls_email_invite_subj='".$_POST['email_invite_subj_'.$langname]."',\n"
				. "surveyls_email_invite='".$_POST['email_invite_'.$langname]."', surveyls_email_remind_subj='".$_POST['email_remind_subj_'.$langname]."',\n"
				. "surveyls_email_remind='".$_POST['email_remind_'.$langname]."', surveyls_email_register_subj='".$_POST['email_register_subj_'.$langname]."',\n"
				. "surveyls_email_register='".$_POST['email_register_'.$langname]."', surveyls_email_confirm_subj='".$_POST['email_confirm_subj_'.$langname]."',\n"
				. "surveyls_email_confirm='".$_POST['email_confirm_'.$langname]."'\n"
				. "WHERE surveyls_survey_id=".$_POST['sid']." and surveyls_language='".$langname."'";
				$usresult = $connect->Execute($usquery) or die("Error updating<br />".htmlspecialchars($usquery)."<br /><br /><strong>".htmlspecialchars($connect->ErrorMsg()));
			}
		}
	}

}


elseif ($action == "insertnewsurvey" && $_SESSION['USER_RIGHT_CREATE_SURVEY'])
{
	if ($_POST['url'] == "http://") {$_POST['url']="";}
	if (!$_POST['surveyls_title'])
	{
		$databaseoutput .= "<script type=\"text/javascript\">\n<!--\n alert(\"".$clang->gT("Survey could not be created because it did not have a short title","js")."\")\n //-->\n</script>\n";
	}
	else
	{
		$_POST  = array_map('db_quote', $_POST);
		if (trim($_POST['expires'])=="")
		{
			$_POST['expires']='1980-01-01';
		}
		else
		{
			$_POST['expires']="'".$_POST['expires']."'";
		}
		// Get random ids until one is found that is not used
		do
		{
			$surveyid = getRandomID();
			$isquery = "SELECT sid FROM ".db_table_name('surveys')." WHERE sid=$surveyid";
			$isresult = db_execute_assoc($isquery);
		}
		while ($isresult->RecordCount()>0);

		$isquery = "INSERT INTO {$dbprefix}surveys\n"
		. "(sid, owner_id, admin, active, useexpiry, expires, "
		. "adminemail, private, faxto, format, template, url, "
		. "language, datestamp, ipaddr, refurl, usecookie, notification, allowregister, attribute1, attribute2, "
		. "allowsave, autoredirect, allowprev,datecreated)\n"
		. "VALUES ($surveyid, {$_SESSION['loginID']},\n"
		. "'{$_POST['admin']}', 'N', \n"
		. "'{$_POST['useexpiry']}',{$_POST['expires']}, '{$_POST['adminemail']}', '{$_POST['private']}',\n"
		. "'{$_POST['faxto']}', '{$_POST['format']}', '{$_POST['template']}', '{$_POST['url']}',\n"
		. "'{$_POST['language']}', '{$_POST['datestamp']}', '{$_POST['ipaddr']}', '{$_POST['refurl']}',\n"
		. "'{$_POST['usecookie']}', '{$_POST['notification']}', '{$_POST['allowregister']}',\n"
		. "'{$_POST['attribute1']}', '{$_POST['attribute2']}', \n"
		. "'{$_POST['allowsave']}', '{$_POST['autoredirect']}', '{$_POST['allowprev']}','".date("Y-m-d")."')";
		$isresult = $connect->Execute($isquery);
		// insert base language into surveys_language_settings
		$isquery = "INSERT INTO ".db_table_name('surveys_languagesettings')
		. "(surveyls_survey_id, surveyls_language, surveyls_title, surveyls_description, surveyls_welcometext, surveyls_urldescription, "
		. "surveyls_email_invite_subj, surveyls_email_invite, surveyls_email_remind_subj, surveyls_email_remind, "
		. "surveyls_email_register_subj, surveyls_email_register, surveyls_email_confirm_subj, surveyls_email_confirm)\n"
		. "VALUES ($surveyid, '{$_POST['language']}', '{$_POST['surveyls_title']}', '{$_POST['description']}',\n"
		. "'".str_replace("\n", "<br />", $_POST['welcome'])."',\n"
		. "'{$_POST['urldescrip']}', '{$_POST['email_invite_subj']}',\n"
		. "'{$_POST['email_invite']}', '{$_POST['email_remind_subj']}',\n"
		. "'{$_POST['email_remind']}', '{$_POST['email_register_subj']}',\n"
		. "'{$_POST['email_register']}', '{$_POST['email_confirm_subj']}',\n"
		. "'{$_POST['email_confirm']}')";


		$isresult = $connect->Execute($isquery);

		// Insert into survey_rights
		$isrquery = "INSERT INTO {$dbprefix}surveys_rights VALUES($surveyid,". $_SESSION['loginID'].",1,1,1,1,1,1)"; //ADDED by Moses inserts survey rights for owner
		$isrresult = $connect->Execute($isrquery) or die ($isrquery."<br />".$connect->ErrorMsg()); //ADDED by Moses
		if ($isresult)
		{
			$surveyselect = getsurveylist();
		}
		else
		{
			$errormsg=$clang->gT("Survey could not be created","js")." - ".$connect->ErrorMsg();
			$databaseoutput .= "<script type=\"text/javascript\">\n<!--\n alert(\"$errormsg\")\n //-->\n</script>\n";
			$databaseoutput .= htmlspecialchars($isquery);
		}
	}
}

else
{

	include("access_denied.php");
}


?>
