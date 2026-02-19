<?php

/**
 * Traitement sécurisé du formulaire de contact
 * Version : 2.0 (Support AJAX + PHPMailer + Captcha Server-side)
 */

header('Content-Type: application/json');

// --- 1. CHARGEMENT DE PHPMAILER ---
$path = __DIR__ . '/PHPMailer/'; // Chemin basé sur votre structure actuelle

if (file_exists($path . 'Exception.php')) {
  require $path . 'Exception.php';
  require $path . 'PHPMailer.php';
  require $path . 'SMTP.php';
} else {
  echo json_encode(['success' => false, 'message' => 'Erreur critique : PHPMailer est introuvable.']);
  exit;
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Initialisation de la réponse
$response = ['success' => false, 'message' => 'Une erreur est survenue.'];

// --- 2. LOGIQUE DE TRAITEMENT ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['envoyer'])) {

  // A. HONEYPOT (Anti-spam robotique)
  if (!empty($_POST['website_check'])) {
    exit; // Arrêt silencieux pour les robots
  }

  // B. NETTOYAGE DES ENTRÉES
  $nom     = strip_tags(trim($_POST['nom'] ?? ''));
  $email   = filter_var(trim($_POST['mail'] ?? ''), FILTER_SANITIZE_EMAIL);
  $message = strip_tags(trim($_POST['message'] ?? ''));

  // Données du CAPTCHA (provenant des champs cachés ajoutés en JS)
  $user_ans = intval($_POST['captcha'] ?? 0);
  $n1       = intval($_POST['c_n1'] ?? 0);
  $n2       = intval($_POST['c_n2'] ?? 0);
  $op       = $_POST['c_op'] ?? '';

  // C. VALIDATION DU CAPTCHA (Double vérification serveur)
  $expected = 0;
  switch ($op) {
    case 'plus':
      $expected = $n1 + $n2;
      break;
    case 'moins':
      $expected = $n1 - $n2;
      break;
    case 'fois':
      $expected = $n1 * $n2;
      break;
  }

  // D. VÉRIFICATIONS FINALES
  if (empty($nom) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $response['message'] = "Veuillez remplir correctement tous les champs.";
  } elseif ($user_ans !== $expected) {
    $response['message'] = "Calcul de sécurité incorrect. Veuillez réessayer.";
  } else {
    // E. ENVOI VIA PHPMailer (Configuration SMTP)
    $mail = new PHPMailer(true);

    try {
      // Paramètres Serveur
      $mail->isSMTP();
      $mail->Host       = 'slinck.com';
      $mail->SMTPAuth   = true;
      $mail->Username   = 'contact@slinck.com';
      $mail->Password   = '@sh417aH8'; // À placer dans un fichier .env en production
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
      $mail->Port       = 465;
      $mail->CharSet    = 'UTF-8';

      // Destinataires
      $mail->setFrom('contact@slinck.com', 'Site Web Slinck');
      $mail->addAddress('contact@slinck.com');
      $mail->addReplyTo($email, $nom);

      // Contenu
      $mail->isHTML(true);
      $mail->Subject = "Nouveau message de $nom (via slinck.com)";
      $mail->Body    = "
                <div style='font-family: sans-serif; line-height: 1.6;'>
                    <h2 style='color: #2c3e50;'>Nouveau message reçu</h2>
                    <p><strong>De :</strong> $nom ($email)</p>
                    <div style='background: #f9f9f9; padding: 15px; border-left: 4px solid #3498db;'>
                        " . nl2br(htmlspecialchars($message)) . "
                    </div>
                </div>";

      $mail->AltBody = "De: $nom ($email)\n\nMessage:\n$message";

      $mail->send();

      $response['success'] = true;
      $response['message'] = "Votre message a bien été envoyé. Merci !";
    } catch (Exception $e) {
      $response['message'] = "Le message n'a pu être envoyé. Erreur SMTP.";
      // Optionnel pour débug : 
      $response['debug'] = $mail->ErrorInfo;
    }
  }
}

// Envoi de la réponse JSON au script JS
echo json_encode($response);
