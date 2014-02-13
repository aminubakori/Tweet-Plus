<?php
	if(!isset($_SESSION)){
		ob_start();
		session_start();
	}
?>
<?php include_once("config/config.php"); ?>
<?php
	if(!isset($_SESSION['username'])) header("Location:".Base_Url."/login.php");
?>
<!doctype html>
<html>
	<head>
		<link rel="stylesheet" href="<?php echo Base_Url; ?>/css/bootstrap.css">
		<script type="text/javascript" src="<?php echo Base_Url; ?>/js/jquery-1.9.0.min.js"></script>
		<script type="text/javascript" src="<?php echo Base_Url; ?>/js/bootstrap.js"></script>
		<style type="text/css">
			footer {
				margin-top: 20px;
			}
		</style>
		<link href="<?php echo Base_Url; ?>/ico/favicon.png" rel="shortcut icon">
		<title>Tweet+</title>
	</head>
	<body>
		<header>
			<nav class="navbar navbar-inverse" role="navigation">
				<div class="navbar-header">
					<a class="navbar-brand" href="<?php echo Base_Url; ?>/home">Tweet+</a>
				</div>
			</nav>
		</header>

		<section class="container">
	    	<h1>Hello <?php echo (!empty($_SESSION['username']) ? '@' . $_SESSION['username'] : 'Guest'); ?></h1>
	    	<p>This is a small twitter client application written in PHP and SQLite.</p>

		</section>

	    <footer>
	    	
	    </footer>
	</body>
</html>