<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') { if ($_SESSION['tracker_csrf'] == $_POST['csrf']) {
    if (isset($_POST['delete'])) {
        mysqli_query($mysql, "DELETE FROM `"._TABLE_DOMAINS_."` WHERE id = \"".htmlspecialchars($_POST["id"])."\"  AND userid = '".$_SESSION["mrdns_userid"]."';");
    } 
	
    if (isset($_POST['updateuserport'])) {
        mysqli_query($mysql, "UPDATE "._TABLE_DOMAINS_." SET ovrserverport = '".htmlspecialchars($_POST["ovrportpost"])."' WHERE id = \"".$_POST["id"]."\"  AND userid = '".$_SESSION["mrdns_userid"]."';");
    } 	
	
    if (isset($_POST['updateusername'])) {
        mysqli_query($mysql, "UPDATE "._TABLE_DOMAINS_." SET ovrservername = '".htmlspecialchars($_POST["ovrportpostname"])."' WHERE id = \"".$_POST["id"]."\"  AND userid = '".$_SESSION["mrdns_userid"]."';");
    } 	

    if (isset($_POST['updateuserserver'])) {
        mysqli_query($mysql, "UPDATE "._TABLE_DOMAINS_." SET serverid = '".htmlspecialchars($_POST["serveridseleect"])."' WHERE id = \"".$_POST["id"]."\" AND userid = '".$_SESSION["mrdns_userid"]."';");
    } 	
	
    if (isset($_POST['updatepass'])) {
        mysqli_query($mysql, "UPDATE "._TABLE_DOMAINS_." SET serverid = '".htmlspecialchars($_POST["password"])."' WHERE id = \"".$_POST["id"]."\"  AND userid = '".$_SESSION["mrdns_userid"]."';");
    } 					
	
    if (isset($_POST['add'])) {
       @mysqli_query($mysql, "INSERT INTO "._TABLE_DOMAINS_." (domain, serverid, userid, sourceexec) 
													VALUES (\"".htmlspecialchars($_POST["username"])."\"
													, \"".htmlspecialchars($_POST["password"])."\"
													, \"".$_SESSION["mrdns_userid"]."\"
													 ,\"man\");") or die (mysqli_error($mysql));
    } 		
}} $csrftoken	=	mt_rand(100000,9999999); $_SESSION['tracker_csrf'] = $csrftoken;


	echo "<div id='tracker_listitem' style='background: #242424;width: 30%;'>Domain</div>";
	echo "<div id='tracker_listitem' style='background: #242424;width: 60%;'>Server</div>";
	echo "<div id='tracker_listitem' style='background: #242424;width: 10%;'>Delete</div>";	
	
	$curissue	=	mysqli_query($mysql, "SELECT *	FROM "._TABLE_DOMAINS_." WHERE userid = '".$_SESSION["mrdns_userid"]."'  ORDER BY id DESC");
			
	while ($curissuer	=	mysqli_fetch_array($curissue) ) { 
		$curissue3434		=	mysqli_query($mysql, "SELECT *	FROM "._TABLE_SERVERS_." WHERE id = ".$curissuer["serverid"]." ORDER BY id DESC");
		$curissuer345654	=	mysqli_fetch_array($curissue3434);
		echo "<div id='hovesasdsd' style='padding: 0 0 0 0; margin: 0 0 0 0;width: 100%;'>";echo '<form method="post">';
		echo "<input type='hidden' name='id' value='".$curissuer["id"]."'>";
		echo "<input name='csrf' type='hidden' value='".$csrftoken."'>";	
		echo "<div id='tracker_listitem' style='width: 30%;'>".$curissuer["domain"]."</div>";
		echo "<div id='tracker_listitem' style='width: 60%;'><b>".@$curissuer345654["servername"]." [".$curissuer345654["port"]."]</b><br />";
			echo "<select name='serveridseleect'>";
			$curissue343423	=	mysqli_query($mysql, "SELECT *	FROM "._TABLE_SERVERS_."  ORDER BY id DESC");				
			while ($curissuer11	=	mysqli_fetch_array($curissue343423) ) { 
				echo "<option value='".$curissuer11["id"]."'>".$curissuer11["servername"]." [".$curissuer11["port"]."]</option>";		
			} echo "</select>";
			echo "</div>";	
		echo "<div id='tracker_listitem' style='width: 10%;'><input name='delete' type='submit' value='DEL'><input name='updateuserserver' type='submit' value='C'></div>";
		echo '</form></div><br clear="left">';
	}

	$query = "SELECT * FROM `"._TABLE_DOMAINS_."`  ORDER BY id DESC LIMIT 15 ";
		
	if($config_adns_mode != "2") {
		echo '<table>';
	
		
		$result = mysqli_query($mysql, $query) or die(mysqli_error($mysql));
				

	echo '<form method="post"><tr>';
					echo "<td><input name='username' type='text' placeholder='New Domain'></td>";
					echo "<td><select name='password'>";
					
					$curissue3434	=	mysqli_query($mysql, "SELECT *	FROM "._TABLE_SERVERS_."  ORDER BY id DESC");				
					while ($curissuer	=	mysqli_fetch_array($curissue3434) ) { 
						
						echo "<option value='".$curissuer["id"]."'>".$curissuer["servername"]." ".$curissuer["port"]."</option>";		
					}			
					
					echo "</select></td>";
					echo "<input name='csrf' type='hidden' value='".$csrftoken."'>";
					echo "<td><input name='add' type='submit' value='add'></td>";
	echo '</tr></form><br clear="left">';			
				
		
		echo '</table>';
	}
?>