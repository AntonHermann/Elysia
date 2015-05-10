<?php
	session_start();
	include 'intern/settings.php';
	
	if (!isset($_SESSION['id'])) {
		// Benutzer nicht angemeldet -> Zugriff verweigert!
		$_SESSION['error'] = "accessdenied";
		header("Location: index.php");
		exit("Zugriff verweigert!");
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
		<script src="js/jquery-ui.min.js"></script>
		
		<!-- Magnific Popup core CSS file -->
		<link rel="stylesheet" href="css/magnific.css" />
		<!-- Magnific Popup core JS file -->
		<script src="js/magnific.js"></script>
		
		<script type="text/javascript">
			$(function(){
				$("body").removeClass("no-js").addClass("js");
				
				var sorted = false;
				$("#pics_list").sortable({
					placeholder: "sortable_placeholder",
					axis: "y",
					cursor: "n-resize",
					revert: true,
					handle: "img",
					connectWith: "#trash_list",
					update: function(event, ui){
						sorted = true;
						//alert($("#pics_list").sortable("toArray", {attribute: "data-id"}).toString());
					}
				});
				$("#trash_list").sortable({
					items: "tr:not(.header)",
					placeholder: "sortable_placeholder",
					axis: "y",
					cursor: "n-resize",
					revert: true,
					connectWith: "#pics_list",
					handle: "img"
				});
				$(window).bind('beforeunload', function(){
					if (sorted) {
						return false;
					}
				});
				$("#saveChanges").click(function(){
					if (sorted) {
						var items	= $("#pics_list" ).sortable("toArray", {attribute: "data-id"}).toString();
						var deleted	= $("#trash_list").sortable("toArray", {attribute: "data-id"}).toString();
						$.post("intern/edit.php", {"items": items, "deleted": deleted}).done(function(data){
							sorted = false; //success
							alert("Änderungen erfolgreich!");
							//console.log(data);
							$("#trash_list tr").not(".header").remove();
						}).fail(function() {
							alert("Änderungen fehlgeschlagen!");
						});
					}
				});
				
				<?php if (isset($_SESSION['error']) && $_SESSION['error'] != "") { ?>
					$.magnificPopup.open({
						items: {
							<?php if ($_SESSION['error']=="deletefailure") { ?>
							src: '<div class="error-popup">Beim L&ouml;schen des Fotos ist ein Fehler aufgetreten!</div>',
							<?php } else { ?>
							src: '<div class="error-popup">Ein unbekannter Fehler ist aufgetreten!</div>',
							<?php } ?>
							type: 'inline',
							closeBtnInside: true,
							closeOnContentClick: true
						}
					});
				<?php
						$_SESSION['error'] = "";
					}
				?>
			});
		</script>
	</head>
	<body class="no-js">
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
			<h1>Fotos:</h1>
			<button id="saveChanges">&Auml;derungen speichern</button>
			<table id="pics">
				<colgroup>
					<col id="imgcol" />
				</colgroup>
				<thead>
					<tr>
						<th>Bild</th>
						<th>Name</th>		
						<th>Beschreibung</th>
						<th>Link</th>
						<?php /* <th>&nbsp;</th> */ ?>
					</tr>
				</thead>
				<tbody id="pics_list">
					<?php
						$ergebnis = mysqli_query($mysqli, "SELECT * FROM pics ORDER BY orderid");
						if(mysqli_num_rows($ergebnis) <> 0) {
							$counter = 0;
							
							$name = "";
							$description = "";
							$pictureurl = "";
							$link = "";
							
							while($row = mysqli_fetch_object($ergebnis))	{
								$counter++;
								$name = htmlentities($row->name, ENT_QUOTES | ENT_IGNORE, "ISO-8859-15");
								$description = htmlentities($row->description, ENT_QUOTES | ENT_IGNORE, "ISO-8859-15");
								$pictureurl = htmlentities($row->pictureurl, ENT_QUOTES | ENT_IGNORE, "ISO-8859-15");
								$link = htmlentities($row->link, ENT_QUOTES | ENT_IGNORE, "ISO-8859-15");
					?>
					<tr id="pic-<?=$row->id?>" data-id="<?=$row->id?>">
						<td><img src="<?=$pictureurl?>" alt="<?=$name?>" /></td>
						<td><?=$name?></td>
						<td><?=$description?></td>
						<td><?=$link?></td>
						<?php /*
						<td>
							<div class="deleteFotoButton">
								&#xe609;
								<a class="deleteFotoButton_Del" href="intern/deletefoto.php?id=<?=$row->id?>">L&ouml;schen!</a>
								<span class="deleteFotoButton_Arrow"></span>
							</div>
						</td>
						*/ ?>
					</tr>
					<?php
							}
						}
						mysqli_free_result($ergebnis);
					?>
				</tbody>
				<tbody id="trash_list">
					<tr class="header">
						<th colspan="4">L&ouml;schen</th>
					</tr>
				</tbody>
			</table>
		</div>
		<?php include_once("intern/analyticstracking.php") ?>
	</body>
</html>