<?php
	// Configurations Include
/*		$path	=	"/var/www/html/bxc";
		require_once($path."/config.php");

		if( $config["dns_fetchmode"] == 1 ) {$pathtobindfile	=	_MODE_1_NAMED_PATH;} // Named.conf file Location
		else {	$pathtobindfile	=	_MODE_2_CACHE_PATH;} // Directory of cache files named by domains

		if($config["autodelete"]) {mysqli_query($mysql, "DELETE FROM ".$config_mysql_table_relay." WHERE sourceexec <> 'man' AND (ovrservername IS NULL OR ovrservername = '')");}

	// Fetch Mode 1 From Content of NAMED.CONF.LOCAL
		if( $config["dns_fetchmode"] == 1 ) {
		$handle = fopen($pathtobindfile, "r");
		if ($handle) {
			while (($line = fgets($handle)) !== false) {
				$pos = strpos($line, "zone ");
				$pos1 = strpos($line, ".arpa");
				if ($pos === false) {} 
				else {
					if ($pos1 === false) {
						$str = $line;
						$finalservername	=	null;
						$finalserverport	=	null;
						$finalserverid		=	$config["fallbackserverid"];
						$finalserversource	=	"fallback";
						
						preg_match('/"(.*?)"/', $str, $match);
							
						if($config["txts"]) {
							@ob_start();
							@passthru("/usr/bin/dig ".$match[1]." TXT");
							@$lookup = @ob_get_contents();
							@ob_end_clean();
								
							if(strpos(@$lookup, $config["txtlayout"]) > -1) {$ssssss = substr($lookup, strpos($lookup, $config["txtlayout"])+strlen($config["txtlayout"]), 1 );} 
							if(!empty($ssssss) AND is_numeric($ssssss)) {
								$finalservername	=	"";
								$finalserverport	=	"";
								$finalserverid		=	$ssssss;
								$finalserversource	=	"txtentrie";								
							}
						}							
						if(!empty(trim($match[1]))) {
						@mysqli_query($mysql, "INSERT INTO ".$config_mysql_table_relay."(domain, serverid, userid, sourceexec, ovrservername, ovrserverport)
						VALUES('".$match[1]."', '".$finalserverid."', '0', '".$finalserversource."', '".$finalservername."', '".$finalserverport."');");	}
					}
				}
			}
			fclose($handle);
			echo $config_servername." ### SUCCESSFULLY FETCHED DOMAINS FROM NAMED.CONF! "; 
		} else { echo "### ERROR OPEN THE FILE FOR BIND DOMAINS NAMED.CONF "; } 		
} else {
	// Fetch Mode 2 FROM FILESNAMES IN BIND CACHE DIR
		if ($handle = opendir($pathtobindfile)) {
			while (false !== ($entry = readdir($handle))) {
				if ($entry != "." && $entry != "..") {
					$pos1 = strpos($entry, "_default.nzd");
					$pos2 = strpos($entry, "keys.bind");
					if ($pos1 === false AND $pos2 === false) {						
						$finalservername	=	null;
						$finalserverport	=	null;
						$finalserverid		=	$config["fallbackserverid"];
						$finalserversource	=	"fallback";
						
						if($config["txts"]) {
							@ob_start();
							@passthru("/usr/bin/dig ".$entry." TXT");
							@$lookup = @ob_get_contents();
							@ob_end_clean();
								
							if(strpos(@$lookup, $config["txtlayout"]) > -1) {$ssssss = substr($lookup, strpos($lookup, $config["txtlayout"])+strlen($config["txtlayout"]), 1 );} 
							if(!empty($ssssss) AND is_numeric($ssssss)) {
								$finalservername	=	"";
								$finalserverport	=	"";
								$finalserverid		=	$ssssss;
								$finalserversource	=	"txtentrie";								
							}
						}
						if(!empty(trim($entry[1]))) {
						@mysqli_query($mysql, "INSERT INTO ".$config_mysql_table_relay."(domain, serverid, userid, sourceexec, ovrservername, ovrserverport)
						VALUES('".$entry."', '".$finalserverid."', '0', '".$finalserversource."', '".$finalservername."', '".$finalserverport."');");	}			
					}	
				}
			}
			closedir($handle);
			echo $config_servername." ### CACHE FILES FROM BIND CACHE DIRECTORY SUCCESSFULLY FETCHED! ";
		} else {echo $config_servername." ### NOTHING FOUND TO FETCH ERROR! ";} 
}

// Build Maps
	// Variables
		$buildstring_transport = "";
		$buildstring_relay = "";

		$result = mysqli_query($mysql, "SELECT * FROM `".$config_mysql_table_relay."`") or die(mysqli_error($mysql));
		while ($vars = mysqli_fetch_array($result, MYSQLI_BOTH)) {			
			$query11	=	"SELECT * FROM `".$config_mysql_table_server."` WHERE id = ".$vars["serverid"].""; 
			$result11 = mysqli_query($mysql, $query11) or die(mysqli_error($mysql));
			while ($vars11 = mysqli_fetch_array($result11, MYSQLI_BOTH)) {
				$servername = $vars11["servername"];
				$serverport = $vars11["port"];
			}
			
			if(!empty($vars["ovrservername"])) {$servername = $vars["ovrservername"];}
			if(!empty($vars["ovrserverport"])) {$serverport = $vars["ovrserverport"];}			
			
			if(true) {
				if($serverport == 25) {$buildstring_transport .= $vars["domain"]." smtp:".$servername.":".$serverport."\n";} 
				else {$buildstring_transport .= $vars["domain"]." smtp:".$servername.":".$serverport."\n";}
				$buildstring_relay .= $vars["domain"]." OK"."\n";
			}
		}

	// Transport Maps File Processing
		@unlink($path."/workfiles/transportmaps");
		$myfile = fopen($path."/workfiles/transportmaps", "w") or die("### Unable to open file /workfiles/transportmaps! ");
		@fwrite($myfile, $buildstring_transport);
		echo $buildstring_transport;
		@fclose($myfile);
		
	// relaydomains File Processing	
		@unlink($path."/workfiles/relaydomains");
		$myfile = fopen($path."/workfiles/relaydomains", "w") or die("Unable to open file Relay!");
		@fwrite($myfile, $buildstring_relay);
		echo $buildstring_relay;
		@fclose($myfile);	

	// Message for Cron
		echo $config_servername."\r\n New Postmap Files written!\r\n";
?>
