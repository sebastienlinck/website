<?php
error_reporting(E_ALL);
ini_set('display_errors', FALSE);
setlocale(LC_TIME, 'fr_FR.utf8', 'fr_FR', 'fra');
// Configure les paramètres du cookie de session en une seule fois
session_set_cookie_params([
	'samesite' => 'Strict',
	'secure' => true,
	'httponly' => true,
]);
session_start();
$cookie_name = "__Secure-cookieDef";
$cookie_accepted = isset($_COOKIE[$cookie_name]);

// Si l'utilisateur a cliqué sur "J'ai compris" et que le cookie n'existe pas encore
if (isset($_GET['accept_cookies']) && $_GET['accept_cookies'] == 'true' && !$cookie_accepted) {

	$expiration_time = time() + (60 * 60 * 24 * 30); // 30 jours

	// Création du cookie côté serveur avec HttpOnly et le préfixe __Secure-
	setcookie(
		$cookie_name,
		'accepted',
		[
			'expires' => $expiration_time,
			'path' => '/',
			'secure' => true,      // OBLIGATOIRE avec le préfixe
			'httponly' => true,    // Ajout de HttpOnly (protection XSS)
			'samesite' => 'Strict'
		]
	);

	// Redirection pour nettoyer l'URL (?accept_cookies=true) et éviter les double soumissions
	header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
	exit();
}
$host = $_SERVER['HTTP_HOST'] ?? '';
// sanitize host to prevent host header injection
$host = preg_replace('/[^a-z0-9\.\-:]/i', '', $host);
if (empty($host)) {
	$host = 'slinck.com';
}
$auth_pages = array(
	'accueil' => array(
		'url' => './pages/accueil.html',
		'nom' => 'Site personnel'
	),
	'enseignements' => array(
		'url' => './pages/enseignements.html',
		'nom' => 'Enseignements'
	),
	'recherche' => array(
		'url' => './pages/recherche.html',
		'nom' => 'Recherche'
	),
	'publications' => array(
		'url' => './pages/publications.html',
		'nom' => 'Publications'
	),
	'contact' => array(
		'url' => './pages/contact.php',
		'nom' => 'Contact'
	),
	'mentions-legales' => array(
		'url' => './pages/mentions-legales.html',
		'nom' => 'Mentions légales'
	),
	'erreur' => array(
		'url' => './pages/erreur.html',
		'nom' => 'Erreur'
	)
);
if (!isset($_GET['page'])) {
	// Pas de paramètre → page d'accueil
	$page = 'accueil';
	$is404 = false;
} elseif (array_key_exists($_GET['page'], $auth_pages)) {
	// Paramètre valide → page correspondante
	$page = $_GET['page'];
	$is404 = false;
} else {
	// Paramètre invalide → page d'erreur
	$page = 'erreur';
	$is404 = true;
}
if ($is404) {
	http_response_code(404);
}
$canonicalPage = $page !== 'accueil' ? '/' . htmlspecialchars($page, ENT_QUOTES, 'UTF-8') : '';

