<?php
// --- IMPORTATION MANUELLE DE PHPMAILER ---
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// --- LOGIQUE METIER ---

$message_statut = '';
$type_statut = ''; 

$nom_saisi = '';
$email_saisi = '';
$message_saisi = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['envoyer'])) {

    // 1. HONEYPOT
    if (!empty($_POST['website_check'])) {
        die();
    }

    // 2. NETTOYAGE
    $nom_saisi = strip_tags(trim($_POST['nom'] ?? ''));
    $email_saisi = filter_var(trim($_POST['mail'] ?? ''), FILTER_SANITIZE_EMAIL);
    $message_saisi = strip_tags(trim($_POST['message'] ?? ''));

    // 3. VALIDATION
    if (empty($nom_saisi) || empty($message_saisi) || !filter_var($email_saisi, FILTER_VALIDATE_EMAIL)) {
        $message_statut = "Merci de vérifier votre email et de remplir tous les champs.";
        $type_statut = "error";
    } else {
        
        // 4. CONFIGURATION SMTP (Vos paramètres)
        $mail = new PHPMailer(true);

        try {
            // Paramètres Serveur
            $mail->isSMTP();
            $mail->Host       = 'slinck.com';           // Votre serveur sortant
            $mail->SMTPAuth   = true;
            $mail->Username   = 'contact@slinck.com';   // Votre utilisateur
            $mail->Password   = 'VOTRE_MOT_DE_PASSE_ICI'; // <-- ATTENTION : Mettez le vrai mot de passe ici
            
            // Sécurité & Port (465 = SMTPS)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; 
            $mail->Port       = 465; 
            $mail->CharSet    = 'UTF-8';

            // Expéditeur et Destinataire
            // Note : Le "From" DOIT être l'adresse authentifiée (contact@slinck.com)
            $mail->setFrom('contact@slinck.com', 'Site Slinck'); 
            
            // Où voulez-vous recevoir le mail ? (Probablement sur la même adresse)
            $mail->addAddress('contact@slinck.com');             
            
            // Permet de répondre directement au visiteur en cliquant sur "Répondre"
            $mail->addReplyTo($email_saisi, $nom_saisi);         

            // Contenu du mail
            $mail->isHTML(true);
            $mail->Subject = 'Nouveau message de ' . $nom_saisi . ' (via le site)';
            
            $mail->Body    = "
                <html>
                <head><title>Message de $nom_saisi</title></head>
                <body>
                  <div style='font-family: Arial, sans-serif; padding: 20px; border: 1px solid #ddd;'>
                    <h2 style='color: #333;'>Nouveau message reçu</h2>
                    <p><strong>De :</strong> $nom_saisi</p>
                    <p><strong>Email :</strong> <a href='mailto:$email_saisi'>$email_saisi</a></p>
                    <hr>
                    <p style='white-space: pre-line;'>" . nl2br(htmlspecialchars($message_saisi)) . "</p>
                  </div>
                </body>
                </html>
            ";
            
            // Version texte brut pour les clients mail très anciens
            $mail->AltBody = "De: $nom_saisi ($email_saisi)\n\nMessage:\n$message_saisi";

            $mail->send();
            
            $message_statut = "Votre message a bien été envoyé.";
            $type_statut = "success";
            
            // Vider les champs après succès
            $nom_saisi = $email_saisi = $message_saisi = '';

        } catch (Exception $e) {
            // En production, évitez d'afficher $mail->ErrorInfo complet aux visiteurs car cela peut révéler des infos serveur.
            // Préférez un log serveur ou un message générique.
            $message_statut = "Une erreur est survenue lors de l'envoi du message.";
            // Pour le débogage (à retirer en prod) : 
            // $message_statut .= " " . $mail->ErrorInfo;
            $type_statut = "error";
        }
    }
}
?>