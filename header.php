<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"https://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="nl" lang="nl">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="description" content="description" />
	<meta name="keywords" content="keywords" />
	<title>CS-4413-Group17-Fall2023</title>
	<link rel="stylesheet" href="style.css" type="text/css">
</head>

<body>
<h2><span><img src="https://staticdelivery.nexusmods.com/mods/3946/images/headers/1106_1679123181.jpg"></span></h2>
<h2><span>CS-4413-Group17-Fall2023</span></h2>
	<div id="wrapper">
	<div id="menu">
		<a class="item" href="index.php">Home</a> -
		<?php /*
			session_start();
			if (isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == true && $_SESSION['user_level'] == 1) {
				echo ' - Admin Functions: <a class="item" href="create_category.php">Create a category</a> -';
			} else {
				echo '<a class="item" href="create_topic.php">Create a topic</a>';
			}
		*/ ?>
		<a class="item" href="create_category.php">Create a category</a> -
		<a class="item" href="create_topic.php">Create a topic</a>
		<?php
			session_start();
			if (isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == true) {
				echo '- <a class="item" href="user_profile.php">User Profile</a>';
			}
		?>
		<div id="userbar">
        <div id="userbar">
			<?php
			session_start();
			
			if (isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == true) {
				echo 'Hello, ' . $_SESSION['user_name'] . '. Not you? <a href="sign_out.php">Sign out</a>';
			} else {
				echo '<a href="sign_in.php">Sign in</a> or <a href="sign_up.php">create an account</a>.';
			}
			?>
        </div>
	</div>
		<div id="content">
		
		<footer>
			<img id="footerimg" src="https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fleosigh.com%2Fwp-content%2Fuploads%2F2022%2F12%2FBocchi-the-Rocks-Nijika-Ijichi.jpg&f=1&nofb=1&ipt=a9e2c1be80a569249480dd710e8f14341a9e55af26c3115dbc852dda61ccf731&ipo=images">
		
		</footer>

	</body>
