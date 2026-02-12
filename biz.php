<?php
// 1. L'ADRESSE QUI EXISTE DANS PLESK (Celle que vous avez validée)
$email_expediteur = 'contact@slinck.com'; // ou contact@slinck.com
$destinataire     = 'poubelle@slinck.com'; // Pour le test

$sujet = "Test Plesk avec paramètre -f";
$message = "Si ce message arrive, le problème était l'enveloppe de l'expéditeur (Return-Path).";

// 2. HEADERS (Ce que voit le client)
$headers = [
    'From' => $email_expediteur,
    'Reply-To' => $email_expediteur,
    'X-Mailer' => 'PHP/' . phpversion(),
    'MIME-Version' => '1.0',
    'Content-Type' => 'text/plain; charset=utf-8'
];

// Formatage des headers pour Linux (Plesk préfère souvent \n à \r\n)
$headers_str = "";
foreach ($headers as $k => $v) {
    $headers_str .= "$k: $v\n";
}

// 3. LA CLÉ DU SUCCÈS : Le 5ème paramètre
// On dit explicitement au serveur : "Je suis contact@..., pas 'apache'"
// Attention : Pas d'espace entre -f et l'email
$parametres_postfix = "-f" . $email_expediteur;
echo "Sendmail Path: " . ini_get('sendmail_path') . "<br>";
// 4. ENVOI ET DIAGNOSTIC
echo "<h3>Tentative d'envoi...</h3>";
echo "De : $email_expediteur <br>";
echo "Avec paramètre : '$parametres_postfix' <br><br>";

if (mail($destinataire, $sujet, $message, $headers_str, $parametres_postfix)) {
    echo "<strong style='color:green'>SUCCÈS : Le serveur a accepté l'email !</strong>";
} else {
    echo "<strong style='color:red'>ÉCHEC : Le serveur refuse toujours.</strong><br>";
    $err = error_get_last();
    echo "Dernière erreur PHP : " . ($err['message'] ?? 'Aucune erreur PHP retournée');
}
