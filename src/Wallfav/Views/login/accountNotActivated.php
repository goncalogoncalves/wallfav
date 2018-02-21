<?php require APP_PATH . '/src/Wallfav/Views/layouts/top_inicio.php'; ?>

<?php require APP_PATH . '/src/Wallfav/Views/layouts/topbar.php'; ?>

<div class="container">
	<div class="row">
		<div class="col-md-12" style="margin-top: 0px;">
			<div class="clearfix">

				<h2>Account NOT Activated!</h2>
				<br>
				Some problem has occurred, please try again. If the problem continues please <a href="/contacts" title="Contact us">contact us</a>.

			</div>
		</div>
	</div>
</div>

<script src="<?php echo BASE_URL;?>js/login.js"></script>
<script type="text/javascript">
	$(document).ready(function() {

		window.location = BASE_URL+'login';

		setTimeout(function() {

			loginUser();

		});
	});
</script>
<?php require APP_PATH . '/src/Wallfav/Views/layouts/bottom_inicio.php'; ?>
