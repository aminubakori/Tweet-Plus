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
			$query = "SELECT * FROM users WHERE oauth_uid = '$user_info->id'";
			$query = sqlite_query($dbconnect,$query);
			$data = sqlite_fetch_array($query, SQLITE_ASSOC);
			if(sqlite_num_rows($query) < 0) {
		        $query = "INSERT INTO users (oauth_uid, username, oauth_token, oauth_secret) VALUES ({$user_info->id}, '{$user_info->screen_name}', '{$access_token['oauth_token']}', '{$access_token['oauth_token_secret']}')";
				$query = sqlite_query($dbconnect,$query);

				$query = "SELECT * FROM users WHERE id = '". last_insert_rowid()."'";
				$query = sqlite_query($dbconnect,$query);
				$data = sqlite_fetch_array($query, SQLITE_ASSOC);
			}else {
				// Update the tokens
        		$query = "UPDATE users SET oauth_token = '{$access_token['oauth_token']}', oauth_secret = '{$access_token['oauth_token_secret']}' AND oauth_uid = {$user_info->id}";
        		$query = sqlite_query($dbconnect,$query);
			}
			print_r($data);
			$_SESSION['id'] = $data['id'];
		    $_SESSION['username'] = $data['username'];
		    $_SESSION['oauth_uid'] = $data['oauth_uid'];
		    $_SESSION['oauth_token'] = $data['oauth_token'];
		    $_SESSION['oauth_secret'] = $data['oauth_secret'];
		 	sqlite_close($dbconnect);
		    //header("Location:".Base_Url."/user.php");
		}
	} else {
	    // Something's missing, go back to square 1
	    header("Location:".Base_Url."/login.php");
	}
?>