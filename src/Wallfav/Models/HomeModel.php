<?php

class HomeModel {

	public function __construct($db) {
		try {
			$this->db = $db;
		} catch (PDOException $e) {
			exit('Database connection could not be established.');
		}
	}

	public function deleteAccountNow($parametros) {

		//$texto_feedback = mysql_real_escape_string($parametros['texto_feedback']);
		$texto_feedback = $parametros['texto_feedback'];

		$user_id = $_SESSION['su_id'];
		$agora = date("Y-m-d G:i:s");
		$deleted_user = '0';

		// adicionar feedback
		if ($texto_feedback != "") {
			$query = $this->db->prepare("INSERT INTO feedback (USER_ID, TEXTO, DATA) VALUES (:USER_ID, :TEXTO, :DATA) ");
			$query->bindParam(':USER_ID', $deleted_user, PDO::PARAM_STR);
			$query->bindParam(':TEXTO', $texto_feedback, PDO::PARAM_STR);
			$query->bindParam(':DATA', $agora, PDO::PARAM_STR);
			$query->execute();

		}

		// eliminar websites (retirar o id do user)
		$query = $this->db->prepare("UPDATE websites SET USER_ID=0, CATEGORIA=0 WHERE USER_ID=:USER_ID ");
		$query->bindParam(':USER_ID', $user_id, PDO::PARAM_INT);
		$query->execute();

		// eliminar categorias
		$query = $this->db->prepare("UPDATE categorias SET USER_ID=0 WHERE USER_ID=:USER_ID ");
		$query->bindParam(':USER_ID', $user_id, PDO::PARAM_INT);
		$query->execute();

		// eliminar info do user
		$sql = "DELETE FROM users WHERE USER_ID = :USER_ID";
		$query = $this->db->prepare($sql);
		$query->bindParam(':USER_ID', $user_id, PDO::PARAM_INT);
		$query->execute();

		// eliminar variaveis de sessao e cookies
		setcookie('rememberme', false, time() - (3600 * 3650), '/', COOKIE_DOMAIN);
		$_SESSION = array();
		session_destroy();

		// TODO: enviar email a dizer o quanto lamentamos

		return true;
	}

}

?>
