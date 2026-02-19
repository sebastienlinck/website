<?php
header('Content-Type: application/json');

// Inclusion des classes PHPMailer
$path = __DIR__ . '/PHPMailer/'; // Ajustez le chemin si nécessaire
require $path . 'Exception.php';
require $path . 'PHPMailer.php';
require $path . 'SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$response = ['success' => false, 'message' => ''];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['envoyer'])) {

  // 1. Honeypot
  if (!empty($_POST['website_check'])) {
    exit;
  }

  // 2. Nettoyage
  $nom = strip_tags(trim($_POST['nom'] ?? ''));
  $email = filter_var(trim($_POST['mail'] ?? ''), FILTER_SANITIZE_EMAIL);
  $message = strip_tags(trim($_POST['message'] ?? ''));

  // 3. Validation
  if (empty($nom) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $response['message'] = "Veuillez vérifier les informations saisies.";
  } else {
    $mail = new PHPMailer(true);
    try {
      // Configuration SMTP (Paramètres de votre premier fichier)
      $mail->isSMTP();
      $mail->Host       = 'slinck.com';
      $mail->SMTPAuth   = true;
      $mail->Username   = 'contact@slinck.com';
      $mail->Password   = '@sh417aH8';
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
      $mail->Port       = 465;
      $mail->CharSet    = 'UTF-8';

      // Destinataires
      $mail->setFrom('contact@slinck.com', 'Site Slinck');
      $mail->addAddress('contact@slinck.com');
      $mail->addReplyTo($email, $nom);

      // Contenu
      $mail->isHTML(true);
      $mail->Subject = "Nouveau message de $nom";
      $mail->Body    = "<strong>Nom:</strong> $nom<br><strong>Email:</strong> $email<hr>" . nl2br(htmlspecialchars($message));

      $mail->send();
      $response['success'] = true;
      $response['message'] = "Votre message a bien été envoyé.";
    } catch (Exception $e) {
      $response['message'] = "Erreur technique : {$mail->ErrorInfo}";
    }
  }
}

echo json_encode($response);
