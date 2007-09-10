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

//import responses from an old_ survey table into an active survey
include_once("login_check.php");        

if (!isset($oldtable)) {$oldtable=returnglobal('oldtable');}
if (!isset($surveyid)) {$surveyid=returnglobal('sid');}


if (!$subaction == "import")
{
	// show UI for choosing old table

	$query = db_select_tables_like("{$dbprefix}old\_survey_{$surveyid}\_%");
	$result = db_execute_num($query) or die("Error:<br />$query<br />".$connect->ErrorMsg());
	$optionElements = '';
	while ($row = $result->FetchRow())
	{
		$optionElements .= "\t\t\t<option>{$row[0]}</option>\n";
	}

	$importoldresponsesoutput = "";
    $importoldresponsesoutput .= "<table width='99%' align='center' class='menubar' cellpadding='1' cellspacing='0'>\n"
    ."\t<tr><td colspan='2' height='4'><strong>".$clang->gT("Quick Statistics")."</strong></td></tr>\n";
    //Get the menubar
    $importoldresponsesoutput .= browsemenubar();
    $importoldresponsesoutput .= "</table>\n";
	$importoldresponsesoutput .= "<br /><table align='center' class='outlinetable'>
		<tr>
			<th colspan='2'>".$clang->gT("Import responses from an old (deactivated) survey table into an active survey")."</th>
		</tr>
		<form method='post'>
		<tr>
		 <td align='right'>".$clang->gT("Target Survey ID")."</td>
		 <td> $surveyid<input type='hidden' value='$surveyid' name='sid'></td>
		</tr>
		<tr>
		 <td align='right'>
		  ".$clang->gT("Source table").":
		 </td>
		 <td>
		  <select name='oldtable' >
{$optionElements}
		  </select>
		 </td>
		</tr>
		<tr>
		 <td colspan='2' align='center'>
		  <input type='submit' value='".$clang->gT("Import Responses")."' onclick='return confirm(\"".$clang->gT("Are you sure?","js").")'>&nbsp;
 	 	  <input type='hidden' name='subaction' value='import'>
		 </td>
		</tr>
		</form>
		</table><br />&nbsp;";
}
elseif (isset($surveyid) && $surveyid && isset($oldtable))
{

	// options (UI not implemented)

	$dontimportfields = array(
	'id' //,'otherfield'
	);
	$presetfields = array( // quote all strings so we can allow NULL
	//'4X13X951'=>"'Y'"
	//'id' => "NULL"
	);
	$importidrange = false; //array('first'=>3,'last'=>10);

	$activetable = "{$dbprefix}survey_$surveyid";

	// fields we can import
	$importablefields = array();
	$query = "SHOW COLUMNS FROM {$activetable}";
	$result = db_execute_assoc($query) or die("Error:<br />$query<br />".$connect->ErrorMsg());
	while ($row = $result->FetchRow())
	{
		if (!in_array($row['Field'],$dontimportfields))
		{
			$importablefields[] = $row['Field'];
		}
	}

	// fields we can supply
	$availablefields = array();
	$query = "SHOW COLUMNS FROM {$oldtable}";
	$result = db_execute_assoc($query) or die("Error:<br />$query<br />".$connect->ErrorMsg());
	while ($row = $result->FetchRow())
	{
		$availablefields[] = $row['Field'];
	}
	foreach ($presetfields as $field => $value)
	{
		if (!in_array($field,$availablefields))
		{
			$availablefields[] = $field;
		}
	}

	$fieldstoimport = array_intersect($importablefields,$availablefields);

	// data sources for each field (field of oldtable or preset value)
	$sourcefields = array();
	foreach ($fieldstoimport as $field)
	{
		$sourcefields[] = array_key_exists($field,$presetfields)?
		$presetfields[$field]
		: ($oldtable.'.'.$field);
	}

	$query = "INSERT INTO {$activetable} (\n\t".join("\t, ",$fieldstoimport)."\n) "
	."SELECT\n\t".join("\t,",$sourcefields)."\n"
	."FROM {$oldtable}";
	if (is_array($importidrange))
	{
		$query .= " WHERE {$oldtable}.id >= {$importidrange['first']} "
		." AND {$oldtable}.id <= {$importidrange['last']}";
	}

	$result = $connect->Execute($query) or die("Error:<br />$query<br />".$connect->ErrorMsg());

	header("Location: $scriptname?action=browse&sid=$surveyid");
}

?>
