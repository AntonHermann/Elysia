<?php
	function genPageHeader() { ?>
	<header id="pageheader">
		<div id="banner">
			<img id="logo" src="img/logo.png" alt="Elysia Logo: Walnuss" />
			<h1>Elysia</h1>
			<div id="logonfield"><a class="button" href="#">Anmelden</a></div>
		</div>
	</header>
<?php } function genPageNav() { ?>
	<nav id="pageNav">
		<ul>
			<?php
				global $pages, $SITE_BASE;
				foreach ($pages as $value) {
			?>
			<li<?=($_SERVER["SCRIPT_NAME"] == $value[0])?" class=\"active\"":""?>>
				<a href="<?=$SITE_BASE.$value[0]?>"><?=$value[1]?><!--<img src="<?=$SITE_BASE."/img/nav/".$value[1]?>.svg" height="20px" />--></a>
			</li>
			<?php
				}
			?>
		</ul>
	</nav>
<?php } ?>