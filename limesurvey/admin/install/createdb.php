<?php
/*
* LimeSurvey
* Copyright (C) 2007 The LimeSurvey Project Team / Carsten Schmitz
* All rights reserved.
* License: http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* LimeSurvey is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

//Ensure script is not run directly, avoid path disclosure
require_once(dirname(__FILE__).'/../../config.php');
require_once($rootdir.'/classes/core/language.php');
$clang = new limesurvey_lang("en");

$dbname = $databasename;

sendcacheheaders();

echo "<br />\n";
echo "<table width='350' align='center' style='border: 1px solid #555555' cellpadding='1' cellspacing='0'>\n";
echo "\t<tr bgcolor='#555555'><td colspan='2' height='4'><font size='1' face='verdana' color='white'><strong>".$clang->gT("Create Database")."</strong></td></tr>\n";
echo "\t<tr bgcolor='#CCCCCC'><td align='center'>$setfont\n";

// In Step2 fill the database with data
if (returnglobal('createdbstep2')==$clang->gT("Populate Database"))
{
   if ($databasetype='mysql') {@$connect->Execute("ALTER DATABASE `$dbname` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;");} //Set the collation also for manually created DBs
   if (modify_database(dirname(__FILE__).'/create-'.$databasetype.'.sql'))
   {
   echo sprintf($clang->gT("Database `%s` has been successfully populated."),$dbname)."</font></strong></font><br /><br />\n";
   echo "<input type='submit' value='".$clang->gT("Main Admin Screen")."' onclick='location.href=\"../$scriptname\"'>";
   exit;
   }
    else
    {
        echo $modifyoutput;      
        echo"Error";
    }

}

if (!$dbname)
{
	echo "<br /><strong>$setfont<font color='red'>".$clang->gT("Error")."</font></strong><br />\n";
	echo $clang->gT("Database Information not provided. This script must be run from admin.php only.");

	echo "<br /><br />\n";
	echo "<input type='submit' value='".$clang->gT("Main Admin Screen")."' onclick='location.href=\"../$scriptname\"'>";
	exit;
}
	
if (!$database_exists) //Database named in config.php does not exist
{
	// TODO SQL: Portable to other databases??
	switch ($databasetype)
	{
		case 'mysql': $createDb=$connect->Execute("CREATE DATABASE `$dbname` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci");
		break;
		case 'odbc_mssql':
		case 'mssql': $createDb=$connect->Execute("CREATE DATABASE [$dbname];");
		break;
		default: $createDb=$connect->Execute("CREATE DATABASE $dbname");
	}
	if ($createDb) //Database has been successfully created
	{
		$connect->database = $dbname;
		$connect->Execute("USE DATABASE `$dbname`");
		echo "<br />$setfont<strong><font color='green'>\n";
		echo $clang->gT("Database has been created.")."</font></strong></font><br /><br />\n";
		echo $clang->gT("Please click below to populate the database")."<br /><br />\n";
		echo "<form method='post'>";
		echo "<input type='submit' name='createdbstep2' value='".$clang->gT("Populate Database")."' onclick='location.href=\"createdb.php\"'></form>";
	}
	else
	{
		echo "<strong>$setfont<font color='red'>".$clang->gT("Error")."</font></strong></font><br />\n";
		echo $clang->gT("Could not create database")." ($dbname)<br /><font size='1'>\n";
		echo $connect->ErrorMsg();
		echo "</font><br /><br />\n";
		echo "<input type='submit' value='".$clang->gT("Main Admin Screen")."' onclick='location.href=\"../$scriptname\"'>";
	}
}
echo "</td></tr></table>\n";

?>
