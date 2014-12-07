<!DOCTYPE HTML>
<html>
<head>
	<meta charset="UTF-8">
	<title>Some Page Title</title>
	<style type="text/css" media="screen">
		@import url( <?php bloginfo('stylesheet_url'); ?> );
	</style>
	<?php wp_head(); ?>
</head>
<body <?php body_class() ?>>

<div id="container">
	<div id="container-inner">
		<header>
			<div id="logo"></div>
			<nav>
				<?php wp_nav_menu( array( 'theme_location' => 'main' ) ); ?>
			</nav>
		</header>
		<div id="content">

