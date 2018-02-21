<div id="tour" style="
position: absolute;
z-index: 9999;
background-color: rgba(0, 0, 0, 0.8);
width: 100%;
height: 100%;
text-align: center;
color: #fff;
font-size: 1.4em;
font-weight: 300;
cursor: pointer;
display:none;">
<div class="clearfix" style="width: 990px; height: 80px; margin: auto;">
	<div style="margin-top: 40px; text-align: left;">Tutorial / Tour</div>
	<div style="position: absolute; margin-left: 935px; margin-top: -18px; font-size: 0.7em;"><i class="fa fa-fw fa-times"></i> Close</div>
</div>

<div id="img_tour"><img src=""></div>
</div>

<div class="div-topo">
	<div class="left logo"><a href="/?o=s" title="wallfav - home">wall<span style="color:#206b70;">fav</span></a></div>
	<div class="left descricao hidden-xs hidden-sm">put your favourite websites on a wall!</div>
	<div class="topo-right">
		<ul>
			<li><a title="Tour" onclick="trata_tour()"><i class="fa fa-fw fa-plane"></i> Tutorial/Tour</a></li>
			<!--<li><a title="Facebook Page" target="_blank" href="https://www.facebook.com/wallfav"><i class="fa fa-fw fa-facebook"></i> Facebook</a></li>-->
			<li><a title="User and Website Settings" href="/settings" target="_blank"><i class="fa fa-fw fa-gear"></i> Settings</a></li>
			<?php if (isset($_SESSION['su_email'])) {?>
			<li><a title="Logout here" href="/login/logout"><i class="fa fa-fw fa-arrow-circle-right"></i> Logout</a></li>
			<?php }?>
		</ul>
	</div>
