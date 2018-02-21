<?php

use Slim\Slim;

class DashboardModel {

	public function __construct($db) {
		try {
			$this->db = $db;
		} catch (PDOException $e) {
			exit('Database connection could not be established.');
		}
	}

	public function utf8_encode_deep(&$input) {
		if (is_string($input)) {
			$input = utf8_encode($input);
		} else if (is_array($input)) {
			foreach ($input as &$value) {
				$this->utf8_encode_deep($value);
			}

			unset($value);
		} else if (is_object($input)) {
			$vars = array_keys(get_object_vars($input));

			foreach ($vars as $var) {
				$this->utf8_encode_deep($input->$var);
			}
		}
	}

	public function getUserInfo() {

		$user_id = isset($_SESSION['su_id']) ? $_SESSION['su_id'] : "";

		$query = $this->db->prepare("SELECT * FROM users WHERE USER_ID = :USER_ID LIMIT 1");
		$query->bindParam(':USER_ID', $user_id, PDO::PARAM_INT);
		$query->execute();
		$resultado = $query->fetch();
		$user_active = 0;
		if ($resultado != false) {
			$user_active = $resultado->USER_ACTIVE;
		}

		$info_user = array('user_active' => $user_active);

		return $info_user;
	}

	public function addWebsite($parametros) {
		//$input_website = mysql_real_escape_string($parametros['input_website']);
		$input_website = $parametros['input_website'];
		$user_id = $_SESSION['su_id'];
		$agora = date("Y-m-d G:i:s");

		$url = new url();
		$resultado = $url->get_info_pagina($input_website);

		$link = $resultado['link_base'];
		$link_raiz = $resultado['link_raiz'];
		$titulo = $resultado['titulo'];
		$imagem = $resultado['imagem'];
		$description = $resultado['description'];
		$keywords = $resultado['keywords'];
		$og_image = $resultado['og_image'];
		$icone = $resultado['icone'];

		$query = $this->db->prepare("INSERT INTO websites
			(USER_ID, TITULO, LINK, LINK_RAIZ, IMAGEM, IMAGEM_2, DESCRICAO, KEYWORDS, DATA, ICONE)
			VALUES
			(:USER_ID, :TITULO, :LINK, :LINK_RAIZ, :IMAGEM, :IMAGEM_2, :DESCRICAO, :KEYWORDS, :DATA, :ICONE)
			");
		$query->bindParam(':USER_ID', $user_id, PDO::PARAM_INT);
		$query->bindParam(':TITULO', $titulo, PDO::PARAM_STR);
		$query->bindParam(':LINK', $link, PDO::PARAM_STR);
		$query->bindParam(':LINK_RAIZ', $link_raiz, PDO::PARAM_STR);
		$query->bindParam(':IMAGEM', $imagem, PDO::PARAM_STR);
		$query->bindParam(':IMAGEM_2', $og_image, PDO::PARAM_STR);
		$query->bindParam(':DESCRICAO', $description, PDO::PARAM_STR);
		$query->bindParam(':KEYWORDS', $keywords, PDO::PARAM_STR);
		$query->bindParam(':DATA', $agora, PDO::PARAM_STR);
		$query->bindParam(':ICONE', $icone, PDO::PARAM_STR);
		$query->execute();

		$count = $query->rowCount();
		if ($count != 1) {
			return false;
		}

		$ultimo_id = $this->db->lastInsertId();

		array_push($resultado, $ultimo_id);

		return $resultado;
	}

	public function saveWebsitePosition($parametros) {

		$id_local = $parametros['id_local'];
		$id_website = $parametros['id_website'];

		$user_id = $_SESSION['su_id'];
		$agora = date("Y-m-d G:i:s");

		$query = $this->db->prepare("UPDATE websites SET CATEGORIA=:LOCAL WHERE USER_ID=:USER_ID AND ID=:WEBSITE_ID ");
		$query->bindParam(':USER_ID', $user_id, PDO::PARAM_INT);
		$query->bindParam(':LOCAL', $id_local, PDO::PARAM_STR);
		$query->bindParam(':WEBSITE_ID', $id_website, PDO::PARAM_INT);
		$query->execute();

		$count = $query->rowCount();
		if ($count != 1) {
			return false;
		}

		return true;
	}

	public function saveLayout($parametros) {

		$obj_layout = json_decode($parametros['obj_layout']);

		return "ok: " . $valor;
	}

	public function addCategory($parametros) {

		$nova_categoria = $parametros['nova_categoria'];
		$cor_bg_categoria = $parametros['cor_bg_categoria'];
		$cor_txt_categoria = $parametros['cor_txt_categoria'];
		$user_id = $_SESSION['su_id'];
		$agora = date("Y-m-d G:i:s");

		$query = $this->db->prepare("INSERT INTO categorias (USER_ID, NOME, COR_BACKGROUND, COR_TEXTO, DATA, ORDEM) VALUES (:USER_ID, :NOME, :COR_BACKGROUND, :COR_TEXTO, :DATA, :ORDEM) ");
		$ordem = '100';
		$query->bindParam(':USER_ID', $user_id, PDO::PARAM_INT);
		$query->bindParam(':NOME', $nova_categoria, PDO::PARAM_STR);
		$query->bindParam(':COR_BACKGROUND', $cor_bg_categoria, PDO::PARAM_STR);
		$query->bindParam(':COR_TEXTO', $cor_txt_categoria, PDO::PARAM_STR);
		$query->bindParam(':DATA', $agora, PDO::PARAM_STR);
		$query->bindParam(':ORDEM', $ordem, PDO::PARAM_STR);
		$query->execute();

		$count = $query->rowCount();
		if ($count != 1) {
			return false;
		}

		$ultimo_id = $this->db->lastInsertId();

		return $ultimo_id;
	}

	public function loadCategorias() {

		$user_id = $_SESSION['su_id'];

		$query = $this->db->prepare("SELECT ID,  NOME, COR_BACKGROUND, COR_TEXTO, ORDEM FROM categorias WHERE USER_ID = :USER_ID ORDER BY ORDEM ASC");
		$query->bindParam(':USER_ID', $user_id, PDO::PARAM_INT);
		$query->execute();

		$categorias = $query->fetchAll();

		$this->utf8_encode_deep($categorias);

		return json_encode($categorias);
	}

	public function loadInfoCategory($parametros) {

		$id_categoria = $parametros['id_categoria'];

		$user_id = $_SESSION['su_id'];

		$query = $this->db->prepare("SELECT NOME, COR_BACKGROUND, COR_TEXTO, ORDEM FROM categorias WHERE USER_ID = :USER_ID AND ID=:ID_CATEGORIA LIMIT 1");
		$query->bindParam(':USER_ID', $user_id, PDO::PARAM_INT);
		$query->bindParam(':ID_CATEGORIA', $id_categoria, PDO::PARAM_INT);
		$query->execute();

		$categoria = $query->fetchAll();

		return json_encode($categoria);
	}

	public function saveInfoCategory($parametros) {

		$id_categoria = $parametros['id_categoria'];
		$category_options_name = $parametros['category_options_name'];
		$category_options_background_color = $parametros['category_options_background_color'];
		$category_options_text_color = $parametros['category_options_text_color'];
		$category_options_layout_position = $parametros['category_options_layout_position'];

		$user_id = $_SESSION['su_id'];
		$agora = date("Y-m-d G:i:s");

		$query = $this->db->prepare("UPDATE categorias
			SET NOME=:NOME , COR_BACKGROUND=:COR_BACKGROUND, COR_TEXTO=:COR_TEXTO, ORDEM=:ORDEM
			WHERE USER_ID=:USER_ID AND ID=:ID_CATEGORIA ");
		$query->bindParam(':NOME', $category_options_name, PDO::PARAM_STR);
		$query->bindParam(':COR_BACKGROUND', $category_options_background_color, PDO::PARAM_STR);
		$query->bindParam(':COR_TEXTO', $category_options_text_color, PDO::PARAM_STR);
		$query->bindParam(':ORDEM', $category_options_layout_position, PDO::PARAM_STR);
		$query->bindParam(':USER_ID', $user_id, PDO::PARAM_INT);
		$query->bindParam(':ID_CATEGORIA', $id_categoria, PDO::PARAM_INT);
		$query->execute();

		$count = $query->rowCount();
		if ($count != 1) {
			return false;
		}

		return true;
	}

	public function loadWebsitesDesorganizados() {

		$user_id = $_SESSION['su_id'];

		$query = $this->db->prepare("SELECT ID, TITULO, DESCRICAO, LINK, IMAGEM, CATEGORIA FROM websites WHERE USER_ID = :USER_ID AND CATEGORIA = 0");
		$query->bindParam(':USER_ID', $user_id, PDO::PARAM_INT);
		$query->execute();

		$websites = $query->fetchAll();

		$this->utf8_encode_deep($websites);

		return json_encode($websites);
	}

	public function loadDestaques() {

		$user_id = $_SESSION['su_id'];

		$query = $this->db->prepare("SELECT  *
			FROM websites
			WHERE USER_ID != :USER_ID  AND
			TITULO != ''
			GROUP BY TITULO
			ORDER BY  EM_DESTAQUE DESC, RAND()
			LIMIT 30");

		$query->bindParam(':USER_ID', $user_id, PDO::PARAM_INT);
		$query->execute();
		$websites = $query->fetchAll();
		$count = $query->rowCount();

		$app = Slim::getInstance();
		$app->log->info("count: " . $count);

		return json_encode($websites);
	}

	public function loadCategoryContent($parametros) {

		$categoria_id = $parametros['categoria_id'];

		$user_id = $_SESSION['su_id'];

		$query = $this->db->prepare("SELECT ID,  TITULO, DESCRICAO, LINK, IMAGEM, CATEGORIA FROM websites WHERE USER_ID = :USER_ID AND CATEGORIA = :CATEGORIA_ID");
		$query->bindParam(':USER_ID', $user_id, PDO::PARAM_INT);
		$query->bindParam(':CATEGORIA_ID', $categoria_id, PDO::PARAM_INT);
		$query->execute();
		$websites = $query->fetchAll();

		$this->utf8_encode_deep($websites);

		return json_encode($websites);
	}

	public function deleteWebsite($parametros) {

		$website_id = $parametros['website_id'];

		$user_id = $_SESSION['su_id'];

		$query = $this->db->prepare("SELECT ID FROM websites WHERE USER_ID = :USER_ID AND ID = :WEBSITE_ID LIMIT 1");
		$query->bindParam(':USER_ID', $user_id, PDO::PARAM_INT);
		$query->bindParam(':WEBSITE_ID', $website_id, PDO::PARAM_INT);
		$query->execute();
		$websites = $query->fetch();
		$count = $query->rowCount();
		if ($count != 1) {
			return false;
		}

		$sql = "DELETE FROM websites WHERE ID =  :WEBSITE_ID";
		$query = $this->db->prepare($sql);
		$query->bindParam(':WEBSITE_ID', $website_id, PDO::PARAM_INT);
		$query->execute();

		return true;
	}

	public function deleteCategory($parametros) {

		$categoria_id = $parametros['categoria_id'];
		$user_id = $_SESSION['su_id'];

		$sql = "DELETE FROM categorias WHERE ID = :CATEGORIA_ID AND USER_ID = :USER_ID";
		$query = $this->db->prepare($sql);
		$query->bindParam(':CATEGORIA_ID', $categoria_id, PDO::PARAM_INT);
		$query->bindParam(':USER_ID', $user_id, PDO::PARAM_INT);
		$query->execute();

		$sql = "DELETE FROM websites WHERE CATEGORIA = :CATEGORIA_ID AND USER_ID = :USER_ID";
		$query = $this->db->prepare($sql);
		$query->bindParam(':CATEGORIA_ID', $categoria_id, PDO::PARAM_INT);
		$query->bindParam(':USER_ID', $user_id, PDO::PARAM_INT);
		$query->execute();

		return true;
	}

	public function sendFeedback($parametros) {

		$texto_feedback = $parametros['texto_feedback'];
		$user_id = $_SESSION['su_id'];
		$agora = date("Y-m-d G:i:s");

		if ($texto_feedback != "") {

			$query = $this->db->prepare("INSERT INTO feedback (USER_ID, TEXTO, DATA) VALUES (:USER_ID, :TEXTO, :DATA) ");
			$query->bindParam(':USER_ID', $user_id, PDO::PARAM_INT);
			$query->bindParam(':TEXTO', $texto_feedback, PDO::PARAM_STR);
			$query->bindParam(':DATA', $agora, PDO::PARAM_STR);
			$query->execute();

			$count = $query->rowCount();
			if ($count != 1) {
				return false;
			}
			return true;

		} else {
			return false;
		}

	}

	public function changePassword($parametros) {

		$old_password = $parametros['old_password'];
		$new_password1 = $parametros['new_password1'];
		$new_password2 = $parametros['new_password2'];

		if (strlen($old_password) > 3 && strlen($new_password1) > 3 && $new_password1 == $new_password2) {

			$user_id = $_SESSION['su_id'];
			$agora = date("Y-m-d G:i:s");

			$hash_cost_factor = (defined('HASH_COST_FACTOR') ? HASH_COST_FACTOR : null);
			$new_user_password_hash = password_hash($new_password1, PASSWORD_DEFAULT, array('cost' => $hash_cost_factor));

			$query_select = $this->db->prepare("SELECT USER_PASSWORD_HASH FROM users WHERE USER_ID = :USER_ID LIMIT 1");
			$query_select->bindParam(':USER_ID', $user_id, PDO::PARAM_INT);
			$query_select->execute();
			$resultado = $query_select->fetch();
			$password_antiga = $resultado->USER_PASSWORD_HASH;

			if (password_verify($old_password, $password_antiga)) {

				$query = $this->db->prepare("UPDATE users
					SET USER_PASSWORD_HASH=:NEW_USER_PASSWORD_HASH
					WHERE USER_ID=:USER_ID  ");
				$query->bindParam(':NEW_USER_PASSWORD_HASH', $new_user_password_hash, PDO::PARAM_STR);
				$query->bindParam(':USER_ID', $user_id, PDO::PARAM_INT);
				$query->execute();

				$count = $query->rowCount();
				if ($count != 1) {
					return false;
				}

			} else {
				return false;
			}

			return true;

		} else {
			return false;
		}

	}

	public function exportInfo($parametros) {

		$formato = $parametros['formato'];

		if ($formato == "1") {
			$formato_file = 'txt';
			$link_file = $this->exportInfoToTxt();

		} else if ($formato == "2") {
			$formato_file = 'csv';
			$link_file = $this->exportInfoToCsv();
		} else {
			$formato_file = 'txt';
			$link_file = '0';
		}

		$resultado = array('link_file' => $link_file, 'formato_file' => $formato_file);

		return $resultado;
	}

	public function exportInfoToCsv() {
		$user_id = $_SESSION['su_id'];
		$string_nome = $user_id . date("YmdHis") . rand(0, 9999);
		$nome_ficheiro = UPLOADS_DIRECTORY . $string_nome . ".csv";
		$nome_ficheiro_local = UPLOADS_DIRECTORY_LOCAL . $string_nome . ".csv";
		$hoje = date("Y-m-d");

		try {

			$conteudo_ficheiro = '';

			$query = $this->db->prepare("SELECT ID,  TITULO, DESCRICAO, LINK, IMAGEM, CATEGORIA
				FROM websites
				WHERE USER_ID = :USER_ID
				ORDER BY CATEGORIA,ORDEM ASC");
			$query->bindParam(':USER_ID', $user_id, PDO::PARAM_INT);
			$query->execute();
			//$query->execute(array(':USER_ID' => $user_id));
			$websites = $query->fetchAll();

			$conteudo_ficheiro .= '"WEBSITE_ID";';
			$conteudo_ficheiro .= '"WEBSITE_NAME";';
			$conteudo_ficheiro .= '"WEBSITE_URL";';
			$conteudo_ficheiro .= '"WEBSITE_DESCRIPTION";';
			$conteudo_ficheiro .= '"CATEGORY_ID";';
			$conteudo_ficheiro .= '"CATEGORY_NAME";';
			$conteudo_ficheiro .= '"CATEGORY_COLOR1";';
			$conteudo_ficheiro .= '"CATEGORY_COLOR2";';
			$conteudo_ficheiro .= '"CATEGORY_ORDER";';
			$conteudo_ficheiro .= PHP_EOL;

			foreach ($websites as $website) {
				$website_id = $website->ID;
				$website_titulo = $website->TITULO;
				$website_descricao = $website->DESCRICAO;
				$website_link = $website->LINK;
				$website_categoria = $website->CATEGORIA;

				$website_titulo = utf8_decode($website_titulo);

				if ($website_categoria != "0") {
					$query_select = $this->db->prepare("SELECT ID, NOME, COR_BACKGROUND, COR_TEXTO, ORDEM
						FROM categorias
						WHERE USER_ID = :USER_ID AND ID=:ID_CATEGORIA LIMIT 1");
					$query_select->bindParam(':USER_ID', $user_id, PDO::PARAM_INT);
					$query_select->bindParam(':ID_CATEGORIA', $website_categoria, PDO::PARAM_INT);
					$query_select->execute();
					$categoria = $query_select->fetch();
					$categoria_id = $categoria->ID;
					$categoria_nome = $categoria->NOME;
					$categoria_cor1 = $categoria->COR_BACKGROUND;
					$categoria_cor2 = $categoria->COR_TEXTO;
					$categoria_ordem = $categoria->ORDEM;
				} else {
					$categoria_id = "0";
					$categoria_nome = "Unorganized";
					$categoria_cor1 = "";
					$categoria_cor2 = "";
					$categoria_ordem = "0";
				}

				$conteudo_ficheiro .= '"' . $website_id . '";';
				$conteudo_ficheiro .= '"' . $website_titulo . '";';
				$conteudo_ficheiro .= '"' . $website_link . '";';
				$conteudo_ficheiro .= '"' . $website_descricao . '";';
				$conteudo_ficheiro .= '"' . $categoria_id . '";';
				$conteudo_ficheiro .= '"' . $categoria_nome . '";';
				$conteudo_ficheiro .= '"' . $categoria_cor1 . '";';
				$conteudo_ficheiro .= '"' . $categoria_cor2 . '";';
				$conteudo_ficheiro .= '"' . $categoria_ordem . '";';
				$conteudo_ficheiro .= PHP_EOL;

			}

			$ficheiro = fopen($nome_ficheiro_local, "w");
			fwrite($ficheiro, $conteudo_ficheiro);
			fclose($ficheiro);

			$link_file = $nome_ficheiro;

		} catch (Exception $e) {

			$link_file = '0';
			$app = Slim::getInstance();
			$app->log->info("erro ao criar ficheiro: " . $e);
		}

		return $link_file;

	}

	public function exportInfoToTxt() {
		$user_id = $_SESSION['su_id'];
		$string_nome = $user_id . date("YmdHis") . rand(0, 9999);
		$nome_ficheiro = UPLOADS_DIRECTORY . $string_nome . ".txt";
		$nome_ficheiro_local = UPLOADS_DIRECTORY_LOCAL . $string_nome . ".txt";
		$hoje = date("Y-m-d");

		try {

			$conteudo_ficheiro = '';

			$query = $this->db->prepare("SELECT ID, NOME
				FROM categorias
				WHERE USER_ID = :USER_ID
				ORDER BY ORDEM ASC ");
			$query->bindParam(':USER_ID', $user_id, PDO::PARAM_INT);
			$query->execute();
			//$query->execute(array( ':USER_ID' => $user_id ));
			$categorias = $query->fetchAll();

			foreach ($categorias as $categoria) {
				$categoria_id = $categoria->ID;
				$categoria_nome = $categoria->NOME;

				$conteudo_ficheiro .= "\n" . strtoupper($categoria_nome) . "\n";

				$query2 = $this->db->prepare("SELECT ID,  TITULO, DESCRICAO, LINK, IMAGEM, CATEGORIA
					FROM websites
					WHERE USER_ID = :USER_ID AND CATEGORIA = :CATEGORIA_ID
					ORDER BY ORDEM ASC");
				$query2->bindParam(':USER_ID', $user_id, PDO::PARAM_INT);
				$query2->bindParam(':CATEGORIA_ID', $categoria_id, PDO::PARAM_INT);
				$query2->execute();
				//$query2->execute(array(':USER_ID' => $user_id, ':CATEGORIA_ID' => $categoria_id ));
				$websites = $query2->fetchAll();

				foreach ($websites as $website) {
					$website_id = $website->ID;
					$website_titulo = $website->TITULO;
					$website_descricao = $website->DESCRICAO;
					$website_link = $website->LINK;

					$website_titulo = utf8_decode($website_titulo);

					$conteudo_ficheiro .= "\n" . $website_titulo;
					$conteudo_ficheiro .= "\n" . $website_link;

				}

				$conteudo_ficheiro .= "\n\n\n";
			}

			$conteudo_ficheiro .= "\n\nWALLFAV";
			$conteudo_ficheiro .= "\nhttp://www.wallfav.com";
			$conteudo_ficheiro .= "\n" . $hoje;

			$ficheiro = fopen($nome_ficheiro_local, "w");
			fwrite($ficheiro, $conteudo_ficheiro);
			fclose($ficheiro);

			$link_file = $nome_ficheiro;

		} catch (Exception $e) {
			$link_file = '0';
			$app = Slim::getInstance();
			$app->log->info("erro ao criar ficheiro: " . $e);
		}

		return $link_file;
	}

}

?>
