<?php
	session_start();
	include 'intern/settings.php';
	
	if (!isset($_SESSION['id'])) {
		// Benutzer nicht angemeldet -> Zugriff verweigert!
		$_SESSION['error'] = "accessdenied";
		header("Location: index.php");
		exit("Zugriff verweigert!");
	}
	
 	$error = false; //will be set to true if the Database-Insert fails
	$forgotSomething = false; //will be set to true if the user forgot to fill in some required fields
	
	if (isset($_GET["title"]) && isset($_GET["description"]) && isset($_GET["url"])) {
		mysqli_begin_transaction($mysqli);
		
		$title = mysqli_real_escape_string($mysqli, htmlentities($_GET["title"], ENT_QUOTES | ENT_IGNORE, "ISO-8859-15"));
		$description = mysqli_real_escape_string($mysqli, htmlentities($_GET["description"], ENT_QUOTES | ENT_IGNORE, "ISO-8859-15"));
		$link =  mysqli_real_escape_string($mysqli, $_GET["link"]);
		$url = mysqli_real_escape_string($mysqli, $_GET["url"]);
		if ((strpos($url,'dropbox.com') !== false) && (strpos($url,'dl=1') === false)) {
			$url = $url."?dl=1"; //Bild kommt von Dropbox -> ?dl=1 muss hinzugefügt werden, damit das Bild richtig geladen wird. (2. Teil der Abfrage verhindert doppeltes hinzufügen..)
		}
		
		$ergebnis = mysqli_query($mysqli, "INSERT INTO pics (name, description, link, pictureurl) VALUES ('" . $title . "', '" . $description . "', '" . $link . "', '" . $url . "')");
		if ($ergebnis) {
			//Erfolg!
			mysqli_commit($mysqli);
			header("Location: index.php");
		} else {
			//Fehler
			mysqli_rollback($mysqli);
			$error = true;
			$forgotSomething = true; //this will force the given Data to be filled in the form again
		}
		mysqli_free_result($ergebnis);
	} else {
		// nichts gegeben oder nur teilweise gegeben:
		if (isset($_GET["title"]) || isset($_GET["description"]) || isset($_GET["url"])) {
			// nur teilweise angegeben
			$forgotSomething = true;
		} else {
			//nichts angegeben
		}
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
        
		<title>Elysia Fotoblog</title>
		<link rel="stylesheet" type="text/css" href="css/style.css" />
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	</head>
	<body>
		<header id="pageheader">
			<div id="banner">
				<a href="index.php">
					<img id="logo" src="img/logo.png" alt="Elysia Logo: Walnuss" />
					<h1>Elysia</h1>
				</a>
				<a href="contact.php">Kontakt</a>
				<?php if (isset($_SESSION['id'])) { ?>
				<div id="logonmessage"><span><?=$_SESSION['name']?></span>, <a id="logofflink" href="intern/logoff.php">Abmelden</a></div>
				<?php } else { ?>
				<a id="logonlink" href="intern/logon.php">Anmelden</a>
				<?php } ?>
			</div>
		</header>
		<div id="content">
			<h1>Foto hinzuf&uuml;gen:</h1>
			<form action="newfoto.php" method="get" id="newfotoform">
				<fieldset>
					<ul>
						<?php if($error){ ?>
						<li>
							<span class="errorfield">Ein Fehler ist aufgetreten, bitte versuchen Sie es erneut!</span>
						</li>
						<?php } ?>
						<li>
							<label for="titlefield">Titel*</label>
							<input type="text" id="titlefield" name="title" maxlength="15" placeholder="Titel" required <?=(($forgotSomething && isset($_GET["title"]))?"value=\"".$_GET["title"]."\"":"class=\"forgotten\"")?> />
						</li>
						<li>
							<label for="descriptionfield">Beschreibung*</label>
							<input type="text" id="descriptionfield" name="description" placeholder="Beschreibung" required <?=(($forgotSomething && isset($_GET["description"]))?"value=\"".$_GET["description"]."\"":"class=\"forgotten\"")?> />
						</li>
						<li>
							<label for="linkfield">Link</label>
							<input type="url" id="linkfield" name="link" placeholder="http://..." <?=(($forgotSomething && isset($_GET["link"]))?"value=\"".$_GET["link"]."\"":"")?> />
						</li>
						<li>
							<label for="urlfield">Bild-URL*</label>
							<input type="url" id="urlfield" name="url" placeholder="http://..." required <?=(($forgotSomething && isset($_GET["url"]))?"value=\"".$_GET["url"]."\"":"class=\"forgotten\"")?> />
						</li>
						<li>
							Felder mit * m&uuml;ssen ausgef&uuml;llt werden.
						</li>
						<li>
							<button>Vorschau</button>
							<input class="mainaction" type="submit" value="Fertig" />
						</li>
					</ul>
				</fieldset>
			</form>
		</div>
		<?php include_once("intern/analyticstracking.php") ?>
	</body>
</html>