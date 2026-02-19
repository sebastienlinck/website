<?php

/**
 * Traitement sécurisé du formulaire de contact - EiSINe
 * Version : 2.1 (AJAX + PHPMailer + Captcha Server-side + .env)
 */

header('Content-Type: application/json');

// --- 1. CHARGEMENT DE L'ENVIRONNEMENT ---

/**
 * Charge les variables d'un fichier .env dans l'environnement du script
 * @param string $path Chemin vers le fichier .env
 */
function loadEnv(string $path): void
{
  if (!file_exists($path)) return;

  $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  foreach ($lines as $line) {
    if (strpos(trim($line), '#') === 0) continue; // Ignorer les commentaires

    $parts = explode('=', $line, 2);
    if (count($parts) === 2) {
      putenv(trim($parts[0]) . "=" . trim($parts[1]));
    }
  }
}

loadEnv(__DIR__ . '/../.env');

// --- 2. DÉPENDANCES ET INITIALISATION ---

$pathMailer = __DIR__ . '/../vendor/PHPMailer/';

if (!file_exists($pathMailer . 'Exception.php')) {
  echo json_encode(['success' => false, 'message' => 'Erreur système : PHPMailer manquant.']);
  exit;
}

require $pathMailer . 'Exception.php';
require $pathMailer . 'PHPMailer.php';
require $pathMailer . 'SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$response = ['success' => false, 'message' => 'Requête invalide.'];

// --- 3. TRAITEMENT DES DONNÉES ---

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['envoyer'])) {

  // A. Honeypot (Anti-Spam)
  if (!empty($_POST['website_check'])) {
    exit;
  }

  // B. Récupération et Nettoyage
  $nom     = strip_tags(trim($_POST['nom'] ?? ''));
  $email   = filter_var(trim($_POST['mail'] ?? ''), FILTER_SANITIZE_EMAIL);
  $message = strip_tags(trim($_POST['message'] ?? ''));

  // C. Validation du CAPTCHA (Vérification serveur)
  $userAns  = intval($_POST['captcha'] ?? 0);
  $num1     = intval($_POST['c_n1'] ?? 0);
  $num2     = intval($_POST['c_n2'] ?? 0);
  $operator = $_POST['c_op'] ?? '';

  $expected = 0;
  switch ($operator) {
    case 'plus':
      $expected = $num1 + $num2;
      break;
    case 'moins':
      $expected = $num1 - $num2;
      break;
    case 'fois':
      $expected = $num1 * $num2;
      break;
  }

  // D. Validation métier
  if (empty($nom) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $response['message'] = "Veuillez vérifier les informations saisies.";
  } elseif ($userAns !== $expected) {
    $response['message'] = "Sécurité : Le calcul est incorrect.";
  } else {
    // E. Envoi via SMTP Authentifié
    $mail = new PHPMailer(true);

    try {
      // Configuration SMTP via getenv()
      $mail->isSMTP();
      $mail->Host       = getenv('SMTP_HOST');
      $mail->SMTPAuth   = true;
      $mail->Username   = getenv('SMTP_USER');
      $mail->Password   = getenv('SMTP_PASS');
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
      $mail->Port       = (int)getenv('SMTP_PORT');
      $mail->CharSet    = 'UTF-8';

      // Expéditeur et Destinataire
      $mail->setFrom(getenv('MAIL_FROM'), getenv('MAIL_FROM_NAME'));
      $mail->addAddress(getenv('MAIL_TO'));
      $mail->addReplyTo($email, $nom);

      // Contenu du courriel
      $mail->isHTML(true);
      $mail->Subject = "Contact slinck.com : $nom";
      $mail->Body    = "
                <div style='font-family: Arial, sans-serif; border: 1px solid #eee; padding: 20px;'>
                    <h2 style='color: #2c3e50;'>Nouveau message de contact</h2>
                    <p><strong>Nom :</strong> $nom</p>
                    <p><strong>Email :</strong> <a href='mailto:$email'>$email</a></p>
                    <div style='margin-top: 20px; padding: 15px; background: #f8f9fa; border-left: 5px solid #007bff;'>
                        " . nl2br(htmlspecialchars($message)) . "
                    </div>
                </div>";

      $mail->AltBody = "Nom: $nom\nEmail: $email\n\nMessage:\n$message";

      $mail->send();

      $response['success'] = true;
      $response['message'] = "Votre message a été envoyé avec succès.";
    } catch (Exception $e) {
      $response['message'] = "Erreur SMTP : Le message n'a pu être transmis.";
    }
  }
}

echo json_encode($response);
