<?php
	// Configurations Include
		$path	=	"/var/www/html/";
		require_once($path."/config.php");

	// Cleanup old DNS Based Entries if Activated (by refresh for cleanup)
		if(_CLEANUP_) {mysqli_query($mysql, "DELETE FROM "._TABLE_DOMAINS_." WHERE sourceexec <> 'man'");}

	#####################################################################
	##### Fetch Mode 1 From Content of NAMED.CONF.LOCAL #################
	#####################################################################
		if( _MODE_ == "named" ) { $handle = fopen(_MODE_1_NAMED_PATH_, "r"); if ($handle) {
			while (($line = fgets($handle)) !== false) { if (strpos($line, "zone ") === false) {} else { if (strpos($line, ".arpa") === false) {						
				preg_match('/"(.*?)"/', $line, $match);
				$arx["domain"] = trim($match[1]);						
				$arx = cron_fallback($arx);
				if(!empty(trim($arx["domain"]))) {
				@mysqli_query($mysql, "INSERT INTO "._TABLE_DOMAINS_."(domain, userid, sourceexec, ovrservername, ovrserverport)
				VALUES('".$arx["domain"]."', '0', '".$arx["src"]."', '".$arx["host"]."', '".$arx["port"]."');");}
			} } }
			fclose($handle);echo _SERVERNAME_.": Fetched DNS Entries from "._MODE_1_NAMED_PATH_."\r\n\r\n";
		} else {  echo _SERVERNAME_.": FAILED TO FETCH DNS Entries from "._MODE_1_NAMED_PATH_."\r\n ERR ERR ERR ERR ERR ERR ERR  \r\n\r\n"; } } 
	
	#####################################################################
	##### Fetch Mode 2 From Content of NAMED.CONF.LOCAL #################
	#####################################################################	
	if( _MODE_ == "cache" ) {
		// Fetch Mode 2 FROM FILESNAMES IN BIND CACHE DIR
			if ($handle = opendir(_MODE_2_CACHE_PATH_)) {
				while (false !== ($entry = readdir($handle))) {
					if ($entry != "." && $entry != "..") {
						if (strpos($entry, "_default.nzd") === false AND strpos($entry, "keys.bind") === false) {	
						
							$arx["domain"] = trim($entry);						
							$arx = cron_fallback($arx);
					
							if(!empty(trim($entry[1]))) {
							@mysqli_query($mysql, "INSERT INTO "._TABLE_DOMAINS_."(domain, userid, sourceexec, ovrservername, ovrserverport)
							VALUES('".$arx["domain"]."', '0', '".$arx["src"]."', '".$arx["host"]."', '".$arx["port"]."');");	}			
						}	
					}
				}
				closedir($handle);
				echo _SERVERNAME_.": Fetched DNS Entries from "._MODE_2_CACHE_PATH_."\r\n\r\n";
			} else { echo _SERVERNAME_.": FAILED TO FETCH DNS Entries from "._MODE_2_CACHE_PATH_."\r\n ERR ERR ERR ERR ERR ERR ERR  \r\n\r\n";} 
	}

	// Build Maps
		// Variables
			$buildstring_transport = "";
			$buildstring_relay = "";
			
			$result = mysqli_query($mysql, "SELECT * FROM `"._TABLE_DOMAINS_."`") or die(mysqli_error($mysql));
			while ($vars = mysqli_fetch_array($result, MYSQLI_BOTH)) {	
				$servername = false;
				$serverport = false;
				
				$query11	=	"SELECT * FROM `"._TABLE_SERVERS_."` WHERE id = '".$vars["serverid"]."'"; 
				$result11 = mysqli_query($mysql, $query11) or die(mysqli_error($mysql));
				while ($vars11 = mysqli_fetch_array($result11, MYSQLI_BOTH)) {
					$servername = $vars11["servername"];
					$serverport = $vars11["port"];
				}
				
				if(!is_string($servername)) { $servername = $vars["ovrservername"]; }
				if(!is_numeric($serverport)) { $serverport = $vars["ovrserverport"]; }
				
				if($serverport == 25) { $buildstring_transport .= $vars["domain"]." smtp:".$servername.":".$serverport."\n"; } 
				else { $buildstring_transport .= $vars["domain"]." smtp:".$servername.":".$serverport."\n"; }
				$buildstring_relay .= $vars["domain"]." OK"."\n";
			}

		// Transport Maps File Processing
			@unlink(_CRON_TRANSMAP_);
			$myfile = fopen(_CRON_TRANSMAP_, "w") or die("\r\n\r\n### ERR ERR Unable to open file "._CRON_TRANSMAP_."! ");
			if($myfile) {@fwrite($myfile, $buildstring_transport);
			echo "\r\n\r\nTransportmaps: \r\n\r\n".$buildstring_transport."\r\n\r\n";
			@fclose($myfile);}
			
		// relaydomains File Processing	
			@unlink(_CRON_RELAYMAP_);
			$myfile = fopen(_CRON_RELAYMAP_, "w") or die("\r\n\r\n### ERR ERR Unable to open file "._CRON_RELAYMAP_."! ");
			if($myfile) {@fwrite($myfile, $buildstring_relay);
			echo $buildstring_relay;
			@fclose($myfile);}

		// Message for Cron
			echo "\r\nTransportmaps/Relaydomains File successfully written! x)\r\n";
	?>
