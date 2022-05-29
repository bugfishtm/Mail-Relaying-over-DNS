<?php
	session_start();
	require_once("./config.php");
	if(_LOGIN_ENABLE_BLOCKING_) {
		$tries1 = mysqli_fetch_array(mysqli_query($mysql, "SELECT MAX(tries) AS tries FROM "._TABLE_USERS_.""));
		if($tries1["tries"] >= _LOGIN_MAXTRIES_) { 
			echo "<html><head><title>MRDNS: Page is blocked!</title><link rel='stylesheet' href='./style.css'></head><body><div id='mrdns_error'>This page is currently blocked!</div></body></html>";
			exit();} }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
    "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html version="-//W3C//DTD XHTML 1.1//EN"
      xmlns="http://www.w3.org/1999/xhtml" xml:lang="en"
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:schemaLocation="http://www.w3.org/1999/xhtml
                          http://www.w3.org/MarkUp/SCHEMA/xhtml11.xsd">
  <head>
	<!-- Meta Tags For Site --> 
	<title>MRDNS: <?php echo _SERVERNAME_; ?></title>	 
	<!-- Meta Tags For Site -->
		<meta http-equiv="content-Type" content="text/html; utf-8" />
		<meta http-equiv="Pragma" content="no-cache" />
		<meta http-equiv="content-Language" content="en" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="icon" type="image/x-icon" href="./favicon.ico">
		<meta name="audience" content="all" />
		<meta name="expires" content="0" />
		<meta name="robots" content="noindex, nofollow" />
		<link rel="stylesheet" href="./style.css">
  </head>
  <body> 
	<?php if (@$_SESSION["mrdns_status"] != "true") { 
				if (!isset($_SESSION[_COOKIES_."sessionlogin"])) {$_SESSION[_COOKIES_."sessionlogin"] = 0;}
				if ($_SESSION[_COOKIES_."sessionlogin"] > _LOGIN_MAXTRIES_BLOCKING_SESSION_ AND _LOGIN_ENABLE_BLOCKING_SESSION_) {
					echo '<div id="mrdns_error">You are banned! Please try again later...</div>'; } else {

					if(isset($_POST["username"]) AND isset($_POST["password"])) {
							$wiki_sql2	=	"SELECT * FROM "._TABLE_USERS_." WHERE user = \"".mysqli_real_escape_string($mysql, htmlspecialchars($_POST["username"]))."\" 
							";
							$wiki_r2	=	mysqli_query($mysql, $wiki_sql2);
							while($bxc_f2=mysqli_fetch_array($wiki_r2)){
									if ( $bxc_f2["user"] == htmlspecialchars($_POST["username"]) AND password_verify($_POST["password"], $bxc_f2["pass"])) {
										$_SESSION["mrdns_status"] 	= "true"; 
										$_SESSION["mrdns_userid"]	=	$bxc_f2["id"];
										$_SESSION["mrdns_username"]	=	$bxc_f2["user"];
										$_SESSION["mrdns_rank"]		=	$bxc_f2["rank"];
										 echo '<meta http-equiv="refresh" content="0; url=./">'; 
										 exit();
									}									
								}	
							 $_SESSION[_COOKIES_."sessionlogin"] = $_SESSION[_COOKIES_."sessionlogin"] + 1;
							 $tries = mysqli_fetch_array(mysqli_query($mysql, "SELECT MAX(tries) AS tries FROM "._TABLE_USERS_.""));
							 $tries = $tries["tries"]+1;
							 mysqli_query($mysql, "UPDATE "._TABLE_USERS_." SET tries = '".$tries."'");
					} ?>
					<div id="mrdns_login">
						<form method="post">
							<input type="text" name="username" placeholder="Username" >
							<input type="password" name="password" placeholder="Password">
							<input type="submit" value="login" id="loginonlogintracking">
						</form>
					</div>
		<?php  } } else { ?>	
		<div id="mrdns_nav">
			<?php if($_SESSION["mrdns_rank"] == "admin") { ?>Admin: <a href="./?colocation=info" <?php if(@$_GET["colocation"] == "info") { echo 'id="nac_active"'; } ?> >Info</a><a href="./?colocation=admin_usermgr" <?php if(@$_GET["colocation"] == "admin_usermgr") { echo 'id="nac_active"'; } ?>>User-Management</a><a href="./?colocation=admin_domains" <?php if(@$_GET["colocation"] == "admin_domains") { echo 'id="nac_active"'; } ?>>All-Domains</a><a href="./?colocation=listadmin" <?php if(@$_GET["colocation"] == "listadmin") { echo 'id="nac_active"'; } ?>>Mail-Servers</a><br /><?php }	?>
			User: <a href="./?colocation=stats" <?php if(@$_GET["colocation"] == "stats" || empty($_GET["colocation"])) { echo 'id="nac_active"'; } ?>>My-Domains</a><a href="./?colocation=user_modify" <?php if(@$_GET["colocation"] == "user_modify") { echo 'id="nac_active"'; } ?>>Change-Password</a><a href="./?colocation=logout">Logout</a>		
		</div>	
		<div id="mrdns_content">
			<?php
				switch($_GET["colocation"]) {
					case "user_modify":  require_once("./includes/tmp_user_changedata.php"); break;								
					case "admin_usermgr": 				
							if($_SESSION["mrdns_rank"] == "admin") {
								require_once("./includes/tmp_adm_users.php");
							} else { echo '<meta http-equiv="refresh" content="0; url=./">'; exit(); }
						break;	
					case "admin_domains": 				
							if($_SESSION["mrdns_rank"] == "admin") {
								require_once("./includes/tmp_adm_domains_all.php");
							} else { echo '<meta http-equiv="refresh" content="0; url=./">'; exit(); }
						break;		
					case "info": 				
							if($_SESSION["mrdns_rank"] == "admin") {
								require_once("./includes/info.php");
							} else { echo '<meta http-equiv="refresh" content="0; url=./">'; exit(); }
						break;		
					case "listadmin": 				
							if($_SESSION["mrdns_rank"] == "admin") {
								require_once("./includes/tmp_adm_servers.php");
							} else { echo '<meta http-equiv="refresh" content="0; url=./">'; exit(); }
						break;							
					case "logout": unset($_SESSION['mrdns_status']);unset($_SESSION['mrdns_rank']);unset($_SESSION['mrdns_userid']); unset($_SESSION['mrdns_username']); 
						echo '<meta http-equiv="refresh" content="0; url=./">';	break;		
					default: require_once("./includes/tmp_user_domains.php");	
				};
			 ?>
		</div> <?php } ?>  
 	<div id="mrdns_footer">Mail Relaying over DNS by <a href="https://bugfishtm.de" target="_blank" rel="noopeener">Bugfish</a></div>
  </body>
</html>