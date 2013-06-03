<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>
			<?= Configure::read('TwitterBootstrap.AppName') ?>
			<?= $title_for_layout ?>
		</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">

		<!-- Le styles -->
		<?= $this->Html->css('bootstrap.min') ?>
		<style>
			body {
				padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
			}
		</style>
		<?= $this->Html->css('bootstrap-responsive.min') ?>

		<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

		<!-- Le fav and touch icons -->

		<link rel="shortcut icon" href="/ico/favicon.ico">
		<link rel="apple-touch-icon-precomposed" sizes="144x144" href="/ico/apple-touch-icon-144-precomposed.png">
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="/ico/apple-touch-icon-114-precomposed.png">
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="/ico/apple-touch-icon-72-precomposed.png">
		<link rel="apple-touch-icon-precomposed" href="/ico/apple-touch-icon-57-precomposed.png">

		<?php
		echo $this->fetch('meta');
		echo $this->fetch('css');
		?>
	</head>

	<body>

		<div class="navbar navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container">
					<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</a>
					<a class="brand" href="/"><?= Configure::read('TwitterBootstrap.AppName') ?></a>
					<div class="nav-collapse">
						<ul class="nav">
							<? foreach ($navLinks as $name => $link) : ?>
								<? if (!isset($link['auth']) || ((bool) $link['auth'] == (bool) AuthComponent::user())) : ?>
							<li<?= ($this->here == $this->Html->url($link['url'])) ? ' class="active"' : '' ?>>
										<?= $this->Html->Link($name, $link['url']) ?>
							</li>
								<? endif; ?>
								<? endforeach; ?>
						</ul>
					</div><!--/.nav-collapse -->
				</div>
			</div>
		</div>

		<div class="container">
			<?= ($this->here !== '/') ? $this->Html->getCrumbList(array('class' => 'breadcrumb', 'lastClass' => 'active', 'separator' => '<span class="divider">/</span>'), array('Home', '/')) : '' ?>
			<?=
			$this->Session->flash('flash', array(
			    'element' => 'alert',
			    'params' => array('plugin' => 'TwitterBootstrap'),
			))
			?>
			<?=
			$this->Session->flash('auth', array(
			    'element' => 'alert',
			    'params' => array('plugin' => 'TwitterBootstrap'),
			))
			?>
			<?= $this->fetch('content') ?>

		</div> <!-- /container -->

		<!-- Le javascript
	    ================================================== -->
		<!-- Placed at the end of the document so the pages load faster -->
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.0/jquery.min.js"></script>
		<?= $this->Html->script('bootstrap.min') ?>
		<?= $this->fetch('script') ?>

	</body>
</html>
