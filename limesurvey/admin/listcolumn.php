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

include_once("login_check.php");

sendcacheheaders();


if (!isset($surveyid)) {$surveyid=returnglobal('sid');}
if (!isset($column)) {$column=returnglobal('column');}
if (!isset($order)) {$order=returnglobal('order');}
if (!isset($sql)) {$sql=returnglobal('sql');}

if (!$surveyid)
{
	//NOSID
	exit;
}
if (!$column)
{
	//NOCOLUMN
	exit;
}

if ($connect->databaseType == 'odbc_mssql')
	{ $query = "SELECT id, ".db_quote_id($column)." FROM {$dbprefix}survey_$surveyid WHERE (".db_quote_id($column)." NOT LIKE '')"; }
else
	{ $query = "SELECT id, ".db_quote_id($column)." FROM {$dbprefix}survey_$surveyid WHERE (".db_quote_id($column)." != '')"; }

if ($sql && $sql != "NULL")
{
	$query .= " AND ".auto_unescape(urldecode($sql));
}

if (incompleteAnsFilterstate() === true) {$query .= " AND submitdate is not null";}

if ($order == "alpha")
{
	$query .= " ORDER BY ".db_quote_id($column);
}

$result=db_execute_assoc($query) or die("Error with query: ".$query."<br />".$connect->ErrorMsg());
$listcolumnoutput= "<table width='98%' class='statisticstable' border='1' cellpadding='2' cellspacing='0'>\n";
$listcolumnoutput.= "<tr><td><input type='image' src='$imagefiles/downarrow.png' align='center' onclick=\"window.open('admin.php?action=listcolumn&sid=$surveyid&column=$column&order=id', '_top')\"></td>\n";
$listcolumnoutput.= "<td valign='top'><input type='image' align='right' src='$imagefiles/close.gif' onclick='window.close()' />";
if ($connect->databaseType != 'odbc_mssql')
	{ $listcolumnoutput.= "<input type='image' src='$imagefiles/downarrow.png' align='left' onclick=\"window.open('admin.php?action=listcolumn&sid=$surveyid&column=$column&order=alpha', '_top')\" />"; }
$listcolumnoutput.= "</td></tr>\n";
while ($row=$result->FetchRow())
{
	$listcolumnoutput.=  "<tr><td valign='top' align='center' >"
	. "<a href='$scriptname?action=browse&sid=$surveyid&subaction=id&id=".$row['id']."' target='home'>"
	. $row['id']."</a></td>"
	. "<td valign='top'>".$row[$column]."</td></tr>\n";
}
$listcolumnoutput.= "</table>\n";

	
?>
