<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
setlocale(LC_TIME, 'fr_FR.utf8', 'fr_FR', 'fra');
session_set_cookie_params(["SameSite" => "Strict"]);
session_set_cookie_params(["Secure" => "true"]);
session_start();
$auth_pages = array(
	'accueil' => array(
		'url' => './pages/accueil.html',
		'nom' => 'Accueil'
	),
	'enseignements' => array(
		'url' => './pages/enseignements.html',
		'nom' => 'Enseignements'
	),
	'publications' => array(
		'url' => './pages/publications.html',
		'nom' => 'Publications'
	),
	'contact' => array(
		'url' => './pages/contact.php',
		'nom' => 'Contact'
	),
	'these' => array(
		'url' => './pages/these.html',
		'nom' => 'Thèse'
	),
	'algopath' => array(
		'url' => './pages/algopath.html',
		'nom' => 'Algopath'
	),
	'mentions-legales' => array(
		'url' => './pages/mentions-legales.html',
		'nom' => 'Mentions légales'
	)
);
$page = 'accueil';
if (!empty($_GET['page']) && array_key_exists($_GET['page'], $auth_pages)) {
	$page = $_GET['page'];
}
?>
<!doctype html>
<html lang="fr">

<head>
	<title><?= $auth_pages[$page]['nom'] ?> - Site de Sébastien Linck - Enseignant</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="<?= $auth_pages[$page]['nom'] ?> - Site web de Sébastien Linck - École d’ingénieurs en Sciences Industrielles et Numérique - EiSINe - Formations - Enseignements - Travaux de recherche">
	<meta name="author" content="Sebastien Linck">
	<link rel="canonical" href="<?php echo 'https://' . $_SERVER['HTTP_HOST'];
								if ($page != "accueil") echo '/' . $page; ?>">
	<meta name="theme-color" content="#EFEFEF">
	<meta property="og:title" content="Site web de Sébastien Linck - Enseignant - École d’ingénieurs en Sciences Industrielles et Numérique">
	<meta property="og:type" content="article">
	<meta property="og:url" content="https://www.slinck.com">
	<meta property="og:image" content="img/linck.webp">
	<link rel="icon" type="image/png" sizes="256x256" href="./img/favicon.png">
	<link rel="apple-touch-icon" sizes="120x120" href="./img/favicon144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="./img/favicon.png">
	<link rel="stylesheet" href="./css/style.min.css">
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
	<link rel="manifest" href="/slinck.webmanifest">
	<meta name="google-site-verification" content="W4B7FHprbWn7QDiEttuBXnN7X6bL2P1SWMmNO2c8Tlw">
</head>

<body>
	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-M85B535" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->
	<div id="page">
		<header>
			<h1>Sébastien Linck <?php if ($page != "accueil") echo " - " . $auth_pages[$page]['nom']; ?></h1>
			<h2>Enseignant en informatique - Responsable de formation</h2>
		</header>
		<nav>
			<div class="hamburger expand">
				<img class="icons" src="./img/menu-burger.svg" alt="icone menu">
			</div>
			<ul>
				<li><a href="<?= 'https://' . $_SERVER['HTTP_HOST'] ?>"><img class="icons" src="./img/home.svg" alt="icone accueil">Accueil</a></li>
				<li><a href="enseignements"><img class="icons" src="./img/e-learning.svg" alt="icone enseignement">Enseignements</a></li>
				<li>
					<a class="sousmenu expand" href="#">
						<img class="icons" src="./img/chart-network.svg" alt="icone recherche">Recherche<img class="icons" src="./img/angle-small-down.svg" alt="icone sous-menu">
					</a>
					<ul>
						<li><a href="these"><img class="icons" src="./img/graduation-cap.svg" alt="icone recherche">Ma Thèse</a></li>
						<li><a href="algopath"><img class="icons" src="./img/puzzle-piece.svg" alt="icone recherche">AlgoPath</a></li>
					</ul>
				</li>
				<li><a href="publications"><img class="icons" src="./img/edit.svg" alt="icone publications">Publications</a></li>
				<li><a href="contact"><img class="icons" src="./img/envelope.svg" alt="icone contact">Contact</a></li>
			</ul>
		</nav>
		<main>
			<?php include($auth_pages[$page]['url']); ?>
		</main>
		<?php include("./pages/footer.php"); ?>
		<section id="cookie">
			<h3></h3>
			<article>
				<p>Ce site utilise des cookies pour vous garantir la meilleure expérience sur notre site.</p>
				<button>J'ai compris</button>
			</article>
		</section>
	</div>
	<div class="enhaut">
		<a href="#page"><img class="icons" src="./img/angle-square-up.svg" alt="icone retour"></a>
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