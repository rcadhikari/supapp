<!doctype html>  

<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
	<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Ubuntu:regular,bold&subset=Latin">

	<?php wp_head(); ?>
</head>
	
<body <?php body_class(); ?>>

<header>
	<!-- Fixed navbar -->
	<nav class="navbar navbar-default navbar-fixed-top">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#">
					<img src="<?php echo get_template_directory_uri().'/images/logo.png';?>" width="158" height="113" />
				</a>
			</div>
			<div id="navbar" class="navbar-collapse collapse">
				<div class="nav navbar-nav site-tagline">
					<?php wp_header_description(); ?>
				</div>
				<div class="main-nav">
					<div class="menu">
				<?php if (has_nav_menu("main_nav")):
						wp_basic_bootstrap_display_main_menu();
					endif ?>
					</div>
				</div>
				<!--<ul class="nav navbar-nav">
					<li class="active"><a href="#">Home</a></li>
					<li><a href="#about">About</a></li>
					<li><a href="#contact">Contact</a></li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li><a href="#">Action</a></li>
							<li><a href="#">Another action</a></li>
							<li><a href="#">Something else here</a></li>
							<li role="separator" class="divider"></li>
							<li class="dropdown-header">Nav header</li>
							<li><a href="#">Separated link</a></li>
							<li><a href="#">One more separated link</a></li>
						</ul>
					</li>
				</ul>-->
				<!--<ul class="nav navbar-nav navbar-right">
					<li><a href="../navbar/">Default</a></li>
					<li><a href="../navbar-static-top/">Static top</a></li>
					<li class="active"><a href="./">Fixed top <span class="sr-only">(current)</span></a></li>
				</ul>-->
			</div><!--/.nav-collapse -->
		</div>
	</nav>

</header>

	<div id="content-wrapper">
		<!--
		<header>
			<!--<img src="<?php /*header_image(); */?>" height="<?php /*echo get_custom_header()->height; */?>" width="<?php /*echo get_custom_header()->width; */?>" alt="" />-- >

			<nav class="navbar navbar-default navbar-static-top">
				<div class="header-box">
					<div class="container">

						<div class="navbar-header">
							<img src="<?php echo get_template_directory_uri().'/images/logo.png';?>" width="158" height="113" />

							<?php if (has_nav_menu("main_nav")): ?>
							<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-responsive-collapse">
								<span class="sr-only"><?php _e('Navigation', 'wp-basic-bootstrap'); ?></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
							<?php endif ?>
						</div>

						<div class="navbar-header-description">
							<?php wp_header_description(); ?>
						</div>

						<div class="main-nav">
						<?php if (has_nav_menu("main_nav")): ?>
						<div id="navbar-responsive-collapse" class="collapse navbar-collapse">
							<?php
								wp_basic_bootstrap_display_main_menu();
							?>
						</div>
						<?php endif ?>
						</div>

					</div>
				</div>

			</nav>
		</header>
		-->

		<div id="page-content">
			<div class="container">

