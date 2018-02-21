<?php
/**
 *
 * Captcha
 * Classe responsavel pela criação e validação de captchas
 * Versão 1.0 / 25-11-2013
 *
 * Dependencias: para o refresh funcionar (novo captcha) é preciso adicionar javascript:
 * function trocar_captcha(){$.ajax({url: "includes/trata_captcha.php",cache: false,data: { aux: "devolve_novo_captcha"}}).done(function( resposta ) {$('#div_container_captcha').html(resposta);});}
 * (não esquecer do ficheiro includes/trata_captcha.php e incluir esta classes através dos includes ou outro sistema)
 * colocar no definitions: define("codigo_captcha" , "");
 *
 * Exemplo de utilização:
 * Novo captcha:
 * $captcha = new captcha(); $html_captcha = $captcha->criar_captcha(); echo $html_captcha;
 *
 * Validar captcha:
 * $captcha = new captcha(); $captcha_valido = $captcha->validar_captcha($_POST["input-captcha"]);
 *
 */

namespace CodigoUtil;

class captcha {

	public $codigo_captcha 			= "";
	public $imagem_largura 			= 180;
	public $imagem_altura 			= 50;
	public $nr_caracteres 			= 4;
	public $array_fontes 			= array('claredon.ttf', 'courier_bold.ttf', 'toledo.ttf','valken.ttf', 'impact.ttf'); //whimsy.ttf
	//public $carateres_possiveis 	= '23456789ABCDEFGHKLMNPQRSTUVYWXZ';
	//public $carateres_possiveis 	= '1234567890';
	public $carateres_possiveis 	= 'ABCDEFGHKLMNPQRSTUVYWXZ';
	public $caminho_fonte 			= "";
	public $adicionar_pontos 		= 1;
	public $nr_pontos 				= 3000;
	public $adicionar_linhas 		= 0;
	public $nr_linhas 				= 10;
	public $adicionar_rectangulos 	= 0;
	public $nr_rectangulos 			= 20;
	public $adicionar_circulos 		= 0;
	public $nr_circulos 			= 30;
	public $cor_barra_tools 		= "#ddd";


	public function __construct($caminho_fonte = 'fonts/'){
		$this->caminho_fonte = RELATIVE_URL . $caminho_fonte;
	}

