<?php
	if(!isset($_SESSION)){
		ob_start();
		session_start();
	}
?>
<?php include_once("config/config.php"); ?>
<?php include_once("twitteroauth/twitteroauth/twitteroauth.php"); ?>
<?php
	if(!empty($_GET['oauth_verifier']) && !empty($_SESSION['oauth_token']) && !empty($_SESSION['oauth_token_secret'])){
	    // TwitterOAuth instance, with two new parameters we got in twitter_login.php
		$twitteroauth = new TwitterOAuth('iG6RGp25g6GXHRKSN6XNA', '1a7w4Rmem40pAB0Q2q1nL7cGBT3ykqACJU59E0p84', $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
		// Let's request the access token
		$access_token = $twitteroauth->getAccessToken($_GET['oauth_verifier']);
		// Save it in a session var
		$_SESSION['access_token'] = $access_token;
		// Let's get the user's info
		$user_info = $twitteroauth->get('account/verify_credentials');
		// Print user's info
		/*print_r($user_info);*/

		$dbconnect = sqlite_open(DB, 0666);
		if(isset($user_info->error)){
		    // Something's wrong, go back to square 1
		    header("Location:".Base_Url."/login.php");
		} else {
			$dbconnect = sqlite_open(DB, 0666);
			$query1 = "SELECT * FROM users WHERE oauth_uid = $user_info->id LIMIT 1";
			$query1 = sqlite_query($dbconnect,$query1);
			$data = sqlite_fetch_array($query1, SQLITE_ASSOC);
			if(sqlite_num_rows($query1) <= 0) {
		        $query2 = "INSERT INTO users (id, oauth_uid, username, oauth_token, oauth_secret) VALUES (null, {$user_info->id}, '{$user_info->screen_name}', '{$access_token['oauth_token']}', '{$access_token['oauth_token_secret']}')";
				$query2 = sqlite_query($dbconnect,$query2);

				$query3 = "SELECT * FROM users ORDER BY id DESC LIMIT 1";
				$query3 = sqlite_query($dbconnect,$query3);
				$data = sqlite_fetch_array($query3, SQLITE_ASSOC);
			}else {
				echo "hi";
				// Update the tokens
        		$query4 = "UPDATE users SET oauth_token = '{$access_token['oauth_token']}', oauth_secret = '{$access_token['oauth_token_secret']}' AND oauth_uid = {$user_info->id}";
        		$query4 = sqlite_query($dbconnect,$query4);
			}
			//var_dump($data);
			$_SESSION['id'] = $data['id'];
		    $_SESSION['username'] = $data['username'];
		    $_SESSION['oauth_uid'] = $data['oauth_uid'];
		    $_SESSION['oauth_token'] = $data['oauth_token'];
		    $_SESSION['oauth_secret'] = $data['oauth_secret'];
		 	sqlite_close($dbconnect);
		    header("Location:".Base_Url."/user.php");
		}
	} else {
	    // Something's missing, go back to square 1
	    header("Location:".Base_Url."/login.php");
	}
?>