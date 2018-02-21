
<?php require APP_PATH . '/src/Wallfav/Views/layouts/top_inicio.php'; ?>

<?php require APP_PATH . '/src/Wallfav/Views/layouts/topbar.php'; ?>

<div class="container">
	<div class="row">

		<div class="col-md-12" style="margin-top: 0px;  padding-bottom: 50px;">

			<div class="clearfix">
				<h2>Send Feedback</h2>
				<br>
				Your feedback is important to increase the quality of the website.
				<br><br>

				<div class="clearfix"><textarea id="textarea_feedback" placeholder="Write here your feedback..." ></textarea></div>
				<div class="clearfix">
					<button id="send_feedback" onclick="send_feedback();"  class="btn-primary"><i class="fa fa-fw fa-rocket"></i> Send Feedback</button>
				</div>

			</div>
		</div>
	</div>
</div>

<script>
	function send_feedback() {

		var texto_feedback = $("#textarea_feedback").val();

		$("#send_feedback").html("Sending...");

		if (texto_feedback != "") {
			$.ajax({
				url: "/dashboard/sendFeedback",
				type: "POST",
				data: { texto_feedback: texto_feedback },
				success: function (dados) {
					$("#send_feedback").html("Done. Thank you!");
				},
				error: function (a,b) { }
			});
		};
	}
</script>

<?php require APP_PATH . '/src/Wallfav/Views/layouts/bottom_inicio.php'; ?>
