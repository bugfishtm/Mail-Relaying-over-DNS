<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') { if ($_SESSION['tracker_csrf'] == $_POST['csrf']) {
    if (isset($_POST['delete'])) { mysqli_query($mysql, "DELETE FROM `"._TABLE_USERS_."` WHERE id = \"".$_POST["id"]."\";"); mysqli_query($mysql, "DELETE FROM `"._TABLE_DOMAINS_."` WHERE userid = \"".$_POST["id"]."\";"); } 
    if (isset($_POST['updateuser'])) { mysqli_query($mysql, "UPDATE "._TABLE_USERS_." SET user = '".$_POST["username"]."' WHERE id = \"".$_POST["id"]."\";"); } 	
    if (isset($_POST['updatepass'])) { mysqli_query($mysql, "UPDATE "._TABLE_USERS_." SET pass = '".password_hash($_POST["password"],PASSWORD_BCRYPT)."' WHERE id = \"".$_POST["id"]."\";"); } 		
    if (isset($_POST['updaterank'])) { mysqli_query($mysql, "UPDATE "._TABLE_USERS_." SET rank = '".$_POST["rank"]."' WHERE id = \"".$_POST["id"]."\";"); } 				
    if (isset($_POST['add'])) {
        mysqli_query($mysql, "INSERT INTO "._TABLE_USERS_." (user, pass, rank) 
													VALUES (\"".$_POST["username"]."\"
													 ,\"".password_hash($_POST["password"],PASSWORD_BCRYPT)."\"
													 ,\"".$_POST["rank"]."\");");
    } 		
} } $csrftoken	=	mt_rand(100000,9999999);$_SESSION['tracker_csrf'] = $csrftoken;

	$query = "SELECT * FROM `"._TABLE_USERS_."` ORDER BY id DESC LIMIT 15 ";	
?>

	<fieldset>admin: Can manage User, can Manage MX Servers, Can add Domains and see All</fieldset>
	<fieldset>user: Can add Domains for himself and see own</fieldset>
	<?php

	echo '<table>';
	
		
		$result = mysqli_query($mysql, $query) or die(mysqli_error($mysql));

				echo '<tr>';
					echo "<td>USER</td>";
					echo "<td>PASS</td>";
					echo "<td>RANK</td>";
					echo "<td>DEL</td>";
				echo '</tr>';		
				
				echo '<form method="post">';
				echo '<tr >';
					echo "<input type='hidden' name='id'>";
					echo "<td><input name='username'  type='text'></td>";
					echo "<td><input name='password'  type='text'></td>";
					echo '<td>  <select name="rank">
									<option value="admin">admin</option>
									<option value="user">user</option>
								  </select></td>';
					echo "<input name='csrf' type='hidden' value='".$csrftoken."'>";
					echo "<td><input name='add' type='submit' value='add'></td>";
				echo '</tr>';
				echo '</form>';			
		
		while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
				echo '<form method="post">';
				echo '<tr >';
					echo "<input type='hidden' name='id' value='".$row["id"]."'>";
					echo "<td><input name='username' style='width: 80% !important;'  type='text' value='".$row["user"]."'>";
					echo "<input name='updateuser' type='submit' style='width: 20% !important;' value='C'></td>";
					
					echo "<td><input name='password' style='width: 80% !important;' type='text' value='xxx'>";
					echo "<input name='updatepass' style='width: 20% !important;' type='submit' value='C'></td>";
					
					echo '<td>  <select name="rank">
									<option value="'.$row['rank'].'">'.$row['rank'].'</option>
									<option value="admin">admin</option>
									<option value="user">user</option>
								  </select>';
					echo "<input name='updaterank' style='width: 20% !important;' type='submit' value='C'></td>";					
					

					echo "<input name='csrf' type='hidden' value='".$csrftoken."'>";
					
					echo "<td><input name='delete' style='width: 100% !important;' type='submit' value='DEL'></td>";
				echo '</tr>';
				echo '</form>';
		}
	echo '</table><br /><br />';
 ?>