?>
<!doctype html>
<html lang="fr">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= $auth_pages[$page]['nom'] ?> - Site de Sébastien Linck - Enseignant</title>
	<meta name="keywords" content="Sébastien Linck, Enseignant Informatique URCA, Responsable Licence Pro Web, Métiers du Numérique, EiSINe, Développement Front End, SEO, HTML, CSS, JS, PHP, Projets tutorés, Doctorat Réseaux" />
	<meta name="description" content="<?= $auth_pages[$page]['nom'] ?> - Sébastien Linck - École d’ingénieurs en Sciences Industrielles et Numérique - EiSINe - Formations - Enseignements - Travaux de recherche">
	<meta name="author" content="Sebastien Linck">
	<meta name="publisher" content="Sébastien Linck" />
	<script type="application/ld+json">
		{
			"@context": "https://schema.org",
			"@type": "Person",
			"name": "Sébastien Linck",
			"jobTitle": "Enseignant en Informatique et Responsable de formation",
			"worksFor": {
				"@type": "Organization",
				"name": "Université de Reims Champagne-Ardenne (URCA) - EiSINe"
			},
			"url": "https://www.slinck.com",
			"alumniOf": [{
					"@type": "EducationalOrganization",
					"name": "Université de Franche-Comté"
				},
				{
					"@type": "EducationalOrganization",
					"name": "UTBM"
				}
			],
			"knowsAbout": [
				"Développement Web Full Stack",
				"HTML5",
				"CSS3",
				"JavaScript",
				"PHP",
				"SEO",
				"Communication Digitale"
			]
		}
	</script>
	<link rel="canonical" href="<?= 'https://' . $host . $canonicalPage; ?>">
	<meta name="theme-color" content="#EFEFEF">
	<meta property="og:title" content="Site web de Sébastien Linck - Enseignant - École d’ingénieurs en Sciences Industrielles et Numérique">
	<meta property="og:description" content="École d’ingénieurs en Sciences Industrielles et Numérique - EiSINe - Formations - Enseignements - Travaux de recherche">
	<meta property="og:type" content="article">
	<meta property="og:url" content="https://www.slinck.com">
	<meta property="og:image" content="./img/linck.webp">
	<link rel="icon" type="image/svg" sizes="256x256" href="./img/favicon.svg">
	<link rel="apple-touch-icon" href="./img/favicon.svg">

	<!-- Google tag (gtag.js) -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-CX6WD4QDPN"></script>
	<script>
		window.dataLayer = window.dataLayer || [];

		function gtag() {
			dataLayer.push(arguments);
		}
		gtag('js', new Date());

		gtag('config', 'G-CX6WD4QDPN');
	</script>

	<link rel="stylesheet" href="./css/style.min.css">
	<link rel="manifest" href="./slinck.webmanifest">
	<meta name="google-site-verification" content="W4B7FHprbWn7QDiEttuBXnN7X6bL2P1SWMmNO2c8Tlw">
	<script>
		window.cookieAccepted = <?= $cookie_accepted ? 'true' : 'false' ?>;
	</script>
</head>

<body>
	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-M85B535"
			height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->
	<div id="page">
		<header>
			<h1>Sébastien Linck <?php echo "- " . $auth_pages[$page]['nom']; ?></h1>
			<h2>
				<span>Enseignant en informatique</span>
				&nbsp;–&nbsp;
				<span>Responsable de formation</span>
			</h2>
		</header>
		<nav>
			<div class="hamburger">
				<img class="icons" loading="lazy" width="64" height="64" src="./img/menu-burger.svg" alt="icone menu" title="menu principal">
			</div>
			<ul>
				<li><a href="<?= 'https://' . $_SERVER['HTTP_HOST'] ?>"><img class="icons" loading="lazy" width="64" height="64" src="./img/home.svg" alt="icone accueil" title="icone accueil">Accueil</a></li>
				<li><a href="enseignements"><img class="icons" loading="lazy" width="64" height="64" src="./img/e-learning.svg" alt="icone enseignement" title="icone enseignement">Enseignements</a></li>
				<li><a href="recherche"><img class="icons" loading="lazy" width="64" height="64" src="./img/chart-network.svg" alt="icone recherche" title="icone recherche">Recherche</a></li>
				<li><a href="publications"><img class="icons" loading="lazy" width="64" height="64" src="./img/edit.svg" alt="icone publications" title="icone publications">Publications</a></li>
				<li><a href="contact"><img class="icons" loading="lazy" width="64" height="64" src="./img/envelope.svg" alt="icone contact" title="icone contact">Contact</a></li>
			</ul>
		</nav>
		<main>
			<?php include($auth_pages[$page]['url']); ?>
		</main>
		<?php include("./pages/footer.php"); ?>
		<?php if (!$cookie_accepted): ?>
			<section id="cookie" class="show">
				<h3>Utilisation des cookies</h3>
				<article>
					<h4>Information</h4>
					<p>Ce site utilise des cookies pour vous garantir la meilleure expérience sur notre site.</p>
					<!-- L'élément <a> stylisé en bouton déclenche la logique PHP via le lien -->
					<button id="cookie-button" type="button" aria-label="Accepter les cookies" data-accept-url="?accept_cookies=true">J'ai compris</button>
				</article>
			</section>
		<?php endif; ?>
	</div>
	<div class="enhaut">
		<a href="#page"><img class="icons" loading="lazy" width="64" height="64" src="./img/angle-square-up.svg" alt="icone retour" title="retour en haut"></a>
	</div>
	<script src="./js/outils.min.js"></script>
	<script>
		if (typeof navigator.serviceWorker !== 'undefined') {
			navigator.serviceWorker.register('sw.js')
		}
	</script>
</body>
<?php
session_unset();
?>

</html>