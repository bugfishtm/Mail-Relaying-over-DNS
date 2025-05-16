<style>.keepredblack:hover{color: black !important;}</style><?php
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
if(!$permsobj->hasPerm($user->user_id, "domains") AND !$permsobj->hasPerm($user->user_id, "perm_domain_own") AND $user->user_rank != 0) { echo "<div class='content_box'>No Permission!</div>"; } else {
if(isset($_POST["exec_edit"])) {
	if(!$csrf->check($_POST["csrf"])) { x_eventBoxPrep("CSRF Error - Try Again!", "error", _COOKIES_); goto asdse87duhn;}
	if(!$permsobj->hasPerm($user->user_id, "perm_domain") AND $user->user_rank != 0) { $ext = " AND fk_user = '".$user->user_id."' ";$ext = ""; } else { $ext = ""; }
	if(mrod_domain_id_exists($mysql, @$_POST["exec_ref"])) {
			if(trim($_POST["domain"]) == "" AND @mrod_domain_get($mysql, @$curissuer["id"])["type"] == "usr") { x_eventBoxPrep("Domain Name cant be emtpy!", "error", _COOKIES_); goto asdse87duhn; }
			if(trim($_POST["ovr_host"]) == "" AND !mrod_relay_id_exists($mysql, @$_POST["fk_relay"]) AND @mrod_domain_get($mysql, @$curissuer["id"])["type"] == "usr") { x_eventBoxPrep("Relay-Host Name cant be emtpy, if there is no relay choosen!", "error", _COOKIES_); goto asdse87duhn; }
			if(trim($_POST["ovr_port"]) == "" AND !mrod_relay_id_exists($mysql, @$_POST["fk_relay"]) AND @mrod_domain_get($mysql, @$curissuer["id"])["type"] == "usr") { x_eventBoxPrep("Relay-Port Name cant be emtpy, if there is no relay choosen!", "error", _COOKIES_); goto asdse87duhn; }
			
			
			if(is_string(@$_POST["ovr_host"])) { $mysql->query("UPDATE "._TABLE_DOMAIN_." SET ovr_servername = '".htmlspecialchars($_POST["ovr_host"])."' WHERE id = \"".$_POST["exec_ref"]."\" ".$ext.";"); }
			else { $mysql->query("UPDATE "._TABLE_DOMAIN_." SET ovr_servername = NULL WHERE id = \"".$_POST["exec_ref"]."\" ".$ext.";"); }
			
			if(is_numeric(@$_POST["ovr_port"])) { $mysql->query("UPDATE "._TABLE_DOMAIN_." SET ovr_serverport = '".htmlspecialchars($_POST["ovr_port"])."' WHERE id = \"".$_POST["exec_ref"]."\" ".$ext.";"); }
			else { $mysql->query("UPDATE "._TABLE_DOMAIN_." SET ovr_serverport = NULL WHERE id = \"".$_POST["exec_ref"]."\" ".$ext.";"); }
		if(@$_POST["ovr_smtps"]) { $ovr_smtps = 1; } else { $ovr_smtps = "NULL"; }
		if(@$_POST["ovr_smtp"]) { $ovr_smtps = 0; }			
			$mysql->query("UPDATE "._TABLE_DOMAIN_." SET ovr_smtps = $ovr_smtps WHERE id = \"".$_POST["exec_ref"]."\" ".$ext.";");
			$mysql->query("UPDATE "._TABLE_DOMAIN_." SET fk_relay = \"".$_POST["fk_relay"]."\" WHERE id = \"".$_POST["exec_ref"]."\" ".$ext.";");
			
			x_eventBoxPrep("Domain has been updated!", "ok", _COOKIES_);
	} else {
		if(trim($_POST["domain"]) == "") { x_eventBoxPrep("Domain Name cant be emtpy!", "error", _COOKIES_); goto asdse87duhn; }
		if(trim($_POST["ovr_host"]) == "" AND !mrod_relay_id_exists($mysql, @$_POST["fk_relay"])) { x_eventBoxPrep("Relay-Host Name cant be emtpy, if there is no relay choosen!", "error", _COOKIES_); goto asdse87duhn; }
		if(!is_numeric($_POST["ovr_port"]) AND !mrod_relay_id_exists($mysql, @$_POST["fk_relay"])) { x_eventBoxPrep("Relay-Port must be set, if there is no relay choosen!!", "error", _COOKIES_); goto asdse87duhn; }
		if(mrod_relay_id_exists($mysql, @$_POST["fk_relay"])) { $fk_relay = $_POST["fk_relay"]; } else { $fk_relay = "NULL"; } 
		if(trim(@$_POST["ovr_host"]) != "")  { $ovr_host = $_POST["ovr_host"]; } 
		if(trim(@$_POST["ovr_port"]) != "")  { $ovr_port = $_POST["ovr_port"]; } else { $ovr_port = "NULL"; }
		if(@$_POST["ovr_smtps"]) { $ovr_smtps = 1; } else { $ovr_smtps = 0; }
		if(@$_POST["ovr_smtp"]) { $ovr_smtps = 0; }
													
		$mysql->query("INSERT INTO "._TABLE_DOMAIN_." (domain, fk_relay, fk_user, type, ovr_servername, ovr_serverport, ovr_smtps) 
													VALUES (\"".$mysql->escape(trim($_POST["domain"]))."\"
													, ".$fk_relay."
													, ".$user->user_id."
													, \"usr\"
													, \"".$mysql->escape($ovr_host)."\"
													, ".$mysql->escape($ovr_port)."
													, ".$mysql->escape($ovr_smtps).");");
		x_eventBoxPrep("Domain has been added!", "ok", _COOKIES_);
	}
} 

if(isset($_POST["exec_del"])) {
	if(!$csrf->check($_POST["csrf"])) { x_eventBoxPrep("CSRF Error - Try Again!", "error", _COOKIES_); goto asdse87duhn;}
	if(is_numeric($_POST["exec_ref"])) {
		if(!$permsobj->hasPerm($user->user_id, "perm_domain") AND $user->user_rank != 0) { $ext = " AND fk_user = '".$user->user_id."' "; $ext = ""; } else { $ext = ""; }
		$mysql->query("DELETE FROM "._TABLE_DOMAIN_." WHERE id = \"".htmlspecialchars($_POST["exec_ref"])."\" ".$ext.";");
		x_eventBoxPrep("Domain has been deleted!", "ok", _COOKIES_);
	} 
} asdse87duhn:
	
	echo '<div class="content_box"><a href="./?site=domains&edit=add">Add new Domain</a><br /> Here you will see added domains by cronjobs or manually. If an email comes in at one of these domains, it will be relayed to the corrospoding Relay: X. You can override auto-fetched values here, or connect a relay with a domain. For more informations about this see "<a href="'._HELP_.'" rel="noopener" target="_blank">Help</a>".';

		
		$curissue	=	mysqli_query($mysql->mysqlcon, "SELECT *	FROM "._TABLE_DOMAIN_." ORDER BY id DESC"); 
		//$curissue	=	mysqli_query($mysql->mysqlcon, "SELECT *	FROM "._TABLE_DOMAIN_." WHERE type LIKE '%dns%' ORDER BY id DESC"); 

		while ($curissuer	=	mysqli_fetch_array($curissue) ) { 
 		$fetch = false; 
				$finalhost = "";  
				$finalport = ""; 	
				$finalcon =  "";
				$finalhostx = ""; 
				$finalportx = ""; 	
				$finalconx =  "";				
		if($curissuer["type"] == "dns-txt") {
			if($getrelay = mrod_relay_get($mysql, @$curissuer["fk_relay"])) {  
				$finalhost = $getrelay["servername"]; 
				$finalport = $getrelay["port"]; 	
				if($getrelay["smtps"] != 1) { $finalcon =  "smtp:"; } else { $finalcon =  "smtps:"; }
				$fetch = true;
			}
		}
		if($curissuer["type"] == "dns-sub") {
				$finalhost = _CRON_SUB_AS_RELAY_SUB_.".".@$curissuer["domain"]; 
				$finalport = _CRON_SUB_AS_RELAY_PORT_; 	
				$finalcon =  _CRON_SUB_AS_RELAY_PROT_.":";
				$fetch = true;
		}
		if($curissuer["type"] == "dns-dom") {
				$finalhost = @$curissuer["domain"]; 
				$finalport = _CRON_DOMAIN_AS_RELAY_PORT_; 	
				$finalcon =  _CRON_DOMAIN_AS_RELAY_PROT_.":";
				$fetch = true;				
		}
			if(mrod_relay_id_exists($mysql, $curissuer["fk_relay"])) { 
							$fetch = true; 
							$relay = mrod_relay_get($mysql, $curissuer["fk_relay"]);
							if($relay["smtps"] == 1) { $tmpsmtps = "smtps:"; } else { $tmpsmtps = "smtp:"; }
							$smtps = $tmpsmtps; 
							$finalconx = $tmpsmtps; 
							$relprot = $tmpsmtps; 
							$serverport = $relay["port"]; 
							$reserverport = $relay["port"]; 
							$finalportx = $relay["port"]; 
							$servername = $relay["servername"]; 
							$finalhostx = $relay["servername"]; 
							$reservername = $relay["servername"]; 
							$type = $curissuer["type"];			
			}			
			if(@mrod_domain_get($mysql, $curissuer["id"])["ovr_smtps"] == 1) { $finalcon1 =  "smtps:"; }
			elseif(@mrod_domain_get($mysql, $curissuer["id"])["ovr_smtps"] == 0 AND is_numeric(mrod_domain_get($mysql, $curissuer["id"])["ovr_smtps"])) { $finalcon1 =  "smtp:"; }
			else  { $finalcon1 =  ""; }
			
			if(trim($finalcon1) == "") { $finalconx = $finalcon;} else {  $finalconx = $finalcon1;}
			if(trim(@mrod_domain_get($mysql, @$curissuer["id"])["ovr_servername"]) == "") { $finalhostx = $finalhost;} else {  $finalhostx = @mrod_domain_get($mysql, @$curissuer["id"])["ovr_servername"]; }
			if(trim(@mrod_domain_get($mysql, @$curissuer["id"])["ovr_serverport"]) == "") { $finalportx = $finalport;} else {  $finalportx = @mrod_domain_get($mysql, @$curissuer["id"])["ovr_serverport"]; }
					if($curissuer["type"] == "usr") { 
						if(mrod_relay_id_exists($mysql, $curissuer["fk_relay"])) { 
							$fetch = true; 
							$relay = mrod_relay_get($mysql, $curissuer["fk_relay"]);
							if($relay["smtps"] == 1) { $tmpsmtps = "smtps:"; } else { $tmpsmtps = "smtp:"; }
							$smtps = $tmpsmtps; 
							$relprot = $tmpsmtps; 
							$finalconx = $tmpsmtps; 
							$finalportx = $relay["port"]; 
							$reserverport = $relay["port"]; 
							$finalhostx = $relay["servername"]; 
							$reservername = $relay["servername"]; 
							$type = $curissuer["type"];
						} else {
							if($curissuer["ovr_smtps"] == 1) { $tmpsmtps = "smtps:"; } else { $tmpsmtps = "smtp:"; }
							$smtps = $tmpsmtps; 
							$finalconx = $tmpsmtps; 
							$fetch = true; 
							$finalportx = $curissuer["ovr_serverport"]; 
							$finalhostx = $curissuer["ovr_servername"]; 
							$type = $curissuer["type"];
						}							
					}


			if(!$fetch) { $border = "style='border: 2px solid red !important; background: rgba(255,0,0,0.4); !important; color: white !important;'"; }
			echo '<fieldset><legend>'.@$curissuer["domain"].'</legend>';
				echo "<div style='width: 70%;float:left;'>";
					echo "<b>Relay:</b> ".$finalconx.$finalhostx.":".$finalportx."<br />";
					if($curissuer["type"] == "usr") { 
						echo "<b>Type: </b> <font color='lightblue'>User created Domain</font>";	
					} else { 
						echo "<b>Type:</b> <font color='lime'>Auto-Fetched Domain</font>";	
					}
				echo "</div>";
				

				
				echo "<div style='width: 30%;float:left;'>";	
					//echo "<a href='./?site=domains&assign=".$curissuer["id"]."'>Assign</a>";
					echo "<a href='./?site=domains&info=".$curissuer["id"]."'>Info</a>";
					echo "<a href='./?site=domains&edit=".$curissuer["id"]."'>Edit</a>";
					echo "<a href='./?site=domains&delete=".$curissuer["id"]."'>Delete</a>";
				echo "</div>";	
			echo '</fieldset>';	
		}
	
	echo '</div>';

 if(mrod_domain_id_exists($mysql, @$_GET["edit"]) OR @$_GET["edit"] == "add") { 
		if(@$_GET["edit"] == "add") { $title = "Add new Domain"; } else { $title = "Edit Domain: ".mrod_domain_get($mysql, $_GET["edit"])["domain"]; } ?>
	
	<div class="internal_popup">
		<div class="internal_popup_inner">
			<div class="internal_popup_title"><?php echo $title; ?></div>		
			<form method="post" action="./?site=domains"><div class="internal_popup_content"><input type="hidden" name="csrf" value="<?php echo $csrf->get(); ?>">

				It is needed to set a domain name for mail-relaying!
				<input type="text" placeholder="Domain-Name" name="domain" value="<?php echo mrod_domain_get($mysql, $_GET["edit"])["domain"]; ?>">
				Here you can set a Server which is set up in the "relay" section. This setting may gets overwritten when using the relay-host and port overwritte setting at the bottom.
				To make this relay active the settings below this setting needs to be cleared... <b>Current Choosen Relay-id: <?php echo mrod_domain_get($mysql, $_GET["edit"])["fk_relay"]; ?></b>
			<?php
						echo "<select name='fk_relay'>";
						$curissue343423	=	mysqli_query($mysql->mysqlcon, "SELECT *FROM "._TABLE_RELAY_." ORDER BY id DESC");	
					if(@$_GET["edit"] != "add") { echo "<option value='".mrod_domain_get($mysql, $_GET["edit"])["fk_relay"]."'>No Change</option>";}
					echo "<option value='0'>No Relay</option>";												
						while ($curissuer11	=	mysqli_fetch_array($curissue343423) ) { 
							echo "<option value='".$curissuer11["id"]."'>".$curissuer11["servername"]." ".@$curissuer11["port"]."</option>";		
						} echo "</select>";			
			
			?>
				If the type is the domain is "usr", it is urgent to set settings below, or choose a relay (because there will be no pre-determined settings for user created domains as there is for auto-fetched domains) This setting below does always overwritte all existing settings on a domain and act as a override.
				<input type="text" placeholder="Relay-Host" name="ovr_host" value="<?php echo @mrod_domain_get($mysql, $_GET["edit"])["ovr_servername"]; ?>">
				<input type="number" placeholder="Relay-Port" name="ovr_port" value="<?php echo @mrod_domain_get($mysql, $_GET["edit"])["ovr_serverport"]; ?>">
				<input type="checkbox" name="ovr_smtps" <?php if(@mrod_domain_get($mysql, $_GET["edit"])["ovr_smtps"] == 1) { echo "checked"; } ?>> Use SMTPS Override
				<input type="checkbox" name="ovr_smtp" <?php if(@mrod_domain_get($mysql, $_GET["edit"])["ovr_smtps"] == 0 AND is_numeric(@mrod_domain_get($mysql, $_GET["edit"])["ovr_smtps"])) { echo "checked"; } ?>> Use SMTP Override
				<?php if(is_numeric(@$_GET["edit"])) { ?><input type="hidden" value="<?php echo @$_GET["edit"]; ?>" name="exec_ref"><?php } ?>
			</div>		
			<div class="internal_popup_submit"><input type="submit" value="Execute" style="cursor:pointer;" name="exec_edit"><a href="./?site=domains" class="keepredblack">Cancel</a></div></form>
		</div>
	</div>
<?php } ?>
<?php if(mrod_domain_id_exists($mysql, @$_GET["delete"])) { ?>	
	<div class="internal_popup">
		<form method="post" action="./?site=domains"><div class="internal_popup_inner"><input type="hidden" name="csrf" value="<?php echo $csrf->get(); ?>">
		<?php if(is_numeric(@$_GET["delete"])) { ?><input type="hidden" value="<?php echo @$_GET["delete"]; ?>" name="exec_ref"><?php } ?>
			<div class="internal_popup_title">Delete: <?php echo @mrod_domain_get($mysql, $_GET["delete"])["domain"]; ?></div>
			<div class="internal_popup_content">Auto-Fetched Domains may will be re-created on cronjob run, user domains will be persistent deleted if they are deleted here.</div>
			<div class="internal_popup_submit"><input type="submit" value="Execute" style="cursor:pointer;" name="exec_del"><?php if(is_numeric(@$_GET["delete"])) { ?><input type="hidden" value="<?php echo @$_GET["delete"]; ?>" name="exec_ref"><?php } ?><a href="./?site=domains" class="keepredblack">Cancel</a></div>		
		</div></form>
	</div>
<?php }
 if(mrod_domain_id_exists($mysql, @$_GET["info"])) { 
		$curissuer = mrod_domain_get($mysql, @$_GET["info"]);
 		$fetch = false;
				$finalhost = ""; 
				$finalport = ""; 	
				$finalcon =  "";
				$finalhostx = ""; 
				$finalportx = ""; 	
				$finalconx =  "";				
		if($curissuer["type"] == "dns-txt") {
			if($getrelay = mrod_relay_get($mysql, @$curissuer["fk_relay"])) {  
				$finalhost = $getrelay["servername"]; 
				$finalport = $getrelay["port"]; 	
				if($getrelay["smtps"] != 1) { $finalcon =  "smtp:"; } else { $finalcon =  "smtps:"; }
				$fetch = true;
			}
		}
		if($curissuer["type"] == "dns-sub") {
				$finalhost = _CRON_SUB_AS_RELAY_SUB_.".".@$curissuer["domain"]; 
				$finalport = _CRON_SUB_AS_RELAY_PORT_; 	
				$finalcon =  _CRON_SUB_AS_RELAY_PROT_.":";
				$fetch = true;
		}
		if($curissuer["type"] == "dns-dom") {
				$finalhost = @$curissuer["domain"]; 
				$finalport = _CRON_DOMAIN_AS_RELAY_PORT_; 	
				$finalcon =  _CRON_DOMAIN_AS_RELAY_PROT_.":";
				$fetch = true;				
		} $reserverport = "";  $reservername = ""; $relprot = "";
			if(mrod_relay_id_exists($mysql, $curissuer["fk_relay"])) { 
							$fetch = true; 
							$relay = mrod_relay_get($mysql, $curissuer["fk_relay"]);
							if($relay["smtps"] == 1) { $tmpsmtps = "smtps:"; } else { $tmpsmtps = "smtp:"; }
							$smtps = $tmpsmtps; 
							$relprot = $tmpsmtps; 
							$serverport = $relay["port"]; 
							$reserverport = $relay["port"]; 
							$servername = $relay["servername"]; 
							$reservername = $relay["servername"]; 
							$type = $curissuer["type"];			
			}			
			if(@mrod_domain_get($mysql, $curissuer["id"])["ovr_smtps"] == 1) { $finalcon1 =  "smtps:"; }
			elseif(@mrod_domain_get($mysql, $curissuer["id"])["ovr_smtps"] == 0 AND is_numeric(mrod_domain_get($mysql, $curissuer["id"])["ovr_smtps"])) { $finalcon1 =  "smtp:"; }
			else  { $finalcon1 =  ""; }
			
			if(trim($finalcon1) == "") { $finalconx = $finalcon;} else {  $finalconx = $finalcon1;}
			if(trim(@mrod_domain_get($mysql, @$curissuer["id"])["ovr_servername"]) == "") { $finalhostx = $finalhost;} else {  $finalhostx = @mrod_domain_get($mysql, @$curissuer["id"])["ovr_servername"]; }
			if(trim(@mrod_domain_get($mysql, @$curissuer["id"])["ovr_serverport"]) == "") { $finalportx = $finalport;} else {  $finalportx = @mrod_domain_get($mysql, @$curissuer["id"])["ovr_serverport"]; }
					if($curissuer["type"] == "usr") { 
						if(mrod_relay_id_exists($mysql, $curissuer["fk_relay"])) { 
							$fetch = true; 
							$relay = mrod_relay_get($mysql, $curissuer["fk_relay"]);
							if($relay["smtps"] == 1) { $tmpsmtps = "smtps:"; } else { $tmpsmtps = "smtp:"; }
							$smtps = $tmpsmtps; 
							$relprot = $tmpsmtps; 
							$finalconx = $tmpsmtps; 
							$finalportx = $relay["port"]; 
							$reserverport = $relay["port"]; 
							$finalhostx = $relay["servername"]; 
							$reservername = $relay["servername"]; 
							$type = $curissuer["type"];
						} else {
							if($curissuer["ovr_smtps"] == 1) { $tmpsmtps = "smtps:"; } else { $tmpsmtps = "smtp:"; }
							$smtps = $tmpsmtps; 
							$finalconx = $tmpsmtps; 
							$fetch = true; 
							$finalportx = $curissuer["ovr_serverport"]; 
							$finalhostx = $curissuer["ovr_servername"]; 
							$type = $curissuer["type"];
						}							
					}
 ?>	
	<div class="internal_popup">
		<form method="post" action="./?site=domains"><div class="internal_popup_inner"><input type="hidden" name="csrf" value="<?php echo $csrf->get(); ?>">
			<div class="internal_popup_title">Info: <?php echo @mrod_domain_get($mysql, $_GET["info"])["domain"]; ?></div>
			<div class="internal_popup_content">
			<b>Creation:</b> <?php echo @mrod_domain_get($mysql, $_GET["info"])["creation"]; ?><br />
			<b>Last Modification:</b> <?php echo @mrod_domain_get($mysql, $_GET["info"])["modification"]; ?><br />
			<b>Type:</b> <?php echo @mrod_domain_get($mysql, $_GET["info"])["type"]; ?><br />
			<b>Determined Auto-DNS Domain:</b> <?php echo "".$finalcon.$finalhost.":".@$finalport.""; ?><br />
			<b>Choosen Relay Server Domain:</b> <?php echo "".$relprot.$reservername.":".$reserverport.""; ?><br />
			<b>Overrided Domain:</b>  <?php echo "".$finalcon1.@mrod_domain_get($mysql, $curissuer["id"])["ovr_servername"].":".@mrod_domain_get($mysql, @$curissuer["id"])["ovr_serverport"].""; ?><br />
			<b>Final Used Relay Domain: </b><?php echo "".$finalconx.$finalhostx.":".$finalportx.""; ?><br />
			
			
			</div>
			<div class="internal_popup_submit"><a href="./?site=domains" class="keepredblack" >Cancel</a></div>		
		</div></form>
	</div>
<?php }
}