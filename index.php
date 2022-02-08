<!DOCTYPE html>
<?php
	session_start();
	$auth_pages['accueil'] = './pages/accueil.html';
	$auth_pages['enseignements'] = './pages/enseignements.html';
	$auth_pages['publications'] = './pages/publications.html';
	$auth_pages['contact'] = './pages/contact.php';
	$auth_pages['these'] = './pages/these.html';
	$auth_pages['algopath'] = './pages/algopath.html';
	$auth_pages['mentions-legales'] = './pages/mentions-legales.html';
	
	$page = 'accueil';
	if(!empty($_GET['page']) && array_key_exists($_GET['page'], $auth_pages))
	{
		$page = $_GET['page'];
	}
?>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="keywords" content="reseaux, NS2, algopath, sebastien linck, these">
		<meta name="description" content="Site web de Sébastien Linck - Enseignant contractuel URCA - Formations - Enseignements - Doctorat - Travaux de recherche">
		<meta name="author" content="Sebastien Linck">
		<meta name="theme-color" content="#EFEFEF">
		<title><?php echo ucfirst($page); ?> - Site de Sébastien Linck - Enseignant URCA</title>	
		<meta property="og:title" content="Site web de Sébastien Linck - Enseignant contractuel URCA">
		<meta property="og:type" content="article">
		<meta property="og:url" content="https://www.slinck.com">
		<meta property="og:image" content="img/linck.jpg">
		<link rel="icon" type="image/png" sizes="256x256" href="./img/favicon.png">
		<link rel="apple-touch-icon" sizes="120x120" href="./img/favicon120.png">
		<link rel="apple-touch-icon" sizes="152x152" href="./img/favicon152.png">
		<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
		<link rel="stylesheet" href="./css/style.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> 
		<script src="https://www.google.com/recaptcha/api.js" async defer></script>
		<script async src="./js/outils.js"></script>
		<link rel="manifest" href="/manifest.json">
		<script async src="./sw.js"></script>	
		<meta name="google-site-verification" content="W4B7FHprbWn7QDiEttuBXnN7X6bL2P1SWMmNO2c8Tlw" />
		<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
		new Date().getTime(),event:'gtm.js'});
		var f=d.getElementsByTagName(s)[0],
		j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
		})(window,document,'script','dataLayer','GTM-M85B535');</script>
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-72813591-1"></script>
		<script>
			window.dataLayer = window.dataLayer || [];
			function gtag(){dataLayer.push(arguments);}
			gtag('js', new Date());
			gtag('config', 'UA-72813591-1');
		</script>
	</head>
	<body>
		<noscript>
			<iframe src="https://www.googletagmanager.com/ns.html?id=GTM-M85B535" height="0" width="0" style="display:none;visibility:hidden"></iframe>
		</noscript>
		<div id="page">
			<header>
				<h1>Sébastien Linck</h1>
				<h2>Professeur contractuel</h2>   
			</header>
			<nav>
				<div class="ssmenu hamburger">
					<div class='barre'></div>
					<div class='barre'></div>
					<div class='barre'></div>
				</div>
				<ul>
					<li><a href="accueil"><img class="icon" src="./img/home.svg"><span class="icon-text">Accueil</span></a></li>
					<li><a href="enseignements"><img class="icon" src="./img/school.svg"><span class="icon-text">Enseignements</span></a></li>
					<li>
						<div class="ssmenu expand">
							<span class="titremenu"><img class="icon" src="./img/computer.svg"><span class="icon-text">Recherche</span><img class="icon" src="./img/small-arrow-bottom.svg"></span>
						</div>
						<ul>       
							<li><a href="these">Ma Thèse</a></li>
							<li><a href="algopath">AlgoPath</a></li>
						</ul>
					</li>
					<li><a href="publications"><img class="icon" src="./img/book.svg"><span class="icon-text">Publications</span></a></li>
					<li><a href="contact"><img class="icon" src="./img/mail.svg"><span class="icon-text">Contact</span></a></li>
				</ul>				
			</nav>			
			<main id="container">
				<?php include($auth_pages[$page]); ?>
			</main>
			<?php include("./pages/footer.php"); ?>
		</div>
		<img src="./img/top_arrow.png" alt="fleche haut" class="enhaut">
	</body>
	
	<?php 
		session_unset(); 
	?>	
</html>
