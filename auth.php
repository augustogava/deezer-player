<?php
 
$app_id     = "133913";
$app_secret = "?";
$my_url     = "http://localhost/ws/Works/augustogava/projects/deezer/auth.php";
 
session_start();
$code = $_REQUEST["code"];

//print_r( $_SESSION );
if(empty($code)){
	$_SESSION['state'] = md5(uniqid(rand(), TRUE)); //CSRF protection

	$dialog_url = "https://connect.deezer.com/oauth/auth.php?app_id=".$app_id
		."&redirect_uri=".urlencode($my_url)."&perms=basic_access,email,offline_access,manage_library,manage_community,listening_history"
		."&state=". $_SESSION['state'];
 
	header("Location: ".$dialog_url);
	exit;
 
	}

//print $_REQUEST['state']." == ".$_SESSION['state'];
if($_REQUEST['state'] == $_SESSION['state']) {
	$token_url = "https://connect.deezer.com/oauth/access_token.php?app_id="
	.$app_id."&secret="
	.$app_secret."&code=".$code;
 	print $token_url."  - ";
	$response  = file_get_contents($token_url);
	$params    = null;
	parse_str($response, $params);
print_r($response);
	$api_url   = "https://api.deezer.com/user/me?access_token="
			.$params['access_token'];
 
	$user = json_decode(file_get_contents($api_url));
	echo("Hello " . $user->name);
	print_r($user);
}else{
	echo("The state does not match. You may be a victim of CSRF.");
}
?>
