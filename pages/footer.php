<?php
$cacheFile = sys_get_temp_dir() . '/site_ts.cache';

// On vérifie le cache (valide si le mois est le même)
$ts = (is_file($cacheFile) && date('Ym', filemtime($cacheFile)) === date('Ym'))
	? (int)file_get_contents($cacheFile)
	: 0;

// Si pas de cache ou cache expiré, on scanne les fichiers
if ($ts <= 0) {
	// Note : '/..' remonte d'un cran car on est dans le dossier /pages/
	$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__ . '/..', FilesystemIterator::SKIP_DOTS));
	foreach ($it as $file) {
		// On cherche le fichier le plus récent
		$ts = max($ts, $file->getMTime());
	}
	file_put_contents($cacheFile, $ts);
}

// Formatage en Français (ex: Février 2026)
$fmt = new IntlDateFormatter('fr_FR', IntlDateFormatter::LONG, IntlDateFormatter::NONE, 'Europe/Paris', IntlDateFormatter::GREGORIAN, 'MMMM yyyy');
$displayDate = mb_convert_case($fmt->format($ts), MB_CASE_TITLE, "UTF-8");
?>

<footer>
	<span>&copy; Sébastien Linck</span>
	<div>Mise à jour : <strong><?= htmlspecialchars($displayDate) ?></strong></div>
	<a href="mentions-legales" rel="nofollow">Mentions légales</a>
</footer>