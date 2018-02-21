$(document).ready(function() {
	console.log("Pronto para trabalhar Mestre GG");

	$("#input_website").bind('paste', function(e) {
		setTimeout( function() {
			$("#add_website").fadeOut();
			adicionar_link();
		}, 100);
	});

	$("#input_website").keypress(function(e) {
		$("#add_website").fadeIn();
		if(e.which == 13) {
			adicionar_link();
		}
	});

	$("#input_website").click(function () {
		$(this).select();
	});



	var $container = $('#container');
	$container.masonry({
		columnWidth: 205,
		itemSelector: '.item'
	});

	activaColorPickers();

	$("#categoria_bg_color").tooltip();
	$("#categoria_txt_color").tooltip();



	load_websites_desorganizados();

	//load_destaques();
});


function salvar_posicao_website(local, website){

	activaLayout();

	var string_id_local   = local.context.id;
	var string_id_website = website.context.id;

	var id_local          = string_id_local.substring(10);
	var id_website        = string_id_website.substring(8);

	//var posicao_website = $('#'+string_id_website).index();
	//var posicao_website = $('#'+string_id_local).find('#'+string_id_website).index();
	//console.log(posicao_website);

	$.ajax({
		url: "/dashboard/saveWebsitePosition",
		type: "POST",
		data: { id_local: id_local, id_website:id_website },
		success: function (dados) {
			//console.log("saveWebsitePosition OK");
			//console.log(dados);
		},
		error: function (a,b) { }
	});

}



function salvar_layout() {

	var html_saving = '<i class="html_loader fa fa-spinner fa-spin white"></i>&nbsp;&nbsp;Saving...';
	$("#bt_salvar_layout").html(html_saving);

	var array_layout = [];
	array_layout[0] = "desorganizados";

	// Desorganizados
	var elemento;
	var elemento_string_id;
	var elemento_id;
	var contador = 0;

	var obj_layout = {};
	var array_desorganizados = [];

	$('#sortable_desorganizados li').each(function(j,li) {
		elemento           = li;
		elemento_string_id = elemento.id;
		elemento_id        = elemento_string_id.substring(8); // corta a partir da posicao 8

		array_layout[0][contador] = elemento_id;

		array_desorganizados[contador] = elemento_id;

		contador++;
	});

	obj_layout.desorganizados = array_desorganizados;

	//console.log(obj_layout.desorganizados);

	//dump(array_layout, 'body');

	obj_layout = JSON.stringify(obj_layout);

	$.ajax({
		url: "/dashboard/saveLayout",
		type: "POST",
		data: { obj_layout: obj_layout },
		success: function (dados) {
			//console.log("saveLayout OK");
			//console.log(dados);
		},
		error: function (a,b) {
			//console.log("Erro saveLayout");
			//console.log(a);
			//console.log(b);
		}
	});


	$("#bt_salvar_layout").fadeOut();

	var html_done_saving = '<i class="fa fa-fw fa-save"></i> Save layout!';
	$("#bt_salvar_layout").html(html_done_saving);
}


function load_categorias(){

	// se ja tiver alguma coisa elimina
	$("#ancora_boxs").html("");

	$.ajax({
		url: "/dashboard/loadCategorias",
		type: "POST",
		success: function (dados) {
			var dados  = JSON.parse(dados);
			$.each(dados, function(index, value) {
				var categoria_id;
				var categoria_nome;
				var categoria_cor_background;
				var categoria_cor_texto;
				$.each(value, function(index2, value2) {
					if (index2 == "ID") { categoria_id = value2; };
					if (index2 == "NOME") { categoria_nome = value2; };
					if (index2 == "COR_BACKGROUND") { categoria_cor_background = value2; };
					if (index2 == "COR_TEXTO") { categoria_cor_texto = value2; };
				});

				adicionar_categoria(categoria_id, categoria_nome, categoria_cor_background, categoria_cor_texto);
			});
			$(".html_loader").remove();

			activaLayout();



		},
		error: function (a,b) { $(".html_loader").remove(); }
	});
}


