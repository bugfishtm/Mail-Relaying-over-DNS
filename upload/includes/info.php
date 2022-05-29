<fieldset>
	<?php
		echo 'Servername: '._SERVERNAME_.'<br />';
		echo 'Cookiename: '._COOKIES_.'<br />';
	?>
</fieldset>
<fieldset>
	<?php
		echo 'mrdns_userid: '.$_SESSION["mrdns_userid"].'<br />';
		echo 'mrdns_username: '.$_SESSION["mrdns_username"].'<br />';
		echo 'mrdns_rank: '.$_SESSION["mrdns_rank"].'<br />';
	?>
</fieldset>
<fieldset>
	<?php
		echo 'DNS MODE: '._MODE_.'<br />';
		echo 'DNS MODE 1: '._MODE_1_NAMED_PATH_.'<br />';
		echo 'DNS MODE 2: '._MODE_2_CACHE_PATH_.'<br /><br />';
		echo 'DNS CLEANUP: '._CLEANUP_.'<br /><br />';
		echo 'Postfix Relaymaps: '._CRON_RELAYMAP_.'<br />';
		echo 'Postfix Transportmaps: '._CRON_TRANSMAP_.'<br /><br />';
		echo 'DNS CLEANUP: '._CLEANUP_.'<br />';
		echo 'DNS DOMAIN USE AS SERVER: '._USE_DOMAINS_AS_SERVER_.'<br />';
		echo 'DNS DOMAIN PORTS: '._USE_DOMAINS_AS_SERVER_PORT_.'<br />';
		echo '<br />';
		echo 'DNS FALLBACK: '._FALLBACK_ENABLE_.'<br />';
		echo 'DNS FALLBACK HOST: '._FALLBACK_HOST_.'<br />';
		echo 'DNS FALLBACK PORT: '._FALLBACK_PORT_.'<br />';
	?>
</fieldset>
<fieldset>	
	<?php
	$tries22 = mysqli_fetch_array(mysqli_query($mysql, "SELECT MAX(tries) AS tries FROM "._TABLE_USERS_.""));
	echo "Current Fail Login Count: ".$tries22["tries"]." <br />";
	echo "Block Limit: "._LOGIN_MAXTRIES_." <br />";
	echo "Enabled: "._LOGIN_ENABLE_BLOCKING_." <br />";
	?>
	<a href="./?colocation=info&reset=yes">Reset Login Counter</a><br />
<?php
	if(@$_GET["reset"] == "yes") {
		mysqli_query($mysql, "UPDATE "._TABLE_USERS_." SET tries = 1");
}?>
</fieldset>	
<fieldset>	
	<?php
	echo "Session Block: "._LOGIN_ENABLE_BLOCKING_SESSION_." <br />";
	echo "Session Block Limit: "._LOGIN_MAXTRIES_BLOCKING_SESSION_." <br />";
?>
<?php
	if(@$_GET["reset"] == "yes") {
		mysqli_query($mysql, "UPDATE "._TABLE_USERS_." SET tries = 1");
	}?>
	
</fieldset>
<fieldset>	
	<a href="./?&colocation=info&reset=deleteall">CAUTION --- Delete All Domains --- CAUTION</a>	
	<?php  if(@$_GET["reset"] == "deleteall") mysqli_query($mysql, "DELETE FROM "._TABLE_DOMAINS_); ?>
</fieldset>