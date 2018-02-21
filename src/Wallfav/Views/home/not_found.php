<?php require APP_PATH . '/src/Wallfav/Views/layouts/top_inicio.php'; ?>

<?php require APP_PATH . '/src/Wallfav/Views/layouts/topbar.php'; ?>

<div class="container">

	<div class="row">

		<div class="col-md-12" style="margin-top: 0px; padding-bottom: 50px;">

			<?php  if ($imagem != "") { ?>

			<?=$imagem?>

			<?php }else{ ?>

			<h2><?=$titulo?></h2>
			<br>
			<?=$mensagem_not_found?>
			<br>
			<!--<a href="<?=$link_valor?>"><?=$link_texto?></a>-->

			<?php } ?>

		</div>

	</div>

</div>


<?php require APP_PATH . '/src/Wallfav/Views/layouts/bottom_inicio.php'; ?>
