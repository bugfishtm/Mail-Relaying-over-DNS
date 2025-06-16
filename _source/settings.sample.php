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
	/* MySQL Connection Informations */				
	define("_SQL_HOST_", 			"127.0.0.1"); // MySQL Host
	define("_SQL_USER_", 			"USERNAME"); // MySQL Username
	define("_SQL_PASS_", 			"PASSWORD"); // MySQL Password
	define("_SQL_DB_", 				"DBNAME");	// MySQL Database Name				
				
	/* Site Setup */				
	# A Imaginary Server Name to show at Title	 
	define("_TITLE_", 		"MRoDNS"); 			
	# URL to your Impressum (with http://https: in front of it)	
	define("_IMPRESSUM_", 		"YOUR_IMPRESSUM_URL");		
	# Cookie Prefix	
	define("_COOKIES_",     		"mrod_");
	# Define Blacklist Limit for IP Bans (1000 Recommended) # Can be left unchanged
	define("_IP_BLACKLIST_DAILY_OP_LIMIT_", 1000); 
	# Define Time for CSRF Validation	(1000 Recommended) # Can be left unchanged		
	define("_CSRF_VALID_LIMIT_TIME_", 	1000); 
	# Activate MySQL Logging Area and MySQL Logging at all? (For Developers) # Can be left unchanged
	define("_MYSQL_LOGGING_", 	false); 			

	/* Postfix Configuration File Path */
	# Path to Relaymap File on System from Postfix, like in main.cf
	define("_CRON_RELAYMAP_", 		"/etc/postfix/relaydomains");	
	# Path to Transportmaps File on System from Postfix, like in main.cf 	
	define("_CRON_TRANSMAP_", 		"/etc/postfix/transportmaps");	
				
	/* Settings for Auto-Fetch Domains from Bind9 Zone File Folder or Domain Table File (named.conf.local) */				
		# _CRON_MODE_ = 1 For Fetch with File like named.conf.local
		# _CRON_MODE_ = 2 - Fetch with Cached Files in example: /var/lib/bind (determine domains from file names)
	define("_CRON_MODE_", 	   			1);  // Set to false to deactivate...	
		
	# This is for _CRON_MODE_ 1 Setting which file should be treated as named.conf.local to fetch domains from!
	define("_CRON_MODE_1_PATH_", 	   	"/etc/bind/named.conf.local"); 
				
	# _CRON_MODE_ 2 : Look where youre replicated dns entries are stores, if they are at not in /var/cache/bind you can change the folder here
	# (sometimes they are in /var/lib/bind) - best to check on your server where this files are stored with bind if you want to use cron_mode 2 
	# if fetching from named.conf.local file is impossible...
	define("_CRON_MODE_2_PATH_", 	   	"/var/cache/bind/"); 
				
	# Set Order, First is highest Priority
	# dns-txt - First get from DNS TXT Entrie for Relay
	# dns-dom - Use the Domain (Always Success on Domain)
	# dns-sub - Use a Pre-defined Subdomain (Always Success on Domain)
	// Array with Prioritys to Use Different Types of Auto-Relay - an be left unchanged by default!
	define("_CRON_ARRAY_", 	   			array("dns-txt", "dns-dom", "dns-sub"));	
	
	# Cleanup old DNS Based Entries if Activated (by refresh for cleanup)	
	# For Fetched Domain Names by _CRON_MODE_ 1 or 2
	define("_CRON_CLEANUP_", 	   		true); 	
	
	/* How to handle auto-fetched domains? - Can be left unchanged, but you may want to defined another protocol (smtps) op port for automatic relay determination
		This is useful if you use the _CRON_MODE_ to Fetch entries out of the binds domain table file or a folder with zone file names! */		
	# If we fetch domain example.de from dns system - should mails be also forwarded to this domains mx at example.de?
	define("_CRON_DOMAIN_AS_RELAY_", 		true); # Use Domain as Relay Domain ?
	define("_CRON_DOMAIN_AS_RELAY_PORT_", 	"25"); #25/265/587
	define("_CRON_DOMAIN_AS_RELAY_PROT_", 	"smtp"); # smtp / smtps

	define("_CRON_SUB_AS_RELAY_", 			true); # Use Default Subdomain on Domains as Relaydomains?
	define("_CRON_SUB_AS_RELAY_SUB_", 		"mail"); # Default Subdomain
	define("_CRON_SUB_AS_RELAY_PORT_", 		"25"); #25/265/587
	define("_CRON_SUB_AS_RELAY_PROT_", 		"smtp"); # smtp / smtps

	define("_CRON_TXT_TO_RELAY_", 			true); # Check if TXT Entrie on Domain if there is Identifier with relay ID
	define("_CRON_TXT_TO_RELAY_STRING_", 	"mrodxmailrelay=");	 # TXT Identifier in DNS to use with MROD Relay-Assignment	
	
	#########################################################################################################################
	## DO NOT CHANGE BELOW! 
	## DO NOT CHANGE BELOW! 
	## DO NOT CHANGE BELOW! 
	#########################################################################################################################
	
	## Determine Document Root - Leave unchanged!
	$current_dir = dirname(__FILE__);
	if(!file_exists($current_dir."/settings.php")) { $current_dir = $current_dir."/../";}
	if(!file_exists($current_dir."/settings.php")) { $current_dir = $current_dir."../";}
	if(!file_exists($current_dir."/settings.php")) { $current_dir = $current_dir."../";}
	if(!file_exists($current_dir."/settings.php")) { echo "No settings.php found!<br />Please change settings.sample.php and rename this file to settings.php after that!"; exit(); }
	define('_MAIN_PATH_', $current_dir);	
	
	## Include Functions File - Do not Change!
	require_once(_MAIN_PATH_."/_instance/library.php");
	require_once(_MAIN_PATH_."/_instance/initialize.php");	