<?php

namespace Principal;

use Rain\Tpl;

class Mailer {

	const USERNAME = "gabrielsventura1995@gmail.com";//para o email do remetentes ser sempre o mesmo
	const PASSWORD = "sventura10";
	const NAME_FROM = "Ventura Store";

	private $mail;

    //endereco do destino, nome do destino, assunto, nome do template, dados do template
	public function __construct($toAdress, $toName, $subject, $tplName, $data = array()){

		$config = array(
			"tpl_dir"       => $_SERVER["DOCUMENT_ROOT"]."/views/email/",//caminho do template de email
			"cache_dir"     => $_SERVER["DOCUMENT_ROOT"]."/views-cache/",
			"debug"         => false
				   );

	Tpl::configure( $config );

	$tpl = new Tpl;

	foreach ($data as $key => $value) {
		
		$tpl->assign($key, $value);
	}

	$html = $tpl->draw($tplName, true);


			//Create a new PHPMailer instance
			$this->mail = new \PHPMailer;


			//Tell PHPMailer to use SMTP
			$this->mail->isSMTP();

			//Enable SMTP debugging
			// 0 = off (for production use)
			// 1 = client messages
			// 2 = client and server messages
			$this->mail->SMTPDebug = 2;//exibe mesagens técnicas, o que está acontecendo, 1 - Exibe mensagens simples, 0 - não exibe nada 

			//Set the hostname of the mail server
			$this->mail->Host = 'smtp.gmail.com';
			// use
			// $this->mail->Host = gethostbyname('smtp.gmail.com');
			// if your network does not support SMTP over IPv6

			//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
			$this->mail->Port = 587;//porta do gmail

			//Set the encryption system to use - ssl (deprecated) or tls
			$this->mail->SMTPSecure = 'tls';

			//Whether to use SMTP authentication
			$this->mail->SMTPAuth = true;

			//Username to use for SMTP authentication - use full email address for gmail
			$this->mail->Username = Mailer::USERNAME;//constante username

			//Password to use for SMTP authentication
			$this->mail->Password = Mailer::PASSWORD;//constante password

			//Set who the message is to be sent from
			$this->mail->setFrom(Mailer::USERNAME, Mailer::NAME_FROM);//email e nome do remetente

			//Set an alternative reply-to address
			//$this->mail->addReplyTo('replyto@example.com', 'First Last');

			//Set who the message is to be sent to
			$this->mail->addAddress($toAdress, $toName);//email destinatário

			//Set the subject line //assunto
			$this->mail->Subject = $subject;//assunto

			//Read an HTML message body from an external file, convert referenced images to embedded,
			//convert HTML into a basic plain-text alternative body
			$this->mail->msgHTML($html);

			//Replace the plain text body with one created manually
			$this->mail->AltBody = 'error';//caso o html não funcionar

			//Attach an image file
			//$this->mail->addAttachment('images/phpmailer_mini.png');


		}

		public function send(){

			return $this->mail->send();

		}
	}

?>