function adicionar_categoria(categoria_id, categoria_nome, categoria_cor_background, categoria_cor_texto) {


	if(typeof(categoria_id) != "undefined" && categoria_id !== null) { // categoria ja existe

		var estilo_cabecalho  = 'border-left: 5px solid #'+categoria_cor_background+';color:#'+categoria_cor_texto+';';

		$("#html_category_loader_"+categoria_id).remove();

		var html_categoria = '<div id="categoria_'+categoria_id+'" class="clearfix box item" cor_bg_categoria='+categoria_cor_background+' cor_txt_categoria='+categoria_cor_texto+'>';
		html_categoria += '<div class="clearfix box-titulo" style="'+estilo_cabecalho+'">'+categoria_nome+'';
		html_categoria += '<i onclick="opcoes_categoria('+categoria_id+');" class="fa fa-fw fa-gear right gear"></i></div>';
		html_categoria += '<div class="clearfix box-conteudo droppable" id="droppable_'+categoria_id+'">';
		html_categoria += '<ul id="sortable_'+categoria_id+'" class="sortable">';
		html_categoria += '<li class="ui-state-default"><i id="html_category_loader_'+categoria_id+'" class="fa fa-spinner fa-spin blue"></i></li>';
		html_categoria += '</ul>';
		html_categoria += '</div>';
		html_categoria += '</div>';

		$("#ancora_boxs").append(html_categoria);

		$( ".droppable" ).droppable({
			drop: function( event, ui ) {
				salvar_posicao_website($(this),$(ui.draggable));

			}
		});



		load_category_content(categoria_id);




	}else{ // vai adicionar uma nova categoria

		var nova_categoria    = $("#nova_categoria").val();
		var cor_bg_categoria  = $("#cor_bg_categoria").val();
		var cor_txt_categoria = $("#cor_txt_categoria").val();

		var estilo_cabecalho  = 'border-left: 5px solid #'+cor_bg_categoria+';color:#'+cor_txt_categoria+';';

		if (nova_categoria != "") {

			$.ajax({
				url: "/dashboard/addCategory",
				type: "POST",
				data: {
					nova_categoria: nova_categoria,
					cor_bg_categoria: cor_bg_categoria,
					cor_txt_categoria: cor_txt_categoria
				},
				success: function (dados) {
					var categoria_id = dados;

					var html_categoria = '<div id="categoria_'+categoria_id+'" class="clearfix box item" cor_bg_categoria='+cor_bg_categoria+' cor_txt_categoria='+cor_txt_categoria+'>';
					html_categoria += '<div class="clearfix box-titulo" style="'+estilo_cabecalho+'">'+nova_categoria+'';
					html_categoria += '<i onclick="opcoes_categoria('+categoria_id+');" class="fa fa-fw fa-gear right gear"></i></div>';
					html_categoria += '<div class="clearfix box-conteudo droppable" id="droppable_'+categoria_id+'">';
					html_categoria += '<ul id="sortable_'+nova_categoria+'" class="sortable">';
					html_categoria += '<li class="ui-state-default"></li>';
					html_categoria += '</ul>';
					html_categoria += '<div class="box-drop-here">Drop here a website!</div>';
					html_categoria += '</div>';
					html_categoria += '</div>';

					$("#ancora_boxs").append(html_categoria);

					activaLayout();

					$( ".box-drop-here" ).droppable({
						drop: function( event, ui ) {
							$( ".box-drop-here" ).remove();
						}
					});

					$( ".droppable" ).droppable({
						drop: function( event, ui ) {
							salvar_posicao_website($(this),$(ui.draggable));
						}
					});

				},
				error: function (a,b) { }
			});

$('.div-adicionar-categoria').fadeToggle();
};

}

}


