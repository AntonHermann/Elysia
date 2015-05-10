<?php
	session_start();
	include 'intern/settings.php';
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
        
		<title>Elysia Fotoblog - Kontakt + Impressum</title>
		<link rel="stylesheet" type="text/css" href="css/style.css" />
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	</head>
	<body>
		<header id="pageheader">
			<div id="banner">
				<a href="/">
					<img id="logo" src="img/logo.png" alt="Elysia Logo: Walnuss" />
					<h1>Elysia</h1>
				</a>
				<?php if (isset($_SESSION['id'])) { ?>
				<div id="logonmessage"><span><?=$_SESSION['name']?></span>, <a id="logofflink" href="intern/logoff.php">Abmelden</a></div>
				<?php } else { ?>
				<a id="logonlink" href="intern/logon.php">Anmelden</a>
				<?php } ?>
			</div>
		</header>
		<div id="content">
			<h1>Kontakt</h1>
			<address class="vcard" itemscope itemtype="http://schema.org/Person">
				<span class="fn" itemprop="name">Anton O.</span><br />
				<a class="email" href="mailto:response.anton@gmx.de" itemprop="email">response.anton@gmx.de</a>
			</address>
			<h2 style="font-size: 1.17em;margin: 1em 0;">Haftungsbestimmungen</h2>
			<!--<p>
			Die Haftung der Inhalte liegt allein bei den Autoren der jeweiligen Texte (Posts & Kommentare)<br />
			Ebenso hafte ich nicht f&uuml;r verlinkte Inhalte, die Haftung liegt dabei bei den jeweiligen Seitenbetreibern<br />
			bzw. unterliegt deren Haftungsbestimmungen.
			</p>-->
			<p style="line-height: 1.5;">
			Die Haftung der Bilder liegt allein bei den Rechtsinhabern, bzw. bei denjenigen, welche die Bilder bereitstellen. (Alle Bilder stammen von externen Quellen)
			</p>
		</div>
		<?php include_once("intern/analyticstracking.php") ?>
	</body>
</html>