	/**
	 * Gera uma nova imagem de captcha
	 * @return string html do captcha (imagem, input, botao refresh)
	 */
	public function criar_captcha() {

		ob_start();

		$temp_codigo = '';
		$i = 0;
		// criar codigo
		while ($i < $this->nr_caracteres) {
			// vai buscar aleatoriamente uma letra
			$temp_codigo .= substr($this->carateres_possiveis, mt_rand(0, strlen($this->carateres_possiveis)-1), 1);
			$i++;
		}

		//header('Content-Type: image/png');
		$imagem 	= imagecreatetruecolor($this->imagem_largura , $this->imagem_altura);
		$cor_branca = imagecolorallocate($imagem, 255, 255, 255);

		// rectangulo de fundo
		imagefilledrectangle($imagem, 0, 0, $this->imagem_largura, $this->imagem_altura, $cor_branca);

		// adicionar pontos
		if ($this->adicionar_pontos) {
			for ($c = 0; $c < $this->nr_pontos; $c++){
				$cor_cinza 		= rand(0, 200);
				$cor_ponto 		= imagecolorallocate($imagem, $cor_cinza, $cor_cinza, $cor_cinza);
				$x = rand(0,$this->imagem_largura-1);
				$y = rand(0,$this->imagem_altura-1);
				imagesetpixel($imagem, $x, $y, $cor_ponto);
			}
		}

		// adicionar linhas
		if ($this->adicionar_linhas) {
			for ($c = 0; $c < $this->nr_linhas; $c++){
				$cor_aleatoria = strtoupper(dechex(rand(0,10000000)));
				$x 	= rand(0,$this->imagem_largura-1);
				$y 	= rand(0,$this->imagem_altura-1);
				$x2 = rand(0,$this->imagem_largura-1);
				$y2 = rand(0,$this->imagem_altura-1);
				imageline($imagem, $x, $y, $x2, $y2, $cor_aleatoria);
			}
		}

		// adicionar rectangulos
		if ($this->adicionar_rectangulos) {
			for ($c = 0; $c < $this->nr_rectangulos; $c++){
				$cor_aleatoria = strtoupper(dechex(rand(0,10000000)));
				$x 	= rand(0,$this->imagem_largura-1);
				$y 	= rand(0,$this->imagem_altura-1);
				$x2 = rand(0,$this->imagem_largura-1);
				$y2 = rand(0,$this->imagem_altura-1);
				imagerectangle($imagem, $x, $y, $x2, $y2, $cor_aleatoria);
			}
		}

		// adicionar circulos
		if ($this->adicionar_circulos) {
			for ($c = 0; $c < $this->nr_circulos; $c++){
				$cor_cinza 		= rand(0, 200);
				$cor_circulo	= imagecolorallocate($imagem, $cor_cinza, $cor_cinza, $cor_cinza);
				$x 	= rand(0,$this->imagem_largura-1);
				$y 	= rand(0,$this->imagem_altura-1);
				$tamanho_circulo = rand(3, 6);
				//imagearc($imagem,  $x,  $y,  $tamanho_circulo,  $tamanho_circulo,  0, 360, $cor_circulo);
				imagefilledellipse($imagem, $x, $y, $tamanho_circulo, $tamanho_circulo, $cor_circulo);
			}
		}

		// adicionar texto letra a letra
		for ($c = 0; $c < $this->nr_caracteres; $c++){
			$cor_cinza 		= rand(0, 100);
			$cor_texto 		= imagecolorallocate($imagem, $cor_cinza, $cor_cinza, $cor_cinza);
			$fonte 			= $this->caminho_fonte.$this->array_fontes[rand(0, sizeof($this->array_fontes)-1)];
			$angulo 		= rand(-20, 20);
			$tamanho_letra 	= rand(20, 28);
			$x = $c*25+5;
			$y = rand(25,45);
			$letra = substr($this->carateres_possiveis, mt_rand(0, strlen($this->carateres_possiveis)-1), 1);

			// goncalo bonus
			/*switch ($c) {
			    case 0: $letra = "G"; break;
			    case 1: $letra = "O"; break;
			    case 2: $letra = "N"; break;
			    case 3: $letra = "C"; break;
			    case 4: $letra = "A"; break;
			    case 5: $letra = "L"; break;
			    case 6: $letra = "O"; break;
			    case 7: $letra = "!"; break;
			}*/

			imagettftext($imagem, $tamanho_letra, $angulo, $x, $y, $cor_texto, $fonte, $letra);
			$this->codigo_captcha = $this->codigo_captcha.$letra;
		}

		imagepng($imagem);
		imagedestroy($imagem);
		$imagem_final = ob_get_clean();

		//session_start();
		$_SESSION['cod_captcha'] = $this->codigo_captcha;

		$html_imagem = "<img alt='Captcha' title='Captcha' src='data:image/jpeg;base64," . base64_encode( $imagem_final )."'>";

		$html_captcha = '
		<table style="width:200px; height:80px; margin-top: 15px;">';
			$html_captcha .= '<tr>
			<td>'.$html_imagem.'
				<div style=" position: absolute; margin-top: -50px; margin-left: 182px; background-color: #fff; width: 17px; padding-left: 5px; padding-bottom: 2px; height: 49px; background-color: '.$this->cor_barra_tools.';">
					<a href="javascript:addCaptcha();" title="Change captcha"><img src="'.BASE_URL.'img/refresh.png"></a>
				</div>
			</td>
			</tr>
			<tr>
				<td><input placeholder="Write here the letters" autocomplete="off" id="input-captcha" name="input-captcha" style="width:200px; height:30px;"></td>
			</tr>
		</table>';

		return $html_captcha;
	}

	/**
	 * Permite verificar se o texto inserido pelo user esta correto
	 * @param  string $valor texto inserido pelo user
	 * @return bolean retorna se o captcha esta correto
	 */
	public function validar_captcha($valor){

		$codigo_correto = 0;
		//session_start();
		$codigo_captcha = $_SESSION['cod_captcha'];

		if ($codigo_captcha == $valor) {
			$codigo_correto = 1;
		}

		return $codigo_correto;
	}

}

?>