function load_category_content(categoria_id) {

	$.ajax({
		url: "/dashboard/loadCategoryContent",
		type: "POST",
		data: { categoria_id: categoria_id },
		success: function (dados) {
			//console.log("carregou conteudo da categoria: " + categoria_id );
			$("#html_category_loader_"+categoria_id).remove();
			//console.log(dados);
			var dados  = JSON.parse(dados);
			$.each(dados, function(index, value) {
				var website_id;
				var website_titulo;
				var website_link;
				var website_imagem;
				var website_descricao;
				$.each(value, function(index2, value2) {
					if (index2 == "ID") { website_id = value2; };
					if (index2 == "TITULO") { website_titulo = value2; };
					if (index2 == "LINK") { website_link = value2; };
					if (index2 == "IMAGEM") { website_imagem = value2; };
					if (index2 == "DESCRICAO") { website_descricao = value2; };
				});

				if (website_titulo == null || website_titulo == "") {
					website_titulo = website_link;
				};

				adiciona_box(categoria_id, website_id, website_titulo, website_link, website_imagem, website_descricao);
			});

		},
		error: function (a,b) { }
	});
}


function load_websites_desorganizados(){

	$.ajax({
		url: "/dashboard/loadWebsitesDesorganizados",
		type: "POST",
		success: function (dados) {
			var dados  = JSON.parse(dados);
			$.each(dados, function(index, value) {
				var website_id;
				var website_titulo;
				var website_link;
				var website_imagem;
				var website_descricao;
				$.each(value, function(index2, value2) {
					if (index2 == "ID") { website_id = value2; };
					if (index2 == "TITULO") { website_titulo = value2; };
					if (index2 == "LINK") { website_link = value2; };
					if (index2 == "IMAGEM") { website_imagem = value2; };
					if (index2 == "DESCRICAO") { website_descricao = value2; };
				});
				if (website_titulo == null || website_titulo == "") {
					website_titulo = website_link;
				};
				adiciona_box("desorganizados", website_id, website_titulo, website_link, website_imagem, website_descricao);
			});
			$(".html_loader").remove();

			load_categorias();
		},
		error: function (a,b) { $(".html_loader").remove(); }
	});
}


function load_destaques(){

	$("#sidebar_destaques").html("");

	$.ajax({
		url: "/dashboard/loadDestaques",
		type: "POST",
		success: function (dados) {
			var dados  = JSON.parse(dados);
			$.each(dados, function(index, value) {
				var website_id;
				var website_titulo;
				var website_link;
				var website_imagem;
				var website_descricao;
				$.each(value, function(index2, value2) {
					if (index2 == "ID") { website_id = value2; };
					if (index2 == "TITULO") { website_titulo = value2; };
					if (index2 == "LINK") { website_link = value2; };
					if (index2 == "IMAGEM") { website_imagem = value2; };
					if (index2 == "DESCRICAO") { website_descricao = value2; };
				});
				if (website_titulo == null || website_titulo == "") {
					website_titulo = website_link;
				};
				adiciona_box("destaques", website_id, website_titulo, website_link, website_imagem, website_descricao);
			});
			$(".html_loader_destaques").remove();

		},
		error: function (a,b) { $(".html_loader_destaques").remove(); }
	});
}


function adiciona_box(local, website_id, website_titulo, website_link, website_imagem, website_descricao) {

	if (website_titulo == null) { website_titulo = website_link; };
	if (website_descricao == null || website_descricao == "") { website_descricao = website_titulo; };

	var html_link = '<li class="ui-state-default li-box-ligacao" id="website_'+website_id+'">';
	html_link += '<div class="clearfix box-ligacao">';
	html_link += '<div class="box-fav"><img src="'+website_imagem+'" height="16" width="16"></div>';
	//html_link += '<div class="box-fav"></div>';
	html_link += '<div class="box-link"><a title="'+website_descricao+'&nbsp;&nbsp;||&nbsp;&nbsp;'+website_link+'" href="'+website_link+'" target="_blank">'+website_titulo+'</a></div>';
	if (local != "destaques") {
		html_link += '<i title="Delete website" onclick="eliminar_link('+website_id+');" class="fa fa-fw fa-trash-o trash right"></i>';
	}
	html_link += '</div>';
	html_link += '</li>';

	if (website_link != null) {

		if (local == "desorganizados") {
			$("#sortable_desorganizados").append(html_link);
		}else if (local == "destaques") {
			$("#sidebar_destaques").append(html_link);
		}else{
			$("#sortable_"+local).append(html_link);
		}

		activaLayout();
	};

}



