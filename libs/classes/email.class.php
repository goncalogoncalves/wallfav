<?php

class email {

	public function sendEmail($de_nome, $de_email, $para_nome, $para_email, $assunto, $mensagem)
	{

		$mail = new PHPMailer;

		$mail->IsSMTP();
		$mail->SMTPAuth  = EMAIL_SMTP_AUTH;
		if (defined(EMAIL_SMTP_ENCRYPTION)) {
			$mail->SMTPSecure = EMAIL_SMTP_ENCRYPTION;
		}
		$mail->Host     = EMAIL_SMTP_HOST;
		$mail->Username = EMAIL_SMTP_USERNAME;
		$mail->Password = EMAIL_SMTP_PASSWORD;
		$mail->IsHTML(true);


		$mail->From     = $de_email;
		$mail->FromName = $de_nome;
		$mail->AddAddress($para_email);
		$mail->Subject  = $assunto;
		$mail->Body     = $mensagem;

		if($mail->Send()) {
			return true;
		} else {
			$erro = $mail->ErrorInfo;
			return $erro;
		}
	}
}

?>