</div>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">

			<div class="div-novo-bookmark">
				<input type="text" placeholder="Add a Website here!" id="input_website">
				<button id="add_website" onclick="adicionar_link();">Add Website</button>
			</div>

			<div class="clearfix barra-opcoes">
				<a class="left"  onclick="$('.div-adicionar-categoria').fadeToggle();"><i class="fa fa-fw fa-plus"></i> Add Category</a>

			</div>
			<div class="clearfix" style="height: 30px;">
				<div class="clearfix div-adicionar-categoria">
					<input id="nova_categoria" type="text" placeholder="Category name" value="">
					<div id="categoria_bg_color" title="Category background color"><div style="background-color: #1384ad"></div></div>
					<div id="categoria_txt_color" title="Category text color"><div style="background-color: #555"></div></div>
					<a class="add-category" onclick="adicionar_categoria();"><i class="fa fa-fw fa-plus"></i> Add</a>
					<input type="hidden" id="cor_bg_categoria" value="1384ad">
					<input type="hidden" id="cor_txt_categoria" value="555">
				</div>
			</div>


			<div id="janela_eliminar_categoria">
				<div class="clearfix">
					<a class="right fechar-opcoes-categoria" onclick="$('#janela_eliminar_categoria').fadeOut();" style="margin-top: 2px;"><i class="fa fa-fw fa-times"></i></a>
					<div class="clearfix titulo">Delete Category and Websites</div>
				</div>
				<div class="clearfix">
					<div class="clearfix pergunta">Do you really want to delete the category and <b>ALL</b> the websites in it?</div>
				</div>
				<div class="clearfix" style="margin-top: 20px;">
					<div class="clearfix">
						<button onclick="eliminar_categoria();" class="bt-save-category-options"><i class="fa fa-fw fa-check"></i> Yes</button>
						<button onclick="$('#janela_eliminar_categoria').fadeOut();" class="bt-save-category-options"><i class="fa fa-fw fa-ban" ></i> No</button>
					</div>
				</div>
			</div>


			<div id="janela_opcoes_categoria">
				<input type="hidden" id="categoria_atual" value="0">
				<div class="clearfix">
					<a class="right fechar-opcoes-categoria" onclick="$('#janela_opcoes_categoria').fadeOut();" style="margin-top: 2px;"><i class="fa fa-fw fa-times"></i></a>
					<div class="clearfix titulo">Category options</div>
				</div>

				<div class="clearfix">
					<div class="clearfix sub-titulo">Name</div>
					<div class="clearfix conteudo"><input type="text" id="category_options_name"></div>
				</div>
				<div class="clearfix">
					<div class="clearfix sub-titulo">Background Color</div>
					<div class="clearfix conteudo">
						<div id="categoria_op_bg_color" title="Category background color"><div class="categoria_op_bg_color" style="background-color: #1384ad"></div></div>
					</div>
				</div>
				<div class="clearfix">
					<div class="clearfix sub-titulo">Text Color</div>
					<div class="clearfix conteudo">
						<div id="categoria_op_txt_color" title="Category text color"><div class="categoria_op_txt_color" style="background-color: #555"></div></div>
					</div>
				</div>
				<input type="hidden" id="cor_op_bg_categoria" value="1384ad">
				<input type="hidden" id="cor_op_txt_categoria" value="555">
				<div class="clearfix">
					<div class="clearfix sub-titulo">Layout Position</div>
					<div class="clearfix conteudo"><input type="text" id="category_options_layout_position"></div>
				</div>

				<div class="clearfix" style="margin-top: 10px;">
					<div class="clearfix sub-titulo">&nbsp;</div>
					<div class="clearfix">
						<button onclick="salvar_opcoes_categoria();" class="bt-save-category-options "><i class="fa fa-fw fa-save"></i> Save</button>
						<button onclick="eliminar_categoria_check();" class="bt-save-category-options"><i class="fa fa-fw fa-trash-o" rel="tooltip" title="Delete Category" ></i></button>
					</div>
				</div>
			</div>


			<button id="bt_salvar_layout" onclick="salvar_layout();"  class="btn-primary"><i class="fa fa-fw fa-save"></i> Save layout!</button>
			<i class='html_loader fa fa-spinner fa-spin blue'></i>

			<div class="row">
			<div class="col-md-12" style="color:#c00; font-weight: bold; display: none;">
					We are having technical difficulties and actively working on a fix.
				</div>
			</div>

			<div id="container" class="clearfix boxs">
				<div class="clearfix box item">
					<div class="clearfix box-titulo" style="padding-left: 0;">Unorganized</div>
					<div class="clearfix box-conteudo">
						<ul id="sortable_desorganizados" class="sortable">
							<div id="ancora_boxs_desorganizados">
								<i class='html_loader fa fa-spinner fa-spin blue'></i>
							</div>
						</ul>
					</div>
				</div>
				<div id="ancora_boxs"></div>

			</div>
		</div>
		<div class="col-md-1" style="display:none;">&nbsp;</div>
		<div class="col-md-3" style="display:none;">
			<div class="titulo-sidebar">Discover new websites</div>
			<div class="clearfix box-conteudo-sidebar" id="sidebar_destaques">
				<i class='html_loader_destaques fa fa-spinner fa-spin blue'></i>
			</div>

			<div class="clearfix" style="margin-top: 30px;">

	<!--<script type="text/javascript">
  ( function() {
    if (window.CHITIKA === undefined) { window.CHITIKA = { 'units' : [] }; };
    var unit = {"calltype":"async[2]","publisher":"goncalofbg","width":300,"height":200,"sid":"Chitika Default","color_button":"ffffff","color_button_text":"1c1c1c"};
    var placement_id = window.CHITIKA.units.length;
    window.CHITIKA.units.push(unit);
    document.write('<div id="chitikaAdBlock-' + placement_id + '"></div>');
}());
</script>
<script type="text/javascript" src="//cdn.chitika.net/getads.js" async></script>-->




				<!--<script async src="http://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>

				<ins class="adsbygoogle"
				style="display:inline-block;width:234px;height:60px"
				data-ad-client="ca-pub-4995466236763556"
				data-ad-slot="4147588428"></ins>
				<script>
					(adsbygoogle = window.adsbygoogle || []).push({});
				</script>-->

			</div>

		</div>
	</div>
</div>


<script>
	function trata_tour() {

		var timestamp = new Date().getTime();
		$('#img_tour').find('img').attr('src', BASE_URL+'img/wallfav_tour.gif'+'?'+timestamp);

		$("#tour").fadeToggle();
		$( "#tour" ).click(function() {
			$("#tour").fadeOut();
		});
	}
</script>