function adicionar_link() {
	var input_website = $("#input_website").val();
	var html_loader = '<i class=\'html_loader fa fa-spinner fa-spin blue\'></i>';
	$("#sortable_desorganizados").append(html_loader);

	if (input_website != "") {

		$.ajax({
			url: "/dashboard/addWebsite",
			type: "POST",
			data: { input_website:input_website },
			success: function (dados) {
				var dados     = JSON.parse(dados);
				//console.log(dados);
				var website_id = dados["0"];
				var website_descricao = dados["description"];
				var link_base = dados["link_base"];
				var titulo    = dados["titulo"];
				var imagem    = dados["imagem"];

				if (titulo == null || titulo == "") {
					titulo = link_base;
				};

				if (titulo != '') {
					var html_link = '<li class="ui-state-default li-box-ligacao" id="website_'+website_id+'">';
					html_link += '<div class="clearfix box-ligacao">';
					html_link += '<div class="box-fav"><img src="'+imagem+'" height="16" width="16"></div>';
					//html_link += '<div class="box-fav"></div>';
					html_link += '<div class="box-link"><a title="'+website_descricao+'" href="'+link_base+'" target="_blank">'+titulo+'</a></div>';
					html_link += '<i title="Delete website" onclick="eliminar_link('+website_id+');" class="fa fa-fw fa-trash-o trash right"></i>';
					html_link += '</div>';
					html_link += '</li>';

					$(".html_loader").remove();
					$("#sortable_desorganizados").append(html_link);
					activaLayout();

				}else{
					$(".html_loader").remove();
					$.notify(
						'A problem occurred while adding the website',
						{ position:"bottom right", autoHide: true }
						);
				}
			},
			error: function (a,b) { $(".html_loader").remove(); }
		});
};

}


function eliminar_link(website_id) {
	console.log("eliminar_link");
	$.ajax({
		url: "/dashboard/deleteWebsite",
		type: "POST",
		data: { website_id:website_id },
		success: function (dados) {
			if (dados == "1") {
				$("#website_"+website_id).fadeOut(300 ,function(){
					$("#website_"+website_id).remove();
				});

				activaLayout();
			};
		},
		error: function (a,b) { }
	});
}


function salvar_opcoes_categoria () {

	var id_categoria = $("#categoria_atual").val();

	var category_options_name             = $("#category_options_name").val();
	var category_options_background_color = $("#cor_op_bg_categoria").val();
	var category_options_text_color       = $("#cor_op_txt_categoria").val();
	var category_options_layout_position  = $("#category_options_layout_position").val();

	$.ajax({
		url: "/dashboard/saveInfoCategory",
		type: "POST",
		data: {
			id_categoria:id_categoria ,
			category_options_name:category_options_name ,
			category_options_background_color:category_options_background_color ,
			category_options_text_color:category_options_text_color ,
			category_options_layout_position:category_options_layout_position
		},
		success: function (dados) {

			$("#categoria_"+id_categoria+" .box-titulo ").css("color" , "#"+category_options_text_color);
			$("#categoria_"+id_categoria+" .box-titulo ").css("border-left" , "5px solid #"+category_options_background_color);
			$("#categoria_"+id_categoria+" .box-titulo ").text(category_options_name);

			$('#janela_opcoes_categoria').fadeOut();

			load_categorias();
		},
		error: function (a,b) { }
	});
}


function eliminar_categoria_check() {

	$('#janela_opcoes_categoria').fadeOut();
	$('#janela_eliminar_categoria').fadeIn();

}


function eliminar_categoria() {

	var categoria_id = $("#categoria_atual").val();

	$.ajax({
		url: "/dashboard/deleteCategory",
		type: "POST",
		data: { categoria_id:categoria_id },
		success: function (dados) {
			if (dados == "1") {
				$('#janela_eliminar_categoria').fadeOut();
				$('#categoria_'+categoria_id).fadeOut();

				activaLayout();
			};
		},
		error: function (a,b) { }
	});
}


