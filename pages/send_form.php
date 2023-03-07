<?php
	session_start();
	function honeypot_validade($req) {
		if (!empty($req)) {
			$honeypot_fields = ["name","email","message"];
			foreach ($honeypot_fields as $field) {
				if (isset($req[$field]) && !empty($req[$field])) {
					return false;
				}
			}
		}
		return true;
	}
	if (honeypot_validade($_REQUEST)) {
		$_SESSION['success'] = 1;
		$to = 'contact@slinck.com';
		
		$headers = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
		$headers .= 'From: '.htmlspecialchars($_REQUEST['emailslinck'] . "\r\n");
		
		$subject = 'Message envoyé par '.htmlspecialchars($_REQUEST['nameslinck']);
		$subject = trim(iconv_mime_encode('',$subject,array("input-charset"=>"UTF-8","output-charset"=>"UTF-8")),' :');
		$message_content = '<html><body><p><b>Contenu du message:</b></p><p>';
		$message_content .= htmlspecialchars($_REQUEST['messageslinck']);
		$message_content .= '</p></body></html>';
		mail($to, $subject, $message_content, $headers);	
	} 
	else {
		$_SESSION['success'] = 0;
	}
	header('Location: ../contact');
	exit();
?>