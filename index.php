<?php
	session_start();
	include 'intern/settings.php';
	# comment
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
		
		<!-- Magnific Popup core CSS file -->
		<link rel="stylesheet" href="css/magnific.css" />
		<!-- Magnific Popup core JS file -->
		<script src="js/magnific.js"></script>
		
		<script type="text/javascript">
			$.extend(true, $.magnificPopup.defaults, {
				tClose: 'Schlie&szlig;en (Esc)', // Alt text on close button
				tLoading: 'L&auml;dt...', // Text that is displayed during loading. Can contain %curr% and %total% keys
				gallery: {
					tPrev: 'Vorheriges (Linke Pfeiltaste)', // Alt text on left arrow
					tNext: 'N&auml;chstes (Rechte Pfeiltaste)', // Alt text on right arrow
					tCounter: '%curr% von %total%' // Markup for "1 of 7" counter
				},
				image: {
					tError: '<a href="%url%">Das Bild</a> konnte nicht geladen werden.' // Error message when image could not be loaded
				},
				ajax: {
					tError: '<a href="%url%">Der Inhalt</a> konnte nicht geladen werden.' // Error message when ajax request failed
				}
			});
			$(function(){
				$('#pics li').not("#newfoto, #editfotos").find("a").magnificPopup({
					type: 'image',
					closeBtnInside: false,
					closeOnContentClick: false,
					//fixedContentPos: true,
					mainClass: 'mfp-with-zoom',
					image: {
						verticalFit: true,
						titleSrc: function(item) {
							return item.el.attr('title') + ((item.el.data('link')=="")? "" : " <wbr /><a class='mfp-link' href='"+item.el.data('link')+"'>"+item.el.data('link')+"</a>");
						},
						markup:
							'<div class="mfp-figure">'+
								'<div class="mfp-close"></div>'+
								'<div class="mfp-img"></div>'+
								'<div class="mfp-bottom-bar">'+
									'<div class="mfp-title"></div>'+
									'<div class="mfp-counter"></div>'+
								'</div>'+
							'</div>', // Popup HTML markup. `.mfp-img` div will be replaced with img tag, `.mfp-close` by close button

					},
					gallery: {
						enabled: true,
						navigateByImgClick: true,
						preload: [0,1], // Will preload 0 - before current, and 1 after the current image
					},
					zoom: {
						enabled: true,
						duration: 300 // don't foget to change the duration also in CSS
					},
					callbacks: {
						change: function() {
							$('#pics li a img').css("visibility", "visible");
							$('#pics li a img').eq($.magnificPopup.instance.index).css("visibility", "hidden");
						},
						close: function() {
							$('#pics li a img').css("visibility", "visible");
						}
					}
				});
				
				<?php if (isset($_SESSION['error']) && $_SESSION['error'] != "") { ?>
					$.magnificPopup.open({
						items: {
							<?php if($_SESSION['error']=="accessdenied") { ?>
							src: '<div class="error-popup">Der Zugriff zu dieser Seite wurde Ihnen verweigert.<br /><a href="intern/logon.php" class="logonlink">Bitte melden Sie sich an!</a></div>',
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
	<body>
		<header id="pageheader">
			<div id="banner">
				<a href="index.php">
					<img id="logo" src="img/logo.png" alt="Elysia Logo: Walnuss" />
					<h1>Elysia</h1>
				</a>
				<a href="contact.php">Kontakt</a>
				<?php if (isset($_SESSION['id'])) { ?>
				<div id="logonmessage">Angemeldet als: <span><?=$_SESSION['name']?></span>, <a id="logofflink" href="intern/logoff.php">Abmelden</a></div>
				<?php } else { ?>
				<a id="logonlink" href="intern/logon.php">Anmelden</a>
				<?php } ?>
			</div>
		</header>
		<div id="content">
			<h1>Fotos:</h1>
			<ul id="pics">
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
				<li itemscope itemtype="http://schema.org/ImageObject">
					<a href="<?=$pictureurl?>" title="<?=$description?>" data-link="<?=$link?>" data-id="<?=$row->id?>">
						<meta itemprop="name" content="<?=$name?>" />
						<meta itemprop="description" content="<?=$description?>" />
						<meta itemprop="url" content="<?=$link?>" />
						<img src="<?=$pictureurl?>" alt="<?=$name?>" itemprop="contentUrl" />
					</a>
				</li>
				<?php
						}
						if (isset($_SESSION['id'])) {
				?>
				<li id="newfoto">
					<a href="newfoto.php" title="Neues Foto hinzuf&uuml;gen">+</a>
				</li>
				<li id="editfotos">
					<a href="editfotos.php" title="Bearbeiten">
						<svg width="240px" height="240px">
							<path fill="#cccccc" stroke-width="0.2" stroke-linejoin="round" d="m156.27299,80.2514l3.476,3.476c4.319,4.3196 4.319,11.3228 0,15.6424l-6.08299,6.0829l-19.119,-19.1184l6.08301,-6.08289c4.31999,-4.3196 11.323,-4.3196 15.64299,0l0,0zm-53.01029,75.6046l-19.1184,-19.119l46.9267,-46.9264l19.118,19.1181l-46.92629,46.9273zm-20.85641,-12.166l13.90411,13.90399l-19.2983,5.39401l5.3942,-19.298z" id="svg_2"/>						
						</svg>
					</a>
				</li>
				<?php
						}
					} else {
				?>
				<li id="noPics">
					<div>Keine Bilder vorhanden...</div>
				</li>
				<?php
					}
					mysqli_free_result($ergebnis);
				?>
			</ul>
		</div>
		<?php include_once("intern/analyticstracking.php") ?>
	</body>
</html>