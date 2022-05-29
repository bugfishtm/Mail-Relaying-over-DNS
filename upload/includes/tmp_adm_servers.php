<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if ($_SESSION['tracker_csrf'] == $_POST['csrf']) {
		if (isset($_POST['delete'])) { mysqli_query($mysql, "DELETE FROM `"._TABLE_SERVERS_."` WHERE id = \"".$_POST["id"]."\";"); } 
		if (isset($_POST['updateuser'])) { mysqli_query($mysql, "UPDATE "._TABLE_SERVERS_." SET servername = '".$_POST["username"]."' WHERE id = \"".$_POST["id"]."\";"); } 	
		if (isset($_POST['updatepass'])) { mysqli_query($mysql, "UPDATE "._TABLE_SERVERS_." SET port = '".$_POST["password"]."'	WHERE id = \"".$_POST["id"]."\";"); } 					
		if (isset($_POST['add'])) { mysqli_query($mysql, "INSERT INTO "._TABLE_SERVERS_." (servername, port) VALUES (\"".$_POST["username"]."\" ,\"".$_POST["password"]."\");"); } 		
	}
} $csrftoken	=	mt_rand(100000,9999999); $_SESSION['tracker_csrf'] = $csrftoken;
$query = "SELECT * FROM `"._TABLE_SERVERS_."` ORDER BY id DESC LIMIT 15 ";

	echo '<table>';
		$result = mysqli_query($mysql, $query) or die(mysqli_error($mysql));
		
		echo '<tr><td>Hostname</td><td>Port</td><td>Connection</td><td>Delete</td></tr>';
		echo '<form method="post"><tr>';
			echo "<td><input name='username'  type='text'></td>";
			echo "<td><input name='password'  type='text'></td>";
			echo "<input name='csrf' type='hidden' value='".$csrftoken."'>";
			echo "<td><input name='add' type='submit' value='ADD'></td>";
		echo '</tr></form>';			
		
		while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
			echo '<form method="post"><tr>';
				echo "<input type='hidden' name='id' value='".$row["id"]."'>";
				echo "<td><input name='username' style='width: 80% !important;'  type='text' value='".$row["servername"]."'>";
				echo "<input name='updateuser' type='submit' style='width: 20% !important;' value='C'></td>";
				
				echo "<td><input name='password' style='width: 80% !important;' type='text' value='".$row["port"]."'>";
				echo "<input name='updatepass' style='width: 20% !important;' type='submit' value='C'></td>";

				echo "<input name='csrf' type='hidden' value='".$csrftoken."'>";
				
				if(@checkSMTPNow($row["servername"], $row["port"])) { echo "<td>OK</td>"; } else { echo "<td>ERR</td>";  }
				echo "<td><input name='delete' style='width: 100% !important;' type='submit' value='DEL'></td>";
				
			echo '</tr></form>';
		}
	echo '</table>';
?>