function opcoes_categoria(id_categoria) {

	$("#categoria_atual").val(id_categoria);

	$.ajax({
		url: "/dashboard/loadInfoCategory",
		type: "POST",
		data: { id_categoria:id_categoria },
		success: function (dados) {

			var dados     = JSON.parse(dados);
			var categoria_nome;
			var categoria_cor_background;
			var categoria_cor_texto;
			var categoria_ordem;

			$.each(dados, function(index, value) {
				$.each(value, function(index2, value2) {
					if (index2 == "NOME") { categoria_nome = value2; };
					if (index2 == "COR_BACKGROUND") { categoria_cor_background = value2; };
					if (index2 == "COR_TEXTO") { categoria_cor_texto = value2; };
					if (index2 == "ORDEM") { categoria_ordem = value2; };
				});

			});

			$(".categoria_op_bg_color").css("background-color","#"+categoria_cor_background);
			$(".categoria_op_txt_color").css("background-color","#"+categoria_cor_texto);

			$("#cor_op_bg_categoria").val(categoria_cor_background);
			$("#cor_op_txt_categoria").val(categoria_cor_texto);

			$("#category_options_name").val(categoria_nome);
			$("#category_options_layout_position").val(categoria_ordem);

			$('#categoria_op_bg_color').ColorPicker({
				color: '#'+categoria_cor_background,
				track: true,
				onShow: function (colpkr) {
					$(colpkr).fadeIn(500);
					return false;
				},
				onHide: function (colpkr) {
					$(colpkr).fadeOut(500);
					return false;
				},
				onChange: function (hsb, hex, rgb) {
					$('#categoria_op_bg_color div').css('backgroundColor', '#' + hex);

					$('#cor_op_bg_categoria').val(hex);
				}
			});

			$('#categoria_op_txt_color').ColorPicker({
				color: '#'+categoria_cor_texto,
				track: true,
				onShow: function (colpkr) {
					$(colpkr).fadeIn(500);
					return false;
				},
				onHide: function (colpkr) {
					$(colpkr).fadeOut(500);
					return false;
				},
				onChange: function (hsb, hex, rgb) {
					$('#categoria_op_txt_color div').css('backgroundColor', '#' + hex);
					$('#cor_op_txt_categoria').val(hex);
				}
			});

			$('#janela_opcoes_categoria').fadeIn();

		},
		error: function (a,b) { }
	});




}


function activaLayout() {
	setTimeout(function() {
	$(".sortable").sortable({
		connectWith: "ul",
		stop: function( event, ui ) {
			$( '#container' ).masonry( 'reloadItems' );
			$( '#container' ).masonry( 'layout' );

			//$("#bt_salvar_layout").fadeIn();
		}
	});

	$( '#container' ).masonry( 'reloadItems' );
	$( '#container' ).masonry( 'layout' );
}, 200);
}


function toggle_links(obj){
	$(obj).siblings('.box-conteudo').toggle();
	$('#container').masonry('reloadItems');
	$('#container').masonry('layout');
}




function activaColorPickers() {
	$('#categoria_bg_color').ColorPicker({
		color: '#1384ad',
		track: true,
		onShow: function (colpkr) {
			$(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			$(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			$('#categoria_bg_color div').css('backgroundColor', '#' + hex);
			$('#cor_bg_categoria').val(hex);
		}
	});

	$('#categoria_txt_color').ColorPicker({
		color: '#fff',
		track: true,
		onShow: function (colpkr) {
			$(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			$(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			$('#categoria_txt_color div').css('backgroundColor', '#' + hex);
			$('#cor_txt_categoria').val(hex);
		}
	});
}


// Avoid `console` errors in browsers that lack a console.
(function() {
	var method;
	var noop = function () {};
	var methods = [
	'assert', 'clear', 'count', 'debug', 'dir', 'dirxml', 'error',
	'exception', 'group', 'groupCollapsed', 'groupEnd', 'info', 'log',
	'markTimeline', 'profile', 'profileEnd', 'table', 'time', 'timeEnd',
	'timeStamp', 'trace', 'warn'
	];
	var length = methods.length;
	var console = (window.console = window.console || {});

	while (length--) {
		method = methods[length];

        // Only stub undefined methods.
        if (!console[method]) {
        	console[method] = noop;
        }
    }
}());
