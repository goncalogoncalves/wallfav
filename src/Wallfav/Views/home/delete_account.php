
<?php require APP_PATH . '/src/Wallfav/Views/layouts/top_inicio.php'; ?>

<?php require APP_PATH . '/src/Wallfav/Views/layouts/topbar.php'; ?>

<div class="container">
	<div class="row">

		<div class="col-md-12" style="margin-top: 0px;  padding-bottom: 50px;">

			<div class="clearfix">
				<h2>Delete Account</h2>
				<br>
				This proccess will erase all the information related to you, are you sure do you want to delete your account?
				<br>
				<br>
				<div class="clearfix"><textarea id="textarea_feedback" placeholder="Write here your feedback..." ></textarea></div>
				<div class="clearfix">
					<button onclick="delete_account()"  class="btn-primary"><i class="fa fa-fw fa-eraser"></i> Delete account</button>
				</div>
				<br>

				<div id="div_feedback_account" style="font-weight: 500;"></div>

				<div>
					<a href="/settings"><i class="fa fa-fw fa-angle-left"></i> Back to settings</a>
				</div>

			</div>
		</div>
	</div>
</div>

<script>
	function delete_account() {

		$("#div_feedback_account").html("<i class='fa fa-spinner fa-spin'></i>");
		var texto_feedback = $("#textarea_feedback").val();

		$.ajax({
			url: "/deleteAccountNow",
			type: "POST",
			data: { texto_feedback: texto_feedback },
			success: function (dados) {

				if (dados == "1") {
					$("#div_feedback_account").html("Account erased! Sad to see you go.");
				}else{
					$("#div_feedback_account").html("Account NOT erased! Some problem occurred.");
				}

			},
			error: function (a,b) { $("#div_feedback_account").html("Account NOT erased! Some problem occurred."); }
		});
	};

</script>

<?php require APP_PATH . '/src/Wallfav/Views/layouts/bottom_inicio.php'; ?>
