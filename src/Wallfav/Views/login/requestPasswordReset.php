<?php require APP_PATH . '/src/Wallfav/Views/layouts/top_inicio.php'; ?>

<?php require APP_PATH . '/src/Wallfav/Views/layouts/topbar.php'; ?>

<div class="container">
	<div class="row">
		<div class="col-md-12" style="margin-top: 0px;">
			<div class="clearfix">

				<h2>Request new password</h2>

				<br>
				Please enter your email so we can send you a new password.
				<br><br>

				<div class="clearfix"><input id="user_email" placeholder="Write here your email" class="input-email " ></input></div>
				<div class="clearfix">
					<button id="request_password" onclick="request_password();"  class="btn-primary"><i class="fa fa-fw fa-key"></i> Request Password</button>
				</div>
				<div class="clearfix" style="margin-top:20px;" id="feedback"></div>

			</div>
		</div>
	</div>
</div>


<script>
	function request_password() {

		var user_email = $("#user_email").val();

		if (user_email != "") {
			$.ajax({
				url: "/login/passwordReset",
				type: "POST",
				data: { user_email: user_email },
				success: function (dados) {
					if (dados == "1") {
						$("#feedback").html("A new email was sent to you.");

					}else{
						$("#feedback").html("A problem occurred, please try again.");
					};

				},
				error: function (a,b) { }
			});
		};
	}
</script>


<?php require APP_PATH . '/src/Wallfav/Views/layouts/bottom_inicio.php'; ?>
