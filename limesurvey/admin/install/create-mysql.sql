-- phpMyAdmin SQL Dump
-- version 2.8.0.2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Jul 23, 2006 at 07:56 PM
-- Server version: 5.0.20
-- PHP Version: 5.1.4
-- 
-- Database: `limesurvey-development`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `answers`
-- 

CREATE TABLE `prefix_answers` (
  `qid` int(11) NOT NULL default '0',
  `code` varchar(5) NOT NULL default '',
  `answer` text NOT NULL,
  `default_value` char(1) NOT NULL default 'N',
  `sortorder` int(11) NOT NULL,
  `language` varchar(20) default 'en',
  PRIMARY KEY  (`qid`,`code`,`language`)
) TYPE=MyISAM CHARACTER SET utf8;

-- --------------------------------------------------------

-- 
-- Table structure for table `assessments`
-- 

CREATE TABLE `prefix_assessments` (
  `id` int(11) NOT NULL auto_increment,
  `sid` int(11) NOT NULL default '0',
  `scope` varchar(5) NOT NULL default '',
  `gid` int(11) NOT NULL default '0',
  `name` text NOT NULL,
  `minimum` varchar(50) NOT NULL default '',
  `maximum` varchar(50) NOT NULL default '',
  `message` text NOT NULL,
  `link` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 CHARACTER SET utf8;

-- --------------------------------------------------------

-- 
-- Table structure for table `conditions`
-- 

CREATE TABLE `prefix_conditions` (
  `cid` int(11) NOT NULL auto_increment,
  `qid` int(11) NOT NULL default '0',
  `cqid` int(11) NOT NULL default '0',
  `cfieldname` varchar(50) NOT NULL default '',
  `method` char(2) NOT NULL default '',
  `value` varchar(5) NOT NULL default '',
  PRIMARY KEY  (`cid`)
) TYPE=MyISAM AUTO_INCREMENT=1 CHARACTER SET utf8;


-- 
-- Table structure for table `groups`
-- 

CREATE TABLE `prefix_groups` (
  `gid` int(11) NOT NULL auto_increment,
  `sid` int(11) NOT NULL default '0',
  `group_name` varchar(100) NOT NULL default '',
  `group_order` int(11) NOT NULL default '0',
  `description` text,
  `language` varchar(20) default 'en',
  PRIMARY KEY  (`gid`,`language`)
) TYPE=MyISAM AUTO_INCREMENT=1 CHARACTER SET utf8;

-- --------------------------------------------------------

-- 
-- Table structure for table `labels`
-- 

CREATE TABLE `prefix_labels` (
  `lid` int(11) NOT NULL default '0',
  `code` varchar(5) NOT NULL default '',
  `title` varchar(100) NOT NULL default '',
  `sortorder` int(11) NOT NULL,
  `language` varchar(20) default 'en',
  PRIMARY KEY  (`lid`,`sortorder`,`language`),
  KEY `ixcode` (`code`)
) TYPE=MyISAM CHARACTER SET utf8;

-- --------------------------------------------------------

-- 
-- Table structure for table `labelsets`
-- 

CREATE TABLE `prefix_labelsets` (
  `lid` int(11) NOT NULL auto_increment,
  `label_name` varchar(100) NOT NULL default '',
  `languages` varchar(200) default 'en',
  PRIMARY KEY  (`lid`)
) TYPE=MyISAM AUTO_INCREMENT=1 CHARACTER SET utf8;

-- --------------------------------------------------------

-- 
-- Table structure for table `question_attributes`
-- 

CREATE TABLE `prefix_question_attributes` (
  `qaid` int(11) NOT NULL auto_increment,
  `qid` int(11) NOT NULL default '0',
  `attribute` varchar(50) default NULL,
  `value` varchar(20) default NULL,
  PRIMARY KEY  (`qaid`)
) TYPE=MyISAM AUTO_INCREMENT=1 CHARACTER SET utf8;

-- --------------------------------------------------------

-- 
-- Table structure for table `questions`
-- 

CREATE TABLE `prefix_questions` (
  `qid` int(11) NOT NULL auto_increment,
  `sid` int(11) NOT NULL default '0',
  `gid` int(11) NOT NULL default '0',
  `type` char(1) NOT NULL default 'T',
  `title` varchar(20) NOT NULL default '',
  `question` text NOT NULL,
  `preg` text,
  `help` text,
  `other` char(1) NOT NULL default 'N',
  `mandatory` char(1) default NULL,
  `lid` int(11) NOT NULL default '0',
  `question_order` int(11) NOT NULL,
  `language` varchar(20) default 'en',
  PRIMARY KEY  (`qid`,`language`)
) TYPE=MyISAM AUTO_INCREMENT=1 CHARACTER SET utf8;

-- --------------------------------------------------------


-- 
-- Table structure for table `saved_control`
-- 

CREATE TABLE `prefix_saved_control` (
  `scid` int(11) NOT NULL auto_increment,
  `sid` int(11) NOT NULL default '0',
  `srid` int(11) NOT NULL default '0',
  `identifier` text NOT NULL,
  `access_code` text NOT NULL,
  `email` varchar(200) default NULL,
  `ip` text NOT NULL,
  `saved_thisstep` text NOT NULL,
  `status` char(1) NOT NULL default '',
  `saved_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `refurl` text,
  PRIMARY KEY  (`scid`)
) TYPE=MyISAM AUTO_INCREMENT=1 CHARACTER SET utf8;

-- --------------------------------------------------------

-- 
-- Table structure for table `surveys`
-- 

CREATE TABLE `prefix_surveys` (
  `sid` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `admin` varchar(50) default NULL,
  `active` char(1) NOT NULL default 'N',
  `expires` date default NULL,
  `adminemail` varchar(100) default NULL,
  `private` char(1) default NULL,
  `faxto` varchar(20) default NULL,
  `format` char(1) default NULL,
  `template` varchar(100) default 'default',
  `url` varchar(255) default NULL,
  `language` varchar(50) default NULL,
  `additional_languages` varchar(255) default NULL,
  `datestamp` char(1) default 'N',
  `usecookie` char(1) default 'N',
  `notification` char(1) default '0',
  `allowregister` char(1) default 'N',
  `attribute1` varchar(255) default NULL,
  `attribute2` varchar(255) default NULL,
  `allowsave` char(1) default 'Y',
  `autonumber_start` bigint(11) default '0',
  `autoredirect` char(1) default 'N',
  `allowprev` char(1) default 'Y',
  `ipaddr` char(1) default 'N',
  `useexpiry` char(1) NOT NULL default 'N',
  `refurl` char(1) default 'N',
  `datecreated` date default NULL,
  PRIMARY KEY  (`sid`)
) TYPE=MyISAM CHARACTER SET utf8;


-- 
-- Table structure for table `surveys_languagesettings`
-- 


DROP TABLE IF EXISTS `prefix_surveys_languagesettings`;
CREATE TABLE `prefix_surveys_languagesettings` (
  `surveyls_survey_id` INT UNSIGNED NOT NULL DEFAULT 0,
  `surveyls_language` VARCHAR(45) NULL DEFAULT 'en',
  `surveyls_title` VARCHAR(200) NOT NULL,
  `surveyls_description` TEXT NULL,
  `surveyls_welcometext` TEXT NULL,
  `surveyls_urldescription` VARCHAR(255) NULL,
  `surveyls_email_invite_subj` VARCHAR(255) NULL,
  `surveyls_email_invite` TEXT NULL,
  `surveyls_email_remind_subj` VARCHAR(255) NULL,
  `surveyls_email_remind` TEXT NULL,
  `surveyls_email_register_subj` VARCHAR(255) NULL,
  `surveyls_email_register` TEXT NULL,
  `surveyls_email_confirm_subj` VARCHAR(255) NULL,
  `surveyls_email_confirm` TEXT NULL,
  PRIMARY KEY (`surveyls_survey_id`, `surveyls_language`)
)
TYPE = MyISAM CHARACTER SET utf8;



-- 
-- Table structure for table `users`
-- 

CREATE TABLE `prefix_users` (
  `uid` int(11) NOT NULL auto_increment PRIMARY KEY,
  `users_name` varchar(64) NOT NULL UNIQUE default '',
  `password` BLOB NOT NULL,
  `full_name` varchar(50) NOT NULL,
  `parent_id` int(10) unsigned NOT NULL,
  `lang` varchar(20),
  `email` varchar(50) NOT NULL UNIQUE,
  `create_survey` tinyint(1) NOT NULL default '0',
  `create_user` tinyint(1) NOT NULL default '0',
  `delete_user` tinyint(1) NOT NULL default '0',
  `move_user` tinyint(1) NOT NULL default '0',
  `configurator` tinyint(1) NOT NULL default '0',
  `manage_template` tinyint(1) NOT NULL default '0',
  `manage_label` tinyint(1) NOT NULL default '0'
) TYPE=MyISAM CHARACTER SET utf8;

CREATE TABLE `prefix_surveys_rights` (
	`sid` int(10) unsigned NOT NULL default '0',
	`uid` int(10) unsigned NOT NULL default '0',
	`edit_survey_property` tinyint(1) NOT NULL default '0',
	`define_questions` tinyint(1) NOT NULL default '0',
	`browse_response` tinyint(1) NOT NULL default '0',
	`export` tinyint(1) NOT NULL default '0',
	`delete_survey` tinyint(1) NOT NULL default '0',
	`activate_survey` tinyint(1) NOT NULL default '0',
	PRIMARY KEY (sid, uid)
) TYPE=MyISAM CHARACTER SET utf8;

CREATE TABLE `prefix_user_groups` (
	`ugid` int(10) unsigned NOT NULL auto_increment PRIMARY KEY,
	`name` varchar(20) NOT NULL UNIQUE,
	`description` TEXT NOT NULL,
	`owner_id` int(10) unsigned NOT NULL
) TYPE=MyISAM CHARACTER SET utf8;

CREATE TABLE `prefix_user_in_groups` (
	`ugid` int(10) unsigned NOT NULL,
	`uid` int(10) unsigned NOT NULL
) TYPE=MyISAM CHARACTER SET utf8;
--
-- Table structure for table `settings_global`
--

CREATE TABLE `prefix_settings_global` (
  `stg_name` varchar(50) NOT NULL default '',
  `stg_value` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`stg_name`)
) TYPE=MyISAM CHARACTER SET utf8;

--
-- Table `settings_global`
--

INSERT INTO `prefix_settings_global` VALUES ('DBVersion', '112');

--
-- Table `users`
--
INSERT INTO `prefix_users` VALUES (NULL, '$defaultuser', '$defaultpass', '$siteadminname', 0, '$defaultlang', '$siteadminemail', 1,1,1,1,1,1,1);

