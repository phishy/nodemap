<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2010, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */
?>
<!doctype html>
<html>
<head>
	<?php echo $this->html->charset();?>
	<title>Application > <?php echo $this->title(); ?></title>
	<?php //echo $this->html->style(array('debug', 'lithium')); ?>
	<?php echo $this->scripts(); ?>
	<?php //echo $this->html->link('Icon', null, array('type' => 'icon')); ?>
	<style>
	body {
		font-family: 'Microsoft Sans Serif';
		font-size: 11px;
	}
	</style>
</head>
<body class="app">
	<div id="container">
		<div id="header">
			<h1>Broad Information Technology Map</h1>
		</div>
		<div id="content">
			<?php echo $this->content(); ?>
		</div>
	</div>
</body>
</html>