<?php
	if(!isset($_SESSION)){
		ob_start();
		session_start();
	}
?>
<?php include_once("config/config.php"); ?>
<?php include_once("twitteroauth/twitteroauth/twitteroauth.php"); ?>
<?php
	// The TwitterOAuth instance
	$twitteroauth = new TwitterOAuth('iG6RGp25g6GXHRKSN6XNA', '1a7w4Rmem40pAB0Q2q1nL7cGBT3ykqACJU59E0p84');
	// Requesting authentication tokens, the parameter is the URL we will be redirected to
	$request_token = $twitteroauth->getRequestToken('http://localhost.com/Tweet-Plus/login_oauth.php');
	 
	// Saving them into the session
	$_SESSION['oauth_token'] = $request_token['oauth_token'];
	$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
	 
	// If everything goes well..
	if($twitteroauth->http_code==200){
	    // Let's generate the URL and redirect
	    $url = $twitteroauth->getAuthorizeURL($request_token['oauth_token']);
	    header('Location: '. $url);
	} else {
	    // It's a bad idea to kill the script, but we've got to know when there's an error.
	    die('Something wrong happened.');
	}
?>