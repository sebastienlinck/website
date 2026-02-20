# Site personnel — Sébastien Linck

Site web officiel de Sébastien Linck (enseignant en informatique, responsable de formation). Ce dépôt contient le site statique/mi-dynamique utilisé pour présenter les enseignements, publications et contacts.

## Fonctionnalités principales

- Navigation simple et responsive
- Pages rendues via PHP (vues dans `views/`)
- Gestion d'envoi d'emails via `core/Mailer.php` et `vendor/PHPMailer`
- Manifest PWA et service worker pour mise en cache basique


## Structure du dépôt

- `index.php` : routeur principal
- `views/` : pages HTML/PHP (accueil, contact, publications, etc.)
- `core/` : logique serveur (ex : `Mailer.php`)
- `css/` et `js/` : assets front-end
- `img/` : images du site
- `vendor/PHPMailer/` : bibliothèque PHPMailer
- `sw.js`, `slinck.webmanifest` : fichiers PWA

Bonnes pratiques & déploiement

- Minifier `css/style.css` et `js/scripts.js` pour la production (déjà présents en `*.min.*`).
- Configurer HTTPS en production (certificat valide) pour PWA et sécurité des formulaires.
- Chaque développeur copie ` .env.example` en ` .env` et y met ses propres valeurs locales :

Contribuer

- Ce site est principalement personnel. Pour suggestions ou correctifs : ouvrir une issue ou proposer une PR avec une description claire des changements.

Licence

- Projet personnel — contacter l'auteur pour toute réutilisation ou collaboration.

Contact

- Voir la page `views/contact.html` pour les coordonnées publiques.


**Ne pas synchroniser le fichier `.env`**

- **But :** ne pas committer de secrets (mots de passe, clés API) dans le dépôt Git.
- **Ajout automatique :** le dépôt inclut déjà un fichier `.gitignore` qui contient ` .env`.
- **Si `.env` est déjà suivi par Git :** exécutez :

```bash
git rm --cached .env
git commit -m "Stop tracking .env"
git push
```

- **Bonnes pratiques :**
	- Gardez un fichier ` .env.example` (fourni) contenant les clés nécessaires mais pas les valeurs sensibles.
	- Chaque développeur copie ` .env.example` en ` .env` et y met ses propres valeurs locales :

```bash
cp .env.example .env
# puis éditez .env
```

- **En production :** définissez les variables d'environnement via l'hébergeur (panel, Docker, systemd, Azure/Heroku, etc.) ou un gestionnaire de secrets (Vault, AWS Secrets Manager...). Ne stockez pas les secrets dans le dépôt.

