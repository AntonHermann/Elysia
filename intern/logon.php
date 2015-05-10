<?php
	session_start();
	include 'settings.php';

	$error = false;
	$ajax = false;
	
	/*function logon() {
		$name = mysqli_real_escape_string($mysqli, $_POST["name"]);
		$password = mysqli_real_escape_string($mysqli, $_POST["password"]);
		$ergebnis = mysqli_query($mysqli, "SELECT * FROM users WHERE name='$name' AND pwd='$password'");
		$row = mysqli_fetch_object($mysqli, $ergebnis);
		if($row->name != '') {
			$_SESSION['id'] = $row->id;
			$_SESSION['name'] = $row->name;
			$_SESSION['error'] = "";
			mysqli_free_result($ergebnis);
			if ($ajax) {
				echo "t"; //true
			} else {
				echo "<p>Sie wurden erfolgreich angemeldet!</p><a href=\"" . $SITE_BASE . "\">Zur&uuml;ck zur Startseite</a>";
			}
		} else {
			mysqli_free_result($ergebnis);
			if ($ajax) {
				echo "f"; //false
			} else {
				echo "<p>Anmelden fehlgeschlagen!</p><a href=\"" . $SITE_BASE . "\">Zur&uuml;ck zur Startseite</a>";
			}
		}
	}
	
	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		//Ajax-Request
		$ajax = true;
	}
	logon();
	*/
	
	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		//Ajax-Request
		$ajax = true;
	}
	
	if (isset($_POST["name"]) && isset($_POST["password"])) {
		$name = mysqli_real_escape_string($mysqli, $_POST["name"]);
		$password = mysqli_real_escape_string($mysqli, $_POST["password"]);
		if (!$ajax) {
			$password = md5($password);
		}
		$ergebnis = mysqli_query($mysqli, "SELECT * FROM users WHERE name='$name' AND password='$password'");
		if ($ergebnis) {
			$row = mysqli_fetch_object($ergebnis);
			if($row->name != '') {
				$_SESSION['id'] = $row->id;
				$_SESSION['name'] = $row->name;
				$_SESSION['error'] = "";
				mysqli_free_result($ergebnis);
				if ($ajax) {
					exit("t"); //true
				} else {
					header("Location: ../index.php");
					exit();
				}
			} else {
				mysqli_free_result($ergebnis);
			}
		}
		// Fehlgeschlagen, da Scriptausführung bei Erfolg abgebrochen wird (Z. 57)
		if ($ajax) {
			exit("f"); //false
		} else {
			$error = true;
		}
	} elseif ($ajax) {
		exit("f"); //false
	}
?>
<!doctype html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
        <?php /* meta-tag to disallow zooming (for mobile page-view): */ ?>
		<meta name="viewport" content="width=device-width, initial-scale=1.0 maximum-scale=1" />
        <?php /* keywords */ ?>
        <meta name="keywords" lang="de" content="Blog, Anton, Elysia, Foto, Fotografie, Bilder" />
		<meta name="author" lang="de" content="Anton O" />
        <meta name="robots" content="Index,Follow" />
        
		<title>Elysia Fotoblog - Anmelden</title>
		<link rel="stylesheet" type="text/css" href="../css/style.css" />
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	</head>
	<body>
		<header id="pageheader">
			<div id="banner">
				<a href="../index.php">
					<img id="logo" src="../img/logo.png" alt="Elysia Logo: Walnuss" />
					<h1>Elysia</h1>
				</a>
				<a href="../contact.php">Kontakt</a>
			</div>
		</header>
		<div id="content">
			<h1>Anmelden</h1>
			<form action="logon.php" method="post" id="logonform">
				<fieldset>
					<ul>
						<?php if($error){ ?>
						<li>
							<span class="errorfield">Benutzername-Passwort-Kombination war falsch. Bitte versuchen Sie es erneut.</span>
						</li>
						<?php } ?>
						<li>
							<label for="namefield">Name</label>
							<input type="text" id="namefield" name="name" placeholder="Name" maxlength="15" required autofocus />
						</li>
						<li>
							<label for="passwordfield">Passwort</label>
							<input type="password" id="passwordfield" name="password" placeholder="Passwort" maxlength="20" required />
						</li>
						<li>
							<input class="mainaction" type="submit" value="Anmelden" />
						</li>
					</ul>
				</fieldset>
			</form>
		</div>
		<?php include_once("analyticstracking.php") ?>
	</body>
</html>