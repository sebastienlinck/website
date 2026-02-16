<?php
header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

// Traitement du formulaire uniquement si envoyé
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['envoyer'])) {
  // 1. PROTECTION HONEYPOT (Anti-Robot)
  if (!empty($_POST['website_check'])) {
    die(json_encode(['success' => false, 'message' => '']));
  }

  // 2. NETTOYAGE DES ENTRÉES
  $nom_saisi = strip_tags(trim($_POST['nom'] ?? ''));
  $email_saisi = filter_var(trim($_POST['mail'] ?? ''), FILTER_SANITIZE_EMAIL);
  $message_saisi = strip_tags(trim($_POST['message'] ?? ''));

  // 3. VALIDATION
  if (empty($nom_saisi) || empty($message_saisi) || !filter_var($email_saisi, FILTER_VALIDATE_EMAIL)) {
    $response['message'] = "Merci de vérifier votre email et de remplir tous les champs.";
  } else {
    // 4. PRÉPARATION DE L'EMAIL
    $to = 'contact@slinck.com';
    $subject = 'Message de ' . $nom_saisi . ' (via slinck.com)';
    $headers = [
      'MIME-Version: 1.0',
      'Content-type: text/html; charset=utf-8',
      'From: contact@slinck.com',
      'Reply-To: ' . $email_saisi,
      'X-Mailer: PHP/' . phpversion()
    ];
    $body = "
      <html>
      <head>
        <title>Nouveau message de $nom_saisi</title>
      </head>
      <body>
        <div style='font-family: Arial, sans-serif; padding: 20px; border: 1px solid #ddd;'>
          <h2 style='color: #333;'>Nouveau message reçu</h2>
          <p><strong>De :</strong> $nom_saisi</p>
          <p><strong>Email :</strong> <a href='mailto:$email_saisi'>$email_saisi</a></p>
          <hr>
          <p style='white-space: pre-line;'>" . htmlspecialchars($message_saisi) . "</p>
        </div>
      </body>
      </html>
    ";

    // 5. ENVOI
    if (mail($to, $subject, $body, implode("\r\n", $headers))) {
      $response['success'] = true;
      $response['message'] = "Votre message a bien été envoyé.";
    } else {
      $response['message'] = "Erreur technique lors de l'envoi du message.";
    }
  }
}

echo json_encode($response);
exit;
