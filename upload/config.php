<?php
	#####################################################################
	##### SYS CONFIG ####################################################
	#####################################################################
		define("_SERVERNAME_",       	    "ServerName");
		define("_COOKIES_",          		"MRDNS");
	#####################################################################
	##### LOGIN CONFIG ##################################################
	#####################################################################
		define("_LOGIN_ENABLE_BLOCKING_",  			true);
		define("_LOGIN_MAXTRIES_", 	       			10000);
		define("_LOGIN_ENABLE_BLOCKING_SESSION_",  	true);
		define("_LOGIN_MAXTRIES_BLOCKING_SESSION_", 20);
	#####################################################################
	##### MySQL LOGIN ###################################################
	#####################################################################
		$configuration_mysql["mysql_host"]		=	"127.0.0.1";
		$configuration_mysql["mysql_db"]		=	"";
		$configuration_mysql["mysql_user"]		=	"";	
		$configuration_mysql["mysql_pass"]		=	"";
	#####################################################################
	##### TABLE SETTINGS ################################################
	#####################################################################	
		define("_TABLE_DOMAINS_", "dbms_relaydomains");
		define("_TABLE_SERVERS_", "dbms_servers");
		define("_TABLE_USERS_",   "dbms_users");
	#####################################################################
	##### DNS FETCH CONFIG ##############################################
	#####################################################################		
		define("_MODE_", 	   			"named");
		# named = get from /etc/bind/named.conf.local content
			define("_MODE_1_NAMED_PATH_", 	   			"/etc/bind/named.conf.local");
		# cache = get from /var/cache/bind directory]
			define("_MODE_2_CACHE_PATH_", 	   			"/var/cache/bind/");
		# Cleanup old DNS Based Entries if Activated (by refresh for cleanup)
			define("_CLEANUP_", 			true);
		# Use Domains itself as Server with port
			define("_USE_DOMAINS_AS_SERVER_", 		true);
			define("_USE_DOMAINS_AS_SERVER_PORT_", 	"25");
		# If this is enabled DNS Fetched entries will be checked for server connection 
		# validity, if not it will be rewriten to fallback host
			define("_FALLBACK_ENABLE_", 	true);
			define("_FALLBACK_HOST_", 		"");		
			define("_FALLBACK_PORT_", 		"25");		
	#####################################################################
	##### POSTFIX PATHES   ##############################################
	#####################################################################	
		define("_CRON_RELAYMAP_", 		"/etc/postfix/relaydomains");		
		define("_CRON_TRANSMAP_", 		"/etc/postfix/transportmaps");			
	#####################################################################
	##### FUNCTIONS -- DO NOT TOUCH! ####################################
	#####################################################################
		$mysql = @mysqli_connect($configuration_mysql["mysql_host"], $configuration_mysql["mysql_user"], $configuration_mysql["mysql_pass"], $configuration_mysql["mysql_db"]);
			if (!$mysql) { http_response_code(503); echo "Error with Database!"; exit(); }	
		function getUsernameFromID($mysql, $userid) { if(is_numeric($userid)) { $query = mysqli_query($mysql, "SELECT * FROM "._TABLE_USERS_." WHERE id = \"".mysqli_real_escape_string($mysql, $userid)."\"");
			while ($result	=	mysqli_fetch_array($query) ) { return $result["user"]; } } return "[Not Avail.]"; }	
		function checkSMTPNow($host, $port) {$f = @fsockopen($host, $port, $errno, $errstr, 1);if ($f !== false) {$res = fread($f, 1024) ;if (strlen($res) > 0 && strpos($res,'220') === 0){@fclose($f);return true;}else{@fclose($f);return false;}} return false;}
	#####################################################################
	##### DNS FUNCTIONS -- DO NOT TOUCH! ################################
	#####################################################################
		function cron_fallback($ar) {
			if(_USE_DOMAINS_AS_SERVER_) {
					$ar["host"] = $ar["domain"];
					$ar["port"] = _USE_DOMAINS_AS_SERVER_PORT_;
					$ar["src"]  = "dmn";	
			} elseif(_FALLBACK_ENABLE_) {
					$ar["host"] = _FALLBACK_HOST_;
					$ar["port"] = _FALLBACK_PORT_;
					$ar["src"]  = "flbck";
			}
			return $ar;
		}
?>