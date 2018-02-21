
<?php require APP_PATH . '/src/Wallfav/Views/layouts/top_inicio.php'; ?>

<?php require APP_PATH . '/src/Wallfav/Views/layouts/topbar.php'; ?>

<div class="container">
	<div class="row">

		<div class="col-md-12" style="margin-top: 0px;">

			<div class="clearfix" id="settings">
				<h2>Settings</h2>
				<br>
				<h3>Change password</h3>
				<div class="clearfix">
					<div class="clearfix sub-titulo">Old password</div>
					<div class="clearfix conteudo"><input type="password" id="old_password" ></div>
				</div>
				<div class="clearfix">
					<div class="clearfix sub-titulo">New password</div>
					<div class="clearfix conteudo"><input type="password" id="new_password1"></div>
				</div>
				<div class="clearfix">
					<div class="clearfix sub-titulo">Repeat new password</div>
					<div class="clearfix conteudo"><input type="password" id="new_password2"></div>
				</div>
				<div class="clearfix">
					<div class="clearfix" id="feedback_change_password"></div>
				</div>
				<div class="clearfix" style="margin-top: 20px;">
					<button onclick="save_password()" class=" btn-primary"><i class="fa fa-fw fa-save"></i> Save new password</button>
				</div>

				<hr>
				<h3>Export favourites</h3>
				<div class="clearfix">
					<select id="opcoes_exportar">
						<option value="0">Choose Format</option>
						<option value="1">TXT</option>
						<option value="2">CSV</option>
					</select>
				</div>
				<div class="clearfix">
					<div class="clearfix" id="feedback_export_info" ></div>
				</div>

				<div class="clearfix" style="margin-top: 10px;">
					<button onclick="export_info()" class="btn-primary"><i class="fa fa-fw fa-download"></i> Export</button>
				</div>
				<hr>
				<h3>Delete account</h3>
				<div class="clearfix">
					Do you want to delete your account and with ALL the categories and websites? (irreversible)
				</div>
				<div class="clearfix"><a href="/delete-account">Delete account</a></div>

				<hr>

			</div>
		</div>
	</div>
</div>

<script>

	function export_info() {

		var formato = $("#opcoes_exportar option:selected").val();

		if (formato != "0") {

			$("#feedback_export_info").html("<i class='fa fa-spinner fa-spin '></i>");

			$.ajax({
				type: 'POST',
				url: '/export-info',
				data: { formato: formato },
				success:function(dados){

					var mensagem_feedback = '';

					try {
						var dados         = JSON.parse(dados);
						var link_file     = dados["link_file"];
						var formato_file  = dados["formato_file"];

						var hoje          = new Date();
						var hoje_dia      = hoje.getDate();
						var hoje_mes      = hoje.getMonth()+1;
						var hoje_ano      = hoje.getFullYear();
						var data_hoje     = hoje_dia+"/"+hoje_mes+"/"+hoje_ano;
						var nome_ficheiro = 'WallFav Favourites - '+data_hoje+'.'+formato_file;


						if (link_file != "0") {
							mensagem_feedback = 'File exported! <a download="'+nome_ficheiro+'" title="Download" href="'+link_file+'">Download File</a>';
							mensagem_feedback += '<br><span style="font-size:0.8em;font-weight:300;">File will be available for 24H.</span>';
						}else{
							mensagem_feedback = "Something went wrong, please try again.";
						};

					} catch (e) {
						mensagem_feedback = "Something went wrong, please try again.";
					}

					$("#feedback_export_info").html(mensagem_feedback);

				},
				error:function(dados){ $("#feedback_export_info").html("Something went wrong, please try again.");}
			});
};
}


function save_password() {

	$("#feedback_change_password").html("<i class='html_loader fa fa-spinner fa-spin '></i>");

	var old_password  = $("#old_password").val();
	var new_password1 = $("#new_password1").val();
	var new_password2 = $("#new_password2").val();

	var erro = false;

	if (old_password == "") { $("#old_password").addClass("form-erro"); erro = true; }else{ $("#old_password").removeClass("form-erro"); };
	if (new_password1 == "" || new_password1.length < 4) { $("#new_password1").addClass("form-erro"); erro = true; }else{ $("#new_password1").removeClass("form-erro"); };
	if (new_password2 == "" || new_password2.length < 4 || new_password2 != new_password1) { $("#new_password2").addClass("form-erro"); erro = true; }else{ $("#new_password2").removeClass("form-erro"); };

	if (erro == true) {
		$("#feedback_change_password").html("");
		return false;
	};

	$.ajax({
		type: 'POST',
		url: '/change-password',
		data: {
			old_password: old_password,
			new_password1: new_password1,
			new_password2: new_password2
		},
		success:function(data){
			var mensagem_feedback = '';
			if (data == "0") {
				mensagem_feedback = "Something went wrong, the password didn't change.";
			}else{
				mensagem_feedback = 'Password was changed!';
			}
			$("#feedback_change_password").html(mensagem_feedback);

		},
		error:function(data){ $("#feedback_change_password").html("Something went wrong, the password didn't change.");}
	});
}


</script>

<?php require APP_PATH . '/src/Wallfav/Views/layouts/bottom_inicio.php'; ?>
