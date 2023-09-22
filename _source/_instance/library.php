<?php
	/*
		__________              _____.__       .__     
		\______   \__ __  _____/ ____\__| _____|  |__  
		 |    |  _/  |  \/ ___\   __\|  |/  ___/  |  \ 
		 |    |   \  |  / /_/  >  |  |  |\___ \|   Y  \
		 |______  /____/\___  /|__|  |__/____  >___|  /
				\/     /_____/               \/     \/  MRoD Functions File */
	function mrod_cron_registerDomain($mysql, $domain) {
		$done = false;
		$output = array();
		foreach(_CRON_ARRAY_ AS $key => $value) {
			if($value == "dns-sub" AND $done == false) {
				if(_CRON_SUB_AS_RELAY_) {
					
					$output["type"] = $value;
					$output["port"] = _CRON_SUB_AS_RELAY_PORT_;
					$output["host"] = _CRON_SUB_AS_RELAY_SUB_.".".$domain;
					$output["prot"] = _CRON_SUB_AS_RELAY_PROT_;
					$output["relay"] = "NULL";
					$done = true;
				}
			}
			if($value == "dns-txt" AND $done == false) {
				if(_CRON_TXT_TO_RELAY_) {
					@ob_start();
					@passthru("/usr/bin/dig ".$domain." TXT");
					@$lookup = @ob_get_contents();
					@ob_end_clean();
					if(strpos(@$lookup, _CRON_TXT_TO_RELAY_STRING_) > -1) { 
						$txtrelay = trim(substr($lookup, strpos($lookup, _CRON_TXT_TO_RELAY_STRING_)+strlen(_CRON_TXT_TO_RELAY_STRING_)));
						$txtrelay = trim(substr($txtrelay, 0, strpos($txtrelay, "\"")));			
					} 
					if(trim(@$txtrelay) != "" AND is_numeric(@$txtrelay)) {
						if(mrod_relay_id_exists($mysql, $txtrelay)) {
							$infos	=	mrod_relay_get($mysql, $txtrelay);
							
							$output["port"] = $infos["port"];
							$output["host"] = $infos["servername"];
							if($infos["smtps"] == 1) { $output["prot"] = "smtps"; } else { $output["prot"] = "smtp"; }
							$output["type"] = $value;	
							$output["relay"] = $txtrelay;		
							$done = true;
						}			
					}
				}
			}
			if($value == "dns-dom" AND $done == false) {
				if(_CRON_DOMAIN_AS_RELAY_) {
					
					$output["type"] = $value;
					$output["port"] = _CRON_DOMAIN_AS_RELAY_PORT_;
					$output["host"] = $domain;
					$output["prot"] = _CRON_DOMAIN_AS_RELAY_PROT_;
					$output["relay"] = "NULL";
					$done = true;
				}
			}

		} 
		
			if($x = mrod_domain_name_exists($mysql, trim($domain))) {
				$y = mrod_domain_get($mysql, $x);
				if(@trim($y["ovr_servername"]) != "") { $output["host"] = $y["ovr_servername"]; } 
				if(@trim($y["ovr_serverport"]) != "") { $output["port"] = $y["ovr_serverport"];  }
				
				if($y["ovr_smtps"] == 1) { $output["prot"] = "smtps";  }
				elseif($y["ovr_smtps"] == 0 AND is_numeric($y["ovr_smtps"])) { $output["prot"] = "smtp";}
				else {  }
			}
		return $output;
	}

	// True of False if Domain Exists
	function mrod_relay_id_exists($mysql, $id) { 
		if(is_numeric($id)) { 
			$query = mysqli_query($mysql->mysqlcon, "SELECT * FROM "._TABLE_RELAY_." WHERE id = \"".$mysql->escape($id)."\"");
			while ($result	=	mysqli_fetch_array($query) ) { return true; } 
		} return false; 
	}	
	
	// Check Function for an Connection
 	function mrod_relay_check($host, $port) {$f = @fsockopen($host, $port, $errno, $errstr, 1);if ($f !== false) {$res = fread($f, 1024) ;if (strlen($res) > 0 && strpos($res,'220') === 0){@fclose($f);return true;}else{@fclose($f);return false;}} return false;}
	
	function mrod_relay_name_exists($mysql, $domain_name) { 
		if(trim($domain_name) != "") { 
			$query = mysqli_query($mysql->mysqlcon, "SELECT * FROM "._TABLE_USERS_." WHERE id = \"".$mysql->escape(trim($domain_name))."\"");
			while ($result	=	mysqli_fetch_array($query) ) { return $result["user"]; } 
		} return false; 
	}	
	
	// Get all Informations of a Domain
	function mrod_relay_get($mysql, $id) {
		if(mrod_relay_id_exists($mysql, $id)) { 
			$query = mysqli_query($mysql->mysqlcon, "SELECT * FROM "._TABLE_RELAY_." WHERE id = \"".$mysql->escape($id)."\"");
			while ($result	=	mysqli_fetch_array($query) ) { return $result; } 
		} return false; 		
	}
	
	// Register a New Domain
	function mrod_relay_delete_by_id($mysql, $id) {
		// If Exists, Do not Register
		if(mrod_relay_id_exists($mysql, $id)) {  
			return $mysql->query("DELETE FROM "._TABLE_RELAY_." WHERE id = '$id';");
		} return false;		
	}
	
	// Register a New Domain
	function mrod_relay_delete_by_name($mysql, $domain) {
		// If Exists, Do not Register
		if(mrod_relay_name_exists($mysql, trim($domain))) {  
			return $mysql->query("DELETE FROM "._TABLE_RELAY_." WHERE domain = '".$mysql->escape(trim($domain))."';");
		} return false;		
	}	
	
	// True of False if Domain Exists
	function mrod_domain_id_exists($mysql, $id) { 
		if(is_numeric($id)) { 
			$query = mysqli_query($mysql->mysqlcon, "SELECT * FROM "._TABLE_DOMAIN_." WHERE id = \"".$mysql->escape($id)."\"");
			while ($result	=	mysqli_fetch_array($query) ) { return true; } 
		} return false; 
	}	
	
	function mrod_domain_name_exists($mysql, $domain_name) { 
		if(trim($domain_name) != "") { 
			$query = mysqli_query($mysql->mysqlcon, "SELECT * FROM "._TABLE_DOMAIN_." WHERE domain = \"".$mysql->escape(trim($domain_name))."\"");
			while ($result	=	mysqli_fetch_array($query) ) { return $result["id"]; } 
		} return false; 
	}	
	
	// Get all Informations of a Domain
	function mrod_domain_get($mysql, $id) {
		if(mrod_domain_id_exists($mysql, $id)) { 
			$query = mysqli_query($mysql->mysqlcon, "SELECT * FROM "._TABLE_DOMAIN_." WHERE id = \"".$mysql->escape($id)."\"");
			while ($result	=	mysqli_fetch_array($query) ) { return $result; } 
		} return false; 		
	}
	
	// Register a New Domain
	function mrod_domain_delete_by_id($mysql, $id) {
		// If Exists, Do not Register
		if(mrod_domain_id_exists($mysql, $id)) {  
			return $mysql->query("DELETE FROM "._TABLE_DOMAIN_." WHERE id = '$id';");
		} return false;		
	}
	
	// Register a New Domain
	function mrod_domain_delete_by_name($mysql, $domain) {
		// If Exists, Do not Register
		if(mrod_domain_name_exists($mysql, trim($domain))) {  
			return $mysql->query("DELETE FROM "._TABLE_DOMAIN_." WHERE domain = '".$mysql->escape(trim($domain))."';");
		} return false;		
	}	
	
	function mrod_user_get_name_from_id($mysql, $userid) { 
		if(is_numeric($userid)) { 
			$query = mysqli_query($mysql->mysqlcon, "SELECT * FROM "._TABLE_USER_." WHERE id = \"".$userid."\"");
			while ($result	=	mysqli_fetch_array($query) ) { return $result["user_name"]; } 
		} return false; 
	}	
	
	function mrod_user_get_name_from_id_read($mysql, $userid) { 
		if(is_numeric($userid)) { 
			$query = mysqli_query($mysql->mysqlcon, "SELECT * FROM "._TABLE_USER_." WHERE id = \"".mysqli_real_escape_string($mysql, $userid)."\"");
			while ($result	=	mysqli_fetch_array($query) ) { return $result["user"]; } 
		} return "[Deleted]";
	}	

	function mrod_user_get_id_from_name($mysql, $userid) { 
		if(is_numeric($userid)) { 
			$query = mysqli_query($mysql->mysqlcon, "SELECT * FROM "._TABLE_USER_." WHERE id = \"".mysqli_real_escape_string($mysql, $userid)."\"");
		while ($result	=	mysqli_fetch_array($query) ) { return $result["user"]; } 
		
		} return false; 
	}	
	
	function mrod_user_get_id_from_name_read($mysql, $userid) { 
		if(is_numeric($userid)) { 
			$query = mysqli_query($mysql->mysqlcon, "SELECT * FROM "._TABLE_USER_." WHERE id = \"".mysqli_real_escape_string($mysql, $userid)."\"");
		while ($result	=	mysqli_fetch_array($query) ) { return $result["user"]; } 
		
		} return "[Deleted]";
	}		
	
?>