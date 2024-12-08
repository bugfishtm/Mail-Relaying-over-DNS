<?php
	/* 
		 ____  __  __  ___  ____  ____  ___  _   _ 
		(  _ \(  )(  )/ __)( ___)(_  _)/ __)( )_( )
		 ) _ < )(__)(( (_-. )__)  _)(_ \__ \ ) _ ( 
		(____/(______)\___/(__)  (____)(___/(_) (_) www.bugfish.eu
			 _______ ______ _______ ______  
			(_______|_____ (_______|______) 
			 _  _  _ _____) )     _ _     _ 
			| ||_|| |  __  / |   | | |   | |
			| |   | | |  \ \ |___| | |__/ / 
			|_|   |_|_|   |_\_____/|_____/  
											
		Copyright (C) 2024 Jan Maurice Dahlmanns [Bugfish]

		This program is free software: you can redistribute it and/or modify
		it under the terms of the GNU General Public License as published by
		the Free Software Foundation, either version 3 of the License, or
		(at your option) any later version.

		This program is distributed in the hope that it will be useful,
		but WITHOUT ANY WARRANTY; without even the implied warranty of
		MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
		GNU General Public License for more details.

		You should have received a copy of the GNU General Public License
		along with this program.  If not, see <https://www.gnu.org/licenses/>.
	*/
	// Includes
	define("_MAIN_FOLDER_", _MAIN_PATH_);
	foreach (glob(_MAIN_PATH_."/_framework/functions/x_*.php") as $filename){require_once $filename;}
	foreach (glob(_MAIN_PATH_."/_framework/classes/x_*.php") as $filename){require_once $filename;}
	
	/* Init x_class_debug Class */
	$debug = new x_class_debug();
	$debug->required_php_module("mysqli", true);

	/* Variables */	
	define('_HELP_',    "https://bugfishtm.github.io/Mail-Relaying-over-DNS"); 
	define("_FOOTER_", '<div id="footer">MRoDv3.2.1 by <a href="https://bugfish.eu/aboutme" target="_blank" rel="noopeener">Bugfish</a> | <a href="'._IMPRESSUM_.'" target="_blank" rel="noopeener">Impressum</a> | <a href="'._HELP_.'" target="_blank" rel="noopeener">Help</a>');				
	
	// Table Names */
	define('_TABLE_PREFIX_',  				"mrod_");	
	define('_TABLE_USER_',   				_TABLE_PREFIX_."user");  
	define('_TABLE_USER_SESSION_',			_TABLE_PREFIX_."user_session");
	define('_TABLE_DOMAIN_',				_TABLE_PREFIX_."domain");
	define('_TABLE_RELAY_',					_TABLE_PREFIX_."relay");
	define('_TABLE_IPBL_',					_TABLE_PREFIX_."ipblacklist");
	define('_TABLE_PERM_',					_TABLE_PREFIX_."perms");
	define('_TABLE_LOG_',					_TABLE_PREFIX_."log");
	define('_TABLE_LOG_MYSQL_',				_TABLE_PREFIX_."log_mysql");
	
	// MySQL
	$mysql = new x_class_mysql(_SQL_HOST_, _SQL_USER_, _SQL_PASS_, _SQL_DB_);
	if ($mysql->lasterror != false) { $mysql->displayError(true); } 
	if(_MYSQL_LOGGING_) { $mysql->log_config(_TABLE_LOG_MYSQL_, "log"); }	
	
	// Create Non-Generated Databases
	$mysql->query("CREATE TABLE IF NOT EXISTS `"._TABLE_DOMAIN_."` (
		  `id` int NOT NULL AUTO_INCREMENT COMMENT 'Unique ID',
		  `domain` varchar(256) NOT NULL COMMENT 'Domain Name',
		  `fk_relay` int DEFAULT NULL COMMENT 'Related Relay',
		  `fk_user` int DEFAULT NULL COMMENT 'Related User',
		  `type` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'usr | dns-dom | dns-txt | dns-sub',
		  `ovr_servername` varchar(512) DEFAULT NULL COMMENT 'Override Server Name',
		  `ovr_serverport` int DEFAULT NULL COMMENT 'Override Server Port',
		  `ovr_smtps` tinyint(1) DEFAULT NULL COMMENT '0 - SMTP | 1 - SMTPS',
		  `creation` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'Creation Date',
		  `modification` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Modification Date',
		  PRIMARY KEY (`id`), UNIQUE KEY `domain` (`domain`) ) "); $mysql->free_all();	
	
	$mysql->query("CREATE TABLE IF NOT EXISTS `"._TABLE_RELAY_."` (
		  `id` int NOT NULL AUTO_INCREMENT COMMENT 'Unique ID',
		  `servername` varchar(512) NOT NULL COMMENT 'Server Hostname',
		  `port` int NOT NULL COMMENT 'Server Port',
		  `smtps` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 - SMTP  | 1 - SMTPS',
		  `creation` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'Creation Date',
		  `modification` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Modification Date',
		  `fk_user` int DEFAULT NULL COMMENT 'Related User',
		  PRIMARY KEY (`id`))"); $mysql->free_all();	
	
	/* Init x_class_user Class */		
	$user = new x_class_user($mysql, _TABLE_USER_, _TABLE_USER_SESSION_, _COOKIES_ , "admin", "changeme", 0);
	$user->multi_login(false);
	$user->login_recover_drop(true);
	$user->login_field_user();
	$user->mail_unique(false);
	$user->user_unique(true);
	$user->log_ip(false);
	$user->log_activation(false);
	$user->log_session(false);
	$user->log_recover(false);
	$user->log_mail_edit(false);
	$user->sessions_days(7);
	$user->init();	
	
	// Init x_class_ipbl IP Blacklist Class */	
	$ipbl = new x_class_ipbl($mysql, _TABLE_IPBL_, _IP_BLACKLIST_DAILY_OP_LIMIT_);		

	// Rename HTAccess */		
	if(@file_exists(_MAIN_FOLDER_."/dot.htaccess") AND !file_exists(_MAIN_FOLDER_."/.htaccess")) {
		@rename(_MAIN_FOLDER_."/dot.htaccess", _MAIN_FOLDER_."/.htaccess");}
	
	// Captcha Settings */
	define('_CAPTCHA_FONT_',   	 _MAIN_FOLDER_."/_style/font_captcha.ttf");
	define('_CAPTCHA_WIDTH_',    "200"); 
	define('_CAPTCHA_HEIGHT_',   "70");	
	define('_CAPTCHA_SQUARES_',   mt_rand(4, 15));	
	define('_CAPTCHA_ELIPSE_',    mt_rand(4, 15));	
	define('_CAPTCHA_RANDOM_',    mt_rand(1000, 9999));