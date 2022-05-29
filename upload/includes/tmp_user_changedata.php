<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if ($_SESSION[_COOKIES_.'csrf'] == $_POST['csrf']) {
		if (isset($_POST['updatepass'])) { mysqli_query($mysql, "UPDATE "._TABLE_USERS_." SET pass = \"".password_hash($_POST["password"],PASSWORD_BCRYPT)."\" WHERE id = ".$_SESSION['mrdns_userid'].";"); } 
		if (isset($_POST['updateuser'])) { mysqli_query($mysql, "UPDATE "._TABLE_USERS_." SET user = \"".mysqli_real_escape_string($mysql, $_POST["username"])."\" WHERE id = ".$_SESSION['mrdns_userid'].";"); } 
	}
}  $csrftoken =	mt_rand(100000,9999999); $_SESSION[_COOKIES_.'csrf'] = $csrftoken; 
   $query = "SELECT * FROM `"._TABLE_USERS_."` WHERE id = ".$_SESSION['mrdns_userid']." ORDER BY id DESC LIMIT 15 ";	

$result = mysqli_query($mysql, $query) or die(mysqli_error($mysql));
while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
		echo '<form method="post">';
			echo "<input name='username' type='text'  value='".$row["user"]."'><br clear='both'/>";
			echo "<input name='updateuser' type='submit' value='Change Username'><br /><br /><br clear='both'/>";
			echo "<input name='password' type='text' value='xxx'><br clear='both'/>";
			echo "<input name='updatepass' type='submit' value='Change Password'><br /><br /><br clear='both'/>";
			echo "<input name='csrf' type='hidden' value='".$csrftoken."'>";
		echo '</form>';
}

if(@$_GET["reset"] == "deleteall") mysqli_query($mysql, "DELETE FROM "._TABLE_DOMAINS_." WHERE userid = '".$_SESSION["mrdns_userid"]."'");
echo '<a href="./?&colocation=user_modify&reset=deleteall">Delete All My Domains</a>';