<?php
// On utilise __DIR__ pour pointer sur le dossier actuel (pages)
$path = __DIR__ . '/PHPMailer/';

if (file_exists($path . 'Exception.php')) {
    require $path . 'Exception.php';
    require $path . 'PHPMailer.php';
    require $path . 'SMTP.php';
} else {
    die("Erreur : Les fichiers PHPMailer sont introuvables dans : " . $path);
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// ============================================================
// 2. LOGIQUE DE TRAITEMENT
// ============================================================

$message_statut = '';
$type_statut = ''; // 'success' ou 'error'

// Variables pour pré-remplir le formulaire en cas d'erreur
$nom_saisi = '';
$email_saisi = '';
$message_saisi = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['envoyer'])) {

    // A. HONEYPOT (Anti-Spam basique)
    if (!empty($_POST['website_check'])) {
        die(); // Arrêt silencieux si un robot remplit ce champ caché
    }

    // B. RÉCUPÉRATION ET NETTOYAGE
    $nom_saisi = strip_tags(trim($_POST['nom'] ?? ''));
    $email_saisi = filter_var(trim($_POST['mail'] ?? ''), FILTER_SANITIZE_EMAIL);
    $message_saisi = strip_tags(trim($_POST['message'] ?? ''));

    // C. VALIDATION
    if (empty($nom_saisi) || empty($message_saisi) || !filter_var($email_saisi, FILTER_VALIDATE_EMAIL)) {
        $message_statut = "Veuillez remplir tous les champs et vérifier votre email.";
        $type_statut = "error";
    } else {
        
        // D. ENVOI VIA SMTP
        $mail = new PHPMailer(true);

        try {
            // --- Configuration Serveur (Vos paramètres slinck.com) ---
            $mail->isSMTP();
            $mail->Host       = 'slinck.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'contact@slinck.com';
            $mail->Password   = '@sh417aH8'; // <--- À REMPLACER !!!
            
            // Port 465 impose le chiffrement SMTPS
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;
            $mail->CharSet    = 'UTF-8';

            // --- Expéditeur & Destinataire ---
            // Le From DOIT être l'adresse authentifiée pour éviter le spam
            $mail->setFrom('contact@slinck.com', 'Site Web Slinck');
            $mail->addAddress('contact@slinck.com'); // Réception
            $mail->addReplyTo($email_saisi, $nom_saisi); // Pour répondre au visiteur

            // --- Contenu du mail ---
            $mail->isHTML(true);
            $mail->Subject = 'Nouveau message de ' . $nom_saisi . ' (via slinck.com)';
            
            // Template HTML du mail
            $mail->Body = "
                <html>
                <body style='background-color:#f4f4f4; padding:20px; font-family: sans-serif;'>
                    <div style='max-width:600px; margin:0 auto; background:#fff; padding:20px; border-radius:8px; box-shadow:0 2px 5px rgba(0,0,0,0.1);'>
                        <h2 style='color:#2c3e50; border-bottom:2px solid #eee; padding-bottom:10px;'>Nouveau contact</h2>
                        <p><strong>Nom :</strong> $nom_saisi</p>
                        <p><strong>Email :</strong> <a href='mailto:$email_saisi'>$email_saisi</a></p>
                        <div style='background:#f9f9f9; padding:15px; border-left:4px solid #007bff; margin-top:20px;'>
                            " . nl2br(htmlspecialchars($message_saisi)) . "
                        </div>
                    </div>
                </body>
                </html>
            ";
            
            $mail->AltBody = "De: $nom_saisi ($email_saisi)\n\nMessage:\n$message_saisi";

            $mail->send();
            
            // Succès
            $message_statut = "Votre message a bien été envoyé via SMTP.";
            $type_statut = "success";
            
            // On vide les variables pour effacer le formulaire
            $nom_saisi = $email_saisi = $message_saisi = '';

        } catch (Exception $e) {
            $message_statut = "Erreur technique lors de l'envoi. Contactez l'administrateur.";
             // Pour débugger (retirer en production) : 
            $message_statut .= $mail->ErrorInfo;
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
      <div style="padding: 1rem; border-radius: var(--radius); margin-bottom: 1rem; text-align: center; font-weight:bold; 
           background: <?= $type_statut == 'success' ? 'var(--pine)' : 'var(--berry)' ?>; 
           color: var(--crystal);">
        <?= $message_statut ?>
      </div>
    <?php endif; ?>

    <form id="contact-form" method="post" action="">

      <div style="display:none; opacity:0; position:absolute; left:-9999px;">
        <label for="website_check">Humains, ignorez ce champ :</label>
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