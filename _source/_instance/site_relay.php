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
if($permsobj->hasPerm($user->user_id, "relay")  OR $permsobj->hasPerm($user->user_id, "relay_own")  OR $user->user_rank == 0) {
if(isset($_POST["exec_edit"]) ) {
	if( trim(@$_POST["hostname"])  == "") { x_eventBoxPrep("Please enter a hostname!", "error", _COOKIES_); goto asd983rt; }
	if( !$csrf->check(@$_POST["csrf"])) { x_eventBoxPrep("CSRF Error - Try Again!", "error", _COOKIES_); goto asd983rt; }
	if( trim(@$_POST["port"])  == "") { x_eventBoxPrep("Please enter a port!", "error", _COOKIES_); goto asd983rt; }
	if(!$permsobj->hasPerm($user->user_id, "relay") AND $user->user_rank != 0) { $ext = " AND fk_user = '".$user->user_id."' "; } else { $ext = ""; }

	if(is_numeric(@$_POST["exec_ref"])) {
		$mysql->query("UPDATE "._TABLE_RELAY_." SET servername = '".$mysql->escape(trim(@$_POST["hostname"]))."' WHERE id = \"".$_POST["exec_ref"]."\" ".@$ext.";");
		$mysql->query("UPDATE "._TABLE_RELAY_." SET port = '".$mysql->escape(trim(@$_POST["port"]))."' WHERE id = \"".$_POST["exec_ref"]."\" ".@$ext.";");
		if(@$_POST["smtps"]) { $smtps = 1; } else  { $smtps = 0 ;}
		$mysql->query("UPDATE "._TABLE_RELAY_." SET smtps = '".$smtps."' WHERE id = \"".$_POST["exec_ref"]."\" ".$ext.";");
		x_eventBoxPrep("Relay has been updated!", "ok", _COOKIES_);	
	} else {											
		if($_POST["smtps"]) { $smtps = 1; } else  { $smtps = 0 ;}
		$mysql->query("INSERT INTO "._TABLE_RELAY_." (servername, port, smtps, fk_user) 
													VALUES (\"".$mysql->escape(trim($_POST["hostname"]))."\"
													, '".$mysql->escape(trim($_POST["port"]))."'
													, '".$smtps."'
													, '".$user->user_id."');");
		x_eventBoxPrep("Relay has been added!", "ok", _COOKIES_);
	}
}asd983rt:

if(isset($_POST["exec_del"]) AND @mrod_relay_id_exists($mysql, @$_POST["exec_ref"])) {
	if(is_numeric($_POST["exec_ref"])) {
		if( !$csrf->check(@$_POST["csrf"])) { x_eventBoxPrep("CSRF Error - Try Again!", "error", _COOKIES_); goto asde983rt; }
		$res = $mysql->select("SELECT * FROM "._TABLE_DOMAIN_." WHERE fk_relay = '".$_POST["exec_ref"]."'", false);
		if(!is_array($res)) {
			if(!$permsobj->hasPerm($user->user_id, "relay") AND $user->user_rank != 0) { $ext = " AND fk_user = '".$user->user_id."' "; } else { $ext = ""; }
			$mysql->query("DELETE FROM `"._TABLE_RELAY_."` WHERE id = \"".$_POST["exec_ref"]."\" ".$ext.";");
			x_eventBoxPrep("Relay has been deleted!", "ok", _COOKIES_);
		} else { x_eventBoxPrep("Relay is in use for different Domains!", "error", _COOKIES_); }
	} 
}asde983rt:
	
	
	echo '<div class="content_box"><a href="./?site=relay&edit=add">Add new Relay</a><br />Here you are able to set up mail-master servers, at which incoming mail to a domain may be forwarded to! For more informations about this take a look at the "<a href="'._HELP_.'" rel="noopener" target="_blank">Help</a>" section of this project!';
		
		if($permsobj->hasPerm($user->user_id, "relay")  OR $user->user_rank == 0) { $curissue	=	mysqli_query($mysql->mysqlcon, "SELECT *	FROM "._TABLE_RELAY_."  ORDER BY id DESC"); }
		else { $curissue	=	mysqli_query($mysql->mysqlcon, "SELECT *	FROM "._TABLE_RELAY_." WHERE fk_user = '".$user->user_id."' ORDER BY id DESC"); }

		while ($curissuer	=	@mysqli_fetch_array($curissue) ) { 
			if($curissuer["smtps"] != 1) { $finalcon =  "smtp"; } else { $finalcon =  "smtps"; }
			echo '<fieldset><legend>Relay-ID: '.@$curissuer["id"].'</legend>';
				echo "<div style='width: 60%;float:left;'>";
					echo "<b>".$finalcon.":".@$curissuer["servername"].":".@$curissuer["port"]."</b><br />";
					echo "Owner: ".mrod_user_get_name_from_id($mysql, $curissuer["fk_user"])."<br />";
					echo "DNS: "._CRON_TXT_TO_RELAY_STRING_.@$curissuer["id"]."<br />";
				echo "</div>";	
				
				echo "<div style='width: 20%;float:left;'>";	
					echo "<a href='./?site=relay&test=".$curissuer["id"]."' style='white-space: nowrap; word-break:keep-all;'>Test-Connection</a>";
					echo "<a href='./?site=relay&edit=".$curissuer["id"]."'>Edit</a>";
					echo "<a href='./?site=relay&delete=".$curissuer["id"]."'>Delete</a>";
				echo "</div>";	
			echo '</fieldset>';	
		}
	
	echo '</div>';
?>	<style>.redomg:hover{color: black !important}</style>
<?php if((@mrod_relay_id_exists($mysql, @$_GET["edit"]) OR @$_GET["edit"] == "add") AND (@mrod_relay_get($mysql, $_GET["edit"])["fk_user"] == $user->user_id OR $user->user_rank == 0  OR @$_GET["edit"] == "add")) { 
		if(@$_GET["edit"] == "add") { $title = "Add new Relay"; } else { $title = "Edit Relay: ".@mrod_relay_get($mysql, $_GET["edit"])["id"]; } ?>
	
	<div class="internal_popup">
		<div class="internal_popup_inner">
			<div class="internal_popup_title"><?php echo $title; ?></div>		
			<form method="post" action="./?site=relay"><div class="internal_popup_content">	<input type="hidden" value="<?php echo @$csrf->get(); ?>" name="csrf">		
				<input type="text" placeholder="Hostname" name="hostname" value="<?php echo @mrod_relay_get($mysql, $_GET["edit"])["servername"]; ?>">
				<input type="number" placeholder="Port" name="port" maxlength="5"  value="<?php echo @mrod_relay_get($mysql, $_GET["edit"])["port"]; ?>">
				<input type="checkbox" name="smtps" <?php if(@mrod_relay_get($mysql, $_GET["edit"])["smtps"] == 1) { echo "checked"; } ?>> Use SMTPS for Relay Settings
				<?php if(is_numeric(@$_GET["edit"])) { ?><input type="hidden" value="<?php echo @$_GET["edit"]; ?>" name="exec_ref"><?php } ?>
			</div>		
			<div class="internal_popup_submit"><input type="submit" value="Execute" style="cursor:pointer;" name="exec_edit"><a href="./?site=relay"class ="redomg">Cancel</a></div></form>
		</div>
	</div>
<?php } ?>
<?php if(@mrod_relay_id_exists($mysql, @$_GET["delete"]) AND (@mrod_relay_get($mysql, $_GET["delete"])["fk_user"] == $user->user_id OR $user->user_rank == 0)) { ?>	
	<div class="internal_popup">
		<form method="post" action="./?site=relay"><div class="internal_popup_inner"><input type="hidden" value="<?php echo @$csrf->get(); ?>" name="csrf">
			<div class="internal_popup_title">Delete Relay-ID: <?php echo mrod_relay_get($mysql, $_GET["delete"])["id"]; ?></div>
			<div class="internal_popup_content">Here you can delete this relay. If you delete an array, domains connected to this relay will set their forwarding values to determined settings, which may differ from your current domains state. Keep this in mind if deleting an array. The domains will not be deleted.</div>
			<div class="internal_popup_submit"><input type="hidden" value="<?php echo @$_GET["delete"]; ?>" name="exec_ref"><input type="submit" style="cursor:pointer;"  value="Execute" name="exec_del"><a href="./?site=relay"class ="redomg">Cancel</a></div>		
		</div></form>
	</div>
<?php } ?>
<?php if(@mrod_relay_id_exists($mysql, @$_GET["test"]) AND (@mrod_relay_get($mysql, $_GET["test"])["fk_user"] == $user->user_id OR $user->user_rank == 0)) { ?>	
	<div class="internal_popup">
		<div class="internal_popup_inner">
			<div class="internal_popup_title">Test Relay-ID: <?php echo @mrod_relay_get($mysql, $_GET["test"])["id"]; ?></div>
			<div class="internal_popup_content"><?php if(mrod_relay_check(@mrod_relay_get($mysql, $_GET["test"])["servername"], mrod_relay_get($mysql, $_GET["test"])["port"])) { echo "SMTP Server is <b>FAILING</b>!"; } else { echo "SMTP Server is <b>OK</b>!"; } ?> </div>
			<div class="internal_popup_submit"><a href="./?site=relay"class ="redomg">Cancel</a></div>		
		</div>
	</div>
<?php }
} else { echo "<div class='content_box'>No Permission!</div>";} ?>