<?php
// --- LOGIQUE PHP (À placer en haut du fichier) ---

$message_statut = '';
$type_statut = ''; // 'success' ou 'error'

// Initialisation des variables pour éviter les erreurs "undefined variable" dans le HTML
$nom_saisi = '';
$email_saisi = '';
$message_saisi = '';

// Traitement du formulaire uniquement si envoyé
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['envoyer'])) {

  // 1. PROTECTION HONEYPOT (Anti-Robot)
  // Si le champ caché "website_check" est rempli, c'est un robot -> on arrête tout.
  if (!empty($_POST['website_check'])) {
    die();
  }

  // 2. NETTOYAGE DES ENTRÉES
  $nom_saisi = strip_tags(trim($_POST['nom'] ?? ''));
  $email_saisi = filter_var(trim($_POST['mail'] ?? ''), FILTER_SANITIZE_EMAIL);
  $message_saisi = strip_tags(trim($_POST['message'] ?? ''));

  // 3. VALIDATION
  if (empty($nom_saisi) || empty($message_saisi) || !filter_var($email_saisi, FILTER_VALIDATE_EMAIL)) {
    $message_statut = "Merci de vérifier votre email et de remplir tous les champs.";
    $type_statut = "error";
  } else {
    // 4. PRÉPARATION DE L'EMAIL
    $to = 'contact@dev.slinck.com'; // Votre adresse de réception

    // Encodage du sujet pour gérer les accents
    $subject = 'Message de ' . $nom_saisi . ' (via slinck.com)';

    // Configuration des en-têtes pour éviter le SPAM
    // Le "From" doit être une adresse de VOTRE domaine (ex: contact@slinck.com)
    // Le "Reply-To" permet de répondre directement à l'internaute
    $headers = [
      'MIME-Version: 1.0',
      'Content-type: text/html; charset=utf-8',
      'From: contact@dev.slinck.com',
      'Reply-To: ' . $email_saisi,
      'X-Mailer: PHP/' . phpversion()
    ];

    // Corps du message en HTML propre
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
      $message_statut = "Votre message a bien été envoyé.";
      $type_statut = "success";
      // On vide les champs après succès
      $nom_saisi = $email_saisi = $message_saisi = '';
    } else {
      $message_statut = "Erreur technique lors de l'envoi du message.";
      $type_statut = "error";
    }
  }
}
?>

<div class="bento-grid">

  <article class="card span-1 card-c4 centered">
    <img class="img-avatar" src="img/linck.webp" alt="Sébastien Linck" loading="lazy" width="150" height="150" />

    <h3>Sébastien Linck</h3>
    <p>
      EiSINe<br>
      Campus Sup Ardenne<br>
      9A rue Claude Chrétien<br>
      08000 Charleville-Mézières<br>
    </p>
    <p>contact(@)slinck(.)com</p>
    <div class="social-links">
      <a target="_blank" href="https://www.linkedin.com/in/slinck/" aria-label="LinkedIn">
        <img src="img/linkedin.svg" alt="LinkedIn" width="40" style="filter: brightness(0) invert(1)" />
      </a>
      <a target="_blank" href="https://www.researchgate.net/profile/Sebastien-Linck" aria-label="ResearchGate">
        <img src="img/researchgate.svg" alt="ResearchGate" width="40" style="filter: brightness(0) invert(1)" />
      </a>
    </div>
  </article>

  <article class="card span-2 card-c3">
    <h3>Envoyer un message</h3>

    <?php if (!empty($message_statut)): ?>
      <div style="padding: 1rem; border-radius: var(--radius); margin-bottom: 1rem; text-align: center; font-weight:bold; background: <?= $type_statut == 'success' ? 'var(--pine)' : 'var(--berry)' ?>; color: var(--crystal);">
        <?= $message_statut ?>
      </div>
    <?php endif; ?>

    <form id="contact-form" method="post" action="">

      <div style="display:none; opacity:0; position:absolute; left:-9999px;">
        <label for="website_check">Ne remplissez pas ce champ si vous êtes humain :</label>
        <input type="text" id="website_check" name="website_check" autocomplete="off" tabindex="-1" value="">
      </div>

      <label for="nom">Votre nom</label>
      <input
        type="text"
        id="nom"
        name="nom"
        placeholder="Nom Prénom"
        required
        value="<?= htmlspecialchars($nom_saisi) ?>" />

      <label for="mail">Votre courriel</label>
      <input
        type="email"
        id="mail"
        name="mail"
        placeholder="email@exemple.com"
        required
        value="<?= htmlspecialchars($email_saisi) ?>" />

      <label for="message">Message</label>
      <textarea
        id="message"
        rows="6"
        name="message"
        placeholder="Votre message..."
        required><?= htmlspecialchars($message_saisi) ?></textarea>

      <input
        type="submit"
        name="envoyer"
        value="Envoyer le message"
        id="envoyer-contact" />
    </form>
  </article>
</div>