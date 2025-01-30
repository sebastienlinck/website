<?php
error_reporting(E_ALL);
ini_set('display_errors', FALSE);
setlocale(LC_TIME, 'fr_FR.utf8', 'fr_FR', 'fra');
session_set_cookie_params(["SameSite" => "Strict"]);
session_set_cookie_params(["Secure" => "true"]);
session_start();
$auth_pages = array(
	'accueil' => array(
		'url' => './pages/accueil.html',
		'nom' => 'Présentation'
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
	)
);
$page = isset($_GET['page']) && in_array($_GET['page'], array_keys($auth_pages)) ? $_GET['page'] : 'accueil';
?>
<!doctype html>
<html lang="fr">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= $auth_pages[$page]['nom'] ?> - Site de Sébastien Linck - Enseignant</title>
	<meta name="description" content="<?= $auth_pages[$page]['nom'] ?> - Site web de Sébastien Linck - École d’ingénieurs en Sciences Industrielles et Numérique - EiSINe - Formations - Enseignements - Travaux de recherche">
	<meta name="author" content="Sebastien Linck">
	<link rel="canonical" href="<?php echo 'https://' . $_SERVER['HTTP_HOST'];
								if ($page != "accueil") echo '/' . $page; ?>">
	<meta name="theme-color" content="#EFEFEF">
	<meta property="og:title" content="Site web de Sébastien Linck - Enseignant - École d’ingénieurs en Sciences Industrielles et Numérique">
	<meta property="og:description" content="École d’ingénieurs en Sciences Industrielles et Numérique - EiSINe - Formations - Enseignements - Travaux de recherche">
	<meta property="og:type" content="article">
	<meta property="og:url" content="https://www.slinck.com">
	<meta property="og:image" content="./img/linck.webp">
	<link rel="icon" type="image/svg" sizes="256x256" href="./img/favicon.svg">
	<link rel="apple-touch-icon" href="./img/favicon.svg">
	<!-- Google Tag Manager -->
	<script>
		(function(w, d, s, l, i) {
			w[l] = w[l] || [];
			w[l].push({
				'gtm.start': new Date().getTime(),
				event: 'gtm.js'
			});
			var f = d.getElementsByTagName(s)[0],
				j = d.createElement(s),
				dl = l != 'dataLayer' ? '&l=' + l : '';
			j.async = true;
			j.src =
				'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
			f.parentNode.insertBefore(j, f);
		})(window, document, 'script', 'dataLayer', 'GTM-M85B535');
	</script>
	<!-- End Google Tag Manager -->
	<link rel="stylesheet" href="./css/style.min.css">
	<link rel="manifest" href="./slinck.webmanifest">
	<meta name="google-site-verification" content="W4B7FHprbWn7QDiEttuBXnN7X6bL2P1SWMmNO2c8Tlw">
</head>

<body>
	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-M85B535"
			height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->
	<div id="page">
		<header>
			<h1>Sébastien Linck - <?= $auth_pages[$page]['nom']; ?></h1>
			<h2>
				<span>Enseignant en informatique -&nbsp;</span>
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
		<section id="cookie">
			<h3></h3>
			<article>
				<h4>Utilisation des cookies</h4>
				<p>Ce site utilise des cookies pour vous garantir la meilleure expérience sur notre site.</p>
				<button>J'ai compris</button>
			</article>
		</section>
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