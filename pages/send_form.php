<?php
	session_start();
	if(isset($_POST['g-recaptcha-response'])){
		$captcha=$_POST['g-recaptcha-response'];
	}
	
	$url = 'https://www.google.com/recaptcha/api/siteverify';
	$privatekey = "6LcBwRgUAAAAAACkjLU8TC6gwBYT9z7_-tV4-k2w";
	$response = file_get_contents($url."?secret=".$privatekey."&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']);
	$data = json_decode($response);
	
	
	if(isset($data->success) AND $data->success==true)
	{
		$_SESSION['success'] = 1;
		$to = 'contact@slinck.com';
		
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
		$headers .= 'From: ' . htmlspecialchars($_POST['email'] . "\r\n");
		
		$subject = 'Message envoyé par ' . htmlspecialchars($_POST['name']);
		$message_content = '
		<html>
		<body>
		<table>
		<tr>
		<td><b>Sujet du message:</b></td>
		</tr>
		<tr>
		<td>'. $subject . '</td>
		</tr>
		<tr>
		<td><b>Contenu du message:</b></td>
		</tr>
		<tr>
		<td>'. htmlspecialchars($_POST['message']) .'</td>
		</tr>
		</table>
		</body>
		</html>
		';
		mail($to, $subject, $message_content, $headers);
		header('Location: ../contact');
	}
	else
	{
		$_SESSION['success'] = 0;
		$_SESSION['inputs'] = $_POST;
		header('Location: ../contact');
	}
?>
