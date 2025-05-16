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
	// Configurations Include
		require_once(dirname(__FILE__) ."/../settings.php");
		$log	=	new x_class_log($mysql, _TABLE_LOG_, "sync");
		function logging_add($text) {
			global $log_output;
			$finaltext = $text;
			echo $text;
			while(strpos($finaltext, "\r\n") != false) { $finaltext = str_replace("\r\n", "<br />", $finaltext); }
			if(substr($finaltext, 0, 2) == "OK") { $finaltext = " <font color='lime'>".$finaltext."</font>"; }
			elseif(substr($finaltext, 0, 8) == "FINISHED") { $finaltext = "<font color='yellow'>".$finaltext."</font>"; }
			elseif(substr($finaltext, 0, 5) == "ERROR") { $finaltext = " <font color='red'>".$finaltext."</font>"; }
			elseif(substr($finaltext, 0, 4) == "WARN") { $finaltext = "<font color='orange'>".$finaltext."</font>"; }
			elseif(substr($finaltext, 0, 4) == "INFO") { $finaltext = "<font color='lightblue'>".$finaltext."</font>"; }
			elseif(substr($finaltext, 0, 5) == "START") { $finaltext = "<font color='yellow'>".$finaltext."</font>"; }
			elseif(substr($finaltext, 0, 5) == "DEBUG") { $finaltext = "<font color='lightblue'>".$finaltext."</font>"; }
			$log_output .= $finaltext;}	

	logging_add("START: Cronjob Execution and Sync of Postix Relaydomains \r\n\r\n");
	// Cleanup old DNS Based Entries if Activated (by refresh for cleanup)
		$all_domains = array();
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//// Fetch Entries from Named.Conf
		if( _CRON_MODE_ == 1 ) {	
			logging_add("START: Fetching Domains from DNS Zone Table File: "._CRON_MODE_1_PATH_." \r\n");
			$handle = fopen(_CRON_MODE_1_PATH_, "r"); if ($handle) {
				while (($line = fgets($handle)) !== false) {	
					if(strpos($line, "zone ") > -1 AND strpos($line, ".in-addr.arpa") === false AND strpos($line, "localhost") === false AND strpos($line, ".ip6.arpa") === false) {	
						preg_match('/"(.*?)"/', $line, $match);
						$domain = trim($match[1]);	
						if($domain == ".") { continue; }
						$output = mrod_cron_registerDomain($mysql, $domain);
						if(isset($output["host"]) AND is_numeric($output["port"]) AND isset($output["prot"]) AND trim($domain) != "") {
							array_push($all_domains, trim($domain));
							if($output["prot"] == "smtps") { $newprot = 1 ; } else {   $newprot = 0 ; } 
							if($x = mrod_domain_name_exists($mysql, trim($domain))) {
								$mysql->query("UPDATE "._TABLE_DOMAIN_." SET fk_user = '0',  fk_relay = ".$output["relay"].", type = '".$output["type"]."', modification = CURRENT_TIMESTAMP() WHERE id = '".$x."';");	
								logging_add("OK: Fetched Domain updated into Database: ".htmlspecialchars(trim($domain))."\r\n");
							} else {
								$mysql->query("INSERT INTO "._TABLE_DOMAIN_."(domain, fk_user, fk_relay, type)
								VALUES('".$mysql->escape($domain)."', '0', ".$output["relay"].", '".$output["type"]."');");	
								logging_add("OK: Fetched Domain inserted into Database: ".htmlspecialchars(trim($domain))."\r\n");					
							}
						} else { logging_add("ERROR: Missing Data for domain (reconfigure of domain needed): ".trim($domain)."\r\n"); } 
					}  
				}
				fclose($handle); logging_add("OK: Success Fetching DNS Entries from File "._CRON_MODE_1_PATH_."\r\n\r\n");
			} else { logging_add("ERROR: Failed to Fetch DNS Entries from File "._CRON_MODE_1_PATH_."\r\n\r\n"); } 
			
		}

		/////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//// Fetch Entries from Cached File Names
		if( _CRON_MODE_ == 2 ) {
			logging_add("START: Fetching Domains Folders File Names at: "._CRON_MODE_2_PATH_." \r\n");
			if ($handle = opendir(_CRON_MODE_2_PATH_)) {
				while (false !== ($entry = readdir($handle))) {
					if ($entry != "." && $entry != "..") {
						if (strpos($entry, "_default.nzd") === false AND strpos($entry, "keys.bind") === false) {	
							$domain = trim($entry);
							$output = mrod_cron_registerDomain($mysql, $domain);
							if(isset($output["host"]) AND is_numeric($output["port"]) AND isset($output["prot"]) AND trim($domain) != "") {
								array_push($all_domains, trim($domain));
								if($output["prot"] == "smtps") { $newprot = 1 ; } else {   $newprot = 0 ; } 
								if($x = mrod_domain_name_exists($mysql, trim($domain))) {
									$mysql->query("UPDATE "._TABLE_DOMAIN_." SET fk_user = '0',  fk_relay = ".$output["relay"].", type = '".$output["type"]."', modification = CURRENT_TIMESTAMP() WHERE id = '".$x."';");	
									logging_add("OK: Cron Domain Updated from Cache File: ".htmlspecialchars(trim($domain)));
								} else {
									$mysql->query("INSERT INTO "._TABLE_DOMAIN_."(domain, fk_user, fk_relay, type)
									VALUES('".$mysql->escape($domain)."', '0', ".$output["relay"].", '".$output["type"]."');");		
									logging_add("OK: Cron Domain Inserted from Cache File: ".htmlspecialchars(trim($domain))."\r\n");
								}	
							}  else { logging_add("ERROR: Missing Data for domain (reconfigure of domain needed): ".trim($domain)."\r\n"); } 
						} else {  logging_add("WARN: Domain hold back: ".htmlspecialchars(trim($entry))."\r\n\r\n"); } 
					}				
				}   logging_add("OK: Success Fetching DNS Entries from "._CRON_MODE_2_PATH_."\r\n\r\n");
				@closedir($handle);
			} else {  logging_add("ERROR: Failed to Fetch DNS Entries "._CRON_MODE_2_PATH_."\r\n\r\n"); } 
		}
		
		if(_CRON_CLEANUP_) {
			logging_add("START: Deleting Auto-Fetched Domains not Existant anymore \r\n");
			$real_all_domains	= $mysql->select("SELECT * FROM "._TABLE_DOMAIN_." WHERE type LIKE '%dns%'", true);	
			if(is_array($real_all_domains)) {
				foreach($real_all_domains as $key => $value) {
					$deleteable = true;
					
					if(is_array($all_domains)) {
						foreach($all_domains as $x => $y) {
							if($y == $value["domain"]) { $deleteable = false; }
						}
					}
					
					if($deleteable) { logging_add("Cron Domain Automatically Deleted: ".htmlspecialchars(trim($value["domain"]))); echo "Domain: ".$value["domain"]."has been deleted!\r\n"; $mysql->query("DELETE FROM "._TABLE_DOMAIN_." WHERE id = '".$value["id"]."'"); }
				}
			}
		}		
		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//// Build the Files for Postfix		
		
		// Prepare Variables
			$buildstring_transport = "";
			$buildstring_relay = "";			
		logging_add("START: Updating Postfix! \r\n");
		
		// Build Strings for File Building
			$domains = $mysql->select("SELECT * FROM "._TABLE_DOMAIN_."", true);		
			if(is_array($domains)) {
				foreach($domains as $key => $value) {		
					// Init Variables for Buildstring Needed
						$servername = false;
						$serverport = false;					
						$type = false;					
						$smtps = false;	
						$valid = false;		
					if($value["type"] == "dns-dom") { $valid = true; $smtps = _CRON_DOMAIN_AS_RELAY_PROT_; $serverport = _CRON_DOMAIN_AS_RELAY_PORT_; $servername = $value["domain"]; $type = "dns-dom"; }
					if($value["type"] == "dns-txt") { 
						if(mrod_relay_id_exists($mysql, $value["fk_relay"])) { 
							$valid = true; 
							$relay = mrod_relay_get($mysql, $value["fk_relay"]);
							if($relay["smtps"] == 1) { $tmpsmtps = "smtps"; } else { $tmpsmtps = "smtp"; }
							$smtps = $tmpsmtps; 
							$serverport = $relay["port"]; 
							$servername = $relay["servername"]; 
							$type = "dns-txt";
						}
					}
					if($value["type"] == "dns-sub") { $valid = true; $smtps = _CRON_SUB_AS_RELAY_PROT_; $serverport = _CRON_SUB_AS_RELAY_PORT_; $servername = _CRON_SUB_AS_RELAY_SUB_.".".$value["domain"]; $type = "dns-sub";}
					
			if(mrod_relay_id_exists($mysql, $value["fk_relay"])) { 
							$valid = true; 
							$relay = mrod_relay_get($mysql, $value["fk_relay"]);
							if($relay["smtps"] == 1) { $tmpsmtps = "smtps:"; } else { $tmpsmtps = "smtp:"; }
							$smtps = $tmpsmtps; 
							$serverport = $relay["port"]; 
							$servername = $relay["servername"]; 
							$type = "dns-relay";			
			}						
					
					
					if($value["type"] == "usr") { 
						if(mrod_relay_id_exists($mysql, $value["fk_relay"])) { 
							$valid = true; 
							$relay = mrod_relay_get($mysql, $value["fk_relay"]);
							if($relay["smtps"] == 1) { $tmpsmtps = "smtps"; } else { $tmpsmtps = "smtp"; }
							$smtps = $tmpsmtps; 
							$serverport = $relay["port"]; 
							$servername = $relay["servername"]; 
							$type = "usr-relay";
						} else {
							if($value["ovr_smtps"] == 1) { $tmpsmtps = "smtps"; } else { $tmpsmtps = "smtp"; }
							$smtps = $tmpsmtps; 
							$valid = true; 
							if(@trim($value["ovr_serverport"]) != "") { $serverport = $value["ovr_serverport"]; }
							if(@trim($value["ovr_servername"]) != "") { $servername = $value["ovr_servername"]; }
							$type = "usr-ovr";
						}							
					}		
					

					
				if(@trim($value["ovr_servername"]) != "") { $servername = $value["ovr_servername"]; } 
				if(@trim($value["ovr_serverport"]) != "") { $serverport = $value["ovr_serverport"];  }		
		
				if($value["ovr_smtps"] == 1) { $smtps = "smtps";  }
				elseif($value["ovr_smtps"] == 0 AND is_numeric($value["ovr_smtps"])) { $smtps = "smtp";  }
				else {  }		
				if(trim($servername) == "" OR trim($smtps) == "" OR trim($serverport) == "") { $valid = false;}
					// If Valid Write to Build String
						if($valid) {
							$buildstring_transport .= $value["domain"]." ".$smtps.":".$servername.":".$serverport."\r\n";
							$buildstring_relay .= $value["domain"]." OK"."\r\n";
							logging_add("OK: Valid Domain with type '".$type."': '".htmlspecialchars(trim($value["domain"]))."'\r\n");
						} else {
							logging_add("ERROR: Cant Determine Domain Specifications of: '".htmlspecialchars(trim($value["domain"]))."'\r\n");
						}
				}
			}
			
			logging_add(" \r\n");
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//// Build the Files for Postfix			
			logging_add("INFO: This will be the transportmaps file: "._CRON_TRANSMAP_." \r\n");
			logging_add($buildstring_transport);
			if(file_exists(_CRON_TRANSMAP_)) { @unlink(_CRON_TRANSMAP_); }
			$file = fopen(_CRON_TRANSMAP_, "w") or die("\r\n\r\n### Error! Unable to write file "._CRON_TRANSMAP_."! ");
			if($file) {@fwrite($file, $buildstring_transport);	
			
				logging_add("OK: File Written: "._CRON_TRANSMAP_." \r\n");
				@fclose($file);			
			} else { logging_add("ERROR: File Could not be written: "._CRON_RELAYMAP_." \r\n"); }
			 logging_add(" \r\n");
			
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//// Build the Files for Postfix			
			logging_add("INFO: This will be the relaymaps file: "._CRON_RELAYMAP_." \r\n");
			logging_add($buildstring_relay);
			if(file_exists(_CRON_RELAYMAP_)) { @unlink(_CRON_RELAYMAP_); }
			$file = fopen(_CRON_RELAYMAP_, "w") or die("\r\n\r\n### Error! Unable to write file "._CRON_RELAYMAP_."! ");
			if($file) {@fwrite($file, $buildstring_relay);	
			
				logging_add("OK: File Written: "._CRON_RELAYMAP_." \r\n \r\n");
				@fclose($file);
			} else { logging_add("ERROR: File Could not be written: "._CRON_RELAYMAP_." \r\n \r\n"); }
			
			

	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// Execute Shell Operations
		shell_exec("/usr/sbin/postmap "._CRON_RELAYMAP_.";");
		shell_exec("/usr/sbin/postmap "._CRON_TRANSMAP_.";");
		shell_exec("systemctl restart postfix");	
	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// Logging and Finish
		logging_add("OK: Execution Done at ".date("Y-m-d H:m:i")."\r\n");
		$log->info($log_output); exit();	