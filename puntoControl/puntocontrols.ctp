<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>AVA - Puntos de Control</title>
	<?php
		echo $this->Html->meta('icon');
		echo $this->Html->css('cake.generic');
		echo $this->Html->css('jquery-ui');
		echo $this->Html->css('dragtable-default');
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->Html->css('menu');
		echo $this->fetch('script');
		echo $this->Html->script('jquery');
		echo $this->Html->script('jquery-ui');
		echo $this->Html->script('jquery-ui.min');
		echo $this->Html->script('jquery.metadata');
		echo $this->Html->script('jquery.tablesorter.min');
		echo $this->Html->script('jquery.dragtable');
		echo $this->Html->script('gmap3');
		echo $this->Html->script('gmap3-menu');
		echo $this->Html->script('niceScroll');
		echo $this->Html->script('animatescroll');
		echo $this->Html->script('drawMarkers');
	?>
	<meta name="viewport" content="width=device-width">
</head>
<body>
	<div id="container">
		<div id="header">
			<h1>
				<a href="/fenix">AVA</a>
				<?php if($this->Session->check('Auth.User')) {	?>
				<span class="username">
					Usuario: <?php echo $this->Session->read('Auth.User.username'); ?>
					<a href="/fenix/users/logout"> / Cerrar sesión</a>
				</span>
			<?php } ?>
			</h1>	
			<?php if($this->Session->check('Auth.User')) {	?>
				<?php require('menu.ctp'); ?>
			<?php } ?>
			
		</div>
		<div id="content">
			<?php echo $this->Session->flash(); ?>
			<?php echo $this->fetch('content'); ?>
		</div>
		<div id="footer">
			<figure>
			<?php echo $this->Html->image('logobea_hor.png', array('id'=>'logoFooter','alt' => 'Sistema BEA','title'=>'Sistema BEA - IDEAR Electrónica','width'=>'394px','height'=>'150px')); ?>
			<figcaption>&copy; Sistema BEA - IDEAR Electrónica 2014</figcaption>
			</figure>
		</div>
	</div>
	<?php //echo $this->element('sql_dump'); ?>
	<?php echo $this->Js->writeBuffer(); ?>
</body>
</html>
